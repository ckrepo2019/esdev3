@if(count($data) > 0)

    <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid uk-grid" uk-grid=""   >

        @foreach($data as $quiz)


                
                <div class="quizCount">
                    <div class="course-card animate-this" >
                            @if($quiz->checking == 1)
                            <a href="/admissiontest/test/{{$quiz->id}}">
                            @else
                            <a href="/admissiontest/test2/{{$quiz->id}}">
                            @endif
                            <div class="course-card-thumbnail">
                                <img src="{{asset('assets/images/elearning8.jpg')}}">
                                <span class="play-button-trigger"></span>
                            </a>
                                <button class="close-button1" data-id="{{$quiz->id}}">×</button> <!-- Add the close button -->
                            </div>
                                <div class="course-card-body ">
                                    <div class="dropdown">
                                        <button class="btn btn-link dropdown-toggle dropdown-title" type="button" id="quizDropdown{{$quiz->id}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{$quiz->title}}
                                        </button>
                                        <div class="dropdown-menu"  aria-labelledby="quizDropdown{{$quiz->id}}">
                                            <span class="dropdown-item quiz-description">{{$quiz->description}}</span>
                                        </div>

                                </div>
                                <div class="course-card-footer">
                                    <h5><i class="icon-feather-calendar"></i> Created: {{\Carbon\Carbon::create($quiz->createddatetime)->isoFormat('MMMM DD, YYYY hh:mm A')}}</h5>
                                </div>
                            </div>
                    </div>
                </div>



        @endforeach
    </div>
@elseif(count($data) == 0)
    <div>
        <div class="uk-card uk-card-default uk-card-body">
            <h5>NO TEST CREATED  YET!</h5>
        </div>
    </div>
@else
    <div id="max_reach"></div>
@endif