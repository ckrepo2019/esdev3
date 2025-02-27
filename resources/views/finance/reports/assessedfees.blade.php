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
          <h1 class="m-0 text-dark">Detailed Assessed Fees</h1>
        </div>
        <div class="row form-group">
            <div class="col-md-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control float-right" id="af_date">
                  </div>
            </div>
            <div class="col-md-3">
                <select id="af_acadprog" class="select2" style="width: 100%;">
                    @foreach(db::table('academicprogram')->get() as $acadprog)
                        <option value="{{$acadprog->id}}">{{$acadprog->acadprogcode}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-3">
                {{-- <label>ITEM</label> --}}
                <select id="af_item" class="select2" style="width: 100%;">
                    @php
                        $items = db::table('items')
                            ->select('items.id', 'items.description')
                            ->join('tuitionitems', 'items.id', '=', 'tuitionitems.itemid')
                            ->where('tuitionitems.deleted', 0)
                            ->where('items.deleted', 0)
                            ->groupBy('items.id')
                            ->orderBy('items.description')
                            ->get();
                    @endphp
                    <option value="0">SELECT ITEM</option>
                    @foreach($items as $item)        
                        <option value="{{$item->id}}">{{$item->description}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-3">
            {{-- <label>&nbsp;</label><br> --}}
                <button id="af_generate" class="btn btn-primary">GENERATE</button>
                <button id="af_print" class="btn btn-info">PRINT</button>
            </div>

        </div>
		<div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        
                    </div>
                    <div class="card-body">
                        <div id="main_table" class="table-responsive p-0">
                            <table class="table  table-head-fixed table-sm text-sm">
                                <thead class="bg-warning p-0">
                                  <tr>
                                    <th>DATE</th>
                                    <th>NAME</th>
									<th>FEE</th>
                                    <th>AMOUNT</th>
                                  </tr>
                                </thead> 
                                <tbody id="af_list"></tbody>             
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
                            <div class="col-md-3">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="expense-new">
                                    <label for="expense-new">
                                      Expense
                                    </label>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="saveItem" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
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

        $('#af_date').daterangepicker()

        screenadjust();

        function screenadjust()
        {
            var screen_height = $(window).height();

            $('#main_table').css('height', screen_height - 300);
            // $('.screen-adj').css('height', screen_height - 223);
        }


        function generate(action='')
        {
            var dates = $('#af_date').val();
            var itemid = $('#af_item').val();
            var acadprog = $('#af_acadprog').val();

            $.ajax({
                url: '{{route('af_generate')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    dates:dates,
                    itemid:itemid,
                    action:action,
                    acadprog:acadprog
                },
                success:function(data)
                {
                    if(action == 'generate')
                    {
                        $('#af_list').html(data.list);
                    }
                }
            });
            
        }

        $(document).on('click', '#af_generate', function(){
            generate('generate');
        })

        $(document).on('click', '#af_print', function(){
            var dates = $('#af_date').val();
            var itemid = $('#af_item').val();
            var action = 'print';
            var acadprog = $('#af_acadprog').val();

            if(itemid > 0)
            {
                window.open('/finance/af_generate?dates=' + dates + '&itemid=' + itemid + '&action='+ action + '&acadprog='+acadprog,'_blank');
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
                  type: 'warning',
                  title: 'No item selected.'
                })
            }
        });

    });

  </script>
  
@endsection