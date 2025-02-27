@if($studentinfo)
    <div class="card-header bg-warning">
        <div class="row">
            <div class="col-md-12">
                <h4>Medical History (For Learners)</h4>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-md-8">
                <h5>{{$studentinfo->lastname}},{{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. {{$studentinfo->suffix}}</h5>
            </div>
            <div class="col-md-4">
                <h5>LRN: {{$studentinfo->lrn}}</h5>
            </div>
        </div>
        <div {{--style="height: 700px; overflow-y: scroll;"--}}>
        @if(count($questions)>0)
            @foreach($questions as $questionkey => $questionval)
                <div class="row mb-2 questionid" data-id="{{$questionval->id}}">
                    <div class="col-md-1">
                        {{$questionkey+1}}
                    </div>
                    <div class="col-md-8">
                        {{$questionval->question}}
                        <div class="row">
                            <div class="col-md-12">
                                @if(count($questionval->choices)>0)
                                    <div class="form-group clearfix">
                                        @foreach($questionval->choices as $choice)
                                            <div class="row choiceid" data-id="{{$choice->id}}"  data-questionid="{{$questionval->id}}">
                                                @if($choice->iscancer == 1)
                                                    <div class="col-md-6">
                                                        <div class="icheck-primary">
                                                            <input type="checkbox" id="checkbox{{$choice->id}}" value="{{$choice->id}}" class="choice-iscancer choices" data-questionid="{{$questionval->id}}">
                                                            <label for="checkbox{{$choice->id}}" style="font-weight: unset;" @if($choice->checked == 1) checked @endif>
                                                                {{$choice->choice}}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>If yes, what Kind?</label>
                                                        <input type="text" class="form-control" name="choice-description{{$questionval->id}}" value="{{$choice->description}}" data-choiceid="{{$choice->id}}">
                                                    </div>
                                                @else
                                                    @if($choice->other == 1)
                                                        <div class="col-md-12">
                                                            <div class="icheck-primary">
                                                                <input type="checkbox" id="checkbox{{$choice->id}}"  @if($choice->checked == 1) checked @endif value="{{$choice->id}}" class="choices" data-questionid="{{$questionval->id}}">
                                                                <label for="checkbox{{$choice->id}}" style="font-weight: unset;" >
                                                                    {{$choice->choice}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <textarea class="form-control" name="choice-description{{$questionval->id}}" placeholder="Others" data-choiceid="{{$choice->id}}">{{$choice->description}}</textarea>
                                                        </div>
                                                    @else
                                                        <div class="col-md-12">
                                                            <div class="icheck-primary">
                                                                <input type="checkbox" id="checkbox{{$choice->id}}"  @if($choice->checked == 1) checked @endif class="choices" data-questionid="{{$questionval->id}}" value="{{$choice->id}}" >
                                                                <label for="checkbox{{$choice->id}}" style="font-weight: unset;">
                                                                    {{$choice->choice}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        @if($questionval->queyesorno == 1)
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                              <input type="radio" class="choice-yesorno" id="radio{{$questionval->id}}1" name="yesorno{{$questionval->id}}" value="1" @if($questionval->ansyesorno == 1) checked @endif>
                              <label for="radio{{$questionval->id}}1">
                                  Yes
                              </label>
                            </div>
                            <div class="icheck-primary d-inline">
                              <input type="radio" class="choice-yesorno" id="radio{{$questionval->id}}2" name="yesorno{{$questionval->id}}" value="0" @if($questionval->ansyesorno == 0) checked @endif>
                              <label for="radio{{$questionval->id}}2">
                                  No
                              </label>
                            </div>
                          </div>
                        @else
                        <div class="form-group clearfix" hidden>
                            <div class="icheck-primary d-inline">
                              <input type="radio" class="choice-yesorno" id="radio{{$questionval->id}}1" name="yesorno{{$questionval->id}}" value="1" >
                              <label for="radio{{$questionval->id}}1">
                                  Yes
                              </label>
                            </div>
                            <div class="icheck-primary d-inline">
                              <input type="radio" class="choice-yesorno" id="radio{{$questionval->id}}2" name="yesorno{{$questionval->id}}" value="0" checked>
                              <label for="radio{{$questionval->id}}2">
                                  No
                              </label>
                            </div>
                          </div>
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-success" id="btn-savechangesform1a"></i> <strong>Save Changes</strong></button>
                </div>
            </div>
        @endif
    </div>
@else
    <div class="card-header">
        <h3 class="card-title">For Students Only</h3>
    </div>
@endif
