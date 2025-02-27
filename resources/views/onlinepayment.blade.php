{{-- 
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Essentiel</title>

    <link rel="stylesheet" href="{{asset('dist/css/select2-bootstrap4.min.css')}}">
    <script type="text/javascript" src="{{asset('assets/scripts/jquery-3.3.1.min.js')}}"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{asset('assets/css/main.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets\css\login.css')}}">
    <style>
          fieldset.scheduler-border {
            border: 2px groove #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow:  0px 0px 0px 0px #000;
                        box-shadow:  0px 0px 0px 0px #000;
            background-color: #fbfbfb;
            min-height: 400px
        
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
            .navbarlogin{
                  background-color: #88b14b
            }
            .wrapper {
                  background-image: linear-gradient( 405deg, #88b14b 13%, #fbfbfb 13%, #fbfbfb 87%, #88b14b 68% );
             }
           
    </style>
   

</head>
<body  class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
      <div class="wrapper">
            <div class="content-wrapper container min-vh-100">
            
            <div class="row navbarlogin pt-2">
                  <nav class="navbar">
                        <a class="nav-link text-white" href="/login"><i class="fas fa-user-edit mr-2"></i> {{ __('Login') }}</a>
                        <a class="nav-link text-white" href="/prereg/{{Crypt::encrypt('new')}}"><i class="fas fa-user-edit mr-2"></i> {{ __('Pre-Registration') }}</a>
                        <a class="nav-link text-white" href="/payment/online"><i class="fas fa-coins mr-2"></i>  {{ __('Online-payment') }}</a>
                  </nav>
            </div> --}}

@extends('layouts.app')

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


@section('content')
{{-- @if(session()->get('paymenInfo')!=null)
      <div class="modal fade" id="updatemodal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header" style="background-color: #88b14b; color:white">
                        <h5 class="modal-title">Payment Reciept Upload Successful!</h5>
                  </div>
                  <div class="modal-body">
                        <div class="alert alert-info">
                                        
                              <h5><i class="icon fas fa-info"></i> Your payment was submitted to the finance Dept!</h5>
                              Thank you for your payment.

                        </div>
                        <div class="row">
                              <div class="col-md-6">
                                    <div class="form-group">
                                          <label>Name</label>
                                          <input class="form-control" readonly value="{{session()->get('paymenInfo')[0]->last_name}}, {{session()->get('paymenInfo')[0]->first_name}}">
                                    </div>
                                    <div class="form-group">
                                          <label>Code</label>
                                          <input class="form-control" readonly value="{{session()->get('paymenInfo')[0]->queingcode}}">
                                    </div>
                                  
                                    <div class="form-group">
                                          <label>Amount</label>
                                        
                                          <input class="form-control" readonly value="{{session()->get('paymenInfo')[0]->amount}}">
                                    </div>
                              </div>
                              <div class="col-md-6">
                                    <img src="{{asset(session()->get('paymenInfo')[0]->picUrl)}}" class="w-100">
                              </div>
                        </div>
                   

                        <div class="alert alert-success mt-4">

                              Please check your registration status in this link <a href="/prereg/inquiry/form"">{{Request::root()}}/prereginquiry</a> after two(2) working days.
                              
                        </div>
                        <hr>
                        <a href="/payment/paymentinformation" class="btn" style="background-color: #88b14b; color:white">UPLOAD ANOTHER RECEIPT</a>
                        <a href="/" class="btn text-white float-right btn-primary" id="validate" >DONE</a>

                  </div>
            </div>
            </div>
      </div>
@endif --}}


            <section class="content ">
                  <div class="row ">
                        <div class="col-md-6">
                              <div class="card">
                                    <div class="card-header">
                                          Online - Payment Upload Form
                                    </div>
                                    <div class="card-body">
                                          <form action="/payment/online/submitreceipt" method="POST" enctype="multipart/form-data">
                                                @csrf
                                           
                                                <div class="form-group row">
                                                     
                                                      <label class="col-md-4 col-form-label">REGISTRATION CODE</label>
                                                      <div class="col-md-8">
                                                            <input type="password" class="form-control @error('queingcode') is-invalid @enderror" id="queingcode" name="queingcode" value="{{old('queingcode')}}" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off">
                                                            @if($errors->has('queingcode'))
                                                                  <span class="invalid-feedback" role="alert">
                                                                  <strong>{{ $errors->first('queingcode') }}</strong>
                                                                  </span>
                                                            @endif
                                                      </div>
                                                     
                                                </div>
                                                <div class="form-group row">
                                                      <label class="col-md-4 col-form-label">STUDENT FIRST NAME</label>
                                                      <div class="col-md-8">
                                                            <input class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" value="{{old('firstname')}}" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off">
                                                            @if($errors->has('firstname'))
                                                                  <span class="invalid-feedback" role="alert">
                                                                  <strong>{{ $errors->first('firstname') }}</strong>
                                                                  </span>
                                                            @endif
                                                      </div>
                                                </div>
                                                <div class="form-group row">
                                                      <label class="col-md-4 col-form-label">STUDENT LAST NAME</label>
                                                      <div class="col-md-8">
                                                            <input class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{old('lastname')}}" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off">
                                                            @if($errors->has('lastname'))
                                                                  <span class="invalid-feedback" role="alert">
                                                                  <strong>{{ $errors->first('lastname') }}</strong>
                                                                  </span>
                                                            @endif
                                                      </div>
                                                </div>
                                                <div class="form-group row">
                                                      <label class="col-md-4 col-form-label">
                                                           
                                                      </label>
                                                      <div class="col-md-4">
                                                            <button type="button" class=" btn btn-block text-white" style="background-color: #88b14b " id="validate">VALIDATE </button>
                                                      </div>
                                                      <div class="col-md-4">
                                                            {{-- <button type="button" class=" btn btn-block btn-danger text-white" id="cancelvalidate">CANCEL</button> --}}
                                                      </div>
                                                      
                                                </div>
                                                <div class="row">

                                                </div>
                                                
                                                <hr>
                                                <div class="row"> 
                                                      <h4 class="mt-0 col-md-6 p-0">Student Information</h4><div id="message" class=" col-md-6 p-0 text-right mt-1"></div>
                                                </div>
                                                <div class="form-group">
                                                      <label for="">STUDENT NAME</label>
                                                      <input class="form-control" name="name" disabled id="name" readonly value="{{old('name')}}">
                                                </div>
                                                <div class="form-group">
                                                      <label for="">GRADE LEVEL</label>
                                                      <input class="form-control" name="gradelevel" disabled id="gradelevel" readonly value="{{old('gradelevel')}}">
                                                      
                                                </div>
                                                <div class="form-group">
                                                      <label for="">PAYMENT TYPE</label>
                                                      <select name="paymentType" id="paymentType" class="form-control @error('paymentType') is-invalid  @enderror" disabled>
                                                            @if($errors->any())
                                                                  <option value="" {{old('paymentType') == NULL ?'selected':''}}>SELECT PAYMENT TYPE</option>
                                                                  <option value="1" {{old('paymentType') == '1' ?'selected':''}}>BANK DEPOSIT</option>
                                                                  <option value="2" {{old('paymentType') == '2' ?'selected':''}}>GCASH</option>
                                                                  <option value="">SELECT PAYMENT TYPE</option>
                                                                  @foreach(DB::table('paymenttype')->where('isonline','1')->get() as $item)
                                                                        <option value="{{$item->id}}" {{old('paymentType') == $item->id ?'selected':''}}>{{$item->description}}</option>
                                                                  @endforeach
                                                                  {{-- <option value="">SELECT PAYMENT TYPE</option>
                                                                  <option value="1">BANK DEPOSIT</option>
                                                                  <option value="2">GCash</option> --}}
                                                            @else
                                                                  <option value="">SELECT PAYMENT TYPE</option>
                                                                  @foreach(DB::table('paymenttype')->where('isonline','1')->get() as $item)
                                                                        <option value="{{$item->id}}">{{$item->description}}</option>
                                                                  @endforeach
                                                            @endif
                                                      </select>
                                                      @if($errors->has('paymentType'))
                                                            <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('paymentType') }}</strong>
                                                            </span>
                                                      @endif
                                                </div>
                                                <div class="form-group">
                                                      <label for="">RECEIPT IMAGE</label>
                                                      <input type="file" class="form-control @error('recieptImage') is-invalid  @enderror" name="recieptImage" disabled id="recieptImage" >
                                                      @if($errors->has('recieptImage'))
                                                            <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('recieptImage') }}</strong>
                                                            </span>
                                                      @endif
                                                </div>
                                                <div class="form-group">
                                                      <label for="">REFERENCE NUMBER</label>
                                                      <input  class="form-control @error('refNum') is-invalid  @enderror" name="refNum" disabled id="refNum"  value="{{old('refNum')}}">
                                                      @if($errors->has('refNum'))
                                                            <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('refNum') }}</strong>
                                                            </span>
                                                      @endif
                                                </div>
                                                <div class="form-group">
                                                      <label for="">PAYMENT AMOUNT</label>
                                                      <input  class="form-control @error('amount') is-invalid  @enderror" name="amount" disabled id="amount"  value="{{old('amount')}}">
                                                      @if($errors->has('amount'))
                                                            <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('amount') }}</strong>
                                                            </span>
                                                      @endif
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-8">
                                                            <button type="submit" class=" btn btn-block text-white" id="submitInfo" style="background-color: #88b14b "  disabled="disabled">SUBMIT PAYMENT RECEIPT</button>

                                                      </div>
                                                      <div class="col-md-4">
                                                            {{-- <button type="button" class=" btn-block btn btn-danger" id="cancelsubmit"   disabled="disabled">CANCEL </button> --}}
                                                      </div>
                                                     
                                                </div>
                                              
                                          </form>
                                    </div>
                              </div>
                        </div>
                        <div class="col-md-6">
                              <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Uploaded Payment</legend>
                                    <img class="mt-3" id="receipt"  style="max-height:400px"/>
                              </fieldset>
                              
                        </div>
                  </div>
            </section>
            <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
            <script>
                  $(document).ready(function(){
                        
                        // $('#cancelsubmit').hide()

                        // $(document).on('click','#cancelvalidate, #cancelsubmit',function(){

                        //       const swalWithBootstrapButtons = Swal.mixin({
                        //             customClass: {
                        //                   confirmButton: 'btn-block btn btn-success',
                        //                   cancelButton: 'btn-block btn btn-danger'
                        //                   },
                        //                   buttonsStyling: false
                        //             })
                        //       swalWithBootstrapButtons.fire({
                        //             text: 'Are you sure want to cancel receipt submission?',
                        //             // text: "You won't be able to revert this!",
                        //             type: 'info',
                        //             showCancelButton: true,
                        //             confirmButtonColor: '#3085d6',
                        //             cancelButtonColor: '#d33',
                        //             confirmButtonText: 'Yes, cancel receipt submission!',
                        //             cancelButtonText: 'No, continue receipt submission!!',
                        //       }).then((result) => {
                        //       if (result.value) {
                        //             window.setTimeout(function () { 
                        //                   window.location.replace('{{Request::root()}}'+'/login');
                        //             }, 0); 
                        //       }
                        //       })
                        // })
                        
                        // @if(session()->get('paymenInfo')!=null)
                        //       console.log("sdfsdf");
                        //       $('#updatemodal').modal('show');
                        //       $('#updatemodal').modal({
                        //             backdrop: 'static',
                        //             keyboard: false
                        //       })

                        //       window.onbeforeunload = function() {
                                
                        //             window.setTimeout(function () { 
                        //                   window.location.replace('{{Request::root()}}'+'/payment/paymentinformation');
                        //             }, 0); 
                        //             window.onbeforeunload = null;
                        //       }

                        // @endif



                        @if ($errors->any())
                              @if(old('name')!=null && old('queingcode')!=null )
                                   
                                    $('#gradelevel').attr('disabled',false)
                                    $('#paymentType').attr('disabled',false)
                                    $('#recieptImage').attr('disabled',false)
                                    $('#name').attr('disabled',false)
                                    $('#refNum').attr('disabled',false)
                                    $('#amount').attr('disabled',false)
                                    $('#submitInfo').removeAttr('disabled')
                                    $('#cancelsubmit').removeAttr('disabled')

                                    // $('#gradelevel').val('{{old('gradelevel')}}')
                                    // // $('#paymentType').val('{{old('paymentType')}}')
                                    // // $('#recieptImage').val('{{old('recieptImage')}}')
                                    // $('#name').val('{{old('name')}}')
                                    // $('#amount').val('{{old('amount')}}')
                                 
                                    
                              @endif
                        @endif
        
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
        
        
                        $(document).on('click','#validate',function(){
                                $.ajax({
                                      type:'GET',
                                      url:'/payment/online/validateinfo/'+$('#queingcode').val()+'/'+$('#firstname').val()+'/'+$('#lastname').val(),
                                      success:function(data){
                                            if(data.length > 0){
                                                      $('#message').addClass('text-success')
                                                      $('#message').removeClass('text-danger')
                                                      $('#name').val(data[0].last_name + ', ' + data[0].first_name )
                                                      $('#gradelevel').val(data[0].levelname)
                                                      $('#name').attr('disabled',false)
                                                      $('#gradelevel').attr('disabled',false)
                                                      $('#paymentType').attr('disabled',false)
                                                      $('#recieptImage').attr('disabled',false)
                                                      $('#message').removeClass('bg-danger')
                                                      $('#message').addClass('text-success')
                                                      $('#message').text('Student found')
                                                      $('#amount').attr('disabled',false)
                                                      $('#submitInfo').removeAttr('disabled')
                                                      $('#cancelsubmit').removeAttr('disabled')
                                                      $('#submit').removeAttr('disabled')
                                                      $('#refNum').removeAttr('disabled')
                                                      $('#cancelvalidate').hide()
                                                      $('#cancelsubmit').show()
                                                  
                                                  
                                            }
                                            else{
                                                  $('#message').text('Pre-registration not found')
                                                  $('#message').addClass('text-danger')
                                                  $('#name').val('');
                                                  $('#gradelevel').val('');
                                                  $('#recieptImage').val('');
                                                  $('#paymentType').val('');
                                                  $('#name').attr('disabled',true)
                                                  $('#gradelevel').attr('disabled',true)
                                                  $('#paymentType').attr('disabled',true)
                                                  $('#recieptImage').attr('disabled',true)
                                                  $('#amount').attr('disabled',true)
                                                  $('#submitInfo').attr('disabled',true)
                                                  $('#cancelsubmit').attr('disabled',true)
                                                  $('#refNum').attr('disabled',true)
                                                  $('#cancelvalidate').show()
                                                  $('#cancelsubmit').hide()
                                            }
                                           
                                      }
                                })
                        })
                  })
            </script>

