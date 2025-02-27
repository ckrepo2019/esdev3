
@if(session()->has('linkid'))
    @if( session()->get('linkid') == 'custom-content-above-profile')
        <div class="tab-pane fade show active" id="custom-content-above-profile" role="tabpanel" aria-labelledby="custom-content-above-profile-tab">
    @else
        <div class="tab-pane fade" id="custom-content-above-profile" role="tabpanel" aria-labelledby="custom-content-above-profile-tab">
    @endif
@else
    <div class="tab-pane fade" id="custom-content-above-profile" role="tabpanel" aria-labelledby="custom-content-above-profile-tab">
@endif
        <div class="card">
            <div class="card-body">
                <form action="/employeebasicsalaryinfo" method="get">
                    <input type="hidden" name="employeeid" value="{{$profile->id}}">
                    <input type="hidden" class="form-control" name="linkid" value="custom-content-above-profile" />
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label">Salary basis <span class="text-danger m-0 p-0">*</span></label>
                                <br>
                                @if(count($employee_basicsalaryinfo)==0)
                                    <select class="form-control" name="salarybasistype" required>
                                        @foreach($salarybasistypes as $salarybasistype)
                                            <option value="{{$salarybasistype->id}}" type="{{$salarybasistype->type}}">{{$salarybasistype->type}}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <select class="form-control" name="salarybasistype" required>
                                        @foreach($salarybasistypes as $salarybasistype)
                                            <option value="{{$salarybasistype->id}}" {{$salarybasistype->id == $employee_basicsalaryinfo[0]->basistypeid ? "selected" : ""}} type="{{$salarybasistype->type}}">{{$salarybasistype->type}}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4" id="generalsalaryamouncontainer">
                            @if(count($employee_basicsalaryinfo) == 0)
                                <div class="form-group">
                                    <label class="col-form-label">Salary amount</label>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">&#8369;</span>
                                        </div>
                                        {{-- &#8369; 0.00 --}}
                                        <input type="text" class="form-control groupOfTexbox" name="salaryamount" placeholder="Type your salary amount" value="0.00" required>
                                    </div>
                                </div>
                            @elseif(count($employee_basicsalaryinfo) > 0)
                                @if($employee_basicsalaryinfo[0]->type == 'Project')
                                @else
                                    <div class="form-group">
                                        <label class="col-form-label">Salary amount</label>
                                        <br>
                                        
                                        @if(count($employeerateelevation) == 0)
                                            <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#rateelevation">
                                                Change rate
                                            </button> 
                                        @else 
                                            @if($employeerateelevation[0]->status == 0 && $employeerateelevation[0]->newsalary != $employeerateelevation[0]->oldsalary)
                                                <button type="button" class="btn btn-sm btn-warning float-right" data-toggle="modal" data-target="#rateelevation">
                                                    Change rate ( &#8369; {{number_format($employeerateelevation[0]->newsalary,2,'.',',')}} )
                                                </button> 
                                            @else
                                                <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#rateelevation">
                                                    Change rate
                                                </button>
                                            @endif
                                        @endif
                                        {{-- <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">&#8369;</span>
                                            </div> --}}
                                        &#8369; {{number_format($employee_basicsalaryinfo[0]->amount,2,'.',',')}}
                                        <input type="hidden" class="form-control groupOfTexbox" name="salaryamount" placeholder="Type your salary amount" value="{{$employee_basicsalaryinfo[0]->amount}}" >
                                            {{-- <input type="text" class="form-control groupOfTexbox" name="salaryamount" value="{{$employee_basicsalaryinfo[0]->amount}}" placeholder="Type your salary amount" value="0.00" required disabled> --}}
                                        {{-- </div> --}}
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="col-sm-4 mb-2" id="noofhours">
                            @if(count($employee_basicsalaryinfo) == 0)
                                <label class="col-form-label">No. working hours per day</label>
                                <input type="number" name="hoursperday" class="form-control mb-2" value="0"placeholder="No. working hours per day" required/>
                            @elseif(count($employee_basicsalaryinfo) > 0)
                                @if($employee_basicsalaryinfo[0]->type == 'Hourly')
                                    <label class="col-form-label">No. working hours per week</label>
                                    <input type="number" name="hoursperweek" class="form-control mb-2" value="{{$employee_basicsalaryinfo[0]->hoursperweek}}" placeholder="No. working hours per week" required/>
                                @elseif($employee_basicsalaryinfo[0]->type == 'Project')
                                @else
                                    <label class="col-form-label">No. working hours per day</label>
                                    <input type="number" name="hoursperday" class="form-control mb-2" value="{{$employee_basicsalaryinfo[0]->hoursperday}}" placeholder="No. working hours per day" required/>
                                @endif
                            @endif
                        </div>
                        @if(strtolower($tardinesssetup[0]->type) == 'custom')
                            <div class="col-sm-12 mb-2" >
                                <div class="row" >
                                    @if(count($employee_timeschedule) == 0)
                                        <div class="col-md-3">
                                            <label>AM IN</label>
                                            <input id="timepickeramin"  employeeid="{{$profile->id}}" class="timepick form-control" value="07:30" name="am_in"/>
                                        </div>
                                        <div class="col-md-3">
                                            <label>AM OUT</label>
                                            <input id="timepickeramout"  employeeid="{{$profile->id}}" class="timepick form-control" value="12:00" name="am_out"/>
                                        </div>
                                        <div class="col-md-3">
                                            <label>PM IN</label>
                                            <input id="timepickerpmin"  employeeid="{{$profile->id}}" class="timepick form-control" value="01:30" name="pm_in"/>
                                        </div>
                                        <div class="col-md-3">
                                            <label>PM OUT</label>
                                            <input id="timepickerpmout"  employeeid="{{$profile->id}}" class="timepick form-control" value="04:30" name="pm_out"/>
                                        </div>
                                    @else
                                        <div class="col-md-3">
                                            <label>AM IN</label>
                                            <input id="timepickeramin"  employeeid="{{$profile->id}}" class="timepick form-control" value="{{$employee_timeschedule[0]->amin}}" name="am_in"/>
                                        </div>
                                        <div class="col-md-3">
                                            <label>AM OUT</label>
                                            <input id="timepickeramout"  employeeid="{{$profile->id}}" class="timepick form-control" value="{{$employee_timeschedule[0]->amout}}" name="am_out"/>
                                        </div>
                                        <div class="col-md-3">
                                            <label>PM IN</label>
                                            <input id="timepickerpmin"  employeeid="{{$profile->id}}" class="timepick form-control" value="{{$employee_timeschedule[0]->pmin}}" name="pm_in"/>
                                        </div>
                                        <div class="col-md-3">
                                            <label>PM OUT</label>
                                            <input id="timepickerpmout"  employeeid="{{$profile->id}}" class="timepick form-control" value="{{$employee_timeschedule[0]->pmout}}" name="pm_out"/>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    <div id="othersalarysettingcontainer">
                        @if(count($employee_basicsalaryinfo)==0)
                        @else
                            @if($employee_basicsalaryinfo[0]->type == 'Hourly')
                                <div class="row mt-2">
                                    <div class="col-md-2 col-5">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline col-md-5">
                                                @if($employee_basicsalaryinfo[0]->mondays == 1)
                                                    <input type="checkbox" name="daysrender[]" value="monday" id="daymon" checked>
                                                @else
                                                    <input type="checkbox" name="daysrender[]" value="monday" id="daymon">
                                                @endif
                                                <label class="mr-5" for="daymon">
                                                    M
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-5">
                                        @if($employee_basicsalaryinfo[0]->mondays == 1)
                                            <input type="number" class="form-control form-control-sm monday daysrender" value="{{$employee_basicsalaryinfo[0]->mondayhours}}" name="nodaysrender[]" value="0" readonly>
                                        @else
                                            <input type="number" class="form-control form-control-sm monday" name="nodaysrender[]" value="0" readonly disabled>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-2 col-5">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline col-md-5">
                                                @if($employee_basicsalaryinfo[0]->tuesdays == 1)
                                                    <input type="checkbox" name="daysrender[]" value="tuesday" id="daytue" checked>
                                                @else
                                                    <input type="checkbox" name="daysrender[]" value="tuesday" id="daytue">
                                                @endif
                                                <label class="mr-5" for="daytue">
                                                    T
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-5">
                                        @if($employee_basicsalaryinfo[0]->tuesdays == 1)
                                            <input type="number" class="form-control form-control-sm tuesday daysrender" value="{{$employee_basicsalaryinfo[0]->tuesdayhours}}" name="nodaysrender[]" value="0" readonly>
                                        @else
                                            <input type="number" class="form-control form-control-sm tuesday" name="nodaysrender[]" value="0" readonly disabled>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-2 col-5">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline col-md-5">
                                                @if($employee_basicsalaryinfo[0]->wednesdays == 1)
                                                    <input type="checkbox" name="daysrender[]" value="wednesday" id="daywed" checked>
                                                @else
                                                    <input type="checkbox" name="daysrender[]" value="wednesday" id="daywed" >
                                                @endif
                                                <label class="mr-5" for="daywed">
                                                    W
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-5">
                                        @if($employee_basicsalaryinfo[0]->wednesdays == 1)
                                            <input type="number" class="form-control form-control-sm wednesday daysrender" name="nodaysrender[]" value="{{$employee_basicsalaryinfo[0]->wednesdayhours}}" readonly>
                                        @else
                                            <input type="number" class="form-control form-control-sm wednesday" name="nodaysrender[]" value="0" readonly disabled>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-2 col-5">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline col-md-5">
                                                @if($employee_basicsalaryinfo[0]->thursdays == 1)
                                                    <input type="checkbox" name="daysrender[]" value="thursday" id="daythu" checked>
                                                @else
                                                    <input type="checkbox" name="daysrender[]" value="thursday" id="daythu">
                                                @endif
                                                <label class="mr-5" for="daythu">
                                                    Th
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-5">
                                        @if($employee_basicsalaryinfo[0]->thursdays == 1)
                                            <input type="number" class="form-control form-control-sm thursday daysrender" name="nodaysrender[]" value="{{$employee_basicsalaryinfo[0]->thursdayhours}}" readonly>
                                        @else
                                            <input type="number" class="form-control form-control-sm thursday" name="nodaysrender[]" value="0" readonly disabled>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-2 col-5">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline col-md-5">
                                                @if($employee_basicsalaryinfo[0]->fridays == 1)
                                                    <input type="checkbox" name="daysrender[]" value="friday" id="dayfri" checked>
                                                @else
                                                    <input type="checkbox" name="daysrender[]" value="friday" id="dayfri">
                                                @endif
                                                <label class="mr-5" for="dayfri">
                                                    F
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-5">
                                        @if($employee_basicsalaryinfo[0]->fridays == 1)
                                            <input type="number" class="form-control form-control-sm friday daysrender" name="nodaysrender[]" value="{{$employee_basicsalaryinfo[0]->fridayhours}}" readonly>
                                        @else
                                            <input type="number" class="form-control form-control-sm friday" name="nodaysrender[]" value="0" readonly disabled>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-2 col-5">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline col-md-5">
                                                @if($employee_basicsalaryinfo[0]->saturdays == 1)
                                                    <input type="checkbox" name="daysrender[]" value="saturday" id="daysat" checked>
                                                @else
                                                    <input type="checkbox" name="daysrender[]" value="saturday" id="daysat">
                                                @endif
                                                <label class="mr-5" for="daysat">
                                                    Sat
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-5">
                                        @if($employee_basicsalaryinfo[0]->saturdays == 1)
                                            <input type="number" class="form-control form-control-sm saturday daysrender" name="nodaysrender[]" value="{{$employee_basicsalaryinfo[0]->saturdayhours}}" readonly>
                                        @else
                                            <input type="number" class="form-control form-control-sm saturday" name="nodaysrender[]" value="0" readonly disabled>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-2 col-5">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline col-md-5">
                                                @if($employee_basicsalaryinfo[0]->sundays == 1)
                                                    <input type="checkbox" name="daysrender[]" value="sunday" id="daysun" checked>
                                                @else
                                                    <input type="checkbox" name="daysrender[]" value="sunday" id="daysun">
                                                @endif
                                                <label class="mr-5" for="daysun">
                                                Sun
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-5">
                                        @if($employee_basicsalaryinfo[0]->sundays == 1)
                                            <input type="number" class="form-control form-control-sm sunday daysrender" name="nodaysrender[]" value="{{$employee_basicsalaryinfo[0]->sundayhours}}" readonly>
                                        @else
                                            <input type="number" class="form-control form-control-sm sunday" name="nodaysrender[]" value="0" readonly disabled>
                                        @endif
                                    </div>
                                </div>
                            @elseif($employee_basicsalaryinfo[0]->type == 'Project')
                                @if($employee_basicsalaryinfo[0]->projectbasedtype == 'perday')
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="projectradiosettingtype1" name="projectradiosettingtype" value="perday" checked>
                                                    <label for="projectradiosettingtype1">Per day</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            {{-- <input type="number" class="form-control form-control-sm projectamount" name="perdayamount" value="{{$employee_basicsalaryinfo[0]->amount}}" placeholder="Amount per day" required> --}}
                                            
                                            @if(count($employeerateelevation) == 0)
                                                <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#rateelevation">
                                                    Change rate
                                                </button> 
                                            @else 
                                                @if($employeerateelevation[0]->status == 0 && $employeerateelevation[0]->newsalary != $employeerateelevation[0]->oldsalary)
                                                    <button type="button" class="btn btn-sm btn-warning float-right" data-toggle="modal" data-target="#rateelevation">
                                                        Change rate ( &#8369; {{number_format($employeerateelevation[0]->newsalary,2,'.',',')}} )
                                                    </button> 
                                                @else
                                                    <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#rateelevation">
                                                        Change rate
                                                    </button>
                                                @endif
                                            @endif
                                        {{-- <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">&#8369;</span>
                                            </div> --}}
                                            &#8369; {{number_format($employee_basicsalaryinfo[0]->amount,2,'.',',')}}
                                            <input type="hidden" class="form-control groupOfTexbox" name="perdayamount" placeholder="Type your salary amount" value="{{$employee_basicsalaryinfo[0]->amount}}" >
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control form-control-sm projecthours" name="perdayhours"  value="{{$employee_basicsalaryinfo[0]->hoursperday}}"placeholder="No. of hours per day" required>
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="projectradiosettingtype1" name="projectradiosettingtype" value="perday">
                                                    <label for="projectradiosettingtype1">Per day</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control form-control-sm projectamount" name="perdayamount" placeholder="Amount per day" disabled required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control form-control-sm projecthours" name="perdayhours" placeholder="No. of hours per day" disabled required>
                                        </div>
                                    </div>
                                @endif
                                @if($employee_basicsalaryinfo[0]->projectbasedtype == 'persalaryperiod')
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="projectradiosettingtype2" name="projectradiosettingtype" value="persalaryperiod" checked>
                                                    <label for="projectradiosettingtype2">Per salary period</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            {{-- <input type="number" class="form-control form-control-sm projectamount" name="persalaryperiodamount" value="{{$employee_basicsalaryinfo[0]->amount}}" placeholder="Amount per salary period" required> --}}
                                            @if(count($employeerateelevation) == 0)
                                                <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#rateelevation">
                                                    Change rate
                                                </button> 
                                            @else 
                                                @if($employeerateelevation[0]->status == 0 && $employeerateelevation[0]->newsalary != $employeerateelevation[0]->oldsalary)
                                                    <button type="button" class="btn btn-sm btn-warning float-right" data-toggle="modal" data-target="#rateelevation">
                                                        Change rate ( &#8369; {{number_format($employeerateelevation[0]->newsalary,2,'.',',')}} )
                                                    </button> 
                                                @else
                                                    <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#rateelevation">
                                                        Change rate
                                                    </button>
                                                @endif
                                            @endif
                                        {{-- <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">&#8369;</span>
                                            </div> --}}
                                            &#8369; {{number_format($employee_basicsalaryinfo[0]->amount,2,'.',',')}}
                                            <input type="hidden" class="form-control groupOfTexbox" name="persalaryperiodamount" placeholder="Type your salary amount" value="{{$employee_basicsalaryinfo[0]->amount}}" >
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="projectradiosettingtype2" name="projectradiosettingtype" value="persalaryperiod">
                                                    <label for="projectradiosettingtype2">Per salary period</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control form-control-sm projectamount" name="persalaryperiodamount" disabled placeholder="Amount per salary period" required disabled>
                                        </div>
                                    </div>
                                @endif
                                @if($employee_basicsalaryinfo[0]->projectbasedtype == 'permonth')
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="projectradiosettingtype3" name="projectradiosettingtype" value="permonth" checked>
                                                    <label for="projectradiosettingtype3">Per month</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            {{-- <input type="number" class="form-control form-control-sm projectamount" name="permonthamount" value="{{$employee_basicsalaryinfo[0]->amount}}" placeholder="Amount per month" required> --}}
                                            @if(count($employeerateelevation) == 0)
                                                <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#rateelevation">
                                                    Change rate
                                                </button> 
                                            @else 
                                                @if($employeerateelevation[0]->status == 0 && $employeerateelevation[0]->newsalary != $employeerateelevation[0]->oldsalary)
                                                    <button type="button" class="btn btn-sm btn-warning float-right" data-toggle="modal" data-target="#rateelevation">
                                                        Change rate ( &#8369; {{number_format($employeerateelevation[0]->newsalary,2,'.',',')}} )
                                                    </button> 
                                                @else
                                                    <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#rateelevation">
                                                        Change rate
                                                    </button>
                                                @endif
                                            @endif
                                        {{-- <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">&#8369;</span>
                                            </div> --}}
                                            &#8369; {{number_format($employee_basicsalaryinfo[0]->amount,2,'.',',')}}
                                            <input type="hidden" class="form-control groupOfTexbox" name="permonthamount" placeholder="Type your salary amount" value="{{$employee_basicsalaryinfo[0]->amount}}" >
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control form-control-sm projecthours" name="permonthhours" value="{{$employee_basicsalaryinfo[0]->hoursperday}}" placeholder="No. of hours per day" required>
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="projectradiosettingtype3" name="projectradiosettingtype" value="permonth">
                                                    <label for="projectradiosettingtype3">Per month</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control form-control-sm projectamount" name="permonthamount" placeholder="Amount per month" required disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control form-control-sm projecthours" name="permonthhours" placeholder="No. of hours per day" required disabled>
                                        </div>
                                    </div>  
                                @endif
                            @endif
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-sm-4 additionalworkondays">
                            <div class="form-group">
                                @if(count($employee_basicsalaryinfo) == 0)
                                    <br>
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="workonsaturdays" name="workonsat" value="1">
                                            <label for="workonsaturdays">
                                                Work on Saturdays
                                            </label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="workonsundays" name="workonsun" value="1">
                                            <label for="workonsundays">
                                                Work on Sundays
                                            </label>
                                        </div>
                                    </div>
                                @elseif(count($employee_basicsalaryinfo) > 0)
                                    <br>
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            @if($employee_basicsalaryinfo[0]->saturdays == 1)
                                                <input type="checkbox" id="workonsaturdays" name="workonsat" value="1" checked>
                                            @else
                                                <input type="checkbox" id="workonsaturdays" name="workonsat" value="1">
                                            @endif
                                            <label for="workonsaturdays">
                                                Work on Saturdays
                                            </label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            @if($employee_basicsalaryinfo[0]->sundays == 1)
                                                <input type="checkbox" id="workonsundays" name="workonsun" value="1" checked>
                                            @else
                                                <input type="checkbox" id="workonsundays" name="workonsun" value="1">
                                            @endif
                                            <label for="workonsundays">
                                                Work on Sundays
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label">Payment type</label>
                                <br>
                                @if(count($employee_basicsalaryinfo) == 0)
                                <select class="form-control" name="paymenttype" required>
                                    <option value="cash">Cash</option>
                                    <option value="check">Check</option>
                                    <option value="banktransfer">Bank deposit</option>
                                </select>
                                @elseif(count($employee_basicsalaryinfo) > 0)
                                    <select class="form-control" name="paymenttype" required>
                                        <option value="cash" {{"cash" == $employee_basicsalaryinfo[0]->paymenttype ? "selected" : ""}}>Cash</option>
                                        <option value="check" {{"check" == $employee_basicsalaryinfo[0]->paymenttype ? "selected" : ""}}>Check</option>
                                        <option value="banktransfer" {{"banktransfer" == $employee_basicsalaryinfo[0]->paymenttype ? "selected" : ""}}>Bank Deposit</option>
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        @if(count($employee_basicsalaryinfo) == 0)
                            <button class="btn btn-primary submit-btn basicsalarybutton" type="submit">Save</button>
                        @elseif(count($employee_basicsalaryinfo) > 0)
                            <button class="btn btn-warning submit-btn basicsalarybutton" type="submit">Update</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>