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

    <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                margin-top: -9px;
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

                .calendar-table{
                display: none;
            }

            .drp-buttons{
                display: none !important;
            }

            .close-button1 {
                position: absolute;
                top: 10px;
                left: 10px;
                width: 30px;
                height: 30px;
                border: none;
                background-color: black;
                color: white;
                border-radius: 50%;
                font-size: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
            }

            .close-button1:hover {
            background-color: white;
            color: black;
            }

            .close-button1::after {
                content: "Delete";
                position: absolute;
                top: 35px;
                left: -20px;
                display: none;
                background-color: white;
                color: black;
                padding: 5px 10px;
                border-radius: 4px;
                font-size: 12px;
            }

            .close-button1:hover::after {
                display: block;
            }

  </style>
@endsection







@section('content')



    <div class="container">

            
            {{-- <div id="modal-close-default" uk-modal> 
                <div class="uk-modal-dialog uk-modal-body"> 
                    <button class="uk-modal-close-default" type="button" uk-close></button> 
                    <h2 class="uk-modal-title">Create New Quiz</h2> 

                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Test Title</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" name="quizname" type="text" placeholder="Test Title" autocomplete="off">
                        </div>
                    </div>
                    <div class="uk-margin">
                        <a href="#" type="button" class="btn btn-success uk-first-column" id="create_quiz" >
                            Create New Test
                        </a>
                    </div>
                </div> 
            </div> --}}


            <div id="modal-close-default" uk-modal> 
                <div class="uk-modal-dialog uk-modal-body"> 
                    <button class="uk-modal-close-default" type="button" uk-close></button> 
                    <h2 class="uk-modal-title">Create New Test</h2> 

                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Test Title</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" name="quizname" type="text" placeholder="Test Title" autocomplete="off">
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label">Checking Options</label>
                        <div class="uk-form-controls">
                            <label><input class="uk-radio" type="radio" name="checking-option" value="1" checked> Automated Checking</label>
                            <label><input class="uk-radio" type="radio" name="checking-option" value="0"> Manual Checking</label>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <a href="#" type="button" class="btn btn-success uk-first-column" id="create_quiz">
                            Create New Test
                        </a>
                    </div>
                </div> 
            </div>


            <div class="section-small" >

                <div class="uk-child-width-1-4@m uk-child-width-1-3@s course-card-grid uk-grid" uk-grid=""  >
                
                    <div class="quizCount">
                        <a href="#"  id="addtest" uk-toggle="target: #modal-close-default"> 
                            <div class="course-card">
                                    <div class="course-card-thumbnail" >
                                        <img src="{{asset('assets/images/plus-icon.png')}}" style="object-fit: contain">
                                    </a>
                                    </div>
                                        <div class="course-card-body">
                                            <div class="dropdown">
                                                <h6 class="text-center">
                                                    Create New                                                                                                                                                                                                                                   Test
                                                </h6>
                                                {{-- <div class="dropdown-menu" aria-labelledby="quizDropdown12">
                                                    <p class="dropdown-item quiz-description">Hello</p>
                                                </div> --}}
                                        </div>
                                        <div class="course-card-footer border-top-0 ">
                                            <h5></h5>
                                        </div>
                                    </div>
                            </div>
                        </a>
                </div>
            </div>

            <hr>

            <div class="section-header pb-0 mt-3">
                <div class="section-header-left">
                    <h1>Available Test</h1>
                </div>
            </div>


            <div class="section-small" id="quiz_table_holder" style="z-index:100">
            </div>

            
    </div>



@endsection
    
@section('footerjavascript')
        <script src="{{asset('templatefiles/framework.js')}}"></script>

<script>


        
        loadquiz()

        function loadquiz(){

            $('#quiz_table_holder').empty();


            $.ajax({
                url: "/admissiontest/table",
                type:"GET",
                success: function(data){

                    $('#quiz_table_holder').append(data)
            
                }
            })
            
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
        });



        $(document).on('click','#create_quiz',function(){

                    var validInput = true

                    if($('input[name=quizname]').val() == ''){

                        UIkit.notification("<span uk-icon='icon: warning'></span> Quiz title is required!", {status:'danger', timeout: 1500 });


                        validInput = false
                    }
                    
                    if(validInput){

                        
                        var selectedOption = $("input[name='checking-option']:checked").val();

                        
                        $.ajax({
                                url: '/admissiontest/create',
                                type:"GET",
                                data:{
                                    quizname: $('input[name=quizname]').val(),
                                    checking: selectedOption,
                                },
                                success: function(data){
                                    skip = null
                                    UIkit.notification("<span uk-icon='icon: check'></span> Created Successfully!", {status:'success', timeout: 1000 });
                                    loadquiz();

                                    //if test is automated
                                    if(data[0].check == 1){
                                        window.location.href = `/admissiontest/test/${data[0].id}`;
                                    }else{
                                        window.location.href = `/admissiontest/test2/${data[0].id}`;
                                    }
                                }
                        })

                    }

        })

        $(document).on('click','.close-button1',function(){

                    var id = $(this).data('id');

                    Swal.fire({
                    title: 'Delete test?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete'
                    }).then((result) => {

                        if (result.value === true) {


                            $.ajax({
                                url: '/admissiontest/delete',
                                type:"GET",
                                data:{
                                    id: id,
                                },
                                success: function(data){
                                    
                                    if(data == 0){
                                        
                                        Swal.fire(
                                            'Deleted!',
                                            'Your file has been deleted.',
                                            'success'
                                            )

                                        loadquiz();

                                    }else{
                                        Toast.fire({
                                            type: 'error',
                                            title: 'Something went wrong'
                                        })
                                        loadquiz();
                                    }
                                    

                                }
                            })
                        
                            


                        }
                    })

                    

        })
</script>


@endsection
