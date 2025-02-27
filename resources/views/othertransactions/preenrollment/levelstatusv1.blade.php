
<style>
      fieldset.scheduler-border {
            border: 2px groove #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow:  0px 0px 0px 0px #000;
                        box-shadow:  0px 0px 0px 0px #000;
            background-color: #fbfbfb;
            min-height: 400px; 
            text-align:center;
      }
      
      legend.scheduler-border {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
            width:auto;
            padding:0 10px;
            border-bottom:none;
            background-color: #fbfbfb
      }

</style>

<style>
      .dropdown-toggle::after {
            display: none;
            margin-left: .255em;
            vertical-align: .255em;
            content: "";
            border-top: .3em solid;
            border-right: .3em solid transparent;
            border-bottom: 0;
            border-left: .3em solid transparent;
      }
</style>

<script>
      
      if($(window).width()<500){
                  $('#enrollmenttimeline').addClass('p-0')
                  $('.timeline-item').css('margin-left','0')
                  $('.timeline-item').css('margin-right','0')
                  $('.col-md-12').css('padding','0')
               
            }
            
</script>


    

<div class="modal fade" id="updatemodal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-body p-0">
                 <img id="modalImage" src="" alt="" class="w-100">
              </div>
          </div>
      </div>
</div>


<div class="modal fade" id="receiptModal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
                  <div class="modal-body" id="paymentReceipt">

                  </div>
          </div>
      </div>
</div>

<div class="modal fade" id="paymentInformation" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="ribbon-wrapper ribbon-xl">
                  <div class="ribbon bg-danger text-lg " id="paymentInfoRibbon">
                    
                  </div>
            </div>
          <div class="modal-content">
                <div class="modal-header bg-primary">
                      <h3 class="mb-0">Payment Information</h3>
                </div>
              <div class="modal-body" id="paymentInfoTable">

              </div>
          </div>
      </div>
</div>

