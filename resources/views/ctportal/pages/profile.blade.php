
@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
@endsection

@section('content')

<section class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1>My Profile</h1>
              </div>
              <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="/home">Home</a></li>
                  <li class="breadcrumb-item active">My Profile</li>
              </ol>
              </div>
          </div>
      </div>
  </section>
  <section class="content pt-0">
      <div class="container-fluid">
          <div class="row">
              <div class="col-md-3">
                    <div class="card shadow">
                          <div class="card-body box-profile">
                                <div class="text-center" id="image_holder">
                                </div>
                                <p></p>
                                <ul class="list-group list-group-unbordered mb-3">
                                      <li class="list-group-item">
                                        <b>TID</b> <a class="float-right" id="label_tid"></a>
                                      </li>
                                </ul>
                                {{-- <button data-toggle="modal"  data-target="#profile-modal" class="btn btn-primary btn-block mt-2"><b>Update Student Photo</b></button> --}}
                          </div>
                    </div>
              </div>
              <div class="col-md-9">
                    <div class="card shadow">
                          <div class="card-body">
                                  {{-- <div class="row">
                                      <div class="col-md-12">
                                          <h5 class="mb-3">Enrollment Information</h5>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-3">
                                          <strong><i class="fas fa-book mr-1"></i>Grade Level</strong>
                                          <p class="text-muted" id="grade_level"></p>
                                      </div>
                                      <div class="col-md-3" id="lrn_holder" hidden>
                                          <strong><i class="fas fa-book mr-1"></i>LRN</strong>
                                          <p class="text-muted" id="lrn"></p>
                                      </div>
                                      <div class="col-md-6" hidden  id="strand_holder">
                                          <strong><i class="fas fa-book mr-1"></i>Strand</strong>
                                          <p class="text-muted" id="input_strand"></p>
                                      </div>
                                      <div class="col-md-6" hidden  id="course_holder">
                                          <strong><i class="fas fa-book mr-1"></i>Course</strong>
                                          <p class="text-muted" id="course"></p>
                                      </div>
                                      
                                  </div>
                                  <hr  class="mt-0"> --}}
                                  <div class="row">
                                      <div class="col-md-12 mb-3">
                                          <h5>Personal Information</h5>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-4">
                                          <strong>First Name</strong>
                                          <p class="text-muted" id="first_name">--</p>
                                      </div>
                                      <div class="col-md-3">
                                          <strong>Middle Name</strong>
                                          <p class="text-muted" id="middle_name">--</p>
                                      </div>
                                      <div class="col-md-4">
                                          <strong>Last Name</strong>
                                          <p class="text-muted" id="last_name">--</p>
                                      </div>
                                      <div class="col-md-1">
                                          <strong>Suffix</strong>
                                          <p class="text-muted" id="suffix">--</p>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-4">
                                          <strong>Date of birth</strong>
                                          <p class="text-muted" id="dob">--</p>
                                      </div>
                                      <div class="col-md-3">
                                          <strong><i class="fas fa-book mr-1"></i>Gender</strong>
                                          <p class="text-muted" id="gender">--</p>
                                      </div>
                                      <div class="col-md-4">
                                          <strong><i class="fas fa-book mr-1"></i>Nationality</strong>
                                          <p class="text-muted" id="nationality">--</p>
                                      </div>
                                  </div>
                                    <div class="row">
                                          <div class="col-md-4">
                                          <strong><i class="fas fa-book mr-1"></i>Mobile Number</strong>
                                          <p class="text-muted" id="contact_number">--</p>
                                          </div>
                                          <div class="col-md-6">
                                          <strong><i class="fas fa-book mr-1"></i>Email Address</strong>
                                          <p class="text-muted" id="email">--</p>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-6">
                                                <strong><i class="fas fa-book mr-1"></i>Address</strong>
                                                <p class="text-muted" id="address">--</p>
                                          </div>
                                    </div>
                                  {{-- <div class="row">
                                      <div class="col-md-4">
                                          <strong><i class="fas fa-book mr-1"></i>Mobile Number</strong>
                                          <p class="text-muted" id="contact_number">--</p>
                                      </div>
                                      <div class="col-md-6">
                                          <strong><i class="fas fa-book mr-1"></i>Email Address</strong>
                                          <p class="text-muted" id="email">--</p>
                                      </div>
                                  </div>
                                  <hr  class="mt-0">
                                  <div class="row">
                                      <div class="col-md-12">
                                          <h5 class="mb-3">Address</h5>
                                      </div>
                                  </div>
                                  
                                  <div class="row">
                                      <div class="col-md-6">
                                          <strong><i class="fas fa-book mr-1"></i>Street</strong>
                                          <p class="text-muted" id="street">--</p>
                                      </div>
                                      <div class="col-md-6">
                                          <strong><i class="fas fa-book mr-1"></i>Barangay</strong>
                                          <p class="text-muted" id="barangay">--</p>
                                      </div>
                                      <div class="col-md-6">
                                          <strong><i class="fas fa-book mr-1"></i>City</strong>
                                          <p class="text-muted" id="city">--</p>
                                      </div>
                                      <div class="col-md-6">
                                          <strong><i class="fas fa-book mr-1"></i>City</strong>
                                          <p class="text-muted" id="province">--</p>
                                      </div>
                                  </div>
                                  <hr   class="mt-0">
                                  <div class="row">
                                      <div class="col-md-12">
                                          <h5 class="mb-3">Parent / Guardian Information</h5>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-4">
                                          <strong><i class="fas fa-book mr-1"></i>Father's Full Name</strong>
                                          <p class="text-muted" id="father_name">--</p>
                                      </div>
                                      <div class="col-md-4">
                                          <strong><i class="fas fa-book mr-1"></i>Father's Occupation</strong>
                                          <p class="text-muted" id="father_occupation">--</p>
                                      </div>
                                      <div class="col-md-4">
                                          <strong><i class="fas fa-book mr-1"></i>Father's Contact Number</strong>
                                          <p class="text-muted" id="father_contact_number">--</p>
                                      </div>
                                </div>
                                <hr class="mt-0">
                                <div class="row">
                                      <div class="col-md-4">
                                          <strong><i class="fas fa-book mr-1"></i>Mother's Full Maiden Name</strong>
                                          <p class="text-muted" id="mother_name">--</p>
                                      </div>
                                      <div class="col-md-4">
                                          <strong><i class="fas fa-book mr-1"></i>Mother's Occupation</strong>
                                          <p class="text-muted" id="mother_occupation">--</p>
                                      </div>
                                      <div class="col-md-4">
                                          <strong><i class="fas fa-book mr-1"></i>Mother's Contact Number</strong>
                                          <p class="text-muted" id="mother_contact_number">--</p>
                                      </div>
                                  </div>
                                  <hr  class="mt-0">
                                  <div class="row">
                                      <div class="col-md-4">
                                          <strong><i class="fas fa-book mr-1"></i>Guardian's Full Name</strong>
                                          <p class="text-muted" id="guardian_name">--</p>
                                      </div>
                                      <div class="col-md-4">
                                          <strong><i class="fas fa-book mr-1"></i>Relationship to Student</strong>
                                          <p class="text-muted" id="guardian_relation">--</p>
                                      </div>
                                      <div class="col-md-4">
                                          <strong><i class="fas fa-book mr-1"></i>Guardian's Contact Number</strong>
                                          <p class="text-muted" id="guardian_contact_number">--</p>
                                      </div>
                                  </div>
                                  <hr  class="mt-0">
                                  <div class="row" id="incaseholder">
                                      <div class="col-md-12 ">
                                              <label>In case of emergency ( Recipient for News, Announcement and School Info)</label>
                                      </div>
                                      <div class="col-md-4">
                                              <div class="icheck-success d-inline">
                                                  <input class="form-control" type="radio" id="father" name="incase" value="1" disabled>
                                                  <label for="father">Father
                                                  </label>
                                              </div>
                                      </div>
                                      <div class="col-md-4">
                                              <div class="icheck-success d-inline">
                                                  <input class="form-control" type="radio" id="mother" name="incase" value="2" disabled>
                                                  <label for="mother">Mother
                                                  </label>
                                              </div>
                                      </div>
                                      <div class="col-md-4">
                                              <div class="icheck-success d-inline">
                                                  <input class="form-control" type="radio" id="guardian" name="incase" value="3" disabled>
                                                  <label for="guardian">Guardian
                                                  </label>
                                              </div>
                                      </div>
                                  </div> --}}
                          </div>
                    </div>
              </div>
        </div>
      </div>
