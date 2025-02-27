@if(count($setup) == 0)
    <div class="col-md-12">
        <div class="alert alert-warning alert-dismissible">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
            Warning alert preview. No setup found! <br/>
        </div>
    </div>
@else
    @php
        $countmale = 1;   
        $countfemale = 1;   
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
                if($todayatt->combinedstatus === 1)
                {
                    $presentmale+=1;
                    $present+=1;
                }
                elseif($todayatt->combinedstatus === 'presentam')
                {
                    $presentmale+=0.5;
                    $present+=0.5;
                    $absentmale+=0.5;
                    $absent+=0.5;
                }
                elseif($todayatt->combinedstatus === 'presentpm')
                {
                    $presentmale+=0.5;
                    $present+=0.5;
                    $absentmale+=0.5;
                    $absent+=0.5;
                }
                elseif($todayatt->combinedstatus === 0)
                {
                    $absentmale+=1;
                    $absent+=1;
                }
                elseif($todayatt->combinedstatus === 'absentam')
                {
                    $absentmale+=0.5;
                    $presentmale+=0.5;
                    $present+=0.5;
                    $absent+=0.5;
                }
                elseif($todayatt->combinedstatus === 'absentpm')
                {
                    $absentmale+=0.5;
                    $presentmale+=0.5;
                    $present+=0.5;
                    $absent+=0.5;
                }elseif($todayatt->combinedstatus === 2 || $todayatt->combinedstatus === 3)
                {
                    $tardymale+=1;
                    $tardy+=1;
                    $presentmale+=1;
                    $present+=1;
                }
                elseif($todayatt->combinedstatus === 'lateam' || $todayatt->combinedstatus === 'latepm' || $todayatt->combinedstatus === 'ccam' || $todayatt->combinedstatus === 'ccpm')
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
                
                if($todayatt->combinedstatus === 1)
                {
                    $presentfemale+=1;
                    $present+=1;
                }
                elseif($todayatt->combinedstatus === 'presentam')
                {
                    $presentfemale+=0.5;
                    $present+=0.5;
                    $absentfemale+=0.5;
                    $absent+=0.5;
                }
                elseif($todayatt->combinedstatus === 'presentpm')
                {
                    $presentfemale+=0.5;
                    $present+=0.5;
                    $absentfemale+=0.5;
                    $absent+=0.5;
                }
                elseif($todayatt->combinedstatus === 0)
                {
                    $absentfemale+=1;
                    $absent+=1;
                }
                elseif($todayatt->combinedstatus === 'absentam')
                {
                    $absentfemale+=0.5;
                    $absent+=0.5;
                    $presentfemale+=0.5;
                    $present+=0.5;
                }
                elseif($todayatt->combinedstatus === 'absentpm')
                {
                    $absentfemale+=0.5;
                    $absent+=0.5;
                    $presentfemale+=0.5;
                    $present+=0.5;
                }elseif($todayatt->combinedstatus === 2 || $todayatt->combinedstatus === 3)
                {
                    $tardyfemale+=1;
                    $tardy+=1;
                    $presentfemale+=1;
                    $present+=1;
                }
                elseif($todayatt->combinedstatus === 'lateam' || $todayatt->combinedstatus === 'latepm' || $todayatt->combinedstatus === 'ccam' || $todayatt->combinedstatus === 'ccpm')
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
    {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')
    <div class="col-md-6 mb-2">
        <label>Course (for TVL only)</label>
        <input type="text" class="form-control form-control-sm" id="input-course" placeholder="Course" value="{{$tvlcourse}}">

    </div>
    @endif --}}
    <div class="row mb-2">
        <div class="col-md-12 text-right">
            <button type="button" id="btn-printpdf" class="btn btn-default"><i class="fa fa-file-pdf"></i> Export to PDF</button>
        </div>
    </div>
