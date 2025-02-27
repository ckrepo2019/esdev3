@if(count($setup) == 0)
    <div class="col-md-12">
        <div class="alert alert-warning alert-dismissible">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
            Warning alert preview. No setup found! Please contact the school principal or academic coordinator to setup school days. <br/>
          
        </div>
    </div>
    <script>
        $('#btn-setup-delete').prop('hidden',true);
        $('#btn-view-setup').prop('hidden',true);
        $('#btn-reselect-setup').prop('hidden',true);
        $('#btn-advisoryatt').prop('hidden',true);
        $('#btn-printpdf').prop('hidden', true);
        $('#btn-printexcel').prop('hidden', true);
        
        function getselecteddates()
        {
            selecteddates = [];
            $('.active-date.btn-success').each(function(){
                selecteddates.push($(this).attr('data-id'));
            })
            @if($setup_numdays)
                if(parseInt('{{$setup_numdays->days}}') < selecteddates.length)
                {
                    $('#btn-submit-setup').prop('disabled',true);
                }else{
                    $('#btn-submit-setup').removeAttr('disabled')
                }
            @else
                $('#btn-submit-setup').removeAttr('disabled')
            @endif
        }
        $('#btn-create-setup').on('click', function(){
                $('#show-calendar').modal('show')
                selecteddates = [];
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
                        getselecteddates()
                        $('.active-date').on('click', function(){
                            $('#selected-dates-container').empty()
                            var idx = $.inArray($(this).attr('data-id'), selecteddates);
                            
                            if (idx == -1) {
                                
                                @if($setup_numdays)
                                    if((selecteddates.length+1) < '{{$setup_numdays->days}}')
                                    {
                                        $('#btn-submit-setup').prop('disabled',true)
                                        selecteddates.push($(this).attr('data-id'));
                                        $(this).addClass('btn-success')
                                    }else if((selecteddates.length+1) == '{{$setup_numdays->days}}')
                                    {
                                        toastr.warning('Date limit reached!')
                                        $('#btn-submit-setup').removeAttr('disabled')
                                        selecteddates.push($(this).attr('data-id'));
                                        $(this).addClass('btn-success')
                                    }
                                    // else{
                                    //     $('#btn-submit-setup').removeAttr('disabled')
                                    //     toastr.warning('Date limit reached!')
                                    // }
                                @else
                                        selecteddates.push($(this).attr('data-id'));
                                        $(this).addClass('btn-success')
                                @endif
                            } else {
                                @if($setup_numdays)
                                $('#btn-submit-setup').prop('disabled',true)
                                @endif
                                selecteddates.splice(idx, 1);
                                $(this).removeClass('btn-success')
                            }
                        })
                    }
                })
            })
    </script>
@else
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<style>
    
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 800px;
        margin: 0 auto;
    }
