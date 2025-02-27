
<table class="table table-bordered">
      <tr>
            <th></td>
            <th></td>
            <th colspan="4" class="text-center">Grading Period</th>
      </tr>
      <tr>
            <th width="5"></th>
            <th width="55%"></th>
            <th width="10%" class="text-center">1</th>
            <th width="10%" class="text-center">2</th>
            <th width="10%" class="text-center">3</th>
            <th width="10%" class="text-center">4</th>
            
      </tr>
   
      @if($grading_system[0]->type == 2)

            @php
                  $overTotal = 0;
            @endphp

            @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                  @php
                        $count = 0;
                        $totalg1 = 0;
                        $totalg2 = 0;
                        $totalg3 = 0;
                        $totalg4 = 0;
                  @endphp
                  @foreach ($groupitem as $item)
                        @if($item->value == 1)
                              <tr>
                                    <th colspan="6">{{$item->description}}</th>
                              </tr>
                        @else
                              @php
                                    $count +=1;
                                    if($item->q1eval == 1){
                                          $totalg1 += 1; 
                                          $overTotal += 1;
                                    }
                                    if($item->q2eval == 1){
                                          $totalg2 += 1; 
                                          $overTotal += 1;
                                    }
                                    if($item->q3eval == 1){
                                          $totalg3 += 1; 
                                          $overTotal += 1;
                                    }
                                    if($item->q4eval == 1){
                                          $totalg4 += 1; 
                                          $overTotal += 1;
                                    }
                              @endphp
                              <tr>
                                    <td class="text-center align-middle"> {{$count}}</td>
                                    <td class="align-middle">{{$item->description}}</td>
                                    <td class="text-center align-middle"> 
                                          <div class="icheck-primary d-inline">
                                                <input type="checkbox" data-type="checkbox" id="checklist{{$item->id}}q1" class="grade_select" value="1" data-quarter="q1eval" data-id="{{$item->id}}" {{$item->q1eval == 1 ? 'checked':''}}>
                                                      <label for="checklist{{$item->id}}q1">
                                                </label>
                                          </div>
                                    </td>
                                    <td class="text-center align-middle"> 
                                          <div class="icheck-primary d-inline">
                                                <input type="checkbox" data-type="checkbox" id="checklist{{$item->id}}q2" class="grade_select" value="1" data-quarter="q2eval" data-id="{{$item->id}}" {{$item->q2eval == 1 ? 'checked':''}}>
                                                      <label for="checklist{{$item->id}}q2">
                                                </label>
                                          </div>
                                    </td>
                                    <td class="text-center align-middle"> 
                                          <div class="icheck-primary d-inline">
                                                <input type="checkbox" data-type="checkbox" id="checklist{{$item->id}}q3" class="grade_select" value="1" data-quarter="q3eval" data-id="{{$item->id}}" {{$item->q3eval == 1 ? 'checked':''}}>
                                                      <label for="checklist{{$item->id}}q3">
                                                </label>
                                          </div>
                                    </td>
                                    <td class="text-center align-middle"> 
                                          <div class="icheck-primary d-inline">
                                                <input type="checkbox" data-type="checkbox" id="checklist{{$item->id}}q4" class="grade_select" value="1" data-quarter="q4eval" data-id="{{$item->id}}" {{$item->q4eval == 1 ? 'checked':''}}>
                                                      <label for="checklist{{$item->id}}q4">
                                                </label>
                                          </div>
                                    </td>
                              </tr>
                        @endif
                  @endforeach
                  <tr>
                        <th></th>
                        <th class="pl-5 align-middle">Total Score</th>
                        <td class="text-center">{{$totalg1}}</td>
                        <td class="text-center">{{$totalg2}}</td>
                        <td class="text-center">{{$totalg3}}</td>
                        <td class="text-center">{{$totalg4}}</td>
                  </tr>
            @endforeach

      @elseif($grading_system[0]->type == 3)

            @php
                  $count = 0;
            @endphp

            @foreach ($checkGrades as $item)

               
                  @if($item->value == 0)
                        <tr>
                              <th colspan="6">{{$item->description}}</th>
                        </tr>
                  @else

                        @php
                              $count += 1;
                        @endphp

                        <tr>
                              <td class="text-center align-middle">{{$count}}</td>
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
      @endif
      
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