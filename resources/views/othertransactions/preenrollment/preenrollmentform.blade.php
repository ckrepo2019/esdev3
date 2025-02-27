

@extends('layouts.app')

@section('headerscript')
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script> 
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <style>
          @media only screen and (max-width: 500px) {
                  .col-md-3{
                        padding: 0 !important
                  }
                  .card{
                        border-radius: 0 !important
                  }
                  .card-header{
                        border-radius: 0 !important
                  }
                  .timeline>div {
                        margin-right: 0 !important;
                  }
            }

            #loadingModal{
                  ba
            }
    </style>

   


@endsection

@section('content')

      <div class="modal fade overlay w-100" id="modalAlert" style="display: none;" aria-hidden="true"    data-backdrop="static" data-keyboard="false"  >
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-body">
                              <h5>Unable to process transaction using this browser. Please click the button (<i class="fas fa-ellipsis-h mr-2 ml-2"></i>) on the upper right corner of the screen and select "Open in Chrome".</h5>
                        </div>
                  </div>
            
            </div>
      </div>
     
      <section class="content ">
           
            <div class="row justify-content-center overlay">
                  <div class="col-md-3">
                        <div class="card">
                              <div class="card-header">
                                   <h1 class="col-md-12 text-center card-title text-white m-0 text-md"> INQUIRY FORM /<br>ONLINE PAYMENTS</h1>
                              </div>
                              <div class="card-body">
                                    <div class="form-group">
                                          <label>REGISTRATION CODE / <br>STUDENT ID / LRN</label>
                                          <input  id="studid" class="form-control" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                    <div class="form-group">
                                          <label>FIRST NAME</label>
                                          <input id="firstname" class="form-control" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                    <div class="form-group">
                                          <label>LAST NAME</label>
                                          <input id="lastname" class="form-control" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                   
                                    <button class="btn btn-success btn-block" id="eval">EVALUATE INFORMATION</button>
                                    {{-- <button class="btn btn-blue btn-block">PAYMENT ASSESSMENT</button> --}}

                                    <hr>
                                    <p style="font-size:20px">
                                          Lost registration code? <a href="/coderecovery">Click here</a>.
                                    </p>
                                    {{-- <p>You may visit <a href="/coderecovery">{{Request::root()}}/coderecovery</a> to your registration code.</p> --}}
                                   
                              </div>
                          
                        </div>

                        <div class="card">
                              <div class="card-header">
                                    ENROLLMENT FLOW
                              </div>
                              <div class="card-body p-0 pt-4">
                                    <div class="timeline">
                                          {{-- <div>
                                                <div class="timeline-item">
                                                      <h3 class="timeline-header bg-blue">ENROLLMENT FLOW</h3>
                                                </div>
                                          </div> --}}
                                          <div>
                                                <i class="fas bg-blue">1</i>
                                                <div class="timeline-item">
                                                      <h3 class="timeline-header bg-blue">FILL UP PRE-REGISTRATION FORM</h3>
                                                      {{-- <div class="timeline-body">
                                                           
                                                      </div> --}}
                                                </div>
                                          </div>
                                          <div>
                                                <i class="fas bg-blue">2</i>
                                                <div class="timeline-item">
                                                      <h3 class="timeline-header bg-blue">PAY DOWNPAYMENT</h3>
                                                      {{-- <div class="timeline-body">
                                                            <p class="m-0">Fill Up Inquiry Form to view pre-enrollment / pre-registration status and upload payment receipt.</p>
                                                            <p class="m-0">Uploaded payment receipts will be proccessed for 2(two) working days. See <a href="#paymentoptions">payment options</a> below.</p>
                                                      </div> --}}
                                                </div>
                                          </div>
                                          <div>
                                                <i class="fas bg-blue">3</i>
                                                <div class="timeline-item">
                                                      <h3 class="timeline-header bg-blue">ENROLLMENT COMPLETE!</h3>
                                                      {{-- <div class="timeline-body"> --}}
                                                            {{-- <p class="m-0">Wait for pre-enrollment to be proccessed.</p> --}}
                                                      {{-- </div> --}}
                                                </div>
                                          </div>
                                    </div>
                              </div>
                             
                        </div>

                      
                        {{--  <div class="card">
                                    <div class="card-header bg-info">
                                          Payment Options
                                    </div>
                                    <div class="card-body">
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
                        </div> --}}
                        
                  </div>
                  <div class="col-md-6 p-0" id="results">
                       
                        <div class="timeline">
                              <div>
                                    <div class="timeline-item">
                                          <h3 class="timeline-header bg-blue">ENROLLMENT FLOW</h3>
                                    </div>
                              </div>
                              <div>
                                    <i class="fas bg-blue">1</i>
                                    <div class="timeline-item">
                                          <h3 class="timeline-header bg-blue">FILL UP PRE-REGISTRATION FORM</h3>
                                          <div class="timeline-body">
                                                <p>
                                                      New student should fill-up pre-enrollment form. Registration Code will be given after completing pre-enrollment form. The given registration code is needed to upload payment receipt and track enrollment status.
                                                </p>

                                                <p class="" > <span class="badge badge-primary text-lg"><a target="_blank" href="/preregv2" class="text-white">Click here</a> 
                                                </span> to fill up pre-enrollment form. </p>

                                                <p>
                                                      For old students, Please fill up inquiry form to submit pre-enrollment.
                                                </p>


                                          </div>
                                    </div>
                              </div>
                              <div>
                                    <i class="fas bg-blue">2</i>
                                    <div class="timeline-item">
                                          <h3 class="timeline-header bg-blue">UPLOAD PAYMENT RECEIPT</h3>
                                          <div class="timeline-body">
                                                <p class="m-0">Fill Up Inquiry Form to view pre-enrollment / pre-registration status and upload payment receipt.</p>
                                                <p class="m-0">Uploaded payment receipts will be proccessed for 2(two) working days. See <a href="#paymentoptions">payment options</a> below.</p>
                                          </div>
                                    </div>
                              </div>
                              <div>
                                    <i class="fas bg-blue">3</i>
                                    <div class="timeline-item">
                                          <h3 class="timeline-header bg-success">ENROLLMENT COMPLETE!</h3>
                                          <div class="timeline-body">
                                                <p class="m-0">Wait for pre-enrollment to be proccessed.</p>
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
                        <div class="col-md-3">
                              <div class="card h-100">
                                    <div class="card-header">
                                          Payment Options
                                    </div>
                                    <div class="card-body p-3">
                                          <ul style="list-style-type: none;" class="mt-2 mb-4 p-0">
                                                {{-- <li>
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
                                                </li> --}}
                                                @foreach(DB::table('onlinepaymentoptions')->where('deleted','0')->where('isActive','1')->get() as $item)
                                                      <li class="mt-3">
                                                      <img width="60" src="{{asset($item->picurl)}}" width="60">
                        
                                                            @if($item->paymenttype == 3)
                                                                  <ul class="mt-2">
                                                                        <li>Account Name: {{$item->accountName}}</li>
                                                                        <li>Account Number:  {{$item->accountNum}}</li>
                                                                  </ul>
                                                            @elseif($item->paymenttype == 4)
                                                                  <ul class="mt-2" >
                                                                        <li>Mobile Number: {{$item->mobileNum}}</li>
                                                                  </ul>
                                                            @else
                                                                  <ul class="mt-2">
                                                                        <li>Account Name: {{$item->accountName}}</li>
                                                                        <li>Account Number:  {{$item->mobileNum}}</li>
                                                                  </ul>
                                                            @endif
                                                            
                                                      </li>
                                                @endforeach
                                          </ul>
                                    </div>
                              </div>
                        </div>
               
            </div>
           
      </section>
     
  
      <script>
            $(document).ready(function(){

            

                  function isFacebookApp() {
                        var ua = navigator.userAgent || navigator.vendor || window.opera;
                        return (ua.indexOf("FBAN") > -1) || (ua.indexOf("FBAV") > -1);
                  } 
                  if(isFacebookApp()){
                       
                        $('#modalAlert').modal('show');
               
                  }

                  $(document).on('click','#cancel',function(){

                        const swalWithBootstrapButtons = Swal.mixin({
                              customClass: {
                                    confirmButton: 'btn-block btn btn-success',
                                    cancelButton: 'btn-block btn btn-danger'
                                    },
                                    buttonsStyling: false
                              })
                        swalWithBootstrapButtons.fire({
                              text: 'Are you sure want to pre-enrollment?',
                              type: 'info',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes, cancel pre-enrollment!',
                              cancelButtonText: 'No, continue pre-enrollment!!',
                        }).then((result) => {
                              if (result.value) {
                                    window.setTimeout(function () { 
                                          window.location.replace('{{Request::root()}}'+'/login');
                                    }, 0); 
                              }
                        })
                  })

                  $(document).on('click','#eval',function(){

                        function isFacebookApp() {
                              var ua = navigator.userAgent || navigator.vendor || window.opera;
                              return (ua.indexOf("FBAN") > -1) || (ua.indexOf("FBAV") > -1);
                        } 

                        if(!isFacebookApp()){
                        
                              $.ajax({

                                    type:'GET',
                                    url:'/preenrollment/evaluate/form',
                                    data:{
                                          a:$("#studid").val(),
                                          b:$("#firstname").val(),
                                          c:$("#lastname").val(),
                                    },
                                    beforeSend: function(){
                                          $("#loadingModal").modal();
                                    },
                                    complete:function(data){
                                          $("#loader").hide();
                                    },
                                    success:function(data) {

                                          if(data == "NSF"){
                                                Swal.fire({
                                                      type: 'error',
                                                      title: 'STUDENT NOT FOUND!',
                                                      showConfirmButton: false,
                                                      timer: 1500,
                                                })
                                          }
                                          else{
                                                Swal.fire({
                                                            type: 'success',
                                                            title: 'STUDENT FOUND!',
                                                            showConfirmButton: false,
                                                            timer: 1500,
                                                      })

                                                $('#results').empty();
                                                $("#results").html(data);
                                          }
                                    
                                    
                                    },
                              })
                        
                        }
                        else{
                              $('#modalAlert').modal('show')
                        }
                  })

            })
      </script>
@endsection
      
            