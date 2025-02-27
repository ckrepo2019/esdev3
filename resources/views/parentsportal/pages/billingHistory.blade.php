@extends('parentsportal.layouts.app2')


@section('pagespecificscripts')

    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

@endsection


@section('content')



@if(Session::get('enrollmentstatus'))
    <div class="modal fade" id="updatemodal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                <img id="modalImage" src="" alt="" class="w-100">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="upload_payment" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Payment Selection</h4>
                </div>
                <div class="modal-body p-0" id="payment_selection_holder">
                    
                </div>
                <div class="card-footer">
                    <button class="btn btn-success uploadreceipt" disabled>Upload Payment Receipt</button>
                    <button type="button" class="btn btn-secondary"  data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="receiptInformation" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="card-header">
                    Receipt information
              </div>
              <form 
                    action="/payment/online/submitreceipt" 
                    id="paymentInfo" 
                    method="POST" 
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                          <div class="row">
                                <div class="col-md-8">
                                      <div class="row">
                                            <div class="form-group col-md-12">
                                                  <label for="">PAYMENT TYPE</label>
  
                                                  <select name="paymentType" id="paymentType" class="form-control ">
                                                        <option value="">SELECT PAYMENT TYPE</option>
                                                        @foreach(DB::table('paymenttype')->where('isonline','1')->where('deleted','0')->get() as $item)
                                                              <option value="{{$item->id}}">{{$item->description}}</option>
                                                        @endforeach
                                                  </select>
  
                                                 
                                                  <span class="invalid-feedback" role="alert">
                                                        <strong></strong>
                                                  </span>
                                            </div>
                                            <div class="form-group col-md-12">
                                                  <label for="">RECEIPT IMAGE</label>
                                                  <input type="file" class="form-control" name="recieptImage" id="recieptImage" accept=".png, .jpg, .jpeg">
                                              
                                                  <span class="invalid-feedback" role="alert" style="display:hidden">
                                                        <strong>required</strong>
                                                  </span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                  <label for="">REFERENCE NUMBER </label>
                                                  <input 
                                                  {{-- oninput="this.value=this.value.replace(/[^0-9]/g,'');" --}}
                                                  
                                                  class="form-control" name="refNum" id="refNum" placeholder="REFERENCE NUMBER" disabled>
                                                 
                                                  <span class="invalid-feedback" role="alert" style="display:hidden">
                                                        <strong>required</strong>
                                                  </span>
                                                 
                                            </div>
                                            <div class="form-group col-md-4">
                                                  <label for="">BANK NAME</label>
                                                  <select id="bankName" name="bankName" class="form-control" disabled>
                                                        <option value="">SELECT BANK</option>
                                                        @foreach (DB::table('onlinepaymentoptions')->where('paymenttype','3')->where('deleted','0')->where('isActive','1')->get() as $item)
                                                              <option value="{{$item->optionDescription}}">{{$item->optionDescription}}</option>
                                                        @endforeach
                                                        {{-- <option value="BPI">BPI</option>
                                                        <option value="CHINABANK">CHINABANK</option>
                                                        <option value="LANDBANK">LANDBANK</option>
                                                        <option value="BDO">BDO</option> --}}
                                                  </select>
                                                  <span class="invalid-feedback" role="alert" style="display:hidden">
                                                        <strong>required</strong>
                                                  </span>
                                            </div>
                                            <div class="form-group col-md-4">
                                                  <label for="">BANK TRANS. DATE</label>
                                                  <input type="date"  class="form-control" name="transDate" id="transDate" >
                                                  <span class="invalid-feedback" role="alert" style="display:hidden">
                                                        <strong>required</strong>
                                                  </span>
                                            </div>
                                            <div class="form-group col-md-12">
                                                  <label for="">PAYMENT AMOUNT</label>
                                                  {{-- <input min="1" step="any" oninput="this.value=this.value.replace(/[^0-9,.]/g,'');" class="form-control" name="amount" id="amount"  value="{{old('amount')}}"> --}}
  
                                                  <input class="form-control" type="text" name="amount" id="amount"  value="" data-type="currency" placeholder="00.00">
  
                                                  <span class="invalid-feedback" role="alert" style="display:hidden">
                                                        <strong id="amountError">required</strong>
                                                  </span>
                                            </div>
                                      </div>
                                </div>
                                <div class="col-md-4">
                                      <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Uploaded Payment</legend>
                                            <img class="mt-3 w-100" id="receipt"  />
                                      </fieldset>
                                </div>
                          </div>
                         
                    </div>
                   
                    <div class="modal-footer">
                          <button class="btn btn-success" id="proceedpayment" >
                                PROCEED
                          </button>
                          <button type="button" class="btn btn-secondary"  data-dismiss="modal">Cancel</button>
                    </div>
              </form>
            </div>
        </div>
    </div>