</section>
@endsection

@section('footerscript')

      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>

      <script>
            $(document).ready(function(){

                  get_profile()
                  function get_profile(){

                        $.ajax({
                              type:'GET',
                              url:'/college/teacher/profile/get',
                              success:function(data) {

                                    $('#first_name').text(data[0].firstname != null ? data[0].firstname : '--')
                                    $('#middle_name').text(data[0].middlename  !=  null ? data[0].middlename : '--')
                                    $('#last_name').text(data[0].lastname  !=  null ? data[0].lastname : '--')
                                    $('#suffix').text(data[0].suffix  !=  null ? data[0].suffix : '--')
                                    $('#dob').text(data[0].dob != null ? data[0].dob : '--')
                                    $('#gender').text(data[0].gender  !=  null ? data[0].gender : '--')
                                    $('#nationality').text(data[0].nationality  !=  null ? data[0].nationality : '--')
                                    $('#contact_number').text(data[0].contactnum  !=  null ? data[0].contactnum : '--')
                                    $('#email').text(data[0].email  !=  null ? data[0].email : '--')
                                    $('#address').text(data[0].address  !=  null ? data[0].address : '--')

                                    
                                   
                                    $('#label_tid').text(data[0].tid)
                                   

                                    var onerror_url = @json(asset('dist/img/download.png'));
                                    var picurl = data[0].picurl.replace('jpg','png')+"?random="+new Date().getTime()
                                    var image = '<img width="100%" src="/'+picurl+'" onerror="this.src=\''+onerror_url+'\'" alt="" class="img-circle img-fluid" >'
                                    $('#image_holder')[0].innerHTML = image

                                   
                              }
                        })
                  }
            })
      </script>
@endsection

