
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
      }else if(Session::get('currentPortal') == 24){
            $extend = 'academiccoor.layouts.app2';
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


@section('content')
    
    
<style>

    p.li_p:hover{
        color: #007bff;
        cursor: pointer;
    }
	h5.values_title:hover{

        color: #007bff;
        cursor: pointer;
    }
</style>
<!-- Font Awesome -->
   
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/eugz.css') }}"> 

<!-- Add Deportment Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addModalLabel">Deportment Setup</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_deportment_setup">
                    <div class="mb-3">
                        <label for="deportment_desc" class="form-label">Deportment Description</label>
                        <input type="email" class="form-control" name="deportment_desc">
                        <div id="emailHelp" class="form-text"></div>
                    </div>

                    <!--<div class="mb-3" id="element_1">
                        <button class="remove_input" value="element_1">
                            ×
                        </button>
                        <label for="deportment_desc" class="form-label">Values Label</label>
                        
                        <input type="email" class="form-control" name="value_1">
                    </div>-->

                </form>

                    <!--<a type="button" class="btn link-primary add_more_values">
                        <i class="fas fa-plus"></i> Add More
                    </a>-->
            </div>
            <div class="modal-footer">
                
                <button type="button" class="btn btn-primary create_deportment">Create</button>
            </div>
            </div>
        </div>
    </div>

<!-- Add Deportment Modal END-->

<!-- View Deportment Modal -->
    <div class="modal fade bd-example-modal-xl" id="viewDeportment" tabindex="-1" role="dialog" aria-labelledby="viewDeportment" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <div class="modal-header">
                <input type="hidden" id="deportmentID">
                <h4 class="modal-title"><span id="deportment_label"></span></h4>
                <div class="modal-header-buttons">
                    <button class="delete_deportment">
                        <i class="far fa-trash-alt text-danger"></i>
                    </button>

                    <button class="edit_deportment">
                        <i class="far fa-edit text-primary"></i>
                    </button>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                
            </div>

            <div class="modal-body">
                <div style="padding: 10px;  overflow: scroll; height: 80vh">
                    <div class="bg-primary note">
                        <p>Note: Click the item to edit or delete values.</p>
                    </div>
                    
                    <button type="button" class="btn btn-success btn-sm add_deportment_value">
                        <i class="fas fa-plus"></i> New Values
                    </button>

                    <div class="container" id="values_container" style="height: 80vh;"></div>

                </div>
            </div>

        </div>
    </div>
    </div>
<!-- View Deportment Modal END-->

<!-- Add Values Modal -->
    <div class="modal fade" id="addValuesModal" tabindex="-1" aria-labelledby="addValuesModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addValuesModalLabel">Add Values</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_values_setup">
                    
                    <input id="deportment_setupID" type="hidden">
                    <div class="mb-3" id="element_1">    
                        <button class="remove_input" value="element_1">
                            ×
                        </button>
                        <label for="deportment_desc" class="form-lab]el">Values Label</label>
                       
                        <input type="email" class="form-control" id="values_desc" name="value_1">
                    </div>

                </form>

                    <button type="button" class="btn link-primary add_more">
                        <i class="fas fa-plus"></i> Add More
                    </button>
            </div>
            <div class="modal-footer">
              
                <button type="button" class="btn btn-primary create_more_values_item" id="create_more_values_item">Create</button>
            </div>
            </div>
        </div>
    </div>

<!-- Add Values Modal END-->

<!-- Add Values Item Modal -->

    <div class="modal fade" id="addValueModal" tabindex="-1" aria-labelledby="addModalItemLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
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

                    <!-- <div class="mb-3">
                        <label for="deportment_desc" class="form-label">Highest Score</label>
                        <input type="text" class="form-control" id="value_highest_score" aria-describedby="emailHelp">
                       
                    </div> -->

                    <div class="mb-3">
                        <label for="deportment_desc" class="form-label">Values Abbreviation</label>
                        <input type="text" class="form-control" id="values_abbreviation" aria-describedby="emailHelp">
                        <p style="font-size: 15px" id="emailHelp" class="form-text">Abbreviation will be the label of table column.</p>
                    </div>

                    <div class="mb-3">
                        <label for="deportment_desc" class="form-label">Sorting</label>
                        <input type="text" class="form-control" id="values_sort" aria-describedby="emailHelp">
                        <p style="font-size: 15px" id="emailHelp" class="form-text">Sorting is limited to alphabet letters (eg. A, B, C ..., Z)</p>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
               
                <button type="button" class="btn btn-primary create_values_item" id="create_values_item">Create</button>
            </div>
            </div>
        </div>
    </div>
<!-- Add Values Item Modal END-->

<!-- BODY -->

    <div class="table-wrapper" style="padding-bottom: 0px">
        <h1 class="page-title" >Deportment Record</h1>
        
        <button type="button" class="btn btn-success btn-sm outerbutton" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus"></i> New Setup
        </button>

    </div>

    <div id="deportment_values_div"></div>

        <section class="content pt-0">
            <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                                <div class="card shadow" style="">
                                    <div class="card-body">
                                            <div class="row mt-2">
                                                <div class="col-md-12" style="font-size:.9rem !important">
                                                        <table class="table-hover table table-striped table-sm table-bordered table-head-fixed nowrap display compact" id="deportment_datatable" width="100%" >
                                                            <thead>
                                                                    <tr>
                                                                        <th width="70%">Deportment Description</th>
                                                                        <!-- <th width="20%" class="align-middle">Status</th> -->
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
                
    
        


<!-- BODY END-->


@endsection


@section('footerjavascript')

<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>

<script>

    var deportment_list = @json($deportments);
    var values_items = [];
    
    $(document).ready(function(){

        var counter = 1;

        load_deportment_datatable();
        get_deportment_list();


        // UI INTERACTION

            $(document).on('click', '.btn_values_item', function (event) { // OPEN MODAL AND ASSIGN VALUES SETUP ID
                var id = $(this).val();

                $('#addModalItemLabel').text('Add Values');
                $('#values_item_desc').val('');
                $('#values_abbreviation').val('');
                // $('#value_highest_score').val('');

                $('#create_values_item').addClass('create_values_item');
                $('#create_values_item').removeClass('edit_values_item');

                $('.create_values_item').text('Add');
                $('#values_id').val(id);
                $('#addValueModal').modal('toggle');


            });
          
            $(document).on('click', '.add_more_values', function (event) { // ADDING MORE VALUE INPUT

                var element = $('#element_1').clone().attr('id', 'element_' + ++counter).appendTo('#form_deportment_setup');
                element.children('input').attr('name', 'value_' + +counter)
                element.children('button').attr('value', 'element_' + +counter)

            });

            $(document).on('click', '.add_more', function (event) { // ADDING MORE VALUE INPUT

                var element = $('#element_1').clone().attr('id', 'values_' + ++counter).appendTo('#form_values_setup');
                element.children('input').attr('name', 'value_' + +counter)
                element.children('button').attr('value', 'values_' + +counter)

            });


            $(document).on('click', '.remove_input', function (event) { // DELETE VALUE INPUT

                var id =  $(this).val();
                
                if (id !== 'element_1') {

                    $("#"+id).remove();

                }else{

                    notify('error', 'Must have atleast 1 values.')
                    return false;
                }


            });

            $(document).on('click', '.view_deportment', function (event) { // OPEN DEPORTMENT MODAL
                
                var id = $(this).attr('data-id');
                $('#deportment_label').text($(this).html());
                $('#viewDeportment').modal('toggle');
                $('#deportmentID').val(id);
                load_values_list(id);

            });

            $(document).on('change', '.hide_show', function (event) { 

                let id = $(this).val();

                if($(this).is(":checked"))   
                $('#hidden_div'+id).prop('hidden', true);
                else
                $('#hidden_div'+id).prop('hidden', false);


            });

            $(document).on('change', '.item_hide_show', function (event) { 

                let id = $(this).val();

                if($(this).is(":checked"))   
                $('#hidden_div_item'+id).prop('hidden', true);
                else
                $('#hidden_div_item'+id).prop('hidden', false);


            });

            $(document).on('click', '.add_deportment_value', function (event) { // OPEN MODAL ADD NEW VALUES

                var deportment_setupID = $('#deportmentID').val();
                $('#values_desc').val('');

                $('.add_more').css('display', 'inline-block');
                $('.add_more').prop('disabled', false);
                $('#addValuesModalLabel').text("Add Values");

                $('#create_more_values_item').text("Create");
                
                $('#create_more_values_item').addClass("create_more_values_item");
                $('#create_more_values_item').removeClass("edit_values");


                $('#deportment_setupID').val(deportment_setupID);
                $('#addValuesModal').modal('toggle');

                


            });


            

        // UI INTERACTION END


        // AJAXX CRUD FUNCTIONS

             $(document).on('click', '.create_deportment', function (event) { //CREAT DEPORTMENT

                let data = $('#form_deportment_setup').serializeArray();

                var isNull = false; 
                
                $('#form_deportment_setup :input').each(function(index, el)
                {
                    if ($(el).val() == null || $(el).val() == ""){

                        isNull = true;
                    } 
                });

                if(isNull){
                    notify('error', "Values Label is Required!");
                    
                }else{
                
					$.ajax({

						url:'{{ route("deportment.create") }}',
						type:"GET",
						data:{
							formData: data
						},
						success:function(response){
							
							notify(response[0].statusCode, response[0].message);
							get_deportment_list();
							
						}
					});

                }

            });
            
            
            $(document).on('click', '.create_values_item', function (event) { //ADD VALUES ITEM PER VALUES

                let values_desc = $('#values_item_desc').val();
                let values_abbreviation = $('#values_abbreviation').val();
                let values_setupID = $('#values_id').val();
                // let value_highest_score = $('#value_highest_score').val();
                let deportment_setupID = $('#deportmentID').val();
                let values_sort = $('#values_sort').val();
                

                if(values_sort.length > 1 || $.isNumeric(values_sort)){

                    notify('error', 'Sorting is limited to alphabet letter!');

                    return false;
                }

                
                event.preventDefault();

                $.ajax({

                    url:'{{ route("values.create") }}',
                    type:"GET",
                    data:{
                        deportment_setupID: deportment_setupID,
                        values_setupID: values_setupID,
                        values_desc: values_desc,
                        values_abbreviation: values_abbreviation,
                        // value_highest_score: value_highest_score,
                        values_sort: values_sort
                    },
                    success:function(response){
                        
                        if(response[0].status == 404){

                            if(response[0].message.lenght > 1 ){

                                message.forEach(element => {
                                    
                                    console.log(element);
                                });

                            }else{

                                // notify(response[0].code, response[0].message['value_highest_score'][0]);

                            }

                        }else if(response[0].status == 500){

                            notify(response[0].statusCode, response[0].message);
                        
                        }else{

                            load_values_list(deportment_setupID);    
                            notify(response[0].statusCode, response[0].message);

                        }
                       
                    }
                });

            });


            $(document).on('click', '.create_more_values_item', function (event) { //ADD VALUES ITEM PER VALUES

                let data = $('#form_values_setup').serializeArray();
                let id = $('#deportment_setupID').val();


                event.preventDefault();

                $.ajax({

                    url:'{{ route("values.more") }}',
                    type:"GET",
                    data:{
                        
                        formData: data,
                        id:id
                    },
                    success:function(response){
                        
                        load_values_list(id);    
                        notify(response[0].statusCode, response[0].message);
                        
                    }
                });

            });


            $(document).on('click', '.edit', function (event) { // OPEN MODAL AND ASSIGN VALUES TO EDIT MODAL
                var id = $(this).val();

                $.ajax({

                    url:'{{ route("get.values") }}',
                    type:"GET",
                    data:{
                        id: id,
                    },
                    success:function(response){
                        
                        $('#values_id').val(response[0]['values_setupID']);
                        $('#values_item_desc').val(response[0]['value_item_desc']);
                        $('#values_abbreviation').val(response[0]['value_item_abbr']);
                        // $('#value_highest_score').val(response[0]['value_highest_score']);
                        $('#values_sort').val(response[0]['sort']);
                        $('#create_values_item').removeClass('create_values_item');
                        $('#create_values_item').addClass('edit_values_item');
                        
                        $('#addModalItemLabel').text('Edit Values');
                        $('.edit_values_item').text('Save');
                        $('.edit_values_item').attr('value',response[0]['id']);
                        $('#addValueModal').modal('toggle');
                        
                    }
                });
                            
            });


            $(document).on('click', '.edit_values_item', function (event) { //EDIT VALUES ITEM PER VALUES

                let id = $(this).val();

                let values_desc = $('#values_item_desc').val();
                let values_abbreviation = $('#values_abbreviation').val();
                // let value_highest_score = $('#value_highest_score').val();
                let deportment_setupID = $('#deportmentID').val();
                let values_sort = $('#values_sort').val();

                event.preventDefault();

                if(values_sort.length > 1 || $.isNumeric(values_sort)){

                    notify('error', 'Sorting is limited to alphabet letter!');

                    return false;
                }

                $.ajax({

                    url:'{{ route("edit.values") }}',
                    type:"GET",                    
                    data:{

                        id:id,
                        values_desc: values_desc,
                        values_abbreviation: values_abbreviation,
                        // values_highest_score: value_highest_score,
                        values_sort:values_sort
                    },
                    success:function(response){
                        
                        load_values_list(deportment_setupID);    
                        notify(response[0].statusCode, response[0].message);    
                    }
                });

            });


            $(document).on('click', '.delete_values_item', function (event) { //DELETE VALUES ITEM PER VALUES

                let id = $(this).val();
                let deportment_setupID = $('#deportmentID').val();


                event.preventDefault();

                $.ajax({

                    url:'{{ route("values.delete") }}',
                    type:"GET",
                    data:{

                        id: id,
                    },
                    success:function(response){

                        load_values_list(deportment_setupID);    
                        notify(response[0].statusCode, response[0].message);
                    }
                });

            });



            $(document).on('click', '.edit_deportment_values', function (event) { //OPEN EDIT VALUES

                let id = $(this).val();
                let deportment_setupID = $('#deportmentID').val();

                event.preventDefault();

                $.ajax({

                    url:'{{ route("get.deportment.values") }}',
                    type:"GET",
                    data:{

                        id: id,
                    },
                    success:function(response){

                        $('#deportment_setupID').val(id);
                        $('#addValuesModalLabel').text("Edit Values");
                        $('.create_more_values_item').text("Save");
                        $('.create_more_values_item').addClass("edit_values");
                        $('.create_more_values_item').removeClass("create_more_values_item");
                        $('.edit_values').attr('value',response[0]['id']);

                        $('.add_more').css('display', 'none');
                        $('.add_more').prop('disabled', true);
                        
                        $('#values_desc').val(response[0]['data'][0]['value_desc']);

                        $('#addValuesModal').modal('toggle');
                        
                    }
                });


            });


            $(document).on('click', '.edit_values', function (event) { //EDIT VALUES

                let id = $(this).val();
                let deportment_setupID = $('#deportmentID').val();
                let value_desc = $('#values_desc').val();

                event.preventDefault();

                $.ajax({

                    url:'{{ route("edit.deportment.values") }}',
                    type:"GET",
                    data:{
                        
                        value_desc: value_desc,
                        id: id,
                    },
                    success:function(response){

                        load_values_list(deportment_setupID);    
                        notify(response[0].statusCode, response[0].message);
                    }
                });

            });


            $(document).on('click', '.delete_deportment_values', function (event) { //DELETE VALUES IN DEPORTMENT
			
			
						Swal.fire({
                              text: 'Are you sure you want to remove deportment values?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                            if (result.value) {

								let id = $(this).val();
								let deportment_setupID = $('#deportmentID').val();

								event.preventDefault();

								$.ajax({

									url:'{{ route("delete.deportment.values") }}',
									type:"GET",
									data:{
										id: id,
									},
									success:function(response){

										load_values_list(deportment_setupID);    
										notify(response[0].statusCode, response[0].message);
									}
								});
						    }
                        })
            });



            $(document).on('click', '.edit_deportment', function (event) { //OPEN EDIT DEPORTMENT MODAL

                var id = $('#deportmentID').val();

                event.preventDefault();

                $.ajax({

                    url:'{{ route("get.edit.deportment") }}',
                    type:"GET",
                    data:{
                        
                        id: id,
                    },
                    success:function(response){

                        $('#deportment_setupID').val(response[0]['id']);
                        $('#addValuesModalLabel').text("Edit Deportment");

                        $('#create_more_values_item').text("Save");
                        $('#create_more_values_item').addClass("edit_deportment_desc");
                        $('#create_more_values_item').removeClass("create_more_values_item");
                        $('#create_more_values_item').removeClass("edit_values");
                        $('.edit_values').attr('value',response[0]['id']);

                        $('.add_more').css('display', 'none');
                        $('.add_more').prop('disabled', true);
                        
                        $('#values_desc').val(response[0]['deportment_desc']);

                        $('#addValuesModal').modal('toggle');
                    }
                });

            });


            $(document).on('click', '.edit_deportment_desc', function (event) { //AJAX EDIT DEPORTMENT 

                let id = $('#deportmentID').val();
                let deportment_desc = $('#values_desc').val();

                event.preventDefault();

                $.ajax({

                    url:'{{ route("edit.deportment") }}',
                    type:"GET",
                    data:{
                        
                        deportment_desc: deportment_desc,
                        id: id,
                    },
                    success:function(response){
                        
                        $('#deportment_label').text(response[0]['deportment'][0]['deportment_desc']);
                        notify(response[0].statusCode, response[0].message);
                        get_deportment_list();
                        
                    }
                });
                

            });


            $(document).on('click', '.delete_deportment', function (event) { //DELETE VALUES IN DEPORTMENT
			
						Swal.fire({
                              text: 'Are you sure you want to remove deportment setup?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {

								 let id = $('#deportmentID').val();
								let deportment_desc = $('#values_desc').val();

								event.preventDefault();

								$.ajax({

									url:'{{ route("delete.deportment") }}',
									type:"GET",
									data:{
										
										id: id,
									},
									success:function(response){
				   
										$('#viewDeportment').modal('toggle');
										notify(response[0].statusCode, response[0].message);
										get_deportment_list();

										
									}
								});
							}
                        })

            });





        // AJAXX CRUD FUNCTIONS END


    });


    function load_deportment_datatable(){

        $("#deportment_datatable").DataTable({
                destroy: true,
                data:deportment_list,
                lengthChange : false,
                columns: [
                            { "data": "deportment_desc" },
                            // { "data": "status" },
                    ],
                columnDefs: [
                    {
                            'targets': 0,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                var disabled = '';
                                var buttons = '<a style="cursor: pointer"  class="view_deportment text-primary" data-id="'+rowData.id+'">'+rowData.deportment_desc+'</a>';
                                $(td)[0].innerHTML =  buttons
                            }
                    },
                ]
                
        });
    }


    function get_deportment_list(){

        $.ajax({
                type:'GET',
                url:'{{ route("get.all.deportment") }}',
                success:function(data) {
                    
                    if(data.length == 0){

						deportment_list = [];
						load_deportment_datatable();
                        notify('warning', 'No deportment found.');
                     
                    }else{
                        deportment_list = data;
                        load_deportment_datatable();
                    }
                }
        })
    }


    function load_values_list(id){

        $.ajax({
                type:'GET',
                url:'{{ route("get.specific.values") }}',
                    data:{

                        id: id,
                    },
                success:function(data) {
                    
                    if(data[0]['values'].length == 0){

                        notify('warning', 'No values found.');
                    
                    }else{

                        $('#values_container').html(data[0]['values']);
                   

                    }
                }
        })
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