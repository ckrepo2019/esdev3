@php

        if(!Auth::check()){ 
                header("Location: " . URL::to('/'), true, 302);
                exit();
        }
        $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
        if(Session::get('currentPortal') == 17){
                $extend = 'superadmin.layouts.app2';
        }else if(Session::get('currentPortal') == 3){
                $extend = 'registrar.layouts.app';
        }else if(Session::get('currentPortal') == 6){
                $extend = 'adminPortal.layouts.app2';
        }else if(Session::get('currentPortal') == 2){
                $extend = 'principalsportal.layouts.app2';
        }
        else if(Session::get('currentPortal') == 8){
                $extend = 'admission.layouts.app2';
        }
        
        else{
                if(isset($check_refid->refid)){
                        if($check_refid->refid == 27){
                                $extend = 'academiccoor.layouts.app2';
                        }
                        if($check_refid->refid == 31){
                                $extend = 'guidance.layouts.app2';
                        }
                }else{
                header("Location: " . URL::to('/'), true, 302); 
                exit();
                }
                
        }
@endphp
@extends($extend)





@section('pagespecificscripts')


    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote.css')}}">

    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{asset('templatefiles/style.css')}}">
    <link rel="stylesheet" href="{{asset('templatefiles/framework.css')}}">
    <link rel="stylesheet" href="{{asset('templatefiles/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('templatefiles/icons.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/jquery-ui/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">

    <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                margin-top: 0px;
                color: black;
            }
            .shadow {
                box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                border: 0 !important;
            }
            .no-border-col{
                border-left: 0 !important;
                border-right: 0 !important;
            }
            .view_info {
                cursor: pointer;
            }
            .tableFixHead thead th {
                    position: sticky;
                    top: 0;
                    background-color: #fff;
                    outline: 2px solid #dee2e6;
                    outline-offset: -1px;
                
                }

            .swal-wide{
                width:850px !important;
            }
            .note-toolbar {
                    position: relative;
                    z-index: 0 !important;
                }
            .gfg_tooltip { 
                    position: relative; 
                    display: inline-block; 
                    border-bottom: 1px dotted black; 
                    background-color: gray; 
                    color: black; 
                    padding: 15px; 
                    text-align: center; 
                    display: inline-block; 
                    font-size: 16px; 
                } 
                .gfg_tooltip:hover {
                -ms-transform: scale(1.2); /* IE 9 */
                -webkit-transform: scale(1.2); /* Safari 3-8 */
                transform: scale(1.2); 
                }
                .gfg_tooltip .gfg_text { 
                    visibility: hidden; 
                    width: 120px; 
                    background-color: gray; 
                    color: white; 
                    text-align: center; 
                    border-radius: 6px; 
                    padding: 5px 0; 
                    position: absolute; 
                    z-index: 1; 
                    top: 5%; 
                    left: 115%; 
                } 

                .gfg_tooltip .gfg_text::after { 
                    content: ""; 
                    position: absolute; 
                    top: 50%; 
                    right: 100%; 
                    margin-top: -5px; 
                    border-width: 5px; 
                    border-style: solid; 
                    border-color: transparent gray transparent  
                                    transparent; 
                } 

                .gfg_tooltip:hover .gfg_text { 
                    visibility: visible; 
                } 
                iframe {
                    width: 100%;
                    height: 100%;
                }
                .col-content {
                    background-color: #fff;
                    }
                .select2-container .select2-selection--single {
                    
                    height: auto;
                    margin-bottom: 20px;
                }

                .select2-selection__choice {
                    font-size: 16px; /* Change the font size */
                    background-color: #5cb85c !important; /* Change the background color */
                    color:rgb(255, 255, 255) !important;
                    border-radius: 5px; /* Add rounded corners */
                    padding: 2px 8px; /* Add some padding */
                    margin-right: 5px; /* Add some space between items */
                }

                .select2-container--default .select2-selection--single .select2-selection__arrow {

                    top: 10px;

                }

                #header.col-content {
                    border-top: 10px solid #673AB7  !important;
                    border-radius: 10px  !important;
                }

                .editcontent{
                    border: 2px solid black;
                    border-radius: 5px;
                
                }

                @media (max-width: 768px) {
                    /* Styles for mobile devices */
                    .editcontent {
                        /* Add your mobile-specific styles here */
                        /* For example */
                        border: 2px solid black;
                    }
                }

                #my-card:hover {
                    transform: scale(1.1); /* make the card 10% larger on hover */
                    background-color: red; /* change the background color to red on hover */
                    cursor: pointer; /* show a pointer cursor on hover */
                }

                #my-card:hover #lessoncardtitle {
                    color: white;
                }

                .col-12 .m-2 {
                    width:500px;
                }

                .selected {
                    background-color: green;
                }

                .border-red {
                    border-color: red;
                }

                .form-check-label.choice {
                    border: none;
                    background-color: transparent;
                    font-size: inherit;
                    font-family: inherit;
                    cursor: pointer;
                    padding: 0;
                }
</style>
@endsection

@section('modalSection')



<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Category Input</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Input field for category -->
                <div class="form-group">
                    <label for="category">Category:</label>
                    <input type="text" class="form-control" id="category">
                </div>
                <!-- Plus button to add item to the list -->
                <button type="button" class="btn btn-success" id="addItemBtn">Add Category</button>
                <!-- <li> element displayed after input field -->
                <ul class="list-group mt-2" id="category-list"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



@endsection






