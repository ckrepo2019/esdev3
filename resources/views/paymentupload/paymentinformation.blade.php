@extends('layouts.app')

@section('headerscript')
    
@endsection

@section('content')
      <section class="content ">
            <div class="row justify-content-center">
                  <div class="col-md-6">
                        <div class="card">
                              <div class="card-header card-title" style="background-color: #88b14b; color: #fff">
                                    Upload Payment Information
                              </div>

                              <div class="card-body">
                                    
                                    <ul style="font-size:15px">
                                          <li>Upload your payment receipts as proof of payment.</li>
                                          <li>Uploaded payment receipts will be processed for Two(2) working days.</li>
                                          <li>You may check your registration status at <a href="/prereg/inquiry/form"">{{Request::root()}}/prereginquiry</a>.</li>
                                    </ul>
                                    <hr>
                                    <div class="row">
                                          <div class="col-md-8 pl-0">
                                                <a href="/payment/online" class="btn btn-block" style="background-color: #88b14b; color: #fff">PROCCEED TO PAYMENT UPLOAD</a>
                                          </div>
                                          {{-- <div class="col-md-4">
                                                <button class="btn btn-danger btn-block" id="cancel">CANCEL</button>
                                          </div> --}}
                                    </div>
                                   
                                   
                              </div>
                        </div>
                        
                  </div>
                 
            </div>
      
      </section>
      <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
      <script>
            $(document).ready(function(){
                  $(document).on('click','#cancel, #cancelsubmit',function(){

                        const swalWithBootstrapButtons = Swal.mixin({
                              customClass: {
                                    confirmButton: 'btn-block btn btn-success',
                                    cancelButton: 'btn-block btn btn-danger'
                                    },
                                    buttonsStyling: false
                              })
                        swalWithBootstrapButtons.fire({
                              text: 'Are you sure want to cancel receipt submission?',
                              // text: "You won't be able to revert this!",
                              type: 'info',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes, cancel receipt submission!',
                              cancelButtonText: 'No, continue receipt submission!!',
                        }).then((result) => {
                              if (result.value) {
                                    window.setTimeout(function () { 
                                          window.location.replace('{{Request::root()}}'+'/login');
                                    }, 0); 
                              }
                        })
                  })
            })
      </script>
@endsection


                        
            

