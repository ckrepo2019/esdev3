
@extends('layouts.app')

@section('headerscript')

      <script type="text/javascript" src="{{asset('assets/scripts/jquery-3.3.1.min.js')}}"></script>

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
                  .center {
                        padding: 120px 0;
                        font-size: 50px;
                        text-align: center;
                  }


      </style>

      @endsection

@section('content')

<section class="fxt-template-animation fxt-template-layout20 m-0">
    <div class="container" id="thiscontainer">
		<div class="row">
			<div class="col-md-6">
                        <div class="card">
                              <div class="card-header ">
                                    <h3 class="card-title mb-0">USERNAME / PASSWORD RECOVERY</h3>
                              </div>
                              <div class="card-body">
                                    <div class="form-group">
                                          <label><strong>FIRST NAME</strong></label>
                                          <input autocomplete="off" id="fname" class="form-control" placeholder="FIRST NAME" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                    <div class="form-group">
                                          <label><strong>LAST NAME</strong></label>
                                          <input autocomplete="off" id="lname" class="form-control" placeholder="LAST NAME" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                    <div class="form-group" id="answerholder">
                                          <label><strong>BIRTH DATE</strong></label>
                                          <input autocomplete="off" type="date" id="answer" name="answer" class="form-control" >
                                    </div>
                                    <button class="btn btn-success" id="recover" >GET CREDENTIALS</button>
                                    <a class="btn btn-success" href="/login">LOGIN TO PORTAL</a>
                              </div>
                        </div>
                        
                  </div>
                  <div class="col-md-6 ">
                        {{-- <fieldset class="scheduler-border ">
                              <legend class="scheduler-border">Get your username and password here</legend>
                              <div class="row">
                                    <div class="col-md-12 text-danger">
                                          <p>Message : <span id="message"></span></p>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-6">
                                          <h3>Student ID:</h3>
                                    </div>
                                    <div class="col-md-6 text-right">
                                          <h3><span id="sid"></span></h3>
                                    </div>
                              </div>
                              <hr>
                              <div class="row">
                                    <div class="col-md-6">
                                          <h3>Username:</h3>
                                    </div>
                                    <div class="col-md-6 text-right">
                                          <h3><span id="username"></span></h3>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-6">
                                          <h3>Password:</h3>
                                    </div>
                                    <div class="col-md-6 text-right">
                                          <h3><span id="password"></span></h3>
                                    </div>
                              </div>
							  <hr>
                              <label for="">Parent</label>
                              <div class="row">
                                    <div class="col-md-6">
                                          <h3>Username:</h3>
                                    </div>
                                    <div class="col-md-6 text-right">
                                          <h3><span id="username_parent"></span></h3>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-6">
                                          <h3>Password:</h3>
                                    </div>
                                    <div class="col-md-6 text-right">
                                          <h3><span id="password_parent"></span></h3>
                                    </div>
                              </div>
                        </fieldset> --}}
<fieldset class="scheduler-border ">
                              <legend class="scheduler-border">Get your username and password here</legend>
                              <div class="row">
                                    <div class="col-md-12 text-danger">
                                          <p>Message : <span id="message"></span></p>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-6">
                                          <h3>Student ID:</h3>
                                    </div>
                                    <div class="col-md-6 text-right">
                                          <h3><span id="sid"></span></h3>
                                    </div>
                              </div>
                              <hr>
                              <div class="row with_contact" hidden>
                                    <div class="col-md-12">
                                          <p class="mb-0">The Username/s and Password/s were sent to this contact number.</p>
                                    </div>
                              </div>
                              <hr class="with_contact"  hidden>
                              <div class="row with_contact"  hidden>
                                    <div class="col-md-6">
                                          <h5>Student Contact:</h5>
                                    </div>
                                    <div class="col-md-6 text-right">
                                          <h5 id="student_contact"></h5>
                                    </div>
                              </div class="with_contact"  hidden>
                              <div class="row with_contact"  hidden>
                                    <div class="col-md-6">
                                          <h5>Parent Contact:</h5>
                                    </div>
                                    <div class="col-md-6 text-right">
                                          <h5 id="parent_contact"></h5>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12 with_contact"  hidden>
                                          <p>Note: If your contact number is not updated, please proceed to the school registrar to change and update your contact information. Thank you</p>
                                    </div>
                              </div>
                        </fieldset>
                  </div>
		</div>
	</div>
</div>
    
                  
           
   
            
@endsection
      
@section('footerscript')

     
      <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>

      <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
      <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
      <script>
            $(document).ready(function(){
            
                  var get = true;

                  // const Toast = Swal.mixin({
                  //       toast: true,
                  //       position: 'top-end',
                  //       showConfirmButton: false,
                  //       timer: 2000,
                  // })

                

                  $(document).on('click','#recover',function(){

                        if(get == false){
                              Toast.fire({
                                    type: 'info',
                                    title: 'Please reload the page'
                              })
                              return false
                        }

                        $('.with_contact').attr('hidden','hidden')

                        $.ajax({
                              type:'GET',
                              url:'/proccess/recoverycode',
                              data:{
                                    a:$("#fname").val(),
                                    b:$("#lname").val(),
                                    c:4,
                                    d:$("#answer").val()
                              },
                              success:function(data) {

                                     if(data[0].sid != 'Not Found'){
                                           $('.with_contact').removeAttr('hidden')
                                           $('#sid').text(data[0].sid)
                                           $('#message').text(data[0].message)
                                           $('#student_contact').text(data[0].scontactno)
                                           $('#parent_contact').text(data[0].pcontactno)
                                           $('#message').text(data[0].message)  
                                           // $('#recover').attr('disabled','disabled')
                                           get = false
                                     }else{
                                           $('#sid').text(data[0].sid)
                                           $('#message').text(data[0].message)
                                     }
                                    
                                    //$('#sid').text(data[0].sid)
                                    //$('#username').text(data[0].username)
                                    //$('#password').text(data[0].password)
                                    //$('#username_parent').text(data[0].pusername)
                                    //$('#password_parent').text(data[0].ppassword)
                                    //$('#message').text(data[0].message)
                                   
                              },
                              error:function(data) {
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'Something went wrong'
                                    })
                              },

                        })
                  })

                 
            })
			
			//  const Toast = Swal.mixin({  
                  //           toast: true,
                  //           position: 'top-end',
                  //           showConfirmButton: false,
                  //           timer: 2000,
                  //     })
			
			var keysPressed = {};
			
			
			
			document.addEventListener('keydown', (event) => {
                        keysPressed[event.key] = true;
                        if (keysPressed['p'] && event.key == 'v') {
                              Toast.fire({
                                          type: 'warning',
                                          title: 'Date Version: 10282023'
                                    })
                        }
                  });
      </script>
	

@endsection