@if($status != 3)
  <div class="modal fade" id="paymentasssesment" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="card-header">
                  Payment Assessment
            </div>
              <div class="modal-body">
                 <div class="row mt-2">
                        <div class="col-md-6 " >
                              @if($status == 1)
                                    
                                    <table class="table table-hover table-bordered mb-0">
                                          <thead>
                                                <tr>
                                                      @if($studinfo->studstatus == 1)
                                                       <td style="cursor:pointer" class="bg-success tselector" width="50%">TUITION 
                                                            ({{count($assessment)}})
                                                      </td>
                                                      @else
                                                      <td style="cursor:pointer" class="bg-success tselector" width="50%">DOWNPAYMENT 
                                                            ({{count($downpayment)}})
                                                      </td>
                                                      @endif
                                                      
                                                   
                                                      <td style="cursor:pointer" class=" bg-danger tselector tselector" width="50%">ITEMS ({{count($items)}})</td>
                                                </tr>
                                          </thead>
                                    </table>
                                    <div class="row table-responsive" style="height: 300px;">
                                          @if($studinfo->studstatus == 1)
                                                <table class="table table-hover table-head-fixed" id="tuitiontable" style="min-height:100px">
                                                      <thead>
                                                            <tr>
                                                                  <th width="60%">DESCRIPTION</th>
                                                                  <th width="35%" class="text-right">AMOUNT</th>
                                                                  <th width="5%"></th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="tuitionbody" class="bg-info">
                                                            @foreach ($assessment as $key=>$item)
                                                                  <tr 
                                                                        sortid="{{$key}}"
                                                                        style="cursor:pointer" 
                                                                        itemtype="tuition" class="items" 
                                                                        pointers="2 @if($item->duedate != null){{$item->classid}} {{$item->id}} {{\Carbon\Carbon::create($item->duedate)->isoFormat('M')}}@else{{$item->classid}} {{$item->id}} 0 @endif"
                                                                        qt = 1
                                                                  >
                                                                        <td>
                                                                              @if($item->duedate != null)
                                                                                    {{strtoupper(\Carbon\Carbon::create($item->duedate)->isoFormat('MMMM'))}}
                                                                              @endif
                                                                              {{-- {{$item->particulars}} --}}PAYABLES
                                                                        </td>
                                                                        <td class="text-right">{{number_format($item->balance, 2)}} </td>
                                                                        <td>
                                                                              <div class="dropdown">
                                                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                          <i class="fas fa-ellipsis-v"></i>
                                                                                    </button>
                                                                                    <div class="dropdown-menu text-center" aria-labelledby="dropdownMenuButton">
                                                                                          <a class="dropdown-item selectPayable" id="selectPayable{{$key}}" href="#" >
                                                                                          Select Payable</a>
                                                                                          <a class="dropdown-item" href="#" id="payableinfo" data-id="{{\Carbon\Carbon::create($item->duedate)->isoFormat('M')}}"> 
                                                                                                View Info</a>
                                                                                    </div>
                                                                              </div>
                                                                        </td>
                                                                  </tr>
                                                            @endforeach
                                                      </tbody>
                                                </table>
                                          @else
                                                <table class="table table-hover table-head-fixed" id="tuitiontable" > 
                                                      <thead>
                                                            <tr>
                                                                  <th width="60%">DESCRIPTION</th>
                                                                  <th width="35%" class="text-right">AMOUNT</th>
                                                                  <th widht="5%"></th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="tuitionbody" class="bg-info">

                                                            {{-- @php
                                                                  $countDP = 0;
                                                            @endphp
                                                            
                                                            @if(isset($balancforwarded->amount))
                                                                  @php
                                                                        $tableDP = $downpayment[0]->amount + $balancforwarded->amount;
                                                                  @endphp
                                                            @else
                                                                  @php
                                                                         $tableDP = $downpayment[0]->amount;
                                                                  @endphp
                                                            @endif --}}

                                                            @if($completeDP)

                                                                  <tr>
                                                                        <td colspan="3" class="text-center">DOWNPAYMENT IS FULLY PAID</td>
                                                                  </tr>

                                                            @else

                                                                  @if(count($downpayment) != 0)

                                                                        <tr 
                                                                              style="cursor:pointer" 
                                                                              itemtype="tuition" 
                                                                              class="items" 
                                                                              pointers="1 {{$downpayment[0]->classid}} {{$downpayment[0]->itemid}}"
                                                                              sortid="0"
                                                                              qt = 1
                                                                        >
                                                                              <td>{{$downpayment[0]->description}}</td>
                                                                              <td class="text-right">{{number_format($overAllDP - $sumsubmittedOnlineDP,2)}}</td>
                                                                              <td>
                                                                                    <div class="dropdown">
                                                                                          <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                                <i class="fas fa-ellipsis-v"></i>
                                                                                          </button>
                                                                                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                                          <a class="dropdown-item selectPayable" href="#" >Select Payable</a>
                                                                                          
                                                                                    </div>
                                                                              </td>

                                                                        </tr>
                                                                  @endif

                                                            @endif


                                                            {{-- @foreach($downpayment as $key=>$item)

                                                                  @if($countDP == 0)

                                                                        @if($item->amount > $totalDP)
                                                                  
                                                                              <tr 
                                                                                    style="cursor:pointer" 
                                                                                    itemtype="tuition" 
                                                                                    class="items" 
                                                                                    pointers="1 {{$item->classid}} {{$item->itemid}}"
                                                                                    sortid="{{$key}}"
                                                                                    qt = 1
                                                                              >
                                                                                    <td>
                                                                                          {{$item->description}}


                                                                                          @if(isset($balancforwarded->amount))
                                                                                                <br>
                                                                                                + {{strtoupper($balancforwarded->particulars)}}

                                                                                          @endif
                                                                                    
                                                                                    </td>


                                                                                    

                                                                                    @if($item->amount > $totalDP)

                                                                                    

                                                                                          <td class="text-right">{{number_format($tableDP - $totalDP, 2)}}</td>

                                                                                    @else
                                                                                          <td class="text-right">{{number_format($tableDP->amount, 2)}}</td>

                                                                                    @endif
                                                                                    
                                                                                    <td>
                                                                                          <div class="dropdown">
                                                                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                                      <i class="fas fa-ellipsis-v"></i>
                                                                                                </button>
                                                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                                                <a class="dropdown-item selectPayable" href="#" >Select Payable</a>
                                                                                              
                                                                                          </div>
                                                                                    </td>
                                                                                    
                                                                              </tr>

                                                                        @else

                                                                              <tr>
                                                                                    <td colspan="3" class="text-center">DOWNPAYMENT IS FULLY PAID</td>
                                                                              </tr>
                                                                              
                                                                        @endif
                                                                        @php
                                                                              $countDP += 1;
                                                                        @endphp
                                                                  @endif
                                                            @endforeach --}}
                                                      
                                                      </tbody>
                                                </table>
                                          @endif
                                   
                                          <table class="table table-hover table-head-fixed" id="itemstable" style="display:none">
                                                <thead>
                                                      <tr>
                                                            <td width="60%">DESCRIPTION</td>
                                                            <td width="35%" class="text-right">AMOUNT</td>
                                                            <td width="5%"></td>
                                                      </tr>
                                                </thead>
                                                <tbody  id="itembody" class="bg-info" class="bg-info">
                                                      @foreach ($items as $key=>$item)
                                                            <tr 
                                                                  style="cursor:pointer" 
                                                                  class="items"  
                                                                  pointers="1 {{$item->classid}} {{$item->id}}"
                                                                  qt = 1
                                                            >
                                                                  <td>{{$item->description}} <span id="itemQuanText{{15+$key+1}}"></span></td>
                                                                  <td class="text-right">{{number_format($item->amount, 2)}}</td>
                                                                  <td>
                                                                        <div class="dropdown">
                                                                              <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                              </button>
                                                                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                              <a data-id={{15+$key+1}}  class="dropdown-item selectPayable"  href="#" >Select Payable</a>
                                                                              {{-- <a class="dropdown-item" href="#" >View Info</a> --}}
                                                                              </div>
                                                                        </div>
                                                                  </td>
                                                            </tr>
                                                      @endforeach
                                                </tbody>
                                          </table>
                                    </div>
                              @else

                                    <table class="table table-hover table-bordered mb-0">
                                          <thead>
                                                <tr>
                                                      <td style="cursor:pointer" class="bg-success tselector" width="50%">DOWNPAYMENT ({{count($downpayment)}})</td>
                                                      <td style="cursor:pointer" class="tselector tselector" width="50%">ITEMS ({{count($items)}})</td>
                                                </tr>
                                          </thead>
                                    </table>
                                    <div class="row table-responsive" style="height: 300px;">
                                          <table class="table table-head-fixed" id="tuitiontable" >
                                                <thead>
                                                      <tr>
                                                            <td width="60%">DESCRIPTION</td>
                                                            <td width="35%" class="text-right">AMOUNT</td>
                                                            <td width="5%"></td>
                                                      </tr>
                                                </thead>
                                                <tbody id="tuitionbody" class="bg-info">

                                                      @php
                                                            $countDP = 0;
                                                      @endphp

                                                      @foreach($downpayment as $key=>$item)
                                                            @if($countDP == 0)
                                                                  @if($item->amount > $totalDP)

                                                                        <tr 
                                                                              style="cursor:pointer" 
                                                                              itemtype="tuition" 
                                                                              class="items" 
                                                                              pointers="1 {{$item->classid}} {{$item->itemid}}"
                                                                              qt = 1
                                                                        >
                                                                              <td>{{$item->description}}</td>
                                                                              
                                                                              @if($item->amount > $totalDP)

                                                                                    <td class="text-right">{{number_format($item->amount - $totalDP, 2)}}</td>
                                                                              
                                                                              @else
                                                                                    <td class="text-right">{{number_format($item->amount, 2)}}</td>

                                                                              @endif


                                                                              <td>
                                                                                    <div class="dropdown">
                                                                                          <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                                <i class="fas fa-ellipsis-v"></i>
                                                                                          </button>
                                                                                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                                          <a class="dropdown-item selectPayable" href="#" >Select Payable</a>
                                                                                          {{-- <a class="dropdown-item" href="#" >View Info</a>
                                                                                          </div> --}}
                                                                                    </div>
                                                                              </td>
                                                                        </tr>
                                                                  @else
                                                                        <tr>
                                                                              <td colspan="3" class="text-center">DOWN PAYMENT IS FULLY PAID</td>
                                                                        </tr>
                                                                        
                                                                  @endif
                                                                  @php
                                                                        $countDP += 1;
                                                                  @endphp
                                                            @endif
                                                      @endforeach
                                                </tbody>
                                          </table>
                                          
                                          <table class="table table-hover table-head-fixed" id="itemstable" style="display:none">
                                                <thead>
                                                      <tr>
                                                            <td width="60%">DESCRIPTION</td>
                                                            <td width="35%" class="text-right">AMOUNT</td>
                                                            <td width="5%"></td>
                                                      </tr>
                                                </thead>
                                                <tbody id="itembody" class="bg-info">
                                                      @foreach ($items as $key=>$item)
                                                            <tr 
                                                                  style="cursor:pointer" 
                                                                  itemtype="item" 
                                                                  class="items"  
                                                                  pointers="1 {{$item->classid}} {{$item->id}}"
                                                                  qt = 1

                                                            >
                                                                  <td>{{$item->description}} <span id="itemQuanText{{15+$key+1}}"></span></td>
                                                                  <td class="text-right">{{number_format($item->amount, 2)}}</td>
                                                                  <td>
                                                                        <div class="dropdown">
                                                                              <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                              </button>
                                                                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                              <a data-id={{15+$key+1}} class="dropdown-item selectPayable" href="#" >Select Payable</a>
                                                                              {{-- <a class="dropdown-item" href="#" >View Info</a> --}}
                                                                              </div>
                                                                        </div>
                                                                  </td>
                                                            </tr>
                                                      @endforeach
                                                </tbody>
                                          </table>
                                    </div>

                              @endif
                              <p class="text-danger text-center pt-2" style="font-size:16px"><em>Please select the items that you want to pay from TUITION or ITEMS.<br> Selected items will reflect at the right table.</em></p>
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-5">
                              <table class="table table-head-fixed  mb-0">
                                    <thead>
                                          <tr>
                                                <th colspan="3" class="bg-success">SELECTED ITEMS</th>
                                          </tr>
                                    </thead>
                              </table>
                              <div class="table-responsive" style="height:300px">
                                    <table class="table table-head-fixed table-hover mb-0">
                                          <thead>
                                                {{-- <tr>
                                                      <th colspan="3" class="bg-success">SELECTED ITEMS</th>
                                                </tr> --}}
                                                <tr>
                                                      <th>ITEM ( <span id="itemCount">0</span> )</th>
                                                      <th class="text-right">AMOUNT</th>
                                                      <th></th>
                                                </tr>
                                          </thead>
                                          <tbody id="dassess" class="bg-info">
                                                <tr>
                                                      <td class="text-center align-middle" colspan="3" id="noitem" >NO ITEM SELECTED</td>   
                                                </tr>
                                          </tbody>
                                         
                                          
                                    </table>
                              </div>
                              <table class="table">
                                    <tfoot class="bg-danger" >
                                          <tr>
                                                <td style="font-size:21px">TOTAL</td>
                                                <td class="text-right" id="totalass" style="font-size:21px">0.00</td>
                                          </tr>
                                    </tfoot>
                              </table>
                             
                              <button class="btn btn-success uploadreceipt" disabled>Upload Payment Receipt</button>
                              <button type="button" class="btn btn-secondary"  data-dismiss="modal">Cancel</button>
                        </div>
                       
                        
                 </div>
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
                                                      @foreach(DB::table('paymenttype')->where('isonline','1')->get() as $item)
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
      <div class="modal fade" id="quantityModal" tabindex="-1" role="dialog"  style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                  <div class="modal-content">
                        <div class="modal-header bg-success">
                              <h3 class="mb-0">ITEM QUANTITY</h3>
                        </div>
                        <div class="modal-body">
                              <div class="form-group">
                                    <label for="itemqa">PLEASE SPECIFY ITEM QUANTITY</label>
                                    <input  id="itemqa" class="form-control form-md form-control-lg" value="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" > 
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-success" id="sumbmitQuantity">
                                    SUBMIT QUANTITY
                              </button>
                        </div>
                  </div>
            </div>
      </div>

      <div class="modal fade" id="payableInfo" tabindex="-1" role="dialog"  style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-header bg-success">
                              <h3 class="mb-0">Payable Info</h3>
                        </div>
                        <div class="modal-body" id="payablemodalbody">
                             
                        </div>
                        <div class="modal-footer">
                              <button type="button" class="btn btn-secondary"  data-dismiss="modal">Close</button>
                        </div>
                  </div>
            </div>
      </div>