#studentstable th{
    border: 1px solid #ddd !important;
}
#studentstable td{
    border: 1px solid #ddd !important;
}
.dataTables_filter, .dataTables_info { display: none; }
.dataTables_wrapper{
    margin: 0px !important;
    width: 100% !important;
}
</style>
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
                    $absentfemale+=0.5;
                    $present+=0.5;
                    $presentfemale+=0.5;
                    $absent+=0.5;
                }
                elseif($todayatt->combinedstatus === 'presentpm')
                {
                    $absentfemale+=0.5;
                    $present+=0.5;
                    $presentfemale+=0.5;
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
    <div class="col-md-12">
		<div class="row">
			<div class="col-md-4">
				<strong>ADV</strong> : Advisory Attendance
			</div>
			<div class="col-md-4">
				<strong>AM</strong>  : AM Subject Attendance
			</div>
			<div class="col-md-4">
				<strong>PM</strong>  : PM Subject Attendance
			</div>
		
		</div>
	</div>
	  
  <table class="table" style="width:100%; font-size: 11px; " id="studentstable">
      <thead class="text-center">
          <tr>
              <th></th>
              <th style="width: 30% !important">
                  Learner's Name
                  {{-- <br/> --}}
                      {{-- <input type="text" class="form-control" id="myInputTextField" placeholder="Search student..."> --}}
                  
              </th>
              @foreach($activedays as $activeday)
                  <th>{{$activeday->daynum}}<br/>{{$activeday->daystr}}</th>
              @endforeach
              <th class="text-center">ABSENT</th>
              <th class="text-center">TARDY</th>
              <th data-toggle="tooltip" data-placement="left" title="(If DROPPED OUT, state reason, please refer to legend number 2. If TRANSFERRED IN/OUT, write the name of School.) " >Remarks</th>
          </tr>
      </thead>
      <tbody>
          <tr style="font-size: 11px;">
            <td></td>
              <td>
                  MALE
              </td>
              @foreach($activedays as $activeday)
              <td></td>
              @endforeach
              <td></td>
              <td></td>
              <td></td>
          </tr>
          @if(count($attendance[0])>0)
              @foreach($attendance[0] as $student)
                  @if(strtolower($student->gender) == 'male')
                      @php
                          $hisabsent = 0;
                          $hispresent = 0;
                          $histardy = 0;
                      @endphp
                      
                      <tr @if($student->display == 0) style="color: red;" @endif style="font-size: 11px;">
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
                                  <td>ADV: {{$att->keystatus}}
                                      @if($att->amstatus != null && $att->pmstatus != null) 
                                          <br/>
                                          AM : {{strtoupper($att->amstatus)}}<br/>
                                          PM : {{strtoupper($att->pmstatus)}}
                                      @else
                                      <br/>
                                          @if($att->amstatus != null ) 
                                          AM : {{strtoupper($att->amstatus)}}
                                          @endif
                                          @if($att->pmstatus != null) 
                                          PM : {{strtoupper($att->pmstatus)}}
                                          @endif
                                      @endif
                                      <br/>
                                      @if($att->combinedstatus === 0)
                                          @php
                                              $hisabsent+=1;
                                          @endphp
                                          <span class="right badge badge-danger">ABSENT</span>
                                      @elseif($att->combinedstatus === 1)
                                          <span class="right badge badge-success">PRESENT</span>
                                      @elseif($att->combinedstatus === 2)
                                          @php
                                              $histardy+=1;
                                          @endphp
                                          <span class="right badge badge-warning">LATE</span>
                                      @elseif($att->combinedstatus === 3)
                                          @php
                                              $histardy+=1;
                                          @endphp
                                          <span class="right badge badge-secondary">CC</span>
                                      @elseif($att->combinedstatus === 'presentam')
                                          <span class="right badge badge-success">AM PRESENT</span>
                                          @php
                                              $hisabsent+=0.5;
                                              $hispresent+=0.5;
                                          @endphp
                                      @elseif($att->combinedstatus === 'presentpm')
                                          <span class="right badge badge-success">PM PRESENT</span>
                                          @php
                                              $hisabsent+=0.5;
                                              $hispresent+=0.5;
                                          @endphp
                                      @elseif($att->combinedstatus === 'absentam')
                                          @php
                                              $hisabsent+=0.5;
                                              $hispresent+=0.5;
                                              //$histardy+=1;
                                          @endphp
                                          <span class="right badge badge-danger">AM ABSENT</span>
                                      @elseif($att->combinedstatus ==='absentpm')
                                          @php
                                              $hisabsent+=0.5;
                                              $hispresent+=0.5;
                                              //$histardy+=1;
                                          @endphp
                                          <span class="right badge badge-danger">PM ABSENT</span>
                                      @elseif($att->combinedstatus === 'lateam')
                                          @php
                                              $histardy+=1;
                                          @endphp
                                          <span class="right badge badge-warning">AM LATE</span>
                                      @elseif($att->combinedstatus === 'latepm')
                                          @php
                                              $histardy+=1;
                                          @endphp
                                          <span class="right badge badge-warning">PM LATE</span>
                                      @elseif($att->combinedstatus === 'ccam')
                                          @php
                                              $histardy+=1;
                                          @endphp
                                          <span class="right badge badge-secondary">AM CC</span>
                                      @elseif($att->combinedstatus === 'ccpm')
                                          @php
                                              $histardy+=1;
                                          @endphp
                                          <span class="right badge badge-secondary">PM CC</span>
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
                              <button type="button" class="btn btn-default student-remarks" data-id="{{$student->id}}" data-toggle="tooltip" data-placement="top" title="@if($student->remarks!=""){{$student->remarks}}@else Add remarks @endif"><i class="fa fa-comment-alt fa-lg	text-info"></i></button>
                              @endif
                          </td>
                      </tr>
                      @php
                          $student->totalpresent = $hispresent+$histardy;
                          $student->totalabsent = $hisabsent;
                          $student->totaltardy = $histardy;
                          $countmale+=1;   
                      @endphp
                  @endif
              @endforeach
          @endif
          <tr style="font-size: 11px;">
              <td></td>
              <td>
                  @php
                      $totalattendmale = 0;
                  @endphp
                  MALE | TOTAL Per Day 
              </td>
              @foreach($activedays as $activeday)
              <td class="text-center">{{$activeday->presentmale}}</td>
              @php
                  $totalattendmale += $activeday->presentmale; 
              @endphp
              @endforeach
              <td class="text-center">{{collect($activedays)->sum('absentmale')}}</td>
              <td class="text-center">{{collect($activedays)->sum('tardymale')}}</td>
              <td></td>
          </tr>
          <tr style="font-size: 11px;">
              <td></td>
              <td>
                  FEMALE
              </td>
              @foreach($activedays as $activeday)
              <td></td>
              @endforeach
              <td></td>
              <td></td>
              <td></td>
          </tr>
          @if(count($attendance[0])>0)
              @foreach($attendance[0] as $student)
                  @if(strtolower($student->gender) == 'female')
                      @php
                          $herabsent = 0;
                          $herpresent = 0;
                          $hertardy = 0;
                      @endphp
                      <tr @if($student->display == 0) style="color: red;"@endif style="font-size: 11px;">
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
                                  <td>ADV: {{$att->keystatus}}
                                      @if($att->amstatus != null && $att->pmstatus != null) 
                                      <br/>
                                          AM : {{strtoupper($att->amstatus)}}<br/>
                                          PM : {{strtoupper($att->pmstatus)}}
                                      @else
                                      <br/>
                                          @if($att->amstatus != null ) 
                                          AM : {{strtoupper($att->amstatus)}}
                                          @endif
                                          @if($att->pmstatus != null) 
                                          PM : {{strtoupper($att->pmstatus)}}
                                          @endif
                                      @endif
                                      <br/>
                                      @if($att->combinedstatus === 0)
                                          @php
                                              $herabsent+=1;
                                          @endphp
                                          <span class="right badge badge-danger">ABSENT</span>
                                      @elseif($att->combinedstatus === 1)
                                          <span class="right badge badge-success">PRESENT</span>
                                      @elseif($att->combinedstatus === 2)
                                          @php
                                              $hertardy+=1;
                                          @endphp
                                          <span class="right badge badge-warning">LATE</span>
                                      @elseif($att->combinedstatus === 3)
                                          @php
                                              $hertardy+=1;
                                          @endphp
                                          <span class="right badge badge-secondary">CC</span>
                                      @elseif($att->combinedstatus === 'presentam')
                                          <span class="right badge badge-success">AM PRESENT</span>
                                          @php
                                              $herabsent+=0.5;
                                              $herpresent+=0.5;
                                          @endphp
                                      @elseif($att->combinedstatus === 'presentpm')
                                          <span class="right badge badge-success">PM PRESENT</span>
                                          @php
                                              $herabsent+=0.5;
                                              $herpresent+=0.5;
                                          @endphp
                                      @elseif($att->combinedstatus === 'absentam')
                                      @php
                                          $herabsent+=0.5;
                                          $herpresent+=0.5;
                                      @endphp
                                          <span class="right badge badge-danger">AM ABSENT</span>
                                      @elseif($att->combinedstatus ==='absentpm')
                                      @php
                                          $herabsent+=0.5;
                                          $herpresent+=0.5;
                                      @endphp
                                          <span class="right badge badge-danger">PM ABSENT</span>
                                      @elseif($att->combinedstatus === 'lateam')
                                          @php
                                              $hertardy+=1;
                                          @endphp
                                          <span class="right badge badge-warning">AM LATE</span>
                                      @elseif($att->combinedstatus === 'latepm')
                                          @php
                                              $hertardy+=1;
                                          @endphp
                                          <span class="right badge badge-warning">PM LATE</span>
                                      @elseif($att->combinedstatus === 'ccam')
                                          @php
                                              $hertardy+=1;
                                          @endphp
                                          <span class="right badge badge-secondary">AM CC</span>
                                      @elseif($att->combinedstatus === 'ccpm')
                                          @php
                                              $hertardy+=1;
                                          @endphp
                                          <span class="right badge badge-secondary">PM CC</span>
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
                              <button type="button" class="btn btn-default student-remarks" data-id="{{$student->id}}" data-toggle="tooltip" data-placement="top" title="@if($student->remarks!=""){{$student->remarks}}@else Add remarks @endif"><i class="fa fa-comment-alt fa-lg	text-info"></i></button>
                              @endif
                          </td>
                      </tr>
                      @php
                      $student->totalpresent = $herpresent+$hertardy;
                      $student->totalabsent = $herabsent;
                      $student->totaltardy = $hertardy;
                          $countfemale+=1;   
                      @endphp
                  @endif
              @endforeach
          @endif
          <tr style="font-size: 11px;">
              <td></td>
              <td>
                  @php
                      $totalattendfemale = 0;
                  @endphp
                  FEMALE | TOTAL Per Day 
              </td>
              @foreach($activedays as $activeday)
              <td class="text-center">{{$activeday->presentfemale}}</td>
              @php
                  $totalattendfemale += $activeday->presentfemale; 
              @endphp
              @endforeach
              <td class="text-center">{{collect($activedays)->sum('absentfemale')}}</td>
              <td class="text-center">{{collect($activedays)->sum('tardyfemale')}}</td>
              <td></td>
          </tr>
      </tbody>
      <tfoot>
        <tr style="font-size: 11px;">
          <td></td>
          <td>
              COMBINED | TOTAL Per Day 
          </td>
          @if(count($studentstotalperday) == 0)
          @foreach($activedays as $activeday)
          <td class="text-center"></td>
          @endforeach
          @else
              @foreach($activedays as $studenttotal)
                  <td class="text-center">{{$studenttotal->present}}</td>
              @endforeach
          @endif
          <td class="text-center">{{collect($activedays)->sum('absent')}}</td>
          <td class="text-center">{{collect($activedays)->sum('tardy')}}</td>
          <td></td>
        </tr>
      </tfoot>
  </table>
  
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
  <div class="row">
    <div class="col-md-12">
      <table class="table table-bordered" style="font-size: 11px; width: 100%;">
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
              <tr>
                  <td>
                      <button type="button" class="btn btn-sm btn-block btn-warning p-1 m-0" id="selectenrollmentmonth" style="font-size: 12px;">Enrollment as of (1st Friday of {{date("F", mktime(0, 0, 0, $enrollmonth, 10))}})</button>
                  </td>
                  <td class="text-center">{{$summarydetails->enrollmentasof_male}}</td>
                  <td  class="text-center">{{$summarydetails->enrollmentasof_female}}</td>
                  <td  class="text-center">
                      {{$summarydetails->enrollmentasof_male+$summarydetails->enrollmentasof_female}}
                  </td>
              </tr>
              <tr>
                  <td>
                      <em>Late Enrollment <strong>during the month</strong></em>
                  </td>
                  <td  class="text-center">{{$summarydetails->lateenrolled_male}}</td>
                  <td  class="text-center">{{$summarydetails->lateenrolled_female}}</td>
                  <td  class="text-center">
                      {{$summarydetails->lateenrolled_male+$summarydetails->lateenrolled_female}}
                  </td>
              </tr>
              <tr>
                  <td>
                      <em>Registered Learner as of <strong>end of the month</strong></em>
                  </td>
                  <td class="text-center">{{$summarydetails->registered_male}}</td>
                  <td class="text-center">{{$summarydetails->registered_female}}</td>
                  <td class="text-center">{{$summarydetails->registered_total}}</td>
              </tr>
              <tr>
                  <td>
                      <em>Percentage of Enrolment as of <strong>end of the month</strong></em>
                  </td>
                  <td class="text-center">{{$summarydetails->enrollmentpercentage_male}}</td>
                  <td class="text-center">{{$summarydetails->enrollmentpercentage_female}}</td>
                  <td class="text-center">{{$summarydetails->enrollmentpercentage_total}}</td>
              </tr>
              <tr style="text-align: center;">
                  <td><em>Average Daily Attendance</em></td>
                  <td id="ada_male">
                      
                      @php
                        if(count($activedays) == 0)
                        {
                            $avedailyatt_male = 0;
                        }else{
                            $avedailyatt_male = (collect($activedays)->sum('presentmale')/count($activedays));
                            
                            $avedailyatt_male = round(bcdiv($avedailyatt_male,1,3),2);
                            $avedailyatt_male = bcdiv($avedailyatt_male,1,2);
                        }
                      @endphp
                      {{ bcdiv($avedailyatt_male,1,2)}}
                  </td>
                  <td id="ada_female">
                      
                      @php
                      if(count($activedays) == 0)
                      {
                          $avedailyatt_female = 0;
                      }else{
                          $avedailyatt_female = (collect($activedays)->sum('presentfemale')/count($activedays));
                          $avedailyatt_female = round(bcdiv($avedailyatt_female,1,3),2);
                          $avedailyatt_female = bcdiv($avedailyatt_female,1,2);
                      }
                      @endphp
                      {{ bcdiv($avedailyatt_female,1,2)}} 
                  </td>
                  <td  id="ada_total">
                      @php
                      $ada_total = bcdiv(($avedailyatt_male+$avedailyatt_female),1,3);
                      $ada_total = round($ada_total,2);
                      @endphp
                      {{bcdiv(($avedailyatt_male+$avedailyatt_female),1,2)}}
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
                          {{bcdiv($pam_male,1,2)}}
              
                      <?php }catch(\Exception $e){ ?>
                          @php
                              if($avedailyatt_male>0 || $registered_male>0)
                              {
                                  if(number_format(($avedailyatt_male/$registered_male)*100,2)>100)
                                  {
                                      $pammale = 100;
                                  }
                                  else{
                                      $pammale = bcdiv((($avedailyatt_male/$registered_male)*100),1,2);
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
                          $averagemale = bcdiv((bcdiv($avedailyatt_male,1,2)/$registered_male)*100,1,2);
                          @endphp
                          @endif
                          {{$averagemale}}
                      <?php } ?>
                  </td>
                  <td id="pam_female">
                      <?php try{ ?> 
                          {{bcdiv($pam_female,1,2)}}
              
                      <?php }catch(\Exception $e){ ?>
                          @php
                              if($avedailyatt_female>0 || $registered_female>0)
                              {
                                  if(bcdiv(($avedailyatt_female/$registered_female)*100,2)>100)
                                  {
                                      $pamfemale = 100;
                                  }
                                  else{
                                      $pamfemale = bcdiv(($avedailyatt_female/$registered_female)*100,1,2);
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
                          $averagefemale = bcdiv((bcdiv($avedailyatt_female,1,2)/$registered_female)*100,1,2);
                          @endphp
                          @endif
                          {{$averagefemale}}
                      <?php } ?>
                  </td>
                  <td id="pam_total">
                      <?php try{ ?> 
                          {{bcdiv(($pam_male+$pam_female)/2,1,2)}}
                      <?php }catch(\Exception $e){ ?>
                          @if($averagemale == 0 && $averagefemale > 0)
                          {{$averagefemale}}
                          @elseif($averagemale > 0 && $averagefemale == 0)
                          {{$averagemale}}
                          @else
                          {{round(($averagemale+$averagefemale)/2,2)}}
                          {{-- {{bcdiv(($ada_total/($registered_total)*100),1,2) }} --}}
                          @endif
                          <!--{{number_format(($pammale+$pamfemale)/2,2)}}-->
                      <?php } ?>
                  </td>
              </tr>
              <tr>
                  <td>
                      <em>Number of students with 5 consecutive days of absences:</em>
                  </td>
                  <td  class="text-center">{{$summarydetails->countconsecutive_male}}</td>
                  <td  class="text-center">{{$summarydetails->countconsecutive_female}}</td>
                  <td  class="text-center">{{$summarydetails->countconsecutive_male+$summarydetails->countconsecutive_female}}</td>
              </tr>
              <tr>
                  <td>
                      <em><strong>NLPA</strong></em>
                  </td>
                  <td class="text-center">{{$summarydetails->droppedout_male+$summarydetails->nlpamale}}</td>
                  <td class="text-center">{{$summarydetails->droppedout_female+$summarydetails->nlpafemale}}</td>
                  <td class="text-center">{{$summarydetails->droppedout_male+$summarydetails->nlpamale+$summarydetails->droppedout_female+$summarydetails->nlpafemale}}</td>
              </tr>
              <tr>
                  <td>
                      <em><strong>Transferred Out</strong></em>
                  </td>
                  <td class="text-center">{{$summarydetails->transferredout_male}}</td>
                  <td class="text-center">{{$summarydetails->transferredout_female}}</td>
                  <td class="text-center">{{$summarydetails->transferredout_total}}</td>
              </tr>
              <tr>
                  <td>
                      <em><strong>Transferred In</strong></em>
                  </td>
                  <td class="text-center">{{$summarydetails->transferredin_male}}</td>
                  <td class="text-center">{{$summarydetails->transferredin_female}}</td>
                  <td class="text-center">{{$summarydetails->transferredin_total}}</td>
              </tr>
          </tbody>
      </table>
    </div>
    <div class="col-md-12">
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
                <div class="row">
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

    </div>
</div>
  <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
  <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
  <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
  <script>
    $('#btn-setup-delete').removeAttr('hidden');
      var oTable = $('#studentstable').DataTable({
  "columnDefs": [
    { "width": "20px", "targets": 0 }
  ],
        scrollY:        "500px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        "ordering": false,
        fixedColumns:   {
            leftColumns: 2,
            rightColumns:3
        },
    "aaSorting": []
      })   //using Capital D, which is mandatory to retrieve "api" datatables' object, latest jquery Datatable
      $('#myInputTextField').keyup(function(){
          oTable.search($(this).val()).draw() ;
      })
      $('th').unbind('click.DT');
      $('#all_teacher_sched').remove()
  </script>
  <script>
    $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
    var collecteddates = [];
    @if(count($setup)>0)
        @if(count($setup[0]->dates) > 0)
            @foreach($setup[0]->dates as $eachdate)
            collecteddates.push('{{$eachdate->daydate}}')
            @endforeach
        @endif
    @endif
    @if($locksf2 == 0)
        $('#btn-setup-delete').removeAttr('hidden');
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
                        strandid               : '{{$strandid}}',
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
                        strandid               : '{{$strandid}}',
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