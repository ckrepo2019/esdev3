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
    }
    else{
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
        }
        else{
          $extend = 'general.defaultportal.layouts.app';
        }
      }
      else{
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
        <h1>Receiving Report</h1>
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
                      <input id="txtsearchitem" type="text" class="form-control" placeholder="Search Reference #" onkeyup="this.value = this.value.toUpperCase();">
                      <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                      {{-- <div class="input-group-append">
                          <button class="btn btn-primary" id="btn_create_payable">Create</button>
                      </div> --}}
                  </div>
              </div>
  
            </div>
            <div class="row">
              <div class="col-md-12" id="main_table" style="overflow: auto">
                  <table width="100%" class="table table-hover table-sm text-sm" id="receiving_order_datatable" style="font-size: 16px;">
                      <thead>
                          <tr>
                              <th width="10%">Date</th>
                              <th width="10%">Reference #</th>
                              <th width="10%">P.O #</th>
                              <th width="18%">Supplier</th>
                              <th class="text-center" width="6%">Amount</th>
                              <th class="" width="6%">Status</th>
                              <th class="" width="10%">Pay Method</th>
                              <th class="" width="45%">Remarks</th>
                          </tr>
                      </thead>
                      <tbody id="rr_list" style="cursor: pointer">
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
<div class="modal fade show" id="modal_addreceiving" style="display: none">
  <div class="modal-dialog modal-xl">
    <div class="modal-content" style="height: 45em">
        <div class="modal-body">
          <div class="row">
              <div class="col-md-8">
                <div class="row">
                  
                  <div class="col-sm-5 col-md-12">
                    <h4><span id="r_suppliername">CK PUBLISHING | PO-00000001 | CASH</span> 
                      <badge class="badge badge-primary text-sm" style="margin-top: 2px;"><i class="fas fa-edit"></i> Override</badge>
                    </h4>
                  </div>
                  {{-- <div class="col-md-4"></div> --}}
                  
                </div>
                <div class="row form-group">
                  <div class="col-md-12 table-responsive" style="height: 14.66em">
                    <table width="100%" class="table table-striped table-sm text-sm" id="items_datatable">
                      <thead>
                          <tr>
                              <th width="60%">Particulars</th>
                              <th class="text-center" width="20%">Amount</th>
                              <th class="text-center" width="10%">QTY</th>
                              <th class="text-center" width="10%">Total</th>
                              <th class="text-center" width="10%">Received QTY</th>
                              <th class="text-center" width="10%">Total Received</th>
                              <th></th>
                          </tr>
                      </thead>
                      <tbody id="r_itemlist" class="">
                        <tr class="">
                          <td class="align-middle">Web Design Books</td>
                          <td class="text-center align-middle" style="">600</td>
                          <td class="text-center align-middle">4</td>
                          <td class="text-center align-middle">2,400</td>
                          <td class="text-center align-middle">
                            <input  type="number" class="form-control form-control-sm r_qty">
                          </td>
                          <td class="text-center align-middle r_treceived">2,400.00</td>
                          <td><button class="btn btn-danger btn-sm text-sm"><i class="far fa-trash-alt"></i></button></td>
                        </tr>
                        
                        
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="3" class="text-right text-bold text-primary">TOTAL: </td>
                          <td id="r_itemtotal" colspan="" class="text-right text-bold text-primary"></td>
                          <td colspan="" class="text-right text-bold text-primary"></td>
                          <td id="r_receivedtotal" colspan="" class="text-right text-bold text-primary">0.00</td>
                          <td></td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                  {{-- <div class="col-sm-12 col-12 text-right" style="position: relative;bottom: 0;right: 0;">
                    <br>
                    <h4>Total: <span style="color: #0275d8;"><b>12,174</b></span></h4>
                    <input type="text" style="color: #0275d8;" id="items_total" value="5658" hidden>
                  </div> --}}
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header bg-info">
                        <div class="row">
                          <div class="col-md-7">
                            JOURNAL ENTRY
                          </div>
                          <div class="col-md-5 text-right">
                            <button id="r_addje" class="btn btn-dark btn-sm text-sm text-right text-light"><i class="fas fa-plus"></i> Add Entries</button>
                          </div>
                        </div>
                        
                      </div>
                      <div class="card-body" style="height: 14em">
                        <div class="row">
                          <div class="col-md-12 table-responsive">
                            <table class="table table-sm text-sm">
                              <thead>
                                <tr>
                                  <th>Account</th>
                                  <th class="text-center">Debit</th>
                                  <th class="text-center">Credit</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody id="je_accounts">

                              </tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-right">TOTAL:</th>
                                  <th id="r_debittotal" class="text-right">0.00</th>
                                  <th id="r_credittotal" class="text-right">0.00</th>
                                  <th></th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
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
                  {{-- <div class="row" style="font-size: 18px;">
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
                  </div> --}}
                  
                  {{-- card for payments --}}
                  

                  <div class="row form-group" style="margin-top: 2em">
                    <div class="col-md-9">
                      <label for="">Invoice Number</label>
                      <input id="r_invoiceno" type="text" class="form-control form-control-sm is-invalid">
                    </div>
                    <div class="col-md-3">
                      <label for="">Terms</label>
                      <input id="r_terms" type="number" class="form-control form-control-sm">
                    </div>
                  </div>
                  
                  <div class="row form-group">
                    <div class="col-md-6">
                      <label for="">Invoice Date</label>
                      <input id="r_invoicedate" type="date" class="form-control form-control-sm" value="{{date_format(App\FinanceModel::getServerDateTime(), 'Y-m-d')}}">
                    </div>
                    <div class="col-md-6">
                      <label for="">Date Delivered</label>
                      <input id="r_datedelivered" type="date" class="form-control form-control-sm" value="{{date_format(App\FinanceModel::getServerDateTime(), 'Y-m-d')}}">
                    </div>
                  </div>

                  <div class="row form-group">
                    <div class="col-md-12">
                      <label>Remarks</label>
                      <textarea id="r_remarks" class="form-control form-control-sm"></textarea>
                    </div>
                  </div>

                  <div class="row" style="margin-top: 1.6em">
                    <div class="col-sm-12 col-12" style="padding-top: 5px;">
                      <div class="card" style="box-shadow: none!important;">
                        <div class="card-header bg-primary" style="height: 41px!important;">
                          <div class="row">
                            <div class="col-md-3" style="margin-top: -4px">TERMS</div>
                            <div class="col-md-9 text-right">
                              <button id="r_apgensched" class="btn btn-dark btn-sm text-right text-light" style="margin-top: -9px"><i class="fas fa-plus"></i> Create A/P Schedule</button>
                            </div>
                          </div>

                        </div>
                        <div class="card-body">
                          <div class="row">
                            <div class="col-sm-12" style="height: 12.4em; overflow:auto;">
                              <table class="table table-sm text-xs" style="margin-bottom: 0!important">
                                <thead>
                                  <tr>
                                    <th width="20%" class="text-left p-0">Due Date</th>
                                    <th width="25%" class="text-center p-0">Amount</th>
                                    <th width="25%" class="text-center p-0">Paid</th>
                                    <th width="25%" class="text-center p-0">Balance</th>
                                  </tr>
                                </thead>
                                <tbody id="r_termlist">
                                  <tr>
                                    <td class="p-0">5/27/2023 <span class="text-primary" style="cursor: pointer;" data-toggle="tooltip" title="Edit Date"><i class="far fa-edit"></i></span></td>
                                    <td class="text-right p-0">0.00</td>
                                    
                                  </tr>
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <td class="text-right p-0 text-bold">TOTAL: </td>
                                    <td id="r_totalamount" class="p-0 text-right text-bold">0.00</td>
                                    <td id="r_totalpaid" class="p-0 text-right text-bold">0.00</td>
                                    <td id="r_totalbalance" class="p-0 text-right text-bold">0.00</td>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
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
            <div id="div_posted" class="row" style="display: none">
              <div class="col-md-8">
                <button class="btn btn-success btn-block" id="btn_posted" disabled><i class="fas fa-check-circle"></i> POSTED</button>    
              </div>
              <div id="div_print" class="col-md-4" style="display: none">
                <button class="btn btn-primary btn-block" id="btn_print" ><i class="fas fa-print"></i> Print</button>    
              </div>
            </div>
            
            {{-- <button class="btn btn-danger" id="btn_delete"><i class="fas fa-trash"></i> Delete</button> --}}
            <button class="btn btn-success" id="btn_post"><i class="fas fa-thumbtack"></i> Post</button>
            <button class="btn btn-primary" id="btn_saveitems" po-id="0" r-id="0"><i class="fas fa-save"></i> Save</button>
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
                  <label for="">Items Description</label>
                  <input type="text" class="form-control" placeholder="Enter Item Description">
                </div>
              </div>
              <div class="col-sm-12 col-12">
                <div class="form-group">
                  <label for="">Amount</label>
                  <input type="number" class="form-control" placeholder="Enter Amount">
                </div>
              </div>
              <div class="col-sm-12 col-12">
                <div class="form-group">
                  <label for="">Quantity</label>
                  <input type="number" class="form-control" placeholder="Enter Quantity">
                </div>
              </div>
              <div class="col-sm-12 col-12">
                <div class="form-group">
                  <label for="">Total</label>
                  <input type="number" class="form-control" placeholder="Total Amount">
                </div>
              </div>
            </div>
                
          </div>
          <div class="modal-footer">
            <div class="col-md-6">
              <button class="btn btn-primary" id="btn-addsubjcom"><i class="fas fa-plus"></i> Add</button>
            </div>
            <div class="col-md-6 text-right">
                <button class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
            </div>
          </div>
      </div>
  </div>
</div>

<div class="modal fade show" id="modal-terms_changedate" style="display:none;">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header">
              {{-- <h5 class="modal-title" id="exportmodalLabel">Add Item</h5> --}}
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span>×</span>
              </button>
          </div>
          <div class="modal-body">

            <div class="row">
              <div class="col-sm-12 col-12">
                <div class="form-group">
                  <label for="">Due Date</label>
                  <input id="r_payduedate" type="date" class="form-control" placeholder="">
                </div>
            </div>
                
          </div>
          <div class="modal-footer">
            <div class="col-md-6 text-right">
                <button class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
            </div>
            <div class="col-md-6">
              <button class="btn btn-primary" id="btn-addsubjcom"><i class="fas fa-plus"></i> Save</button>
            </div>
            
          </div>
      </div>
  </div>
</div>
@endsection

@section('jsUP')
<style>
  .scrollable-tbody {
        height: 200px; /* Set the desired height for the tbody */
        overflow-y: scroll; /* Enable vertical scrolling */
    }
</style>
@endsection

@section('js')
  
  <script type="text/javascript">
    
    $(document).ready(function(){
      $('.select2').select2({
            theme: 'bootstrap4'
        });

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
      

      // calling functions
      // purchase_orders()
      // items_datatable()
      
      // ------------------ events --------------------------
      // Checkbox Payment Method

      screenadjust()

      function screenadjust()
      {
        var screen_height = $(window).height();

        $('#main_table').css('height', screen_height - 315)
        $('#setup_table').css('height', screen_height - 150)
            // $('.screen-adj').css('height', screen_height - 223);
      }

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

      $(document).on('keyup', '.currency', function(e){
        var char = e.which;
        var str = String.fromCharCode(char);

        if (!(/[0-9.,]/.test(str))) {
          event.preventDefault();
        }
      })

      $(document).on('change', '.currency', function(){
        var value = $(this).val()

        $(this).val(parseFloat(value, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
      })

      $(document).on('click', '#btn_create_payable', function(){
        $('#modal_addpayable').modal('show')
      })
      
      // click Add Item
      $(document).on('click', '#btn_additem', function(){
        $('#modal-additems').modal('show')
      })

      loadpurchasing()

      function loadpurchasing()
      {
        var supplierid = $('#filter_supplier').val()
        var datefrom = $('#datefrom').val()
        var dateto = $('#dateto').val()
        var filter = $('#filter_search').val()

        $.ajax({
          type: "GET",
          url: "{{route('receiving_load')}}",
          data: {
            supplierid:supplierid,
            datefrom:datefrom,
            dateto:dateto,
            filter:filter
          },
          // dataType: "dataType",
          success: function (data) {

            console.log(data)
            $('#rr_list tr').empty()
            $.each(data, function (index, val) { 
              if(val.remarks == null)
              {
                var remarks = ''
              }
              else{
                var remarks = val.remarks
              }

              $('#rr_list').append(`
                <tr po-id="`+val.id+`">
                  <td>`+val.date+`</td>
                  <td>`+val.ref+`</td>
                  <td>`+val.ponum+`</td>
                  <td>`+val.supplier+`</td>
                  <td class="text-right">`+val.amount+`</td>
                  <td>`+val.pstatus+`</td>
                  <td>`+val.ptype+`</td>
                  <td>`+remarks+`</td>
                </tr>
              `)
              
            });
          }
        });
      }

			function validate_invoice()
			{
				if($('#r_invoiceno').val() == '')
				{
					$('#r_invoiceno').addClass('is-invalid')
					$('#r_invoiceno').removeClass('is-valid')
					$('#btn_post').prop('disabled', true)
				}
				else{
					$('#r_invoiceno').removeClass('is-invalid')
					$('#r_invoiceno').addClass('is-valid')
					$('#btn_post').prop('disabled', false)
				}


			}

			$(document).on('keyup', '#r_invoiceno', function(){
				validate_invoice()
			})

      $(document).on('click', '#rr_list tr', function(){
        var poid = $(this).attr('po-id')

        $.ajax({
          type: "GET",
          url: "{{route('receiving_read')}}",
          data: {
            poid:poid
          },
          // dataType: "dataType",
          success: function (data) {
            var headertext = data.supplier + ' | ' + data.refnum + ' | ' + data.ptype
            var total = 0;

            $('#r_suppliername').text(headertext )
            $('#btn_saveitems').attr('po-id', poid)
            $('#btn_saveitems').attr('r-id', data.rid)
            $('#r_invoiceno').val(data.invoiceno).trigger('keyup')
            $('#r_remarks').val(data.remarks)

            if(data.rid == 0)
            {
              $('#btn_post').hide()
            }
            else{
              $('#btn_post').show() 
            }

            if(data.invoicedate == '')
            {
              var invdate = moment();
              invdate = moment(invdate).format('YYYY-MM-DD')
              $('#r_invoicedate').val(invdate)
            }
            else{
              $('#r_invoicedate').val(data.invoicedate)  
            }

            if(data.datedelivered == '')
            {
              var deldate = moment()
              deldate = moment(deldate).format('YYYY-MM-DD')
              $('#r_datedelivered').val(deldate)
            }
            else{
              $('#r_datedelivered').val(data.datedelivered)  
            }

            
            $('#r_terms').val(data.terms)
            
            

            $('#r_itemlist').empty()

            $.each(data.items, function (index, val) { 
              total += parseFloat(val.totalamount)

              // console.log(val.totalamount)

              $('#r_itemlist').append(`
                <tr data-id="`+val.id+`" podetail-id="`+val.podetailid+`" data-amount="`+val.amount+`" data-itemid="`+val.itemid+`" data-qty="`+val.qty+`" data-totalamount="`+val.totalamount+`">
                  <td class="align-middle">`+val.description+`</td>
                  <td class="text-center align-middle" style="">`+parseFloat(val.amount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
                  <td class="text-center align-middle">`+val.qty+`</td>
                  <td class="text-right align-middle">`+parseFloat(val.totalamount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
                  <td class="text-right align-middle">
                    <input  type="number" class="form-control form-control-sm r_qty" value="`+val.receivedqty+`">
                  </td>
                  <td class="text-right align-middle r_treceived">0.00</td>
                  <td><button class="btn btn-danger btn-sm text-sm"><i class="far fa-trash-alt"></i></button></td>
                </tr>
              `)
            });

            $('#r_itemtotal').text(parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())

            $('#r_itemlist tr').each(function() {
              $(this).find('.r_qty').trigger('change')
            });

            var totalpaid = 0
            var totalamount = 0
            var payment = 0
            var totalpayment = data.d_payment
            var balance = 0
            var totalbalance = 0

            if(data.d_payment == null)
            {
              totalpayment = 0
            }

            // console.log('totalpayment: ' + totalpayment)

            $('#r_termlist').empty()
            $.each(data.payables, function(index, val) {
              date = val.duedate
              totalamount += parseFloat(val.amount)

              // if(parseFloat(totalpayment) > 0)
              // {
                
              // }
              // else{
              //   payment = 0
              // }

              if(parseFloat(val.amount) > parseFloat(totalpayment))
              {
                balance = parseFloat(val.amount) - parseFloat(totalpayment)
                payment = totalpayment
                totalpayment = 0
              }
              else{
                balance = 0
                payment = val.amount
                totalpayment = parseFloat(totalpayment) - parseFloat(val.amount)

              }

              totalbalance += parseFloat(balance)

              console.log('payment: ' + totalpayment)

              var duedate = moment(date).format('MM/DD/YYYY')
              $('#r_termlist').append(`
                <tr data-id="`+val.id+`" header-id="`+val.headerid+`">
                    <td class="due">
                      `+duedate+` <span id="r_termdate" class="text-primary" style="cursor: pointer;" data-toggle="tooltip" title="Edit Date">
                      </td>
                    <td class="text-right termamount">`+parseFloat(val.amount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
                    <td class="text-right termpayment">`+parseFloat(payment, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
                    <td class="text-right termbalance">`+parseFloat(balance, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
                  </tr>
              `)

              totalpaid += parseFloat(payment)
            });

            $('#r_totalpaid').text(parseFloat(totalpaid, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
            $('#r_totalamount').text(parseFloat(totalamount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
            $('#r_totalbalance').text(parseFloat(totalbalance, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
            
            $('#je_accounts').empty()

            $.each(data.je, function(index, val) {
              if(val.debit == null)
              {
                var debit = 0
              }
              else{
                var debit = val.debit
              }

              if(val.credit == null)
              {
                var credit = 0
              }
              else{
                var credit = val.credit
              }
              $('#je_accounts').append(`
                <tr data-id=`+val.id+`>
                  <td class="je_acc" coa-id="`+val.glid+`" style="width: 24em">
                    `+val.code+` - `+val.account+`
                  </td>
                  <td class="je_debit text-right" debit-val="`+debit+`" style="width: 8em">
                    `+parseFloat(debit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`
                  </td>
                  <td class="je_credit text-right" credit-val="`+credit+`" style="width: 8em">
                    `+parseFloat(credit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`
                  </td>
                  <td>
                    <button class="btn btn-danger btn-sm mt-1 je_delete">
                      <i class="far fa-trash-alt"></i>
                    </button> 
                  </td>
                </tr>
              `)
            });

            jecalc_debit()
            jecalc_credit()

            // console.log('rstatus: ' + data.rstatus)

            if(data.rstatus == 'POSTED')
            {
              postdisplay(1)
            }
            else{
              postdisplay(0)
            }


            $('#modal_addreceiving').modal('show');    
          }
        });  
      })

      

      $(document).on('change', '.r_qty', function(){
        var qty = $(this).val()
        var amount = $(this).closest('tr').attr('data-amount')

        if(qty == '' || qty == null)
        {
          qty = 0
        }
        
        var totalamount = parseFloat(amount) * parseFloat(qty)
        var totalreceived = 0;
        $(this).closest('tr').find('.r_treceived').text(parseFloat(totalamount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())

        $('#r_itemlist tr').each(function(){
          receivedamount = $(this).find('.r_treceived').text().replace(/,/g, '')
          totalreceived += parseFloat(receivedamount)

          console.log(totalreceived)
          
        })

        $('#r_receivedtotal').text(parseFloat(totalreceived, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
      })

      $(document).on('click', '#r_apgensched', function(){
        var term = $('#r_terms').val()
        var rrid = $('#btn_saveitems').attr('r-id')

        if(term != '' && term != null && term != 0)
        {
          
          Swal.fire({
            title: 'Create A/P Schedule?',
            text: "Existing A/P Schedule will be replaced.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Create A/P Schedule'
          }).then((result) => {
            if (result.value == true) {
              var currentDate = moment()
              $('#r_termlist').empty()

              $.ajax({
                type: "GET",
                url: "{{route('receiving_removesched')}}",
                data: {
                  rrid:rrid
                },
                // dataType: "dataType",
                success: function (data){

                }
              })

              var totalreceived = parseFloat($('#r_receivedtotal').text().replace(/,/g, ''))
              var divamount = totalreceived/term
              var curamount = totalreceived
              var totalbalance = 0;

                
              
              for (var count = 1; count <= term; count++)
              {
                var incrementedDate = currentDate.add(1, 'month')
                var amount = 0;

                if(count != term)
                {
                  if(curamount > divamount)
                  {
                    amount = divamount.toFixed(2)
                    curamount -= divamount.toFixed(2)
                  }
                  else{
                    amount = curamount
                    curamount = 0
                  }
                }
                else{
                  amount = curamount
                }

                totalbalance += parseFloat(amount)
                console.log(totalbalance)

                $('#r_termlist').append(`
                  <tr data-id="0">
                    <td class="due">
                      `+incrementedDate.format('MM/DD/YYYY')+` 
                      </td>
                    <td class="text-right termamount">`+parseFloat(amount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>  
                    <td class="text-right termpayment">`+"0.00"+`</td>
                    <td class="text-right termbalance">`+parseFloat(amount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
                  </tr>
                `)
              }

              $('#r_totalamount').text(parseFloat(totalbalance, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
              $('#r_totalbalance').text(parseFloat(totalbalance, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())

              Swal.fire(
                'DONE!',
                'A/P Schedule Successfully generated',
                'success'
              )
            }

            
            
          })
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
            title: 'Please input the desired term'
          }) 
        }
      })

      $(document).on('click', '#r_termdate', function(){
        $('#modal-terms_changedate').modal('show')
      })

      $(document).on('click', '#btn_saveitems', function(){
        var poid = $(this).attr('po-id')
        var rid = $(this).attr('r-id')
        var invoiceno = $('#r_invoiceno').val()
        var invoicedate = $('#r_invoicedate').val()
        var datedelivered = $('#r_datedelivered').val()
        var terms = $('#r_terms').val()
        var remarks = $('#r_remarks').val()

        var termsched = []
        var items_array = []
        var je_array = []

        $(this).prop('disabled', true)

        $('#r_itemlist tr').each(function(){
          var dataid = $(this).attr('data-id')
          var podetailid = $(this).attr('podetail-id')
          var amount = $(this).attr('data-amount')
          var itemid = $(this).attr('data-itemid')
          var qty = $(this).attr('data-qty')
          var totalamount = $(this).attr('data-totalamount')

          var r_qty = $(this).find('.r_qty').val()
          var r_totalamount = $(this).find('.r_treceived').text()

          var obj = {
            dataid:dataid,
            podetailid:podetailid,
            amount:amount,
            itemid:itemid,
            qty:qty,
            totalamount:totalamount,
            r_qty:r_qty,
            r_totalamount:r_totalamount,
            remarks:remarks
          }

          items_array.push(obj)
        })

        $('#r_termlist tr').each(function(){
          var dataid = $(this).attr('data-id')
          var due = $(this).find('.due').text()
          var amount = $(this).find('.termamount').text()
          var payment = $(this).find('.termpayment').text()

          var t = {
            dataid:dataid,
            due:due,
            amount:amount,
            payment:payment
          }

          termsched.push(t)
        })

        $('#je_accounts tr').each(function(){
          var dataid = $(this).attr('data-id')
          var glid = $(this).find('.je_acc').attr('coa-id')
          var debit = $(this).find('.je_debit').attr('debit-val')
          var credit = $(this).find('.je_credit').attr('credit-val')

          var acc = {
            dataid:dataid,
            glid:glid,
            debit:debit,
            credit:credit
          }

          je_array.push(acc)
        })

        $.ajax({
          type: "POST",
          url: "{{route('receiving_save')}}",
					headers: {
								'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the headers
						},
          data: {
            poid:poid,
            rid:rid,
            invoiceno:invoiceno,
            invoicedate:invoicedate,
            datedelivered:datedelivered,
            terms:terms,
            remarks:remarks,
            items:items_array,
            termsched:termsched,
            je_array:je_array
          },
          // dataType: "dataType",
          success: function (data){
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
              title: 'Saved'
            })

            setTimeout(function(){
              $('#btn_saveitems').prop('disabled', false)

              $('#rr_list tr[po-id="'+poid+'"]').trigger('click')

            }, 500)
          }
        })
      });

      var acc_coa = '';
      
      getcoa()

      function getcoa()
      {
        $.ajax({
          type: "GET",
          url: "{{route('expenses_gencoa')}}",
          // data: "data",
          // dataType: "dataType",
          success: function (data) {
            acc_coa = ''
            acc_coa = data
          }
        });
      }

      function appendje()
      {
        console.log('click')

        var rowcount = $('#je_accounts').length;
        var totaldebit = $('#totaldebit').text().replace(/,/g, '')
        var total = $('#r_receivedtotal').text()

        var je_debittotal = $('#r_debittotal').text().replace(/,/g, '')
        var je_credittotal = $('#r_credittotal').text().replace(/,/g, '')
        var rtotal = total.replace(/,/g, '')
        var t_debit = parseFloat(rtotal) - parseFloat(je_debittotal)
        var t_credit = parseFloat(rtotal) - parseFloat(je_credittotal)

        // console.log('tdebit: ' + t_debit)
        if(t_debit != 0)
        {
          $('#je_accounts').append(`
            <tr data-id="0">
              <td class="je_acc" coa-id="0" style="width: 24em">
                <select id="jeid_`+rowcount+`" class="form-control select2 je_input je_coa ">
                  `+acc_coa+`
                </select>
              </td>
              <td class="je_debit text-right" debit-val="0" style="width: 9em">
                <input type="text" class="form-control text-right je_input debit currency" value="`+parseFloat(t_debit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`">
              </td>
              <td class="je_credit text-right" credit-val="0" style="width: 9em">
                <input type="text" class="form-control text-right je_input credit currency" value="`+totaldebit+`">
              </td>
              <td>
                <button class="btn btn-primary btn-sm mt-1 je_button">
                  <i class="fas fa-download"></i>
                </button> 
                <button class="btn btn-danger btn-sm mt-1 je_delete">
                  <i class="far fa-trash-alt"></i>
                </button> 
              </td>
            </tr>
          `)
        }
        else{
          $('#je_accounts').append(`
            <tr data-id=0>
              <td class="je_acc" coa-id="0" style="width: 24em">
                <select id="jeid_`+rowcount+`" class="form-control select2 je_input je_coa ">
                  `+acc_coa+`
                </select>
              </td>
              <td class="je_debit text-right" debit-val="0" style="width: 8em">
                <input type="text" class="form-control text-right je_input debit currency" value="`+parseFloat(t_debit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`">
              </td>
              <td class="je_credit text-right" credit-val="0" style="width: 8em">
                <input type="text" class="form-control text-right je_input credit currency" value="`+parseFloat(t_credit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`">
              </td>
              <td>
                <button class="btn btn-primary btn-sm mt-1 je_button">
                  <i class="fas fa-download"></i>
                </button> 
                <button class="btn btn-danger btn-sm mt-1 je_delete">
                  <i class="far fa-trash-alt"></i>
                </button> 
              </td>
            </tr>
          `) 
        }

        $('.select2').select2({
          theme: 'bootstrap4'
        });
      }

      function jecalc_debit()
      {
        var trdebittotal = 0;
        $('#je_accounts tr').each(function(){
          trdebit = $(this).find('.je_debit').attr('debit-val')
          console.log(trdebit)
          trdebittotal += parseFloat(trdebit)
        })

        $('#r_debittotal').text(parseFloat(trdebittotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
      }

      function jecalc_credit()
      {
        var trcredittotal = 0;
        $('#je_accounts tr').each(function(){
          trcredit = $(this).find('.je_credit').attr('credit-val')

          if(trcredit == '' || trcredit == null)
          {
            trcredit = 0
          }

          trcredittotal += parseFloat(trcredit)
        })

        $('#r_credittotal').text(parseFloat(trcredittotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
        
        
      }

      $(document).on('click', '#r_addje', function(){
        appendje()
      })

      $(document).on('click', '.je_button', function(){
        var account = $(this).closest('tr').find('.je_coa').find('option:selected').text()
        var glid = $(this).closest('tr').find('.je_coa').val()
        var debit = $(this).closest('tr').find('.debit').val()
        var credit = $(this).closest('tr').find('.credit').val()

        $(this).closest('tr').find('td.je_input').remove()
        $(this).closest('tr').find('.debit').remove()
        $(this).closest('tr').find('.credit').remove()
        
        $(this).closest('tr').find('td.je_acc').text(account)
        $(this).closest('tr').find('td.je_acc').attr('coa-id', glid)
        $(this).closest('tr').find('td.je_debit').text(debit)
        $(this).closest('tr').find('td.je_debit').attr('debit-val', debit.replace(/,/g, ''))
        $(this).closest('tr').find('td.je_credit').text(credit)
        $(this).closest('tr').find('td.je_credit').attr('credit-val', credit.replace(/,/g, ''))

        $(this).closest('tr').find('.je_button').remove()

        jecalc_debit()
        jecalc_credit()

        console.log(account + ' debit: ' + debit + ' credit' + credit)
      })

			var csrfToken = $('meta[name="csrf-token"]').attr('content');

      $(document).on('click', '.je_delete', function(){
        dataid = $(this).closest('tr').attr('data-id');
        if(dataid == 0)
        {
          $(this).closest('tr').remove()
        }
        else{
					$(this).closest('tr').remove()

					$.ajax({
						type: "POST",
						url: "{{ route('receiving_removeje') }}",
						headers: {
								'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the headers
						},
						data: {
							id:dataid
						},
						// dataType: "dataType",
						success: function (data) {
							jecalc_debit()
        			jecalc_credit()
						}
					});
        }
      })

      $(document).on('click', '#btn_post', function(){
        var dataid = $('#btn_saveitems').attr('r-id')
        var poid = $('#btn_saveitems').attr('po-id')

        setTimeout(() => {
          
        }, 1500);
          Swal.fire({
            title: 'Post Receivable?',
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
                  url: "{{route('receiving_post')}}",
                  data: {
                    dataid:dataid
                  },
                  // dataType: "dataType",
                  success: function (data){
                    setTimeout(function(){
                      $('#rr_list tr[po-id="'+poid+'"]').trigger('click')
                    }, 500)

                    Swal.fire(
                      'Posted!',
                      'Receiving Report has been posted.',
                      'success'
                    )
                  }
                })            
              }, 1500);
            }
          })
      })

      function postdisplay(posted)
      {
        if(posted == 1)
        {
          $('.r_qty').prop('disabled', true)
          $('#modal_addreceiving').find('.form-control').prop('disabled', true)
          // $('.form-control').prop('disabled', true)
          $('.je_delete').prop('disabled', true)
          $('#div_posted').show()
          $('#div_print').show()
          $('#btn_post').hide()
          $('#btn_saveitems').hide()
          $('#r_apgensched').prop('disabled', true)
          $('#r_addje').prop('disabled', true)
        }
        else{
          $('.r_qty').prop('disabled', false)
          $('#modal_addreceiving').find('.form-control').prop('disabled', false)
          // $('.form-control').prop('disabled', false)
          $('.je_delete').prop('disabled', false)
          $('#div_posted').hide()
          $('#btn_post').show()
          $('#div_print').hide()
          $('#btn_saveitems').show()
          $('#r_apgensched').prop('disabled', false)
          $('#r_termdate').prop('disabled', false)
          $('#r_addje').prop('disabled', false)
        }
      }

      $(document).on('click', '#btn_print', function(){
        var id = $('#btn_saveitems').attr('po-id')
        window.open('/finance/purchasing/receiving_read?poid='+id+'&action=print', '_blank');
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

      $(document).on('change', '#txtsearchitem', function(){
        loadpurchasing()
      })

      // $.ajax({
      //   type: "GET",
      //   url: "{{route('receiving_save')}}",
      //   data: {

      //   },
      //   // dataType: "dataType",
      //   success: function (data){
      //     console.log('aaa')

      //   }
      // })
      


      // ----------- load data ajax functions ------------------------


      // ------------- datatable functions --------------------------

      // -------------- others function ------------------------------
    

    });

  </script>
  
@endsection