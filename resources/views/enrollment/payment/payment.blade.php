

@extends('layouts.app')

@section('headerscript')

<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

@php
      $schoolInfo = DB::table('schoolinfo')->first();
@endphp


<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
      * {
        box-sizing: border-box;
      }
      
      body {
        background-color: #f1f1f1;
      }
      
      #regForm {
        /* background-color: #ffffff; */
        margin: 20px auto;
    
        /* padding: 40px; */
        width: 70%;
        min-width: 300px;
      }
      
      h1 {
        text-align: center;  
      }
      
      input {
        padding: 10px;
        /* width: 100%; */
        /* font-size: 17px; */
      
        border: 1px solid #aaaaaa;
      }
      
      /* Mark input boxes that gets an error on validation: */
      input.invalid {
        background-color: #ffdddd;
      }
      
      /* Hide all steps by default: */
      .tab {
        display: none;
      }
      
      button {
        background-color: #4CAF50;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        /* font-size: 17px; */
      
        cursor: pointer;
      }
      
      button:hover {
        opacity: 0.8;
      }
      
      /* #prevBtn {
        background-color: #bbbbbb;
      } */
      
      /* Make circles that indicate the steps of the form: */
      .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;  
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
      }
      
      .step.active {
        opacity: 1;
      }
      
    
      .step.finish {
        background-color: #4CAF50;
      }


      .bg-success {
            color: white !important;
            background-color: {{$schoolInfo->schoolcolor}} !important;

      }

      .btn-success.disabled, .btn-success:disabled {
            background-color: #bbbbbb !important;
            border-color: #bbbbbb !important;
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

      <div class="modal fade overlay w-100" id="student_not_found" style="display: none;" aria-hidden="true" >
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-body">
                              <h5>Student not found. Please review the information you provided or contact your school registrar for more information.</h5>
                        </div>
                  </div>
            
            </div>
      </div>

      <div class="modal fade overlay w-100" id="student_enrolled" style="display: none;" aria-hidden="true" >
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-body">
                              <h5>Student is already enrolled. Please login the parents portal to continue payment. <a href="/">Click here to login.<a/></h5>
                        </div>
                  </div>
            
            </div>
      </div>

      <div class="modal fade" id="recoverCode" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-header bg-success">
                              <h5 class="modal-title">VALIDATE STUDENT INFORMATION</h5>
                            </div>
                        <div class="modal-body">
                              <div class="row">
                                    {{-- <div class="form-group  col-md-12">
                                          <label><strong>FIRST NAME</strong></label>
                                          <input name="studid" id="studid" class="form-control" placeholder="FIRST NAME" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                    <div class="form-group  col-md-12">
                                          <label><strong>LAST NAME</strong></label>
                                          <input  name="firstname" id="firstname" class="form-control" placeholder="LAST NAME" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                    <div class="form-group col-md-12">
                                          <label><strong>SELECT QUESTION TO RECOVER YOU CODE</strong></label>
                                          <select name="lastname" id="lastname" class="form-control">
                                                <option value="" selected>SELECT QUESTION</option>
                                                <option value="1">Guardian's Contact Number</option>
                                                <option value="4">Birth Date</option>
                                          </select>
                                    </div> --}}
                                    <div class="form-group col-md-12" id="answerholder">
                                    
                                    </div>
                                    
                              </div>
                              <div class="row">
                                    <div class="col-md-6">
                                          <h4>Student ID:</h4>
                                    </div>
                                    <div class="col-md-6 text-right">
                                          <h4><span id="sid"></span></h4>
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-success" id="recCodeButton">
                                    GET ID
                              </button>
                        </div>
                  </div>
            </div>
      </div>
      
      <form id="regForm" 
            action="#" 
            method="POST" 
            enctype="multipart/form-data"
            autocomplete="off"
            >
      
            @csrf
            <div class="card">
                  <div class="card-header">
                        APPLICATION INFORMATION
                  </div>
                  <div class="card-body" style="min-height: 400px">
                        <div class="tab">
                              
                              <div class="row">
                                    <div class="form-group col-md-6" style="float:none;margin:auto;">
                                          <label><b>REGISTRATION CODE /
                                                STUDENT ID / LRN *</b></label>
                                          <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="REGISTRATION CODE / STUDENT ID / LRN"  name="studid"  id="studid" required>
                                          <span class="invalid-feedback" role="alert">
                                                <strong>REGISTRATION CODE / STUDENT ID / LRN is required</strong>
                                          </span>
                                    </div>

                                   
                              </div>
                              <div class="row mt-3"> 
                                    <div class="form-group col-md-6" style="float:none;margin:auto;">
                                          <label><b>FIRST NAME *</b></label>
                                          <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="FIRST NAME" id="firstname"  name="firstname" required>
                                          <span class="invalid-feedback" role="alert">
                                                <strong>First Name is required</strong>
                                          </span>
                                    </div>
                              </div>
                              <div class="row mt-3"> 
                                    <div class="form-group col-md-6" style="float:none;margin:auto;">
                                          <label><b>LAST NAME*</b></label>
                                          <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="LAST NAME"  name="lastname" id="lastname" required>
                                          <span class="invalid-feedback" role="alert">
                                                <strong>Last Name is required</strong>
                                          </span>
                                    </div>
                              </div>
                              <div class="row mt-3"> 
                                    <div class="form-group" style="float:none;margin:auto;">
                                        <button class="btn btn-success" type="button" id="evaluate">EVALUATE</button>
                                    </div>
                              </div>

                             
                        </div>
                     
                        <div class="tab" id="paymentpage2">
                              {{-- <div class="row">
                                    <table class="table col-md-12" style="float:none;margin:auto;">
                                          <thead>
                                                <tr>
                                                      <th>ITEM</th>
                                                      <th>AMOUNT</th>
                                                </tr>
                                          </thead>
                                          <tbody id="itemtableholder">

                                          </tbody>
                                          
                                    </table>
                              </div>

                              <div class="row mt-3">  
                                    <div class="form-group col-md-12 pl-0" style="float:none;margin:auto;">
                                          <button type="button" class="btn btn-success" id="nextBtn" onclick="nextPrev(-1)">Previous</button>
                                          <button type="button" class="btn btn-success" id="nextBtn" onclick="nextPrev(1)">Proceed to payment</button>
                                    </div>
                              </div> --}}
                        </div>
                        <div class="tab">
                              <div class="row">
                                    <div class="form-group col-md-12">
                                          <label for="">PAYMENT TYPE</label>
                                          <select name="paymentType" id="paymentType" class="form-control " required>
                                                <option value="">SELECT PAYMENT TYPE</option>
                                                @foreach(DB::table('paymenttype')->where('isonline','1')->where('deleted','0')->get() as $item)
                                                      <option value="{{$item->id}}">{{$item->description}}</option>
                                                @endforeach
                                          </select>
                                          <span class="invalid-feedback" role="alert">
                                                <strong>Payment type is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                          <label for="">REFERENCE NUMBER </label>
                                          <input class="form-control" name="refNum" id="refNum" placeholder="REFERENCE NUMBER" required>
                                          <span class="invalid-feedback" role="alert" style="display:hidden" id="refNumMessage">
                                                <strong>Reference number is required</strong>
                                          </span>
                                          
                                    </div>
                                    <div class="form-group col-md-4">
                                          <label for="">BANK NAME</label>
                                          <select id="bankName" name="bankName" class="form-control" disabled>
                                                <option value="">SELECT BANK</option>
                                                @foreach (DB::table('onlinepaymentoptions')->where('paymenttype','3')->where('deleted','0')->where('isActive','1')->get() as $item)
                                                      <option value="{{$item->optionDescription}}">{{$item->optionDescription}}</option>
                                                @endforeach
                                          </select>
                                          <span class="invalid-feedback" role="alert" style="display:hidden">
                                                <strong>Bank required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                          <label for="">TRANSACTION DATE</label>
                                          <input type="date"  class="form-control" name="transDate" id="transDate" required>
                                          <span class="invalid-feedback" role="alert" style="display:hidden">
                                                <strong>Transaction date is required</strong>
                                          </span>
                                    </div>
                                    <div class="form-group col-md-12">
                                          <label for="">PAYMENT AMOUNT</label>
                                          <input class="form-control" type="text" name="amount" id="amount"  value="" data-type="currency" placeholder="00.00" required>
                                          <span class="invalid-feedback" role="alert" style="display:hidden">
                                                <strong id="amountError">Payment is required</strong>
                                          </span>
                                    </div>
                              </div>
                              <div class="row mt-3">  
                                    <div class="form-group" style="float:none;margin:auto;">
                                          <button type="button" class="btn btn-success" onclick="nextPrev(-1)">Previous</button>
                                          <button type="button" class="btn btn-success" id="validate_payment_info">Continue</button>
                                          {{-- <button type="button" class="btn btn-success" onclick="nextPrev(1)">Continue</button> --}}
                                    </div>
                              </div>
                             
                        </div>
                        <div class="tab">
                              <div class="row">
                                    <div class="form-group col-md-6" style="float:none;margin:auto;">
                                          <label for="">RECEIPT IMAGE</label>
                                          <input type="file" class="form-control" name="recieptImage" id="recieptImage" accept=".png, .jpg, .jpeg" required>
                                          <span class="invalid-feedback" role="alert" style="display:hidden">
                                                <strong>Payment receipt image is required</strong>
                                          </span>
                                    </div>
                              </div>
                              <div class="row mt-2">
                                    <div class="form-group col-md-6" style="float:none;margin:auto;">
                                          <img class="mt-3 w-100" id="receipt"  />
                                    </div>
                              </div>
                              <div class="row mt-2">
                                    <div class="form-group col-md-6" style="float:none;margin:auto;">
                                          <button type="button" class="btn btn-success" onclick="nextPrev(-1)">Previous</button>
                                          <button type="button" class="btn btn-success" onclick="nextPrev(1)">Proceed</button>
                                    </div>
                              </div>
                          
                        </div>
                        <div class="tab">
                              <div class="row mt-3">
                                    <div class="form-group col-md-8" style="float:none;margin:auto;">
                                          <label><b>REGISTRATION CODE /
                                                STUDENT ID / LRN *</b></label>
                                          <input class="form-control" readonly id="regsum">
                                    </div>
                              </div>
                              <div class="row mt-3">
                                    <div class="form-group col-md-8" style="float:none;margin:auto;">
                                          <label><b>FIRST NAME</b></label>
                                          <input class="form-control" readonly id="fnsum">
                                    </div>
                              </div>
                              <div class="row mt-3">
                                    <div class="form-group col-md-8" style="float:none;margin:auto;">
                                          <label><b>LAST NAME</b></label>
                                          <input class="form-control" readonly id="lnsum">
                                    </div>
                              </div>
                              <div class="row mt-3">
                                    <div class="form-group col-md-8" style="float:none;margin:auto;">
                                          <label><b>PAYMENT TYPE</b></label>
                                          <input class="form-control" readonly id="ptsum">
                                    </div>
                              </div>
                              <div class="row mt-3">
                                    <div class="form-group col-md-8" style="float:none;margin:auto;">
                                          <label><b>REFERENCE NUMBER</b></label>
                                          <input class="form-control" readonly id="rnsum">
                                    </div>
                              </div>
                              <div class="row mt-3">
                                    <div class="form-group col-md-8" style="float:none;margin:auto;">
                                          <label><b>BANK NAME</b></label>
                                          <input class="form-control" readonly id="bnsum">
                                    </div>
                              </div>
                              <div class="row mt-3">
                                    <div class="form-group col-md-8" style="float:none;margin:auto;">
                                          <label><b>PAYMENT TRANSACTION DATE</b></label>
                                          <input class="form-control" type="date" readonly id="tdsum">
                                    </div>
                              </div>
                              <div class="row mt-3">
                                    <div class="form-group col-md-8" style="float:none;margin:auto;">
                                          <label><b>PAYMENT AMOUNT</b></label>
                                          <input class="form-control" readonly id="pasum">
                                    </div>
                              </div>
                              <div class="row mt-3">
                                    <div class="form-group col-md-8" style="float:none;margin:auto;">
                                          <label><b>PAYMENT RECEIPT IMAGE</b></label>
                                          <img class="mt-3 w-100" id="receiptsum"  />
                                    </div>
                              </div>
                              <div class="row mt-3" id="final_process">  
                                    <div class="form-group" style="float:none;margin:auto;">
                                          <button type="button" class="btn btn-success" onclick="nextPrev(-1)">Previous</button>
                                          <button type="submit" class="btn btn-success">Submit Payment</button>
                                    </div>
                              </div>
                              <div class="row mt-3" id="processing_button" hidden="hidden">  
                                    <div class="form-group" style="float:none;margin:auto;">
                                          <button disabled class="btn btn-success" type="button" id="processing">PROCESSING ...</button>
                                      </div>
                              </div>

                             
                        </div>
                        <div class="tab">
                              <div class="row">
                                    <div class="form-group col-md-6" style="float:none;margin:auto;">
                                          
                                          <h2 class="text-success text-center">
                                                <i class="fas fa-check-square"></i> <b>PAYMENT SUBMITTED SUCCESSFUL</b>
                                          </h2>

                                          <h5 style="text-align: justify;" class="mt-4">
                                                Your payment will be processed for 2 (two) working days. 
                                                You will receive a text message regarding your enrollment status. 
                                          </h5>
                                          <h5 style="text-align: justify;">
                                                For more information please contact the school registrar. 
                                          </h5>

                                          <h5 style="text-align: justify;" class="mt-4">
                                                Please visit this page again to inquire your last online payment transaction. Thank you for your payment.
                                          </h5>
                                    </div>
                              </div>
                              <div class="row mt-3">  
                                    <div class="form-group" style="float:none;margin:auto;">
                                          <a href="{{asset( DB::table('schoolinfo')->first()->websitelink)}}" class="btn btn-success">Done</a>
                                    </div>
                              </div>
                        </div>
                        
                  </div>
            </div>
            <div style="text-align:center;margin-top:40px;" class="stepHolder">
                  <span class="step"></span>
                  <span class="step"></span>
                  <span class="step"></span>
                  <span class="step"></span>
                  <span class="step"></span>
            </div>
      </form>

      <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
      <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>

      <script>
            $(document).ready(function(){




                  function isFacebookApp() {

                        var ua = navigator.userAgent || navigator.vendor || window.opera;
                        return (ua.indexOf("FBAN") > -1) || (ua.indexOf("FBAV") > -1);

                  } 
                  if(isFacebookApp()){
                        
                        $('#modalAlert').modal('show')
               
                  }

            })
      </script>

      <script>

            $(document).ready(function(){

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                  });

                  var allowLess = false;

                  $(document).on('click','#proceed_to_payment',function(){

                        $('#amount').val($('#total_fee')[0].innerText)

                        if($('#total_fee').attr('data-less') == 1){

                              allowLess = true;

                        }

                  })

                  $(document).on('click','#cancel_payment',function(){


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
                                    $.ajax( {
                                          url: '/preenrollment/cancel/paymnent/'+$('#studid').val()+'/'+$(this).attr('data-id'),
                                          type: 'GET',
                                          success:function(data) {

                                                Toast.fire({
                                                      type: 'success',
                                                      title: 'Canceled successfully!'
                                                })

                                                nextPrev(-1)
                                          
                                          }
                                    } );

                              }
                        })

                  })


                  $(document).on('click','#validate_payment_info',function(){

                        var validInput = true;

                        if(!allowLess){

                              if(parseFloat($('#amount').val().replace(",","")) < $('#total_fee')[0].innerText.replace(",","") ){
                                    validInput = false
                              }

                        }

                        if(validInput){


                              $('#amountError')[0].innerText = 'Payment is required'

                              $.ajax({
                                    type:'GET',
                                    url:'/enrollment/validaterefnum?refNum='+$('#refNum').val(),
                                    success:function(data) {

                                          if(data > 0){

                                                $('#refNum').addClass('is-invalid')
                                                $('#refNumMessage')[0].innerHTML = '<strong>Reference Number already exist</strong>'

                                          }
                                          else{
                                                nextPrev(1)
                                                // $('#refNum').removeClass('is-invalid')
                                                // $('#refNumMessage')[0].innerHTML = '<strong>Reference is required</strong>'
                                          }

                                    },
                              })

                        }
                        else{

                              $('#amountError')[0].innerText = 'The given amount is less than the required downpayment'
                              $('#amount').addClass('is-invalid')

                        }

                  
                  })


                  $( '#regForm' )
                  .submit( function( e ) {
                       
                        var inputs = new FormData(this)


                        var counter = 1;
                        var summary = [];
                        
                        var length = $('.dassitem').length;

                        $('#final_process').attr('hidden','hidden')
                        $('#processing_button').removeAttr('hidden')

                        setInterval(doBounce($('#processing'), 25, '10px', 300), 3000);

                        function doBounce(element, times, distance, speed) {

                              for(i = 0; i < times; i++) {
                                    element.animate({marginTop: '-='+distance},speed)
                                          .animate({marginTop: '+='+distance},speed);
                              }        
                        }

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
                              url: '/payment/online/submitpaymentrecieptv2',
                              type: 'POST',
                              data: inputs,
                              processData: false,
                              contentType: false,
                              success:function(data) {
                                    nextPrev(1)
                              }
                        } );
                        e.preventDefault();
            } );

                  

                  $(document).on('change','#paymentType',function(){

                        if($(this).val() == '3'){

                              $('#bankName').removeAttr('disabled')
                              $('#refNum').removeAttr('disabled')
                              $('#bankName').attr('required','required')

                        }
                        else{

                              $('#bankName').attr('disabled','disabled')
                              $('#refNum').removeAttr('disabled')
                              $('#bankName').removeAttr('required')

                        }


                  })

                  function readURL(input) {
                        if (input.files && input.files[0]) {
                              var reader = new FileReader();
                              
                              reader.onload = function (e) {

                                    $('#receipt').attr('src', e.target.result);
                                    $('#receiptsum').attr('src', e.target.result);
                              
                              }
                              
                              reader.readAsDataURL(input.files[0]);
                        }
                  }
                  
                  $("#recieptImage").change(function(){
                        readURL(this);
                  });

                  $(function () {
                        $('.select2').select2({
                              theme: 'bootstrap4'
                        })
                  })

            })

            $(document).on('click','#evaluate',function(){

                  var validInput = true;

                  // console.log("sdfsdf")

                  if($('#studid').val() == '' || $('#studid').val().trim().length == 0){
                        validInput = false;
                        $('#studid').addClass('is-invalid')
                  }
                  else{
                        $('#studid').removeClass('is-invalid')
                  }

                  if($('#firstname').val() == '' || $('#firstname').val().trim().length == 0){
                        validInput = false;
                        $('#firstname').addClass('is-invalid')
                  }
                  else{
                        $('#firstname').removeClass('is-invalid')
                  }

                  if($('#lastname').val() == '' || $('#lastname').val().trim().length == 0){
                        validInput = false;
                        $('#lastname').addClass('is-invalid')
                  }
                  else{
                        $('#lastname').removeClass('is-invalid')
                  }

                 

                  if(validInput && currentTab == 0){

                        $.ajax({

                              type:'GET',
                              url:'/evaluate',
                              data:{
                                    a:$("#studid").val(),
                                    b:$("#firstname").val(),
                                    c:$("#lastname").val(),
                              },
                              success:function(data) {

                                    $('#paymentpage2').empty()
                                    $('#paymentpage2').append(data)

                                   

                              },
                        })

                        nextPrev(1)
                  }

            })

            

            var currentTab = 0; // Current tab is set to be the first tab (0)
            showTab(currentTab); // Display the current tab
            
            function showTab(n) {
            // This function will display the specified tab of the form...
            var x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            //... and fix the Previous/Next buttons:
            if (n == 0) {
            document.getElementById("prevBtn").style.display = "none";
            } else {
            document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
            document.getElementById("nextBtn").innerHTML = "Submit";
            } else {
            document.getElementById("nextBtn").innerHTML = "Next";
            }
            //... and run a function that will display the correct step indicator:
            fixStepIndicator(n)
            }
            
            function nextPrev(n) {

                  var x = document.getElementsByClassName("tab");
                        
                  if (n == 1 && !validateForm()) return false;

                  if (currentTab+n < x.length) {
                  
                        x[currentTab].style.display = "none";

                  }

                  currentTab = currentTab + n;

                  if(currentTab == 0){

                        if($('#gradelevelid').val() == '14' || $('#gradelevelid').val() == '15'){

                              $('#gradelevelid').attr('disabled','disabled')

                        }
                       
                  }
                  else{

                        $('#gradelevelid').removeAttr('disabled')

                        if($('#gradelevelid').val() == '14' || $('#gradelevelid').val() == '15'){

                              $('#gradelevelid').removeAttr('disabled')
                              $('#studstrand').removeAttr('disabled')

                        }

                  }
                  
                  if(currentTab == 0){
                        $('.card-header')[0].innerText = 'STUDENT INFORMATION'
                  }

                  else if(currentTab == 1){

                        $('.card-header')[0].innerText = 'PAYABLE INFORMATION'

                  }
                  else if(currentTab == 2){

                        $('.card-header')[0].innerText = 'PAYMENT INFOMATION'

                  }
                  else if(currentTab == 3){
                        $('.card-header')[0].innerText = 'PAYMENT RECEIPT IMAGE'
                  }
                  else if(currentTab == 4){

                        $('.card-header')[0].innerText = 'PAYMENT SUMMARY'

                        $('#regsum').val($('#studid').val())
                        $('#fnsum').val($('#firstname').val())
                        $('#lnsum').val($('#lastname').val())
                        $('#bnsum').val($('#bankName option:selected').html())
                        $('#ptsum').val($('#paymentType option:selected').html())
                        $('#rnsum').val($('#refNum').val())
                        $('#tdsum').val($('#transDate').val())
                        $('#pasum').val($('#amount').val())

                  }
                  else if(currentTab == 5){

                        $('.card-header')[0].innerText = 'PAYMENT COMPLETE'

                  }

                  if(
                        currentTab == 4
                  ){

                        $('#agree').prop("checked",false)
                        $('#nextBtn').attr('disabled','disabled')

                  }else{
                        $('#nextBtn').removeAttr('disabled')
                  }

                  if (currentTab >= x.length) {
                  
                        document.getElementById("regForm").submit();
                        return false;
                  }
                  else{

                        showTab(currentTab);

                  }
              
            }
            
            function validateForm() {

                  var x, y, i, valid = true;

                  x = document.getElementsByClassName("tab");

                  y = x[currentTab].getElementsByTagName("input");


                  for (i = 0; i < y.length; i++) {

                        if (y[i].value == "" && $(y[i]).attr('required') != undefined && ( $('#studtype').val() != 3 )) {
                             
                              y[i].className += " is-invalid";
                         
                              valid = false;
                        }
                        else{
                              $(y[i]).removeClass('is-invalid')
                        }
                  }

                  yselect = x[currentTab].getElementsByTagName("select");

                  for (i = 0; i < yselect.length; i++) {

                        if (yselect[i].value == "" && $(yselect[i]).attr('required') != undefined) {
                             
                              yselect[i].className += " is-invalid";
                         
                              valid = false;
                        }
                        else{

                              $(yselect[i]).removeClass('is-invalid')

                        }
                  }
                 
            // If the valid status is true, mark the step as finished and valid:
            if (valid) {
                  document.getElementsByClassName("step")[currentTab].className += " finish";
            }
            return valid; // return the valid status
            }
            
            function fixStepIndicator(n) {
            
            var i, x = document.getElementsByClassName("step");
            for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
            }
            
            x[n].className += " active";
            }




            
      </script>


      <script>
            $(document).ready(function(){
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

@endsection
      
            