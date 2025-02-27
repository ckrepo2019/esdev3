<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Adminission Test</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
{{-- <link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}"> --}}
{{-- <link rel="stylesheet" href="{{asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css')}}"> --}}
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables/DataTables/css/jquery.dataTables.css')}}">


<style>

    .answered{

        background-color: #77dd77;

    }

    .timer-container {
            color:#779ecb;
            border-radius: 10px;
            padding: 20px;
    }

    .timer {
            font-size: 4em;
        }

    .seconds {
        animation: fade 1s alternate infinite;
    }

    .red-text {
        color: #ff6961;
        font-size: 5em;
    }

    @keyframes fade {
        from {
            opacity: 1;
        }
        to {
            opacity: 0.5;
        }
    }



    .points {
        width: 60px;
        height: 60px;
        background-color: #4d4d99;
        border-radius: 50%;
        position: relative;
        top: -50px;
        left: -50px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        padding: 0;
        margin: 0;
        font-size: 15pt;
        font-weight: 600;
    }



</style>


</head>
<body>


        <div class="container quizcontent" style="background-color: #fff !important;">
            <div class="row">

                <div class="col-md-12 m-2">


                        <div class="card mt-5 editcontent">
                            <div class="card-body">
                                <div class="text-center">
                                    <h1 class="mb-2"> Applicant Information </h1>
                                </div>
                                <form id="myForm" class="mt-2">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name:</label>
                                        <input type="text" class="form-control" value="{{$applicantinfo->applicantname}}" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Pooling Number:</label>
                                        <input type="number" class="form-control" id="name" value="{{$applicantinfo->poolingnumber}}" disabled>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @foreach($admissiontestquestions as $key=>$item)
                        @if($item->typeofquiz == 1)

                                <!-- multiple choice -->
                                
                                    <div class="card mt-5 editcontent" id="question{{$item->id}}"
                                        @if($item->answer)

                                            style = "border: 2px solid dodgerblue; border-radius: 5px"

                                        @endif
                                        
                                        
                                        
                                        >
                                        <div class="card-body ">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="points student-score">
                                                        {{$key+=1}}
                                                    </div>
                                                </div>
                                            </div>
                            
                                                    
                                                    <p class="question" data-question-type="{{$item->typeofquiz}}">{!! $item->question !!}</p>

                                                    @foreach ($item->choices as $questioninfo)
                                                        <div class="form-check mt-2">
                                                            @if($questioninfo->id == $item->answer)
                                                                <input data-question-type="{{$item->typeofquiz}}" data-question-id="{{  $item->id }}" id="{{ $questioninfo->id}}" class="answer-field form-check-input" type="radio" name="{{ $item->id }}" value="{{ $questioninfo->id}}" checked>
                                                            @else
                                                                <input data-question-type="{{$item->typeofquiz}}" data-question-id="{{  $item->id }}" id="{{ $questioninfo->id}}" class="answer-field form-check-input" type="radio" name="{{ $item->id }}" value="{{ $questioninfo->id}}">
                                                            @endif
                                                            <label for="{{ $item->id }}" class="form-check-label">
                                                                {{$questioninfo->description}}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                
                                            
                                        </div>
                                    </div>
                            @endif
                        

                            @if($item->typeofquiz == 2)
                                <div class="card mt-5 editcontent" id="question{{$item->id}}"
                                    
                                    
                                    @if($item->answer)

                                            style = "border: 2px solid dodgerblue; border-radius: 5px"

                                    @endif
                                    
                                    >
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="points student-score">
                                                    {{$key+=1}}
                                                </div>
                                            </div>
                                        </div>
                                                <p class="question" data-question-type="{{$item->typeofquiz}}"> <b> Points. </b> {{$item->points}}</p>
                                                <p class="question" data-question-type="{{$item->typeofquiz}}"> {!! $item->question !!}</p>
                                                <input type="text" data-question-type="{{$item->typeofquiz}}" data-question-id="{{ $item->id}}" class="answer-field form-control mt-2" placeholder="Answer here" value="{{ $item->answer}}" >

                                    </div>
                                </div>
                            @endif


                            @if($item->typeofquiz == 3)
                                <div class="v mt-5 editcontent" id="question{{$item->id}}"
                                    
                                    @if($item->answer)

                                            style = "border: 2px solid dodgerblue; border-radius: 5px"

                                    @endif
                                    
                                    
                                    >
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="points student-score">
                                                    {{$key+=1}}
                                                </div>
                                            </div>
                                        </div>
                                                <p class="question" data-question-type="{{$item->typeofquiz}}"> <b> Points. </b> {{$item->points}}</p>
                                                <p class="question" data-question-type="{{$item->typeofquiz}}"> {!! $item->question !!} </p>
                                                <textarea data-question-type="{{$item->typeofquiz}}" data-question-id="{{ $item->id}}" class="answer-field form-control mt-2"type="text">{{$item->answer}}</textarea>

                                    </div>
                                </div>
                            @endif


                            @if($item->typeofquiz == 4)
                            
                                

                                <div class="card mt-5 editcontent">
                                    <div class="card-body">

                                                <p>Instruction. {!! $item->question !!}</p>

                                    </div>
                                </div>
                            @endif

                        


                            @if($item->typeofquiz == 5)
                                <!-- drag and drop -->
                                

                                <div class="card mt-5 editcontent" id="question{{$item->id}}" 
                                    
                                    @if($item->answer)

                                            style = "border: 2px solid dodgerblue; border-radius: 5px"

                                    @endif
                                    
                                    
                                    >
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="points student-score">
                                                    {{$key+=1}}
                                                </div>
                                            </div>
                                        </div>


                                        <p class="question" data-question-type="{{$item->typeofquiz}}">
                                            Drag the correct option and drop it onto the corresponding box. 
                                        </p>

                                        <div class="options p-3 mt-2" style="border:3px solid #3e416d;border-radius:6px;">
                                            @foreach ($item->drag as $questioninfo)
                                                <div class="drag-option btn bg-primary text-white m-1" data-target="drag-1">{{$questioninfo->description}}</div>
                                            @endforeach
                                        </div>

                                            @foreach($item->drop as $items)
                                        
                                                <p>

                                                    {{$items->sortid}}. {!! $items->question !!}

                                                </p>
                                            @endforeach

                                        </div>
                                    </div>
                            @endif

                            @if($item->typeofquiz == 7)
                                <div class="card mt-5 editcontent" id="question{{$item->id}}" 
                                    
                                    @if($item->answer)

                                            style = "border: 2px solid dodgerblue; border-radius: 5px"

                                    @endif
                                    
                                    >
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="points student-score">
                                                    {{$key+=1}}
                                                </div>
                                            </div>
                                        </div>

                                        <span style="font-weight:600;font-size:1.0pc">
                                            Fill in the blanks
                                        </span>

                                        

                                        @foreach($item->fill as $items)
                                        
                                                <p>
                                                    {{$items->sortid}}. {!! $items->question !!}

                                                </p>
                                        @endforeach

                                    </div>
                                </div>
                        @endif


                        @if($item->typeofquiz == 8)
                                <div class="card mt-5 editcontent" id="question{{$item->id}}" 
                                    

                                    @if($item->alreadyanswered)

                                            style = "border: 2px solid dodgerblue; border-radius: 5px"

                                    @endif
                                    


                                    >
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="points student-score">
                                                    {{$key+=1}}
                                                </div>
                                            </div>
                                        </div>

                                        <span style="font-weight:600;font-size:1.0pc">
                                            Enumeration
                                        </span>

                                        <ol class="list-group list-group-numbered p-3" type="A">
                                        <li>
                                            <p>{{$item->question}}</p>
                                        <ol>

                                        @php

                                            $numberOfTimes = $item->item

                                        @endphp
                                        
                                        @for ($i = 0; $i < $numberOfTimes; $i++)

                                        <div class="row">
                                            <div class="col-md-12">
                                                <li>
                                                    <p><input data-question-id="{{ $item->id }}" data-sortid={{ $i+1 }} data-question-type="8" class="answer-field d-inline form-control q-input" value="{{$item->answer[$i]}}" type="text"></p>
                                                </li>
                                            </div>
                                        </div>
                                        
                                        @endfor
                                        
                                        </ol>
                                        
                                        </li>
                                    </ol>
                                        

                                    </div>
                                </div>
                        @endif

                        @endforeach

                        <div class="save mb-5">
                        <div class="row">
                            <div class="col-md-12 mt-2 d-flex justify-content-end">
                                <div class="btn btn-success btn-lg" data-id="{{$headerid}}" data-testid="{{$testinfo->id}}" id="save-quiz">Submit</div>
                            </div>
                        </div>                        
                    
                        </div>
                    
                    <button id="scroll-to-bottom" class="btn btn-dark btn-lg mb-3 mr-3" style= "

                        position: fixed;
                        bottom: 0px;
                        right: 10px;
                        padding: 9px 15px 9px 15px !important;
                    "><i class="fas fa-arrow-circle-down"></i></button>




                </div>

                <div class="col-md-2 bg-light" style="position: fixed; top: 0; right: 0; margin:20px;">
                    <div class="row">

                        <div class="col-md-12 mt-2">
                            <div class="text-center timer-container shadow" role="alert">
                                <h4 class="mb-0"> Timer: </h4>
                                <div id="timer" class="timer mt-0">
                                    <span class="hours">0</span>:<span class="minutes">0</span>:<span class="seconds">00</span>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-12 p-3">

                                <div class="row shadow ">
                                        @for ($i = 0; $i <16; $i++)
                                            <div class="col-2 mt-1 mb-1 text-center circle" style="height: 40px" >
                                                <h6 class="border rounded text-dark align-middle bg-dark h-100 w-100" style="font-size: 30px">{{$i + 1}}</h6>
                                            </div>
                                        @endfor
                                </div>

                        </div> --}}

                    </div>

                        

                

                </div>

            
            </div>
        </div>

