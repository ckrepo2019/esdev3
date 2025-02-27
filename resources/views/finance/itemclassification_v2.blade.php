@extends('finance.layouts.app')

@section('content')
  {{-- <style type="text/css">
    .table thead th  { 
                position: sticky !important; left: 0 !important; 
                width: 150px !important;
                background-color: #fff !important; 
                outline: 2px solid #fff !important;
                outline-offset: -1px !important;
            }
  </style> --}}
	{{-- <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Finance</h1> -->
          
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Payment Items</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section> --}}
  <section class="content">
  			<!-- Payment Items -->
        <div class="row mb-2 ml-2">
            <h1 class="m-0 text-dark">Item Classification</h1>
        </div>
        {{-- <div class="row">
            <div class="col-8">
            </div>
            <div class="col-4">
                <div class="input-group mb-3">
                    <input id="txtsearchitem" type="text" class="form-control" placeholder="Search Item" onkeyup="this.value = this.value.toUpperCase();">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-primary" id="class_create">Create</button>
                    </div>
                </div>
            </div>

        </div> --}}
		<div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        
                    </div>
                    <div class="card-body">
                        <div id="main_table" class="table-responsive p-0">
                            <button class="btn btn-primary text-sm btn-sm" id="class_create">Create Classification</button>
                            <table id="class_list" class="table table-hover table-head-fixed table-sm text-sm">
                                <thead class="bg-warning p-0">
                                  <tr>
                                    <th>DESCRIPTION</th>
                                    <th>ACCOUNTS</th>
                                    <th>CASHIER SETUP</th>
                                    <th>ITEMIZED - CASHIER</th>
                                    <th>CLASS CODE</th>
                                  </tr>
                                </thead> 
                                <tbody id="class_body" style="cursor: pointer;"></tbody>             
                            </table>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
  </section>
@endsection

