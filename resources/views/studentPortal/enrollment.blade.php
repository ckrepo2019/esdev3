
@extends('layouts.app')

@section('headerscript')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@endsection
    

@section('content')
      @php
            $academicprogram = DB::table('academicprogram')->get();
      @endphp
      <div class="row">
            <div class="col-md-12">
                  <div class="card">
                        <div class="card-body">
                              <div class="row">
                                    <div class="col-md-4 form-group">
                                          <label for="">Academic Program</label>
                                          <select name="" id="input_acadprog" class=" form-control">
                                                <option value="">Select Academic Program</option>
                                                @foreach ($academicprogram as $item)
                                                      <option value="{{$item->id}}">{{$item->progname}}</option>
                                                @endforeach
                                          </select>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12" id="error_message">

                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12" id="success_message">

                                    </div>
                              </div>
                           
                              <div class="row mt-4" id="enrollment_form" >
                                    <div class="col-md-12">
                                          <div class="row">
                                                <div class="col-md-12 border-bottom">
                                                      <h5><b>Student Personal Information</b></h5>
                                                </div>
                                          </div>
                                          <div class="row mt-3">
                                                <div class="col-md-12">
                                                      <p id="duplication_error" class="text-danger mb-0" hidden></p>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                      <label for="">*First Name</label>
                                                      <input name="" id="input_firstname" class="form-control" autocomplete="off" required>
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong>First Name is required!</strong>
                                                      </span>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                      <label for="">Middle Name</label>
                                                      <input name="" id="input_middlename" class="form-control" autocomplete="off">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                      <label for="">*Last Name</label>
                                                      <input name="" id="input_lastname" class="form-control" autocomplete="off" required>
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong>Last name is required!</strong>
                                                      </span>
                                                </div>
                                                <div class="col-md-1 form-group">
                                                      <label for="">Suffix</label>
                                                      <input name="" id="input_suffix" class="form-control" autocomplete="off">
                                                </div>
                                          </div>
                                          <div class="row">
                                                <div class="col-md-3 form-group">
                                                      <label for="">*Gender</label>
                                                      <select id="input_gender" class="form-control" required>
                                                            <option value="">Gender</option>      
                                                            <option value="MALE">MALE</option>  
                                                            <option value="FEMALE">FEMALE</option>  
                                                      <select>
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong>Gender is required!</strong>
                                                      </span>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                      <label for="">*Date of birth</label>
                                                      <input id="input_dob" class="form-control" type="date" autocomplete="off" required>
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong>Date of birth is required!</strong>
                                                      </span>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                      <label for="">*Nationality</label>
                                                      <input name="" id="input_nationality reqiured" class="form-control" autocomplete="off" required>
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong>Nationality is required!</strong>
                                                      </span>
                                                </div>
                                          </div>
                                          <div class="row mt-3 border-bottom">
                                                <div class="col-md-12 ">
                                                      <h5><b>Student Contact Information</b></h5>
                                                </div>
                                          </div>
                                          <div class="row mt-3">
                                                <div class="form-group col-md-6">
                                                      <label>Mobile Number *</label>
                                                      <input class="form-control" placeholder="09XX-XXXX-XXXX "  name="contact_number" id="contact_number" minlength="13" maxlength="13" autocomplete="off" required>
                                                      <span class="invalid-feedback" role="alert" >
                                                            <strong id="mobileError">Mobile number is required</strong>
                                                      </span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                      <label class="form-control-label">Email Address</label>
                                                      <input type="email" class="form-control " placeholder="Email address"  name="email" autocomplete="off" required>
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong>Email address is required</strong>
                                                      </span>
                                                </div>
                                          </div>
                                          <div class="row mt-3 border-bottom">
                                                <div class="col-md-12 ">
                                                      <h5><b>Parent / Guardian Information</b></h5>
                                                </div>
                                          </div>
                                          <div class="row mt-3">
                                                <div class="form-group col-md-4">
                                                      <label>Father's Full Name<span></span></label>
                                                      <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="Father's Full Name"  name="father_name" id="father_name">
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong>Father's Full Name is required</strong>
                                                      </span>
                                                </div>
                                                <div class="form-group col-md-4">
                                                      <label>Father's Occupation</label>
                                                      <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="Father's Occupation"  name="father_occupation" >
                                                      
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong>Father's occupation is required</strong>
                                                      </span>
                                                </div>
                                                <div class="form-group col-md-4">
                                                      <label>Father's Contact Number</label>
                                                      <input class="form-control " id="father_contact_number"  name="father_contact_number" placeholder="09XX-XXXX-XXXX " minlength="13" maxlength="13" >
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong id="fmobileError">Father's Contact Number is required</strong>
                                                      </span>
                                                </div>
                                          </div>
                                          <div class="row">
                                                <div class="form-group col-md-4">
                                                      <label>Mother's Full Maiden Name </label>
                                                      <input class="form-control " onkeyup="this.value = this.value.toUpperCase();" placeholder="Mother's Full Maiden Name"  name="mother_name" id="mother_name" >
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong>Mother's Full Maiden Name is required</strong>
                                                      </span>
                                                </div>
                                                <div class="form-group col-md-4">
                                                      <label>Mother's Occupation</label>
                                                      <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="Mother's occupation"  name="mother_occupation" >
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong>Mother's occupation is required</strong>
                                                      </span>
                                                </div>
                                                <div class="form-group col-md-4">
                                                      <label>Mother's Contact Number</label>
                                                      <input class="form-control " id="mother_contact_number"  name="mother_contact_number" placeholder="09XX-XXXX-XXXX " minlength="13" maxlength="13" >
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong id="mmobileError">Mother's contact number is required</strong>
                                                      </span>
                                                </div>
                                          </div>
                                          <div class="row border-bottom">
                                                <div class="form-group col-md-4">
                                                      <label>Guardian's Full Name </label>
                                                      <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="(surname, full name, middle name)"  name="guardian_name" id="guardian_name">
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong>Guardian's Full is required</strong>
                                                      </span>
                                                </div>
                                                <div class="form-group col-md-4">
                                                      <label>Relationship to Student</label>
                                                      <input onkeyup="this.value = this.value.toUpperCase();" class="form-control " placeholder="Guardian's Relationship"  name="guardian_relation" >
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong>Guardian's relationship is required</strong>
                                                      </span>
                                                </div>
                                                <div class="form-group col-md-4">
                                                      <label>Guardian's Contact Number</label>
                                                      <input class="form-control"  id="guardian_contact_number"  name="guardian_contact_number" placeholder="09XX-XXXX-XXXX " minlength="13" maxlength="13" >
                                                      <span class="invalid-feedback" role="alert">
                                                            <strong id="gmobileError">Guardian's contact number is required</strong>
                                                      </span>
                                                </div>
                                          </div>
                                          <div class="row">
                                                <div class="col-md-12 mt-2">
                                                      <label>In case of emergency ( Recipient for News, Announcement and School Info)</label>  <span role="alert" id="incaseinvalid" class="text-danger" style="font-size: 80%;" hidden>
                                                            <strong>* In case of emergency is required</strong>
                                                      </span>
                                                </div>
                                                <div class="col-md-4">
                                                      <div class="icheck-success d-inline">
                                                            <input class="form-control" type="radio" id="father" name="incase" value="1" required>
                                                            <label for="father">Father
                                                            </label>
                                                      </div>
                                                </div>
                                                <div class="col-md-4">
                                                      <div class="icheck-success d-inline">
                                                            <input class="form-control" type="radio" id="mother" name="incase" value="2" required>
                                                            <label for="mother">Mother
                                                            </label>
                                                      </div>
                                                </div>
                                                <div class="col-md-4">
                                                      <div class="icheck-success d-inline">
                                                            <input class="form-control" type="radio" id="guardian" name="incase" value="3" required>
                                                            <label for="guardian">Guardian
                                                            </label>
                                                      </div>
                                                </div>
                                          </div>
                                          <div class="row mt-4 border-bottom">
                                                <div class="col-md-12 ">
                                                      <h5><b>Requirements</b></h5>
                                                </div>
                                          </div>
                                          <div class="row mt-3">
                                                <div class="col-md-12">
                                                      <table class="table table-bordered table-sm">
                                                            <thead >
                                                                  <tr>
                                                                        <td>Requirement Description</td>
                                                                        <td></td>
                                                                  </tr>
                                                            </thead>
                                                            <tbody id="preregreqbody">
                                                                  @foreach(DB::table('preregistrationreqlist')->where('deleted','0')->where('acadprogid',null)->where('isActive','1')->get() as $item)
                                                                        <tr data-status="1">
                                                                              <td class="align-middle">{{$item->description}}</td>
                                                                              <td><input name="req{{$item->id}}" type="file" class="form-control form-control-sm" accept=".png, .jpg, .jpeg"></td>
                                                                        </tr>
                                                                  @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                  <tr>
                                                                        <td colspan="2">
                                                                              <i><b>Note:</b> Old/Continuing Students don’t have to upload requirements ( unless specified and requested by the Registrar’s Office). You can skip this step by clicking next. </>
                                                                        </td>
                                                                  </tr>
                                                            </tfoot>
                                                          
                                                      </table>
                                                </div>
                                          </div>
                                          <div class="row mt-4 border-bottom">
                                                <div class="col-md-12 ">
                                                      <h5><b>Terms and Agreements</b></h5>
                                                </div>
                                          </div>
                                          <div class="row">
                                                <div class="col-md-12">
                                                      <div style="overflow: auto;max-height: 363px; " id="terms">
                                                            {!! html_entity_decode(DB::table('schoolinfo')->first()->terms) !!}
                                                          
                                                      </div>
                                                </div>
                                          </div>
                                          <div class="row mt-4">
                                                <div class="col-md-12">
                                                      <div class="icheck-success d-inline">
                                                            <input class="form-control" type="checkbox" id="agree" name="agree" value="2" required>
                                                            <label for="agree">I confirm that I have read, understand and agree to the above terms and agreement for enrollment of {{DB::table('schoolinfo')->first()->schoolname}}
                                                            </label>
                                                      </div>
                                                </div>
                                          </div>
                                          <div class="row mt-4">
                                                <div class="col-md-12">
                                                      <p>Pre-enrollment is almost complete.</p>
                                                </div>
                                          </div>
                                          <div class="row mb-5">
                                                <div class="col-md-12">
                                                      <button class="btn btn-primary" id="submit_preenrollment" disabled>SUBMIT PRE-ENROLMENT</button>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>

      <div class="row">
            <div class="col-md-12">
                  <div class="card">
                        <div class="card-header">
                              Update Student Information
                        </div>
                  </div>
            </div>
      </div>


     