@section('content')






        <body class="dark">
            <div class="container" id="quiz-info" data-testid="{{ $id }}">
                <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="contentcontainer">
                                <div class="row p-4 dragrow">
                                    <div class="col-lg-1 col-2 rowhidden d-flex align-items-center">
                                        <div class="btn-group-verticals">
                                            <a class="btn btn-sm text-white gfg_tooltip newrow" style="background-color: #3175c2; border: 3px solid #1d62b7;">
                                                <i class="fas fa-plus m-0"></i><span class="gfg_text">Add Question</span>
                                            </a> 
                                            <a class="btn btn-sm text-white gfg_tooltip category" style="background-color: #3175c2; border: 3px solid #1d62b7; z-index: 10">
                                                <i class="fas fa-columns m-0"></i><span class="gfg_text">Add Category</span>
                                            </a>                                              
                                        </div>
                                    </div>
                    
                                    <div class="col-lg-11 col-10 editcontent col-content" id = "header">
                                        <div class="card mt-5 shadow-none  border-0">
                                            <div class="card-header" id="quizTitle">
                                                <h3 class="text-center title" contenteditable="true" >{{$testinfo->title}}</h3>
                                            </div>
                                            <div class="card-body">
                                                <form>
                                                    <div class="form-group">
                                                    <label for="description">Test Description:</label>
                                                    <textarea class="form-control description" id="description" data-id ="{{ $id }}" value= '{{$testinfo->description}}' rows="1">{{$testinfo->description}}</textarea>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                        
                                    

                                    @if(count($testquestions) > 0)
                                        @foreach($testquestions as $question)


                                            {{-- Multiple choice --}}

                                            @if($question->typeofquiz == 1)
                                            <div id="{{$question->id}}" class="row p-4 dragrow{{$question->id}}">
                                                <div class="col-lg-1 col-2 rowhidden buttonholder{{$question->id}} d-flex align-items-center">
                                                </div>
                                                <div id="{{$question->id}}" class="col-lg-11 col-10 editcontent col-content identifier{{$question->id}}">
                                                    <div class="card mt-5 shadow-none border-0">
                                                        <div class="card-header">
                                                            <div class="row justify-content-end">
                                                                <div class="col-sm-6 col-md-6 col-lg-6 mr-1" id="categorylist{{$question->id}}">
                                                                    @if(isset($question->category))
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Category" value="{{$question->category}}">    
                                                                    @else
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Assign Category">    
                                                                    @endif
                                                                </div>
                                                                <div class="col-sm-6 col-md-10 col-lg-6 mr-1 quizarea">
                                                                    <select class="form-control quiztype" data-id="{{$question->id}}" id="quiztype{{$question->id}}">
                                                                    <option value="multiple_choice">Multiple Choice</option>
                                                                    <option value="short_answer">Short Answer</option>
                                                                    <option value="paragraph_answer">Essay</option>
                                                                    <option value="instruction">Instruction</option>
                                                                    <option value="drag_drop">Drag & drop</option>
                                                                    {{-- <option value="image">Image Answer</option> --}}
                                                                    <option value="fill_n_blanks">Fill in the blanks</option>
                                                                    <option value="enumeration">Enumeration</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-12 m-2" id="quiztioncontent{{$question->id}}">
                                                                    <div class="row">
                                                                        <div class="col-12 m-2">
                                                                            <textarea class="form-control question" placeholder="Untitled question" data-id ="{{$question->id}}" data-question-type ="1" id="multiplechoice{{$question->id}}" >{{$question->question}}</textarea>
                                                                        </div>
                                                                        @php
                                                                        $quizchoices = DB::table('admissiontestchoices')
                                                                            ->where('questionid', $question->id)
                                                                            ->where('deleted', 0)
                                                                            ->orderBy('sortid')
                                                                            ->get();


                                                                        @endphp
                                                                        <div class="col-12" id="list_option{{$question->id}}">
                                                                            @if(count($quizchoices) > 0)
                                                                            @foreach($quizchoices as $choice)
                                                                            <div id="containerchoices{{$question->id}}">
                                                                                <input class="form-check-input ml-2" type="radio" name="option1" value="1">
                                                                                <label class="form-check-label ml-5 option{{$question->id}}" id="option{{$question->id}}" contenteditable="true">{{$choice->description}} 
                                                                                    @if($choice->answer==1)
                                                                                        <span><i class="fa fa-check" style="color:rgb(7, 255, 7)" aria-hidden="true"></i></span>
                                                                                    @endif
                                                                                    <span id= "deletechoice" data-id= "{{$choice->id}}" class = "pl-1"><i class="fa fa-trash" aria-hidden="true"></i></span>
                                                                                </label>
                                                                            </div>
                                                                            
                                                                            @endforeach
                                                                            @endif
                                                                            
                                                                        </div>                                                                        
                                                                        <div class="col-12">
                                                                            <div class="row justify-content-end p-3 mt-2">
                                                                                    <button class="btn btn-success addoption" id="{{$question->id}}">Add option</button>
                                                                            </div>
                                                                            <button class="btn btn-primary btn-sm answer-key" id="{{$question->id}}">Set Up Answer</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            
                    

                                            {{-- Short Answer --}}

                                            @elseif($question->typeofquiz == 2)
                                            <div id="{{$question->id}}" class="row p-4 dragrow{{$question->id}}">
                                                <div class="col-lg-1 col-2 rowhidden buttonholder{{$question->id}} d-flex align-items-center">
                                                </div>
                                                <div id="{{$question->id}}" class="col-lg-11 col-10 editcontent col-content identifier{{$question->id}}">
                                                    <div class="card mt-5 shadow-none border-0">
                                                        <div class="card-header">
                                                            <div class="row justify-content-end">
                                                                <div class="col-sm-6 col-md-6 col-lg-6 mr-1" id="categorylist{{$question->id}}">
                                                                    @if(isset($question->category))
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Category" value="{{$question->category}}">    
                                                                    @else
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Assign Category">    
                                                                    @endif
                                                                </div>
                                                                <div class="col-sm-6 col-md-10 col-lg-6 mr-1 quizarea">
                                                                    <select class="form-control quiztype" data-id="{{$question->id}}" id="quiztype{{$question->id}}">
                                                                    <option value="short_answer">Short Answer</option>
                                                                    <option value="multiple_choice">Multiple Choice</option>
                                                                    <option value="paragraph_answer">Essay</option>
                                                                    <option value="instruction">Instruction</option>
                                                                    <option value="drag_drop">Drag & drop</option>
                                                                    <option value="image">Image Answer</option>
                                                                    <option value="fill_n_blanks">Fill in the blanks</option>
                                                                    <option value="enumeration">Enumeration</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-12 m-2" id="quiztioncontent{{$question->id}}">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <h6><label class= "  ml-2" for="number_question' + parentId + '">Points:</label></h6>
                                                                            <input type="number" class="form-control m-2 addpoints w-100" placeholder="Required" data-id= "{{$question->id}}" id="Required{{$question->id}}" value="{{$question->points}}">
                                                                            <textarea class="form-control m-2 question2" placeholder="Untitled question" data-id ="{{$question->id}}" data-question-type ="2" id="shortz_answer_question{{$question->id}}" >{{$question->question}}</textarea>
                                                                            {{-- <h6><label class= "ml-2" for="shortz_answer_answer{{$question->id}}">Guide answer:</label></h6>
                                                                            <textarea class="form-control m-2 setanswer" data-id="{{$question->id}}" placeholder="Set Guide Answer" style="height: 20px !important;" id="shortz_answer_answer{{$question->id}}" >{{$question->guideanswer}}</textarea> --}}
                                                                        </div>
                                                                        <div class="col-12">    
                                                                            <input type="text" class="form-control mt-2 ml-2" placeholder="Short answer text" disabled>
                                                                        </div>                                                
                                                                    </div>
                                                                </div>
                                                            </div>        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                            
                        

                                            {{-- Paragraph --}}
                                            
                                            @elseif($question->typeofquiz == 3)
                                            <div id="{{$question->id}}" class="row p-4 dragrow{{$question->id}}">
                                                <div class="col-lg-1 col-2 rowhidden buttonholder{{$question->id}} d-flex align-items-center">
                                                </div>
                                                <div id="{{$question->id}}" class="col-lg-11 col-10 editcontent col-content identifier{{$question->id}}">
                                                    <div class="card mt-5 shadow-none border-0">
                                                        <div class="card-header">
                                                            <div class="row justify-content-end">
                                                                <div class="col-sm-6 col-md-6 col-lg-6 mr-1" id="categorylist{{$question->id}}">
                                                                    @if(isset($question->category))
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Category" value="{{$question->category}}">    
                                                                    @else
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Assign Category">    
                                                                    @endif
                                                                </div>
                                                                <div class="col-sm-6 col-md-10 col-lg-6 mr-1 quizarea">
                                                                    <select class="form-control quiztype" data-id="{{$question->id}}" id="quiztype{{$question->id}}">
                                                                    <option value="paragraph_answer">Essay</option>
                                                                    <option value="multiple_choice">Multiple Choice</option>
                                                                    <option value="short_answer">Short Answer</option>
                                                                    <option value="instruction">Instruction</option>
                                                                    <option value="drag_drop">Drag & drop</option>
                                                                    {{-- <option value="image">Image Answer</option> --}}
                                                                    <option value="fill_n_blanks">Fill in the blanks</option>
                                                                    <option value="enumeration">Enumeration</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-12 mt-2" id="quiztioncontent{{$question->id}}">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <h6><label class= "ml-2" for="number_question' + parentId + '">Points:</label></h6>
                                                                            <input type="number" class="form-control m-2 addpoints w-100" placeholder="Required" data-id= "{{$question->id}}" id="Required{{$question->id}}" value="{{$question->points}}">
                                                                            <textarea class="form-control m-2 question2" placeholder="Untitled question" data-id ="{{$question->id}}" data-question-type ="3" id="long_answer_question{{$question->id}}" >{{$question->question}}</textarea>
                                                                            {{-- <h6><label class= "ml-2" for="long_answer_answer{{$question->id}}">Guide answer: </label></h6>
                                                                            <textarea class="form-control m-2 setanswer" data-id="{{$question->id}}" placeholder="Set Guide Answer" style="height: 20px !important;" id="long_answer_answer{{$question->id}}" >{{$question->guideanswer}}</textarea> --}}
                                                                        </div>
                                                                        <div class="col-12">    
                                                                            <input type="text" class="form-control mt-2 ml-2" placeholder="Long answer text" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                    

                                            {{-- Instruction --}}

                                            @elseif($question->typeofquiz == 4)
                                            <div id="{{$question->id}}" class="row p-4 dragrow{{$question->id}}">
                                                <div class="col-lg-1 col-2 rowhidden buttonholder{{$question->id}} d-flex align-items-center">
                                                </div>
                                                <div id="{{$question->id}}" class="col-lg-11 col-10 editcontent col-content identifier{{$question->id}}">
                                                    <div class="card mt-5 shadow-none border-0">
                                                        <div class="card-header">
                                                            <div class="row justify-content-end">
                                                                <div class="col-sm-6 col-md-6 col-lg-6 mr-1" id="categorylist{{$question->id}}">
                                                                    @if(isset($question->category))
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Category" value="{{$question->category}}">    
                                                                    @else
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Assign Category">    
                                                                    @endif
                                                                </div>
                                                                <div class="col-sm-6 col-md-10 col-lg-6 mr-1 quizarea">
                                                                    <select class="form-control quiztype" data-id="{{$question->id}}" id="quiztype{{$question->id}}">
                                                                    <option value="instruction">Instruction</option>
                                                                    <option value="multiple_choice">Multiple Choice</option>
                                                                    <option value="short_answer">Short Answer</option>
                                                                    <option value="paragraph_answer">Essay</option>
                                                                    <option value="drag_drop">Drag & drop</option>
                                                                    {{-- <option value="image">Image Answer</option> --}}
                                                                    <option value="fill_n_blanks">Fill in the blanks</option>
                                                                    <option value="enumeration">Enumeration</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-12 mt-2" id="quiztioncontent{{$question->id}}">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <textarea class="form-control m-2 instruction" placeholder="Untitled question" id="instruction_item{{$question->id}}">{{$question->question}}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                            
                                            
                                            {{-- Drag and Drop --}}
                                            @elseif($question->typeofquiz == 5)
                                            <div id="{{$question->id}}" class="row p-4 dragrow{{$question->id}}">
                                                <div class="col-lg-1 col-2 rowhidden buttonholder{{$question->id}} d-flex align-items-center">
                                                </div>
                                                <div id="{{$question->id}}" class="col-lg-11 col-10 editcontent col-content identifier{{$question->id}}">
                                                    <div class="card mt-5 shadow-none border-0">
                                                        <div class="card-body">
                                                            <div class="row justify-content-end">
                                                                <div class="col-sm-6 col-md-6 col-lg-6 mr-1" id="categorylist{{$question->id}}">
                                                                    @if(isset($question->category))
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Category" value="{{$question->category}}">    
                                                                    @else
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Assign Category">    
                                                                    @endif
                                                                </div>
                                                                <div class="col-sm-6 col-md-10 col-lg-6 mr-1 quizarea">
                                                                    <select class="form-control quiztype" data-id="{{$question->id}}" id="quiztype{{$question->id}}">
                                                                        <option value="drag_drop">Drag & drop</option>
                                                                        <option value="multiple_choice">Multiple Choice</option>
                                                                        <option value="short_answer">Short Answer</option>
                                                                        <option value="paragraph_answer">Essay</option>
                                                                        <option value="instruction">Instruction</option>
                                                                        {{-- <option value="image">Image Answer</option> --}}
                                                                        <option value="fill_n_blanks">Fill in the blanks</option>
                                                                        <option value="enumeration">Enumeration</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-12 m-2" id="quiztioncontent{{$question->id}}">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="options p-3 mt-2" id="options{{$question->id}}" style="border:3px solid #3e416d;border-radius:6px;">
                                                                                @php
                                                                                $dragoptions = DB::table('admission_drag_option')
                                                                                    ->where('questionid', $question->id)
                                                                                    ->where('deleted', 0)
                                                                                    ->orderBy('sortid')
                                                                                    ->get();
                                                                                @endphp
                                                                                @foreach($dragoptions as $item)
                                                                                <div class="drag-option btn bg-primary text-white m-1 drag{{$question->id}}" contentEditable="true" data-target="drag-1">
                                                                                    {{ $item->description }}
                                                                                </div>
                                                                                @endforeach
                                                                            </div>
                                                                            <div class="row justify-content-end p-3 mt-2">
                                                                                <button class="btn btn-success add_drag_option" id="{{$question->id}}">Add drag option</button>
                                                                            </div>
                                                                            <p><b>Note: </b>To set up the drop area, please input [~input] where you want the drop zone to appear. Ex. The planet ~input is the biggest planet in the solar system</p>
                                                                            @php
                                                                            $dropquestions = DB::table('admission_drop_question')
                                                                                ->where('questionid', $question->id)
                                                                                ->where('deleted', 0)
                                                                                ->orderBy('sortid')
                                                                                ->get();

                                                                            foreach($dropquestions as $item){
                                                                                $answer = DB::table('admission_drop_answer')
                                                                                    ->where('headerid', $item->id)
                                                                                    ->orderBy('sortid')
                                                                                    ->pluck('answer');

                                                                                $answerString = implode(',', $answer->toArray());

                                                                                $item->answer = $answerString;
                                                                            }
                                                                            @endphp
                                                                            <div id="item_question{{$question->id}}">
                                                                            @foreach($dropquestions as $item)
                                                                            
                                                                                <input type="text" class="form-control drop{{$question->id}}" style="margin-top: 10px; border: 2px solid dodgerblue; color: black;" placeholder="Item text" value="{{$item->question}}">
                                                                                <span>Answer :  
                                                                                    @if(empty($item->answer))
                                                                                    <em>undefined</em>
                                                                                    @else
                                                                                    <em>{{$item->answer}}</em>.
                                                                                    @endif
                                                                                </span>
                                                                            
                                                                            @endforeach
                                                                            </div>

                                                                            <div class="row justify-content-end p-3 mt-2">
                                                                                <button class="btn btn-success add_drag_question" id="{{$question->id}}">Add drop question</button>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <button class="btn btn-primary btn-sm answer-key-drag" id="{{$question->id}}">Set Up Answer</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                                    
                                        

                                            {{-- Image answer --}}

                                            @elseif($question->typeofquiz == 6)
                                            <div id="{{$question->id}}" class="row p-4 dragrow{{$question->id}}">
                                                <div class="col-lg-1 col-2 rowhidden buttonholder{{$question->id}} d-flex align-items-center">
                                                </div>
                                                <div id="{{$question->id}}" class="col-lg-11 col-10 editcontent col-content identifier{{$question->id}}">
                                                    <div class="card mt-5 shadow-none border-0">
                                                        <div class="card-header">
                                                            <div class="row justify-content-end">
                                                                <div class="col-sm-6 col-md-6 col-lg-6 mr-1" id="categorylist{{$question->id}}">
                                                                    @if(isset($question->category))
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Category" value="{{$question->category}}">    
                                                                    @else
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Assign Category">    
                                                                    @endif
                                                                </div>
                                                                <div class="col-sm-6 col-md-10 col-lg-6 mr-1 quizarea">
                                                                    <select class="form-control quiztype" data-id="{{$question->id}}" id="quiztype{{$question->id}}">
                                                                    <option value="multiple_choice">Multiple Choice</option>
                                                                    <option value="short_answer">Short Answer</option>
                                                                    <option value="paragraph_answer">Essay</option>
                                                                    <option value="instruction">Instruction</option>
                                                                    <option value="drag_drop">Drag & drop</option>
                                                                    <option value="fill_n_blanks">Fill in the blanks</option>
                                                                    <option value="enumeration">Enumeration</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-12 m-2" id="quiztioncontent{{$question->id}}">
                                                                    <div class="row">
                                                                        <div class="col-12 m-2">
                                                                            <h6><label class= "ml-2" for="number_question{{$question->id}}">Points:</label></h6>
                                                                            <input type="number" class="form-control m-2 addpoints" placeholder="Required" data-id= "{{$question->id}}" id="Required{{$question->id}}" value="{{$question->points}}">
                                                                            <textarea class="form-control imageanswer" placeholder="Untitled instruction" style="height: 20px !important;" id="image_item{{$question->id}}">{{$question->question}}</textarea>
                                                                            <input type="file" class="mt-2" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                    

                                            {{-- Fill in the blanks --}}
                                            @elseif($question->typeofquiz ==7)
                                            <div id="{{$question->id}}" class="row p-4 dragrow{{$question->id}}">
                                                <div class="col-lg-1 col-2 rowhidden buttonholder{{$question->id}} d-flex align-items-center">
                                                </div>
                                                <div id="{{$question->id}}" class="col-lg-11 col-10 editcontent col-content identifier{{$question->id}}">
                                                    <div class="card mt-5 shadow-none border-0">
                                                        <div class="card-header">
                                                            <div class="row justify-content-end">
                                                                <div class="col-sm-6 col-md-6 col-lg-6 mr-1" id="categorylist{{$question->id}}">
                                                                    @if(isset($question->category))
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Category" value="{{$question->category}}">    
                                                                    @else
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Assign Category">    
                                                                    @endif
                                                                </div>
                                                                <div class="col-sm-6 col-md-10 col-lg-6 mr-1 quizarea">
                                                                    <select class="form-control quiztype" data-id="{{$question->id}}" id="quiztype{{$question->id}}">
                                                                    <option value="fill_n_blanks">Fill in the blanks</option>
                                                                    <option value="multiple_choice">Multiple Choice</option>
                                                                    <option value="short_answer">Short Answer</option>
                                                                    <option value="paragraph_answer">Essay</option>
                                                                    <option value="instruction">Instruction</option>
                                                                    <option value="drag_drop">Drag & drop</option>
                                                                    {{-- <option value="image">Image Answer</option> --}}
                                                                    <option value="enumeration">Enumeration</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-12 m-2" id="quiztioncontent{{$question->id}}">
                                                                    <div class="row">
                                                                        <div class="col-12 m-2">
                                                                            <p><b>Note: </b>To set up the blanks, please input [~input] where you want the blank to appear. Ex. The planet ~input is the biggest planet in the solar system</p>
                                                                            @php
                                                                                $fillquestions = DB::table('admission_fill_question')
                                                                                    ->where('questionid', $question->id)
                                                                                    ->where('deleted', 0)
                                                                                    ->orderBy('sortid')
                                                                                    ->get();

                                                                                foreach($fillquestions as $item){

                                                                                    $answer = DB::table('admission_fill_answer')
                                                                                        ->where('headerid', $item->id)
                                                                                        ->orderBy('sortid')
                                                                                        ->pluck('answer');

                                                                                    if(isset($answer)){
                                                                                    $answerString = implode(',', $answer->toArray());

                                                                                    $item->answer = $answerString;
                                                                                        }
                                                                                    }
                                                                            @endphp

                                                                            <div id="item_fill{{$question->id}}">
                                                                                @foreach($fillquestions as $item)
                                                                                    <input type="text" class="form-control fill{{$question->id}}" style="margin-top: 10px; border: 2px solid dodgerblue; color: black;" placeholder="Item text" value="{{$item->question}}">
                                                                                
                                                                                    <span>Answer :  
                                                                                                @if(empty($item->answer))
                                                                                                    <em>undefined</em>
                                                                                                @else
                                                                                                    <em>{{$item->answer}}</em>.
                                                                                                @endif
                                                                                                
                                                                                    </span>
                                                                                @endforeach
                                                                            </div>
                                                                        
                                                                            
                                                                            <div class="row justify-content-end p-3 mt-2">
                                                                                <button class="btn btn-success add_fill_question"  id="{{$question->id}}">Add fill question</button>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <button class="btn btn-primary btn-sm answer-key-fill" id="{{$question->id}}">Set Up Answer</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                        


                                            

                                            {{-- Enumerations --}}

                                            @elseif($question->typeofquiz == 8)
                                            <div id="{{$question->id}}" class="row p-4 dragrow{{$question->id}}">
                                                <div class="col-lg-1 col-2 rowhidden buttonholder{{$question->id}} d-flex align-items-center">
                                                </div>
                                                <div id="{{$question->id}}" class="col-lg-11 col-10 editcontent col-content identifier{{$question->id}}">
                                                    <div class="card mt-5 shadow-none border-0">
                                                        <div class="card-header">
                                                            <div class="row justify-content-end">
                                                                <div class="col-sm-6 col-md-6 col-lg-6 mr-1" id="categorylist{{$question->id}}">
                                                                    @if(isset($question->category))
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Category" value="{{$question->category}}">    
                                                                    @else
                                                                        <input type="text" class="form-control categorylist" data-id= "{{$question->id}}" placeholder="Assign Category">    
                                                                    @endif
                                                                </div>
                                                                <div class="col-sm-6 col-md-10 col-lg-6 mr-1 quizarea">
                                                                    <select class="form-control quiztype" data-id="{{$question->id}}" id="quiztype{{$question->id}}">
                                                                    <option value="enumeration">Enumeration</option>
                                                                    <option value="multiple_choice">Multiple Choice</option>
                                                                    <option value="short_answer">Short Answer</option>
                                                                    <option value="paragraph_answer">Essay</option>
                                                                    <option value="instruction">Instruction</option>
                                                                    <option value="drag_drop">Drag & drop</option>
                                                                    {{-- <option value="image">Image Answer</option> --}}
                                                                    <option value="fill_n_blanks">Fill in the blanks</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-12 m-2" id="quiztioncontent{{$question->id}}">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <textarea class="form-control enumeration mt-2 ml-2" placeholder="Untitled question" data-id ="{{$question->id}}" data-question-type ="1" id="enumerationquestion{{$question->id}}" >{{$question->question}}</textarea>
                                                                            <h5><label class= "ml-2 mt-2" for="itemcount">Item:</label></h5>
                                                                            <input type="number" class="form-control mt-2 ml-2 itemcount" placeholder="Item count" data-id= "{{$question->id}}" id="enumerationitem{{$question->id}}" value= "{{$question->item}}">
                                                                            <div id="item_option{{$question->id}}">
                                                                                @php

                                                                                $numberOfTimes = $question->item;

                                                                                $answer = DB::table('admission_test_enum_answer')
                                                                                    ->where('headerid', $question->id)
                                                                                    ->where('deleted', 0)
                                                                                    ->select('answer')
                                                                                    ->orderBy('sortid')
                                                                                    ->get();

                                        
                                                                                @endphp

                                                                                <span class ="ml-2 mt-2"><b>Answer :   </b>
                                                                                                @if($question->ordered == 0)
                                                                                                        [IN ORDER]
                                                                                                @else
                                                                                                        [RANDOM]
                                                                                                @endif

                                                                                                @if(empty($answerarray))
                                                                                                    <em>Answer not set</em>
                                                                                                @endif
                                                                                                
                                                                                </span>

                                                                                @for ($i = 0; $i < $numberOfTimes; $i++)
                                                                                    <div class="input-group mt-2">
                                                                                        <input type="text" class="form-control mt-2 ml-2" placeholder="Item text &nbsp;{{$i+1}}" 
                                                                                        @if(isset($answer[$i]->answer))
                                                                                            value="{{$answer[$i]->answer}}" 
                                                                                        @endif
                                                                                        disabled>
                                                                                    </div>
                                                                                @endfor
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 mt-2">
                                                                            <button class="btn btn-primary btn-sm ml-2 answer-key-enum" id="{{$question->id}}">Set Up Answer</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                    @endforeach
                                @endif
                            </div>

                        <div class="save mb-5">
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="btn btn-warning mr-2  btn-lg"  id="save-quiz-score">Save</div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div> 

        </body>

    