</body>



<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
{{-- <script src="{{asset('plugins/pace-progress/pace.min.js') }}"></script> --}}
<script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('templatefiles/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>

<script>


var time =  {!! json_encode($time) !!};
var headerId = {!! json_encode($headerid) !!};


function save()
{
    var dataId = $('#save-quiz').data('id');
    var testid = $('#save-quiz').data('testid');

    $.ajax({
            url: '/admissiontest/submitanswers',
            type:"GET",
            data: {
                recordid: dataId,
                testid  : testid
            },
            success: function(data){

                window.location.href = `/admissiontake/doneTake`;
                

            }
        })
}



$(document).ready(function(){

            console.log(headerId);


            var timerDisplay = $('#timer');
            var hoursDisplay = timerDisplay.find('.hours'); // Added this line
            var minutesDisplay = timerDisplay.find('.minutes'); // Added this line
            var secondsDisplay = timerDisplay.find('.seconds');

            var countdown = time * 60;
            var timerDisplay = $('#timer');
            var secondsDisplay = timerDisplay.find('.seconds');

            function updateTimer() {

                //To track the time remaining
                var time_remaining = countdown / 60;

                $.ajax({
                        url: '/admissiontest/save-timeremaining',
                        method: 'GET',
                        data: {
                            countdown : time_remaining,
                            headerId  : headerId,
                        }
                        });




                 // Calculate hours, minutes, and seconds
                var hours = Math.floor(countdown / 3600); // 3600 seconds in an hour
                var minutes = Math.floor((countdown % 3600) / 60); // Remaining minutes
                var seconds = Math.floor(countdown % 60); // Remaining seconds
                var secondsText = (seconds < 10 ? '0' : '') + seconds;

                // Update the display
                hoursDisplay.text(hours);
                minutesDisplay.text(minutes);
                secondsDisplay.text(secondsText);

                if (countdown <= 60) {
                    timerDisplay.addClass('red-text');
                } else {
                    timerDisplay.removeClass('red-text');
                }

                if (countdown <= 0) {
                    save();
                    timerDisplay.text("0:00");
                    // You can add code to execute when the timer reaches zero here.
                } else {
                    countdown--;
                    time--;
                    setTimeout(updateTimer, 1000);
                }
            }

                updateTimer();






});



</script>



<script>




    $(document).ready(function(){
                

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });


                function autoSaveAnswer(thisElement) {
                    // Get the answer data
                    var quizId = $('#quiz-info').data('quizid');
                    var headerId = {!! json_encode($headerid) !!};;
                    var answer = $(thisElement).val();
                    var questionId = $(thisElement).data('question-id');
                    var questionType = $(thisElement).data('question-type');
                    var sortId = $(thisElement).data('sortid');


                    

                    console.log(`student answer: ${answer}, sortid: ${sortId}, question-id: ${questionId}, question-type: ${questionType}`)


        
                    //Send an AJAX request to save the answer data

                    //Send an AJAX request to save the answer data
                    $.ajax({
                        url: '/admissiontest/save-answer',
                        method: 'GET',
                        data: {
                        chapterquizid : quizId,
                        answer: answer,
                        headerId: headerId,
                        questionType: questionType,
                        sortId: sortId,
                        question_id: questionId
                        },
                        success: function(response) {
                            if (response == 1){
                                console.log("Answer Inserted successfully");
                            }else{
                                console.log("Answer Updated successfully");
                            }


                                if(questionType != 5 && questionType != 7)
                                {

                                    changeBorder(questionId);

                                }else{


                                    var id = $(thisElement).data('borderid');
                                    changeBorder(id);
                                    


                                }

                            //Handle the response from the server if needed
                        }
                    });
                }

                function changeBorder(id) {
                    $("#question" + id).css({
                        "border": "2px solid dodgerblue",
                        "border-radius": "5px"
                    });
                }


                



                // drag and drop
                $( ".drag-option" ).draggable({
                    helper: "clone",
                    revertDuration: 100,
                    revert: 'invalid'
                });

                $( ".drop-option" ).droppable({
                    drop: function(event, ui) {

                        var dragElement = $(ui.draggable)
                        var dropElement = $(this)

                        dropElement.val(dragElement.text())
                        dropElement.addClass('bg-primary text-white')
                        dropElement.prop( "disabled", true );

                        dragElement.removeClass('bg-primary')
                        dragElement.addClass('bg-dark')

                        // auto save answer
                        autoSaveAnswer(dropElement)
                    }
                });

        // select choice by clicking label
        $("label").click(function() {
            var radioBtnId = $(this).attr("for");
            var inputElement = $(`input.answer-field[id='${radioBtnId}']`);

            inputElement.prop("checked", true);
            autoSaveAnswer(inputElement);
        });

        // auto save answer when switching to 
        $(document).on('change', '.answer-field', function(){
            autoSaveAnswer(this);
        });

        // show the button when the user scrolls past a certain point
        $(window).scroll(function() {
            if ($(this).scrollTop() > 700) {
                $('#scroll-to-bottom').fadeIn();
            } else {
                $('#scroll-to-bottom').fadeOut();
            }
        });
        
        // scroll to the bottom of the page when the button is clicked
        $('#scroll-to-bottom').click(function() {
            $('html, body').animate({
                scrollTop: $(document).height(),
            }, 'slow', function() {
                $('#scroll-to-bottom').fadeOut();
            });
            return false;
        });

        



        $(document).on('click', '#save-quiz', function() {

                var isvalid = true;

                console.log(isvalid)

                if (isvalid) {

                    Swal.fire({
                        title: 'Submit Answer?',
                        text: "You won't be able to revert this!",
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                        }).then((result) => {
                            if (result.value == true) {

                                save();
                            }
                        })

                        
                }

        });

        
    })
    </script>

