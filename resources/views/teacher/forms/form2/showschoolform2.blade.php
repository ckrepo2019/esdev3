@if(count($setup) == 0)
    <div class="col-md-12">
        <div class="alert alert-warning alert-dismissible">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
            Warning alert preview. No setup found! <br/>
            <button id="btn-create-setup" class="btn btn-default"><i class="fa fa-plus"></i> Create Setup</button>
        </div>
    </div>
    <script>
        $('#btn-view-setup').prop('hidden',true);
        $('#btn-reselect-setup').prop('hidden',true);
        $('#btn-advisoryatt').prop('hidden',true);
        $('#btn-printpdf').prop('hidden', true);
        $('#btn-printexcel').prop('hidden', true);
        
        $('#btn-create-setup').on('click', function(){
                $('#show-calendar').modal('show')

                $.ajax({
                    url: '/forms/form2',
                    type: 'GET',
                    data: {
                        action                  : 'getcalendar',
                        syid                 : '{{$syid}}',
                        levelid                 : '{{$levelid}}',
                        semid                   : '{{$semid}}',
                        sectionid               : $('#sectionid').attr('data-id'),
                        selectedyear            : $('#selectedyear').val(),
                        selectedmonth           : $('#selectedmonth').val()
                    },
                    success:function(data){
                        $('#calendar-container').empty();
                        $('#calendar-container').append(data)
                        $('.active-date').on('click', function(){
                            $('#selected-dates-container').empty()
                            var idx = $.inArray($(this).attr('data-id'), selecteddates);
                            
                            if (idx == -1) {
                                
                                @if($setup_numdays)
                                    if(selecteddates.length < '{{$setup_numdays->days}}')
                                    {
                                        selecteddates.push($(this).attr('data-id'));
                                        $(this).addClass('btn-success')
                                    }else{
                                        
                                        toastr.warning('Date limit reached!')
                                    }
                                @else
                                        selecteddates.push($(this).attr('data-id'));
                                        $(this).addClass('btn-success')
                                @endif
                            } else {
                                selecteddates.splice(idx, 1);
                                $(this).removeClass('btn-success')
                            }
                        })
                    }
                })
            })
    </script>
