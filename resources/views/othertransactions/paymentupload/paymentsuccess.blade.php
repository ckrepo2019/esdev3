

@extends('layouts.app')

@section('content')
      <section class="content ">
            <div class="row justify-content-center">
                  <div class="col-md-6">
                        <div class="card">
                              <div class="card-header">
                                    Payment Receipt Upload Successful!
                              </div>
                              <div class="card-body">
                                    <div class="alert alert-info">
                                        
                                          <h5><i class="icon fas fa-info"></i> Your payment was submitted to the Finance Department!</h5>
                                          Thank you for your payment.

                                    </div>
                                    <div class="row">
                                          <div class="col-md-6">
                                                <div class="form-group">
                                                      <label>Name</label>
                                                      <input class="form-control" readonly value="{{$paymenInfo->last_name}}, {{$paymenInfo->first_name}}">
                                                </div>
                                                <div class="form-group">
                                                      <label>Code</label>
                                                      <input class="form-control" readonly value="{{$paymenInfo->queingcode}}">
                                                </div>
                                                <div class="form-group">
                                                      <label>Reference number</label>
                                                      <input class="form-control" readonly value="{{$paymenInfo->refNum}}">
                                                </div>
                                                <div class="form-group">
                                                      <label>Amount</label>
                                                      <input class="form-control" readonly value="{{$paymenInfo->amount}}">
                                                </div>
                                          </div>
                                          <div class="col-md-6">
                                                <img src="{{asset($paymenInfo->picUrl)}}" class="w-100">
                                          </div>
                                    </div>
                               

                                    <div class="alert alert-success mt-4">

                                          Please check your registration status in this link <a href="/prereg/inquiry/form"">{{Request::root()}}/prereginquiry</a> after two(2) working day.
                                          
                                    </div>
                                    <hr>
                                    <a href="/payment/paymentinformation" class="btn" style="background-color: #88b14b; color:white">UPLOAD ANOTHER RECEIPT</a>
                                    <a href="/" class="btn text-white float-right btn-primary" id="done">DONE</a>

                              </div>
                        </div>
                  </div>
               
            </div>
      </section>
      <script>
            $(document).ready(function(){
                  var clicked = 0;
                  $(document).on('click','#done',function(){
                        window.onbeforeunload = function() {
                              window.setTimeout(function () { 
                                    if(clicked != 0){
                                          window.location.replace('{{Request::root()}}'+'/');
                                    }
                              }, 0); 
                              window.onbeforeunload = null;
                        }
                  })
                  
                  window.onbeforeunload = function() {
                        window.setTimeout(function () { 
                              if(clicked != 0){
                                    window.location.replace('{{Request::root()}}'+'/payment/paymentinformation');
                              }
                        }, 0); 
                        window.onbeforeunload = null;
                  }
            })
      </script>

@endsection
      
            