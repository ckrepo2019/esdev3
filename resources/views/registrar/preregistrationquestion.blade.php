@php
      $questionCount = count($questions);
@endphp
@foreach($questions as $key=>$question)
     <div class="row m-3 eachquestion" >
            <div class="col-md-3">
                  Question {{$key+1}}
            </div>
            <div class="col-md-5 answers" id="answer{{$key+1}}">
            <strong>{{$question->question->question}}</strong>
            <input type="hidden" name="questionids[]" value="{{$question->question->id}}">
            <br>
            <br>
            @foreach ($question->answers as $answer)
                  <div class="form-group clearfix" >
                        <div class="icheck-success d-inline">
                        <input type="radio" name="answer{{$question->question->id}}" value="{{$answer->id}}" class="examradiobuttons" id="radioPrimary{{$answer->id}}" >
                              <label for="radioPrimary{{$answer->id}}">
                                    {{$answer->answer}}
                              </label>
                        </div>
                  </div>
            @endforeach
            </div>
      </div>
  
@endforeach