@endsection
      
            {{-- </div>
            
      </div>
      @include('sweetalert::alert')
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script>
          $(document).ready(function(){

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


                $(document).on('click','#validate',function(){
                        $.ajax({
                              type:'GET',
                              url:'/payment/online/validateinfo/'+$('#queingcode').val()+'/'+$('#firstname').val(),
                              success:function(data){
                                    if(data.length > 0){
                                          $('#name').val(data[0].last_name + ', ' + data[0].first_name )
                                          $('#gradelevel').val(data[0].levelname)
                                          $('#name').attr('disabled',false)
                                          $('#gradelevel').attr('disabled',false)
                                          $('#paymentType').attr('disabled',false)
                                          $('#recieptImage').attr('disabled',false)
                                          $('#message').removeClass('bg-danger')
                                          $('#message').addClass('text-success')
                                          $('#message').text('Pre-registration found')
                                          $('#amount').attr('disabled',false)
                                    }
                                    else{
                                          $('#message').text('Pre-registration not found')
                                          $('#message').addClass('text-danger')
                                          $('#name').val('');
                                          $('#gradelevel').val('');
                                          $('#recieptImage').val('');
                                          $('#paymentType').val('');
                                          $('#name').attr('disabled',true)
                                          $('#gradelevel').attr('disabled',true)
                                          $('#paymentType').attr('disabled',true)
                                          $('#recieptImage').attr('disabled',true)
                                          $('#amount').attr('disabled',true)
                                    }
                                   
                              }
                        })
                })
          })
    </script>
</body>
</html> --}}
