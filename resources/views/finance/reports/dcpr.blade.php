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
          <h1 class="m-0 text-dark">Daily Cash Progress Report</h1>
        </div>
        <div class="row form-group">
            <div class="col-md-3 dcpr_divdate" style="display: block;">
                <input type="date" id="dcpr_date" class="form-control">
            </div>
            <div class="col-md-3 dcpr_divor" style="display: none;">
                <div class="row">
                    <div class="col-md-6">
                        <input type="number" id="dcpr_filterorfrom" class="form-control" placeholder="OR From">
                    </div>
                    <div class="col-md-6">
                        <input type="number" id="dcpr_filterorto" class="form-control" placeholder="OR To">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group clearfix mt-2">
                    <div class="icheck-primary d-inline">
                        
                        <input type="radio" id="dcpr_filterdate" name="r1" checked="">
                        <label for="dcpr_filterdate">
                            Date
                        </label>
                    </div>
                    <div class="icheck-primary d-inline ">
                        <input type="radio" id="dcpr_filteror" name="r1">
                        <label for="dcpr_filteror">
                            OR Range
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <button id="dcpr_generate" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Generate</button>
                <button id="dcpr_export" class="btn btn-warning"><i class="fas fa-download"></i> Export</button>
            </div>
        </div>
		<div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        
                    </div>
                    <div class="card-body">
                        <div id="main_table" class="table-responsive p-0">
                            <table class="table table-striped table-sm text-sm" style="table-layout: fixed;">
                                <thead class="bg-gray-dark" id="dcpr_header">
                                    
                                </thead>
                                <tbody id="dcpr_list">
                                </tbody>
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

    <div class="modal fade" id="modal-overlay" data-backdrop="static" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content bg-gray-dark" style="opacity: 78%; margin-top: 15em">
                <div class="modal-body" style="height: 250px">
                    <div class="row">
                        <div class="col-md-12 text-center text-lg text-bold b-close">
                            Please Wait
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="loader"></div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: -30px">
                        <div class="col-md-12 text-center text-lg text-bold">
                            Processing...
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

@endsection

@section('js')
    
    <style>
        .loader{
            width: 100px;
            height: 100px;
            margin: 50px auto;
            position: relative;
        }
        .loader:before,
        .loader:after{
            content: "";
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: solid 8px transparent;
            position: absolute;
            -webkit-animation: loading-1 1.4s ease infinite;
            animation: loading-1 1.4s ease infinite;
        }
        .loader:before{
            border-top-color: #d72638;
            border-bottom-color: #07a7af;
        }
        .loader:after{
            border-left-color: #ffc914;
            border-right-color: #66dd71;
            -webkit-animation-delay: 0.7s;
            animation-delay: 0.7s;
        }
        @-webkit-keyframes loading-1{
            0%{
                -webkit-transform: rotate(0deg) scale(1);
                transform: rotate(0deg) scale(1);
            }
            50%{
                -webkit-transform: rotate(180deg) scale(0.5);
                transform: rotate(180deg) scale(0.5);
            }
            100%{
                -webkit-transform: rotate(360deg) scale(1);
                transform: rotate(360deg) scale(1);
            }
        }
        @keyframes loading-1{
            0%{
                -webkit-transform: rotate(0deg) scale(1);
                transform: rotate(0deg) scale(1);
            }
            50%{
                -webkit-transform: rotate(180deg) scale(0.5);
                transform: rotate(180deg) scale(0.5);
            }
            100%{
                -webkit-transform: rotate(360deg) scale(1);
                transform: rotate(360deg) scale(1);
            }
        }
    </style>
  
  <script type="text/javascript">
    
    $(document).ready(function(){
        var searchVal = $('#txtsearchitem').val();
        // searchitems();

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

        $(document).on('click', '#dcpr_generate', function(){
            var date = $('#dcpr_date').val();
            var orfrom = $('#dcpr_filterorfrom').val();
            var orto = $('#dcpr_filterorto').val();

            if($('#dcpr_filterdate').prop('checked') == true)
            {
                var type = 'date';
            }
            else
            {
                var type = 'or';
            }
			
			if(date != '')
			{
				$('#modal-overlay').modal('show');
				$.ajax({
					url: '{{route('dcpr_generate')}}',
					type: 'GET',
					dataType: 'json',
					data: {
						date:date,
						orfrom:orfrom,
						orto:orto,
						type:type
					},
					success:function(data)
					{
						$('#dcpr_header').html(data.headerlist);
						$('#dcpr_list').html(data.bodylist);

						setTimeout(function(){
							$('#modal-overlay').modal('hide');
						}, 1000)
					}
				});    
			}
			else
			{
				const Toast = Swal.mixin({
				  toast: true,
				  position: 'top',
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
				  title: 'Please select a date'
				})
			}
        });

        $(document).on('click', '#dcpr_export', function(){
            var dcpr_date = $('#dcpr_date').val();
            var date = $('#dcpr_date').val();
            var orfrom = $('#dcpr_filterorfrom').val();
            var orto = $('#dcpr_filterorto').val();

            if($('#dcpr_filterdate').prop('checked') == true)
            {
                var type = 'date';
            }
            else
            {
                var type = 'or';
            }
			
			if(date != '')
			{
				window.open("/finance/reports/dcpr_export?dcpr_date="+dcpr_date+"&orfrom="+orfrom+"&orto="+orto+"&type="+type);
			}
			else
			{
				const Toast = Swal.mixin({
				  toast: true,
				  position: 'top',
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
				  title: 'Please select a date'
				})
			}

            
        });

        $(document).on('click', '#dcpr_filterdate', function(){
            $('.dcpr_divdate').show();
            $('.dcpr_divor').hide();
        });

        $(document).on('click', '#dcpr_filteror', function(){
            $('.dcpr_divdate').hide();
            $('.dcpr_divor').show();
        });

    });

  </script>
  
@endsection