@endif


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

            $(document).on('click','.selectPayable',function(){

                  $selectedtd =  $(this).closest('tr')

                

                  $($(this)).text('Remove Payable')
                  $($(this)).removeClass('selectPayable')
                  $($(this)).addClass('removePayable')

                  if($selectedtd.attr('itemtype') == 'tuition'){

                        $selectedtd.addClass('dassitem')
                        $selectedtd.removeClass('items')

                        $('#dassess').append($selectedtd)

                        var tditem = $selectedtd

                        $('#tuitiontable tbody tr').each(function(a,b){

                              if(parseInt($(b).attr('sortid')) < parseInt(tditem.attr('sortid'))){

                                    $(this).addClass('dassitem')
                                    $(this).removeClass('items')
                                 
                                    $('#selectPayable'+$(this).attr('sortid')).removeClass('selectPayable')
                                    $('#selectPayable'+$(this).attr('sortid')).text('Remove Payable')
                                    $('#selectPayable'+$(this).attr('sortid')).addClass('removePayable')
                                    sortTable($('#dassess tr'),$(this))

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

                        // $selectedtd[0].children[0].children[0].remove()
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

                  // console.log($('.dassitem').length)

                  if($('.dassitem').length == 0){

                        $('.uploadreceipt').attr('disabled','disabled')

                  }
                  else{

                        $('.uploadreceipt').removeAttr('disabled')
                  }
           
                 
            })

            // $(document).on('click','.items',function(){


            //       if($(this).attr('itemtype') == 'tuition'){

            //             $(this).addClass('dassitem')
            //             $(this).removeClass('items')

            //             $('#dassess').append($(this))

            //             var tditem = $(this)

            //             $('#tuitiontable tbody tr').each(function(a,b){

            //                   if(parseInt($(b).attr('sortid')) < parseInt(tditem.attr('sortid'))){

            //                         $(this).addClass('dassitem')
            //                         $(this).removeClass('items')
            //                         sortTable($('#dassess tr'),$(this))

                                   

            //                   }

            //             })

            //       }
            //       else{

            //             $('#quantityModal').modal();
            //             selectedItem = $(this)
            //       }
                  
            //       if($('.dassitem').length == 0){

            //             $('.uploadreceipt').attr('disabled','disabled')

            //       }
            //       else{

            //             $('.uploadreceipt').removeAttr('disabled')

            //       }
            //       calculateTotal()
                  
            // })

            // $(document).on('click','.dassitem',function(){

            //       $(this).addClass('items')
            //       $(this).removeClass('dassitem')

            //       if($(this).attr('itemtype') == 'tuition'){

            //             $('#tuitionbody').append($(this))
            //             sortTable($('#tuitiontable tbody tr'),$(this))

            //             var tditem = $(this)

            //             $('#dassess tr').each(function(a,b){

            //                   if(parseInt($(b).attr('sortid')) > parseInt(tditem.attr('sortid'))){

            //                         $(this).addClass('items')
            //                         $(this).removeClass('dassitem')
            //                         $('#tuitionbody').append(b)
            //                         sortTable($('#tuitiontable tbody tr'),$(this))

            //                   }

            //             })

            //             $('#itemstable').css('display','none')
            //             $('#tuitiontable').removeAttr('style','block')

            //             $($('.tselector')[0]).addClass('bg-success')
            //             $($('.tselector')[1]).removeClass('bg-success')


            //             // $($('.tselector')[0]).removeClass('bg-danger')
            //             // $($('.tselector')[0]).addClass('bg-success')

                      

            //       }
            //       else{

            //             var origAmount =  parseFloat( $(this)[0].cells[1].innerText ) / $(this)[0].children[0].children[0].innerText.replace('X ','')

            //             $(this)[0].children[0].children[0].remove()
            //             $(this)[0].cells[1].innerText = origAmount

            //             $('#itembody').append($(this))
            //             sortTable($('#itemstable'),$(this))

            //             $('#tuitiontable').css('display','none')
            //             $('#itemstable').removeAttr('style','block')

            //             $($('.tselector')[1]).addClass('bg-success')
            //             $($('.tselector')[0]).removeClass('bg-success')

            //             // $($('.tselector')[1]).removeClass('bg-danger')
            //             // $($('.tselector')[0]).addClass('bg-danger')

                  
            //       }
                  

            //       calculateTotal()

            //       if($('.dassitem').length == 0){

            //             $('.uploadreceipt').attr('disabled','disabled')

            //       }
            //       else{

            //             $('.uploadreceipt').removeAttr('disabled')
            //       }
           
                 
            // })

            function calculateTotal(){

                  var total = parseFloat(0.00);

                  var summary = [];
         

                  $('.dassitem').each(function(){

                        var itemAmount = $(this)[0].cells[1].innerText.replace(',','')

                        total += parseFloat(itemAmount);
                   
                        var info = {
                                    'des':$(this)[0].cells[1].innerText,
                                    'mount':$(this)[0].cells[0].innerText,
                                    'pointers':$(this).attr('pointers'),
                                    'qt': $(this).attr('qt')
                              }


                  })


                  $('#itemCount')[0].innerText = $('.dassitem').length

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

                              // console.log($(b).attr('sortid') > tobeInserted.attr('sortid'))

                              if(parseInt($(b).attr('sortid')) > parseInt(tobeInserted.attr('sortid'))){

                                    $(tobeInserted).insertBefore($(b))

                                    inserted = true;

                              }
                        }

                       

                  })

                  
            }

            



        })
  </script>

@if($status == 3)

      <div class="card">
            <div class="card-header bg-danger">
                  INFORMATION NOT FOUND!
            </div>
            <div class="card-body" >
                  <p>You may visit the following links:</p>
                  <ul>
                        <li><a href="/coderecovery">Registration Code Recovery</a></li>
                        <li><a href="/eyJpdiI6IkQwSmdpaVc1K0ltNTZDbzl4SFJoSmc9PSIsInZhbHVlIjoiM0lrUDNrQTFcLzhsYjNZQzdhQmd0TUE9PSIsIm1hYyI6ImVmOTNkMDVkODNmZjM1ZDc4NzI5MThiNzdiMDkwZjdkNGJkYzdkYjM4NTIxMjQ0NTY1ZTc3OWFkN2IwMjk2ZGYifQ==">New Student / Pre-Registration</a></li>
                       
                  </ul>
            </div>
      </div>

