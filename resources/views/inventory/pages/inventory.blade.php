@php

    
    $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid','resourcepath')->first();

    if(Session::get('currentPortal') == 14){    
		$extend = 'deanportal.layouts.app2';
	}else if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
    }else if(Session::get('currentPortal') == 8){
        $extend = 'admission.layouts.app2';
    }else if(Session::get('currentPortal') == 1){
        $extend = 'teacher.layouts.app';
    }else if(Session::get('currentPortal') == 2){
        $extend = 'principalsportal.layouts.app2';
    }else if(Session::get('currentPortal') == 4){
        $extend = 'finance.layouts.app';
    }else if(Session::get('currentPortal') == 15){
        $extend = 'finance.layouts.app';
    }else if(Session::get('currentPortal') == 18){
        $extend = 'ctportal.layouts.app2';
    }else if(Session::get('currentPortal') == 10){
        $extend = 'hr.layouts.app';
    }else if(Session::get('currentPortal') == 16){
        $extend = 'chairpersonportal.layouts.app2';
    }else if(auth()->user()->type == 16){
        $extend = 'chairpersonportal.layouts.app2';
    }else{
        if(isset($check_refid->refid)){
			
			if($check_refid->resourcepath == null){
                $extend = 'general.defaultportal.layouts.app';
			}else if($check_refid->refid == 27){
                $extend = 'academiccoor.layouts.app2';
            }else if($check_refid->refid == 22){
                $extend = 'principalcoor.layouts.app2';
            }else if($check_refid->refid == 29){
                $extend = 'idmanagement.layouts.app2';
            }else if($check_refid->refid ==  23){
				$extend = 'clinic.index';
			}elseif($check_refid->refid ==  24){
				$extend = 'clinic_nurse.index';
			}elseif($check_refid->refid ==  25){
				$extend = 'clinic_doctor.index';
			}elseif($check_refid->refid ==  33){
                $extend = 'inventory.layouts.app2';
                
            }else{
                $extend = 'general.defaultportal.layouts.app';
            }
        }else{
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp


@extends($extend)

@section('pagespecificscripts')

    <style>

    .select2-container {
            z-index: 9999;
            margin: 0px;
        }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #006fe6;
        color: #fff;
        padding: 0 10px;
        margin-top: .31rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: rgba(255,255,255,.7);
        float: right;
        margin-left: 5px;
        margin-right: -2px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 18px;
    }



    </style>


@endsection

@section('modalSection')


<div class="modal fade show" id="add_product" style="padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body text-sm">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Code</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="item_code" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Description</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm item_val" id="item_description" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Amount</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control form-control-sm text-right" id="item_cost" placeholder="0.00" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Classification</label>
                                <div class="col-sm-9">
                                    <select id="item_class" class="select2" style="width: 100%">
                                        <option value="0">SELECT CLASSIFICATION</option>
                                        @foreach(db::table('itemclassification')->where('deleted', 0)->orderBy('description')->get() as $class)
                                        <option value="{{$class->id}}">{{$class->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Account</label>
                                <div class="col-sm-9">
                                    <select id="item_account" class="select2" style="width: 100%">
                                        <option value="0">SELECT ACCOUNT</option>
                                        @foreach(db::table('acc_coa')->where('deleted', 0)->orderBy('code')->get() as $coa)
                                        <option value="{{$coa->id}}">{{$coa->code}} - {{$coa->account}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-3 col-form-label">Item Type</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-sm-6 mt-2">
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="inventory" name="inventory" checked>
                                                <label for="inventory">Inventory
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" id="qtyContainer">
                                <label for="class-desc" class="col-sm-3 col-form-label">Quantity</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control form-control-sm" id="item_quantity" placeholder="0" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                </div>
                                <label for="class-desc" class="col-sm-3 col-form-label">Min Qty</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control form-control-sm" id="item_min_quantity" placeholder="0" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button id="item_delete" type="button" class="btn btn-danger" style="display: none">Delete</button>
                <button id="item_save" type="button" class="btn btn-primary" data-id="0">Save</button>
            </div>
        </div>

    </div>

</div>

<div class="modal fade show" id="assign_product" style="padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header  bg-primary">
                <h4 class="modal-title departmentTitle">Assign Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body text-sm">
                    <div class="card-body">
                        <div class="form-group row">
                            <input type="hidden" id="assignProductId">
                            <label for="class-desc" class="col-sm-3 col-form-label">Deparment</label>
                            <div class="col-sm-9">
                                <select id="assigned_department" class="select2" style="width: 100%">
                                    @foreach(db::table('hr_departments')->where('deleted', 0)->orderBy('department')->get() as $class)
                                    <option value="{{$class->id}}">{{$class->department}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <div class="row justify-content-end">
                                    <div class="col-sm-5 text-right">
                                        <button class="btn btn-primary pr-0 bg-white" data-toggle="modal" data-target="#add_department_modal" style="border: none;"> <i class="fa fa-plus" aria-hidden="true"></i> Add Department </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" id="qtyContainer">
                            <label for="class-desc" class="col-sm-3 col-form-label">Quantity</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control form-control-sm" id="assign_item_quantity" placeholder="0" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                <span class="text-danger"><span id="qtySpan">0</span> stock left</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="class-desc" class="col-sm-3 col-form-label">Remarks</label>
                            <div class="col-sm-9">
                                <textarea rows="3" id="remarks" style="width: 100%; box-sizing: border-box;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button id="asign_save" type="button" class="btn btn-primary" data-id="0">Save</button>
            </div>
        </div>

    </div>

</div>


<div class="modal fade show" id="add_department_modal" style="padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Add Department</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            <form action="/inventory/addDepartment" id="departmentForm" method="POST">
                @csrf
                <div class="modal-body text-sm">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="class-desc" class="col-sm-3 col-form-label">Department</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="item_code" name="department" placeholder="Enter Department Name Here.." onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                <button type="submit" class="btn btn-primary" id="saveDepartment">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection



@section('content')


<!-- DataTables -->
<div class="row m-2">
    <div class="col-md-12">
        <div class="card rounded shadow-none mt-2">
            <div class="card-header bg-primary">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center w-100">
                    <h1 class="flex-sm-fill h3 my-2 font-w400">
                        Inventory
                    </h1>
                </div>
            </div>
            <div class="card-body p-3 w-100">
                <div class="row align-items-center mt-2">
                    <div class="col-sm-12 pt-2 d-flex flex-column flex-md-row justify-content-end">
                            <button class="btn btn-sm btn-primary align-middle mr-1" type="button" data-toggle="modal" id="item_create" data-target="#add_product">
                            + New Item
                            </button>
                    </div>
                </div>
                <div class="mt-2">
                    <table class="table table-striped font-size-sm" id="productDatable" style="width: 100%">
                        <thead >
                            <tr >
                                <th width="1%" class="text-center"></th>
                                <th width="29%" >Item Code</th>
                                <th width="45%" >Item Name</th>
                                <th width="15%" class="text-center">In Stock</th>
                                <th width="10%"> </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection


@section('footerjavascript')


<script>

    function getItems(id) {
        return $.ajax({
            type: 'GET',
            data:{id:id},
            url: '/inventory/getItems'
        })
    }


    function renderItems(id) {

        getItems(id).done(function (data) {
            var table = $('#productDatable').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 15, 20],
                autoWidth: true,
                responsive: true,
                destroy: true,
                stateSave: true,
                data: data,
                columns: [
                    { data: null, orderable: false , render: function(data, type, row, meta) { return meta.row + 1; } }, // Index column
                    { data: 'itemcode'},
                    { data: 'description'},
                    { data: 'qty' , className: 'text-center', orderable: false  },
                    { data: null},

                ],
                columnDefs: [
                {
                    targets: -1, // Last column index
                    orderable: false, // Disable sorting on this column
                    render: function(data, type, row) {
                            return '<div class="btn-group">' +
                                '<button type="button" id="items_list" class="btn btn-sm btn-light"  data-toggle="modal" data-target="#add_stocks" title="stock" data-id="' + row.id + '">' +
                                '<i class="fas fa-pencil-alt"></i>' +
                                '</button>' +
                                '<button type="button" class="btn btn-sm btn-light" data-toggle="modal" data-target="#assign_product" title="Assign to Department" id="assignProduct" data-id="' + row.id + '">' +
                                '<i class="fas fa-exchange-alt"></i>' +
                                '</button>' +
                                '</div>';

                    }
                }
                ]
            });

            table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                var rowData = this.data();
                console.log(rowData);
                if (rowData.qty < rowData.minimum_qty) {
                    $(this.node()).addClass('text-warning'); // Apply 'bg-danger' class to the row
                }
                if(rowData.qty == 0){
                    $(this.node()).addClass('text-danger');
                }
            });
        });
    }


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

    $(document).ready(function(){

        $('#Date').daterangepicker();

        $('.select2').select2({
            theme: 'bootstrap4'
        });


        $('#item_class').select2();
        $('#item_account').select2();
        $('#assigned_department').select2();


        renderItems();

    });

    $(document).on('click','#saveDepartment',function(e){
                
                $('#saveDepartment').prop('disabled', true);
                e.preventDefault();

                var formData = new FormData($('#departmentForm')[0]); // Create FormData object

                $.ajax({
                    url: $('#departmentForm').attr('action'), // URL from the form action attribute
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        
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

                        console.log(response);

                        $('#assigned_department').prepend('<option value="'+response.department.id+'">'+response.department.department+'</option>')
                        $('#assigned_department').val(response.department.id).trigger('change')
                        Toast.fire({
                            type: 'success',
                            title: 'Department saved'
                        })
                        $('#saveDepartment').prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);

                        Toast.fire({
                            type: 'success',
                            title:  xhr.responseJSON.message
                        })
                        
                    }
                });

    });

    $(document).on('click', '#item_create', function(){
            $('#item_code').val('')
            $('#item_description').val('')
            $('#item_cost').val('')
            $('#item_class').val(0).trigger('change')
            $('#item_account').val(0).trigger('change')
            $('#item_quantity').val('')
            $('#item_quantity').val('')
            $('#item_save').attr('data-id', 0)
    })

    $(document).on('click', '#item_save', function(){
            
            $('#item_save').prop('disabled', true);
            var code = $('#item_code').val()
            var description = $('#item_description').val()
            var cost = $('#item_cost').val()
            var classid = $('#item_class').val()
            var coa = $('#item_account').val()
            var qty = $('#item_quantity').val()
            var min_qty = $('#item_min_quantity').val()
            var dataid = $(this).attr('data-id')
            var itemType = 0;

            if($('#inventory').is(':checked')){
                itemType = 1;
            }

            $.ajax({
                type: "GET",
                url: "{{route('expenses_items_create')}}",
                data: {
                    code:code,
                    description:description,
                    cost:cost,
                    classid:classid,
                    coa:coa,
                    qty:qty,
                    min_qty:min_qty,
                    itemType:itemType,
                    dataid:dataid
                },
                // dataType: "dataType",
                success: function (data) {

                    $('#item_save').prop('disabled', false);

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
                            title: 'Item saved'
                        })

                        getItems()
                        $('#modal-itemdetail').modal('hide')
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
                            title: 'Item already exist'
                        })
                    }
                },
                error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr);
                        $('#item_save').prop('disabled', false);
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
                            title: 'Something went wrong!'
                        })

                    }
            });
        })

    $(document).on('click', '#assignProduct', function(){
            
            var dataid = $(this).data('id')
            $.ajax({
                type: "GET",
                url: "{{route('expenses_items_read')}}",
                data: {
                    dataid:dataid
                },
                // dataType: "dataType",
                success: function (data) {
                    console.log(data);
                    $('.departmentTitle').text(data.description)
                    $('#assignProductId').val(dataid)
                    $('#qtySpan').text(data.qty);
                }
            });
    });

    $(document).on('input', '#assign_item_quantity', function(){
            
            var max = parseInt($('#qtySpan').text());
            var qty = $(this).val();

            if(max < qty){
                $(this).addClass("is-invalid");
                $('#asign_save').prop('disabled', true);
            }else{
                $(this).removeClass("is-invalid");
                $('#asign_save').prop('disabled', false);
            }



    });

    $(document).on('click', '#items_list', function(){
            var dataid = $(this).data('id');
            $.ajax({
                type: "GET",
                url: "{{route('expenses_items_read')}}",
                data: {
                    dataid:dataid
                },
                // dataType: "dataType",
                success: function (data) {
                    $('#item_code').val(data.code)
                    $('#item_description').val(data.description)
                    $('#item_cost').val(data.cost)
                    $('#item_class').val(data.classid).trigger('change')
                    $('#item_account').val(data.glid).trigger('change')
                    $('#item_quantity').val(data.qty);
                    $('#item_min_quantity').val(data.minimum_qty);
                    if(data.itemtype == 'non_inventotry'){
                        $('#non_inventory').prop('checked', true);
                        $('#qtyContainer').hide();
                    }else{
                        $('#inventory').prop('checked', true);
                        $('#qtyContainer').show();
                    }
                    $('#item_save').attr('data-id', dataid)
                    $('#item_delete').show();

                    $('#add_product').modal('show');
                }
            });
    });

    $(document).on('click', '#asign_save', function(){
            
            $('#asign_save').prop('disabled', true);
            var id = $('#assignProductId').val();
            var department = $('#assigned_department').val();
            var qty = $('#assign_item_quantity').val();
            var remark = $('#remarks').val();


            $.ajax({
                url: '/inventory/saveAssigned', // Replace with your actual delete endpoint
                method: 'POST',
                data: {
                    id: id,
                    department: department,
                    qty: qty,
                    remark: remark,
                    _token: "{{ csrf_token() }}" // Include CSRF token from Laravel
                },
                success: function(response, xhr) {
                    // Handle success response
                        $('#asign_save').prop('disabled', false);

                        Toast.fire({
                            type: 'success',
                            title: 'Assigning Succesful'
                        })

                        renderItems();
                        $('#assign_product').modal('hide')
                },
                error: function(xhr, status, error) {
                    // Handle error
                    $('#asign_save').prop('disabled', false);
                    console.log(xhr);
                    Toast.fire({
                        type: 'success',
                        title: 'Something went Wrong'
                    })
                }
            });
    });


</script>
@endsection