@endsection


@section('footerjavascript')




    
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('templatefiles/framework.js')}}"></script>
    <script src="{{asset('templatefiles/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('plugins/summernote/summernote-bs4.js')}}"></script>
    <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>



<script>
        
        



        // Global variables for tracking click count, quiz IDs, and last quiz type
        var click = 0;
        var option = 0;
        var id;
        var last_id;
        var last_quiz_type = 'multiple_choice';


        function calculate(id) {
            var total = 0;

            var promise = new Promise(function(resolve, reject) {
                $('.fill' + last_id).each(function() {
                    const value = $(this).val();
                    var description = $(this).val();
                    var keyword = "~input";
                    var count = (description.match(new RegExp(keyword, "g")) || []).length;

                    total += parseInt(count);
                    console.log('Count: ' + count);
                });

                var questionid = id;
                resolve({ total: total, questionid: questionid });
            });

            promise.then(function(data) {
                console.log('Total: ' + data.total);
                
                $.ajax({
                    type: "GET",
                    url: "/admissiontest/quiz/fill/setpoints",
                    data: {
                        total: data.total,
                        questionid: data.questionid
                    }
                });
            });
        }



        function calculateDrag(id) {
            console.log("Here:");
            var total = 0;
            console.log(id);

            var promise = new Promise(function(resolve, reject) {
                $('.drop' + id).each(function() {
                    const value = $(this).val();
                    var description = $(this).val();
                    var keyword = "~input";
                    var count = (description.match(new RegExp(keyword, "g")) || []).length;

                    total += parseInt(count);
                    console.log('Count: ' + count);
                });

                var questionid = id;

                resolve({ total: total, questionid: questionid });
            });

            promise.then(function(data) {
                console.log('Totalpointsdapat: ' + data.total);
                console.log('ID: ' + data.questionid);
                $.ajax({
                    type: "GET",
                    url: "/admissiontest/quiz/drag/setpoints",
                    data: {
                        total: data.total,
                        questionid: data.questionid
                    }
                });
            });
        }





        $(document).ready(function(){
            
            
            const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 4000,
            });


                    $('.instruction').summernote({
                                    height: 200,
                                    toolbar: [
                                            ['style', ['bold', 'italic', 'underline']],
                                            ]
                                    });

                    $('.imageanswer').summernote({
                                    height: 200,
                                    toolbar: [
                                            ['style', ['bold', 'italic', 'underline']],
                                            ]
                                    });


                    $('.question').summernote({
                                    height: 200,
                                    toolbar: [
                                            ['style', ['bold', 'italic', 'underline']],
                                            ]
                                    });
                    $('.question2').summernote({
                                    height: 200,
                                    toolbar: [
                                            ['style', ['bold', 'italic', 'underline']],
                                            ]
                                    });

                    $(document).on('input', '.title', function() {
                        var title = $(this).text();
                        var quizId = $('#quiz-info').data('testid');
                        console.log("Description: " ,title, "ID: " , quizId);

                        $.ajax({
                                    type: "GET",
                                    url: "/admissiontest/quiz/title",
                                    data: { 
                                        title : title,
                                        quizId      : quizId
                                            }
                            });


                    });

                    $(document).on('change', '.description', function() {
                        var description = $(this).val();
                        var quizId = $('#quiz-info').data('testid');
                        console.log("Description: " ,description, "ID: " , quizId);

                        $.ajax({
                                    type: "GET",
                                    url: "/admissiontest/quiz/description",
                                    data: { 
                                        description : description,
                                        quizId: quizId
                                            },
                                    success: function(response) {

                                        Toast.fire({
                                            type: 'success',
                                            title: 'Description successfully Save!'
                                        })
                                        
                                    },
                                    error: function(xhr) {
                                        // Handle error here
                                        Toast.fire({
                                            type: 'error',
                                            title: 'Something went wrong'
                                        })
                                    }
                            });


                    });

                    $(document).keydown(function(e) {
                    // Check if Ctrl key is pressed and the "S" key is also pressed
                    if (e.ctrlKey && (e.key === "s" || e.key === "S")) {
                        e.preventDefault(); // Prevent the default browser behavior (e.g., saving the page)
                        // Your custom code to handle Ctrl+S
                        // Trigger click event on the button with ID "save-quiz"
                        
                        $('#save-quiz').trigger('click');
                        console.log("Ctrl+S pressed");
                        // Call a function or perform any action you want
                    }

                    if (e.ctrlKey && (e.key === "i" || e.key === "I")) {
                        e.preventDefault(); // Prevent the default browser behavior (e.g., saving the page)
                        // Your custom code to handle Ctrl+S
                        // Trigger click event on the button with ID "save-quiz"
                        
                        var focusedInput = $(':focus');

                        // Insert "~input" at the current cursor position
                        var currentText = focusedInput.val();
                        var cursorPosition = focusedInput.prop("selectionStart");
                        var updatedText = currentText.slice(0, cursorPosition) + "~input" + currentText.slice(cursorPosition);
                        focusedInput.val(updatedText);

                        // Call a function or perform any action you want
                    }


                    if (e.ctrlKey && (e.key === "u" || e.key === "U")) {
                        e.preventDefault(); // Prevent the default browser behavior (e.g., saving the page)
                        // Your custom code to handle Ctrl+S
                        // Trigger click event on the button with ID "save-quiz"
                        
                        var focusedInput = $(':focus');

                        // Insert "~input" at the current cursor position
                        var currentText = focusedInput.val();
                        var cursorPosition = focusedInput.prop("selectionStart");
                        var updatedText = currentText.slice(0, cursorPosition) + "<u>  </u>" + currentText.slice(cursorPosition);
                        focusedInput.val(updatedText);

                        // Call a function or perform any action you want
                    }
                });


                    $(document).on('click', '.newrow', function(){
                        var addrow;
                        var quizId = $('#quiz-info').data('testid');

                        console.log(quizId);

                        $.ajax({
                                    type: "get",
                                    dataType: 'json',
                                    url: "/admissiontest/addquestion",
                                    data: { 
                                        quizId : quizId,
                                            },
                                    success: function(response) {

                                        addrow =  response.id;
                                        console.log("Row ID: ", typeof addrow);
                        
                                        $('.ui-helper-hidden-accessible').remove();
                                        option= 1;
                                        $('.btn-group-vertical').remove();
                                        $(this).closest('.row').find('.rowhidden').empty()
                                        $('.contentcontainer').append(
                                            '<div id="'+addrow+'" class="row p-4 dragrow'+addrow+'">' +
                                                '<div class="col-lg-1 col-2 rowhidden d-flex align-items-center buttonholder'+addrow+'">' + 
                                                '<div class="btn-group-vertical">' +
                                                    '<a class="btn btn-sm text-white gfg_tooltip delrow" id="'+addrow+'" style="background-color: #3175c2; border: 3px solid #1d62b7;">' +
                                                    '<i class="fas fa-trash m-0"></i><span class="gfg_text">Delete</span>' +
                                                    '</a>' + 
                                                    '<a class="btn btn-sm text-white gfg_tooltip category" style="background-color: #3175c2; border: 3px solid #1d62b7; z-index: 10">' +
                                                        '<i class="fas fa-columns m-0"></i><span class="gfg_text">Add Category</span>' +
                                                    '</a>' +
                                                    '<a class="btn btn-sm text-white gfg_tooltip newrow" style="background-color: #3175c2; border: 3px solid #1d62b7;">' +
                                                    '<i class="fas fa-plus m-0"></i><span class="gfg_text">Add Question</span>' +
                                                    '</a>' +                                      
                                                '</div>' +
                                                '</div>' +
                                                '<div id="'+addrow+'" class="col-lg-11 col-10 editcontent col-content identifier'+addrow+'">' +
                                                '<div class="card mt-5 shadow-none border-0">' +
                                                '<div class="card-header">' +
                                                    '<div class="row ml-2 justify-content-end">' +
                                                        '<div class="col-sm-6 col-md-6 col-lg-6 mr-1" id="categorylist'+addrow+'">' +
                                                                '<input type="text" class="form-control categorylist" value="'+response.category+'" data-id= "'+addrow+'" placeholder="Category">' +   
                                                        '</div>' +
                                                        '<div class="col-sm-6 col-md-10 col-lg-6 mr-1 quizarea">' +
                                                            '<select class="form-control quiztype" data-id="'+addrow+'" id="quiztype'+addrow+'">' +
                                                            '<option value="multiple_choice">Multiple Choice</option>' +
                                                            '<option value="instruction">Instruction</option>' +
                                                            '<option value="short_answer">Short Answer</option>' +
                                                            '<option value="paragraph_answer">Essay</option>'+
                                                            '<option value="enumeration">Enumeration</option>' +
                                                            '<option value="fill_n_blanks">Fill in the blanks</option>' +
                                                            '<option value="drag_drop">Drag & drop</option>' +
                                                            // '<option value="image">Image Answer</option>' +
                                                            '</select>' +
                                                        '</div>' +
                                                        '<div class="col-12">'+
                                                        '<div id="quiztioncontent'+addrow+'">'+
                                                            '<div class="row">' +  
                                                                '<div class="col-12 m-2">'+
                                                                    '<textarea class="form-control question" placeholder="Untitled question" data-id ="'+addrow+'" data-question-type ="1" style="height: 20px !important;" id="multiplechoice'+addrow+'" ></textarea>'+
                                                                '</div>'+
                                                                '<div class="col-12"  id="list_option'+addrow+'">' +
                                                                    '<input class="form-check-input ml-2" type="radio" name="option1" value="1">'+
                                                                    '<label class="form-check-label option'+addrow+' ml-5" id="option'+addrow+'" contenteditable="true">Option '+option+'</label>'+
                                                                '</div>' +
                                                            '</div>' +
                                                            '<div class="col-12">' +
                                                                '<div class="row justify-content-end p-3 mt-2">'+
                                                                    '<button class="btn btn-success addoption" id="'+addrow+'">Add option</button>'+
                                                                '</div>'+
                                                                ' <button class="btn btn-primary btn-sm answer-key" id="'+addrow+'">Set Up Answer</button>'+
                                                            '</div>'+
                                                        '</div>' +
                                                    '</div>' +
                                                '</div>' +
                                                '</div>' +
                                                '</div>' +
                                            '</div>');




                                            $('.question').summernote({
                                                height: 200,
                                                toolbar: [
                                                        ['style', ['bold', 'italic', 'underline']],
                                                        ]
                                                });


                                        
                                    },
                                    error: function(xhr) {
                                    // Handle error here
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Something went wrong'
                                    })
                                    }
                        });


                    })



                $(document).on('click', '.addoption', function(){
                    option+=1;
                    var parentId = $(this).attr('id');
                    console.log("ID: ", parentId)
                    $('#list_option' + parentId).append(`<input class="form-check-input ml-2" type="radio" name="option1" value="1">
                    <label class="form-check-label ml-5  option${parentId}" contenteditable="true">Option ${option}</label>`)
                
                })


                $(document).on('click', '#save-quiz-score', function(){

                    if(last_id != undefined){
                

                    }else{
                    
                        UIkit.notification({
                                    message: '<span uk-type="type: check"></span> Please click a question again and then try again',
                                    status: 'danger',
                                    timeout: 1000
                                    });

                    }
                })



                    
                $(document).on('click', '.editcontent', function(){
                    last_id = id;
                    $('.editcontent').css({
                        "border-right": "2px solid black",
                        "border-radius": "5px"                    
                    });

                    $(this).css({
                        "border-right": "5px solid dodgerblue",
                        "border-radius": "5px"
                    });
                        


                        
                        id = $(this).attr('id');
                        console.log("Thiss ID: ", id);

                        $('.btn-group-vertical').remove();

                        $(this).closest('.row').find('.buttonholder' + id).append(
                                    '<div class="btn-group-vertical">' +
                                        '<a class="btn btn-sm text-white gfg_tooltip delrow" id="'+id+'" style="background-color: #3175c2; border: 3px solid #1d62b7;">' +
                                        '<i class="fas fa-trash m-0"></i><span class="gfg_text">Delete</span>' + '</a>' + 
                                        '<a class="btn btn-sm text-white gfg_tooltip category" style="background-color: #3175c2; border: 3px solid #1d62b7; z-index: 10">' +
                                        '<i class="fas fa-columns m-0"></i><span class="gfg_text">Add Category</span>' +
                                        '</a>' +      
                                        '<a class="btn btn-sm text-white gfg_tooltip newrow" style="background-color: #3175c2; border: 3px solid #1d62b7;">' +
                                        '<i class="fas fa-plus m-0"></i><span class="gfg_text">Add Question</span>' +
                                        '</a>' +    
                                    '</div>' +
                                    '</div>'
                        );
                        
                            
                    
        
                        })




                $(document).on('click', '.delrow', function(){
                    console.log("$(this).attr('id')");
                    console.log($(this).attr('id'));
                    var rowid = $(this).attr('id');

                    $('.dragrow' + rowid).remove()

                            $.ajax({
                                    type: "get",
                                    dataType: 'json',
                                    url: "/admissiontest/delquestion",
                                    data: { 
                                        id: rowid
                                            },
                                    complete: function(data){
                                }
                            });
                });

                $(document).on('click', '.itemchoices', function(){
                            var radioBtnId = $(this).attr("for");
                            var inputElement = $(`input.answer-field[id='${radioBtnId}']`);

                            inputElement.prop("checked", true);
                            autoSaveAnswer(inputElement);

                            $('.form-check-label').css({
                                "background": "white",
                                "padding": "2px"
            
                            });

                            $(this).css({
                                "background-color": "rgba(0, 128, 0, 0.5)",
                                "padding": "2px"
            
                                // "padding": "20px",
                            });
                        });

                        
                $(document).on('click', function(event) {
                    last_quiz_type = $('#quiztype' + last_id).val();

                        if (!$(event.target).closest('.dragrow' + last_id).length || $(event.target).hasClass('delrow') && !$(event.target).hasClass('category') && !$(event.target).hasClass('select2-selection')) {

                        
                            console.log("Last ID: ", last_id);
                            
                            switch (last_quiz_type) {
                                case 'multiple_choice':
                                    var textareaValue = $('#multiplechoice' + last_id).val();
                                    console.log("Question: ", textareaValue);
                                    console.log("Quiztype: ", last_quiz_type);

                                    var formData = new FormData();
                                    formData.append('question', textareaValue);
                                    formData.append('typeofquiz', 1);
                                    formData.append('id', last_id);
                



                                    if (textareaValue.length != 0) {

                                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                                    
                
                                                                    // Set the CSRF token in the request headers
                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken
                                        }
                                    });

                                    $.ajax({
                                        url: "/admissiontest/createquestion",
                                        type: 'POST', // Use lowercase 'type' instead of 'TYPE'
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        success: function(response) {
                                            // Handle the success response
                                            console.log(response);
                                            Toast.fire({
                                                type: 'success',
                                                title: 'All the changes have been saved'
                                            });
                                        },
                                        error: function(xhr) {
                                            // Handle error here
                                            Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong'
                                            });
                                        }
                                    });

                                    


                                    var i = 1;
                                    $('.option' + last_id).each(function() {
                                        const value = $(this).text().trim();
                                        console.log(i);

                                        $.ajax({
                                        type: "get",
                                        dataType: 'json',
                                        url: "/admissiontest/createchoices",
                                        data: {
                                            questionid: last_id,
                                            sortid: i,
                                            description: value
                                        },
                                        success: function(response) {
                                            console.log("Choices Successfully saved!");
                                        },
                                        error: function(xhr) {
                                            // Handle error here
                                        }
                                        });

                                        i += 1;
                                    });
                                    }

                                    break;

                                case 'enumeration':
                                    var textareaValue = $('#enumerationquestion' + last_id).val();
                                    var itemval = $('#enumerationitem' + last_id).val();
                                    console.log("Question: ", textareaValue);
                                    console.log("Quiztype: ", last_quiz_type);
                                    console.log("Enumeration ITEM: ", itemval);

    
                                    $('#enumerationitem' + last_id).css('border-color', 'black');
                                    $.ajax({
                                        type: "get",
                                        dataType: 'json',
                                        url: "/admissiontest/createquestionitem",
                                        data: {
                                        question: textareaValue,
                                        typeofquiz: 8,
                                        item: itemval,
                                        id: last_id
                                        },
                                        success: function(response) {
                                        if (response == 1) {
                                            Toast.fire({
                                            type: 'success',
                                            title: 'All the changes have been saved'
                                            });
                                        }
                                        },
                                        error: function(xhr) {
                                            // Handle error here
                                            Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong'
                                            })
                                    }
                                    });

                                    break;

                                case 'instruction':
                                    var textareaValue = $('#instruction_item' + last_id).val();
                                    console.log("Question: ", textareaValue);
                                    console.log("Quiztype: ", last_quiz_type);


                                    var formData = new FormData();
                                    formData.append('question', textareaValue);
                                    formData.append('typeofquiz', 4);
                                    formData.append('id', last_id);
                



                                    if (textareaValue.length != 0) {

                                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                                    
                
                                        $.ajaxSetup({
                                            headers: {
                                                'X-CSRF-TOKEN': csrfToken  // Set the CSRF token in the request headers
                                            }
                                        });

                                        $.ajax({
                                            url: "/admissiontest/createquestion",
                                            type: 'POST', // Use lowercase 'type' instead of 'TYPE'
                                            data: formData,
                                            processData: false,
                                            contentType: false,
                                            success: function(response) {
                                                // Handle the success response
                                                console.log(response);
                                                Toast.fire({
                                                    type: 'success',
                                                    title: 'All the changes have been saved'
                                                });
                                            },
                                            error: function(xhr) {
                                                // Handle error here
                                                Toast.fire({
                                                    type: 'error',
                                                    title: 'Something went wrong'
                                                });
                                            }
                                        });


                                    }


                                    break;

                                case 'drag_drop':


                                    var formData = new FormData();
                                    formData.append('question', textareaValue);
                                    formData.append('typeofquiz', 5);
                                    formData.append('id', last_id);

                                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                                    
                
                                        $.ajaxSetup({
                                            headers: {
                                                'X-CSRF-TOKEN': csrfToken  // Set the CSRF token in the request headers
                                            }
                                        });

                                        $.ajax({
                                            url: "/admissiontest/createquestion",
                                            type: 'POST', // Use lowercase 'type' instead of 'TYPE'
                                            data: formData,
                                            processData: false,
                                            contentType: false,
                                            success: function(response) {
                                                // Handle the success response
                                                console.log(response);
                                                Toast.fire({
                                                    type: 'success',
                                                    title: 'All the changes have been saved'
                                                });
                                            },
                                            error: function(xhr) {
                                                // Handle error here
                                                Toast.fire({
                                                    type: 'error',
                                                    title: 'Something went wrong'
                                                });
                                            }
                                        });

                                    var i = 1;
                                    console.log("Drag and Drop");
                                    

                                    $('.drag' + last_id).each(function() {
                                    const value = $(this).text().trim();
                                    console.log(value);

                                    $.ajax({
                                        type: "get",
                                        dataType: 'json',
                                        url: "/admissiontest/createdragoption",
                                        data: {
                                        questionid: last_id,
                                        sortid: i,
                                        description: value
                                        },
                                        success: function(response) {
                                        console.log("Options Successfully saved!");
                                        },
                                        error: function(xhr) {
                                            // Handle error here
                                            Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong'
                                            })
                                        }
                                    });

                                    i += 1;
                                    });

                                    var i = 1;

                                    calculateDrag(last_id)
                                    $('.drop' + last_id).each(function() {
                                    const value = $(this).val();
                                    console.log(value);




                                    $.ajax({
                                        type: "get",
                                        dataType: 'json',
                                        url: "/admissiontest/createdropquestion",
                                        data: {
                                        questionid: last_id,
                                        sortid: i,
                                        description: value
                                        },
                                        success: function(response) {
                                            console.log("Drop question Successfully saved!");
                                        },
                                        error: function(xhr) {
                                            // Handle error here
                                            Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong'
                                            })
                                        }
                                    });

                                    i += 1;
                                    });

                                    Toast.fire({
                                    type: 'success',
                                    title: 'All the changes have been saved'
                                    });

                                    break;

                                case 'short_answer':
                                case 'paragraph_answer':
                                    var points = $('#Required' + last_id).val();
                                    console.log("points: ", points);
                                    console.log("Quiztype: ", last_quiz_type);

                                    if(last_quiz_type == 'short_answer'){

                                        var typeofquiz = 2; 
                                        var question = $('#shortz_answer_question' + last_id).val();


                                    }else{

                                        var typeofquiz = 3;
                                        var question = $('#long_answer_question' + last_id).val();

                                    }

                                    var formData = new FormData();
                                    formData.append('question', question);
                                    formData.append('typeofquiz', typeofquiz);
                                    formData.append('id', last_id);
                                    formData.append('points', points);

                                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                                    
                
                                                                    // Set the CSRF token in the request headers
                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken
                                        }
                                    });

                                    $.ajax({
                                        url: "/admissiontest/createquestion2",
                                        type: 'POST', // Use lowercase 'type' instead of 'TYPE'
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        success: function(response) {
                                            // Handle the success response
                                            console.log(response);
                                            Toast.fire({
                                                type: 'success',
                                                title: 'All the changes have been saved'
                                            });
                                        },
                                        error: function(xhr) {
                                            // Handle error here
                                            Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong'
                                            });
                                        }
                                    });


                                    if (!points) {
                                    UIkit.notification({
                                        message: '<span uk-type="type: close"></span> Points Required!',
                                        status: 'error',
                                        timeout: 1000
                                    });
                                    $('#Required' + last_id).focus();
                                    }

                                    break;

                                case 'fill_n_blanks':
                                    var i = 1;
                                    var validation = true;

                                    var formData = new FormData();
                                    formData.append('question', 'fill in the blanks');
                                    formData.append('typeofquiz', 7);
                                    formData.append('id', last_id);
                

                                    $('.fill' + last_id).each(function() {
                                        const value = $(this).val();
                                        console.log(i);
                                        console.log(value);

                                        if (i == 1) {

                                            calculate(last_id);

                                            var csrfToken = $('meta[name="csrf-token"]').attr('content');
                                    
                
                                            $.ajaxSetup({
                                                headers: {
                                                    'X-CSRF-TOKEN': csrfToken  // Set the CSRF token in the request headers
                                                }
                                            });

                                            $.ajax({
                                                url: "/admissiontest/createquestion",
                                                type: 'POST', // Use lowercase 'type' instead of 'TYPE'
                                                data: formData,
                                                processData: false,
                                                contentType: false,
                                                success: function(response) {
                                                    // Handle the success response
                                                    console.log(response);
                                                    Toast.fire({
                                                        type: 'success',
                                                        title: 'All the changes have been saved'
                                                    });
                                                },
                                                error: function(xhr) {
                                                    // Handle error here
                                                    Toast.fire({
                                                        type: 'error',
                                                        title: 'Something went wrong'
                                                    });
                                                }
                                            });
                                        }

                                        $.ajax({
                                        type: "get",
                                        url: "/admissiontest/createfillquestion",
                                        data: {
                                            questionid: last_id,
                                            sortid: i,
                                            description: value
                                        },
                                        success: function(response) {
                                            
                                            console.log("Fill question Successfully saved!");

                                            if(response == 1){


                                                Toast.fire({
                                                type: 'error',
                                                title: 'One question has been deleted'
                                                });



                                            }
                                        },
                                        error: function(xhr) {
                                                    // Handle error here
                                                    Toast.fire({
                                                        type: 'error',
                                                        title: 'Something went wrong'
                                                    })
                                        }
                                    });

                                        i += 1;
                                    });

                                break;
                            }

                    }
                });

                    



            
                    
                    var option = 1;
                    var enumerationitem = 1;
                    $(document).on('change', '.quiztype', function(){
                        var parentId = $(this).attr('data-id');
                        var addrowid = $(this).attr('id');
                        var select_quiz_type = $(this).val();
                        last_quiz_type = select_quiz_type;
                        console.log(select_quiz_type);
                        console.log("Add row ID: ", addrowid)
                        console.log("ID: ", parentId)



                        switch (select_quiz_type) {
                            case 'multiple_choice':
                                option = 1;
                                $('#quiztioncontent' + parentId).empty().append(
                                    '<div class="row">' +
                                    '<div class="col-12 m-2">' +
                                    '<textarea class="form-control question" placeholder="Untitled question" data-id="' + parentId + '" data-question-type="1" style="height: 20px !important;" id="multiplechoice' + parentId + '"></textarea>' +
                                    '</div>' +
                                    '<div class="col-12" id="list_option' + parentId + '">' +
                                    '<input class="form-check-input ml-2" type="radio" name="option1" value="1">' +
                                    '<label class="form-check-label option' + parentId + ' ml-5" id="option' + parentId + '" contenteditable="true">Option ' + option + '</label>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="col-12">' +
                                    '<div class="row justify-content-end p-3 mt-2">' +
                                    '<button class="btn btn-success addoption" id="' + parentId + '">Add option</button>' +
                                    '</div>' +
                                    '<button class="btn btn-primary btn-sm answer-key" id="' + parentId + '">Set Up Answer</button>' +
                                    '</div>'
                                );
                                break;

                            case 'short_answer':
                                $('#quiztioncontent' + parentId).empty().append(
                                    '<h6><label class="ml-2" for="number_question' + parentId + '">Points:</label></h6>' +
                                    '<input type="number" class="form-control m-2 addpoints w-100" placeholder="Required" data-id="' + parentId + '" id="Required' + parentId + '">' +
                                    '<h6><label class="ml-2" for="shortz_answer_question' + parentId + '">Question:</label></h6>' +
                                    '<textarea class="form-control m-2 question2 shortz_answer_question"' + parentId + '" data-id="' + parentId + '" data-question-type="2" placeholder="Untitled question" style="height: 20px !important;" id="shortz_answer_question' + parentId + '"></textarea>' +
                                    // '<h6><label class="ml-2" for="shortz_answer_answer' + parentId + '">Guide answer</label></h6>' +
                                    // '<textarea class="form-control m-2 setanswer" data-id="' + parentId + '" placeholder="Only admin can view the Guide answer" style="height: 20px !important;" id="shortz_answer_answer' + parentId + '"></textarea>' +
                                    '<input type="text" class="form-control mt-2 ml-2" placeholder="Short answer text" disabled>'
                                );

                                $('.question2').summernote({
                                    height: 200,
                                    toolbar: [
                                            ['style', ['bold', 'italic', 'underline']],
                                            ]
                                    });
                                break;

                            case 'paragraph_answer':
                                $('#quiztioncontent' + parentId).empty().append(
                                    '<h6><label class="ml-2" for="number_question' + parentId + '">Points:</label></h6>' +
                                    '<input type="number" class="form-control m-2 addpoints w-100" placeholder="Required" data-id="' + parentId + '" id="Required' + parentId + '">' +
                                    '<h6><label class="ml-2" for="long_answer_question' + parentId + '">Question:</label></h6>' +
                                    '<textarea class="form-control m-2 question2" placeholder="Untitled question" data-id="' + parentId + '" data-question-type="3" style="height: 20px !important;" id="long_answer_question' + parentId + '"></textarea>' +
                                    // '<h6><label class="ml-2" for="Long_answer_answer' + parentId + '">Guide answer:</label></h6>' +
                                    // '<textarea class="form-control m-2 setanswer"' + parentId + '" placeholder="Set Guide Answer:" style="height: 20px !important;" id="long_answer_answer' + parentId + '"></textarea>' +
                                    '<textarea type="text" class="form-control mt-2 ml-2" placeholder="Long answer text" disabled>'
                                );

                                $('.question2').summernote({
                                    height: 200,
                                    toolbar: [
                                            ['style', ['bold', 'italic', 'underline']],
                                            ]
                                    });
                                break;

                            case 'enumeration':
                                enumerationitem = 1;
                                $('#quiztioncontent' + parentId).empty().append(
                                    '<div class="row">' +
                                    '<div class="col-12 m-2">' +
                                    '<textarea class="form-control m-2" placeholder="Untitled question" data-id="' + parentId + '" data-question-type="8" id="enumerationquestion' + parentId + '"></textarea>' +
                                    '<input type="number" class="form-control mt-2 ml-2" placeholder="Item count" data-id="' + parentId + '" id="enumerationitem' + parentId + '">' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="col-12">' +
                                    '<button class="btn btn-primary btn-sm ml-2 answer-key-enum" id="' + parentId + '">Set Up Answer</button>' +
                                    '</div>'
                                );
                                break;

                            case 'instruction':
                                $('#quiztioncontent' + parentId).empty().append(
                                    '<div class="row">' +
                                    '<div class="col-12 m-2">' +
                                    '<textarea class="form-control mt-2 question" placeholder="Untitled instruction" style="width: 100% !important;" data-id="' + parentId + '" data-question-type="1" id="instruction_item' + parentId + '"></textarea>' +
                                    '</div>' +
                                    '</div>'
                                );
                                $('#instruction_item' + parentId).summernote({
                                    height: 200,
                                    toolbar: [
                                        ['style', ['bold', 'italic', 'underline', 'clear']],
                                        ['fontsize', ['fontsize']],
                                        ['color', ['color']],
                                        ['para', ['ul', 'ol', 'paragraph']]
                                    ]
                                });
                                break;

                            case 'fill_n_blanks':
                                var option = 0;
                                $('#quiztioncontent' + parentId).empty().append(
                                    '<div class="row">' +
                                    '<div class="col-12 m-2">' +
                                    '<p><b>Note: </b>To set up the blanks, please input [~input] where you want the blank to appear. Ex. The planet ~input is the biggest planet in the solar system</p>' +
                                    '<div id="item_fill' + parentId + '">' +
                                    '<input type="text" class="form-control fill' + parentId + '" style="margin-top: 10px; border: 2px solid dodgerblue; color: black;" placeholder="Item text &nbsp;' + option + '">' +
                                    '</div>' +
                                    '<div class="row justify-content-end p-3 mt-2">' +
                                    '<button class="btn btn-success add_fill_question"  id="' + parentId + '">Add fill question</button>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="col-12">' +
                                    '<button class="btn btn-primary btn-sm answer-key-fill" id="' + parentId + '">Set Up Answer</button>' +
                                    '</div>' +
                                    '</div>'
                                );
                                break;

                            case 'drag_drop':
                                var option = 0;
                                $('#quiztioncontent' + parentId).empty().append(
                                    '<div class="row">' +
                                    '<div class="col-12 m-2">' +
                                    '<div class="options p-3 mt-2" id="options' + parentId + '" style="border:3px solid #3e416d;border-radius:6px;">' +
                                    '<div class="drag-option btn bg-primary text-white m-1 drag' + parentId + '" contentEditable="true" data-target="drag-1">Option &nbsp;' + option + '</div>' +
                                    '</div>' +
                                    '<div class="row justify-content-end p-3 mt-2">' +
                                    '<button class="btn btn-success add_drag_option" id="' + parentId + '">Add drag option</button>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '<p><b>Note: </b>To set up the drop area, please input [~input] where you want the drop zone to appear. Ex. The planet ~input is the biggest planet in the solar system</p>' +
                                    '<div id="item_question' + parentId + '">' +
                                    '<input type="text" class="form-control drop' + parentId + '" style="margin-top: 10px; border: 2px solid dodgerblue; color: black;" placeholder="Item text &nbsp;' + option + '">' +
                                    '</div>' +
                                    '<div class="row justify-content-end p-3 mt-2">' +
                                    '<button class="btn btn-success add_drag_question"  id="' + parentId + '">Add drop question</button>' +
                                    '</div>' +
                                    '<div class="col-12">' +
                                    '<button class="btn btn-primary btn-sm answer-key-drag" id="' + parentId + '">Set Up Answer</button>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>'
                                );
                                break;

                            case 'image':
                                $('#quiztioncontent' + parentId).empty().append(
                                    '<div class="row">' +
                                    '<div class="col-12 m-2">' +
                                    '<h6><label class="ml-2" for="number_question' + parentId + '">Points:</label></h6>' +
                                    '<input type="number" class="form-control m-2 addpoints is-invalid" placeholder="Required" data-id="' + parentId + '" id="Required' + parentId + '">' +
                                    '<textarea class="form-control question" placeholder="Untitled instruction" data-id="' + parentId + '" data-question-type="6"  style="height: 20px !important;"  id="image_item' + parentId + '"></textarea>' +
                                    '<input type="file" class="mt-2" disabled>' +
                                    '</div>' +
                                    '</div>'
                                );
                                $('#image_item' + parentId).summernote({
                                    height: 200,
                                    toolbar: [
                                        ['style', ['bold', 'italic', 'underline', 'clear']],
                                        ['fontsize', ['fontsize']],
                                        ['color', ['color']],
                                        ['para', ['ul', 'ol', 'paragraph']]
                                    ]
                                });
                                break;
                        }


                    })


                    $(document).on('change', '.question2', function(){
                            var dataid = $(this).data('id');
                            var type = $(this).data('question-type');
                            var question = $(this).val();

                            var formData = new FormData();

                            formData.append('question', question);
                            formData.append('typeofquiz', type);
                            formData.append('id', dataid);
                


                            var csrfToken = $('meta[name="csrf-token"]').attr('content');
                            
            

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });

                            $.ajax({
                                url: "/admissiontest/createquestion",
                                type: 'POST', // Use lowercase 'type' instead of 'TYPE'
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    // Handle the success response
                                    console.log(response);
                                    Toast.fire({
                                        type: 'success',
                                        title: 'All the changes have been saved'
                                    });
                                },
                                error: function(xhr) {
                                    // Handle error here
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Something went wrong'
                                    });
                                }
                            });

                            
    

                    });


                    $(document).on('click', '#deletechoice', function(){

                            console.log("Delete button clicked!");

                            var id = $(this).data('id');
                            $(this).parent().parent().remove();


                            

                            $.ajax({
                                url: '/admissiontest/del-choices',
                                method: 'GET',
                                data: {
                                id: id

                                },
                                success: function(response) {
                                    console.log(response);
                                    
                                
                                    //Handle the response from the server if needed
                                }
                            });
                    
                    });



                        // Add click event listener to answer key button
                    $(document).on('click', '.answer-key', function() {
                            var parentId = $(this).attr('id');
                            console.log(parentId);
                            var textareaValue = $('#multiplechoice' + parentId).val();
                            var formData = new FormData();


                            formData.append('question', textareaValue);
                            formData.append('typeofquiz', 1);
                            formData.append('id', parentId);
                


                            var csrfToken = $('meta[name="csrf-token"]').attr('content');
                            
                            


                            function saveChoices(parentId) {
                                var promises = []; // Array to store the promise

                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken
                                    }
                                });

                                $.ajax({
                                    url: "/admissiontest/createquestion",
                                    type: 'POST', // Use lowercase 'type' instead of 'TYPE'
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(response) {
                                        // Handle the success response
                                        console.log(response);
                                        Toast.fire({
                                            type: 'success',
                                            title: 'All the changes have been saved'
                                        });
                                    },
                                    error: function(xhr) {
                                        // Handle error here
                                        Toast.fire({
                                            type: 'error',
                                            title: 'Something went wrong'
                                        });
                                    }
                                });

                                var i = 1;
                                $('.option' + parentId).each(function() {
                                    const value = $(this).text();
                                    console.log(i);
                                    
                                    var promise = $.ajax({
                                        type: "get",
                                        dataType: 'json',
                                        url: "/admissiontest/createchoices",
                                        data: { 
                                            questionid : parentId,
                                            sortid: i,
                                            description : value
                                        }
                                    });

                                    promises.push(promise); // Add the promise to the array
                                    i += 1;
                                });

                                return Promise.all(promises); // Return a promise that resolves when all AJAX requests are completed
                            }

                            saveChoices(parentId)
                                .then(function() {
                                    getQuestion(parentId); // Call getQuestion function after all saveChoices AJAX requests are completed
                                })
                                .catch(function(error) {
                                    // Handle error here
                                });
                        });




                        function getQuestion(parentId){

                            $.ajax({
                                    type: "get",
                                    dataType: 'json',
                                    url: "/admissiontest/getquestion",
                                    data: { 
                                        id: parentId
                                
                                            },
                                    success: function(response) {
                                            console.log(response);

                                            $('#quiztype' + parentId).prop('disabled', true);


                                            var html = `<div class="row">
                                                            <div class="col-12 m-2">
                                                                <h6> Question:  </h6>
                                                                <label> ${response.question}</label>`;

                                            response.choices.forEach(function(item) {
                                                html += `<div class="form-check mt-2">
                                                            <input data-question-type="${item.typeofquiz}" data-question-id="${response.id}" id="${item.id}" class="answer-field form-check-input" type="radio" data-type="1" name="${response.id}" value="${item.id}">
                                                            <label for="${item.id}" class="form-check-label itemchoices">
                                                                ${item.description}
                                                            </label>
                                                        </div>`;
                                            });

                                            html += `</div><div class="col-12 p-3 text-end">
                                                                        <button class="btn btn-primary btn-sm answerdone" id="${response.id}">Done</button>
                                                                    </div></div>`;

                                            $('#quiztioncontent' + parentId).empty().append(html);
                                        },
                                    error: function(xhr) {
                                        console.log("error");
                                        // Handle error here
                                    }

                                    });
                        }


                        function autoSaveAnswer(thisElement) {
                            var answer = $(thisElement).val();
                            var questionId = $(thisElement).data('question-id');
                            var questiontype = $(thisElement).data('type');
                            var sortid = $(thisElement).data('sortid');
                            
                            console.log(sortid)

                            console.log(`student answer: ${answer}, question-id: ${questionId}, question type: ${questiontype}`)

                            $.ajax({
                                url: '/admissiontest/save-answer-key',
                                method: 'GET',
                                data: {
                                answer: answer,
                                question_id: questionId,
                                sortid : sortid,
                                questiontype: questiontype

                                },
                                success: function(response) {
                                    console.log(response);
                                
                                    //Handle the response from the server if needed
                                }
                            });

                        }


                
                        $(document).on('click', '.answerdone', function(){

                            var id = $(this).attr("id");
                            console.log(id);


                            $.ajax({
                                    type: "get",
                                    dataType: 'json',
                                    url: "/admissiontest/returneditquiz",
                                    data: { 
                                        id: id
                                
                                            },
                                    success: function(response) {
                                            console.log(response);

                                            $('#quiztype' + id).prop('disabled', false);



                                            var html = `<div class="row">
                                                            <div class="col-12 m-2">
                                                                <textarea class="form-control question" placeholder="Untitled question" id="multiplechoice${response.id}" > ${response.question}</textarea>
                                                            </div>
                                                        <div class="col-12" id="list_option${response.id}">`;

                                            response.choices.forEach(function(item) {
                                                html += `<div id="containerchoices${response.id}">
                                                            <input class="form-check-input ml-2" type="radio" name="option${response.id}" value="${item.id}">
                                                            <label class="form-check-label ml-5 option${response.id}" id="option${response.id}" contenteditable="true">
                                                                ${item.description}`;
                                                                
                                                                if(item.answer == 1){
                                                                    html +=`<span class="ml-2"><i class="fa fa-check" style="color:rgb(7, 255, 7)" aria-hidden="true"></i></span>`;
                                                                }
                                                                
                                                                html += `<span id="deletechoice" data-id="${item.id}" class="pl-1"><i class="fa fa-trash" aria-hidden="true"></i></span>
                                                            </label>
                                                        </div>`;
                                            });


                                            html += `</div>
                                                    <div class="col-12">
                                                        <div class="row justify-content-end p-3 mt-2">
                                                            <button class="btn btn-success addoption" id="${response.id}">Add option</button>
                                                        </div>
                                                        <button class="btn btn-primary btn-sm answer-key" id="${response.id}">Set Up Answer</button>
                                                    </div>`;

                                            $('#quiztioncontent' + id).empty().append(html);

                                            $('.question').summernote({
                                                height: 200,
                                                toolbar: [
                                                    ['style', ['bold', 'italic', 'underline']],
                                                ]

                                            });
                                        },
                                    error: function(xhr) {
                                        console.log("error");
                                        // Handle error here
                                    }
                            });
                        });

                        
                $(document).on('change', '.addpoints', function(){
                            var $this = $(this);

                            var dataid = $(this).data('id');
                            var points = $(this).val();

                            if(points.length != 0){
                                console.log("ID: ", dataid,"Points:", points);

                                $.ajax({
                                    url: '/admissiontest/setpoints',
                                    method: 'GET',
                                    data: {
                                    dataid : dataid,
                                    points : points

                                    },
                                    success: function(response) {

                                        console.log("Done")
                                        console.log("Remove Done")
                                        
                                        $this.css('border-color', 'black');
                                        
                                    
                                        //Handle the response from the server if needed
                                    }
                                });

                            
                        }else{

                            UIkit.notification({
                                        message: '<span uk-type="type: close"></span> Points Required!',
                                        status: 'error',
                                        timeout: 1000
                                    });
                            $this.css('border-color', 'red');


                        }

                            

                });

                $(document).on('change', '.setanswer', function(){
                            var dataid = $(this).data('id');
                            var answer = $(this).val();
                            console.log("ID: ", dataid,"Answer:", answer);

                            $.ajax({
                                url: '/admissiontest/setguideanswer',
                                method: 'GET',
                                data: {
                                dataid : dataid,
                                answer : answer

                                },
                                success: function(response) {

                                    console.log("Done")
                                    
                                
                                    //Handle the response from the server if needed
                                }
                            });
                            

                        });


                        $(document).on('click', '.answer-key-enum', function(){
                            var parentId = $(this).attr('id');
                            console.log(parentId)

                            var itemval = $('#enumerationitem' + parentId).val();


                            var textareaValue = $('#enumerationquestion' + parentId).val();
                            console.log("Question: ", parentId);
                            console.log("Quiztype: ", parentId);
                            console.log("Enumeration ITEM: ", itemval);

                                if (textareaValue.length != 0) {
                                    $.ajax({
                                        type: "get",
                                        dataType: 'json',
                                        url: "/admissiontest/createquestionitem",
                                        data: { 
                                            question : textareaValue,
                                            typeofquiz : 8,
                                            item : itemval,
                                            id: parentId
                                                },
                                        success: function(response) {

                                            if (response == 1){

                
                                        
                                            Toast.fire({
                                                type: 'success',
                                                title: 'All the changes have been saved'
                                                
                                            })

                                            getenum(parentId)

                                            }
                                            
                                        },
                                        error: function(xhr) {
                                            // Handle error here
                                        }
                                    });

                                }

                        });

                        function getenum(parentId){

                            $.ajax({
                                    type: "get",
                                    dataType: 'json',
                                    url: "/admissiontest/getenumquestion",
                                    data: { 
                                        id: parentId
                                
                                            },
                                    success: function(response) {
                                            console.log(response);

                                            $('#quiztype' + parentId).prop('disabled', true);
                                            

                                            var html = `<div class="form-check mt-2 ml-4 border border-4 p-3 pl-2">
                                                            <input data-type="16" data-question-id="${response.id}" class="answer-field form-check-input ml-1" type="radio" name="${response.id}" value="0">
                                                            <label for="0" class="form-check-label ml-4">
                                                                Order answer
                                                            </label>
                                                        `
                                            html += `
                                                            <input data-type="16" data-question-id="${response.id}" class="answer-field form-check-input ml-1" type="radio" name="${response.id}" value="1">
                                                            <label for="1" class="form-check-label ml-4">
                                                                Random
                                                            </label>
                                                        </div>`

                                            html +=`<p class="ml-4 mt-2 mb-2">A. ${response.question} </p>`
                                            html +=`<ol>`

                                            for (var i = 0; i < response.item; i++){

                                            html +=   `<div class="row">
                                                    <div class="col-md-12">
                                                        <li>
                                                            <p class="ml-2 d-inline">
                                                                
                                                                <input
                                                                    data-question-id="${response.id}"
                                                                    data-sortid="${i + 1}"
                                                                    data-question-type="8"
                                                                    data-type="8"
                                                                    id="enumerationfield"
                                                                    class="answer-field d-inline form-control q-input"`
                                                            

                                            if (response.answer.length !== 0 && i < response.answer.length) {
                                                                    html += `value="${response.answer[i].answer}"`;
                                            }


                                            html +=         `type="text">  
                                                            </li>
                                                        </p>
                                                    </div>
                                                </div>`
                                            }

                                            html +=`</ol>`

                                            html += `</div><div class="col-12 p-3 text-end">
                                                                        <button class="btn btn-dark btn-sm answerdoneenum" id="${response.id}">Done</button>
                                                                        <button class="btn btn-dark btn-sm clearanswer" id="${response.id}">Clear all Answer</button>
                                                                    </div></div>`;

                                            $('#quiztioncontent' + parentId).empty().append(html);
                                        },
                                    error: function(xhr) {
                                        console.log("error");
                                        // Handle error here
                                    }
                                    })

                        }



                        //auto save answer when switching to 
                        $(document).on('change', '.answer-field', function(){
                            autoSaveAnswer(this);
                            

                        });




                        $(document).on('click', '.answerdoneenum', function(){

                            var id = $(this).attr("id");
                            console.log(id);


                            $.ajax({
                                    type: "get",
                                    dataType: 'json',
                                    url: "/admissiontest/returneditquizenum",
                                    data: { 
                                        id: id
                                
                                            },
                                    success: function(response) {
                                            console.log(response);


                                            $('#quiztype' + id).prop('disabled', false);


                                            var html = `<div class="row">
                                                            <div class="col-12 m-2">
                                                                <textarea class="form-control enumeration mt-2 ml-2" placeholder="Untitled question" id="enumerationquestion${response.id}" > ${response.question}</textarea>
                                                                <h5><label class= "ml-2 mt-2" for="itemcount">Item:</label></h5>
                                                                <input type="number" class="form-control mt-2 ml-2 itemcount" placeholder="Item count" data-id= "${response.id}" id="enumerationitem${response.id}" value= "${response.item}">`;
                                            html += `<div id="item_option${response.id}}">
                                            <span class ="ml-2 mt-2"><b>Answer :   </b>`;

                                            
                                            if(response.ordered == 0){
                                            html +=` [IN ORDER]`;
                                            }else{
                                            html += `[RANDOM]`;
                                            }
                    
                                            html+= `</span>`;

                                            // if(response.answer.length < 0){
                                            
                                            // html += `<em>undefined</em>`;
                                            
                                            // }else{
                                            
                                            // html +=    `<em>${response.answer}</em>.`;
                                        
                                            // }
                                                                                    
                                            

                                            for (var i = 0; i < response.item; i++) {

                                                html += `<div id="item_option${response.id}}">
                                                            <div class="input-group mt-2">
                                                                <input type="text" class="form-control mt-2 ml-2" placeholder="Item text &nbsp;${i+1}"`;
                                                if(response.answer){
                                                            html += `value= ${response.answer[i].answer}`

                                                }
                                                                
                                                                
                                                html +=` disabled>
                                                            </div>`;


                                            }

                                            html+=       `</div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-12 mt-2">
                                                <button class="btn btn-primary btn-sm answer-key-enum" id="${response.id}">Set Up Answer</button>
                                            </div>
                                        </div>`;

                                            
                                            
                                            $('#quiztioncontent' + id).empty().append(html);
                                        },
                                    error: function(xhr) {
                                        console.log("error");
                                        // Handle error here
                                    }
                            });


                        

                        



                        });

                        $(document).on('click', '.clearanswer', function(){

                            var parentId = $(this).attr("id");


                            $.ajax({
                                                type: "get",
                                                url: "/admissiontest/clearanswerenum",
                                                data: { 
                                                    parentId : parentId,
                                                        },
                                                success: function(response) {

                                                    UIkit.notification({
                                                    message: '<span uk-type="type: check"></span> The answer has been cleared!',
                                                    status: 'success',
                                                    timeout: 1000
                                                    });
                                                    
                                                    
                                                },
                                                error: function(xhr) {
                                                    // Handle error here
                                                }

                                    
                            });

                            $('.answerdoneenum').click();
                            
                            
                            console.log("Done clearing answer")
                            

                        });

                        $(document).on('click', '.add_fill_question', function(){
                            option+=1;
                            var parentId = $(this).attr('id');
                            console.log("IDs: ", parentId)
                            $('#item_fill' + parentId).append(`<input type="text" class="form-control fill${parentId}" style="margin-top: 10px; border: 2px solid dodgerblue; color: black;" placeholder="Item text &nbsp;${option}">`)
                        
                        })




                        
                        $(document).on('click', '.answer-key-fill', function() {
                            var parentId = $(this).attr('id');
                            console.log(parentId);



                            calculate(parentId);

                            var formData = new FormData();
                            formData.append('question', 'fill in the blanks');
                            formData.append('typeofquiz', 7);
                            formData.append('id', last_id);

                            var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    


                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken  // Set the CSRF token in the request headers
                                }
                            });


                            var i = 1;
                            var validation = true;

                            var promises = []; // Array to store the promises

                    
                            $('.fill' + parentId).each(function() {
                                const value = $(this).val();
                                console.log(i);
                                console.log(value);

                                    if (i == 1) {
                                        var questionPromise =  $.ajax({
                                            url: "/admissiontest/createquestion",
                                            type: 'POST', // Use lowercase 'type' instead of 'TYPE'
                                            data: formData,
                                            processData: false,
                                            contentType: false
                                        });
                                        promises.push(questionPromise); // Add the promise to the array
                                    }

                                    var fillPromise = $.ajax({
                                        type: "get",
                                        url: "/admissiontest/createfillquestion",
                                        data: {
                                            questionid: parentId,
                                            sortid: i,
                                            description: value
                                        }
                                    });
                                    promises.push(fillPromise); // Add the promise to the array

                                    i += 1;
                    
                            });

                            Promise.all(promises)
                                .then(function() {
                                    getfillquestion(parentId);
                                })
                                .catch(function(error) {
                                    // Handle error here
                                });

                            console.log('parentId');
                            console.log(parentId);
                        });


                        function getfillquestion(parentId){

                            $.ajax({
                                    type: "get",
                                    dataType: 'json',
                                    url: "/admissiontest/getfillquestion",
                                    data: { 
                                        id: parentId
                                
                                            },
                                    success: function(response) {
                                            console.log(response);

                                            $('#quiztype' + parentId).prop('disabled', true);
                                            var html ='';

                                            response.fill.forEach(function(item) {
                                                html += `<p class="ml-2">${item.sortid}. ${item.question} </p>`;
                                            
                                            });


                                            html += `</div><div class="col-12 p-3 text-end">
                                                                        <button class="btn btn-dark btn-sm answerdonefill" id="${response.id}">Done</button>
                                                                    </div></div>`;

                                            $('#quiztioncontent' + parentId).empty().append(html);
                                        },
                                    error: function(xhr) {
                                        console.log("error");
                                        // Handle error here
                                    }
                                    })

                        }


                        $(document).on('click', '.answerdonefill', function(){

                            var id = $(this).attr("id");
                            console.log(id);


                            $.ajax({
                                    type: "get",
                                    dataType: 'json',
                                    url: "/admissiontest/returneditfill",
                                    data: { 
                                        id: id
                                
                                            },
                                    success: function(response) {
                                            console.log(response);


                                            $('#quiztype' + id).prop('disabled', false);
                                            var html = ` <p><b>Note: </b>To set up the blanks, please input [~input] where you want the blank to appear. Ex. The planet ~input is the biggest planet in the solar system</p>
                                            <div id="item_fill${response.id}">`
                                
                                            response.fill.forEach(function(item){
                                            html += ` <input type="text" class="form-control fill${response.id}" style="margin-top: 10px; border: 2px solid dodgerblue; color: black;" placeholder="Item text" value="${item.question}">
                                            <span>Answer :  `
                                                if (!item.answer || item.answer.length === 0) {
                                                    html += `<em>undefined</em>`;
                                                    } else {
                                                    html += `<em>${item.answer}</em>.</span>`;
                                                    }

                                            });

                                            html += `</div>
                                                    <div class="row justify-content-end p-3 mt-2">
                                                    <button class="btn btn-success add_fill_question"  id="${response.id}">Add fill question</button>
                                                    </div>
                                                    <div class="col-12">
                                                    <button class="btn btn-primary btn-sm answer-key-fill" id="${id}">Set Up Answer</button>
                                                    </div>`

                                        $('#quiztioncontent' + id).empty().append(html);
                                        },
                                    error: function(xhr) {
                                        console.log("error");
                                        // Handle error here
                                    }
                            });

                        });


                        $(document).on('click', '.add_drag_option', function(){
                            option+=1;
                            // var parentId = $(this).parent().parent().parent().parent().parent().attr("id");
                            var parentId = $(this).attr('id');
                            var addrowid = $(this).attr('id');
                            console.log("Add row ID: ", addrowid)
                            console.log("ID: ", parentId)
                            
                            // $(this).closest('quizarea2').find('.list_option').empty()
                            $('#options' + parentId).append('<div class="drag-option btn bg-primary text-white m-1 drag'+parentId+'" contentEditable="true" data-target="drag-1">Option &nbsp;'+option+'</div>')

                        })


                

                    $(document).on('click', '.add_drag_question', function(){
                        option+=1;
                        var parentId = $(this).attr('id');
                        var addrowid = $(this).attr('id');
                        console.log("Add row ID: ", addrowid)
                        console.log("ID: ", parentId)
                        
                        $('#item_question' + parentId).append('<input type="text" class="form-control drop'+parentId+'" style="margin-top: 10px; border: 2px solid dodgerblue; color: black;" placeholder="Item text &nbsp;'+option+'">')

                    })

                    
                        

                        $(document).on('click', '.answer-key-drag', function() {
                            var parentId = $(this).attr('id');
                        
                            var i = 1;
                            var dragPromises = [];
                            console.log("Drag and Drop");
                            calculateDrag(parentId);
                            $('.drag' + parentId).each(function() {
                                const value = $(this).text();
                                console.log(value);

                                var dragPromise = $.ajax({
                                    type: "get",
                                    dataType: 'json',
                                    url: "/admissiontest/createdragoption",
                                    data: {
                                        questionid: parentId,
                                        sortid: i,
                                        description: value
                                    }
                                });

                                dragPromises.push(dragPromise);
                                i += 1;
                            });

                            var dropPromises = [];
                            i = 1;
                            $('.drop' + parentId).each(function() {
                                const value = $(this).val();
                                console.log(value);

                                var dropPromise = $.ajax({
                                    type: "get",
                                    dataType: 'json',
                                    url: "/admissiontest/createdropquestion",
                                    data: {
                                        questionid: parentId,
                                        sortid: i,
                                        description: value
                                    }
                                });

                                dropPromises.push(dropPromise);
                                i += 1;
                            });

                            Promise.all([...dragPromises, ...dropPromises])
                                // .then(function() {
                                //     Toast.fire({
                                //         type: 'success',
                                //         title: 'All the changes have been saved'
                                //     });

                                //     var questionPromise = $.ajax({
                                //             url: "/admissiontest/createquestion",
                                //             type: 'POST', // Use lowercase 'type' instead of 'TYPE'
                                //             data: formData,
                                //             processData: false,
                                //             contentType: false
                                //         });

                                //     return questionPromise;
                                // })
                                .then(function() {
                                    getDropQuestion(parentId);
                                })
                                .catch(function(error) {
                                    // Handle error here
                                });
                        });


                        function getDropQuestion(parentId){

                            $.ajax({
                                    type: "get",
                                    dataType: 'json',
                                    url: "/admissiontest/getdropquestion",
                                    data: { 
                                        id: parentId
                                
                                            },
                                    success: function(response) {
                                            console.log(response);

                                            $('#quiztype' + parentId).prop('disabled', true);

                                            var html = `<div class="options p-3 mt-2" style="border:3px solid #3e416d;border-radius:6px;">`;
                                            
                                            

                                            response.drag.forEach(function(item) {
                                                html += `<div class="drag-option btn bg-primary text-white m-1" data-target="drag-1">${item.description}</div>`;
                                            
                                            });

                                            html += `</div>`

                                            response.drop.forEach(function(item) {
                                                html += `<p class="ml-2">${item.sortid}. ${item.question} </p>`;
                                            
                                            });

                                            

                                            html += `</div><div class="col-12 p-3 text-end">
                                                                        <button class="btn btn-dark btn-sm answerdonedrag" id="${response.id}">Done</button>
                                                                    </div></div>`;

                                            $('#quiztioncontent' + parentId).empty().append(html);


                                                            
                                        // Initialize draggable with touch-punch
                                        $(".drag-option").draggable({
                                            helper: "clone",
                                            revertDuration: 100,
                                            revert: "invalid"
                                        });

                                        // Initialize droppable
                                        $(".drop-option").droppable({
                                            drop: function(event, ui) {
                                            var dragElement = $(ui.draggable);
                                            var dropElement = $(this);

                                            dropElement.val(dragElement.text());
                                            dropElement.addClass("bg-primary text-white");
                                            dropElement.prop("disabled", true);

                                            dragElement.removeClass("bg-primary");
                                            dragElement.addClass("bg-dark");


                                            autoSaveAnswerdragandrop(dropElement);
                                            }
                                        });
                                        },
                                    error: function(xhr) {
                                        console.log("error");
                                        // Handle error here
                                    }
                            })
                        }


                        function autoSaveAnswerdragandrop(thisElement) {
                            var answer = $(thisElement).val();
                            var questionId = $(thisElement).data('question-id');
                            var sortId = $(thisElement).data('sortid');

                            console.log(`student answer: ${answer}, question-id: ${questionId}, sort-id: ${sortId}`)

                            $.ajax({
                                url: '/admissiontest/save-answer-drop',
                                method: 'GET',
                                data: {
                                answer: answer,
                                question_id: questionId,
                                sortId: sortId

                                },
                                success: function(response) {
                                    console.log(response);
                                
                                    //Handle the response from the server if needed
                                }
                            });

                        }

                        $(document).on('click', '.answerdonedrag', function(){

                            var id = $(this).attr("id");
                            console.log(id);


                            $.ajax({
                                    type: "get",
                                    dataType: 'json',
                                    url: "/admissiontest/returneditquizdrag",
                                    data: { 
                                        id: id
                                
                                            },
                                    success: function(response) {
                                            console.log(response);


                                            $('#quiztype' + id).prop('disabled', false);
                                            var html = `<div class="row">
                                                            <div class="col-12">
                                                                <div class="options p-3 mt-2" id="options${response.id}" style="border:3px solid #3e416d;border-radius:6px;">`
                                            
                                            response.drag.forEach(function(item) {
                                            html += `<div class="drag-option btn bg-primary text-white m-1 drag${response.id}" contentEditable="true" data-target="drag-1">
                                                                                    ${ item.description }
                                                                                </div>`
                                                                            });
                                            
                                            html += `</div>
                                            <div class="row justify-content-end p-3 mt-2">
                                                <button class="btn btn-success add_drag_option" id="${response.id}">Add drag option</button>
                                            </div>

                                            <p><b>Note: </b>To set up the drop area, please input [~input] where you want the drop zone to appear. Ex. The planet ~input is the biggest planet in the solar system</p>
                                            <div id="item_question${response.id}">`
                                
                                            response.drop.forEach(function(item){
                                            html += `<input type="text" class="form-control drop${response.id}" style="margin-top: 10px; border: 2px solid dodgerblue; color: black;" placeholder="Item text" value = "${item.question}">
                                            <span>Answer :  `
                                                if (!item.answer || item.answer.length === 0) {
                                                    html += `<em>undefined</em>`;
                                                    } else {
                                                    html += `<em>${item.answer}</em>.</span>`;
                                                    }

                                            });

                                            html += `</div>
                                                    <div class="row justify-content-end p-3 mt-2">
                                                        <button class="btn btn-success add_drag_question"  id="${response.id}">Add drop question</button>
                                                    </div>
                                                <div class="col-12">
                                                    <button class="btn btn-primary btn-sm answer-key-drag" id="${response.id}">Set Up Answer</button>
                                                </div>
                                                </div>
                                            </div>
                                        </div>`

                                        $('#quiztioncontent' + id).empty().append(html);
                                        },
                                    error: function(xhr) {
                                        console.log("error");
                                        // Handle error here
                                    }
                            });

                        



                        });


                     //------------------Question Category ----------------//
            
                    $("#category-list").sortable({
                        
                        containment: "parent",
                        tolerance: "pointer",
                        update: function(event, ui) {
                            // Get all list items and update their data-sort-id attribute
                            var quizId = $('#quiz-info').data('testid');
                            $("#category-list li").each(function(index) {

                                var id = $(this).data('id');
                                var sortid = index + 1;
                                $.ajax({
                                    type: "GET",
                                    url: "/admissiontest/quiz/category/sorting",
                                    data: { 
                                        id          : id,
                                        quizId      : quizId,
                                        sortid      : sortid
                                            }
                                });

                            });
                        }
                    });

        

                    // Prevent text selection during dragging
                    $("#category-list").disableSelection();

                    function categorySelect(id){
                        var quizId = $('#quiz-info').data('testid');
        
                        $('#categorySelect' + id).select2({
                                    width: '100%',
                                    allowClear: true,
                                    placeholder: "Select Category",
                                    language: {
                                        noResults: function () {
                                            return "No results found";
                                        }
                                    },
                                    escapeMarkup: function (markup) {
                                        return markup;
                                    },
                                    ajax: {
                                        url: "{{ route('categorySelect') }}",
                                        dataType: 'json',
                                        delay: 250,
                                        data: function (params) {
                                            var query = {
                                                search: params.term,
                                                page: params.page || 0,
                                                id : quizId
                                            }
                                            return query;
                                        },
                                        processResults: function (data, params) {
                                            params.page = params.page || 0;
                                            return {
                                                results: data.results,
                                                pagination: {
                                                    more: data.pagination.more
                                                }
                                            };
                                        },
                                        cache: true
                                    }
                        });

                    }


                    
                    $(document).on('click', '.category', function(){

                        var quizId = $('#quiz-info').data('testid');


                            $.ajax({
                                type: "GET",
                                url: "/admissiontest/quiz/getcategory",
                                data: {
                                    quizId: quizId
                                },
                                success: function(response) {

                                    console.log(response);
                                    $("#category-list").empty();
                                    // Assuming response.data is an array of objects with 'category' property
                                    $.each(response, function(index, item) {
                                        console.log(item.category);
                                        var listItem = $("<li class='list-group-item d-flex justify-content-between align-items-center' data-sortid="+ item.sortid +" data-id="+ item.id +">" + item.category + "<button class='btn btn-danger btn-sm delete-btn' data-id="+ item.id +">Delete</button></li>");
                                        $("#category-list").append(listItem);
                                    });

                                    // After appending the list items, open the modal
                                    $("#myModal").modal("show");

    
                                },
                                error: function(xhr) {
                                    // Handle error here
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Something went wrong'
                                    });
                                }
                            });


                    });

                    $(document).on('click', '#addItemBtn', function(){

                        var quizId = $('#quiz-info').data('testid');
                        var categoryInput = $("#category").val();
                        if(categoryInput.trim() !== "") {


                            $.ajax({
                                    type: "GET",
                                    url: "/admissiontest/quiz/addcategory",
                                    data: { 
                                        category : categoryInput,
                                        quizId: quizId
                                            },
                                    success: function(response) {


                                        var listItem = $(" <li class='list-group-item d-flex justify-content-between align-items-center'>" + categoryInput + "<button class='btn btn-danger btn-sm delete-btn'>Delete</button></li>");
                                        $("#category-list").append(listItem);
                                        $("#category").val(""); // Clear the input field after adding to the list
                                        
                                        Toast.fire({
                                            type: 'success',
                                            title: 'Category has been added!'
                                        })
                                        
                                    },
                                    error: function(xhr) {
                                        // Handle error here
                                        Toast.fire({
                                            type: 'error',
                                            title: 'Something went wrong'
                                        })
                                    }
                            });


                            
                        }


                    });

                    $("#category-list").on("click", ".delete-btn", function(){
                        var id = $(this).data('id');

                        console.log(id);

                        $.ajax({
                                    type: "get",
                                    url: "/admissiontest/category/delcategory",
                                    data: { 
                                        id: id
                                            },
                                    complete: function(data){
                                    
                                    

                                }
                        });
                        
                        $(this).parent().remove();

                        
                    });


                    $(document).on('click', '.categorylist', function(){

                            var id = $(this).data('id');
                            console.log(id);
                            $('#categorylist' + id).empty();
                            $('#categorylist' + id).append(`<select class="form-control select2 mb-5 categorySelect" data-id="${id}" id="categorySelect${id}"></select>`);
                            categorySelect(id);


                    });

                    $(document).on('change', '.categorySelect', function(){

                            var id = $(this).data('id');
                            var category = $(this).val();   
                            var quizId = $('#quiz-info').data('testid');


                            console.log(id);
                            console.log("Category: ", category);

                            $.ajax({
                                type: "get",
                                url: "/admissiontest/category/asssignCategory",
                                data: { 
                                    id: id,
                                    category: category,
                                        },
                                complete: function(data){
                                
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Category has been assigned successfully'
                                    });

                                }
                            });


                    });

                    



        });



        </script>


@endsection