


<table class="table table-bordered">
      <tr>
            <th width="13%" rowspan="2" class="text-center align-middle">Core Values</th>
            <th width="47%" rowspan="2" class="text-center align-middle">Behaviour Statements</th>
            <th colspan="4" class="text-center p-1">Grading Period</th>
      </tr>
      <tr>
            <th width="10%" class="text-center p-1">1</th>
            <th width="10%" class="text-center p-1">2</th>
            <th width="10%" class="text-center p-1">3</th>
            <th width="10%" class="text-center p-1">4</th>
      </tr>
      @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
            @php
                  $count = 0;
            @endphp
            @foreach ($groupitem as $item)
                  @if($item->value == 0)
                        <tr>
                              <th colspan="6">{{$item->description}}</th>
                        </tr>
                  @else
                        <tr>
                              @if($count == 0)
                                    <td class="text-center align-middle" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                                    @php
                                          $count = 1;
                                    @endphp
                              @endif
                              <td class="align-middle">{{$item->description}}</td>
                              <td class="text-center">
                                    <select data-type="select" id="" class="form-control grade_select" data-quarter="q1eval" data-id="{{$item->id}}">
                                          @foreach ($ratingValue as $key=>$rvitem)
                                                <option {{$item->q1eval == $rvitem->id ? 'selected':''}} value="{{$rvitem->id}}">{{$rvitem->value}}</option>
                                          @endforeach
                                    </select>
                              </td>
                              <td class="text-center">
                                    <select data-type="select" id="" class="form-control grade_select" data-quarter="q2eval" data-id="{{$item->id}}">
                                          @foreach ($ratingValue as $key=>$rvitem)
                                          <option {{$item->q2eval == $rvitem->id ? 'selected':''}} value="{{$rvitem->id}}">{{$rvitem->value}}</option>
                                    @endforeach 
                                    </select>
                              </td>
                              <td class="text-center">
                                    <select data-type="select" id="" class="form-control grade_select" data-quarter="q3eval" data-id="{{$item->id}}">
                                          @foreach ($ratingValue as $key=>$rvitem)
                                                <option {{$item->q3eval == $rvitem->id ? 'selected':''}} value="{{$rvitem->id}}">{{$rvitem->value}}</option>
                                          @endforeach
                                    </select>
                              </td>
                              <td class="text-center">
                                    <select data-type="select" id="" class="form-control grade_select" data-quarter="q4eval" data-id="{{$item->id}}"> 
                                          @foreach ($ratingValue as $key=>$rvitem)
                                                <option {{$item->q4eval == $rvitem->id ? 'selected':''}} value="{{$rvitem->id}}">{{$rvitem->value}}</option>
                                          @endforeach 
                                    </select>
                              </td>
                        </tr>
                  @endif
            @endforeach
      @endforeach
</table>

@if(count($ratingValue) > 0)
      <table class="table  mt-5">
            <tr>
                  <th width="50%">Observed Values Markings</th>
                  <th width="50%">Non-numerical Rating</th>
            </tr>
            @foreach ($ratingValue as $rvitem)
                  <tr>
                        <td>{{$rvitem->value}}</td>
                        <td>{{$rvitem->description}}</td>
                  </tr>
            @endforeach
      </table>
@endif

<script>
      $(document).ready(function(){
            @if($widthAdditionalgs)
                  $('#generate_grade_holder').removeAttr('hidden')
                  $('#no_gs_count').text('{{$nogscount}}')
            @else
                  $('#generate_grade_holder').attr('hidden','hidden')
                  $('#no_gs_count').text('{{$nogscount}}')
            @endif
      })
</script>
<script>
      $(document).ready(function(){
            @if(count($checkGrades) == 0)
                  $('#generate_student_grade_detail')[0].innerHTML = '<i class="fas fa-sync-alt"></i> GENERATE STUDENT GRADE DETAIL'
            @elseif($widthAdditionalgs && count($checkGrades) > 0)
                  $('#generate_grade_holder').removeAttr('hidden')
                  $('#generate_student_grade_detail')[0].innerHTML = '<i class="fas fa-sync-alt"></i> UPDATE STUDENT GRADE DETAIL'
            @else 
                  $('#generate_student_grade_detail')[0].innerHTML = '<i class="fas fa-sync-alt"></i> GENERATE STUDENT GRADE DETAIL'
            @endif
      })
</script>

