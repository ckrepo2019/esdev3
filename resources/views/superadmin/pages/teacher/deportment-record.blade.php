
@php

      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

      if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }else if(Session::get('currentPortal') == 1){
            $extend = 'teacher.layouts.app';
      }else{
            if(isset($check_refid->refid)){
                  if($check_refid->refid == 27){
                        $extend = 'academiccoor.layouts.app2';
                  }
            }else{
                  $extend = 'general.defaultportal.layouts.app';
            }
      }
@endphp

@extends($extend)

@section('pagespecificscripts')
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{asset('plugins/jquery-image-viewer-magnify/css/jquery.magnify.min.css')}}">
      <link rel="stylesheet" href="{{asset('plugins/jquery-image-viewer-magnify/css/magnify-bezelless-theme.css')}}">
      <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }
            img{
                  border-radius: 0 !important
            }
            .myFont{
                  font-size:.8rem !important;
            }
            .tableFixHead {
                  overflow: auto;
                  height: 100px;
            }

            .tableFixHead thead th {
                  position: sticky;
                  top: 0;
                  background-color: #fff;
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            
            }

            .ribbon-wrapper.ribbon-lg .ribbon {
                  right: -16px;
                  top: 4px;
                  width: 160px;
            }

            .enroll , .view_enrollment {
                  cursor: pointer;
            }

            .form-control-sm-form {
                  height: calc(1.4rem + 1px);
                  padding: 0.75rem 0.3rem;
                  font-size: .875rem;
                  line-height: 1.5;
                  border-radius: 0.2rem;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }

      </style>
@endsection

@section('content')


    @php 
      $data = $transmutation;
      $deportments = $deportments;
      $sy = $sy;
      $sections = $sections;
      $student_grade;
    @endphp

    <!-- Font Awesome -->
   
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/eugz.css') }}"> 

<!-- Add Values Item Modal -->

    <div class="modal fade" id="addValueModal" tabindex="-1" aria-labelledby="addModalItemLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalItemLabel">Add Values</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>

                    <input id="values_id" type="hidden">

                    <div class="mb-3">
                        <label for="deportment_desc" class="form-label">Values Description</label>
                        <input type="text" class="form-control" id="values_item_desc" aria-describedby="emailHelp">
                        
                    </div>

                    <div class="mb-3">
                        <label for="deportment_desc" class="form-label">Highest Score</label>
                        <input type="text" class="form-control" id="value_highest_score" aria-describedby="emailHelp">
                       
                    </div>

                    <div class="mb-3">
                        <label for="deportment_desc" class="form-label">Values Abbreviation</label>
                        <input type="text" class="form-control" id="values_abbreviation" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary create_values_item" id="create_values_item">Create</button>
            </div>
            </div>
        </div>
    </div>
<!-- Add Values Item Modal END-->


