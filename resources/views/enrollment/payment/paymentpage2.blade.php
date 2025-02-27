@if($data[0]->enrollmentstatus == 5)
      @if($data[0]->onlinepayment != null)
      
            <div class="row">
                  <div class="form-group col-md-6" style="float:none;margin:auto;" >

                        @if($data[0]->onlinepayment[0]->isapproved == 0 || $data[0]->onlinepayment[0]->isapproved == 1)
                              <label><h4 class="text-success">Your last online payment transaction is still being processed.</h4></label>
                        @elseif($data[0]->onlinepayment[0]->isapproved == 5)
                              <label><h4 class="text-success">Your last online payment transaction has been processed.</h4></label>
                        @elseif($data[0]->onlinepayment[0]->isapproved == 3)
                              <label><h4 class="text-danger">You canceled your last online payment transaction.</h4></label>
                        @elseif($data[0]->onlinepayment[0]->isapproved == 2)
                              <label><h4 class="text-danger">Your last online payment transaction was not approved.</h4>
                              <p class="mb-0">Please contact your school finance department for more information about your online payment.</p></label>
                        @endif
                  
                  </div>
            </div>

            @if($data[0]->onlinepayment[0]->isapproved == 2)
                  <div class="row">
                        <div class="form-group col-md-6" style="float:none;margin:auto;">
                              <label><b>REMARKS</b></label>
                              <p>" {{$data[0]->onlinepayment[0]->remarks}} "</p>
                        </div>
                  </div>
            @endif
            <div class="row">
                  <div class="form-group col-md-6" style="float:none;margin:auto;">
                        <label><b>PAYMENT DESCRIPTION</b></label>
                        <input readonly="readonly" value="{{$data[0]->onlinepayment[0]->description}}" class="form-control " >
                  </div>
            </div>
            <div class="row mt-3">
                  <div class="form-group col-md-6" style="float:none;margin:auto;">
                        <label><b>PAYMENT AMOUNT</b></label>
                        <input readonly="readonly" value="{{$data[0]->onlinepayment[0]->amount}}" class="form-control " >
                  </div>
            </div>
            <div class="row mt-3">
                  <div class="form-group col-md-6" style="float:none;margin:auto;">
                        <label><b>PAYMENT STATUS</b></label>
                        @if($data[0]->onlinepayment[0]->isapproved == 0)
                              <input readonly="readonly" value="ON PROCESS" class="form-control " >
                        @elseif($data[0]->onlinepayment[0]->isapproved == 1)
                              <input readonly="readonly" value="APPROVED" class="form-control " >
                        @elseif($data[0]->onlinepayment[0]->isapproved == 3)
                              <input readonly="readonly" value="CANCELED" class="form-control " >
                        @elseif($data[0]->onlinepayment[0]->isapproved == 5)
                              <input readonly="readonly" value="PAID" class="form-control " >
                        @elseif($data[0]->onlinepayment[0]->isapproved == 2)
                              <input readonly="readonly" value="NOT APPROVED" class="form-control " >
                        @endif
                  </div>
            </div>
            @if($data[0]->onlinepayment[0]->isapproved == 3 || $data[0]->onlinepayment[0]->isapproved == 2)
                  <div class="row mt-3">
                        <table class="table col-md-6" style="float:none;margin:auto;">
                              <thead>
                                    <tr>
                                          <th>ITEM</th>
                                          <th>AMOUNT</th>
                                    </tr>
                              </thead>
                              @php
                                    $total = 0;
                              @endphp
                              <tbody>
                                    @foreach ($data[0]->downpayment as $item)
                                          @php  
                                                $allowless = $item->allowless;
                                                $total += $item->amount;
                                          @endphp
                                          <tr class="dassitem" pointers="1 {{$item->classid}} {{$item->id}}" qt='1'>
                                                <td>{{$item->description}}</td>
                                                <td>{{$item->amount}}</td>
                                          </tr>
                                    @endforeach
                                    <tr>
                                          <td>TOTAL</td>
                                          <td id="total_fee" data-less="{{$allowless}}">{{ number_format($total,2)}}</td>
                                    </tr>
                              </tbody>
                        </table>
                  
                  </div>
                  @if($allowless == 0)
                        <div class="row mt-3">  
                              <p class="text-danger text-center col-md-6" style="float:none;margin:auto;">
                                    Please be reminded that you are required to pay the exact amount or more to complete pre-enrollment.
                              </p>
                        </div>
                  @endif

            @endif
            <div class="row mt-3">  
                  <div class="form-group" style="float:none;margin:auto;">
                   <button type="button" class="btn btn-success" onclick="nextPrev(-1)">Previous</button>
                  @if($data[0]->onlinepayment[0]->isapproved == 3 || $data[0]->onlinepayment[0]->isapproved == 2)
                        <button type="button" class="btn btn-success" id="proceed_to_payment" onclick="nextPrev(1)">Proceed to payment</button>
                  @elseif($data[0]->onlinepayment[0]->isapproved == 0)
                        <button type="button" class="btn btn-success"  data-id="{{$data[0]->onlinepayment[0]->id}}" id="cancel_payment">Cancel Payment</button>
                  @endif
                  </div>
            </div>
      @else
            <div class="row mt-3">
                  <table class="table col-md-6" style="float:none;margin:auto;">
                        <thead>
                              <tr>
                                    <th>ITEM</th>
                                    <th>AMOUNT</th>
                              </tr>
                        </thead>
                        @php
                              $total = 0;
                        @endphp
                        <tbody>
                              @foreach ($data[0]->downpayment as $item)
                                    @php  
                                          $allowless = $item->allowless;
                                          $total += $item->amount;
                                    @endphp
                                    <tr class="dassitem" pointers="1 {{$item->classid}} {{$item->id}}" qt='1'>
                                          <td>{{$item->description}}</td>
                                          <td>{{$item->amount}}</td>
                                    </tr>
                              @endforeach
                              <tr>
                                    <td>TOTAL</td>
                                    <td id="total_fee" data-less="{{$allowless}}">{{ number_format($total,2)}}</td>
                              </tr>
                        </tbody>
                  </table>
                
            </div>
            @if($allowless == 0)
                  <div class="row mt-3">  
                        <p class="text-danger text-center col-md-6" style="float:none;margin:auto;">
                              Please be reminded that you are required to pay the exact amount or more to complete pre-enrollment.
                        </p>
                  </div>
            @endif
            <div class="row mt-3">  
                  <div class="form-group" style="float:none;margin:auto;">
                        <button type="button" class="btn btn-success"  onclick="nextPrev(-1)">Previous</button>
                        <button type="button" class="btn btn-success" id="proceed_to_payment" onclick="nextPrev(1)">Proceed to payment</button>
                      
                  </div>
            </div>
      @endif


      {{-- </div> --}}

