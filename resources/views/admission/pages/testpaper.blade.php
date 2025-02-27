@extends('admission.layouts.test')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <style>
        /* Style for the label associated with invalid radio inputs */
        .radio_input.is-invalid+label {
            color: red;
            /* Change text color to red for invalid radio inputs */
        }

        /* Style for the invalid feedback message */
        .invalid-feedback {
            display: block;
            /* Ensure the feedback message is visible */
            color: red;
            /* Set text color to red for the feedback message */
        }

        .uppercase {
            text-transform: uppercase;
        }
    </style>
@endsection

@section('content')
    {{-- <div class="modal fade" id="modalScore" tabindex="-1" role="dialog" aria-labelledby="modalScore" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align: center; width: 100%">Congrats! Score: 10</h5>

                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-primary" id="submit_pooling"><i
                            class="far fa-paper-plane mr-1"></i>ENTER</button>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="content-header">
        <div class="container">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            @if ($studinfo)
                                <h3> <strong> {{ $studinfo->lname }}, {{ $studinfo->fname }}</strong>
                                </h3>
                                <p>
                                    Level to Enroll: <strong> {{ $studinfo->levelname }}</strong> <br>
                                    {{-- <span>Desired Course: <strong> {{ $studinfo->courseabrv }}</strong></span> --}}
                                </p>
                            @endif
                        </div><!-- /.col -->
                        <div class="col-sm-6 text-right">
                            <label>Time Remaining:</label>
                            <h3 class="text-right"> <span id="timer">00:00:00</span></h3>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div>
                <div class="card-footer">
                    <div class="row justify-content-center">
                        <h5 class="m-0 uppercase">{{ $detail->examTitle }}</h5>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div>

    <div class="content text-dark">
        <div class="container">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title uppercase py-1"><strong>INSTRUCTION: </strong>
                        @foreach ($directions as $item)
                            {{ $item->textdirection }}.
                        @endforeach
                    </h5>
                </div>
                <div class="card-body">
                    @if (count($questions))
                        @foreach ($questions as $keyhead => $items)
                            <div class="main-container div{{ $keyhead }}" hidden>
                                @foreach ($items as $key => $question)
                                    <div class="question-wrapper" data-testtype="{{ $question->testtype }}">
                                        <p><label> <span class="question_number">{{ $key + 1 }}</span>.
                                                {{ $question->testquestion }}
                                            </label> <span class="text-success">({{ $question->points }} points)</span>
                                            @if (!empty($question->image) && $question->image != 'test_questions/')
                                                <div>
                                                    <img src="{{ asset('images/' . $question->image) }}"
                                                        alt="Question Image" style="max-width:100%; max-height:200px;">
                                                </div>
                                            @endif
                                        </p>
                                        <input class="testanswer form-control" type="text"
                                            value="{{ $question->encoded_answer }}" hidden>
                                        <div class="form-group ml-3">
                                            <div class="row options-wrapper">
                                                @php $options = explode('*^*', $question->testoptions); @endphp
                                                @foreach ($options as $keymain => $option)
                                                    @php $letter = chr($keymain + 65); @endphp <!-- A = 65 in ASCII -->
                                                    <div class="icheck-success d-inline col-md-12">
                                                        <input type="radio" class="radio_input"
                                                            data-letter="{{ $letter }}"
                                                            data-points="{{ $question->points }}"
                                                            name="radio{{ $key + 1 }}"
                                                            data-question-id="{{ $question->id }}"
                                                            id="radio{{ $key + 1 }}{{ $keymain + 1 }}"
                                                            {{ isset($question->history) && $question->history == $letter ? 'checked' : '' }}>
                                                        <label for="radio{{ $key + 1 }}{{ $keymain + 1 }}">
                                                            {{ $letter }}. {{ $option }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <p class="text-center">NO QUESTIONS AVAILABLE!</p>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="row justify-content-between">
                        <button type="button" class="btn btn-default btn_previous"><i
                                class="fas fa-step-backward mr-1"></i>Previous</button>
                        <button type="button" class="btn btn-primary btn_submit" hidden><i
                                class="far fa-paper-plane mr-1"></i>Submit</button>
                        <button type="button" class="btn btn-default btn_next"> Next <i
                                class="fas fa-step-forward ml-1"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script>
        var count = 0
        var score = 0
        var listOfCorrectAnswer = [];
        var qlength = {!! json_encode($count_question) !!}
        var examid = {!! json_encode($detail->examtitleId) !!}
        var studid = {!! json_encode($studinfo->id) !!}
        var poolingnumber = {!! json_encode($poolingnumber) !!}
        var totalMinutes = {!! json_encode($totalMinutes) !!}
        var jsonMessage = {!! json_encode($message) !!}
        var questions = {!! json_encode($questions) !!}
        var starttime = new Date()

        let preventNavigation = true;

        window.addEventListener('popstate', function() {
            if (preventNavigation) {
                history.pushState(null, null, document.URL);
            }
        });

        // Prevent page refresh
        window.addEventListener('beforeunload', function(e) {
            if (preventNavigation) {
                e.preventDefault();
                e.returnValue = ''; // For older browsers
            }
        });

        // Prevent navigation to other pages
        window.addEventListener('beforeunload', function(e) {
            if (preventNavigation && e.clientY < 0) {
                e.preventDefault();
                e.returnValue = ''; // For older browsers
            }
        });


        $(document).ready(function() {



            console.log(questions);

            if (jsonMessage) {
                notify('info', jsonMessage)
            }

            console.log(`Total Min: ${totalMinutes}`);


            console.log(starttime);
            startTimer(totalMinutes);
            // startTimer(0.1667);
            toastr.options = {
                closeButton: true,
                progressBar: true,
            };
            active_btn_nav()
            render_qwrapper()

            $('.btn_next').on('click', function() {
                if (count < qlength) {
                    count++
                }
                render_qwrapper()
            })

            $('.btn_previous').on('click', function() {
                if (count > 0) {
                    count--
                }
                render_qwrapper()
            })

            $('.radio_input').on('click', function() {
                var id = $(this).data('question-id')
                var points = $(this).data('points')
                var letter = $(this).data('letter')
                var encodedAnswer = $(this).closest('.question-wrapper').find('.testanswer').val();
                var testtype = $(this).closest('.question-wrapper').data('testtype');
                var decodedAnswer = atob(encodedAnswer);
                // console.log('testtype:' + testtype)
                addCorrectAnswer(id, letter, decodedAnswer, points, testtype)
            });

            $('.btn_submit').on('click', function() {
                var isvalid = true
                Swal.fire({
                    type: 'info',
                    title: 'Are you sure you want to submit your Test ?',
                    text: `This action cannot be undone! `,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        $('.question-wrapper').each(function() {
                            var isChecked = $(this).find('.radio_input:checked').length > 0;
                            if (!isChecked) {
                                isvalid = false
                                var questionNumber = $(this).find('.question_number')
                                    .text();
                                Toasts(`Please provide answer to question number ${questionNumber}`,
                                    questionNumber)
                                // Add is-invalid class to unchecked radio inputs
                                $(this).find('.radio_input:not(:checked)').addClass(
                                    'is-invalid');
                                // Display invalid feedback message
                                $(this).find('.invalid-feedback').show();
                            } else {
                                $(this).find('.radio_input').removeClass('is-invalid');
                                $(this).find('.invalid-feedback').hide();
                            }
                        });

                        if (isvalid) {
                            preventNavigation = false;

                            $.ajax({
                                url: '{{ route('submit.test') }}',
                                type: "GET",
                                data: {
                                    studid: studid,
                                    examid: examid,
                                    score: score,
                                    starttime: starttime,
                                },
                                success: function(data) {
                                    notify(data.status, data.message);
                                    if (data.status == 'success') {
                                        window.location.href =
                                            `/admission/diagnostictest?poolingnumber=${poolingnumber}`;
                                    }
                                },
                                error: function(xhr, status, error) {
                                    // console.log(xhr.responseJSON);
                                }
                            });
                        }
                    }
                });

            });

        })

        function autoSubmit(timeLimit) {
            preventNavigation = false; // Disable event listeners
            $.ajax({
                url: '{{ route('submit.test') }}',
                type: "GET",
                data: {
                    studid: studid,
                    examid: examid,
                    score: score
                },
                success: function(data) {
                    console.log(data)
                    notify(data.status, data.message);
                    window.location.href =
                        `/admission/diagnostictest?poolingnumber=${poolingnumber}`;
                    // Swal.fire({
                    //     type: 'warning',
                    //     title: 'Time Expired',
                    //     html: '<p>Your time limit of ' + (timeLimit / 60).toFixed(2) +
                    //         ' minutes has expired.</p>' +
                    //         '<p>Please click "Proceed" to submit.</p>',
                    //     showCancelButton: false,
                    //     confirmButtonColor: '#3085d6',
                    //     cancelButtonColor: '#d33',
                    //     confirmButtonText: 'Proceed',
                    //     allowOutsideClick: false, // Prevent closing by clicking outside
                    //     allowEscapeKey: false // Prevent closing by pressing Escape key
                    // }).then((result) => {
                    //     if (result.value) {
                    //         window.location.href =
                    //             `/admission/diagnostictest?poolingnumber=${poolingnumber}`;
                    //     }
                    // });
                },
                error: function(xhr, status, error) {
                    // console.log(xhr.responseJSON);
                }
            });
        }

        function startTimer(timeLimitInSeconds) {
            const timerElement = $('#timer');
            const timeLimit = parseInt(timeLimitInSeconds);

            const targetTime = new Date();
            targetTime.setSeconds(targetTime.getSeconds() + timeLimit);

            const timerInterval = setInterval(function() {
                const currentTime = new Date();
                const remainingTime = targetTime - currentTime;

                if (remainingTime > 0) {
                    const hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

                    timerElement.text(`${hours}:${minutes}:${seconds}`);
                } else {
                    clearInterval(timerInterval);
                    timerElement.text('Time Expired');
                    autoSubmit(timeLimit);
                }
            }, 1000);
        }


        function Toasts(msg, num) {
            $(document).Toasts('create', {
                class: 'bg-maroon',
                title: `Answer is required for Item ${num} !`,
                autohide: true,
                delay: 5000,
                body: msg
            })
        }

        function saveAnswerHistory(quesid, answer, status, points) {
            $.ajax({
                url: '{{ route('save.answer') }}',
                type: "GET",
                data: {
                    studid: studid,
                    id: quesid,
                    answer: answer,
                    status: status,
                    points: points,
                },
                success: function(data) {
                    console.log(data)
                    // notify(data.status, data.message);
                },
                error: function(xhr, status, error) {
                    // console.log(xhr.responseJSON);
                }
            });
        }

        function addCorrectAnswer(id, letter, answer, points, testtype) {
            // console.log(id, letter, answer, points, testtype);
            var obj = {}
            score = 0

            if (testtype == 1 || testtype == 2) {
                // console.log(`${letter} ${answer}`)
                if (letter == answer) {
                    saveAnswerHistory(id, letter, 'correct', points)
                    // console.log(letter);

                    // console.log(points)
                    if (!listOfCorrectAnswer.some(item => item.id == id)) {
                        obj.id = id
                        obj.points = points
                        // notify('success', 'Correct!')
                        listOfCorrectAnswer.push(obj)
                    } else {
                        // console.log('item already exist but correct!');
                    }
                    // console.log(listOfCorrectAnswer)
                } else {
                    saveAnswerHistory(id, letter, 'wrong', points)
                    if (listOfCorrectAnswer.some(item => item.id === id)) {
                        // notify('error', 'Wrong!')
                        obj.id = id
                        obj.points = points
                        var index = listOfCorrectAnswer.indexOf(obj.id)
                        listOfCorrectAnswer.splice(index, 1)
                    } else {
                        // console.log('item doenst exist and wrong!');
                    }
                    // console.log(listOfCorrectAnswer)
                }
            }


            listOfCorrectAnswer.forEach(element => {
                score += element.points;
            });
            // console.log(score);

        }


        function active_btn_nav() {
            if (count == 0) {
                $('.btn_previous').attr('disabled', true)
            } else {
                $('.btn_previous').attr('disabled', false)
            }

            if (count == qlength - 1) {
                $('.btn_next').attr('disabled', true)
                $('.btn_submit').attr('disabled', false)
                $('.btn_submit').attr('hidden', false)
            } else {
                $('.btn_next').attr('disabled', false)
                $('.btn_submit').attr('disabled', true)
                $('.btn_submit').attr('hidden', true)
            }

            if (qlength == 0) {
                $('.btn_next').attr('disabled', true)
            }

        }

        function render_qwrapper() {
            for (let index = 0; index < qlength; index++) {
                $('.div' + index).attr('hidden', true)
            }

            $('.div' + count).attr('hidden', false)
            active_btn_nav()
            window.scrollTo(0, 0);

        }
    </script>
@endsection
