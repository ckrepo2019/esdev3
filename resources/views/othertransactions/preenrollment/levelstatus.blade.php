@php
      $schoolyear = DB::table('sy')->where('isactive',1)->first();
      $semester = DB::table('semester')->where('isactive',1)->first();
@endphp


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


<div class="modal fade" id="paymentasssesment" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
      <div class="modal-content">
            <div class="card-header">
                  Payment Assessment
            </div>
            <div class="modal-body">
            <div class="row mt-2">
                        <div class="col-md-12 " >
                              @if($status == 1)
                                    <div class="row table-responsive" style="height: 300px;">
                                          @if($studinfo->studstatus == 1)
                                                <table class="table table-hover table-head-fixed" id="tuitiontable" style="min-height:100px">
                                                      <thead>
                                                            <tr>
                                                                  <th width="5%"></th>
                                                                  <th width="60%">DESCRIPTION</th>
                                                                  <th width="35%" class="text-right">AMOUNT</th>
                                                                  
                                                            </tr>
                                                      </thead>
                                                      <tbody id="tuitionbody" class="bg-info">
                                                            @foreach ($assessment as $key=>$item)
                                                                  @if($item->balance != '0.00')
                                                                        <tr 
                                                                              sortid="{{$key}}"
                                                                              style="cursor:pointer" 
                                                                              itemtype="tuition" class="items" 
                                                                              pointers="2 @if($item->duedate != null){{$item->classid}} {{$item->id}} {{\Carbon\Carbon::create($item->duedate)->isoFormat('M')}}@else{{$item->classid}} {{$item->id}} 0 @endif"
                                                                              qt = 1
                                                                        >
                                                                              <td>
                                                                                    <div class="icheck-success d-inline selectPayable">
                                                                                          <input type="checkbox" id="ass{{$key}}">
                                                                                          <label for="ass{{$key}}">
                                                                                          </label>
                                                                                    </div>
                                                                              </td>
                                                                              <td>
                                                                                    @if($item->duedate != null)
                                                                                          {{strtoupper(\Carbon\Carbon::create($item->duedate)->isoFormat('MMMM'))}}
                                                                                    @endif
                                                                                    PAYABLES
                                                                              </td>
                                                                              <td class="text-right">{{number_format($item->balance, 2)}} </td>
                                                                        </tr>
                                                                  @endif
                                                            @endforeach
                                                      </tbody>
                                                </table>
                                          @else
                                                <table class="table table-hover table-head-fixed" id="tuitiontable" > 
                                                      <thead>
                                                            <tr>
                                                                  <th widht="5%"></th>
                                                                  <th width="60%">DESCRIPTION</th>
                                                                  <th width="35%" class="text-right">AMOUNT</th>
                                                                  
                                                            </tr>
                                                      </thead>
                                                      <tbody id="tuitionbody" class="bg-info">
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
                                                                              <td>
                                                                                    <div class="icheck-success d-inline selectPayable">
                                                                                          <input type="checkbox" id="ass0">
                                                                                          <label for="ass0">
                                                                                          </label>
                                                                                    </div>
                                                                              </td>
                                                                              <td>{{$downpayment[0]->description}}</td>
                                                                              <td class="text-right">{{number_format($overAllDP - $sumsubmittedOnlineDP,2)}}</td>
                                                                        </tr>
                                                                  @endif
                                                            @endif
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
                                                                              <button class="btn btn-sm btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    <i class="fas fa-plus"></i>
                                                                              </button>
                                                                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                              <a data-id={{15+$key+1}}  class="dropdown-item selectPayable"  href="#" >Select Payable</a>
                                                                              </div>
                                                                        </div>
                                                                  </td>
                                                            </tr>
                                                      @endforeach
                                                </tbody>
                                          </table>
                                    </div>
                                    <table class="table">
                                          <tfoot class="bg-danger" >
                                                <tr>
                                                      <td style="font-size:13px">TOTAL AMOUNT OF SELECTED ITEM</td>
                                                      <td class="text-right" id="totalass" style="font-size:21px">0.00</td>
                                                </tr>
                                          </tfoot>
                                    </table>
                                    <button class="btn btn-success uploadreceipt" disabled>Upload Payment Receipt</button>
                                    <button type="button" class="btn btn-secondary"  data-dismiss="modal">Cancel</button>
                              @else
                                    <div class="row table-responsive" style="height: 300px;">
                                          <table class="table table-head-fixed" id="tuitiontable" >
                                                <thead>
                                                      <tr>
                                                            <td width="5%"></td>
                                                            <td width="60%">DESCRIPTION</td>
                                                            <td width="35%" class="text-right">AMOUNT</td>
                                                      
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
                                                                              sortid=0
                                                                        >
                                                                              <td>
                                                                                    <div class="icheck-success d-inline selectPayable">
                                                                                          <input type="checkbox" id="ass0">
                                                                                          <label for="ass0">
                                                                                          </label>
                                                                                    </div>
                                                                              </td>
                                                                              <td>{{$item->description}}</td>
                                                                              @if($item->amount > $totalDP)
                                                                                    <td class="text-right">{{number_format($item->amount - $totalDP, 2)}}</td>
                                                                              @else
                                                                                    <td class="text-right">{{number_format($item->amount, 2)}}</td>
                                                                              @endif
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
                                                                              <button class="btn btn-sm btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    <i class="fas fa-plus"></i>
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
                                    <table class="table">
                                          <tfoot class="bg-danger" >
                                                <tr>
                                                      <td style="font-size:13px">TOTAL AMOUNT OF SELECTED ITEM</td>
                                                      <td class="text-right" id="totalass" style="font-size:21px">0.00</td>
                                                </tr>
                                          </tfoot>
                                    </table>
                                    <button class="btn btn-success uploadreceipt" disabled>Upload Payment Receipt</button>
                                    <button type="button" class="btn btn-secondary"  data-dismiss="modal">Cancel</button>

                              @endif
                              <p class="text-danger text-center pt-2" style="font-size:16px"><em>Please select the items that you want to pay by clicking/pressing the box beside the description.</em></p>
                        </div>
                        <div class="col-md-1">
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
                                                <input class="form-control" name="refNum" id="refNum" placeholder="REFERENCE NUMBER">
                                                <span class="invalid-feedback" role="alert" style="display:hidden">
                                                      <strong>required</strong>
                                                </span>
                                          </div>
                                          <div class="form-group col-md-4">
                                                <label for="">BANK NAME</label>
                                                <select id="bankName" name="bankName" class="form-control">
                                                      <option value="">SELECT BANK</option>
                                                      @foreach (DB::table('onlinepaymentoptions')->where('paymenttype','3')->where('deleted','0')->where('isActive','1')->get() as $item)
                                                            <option value="{{$item->optionDescription}}">{{$item->optionDescription}}</option>
                                                      @endforeach
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



            @if($with_early_enrollment)
                  @if($enrollmnet_setup[0]->type == 2 && count($early_enrollment) == 0)
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="card">
                                          <div class="card-header">
                                                <h6 class="mb-0">Early Enrollment</h6>
                                          </div>
                                          <div class="card-body">
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <h3>Early enrollment is now open!</h3>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      @if(count($early_enrollment) > 0)
                                                            <div class="col-md-12">
                                                                  <p>Early enrollment started last {{\Carbon\Carbon::create($enrollmnet_setup[0]->enrollmentstart)->isoFormat('MMMM DD, YYYY') }} and will end on {{\Carbon\Carbon::create($enrollmnet_setup[0]->enrollmentend)->isoFormat('MMMM DD, YYYY') }}. Please submit early enrollment payment on or before {{\Carbon\Carbon::create($enrollmnet_setup[0]->enrollmentend)->isoFormat('MMMM DD, YYYY') }} to complete your early enrollment transaction.</p>
                                                            </div>
                                                      @else
                                                            <div class="col-md-12">
                                                                  <p>Early enrollment started last {{\Carbon\Carbon::create($enrollmnet_setup[0]->enrollmentstart)->isoFormat('MMMM DD, YYYY') }} and will end on {{\Carbon\Carbon::create($enrollmnet_setup[0]->enrollmentend)->isoFormat('MMMM DD, YYYY') }}.</p>
                                                            </div>
                                                            <div class="col-md-12">
                                                                  <a class="btn btn-primary" id="submit_earlyenrollment" href="#">Click here to submit Early Enrollment Form</a>
                                                            </div>
                                                      @endif
                                                </div>

                                                @if(count($early_enrollment) > 0)
                                                      <div class="row">
                                                            <div class="col-md-12">
                                                                  <p>Early enrollment was submitted last <i>{{\Carbon\Carbon::create($early_enrollment[0]->createddatetime)->isoFormat('MMMM DD, YYYY') }}</i>.</p>
                                                            </div>
                                                      </div>
                                                @else

                                                @endif
                                                @if(count($early_enrollment) > 0)
                                                      @if(count($early_enrollment_payment_setup) > 0)
                                                            <div class="row">
                                                                  <div class="col-md-12">
                                                                        <p>Online payment upload is now available. Please upload a photo of your payment.</p>
                                                                  </div>
                                                                  <div class="col-md-4">
                                                                        {{$early_enrollment_payment_setup[0]->description}}
                                                                  </div>
                                                                  <div class="col-md-2">
                                                                        &#8369; {{$early_enrollment_payment_setup[0]->amount}}
                                                                  </div>
                                                            </div>
                                                            <div class="row mt-3">
                                                                  <div class="col-md-12">
                                                                        <button class="btn btn-primary dassitem" pointers="1 {{$early_enrollment_payment_setup[0]->classid}} {{$early_enrollment_payment_setup[0]->itemid}}" id="upload_early_enrollment" qt="1">Click here to upload payment transactions for Early Enrollment</button>
                                                                  </div>
                                                            </div>
                                                      @else
                                                            <div class="row">
                                                                  <div class="col-md-12">
                                                                        <label for="">Online payment upload is not yet available</label>
                                                                  </div>
                                                            </div>
                                                      @endif
                                                @endif
                                                <div class="row mt-4">
                                                      <div class="col-md-12">
                                                            <label for="">Upload Early Enrollment Payment Transaction</label>
                                                            <table class="table table-head-fixed" style="min-width:500px">
                                                                  <thead>      
                                                                        <tr>
                                                                              <th width="30%" class="text-center">PAYMENT RECEIPT</th>
                                                                              <th width="20%">AMOUNT</th>
                                                                              <th width="20%">STATUS</th>
                                                                              <th width="30%">DATE UPLOADED</th>
                                                                        </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                        @foreach ($early_enrollment_payment as $item)
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
                                                                              </tr>
                                                                        @endforeach
                                                                  </tbody>
                                                            </table>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <script>
                              $(document).ready(function (){
                                    
                                    var early_enrollment_payment_setup = @json($early_enrollment_payment_setup)

                                    var enrollmnet_setup = @json($enrollmnet_setup)

                                    var studinfo = @json($studinfo)

                                    $(document).on('click','#upload_early_enrollment',function(){
                                          $('#receiptInformation').modal()
                                          $('#amount').val(early_enrollment_payment_setup[0].amount)
                                    })

                                    $('#submit_earlyenrollment').unbind().click(function(){
                                          $.ajax({
                                                type:'GET',
                                                url:'/early/enrollment/submit',
                                                data:{
                                                      studid:studinfo.id,
                                                      syid:enrollmnet_setup[0].syid,
                                                      semid:enrollmnet_setup[0].semid,
                                                      levelid:studinfo.levelid
                                                },
                                                success:function(data) {
                                                      if(data[0].status == 1){
                                                            evaluate()
                                                      }
                                                      else{

                                                      }
                                                },
                                          })    
                                    })

                                    function evaluate(){
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
                  @elseif(count($early_enrollment) > 0)
                        {{-- already submitted an early enrollment --}}
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="card">
                                          <div class="card-header">
                                                <h6 class="mb-0">Early Enrollment</h6>
                                          </div>
                                          <div class="card-body">
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <h3>YOU'RE HALF WAY THERE!</h3>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <p>To complete and secure your slot, please pay the Early registraion fee on or before <i>{{\Carbon\Carbon::create($enrollmnet_setup[0]->enrollmentend)->isoFormat('MMMM DD, YYYY') }}</i> to avoid cancellation of your early enrollment submission</p>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <p>You have submitted your Early Registration last {{\Carbon\Carbon::create($early_enrollment[0]->createddatetime)->isoFormat('MMMM DD, YYYY') }}</p>
                                                      </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-12">
                                                            <p>You can pay directly to School Cashier ( Just proceed to the Cashier's office ).</p>
                                                      </div>
                                                      <div class="col-md-12">
                                                            <p>Or your can do online payment. Just upload the photo of the transaction reciept / deposit slip / e-receipt  of your payment.</p>
                                                      </div>
                                                </div>
                                                @if(count($early_enrollment_payment_setup) > 0)
                                                      <div class="row">
                                                            <div class="col-md-12">
                                                                  <p>Online payment upload is now available.</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                  {{$early_enrollment_payment_setup[0]->description}} :
                                                            </div>
                                                            <div class="col-md-2">
                                                                  &#8369; {{$early_enrollment_payment_setup[0]->amount}}
                                                            </div>
                                                      </div>
                                                      <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                  <button class="btn btn-primary dassitem" pointers="1 {{$early_enrollment_payment_setup[0]->classid}} {{$early_enrollment_payment_setup[0]->itemid}}" id="upload_early_enrollment" qt="1">Upload Payment Reciept</button>
                                                            </div>
                                                            
                                                      </div>
                                                      <div class="row">
                                                            <div class="col-md-12" style="font-size:13px !important">
                                                                  <i>( Please click the button to upload your online transaction receipt / deposit slip /e-receipt photo)</i>
                                                            </div>
                                                      </div>
                                                @else
                                                      <div class="row">
                                                            <div class="col-md-12">
                                                                  <p>Online payment upload is not yet available!</p>
                                                            </div>
                                                      </div>
                                                @endif
                                                
                                                <div class="row mt-4">
                                                      <div class="col-md-12">
                                                            <label for="">STATUS BOARD</label>
                                                            <table class="table table-head-fixed table-sm" style="min-width:500px">
                                                                  <thead>      
                                                                        <tr>
                                                                              <th width="30%" class="text-center">PAYMENT RECEIPT</th>
                                                                              <th width="20%">AMOUNT</th>
                                                                              <th width="20%">STATUS</th>
                                                                              <th width="30%">DATE UPLOADED</th>
                                                                        </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                        @foreach ($early_enrollment_payment as $item)
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
                                                                              </tr>
                                                                        @endforeach
                                                                  </tbody>
                                                            </table>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <script>
                              $(document).ready(function (){
                                    
                                    var early_enrollment_payment_setup = @json($early_enrollment_payment_setup)

                                    var enrollmnet_setup = @json($enrollmnet_setup)

                                    var studinfo = @json($studinfo)

                                    $(document).on('click','#upload_early_enrollment',function(){
                                          $('#receiptInformation').modal()
                                          $('#amount').val(early_enrollment_payment_setup[0].amount)
                                    })

                                    $('#submit_earlyenrollment').unbind().click(function(){
                                          $.ajax({
                                                type:'GET',
                                                url:'/early/enrollment/submit',
                                                data:{
                                                      studid:studinfo.id,
                                                      syid:enrollmnet_setup[0].syid,
                                                      semid:enrollmnet_setup[0].semid,
                                                      levelid:studinfo.levelid
                                                },
                                                success:function(data) {
                                                      if(data[0].status == 1){
                                                            evaluate()
                                                      }
                                                      else{

                                                      }
                                                },
                                          })    
                                    })

                                    function evaluate(){
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
                  @endif
            @elseif(count($early_enrollment) > 0)
                  {{-- if new school year --}}
                  <div class="row">
                        <div class="col-md-12">
                              <div class="card">
                                    <div class="card-header">
                                          <h6 class="mb-0">Early Enrollment</h6>
                                    </div>
                                    <div class="card-body">
                                          <div class="row">
                                                <div class="col-md-12">
                                                      <p>{{count($enrollmnet_setup) > 0 ? $enrollmnet_setup[0]->message : 'Early Enrollment is not available'}}</i>.</p>
                                                </div>
                                          </div>
                                          <div class="row">
                                                <div class="col-md-12">
                                                      <p>Early enrollment for school year {{$early_enrollment[0]->sydesc}} was submitted last <i>{{\Carbon\Carbon::create($early_enrollment[0]->createddatetime)->isoFormat('MMMM DD, YYYY') }}</i>.</p>
                                                </div>
                                                @if(!$with_early_enrollment)
                                                      <div class="col-md-12">
                                                            <p>Online payment upload for early enrollment is currently not available.</p>
                                                      </div>
                                                @endif
                                          </div>
                                          <div class="row">
                                                <div class="col-md-12">
                                                      <label for="">Upload Early Enrollment Payment Transaction</label>
                                                      <table class="table table-head-fixed" style="min-width:500px">
                                                            <thead>      
                                                                  <tr>
                                                                        <th width="30%" class="text-center">PAYMENT RECEIPT</th>
                                                                        <th width="20%">AMOUNT</th>
                                                                        <th width="20%">STATUS</th>
                                                                        <th width="30%">DATE UPLOADED</th>
                                                                  </tr>
                                                            </thead>
                                                            <tbody>
                                                                  @foreach ($early_enrollment_payment as $item)
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
                                                                        </tr>
                                                                  @endforeach
                                                            </tbody>
                                                      </table>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                  
            @endif




      @if($status != 3)
           
      
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
                                                <h3 class="timeline-header bg-primary">ONLINE PAYMENT(S) </h3>
                                                      <div class="timeline-body table-responsive" style="height:400px">
                                                            @if($countPending == 0)
                                                                  @if(count($downpayment)  != 0 )
                                                                        <p class="p-2 mb-0">To complete the pre-enrollment process, you have to pay the downpayment amounting</p>
                                                                        <span class="text-danger pl-1 pr-1 text-xl" >&#8369; {{number_format($downpayment[0]->amount, 2)}}</span>
                                                                        @if($downpayment[0]->allowless == 0)
                                                                              <p class="text-success text-lg text-center"><em>Please be reminded that you are required to pay the exact amount or more to complete pre-enrollment.</em></p>
                                                                        @endif
                                                                        <p class="p-2 mt-4">
                                                                              <span class="badge badge-primary text-lg"><a href="#" class="payassbutton text-white">Click here</a> </span>
                                                                              to upload payment receipt
                                                                        </p>
                                                                        <p class="p-2">You can pay to any partnered banks, GCash or Palawan as you payment options or visit your school cashier for payment. See <a href="#paymentoptions">payment options</a> below</p>
                                                                  @else
                                                                        <p class="text-center">Payment upload is not yet available.</p>
                                                                  @endif
                                                            @else
                                                                  @if(count($downpayment) != 0)
                                                                        @if($downpayment[0]->amount < $totalDP)
                                                                              <p class="p-2 mb-0">To complete the pre-enrollment process, you have to pay the downpayment amounting</p>
                                                                              <span class="text-danger pl-1 pr-1 text-xl" >&#8369; {{number_format($downpayment[0]->amount, 2)}}</span>
                                                                        @endif
                                                                  @endif
                                                                  <table class="table table-head-fixed" style="min-width:500px">
                                                                        <thead>      
                                                                              <tr>
                                                                                    <th width="30%" class="text-center">PAYMENT RECEIPT</th>
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
                                                                                                                  View Payment Receipt</a>
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
                                                                        <p class="p-2 mt-4">
                                                                              <span class="badge badge-primary text-lg"><a href="#" class="payassbutton text-white">Click here</a> 
                                                                              </span>
                                                                              to upload payment receipt.
                                                                        </p>
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
                                                                  <tr class="bg-info">
                                                                        <td>TOTAL</td>
                                                                        <td class="text-right" style="font-size:20px">&#8369; {{number_format(collect($getpayables)->sum('amount'), 2)}}</td>
                                                                  </tr>
                                                            </tbody>
                                                            </table>
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            
            @elseif($status == 1 && !$with_early_enrollment)
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
                                                            <h3 class="timeline-header bg-danger">{{$schoolyear->sydesc}} - {{$semester->semester}} PRE-ENROLLMENT</h3>
                                                            <div class="timeline-body">
                                                                  <p>Pre-enrollment is not yet submitted.</p>

                                                                  <p>  <span class="badge badge-primary text-lg"><a id="submit_pre-enrollment" class="text-white" href="#">Click here</a> 
                                                                  </span> button to submit pre-enrollment for {{$schoolyear->sydesc}} - {{$semester->semester}}.</p> 
                                                            
                                                            </div>
                                                      </div>
                                                </div>
                                                <script>
                                                      $(document).ready(function(){

                                                           

                                                            var studinfo = @json($studinfo)
                                                            
                                                            $('#submit_pre-enrollment').unbind().click(function() {
                                                                  $.ajax({
                                                                        type:'GET',
                                                                        url:'/pre/enrollment/submit',
                                                                        data:{
                                                                              studid:studinfo.id
                                                                        },
                                                                        success:function(data) {
                                                                              if(data[0].status == 1){
                                                                                    evaluate()
                                                                              }else{

                                                                              }
                                                                        },
                                                                  })
                                                            })

                                                            function evaluate(){
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

                                    @elseif($studinfo->preEnrolled == 1 && $studinfo->studstatus == 0 && !$isEnrolled)
                                          <div>
                                                <i class="fas bg-primary">1</i>
                                                <div class="timeline-item">
                                                      <h3 class="timeline-header bg-danger">{{$schoolyear->sydesc}} - {{$semester->semester}} PRE-ENROLLMENT</h3>
                                                      <div class="timeline-body">
                                                            <p class="m-0">{{$schoolyear->sydesc}} - {{$semester->semester}} Pre-enrollment has been submitted.</p>
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

                                                                  <p class="p-2 mb-0">To complete the pre-enrollment process, you have to pay the downpayment amounting</p>

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
                                                                                          <th width="30%" class="text-center">PAYMENT RECEIPT</th>
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
                                                                                                                        View Payment Receipt</a>
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
                                                </div>
                                                

                                                @if($studinfo->studstatus == 1 || $studinfo->preEnrolled == 1 || $isEnrolled)
                                                      @if($countPending > 0)
                                                            <p class="p-2 mt-4">Your payment is being processed. Submitted payments will be processed for 2(two) working days.</p>
                                                      @endif
                                                      
                                                      @if(count($downpayment) != 0)
                                                            <p class="p-2 mt-4 mb-0">
                                                                  <span class="badge badge-primary text-lg"><a href="#" class="payassbutton text-white">Click here</a> 
                                                                  </span>
                                                                  to upload payment receipt.
                                                            </p>
                                                            @if($studinfo->studstatus == 0)
                                                            @endif
                                                      @endif
                                                @endif
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
                                                            <tr>
                                                                  <td>TOTAL</td>
                                                                  <td class="text-right">&#8369; {{number_format(collect($getpayables)->sum('amount'), 2)}}</td>
                                                            </tr>
                                                      </tbody>
                                                      </table>
                                                </div>
                                          </div>
                                    </div>
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
            var with_early_enrollment = @json($with_early_enrollment)

           
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

                        var early_enrollment_payment_setup = @json($early_enrollment_payment_setup)

                      

                        if(!with_early_enrollment){
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
                        }else{
                              summary.push($('#amount').val().replace(',',''))
                              summary.push(early_enrollment_payment_setup.description)
                              summary.push($('.dassitem').attr('pointers'))
                              summary.push(1)
                        }
                    
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
                                                $('#'+key).addClass('is-invalid')
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