@section('modal')
    <div class="modal fade show" id="modal-classification" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                <h4 class="modal-title">Item Classification</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Description</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control validation" id="class_description" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Accounts</label>
                                <div class="col-sm-9">
                                    <select class="form-control select2" id="class_account">
                                        <option value="0">&nbsp;</option>
                                        @foreach(db::table('acc_coa')->where('deleted', 0)->get() as $gl)
                                            <option value="{{$gl->id}}">{{$gl->code}} - {{$gl->account}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-3 col-form-label">Group</label>
                                <div class="col-sm-5">
                                    <select class="form-control select2" id="class_group">
                                        <option value="">&nbsp;</option>
                                        <option value="TUI">TUITION</option>
                                        <option value="MISC">MISCELLANEOUS</option>
                                        <option value="OTH">OTHER FEES</option>
                                    </select>
                                </div>
                                <div class="col-sm-3 mt-2">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="class_itemized" disabled>
                                        <label for="class_itemized">
                                            Itemized
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Class Code</label>
                                <div class="col-sm-9">
                                    <select class="form-control select2" id="class_code">
                                        <option value="0">&nbsp;</option>
                                        @foreach(db::table('items_classcode')->get() as $code)
                                            <option value="{{$code->id}}">{{$code->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="class_delete" type="button" class="btn btn-danger" style="display: none">Delete</button>
                    <button id="class_save" type="button" class="btn btn-primary" data-id="0">Save</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>


  

  
@endsection

@section('js')
  
  <script type="text/javascript">
    
    $(document).ready(function(){
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        $(window).resize(function(){
            screenadjust()    
        })

        screenadjust()

        function screenadjust()
        {
            var screen_height = $(window).height();

            $('#main_table').css('height', screen_height - 244)
            // $('.screen-adj').css('height', screen_height - 223);
        }

		genclassid()

		function genclassid()
		{
			$.ajax({
				type: "GET",
				// url: "{{route('itmclsgenerate')}}",
				url: '{{route('itmclsgenerate')}}',
				// data: "",
				// dataType: "dataType",
				success: function (data) {
					console.log(data)
					$('#class_list').dataTable({
						// paging: false,
                        // lengthChange: false,
                        searching: true,
                        ordering: false,
                        info: false,
                        autoWidth: true,
                        responsive: true,
                        paging:false,
                        // pageLength:8,
                        stateSave: true,
                        // scrollY: '255px',
                        destroy:true,
                        scrollX: false,
                        scrollCollapse:true,
                        data:data,
						columns: [
							{data:'description'},
							{data: 'account'},
							{data:'groupname'},
                            {data:'itemized'},
                            {data:'classcode'}
						],
						createdRow: function (row, data, dataIndex) {        
                          $(row).attr("data-id",data.id);
                          // $(row).attr("data-preregid",data.id);
                        },
						columnDefs: [{
							'targets': 3,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) 
                            {
                                // $(td).text(rowData.description)
                                if($(td).text() == 0)
                                {
                                    $(td)[0].innerHTML = '<span class="" ></span>'
                                }
                                else
                                {
                                    $(td)[0].innerHTML = '<span class="fa fa-check text-center" ></span>'
                                }

                                
                            }
						}]
					})
				}
			})
		}

		$(document).on('click', '#class_body tr', function(){
			var headerid = $(this).attr('data-id')
			
			
		})

        $(document).on('change', '#class_group', function(){
            if($(this).val() == '')
            {
                $('#class_itemized').prop('disabled', true);
                $('#class_itemized').prop('checked', false);
            }
            else{
                $('#class_itemized').prop('disabled', false);
            }
        });

        $(document).on('click', '#class_create', function(){
        	$('#modal-classification').modal('show')
			$('#class_description').val('')
			$('#class_account').val(0).change()
			$('#class_group').val('').change()
			$('#class_save').attr('data-id', 0)
        })

		$(document).on('click', '#class_save', function(){
			var description = $('#class_description').val()
			var account = $('#class_account').val()
			var group = $('#class_group').val()
            var dataid = $('#class_save').attr('data-id')
            var classcode = $('#class_code').val()

            if($('#class_itemized').prop('checked') == true)
            {
                var itemized =  1
            }
            else{
                var itemized =  0
            }

            

            if(dataid == 0)
            {
                $.ajax({
                    type: "GET",
                    url: "{{route('itmclscreate')}}",
                    data: {
                        description:description,
                        account:account,
                        group:group,
                        itemized:itemized,
                        classcode:classcode
                    },
                    // dataType: "dataType",
                    success: function (data) {
                        if(data == 'done')
                        {
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
                            })

                            Toast.fire({
                                type: 'success',
                                title: 'Item classification saved successfully'
                            })

                            $('#modal-classification').modal('hide')
                            genclassid()
                        }
                        else{
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
                            })

                            Toast.fire({
                                type: 'error',
                                title: 'Item classification already exist'
                            })
                        }
                    }
                })
            }
            else
            {
                $.ajax({
                    type: "GET",
                    url: "{{route('itmclsupdate')}}",
                    data: {
                        dataid:dataid,
                        description:description,
                        account:account,
                        group:group,
                        itemized:itemized,
                        classcode:classcode
                    },
                    // dataType: "dataType",
                    success: function (data) {
                        if(data == 'done')
                        {
                            console.log('aaa')
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
                            })

                            Toast.fire({
                                type: 'success',
                                title: 'Item classification updated successfully'
                            })

                            $('#modal-classification').modal('hide')
                            genclassid()
                        }
                        else{
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
                            })

                            Toast.fire({
                                type: 'success',
                                title: 'Item classification already exist'
                            })
                        }
                    }
                });
            }
		})

		$(document).on('click', '#class_body tr', function(){
            var dataid = $(this).attr('data-id');
            
            $('#class_save').attr('data-id', dataid)

            $.ajax({
                type: "GET",
                url: "{{route('itmclsread')}}",
                data: {
                    dataid:dataid
                },
                dataType: "json",
                success: function (data) {
                    $('#class_description').val(data.description)
                    $('#class_account').val(data.glid).change()
                    $('#class_group').val(data.group).change()
                    $("#class_code").val(data.classcode).change()
                    
                    if(data.itemized == 1)
                    {
                        $('#class_itemized').prop('checked', true)
                    }
                    else
                    {
                        $('#class_itemized').prop('checked', false)
                    }

                    if(data.withtransaction == 0)
                    {
                        $('#class_delete').show();
                    }
                    else{
                        $('#class_delete').hide();
                    }

                   $('#modal-classification').modal('show');
                }
            });
        })

        $(document).on('click', '#class_delete', function(){
            var dataid = $('#class_save').attr('data-id')
            
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.value == true) {
                $.ajax({
                    type: "GET",
                    url: "{{route('itmclsdelete')}}",
                    data: {
                        dataid:dataid
                    },
                    // dataType: "dataType",
                    success: function (response) {
                        $('#modal-classification').modal('hide')
                        genclassid()
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    }
                });

                
            }
            })

            
        })

	})

  </script>
  
@endsection