@else
    @php
        $countmale = 1;   
        $countfemale = 1;   
        
        foreach($activedays as $totalatt)
        {

            $presentmale = 0;
    $absentmale = 0;
    $tardymale = 0;

    $presentfemale = 0;
    $absentfemale = 0;
    $tardyfemale = 0;

    $present = 0;
    $absent = 0;
    $tardy = 0;


    foreach($attendance[0] as $att)
    {

        if(strtolower($att->gender) == 'male')
        {
            $todayatt = collect($att->attendance)->where('day', $totalatt->daynum)->first();
            if($todayatt)
            {
                if($todayatt->status ==2)
                {
                    $presentmale+=1;
                    $present+=1;
                }
                elseif($todayatt->status == 1)
                {
                    $absentmale+=1;
                    $absent+=1;
                }elseif($todayatt->status == 3 || $todayatt->status == 4)
                {
                    $tardymale+=1;
                    $tardy+=1;
                    $presentmale+=1;
                    $present+=1;
                }
            }
        }
        if(strtolower($att->gender) == 'female')
        {
            $todayatt = collect($att->attendance)->where('day', $totalatt->daynum)->first();
            if($todayatt)
            {
                if($todayatt->status ==2)
                {
                    $presentfemale+=1;
                    $present+=1;
                }
                elseif($todayatt->status == 1)
                {
                    $absentfemale+=1;
                    $absent+=1;
                }elseif($todayatt->status == 3 || $todayatt->status == 4)
                {
                    $tardyfemale+=1;
                    $tardy+=1;
                    $presentfemale+=1;
                    $present+=1;
                }
            }
        }
    }
    $totalatt->presentmale = $presentmale;
    $totalatt->absentmale = $absentmale;
    $totalatt->tardymale = $tardymale;
    $totalatt->presentfemale = $presentfemale;
    $totalatt->absentfemale = $absentfemale;
    $totalatt->tardyfemale = $tardyfemale;
    $totalatt->present = $present;
    $totalatt->absent = $absent;
    $totalatt->tardy = $tardy;
        }
    @endphp
    <div class="col-md-12 tableFixHead" style="height: 600px; overflow: scroll;">
        <table class="table table-bordered table-hover  table-head-fixed" id="studentstable">
            <thead class="text-center">
                <tr>
                    <th colspan="2" style="width: 30% !important">Learner's Name</th>
                    @foreach($activedays as $activeday)
                        <th>{{$activeday->daynum}}<br/>{{$activeday->daystr}}</th>
                    @endforeach
                    <th >ABSENT</th>
                    <th >TARDY</th>
                    <th data-toggle="tooltip" data-placement="left" title="(If DROPPED OUT, state reason, please refer to legend number 2. If TRANSFERRED IN/OUT, write the name of School.) " >Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">
                        MALE
                    </td>
                    <td colspan="{{count($activedays)}}">
                        &nbsp;
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @if(count($attendance[0])>0)
                    @foreach($attendance[0] as $student)
                        @if(strtolower($student->gender) == 'male')
                            @php
                                $hisabsent = 0;
                                $histardy = 0;
                            @endphp
                            
                            <tr @if($student->display == 0) style="color: red;" @endif>
                                <td style="width: 2% !important;">
                                    {{$countmale}}
                                </td>
                                <td @if($student->display == 0) style="color: red;" @endif>
                                    @if($student->display == 0) <del>@endif
                                    @if($student->middlename != null)
                                    {{$student->lastname}},  {{$student->firstname}}  {{$student->middlename[0]}}.  {{$student->suffix}}
                                    @else
                                    {{$student->lastname}},  {{$student->firstname}}  {{$student->suffix}}
                                    @endif
                                    @if($student->display == 0) </del>@endif
                                    @if($student->display == 0)
                                    @if($student->studstatus == 3 ) D/O @elseif($student->studstatus == 5) T/O @endif
                                    @endif
                                </td>
                                @foreach($activedays as $activeday)
                                    @foreach($student->attendance as $att)
                                    
                                        @if($att->day == $activeday->daynum)
                                        <td>
                                            {{-- //2 = present; 1 = absent; 3 = late; 4 = cc --}}
                                            @if($att->status == 1)
                                                @php
                                                    $hisabsent+=1;
                                                @endphp
                                                <span class="right badge badge-danger">ABSENT</span>
                                            @elseif($att->status == 2)
                                                <span class="right badge badge-success">PRESENT</span>
                                            @elseif($att->status == 3)
                                                @php
                                                    $histardy+=1;
                                                @endphp
                                                <span class="right badge badge-warning">LATE</span>
                                            @elseif($att->status == 4)
                                                @php
                                                    $histardy+=1;
                                                @endphp
                                                <span class="right badge badge-info">CC</span>
                                            @elseif($att->status == 10)
                                                @php
                                                    $hisabsent+=0.5;
                                                @endphp
                                                <span class="right badge badge-info">AAM</span>
                                            @elseif($att->status == 11)
                                                @php
                                                    $hisabsent+=0.5;
                                                @endphp
                                                <span class="right badge badge-info">APM</span>
                                            @elseif($att->status == 30)
                                                @php
                                                    $histardy+=1;
                                                @endphp
                                                <span class="right badge badge-warning">LAM</span>
                                            @elseif($att->status == 31)
                                                @php
                                                    $histardy+=1;
                                                @endphp
                                                <span class="right badge badge-warning">LPM</span>
                                            @endif
                                        </td>
                                        @endif
                                    @endforeach
                                @endforeach
                                <td class="text-center">
                                    @if($hisabsent>0)
                                        {{$hisabsent}}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($histardy>0)
                                        {{$histardy}}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($locksf2 == 0)
                                    <button type="button" class="btn btn-default student-remarks" data-id="{{$student->id}}" data-toggle="tooltip" data-placement="left" title="@if($student->remarks!=""){{$student->remarks}}@else Add remarks @endif"><i class="fa fa-comment-alt fa-lg	text-info"></i></button>
                                    @endif
                                </td>
                            </tr>
                            @php
                                $countmale+=1;   
                            @endphp
                        @endif
                    @endforeach
                @endif
                <tr>
                    <td colspan="2">
                        @php
                            $totalattendmale = 0;
                        @endphp
                        MALE | TOTAL Per Day 
                    </td>
                    @if(count($studentstotalperday) == 0)
                    @foreach($activedays as $activeday)
                    <td></td>
                    @endforeach
                    @else
                        @foreach($studentstotalperday as $maletotal)
                            <td>{{$maletotal->withrecordsmale}}</td>
                            @php
                                $totalattendmale += $maletotal->withrecordsmale; 
                            @endphp
                        @endforeach
                    @endif
                    <td class="text-center">{{collect($activedays)->sum('absentmale')}}</td>
                    <td class="text-center">{{collect($activedays)->sum('tardymale')}}</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                        FEMALE
                    </td>
                    <td colspan="{{count($activedays)}}">
                        &nbsp;
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @if(count($attendance[0])>0)
                    @foreach($attendance[0] as $student)
                        @if(strtolower($student->gender) == 'female')
                            @php
                                $herabsent = 0;
                                $hertardy = 0;
                            @endphp
                            <tr @if($student->display == 0) style="color: red;"@endif>
                                <td style="width: 2% !important;">
                                    {{$countfemale}}
                                </td>
                                <td @if($student->display == 0) style="color: red;"@endif>
                                    @if($student->display == 0) <del>@endif
                                    @if($student->middlename != null)
                                    {{$student->lastname}},  {{$student->firstname}}  {{$student->middlename[0]}}.  {{$student->suffix}}
                                    @else
                                    {{$student->lastname}},  {{$student->firstname}}  {{$student->suffix}}
                                    @endif
                                    @if($student->display == 0) </del>@endif
                                </td>
                                @foreach($activedays as $activeday)
                                    @foreach($student->attendance as $att)
                                    
                                        @if($att->day == $activeday->daynum)
                                        <td>
                                            {{-- //2 = present; 1 = absent; 3 = late; 4 = cc --}}
                                            @if($att->status == 1)
                                                @php
                                                    $herabsent+=1;
                                                @endphp
                                                <span class="right badge badge-danger">ABSENT</span>
                                            @elseif($att->status == 2)
                                                <span class="right badge badge-success">PRESENT</span>
                                            @elseif($att->status == 3)
                                                @php
                                                    $hertardy+=1;
                                                @endphp
                                                <span class="right badge badge-warning">LATE</span>
                                            @elseif($att->status == 4)
                                                @php
                                                    $hertardy+=1;
                                                @endphp
                                                <span class="right badge badge-info">CC</span>
                                            @elseif($att->status == 10)
                                                @php
                                                    $herabsent+=0.5;
                                                @endphp
                                                <span class="right badge badge-info">AAM</span>
                                            @elseif($att->status == 11)
                                                @php
                                                    $herabsent+=0.5;
                                                @endphp
                                                <span class="right badge badge-info">APM</span>
                                            @elseif($att->status == 30)
                                                @php
                                                    $hertardy+=0.5;
                                                @endphp
                                                <span class="right badge badge-warning">LAM</span>
                                            @elseif($att->status == 31)
                                                @php
                                                    $hertardy+=0.5;
                                                @endphp
                                                <span class="right badge badge-warning">LPM</span>
                                            @endif
                                        </td>
                                        @endif
                                    @endforeach
                                @endforeach
                                <td class="text-center">
                                    @if($herabsent>0)
                                        {{$herabsent}}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($hertardy>0)
                                        {{$hertardy}}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($locksf2 == 0)
                                    <button type="button" class="btn btn-default student-remarks" data-id="{{$student->id}}" data-toggle="tooltip" data-placement="left" title="@if($student->remarks!=""){{$student->remarks}}@else Add remarks @endif"><i class="fa fa-comment-alt fa-lg	text-info"></i></button>
                                    @endif
                                </td>
                            </tr>
                            @php
                                $countfemale+=1;   
                            @endphp
                        @endif
                    @endforeach
                @endif
                <tr>
                    <td colspan="2">
                        @php
                            $totalattendfemale = 0;
                        @endphp
                        FEMALE | TOTAL Per Day 
                    </td>
                    @if(count($studentstotalperday) == 0)
                    @foreach($activedays as $activeday)
                    <td></td>
                    @endforeach
                    @else
                    @foreach($studentstotalperday as $femaletotal)
                        <td>{{$femaletotal->withrecordsfemale}}</td>
                        @php
                            $totalattendfemale += $femaletotal->withrecordsfemale; 
                        @endphp
                    @endforeach
                    @endif
                    <td class="text-center">{{collect($activedays)->sum('absentfemale')}}</td>
                    <td class="text-center">{{collect($activedays)->sum('tardyfemale')}}</td>
                    <td></td>
                </tr>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2">
                    COMBINED | TOTAL Per Day 
                </td>
                @if(count($studentstotalperday) == 0)
                @foreach($activedays as $activeday)
                <td></td>
                @endforeach
                @else
                    @foreach($activedays as $studenttotal)
                        <td>{{$studenttotal->present}}</td>
                    @endforeach
                @endif
                <td class="text-center">{{collect($activedays)->sum('absent')}}</td>
                <td class="text-center">{{collect($activedays)->sum('tardy')}}</td>
                <td></td>
              </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <small><strong>GUIDELINES:</strong></small>
                <small>
                    <ol style="font-size: 11px; padding-left: 10px;">
                        <li> The attendance shall be accomplished daily. Refer to the codes for checking learners' attendance.</li>
                        <li> Dates shall be written in the preceding columns beside Learner's Name.</li>
                        <li>
                            To compute the following:
                            <br>
                            <div class="row" style="">
                                <div class="col-md-5"  style="">
                                    a. Percentage of Enrollment = 
                                </div>
                                <div class="col-md-5">
                                    <div class="row text-center" style="border-bottom: 1px solid black;"><center>Registered Learner as of End of the Month</center></div> 
                                    <div class="row" style=""><center>Enrollment as of 1st Friday of the schoolyear</center></div> 
                                </div>
                                <div class="col-md-2" style=" vertical-align:middle !important;text-align:center; ">
                                     x100
                                </div>
                            </div>
                            <div class="row" style="">
                                <div class="col-md-5"  style="">
                                    b. Average Daily Attendance = 
                                </div>
                                <div class="col-md-5" style="">
                                    <div class="row" style="border-bottom: 1px solid black;"><center>Total Daily Attendance</center></div> 
                                    <div class="row" style=""><center>Number of School Days in Reporting Month</center></div> 
                                </div>
                                <div class="col-md-2" style=" vertical-align:middle !important;text-align:center;">
                                    &nbsp;
                                </div>
                            </div>
                            <div class="row" style="">
                                <div class="col-md-5"  style="">
                                    c. Pecentage of Attendance for the month = 
                                </div>
                                <div class="col-md-5" style="text-align:center;">
                                    <div class="row text-center" style="border-bottom: 1px solid black;"><center>Average Daily Attendance</center></div> 
                                    <div class="row" style=""><center>Registered Learner as of End of the Month</center></div> 
                                </div>
                                <div class="col-md-2" style=" vertical-align:middle !important;text-align:center;">
                                        x100
                                </div>
                            </div>
                            <br>
                        </li>
                        <li> Every End of the month, the class adviser will submit this form to the office of the principal for recording of summary table into the School Form 4. Once signed by the principal, this form should be returned to the adviser.</li>
                        <li> The adviser will extend neccessary intervention including but not limited to home visitation to learner/s that committed 5 consecutive days of absences or those with potentials of dropping out.</li>
                        <li> Attendance performance of learner is expected to reflect in Form 137 and Form 138 every grading period<br> * Beginning of School Year cut-off report is every 1st Friday of School Calendar Days</li>
                    </ol>
                </small>
            </div>
            <div class="col-md-6">
                <div class="col-md-12" style="border-bottom: 1px solid black;">
                    <small>
                        <strong>1. CODES FOR CHECKING ATTENDANCE</strong>
                    </small>
                </div>
                <div class="col-md-12" style="font-size: 11px; padding-top:5px; padding-bottom:5px;">
                        <strong>blank</strong> - Present; (<strong>x</strong>)- Absent; Tardy (<strong>half shaded</strong> = Upper for Late Comer, Lower for Cutting Classes)
                </div>
                <div class="col-md-12" >
                    <span>
                        <small>
                            <strong>2. REASONS/CAUSES OF DROP-OUTS</strong>
                        </small>
                    </span>
                    <br>
                    <div style="padding-bottom:3px;">
                        <span style="font-size: 11px;">
                            <strong>a. Domestic-Related Factors</strong>
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            a.1. Had to take care of siblings
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            a.2. Early marriage/pregnancy
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            a.3. Parents' attitude toward schooling
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            a.4. Family problems
                        </span>
                    </div>
                    <div style="padding-bottom:3px;">
                        <span style="font-size: 11px;">
                            <strong>b. Individual-Related Factors</strong>
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.1. Illness
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.2. Overage
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.3. Death
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.4. Drug Abuse
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.5. Poor academic performance
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.6. Lack of interest/Distractions
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            b.7. Hunger/Malnutrition
                        </span>
                    </div>
                    <div style="padding-bottom:3px;">
                        <span style="font-size: 11px;">
                            <strong>c. School-Related Factors</strong>
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            c.1. Teacher Factor
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            c.2. Physical condition of classroom
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            c.3. Peer influence
                        </span>
                    </div>
                    <div style="padding-bottom:3px;">
                        <span style="font-size: 11px;">
                            <strong>d. Geographic/Environmental</strong>
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            d.1. Distance between home and school
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            d.2. Armed conflict (incl. Tribal wars & clan feuds)
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            d.3. Calamities/Disasters
                        </span>
                    </div>
                    <div style="padding-bottom:3px;">
                        <span style="font-size: 11px;">
                            <strong>e. Financial-Related</strong>
                        </span>
                        <br>
                        <span style="font-size: 11px;">
                            e.1. Child labor, work
                        </span>
                    </div>
                    <div style="padding-bottom:3px;">
                        <span style="font-size: 11px;">
                            <strong>f. Others</strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <table class="table table-bordered" style="font-size: 11px;">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 70%;">
                        No. of Days of
                        Classes: {{count($activedays)}}
                    </th>
                    <th colspan="3">Summary for the Month</th>
                </tr>
                <tr>
                    <th>M</th>
                    <th>F</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                {{-- enrollmentasof_male
enrollmentasof_female --}}
                <tr>
                    <td>
                        <button type="button" class="btn btn-sm btn-block btn-warning p-1 m-0" id="selectenrollmentmonth" style="font-size: 12px;">Enrollment as of (1st Friday of {{date("F", mktime(0, 0, 0, $enrollmonth, 10))}})</button>
                    </td>
                    <td>{{$summarydetails->enrollmentasof_male}}</td>
                    <td>{{$summarydetails->enrollmentasof_female}}</td>
                    <td>
                        {{$summarydetails->enrollmentasof_male+$summarydetails->enrollmentasof_female}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <em>Late Enrollment <strong>during the month</strong></em>
                    </td>
                    <td>{{$summarydetails->lateenrolled_male}}</td>
                    <td>{{$summarydetails->lateenrolled_female}}</td>
                    <td>
                        {{$summarydetails->lateenrolled_male+$summarydetails->lateenrolled_female}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <em>Registered Learner as of <strong>end of the month</strong></em>
                    </td>
                    <td>{{$summarydetails->registered_male}}</td>
                    <td>{{$summarydetails->registered_female}}</td>
                    <td>{{$summarydetails->registered_total}}</td>
                </tr>
                <tr>
                    <td>
                        <em>Percentage of Enrolment as of <strong>end of the month</strong></em>
                    </td>
                    <td>{{$summarydetails->enrollmentpercentage_male}}</td>
                    <td>{{$summarydetails->enrollmentpercentage_female}}</td>
                    <td>{{$summarydetails->enrollmentpercentage_total}}</td>
                </tr>
                <tr>
                    <td>
                        <em>Average Daily Attendance</em>
                    </td>
                    <td>
                        @php
                            if($totalattendmale <=0 || count($activedays) == 0)
                            {
                                $avedailyatt_male = 0;
                            }else{
                                $avedailyatt_male = round(($totalattendmale/count($activedays)),2);
                            }
                        @endphp
                        {{$avedailyatt_male}}
                    </td>
                    <td>
                        @php
                            if($totalattendfemale <=0 || count($activedays) == 0)
                            {
                                $avedailyatt_female = 0;
                            }else{
                                $avedailyatt_female = round(($totalattendfemale/count($activedays)),2);
                            }
                        @endphp
                        {{$avedailyatt_female}} 
                    </td>
                    <td>
                        {{$avedailyatt_male+$avedailyatt_female}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <em>Percentage of Attendance for the month</em>
                    </td>
                    @php
                        if($avedailyatt_male>0 && $summarydetails->registered_male>0)
                        {
                            if(number_format(($avedailyatt_male/$summarydetails->registered_male)*100,1)>100)
                            {
                                $pammale = 100;
                            }
                            else{
                                $pammale = number_format(($avedailyatt_male/$summarydetails->registered_male)*100,1);
                            }
                        }else{
                                $pammale = 0;
                        }
                    @endphp
                    <td id="pam_male" data-id="@if($avedailyatt_male > 0 && $summarydetails->registered_male > 0){{$pammale}}@else 0 @endif">
                        @if($avedailyatt_male > 0 && $summarydetails->registered_male > 0)
                            {{$pammale}}
                        @else
                        0
                        @endif
                        
                    </td>
                    @php
                        if($avedailyatt_female>0 || $summarydetails->registered_female>0)
                        {
                            if(round(($avedailyatt_female/$summarydetails->registered_female)*100)>100)
                            {
                                $pamfemale = 100;
                            }
                            else{
                                $pamfemale = number_format(($avedailyatt_female/$summarydetails->registered_female)*100,1);
                            }
                        }else{
                            $pamfemale = 0;
                        }
                    @endphp
                    <td id="pam_female" data-id="@if($avedailyatt_female > 0 && $summarydetails->registered_female > 0){{$pamfemale}}@else 0 @endif">
                        @if($avedailyatt_female > 0 && $summarydetails->registered_female > 0)
                            {{$pamfemale}}
                        @else
                        0
                        @endif
                    </td>
                    @php
                        $avedailyatt_total = $avedailyatt_male + $avedailyatt_female;
                        $registeredtotal = $summarydetails->registered_male + $summarydetails->registered_female;
                        if($avedailyatt_total == 0 || $registeredtotal == 0)
                        {
                            $percentatttotal = 0;
                        }else{
                            $percentatttotal = number_format(($avedailyatt_total/$registeredtotal)*100,1);
                            if($percentatttotal>100)
                            {
                                $percentatttotal = 100;
                            }
                        }
                    @endphp
                    <td id="pam_total" data-id="{{$percentatttotal}}">
                        {{$percentatttotal}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <em>Number of students with 5 consecutive days of absences:</em>
                    </td>
                    <td>{{$summarydetails->countconsecutive_male}}</td>
                    <td>{{$summarydetails->countconsecutive_female}}</td>
                    <td>{{$summarydetails->countconsecutive_male+$summarydetails->countconsecutive_female}}</td>
                </tr>
                <tr>
                    <td>
                        {{-- <em><strong>Drop out</strong></em> --}}
                        <em><strong>NLPA</strong></em>
                    </td>
                    <td>{{$summarydetails->droppedout_male+$summarydetails->nlpamale}}</td>
                    <td>{{$summarydetails->droppedout_female+$summarydetails->nlpafemale}}</td>
                    <td>{{$summarydetails->droppedout_male+$summarydetails->nlpamale+$summarydetails->droppedout_female+$summarydetails->nlpafemale}}</td>
                </tr>
                <tr>
                    <td>
                        <em><strong>Transferred Out</strong></em>
                    </td>
                    <td>{{$summarydetails->transferredout_male}}</td>
                    <td>{{$summarydetails->transferredout_female}}</td>
                    <td>{{$summarydetails->transferredout_total}}</td>
                </tr>
                <tr>
                    <td>
                        <em><strong>Transferred In</strong></em>
                    </td>
                    <td>{{$summarydetails->transferredin_male}}</td>
                    <td>{{$summarydetails->transferredin_female}}</td>
                    <td>{{$summarydetails->transferredin_total}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
<div class="modal fade" id="enrollmonth" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Select Month</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="/forms/form2enrollmentmonth" method="get">
            <div class="modal-body">
                <label>Enrollment as of 1st Friday of</label>
                <select id="selectedenrollmentmonth" name="selectedenrollmentmonth" class="col-md-12" style="text-transform:uppercase;">
                    <option value="01" {{'01' == $enrollmonth ? 'selected' : ''}}>January</option>
                    <option value="02" {{'02' == $enrollmonth ? 'selected' : ''}}>February</option>
                    <option value="03" {{'03' == $enrollmonth ? 'selected' : ''}}>March</option>
                    <option value="04" {{'04' == $enrollmonth ? 'selected' : ''}}>April</option>
                    <option value="05" {{'05' == $enrollmonth ? 'selected' : ''}}>May</option>
                    <option value="06" {{'06' == $enrollmonth ? 'selected' : ''}}>June</option>
                    <option value="07" {{'07' == $enrollmonth ? 'selected' : ''}}>July</option>
                    <option value="08" {{'08' == $enrollmonth ? 'selected' : ''}}>August</option>
                    <option value="09" {{'09' == $enrollmonth ? 'selected' : ''}}>September</option>
                    <option value="10" {{'10' == $enrollmonth ? 'selected' : ''}}>October</option>
                    <option value="11" {{'11' == $enrollmonth ? 'selected' : ''}}>November</option>
                    <option value="12" {{'12' == $enrollmonth ? 'selected' : ''}}>December</option>
                </select>
            </div>
            <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" >Save changes</button>
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <script>
    var collecteddates = [];
    @if(count($setup)>0)
        @if(count($setup[0]->dates) > 0)
            @foreach($setup[0]->dates as $eachdate)
            collecteddates.push('{{$eachdate->daydate}}')
            @endforeach
        @endif
    @endif
    @if($locksf2 == 0)
        $('#btn-view-setup').removeAttr('hidden');
        $('#btn-reselect-setup').removeAttr('hidden');
        $('#btn-advisoryatt').removeAttr('hidden');
        $('#btn-locksf2').hide();
    @else
        $('#btn-locksf2').removeAttr('hidden');
    @endif
        $('#btn-printpdf').removeAttr('hidden');
        $('#btn-printexcel').removeAttr('hidden');
        $('[data-toggle="tooltip"]').tooltip();
        $('#btn-printpdf').show()
        $('#btn-printexcel').show()
        $('#selectenrollmentmonth').unbind().click(function(){
            $('#enrollmonth').modal('show')
        });
        $('.student-remarks').on('click', function(){
            var studentid = $(this).attr('data-id')
            $('#btn-submit-remarks').attr('studentid',studentid);
            $('#show-remark').modal('show')
                $.ajax({
                    url: '/forms/form2',
                    type: 'GET',
                    data: {
                        levelid                 : '{{$levelid}}',
                        sectionid               : '{{$sectionid}}',
                        studentid               : studentid,
                        selectedyear            : $('#selectedyear').val(),
                        selectedmonth           : $('#selectedmonth').val(),
                        action                  : 'getremarks'
                    },
                    success:function(data){
                        $('#text-area-remark').val(data)
                        // window.location.reload();
                    }
                })
        })
        $('#btn-submit-remarks').on('click', function(){
            var studentid = $(this).attr('studentid');
            $.ajax({
                url: '/forms/form2',
                type: 'GET',
                data: {
                    remarks                 : $('#text-area-remark').val(),
                    levelid                 : '{{$levelid}}',
                    sectionid               : '{{$sectionid}}',
                    studentid               : studentid,
                    selectedyear            : $('#selectedyear').val(),
                    selectedmonth           : $('#selectedmonth').val(),
                    action                  : 'updateremarks'
                },
                success:function(data){
                    // $('body').removeClass('modal-open');
                    // $('#show-remark').removeClass('show');
                    // $('#show-remark').removeAttr('style');
                    // $('#show-remark').css('display','none');
                    // $('.modal-backdrop').removeClass('show')
                    // $('.modal-backdrop').remove()
                    $('.student-remarks[data-id="'+studentid+'"]').attr('title',data)
                    $('.student-remarks[data-id="'+studentid+'"]').attr('data-original-title',data)
                    
                    // console.log($('.student-remarks[data-id="'+studentid+'"]'))
                    $('[data-toggle="tooltip"]').tooltip();
                    $('#closeremarks').click()
                }
            })
        })
        $('#btn-advisoryatt').on('click', function(e){
            $('#modal-view-advisoryatt').modal('show')
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })

            $.ajax({
                url: "/forms/form2?action=getattendance",
                type: "get",
                data: {
                    studentids              : JSON.stringify('{{$studentids}}'),
                    dates              : JSON.stringify(collecteddates),
                    selectedyear            : $('#selectedyear').val(),
                    selectedmonth           : $('#selectedmonth').val(),
                    syid                 : '{{$syid}}',
                    levelid                 : '{{$levelid}}',
                    sectionid               : '{{$sectionid}}',
                    strandid                : '{{$strandid}}',
                },
                success: function (data) {
                    $('#advisoryatt-container').empty()
                    $('#advisoryatt-container').append(data)

                }
            })
                .done(function(){
                    
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                });
            
   e.preventDefault();
        })
        
        $('#input-course').attr('readonly', true);
        $(document).on('dblclick','#input-course',function () {
            $(this).removeAttr('readonly');
            $(this).focus()
        })
        $(document).on('keypress', '#input-course',function (e) {
                if (e.which == 13) {
                    
                    $.ajax({
                        url: "/forms/form2?action=updatesetupdates",
                        type: "get",
                        data: {
                            newcourse: $(this).val(),
                            setupid: '{{$setup[0]->id}}'
                        },
                        success: function (data) {
                            if(data == 1)
                            {
                                toastr.success('Updated successfully!')
                                $('#input-course').attr('readonly', true);
                            }
                        }
                    });
                    return false;    //<---- Add this line
                }
            });
  </script>
@endif