<div class="row">
    <div class="col-md-7 tableFixHead" style="height: 600px; overflow: scroll;">
        <table class="table table-bordered table-hover  table-head-fixed" id="studentstable">
            <thead class="text-center">
                <tr>
                    <th colspan="2" style="width: 30% !important">Learner's Name</th>
                    {{-- @foreach($activedays as $activeday)
                        <th>{{$activeday->daynum}}<br/>{{$activeday->daystr}}</th>
                    @endforeach --}}
                    <th >PRESENT</th>
                    <th >ABSENT</th>
                    <th >TARDY</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">
                        MALE
                    </td>
                    {{-- <td colspan="{{count($activedays)}}">
                        &nbsp;
                    </td> --}}
                    <td></td>
                    <td></td>
                    <td></td>
                    {{-- <td></td> --}}
                </tr>
                @if(count($attendance[0])>0)
                    @foreach($attendance[0] as $student)
                        @if(strtolower($student->gender) == 'male')
                            @php
                                $hisabsent = 0;
                                $histardy = 0;
                                $hispresent = 0;
                            @endphp
                            
                            @foreach($activedays as $activeday)
                            @foreach($student->attendance as $att)
                            
                                @if($att->day == $activeday->daynum)
                                {{-- <td> --}}
                                    {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi') --}}
                                        @if($att->combinedstatus === 0)
                                            @php
                                                $hisabsent+=1;
                                            @endphp
                                        @elseif($att->combinedstatus === 1)
                                        @php
                                            $hispresent+=1;
                                        @endphp
                                        @elseif($att->combinedstatus === 2)
                                            @php
                                                $histardy+=1;
                                            @endphp
                                        @elseif($att->combinedstatus === 3)
                                            @php
                                                $histardy+=1;
                                            @endphp
                                        @elseif($att->combinedstatus === 'presentam')
                                        @php
                                            $hispresent+=0.5;
                                                $hisabsent+=0.5;
                                        @endphp
                                        @elseif($att->combinedstatus === 'presentpm')
                                        @php
                                            $hispresent+=0.5;
                                                $hisabsent+=0.5;
                                        @endphp
                                        @elseif($att->combinedstatus === 'absentam')
                                            @php
                                                $hisabsent+=0.5;
                                                $hispresent+=0.5;
                                                //$histardy+=1;
                                            @endphp
                                        @elseif($att->combinedstatus ==='absentpm')
                                            @php
                                                $hisabsent+=0.5;
                                                $hispresent+=0.5;
                                                //$histardy+=1;
                                            @endphp
                                        @elseif($att->combinedstatus === 'lateam')
                                            @php
                                                $histardy+=1;
                                            @endphp
                                        @elseif($att->combinedstatus === 'latepm')
                                            @php
                                                $histardy+=1;
                                            @endphp
                                        @elseif($att->combinedstatus === 'ccam')
                                            @php
                                                $histardy+=1;
                                            @endphp
                                        @elseif($att->combinedstatus === 'ccpm')
                                            @php
                                                $histardy+=1;
                                            @endphp
                                        @endif
                                    {{-- @else
                                        @if($att->status == 1)
                                            @php
                                                $hisabsent+=1;
                                            @endphp
                                        @elseif($att->status == 2)
                                            @php
                                                $hispresent+=1;
                                            @endphp
                                        @elseif($att->status == 3)
                                            @php
                                                $histardy+=1;
                                            @endphp
                                        @elseif($att->status == 4)
                                            @php
                                                $histardy+=1;
                                            @endphp
                                        @elseif($att->status == 10)
                                            @php
                                                $hisabsent+=0.5;
                                            @endphp
                                        @elseif($att->status == 11)
                                            @php
                                                $hisabsent+=0.5;
                                            @endphp
                                        @elseif($att->status == 30)
                                            @php
                                                $histardy+=1;
                                            @endphp
                                        @elseif($att->status == 31)
                                            @php
                                                $histardy+=1;
                                            @endphp
                                        @endif
                                    @endif --}}
                                @endif
                            @endforeach
                        @endforeach
                            <tr @if($student->display == 0) style="color: red;" @endif>
                                <td style="width: 2% !important;">
                                    {{$countmale}}
                                </td>
                                <td @if($student->display == 0) style="color: red; width: 40%;" @else style="width: 50%;" @endif>
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
                                <td class="text-center">{{$hispresent+$histardy}}</td>
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
                                @php
                                    $student->totalpresent = $histardy+$hispresent;
                                    $student->totalabsent = $hisabsent;
                                    $student->totaltardy = $histardy;
                                @endphp
                                {{-- <td class="text-center">
                                    <button type="button" class="btn btn-default student-remarks" data-id="{{$student->id}}" data-toggle="tooltip" data-placement="left" title="@if($student->remarks!=""){{$student->remarks}}@else Add remarks @endif"><i class="fa fa-comment-alt fa-lg	text-info"></i></button>
                                </td> --}}
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
                    @foreach($studentstotalperday as $maletotal)
                        {{-- <td>{{$maletotal->withrecordsmale}}</td> --}}
                        @php
                            $totalattendmale += $maletotal->withrecordsmale; 
                        @endphp
                    @endforeach
                    <td class="text-center">{{collect($activedays)->sum('presentmale')}}</td>
                    <td class="text-center">{{collect($activedays)->sum('absentmale')}}</td>
                    <td class="text-center">{{collect($activedays)->sum('tardymale')}}</td>
                    {{-- <td></td> --}}
                </tr>
                <tr>
                    <td colspan="2">
                        FEMALE
                    </td>
                    {{-- <td colspan="{{count($activedays)}}">
                        &nbsp;
                    </td>
                    <td></td> --}}
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
                                $herpresent = 0;
                            @endphp
                                @foreach($activedays as $activeday)
                                    @foreach($student->attendance as $att)
                                    
                                        @if($att->day == $activeday->daynum)
                                            {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi') --}}
                                                @if($att->combinedstatus === 0)
                                                    @php
                                                        $herabsent+=1;
                                                    @endphp
                                                @elseif($att->combinedstatus === 1)
                                                    @php
                                                        $herpresent+=1;
                                                    @endphp
                                                @elseif($att->combinedstatus === 2)
                                                    @php
                                                        $hertardy+=1;
                                                    @endphp
                                                @elseif($att->combinedstatus === 3)
                                                    @php
                                                        $hertardy+=1;
                                                    @endphp
                                                @elseif($att->combinedstatus === 'presentam')
                                                    @php
                                                        $herpresent+=0.5;
                                                        $herabsent+=0.5;
                                                    @endphp
                                                @elseif($att->combinedstatus === 'presentpm')
                                                    @php
                                                        $herpresent+=0.5;
                                                        $herabsent+=0.5;
                                                    @endphp
                                                @elseif($att->combinedstatus === 'absentam')
                                                    @php
                                                        $herpresent+=0.5;
                                                        $herabsent+=0.5;
                                                        $hertardy+=1;
                                                    @endphp
                                                @elseif($att->combinedstatus ==='absentpm')
                                                    @php
                                                        $herpresent+=0.5;
                                                        $herabsent+=0.5;
                                                        $hertardy+=1;
                                                    @endphp
                                                @elseif($att->combinedstatus === 'lateam')
                                                    @php
                                                        $hertardy+=1;
                                                    @endphp
                                                @elseif($att->combinedstatus === 'latepm')
                                                    @php
                                                        $hertardy+=1;
                                                    @endphp
                                                @elseif($att->combinedstatus === 'ccam')
                                                    @php
                                                        $hertardy+=1;
                                                    @endphp
                                                @elseif($att->combinedstatus === 'ccpm')
                                                    @php
                                                        $hertardy+=1;
                                                    @endphp
                                                @endif
                                            {{-- @else
                                                @if($att->status == 1)
                                                    @php
                                                        $herabsent+=1;
                                                    @endphp
                                                @elseif($att->status == 2)
                                                    @php
                                                        $herpresent+=1;
                                                    @endphp
                                                @elseif($att->status == 3)
                                                    @php
                                                        $hertardy+=1;
                                                    @endphp
                                                @elseif($att->status == 4)
                                                    @php
                                                        $hertardy+=1;
                                                    @endphp
                                                @elseif($att->status == 10)
                                                    @php
                                                        $herabsent+=0.5;
                                                    @endphp
                                                @elseif($att->status == 11)
                                                    @php
                                                        $herabsent+=0.5;
                                                    @endphp
                                                @elseif($att->status == 30)
                                                    @php
                                                        $hertardy+=0.5;
                                                    @endphp
                                                @elseif($att->status == 31)
                                                    @php
                                                        $hertardy+=0.5;
                                                    @endphp
                                                @endif
                                            @endif --}}
                                        @endif
                                    @endforeach
                                @endforeach
                            <tr @if($student->display == 0) style="color: red;"@endif>
                                <td style="width: 2% !important;">
                                    {{$countfemale}}
                                </td>
                                <td @if($student->display == 0) style="color: red; width: 40%;" @else style="width: 50%;" @endif>
                                    @if($student->display == 0) <del>@endif
                                    @if($student->middlename != null)
                                    {{$student->lastname}},  {{$student->firstname}}  {{$student->middlename[0]}}.  {{$student->suffix}}
                                    @else
                                    {{$student->lastname}},  {{$student->firstname}}  {{$student->suffix}}
                                    @endif
                                    @if($student->display == 0) </del>@endif
                                </td>
                                <td class="text-center">{{$herpresent+$hertardy}}</td>
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
                                @php
                                    $student->totalpresent = $herpresent+$hertardy;
                                    $student->totalabsent = $herabsent;
                                    $student->totaltardy = $hertardy;
                                @endphp
                                {{-- <td class="text-center">
                                    <button type="button" class="btn btn-default student-remarks" data-id="{{$student->id}}" data-toggle="tooltip" data-placement="left" title="@if($student->remarks!=""){{$student->remarks}}@else Add remarks @endif"><i class="fa fa-comment-alt fa-lg	text-info"></i></button>
                                </td> --}}
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
                    @foreach($activedays as $activeday)
                    {{-- <td>{{$activeday->presentfemale}}</td> --}}
                    @php
                        $totalattendfemale += $activeday->presentfemale; 
                    @endphp
                    @endforeach
                    <td class="text-center">{{collect($activedays)->sum('presentfemale')}}</td>
                    <td class="text-center">{{collect($activedays)->sum('absentfemale')}}</td>
                    <td class="text-center">{{collect($activedays)->sum('tardyfemale')}}</td>
                </tr>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2">
                    COMBINED | TOTAL Per Day 
                </td>
                {{-- @foreach($studentstotalperday as $studenttotal)
                    <td>{{$studenttotal->total}}</td>
                @endforeach
                <td></td> --}}
                    <td class="text-center">{{collect($activedays)->sum('present')}}</td>
                    <td class="text-center">{{collect($activedays)->sum('absent')}}</td>
                    <td class="text-center">{{collect($activedays)->sum('tardy')}}</td>
              </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-md-5">
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
                    <td><em>* Enrolment as of (1st Friday of {{$enrollmentmonth}})</em></td>
                    <td>
                        {{$enrollmentasof_male}}
                    </td>
                    <td>
                        {{$enrollmentasof_female}}
                    </td>
                    <td>
                        {{$enrollmentasof_total}}
                    </td>
                </tr>
                <tr>
                    <td><em>Late Enrollment <strong>during the month</strong><br>(beyond cut-off)</em></td>
                    <td>
                        {{$lateenrolled_male}}
                    </td>
                    <td>
                        {{$lateenrolled_female}}
                    </td>
                    <td>
                        {{$lateenrolled_total}}
                    </td>
                </tr>
                <tr>
                    <td><em>Registered Learner as of <strong>end of the month</strong></em></td>
                    <td>
                        {{$registered_male}}
                    </td>
                    <td>
                        {{$registered_female}}
                    </td>
                    <td>
                        {{$registered_total}}
                    </td>
                </tr>
                <tr>
                    <td><em>Percentage of Enrollment as of <strong>end of the month</strong></em></td>
                    <td>
                        {{$enrollmentpercentage_male}}
                    </td>
                    <td>
                        {{$enrollmentpercentage_female}}
                        
                    </td>
                    <td>
                        {{$enrollmentpercentage_total}}
                    </td>
                </tr>
                <tr style="text-align: center;">
                    <td><em>Average Daily Attendance</em></td>
                    <td id="ada_male">
                        
                        @php
                            if(count($currentdays) == 0)
                            {
                                $avedailyatt_male = 0;
                            }else{
                                $avedailyatt_male = (collect($currentdays)->sum('presentmale')/count($currentdays));
                            }
                        @endphp
                        {{ number_format($avedailyatt_male,2)}}
                    </td>
                    <td id="ada_female">
                        
                        @php
                            if(count($currentdays) == 0)
                            {
                                $avedailyatt_female = 0;
                            }else{
                                $avedailyatt_female = (collect($currentdays)->sum('presentfemale')/count($currentdays));
                            }
                        @endphp
                        {{ number_format($avedailyatt_female,2)}} 
                    </td>
                    <td  id="ada_total">
                        @php
                            $ada_total = number_format(($avedailyatt_male+$avedailyatt_female),2);
                        @endphp
                        {{number_format(($avedailyatt_male+$avedailyatt_female),2)}}
                    </td>
            </tr>
            <tr>
                @php
                $averagemale = 0;
                $averagefemale = 0;
                @endphp
                <tr style="text-align: center;">
                    <td><em>Percentage of Attendance for the month</em></td>
                    <td id="pam_male">
                        <?php try{ ?> 
                            {{number_format($pam_male,2)}}
                
                        <?php }catch(\Exception $e){ ?>
                            @php
                                if($avedailyatt_male>0 || $registered_male>0)
                                {
                                    if(number_format(($avedailyatt_male/$registered_male)*100,2)>100)
                                    {
                                        $pammale = 100;
                                    }
                                    else{
                                        $pammale = number_format((($avedailyatt_male/$registered_male)*100),2);
                                    }
                                }else{
                                        $pammale = 0;
                                }
                            @endphp
                            <!--@if($avedailyatt_male > 0 && $registered_male > 0)-->
                            <!--    {{$pammale}}-->
                            <!--@else-->
                            <!--0-->
                            <!--@endif-->
                            
                            @if($avedailyatt_male > 0 && $registered_male > 0)
                            @php
                            $averagemale = number_format((number_format($avedailyatt_male,2)/$registered_male)*100,2);
                            @endphp
                            @endif
                            {{$averagemale}}
                        <?php } ?>
                    </td>
                    <td id="pam_female">
                        <?php try{ ?> 
                            {{number_format($pam_female,2)}}
                
                        <?php }catch(\Exception $e){ ?>
                            @php
                                if($avedailyatt_female>0 || $registered_female>0)
                                {
                                    if(number_format(($avedailyatt_female/$registered_female)*100,2)>100)
                                    {
                                        $pamfemale = 100;
                                    }
                                    else{
                                        $pamfemale = number_format(($avedailyatt_female/$registered_female)*100,2);
                                    }
                                }else{
                                        $pamfemale = 0;
                                }
                            @endphp
                            <!--@if($avedailyatt_female > 0 && $registered_female > 0)-->
                            <!--    {{$pamfemale}}-->
                            <!--@else-->
                            <!--0-->
                            <!--@endif-->
                            @if($avedailyatt_female > 0 && $registered_female > 0)
                            @php
                            $averagefemale = number_format((number_format($avedailyatt_female,2)/$registered_female)*100,2);
                            @endphp
                            @endif
                            {{$averagefemale}}
                        <?php } ?>
                    </td>
                    <td id="pam_total">
                        @php
                        $avedailytotal = number_format($avedailyatt_male,2) + number_format($avedailyatt_female,2);
                        $averagetotal = number_format($avedailytotal * count($activedays),2);
                        $enrollmentschooldays = $enrollmentasof_total * count($activedays);
                        $averagetotals = number_format(($averagetotal/$enrollmentschooldays)*100,2);
                        @endphp
                        {{$averagetotals}}

                    </td>
                </tr>
                <tr>
                    <td><em>Number of students with 5 consecutive days of absences:</em></td>
                    {{-- <td>{{$countconsecutive_male}}</td>
                    <td>{{$countconsecutive_female}}</td>
                    <td>{{$countconsecutive_total}}</td> --}}
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td><em><strong>NLPA</strong></em></td>
                    <td>
                        {{$droppedout_male}}
                    </td>
                    <td>
                        {{$droppedout_female}}
                    </td>
                    <td>
                        {{$droppedout_total}}
                    </td>
                </tr>
                <tr>
                    <td><em><strong>Transferred out</strong></em></td>
                    <td>
                        {{$transferredout_male}}
                    </td>
                    <td>
                        {{$transferredout_female}}
                    </td>
                    <td>
                        {{$transferredout_total}}
                    </td>
                </tr>
                <tr>
                    <td><em><strong>Transferred in</strong></em></td>
                    <td>
                        {{$transferredin_male}}
                    </td>
                    <td>
                        {{$transferredin_female}}
                    </td>
                    <td>
                        {{$transferredin_total}}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
    <div class="col-md-12">
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
        $('#btn-view-setup').removeAttr('hidden');
        $('#btn-reselect-setup').removeAttr('hidden');
        $('#btn-advisoryatt').removeAttr('hidden');
        $('#btn-printpdf').removeAttr('hidden');
        $('#btn-printexcel').removeAttr('hidden');
        $('[data-toggle="tooltip"]').tooltip();
        $('#btn-printpdf').show()
        $('#btn-printexcel').show()
  </script>
@endif