@endsection


@section('footerscript')
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script>
            $(document).ready(function(){

                  var all_enrollmentsetup = []
                  var selected_acadprog = null
                  
                  function get_enrollmentsetup(){
                        $.ajax({
					type:'GET',
					url: '/superadmin/enrollmentsetup/list',
					success:function(data) {
						all_enrollmentsetup = data
                                    
					}
				})
                  }

                  $(document).on('click','#agree',function(){
                        $('#submit_preenrollment').removeAttr('disabled')
                  })

                  function check_enrollment_setup(){
                        $.ajax({
					type:'GET',
					url: '/student/enrollment/setup',
                              data:{
                                    acadprogid:selected_acadprog
                              },
					success:function(data) {
                                    $('#success_message').empty()
                                    $('#error_message').empty()
						if(data[0].status == 1){
                                          $('#success_message').text(data[0].message)
                                          $('#enrollment_form').removeAttr('hidden')
                                    }
                                    else if(data[0].status == 0){
                                          $('#error_message').text(data[0].message)
                                          $('#enrollment_form').attr('hidden','hidden')
                                    }
                                    
					}
				})
                  }

                  $(document).on('input','#input_firstname , #input_lastname',function(){
                        student_information()
                  })

                  function student_information(){
                        $.ajax({
					type:'GET',
					url: '/student/enrollment/check/duplication',
                              data:{
                                    firstname:$('#input_firstname').val(),
                                    lastname:$('#input_lastname').val()
                              },
					success:function(data) {
						if(data[0].status == 1){
                                          $('#duplication_error').removeAttr('hidden')
                                          $('#duplication_error').text(data[0].message)
                                    }else{
                                          $('#duplication_error').attr('hidden','hidden')
                                    }
					}
				})
                  }

                  $(document).on('click','input[name="incase"]',function(){
                        $('#father_contact_number').removeAttr('required')
                        $('#mother_contact_number').removeAttr('required')
                        $('#guardian_contact_number').removeAttr('required')
                        $('#mother_name').removeAttr('required')
                        $('#father_name').removeAttr('required')
                        $('#guardian_name').removeAttr('required')
                        if($(this).val() == 1){
                              $('#father_contact_number').attr('required','required')
                              $('#father_name').attr('required','required')
                        }
                        else if($(this).val() == 2){
                              $('#mother_contact_number').attr('required','required')
                              $('#mother_name').attr('required','required')
                        }
                        else if($(this).val() == 3){
                              $('#guardian_contact_number').attr('required','required')
                              $('#guardian_name').attr('required','required')
                        }
                  })

                  $(document).on('click','#submit_preenrollment',function(){
                        $('.form-control').each(function(a,b){
                              if($(b).attr('required') == 'required' && ( $(b).val() == null || $(b).val() == '' ) ){
                                    $(b).addClass('is-invalid')
                              }else{
                                    $(b).removeClass('is-invalid')
                              }
                        })

                   
                        if($('input[name=incase]:checked').length == 0){
                              $('#incaseinvalid').removeAttr('hidden')
                        }else{
                              $('#incaseinvalid').attr('hidden','hidden')
                        }
                       
                  })

                  $(document).on('change','#input_acadprog',function(){
                        selected_acadprog = $(this).val()
                        check_enrollment_setup()
                  })
            })
      </script>
@endsection