@elseif($status == 2)
{{-- preregisration downpayment --}}
      <div class="row">
            <div class="col-md-12" id="enrollmenttimeline">
                  <div class="timeline">
                        <div>
                              <i class="fas bg-blue">1</i>
                              <div class="timeline-item">
                                    <h3 class="timeline-header bg-primary">PRE-REGISTRATION</h3>
                                    <div class="timeline-body">
                                    <p class="pl-3 m-0">Pre-registration has been submitted last 
                                          {{\Carbon\Carbon::create($prereg->date_created)->isoFormat('MMM DD, YYYY hh:mm A')}}
                                    </p>
                                    </div>
                              </div>
                        </div>
                        
                              <div>
                                    <i class="fas bg-primary">2</i>
                                    <div class="timeline-item">
                                    <h3 class="timeline-header bg-primary">ONLINE PAYMENT(S)</h3>
                                          <div class="timeline-body table-responsive" style="height:400px">
                                                @if($countPending == 0)
                                                      @if(count($downpayment)  != 0 )

                                                            <p class="p-2 mb-0">To complete the pre-enrollment process, you have to pay the downpayment amounting </p>
                                                            
                                                            
                                                           
                                                      
                                                            <span class="text-danger pl-1 pr-1 text-xl" >&#8369; {{number_format($downpayment[0]->amount, 2)}}</span>
                                              
                                                      
                                                            @if($downpayment[0]->allowless == 0)
                                                                  <p class="text-success text-lg text-center"><em>Please be reminded that you are required to pay the exact amount or more to complete pre-enrollment.</em></p>
                                                            @endif

                                                            <p class="p-2 mt-4">
                                                                  <span class="badge badge-primary text-lg"><a href="#" class="payassbutton text-white">Click here</a> </span>
                                                                  to upload payment receipt.</p>


                                                            <p class="p-2">You can pay to any partnered banks, GCash or Palawan as you payment options or visit your school cashier for payment. See <a href="#paymentoptions">payment options</a> below</p>

                                                           

                                                      @else

                                                            <p class="text-center">Payment upload is not yet available.</p>
                                                            
                                                      @endif
                                                @else
                                                      @if(count($downpayment) != 0)

                                                            @if($downpayment[0]->amount > $totalDP)

                                                                  <p class="p-2 mb-0">To complete the pre-enrollment process, you have to pay the downpayment amounting </p>
                                                                  
                                                                  <span class="text-danger pl-1 pr-1 text-xl" >&#8369; {{number_format($downpayment[0]->amount, 2)}}</span>

                                                            @endif

                                                      @endif
                                                      <table class="table table-head-fixed" style="min-width:500px">
                                                            <thead>      
                                                                  <tr>
                                                                        <th width="30%" class="text-center">PAYMENT RECIEPT</th>
                                                                        <th width="20%">AMOUNT</th>
                                                                        <th width="20%">STATUS</th>
                                                                        <th width="25%">DATE UPLOADED</th>
                                                                        <th width="5%"></th>
                                                                  </tr>
                                                            </thead>
                                                            <tbody>
                                                                  @foreach ($onlinepayment as $item)
                                                                        <tr>
                                                                              <td class="imagereceipt text-center"><img src="{{asset($item->picUrl)}}" style="max-width:80px; cursor: pointer;" class="imagereceipt"></td>
                                                                              <td class="align-middle">&#8369; {{number_format($item->amount,2)}}</td>
                                                                              <td class="align-middle">
                                                                                    @if($item->isapproved == 0)
                                                                                          <span class="badge badge-danger">On process</span> 
                                                                                    @elseif($item->isapproved == 1)
                                                                                          <span class="badge badge-success">Approved</span> 
                                                                                    @elseif($item->isapproved == 3)
                                                                                          <span class="badge badge-danger">Canceled</span> 
                                                                                    @elseif($item->isapproved == 2)
                                                                                          <span class="badge badge-danger">Not approved</span>{{$item->remarks}}
                                                                                    @elseif($item->isapproved == 5)
                                                                                          <span class="badge badge-success">Paid</span> 
                                                                                    @endif
                                                                              </td>
                                                                              <td class="align-middle">{{\Carbon\Carbon::create($item->paymentDate)->isoFormat('MMM DD, YYYY hh:mm A')}}</td>
                                                                              <td class="align-middle">
                                                                                    <div class="dropdown">
                                                                                          <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                                <i class="fas fa-ellipsis-v"></i>
                                                                                          </button>
                                                                                          <div class="dropdown-menu text-center" aria-labelledby="dropdownMenuButton">

                                                                                                @if($item->isapproved == 0 )
                                                                                                      <a class="dropdown-item" href="#" id="cancelPayment" data-id="{{$item->id}}">
                                                                                                      Cancel Payment</a>
                                                                                                @endif

                                                                                                @if($item->isapproved == 5)
                                                                                                      <a class="dropdown-item" href="#" id="viewpPaymentReciept" data-id="{{$item->id}} {{$item->chrngtransid}}">
                                                                                                      View Payment Reciept</a>
                                                                                                @endif

                                                                                                <a class="dropdown-item" href="#" id="viewPaymentInfo" data-id="{{$item->id}}" > 
                                                                                                      View Payment Info</a>
                                                                                          </div>
                                                                                    </div>
                                                                              </td>
                                                                        </tr>
                                                                  @endforeach
                                                            </tbody>
                                                      </table>
                                                @endif
                                          </div>
                                          @if($countPending > 0)
                                                <div class="timeline-footer">
                                                      @if(count($downpayment)  != 0 )
                                                            {{-- <p><a href="#" class="payassbutton">Click here</a> to upload more payment receipt. See <a href="#paymentoptions">payment options</a> below.</p> --}}
                                                            <p class="p-2 mt-4">
                                                                  <span class="badge badge-primary text-lg"><a href="#" class="payassbutton text-white">Click here</a> 
                                                                  </span>
                                                                  to upload payment receipt.
                                                            </p>
                                                            <p>See <a href="#paymentoptions">payment options</a> below.</p>
                                                      @endif
                                                      Your payment is being processed. Submitted payments will be processed for 2(two) working days.
                                                </div>
                                          @endif
                                    </div>
                                    
                              </div>
                              <div>
                                    <i class="fas bg-primary">2</i>
                                    <div class="timeline-item">
                                    <h3 class="timeline-header bg-primary">WAITING FOR PRE-REGISTRATION</h3>
                                    
                                    </div>
                                    
                              </div>
                              <div>
                                    <i class="fas fa-info bg-info"></i>
                                    <div class="timeline-item">
                                          <h3 class="timeline-header bg-info">TUITION SUMMARY</h3>
                                          <div class="timeline-body">
                                                <table class="table">
                                                     <thead>
                                                           <tr>
                                                                 <td>TUITION ITEM</td>
                                                                 <td class="text-right">AMOUNT</td>
                                                           </tr>
                                                     </thead>
                                                     <tbody>
                                                           @foreach($getpayables as $item)
                                                            <tr>
                                                                  <td>{{$item->description}}</td>
                                                                  <td class="text-right">&#8369; {{number_format($item->amount, 2)}}</td>
                                                            </tr>
                                                           @endforeach
                                                           <tr class="bg-info">
                                                                  <td>TOTAL</td>
                                                                  <td class="text-right" style="font-size:20px">&#8369; {{number_format(collect($getpayables)->sum('amount'), 2)}}</td>
                                                            </tr>
                                                     </tbody>
                                                </table>
                                               
                                          </div>
                                    </div>
                                    
                              </div>
                              {{-- <div>
                                    <i class="fas fa-info bg-info"></i>
                                    <div class="timeline-item" id="paymentoptions">
                                          <h3 class="timeline-header bg-info">PAYMENT OPTIONS</h3>
                                          <div class="timeline-body">
                                                <ul style="list-style-type: none;" class="mt-2 mb-4">
                                                      <li>
                                                            <img width="60" src="{{asset('paymentlogos/bpi.png')}}">
                                                            <ul class="mt-2">
                                                                  <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                                                  <li>Account Number: 3056 3665</li>
                                                            </ul>
                                                      </li>
                                                      <li class="mt-3">
                                                            <img width="60" src="{{asset('paymentlogos/landbank.png')}}">
                                                            <ul class="mt-2">
                                                                  <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                                                  <li>Account Number: 3607-0057-34</li>
                                                            </ul>
                                                      </li>
                                                      <li class="mt-3">
                                                            <img width="60" src="{{asset('paymentlogos/bdo.png')}}">
                                                            <ul class="mt-2">
                                                                  <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                                                  <li>Account Number: 0003800278949</li>
                                                            </ul>
                                                      </li>
                                                      <li class="mt-3">
                                                            <img width="60" src="{{asset('paymentlogos/chinabank.png')}}">
                                                            <ul>
                                                                  <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                                                  <li>Account Number: 128900000452</li>
                                                            </ul>
                                                      </li>
                                                      <li class="mt-3">
                                                            <img width="60" src="{{asset('paymentlogos/gcash.png')}}">
                                                            <ul class="mt-2">
                                                                  <li>GCASH Mobile Number: 0928-168-0536</li>
                                                            </ul>
                                                      </li>
                                                </ul>
                                          </div>
                                    </div>
                                    
                              </div> --}}
                        </div>
                  </div>
            </div>
      </div>
     
                        
           











      {{-- <div class="card">
            <div>
                  <i class="fas fa-user bg-blue"></i>
                  <div class="timeline-item">
                        <h3 class="timeline-header bg-primary">PRE-REGISTRATION FOUND</h3>
                        <div class="timeline-body">
                              <div class="row">
                                    <div class="col-md-6">
                                          <div class="form-group">
                                                <label>CURRENT GRADE LEVEL</label>
                                                <input class="form-control" readonly placeholder="{{$studinfo->levelname}}">
                                          </div>
                                    </div>
                                    <div class="col-md-6">
                                          <div class="form-group">
                                                <label>ENROLLMENT STATUS</label>
                                                <input class="form-control" readonly placeholder="{{$studinfo->description}}">
                                          </div>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-3">
                                          <div class="icheck-success d-inline">
                                                @if($studinfo->preEnrolled == 1)
                                                      <input type="checkbox" id="preenrolled" checked>
                                                @else
                                                      <input type="checkbox" id="preenrolled">
                                                @endif
                                          <label for="preenrolled">PRE-ENROLLED
                                          </label>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            
      </div>

      <div class="card">

            @if(count($onlinepayment)==0)

            <div class="card-header">
                Uploaded payment receipt
            </div>
                  <div class="card-body">
                  
                        <h3>No payment found!</h3> Please upload a payment receipt to this link <a href="/payment/paymentinformation">{{Request::root()}}/payment/paymentinformation</a> to complete pre-registration.
                        
                        <hr>
                        <div class="row">
                              <h4 class="underlined">Payment Options:</h4>
                        </div>
                  
                        <ul style="list-style-type: none;" class="mt-2 mb-4">
                        <li>
                              <img width="60" src="{{asset('paymentlogos/bpi.png')}}">
                              <ul class="mt-2">
                                    <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                    <li>Account Number: 3056 3665</li>
                              </ul>
                        </li>
                        <li class="mt-3">
                              <img width="60" src="{{asset('paymentlogos/landbank.png')}}">
                              <ul class="mt-2">
                                    <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                    <li>Account Number: 3607-0057-34</li>
                              </ul>
                        </li>
                        <li class="mt-3">
                              <img width="60" src="{{asset('paymentlogos/bdo.png')}}">
                              <ul class="mt-2">
                                    <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                    <li>Account Number: 0003800278949</li>
                              </ul>
                        </li>
                        <li class="mt-3">
                              <img width="60" src="{{asset('paymentlogos/chinabank.png')}}">
                              <ul>
                                    <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                    <li>Account Number: 128900000452</li>
                              </ul>
                        </li>
                        <li class="mt-3">
                              <img width="60" src="{{asset('paymentlogos/gcash.png')}}">
                              <ul class="mt-2">
                                    <li>GCASH Mobile Number: 0928-168-0536</li>
                              </ul>
                        </li>
                        </ul>
                  </div>
            </div>
            @else
                  @foreach ($onlinepayment as $item)
                        <tr>
                              <td><img src="{{asset($item->picUrl)}}" style="max-height:80px; cursor: pointer;" class="imagereceipt"></td>
                              <td class="align-middle">{{$item->amount}}</td>
                              <td class="align-middle">
                                    @if($item->isapproved == 0)
                                          <span class="badge badge-danger">On process</span> 
                                    @elseif($item->isapproved == 1)
                                          <span class="badge badge-success">Approved</span> 
                                    @elseif($item->isapproved == 5)
                                          <span class="badge badge-success">Paid</span> 
                                    @endif
                              </td>
                              <td class="align-middle">{{\Carbon\Carbon::create($item->paymentDate)->isoFormat('MMM DD, YYYY hh:mm A')}}</td>
                        </tr>
                  @endforeach
            @endif
      </div> --}}


