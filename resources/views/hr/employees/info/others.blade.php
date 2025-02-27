
    <div class="tab-pane fade show active" id="custom-content-above-basicsalary" role="tabpanel" aria-labelledby="custom-content-above-profile-tab">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-2" >
                        <div class="row">
                            <div class="col-md-4">
                                <label>Designation</label>
                                <select class="form-control" id="selecteddesignation">
                                    <option value=""></option>
                                    @if(count($selecteddesignations)>0)
                                    @foreach($selecteddesignations as $selecteddesignation)
                                        <option value="{{$selecteddesignation->id}}" {{$selecteddesignation->id == $designationid ? 'selected' : ''}}>{{$selecteddesignation->utype}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="row mt-2" id="academicprogram">
                                    @if($usertypeid == 1 || $usertypeid == 2)
                                        @foreach($academicprograms as $academicprogram)
                                            <div class="icheck-primary d-inline col-md-6 m-0">
                                                <input type="checkbox" id="checkboxPrimary{{$academicprogram->id}}" name="academicprogram" value="{{$academicprogram->id}}" @if($academicprogram->selected == 1) checked="" @endif>
                                                <label for="checkboxPrimary{{$academicprogram->id}}">
                                                    {{$academicprogram->progname}}
                                                </label>
                                            </div>
                                            <br>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2" style="display: grid;">
                                <br/>
                                @if(count($selecteddesignations)>0)
                                <button type="button" class="btn btn-success btn-sm float-right" id="updatedesignation">Save Changes</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-4">
                        <label>Department</label>
                        <select class="form-control" id="selecteddepartment">
                            <option value=""></option>
                            @if(count($departments)>0)
                                @foreach($departments as $department)
                                    <option value="{{$department->id}}" {{$department->id == $departmentid ? 'selected' : ''}}>{{strtoupper($department->department)}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-2" style="display: grid;">
                        <br/>
                        @if(count($departments)>0)
                            <button type="button" class="btn btn-success btn-sm float-right" id="updatedepartment">Save Changes</button>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-2" >
                        {{-- <label>WORK SHIFT</label>
                        <br/> --}}
                        @php
                            $shiftdef = '';
                            $shiftmor = '';
                            $shiftnig = '';
                            $aminavail = '';
                            $amoutavail = '';
                            $pminavail = '';
                            $pmoutavail = '';
                            if(count(collect($shiftid)) == 0)
                            {
                                $shiftdef = 'checked';
                            }else{
                                if($shiftid->shiftid == 0)
                                {
                                    $shiftdef = 'checked';
                                }
                                elseif($shiftid->shiftid == 1)
                                {
                                    $shiftmor = 'checked';
                                    $pminavail = 'disabled';
                                    $pmoutavail = 'disabled';
                                }
                                elseif($shiftid->shiftid == 2)
                                {
                                    $shiftnig = 'checked';
                                    $aminavail = 'disabled';
                                    $amoutavail = 'disabled';
                                }
                            }
                        @endphp
                        {{-- <div class="form-group clearfix">
                            <div class="icheck-primary d-inline mr-5">
                              <input type="radio" id="workshift0" name="workshift" value="0" {{$shiftdef}}>
                              <label for="workshift0">
                                  Whole day
                              </label>
                            </div>
                            <div class="icheck-primary d-inline mr-5">
                              <input type="radio" id="workshift1" name="workshift" value="1" {{$shiftmor}}>
                              <label for="workshift1">
                                  Morning Shift
                              </label>
                            </div>
                            <div class="icheck-primary d-inline mr-5">
                              <input type="radio" id="workshift2" name="workshift" value="2" {{$shiftnig}}>
                              <label for="workshift2">
                                Night Shift
                              </label>
                            </div>
                          </div> --}}
                    </div>
                </div>
                {{-- <hr/> --}}
                {{-- <div class="row">
                    <div class="col-sm-12 mb-2" >
                        <label>SALARY BASED ON ATTENDANCE</label>
                        <br/>
                        <input type="checkbox" id="attendancebased" name="my-checkbox" checked data-bootstrap-switch data-off-color="danger" data-on-color="success">
                    </div>
                </div> --}}
                    
                    {{-- @if(strtolower($tardinesssetup[0]->type) == 'custom') --}}
                        {{-- <hr/> --}}
                        {{-- <div class="row" >
                            <div class="col-md-12">
                                <label>TIME SCHED</label>
                            </div>
                            @if(count($timeschedule) == 0)
                                <div class="col-md-2">
                                    <label>AM IN</label>
                                    <input id="timepickeramin"  employeeid="{{$profileinfoid}}" class="timepick form-control" value="00:00" name="am_in" {{$aminavail}}/>
                                </div>
                                <div class="col-md-2">
                                    <label>AM OUT</label>
                                    <input id="timepickeramout"  employeeid="{{$profileinfoid}}" class="timepick form-control" value="00:00" name="am_out" {{$amoutavail}}/>
                                </div>
                                <div class="col-md-2">
                                    <label>PM IN</label>
                                    <input id="timepickerpmin"  employeeid="{{$profileinfoid}}" class="timepick form-control" value="00:00" name="pm_in" {{$pminavail}}/>
                                </div>
                                <div class="col-md-2">
                                    <label>PM OUT</label>
                                    <input id="timepickerpmout"  employeeid="{{$profileinfoid}}" class="timepick form-control" value="00:00" name="pm_out" {{$pmoutavail}}/>
                                </div>
                            @else
                                <div class="col-md-2">
                                    <label>AM IN</label>
                                    <input id="timepickeramin"  employeeid="{{$profileinfoid}}" class="timepick form-control" value="{{$timeschedule[0]->amin}}" name="am_in" {{$aminavail}}/>
                                </div>
                                <div class="col-md-2">
                                    <label>AM OUT</label>
                                    <input id="timepickeramout"  employeeid="{{$profileinfoid}}" class="timepick form-control" value="{{$timeschedule[0]->amout}}" name="am_out" {{$amoutavail}}/>
                                </div>
                                <div class="col-md-2">
                                    <label>PM IN</label>
                                    <input id="timepickerpmin"  employeeid="{{$profileinfoid}}" class="timepick form-control" value="{{$timeschedule[0]->pmin}}" name="pm_in" {{$pminavail}}/>
                                </div>
                                <div class="col-md-2">
                                    <label>PM OUT</label>
                                    <input id="timepickerpmout"  employeeid="{{$profileinfoid}}" class="timepick form-control" value="{{$timeschedule[0]->pmout}}" name="pm_out" {{$pmoutavail}}/>
                                </div>
                            @endif
                            <div class="col-md-2">
                                &nbsp;
                            </div>
                            <div class="col-md-2" style="display: grid;">
                                <br/>
                                <button type="button" class="btn btn-success btn-sm float-right" id="updatecustomtime">Save Changes</button>
                            </div>
                        </div> --}}
                    {{-- @endif --}}
                </div>
            </div>
        </div>
    <script>
        $(document).ready(function(){
            $('#updatedesignation').unbind().click(function(){
                var selecteddesignation = $('#selecteddesignation').val();
                var academicprograms = [];
                if($('input[name="academicprogram"]:checked').length>0)
                {
                    $('input[name="academicprogram"]:checked').each(function(){
                        academicprograms.push($(this).val());
                    })
                }
                $.ajax({
                        url: '/hr/employees/profile/tabothers/updatedesignation',
                        type:"GET",
                        dataType:"json",
                        data:{
                            employeeid:'{{$profileinfoid}}',
                            selecteddesignation: selecteddesignation,
                            academicprograms: JSON.stringify(academicprograms),
                        },
                        complete:function() {
                            toastr.success('Updated successfully!','DESIGNATION',)
                        }
                    });
            })
            $('#updatedepartment').unbind().click(function(){
                var selecteddepartment = $('#selecteddepartment').val();
                $.ajax({
                        url: '/hr/employees/profile/tabothers/updatedepartment',
                        type:"GET",
                        dataType:"json",
                        data:{
                            employeeid:'{{$profileinfoid}}',
                            selecteddepartment: selecteddepartment
                        },
                        complete:function() {
                            toastr.success('Updated successfully!','DEPARTMENT',)
                        }
                    });
            })
            $('input[name="workshift"]').on('click', function(){
                if($(this).prop('checked') == true)
                {
                    if($(this).val() == 0)
                    {
                        $('#timepickeramin').attr('disabled',false)
                        $('#timepickeramout').attr('disabled',false)
                        $('#timepickerpmin').attr('disabled',false)
                        $('#timepickerpmout').attr('disabled',false)
                    }
                    else if($(this).val() == 1)
                    {
                        $('#timepickeramin').attr('disabled',false)
                        $('#timepickeramout').attr('disabled',false)
                        $('#timepickerpmin').attr('disabled',true)
                        $('#timepickerpmout').attr('disabled',true)
                    }
                    else if($(this).val() == 2)
                    {
                        $('#timepickeramin').attr('disabled',true)
                        $('#timepickeramout').attr('disabled',true)
                        $('#timepickerpmin').attr('disabled',false)
                        $('#timepickerpmout').attr('disabled',false)
                    }
                    $.ajax({
                        url: '/hr/employees/profile/tabothers/updateworkshift',
                        type:"GET",
                        dataType:"json",
                        data:{
                            employeeid:'{{$profileinfoid}}',
                            shiftid:$(this).val()
                        },
                        complete:function() {
                            toastr.success('Updated successfully!','WORK SHIFT',)
                        }
                    });
                }
            })
            
            // ------------------------------------------------------------------------------------ CUSTOM TIMESCHED
            $('#timepickeramin').timepicker({ modal: false, header: false, footer: false, format: 'HH:MM'});
            $('#timepickeramout').timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});
            $('#timepickerpmin').timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});
            $('#timepickerpmout').timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});
            
            $('#timepickeramin').on('change', function(){
                var timepickeramin = $(this).val().split(':');
                if(timepickeramin[0] == '00'){
                    $(this).val('12:'+timepickeramin[1])
                }
            })
            $('#timepickeramout').on('change', function(){
                var timepickeramout = $(this).val().split(':');
                if(timepickeramout[0] == '00'){
                    $(this).val('12:'+timepickeramout[1])
                }
            })
            $('#timepickerpmin').on('change', function(){
                var timepickerpmin = $(this).val().split(':');
                if(timepickerpmin[0] == '00'){
                    $(this).val('12:'+timepickerpmin[1])
                }
            })
            $('#timepickerpmout').on('change', function(){
                var timepickerpmout = $(this).val().split(':');
                if(timepickerpmout[0] == '00'){
                    $(this).val('12:'+timepickerpmout[1])
                }
            })
            $('#updatecustomtime').on('click', function(){
                var amin    = $('#timepickeramin').val();
                var amout   = $('#timepickeramout').val();
                var pmin    = $('#timepickerpmin').val();
                var pmout   = $('#timepickerpmout').val();
                $.ajax({
                    url: '/hr/employees/profile/tabothers/updatecustomtimesched',
                    type:"GET",
                    dataType:"json",
                    data:{
                        employeeid:'{{$profileinfoid}}',
                        am_in:amin,
                        am_out:amout,
                        pm_in:pmin,
                        pm_out:pmout
                    },
                    complete:function() {
                        toastr.success('Updated successfully!','WORK SHIFT',)
                    }
                });
            })
            $("#attendancebased").each(function(){
            if('{{$attendancebased}}' == 0)
            {
                $(this).bootstrapSwitch('state', false);
            }else{
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            }
            });
            $('#attendancebased').on('switchChange.bootstrapSwitch',function () {
            var check = $('.bootstrap-switch-on');
                if (check.length > 0) {
                    var attendancebasedstatus = '0';
                } else {
                    var attendancebasedstatus = '1';
                }
                $.ajax({
                    // url: '/hr/employeeotherstab/updateattendancebasedsalary',
                        url: '/hr/employees/profile/tabothers/updateattendancebasedsalary',
                    type:"GET",
                    dataType:"json",
                    data:{
                        employeeid:'{{$profileinfoid}}',
                        attendancebasedstatus:attendancebasedstatus
                    },
                    complete:function() {
                        toastr.success('Updated successfully!','Salary based attendance',)
                    }
                });
            });
            $('#selecteddesignation').on('change', function(){
                $('#academicprogram').empty();
                if($(this)[0].selectedOptions[0].text == 'TEACHER' || $(this)[0].selectedOptions[0].text == 'PRINCIPAL'){
                    $.ajax({
                        url: '/hr/employees/getacademicprogram',
                        type:"GET",
                        dataType:"json",
                        success:function(data) {
                            $.each(data, function(key, value){
                                $('#academicprogram').append(
                                    '<div class="icheck-primary d-inline col-md-6 m-0">'+
                                        '<input type="checkbox" id="checkboxPrimary'+value.id+'" name="academicprogram" value="'+value.id+'" checked="">'+
                                        '<label for="checkboxPrimary'+value.id+'">'+
                                        value.progname+
                                        '</label>'+
                                    '</div>'+
                                    '<br>'
                                );
                            })
                        }
                    });
                }
            });
        })
    </script>