{{-- @extends('guidanceV2.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v3.10.2/main.min.css') }}" />
@endsection --}}

@section('content')
    <!-- Content Header (Page header) -->
    {{--    
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Entrance Exam Setup</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="/guidance/admission/diagnosticsetup?page=2">
                                Diagnostic Setup
                            </a>
                        </li>
                        <li class="breadcrumb-item active"> Entrance Exam Setup</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>  --}}
    <!-- /.content-header -->
    {{-- <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-12">
                    <div class="card shadow" id="card_main">
                        <div class="card-header">
                            <h5 class="card-title">New Entrance Exam</h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <label class="mb-1">Choose Category</label>
                                    <select class="form-control" id="select-category" style="width: 100%;">
                                        <option value="">Select Category</option>
                                        @foreach (DB::table('guidance_test_category')->where('category_deleted', 0)->where('category_hastest', 1)->join('guidance_passing_rate', 'guidance_test_category.passing_rate_setup_id', '=', 'guidance_passing_rate.id')->select('guidance_test_category.*', 'guidance_passing_rate.description')->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->category_name }} -
                                                {{ $item->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="mb-1">Exam Title</label>
                                    <input type="text" class="form-control" id="examtitle">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="icheck-success d-inline">
                                            <input type="radio" id="automated" name="checking-option"
                                                class="checking-option" value="automated">
                                            <label for="automated">Automated Checking</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="icheck-success d-inline">
                                            <input type="radio" id="manual" name="checking-option"
                                                class="checking-option" value="manual">
                                            <label for="manual">Manual Checking</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer justify-content-between">
                            <a type="button" class="btn btn-danger"
                                href="/guidance/admission/diagnosticsetup?page=2">Cancel</a>
                            <button type="button" class="btn btn-primary" id="create_exam">Create Test</button>
                        </div>
                    </div>

                    <div id="card_test_direction" class="card shadow" hidden>
                        <div class="card-header">
                            <h5 class="card-title test_category_title">IQ TEST</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="mb-1">Test Directions</label>
                                <textarea class="form-control" id="textdirection" placeholder=""></textarea>
                                <span class="invalid-feedback" role="alert">
                                    <strong>Directions is required!</strong>
                                </span>
                            </div>
                        </div>
                        <div class="card-footer justify-content-between">
                            <button type="button" class="btn btn-success add_test_question"> <i class="fas fa-plus"></i>
                            </button>

                        </div>
                    </div>

                    <div class="list_test_question">
                    </div>

                    <a type="button" class="btn btn-success" href="/guidance/admission/diagnosticsetup?page=2"><i
                            class="far fa-paper-plane mr-1"></i> SAVE</a>
                </div>
            </div>
        </div>



    </section> --}}
@endsection

{{-- @section('footerjavascript')
    <script>
        // var listofTestQuestion = [];
        var jsonTestTypes = {!! json_encode($types) !!};
        var examid = 0;
        var count = 0;
        var hasOption = true;
        var test_category_title = '';
        var test_category_id = 0;
        var optionsArray = [];
        var optionsLetter = ['A', 'B', 'C', 'D'];
        var selectedValue = 0
        var currentdirectionid = 0
        var currentType = 1
        var listOfCards = []

        $(document).ready(function() {
            // console.log(jsonTestTypes)
            $('#select-category').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Category'
            })

            $('.add_test_question').on('click', function() {
                var button = $(this);
                var card = $('#card_test_direction');

                if (!$('#textdirection').val()) {
                    $('#textdirection').addClass('is-invalid')
                    notify('error', 'Test Directions is required!')
                    return
                } else {
                    $('#textdirection').removeClass('is-invalid');
                }
                if (!currentdirectionid) {
                    save_direction()
                }
                count++
                if (count == 0) {
                    // $('.add_test_question').prop('disabled', false);
                    card.find(':input').prop('disabled', false);
                    card.find('.btn').prop('disabled', false);
                    card.removeClass('bg-gray');
                } else {
                    // $('.add_test_question').prop('disabled', true);
                    card.find(':input').prop('disabled', true);
                    card.find('.btn').prop('disabled', true);
                    card.addClass('bg-gray');
                }

                var cardobj = {
                    id: 'card' + count,
                    type: currentType,
                    index: count,
                    questions: '',
                    options: [],
                    answer: ''
                }
                listOfCards.push(cardobj)
                add_question()
            })

            $(document).on('click', '.btn-delete', function() {
                var cardid = ''
                // Find the closest parent card and remove it
                if (selectedValue == 1) {
                    // testanswer = $(this).closest('.card_test_question').find('.multiplechoice .answer');
                    cardid = $(this).closest('.card_test_question').find('.cardid').val();

                } else if (selectedValue == 2) {
                    // testanswer = $(this).closest('.card_test_question').find(
                    //     '.trueorfalse input[type="radio"]:checked');
                    cardid = $(this).closest('.card_test_question').find('.cardid').val();
                } else if (selectedValue == 3) {
                    // testanswer = $(this).closest('.card_test_question').find('.trueorfalse .answer');
                }
                console.log(cardid)


                // Decrease the count when a card is deleted
                count--;

                // Update count badge in each remaining card
                const cards = document.querySelectorAll('.card_test_question');
                cards.forEach((card, index) => {
                    const badge = card.querySelector('.badge');
                    if (badge) {
                        badge.textContent = index + 1; // Update badge count
                    }
                });


                if (cardid) {

                    $.ajax({
                        type: 'GET',
                        url: '{{ route('delete.testquestion') }}',
                        data: {
                            id: cardid,
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status == 'success') {
                                if (count == 0) {
                                    $('.add_test_question').prop('disabled', false);
                                } else {
                                    $('.add_test_question').prop('disabled', true);
                                }
                                notify(response.status, response.message)
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseJSON);
                        }
                    });
                }
                console.log(count);
                if (count == 0) {
                    $('.add_test_question').prop('disabled', false);
                } else {
                    $('.add_test_question').prop('disabled', true);
                }

                $(this).closest('.card_test_question').remove();

                event.preventDefault();
            });

            $('#create_exam').on('click', function() {
                // add_question()
                // return
                var button = $(this);
                var cardIdToEdit = button.closest('.card').attr('id');
                var card = $(`#${cardIdToEdit}`);
                var isvalid = true;

                if (!$('#select-category').val()) {
                    $('#select-category').addClass('is-invalid');
                    notify('error', 'Category is required!')
                    isvalid = false;
                    return;
                } else {
                    $('#select-category').removeClass('is-invalid');
                }

                if (!$('#examtitle').val()) {
                    $('#examtitle').addClass('is-invalid');
                    notify('error', 'Title is required!')
                    isvalid = false;
                    return
                } else {
                    $('#examtitle').removeClass('is-invalid');
                }

                if (!$('input[name="checking-option"]:checked').val()) {
                    notify('error', 'Checking Option is required!')
                    isvalid = false;
                    return
                }

                if (isvalid) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('store.entranceexam') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            examtitle: $('#examtitle').val(),
                            categoryid: $('#select-category').val(),
                            checkingoption: $('input[name="checking-option"]:checked').val()
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status == 'success') {
                                notify(response.status, response.message)
                                $('#card_test_direction').prop('hidden', false)
                                $('#create_exam').prop('disabled', true);
                                examid = response.result.id
                                test_category_id = response.result.categoryid;
                                test_category_title = response.result.category_name;
                                $('.test_category_title').text(test_category_title)
                                card.find(':input').prop('disabled', true);
                                card.find('.btn').prop('disabled', true);
                                card.addClass('bg-gray');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseJSON);
                        }
                    });
                }
            })

            $(document).on('click', '.btn_edit', function(event) {
                event.stopPropagation(); // Prevent click event from bubbling up

                var button = $(this);
                var indexToEdit = button.data('index');
                var cardIdToEdit = button.closest('.card_test_question').attr('id');
                var card = $(`#${cardIdToEdit}`);

                console.log('Button clicked:', button);

                if (button.hasClass('edit-mode')) {
                    console.log('Switching to Save mode');
                    button.html('Save');
                    console.log('Button content:', button.html());

                    // Enable all inputs and buttons inside the card
                    card.find(':input').prop('disabled', false);
                    card.find('.btn').prop('disabled', false);
                    card.removeClass('bg-gray');
                    button.removeClass('edit-mode').addClass('save-mode');

                } else if (button.hasClass('save-mode')) {
                    console.log('Switching back to Edit mode');
                    button.html('Edit');
                    console.log('Button content:', button.html());

                    // Disable all inputs and buttons inside the card
                    card.find(':input').prop('disabled', true);
                    card.find('.btn').prop('disabled', true);
                    card.addClass('bg-gray');
                    button.removeClass('save-mode').addClass('edit-mode');

                    // Create card object based on element type
                    var cardobj = {
                        id: cardIdToEdit,
                        type: card.hasClass('multiplechoice') ? '1' : card.hasClass('trueorfalse') ?
                            '2' :
                            '3', // Assuming class 'multiplechoice' is for type 1 and 'trueorfalse' is for type 2
                        index: indexToEdit,
                        questions: '',
                        options: [],
                        answer: ''
                    };

                    cardobj.questions = card.find('.question').val();

                    if (card.hasClass('multiplechoice')) {
                        // Save inputs from multiplechoice div
                        card.find('.option').each(function() {
                            cardobj.options.push($(this).val());
                        });
                        cardobj.answer = card.find('.select-answer').val();
                    } else if (card.hasClass('trueorfalse')) {
                        // Save inputs from trueorfalse div
                        cardobj.options = ['true', 'false'];
                        card.find('input[type="radio"]').each(function() {
                            if ($(this).prop('checked')) {
                                cardobj.answer = $(this).val();
                            }
                        });
                    }

                    console.log(cardobj.questions);
                    console.log(cardobj.options);
                    console.log(cardobj.answer);
                    console.log(cardobj.type);
                    console.log(cardobj.serverid);

                    listOfCards[indexToEdit].type = cardobj.type
                    listOfCards[indexToEdit].questions = cardobj.questions
                    listOfCards[indexToEdit].options = cardobj.options
                    listOfCards[indexToEdit].answer = cardobj.answer
                    console.log('List of cards:', listOfCards);

                    if (listOfCards[indexToEdit].serverid) {

                        $.ajax({
                            type: 'GET',
                            url: '{{ route('update.test.question') }}',
                            data: {
                                serverid: listOfCards[indexToEdit].serverid,
                                testtype: listOfCards[indexToEdit].type,
                                testquestion: listOfCards[indexToEdit].questions,
                                testoptions: listOfCards[indexToEdit].options.join('*^*'),
                                testanswer: listOfCards[indexToEdit].answer,
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.status == 'success') {
                                    notify(response.status, response.message)
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(xhr.responseJSON);
                            }
                        });
                    }

                }

                console.log(listOfCards[indexToEdit]);
            });
        })

        function generateOptionsHTML(letters) {
            return letters.map(item =>
                `<div class="input-group col-md-6">
                <div class="input-group-prepend">
                    <span class="input-group-text">${item}</span>
                </div>
                <input type="text" class="form-control option" placeholder="option ${item}">
            </div>`
            ).join('');
        }

        function add_question() {
            console.log(listOfCards);
            $('.list_test_question').empty()
            listOfCards.forEach((element, key) => {
                var listoptions = '';
                if (element.options.length > 0 && element.type == 1) {
                    // Use map to generate HTML for each option
                    listoptions = element.options.map((item, index) =>
                        `<div class="input-group col-md-6">
                            <div class="input-group-prepend">
                                <span class="input-group-text">${String.fromCharCode(65 + index)}</span>
                            </div>
                            <input type="text" value="${item}" class="form-control option" placeholder="option ${item}">
                        </div>`
                    ).join('');
                } else {
                    // If options don't exist or element type is not 1, use default letters for options
                    listoptions = optionsLetter.map(item =>
                        `<div class="input-group col-md-6">
                            <div class="input-group-prepend">
                                <span class="input-group-text">${item}</span>
                            </div>
                            <input type="text" class="form-control option" placeholder="option ${item}">
                        </div>`
                    ).join('');
                }

                var classToApply = element.type == "1" ? "multiplechoice" :
                    element.type == "2" ? "trueorfalse" :
                    element.type == "3" ? "enumeration" : "";


                var renderHtml = `<div class="card shadow mt-2 card_test_question ${element.answer != "" ? "bg-gray" : ""}  ${classToApply}" id="${element.id}" >
                    <input type="text" class="cardid" val="${element.serverid != '' ? element.serverid : ""}" hidden>
                        <div class="card-header">
                            <h5 class="card-title test_category_title">${ test_category_title } ${element.id}</h5>
                            <div class="card-tools">
                                ${element.answer != "" ? 
                                `<span class="btn_edit edit-mode mr-1" data-index="${key}" style="cursor:pointer;" >Edit</span>`
                                : ""}
                            </div>
                        </div>

                        <div class="card-body">
                            <select class="form-control select-types testype${count}" style="width:100%;" >
                                ${jsonTestTypes.map(elem =>
                                     `<option value="${elem.id}" ${ element.type == elem.id  ? "selected" : "" } >${elem.typename}</option>`).join('')
                                    }
                            </select>
                           
                            <div class="form-group mt-2">
                                <label class="mb-1">Points Per Item</label>
                                <input type="number" min="1" value="1" id="points" class="form-control">
                            </div>
    
                            <div class="form-group mt-2">
                                <label class="mb-1">
                                    <span class="badge badge-primary p-1" style="border-radius: 100%; height: 20px; width: 20px;">${key + 1}</span>
                                </label>
                                ${
                                    element.questions !== '' ?
                                    `<textarea class="form-control question" rows="3" placeholder="Enter Question Here">${element.questions}</textarea>` :
                                    `<textarea class="form-control question" rows="3" placeholder="Enter Question Here"></textarea>`
                                }
                                <span class="invalid-feedback" role="alert">
                                    <strong>Question is required!</strong>
                                </span>
                            </div>

    
                            <div class="multiplechoice" ${ element.type != "1" ? 'hidden': '' }>
                                <div class="row listoption">
                                    ${listoptions}
                                </div>
                                <div class="row justify-content-end mt-2">
                                    <button type="button" class="btn btn-sm btn-primary mr-2 add_option${element.index}">Add Option</button>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label class="mb-1">Answer</label>
                                        <select class="form-control select-answer answer${element.id} answer" style="width:100%;">
                                            ${optionsLetter.map(item => `<option value="${item}" ${item == element.answer ? "selected" : ""}>${item}</option>` ).join('')}
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Answer is required!</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
        
                            <div class="trueorfalse" ${element.type != "2" ? 'hidden' : ''}>
                                <div class="form-group">
                                    <label class="mr-2">Answer</label>
                                    <div class="icheck-success d-inline mr-2">
                                        <input type="radio" id="radioPrimary1${element.index}" value="true" name="r${element.index}"
                                        ${element.answer == "true" && element.type == 2 ? 'checked' : "" } >
                                        <label for="radioPrimary1${element.index}">
                                            True
                                        </label>
                                    </div>
                                    <div class="icheck-success d-inline">
                                        <input type="radio" id="radioPrimary2${element.index}"" value="false" name="r${element.index}"
                                        ${element.answer == "false" && element.type == 2 ? 'checked' : "" } >
                                        <label for="radioPrimary2${element.index}">
                                            False
                                        </label>
                                    </div>
                                </div>
                            </div>
    
                            <div class="enumeration" hidden>
                                this is enumeration
                            </div>

                        </div>
                        <div class="card-footer justify-content-between">
                            <button type="button" class="btn btn-success btn_add_card_question" data-id="${element.id}" ${element.answer != "" ? "disabled" : ""}> <i class="fas fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-delete" data-id="${element.id}"><i class="far fa-trash-alt"></i></button>
                        </div>
                    </div>`
                $('.list_test_question').append(renderHtml)

                // Disable inputs and buttons inside the card if answer is not empty
                if (element.answer !== "") {
                    $('.btn_add_card_question').attr('hidden', true)
                    $(`#${element.id} :input`).prop('disabled', true); // Disable all inputs inside the card
                    $(`#${element.id} .btn`).prop('disabled', true); // Disable all buttons inside the card
                }
            })

            $('.btn_add_card_question').on('click', function() {
                var btnid = $(this).data('id');
                var cardobj = {}
                var validinput = true;
                optionsArray = []
                var testanswer;
                var cardid;
                var selectedType = $(this).closest('.card_test_question').find('.select-types').val();
                console.log(selectedType)
                const testquestion = $(this).closest('.card_test_question').find('.question');
                if (selectedType == 1) {
                    testanswer = $(this).closest('.card_test_question').find(
                        '.multiplechoice .answer');
                    cardid = $(this).closest('.card_test_question').find('.cardid');
                    optionsArray = []
                    $(this).closest('.card_test_question').find('.multiplechoice .option').each(
                        function() {
                            const optionValue = $(this).val();
                            if (!optionValue.trim()) {
                                validinput = false;
                                hasOption = false
                                notify('error', 'Options are required!')
                                return
                            }

                            if (validinput) {
                                optionsArray.push(optionValue);
                            }
                        });

                } else if (selectedType == 2) {
                    testanswer = $(this).closest('.card_test_question').find(
                        '.trueorfalse input[type="radio"]:checked');
                    cardid = $(this).closest('.card_test_question').find('.cardid');
                    optionsArray = []
                    optionsArray = ['true', 'false']
                } else if (selectedType == 3) {
                    // testanswer = $(this).closest('.card_test_question').find('.trueorfalse .answer');
                }
                // console.log(selectedValue, testquestion.val(), testanswer.val());
                console.log(testquestion.val().trim())
                if (!testquestion.val()) {
                    testquestion.addClass('is-invalid')
                    notify('error', 'Question is required!')
                    validinput = false
                    return
                } else {
                    testquestion.removeClass('is-invalid')
                }

                if (!testanswer.val()) {
                    testanswer.addClass('is-invalid')
                    notify('error', 'Answer is required!')
                    validinput = false
                    return
                } else {
                    testanswer.removeClass('is-invalid')
                }

                cardobj.id = btnid
                cardobj.type = selectedType
                cardobj.questions = testquestion.val()
                cardobj.options = optionsArray
                cardobj.answer = testanswer.val()

                console.log(testquestion.val());
                console.log(testanswer.val());
                console.log(selectedType);


                if (validinput) {
                    listOfCards.forEach(element => {
                        if (element.id == cardobj.id) {
                            element.type = cardobj.type;
                            element.questions = cardobj.questions;
                            element.options = cardobj.options;
                            element.answer = cardobj.answer;
                            console.log('Card updated:', element);
                            return
                        }
                    });

                    $.ajax({
                        type: 'GET',
                        url: '{{ route('store.testquestion') }}',
                        data: {
                            testtype: cardobj.type,
                            points: $('#points').val(),
                            examid: examid,
                            testquestion: cardobj.questions,
                            options: cardobj.options.join('*^*'),
                            answer: cardobj.answer

                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status == 'success') {
                                cardid.val(response.returnid)
                                const updatedCard = listOfCards.find(element => element.id === cardobj
                                    .id);
                                if (updatedCard) {
                                    updatedCard.serverid = response.returnid;
                                    console.log('Card updated:', updatedCard);
                                }

                                notify(response.status, response.message)
                                currentType = cardobj.type
                                console.log(listOfCards);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseJSON);
                        }
                    });
                    count++
                    var newcardobj = {
                        id: 'card' + count,
                        type: cardobj.type,
                        index: count,
                        questions: '',
                        options: [],
                        answer: '',
                        serverid: ''
                    }

                    listOfCards.push(newcardobj)

                    add_question()
                }
                // return

            })

            $('.select-types').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Test Types'
            });

            $('.select-answer').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Answer'
            });

            $('.select-types').on('change', function() {
                console.log($(this).val())
                var testtype = $(this).val()
                $(this).closest('.card_test_question').find(
                    '.multiplechoice, .trueorfalse, .enumeration').prop(
                    'hidden', true);
                if (testtype == 1) {
                    $(this).closest('.card_test_question').find('.multiplechoice').prop(
                        'hidden', false);
                } else if (testtype == 2) {
                    $(this).closest('.card_test_question').find('.trueorfalse').prop(
                        'hidden', false);
                } else if (testtype == 3) {
                    $(this).closest('.card_test_question').find('.enumeration').prop(
                        'hidden', false);
                }
            })

            if (currentType == 1) {
                $('.multiple' + count).prop('hidden', false);
            } else if (currentType == 2) {
                $('.tf' + count).prop('hidden', false);
            } else if (currentType == 3) {
                // $('.enumeration').prop('hidden', false);
            }

            $(document).on('click', '.add_option' + count, function() {
                var currentLetterIndex = optionsLetter.length;
                var nextLetter = String.fromCharCode(65 + currentLetterIndex); // Convert ASCII code to letter
                optionsLetter.push(nextLetter); // Add the next letter to the optionsLetter array
                currentLetterIndex++; // Increment the index for the next letter

                console.log(optionsLetter); // Output the updated array of letters
                $(this).closest('.card_test_question').find('.listoption').html(generateOptionsHTML(optionsLetter));
                $(this).closest('.card_test_question').find('.select-answer').select2({
                    data: optionsLetter,
                    allowClear: true,
                    theme: 'bootstrap4',
                    placeholder: 'Select Answer'
                })
            });

        }

        function save_direction() {
            $.ajax({
                type: 'GET',
                url: '{{ route('store.testdirection') }}',
                data: {
                    examid: examid,
                    textdirection: $('#textdirection').val(),
                },
                success: function(response) {
                    console.log(response);
                    if (response.status == 'success') {
                        $('.add_test_question').prop('disabled', true);
                        currentdirectionid = response.directionid;
                        notify(response.status, response.message)
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON);
                }
            });
        }
    </script>
@endsection --}}