@elseif($status == 1)

      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
            <div class="row">
                  <div class="col-md-12" id="enrollmenttimeline">
                        <div class="timeline">

                        @if(  $studinfo->studstatus == 1 && $studinfo->preEnrolled  == 0 && $isEnrolled)
                              <div>
                                    <i class="fas fa-info bg-success-perschool"></i>
                                    <div class="timeline-item">
                                          <h3 class="timeline-header bg-success-perschool">CONGRATULATIONS! YOU ARE NOW ENROLLED!</h3>
                                          <div class="timeline-body table-responsive">
                                                <table class="table">
                                                      <thead>
                                                            <tr>
                                                                  <th colspan="2">ENROLLMENT INFORMATION</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
                                                            <tr>
                                                                  <td>GRADE LEVEL:</td>
                                                                  <td>{{$enrollmentDetail->levelname}}</td>
                                                            </tr>
                                                            <tr>
                                                                  <td>SECTION: </td>
                                                                  <td>{{$enrollmentDetail->sectionname}}</td>
                                                            </tr>
                                                            <tr>
                                                                  <td>SCHOOL YEAR: </td>
                                                                  <td>{{$enrollmentDetail->sydesc}}</td>
                                                            </tr>
                                                      </tbody>
                                                      <tfoot>
                                                            <tr>
                                                                  <td colspan="2">
                                                                        Please review enrollment information. Contact or visit the office of principal or registrar for more information about your enrollment.
                                                                  </td>
                                                            </tr>
                                                      </tfoot>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        @endif

                        
                        @if($studinfo->preEnrolled == 0 && $studinfo->studstatus == 0 && !$isEnrolled)
                                    <div>
                                          <i class="fas bg-primary">1</i>
                                          <div class="timeline-item">
                                                <h3 class="timeline-header bg-danger">PRE-ENROLLMENT</h3>
                                                <div class="timeline-body">
                                                      <p>Pre-enrollment is not yet submitted.</p>

                                                      <p>  <span class="badge badge-primary text-lg"><a target="_blank" href="/preregv2" class="text-white">Click here</a> 
                                                      </span> button to submit pre-enrollment.</p> 
                                                
                                                </div>
                                          </div>
                                    </div>
                                    <script>
                                          $(document).ready(function(){
                                                $(document).on('click','#preenrollproccess',function(){
                                                      $.ajax({
                                                            type:'GET',
                                                            url:'/preenrollment/process/S'+'{{$studinfo->sid}}'+'/'+'{{Str::slug($studinfo->firstname.'-pe-'.$studinfo->lastname)}}',
                                                            success:function(data) {
                                                                  if(data == 1){
                                                                        $.ajax({
                                                                              type:'GET',
                                                                              url:'/preenrollment/evaluate/form',
                                                                              data:{
                                                                                    a:$("#studid").val(),
                                                                                    b:$("#firstname").val(),
                                                                                    c:$("#lastname").val(),
                                                                              },
                                                                              success:function(data) {
                                                                                    $('#results').empty();
                                                                                    $("#results").html(data);

                                                                                    
                                                                              },
                                                                        })
                                                                  }
                                                            },
                                                      })
                                                })
                                          })
                                    </script>

                        @elseif($studinfo->preEnrolled == 1 && $studinfo->studstatus == 0 && !$isEnrolled)
                              <div>
                                    <i class="fas bg-primary">1</i>
                                    <div class="timeline-item">
                                          <h3 class="timeline-header bg-primary">PRE-ENROLLMENT</h3>
                                          <div class="timeline-body">
                                                <p class="m-0">Pre-enrollment has been submitted.</p>
                                          </div>
                                    </div>
                              </div>
                        @endif


                        <div>
                              <i class="fas bg-primary">2</i>
                                    <div class="timeline-item">
                                    <h3 class="timeline-header bg-primary">ONLINE PAYMENT(S)</h3>
                             
                                    <div class="timeline-body  p-0" >


                                          @if(count($downpayment) == 0)

                                                <p class="text-center">Payment upload is not yet available.</p>


                                          @else

                                                @if(!$completeDP && $studinfo->preEnrolled == 1 )

                                                      <p class="p-2 mb-0">To complete the pre-enrollment process, you have to pay the downpayment amounting </p>

                                                      <span class="text-danger pl-1 pr-1 text-xl" >&#8369; {{number_format($overAllDP - $sumsubmittedOnlineDP, 2)}}</span>

                                                      @if(isset($balancforwarded->amount))
                                                            <div class="row mt-2 p-2">
                                                                  <table class="table">
                                                                        <tr>
                                                                              <th>Description</th>
                                                                              <th class="text-right">Amount</th>
                                                                        </tr>
                                                                        <tr>
                                                                              <td>{{$balancforwarded->particulars}}</td>
                                                                              <td class="text-right">{{number_format($balancforwarded->amount, 2)}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                              <td>{{$downpayment[0]->description}}</td>
                                                                              <td class="text-right">{{number_format($downpayment[0]->amount, 2)}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                              <td>TOTAL</td>
                                                                              <td class="text-right">{{number_format($overAllDP, 2)}}</td>
                                                                        </tr>
                                                                  </table>
                                                            </div>
                                                      @endif

                                                      @if($downpayment[0]->allowless == 0)
                                                            <p class="text-success text-lg text-center"><em>Please be reminded that you are required to pay the exact amount or more to complete pre-enrollment.</em></p>
                                                      @endif

                                                @elseif($studinfo->preEnrolled == 0 && !$isEnrolled)
                                                     
                                                      <p class="p-3">Please submit pre-enrollment request to continue with payment</p>

                                                @endif

                                                @if($studinfo->preEnrolled == 1 || $studinfo->studstatus == 1 || $isEnrolled)   

                                                      <div class="row table-responsive" style="height:400px">
                                                            <table class="table table-head-fixed" style="min-width:500px">
                                                                  <thead>      
                                                                        <tr>
                                                                              <th width="30%" class="text-center">PAYMENT RECIEPT</th>
                                                                              <th width="20%">AMOUNT</th>
                                                                              <th width="20%">STATUS</th>
                                                                              <th width="25%">DATE UPLOADED</th>
                                                                              <th width="5%"></th>
                                                                        </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                        @foreach ($onlinepayment as $item)
                                                                              <tr>
                                                                                    <td class="imagereceipt text-center"><img src="{{asset($item->picUrl)}}" style="max-width:80px; cursor: pointer;" class="imagereceipt"></td>
                                                                                    <td class="align-middle">&#8369; {{number_format($item->amount,2)}}</td>
                                                                                    <td class="align-middle text-center">
                                                                                          @if($item->isapproved == 0)
                                                                                                <span class="badge badge-danger">On process</span> 
                                                                                          @elseif($item->isapproved == 1)
                                                                                                <span class="badge badge-success">Approved</span> 
                                                                                          @elseif($item->isapproved == 3)
                                                                                                <span class="badge badge-danger">Canceled</span> 
                                                                                          @elseif($item->isapproved == 5)
                                                                                                <span class="badge badge-success">Paid</span>
                                                                                          @elseif($item->isapproved == 2)
                                                                                                <span class="badge badge-danger">Not approved</span>{{$item->remarks}}
                                                                                          @endif
                                                                                    </td>
                                                                                    <td class="align-middle">{{\Carbon\Carbon::create($item->paymentDate)->isoFormat('MMM DD, YYYY hh:mm A')}}</td>
                                                                                    <td class="align-middle">
                                                                                          <div class="dropdown">
                                                                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                                      <i class="fas fa-ellipsis-v"></i>
                                                                                                </button>
                                                                                                <div class="dropdown-menu text-center" aria-labelledby="dropdownMenuButton">
                                                                                                      @if($item->isapproved == 0 )
                                                                                                            <a class="dropdown-item" href="#" id="cancelPayment" data-id="{{$item->id}}">
                                                                                                            Cancel Payment</a>
                                                                                                      @endif
                                                                                                      @if($item->isapproved == 5)
                                                                                                            <a class="dropdown-item" href="#" id="viewpPaymentReciept" data-id="{{$item->id}} {{$item->chrngtransid}}">
                                                                                                            View Payment Reciept</a>
                                                                                                      @endif
                                                                                                      <a class="dropdown-item" href="#" id="viewPaymentInfo" data-id="{{$item->id}}" > 
                                                                                                            View Payment Info</a>
                                                                                                </div>
                                                                                          </div>
                                                                                    </td>
                                                                              </tr>
                                                                        @endforeach
                                                                  </tbody>
                                                            </table>
                                                      </div>
                                               @endif

                                          @endif

                                          {{-- @if($countPending == 0 && $studinfo->studstatus == 0)
                                     
                                                @if($studinfo->preEnrolled == 1)

                                                      @if(count($downpayment) != 0)

                                                                  <p class="p-2 mb-0">To complete the pre-enrollment process, you have to pay the downpayment amounting </p>
                                                                  @if(isset($balancforwarded->amount ))
                                                                        @php
                                                                              $totalDPSum = $downpayment[0]->amount + $balancforwarded->amount;
                                                                        @endphp

                                                                        <span class="text-danger pl-1 pr-1 text-xl" >&#8369; {{number_format($totalDPSum, 2)}}</span>
                                                                        <div class="row mt-2 p-2">
                                                                              <label for="">Payment Details</label>
                                                                              <table class="table">
                                                                                    <tr>
                                                                                          <th>Description</th>
                                                                                          <th class="text-right">Amount</th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                          <td>{{$balancforwarded->particulars}}</td>
                                                                                          <td class="text-right">{{number_format($balancforwarded->amount, 2)}}</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                          <td>{{$downpayment[0]->description}}</td>
                                                                                          <td class="text-right">{{number_format($downpayment[0]->amount, 2)}}</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                          <td>TOTAL</td>
                                                                                          <td class="text-right">{{number_format($totalDPSum, 2)}}</td>
                                                                                    </tr>
                                                                              </table>
                                                                        </div>
                                                                  @else
                                                                        <span class="text-danger pl-1 pr-1 text-xl" >&#8369; {{number_format($downpayment[0]->amount, 2)}}</span>

                                                                  @endif

                                                                  

                                                                  @if($downpayment[0]->allowless == 0)
                                                                        <p class="text-success text-lg text-center"><em>Please be reminded that you are required to pay the exact amount or more to complete pre-enrollment.</em></p>
                                                                  @endif
                                                      @else
                                                            <p class="text-center">Payment upload is not yet available.</p>
                                                      @endif
                                                @else

                                                      <p class="p-3">Please submit pre-enrollment request to continue with payment</p>

                                                @endif

                                          @else
                                       
                                                @if(count($downpayment) != 0)

                                                      @php
                                                            $totalDPwithBalFor = $downpayment[0]->amount;
                                                      @endphp

                                                      @if(isset($balancforwarded->amount))

                                                            @php
                                                                  $totalDPwithBalFor = $downpayment[0]->amount + $balancforwarded->amount;
                                                            @endphp

                                                      @endif

                                                      @if($totalDPwithBalFor > $totalDP)

                                                            <p class="p-2 mb-0">To complete the pre-enrollment process, you have to pay the downpayment amounting </p>

                                                            @if(isset($balancforwarded->amount ))

                                                                  @php
                                                                        $totalDPSum = $downpayment[0]->amount + $balancforwarded->amount;
                                                                  @endphp

                                                                  <span class="text-danger pl-1 pr-1 text-xl" >&#8369; {{number_format($totalDPSum, 2)}}</span>
                                                                  <div class="row mt-2 p-2">
                                                                        <label for="">Payment Details</label>
                                                                        <table class="table">
                                                                              <tr>
                                                                                    <th>Description</th>
                                                                                    <th class="text-right">Amount</th>
                                                                              </tr>
                                                                              <tr>
                                                                                    <td>{{$balancforwarded->particulars}}</td>
                                                                                    <td class="text-right">{{number_format($balancforwarded->amount, 2)}}</td>
                                                                              </tr>
                                                                              <tr>
                                                                                    <td>{{$downpayment[0]->description}}</td>
                                                                                    <td class="text-right">{{number_format($downpayment[0]->amount, 2)}}</td>
                                                                              </tr>
                                                                              <tr>
                                                                                    <td>TOTAL</td>
                                                                                    <td class="text-right">{{number_format($totalDPSum, 2)}}</td>
                                                                              </tr>
                                                                        </table>
                                                                  </div>
                                                            @else
                                                                  <span class="text-danger pl-1 pr-1 text-xl" >&#8369; {{number_format($downpayment[0]->amount, 2)}}</span>
                                                            @endif

                                                      @endif

                                                @endif
                                                
                                                <table class="table table-head-fixed" style="min-width:500px">
                                                      <thead>      
                                                            <tr>
                                                                  <th width="30%" class="text-center">PAYMENT RECIEPT</th>
                                                                  <th width="20%">AMOUNT</th>
                                                                  <th width="20%">STATUS</th>
                                                                  <th width="25%">DATE UPLOADED</th>
                                                                  <th width="5%"></th>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
                                                            @if(count($onlinepayment))
                                                                  @foreach ($onlinepayment as $item)
                                                                        <tr>
                                                                              <td class="imagereceipt text-center"><img src="{{asset($item->picUrl)}}" style="max-width:80px; cursor: pointer;" class="imagereceipt"></td>
                                                                              <td class="align-middle">&#8369; {{number_format($item->amount,2)}}</td>
                                                                              <td class="align-middle text-center">
                                                                                    @if($item->isapproved == 0)
                                                                                          <span class="badge badge-danger">On process</span> 
                                                                                    @elseif($item->isapproved == 1)
                                                                                          <span class="badge badge-success">Approved</span> 
                                                                                    @elseif($item->isapproved == 3)
                                                                                          <span class="badge badge-danger">Canceled</span> 
                                                                                    @elseif($item->isapproved == 5)
                                                                                          <span class="badge badge-success">Paid</span>
                                                                                    @elseif($item->isapproved == 2)
                                                                                          <span class="badge badge-danger">Not approved</span>{{$item->remarks}}
                                                                                    @endif
                                                                              </td>
                                                                              <td class="align-middle">{{\Carbon\Carbon::create($item->paymentDate)->isoFormat('MMM DD, YYYY hh:mm A')}}</td>
                                                                              <td class="align-middle">
                                                                                    <div class="dropdown">
                                                                                          <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                                <i class="fas fa-ellipsis-v"></i>
                                                                                          </button>
                                                                                          <div class="dropdown-menu text-center" aria-labelledby="dropdownMenuButton">
                                                                                                @if($item->isapproved == 0 )
                                                                                                      <a class="dropdown-item" href="#" id="cancelPayment" data-id="{{$item->id}}">
                                                                                                      Cancel Payment</a>
                                                                                                @endif
                                                                                                @if($item->isapproved == 5)
                                                                                                      <a class="dropdown-item" href="#" id="viewpPaymentReciept" data-id="{{$item->id}} {{$item->chrngtransid}}">
                                                                                                      View Payment Reciept</a>
                                                                                                @endif
                                                                                                <a class="dropdown-item" href="#" id="viewPaymentInfo" data-id="{{$item->id}}" > 
                                                                                                      View Payment Info</a>
                                                                                          </div>
                                                                                    </div>
                                                                              </td>
                                                                        </tr>
                                                                  @endforeach
                                                            @else
                                                                  <tr>
                                                                        <td colspan="4" class="text-center">No payment receipt was uploaded</td>
                                                                  </tr>           
                                                            @endif
                                                      </tbody>
                                                </table>
                                          @endif --}}
                                     </div>
                                    

                                    @if($studinfo->studstatus == 1 || $studinfo->preEnrolled == 1 || $isEnrolled)

                                          @if($countPending > 0)

                                                <p class="p-2 mt-4">Your payment is being processed. Submitted payments will be processed for 2(two) working days.</p>

                                          @endif
                                          
                                          @if(count($downpayment) != 0)
                                                <p class="p-2 mt-4">
                                                      
                                                      <span class="badge badge-primary text-lg"><a href="#" class="payassbutton text-white">Click here</a> 
                                                      </span>
                                                      to upload payment receipt.
                                                </p>
                                          @endif

                                    @endif
                                     {{-- @if($countPending != 0)
                                          @if($studinfo->preEnrolled == 1 || $studinfo->studstatus == 1)
                                                <div class="timeline-footer">
                                                      <p class="p-2 mt-4">
                                                            <span class="badge badge-primary text-lg"><a href="#" class="payassbutton text-white">Click here</a> 
                                                            </span>
                                                            to upload payment receipt.
                                                      </p>
                                                            
                                                      <p>See <a href="#paymentoptions">payment options</a> below.</p>
                                                      @if($countPending > 0)
                                                            Your payment is being processed. Submitted payments will be processed for 2(two) working days.
                                                      @endif
                                                </div>
                                          @else
                                                <p class="p-3">Please submit pre-enrollment request to continue with payment</p>
                                          @endif
                                    @else
                                          @if($studinfo->preEnrolled == 1 || $studinfo->studstatus == 1)
                                                <div class="timeline-footer">
                                                      <p class="p-2 mt-4">
                                                            <span class="badge badge-primary text-lg"><a href="#" class="payassbutton text-white">Click here</a> 
                                                            </span>
                                                            to upload payment receipt.
                                                      </p>
                                                      
                                                      <p>See <a href="#paymentoptions">payment options</a> below.</p>
                                                      @if($countPending > 0)
                                                            Your payment is being processed. Submitted payments will be processed for 2(two) working days.
                                                      @endif
                                                </div>
                                          @endif
                                                
                                    @endif --}}
                              </div>
                        </div>
                      
                        <div>
                              <i class="fas fa-info bg-info"></i>
                              <div class="timeline-item">
                                    <h3 class="timeline-header bg-info">TUITION SUMMARY</h3>
                                    <div class="timeline-body">
                                          <table class="table">
                                               <thead>
                                                     <tr>
                                                    
                                                            <td>TUITION ITEM</td>
                                                            <td class="text-right">AMOUNT</td>
                                                     </tr>
                                               </thead>
                                               <tbody>
                                                     @foreach($getpayables as $item)
                                                      <tr>
                                                            <td>{{$item->description}}</td>
                                                            <td class="text-right">&#8369; {{number_format($item->amount, 2)}}</td>
                                                      </tr>
                                                     @endforeach
                                                     <tr>
                                                            <td>TOTAL</td>
                                                            <td class="text-right">&#8369; {{number_format(collect($getpayables)->sum('amount'), 2)}}</td>
                                                      </tr>
                                               </tbody>
                                          </table>
                                         
                                    </div>
                              </div>
                              
                        </div>
                        {{-- <div>
                              <i class="fas fa-info bg-info"></i>
                              <div class="timeline-item">
                                    <h3 class="timeline-header bg-info" id="paymentoptions">PAYMENT OPTIONS</h3>
                                    <div class="timeline-body">
                                          <ul style="list-style-type: none;" class="mt-2 mb-4">
                                                <li>
                                                      <img width="60" src="{{asset('paymentlogos/bpi.png')}}">
                                                      <ul class="mt-2">
                                                            <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                                            <li>Account Number: 3056 3665</li>
                                                      </ul>
                                                </li>
                                                <li class="mt-3">
                                                      <img width="60" src="{{asset('paymentlogos/landbank.png')}}">
                                                      <ul class="mt-2">
                                                            <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                                            <li>Account Number: 3607-0057-34</li>
                                                      </ul>
                                                </li>
                                                <li class="mt-3">
                                                      <img width="60" src="{{asset('paymentlogos/bdo.png')}}">
                                                      <ul class="mt-2">
                                                            <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                                            <li>Account Number: 0003800278949</li>
                                                      </ul>
                                                </li>
                                                <li class="mt-3">
                                                      <img width="60" src="{{asset('paymentlogos/chinabank.png')}}">
                                                      <ul>
                                                            <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                                            <li>Account Number: 128900000452</li>
                                                      </ul>
                                                </li>
                                                <li class="mt-3">
                                                      <img width="60" src="{{asset('paymentlogos/gcash.png')}}">
                                                      <ul class="mt-2">
                                                            <li>GCASH Mobile Number: 0928-168-0536</li>
                                                      </ul>
                                                </li>
                                          </ul>
                                    </div>
                              </div>
                        </div> --}}
                        {{-- <div>
                              <i class="fas fa-info bg-info"></i>
                              <div class="timeline-item">
                                    <h3 class="timeline-header bg-info">PAYMENT OPTIONS</h3>
                                    <div class="timeline-body">
                                          <ul style="list-style-type: none;" class="mt-2 mb-4 p-0">
                                                <li>
                                                      <img width="60" src="{{asset('paymentlogos/bpi.png')}}">
                                                      <ul class="mt-2">
                                                            <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                                            <li>Account Number: 3056 3665</li>
                                                      </ul>
                                                </li>
                                                <li class="mt-3">
                                                      <img width="60" src="{{asset('paymentlogos/landbank.png')}}">
                                                      <ul class="mt-2">
                                                            <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                                            <li>Account Number: 3607-0057-34</li>
                                                      </ul>
                                                </li>
                                                <li class="mt-3">
                                                      <img width="60" src="{{asset('paymentlogos/bdo.png')}}">
                                                      <ul class="mt-2">
                                                            <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                                            <li>Account Number: 0003800278949</li>
                                                      </ul>
                                                </li>
                                                <li class="mt-3">
                                                      <img width="60" src="{{asset('paymentlogos/chinabank.png')}}">
                                                      <ul>
                                                            <li>Account Name: Brokenshire College Toril, Davao City Inc.</li>
                                                            <li>Account Number: 128900000452</li>
                                                      </ul>
                                                </li>
                                                <li class="mt-3">
                                                      <img width="60" src="{{asset('paymentlogos/gcash.png')}}">
                                                      <ul class="mt-2">
                                                            <li>GCASH Mobile Number: 0928-168-0536</li>
                                                      </ul>
                                                </li>
                                          </ul>
                                    </div>
                              </div>
                              
                        </div> --}}
                         
                         
                  </div>
                  
            </div>
                        
      </div>
</div>
 


@endif


<script>

// viewpPaymentReciept


      $(document).on('click','#viewpPaymentReciept',function(e){

            $('#receiptModal').modal();
            
            $.ajax({
                  type:'GET',
                  url:'/preenrollment/get/payment/receipt/'+$(this).attr('data-id'),
                  success:function(data) {
                     
                        $('#paymentReceipt').empty()
                        $('#paymentReceipt').append(data)
                        e.preventDefault();
                  }
            })

            $(this).off('click'); 
           

      })

      $(document).on('click','#viewPaymentInfo',function(e){
          
            $('#paymentInformation').modal()

            $.ajax({
                  type:'GET',
                  url:'/preenrollment/view/paymnent/info/'+$('#studid').val()+'/'+$(this).attr('data-id'),
                  success:function(data) {
                  
                        $('#paymentInfoTable').empty()
                        $('#paymentInfoTable').append(data)

                        e.preventDefault();
                  }
            })

            $(this).off('click'); 
           
      })

</script>



<script>
      $(document).ready(function(){

            $(document).on('click','#cancelPayment',function(){
                  
                  Swal.fire({
                              title: 'Are you sure you want to cancel payment?',
                              type: 'info',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Cancel Payment'
                        })
                        .then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'GET',
                                          url:'/preenrollment/cancel/paymnent/'+$('#studid').val()+'/'+$(this).attr('data-id'),
                                          success:function(data) {
                                                evalForm()
                                          },
                                    })
                              }
                        })
           
            })

            $(document).on('click','.imagereceipt',function(){
                  $("#updatemodal").modal();
                  $('#modalImage').attr('src',$(this).attr('src'))
            }) 

            

            $(document).on('click','.payassbutton',function(){
             
                  $("#paymentasssesment").modal();
                  
            }) 

            $(document).on('click','.uploadreceipt',function(){
             
                  $("#receiptInformation").modal();

                  $('#amount').val($('#totalass')[0].innerText)
               
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


            function evalForm(){
                  $.ajax({
                        type:'GET',
                        url:'/preenrollment/evaluate/form',
                        data:{
                              a:$("#studid").val(),
                              b:$("#firstname").val(),
                              c:$("#lastname").val(),
                        },
                        success:function(data) {
                              $('#results').empty();
                              $("#results").html(data);

                              
                        },
                  })
            }
            

      })