<section class="content-header">
  <div class="container-fluid">
  <div class="row">
      <div class="col-sm-6">
      <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-calendar-alt nav-icon"></i> BILLING HISTORY</h4>
      </div>
      <div class="col-sm-6">
      <!-- <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item active">Reports</li>
      </ol> -->
      </div>
  </div>
  </div>
</section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-success">
                    Billing Information
                    </div>
                    <div class="card-body table-responsive p-0" style="height: 300px;">
                        <table class="table table-head-fixed" style="min-width:700px">
                            <thead>
                                <tr>
                                    <th>Particulars</th>
                                    {{-- <th>Due Date</th> --}}
                                    <th class="text-right">Amount Due</th>
                                    <th class="text-right">Amount Pay</th>
                                    <th class="text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($billings) > 0)
                                    @foreach ($billings as $item)
                                        @if($item->balance == 0)
                                            <tr class="text-success">
                                        @elseif($item->amountpay != 0 && $item->amountpay != $item->amountdue)
                                            <tr class="text-primary">
                                        @else
                                            <tr class="text-danger">
                                        @endif
                                                <td>PAYABLE - 
                                                    @if($item->duedate != null)
                                                        {{strtoupper(\Carbon\Carbon::create($item->duedate)->isoFormat('MMMM'))}}
                                                    @else
                                                       
                                                    @endif
                                                </td>
                                                {{-- @if($item->duedate != null)
                                                    <td>{{\Carbon\Carbon::create($item->duedate)->isoFormat('MMMM')}}</td>
                                                @else
                                                    <td>June</td>
                                                @endif --}}
                                                <td class="text-right">	&#8369; {{number_format($item->amountdue,2)}}</td>
                                                <td class="text-right">	&#8369; {{number_format($item->amountpay,2)}}</td>
                                                <td class="text-right">	&#8369; {{number_format($item->balance,2)}}</td>
                                            </tr>
                                    @endforeach
    
                                    {{-- @if($item->balance == 0)
                                        <tr class="text-success">
                                    @else
                                        <tr class="text-danger">
                                    @endif
                                            <td colspan="4" class="text-right h4">Total Balance:</td>
                                            <td class="text-right h4">	&#8369; {{number_format($billings->sum('balance'),2)}}</td>
                                        </tr> --}}
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">Payment Schedule is yet available.</td>
                                    </tr>
                                    
                                    
                                
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" id="upload_payment_button"><i class="fas fa-shopping-cart"></i> Upload Online Payment</button>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-success">
                        @php
                            $runbal = 0;
                        @endphp
                        Billing Transactions
                    </div>
                    <div class="card-body table-responsive p-0" style="height: 300px;">
                        {{-- {{$billhis}} --}}
                        
                    
                        <table class="table table-head-fixed" style="min-width:800px">
                            <thead>
                                <tr>
                                    <th>Particulars</th>
                                    <th>Amount</th>
                                    <th class="text-right">Payment</th>
                                    <th class="text-right">O.R. #</th>
                                    <th class="text-right">Running Bal. </th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(count($billhis) > 0)
                                @php
                                    $withoutOR = false;
                                    $lastItem = null;
                                    $runbal = 0;
                                    
                                    if($withBalFor){
                                        $tuitionFee = collect($billhis)->sum('amount') - $balForInfo->amount;
                                        $runbal =  collect($billhis)->sum('amount');
                                    }
                                    else{
                                        $tuitionFee = collect($billhis)->sum('amount');
                                        $runbal = $tuitionFee;
                                    }

                                @endphp

                                
                                @if($withBalFor)

                                    @php
                                    
                                        $runbal = $tuitionFee + $balForInfo->amount;

                                    @endphp

                                    <tr class="text-success">
                                        <td>{{$balForInfo->particulars}}</td>
                                        <td>&#8369; {{number_format($balForInfo->amount,2)}}</td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">&#8369; {{number_format($balForInfo->amount,2)}}</td>
                                    </tr>

                                @endif

                                <tr class="text-success">
                                    <td>TUITION FEE </td>
                                    <td>&#8369; {{number_format($tuitionFee,2)}}</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">&#8369; {{number_format($runbal,2)}}</td>
                                </tr>

                                        
                                @foreach ($billhis as $item)

                                        {{-- @if($item->ornum != null)
                                            @if(!$withoutOR)

                                                <tr class="text-success">
                                                    <td>{{$lastItem->particulars}}</td>
                                                    @if($lastItem->amount!=0)
                                                        <td>&#8369; {{number_format($lastItem->amount,2)}}</td>
                                                    @else
                                                        <td></td>
                                                    @endif

                                                    @if($lastItem->payment!=0)
                                                        <td>	&#8369; {{number_format($lastItem->payment,2)}}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    <td class="text-right">{{$lastItem->ornum}}</td>
                                                    <td class="text-right">	&#8369; {{number_format($lastItem->runbal,2)}}</td>
                                                </tr>

                                                @php
                                                    $withoutOR = true;
                                                @endphp

                                            @endif

                                            <tr class="text-success">
                                                <td>{{$item->particulars}}</td>
                                                @if($item->amount!=0)
                                                    <td>&#8369; {{number_format($item->amount,2)}}</td>
                                                @else
                                                    <td></td>
                                                @endif

                                                @if($item->payment!=0)
                                                    <td>	&#8369; {{number_format($item->payment,2)}}</td>
                                                @else
                                                    <td></td>
                                                @endif
                                                <td class="text-right">{{$item->ornum}}</td>
                                                <td class="text-right">	&#8369; {{number_format($item->runbal,2)}}</td>
                                            </tr>

                                            @php
                                                $runbal = $item->runbal;
                                            @endphp

                                        @else
                                            @php
                                                $lastItem = $item;
                                                $runbal = $item->runbal;
                                            @endphp
                                        @endif --}}

                                        {{-- @php
                                            $runbal = $item->runbal;
                                        @endphp --}}

                                        {{-- <tr class="text-success">
                                            <td>{{$item->particulars}}</td>
                                            @if($item->amount!=0)
                                                <td>&#8369; {{number_format($item->amount,2)}}</td>
                                            @else
                                                <td></td>
                                            @endif

                                            @if($item->payment!=0)
                                                <td>	&#8369; {{number_format($item->payment,2)}}</td>
                                            @else
                                                <td></td>
                                            @endif
                                            <td class="text-right">{{$item->ornum}}</td>
                                            <td class="text-right">	&#8369; {{number_format($item->runbal,2)}}</td>
                                        </tr>
                                        @php
                                            $runbal = $item->runbal;
                                        @endphp --}}

                                        <tr class="text-success">

                                            @if($item->ornum != null || ( $item->ornum == null && $item->classid == null ) )

                                                @php
                                                    $runbal = $runbal - $item->payment
                                                @endphp

                                                <td>{{$item->particulars}}</td>
                                                @if($item->amount!=0)
                                                    <td class="text-right">&#8369; {{number_format($item->amount,2)}}</td>
                                                @else
                                                    <td></td>
                                                @endif

                                                @if($item->payment!=0)
                                                    <td class="text-right">	&#8369; {{number_format($item->payment,2)}}</td>
                                                @else
                                                    <td></td>
                                                @endif
                                                <td class="text-right">{{$item->ornum}}</td>
                                                <td class="text-right">	&#8369; {{number_format($runbal,2)}}</td>
                                            
                                            @endif
                                        </tr>

                                @endforeach
                                
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">Billing Transaction is yet available.</td>
                                    </tr>
                                
                                @endif
                                
                               
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        @if(count($billhis) > 0)
                            <span class="text-success h4 float-right">Balance: <span>&#8369; {{number_format($runbal,2)}}</span></span>
                        @endif
                    </div>
                </div>
            </div>
            
            
          
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title"> Online Payment Transactions</h3>
                    </div>
                    <div class="card-body p-0 table-responsive p-0" id="online_payment_holder" style="height: 300px;">
                    </div>
                </div>
            </div>


        </div>
    </section>

    <script>
        $(document).ready(function(){



       
        })
    </script>


    {{-- payment receitp uploading query --}}
    <script>

        $(document).ready(function(){


            payment_selection_holder

            $(document).on('click','#upload_payment_button',function(){
                $("#upload_payment").modal();

                $.ajax({
                        type:'GET',
                        url:'/getremBill',
                        success:function(data) {

                              $('#payment_selection_holder').empty()
                              $('#payment_selection_holder').append(data)
                            
                        },
                })    

            }) 


            function readURL(input) {
                  if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        
                        reader.onload = function (e) {
                        $('#receipt').attr('src', e.target.result);
                        }
                        
                        reader.readAsDataURL(input.files[0]);
                  }
            }
            
            $("#recieptImage").change(function(){
                  readURL(this);
            });

        });

    </script>

    <script>
      
        $(document).ready(function(){

       

            $(document).on('click','#payableinfo',function(){

                  $('#payableInfo').modal();

                  $.ajax({
                        type:'GET',
                        url:'/get/payable/information/'+$('#studid').val()+'/'+$(this).attr('data-id'),
                        success:function(data) {

                              $('#payablemodalbody').empty()
                              $('#payablemodalbody').append(data)

                            
                        },
                  })    
            })

            var itemdataid
            var selectedItem

            $(document).on('click','#sumbmitQuantity',function(){

                  $('#quantityModal').modal('hide');
                  $(this).attr('disabled','disabled');

                  var itemAmount = selectedItem[0].cells[1].innerText.replace(',','')

                  totalItemAmount = itemAmount * $('#itemqa').val()

                  $('#itemQuanText'+itemdataid).text('X '+ $('#itemqa').val())

                  selectedItem[0].cells[1].innerText = totalItemAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
                  
                  selectedItem.attr('qt', $('#itemqa').val())

                  selectedItem.addClass('dassitem')
                  selectedItem.removeClass('items')

                  $('#dassess').append(selectedItem)

                  $('#itemqa').val('1')

                  calculateTotal()

                  if($('.dassitem').length == 0){

                        $('.uploadreceipt').attr('disabled','disabled')

                  }
                  else{

                        $('.uploadreceipt').removeAttr('disabled')

                  }

            })

            $(document).on('change','#paymentType',function(){

                  if($(this).val() == '3'){
                        $('#bankName').removeAttr('disabled')
                        $('#refNum').removeAttr('disabled')
                  }
                  else{
                        $('#bankName').attr('disabled','disabled')
                        $('#refNum').removeAttr('disabled')
                  }

            })

            $(document).on('click','.tselector',function(){

                  $('.tselector').each(function(){

                        $(this).removeClass('bg-success');
                        $(this).addClass('bg-danger');
                  })


                  if($(this)[0].cellIndex == 0){

                        $('#itemstable').css('display','none')
                        $('#tuitiontable').removeAttr('style','block')

                  }
                  else if($(this)[0].cellIndex == 1){
                        $('#itemstable').removeAttr('style')
                        $('#tuitiontable').css('display','none')

                  }
                  $(this).removeClass('bg-danger')
                  $(this).addClass('bg-success')

            })

            $('input[type="checkbox"]').each(function(){
                  $(this).prop('checked',false)
            })

            $(document).on('click','.selectPayable',function(e){

                  $selectedtd =  $(this).closest('tr')

                  if($selectedtd.attr('itemtype') == 'tuition'){

                        if($('input[id="ass'+$selectedtd.attr('sortid')+'"]').prop('checked') == true){

                              $selectedtd.addClass('items')
                              $selectedtd.removeClass('dassitem')

                              $('input[id="ass'+$selectedtd.attr('sortid')+'"]').prop('checked',false)

                        }else{
                              
                              $('input[id="ass'+$selectedtd.attr('sortid')+'"]').prop('checked',true)

                              $selectedtd.addClass('dassitem')
                              $selectedtd.removeClass('items')
                              
                        }

                        var tditem = $selectedtd

                        $('#tuitiontable tbody tr').each(function(a,b){

                              if(parseInt($(b).attr('sortid')) < parseInt(tditem.attr('sortid'))){
                                 
                                    $(this).addClass('dassitem')
                                    $(this).removeClass('items')

                                    $('input[id="ass'+$(this).attr('sortid')+'"]').prop('checked',true)

                              }
                              else if(parseInt($(b).attr('sortid')) > parseInt(tditem.attr('sortid'))){

                                    $(this).addClass('items')
                                    $(this).removeClass('dassitem')

                                    $('input[id="ass'+$(this).attr('sortid')+'"]').prop('checked',false)
                              }

                        })

                  }
                  else{

                        $('#selectPayable'+$(this).attr('sortid')).removeClass('selectPayable')

                        $('#sumbmitQuantity').removeAttr('disabled')
                        $('#quantityModal').modal();

                        selectedItem = $selectedtd
                        itemdataid = $(this).attr('data-id')
                  }
                  
                  if($('.dassitem').length == 0){

                        $('.uploadreceipt').attr('disabled','disabled')

                  }
                  else{

                        $('.uploadreceipt').removeAttr('disabled')

                  }

                  calculateTotal()

                  e.preventDefault()

            })

            $(document).on('click','.removePayable',function(){

                  $selectedtd =  $(this).closest('tr')

                  $selectedtd.addClass('items')
                  $selectedtd.removeClass('dassitem')

                  $($(this)).text('Select Payable')
                  $($(this)).removeClass('removePayable')
                  $($(this)).addClass('selectPayable')

                  if($selectedtd.attr('itemtype') == 'tuition'){

                        $('#tuitionbody').append($selectedtd)
                        sortTable($('#tuitiontable tbody tr'),$selectedtd)

                        var tditem = $selectedtd

                        $('#dassess tr').each(function(a,b){

                              if(parseInt($(b).attr('sortid')) > parseInt(tditem.attr('sortid'))){

                                    $(this).addClass('items')
                                    $(this).removeClass('dassitem')
                                    $('#tuitionbody').append(b)

                                    $('#selectPayable'+$(this).attr('sortid')).text('Select Payable')
                                    $('#selectPayable'+$(this).attr('sortid')).removeClass('removePayable')
                                    $('#selectPayable'+$(this).attr('sortid')).addClass('selectPayable')

                                    sortTable($('#tuitiontable tbody tr'),$(this))

                              }

                        })

                        $('#itemstable').css('display','none')
                        $('#tuitiontable').removeAttr('style','block')

                        $($('.tselector')[0]).addClass('bg-success')
                        $($('.tselector')[1]).removeClass('bg-success')


                  }
                  else{

                        var removedDot = $selectedtd[0].cells[1].innerText.replace(',','');

                        var origAmount =  parseFloat( removedDot ) / $('#itemQuanText'+itemdataid)[0].innerText.replace('X ','')

                        $selectedtd[0].cells[1].innerText = origAmount.toFixed(2)

                        $('#itembody').append($selectedtd)
                        sortTable($('#itemstable'),$selectedtd)

                        $('#tuitiontable').css('display','none')
                        $('#itemstable').removeAttr('style','block')

                        $($('.tselector')[1]).addClass('bg-success')
                        $($('.tselector')[0]).removeClass('bg-success')

                        selectedItem[0].cells[0].innerHTML += '<span id="itemQuanText'+itemdataid+'"></span>'
                  
                  }
                  
                  calculateTotal()

                  if($('.dassitem').length == 0){

                        $('.uploadreceipt').attr('disabled','disabled')

                  }
                  else{

                        $('.uploadreceipt').removeAttr('disabled')
                  }
           
                 
            })

            function calculateTotal(){

                  var total = parseFloat(0.00);

                  var summary = [];
         

                  $('.dassitem').each(function(){

                        var itemAmount = $(this)[0].cells[2].innerText.replace(',','')

                        total += parseFloat(itemAmount);

                        console.log($(this)[0].cells[2].innerText)
                        console.log($(this)[0].cells[1].innerText)

                        var info = {
                                    'des':$(this)[0].cells[1].innerText,
                                    'mount':$(this)[0].cells[2].innerText,
                                    'pointers':$(this).attr('pointers'),
                                    'qt': $(this).attr('qt')
                              }

                  })

                  if(total == 0.00){

                        $('#noitem').removeAttr('style')
                  }
                  else{

                        $('#noitem').css('display','none')
                        
                  }

                  $('#totalass')[0].innerText = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

            }

            function sortTable(table,tobeInserted) {
             
                  var inserted = false

                  table.each(function(a,b){     
                        if(!inserted){

                              if(parseInt($(b).attr('sortid')) > parseInt(tobeInserted.attr('sortid'))){

                                    $(tobeInserted).insertBefore($(b))

                                    inserted = true;

                              }
                        }
                  })
            }
        })

    </script>

    <script>

        $(document).ready(function(){

            $(document).on('click','.uploadreceipt',function(){
             
                $("#receiptInformation").modal();

                $('#amount').val($('#totalass')[0].innerText)
            
            }) 


        })
        


    </script>


    <script>
        $(document).ready(function(){

                 
            loadOnlinePayments()

            function loadOnlinePayments(){

                $.ajax({
                        type:'GET',
                        url:'/parent/onlinepayment',
                        success:function(data) {
                            $('#online_payment_holder').empty()
                            $('#online_payment_holder').append(data)
                            
                        }
                })

            }
       
        
            $(document).on('click','.view-image',function(){
                  $("#updatemodal").modal();
                  $('#modalImage').attr('src',$(this).attr('data-src'))
            }) 

            $( '#paymentInfo' )
                    .submit( function( e ) {
                       

                        $('#proceedpayment').removeClass('btn-success')
                        $('#proceedpayment').removeClass('btn-defalult')
                        $('#proceedpayment').addClass('disabled')

                        var inputs = new FormData(this)

                        var counter = 1;
                        var summary = [];
                        
                        var length = $('.dassitem').length;


                        $('.dassitem').each(function(){
                                
                                $origText = 

                                summary.push($(this)[0].cells[2].innerText.replace(',',''))
                                summary.push($(this)[0].cells[1].innerText.split('X ')[0])
                                summary.push($(this).attr('pointers'))
                                summary.push($(this).attr('qt'))
                                if(counter != length){

                                    summary.push('||')
                                    counter += 1;
                                }

                        })

                        
                        inputs.append('info',summary)
                        inputs.append('studid',$('#studid').val())

                        $.ajax( {
                                url: '/parentEnterAmount',
                                type: 'POST',
                                data: inputs,
                                processData: false,
                                contentType: false,
                                success:function(data) {

                                    if(data[0].status == 0){

                                        $('#proceedpayment').removeClass('disabled')
                                        $('#proceedpayment').removeClass('btn-default')
                                        $('#proceedpayment').addClass('btn-success')

                                        $('#bankName').removeClass('is-invalid')
                                        $('#bankName').css('display','hidden')

                                        $('#refNum').removeClass('is-invalid')
                                        $('#refNum').css('display','hidden')

                                        $.each(data[0].inputs,function(key,value){

                                        if(value != null){

                                            $('#'+key).removeClass('is-invalid')
                                            $('#'+key).css('display','hidden')

                                        }

                                        })

                                        $.each(data[0].errors,function(key,value){
                                            $('#'+key).addClass('is-invalid')
                                            $('#'+key).next('.invalid-feedback').text(value)

                                        })


                                    }
                                    else if(data[0].status == 1){

                                        $("#receiptInformation").modal('hide');

                                        Swal.fire({
                                            type: 'success',
                                            title: 'PAYMENT SUBMITTED',
                                            showConfirmButton: false,
                                            timer: 1500,
                                        })

                                        $('#paymentInfo')[0].reset()

                                        loadOnlinePayments()

                                        $.ajax({
                                            type:'GET',
                                            url:'/getremBill',
                                            success:function(data) {

                                                $('#payment_selection_holder').empty()
                                                $('#payment_selection_holder').append(data)
                                                
                                            },
                                        })  

                                    }


                                   
                                
                                },
                        } );
                        e.preventDefault();
            } );
        
        $("input[data-type='currency']").on({
            keyup: function() {
                    formatCurrency($(this));
            },
            blur: function() { 
                    formatCurrency($(this), "blur");
            }
        });
    

        function formatNumber(n) {
        // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }


        function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.
        
        // get input value
        var input_val = input.val();
        
        // don't validate empty input
        if (input_val === "") { return; }
        
        // original length
        var original_len = input_val.length;

        // initial caret position 
        var caret_pos = input.prop("selectionStart");
        
        // check for decimal
        if (input_val.indexOf(".") >= 0) {

        // get position of first decimal
        // this prevents multiple decimals from
        // being entered
        var decimal_pos = input_val.indexOf(".");

        // split number by decimal point
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);

        // add commas to left side of number
        left_side = formatNumber(left_side);

        // validate right side
        right_side = formatNumber(right_side);
        
        // On blur make sure 2 numbers after decimal
        if (blur === "blur") {
            right_side += "00";
        }
        
        // Limit decimal to only 2 digits
        right_side = right_side.substring(0, 2);

        // join number by .
        input_val =  left_side + "." + right_side;

        } else {
        // no decimal entered
        // add commas to number
        // remove all non-digits
        input_val = formatNumber(input_val);
        input_val = input_val;
        
        // final formatting
        if (blur === "blur") {
            input_val += ".00";
        }
        }
        
        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
        }

    })

    </script>











@else
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <p>Your student is not yet enrolled for {{App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->sydesc}} School Year.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif



@endsection
