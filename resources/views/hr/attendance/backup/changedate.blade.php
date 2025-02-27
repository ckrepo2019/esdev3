
            @foreach($attendance as $att)
            <div class="card card-each-emp" style="border: none !important;box-shadow: none !important;" data-string="{{$att->employeeinfo->lastname}}, {{$att->employeeinfo->firstname}}  {{$att->employeeinfo->suffix}} {{$att->employeeinfo->utype}}<">
                <div class="card-body p-0" >
                    <div class="row" id="{{$att->employeeinfo->id}}" usertypeid="{{$att->employeeinfo->usertypeid}}">
                        @php
                        
                            if($att->attendance->taphistorystatus== 0)
                            {
                                $attrdisabled = '';
                            }else{
                                $attrdisabled = 'disabled';
                            }
                        @endphp
                        <div class="col-md-3">
                            {{-- @php
                                $number = rand(1,3);
                                if(strtoupper($att->employeeinfo->gender) == 'FEMALE'){
                                    $avatar = 'avatar/T(F) '.$number.'.png';
                                }
                                elseif(strtoupper($att->employeeinfo->gender) == 'MALE'){
                                    $avatar = 'avatar/T(M) '.$number.'.png';
                                }else{
                                    
                                    $avatar = 'assets/images/avatars/unknown.png';
                                }
                            @endphp --}}
                            {{-- <a href="#" class="avatar"> --}}
                                    {{-- <img src="{{asset($att->employeeinfo->picurl)}}" alt="" onerror="this.onerror = null, this.src='{{asset($avatar)}}'"/> --}}
                                    <a href="/hr/employees/profile/index?employeeid={{$att->employeeinfo->id}}">
                                        <strong>{{ucwords(strtolower($att->employeeinfo->lastname))}}</strong>, {{ucwords(strtolower($att->employeeinfo->firstname))}} {{ucwords(strtolower($att->employeeinfo->middlename))}} {{$att->employeeinfo->suffix}} <br/><span class="text-muted pl-5"><sup>{{$att->employeeinfo->utype}}</sup></span>
                                    </a>
                                {{-- </a> --}}
                        </div>
                        <div class="col-md-2">
                            {{-- <label>AM IN</label> --}}
                            
                            @if($att->attendance->taphistorystatus == 0)
                                <input id="timepickeramin{{$att->employeeinfo->id}}"  datevalue="{{$currentdate}}" value="{{$att->attendance->in_am}}" class="timepick timepickerinputs" name="am_in" />
                                <script>
                                    $(document).ready(function(){
                                        $('#timepickeramin{{$att->employeeinfo->id}}').timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});
                                        $('#timepickeramin{{$att->employeeinfo->id}}').on('change', function(){
                                            $(this).closest('.row').find('button').attr('disabled',false);
                                            var timepickeramin = $(this).val().split(':');
                                            if(timepickeramin[0] == '00'){
                                                $(this).val('12:'+timepickeramin[1])
                                            }
                                        })
                                    })
                                </script>
                            @else
                                <input id="timepickeramin{{$att->employeeinfo->id}}"  datevalue="{{$currentdate}}" value="{{$att->attendance->in_am}}" class="timepick timepickerinputs form-control form-control-sm" name="am_in" disabled/>
                            @endif
                            {{-- <script>
                                $(document).ready(function(){
                                    if('{{$att->attendance->taphistorystatus}}' == 0)
                                    {
                                        $('#timepickeramin{{$att->employeeinfo->id}}').timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});
                                        $('#timepickeramin{{$att->employeeinfo->id}}').on('change', function(){
                                            $(this).closest('.row').find('button').attr('disabled',false);
                                            var timepickeramin = $(this).val().split(':');
                                            if(timepickeramin[0] == '00'){
                                                $(this).val('12:'+timepickeramin[1])
                                            }
                                        })
                                    }else{
                                        $('#timepickeramin{{$att->employeeinfo->id}}').timepicker({ modal: false});
                                        $('#timepickeramin{{$att->employeeinfo->id}}').timepicker('disable');
                                        $('.clock').on('click', function(e){
                                            e.preventDefault();
                                        })
                                    }
                                })
                            </script> --}}
                        </div>
                        <div class="col-md-2">
                            {{-- <label>AM OUT</label> --}}
                            @if($att->attendance->taphistorystatus == 0)
                                <input id="timepickeramout{{$att->employeeinfo->id}}"  datevalue="{{$currentdate}}" value="{{$att->attendance->out_am}}" class="timepick timepickerinputs" name="am_out"/>
                                <script>
                                    $(document).ready(function(){
                                        if('{{$att->attendance->taphistorystatus}}' == 0)
                                        {
                                            $('#timepickeramout{{$att->employeeinfo->id}}').timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});
                                            $('#timepickeramout{{$att->employeeinfo->id}}').on('change', function(){
                                                $(this).closest('.row').find('button').attr('disabled',false);
                                                var timepickeramout = $(this).val().split(':');
                                                if(timepickeramout[0] == '00'){
                                                    $(this).val('12:'+timepickeramout[1])
                                                }
                                            })
                                        }else{

                                        }
                                    })
                                </script>
                            @else
                                <input id="timepickeramout{{$att->employeeinfo->id}}"  datevalue="{{$currentdate}}" value="{{$att->attendance->out_am}}" class="timepick timepickerinputs form-control form-control-sm" name="am_out" disabled/>
                            @endif
                        </div>
                        <div class="col-md-2">
                            {{-- <label>PM IN</label> --}}
                            @if($att->attendance->taphistorystatus == 0)
                                <input id="timepickerpmin{{$att->employeeinfo->id}}"   datevalue="{{$currentdate}}"value="{{$att->attendance->in_pm}}" class="timepick timepickerinputs" name="pm_in"/>
                                <script>
                                    $(document).ready(function(){
                                        if('{{$att->attendance->taphistorystatus}}' == 0)
                                        {
                                            $('#timepickerpmin{{$att->employeeinfo->id}}').timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});
                                            $('#timepickerpmin{{$att->employeeinfo->id}}').on('change', function(){
                                                $(this).closest('.row').find('button').attr('disabled',false);
                                                var timepickerpmin = $(this).val().split(':');
                                                if(timepickerpmin[0] == '00'){
                                                    $(this).val('12:'+timepickerpmin[1])
                                                }
                                            })
                                        }else{
                                            $('.gj-modal').css('display','none')
                                        }
                                    })
                                </script>
                            @else
                                <input id="timepickerpmin{{$att->employeeinfo->id}}"  datevalue="{{$currentdate}}" value="{{$att->attendance->in_pm}}" class="timepick timepickerinputs form-control form-control-sm" name="pm_in" disabled/>
                            @endif
                        </div>
                        <div class="col-md-2">
                            {{-- <label>PM OUT</label> --}}
                            @if($att->attendance->taphistorystatus == 0)
                                <input id="timepickerpmout{{$att->employeeinfo->id}}"   datevalue="{{$currentdate}}"value="{{$att->attendance->out_pm}}" class="timepick timepickerinputs" name="pm_out"/>
                                <script>
                                    $(document).ready(function(){
                                        if('{{$att->attendance->taphistorystatus}}' == 0)
                                        {
                                            $('#timepickerpmout{{$att->employeeinfo->id}}').timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});
                                            $('#timepickerpmout{{$att->employeeinfo->id}}').on('change', function(){
                                                $(this).closest('.row').find('button').attr('disabled',false);
                                                var timepickerpmout = $(this).val().split(':');
                                                if(timepickerpmout[0] == '00'){
                                                    $(this).val('12:'+timepickerpmout[1])
                                                }
                                            })
                                        }else{
                                            $('.gj-modal').css('display','none')

                                        }
                                    })
                                </script>
                            @else
                                <input id="timepickerpmout{{$att->employeeinfo->id}}"  datevalue="{{$currentdate}}" value="{{$att->attendance->out_pm}}" class="timepick timepickerinputs form-control form-control-sm" name="pm_out" disabled/>
                            @endif
                        </div>
                        <div class="col-md-1 col-sm-1">
                            <button type="button" class="btn btn-sm btn-default {{--updatetimeatt--}} timelogs"  id="timelogbutton{{$att->employeeinfo->id}}">
                                {{-- <i class="fa fa-clock"></i> --}}
                                Logs</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach