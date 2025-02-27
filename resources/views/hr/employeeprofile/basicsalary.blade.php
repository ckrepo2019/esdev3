
{{-- @if(session()->has('linkid'))
@if( session()->get('linkid') == 'custom-content-above-profile') --}}
    {{-- <div class="tab-pane fade show active" id="custom-content-above-basicsalary" role="tabpanel" aria-labelledby="custom-content-above-profile-tab"> --}}
{{-- @else
    <div class="tab-pane fade" id="custom-content-above-profile" role="tabpanel" aria-labelledby="custom-content-above-profile-tab">
@endif
@else
<div class="tab-pane fade" id="custom-content-above-profile" role="tabpanel" aria-labelledby="custom-content-above-profile-tab">
@endif --}}
<style>
        .modal-dialog-full-width {
        width: 100% !important;
        height: 50% !important;
        margin: 10px !important;
        padding: !important;
        max-width:none !important;

    }

    .modal-content-full-width  {
        height: auto !important;
        min-height: 100% !important;
        border-radius: 0 !important;
        background-color: #ececec !important 
    }

    .modal-header-full-width  {
        border-bottom: 1px solid #9ea2a2 !important;
    }

    .modal-footer-full-width  {
        border-top: 1px solid #9ea2a2 !important;
    }
</style>
    <div class="card">
        <div class="card-body">
            <form id="thisform">
            <div class="row">
                <div class="col-4">
                    <label>Salary basis type</label>
                    @if(count(collect($employeebasicsalaryinfo))==0)
                    
                        <select class="form-control" name="salarybasistype" id="selectbasis" required>
                            <option value="">Select</option>
                            @if(count($salarybasistypes)>0)
                                @foreach($salarybasistypes as $salarybasistype)
                                    <option value="{{$salarybasistype->id}}" type="{{$salarybasistype->type}}">{{$salarybasistype->type}}</option>
                                @endforeach
                            @endif
                        </select>
                    @else
                        <select class="form-control" name="salarybasistype" id="selectbasis" required>
                            @if(count($salarybasistypes)>0)
                                @foreach($salarybasistypes as $salarybasistype)
                                    <option value="{{$salarybasistype->id}}" {{$salarybasistype->id == $employeebasicsalaryinfo->basistypeid ? "selected" : ""}} type="{{$salarybasistype->type}}">{{$salarybasistype->type}}</option>
                                @endforeach
                            @endif
                        </select>
                    @endif
                </div>
            </div>
            <div id="configcontainer">
                @if(count(collect($employeebasicsalaryinfo))>0)
                    @if($employeebasicsalaryinfo->basistypeid == 4)
                        <hr/>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label>No of hours per day</label>
                                <input type="number" step="any" class="form-control" name="hoursperday" id="hoursperday" value="{{$employeebasicsalaryinfo->hoursperday}}" />
                            </div>
                            <div class="col-md-3">
                                <label>Salary amount</label>
                                <br/>
                                <input type="hidden" step="any" class="form-control" name="salaryamount" id="salaryamount" value="{{$employeebasicsalaryinfo->amount}}" disabled/>
                                <h5 class="text-right">{{$employeebasicsalaryinfo->amount}}</h5>
                            </div>
                            <div class="col-md-2" >
                                <label>&nbsp;</label><br/>
                                @if($employeebasicsalaryinfo->rateelevationstatus == 0)
                                <button type="button" class="btn btn-info mb-2" id="changesalaryamount" oldsalaryrate="{{$employeebasicsalaryinfo->amount}}">Change</button>
                                @else
                                <a type="button" class="btn btn-warning mb-2" id="viewchangesalaryamountrequest" oldsalaryrate="{{$employeebasicsalaryinfo->amount}}">Requested</a>
                                @endif
                            </div>
                            <div class="col-md-3 pt-2">
                                <label>&nbsp;</label>
                                <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                @if($employeebasicsalaryinfo->saturdays == 1)
                                    <input type="checkbox"  name="saturday" id="saturdaywork" checked>
                                @else
                                    <input type="checkbox"  name="saturday" id="saturdaywork">
                                @endif
                                <label for="saturdaywork">
                                    Saturday
                                </label>
                                </div>
                                <div class="icheck-primary d-inline">
                                @if($employeebasicsalaryinfo->sundays == 1)
                                    <input type="checkbox"  name="sunday"  id="sundaywork" checked>
                                @else
                                    <input type="checkbox"  name="sunday"  id="sundaywork">
                                @endif
                                <label for="sundaywork"> 
                                    Sunday
                                </label>
                                </div>
                            </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label>Payment Type</label>
                                <select class="form-control" name="paymenttype" id="paymenttype" required>
                                    <option value="cash">Cash</option>
                                    <option value="check">Check</option>
                                    <option value="banktransfer">Bank deposit</option>
                                </select>
                            </div>
                            <div class="col-md-2"  style=" display: grid;" id="submitbuttoncontainer">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-warning" id="submitbutton">Save Changes</button>
                            </div>
                        </div>
                        <script>
                            $('#submitbuttoncontainer').hide();
                            $('#hoursperday').on('keyup', function(){
                                if($(this).val().length>0)
                                {
                                    $('#submitbuttoncontainer').show();
                                }
                            })
                            $('#salaryamount').on('keyup', function(){
                                if($(this).val()>0)
                                {
                                    $('#submitbuttoncontainer').show();
                                }
                            })
                            $('#submitbutton').on('click', function(){
                                
                                var basistypeid = $('#selectbasis').val();
                                var hoursperday = $('#hoursperday').val();
                                var salaryamount = $('#salaryamount').val();
                                var paymenttype = $('#paymenttype').val();
                                
                                if(hoursperday.replace(/^\s+|\s+$/g, "").length == 0){
                                    $('#hoursperday').css("border","2px solid red")
                                }else{
                                    $('#hoursperday').removeAttr('style')
                                }
                                if(salaryamount.replace(/^\s+|\s+$/g, "").length == 0){
                                    $('#salaryamount').css("border","2px solid red")
                                }else{
                                    $('#salaryamount').removeAttr('style')
                                }
                        
                                if($('#saturdaywork').prop('checked') == true)
                                {
                                    var saturdaywork = 1;
                                }else{
                                    var saturdaywork = 0;
                                }
                                if($('#sundaywork').prop('checked') == true)
                                {
                                    var sundaywork = 1;
                                }else{
                                    var sundaywork = 0;
                                }
                        
                                
                                if(hoursperday.replace(/^\s+|\s+$/g, "").length > 0 && salaryamount.replace(/^\s+|\s+$/g, "").length > 0){
                                    $.ajax({
                                        url: '/hr/employeebasicsalaryinfo',
                                        type: 'GET',
                                        dataType: 'json',
                                        data: {
                                            basistypeid     : basistypeid,
                                            hoursperday     : hoursperday,
                                            salaryamount    : salaryamount,
                                            saturdaywork    : saturdaywork,
                                            sundaywork      : sundaywork,
                                            paymenttype     : paymenttype,
                                            employeeid      : '{{$profileinfoid}}'
                                        },
                                        success:function(data)
                                        {
                                            if(data == 1)
                                            {
                                                $('#custom-content-above-salary-tab').click()
                                                toastr.success('Updated successfully!', 'Basic Salary Info')
                                            }else{
                                                toastr.danger('Unable to make changes!', 'Basic Salary Info')
                                            }
                                        }
                                    })
                                }
                                
                            })
                        </script>
                    @elseif($employeebasicsalaryinfo->basistypeid == 5)
                        <hr/>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label>No of hours per day</label>
                                <input type="number" step="any" class="form-control" name="hoursperday" id="hoursperday" value="{{$employeebasicsalaryinfo->hoursperday}}" />
                            </div>
                            <div class="col-md-3">
                                <label>Salary amount</label>
                                <br/>
                                <input type="hidden" step="any" class="form-control" name="salaryamount" id="salaryamount" value="{{$employeebasicsalaryinfo->amount}}" disabled/>
                                <h5 class="text-right">{{$employeebasicsalaryinfo->amount}}</h5>
                            </div>
                            <div class="col-md-2" >
                                <label>&nbsp;</label><br/>
                                @if($employeebasicsalaryinfo->rateelevationstatus == 0)
                                <button type="button" class="btn btn-info mb-2" id="changesalaryamount" oldsalaryrate="{{$employeebasicsalaryinfo->amount}}">Change</button>
                                @else
                                <a type="button" class="btn btn-warning mb-2" id="viewchangesalaryamountrequest" oldsalaryrate="{{$employeebasicsalaryinfo->amount}}">Requested</a>
                                @endif
                            </div>
                            <div class="col-md-3 pt-2">
                                <label>&nbsp;</label>
                                <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                @if($employeebasicsalaryinfo->saturdays == 1)
                                    <input type="checkbox"  name="saturday" id="saturdaywork" checked>
                                @else
                                    <input type="checkbox"  name="saturday" id="saturdaywork">
                                @endif
                                <label for="saturdaywork">
                                    Saturday
                                </label>
                                </div>
                                <div class="icheck-primary d-inline">
                                @if($employeebasicsalaryinfo->sundays == 1)
                                    <input type="checkbox"  name="sunday"  id="sundaywork" checked>
                                @else
                                    <input type="checkbox"  name="sunday"  id="sundaywork">
                                @endif
                                <label for="sundaywork"> 
                                    Sunday
                                </label>
                                </div>
                            </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label>Payment Type</label>
                                <select class="form-control" name="paymenttype" id="paymenttype" required>
                                    <option value="cash">Cash</option>
                                    <option value="check">Check</option>
                                    <option value="banktransfer">Bank deposit</option>
                                </select>
                            </div>
                            <div class="col-md-2"  style=" display: grid;" id="submitbuttoncontainer">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-warning" id="submitbutton">Save Changes</button>
                            </div>
                        </div>
                        <script>
                            $('#submitbuttoncontainer').hide();
                            $('#hoursperday').on('keyup', function(){
                                if($(this).val().length>0)
                                {
                                    $('#submitbuttoncontainer').show();
                                }
                            })
                            $('#salaryamount').on('keyup', function(){
                                if($(this).val()>0)
                                {
                                    $('#submitbuttoncontainer').show();
                                }
                            })
                            $('#submitbutton').on('click', function(){
                                
                                var basistypeid = $('#selectbasis').val();
                                var hoursperday = $('#hoursperday').val();
                                var salaryamount = $('#salaryamount').val();
                                var paymenttype = $('#paymenttype').val();
                                
                                if(hoursperday.replace(/^\s+|\s+$/g, "").length == 0){
                                    $('#hoursperday').css("border","2px solid red")
                                }else{
                                    $('#hoursperday').removeAttr('style')
                                }
                                if(salaryamount.replace(/^\s+|\s+$/g, "").length == 0){
                                    $('#salaryamount').css("border","2px solid red")
                                }else{
                                    $('#salaryamount').removeAttr('style')
                                }
                        
                                if($('#saturdaywork').prop('checked') == true)
                                {
                                    var saturdaywork = 1;
                                }else{
                                    var saturdaywork = 0;
                                }
                                if($('#sundaywork').prop('checked') == true)
                                {
                                    var sundaywork = 1;
                                }else{
                                    var sundaywork = 0;
                                }
                        
                                
                                if(hoursperday.replace(/^\s+|\s+$/g, "").length > 0 && salaryamount.replace(/^\s+|\s+$/g, "").length > 0){
                                    $.ajax({
                                        url: '/hr/employeebasicsalaryinfo',
                                        type: 'GET',
                                        dataType: 'json',
                                        data: {
                                            basistypeid     : basistypeid,
                                            hoursperday     : hoursperday,
                                            salaryamount    : salaryamount,
                                            saturdaywork    : saturdaywork,
                                            sundaywork      : sundaywork,
                                            paymenttype     : paymenttype,
                                            employeeid      : '{{$profileinfoid}}'
                                        },
                                        success:function(data)
                                        {
                                            if(data == 1)
                                            {
                                                $('#custom-content-above-salary-tab').click()
                                                toastr.success('Updated successfully!', 'Basic Salary Info')
                                            }else{
                                                toastr.danger('Unable to make changes!', 'Basic Salary Info')
                                            }
                                        }
                                    })
                                }
                                
                            })
                        </script>
                    @elseif($employeebasicsalaryinfo->basistypeid == 6)
                        <hr/>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label>No of hours per week</label>
                                <input type="number" step="any" class="form-control" name="hoursperweek" id="hoursperweek" value="{{$employeebasicsalaryinfo->hoursperweek}}" readonly/>
                            </div>
                            <div class="col-md-3">
                                <label>Salary amount (per hour)</label>
                                <input type="hidden" step="any" class="form-control" name="salaryamount" id="salaryamount" value="{{$employeebasicsalaryinfo->amount}}" disabled/>
                                <br/>
                                
                                <h5 class="text-right">{{$employeebasicsalaryinfo->amount}}</h5>
                            </div>
                            <div class="col-md-2" >
                                <label>&nbsp;</label><br/>
                                @if($employeebasicsalaryinfo->rateelevationstatus == 0)
                                <button type="button" class="btn btn-info mb-2" id="changesalaryamount" oldsalaryrate="{{$employeebasicsalaryinfo->amount}}">Change</button>
                                @else
                                <a type="button" class="btn btn-warning mb-2" id="viewchangesalaryamountrequest" oldsalaryrate="{{$employeebasicsalaryinfo->amount}}">Requested</a>
                                @endif
                            </div>
                        </div>
                        <br/>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-2" style=" display: flex;align-items: center;">
                                            <div class="icheck-primary d-inline col-md-5">
                                                @if($employeebasicsalaryinfo->mondays == 1)
                                                    <input type="checkbox" name="daysrender[]" value="monday" id="daymon" class="hourlycheckbox"  checked disabled>
                                                @else
                                                    <input type="checkbox" name="daysrender[]" value="monday" id="daymon" class="hourlycheckbox"  disabled>
                                                @endif
                                                <label class="mr-5" for="daymon">
                                                Mon
                                                </label>
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group m-0">                          
                                            <div class="input-group input-group-sm">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text">No. of Hours</span>
                                              </div>
                                              <input type="number" class="form-control form-control-sm monday hourlydaysrender" name="nodaysrender[]" value="{{$employeebasicsalaryinfo->mondayhours}}">
                                            </div>
                                          </div>
                                    </div>
                                    @if($employeebasicsalaryinfo->mondays == 1)
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-block btn-default scheddetails" data-id="mon">Time Sched</button>
                                        </div>
                                    @endif
                                </div>
                                <div id="rowtimeschedmon">

                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-2" style=" display: flex;align-items: center;">
                                            <div class="icheck-primary d-inline col-md-5">
                                                @if($employeebasicsalaryinfo->tuesdays == 1)
                                                    <input type="checkbox" name="daysrender[]" value="tuesday" id="daytue" class="hourlycheckbox" checked disabled>
                                                @else
                                                    <input type="checkbox" name="daysrender[]" value="tuesday" id="daytue" class="hourlycheckbox" disabled>
                                                @endif
                                                <label class="mr-5" for="daytue">
                                                Tue
                                                </label>
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group m-0">                          
                                            <div class="input-group input-group-sm">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text">No. of Hours</span>
                                              </div>
                                              <input type="number" class="form-control form-control-sm tuesday hourlydaysrender" name="nodaysrender[]" value="{{$employeebasicsalaryinfo->tuesdayhours}}">
                                            </div>
                                          </div>
                                    </div>
                                    @if($employeebasicsalaryinfo->tuesdays == 1)
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-block btn-default scheddetails" data-id="tue">Time Sched</button>
                                        </div>
                                    @endif
                                </div>
                                <div id="rowtimeschedtue">

                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-2" style=" display: flex;align-items: center;">
                                            <div class="icheck-primary d-inline col-md-5">
                                                @if($employeebasicsalaryinfo->wednesdays == 1)
                                                    <input type="checkbox" name="daysrender[]" value="wednesday" id="daywed" class="hourlycheckbox" checked disabled>
                                                @else 
                                                    <input type="checkbox" name="daysrender[]" value="wednesday" id="daywed" class="hourlycheckbox" disabled>
                                                @endif
                                                <label class="mr-5" for="daywed">
                                                Wed
                                                </label>
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group m-0">                          
                                            <div class="input-group input-group-sm">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text">No. of Hours</span>
                                              </div>
                                              <input type="number" class="form-control form-control-sm wednesday hourlydaysrender" name="nodaysrender[]" value="{{$employeebasicsalaryinfo->wednesdayhours}}">
                                            </div>
                                          </div>
                                    </div>
                                    @if($employeebasicsalaryinfo->wednesdays == 1)
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-block btn-default scheddetails" data-id="wed">Time Sched</button>
                                        </div>
                                    @endif
                                </div>
                                <div id="rowtimeschedwed">

                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-2" style=" display: flex;align-items: center;">
                                            <div class="icheck-primary d-inline col-md-5">
                                                @if($employeebasicsalaryinfo->thursdays == 1)
                                                    <input type="checkbox" name="daysrender[]" value="thursday" id="daythu" class="hourlycheckbox" checked disabled>
                                                @else
                                                    <input type="checkbox" name="daysrender[]" value="thursday" id="daythu" class="hourlycheckbox" disabled>
                                                @endif
                                                <label class="mr-5" for="daythu">
                                                Thu
                                                </label>
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group m-0">                          
                                            <div class="input-group input-group-sm">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text">No. of Hours</span>
                                              </div>
                                              <input type="number" class="form-control form-control-sm thursday hourlydaysrender" name="nodaysrender[]" value="{{$employeebasicsalaryinfo->thursdayhours}}">
                                            </div>
                                          </div>
                                    </div>
                                    @if($employeebasicsalaryinfo->thursdays == 1)
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-block btn-default scheddetails" data-id="thu">Time Sched</button>
                                        </div>
                                    @endif
                                </div>
                                <div id="rowtimeschedthu">

                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-2" style=" display: flex;align-items: center;">
                                        <div class="icheck-primary d-inline col-md-5">
                                            @if($employeebasicsalaryinfo->fridays == 1)
                                                <input type="checkbox" name="daysrender[]" value="friday" id="dayfri" class="hourlycheckbox" checked disabled>
                                            @else 
                                                <input type="checkbox" name="daysrender[]" value="friday" id="dayfri" class="hourlycheckbox" disabled>
                                            @endif
                                            <label class="mr-5" for="dayfri">
                                            Fri
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group m-0">                          
                                            <div class="input-group input-group-sm">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text">No. of Hours</span>
                                              </div>
                                              <input type="number" class="form-control form-control-sm friday hourlydaysrender" name="nodaysrender[]" value="{{$employeebasicsalaryinfo->fridayhours}}">
                                            </div>
                                          </div>
                                    </div>
                                    @if($employeebasicsalaryinfo->fridays == 1)
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-block btn-default scheddetails" data-id="fri">Time Sched</button>
                                        </div>
                                    @endif
                                </div>
                                <div id="rowtimeschedfri">

                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-2" style=" display: flex;align-items: center;">
                                            <div class="icheck-primary d-inline col-md-5">
                                                @if($employeebasicsalaryinfo->saturdays == 1)
                                                    <input type="checkbox" name="daysrender[]" value="saturday" id="daysat" class="hourlycheckbox" checked disabled>
                                                @else 
                                                    <input type="checkbox" name="daysrender[]" value="saturday" id="daysat" class="hourlycheckbox" disabled>
                                                @endif
                                                <label class="mr-5" for="daysat">
                                                Sat
                                                </label>
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group m-0">                          
                                            <div class="input-group input-group-sm">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text">No. of Hours</span>
                                              </div>
                                              <input type="number" class="form-control form-control-sm saturday hourlydaysrender" name="nodaysrender[]" value="{{$employeebasicsalaryinfo->saturdayhours}}">
                                            </div>
                                          </div>
                                    </div>
                                    @if($employeebasicsalaryinfo->saturdays == 1)
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-block btn-default scheddetails" data-id="sat">Time Sched</button>
                                        </div>
                                    @endif
                                </div>
                                <div id="rowtimeschedsat">

                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-2" style=" display: flex;align-items: center;">
                                            <div class="icheck-primary d-inline col-md-5">
                                                @if($employeebasicsalaryinfo->sundays == 1)
                                                    <input type="checkbox" name="daysrender[]" value="sunday" id="daysun" class="hourlycheckbox" checked disabled>
                                                @else 
                                                    <input type="checkbox" name="daysrender[]" value="sunday" id="daysun" class="hourlycheckbox" disabled>
                                                @endif
                                                <label class="mr-5" for="daysun">
                                                Sun
                                                </label>
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group m-0">                          
                                            <div class="input-group input-group-sm">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text">No. of Hours</span>
                                              </div>
                                              <input type="number" class="form-control form-control-sm sunday hourlydaysrender" name="nodaysrender[]" value="{{$employeebasicsalaryinfo->sundayhours}}">
                                            </div>
                                          </div>
                                    </div>
                                    @if($employeebasicsalaryinfo->sundays == 1)
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-block btn-default scheddetails" data-id="sun">Time Sched</button>
                                        </div>
                                    @endif
                                </div>
                                <div id="rowtimeschedsun">

                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label>Payment Type</label>
                                <select class="form-control" name="paymenttype" id="paymenttype" required>
                                    <option value="cash">Cash</option>
                                    <option value="check">Check</option>
                                    <option value="banktransfer">Bank deposit</option>
                                </select>
                            </div>
                            <div class="col-md-2"  style=" display: grid;" id="submitbuttoncontainer">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-warning" id="submitbutton">Save Changes</button>
                            </div>
                        </div>
                        <div class="modal fade right" id="timescheddetailsview" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
                            <div class="modal-dialog-full-width modal-dialog momodel modal-fluid" role="document">
                                <div class="modal-content-full-width modal-content " id="timscheddetailcontainer">

                                </div>
                            </div>
                        </div>
                        <script>
                            
                            $('#submitbuttoncontainer').hide();
                            
                            var workingdays;
                            var workinghours;
                            $('.hourlycheckbox').unbind().click(function(){
                                var workingdaysarray= [];
                                var workinghoursarray= [];
                                $('.hourlycheckbox:checked').each(function(){
                                    workingdaysarray.push($(this).val())
                                    workinghoursarray.push($(this).closest('.row').find('.hourlydaysrender').val())
                                })
                                workingdays = workingdaysarray;
                                workinghours = workinghoursarray;
                            })
                            $('.hourlydaysrender').on('keyup', function(){
                                if($(this).val() > 0)
                                {
                                    $(this).closest('.row').find('.hourlycheckbox').prop('checked', true)
                                }else{
                                    $(this).closest('.row').find('.hourlycheckbox').prop('checked', false)
                                }
                                $('.hourlycheckbox').click();
                                console.log(workingdays)
                                console.log(workinghours)
                                var total = 0;
                                for (var i = 0; i < workinghours.length; i++) {
                                    total += workinghours[i] << 0;
                                }
                                $('input[name="hoursperweek"]').val(total)

                                if(total > 0)
                                {
                                    $('#submitbuttoncontainer').show();
                                }else{
                                    $('#submitbuttoncontainer').hide();
                                }
                            })
                            $('.scheddetails').on('click', function(){
                                    var selectedday = $(this).attr('data-id')
                                    $('#timescheddetailsview').modal('show')
                                    $.ajax({
                                        url: '/hr/employeebasicsalaryinfotimesched',
                                        type: 'GET',
                                        data: {
                                            selectedday     : selectedday,
                                            action          : 'get',
                                            employeeid      : '{{$profileinfoid}}'
                                        },
                                        success:function(data)
                                        {
                                            $('#timscheddetailcontainer').empty()
                                            $('#timscheddetailcontainer').append(data)
                                        }
                                    })

                            })
                            $('#submitbutton').on('click', function(){
                                var basistypeid = $('#selectbasis').val();
                                var hoursperweek = $('#hoursperweek').val();
                                var salaryamount = $('#salaryamount').val();
                                var paymenttype = $('#paymenttype').val();

                                if($('#salaryamount').val().replace(/^\s+|\s+$/g, "").length > 0 && salaryamount.replace(/^\s+|\s+$/g, "").length > 0){
                                    $('#salaryamount').removeAttr('style');
                                    $.ajax({
                                        url: '/hr/employeebasicsalaryinfo',
                                        type: 'GET',
                                        dataType: 'json',
                                        data: {
                                            basistypeid     : basistypeid,
                                            hoursperweek    : hoursperweek,
                                            salaryamount    : salaryamount,
                                            workingdays     : workingdays,
                                            workinghours    : workinghours,
                                            paymenttype     : paymenttype,
                                            employeeid      : '{{$profileinfoid}}'
                                        },
                                        success:function(data)
                                        {
                                            if(data == 1)
                                            {
                                                $('#custom-content-above-salary-tab').click()
                                                toastr.success('Updated successfully!', 'Basic Salary Info')
                                            }else{
                                                toastr.error('Unable to make changes!', 'Basic Salary Info')
                                            }
                                        }
                                    })
                                }else{
                                    $('#salaryamount').css("border","2px solid red")
                                }
                            })
                        </script>
                    @elseif($employeebasicsalaryinfo->basistypeid == 8)
                        <hr/>
                        <div class="row mt-2">
                            <div class="col-md-3 " style=" display: flex;align-items: center;">
                                <label>Type</label>
                            </div>
                            <div class="col-md-3 " style=" display: flex;align-items: center;">
                                <label>Hours per day</label>
                            </div>
                            <div class="col-md-3 " style=" display: flex;align-items: center;">
                                <label>Amount</label>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3 " style=" display: flex;align-items: center;">
                                <div class="icheck-primary d-inline">
                                    @if($employeebasicsalaryinfo->projectbasedtype == 'perday')
                                        <input type="radio" id="perday" name="projecttype" checked>
                                    @else
                                        <input type="radio" id="perday" name="projecttype">
                                    @endif
                                    <label for="perday">
                                        Per day
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="any" class="form-control hoursperday" name="salaryamount" id="" placeholder="No. of hours per day" @if($employeebasicsalaryinfo->projectbasedtype == 'perday') value="{{$employeebasicsalaryinfo->hoursperday}}" @else disabled @endif/>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="any" class="form-control salaryamount" name="salaryamount" id="" placeholder="Amount per day" @if($employeebasicsalaryinfo->projectbasedtype == 'perday') value="{{$employeebasicsalaryinfo->amount}}" @endif disabled/>
                            </div>
                            @if($employeebasicsalaryinfo->projectbasedtype == 'perday')
                            <div class="col-md-3">
                                
                                @if($employeebasicsalaryinfo->rateelevationstatus == 0)
                                <button type="button" class="btn btn-info mb-2" id="changesalaryamount" oldsalaryrate="{{$employeebasicsalaryinfo->amount}}">Change</button>
                                @else
                                <a type="button" class="btn btn-warning mb-2" id="viewchangesalaryamountrequest" oldsalaryrate="{{$employeebasicsalaryinfo->amount}}">Requested</a>
                                @endif
                                {{-- <button type="button" class="btn btn-info mb-2" id="changesalaryamount" oldsalaryrate="{{$employeebasicsalaryinfo->amount}}">Change</button> --}}
                            </div>
                            @endif
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3 " style=" display: flex;align-items: center;">
                                <div class="icheck-primary d-inline">
                                    @if($employeebasicsalaryinfo->projectbasedtype == 'permonth')
                                        <input type="radio" id="permonth" name="projecttype" checked >
                                    @else 
                                        <input type="radio" id="permonth" name="projecttype">
                                    @endif
                                    <label for="permonth">
                                        Per month
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="any" class="form-control hoursperday" name="salaryamount" id="" placeholder="No. of hours per day"  @if($employeebasicsalaryinfo->projectbasedtype == 'permonth') value="{{$employeebasicsalaryinfo->hoursperday}}" @else disabled @endif/>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="any" class="form-control salaryamount" name="salaryamount" id="" placeholder="Amount per month"  @if($employeebasicsalaryinfo->projectbasedtype == 'permonth') value="{{$employeebasicsalaryinfo->amount}}"  @endif disabled/>
                            </div>
                            @if($employeebasicsalaryinfo->projectbasedtype == 'permonth')
                            <div class="col-md-3">
                                {{-- <button type="button" class="btn btn-info mb-2" id="changesalaryamount" oldsalaryrate="{{$employeebasicsalaryinfo->amount}}">Change</button> --}}
                                
                                @if($employeebasicsalaryinfo->rateelevationstatus == 0)
                                <button type="button" class="btn btn-info mb-2" id="changesalaryamount" oldsalaryrate="{{$employeebasicsalaryinfo->amount}}">Change</button>
                                @else
                                <a type="button" class="btn btn-warning mb-2" id="viewchangesalaryamountrequest" oldsalaryrate="{{$employeebasicsalaryinfo->amount}}">Requested</a>
                                @endif
                            </div>
                            @endif
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3 " style=" display: flex;align-items: center;">
                                <div class="icheck-primary d-inline">
                                    @if($employeebasicsalaryinfo->projectbasedtype == 'perperiod')
                                    <input type="radio" id="perperiod" name="projecttype" checked>
                                    @else 
                                    <input type="radio" id="perperiod" name="projecttype">
                                    @endif
                                    <label for="perperiod">
                                        Per payroll period
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2" style=" display: flex;align-items: center;">
                                <label>Amount</label>
                            </div>
                            <div class="col-md-4">
                                <input type="number" step="any" class="form-control salaryamount" name="salaryamount" id="" placeholder="Amount per payroll period" @if($employeebasicsalaryinfo->projectbasedtype == 'perperiod') value="{{$employeebasicsalaryinfo->amount}}" @else disabled @endif/>
                            </div>
                            @if($employeebasicsalaryinfo->projectbasedtype == 'perperiod')
                            <div class="col-md-3">
                                {{-- <button type="button" class="btn btn-info mb-2" id="changesalaryamount" oldsalaryrate="{{$employeebasicsalaryinfo->amount}}">Change</button> --}}
                                
                                @if($employeebasicsalaryinfo->rateelevationstatus == 0)
                                <button type="button" class="btn btn-info mb-2" id="changesalaryamount" oldsalaryrate="{{$employeebasicsalaryinfo->amount}}">Change</button>
                                @else
                                <a type="button" class="btn btn-warning mb-2" id="viewchangesalaryamountrequest" oldsalaryrate="{{$employeebasicsalaryinfo->amount}}">Requested</a>
                                @endif
                            </div>
                            @endif
                        </div>
                        <hr/>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label>Payment Type</label>
                                <select class="form-control" name="paymenttype" id="paymenttype" required>
                                    <option value="cash">Cash</option>
                                    <option value="check">Check</option>
                                    <option value="banktransfer">Bank deposit</option>
                                </select>
                            </div>
                            <div class="col-md-2"  style=" display: grid;" id="submitbuttoncontainer">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-warning" id="submitbutton">Save Changes</button>
                            </div>
                        </div>
                        <script>
                            $('#submitbuttoncontainer').hide();
                        
                            var clickedprojecttype;
                        
                            $('input[name="projecttype"]').on('click', function(){
                                $('input[type="number"]').prop('disabled',true)
                                $('input[type="number"]').removeAttr('style')
                                $('input[type="number"]').val('')
                                if($(this).attr('id') == 'perday')
                                {
                                    clickedprojecttype = 'perday';
                                    $(this).closest('.row').find('input[type="number"]').prop('disabled', false)
                                }
                                if($(this).attr('id') == 'perperiod')
                                {
                                    clickedprojecttype = 'perperiod';
                                    $(this).closest('.row').find('input[type="number"]').prop('disabled', false)
                                }
                                if($(this).attr('id') == 'permonth')
                                {
                                    clickedprojecttype = 'permonth';
                                    $(this).closest('.row').find('input[type="number"]').prop('disabled', false)
                                }
                                $('#submitbuttoncontainer').show();
                            })
                        
                            $('#submitbutton').on('click', function(){
                                var oldbasistypeid = $()
                                var basistypeid = $('#selectbasis').val();
                                var paymenttype = $('#paymenttype').val();
                                var hoursperday = 0;
                                var salaryamount = 0;
                        
                                var fillin = 0;
                                var thisrow = $('input[name="projecttype"]:checked').closest('.row');
                        
                                if(clickedprojecttype == 'perday')
                                {
                                    if(thisrow.find('.hoursperday').val().replace(/^\s+|\s+$/g, "").length == 0){
                                        thisrow.find('.hoursperday').css("border","2px solid red")
                                    }else{
                                        thisrow.find('.hoursperday').removeAttr('style')
                                    }
                                    if(thisrow.find('.salaryamount').val().replace(/^\s+|\s+$/g, "").length == 0){
                                        thisrow.find('.salaryamount').css("border","2px solid red")
                                    }else{
                                        thisrow.find('.salaryamount').removeAttr('style')
                                    }
                                    if(thisrow.find('.hoursperday').val().replace(/^\s+|\s+$/g, "").length > 0 && thisrow.find('.salaryamount').val().replace(/^\s+|\s+$/g, "").length > 0){
                                        hoursperday = thisrow.find('.hoursperday').val();
                                        salaryamount = thisrow.find('.salaryamount').val();
                                        fillin+=1;
                                    }
                                }
                                else if(clickedprojecttype == 'perperiod')
                                {
                                    if(thisrow.find('.salaryamount').val().replace(/^\s+|\s+$/g, "").length == 0){
                                        thisrow.find('.salaryamount').css("border","2px solid red")
                                    }else{
                                        thisrow.find('.salaryamount').removeAttr('style')
                                        hoursperday = 0;
                                        salaryamount = thisrow.find('.salaryamount').val();
                                        fillin+=1;
                                    }
                                }
                                else if(clickedprojecttype == 'permonth')
                                {
                                    if(thisrow.find('.hoursperday').val().replace(/^\s+|\s+$/g, "").length == 0){
                                        thisrow.find('.hoursperday').css("border","2px solid red")
                                    }else{
                                        thisrow.find('.hoursperday').removeAttr('style')
                                    }
                                    if(thisrow.find('.salaryamount').val().replace(/^\s+|\s+$/g, "").length == 0){
                                        thisrow.find('.salaryamount').css("border","2px solid red")
                                    }else{
                                        thisrow.find('.salaryamount').removeAttr('style')
                                    }
                                    if(thisrow.find('.hoursperday').val().replace(/^\s+|\s+$/g, "").length > 0 && thisrow.find('.salaryamount').val().replace(/^\s+|\s+$/g, "").length > 0){
                                        hoursperday = thisrow.find('.hoursperday').val();
                                        salaryamount = thisrow.find('.salaryamount').val();
                                        fillin+=1;
                                    }
                                }
                        
                                if(fillin>0)
                                {
                                    $.ajax({
                                        url: '/hr/employeebasicsalaryinfo',
                                        type: 'GET',
                                        dataType: 'json',
                                        data: {
                                            basistypeid     : basistypeid,
                                            hoursperday     : hoursperday,
                                            salaryamount    : salaryamount,
                                            paymenttype     : paymenttype,
                                            projectbasedtype: clickedprojecttype,
                                            employeeid      : '{{$profileinfoid}}'
                                        },
                                        success:function(data)
                                        {
                                            if(data == 1)
                                            {
                                                $('#custom-content-above-salary-tab').click()
                                                toastr.success('Updated successfully!', 'Basic Salary Info')
                                            }else{
                                                toastr.danger('Unable to make changes!', 'Basic Salary Info')
                                            }
                                        }
                                    })
                                }
                                
                            })
                        </script>
                    @endif
                @endif
            </div>
        </form>
        </div>
    </div>