@elseif($data[0]->enrollmentstatus == 6)

      <div class="row">
            <div class="form-group col-md-6" style="float:none;margin:auto;" >

                  <h5>Student not found. Please review the informations you provided or contact your school registrar for more information.</h5>
            
            </div>
      </div>
      <div class="row mt-3">  
            <div class="form-group" style="float:none;margin:auto;">
                  <button type="button" class="btn btn-success" onclick="nextPrev(-1)">Previous</button>
            </div>
      </div>

@elseif($data[0]->enrollmentstatus == 4)

      <div class="row">
            <div class="form-group col-md-6 text-center" style="float:none;margin:auto;" >

                  <h5>Downpayment is not yet available.</h5>
            
            </div>
      </div>
      <div class="row mt-3">  
            <div class="form-group" style="float:none;margin:auto;">
                  <button type="button" class="btn btn-success" onclick="nextPrev(-1)">Previous</button>
            </div>
      </div>

@elseif($data[0]->enrollmentstatus == 7)

      <div class="row">
            <div class="form-group col-md-6" style="float:none;margin:auto;" >
                  <h5 class="text-center">Student is already enrolled. Please login the parents portal to continue payment. <br><br><a href="/">Click here to login.<a/></h5>
            </div>
      </div>
      <div class="row mt-3">  
            <div class="form-group" style="float:none;margin:auto;">
                  <button type="button" class="btn btn-success" onclick="nextPrev(-1)">Previous</button>
            </div>
      </div>

@elseif($data[0]->enrollmentstatus == 8)

      <div class="row">
            <div class="form-group col-md-6" style="float:none;margin:auto;" >
                  <h5>You are not yet pre-enrolled. Please fill up and submit pre-enrollment to continue with payment.<br>
                        <br><a href="/preregv2" id="go_to_prereg">Click here to submit pre-enrollment.<a/></h5>
            </div>
      </div>
      <div class="row mt-3">  
            <div class="form-group" style="float:none;margin:auto;">
                  <button type="button" class="btn btn-success" onclick="nextPrev(-1)">Previous</button>
            </div>
      </div>

@endif