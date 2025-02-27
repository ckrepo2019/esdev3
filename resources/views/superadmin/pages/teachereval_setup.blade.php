
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
    </style>

    @php
        $sy = DB::table('sy')
                ->select(
                    'sydesc as text',
                    'id'
                )
                ->orderBy('sydesc')
                ->get();

        $gradelevel = DB::table('gradelevel')
                        ->where('acadprogid','!=',6)
                        ->select(
                            'levelname as text',
                            'id'
                        )
                        ->orderBy('sortid')
                        ->get();

    @endphp

@endsection

@section('content')


    {{-- Table Modal --}}


    <div class="modal fade" id="moda_sy" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">School Year List</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12  mt-2">
                            <table class=" table table-sm table-bordered">
                                <tr>
                                    <th width="40%">School Year</th>
                                    <th width="30%"></th>
                                    <th width="30%"></th>
                                </tr>
                                @foreach ($sy as $item)
                                    <tr>
                                        <td>{{$item->text}}</td>
                                        <td class="text-center">  <button class="btn btn-primary btn-sm" href="#modal_teval_sy" role="button" data-toggle="modal" style="font-size: .8rem !important" onclick="selected_sy({{$item->id}},'student')" eval-type="student">View Student Setup</button>
                                        </td>
                                        <td class="text-center">  <button class="btn btn-secondary btn-sm" href="#modal_teval_sy" role="button" eval-type="teacher" data-toggle="modal" style="font-size: .8rem !important" onclick="selected_sy({{$item->id}},'teacher')">View Teacher Setup</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_teval_sy" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">Grade Level </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-6">
                            <label>School Year: </label> <span class="sy_label">2023 - 2024</span>
                        </div>
                        <div class="col-md-6">
                            <label>Type: </label> <span class="type_label"></span>
                        </div>
                    </div>
                    <div class="row table-responsive" style="height: 500px !important">
                        <div class="col-md-12">
                            <div class="error-container"></div>
                        </div>
                        <div class="col-md-12  mt-2 eval_setup_display" data-type="student" hidden="hidden">
                            <table class=" table table-sm table-bordered table-striped" style="font-size:.9rem !important">
                                <tr>
                                    <th width="10%">Grade Level</th>
                                    <th width="40%">Evaluation Setup</th>
                                    <th width="25%" class="text-center">Status</th>
                                    <th width="10%" class="text-center">Term</th>
                                    <th width="15%"></th>
                                </tr>
                                @foreach($gradelevel as $item)
                                    <tr>
                                        <td class="align-middle">{{$item->text}}</td>
                                        <td class="level_evalsetup align-middle" data-id="{{$item->id}}"></td>
                                        <td class="align-middle level_status text-center"  data-id="{{$item->id}}"></td>
                                        <td class="align-middle level_term text-center"  data-id="{{$item->id}}"></td>
                                        <td class="text-center level_button align-middle"  data-id="{{$item->id}}">
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="col-md-12  mt-2 eval_setup_display" data-type="teacher" hidden="hidden">
                            <table class=" table table-sm table-bordered" style="font-size:.9rem !important" >
                                <tr>
                                    <th width="50%">Evaluation Setup</th>
                                    <th width="25%" class="text-center">Status</th>
                                    <th width="10%" class="text-center">Term</th>
                                    <th width="15%"></th>
                                </tr>
                                <tr>
                                    <td class="level_evalsetup align-middle" data-id="0"></td>
                                    <td class="align-middle level_status text-center"  data-id="0"></td>
                                    <td class="align-middle level_term text-center"  data-id="0"></td>
                                    <td class="text-center level_button align-middle"  data-id="0">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_teval" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">Evaluation Form Setup</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="error-container"></div>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-sm btn-primary"  href="#modal_teval_qgroup_form" role="button" data-toggle="modal" onclick="form_teval_group()">Create Evaluation Group</button>
                            <button class="btn btn-sm btn-primary"  href="#modal_teval_question_option" role="button" data-toggle="modal">Multiple Choice Setup</button>
                        </div>
                        <div  class="col-md-6">
                            Legend: <span class="badge badge-secondary float-rigth">MC</span> - Multiple Choice <span class="badge badge-secondary">LA</span> - Long Answer
                        </div>
                        <div class="col-md-12  mt-2 table-responsive"  style="height: 500px;">
                            <table class="table table-sm table-bordered" style="font-size: .9rem !important" >
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center align-middle">Sort</th>
                                        <th width="57%" class="align-middle">Description</th>
                                        <th width="8%" class="text-center align-middle">Type</th>
                                        <th width="20%" class="text-center"><button class="btn btn-sm btn-primary" style="font-size:.8rem !important" href="#modal_view_questionnaire" role="button" data-toggle="modal">View Questionnaire</button></th>
                                        <td width="5%" class="align-middle text-center"></td>
                                        <td width="5%" class="align-middle text-center"></td>
                                    </tr>
                                </thead>
                                <tbody id="teval_quetion_holder">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_teval_question_option" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">Question Option</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="error-container"></div>
                        </div>
                        <div class="col-md-12  mt-2">
                            <table class="table table-sm table-bordered" id="question_option_datatable">
                                <thead>
                                    <tr>
                                        <th width="50%">Description</th>
                                        <th width="34%"></th>
                                        <th width="8%" class="align-middle text-center"></th>
                                        <th width="8%" class="align-middle text-center"></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modal_view_questionnaire" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">School Year List</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12  mt-2 table-responsive" style="height: 500px;">
                            <table class=" table table-sm table-bordered" id="questionnaire_holder">
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_teval_question_option_detail" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">Questionnaire Evaluation Ratings</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="error-container"></div>
                        </div>
                        <div class="col-md-12  mt-2">
                            <table class="table table-sm table-bordered" id="teval_question_option_detail">
                                <thead>
                                    <tr>
                                        <th width="10%">Sort</th>
                                        <th width="20%">Rate Display</th>
                                        <th width="20%">Rate Value</th>
                                        <th width="34%">Rate Description</th>
                                        <td width="8%" class="align-middle text-center"></td>
                                        <td width="8%" class="align-middle text-center"></td>
                                    </tr>
                                </thead>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Modals --}}
    <div class="modal fade" id="modal_teval_form" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">Teacher Evaluation Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                   
                    <div class="row">
                        <div class="col-md-12">
                            <div class="error-container"></div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Description</label>
                            <input type="text" id="input_teval_desc" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary" onclick="submit_form_teval()">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   

    <div class="modal fade" id="modal_teval_qgroup_form" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">Question Group Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="error-container"></div>
                        </div>
                        <div class="col-md-12  form-group">
                            <label for="">Description</label>
                            <input type="text" id="input_teval_qgroup_desc" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12  form-group">
                            <label for="">Sort</label>
                            <input type="text" id="input_teval_sort_desc" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary" onclick="submit_form_teval_group()">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   

    <div class="modal fade" id="modal_teval_question_form" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">Question Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                    <div class="col-md-12">
                        <div class="error-container"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12  form-group">
                            <label for="">Description</label>
                            <input type="text" id="input_teval_question_desc" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12  form-group">
                            <label for="">Sort</label>
                            <input type="text" id="input_teval_question_sort" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12  form-group">
                            <label for="">Group</label>
                            <select id="input_teval_question_group" class="form-control form-control-sm" disabled="disabled">
                            </select>
                        </div>
                        <div class="col-md-12  form-group">
                            <label for="">Type</label>
                            <select id="input_teval_question_type" class="form-control form-control-sm select2" onchange="display_type()">
                                <option value="">Select Type</option>
                                <option value="multiple_choice">Multiple Choice</option>
                                <option value="long_answer">Long Answer</option>
                            </select>
                        </div>
                        <div class="col-md-12  form-group input_option_holder" hidden>
                            <label for="">Multiple Choice Setup</label>
                            <select id="input_teval_question_option_id" class="form-control form-control-sm select2">
                                <option value="">Select Option</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary" onclick="submit_form_teval_question()">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   

    <div class="modal fade" id="modal_teval_option_form" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">Option Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="error-container"></div>
                        </div>
                        <div class="col-md-12  form-group">
                            <label for="">Description</label>
                            <input type="text" id="input_teval_option_desc" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary" onclick="submit_form_teval_question_option()">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <div class="modal fade" id="modal_teval_option_detail_form" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">Evaluation Ratings Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="error-container"></div>
                        </div>
                        <div class="col-md-12  form-group">
                            <label for="">Rate Display</label>
                            <input type="text" id="input_teval_option_detail_display" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12  form-group">
                            <label for="">Rate Value</label>
                            <input type="text" id="input_teval_option_detail_value" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12  form-group">
                            <label for="">Rate Sort</label>
                            <input type="text" id="input_teval_option_detail_sort" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12  form-group">
                            <label for="">Rate Description</label>
                            <input type="text" id="input_teval_option_detail_desc" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12 ">
                            <button class="btn btn-sm btn-primary" onclick="submit_form_teval_question_option_detail()">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <div class="modal fade" id="modal_teval_sy_form" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">Evaluation S.Y, Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="error-container"></div>
                        </div>
                        <div class="col-md-12">
                            <label>School Year: </label> <span class="sy_label"></span>
                        </div>
                        <div class="col-md-12 form-group eval_setup_display" data-type="student">
                            <label for="">Grade Level</label>
                            <select id="input_teval_sy_levelid" class="form-control form-control-sm" disabled="disabled">
                                <option value="">Select Grade Level</option>
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Evaluation Setup</label>
                            <select id="input_teval_sy_tevalid" class="form-control form-control-sm">
                                <option value="">Select Evaluation Setup</option>
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Term</label>
                            <select id="input_teval_sy_term" class="form-control form-control-sm">
                                <option value="">Select Term</option>
                                <option value="1">Term 1</option>
                                <option value="2">Term 2</option>
                                <option value="3">Term 3</option>
                                <option value="4">Term 4</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary" onclick="submit_form_teval_sy()">Assign</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

 
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Teacher Evaluaton Setup</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Teacher Evaluaton Setup</li>
                </ol>
                </div>
            </div>
        </div>
    </section>
    
    <section class="content ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="error-container"></div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-sm table-bordered" id="eval_datatable">
                                        <thead>
                                            <tr>
                                                <th width="65%">Description</th>
                                                <th width="19%"></th>
                                                <th width="8%"></th>
                                                <th width="8%"></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footerjavascript')

    <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        var gradelevel = @json($gradelevel);
        var sy = @json($sy);
        var selectedsy = null
        var selectedgradelevel = null
        var teval_data = []
        var selected_type = null
        

        var selected_teval = null

        $('#input_teval_sy_levelid').select2({
            data:gradelevel,
            placeholder:'Select Grade Level'
        })

        $('.modal').on('hidden.bs.modal', function () {
            $('.alert').alert('close')
        });

        $('.modal').on('shown.bs.modal', function () {
            $('.alert').alert('close')
        });

        $('#input_teval_sy_term').select2()


    </script>


    <script>

        function show_modal_teval(id){
            selected_teval = id
            list_teval_question_option()
            list_teval_group()
            $('#teval_quetion_holder').empty();
            $('#modal_teval').modal()
        }

        function selected_sy(syid,type){
            selectedsy = syid
            selected_type = type
            list_teval_sy()
            var temp_sy_info = sy.filter(x=>x.id == selectedsy)
            $('.sy_label').text(temp_sy_info[0].text)
            $('.type_label').text(type)

            $('.level_status').text("")
            $('.level_term').text("")
            $('.level_button').text("")
            $('.level_evalsetup').text("")

            if(type == "teacher"){
                $('.eval_setup_display[data-type="teacher"]').removeAttr('hidden')
                $('.eval_setup_display[data-type="student"]').attr('hidden','hidden')
            }else{
                $('.eval_setup_display[data-type="student"]').removeAttr('hidden','hidden')
                $('.eval_setup_display[data-type="teacher"]').attr('hidden','hidden')
            }

        }

        function selected_gradelevel(levelid = null){
           
            selectedgradelevel = levelid
            selected_teval_sy = null
            $('#input_teval_sy_levelid').val(levelid).change()

        }

        function display_type(){
            if($('#input_teval_question_type').val() == 'multiple_choice'){
                $('.input_option_holder').removeAttr('hidden')
            }else{
                $('.input_option_holder').attr('hidden','hidden')
            }
        }
    </script>

    <script>
         function displayMessage(type, message) {
            var messageContainer = $('.error-container');
            messageContainer.empty();

            if (type === 'success') {
                var alert = 'alert-success'
            } else if (type === 'error') {
                var alert = 'alert-danger'
            }

            $('.error-container').append(`<div class="alert `+alert+` alert-dismissible fade show" role="alert">
                        `+message+`
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>`);
        }

        function displayErrors(errors) {
            // Clear previous errors
            $('.error-container').empty();

            // Display new errors
            $.each(errors, function(key, value) {
                $('.error-container').append(`<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        `+value+`
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>`);
            });
        }
    </script>
   
   <script>
        function update_form_button(id,button){
            if(id == null){
                $(button).removeClass('btn-success')
                $(button).addClass('btn-primary')
                $(button).text('Create')
            }else{
                $(button).addClass('btn-success')
                $(button).text('Update')
                $(button).removeClass('btn-primary')
            }
            $('.alert').alert('close')
        }
   </script>
    
    <script> //teacher evaluation #teval

        list_teval()

        function form_teval(id = null){
            var button = $('.btn[onclick="submit_form_teval()"]')
            selected_teval = id;
            $('#input_teval_desc').val(id === null ? "" : teval_data.find(x => x.id === id)?.teval_desc || "");
            update_form_button(id, button)
        }

        function teval_datatable(data){
            $("#eval_datatable").DataTable({
                    destroy: true,
                    lengthChange: false,
                    autoWidth: false,
                    stateSave: true,
                    data:data,
                    columns: [
                            { "data": "teval_desc" },
                            { "data": null },
                            { "data": null },
                            { "data": null },
                    ],
                    columnDefs: [
                                    {
                                        'targets': 0,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                        }
                                    },
                                    {
                                        'targets': 1,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<button class="btn btn-sm btn-primary btn-block view_teval_question_modal" onclick="show_modal_teval('+rowData.id+')"><i class="fas fa-cogs"> Setup</button>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                        }
                                    },
                                    {
                                        'targets': 2,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="javascript:void(0)" onclick="form_teval('+rowData.id+')"  ><i class="far fa-edit text-primary" href="#modal_teval_form" role="button" data-toggle="modal"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                        }
                                    },
                                    {
                                        'targets': 3,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="javascript:void(0)" onclick="delete_teval('+rowData.id+')"><i class="far fa-trash-alt text-danger"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                        }
                                    },
                            ]
                })

                var label_text = $($('#eval_datatable_wrapper')[0].children[0])[0].children[0]
                $(label_text)[0].innerHTML = ' <button class="btn btn-primary btn-sm mr-2" href="#modal_teval_form" role="button" data-toggle="modal" onclick="form_teval()">Create Setup</button><button class="btn btn-primary btn-sm" href="#moda_sy" role="button" data-toggle="modal">Evaluation S.Y.</button>'

        }

        function submit_form_teval(){
            var url = selected_teval == null ? '/teval/create' : '/teval/update'
            var button =  $('.btn[onclick="submit_form_teval()"]')
            $(button).attr('disabled','disabled')
            submit_teval(url)
        }

        function delete_teval(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "This will remove setup.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    selected_teval = id
                    submit_teval('/teval/delete')
                }
            })
        }

        function submit_teval(url){
            var button = $('.btn[onclick="submit_form_teval()"]')
            $.ajax({
                    type:'GET',
                    url: url,
                    data:{
                        id:selected_teval,
                        teval_desc: $('#input_teval_desc').val(),
                    },
                    success:function(response) {
                        if (response.success) {
                            displayMessage('success', response.message);
                            list_teval()
                        }else {
                            displayMessage('error', response.message);
                        }
                        $(button).removeAttr('disabled')
                    },
                    error:function(response){
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            displayErrors(errors);
                        }else if(response.status === 404){
                            displayMessage('error', 'Page not found');
                        }else{
                            displayMessage('error', response.responseJSON.message);
                        }
                        $(button).removeAttr('disabled')
                    }
            })
        }

        function list_teval(){
            $.ajax({
                    type:'GET',
                    url: '/teval/list',
                    success:function(response) {
                        teval_data =  JSON.parse( response).data
                        $('#input_teval_sy_tevalid').select2({
                            data:teval_data
                        })
                        teval_datatable(teval_data)
                    },
                    error:function(response){
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            displayErrors(errors);
                        }else if(response.status === 404){
                            displayMessage('error', 'Page not found');
                        }else{
                            displayMessage('error', response.responseJSON.message);
                        }
                    }
            })
        }

    </script>

    <script>  //teacher evaluation group

        var selected_teval_group = null
        var teval_group_data = []

        function form_teval_group(id = null){
            var button = $('.btn[onclick="submit_form_teval_group()"]')
            selected_teval_group = id;
            if(id != null){
                $('#input_teval_qgroup_desc').val(id === null ? "" : teval_group_data.find(x => x.id === id)?.description || "");
                $('#input_teval_sort_desc').val(id === null ? "" : teval_group_data.find(x => x.id === id)?.group_sort || "");
            }
           
            update_form_button(id, button)
        }

        function teval_group_datatable(data){
            
            $('#teval_quetion_holder').empty()
            $('#questionnaire_holder').empty()

            

            if(data.length == 0){
                $('#teval_quetion_holder').append( `<tr>
                    <th class="text-center align-middle" colspan="6"><a href="#modal_teval_qgroup_form" role="button" data-toggle="modal" onclick="form_teval_group()">Click here</a> to add group</th>
                 
                </tr>`)
            }

            $.each(data,function(a,b){
                $('#teval_quetion_holder').append(
                    `<tr>
                        <th class="text-center align-middle">`+b.group_sort+`</th>
                        <th class="align-middle">`+b.description+`</th>
                        <td></td>
                        <th class="text-center"><button class="btn btn-sm btn-primary " href="#modal_teval_question_form" role="button" data-toggle="modal" style="font-size: .8rem !important" onclick="form_teval_question(`+null+`,`+b.id+`)">Add Question</button></th>
                        <td class="align-middle text-center"><a onclick="form_teval_group(`+b.id+`)" href="#modal_teval_qgroup_form" role="button" data-toggle="modal"><i class="far fa-edit text-primary"></i></a></td>
                        <td  class="align-middle text-center"><a href="javascript:void(0)" onclick="delete_teval_group(`+b.id+`)"><i class="far fa-trash-alt text-danger"></i></a></td>
                    </tr>`
                )

                $('#questionnaire_holder').append(
                    `<tr>
                        <td width="85%"><b>`+b.description+`</b></td>   
                        <td  width="15%"></td> 
                    </tr>`
                )
                

                var question = b.question

                if(question.length == 0){
                    $('#teval_quetion_holder').append( `<tr>
                        <th></th>
                        <th class="text-center align-middle" colspan="5"><a href="#modal_teval_question_form" role="button" data-toggle="modal" onclick="form_teval_question(`+null+`,`+b.id+`)">Click here</a> to add question to `+b.description+`</th>
                    
                    </tr>`)

                  
                }

                $.each(question,function(c,d){

                    var temp_type = 'MC'
                    if(d.type == 'long_answer'){
                        temp_type = 'LA'
                    }

                    var type_info = '255 Characters';
                    if(d.type == 'multiple_choice'){
                        type_info = '<a href="#modal_teval_question_option_detail" role="button" data-toggle="modal" onclick="list_teval_question_option_detail('+d.option_id+')">'+d.option_desc+'</a>'
                    }

                    $('#teval_quetion_holder').append(
                        ` <tr>
                            <td class="text-center" >`+d.sort+`</td>
                            <td class="pl-4">`+d.question_desc+`</td>
                            <td class="text-center">`+temp_type+`</td>
                            <td class="text-center align-middle">`+type_info+`</td>
                            <td class="align-middle text-center"><a onclick="form_teval_question(`+d.id+`,`+b.id+`)" href="#modal_teval_question_form" role="button" data-toggle="modal"><i class="far fa-edit text-primary"></i></a></td>
                            <td class="align-middle text-center"><a href="javascript:void(0)" onclick="delete_teval_question(`+d.id+`)"><i class="far fa-trash-alt text-danger"></i></a></td>
                        </tr>`
                    )


                    if(d.type == 'multiple_choice'){

                        var temp_select = `<select class="form-control form-control-sm">
                                                <option values=""></option>`
                                
                        $.each(d.mc_detail,function(a,b){
                            temp_select +=  '<option values="'+b.value+'">'+b.display+'</option>'
                        })

                        temp_select += '</select>'

                        $('#questionnaire_holder').append(
                            ` <tr>
                                <td class="pl-4">
                                   `+d.question_desc+`
                                </td>
                                <td class="align-middle">
                                    `+temp_select+`
                                </td>
                            </tr>`
                        )
                    }else{
                        $('#questionnaire_holder').append(
                            ` <tr>
                                <td  colspan="2" class="pl-4">
                                    <p class="mb-0">`+d.question_desc+`</p>
                                    <textarea class="form-control" row="3"></textarea>
                                </td>
                            </tr>`
                        )
                    }
                   
                })

                
            })
        }

        function submit_form_teval_group(){
            var url = selected_teval_group == null ? '/teval/group/create' : '/teval/group/update'
            var button =  $('.btn[onclick="submit_form_teval_group()"]')
            $(button).attr('disabled','disabled')
            submit_teval_group(url)
        }

        function delete_teval_group(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "This will remove group.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    selected_teval_group = id
                    submit_teval_group('/teval/group/delete')
                }
            })
        }

        function submit_teval_group(url){
            var button = $('.btn[onclick="submit_form_teval_group()"]')
            $.ajax({
                    type:'GET',
                    url: url,
                    data:{
                        id:selected_teval_group,
                        description: $('#input_teval_qgroup_desc').val(),
                        group_sort: $('#input_teval_sort_desc').val(),
                        teval_id:selected_teval
                    },
                    success:function(response) {
                        if (response.success) {
                            displayMessage('success', response.message);
                            list_teval_group()
                        }else {
                            displayMessage('error', response.message);
                        }
                        $(button).removeAttr('disabled')
                    },
                    error:function(response){
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            displayErrors(errors);
                        }else if(response.status === 404){
                            displayMessage('error', 'Page not found');
                        }else{
                            displayMessage('error', response.responseJSON.message);
                        }
                        $(button).removeAttr('disabled')
                    }
            })
        }

        function list_teval_group(){
            $.ajax({
                    type:'GET',
                    url: '/teval/group/list',
                    data:{
                        teval_id:selected_teval
                    },
                    success:function(response) {
                        teval_group_data =  response

                        $('#input_teval_question_group').select2({
                            data:teval_group_data
                        })
                        
                        

                        teval_group_datatable(teval_group_data)
                    },
                    error:function(response){
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            displayErrors(errors);
                        }else if(response.status === 404){
                            displayMessage('error', 'Page not found');
                        }else{
                            displayMessage('error', response.responseJSON.message);
                        }
                    }
            })
        }

        
    </script>

    <script>  //teacher evaluation question
        var selected_teval_question = null
        $('#input_teval_question_type').select2()

        function form_teval_question(id = null, group =null){
            var button = $('.btn[onclick="submit_form_teval_question()"]')
            selected_teval_question = id;

            console.log(id)

            $('#input_teval_question_group').val(group).change()

            if(id != null){
               
                var group_info = teval_group_data.find(x=>x.id == group)
                var question_info =  group_info.question.find(x=>x.id == id)
              
                $('#input_teval_question_desc').val(question_info.question_desc)
                $('#input_teval_question_sort').val(question_info.sort)
                $('#input_teval_question_type').val(question_info.type).change()
                console.log(question_info)
                if(question_info.type == 'multiple_choice'){
                  
                    $('#input_teval_question_option_id').val(question_info.option_id).change()
                }
                // $('#input_teval_qgroup_desc').val(id === null ? "" : teval_group_data.find(x => x.id === id)?.description || "");
                // $('#input_teval_sort_desc').val(id === null ? "" : teval_group_data.find(x => x.id === id)?.group_sort || "");
            }else{
                $('#input_teval_question_desc').val("")
                $('#input_teval_question_sort').val("")
                $('#input_teval_question_type').val("").change()
            }
           
            update_form_button(id, button)
        }

        function submit_form_teval_question(){
            var url = selected_teval_question == null ? '/teval/question/create' : '/teval/question/update'
            var button =  $('.btn[onclick="submit_form_teval_question()"]')
            $(button).attr('disabled','disabled')
            submit_teval_question(url)
        }

        function delete_teval_question(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "This will remove question.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    selected_teval_group = id
                    submit_teval_group('/teval/question/delete')
                }
            })
        }

        function submit_teval_question(url){
            var button = $('.btn[onclick="submit_form_teval_question()"]')
            $.ajax({
                    type:'GET',
                    url: url,
                    data:{
                        id:selected_teval_question,
                        teval_id:selected_teval,
                        question_desc: $('#input_teval_question_desc').val(),
                        sort: $('#input_teval_question_sort').val(),
                        group: $('#input_teval_question_group').val(),
                        type: $('#input_teval_question_type').val(),
                        option_id: $('#input_teval_question_option_id').val(),
                    },
                    success:function(response) {
                        if (response.success) {
                            displayMessage('success', response.message);
                            list_teval_group()
                        }else {
                            displayMessage('error', response.message);
                        }
                        $(button).removeAttr('disabled')
                    },
                    error:function(response){
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            displayErrors(errors);
                        }else if(response.status === 404){
                            displayMessage('error', 'Page not found');
                        }else{
                            displayMessage('error', response.responseJSON.message);
                        }
                        $(button).removeAttr('disabled')
                    }
            })
        }

    </script>

    <script>  //teacher evaluation option
        var teval_question_option_data = []
        var selected_teval_question_option = null

        function form_teval_question_option(id = null){
            var button = $('.btn[onclick="submit_form_teval_question_option()"]')
            selected_teval_question_option = id;
            $('#input_teval_option_desc').val(id === null ? "" : teval_question_option_data.find(x => x.id === id)?.desc || "");
            update_form_button(id, button)
        }

        function teval_question_option_datatable(data){
            $("#question_option_datatable").DataTable({
                    destroy: true,
                    lengthChange: false,
                    autoWidth: false,
                    stateSave: true,
                    data:data,
                    columns: [
                            { "data": "desc" },
                            { "data": null },
                            { "data": null },
                            { "data": null },
                    ],
                    columnDefs: [
                                    {
                                        'targets': 0,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                        }
                                    },
                                    {
                                        'targets': 1,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="#modal_teval_question_option_detail" role="button" data-toggle="modal" onclick="list_teval_question_option_detail('+rowData.id+')">'+rowData.details.length+' ( View Details )</a>';
                                                $(td)[0].innerHTML = buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                        }
                                    },
                                    {
                                        'targets': 2,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="javascript:void(0)" onclick="form_teval_question_option('+rowData.id+')"  ><i class="far fa-edit text-primary" href="#modal_teval_option_form" role="button" data-toggle="modal"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                        }
                                    },
                                    {
                                        'targets': 3,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="javascript:void(0)" onclick="delete_teval_question_option('+rowData.id+')"><i class="far fa-trash-alt text-danger"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                        }
                                    },
                            ]
                })

                var label_text = $($('#question_option_datatable_wrapper')[0].children[0])[0].children[0]
                $(label_text)[0].innerHTML = '<button class="btn btn-sm btn-primary" href="#modal_teval_option_form" role="button" data-toggle="modal" onclick="form_teval_question_option()">Create Option</button>'

        }

        function submit_form_teval_question_option(){
            var url = selected_teval_question_option == null ? '/teval/question/option/create' : '/teval/question/option/update'
            var button =  $('.btn[onclick="submit_form_teval_question_option()"]')
            $(button).attr('disabled','disabled')
            submit_teval_question_option(url)
        }

        function submit_teval_question_option(url){
            var button = $('.btn[onclick="submit_form_teval_question_option()"]')
            $.ajax({
                    type:'GET',
                    url: url,
                    data:{
                        id:selected_teval_question_option,
                        teval_id:selected_teval,
                        desc: $('#input_teval_option_desc').val(),
                    },
                    success:function(response) {
                        if (response.success) {
                            displayMessage('success', response.message);
                            list_teval_question_option()
                        }else {
                            displayMessage('error', response.message);
                        }
                        $(button).removeAttr('disabled')
                    },
                    error:function(response){
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            displayErrors(errors);
                        }else if(response.status === 404){
                            displayMessage('error', 'Page not found');
                        }else{
                            displayMessage('error', response.responseJSON.message);
                        }
                        $(button).removeAttr('disabled')
                    }
            })
        }

        function delete_teval_question_option(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "This will remove question option.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    selected_teval_question_option = id
                    submit_teval_question_option('/teval/question/option/delete')
                }
            })
        }

        function list_teval_question_option(id = null){
            $.ajax({
                    type:'GET',
                    data:{
                        teval_id:selected_teval
                    },
                    url: '/teval/question/option/list',
                    success:function(response) {
                        teval_question_option_data = response

                        $('#input_teval_question_option_id').select2({
                            'data':teval_question_option_data
                        })

                        teval_question_option_datatable(teval_question_option_data)
                    },
                    error:function(response){
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            displayErrors(errors);
                        }else if(response.status === 404){
                            displayMessage('error', 'Page not found');
                        }else{
                            displayMessage('error', response.responseJSON.message);
                        }
                    }
            })
        }
    </script>

    <script>  //teacher evaluation option detial
        var teval_question_option_detail_data = []
        var selected_teval_question_option_detail = null

        function form_teval_question_option_detail(id = null){
            var button = $('.btn[onclick="submit_form_teval_question_option_detail()"]')
            selected_teval_question_option_detail = id;
            if(selected_teval_question_option_detail != null){
                var temp_info = teval_question_option_detail_data.find(x => x.id === id)
                $('#input_teval_option_detail_display').val(temp_info.display);
                $('#input_teval_option_detail_value').val(temp_info.value);
                $('#input_teval_option_detail_sort').val(temp_info.sort);
                $('#input_teval_option_detail_desc').val(temp_info.desc);
            }else{
                $('#input_teval_option_detail_display').val("");
                $('#input_teval_option_detail_value').val("");
                $('#input_teval_option_detail_sort').val("");
                $('#input_teval_option_detail_desc').val("");
            }
           
            update_form_button(id, button)
        }

        function teval_question_option_detail_datatable(data){

            console.log(data)

            $("#teval_question_option_detail").DataTable({
                    destroy: true,
                    lengthChange: false,
                    autoWidth: false,
                    stateSave: true,
                    data:data,
                    columns: [
                            { "data": "sort" },
                            { "data": "display" },
                            { "data": "value" },
                            { "data": "desc" },
                            { "data": null },
                            { "data": null },
                    ],
                    columnDefs: [
                                    {
                                        'targets': 4,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="javascript:void(0)" onclick="form_teval_question_option_detail('+rowData.id+')"  ><i class="far fa-edit text-primary" href="#modal_teval_option_detail_form" role="button" data-toggle="modal"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                        }
                                    },
                                    {
                                        'targets': 5,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<a href="javascript:void(0)" onclick="delete_teval_question_option_detail('+rowData.id+')"><i class="far fa-trash-alt text-danger"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('text-center')
                                                $(td).addClass('align-middle')
                                        }
                                    },
                            ]
                })

                var label_text = $($('#teval_question_option_detail_wrapper')[0].children[0])[0].children[0]
                $(label_text)[0].innerHTML = '<button class="btn btn-sm btn-primary" href="#modal_teval_option_detail_form" role="button" data-toggle="modal" onclick="form_teval_question_option_detail()">Create Evaluation Ratings</button>'

        }

        function submit_form_teval_question_option_detail(){
            var url = selected_teval_question_option_detail == null ? '/teval/question/option/detail/create' : '/teval/question/option/detail/update'
            var button =  $('.btn[onclick="submit_form_teval_question_option_detail()"]')
            $(button).attr('disabled','disabled')
            submit_teval_question_option_detail(url)
        }

        function submit_teval_question_option_detail(url){
            var button = $('.btn[onclick="submit_form_teval_question_option_detail()"]')
            $.ajax({
                    type:'GET',
                    url: url,
                    data:{
                        id:selected_teval_question_option_detail,
                        headerid:selected_teval_question_option,
                        display: $('#input_teval_option_detail_display').val(),
                        value: $('#input_teval_option_detail_value').val(),
                        sort: $('#input_teval_option_detail_sort').val(),
                        desc: $('#input_teval_option_detail_desc').val(),
                    },
                    success:function(response) {
                        if (response.success) {
                            displayMessage('success', response.message);
                            list_teval_question_option_detail()
                        }else {
                            displayMessage('error', response.message);
                        }
                        console.log("ab")
                        $(button).removeAttr('disabled')
                    },
                    error:function(response){
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            displayErrors(errors);
                        }else if(response.status === 404){
                            displayMessage('error', 'Page not found');
                        }else{
                            displayMessage('error', response.responseJSON.message);
                        }
                        $(button).removeAttr('disabled')
                    }
            })
        }

        function delete_teval_question_option_detail(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "This will remove question option detail.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    selected_teval_question_option_detail = id
                    submit_teval_question_option_detail('/teval/question/option/detail/delete')
                }
            })
        }

        function list_teval_question_option_detail(id=null){
            if(id != null){
                selected_teval_question_option = id
            }
         
            $.ajax({
                    type:'GET',
                    data:{
                        headerid:selected_teval_question_option
                    },
                    url: '/teval/question/option/detail/list',
                    success:function(response) {
                        teval_question_option_detail_data =  response
                        teval_question_option_detail_datatable(teval_question_option_detail_data)
                        list_teval_question_option()
                    },
                    error:function(response){
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            displayErrors(errors);
                        }else if(response.status === 404){
                            displayMessage('error', 'Page not found');
                        }else{
                            displayMessage('error', response.responseJSON.message);
                        }
                    }
            })
        }
    </script>

    <script>  //teacher evaluation sy
        
        var selected_teval_sy = null

        function form_sy_teval(id = null){
            var button = $('.btn[onclick="submit_form_teval_sy()"]')
            selected_teval_sy = id;
            update_form_button(id, button)
        }

        function teval_sy_datatable(data){
          

        }

        function submit_form_teval_sy(){
            var url = selected_teval_sy == null ? '/teval/sy/create' : '/teval/sy/update'
            var button =  $('.btn[onclick="submit_form_teval_sy()"]')
            $(button).attr('disabled','disabled')
            submit_teval_sy(url)
        }

        function delete_teval_sy(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "This will remove setup.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    selected_teval_sy = id
                    submit_teval_sy('/teval/sy/delete')
                }
            })
        }

        function activate_teval_sy(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "This will activate evaluation.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Activate'
            }).then((result) => {
                if (result.value) {
                    selected_teval_sy = id
                    submit_teval_sy('/teval/sy/activate')
                }
            })
        }

        function end_teval_sy(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "This end evaluation.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'End'
            }).then((result) => {
                if (result.value) {
                    selected_teval_sy = id
                    submit_teval_sy('/teval/sy/end')
                }
            })
        }

        function submit_teval_sy(url){
            var button = $('.btn[onclick="submit_form_teval_sy()"]')
            $.ajax({
                    type:'GET',
                    url: url,
                    data:{
                        syid:selectedsy,
                        levelid:selectedgradelevel,
                        teval_id:$('#input_teval_sy_tevalid').val(),
                        term:$('#input_teval_sy_term').val(),
                        id:selected_teval_sy,
                        type:selected_type
                        // teval_desc: $('#input_teval_desc').val(),
                    },
                    success:function(response) {
                        if (response.success) {
                            displayMessage('success', response.message);
                            list_teval_sy()
                        }else {
                            displayMessage('error', response.message);
                        }
                        $(button).removeAttr('disabled')
                    },
                    error:function(response){
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            displayErrors(errors);
                        }else if(response.status === 404){
                            displayMessage('error', 'Page not found');
                        }else{
                            displayMessage('error', response.responseJSON.message);
                        }
                        $(button).removeAttr('disabled')
                    }
            })
        }

        function list_teval_sy(){
            $.ajax({
                    type:'GET',
                    url: '/teval/sy/list',
                    data:{
                        syid:selectedsy,
                        type:selected_type
                    },
                    success:function(response) {
                        teval_sy_data =  response
                        $('.level_evalsetup').each(function(){
                            $(this).text("")
                        })
                        $('.level_term').each(function(){
                            $(this).text("")
                        })
                        $('.level_status').each(function(){
                            $(this).text("")
                        })
                        $('.level_button').each(function(){
                            $(this)[0].innerHTML = '<button class="btn btn-primary btn-sm" href="#modal_teval_sy_form" role="button" data-toggle="modal" onclick="selected_gradelevel('+$(this).attr('data-id')+')" style="font-size: .7rem !important">Assign</button>'
                        })
                       

                        $.each(teval_sy_data,function(a,b){
                        
                            $('.level_evalsetup[data-id="'+b.levelid+'"]').text(b.teval_desc)
                            $('.level_term[data-id="'+b.levelid+'"]').text(b.term)

                            if(b.activated == 0){
                                $('.level_status[data-id="'+b.levelid+'"]')[0].innerHTML = '<a href="#" onclick="activate_teval_sy('+b.id+')">Click here</a> to activate'

                                $('.level_button[data-id="'+b.levelid+'"]')[0].innerHTML = '<button class="btn btn-sm btn-danger" style="font-size:.6rem !important" onclick="delete_teval_sy('+b.id+')">Delete Setp</button>'

                            }else{
                                $('.level_status[data-id="'+b.levelid+'"]')[0].innerHTML = '<span class="badge badge-success">Activated</span> <br><i style="font-size:.7rem !important">' + b.dateactivated + '</i>'
                                $('.level_button[data-id="'+b.levelid+'"]')[0].innerHTML = '<button class="btn btn-sm btn-danger" style="font-size:.6rem !important" onclick="end_teval_sy('+b.id+')">End Evaluation</button>'
                                
                            }
                        })
                    },
                    error:function(response){
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            displayErrors(errors);
                        }else if(response.status === 404){
                            displayMessage('error', 'Page not found');
                        }else{
                            displayMessage('error', response.responseJSON.message);
                        }
                    }
            })
        }

    </script>
    
@endsection