<!--Transmutation Table Modal -->

    <div class="modal fade" id="showTransTable" tabindex="-1" aria-labelledby="showTransTableLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-trans">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showTransTableLabel">Transmutation Table</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body modal-body-trans">
                <div class="table-holder">
                    <table class="table trans-table">
                        <thead class="thead-dark table-header">
                            <tr>
                                <th class="text-center trans-table-th" scope="col">Initial Grade</th>
                                <th class="text-center trans-table-th" scope="col">Transmuted Grade</th>

                            </tr>
                        </thead>
                        <tbody>

                            @foreach($transmutation as $trans)
                                <tr>
                                    <td class="text-center"> {{ $trans->initial1 }} - {{ $trans->initial2 }}</td>
                                    <td class="text-center">{{ $trans->transmuted }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
<!-- Transmutation Table Modal END-->

<!--Base Rating Modal -->

    <div class="modal fade" id="showBaseRating" tabindex="-1" aria-labelledby="showBaseRatingLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showBaseRatingLabel">Bases for rating</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div class="card">
                    <div class="card-body">
                        <div id="values_item_div">
                                
                            <div class="card text-center">
                                <div class="card-body">
                                    <p class="card-text">No Data Found.</p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

            </div>
            </div>
        </div>
    </div>
<!-- Base Rating Modal END-->

<!-- Signatory Modal -->

    <div class="modal fade" id="signatoryModal" tabindex="-1" aria-labelledby="signatoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatoryModalLabel">New Signatory</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                
                <form>

                    <div class="mb-3">
                        <label for="deportment_desc" class="form-label">Signatory Type</label>
                        <input type="text" class="form-control" id="sign_type">
                        
                    </div>

                    <div class="mb-3">
                        <label for="deportment_desc" class="form-label">Name</label>
                        <input type="text" class="form-control" id="sign_name">
                    
                    </div>

                    <div class="mb-3">
                        <label for="deportment_desc" class="form-label">Position</label>
                        <input type="text" class="form-control" id="sign_position">
                    </div>

                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success add_signatory" id="add_signatory">Add</button>
            </div>
            </div>
        </div>
    </div>

<!-- Signatory Modal END-->


<!--Submit Grade Modal -->

    <div class="modal fade" id="submitGrade" tabindex="-1" aria-labelledby="submitGradeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-trans  modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="submitGradeLabel">Submit Grades</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <div id="submit-grade" > -->
                    
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table-hover table-bordered table table-striped table-sm " id="deportment_datatable" width="100%" style="font-size: 12px">
                                <thead>
                                        <tr>
                                            <td width="2%">
             
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="allCheck">
                                                </div>
                                            </td>
                                            <th width="58%">Stdent Name</th>
                                            <th width="20%" class="text-center">Initial</th>
                                            <th width="20%" class="text-center">Final</th>
                                            <!-- <th width="20%" class="align-middle">Status</th> -->
                                        </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                <!-- </div> -->
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success submit" id="submit">Submit</button>
            </div>
            </div>
        </div>
    </div>
<!-- Submit Grade Modal END-->

<!-- BODY -->

    <div class="table-wrapper">
        <h1 class="page-title" >Deportment Record</h1>

        <!-- <button type="button" class="btn btn-success btn-sm outerbutton" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus"></i> New Setup
        </button> -->
        
        <div class="card">
            <div class="card-header">
                <h5 class="base-rating-title"><i class="fa fa-filter"></i> Filters</h5> 
                <button type="button" class="btn btn-primary btn-sm btn_filter">
                    <i class="fa fa-filter"></i> Filter
                </button>

            </div>

            <div class="card-body p-0">

                <div style="padding: 12px 12px 20px 12px">
                    
                    <form id="selection_form">

                        <div class="row">
                            

                            <div class="col-md-3">
                                <div class="select_container">
                                    <label for="search_sy">School Year</label>
                                    <select id="search_sy" name="search_sy" class=" form-control select2"></select>

                                </div>
                            </div>
                                
                            <div class="col-md-3">
                                <div class="select_container">
                                    <label for="search_section">Section</label>
                                    <select id="search_section" name="search_section" class=" form-control select2"></select>
                                    
                                </div>
                            </div>
                            
                            

                            <div class="col-md-3">
                                <div class="select_container">
                                    <label for="search_quarter">Quarter</label>
                                    <select id="search_quarter" name="search_quarter" class=" form-control select2"></select>

                                </div>
                            </div>
                        
        


                            <div class="col-md-3  form-group mb-0">
                                <div class="select_container_deportment">
                                    <label for="search_deportment">Deportment Setup</label>
                                    <select id="search_deportment" name="search_deportment" class=" form-control select2"></select>
                                </div>
                                
                            </div>

                        </div>

                    </form>
                </div>

            </div>

    
        </div>


            <div class="d-flex mb-2">

                <div class="text-left" style="flex-basis: 50%">

                    <a type="button" class="table_upper_button text-success submit_grade" hidden>
                        <i class="fas fa-sign-out-alt"></i>
                        Submit Grade
                    </a>

                    <a target="_blank" type="button" id="pdf" class="table_upper_button text-secondary generate_pdf" hidden>
                        <i class="fas fa-file-pdf text-danger"></i>
                        PDF
                    </a>

                    <a type="button" href="#" id="excel" class="table_upper_button text-secondary generate_excel" hidden>
                        <i class="fas fa-file-excel text-success"></i>
                        Excel
                    </a>

                </div>

                <div class="text-right" style="flex-basis: 50%">
                    <a type="button" id="reload" class="table_upper_button text-success" hidden >
                        <i class="fas fa-sync-alt"></i>
                        Reload
                    </a>
                    <a type="button" id="trans_trable" class="table_upper_button text-primary" hidden>
                        <i class="fas fa-table"></i>
                        Transmutation
                    </a>

                    <a type="button" id="rating" class="table_upper_button text-info" hidden>
                        <i class="fas fa-list"></i>
                        Base Rating
                    </a>

                </div>

            </div>   

            
            <div id="deportment_values_div" class="deportment_values_div m-0">
            
                <div class="card text-center">
                    <div class="card-header"></div>
                    <div class="card-body">
                        <p class="card-text">Use filter to display deportment table.</p>
                    </div>
                </div>
            
            </div>

        </div>

        <div id="signatory"></div>
    

<!-- BODY END-->


@endsection


@section('footerjavascript')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>    
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
      <script src="{{asset('plugins/jquery-image-viewer-magnify/js/jquery.magnify.min.js')}}"></script>
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
<script>




    var total_col = null;

    
    $(document).ready(function(){

        var counter = 1;
        var selected_sy = null;

        get_sy();
        get_section();
        get_quarter()
        get_deportment();


        // UI INTERACTION

            $(document).on('click', '.btn_values_item', function (event) { // OPEN MODAL AND ASSIGN VALUES SETUP ID
                var id = $(this).val();

                $('#addModalItemLabel').text('Add Values');
                $('#values_item_desc').val('');
                $('#values_abbreviation').val('');
                $('#value_highest_score').val('');

                $('#create_values_item').addClass('create_values_item');
                $('#create_values_item').removeClass('edit_values_item');

                $('.create_values_item').text('Add');
                $('#values_id').val(id);
                $('#addValueModal').modal('toggle');


            });

            $(document).on('click', '.add_deportment_value', function (event) { // OPEN MODAL ADD NEW VALUES

                search = $('.search_deportment').val();
                id = $(this).val();

                if(search != 0){

                    $('.add_more').css('display', 'inline-block');
                    $('.add_more').prop('disabled', false);
                    $('#addValuesModalLabel').text("Add Values");


                    $('#deportment_setupID').val(search);
                    $('#addValuesModal').modal('toggle');

                }else{

                    notify('warning', 'Select Deportment Setup first');
                    $('.search_deportment').css('box-shadow', 'red 0px 0px 7px');
                }
                


            });

                        
            $(document).on('click', '.add_more_values', function (event) { // ADDING MORE VALUE INPUT

                var element = $('#element_1').clone().attr('id', 'element_' + ++counter).appendTo('#form_deportment_setup');
                element.children('input').attr('name', 'value_' + +counter)
                element.children('button').attr('value', 'element_' + +counter)

            });

            $(document).on('click', '.add_more', function (event) { // ADDING MORE VALUE INPUT

                var element = $('#values_1').clone().attr('id', 'values_' + ++counter).appendTo('#form_values_setup');
                element.children('input').attr('name', 'value_' + +counter)
                element.children('button').attr('value', 'values_' + +counter)

            });


            $(document).on('click', '.remove_input', function (event) { // DELETE VALUE INPUT

                var id =  $(this).val();

                if (id !== 'values_1') {

                    $("#"+id).remove();

                }else{

                    notify('error', 'Must have atleast 1 values.')
                    return false;
                }

            });

            
            $(document).on('focusout', '.row_data', function(event) { //Remove yellow background sa cell

                $(this).removeClass('bg-warning');

            });


            $(document).on('click', '.btn_filter', function (event) { //FILTER

                if($('#search_section').val() == 0){

                    notify('warning', 'Please select a Section.')
                }

                else if($('#search_quarter').val() == 0){

                    notify('warning', 'Please select a Quarter.')

                }

                else if($('#search_deportment').val() == 0){

                    notify('warning', 'Please select a Deportment Setup.')

                }

                else{

                    load_data();

                }


            });


            $(document).on('click', '#reload', function (event) { //Reload Table

                load_data();

            });


            $(document).on('click', '#trans_trable', function (event) { //Show Transmutationt Table

                $('#showTransTable').modal('toggle');

            });


            $(document).on('click', '#rating', function (event) { //Show Transmutationt Table

                $('#showBaseRating').modal('toggle');

            });
            

            $(document).on('click', '.signatory', function (event) { //Show Transmutationt Table

                $('#signatoryModal').modal('toggle');
            });


            $(document).on('change', '#search_sy', function(event){

 
                temp = '<div class="card text-center">'
                        +'<div class="card-header"></div>'
                            +'<div class="card-body">'
                                +'<p class="card-text">Use filter to display deportment table.</p>'
                            +'</div>'
                        +'</div>';
                $('.table_upper_button').attr('hidden', true);
                $('#deportment_values_div').html(temp);

            });

            $(document).on('change', '#search_section', function(event){

            
                temp = '<div class="card text-center">'
                        +'<div class="card-header"></div>'
                            +'<div class="card-body">'
                                +'<p class="card-text">Use filter to display deportment table.</p>'
                            +'</div>'
                        +'</div>';
                $('.table_upper_button').attr('hidden', true);
                $('#deportment_values_div').html(temp);

            });

            $(document).on('change', '#search_quarter', function(event){

 
                temp = '<div class="card text-center">'
                        +'<div class="card-header"></div>'
                            +'<div class="card-body">'
                                +'<p class="card-text">Use filter to display deportment table.</p>'
                            +'</div>'
                        +'</div>';
                $('.table_upper_button').attr('hidden', true);
                $('#deportment_values_div').html(temp);

            });

            $(document).on('change', '#search_deportment', function(event){

 
                temp = '<div class="card text-center">'
                        +'<div class="card-header"></div>'
                            +'<div class="card-body">'
                                +'<p class="card-text">Use filter to display deportment table.</p>'
                            +'</div>'
                        +'</div>';
                $('.table_upper_button').attr('hidden', true);
                $('#deportment_values_div').html(temp);

            });

            $(document).on('click', '#allCheck', function(){
                $('input:checkbox[name=studentgrade]').not(this).prop('checked', this.checked);
            });



        // UI INTERACTION END



        // TABLE INTERACTION

            /////////////ARROW KEY CONTROLLER///////////////
            $(document).on('keyup', '.row_data', function(e){

                e.preventDefault(); 


                if (e.keyCode  == 9) {  //tab 

                    $(this).next('.row_data').find('div').focus();
                    $(this).addClass('bg-warning').css('padding-left','5px');

                }

                else if (e.keyCode == 37) { //left;

                    col = parseInt($(this).attr('col'))-1;
                    row = $(this).attr('row');
                    id = col+"_"+row;

                    // $(id).addClass('bg-warning').css('padding-left','5px');
                    // $(id).focus();

                    placeCaretAtEnd(document.getElementById(id));


                }
                else if (e.keyCode == 38) { //up

                    col = $(this).attr('col');
                    row = parseInt($(this).attr('row'))-1;
                    id = col+"_"+row;

                    // $(id).addClass('bg-warning').css('padding-left','5px'); 
                    // $(id).focus(); 

                    placeCaretAtEnd(document.getElementById(id));


                }
                else if (e.keyCode == 39) { //right

                    col = parseInt($(this).attr('col'))+1;
                    row = $(this).attr('row');
                    id = col+"_"+row;

                    // $(id).addClass('bg-warning').css('padding-left','5px');
                    // $(id).focus();

                    placeCaretAtEnd(document.getElementById(id));

                    


                }
                else if (e.keyCode == 40) {   //down

                    col = $(this).attr('col');
                    row = parseInt($(this).attr('row'))+1;
                    id = col+"_"+row;

                    // $(id).addClass('bg-warning').css('padding-left','5px');
                    // $(id).focus(); 

                    placeCaretAtEnd(document.getElementById(id));


                }
            });

            /////////////ARROW KEY CONTROLLER///////////////
            $(document).on('keyup', '.hps', function(e){

                e.preventDefault(); 


                if (e.keyCode == 37) { //left;

                    col = parseInt($(this).attr('col'))-1;
                    row = $(this).attr('row');
                    id = "hpsrow"+col;

                    placeCaretAtEnd(document.getElementById(id));


                }
               
                else if (e.keyCode == 39) { //right

                    col = parseInt($(this).attr('col'))+1;
                    row = $(this).attr('row');
                    id = "hpsrow"+col;

                    placeCaretAtEnd(document.getElementById(id));

                    


                }

            });
            

            /////////////EDITABLE CELL///////////////       
            $(document).on('click', '.row_data', function(event) {
                event.preventDefault(); 

                if($(this).attr('edit_type') == 'button')
                {
                    return false; 
                }

                //make div editable
                //add bg css
                // $(this).addClass('bg-warning');

                $(this).focus();

                $(this).attr('original_entry', $(this).html());
                

            });

            

        // TABLE INTERACTION END


        // AJAXX CRUD FUNCTIONS

            $(document).on('keyup', '.hps', function(e){

                var id = $(this).closest('tr').attr('row_id'); 
                var col_val = $(this).html(); 
                var col_name = $(this).attr('col_name'); 
                var total = 0; 

                if(col_val == ""){

                    return false;

                }else{

                    if($.isNumeric(col_val)){
                        
                        

                        for (let i = 1; i < total_col; i++) {
                            
                            value = $('#hpsrow'+i).html();

                            if(value == ""){

                                value = 0;
                            }
                            total += parseInt(value);

                        }

                        console.log(total_col);


                        
                        $('#over_all_total').text(total.toFixed());

                        $.ajax({
                            url: '{{ route("deportment.hps") }}' ,
                            method:'GET',
                            data: {
                                
                                id:id,
                                col_name:col_name,
                                value:col_val

                                
                            },
                            success:function(response){

                            }
                         
                            
                        });

                    }else{

                        notify("error", "Value must be numberic.")

                    }

                }

            });
            
            $(document).on('keyup', '.row_data', function(event) { //KEYUP GRADING
                event.preventDefault();

                if($(this).attr('edit_type') == 'button')
                {
                    return false; 
                }

                //get the original entry
                var original_entry = $(this).attr('original_entry');

                $(this)
                .removeClass('bg-warning') //remove bg css
                .css('padding','');

                var id = $(this).closest('tr').attr('row_id'); 
                var col_name = $(this).attr('col_name'); 
                var col = $(this).attr('col'); 
                var row = $(this).attr('row'); 
                var row_id = $(this).attr('id'); 
                var col_val = $(this).html(); 

                var over_all_total = parseInt($('#over_all_total').html());
                var high_score =  parseInt($('#hpsrow'+col).html());
                var total = 0; 
                var initial = 0; 
                var final = 0;


                if($('#hpsrow'+col).is(':empty')){

                    notify("error", "Please add Highest Possible Score first.")

                }else {

                    if(col_val == ""){

                        return false;

                    }else{

                        if($.isNumeric(col_val)){

                            if(col_val <= high_score){

                                for (let i = 1; i < total_col; i++) {
                            
                                    value = $('#'+i+'_'+row).html();

                                if(value == ""){

                                    value = 0;
                                }

                                total += parseInt(value);

                                }

                                initial = total/over_all_total*100;
                                final = transmute(initial);


                                $('#totalid'+row).text(total.toFixed());
                                $('#initialid'+row).text(initial.toFixed());
                                $('#finalid'+row).text(final.toFixed());


                                $.ajax({
                                url: '{{ route("grading.deportment") }}' ,
                                method:'GET',
                                data: {
                                    
                                    id:id,
                                    total: total,
                                    initial: initial,
                                    final: final,
                                    col_name:col_name,
                                    value:col_val,

                                    
                                },
                                success:function(response){

                                        if(response[0].status == 500){

                                            return false;

                                        }
                                    },
                                error:function(error){
                                    console.log(error)
                                }
                                
                                });
                                
                            }else{

                                notify("error", "Value must not exceed highest score.")

                            }

                        }else{

                            notify("error", "Value must be numberic.")

                        }

                    }
                }
                
                
            });

            
            $(document).on('click', '.add_signatory', function(event){

                let sign_name = $('#sign_name').val();
                let sign_type = $('#sign_type').val();
                let sign_position = $('#sign_position').val();

                let syid = $('#search_sy').val();
                let sectionid = $('#search_section').val();
                let quarter_ID = $('#search_quarter').val();
                let deportment_setupID = $('#search_deportment').val();

                $.ajax({
                    url: '{{ route("add.signatory") }}' ,
                    method:'GET',
                    data: {
                        
                        sign_name:sign_name,
                        sign_type: sign_type,
                        sign_position: sign_position,
                        
                        syid:syid,
                        sectionid:sectionid,
                        quarter_ID:quarter_ID,
                        
                    },
                    success:function(response){

                        notify(response[0]['statusCode'], response[0]['message']);
                        load_data();

                    }
                    
                });
                

            });

            $(document).on('click', '.delete_signatory', function(event){

                let id = $(this).attr('value');

                $.ajax({
                    url: '{{ route("delete.signatory") }}' ,
                    method:'GET',
                    data: {
                        id:id
                    },
                    success:function(response){

                        notify(response[0]['statusCode'], response[0]['message']);
                        load_data();

                    }
                    
                });
                

            });


            $(document).on('keyup', '.signatory_data', function(event) {
                event.preventDefault();

                var val = $(this).html(); 
                var col_name = $(this).attr('col_name'); 
                var id = $(this).attr('col_id'); 


                $.ajax({

                    url:'{{ route("edit.signatory") }}',
                    type:"GET",
                    data:{
                        val: val,
                        col_name: col_name,
                        id: id,
                    }
                });

            });

            $(document).on('click', '.generate_excel', function(event) { 
               
               var syid = $('#search_sy').val();
               var sectionid = $('#search_section').val();
               var quarter_ID = $('#search_quarter').val();
               var deportment_setupID = $('#search_deportment').val();

               var signatory_count = $('.signatory-holder').attr('signatory_count');

               if(signatory_count > 0){

                   $('.generate_excel').attr('href', "/grade/deportment-record/generate-excel" +"/"+syid+"/"+sectionid+"/"+quarter_ID+"/"+deportment_setupID);

               }else{

                   notify('error', 'Please assign signatory!')
               }

               

           });

            $(document).on('click', '.generate_pdf', function(event) { 
               
                var syid = $('#search_sy').val();
                var sectionid = $('#search_section').val();
                var quarter_ID = $('#search_quarter').val();
                var deportment_setupID = $('#search_deportment').val();

                var signatory_count = $('.signatory-holder').attr('signatory_count')

                if(signatory_count > 0){

                    window.open('/grade/deportment-record/generate-pdf' +'/'+syid+'/'+sectionid+'/'+quarter_ID+'/'+deportment_setupID, '_blank');

                }else{

                    notify('error', 'Please assign signatory!')
                }

                

            });

            $(document).on('click', '.submit_grade', function(event){

                var syid = $('#search_sy').val();
                var sectionid = $('#search_section').val();
                var quarter_ID = $('#search_quarter').val();
                $('#submitGrade').modal();

                $.ajax({

                    url:'{{ route("get.student.submit") }}',
                    type:"GET",
                    data:{

                        syid:syid,
                        sectionid:sectionid,
                        quarter_ID:quarter_ID,
                        
                    },success:function(response){


                        $("#deportment_datatable").DataTable({
                            destroy: true,
                            data:response,
                            lengthChange : false,
                            autoWidth: false,
                            scrollY: '60vh',
                            paging: false,
                            info: false,
                            columns: [
                                { "data": "id" },
                                { "data": null,

                                    render: function ( data, type, row ) {

                                        var middlename = row.middlename; 
                                        if(middlename == null){
                                            middlename = " ";

                                        }else{
                                            middlename = row.middlename.charAt(0)+".";

                                        }
                                        return row.lastname+", "+ row.firstname +" "+middlename;
                                    }  
                                                                
                                    },
                                { "data": "initial" },
                                { "data": "final" },
                            ],
                                
                            columnDefs: [
                                {
                                    'targets': 0,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                        var checkbox = '<div class="form-check"><input class="form-check-input grade_check" type="checkbox" name="studentgrade" value="'+rowData.id+'" id="flexCheckDefault"></div>';
                                        $(td)[0].innerHTML =  checkbox;
                                    }
                                },

                                {
                                    'targets': 1,
                                    'orderable': false, 
                                        
                                },


                                {
                                    'targets': 2,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                        $(td).addClass('text-center')
    
                                    }
                                },

                                {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                        $(td).addClass('text-center')
                                        if(rowData.final <= 74){

                                            $(td).addClass('text-danger')
                                            
                                        }

                                    }
                                },
                            ]
                                
                        });


                    }
                });

            });

            $(document).on('click', '.submit', function(event){

                array = [];
                    

                $("input:checkbox[name=studentgrade]:checked").each(function(){
                    array.push($(this).val());
                });

                if(array.length == 0){

                    notify('error', 'Please select student you want to submit.');

                }else{

                    Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to submit?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                    }).then((result) => {

                        if (result.value) {
                            var syid = $('#search_sy').val();
                            var sectionid = $('#search_section').val();
                            var quarter_ID = $('#search_quarter').val();
                            var hpsid = $('.hps-holder').attr('row_id');
                        

                            $.ajax({
                                
                                url:'{{ route("submit.student.grade") }}',
                                type:"GET",
                                data:{

                                    array:array,
                                    syid:syid,
                                    sectionid:sectionid,
                                    quarter_ID:quarter_ID,
                                    hpsid: hpsid
                                    
                                },success:function(response){

                                    notify(response[0]['statusCode'], response[0]['message']);

                                    $('#submitGrade').modal('toggle');

                                    load_data();
                                }

                            });
                        }
                            
                    })

                }

            });

        // AJAXX CRUD FUNCTIONS END

    });

    function get_sy(){ // Para ma display ang mga available school year sa select2
        var sy = @json($sy);

        for (let i = 0; i < sy.length; i++) {
            
            if(sy[i]['isactive'] == 1){

                selected_sy = sy[i]['id'];
            }
            
        }

        $('#search_sy').empty()
        $('#search_sy').append('<option value="">Select School Year</option>')
        $("#search_sy").select2({
            data: sy,
            allowClear: true,
            placeholder: "Select School Year",
        });

        if(selected_sy != null){

            $('#search_sy').val(selected_sy).change()
        }

    }

    function get_section(){ // Para ma display ang mga sections sa select2

        var sections = @json($sections);

        
        $('#search_section').empty()
        $('#search_section').append('<option value="">Select Sections</option>')
        $("#search_section").select2({
            data: sections,
            allowClear: true,
            placeholder: "Select Sections",
        })
    }

    function get_quarter(){ // Para ma display ang mga quarter sa select2

        var quarter = [
            {"id":1,"text":"1st Quarter"},
            {"id":2,"text":"2nd Quarter"},
            {"id":3,"text":"3rd Quarter"},
            {"id":4,"text":"4th Quarter"},

        ]


        $('#search_quarter').empty()
        $('#search_quarter').append('<option value="">Select Quarter</option>')
        $("#search_quarter").select2({
            data: quarter,
            allowClear: true,
            placeholder: "Select Quarter",
        })
    }

    function get_deportment(){ // Para ma display ang mga deportment sa select2
        var data = @json($deportments);
        
        $('#search_deportment').empty()
        $('#search_deportment').append('<option value="">Select Deportment Setup</option>')
        $("#search_deportment").select2({
            data: data,
            allowClear: true,
            placeholder: "Select Deportment Setup",
        })
    }

    function transmute (initial) { 

        var value = initial;
        var transmutation = @json($data);
        var grade = 0;

        $.each(transmutation,function(a,b){
            if(parseFloat(b.initial1) >= parseFloat(value)){
                    $.each(transmutation,function(c,d){
                        if(parseFloat(d.initial2) >= parseFloat(value)){
                            
                            grade = d.transmuted;
                        }
                    })
                }
        })

        return grade;
    }

    function placeCaretAtEnd(el) {
        el.focus();
        if (typeof window.getSelection != "undefined"
                && typeof document.createRange != "undefined") {
            var range = document.createRange();
            range.selectNodeContents(el);
            range.collapse(false);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        } else if (typeof document.body.createTextRange != "undefined") {
            var textRange = document.body.createTextRange();
            textRange.moveToElementText(el);
            textRange.collapse(false);
            textRange.select();
        }
    }

    function load_data(){


        let data = $('#selection_form').serializeArray();

        event.preventDefault();

        $.ajax({

            url:'{{ route("search.deportment") }}',
            type:"GET",
            data:{
                data: data,
            },
            success:function(response){

                $("#deportment_values_div").html(response[0]['deportment_values']);
                $("#values_item_div").html(response[0]['values_item']);
                $("#signatory").html(response[0]['signatory']);
  
                total_col = response[0]['total_col'];
                $('.table_upper_button').removeAttr('hidden')


            }
        });
    }


         
    /////////////SWEET ALERT///////////////
        function notify(code, message){
            Swal.fire({
                type: code,
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
            });

        }

</script>
@endsection