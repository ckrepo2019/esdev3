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
            }elseif($check_refid->refid ==  19){
                $extend = 'finance.layouts.app';
                
            }else{
                $extend = 'general.defaultportal.layouts.app';
            }
        }else{
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp

@extends($extend)
@section('content')
  
  <section class="content">
    <div class="row">
      {{-- <h1 class="m-0 text-dark">Purchase Order</h1> --}}
      <div class="col-sm-6">
        <h1>Purchase Order</h1>
      </div>
      <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right" style="background-color: #f4f6f9;">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Purchase Order</li>
      </ol>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-primary">

          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-2 col-12">
                <div class="form-group mb-3">
                  <select name="ftype" class="form-control form-control-sm select2" id="filter_supplier">
                    <option value="0">Select Supplier</option>
                    @foreach(DB::table('expense_company')->where('deleted', 0)->orderBy('companyname')->get() as $vendor)
                        <option value="{{$vendor->id}}">{{$vendor->companyname}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-2">
              </div>
              <div class="col-md-2">
                <div class="input-group mb-3">
                  <input type="date" id="datefrom" class="form-control searchcontrol" data-toggle="tooltip" title="Date From" value="{{date('Y-m-01')}}">
                </div>
              </div>
              <div class="col-md-2">
                <div class="input-group mb-3">
                  <input type="date" id="dateto" class="form-control searchcontrol" data-toggle="tooltip" title="Date To" value="{{\Carbon\Carbon::now('Asia/Manila')->toDateString()}}">
                </div>
              </div>
              <div class="col-md-4 col-12">
                  <div class="input-group mb-3">
                      <input id="filter_search" type="text" class="form-control" placeholder="Search Reference #" onkeyup="this.value = this.value.toUpperCase();">
                      <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                      <div class="input-group-append">
                          <button class="btn btn-primary" id="btn_create_payable">Create</button>
                      </div>
                  </div>
              </div>
  
            </div>
            <div class="row">
              <div class="col-md-12 overflow-auto" id="main_table">
                  <table width="100%" class="table table-hover table-sm text-sm" id="" style="font-size: 16px;">
                      <thead>
                          <tr>
                              <th width="10%">Date</th>
                              <th width="10%">Reference #</th>
                              <th width="25%">Supplier</th>
                              <th class="text-center" width="10%">Amount</th>
                              <th class="" width="15%">Status</th>
                              <th class="" width="10%">Purchase Method</th>
                              <th class="" width="20%">Remarks</th>
                          </tr>
                      </thead>
                      <tbody id="purchase_order_datatable" style="cursor: pointer;">
                      </tbody>
                  </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('modal')
  
<!-- Modal When Create Button is click -->
<div class="modal fade show" id="modal_addpayable" style="display: none">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-body">
          <div class="row">
              <div class="col-md-7">
                <div class="row">
                  
                  <div class="col-sm-4 col-5">
                    <div class="form-group mb-3">
                      <select name="ftype" class="form-control form-control-sm select2 is-invalid req" id="select_supplier">
                        <option value="0" selected="selected">Select Supplier</option>
                        @foreach(DB::table('expense_company')->where('deleted', 0)->orderBy('companyname')->get() as $vendor)
                          <option value="{{$vendor->id}}">{{$vendor->companyname}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-2 col-3 text-right pt-1">
                    <label class="">Date</label>
                  </div>
                  <div class="col-sm-3 col-4">
                    <input type="date" id="purchasing_date" class="form-control">
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 col-12 text-right">
                    <p><span><a href="#" id="btn_additem"><u><i class="fas fa-plus"></i> Add Item</u></a></span></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 col-12 table-responsive" style="height: 12em">
                    <table width="100%" class="table table-hover table-sm text-sm" id="" style="font-size: 16px;">
                      <thead>
                          <tr>
                              <th width="60%">Particulars</th>
                              <th class="text-center" width="20%">Amount</th>
                              <th class="text-center" width="10%">QTY</th>
                              <th class="text-center" width="10%">Total</th>
                              <th></th>
                          </tr>
                      </thead>
                      <tbody id="p_itemlist">
                        
                      </tbody>
                    </table>
                  </div>
                  <div class="col-sm-12 col-12 text-right" style="position: relative;bottom: 0;right: 0;">
                    <br>
                    <h4>Total: <span id="p_grandtotal" class="text-bold" style="color: #0275d8;">0.00</span></h4>
                    <input type="text" style="color: #0275d8;" id="items_total" value="5658" hidden>
                  </div>
                </div>

                
              </div>
              <div class="col-md-5">
                <div style="">
                  {{-- <div class="row">
                    <div class="col-sm-6 col-12">
                      <h5><b>Payment Method</b></h5>
                    </div>
                    <div class="col-sm-6 col-12">
                      <div class="col-md-12">
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline mr-3">
                              <input type="checkbox" id="monthly" name="pmethod" value="1">
                              <label for="monthly">Monthly</label>
                            </div>
                            <div class="icheck-primary d-inline mr-3">
                              <input type="checkbox" id="yearly" name="pmethod" value="2">
                              <label for="yearly">Yearly</label>
                            </div>
                            <div class="retun-message mt-1">
                            </div>
                        </div>
                      </div>
                    </div>
                  </div> --}}
                  <div class="row" style="font-size: 18px;">
                    <div class="col-sm-12 col-12" id="method_monthly">
                      <div>
                        <span>Months <a href="#" id="icon_addmonth"> <i class="fas fa-plus-circle"></i></a> : <u><span id="monthscount" style="font-size: 20px;"></span></u></span>
                      </div>
                    </div>
                  </div>
                  <div class="row" style="font-size: 18px;">
                    <div class="col-sm-12 col-12" id="method_yearly">
                      <div>
                        <span>Yearly <a href="#" id="icon_addyear"> <i class="fas fa-plus-circle"></i></a> : <u><span id="yearcount" style="font-size: 20px;"><b>0</b></span></u></span>
                      </div>
                    </div>
                  </div>

                  {{-- card for payments --}}
                  <div class="row">
                    <div class="col-sm-12 col-12" style="padding-top: 5px;">
                      <label>Remarks</label>
                      <textarea id="p_remarks" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12" style="height: 6.3em">
                      &nbsp;
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group clearfix">
                        <div class="icheck-primary d-inline">
                          <input type="radio" id="p_cash" name="r1" checked="">
                          <label for="p_cash">
                            CASH
                          </label>
                        </div>
                        &nbsp;&nbsp;
                        <div class="icheck-primary d-inline">
                          <input type="radio" id="p_payable" name="r1">
                          <label for="p_payable">
                            CREDIT
                          </label>
                        </div>
                        
                      </div>
                    </div>
                    <div class="col-md-5 text-right">
                      <button id="p_printpo" class="btn btn-info btn-block">Print P.O</button>
                    </div>
                  </div>

                </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="col-md-6">
            <button class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
          </div>
          <div class="col-md-6 text-right">
            <button class="btn btn-success btn-block" id="btn_posted" style="display: none" disabled><i class="fas fa-check-circle"></i> POSTED</button>
            <button class="btn btn-danger" id="btn_delete"><i class="fas fa-trash"></i> Delete</button>
            <button class="btn btn-success" id="btn_post"><i class="fas fa-thumbtack"></i> Post</button>
            <button class="btn btn-primary" id="btn_saveitems" data-id="0"><i class="fas fa-save"></i> Save</button>
          </div>
        </div>
    </div>
  </div>
</div>

{{-- Modal when Add Item is click --}}
<div class="modal fade" id="modal-additems">
  <div class="modal-dialog modal-md">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exportmodalLabel">Add Item</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span>×</span>
              </button>
          </div>
          <div class="modal-body">

            <div class="row">
              <div class="col-sm-12 col-12">
                <div class="form-group">
                  <label for="">Item</label>
                  {{-- <input type="text" class="form-control" placeholder="Enter Item Description"> --}}
                  <select id="p_item" name="" id="" class="select2" style="width: 100%">
                    @php
                        $items = db::table('items')
                            ->where('isexpense', 1)
                            ->where('deleted', 0)
                            ->orderBy('description')
                            ->get();
                    @endphp
                    @foreach($items as $item)
                        <option value="{{$item->id}}">{{$item->description}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-sm-12 col-12">
                <div class="form-group">
                  <label for="">Amount</label>
                  <input id="p_itemamount" type="" class="form-control srctotal" placeholder="Enter Amount">
                </div>
              </div>
              <div class="col-sm-12 col-12">
                <div class="form-group">
                  <label for="">Quantity</label>
                  <input id="p_itemqty" type="number" class="form-control srctotal" placeholder="Enter Quantity">
                </div>
              </div>
              <div class="col-sm-12 col-12">
                <div class="form-group">
                  <label for="">Total</label>
                  <input id="p_itemtotal" type="" class="form-control" placeholder="Total Amount" disabled>
                </div>
              </div>
            </div>
                
          </div>
          <div class="modal-footer">
            <div class="col-md-6">
                <button class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
            </div>
            <div class="col-md-6 text-right">
                <button class="btn btn-primary" id="p_saveitem" data-id=""><i class="fas fa-plus"></i> Add</button>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection

@section('js')
  
  <script type="text/javascript">
    
    $(document).ready(function(){
      $('#select_filetype').select2()
      $('#select_supplier').select2()
      $('.check_monthly').prop('checked',false)
      $('.check_yearly').prop('checked',false)
      $('.breakdowndatacontainer').attr('hidden','hidden')
      
      $('#method_monthly').hide()
      $('#method_yearly').hide()

      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      });

    });

  </script>

  <script type="text/javascript">
    
    $(document).ready(function(){
      // decalarations variables 
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        screenadjust()

      function screenadjust()
      {
        var screen_height = $(window).height();

        $('#main_table').css('height', screen_height - 315)
        $('#setup_table').css('height', screen_height - 150)
            // $('.screen-adj').css('height', screen_height - 223);
      }

      // calling functions
      // purchase_orders()
      // items_datatable()
      
      // ------------------ events --------------------------
      // Checkbox Payment Method
      $('input[type=checkbox][name=pmethod]').change(function() {
        var itemstotal = parseInt($('#items_total').val())

        if ($(this).attr('id') == 'monthly') {
          $('#yearly').prop('checked', false);
          $("#breakdowndata").empty();
          if (this.checked) {
            var value = $(this).val();
            $('#monthscount').text(0);
            $('#method_monthly').show()
            $('#method_yearly').hide()

          }
        } else {
          $('#monthly').prop('checked', false);
          $("#breakdowndata").empty();
          if (this.checked) {
            var value = $(this).val();
            $('#method_monthly').hide()
            $('#method_yearly').show()

          }
        }
      });
      
      // add months plus icon
      var count = 0; // initialize click count variable
      $(document).on('click', '#icon_addmonth', function(){
          $('.breakdowndatacontainer').removeAttr('hidden','hidden')
          var itemstotal = parseInt($('#items_total').val())
          count++; // increment click count
          
          $('#monthscount').text(count);
          $('#yearcount').text(0);
          var tmonth = (itemstotal / count)

          var tally = $("<tr><td id='tmonth' class='p-0 list-group-item' style='border-top: none!important; border-right: none!important; border-left: none!important'><u><b>"+ tmonth +"</b></u></td></tr>");
          
          $("#breakdowndata").append(tally);
      })
      
      $(document).on('click', '#icon_addyear', function(){
          $('.breakdowndatacontainer').removeAttr('hidden','hidden')
          var itemstotal = parseInt($('#items_total').val())
          count++; // increment click count
          
          $('#yearcount').text(count);
          $('#monthscount').text(0);
          
      })
      // Add year plus icon

      // click button create
      function clearitemdetail()
      {
        $('#p_item').val(0).change()
        $('#p_itemamount').val('0.00')
        $('#p_itemqty').val(1)
        $('#p_total').val('0.00')
      }

      function clearinputs()
      {
        $('#select_supplier').val(0).change()
				$('#select_supplier').addClass('is-invalid')
				$('#select_supplier').removeClass('is-valid')
        $('#p_itemlist').empty()
        $('#p_grandtotal').text('0.00')
        $('#p_cash').prop('checked', true)
        $('#p_remarks').val('')
        $('#btn_post').hide()
        $('#btn_delete').hide()
      }

      

      $(document).on('click', '#btn_create_payable', function(){
        var currentDate = new Date().toISOString().slice(0, 10)
        viewtrans = 'create'
        clearinputs()
        postedpurchase(2)
        $('#purchasing_date').val(currentDate)
        $('#btn_saveitems').attr('data-id', 0)
        $('#modal_addpayable').modal('show')
      })
      
      // click Add Item
      $(document).on('click', '#btn_additem', function(){
        clearitemdetail()
        $('#modal-additems').modal('show')
      })

      $(document).on('change', '.srctotal', function(){
        var amount = $('#p_itemamount').val().replace(/,/g, '')
        var qty = $('#p_itemqty').val()
        var total = 0;

        total = parseFloat(amount) * parseFloat(qty)
        total = parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
        console.log(amount + ' + ' + qty + ' = ' + total)
        $('#p_itemtotal').val(total)
      })

      $('#p_itemamount').on('keyup', function(event) {
        var input = $(this).val();
        
        // Remove existing commas, non-digit characters, and extra decimal points
        var number = input.replace(/[^\d.]/g, '').replace(/\.(?=.*\.)/g, '');
        
        // Split number into integer and decimal parts
        var parts = number.split('.');
        var integerPart = parts[0];
        var decimalPart = parts[1];
        
        // Add commas for thousands separation
        var formattedIntegerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        // Concatenate integer and decimal parts
        var formattedNumber = decimalPart ? formattedIntegerPart + '.' + decimalPart : formattedIntegerPart;
        
        // Update the input value with formatted number
        $(this).val(formattedNumber);
      });

      $(document).on('click' , '#p_saveitem', function(){

        var itemdesc = $('#p_item').find('option:selected').text()
        var itemid = $('#p_item').val()
        var itemamount = $('#p_itemamount').val().replace(/,/g, '')
        var itemqty = $('#p_itemqty').val()
        var itemtotal = $('#p_itemtotal').val().replace(/,/g, '')
        
        var amount = $('#p_itemamount').val()
        var total = $('#p_itemtotal').val()
        

        $('#p_itemlist').append(`
          <tr data-id="0" item-id="`+itemid+`" item-amount="`+itemamount+`" item-qty="`+itemqty+`" item-total="`+itemtotal+`">
            <td class="">`+itemdesc+`</td>
            <td class="text-right">`+parseFloat(itemamount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
            <td class="text-center">`+itemqty+`</td>
            <td class="text-right col_total">`+total+`</td>
            <td class="text-center">
              <button class="btn btn-danger p_removeitem btn-block btn-sm"><i class="fas fa-trash-alt"></i></button>
            </td>
          </tr>
        `)

        sumitemtotal()
        $('#modal-additems').modal('hide')
      })

      function sumitemtotal()
      {
        var gtotal = 0;
        var curtotal = 0;

        $('#p_itemlist tr').each(function(){
          curtotal = $(this).find('.col_total').text().replace(/,/g, '')

          if(curtotal == '')
          {
            curtotal = 0;
          }

          gtotal += parseFloat(curtotal)

          console.log(gtotal +' + '+ curtotal)
        })

        $('#p_grandtotal').text(parseFloat(gtotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
      }

      $(document).on('click', '#btn_saveitems', function(){

				if($('#select_supplier').hasClass('is-invalid') || $('#p_itemlist tr').length == 0)
				{
					Swal.fire({
						type: "error",
						title: "Fill in required fields",
						text: "No Supplier or No Items were added",
						footer: ''
					});

					return;
				}

        var items = []
        var supplier = $('#select_supplier').val()
        var grandtotal = $('#p_grandtotal').text()
        var remarks = $('#p_remarks').val()
        var dataid = $(this).attr('data-id')
        var date = $('#purchasing_date').val()
        
        if($('#p_cash').prop('checked') == true)
        {
          var ptype = 'CASH'
        }
        else{
          var ptype = 'CREDIT'
        }

        $('#p_itemlist tr').each(function(){
          // console.log($(this).attr('item-id'))
          var i = {}
          i.dataid = $(this).attr('data-id')
          i.itemid = $(this).attr('item-id')
          i.itemamount = $(this).attr('item-amount')
          i.qty = $(this).attr('item-qty')
          i.total = $(this).attr('item-total')

          items.push(i)
        })

        $.ajax({
          type: "GET",
          url: "{{route('purchase_create')}}",
          data: {
            items:items,
            supplier:supplier,
            grandtotal:grandtotal,
            ptype:ptype,
            remarks:remarks,
            dataid:dataid,
            date:date
          },
          // dataType: "dataType",
          success: function (data) {
            loadpurchasing(data)

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
              title: 'P.O Successfully saved'
            })
            
            $('#btn_saveitems').attr('data-id', data)
						postedpurchase(0)
            // $('#modal_addpayable').modal('hide');
          }
        });
        
      })

      var viewtrans = '';

      loadpurchasing()

      function loadpurchasing(dataid="")
      {
        var supplierid = $('#filter_supplier').val()
        var datefrom = $('#datefrom').val()
        var dateto = $('#dateto').val()
        var filter = $('#filter_search').val()

        $.ajax({
          type: "GET",
          url: "{{route('purchase_load')}}",
          data: {
            supplierid:supplierid,
            datefrom:datefrom,
            dateto:dateto,
            filter:filter
          },
          // dataType: "dataType",
          success: function (data) {
            $('#purchase_order_datatable tr').empty()
            $.each(data, function (index, val) { 
              if(val.remarks == null)
              {
                var remarks = ''
              }
              else{
                var remarks = val.remarks
              }

              $('#purchase_order_datatable').append(`
                <tr data-id="`+val.id+`">
                  <td>`+val.date+`</td>
                  <td>`+val.ref+`</td>
                  <td>`+val.supplier+`</td>
                  <td class="text-right">`+val.amount+`</td>
                  <td>`+val.pstatus+`</td>
                  <td>`+val.ptype+`</td>
                  <td>`+remarks+`</td>
                </tr>
              `)
            });

            if(viewtrans == 'create')
            {
              getPOItems(dataid)
            }
          }
        });
      }

      $(document).on('click', '#purchase_order_datatable tr', function(){
        var id = $(this).attr('data-id');
        viewtrans = 'detail'
        $.ajax({
          type: "GET",
          url: "{{route('purchase_read')}}",
          data: {
            id:id
          },
          // dataType: "dataType",
          success: function (data) {
            $('#select_supplier').val(data.supplierid).change()
            $('#p_grandtotal').text(data.totalamount)
            $('#p_remarks').val(data.remarks)
            $('#btn_saveitems').attr('data-id', id)
            $('#purchasing_date').val(data.date)

            if(data.ptype == 'CASH')
            {
              $('#p_cash').prop('checked', true);
            }
            else{
              $('#p_payable').prop('checked', true);
            }

            $('#p_itemlist').empty()
            $.each(data.items, function (index, val) { 
              $('#p_itemlist').append(`
                <tr data-id="`+val.id+`" item-id="`+val.itemid+`"item-amount="`+val.amount+`" item-qty="`+val.qty+`" item-total="`+val.totalamount+`">
                  <td>`+val.description+`</td>
                  <td class="text-right">`+parseFloat(val.amount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
                  <td class="text-center">`+val.qty+`</td>
                  <td class="text-right col_total">`+parseFloat(val.totalamount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
                  <td class="text-center">
                    <button class="btn btn-danger p_removeitem btn-block btn-sm"><i class="fas fa-trash-alt"></i></button>
                  </td>
                </tr>
              `)
            });

            if(data.pstatus == 'POSTED')
            {
              postedpurchase(1)

            }
            else if(data.pstatus == 'SUBMITTED')
            {
              postedpurchase(0)
            }

            
            $('#modal_addpayable').modal('show')
          }
        });
      })

      function postedpurchase(posted)
      {
        if(posted == 1)
        {
          $('#btn_post').hide()
          $('#btn_delete').hide()    
          $('#btn_saveitems').hide()
          $('#btn_posted').show()
          $('#p_printpo').show()

          $('#select_supplier').prop('disabled', true)
          $('#p_remarks').prop('disabled', true)
          $('#p_cash').prop('disabled', true)
          $('#p_payable').prop('disabled', true)
          $('#btn_additem').prop('disabled', true)
          $('.p_removeitem').prop('disabled', true)
        }
        else if(posted == 0){
          $('#btn_post').show()
          $('#btn_delete').show()
          $('#btn_saveitems').show()
          $('#btn_posted').hide()
          $('#p_printpo').hide()

          $('#select_supplier').prop('disabled', false)
          $('#p_remarks').prop('disabled', false)
          $('#p_cash').prop('disabled', false)
          $('#p_payable').prop('disabled', false)
          $('#btn_additem').prop('disabled', false)
        }
        else if(posted == 2)
        {
          $('#btn_post').hide()
          $('#btn_delete').hide()
          $('#btn_saveitems').show()
          $('#btn_posted').hide()
          $('#p_printpo').hide()

          $('#select_supplier').prop('disabled', false)
          $('#p_remarks').prop('disabled', false)
          $('#p_cash').prop('disabled', false)
          $('#p_payable').prop('disabled', false)
          $('#btn_additem').prop('disabled', false)
        }
      }


      function loaditemdetail()
      {
        var headerid = $('#btn_saveitems').attr('data-id')

        $.ajax({
          type: "GET",
          url: "{{route('purchase_loaditems')}}",
          data: {
            headerid:headerid
          },
          // dataType: "dataType",
          success: function (data) {
            setTimeout(() => {
              $('#p_itemlist').empty()
              $.each(data, function (index, val) { 
                $('#p_itemlist').append(`
                  <tr data-id="`+val.id+`" item-id="`+val.itemid+`"item-amount="`+val.amount+`" item-qty="`+val.qty+`" item-total="`+val.totalamount+`">
                    <td>`+val.description+`</td>
                    <td class="text-right">`+parseFloat(val.amount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
                    <td class="text-center">`+val.qty+`</td>
                    <td class="text-right col_total">`+parseFloat(val.totalamount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
                    <td class="text-center">
                      <button class="btn btn-danger p_removeitem btn-block btn-sm"><i class="fas fa-trash-alt"></i></button>
                    </td>
                  </tr>
                `)
              });

              setTimeout(() => {
                sumitemtotal()
              }, 300);
                
            }, 0);
              

              
          }
        });
      }

      $(document).on('click', '.p_removeitem', function(){
        var headerid = $('#btn_saveitems').attr('data-id')

        Swal.fire({
          title: 'Delete Item',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Delete'
        }).then((result) => {
          if (result.value == true) {
            if(headerid == 0)
            {
              $(this).closest('tr').remove()
              sumitemtotal()
              Swal.fire(
                'Deleted!',
                'Item has been successfully deleted',
                'success'
              )
            }
            else{
              var dataid = $(this).closest('tr').attr('data-id')

              $.ajax({
                type: "GET",
                url: "{{route('purchase_deleteitem')}}",
                data: {
                  headerid:headerid,
                  dataid:dataid
                },
                success: function (data) {
                  loaditemdetail()
                }
              });
            }
          }
        })
      })

      $(document).on('click', '#btn_delete', function(){
        var dataid = $('#btn_saveitems').attr('data-id')

        Swal.fire({
          title: 'Delete Purchasing?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Delete'
        }).then((result) => {
          if (result.value == true) {
            $.ajax({
              type: "GET",
              url: "{{route('purchase_delete')}}",
              data: {
                dataid:dataid
              },
              // dataType: "dataType",
              success: function (data) {
                Swal.fire(
                  'Deleted!',
                  'Purchasing has been successfully deleted',
                  'success'
                ) 
                
                loadpurchasing()
                $('#modal_addpayable').modal('hide')
              }
            });
          }
        })
      })

      $(document).on('click', '#btn_post', function(){
        var dataid = $('#btn_saveitems').attr('data-id')

        //$('#btn_saveitems').trigger('click')

        Swal.fire({
          title: 'Post Purchasing Transaction?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Post'
        }).then((result) => {
          if (result.value == true) {

						$('#btn_saveitems').trigger('click')

						setTimeout(() => {
							$.ajax({
								type: "GET",
								url: "{{route('purchase_post')}}",
								data: {
									dataid:dataid
								},
								// dataType: "dataType",
								success: function (data) {
									Swal.fire(
										'Posted!',
										'Purchasing has been Posted',
										'success'
									) 
									
									loadpurchasing()
									$('#modal_addpayable').modal('hide')
								}
							});
						}, 1500);
          }
        })
      })

      $(document).on('click', '#p_printpo', function(){
        var id = $('#btn_saveitems').attr('data-id')
        window.open('/finance/purchasing/purchase_read?id='+id+'&action=print', '_blank');
      })

      $(document).on('change', '#datefrom', function(){
        loadpurchasing()
      })

      $(document).on('change', '#dateto', function(){
        loadpurchasing()
      })

      $(document).on('change', '#filter_supplier', function(){
        loadpurchasing()
      })

			$(document).on('change', '#select_supplier', function(){
				if($(this).val() != 0){
					$(this).removeClass('is-invalid');
					$(this).addClass('is-valid');
				}
			})

      function getPOItems(id){
        $('#purchase_order_datatable tr[data-id="'+id+'"]').trigger('click')
      }

      $(document).on('focusin', '#p_itemamount', function(){
        $(this).select();
      })

      // ----------- load data ajax functions ------------------------


      // ------------- datatable functions --------------------------

      // -------------- others function ------------------------------
    

    });

  </script>
  
@endsection