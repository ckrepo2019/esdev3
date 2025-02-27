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
          <h1 class="m-0 text-dark">Detailed Fees Collected</h1>
        </div>
        <div class="row form-group">
            <div class="col-md-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control float-right" id="fc_date">
                  </div>
            </div>
            <div class="col-4">
                {{-- <label>ITEM</label> --}}
                <select id="fc_item" class="select2" style="width: 100%;">
                    <option value="0">SELECT ITEM</option>
                    @foreach(db::table('items')->where('deleted', 0)->orderBy('description')->get() as $item)        
                        <option value="{{$item->id}}">{{$item->description}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
            {{-- <label>&nbsp;</label><br> --}}
                <button id="fc_generate" class="btn btn-primary">GENERATE</button>
                <button id="fc_print" class="btn btn-info">PRINT</button>
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
                                    <th>DATE</th>
                                    <th>RECEIPT NO</th>
                                    <th>NAME</th>
									                  <th>FEE</th>
                                    <th>AMOUNT</th>
                                  </tr>
                                </thead> 
                                <tbody id="fc_list" style="cursor: pointer;"></tbody>             
                            </table>
                          <div id="#demo"></div>
                        </div>
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
          <h4 class="modal-title">Payment Items - New</h4>
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
                    <h4 class="modal-title">Items <span id="item_action"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="item_code" class="col-sm-2 col-form-label">Item Code</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control validation" id="item_code" placeholder="Item Code" onkeyup="this.value = this.value.toUpperCase();">
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
                        <label for="item_desc" class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control validation" id="item_desc" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
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
                          <input type="number" class="form-control validation" id="item_amount" onkeyup="this.value = this.value.toUpperCase();">
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
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        $('#fc_date').daterangepicker()

        screenadjust();

        function screenadjust()
        {
            var screen_height = $(window).height();

            $('#main_table').css('height', screen_height - 300);
            // $('.screen-adj').css('height', screen_height - 223);
        }


        function generate(action='')
        {
            var dates = $('#fc_date').val();
            var itemid = $('#fc_item').val();

            $.ajax({
                url: '{{route('fc_generate')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    dates:dates,
                    itemid:itemid,
                    action:action
                },
                success:function(data)
                {
                    if(action == 'generate')
                    {
                        $('#fc_list').html(data.list);
                    }
                }
            });
            
        }

        $(document).on('click', '#fc_generate', function(){
            generate('generate');
        })

        $(document).on('click', '#fc_print', function(){
          	if($('#fc_item').val() != 0)
		  	{
				var dates = $('#fc_date').val();
				var itemid = $('#fc_item').val();
				var action = 'print';

				window.open('/finance/fc_generate?dates=' + dates + '&itemid=' + itemid + '&action='+ action,'_blank');
			}
			else{
				const Toast = Swal.mixin({
					toast: true,
					position: "top-end",
					showConfirmButton: false,
					timer: 3000,
					timerProgressBar: true,
					didOpen: (toast) => {
						toast.onmouseenter = Swal.stopTimer;
						toast.onmouseleave = Swal.resumeTimer;
					}
				});
				Toast.fire({
					type: "error",
					title: "Please select an item"
				});
			}
        });

    });

  </script>
  
@endsection