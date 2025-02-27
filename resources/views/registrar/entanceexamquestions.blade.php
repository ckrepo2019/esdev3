@if(isset($questions))
      @if(count($questions) > 0)
            <form action="/deletequestion" method="get" name="deletequestionform">
                  <input type="hidden" name="deletequestionid" />
            </form>
            @if(count($questions) > 0)
            @php
                  $uniqueid = 0;   
            @endphp
                  @foreach ($questions as $question)
                  <form action="/editquestion" method="get" class="updateform">
                        
                        @if($question->withcorrectanswer == 0)
                        <div class="eachquestioncontainer p-1" style="background-color: #e69199 ">
                        @else
                        <div class="eachquestioncontainer">
                        @endif
                              <div class="row">
                              <div class="col-md-10"><label>Question</label></div>
                              <div class="col-md-2 buttonscontainer"><button class="btn btn-sm btn-block btn-warning editquestion"><i class="fa fa-edit"></i></button></div>
                              </div>
                              <input type="hidden" name="questionid" value="{{$question->question->id}}" >
                              <textarea type="text" class="form-control" name="question" required disabled>{{$question->question->question}}</textarea>
                              <br>
                              <label>Choices</label>
                              
                              <div class="row">
                              <div class="col-md-6">
                                    @foreach($question->answers as $answer)
                                          @if($answer->correctanswer == 0)
                                          <div class="icheck-success d-inline">
                                                <input type="radio" name="correctanswer" value="{{$answer->id}}" id="{{$answer->id.''.$uniqueid}}" disabled>
                                                <label for="{{$answer->id.''.$uniqueid}}">
                                                      <input type="text" name="answers[]" class="form-control form-control-sm mt-1" value="{{$answer->answer}}" disabled>
                                                      <input type="hidden" name="choiceids[]" class="form-control form-control-sm mt-1" value="{{$answer->id}}" required disabled>
                                                </label>
                                          </div>
                                          @else
                                          <div class="icheck-success d-inline">
                                                <input type="radio" name="correctanswer" value="{{$answer->id}}" id="{{$answer->id.''.$uniqueid}}"  checked disabled>
                                                <label for="{{$answer->id.''.$uniqueid}}">
                                                      <input type="text" name="answers[]" class="form-control form-control-sm mt-1" style="border: 1px solid #28a745; background-color: #c5f0cf " value="{{$answer->answer}}"  >
                                                      <input type="hidden" name="choiceids[]" class="form-control form-control-sm mt-1" style="border: 1px solid #28a745; background-color: #c5f0cf " value="{{$answer->id}}" required >
                                                </label>
                                          </div>
                                          @endif
                                          @php
                                          $uniqueid+=1;   
                                          @endphp
                                    @endforeach
                              </div>
                              </div>
                        </div>
                  </form>
                  <hr>
                  <br>
                  @endforeach
            @endif
      @else
            <div>
                  No questions created for this grade level.
            </div>

      @endif

  @else
      <div>
           No Grade Level Selected.
      </div>
  @endif