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
            <li class="breadcrumb-item active">Books</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section> --}}
  <section class="content">
  			<!-- Payment Items -->
        <div class="row mb-2 ml-2">
            <h1 class="m-0 text-dark">Books</h1>
        </div>
        <div class="row">
            <div class="col-8">
            </div>
            <div class="col-4">
                <div class="input-group mb-3">
                    <input id="txtsearchitem" type="text" class="form-control" placeholder="Search Item" onkeyup="this.value = this.value.toUpperCase();">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-primary" id="item_create">Create</button>
                    </div>
                </div>
            </div>

        </div>
		<div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        
                    </div>
                    <div class="card-body">
                        <div id="main_table" class="table-responsive p-0">
                            <table class="table table-hover table-head-fixed table-sm text-sm">
                                <thead class="bg-warning p-0">
                                  <tr>
                                    <th>CODE</th>
                                    <th>TITLE</th>
                                    <th>PAYMENT GROUP</th>
                                    <th class="text-center">AMOUNT</th>
                                  </tr>
                                </thead> 
                                <tbody id="item-list" style="cursor: pointer;"></tbody>             
                            </table>
                          <div id="#demo"></div>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
  </section>
@endsection

@section('modal')
  <div class="modal fade show" id="modal-item-new" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="class-desc" class="col-sm-2 col-form-label">Item1 Code</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control validation" id="item-code" placeholder="Item Code" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-desc" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control validation" id="item-desc" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-glid" class="col-sm-2 col-form-label">Classification</label>
                <div class="col-sm-10">
                  <select class="form-control select2bs4" id=item-class>
                    <option></option>
                  </select>
                </div>
              </div>


              <div class="form-group row">
                <label for="class-desc" class="col-sm-2 col-form-label">Amount</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control validation" id="item-amount" placeholder="0.00">
                </div>
              </div>

              <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-3">
                  <div class="icheck-primary d-inline">
                    <input type="checkbox" id="isreceivable-new">
                    <label for="isreceivable-new">
                      Receivable
                    </label>
                  </div>
                </div>
                {{-- <div class="col-md-4">
                  <div class="icheck-primary d-inline">
                    <input type="checkbox" id="dp-new">
                    <label for="dp-new">
                      Downpayment
                    </label>
                  </div>
                </div> --}}
                <div class="col-md-3">
                  <div class="icheck-primary d-inline">
                    <input type="checkbox" id="expense-new">
                    <label for="expense-new">
                      Expense
                    </label>
                  </div>
                </div>
              </div>

              
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="saveItem" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

    <div class="modal fade show" id="modal-item-edit" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                  <h4 class="modal-title">Payment Items - Edit</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-2 col-form-label">Item Code</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control validation" id="item-code-edit" placeholder="Item Code" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control validation" id="item-desc-edit" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-2 col-form-label">Classification</label>
                                <div class="col-sm-10">
                                  <select class="form-control" id="item-class-edit">
                                    <option></option>
                                  </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-2 col-form-label">Amount</label>
                                <div class="col-sm-10">
                                  <input type="number" class="form-control validation" id="item-amount-edit" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-3">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="isreceivable-edit">
                                        <label for="isreceivable-edit">
                                            Receivable
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="col-md-3">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="expense-edit">
                                            <label for="expense-edit">
                                                Expense
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="updateItem" type="button" class="btn btn-primary" data-dismiss="modal" data-id="">Save</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-items_detail" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-sm" style="height: 38em; margin-top: 4em;">
                <div id="modalhead" class="modal-header bg-info">
                    <h4 class="modal-title">Books <span id="item_action"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="item_code" class="col-sm-2 col-form-label">Code</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control validation" id="item_code" placeholder="Code" onkeyup="this.value = this.value.toUpperCase();">
                        </div>
                        <div class="col-sm-5">
                          <select id="item_classcode" class="select2" style="width:100%">
                            <option value="0"></option>
                            @foreach(db::table('items_classcode')->get() as $itemclass)
                              <option value="{{$itemclass->id}}">{{$itemclass->description}}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="item_desc" class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control validation" id="item_desc" placeholder="Book Title" onkeyup="this.value = this.value.toUpperCase();">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="item_classid" class="col-sm-2 col-form-label">Classification</label>
                        <div class="col-sm-10">
                            <select class="select2 " id="item_classid" style="width: 100%;">
                                <option value="0"></option>
                                @foreach(db::table('itemclassification')->where('deleted', 0)->orderBy('description')->get() as $class)
                                    <option value="{{$class->id}}">{{$class->description}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="item_amount" class="col-sm-2 col-form-label">Amount</label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control validation" id="item_amount">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-3">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="item_cash">
                                <label for="item_cash">
                                    Cash
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="item_receivable" >
                                <label for="item_receivable">
                                    Receivable
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="item_expense" >
                                <label for="item_expense">
                                    Expense
                                </label>
                            </div>
                        </div>
                        {{-- </div> --}}
                    </div>

                    <hr>
                    <div class="form-group row">
                        <label for="item_glid" class="col-sm-2 col-form-label">GL Account</label>
                        <div class="col-sm-10">
                            <select id="item_glid" class="select2" style="width: 100%;">
                                <option value="0"></option>
                                @foreach(db::table('acc_coa')->where('deleted', 0)->orderBy('code')->get() as $coa)
                                    <option value="{{$coa->id}}">{{$coa->code . ' - ' . $coa->account}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="item_save" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>


  

  
@endsection

@section('js')
  
  <script type="text/javascript">
    
    $(document).ready(function(){
        var searchVal = $('#txtsearchitem').val();
        searchitems();

        $('.select2').select2({
            theme: 'bootstrap4'
        });

        screenadjust();

        function screenadjust()
        {
            var screen_height = $(window).height();

            $('#main_table').css('height', screen_height - 300);
            // $('.screen-adj').css('height', screen_height - 223);
        }

        function searchitems(query='')
        {
            var query = $('#txtsearchitem').val();
            
            $.ajax({
              url:"{{route('book_search')}}",
              method:'GET',
              data:{
                query:query
              },
              dataType:'json',
              success:function(data)
              {
                $('#item-list').html(data.output);
                // console.log(data.items);

                


              }
            });
        }

        $(document).on('keyup', '#txtsearchitem', function(){
            searchitems()
        })

        $(document).on('click', '#saveItem', function(){

            var itemcode = $('#item-code').val();
            var itemdesc = $('#item-desc').val();
            var classid = $('#item-class').val();
            var slid = 0;
            var amount = $('#item-amount').val();

            if($('#dp-new').prop('checked') == true)
            {
              var isdp = 1;
            }
            else
            {
              var isdp = 0;
            }

            if($('#isreceivable-new').prop('checked') == true)
            {
              var isreceivable = 1;
            }
            else
            {
              var isreceivable = 0;
            }

            if($('#expense-new').prop('checked') == true)
            {
              var isexpense = 1;
            }
            else
            {
              var isexpense = 0; 
            }

            $.ajax({
              url:"{{route('saveItem')}}",
              method:'GET',
              data:{
                itemcode:itemcode,
                itemdesc:itemdesc,
                classid:classid,
                slid:slid,
                amount:amount,
                isdp:isdp,
                isreceivable:isreceivable,
                isexpense:isexpense
              },
              dataType:'',
              success:function(data)
              {
                const Toast = Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                onOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'success',
                  title: 'Item successfully saved.'
                })

                searchitems($('#txtsearchitem').val());

              }
            });
        });

        $(document).on('click', '#btnitem-edit', function(){
        
            var itemid = $(this).attr('data-id');
            $('#updateItem').attr('data-id', itemid);

            $.ajax({
              url:"{{route('loadEDIT')}}",
              method:'GET',
              data:{
                itemid:itemid
              },
              dataType:'json',
              success:function(data)
              {
                $('#item-code-edit').val(data.itemcode);
                $('#item-desc-edit').val(data.itemdesc);
                $('#item-class-edit').html(data.classification);
                $('#item-amount-edit').val(data.amount);

                if(data.isdp == 1)
                {
                  $('#dp-edit').prop('checked', true);
                }
                else
                {
                  $('#dp-edit').prop('checked', false);
                }

                if(data.isreceivable == 1)
                {
                  $('#isreceivable-edit').prop('checked', true)
                }
                else
                {
                  $('#isreceivable-edit').prop('checked', false)
                }

                if(data.isexpense == 1)
                {
                  $('#expense-edit').prop('checked', true)
                }
                else
                {
                  $('#expense-edit').prop('checked', false)
                }
              }
            });
        });

        $(document).on('click', '#updateItem', function(){
            var itemid = $('#updateItem').attr('data-id');
            var itemcode = $('#item-code-edit').val();
            var itemdesc = $('#item-desc-edit').val();
            var classid = $('#item-class-edit').val();
            var amount = $('#item-amount-edit').val();

            if($('#dp-edit').prop('checked') == true)
            {
              var isdp = 1;
            }
            else
            {
              var isdp = 0;
            }

            if($('#isreceivable-edit').prop('checked') == true)
            {
              var isreceivable = 1;
            }
            else
            {
              var isreceivable = 0;
            }

            console.log(itemid + ' ' + itemcode + ' ' + itemdesc + ' ' + classid);


            $.ajax({
              url:"{{route('updateItem')}}",
              method:'GET',
              data:{
                itemid:itemid, 
                itemcode:itemcode,
                itemdesc:itemdesc,
                classid:classid,
                amount:amount,
                isdp:isdp,
                isreceivable:isreceivable
              },
              dataType:'',
              success:function(data)
              {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                  type: 'success',
                  title: 'Item successfully saved.'
                })

                searchitems($('#txtsearchitem').val());
              }
            });

        });

        $(document).on('click', '#btnitem-delete', function(){
            var itemid = $(this).attr('data-id');

            Swal.fire({
              title: 'Delete selected Item?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.value) {

                $.ajax({
                  url:"{{route('deleteItem')}}",
                  method:'GET',
                  data:{
                    itemid:itemid
                  },
                  dataType:'',
                  success:function(data)
                  {
                    Swal.fire(
                      'Deleted!',
                      'Item has been deleted.',
                      'success'
                    );

                    searchitems($('#txtsearchitem').val());
                  }
                }); 
              }
            });
        });

        $(document).on('click', '#item-list tr', function(){
            var dataid = $(this).attr('data-id');

            $.ajax({
                url: '{{route('item_edit')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    dataid:dataid
                },
                success:function(data)
                {
                    $('#item_code').val(data.code);
                    $('#item_classcode').val(data.classcode);
                    $('#item_classcode').trigger('change');
                    $('#item_desc').val(data.description);
                    $('#item_classid').val(data.classid);
                    $('#item_classid').trigger('change');
                    $('#item_amount').val(data.amount);
                    $('#item_glid').val(data.glid);
                    $('#item_glid').trigger('change');
                    $('#item_save').attr('data-id', data.id);

                    $('#modal-items_detail').modal('show');

                    if(data.cash == 1)
                    {
                        $('#item_cash').prop('checked', true);
                    }
                    else
                    {
                        $('#item_cash').prop('checked', false);
                    }

                    if(data.receivable == 1)
                    {
                        $('#item_receivable').prop('checked', true);
                    }
                    else
                    {
                        $('#item_receivable').prop('checked', false);
                    }

                    if(data.expense == 1)
                    {
                        $('#item_expense').prop('checked', true);
                    }
                    else
                    {
                        $('#item_expense').prop('checked', false);
                    }
                }
            });
        });

        $(document).on('click', '#item_save', function(){
            var code = $('#item_code').val();
            var classcode = $('#item_classcode').val();
            var description = $('#item_desc').val();
            var classid = $('#item_classid').val();
            var amount = $('#item_amount').val();
            var glid = $('#item_glid').val();
            var dataid = $(this).attr('data-id');

            if($('#item_cash').prop('checked') == true)
            {
                var cash = 1;
            }
            else
            {
                var cash = 0;
            }

            if($('#item_receivable').prop('checked') == true)
            {
                var receivable = 1;
            }
            else
            {
                var receivable = 0;
            }

            if($('#item_expense').prop('checked') == true)
            {
                var expense = 1;
            }
            else
            {
                var expense = 0;
            }


            $.ajax({
                url: '{{route('book_append')}}',
                type: 'GET',
                data: {
                    dataid:dataid,
                    code:code,
                    classcode:classcode,
                    description:description,
                    classid:classid,
                    amount:amount,
                    glid:glid,
                    cash:cash,
                    receivable:receivable,
                    expense:expense
                },
                success:function(data)
                {
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
                          title: 'Save successfully'
                        })

                        searchitems();
                        $('#modal-items_detail').modal('hide');
                    }
                    else
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
                          error: 'success',
                          title: 'Item already exist'
                        })
                    }
                }
            });
        });

        function clearinputs()
        {
            $('#item_code').val('');
            $('#item_classcode').val(0);
            $('#item_classcode').trigger('change');
            $('#description').val('');
            $('#item_classid').val(0);
            $('#item_classid').trigger('change');
            $('#item_amount').val('');
            $('#item_glid').val(0);
            $('#item_glid').trigger('change');

            $('#item_cash').prop('checked', false);
            $('#item_receivable').prop('checked', false);
            $('#item_expense').prop('checked', false);
			$('#item_save').attr('data-id', 0);
        }

        $(document).on('click', '#item_create', function(){
            clearinputs();
            $('#modal-items_detail').modal('show');
        });

    });

  </script>
  
@endsection