<div id="rateelevation" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title"><strong>Change rate</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <label>Old Salary Amount</label>
                <input type="text" class="form-control" id="formoldsalaryamount" value="" disabled />
                <br/>
                
                <label>New Salary Amount</label>
                <input type="number" step="any" class="form-control" id="formnewsalaryamount" />
                <br/>
                <div id="passwordsubmit">
                    <label>Authorized Personnel Only</label>
                    <input type="password" class="form-control" id="authorizedpassword" placeholder="Password"/>
                </div>
                <br/>
                <div class="submit-section">
                    <button type="button" class="btn btn-primary submit-btn float-right mt-2" id="buttonsubmitnewrate">Submit request</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="showrequest" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title"><strong>Salary rate elevation request</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <label>Old Salary Amount</label>
                <input type="text" class="form-control" id="viewoldsalaryamount" value="" disabled />
                <br/>
                
                <label>New Salary Amount</label>
                <input type="text" class="form-control" id="viewnewsalaryamount" disabled/>
                
                <div class="submit-section">
                    <button type="button" class="btn btn-danger submit-btn float-right mt-2" id="buttonundorequest">Undo Request</button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @include('hr.employeeprofile.scripts.basicsalary_js') --}}
<script>
    $('#passwordsubmit').hide()
    $('#buttonsubmitnewrate').hide()
    @if(count(collect($employeebasicsalaryinfo))>0)
        @if($employeebasicsalaryinfo->rateelevationstatus == 1)
            $("#thisform :input").prop("disabled", true);
            $("#thisform select").prop("disabled", true);
        @endif
    @endif
    $(document).on('change','#selectbasis', function(){
        $.ajax({
            url: '/hr/employeebasicsalaryinfobasisselection',
            type: 'GET',
            data: {
                typeid : $(this).val(),
                employeeid : '{{$profileinfoid}}'
            },
            success:function(data){
                $('#configcontainer').empty()
                $('#configcontainer').append(data)
            }
        })
    })
    $('#changesalaryamount').on('click', function(){
        $('#rateelevation').modal('show');
        $('#formoldsalaryamount').val($(this).attr('oldsalaryrate'))
    })
    $('#formnewsalaryamount').on('keyup', function(){
        if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
        {
            $('#passwordsubmit').hide()
        }else{
            $('#passwordsubmit').show()
        }
    })
    $('#authorizedpassword').on('keyup', function(){
        if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
        {
            $('#buttonsubmitnewrate').hide()
        }else{
            $('#buttonsubmitnewrate').show()
        }
    })
    $('#buttonsubmitnewrate').on('click', function(){
        
        if($('#formnewsalaryamount').val().replace(/^\s+|\s+$/g, "").length == 0){
            $('#formnewsalaryamount').css("border","2px solid red")

        }else{
            var newsalary = $('#formnewsalaryamount').val();
            $('#formnewsalaryamount').removeAttr('style');
            $.ajax({
                url: '/hr/employeerateelevation',
                type: 'GET',
                data: {
                    authorizedpassword: $('#authorizedpassword').val(),
                    oldsalary : $('#formoldsalaryamount').val(),
                    newsalary : $('#formnewsalaryamount').val(),
                    action     : 'request',
                    employeeid : '{{$profileinfoid}}'
                },
                success:function(data){
                    $('body').removeClass('modal-open');
                    $('#rateelevation').removeClass('show');
                    $('#rateelevation').removeAttr('style');
                    $('#rateelevation').css('display','none');
                    $('.modal-backdrop').removeClass('show')
                    $('.modal-backdrop').remove()
                    $('#changesalaryamount').text(newsalary)
                    $('#changesalaryamount').attr('id','undorequest')
                    $('#selectbasis').attr('disabled',true);
                    $('#hoursperday').attr('disabled',true);
                    $('#saturdaywork').attr('disabled',true);
                    $('#sundaywork').attr('disabled',true);
                    $('#paymenttype').attr('disabled',true);
                    $('#custom-content-above-salary-tab').click()
                    if(data == 3)
                    {
                        toastr.error('Password doesn\'t match!', 'Basic Salary Info');
                    }else{
                        toastr.success('Request sent!', 'Basic Salary Info')
                    }
                }
            })
        }
    })
    $('#viewchangesalaryamountrequest').on('click', function(){
        $('#showrequest').modal('show');
        var oldsalary = $(this).attr('oldsalaryrate')
        $('#viewoldsalaryamount').val(oldsalary)
        $.ajax({
            url: '/hr/employeerateelevation',
            type: 'GET',
            data: {
                action     : 'viewrequest',
                employeeid : '{{$profileinfoid}}'
            },
            success:function(data){
                $('#viewnewsalaryamount').val(data)
            }
        })
    })
    $('#buttonundorequest').on('click', function(){
        $.ajax({
            url: '/hr/employeerateelevation',
            type: 'GET',
            data: {
                action     : 'undorequest',
                employeeid : '{{$profileinfoid}}'
            },
            success:function(data){
                $('body').removeClass('modal-open');
                $('#showrequest').removeClass('show');
                $('#showrequest').removeAttr('style');
                $('#showrequest').css('display','none');
                $('.modal-backdrop').removeClass('show')
                $('.modal-backdrop').remove()
                $('#custom-content-above-salary-tab').click()
                toastr.success('Request reverted successfully!', 'Basic Salary Info')
            }
        })
    })
</script>