</script>





<script>
      $(document).ready(function(){

            $( '#paymentInfo' )
                  .submit( function( e ) {
                        @if(count($downpayment) != 0)

                              var payDP = false      
                              var payDPAmount = parseFloat('{{$downpayment[0]->amount}}')
                             

                              @if($downpayment[0]->allowless == 0)

                                    payDP = true
                                    
                                    @if($status == 1)

                                          payDP = true;

                                          payDPAmount = parseFloat('{{$overAllDP}}' - '{{$sumsubmittedOnlineDP}}')

                                    @endif

                              @endif

                              if(payDP){

                                    if( parseFloat($('#amount').val().replace(',','')) >= parseFloat(payDPAmount)){

                                          $('#amount').removeClass('is-invalid')

                                    }
                                    else{
                                        

                                          $('#amount').addClass('is-invalid')
                                          $('#amountError').text('The given amount is less than the required downpayment.')

                                          return false;
                                    }

                              }
                              

                             

                        @endif

                        $('#proceedpayment').removeClass('btn-success')
                        $('#proceedpayment').removeClass('btn-defalult')
                        $('#proceedpayment').addClass('disabled')

                        var inputs = new FormData(this)

                        var counter = 1;
                        var summary = [];
                        
                        var length = $('.dassitem').length;


                        $('.dassitem').each(function(){
                              
                              $origText = 

                              summary.push($(this)[0].cells[1].innerText.replace(',',''))
                              summary.push($(this)[0].cells[0].innerText.split('X ')[0])
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
                              url: '/payment/online/submitreceipt',
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
                                                // console.log(key);
                                                // console.log($('#'+key).addClass('is-invalid'))
                                                $('#'+key).addClass('is-invalid')
                                                // $('#'+key).css('display','block')
                                                $('#'+key).next('.invalid-feedback').text(value)
                                          
                                          })

                                    
                                    }
                                    else if(data[0].status == 1){

                                          Swal.fire({

                                                type: 'success',
                                                title: 'PAYMENT SUBMITTED',
                                                showConfirmButton: false,
                                                timer: 1500,
                                                onBeforeOpen: () => {
                                                      $("#updatemodal").modal('hide');
                                                      $("#paymentasssesment").modal('hide');
                                                      $("#receiptInformation").modal('hide');
                                                },
                                                onClose: () => {
                                                
                                                      $.ajax({
                                                            type:'GET',
                                                            url:'/preenrollment/evaluate/form',
                                                            data:{
                                                                  a:$("#studid").val(),
                                                                  b:$("#firstname").val(),
                                                                  c:$("#lastname").val(),
                                                            },
                                                            success:function(data) {
                                                                  $('#results').empty();
                                                                  $("#results").html(data);

                                                                  
                                                            },
                                                      })
                                                }
                                                
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

{{-- base64_decode --}}

