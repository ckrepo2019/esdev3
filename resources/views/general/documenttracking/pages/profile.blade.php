
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}">

@extends('general.defaultportal.layouts.app')

<style>

  .maindiv{
    background-image: url('https://ds6br8f5qp1u2.cloudfront.net/blog/wp-content/uploads/2015/12/funny-cat-year2015-web-dev.gif') !important;
    background-size: cover; /* Optional: Adjusts the size of the background image to cover the entire body */
    background-position: center; /* Optional: Centers the background image horizontally and vertically */
    height: 100%;
  }

</style>
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
                                        <b>Account ID</b> <a class="float-right" id="label_tid"></a>
                                      </li>
                                </ul>
                                <button  data-toggle="modal"  data-target="#image-modal" class="btn btn-primary btn-block mt-2"><b>Update Profile Picture</b></button>
                          </div>
                    </div>
              </div>
              <div class="col-md-9">
                    <div class="card shadow">
                          <div class="card-body">
                                  <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <h5>Personal Information</h5>
                                        </div>
                                        <div class="col-md-6 mb-3 text-right">
                                            {{-- <button hidden class="btn btn-primary btn-sm" id="profile_modal_button">Update Profile</button> --}}
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
                                            <strong><i class="fas fa-book mr-1"></i>Marital Status</strong>
                                            <p class="text-muted" id="maritalstatus">--</p>
                                        </div>
                                         <div class="col-md-3">
                                          <strong><i class="fas fa-book mr-1"></i>Mobile Number</strong>
                                          <p class="text-muted" id="contact_number">--</p>
                                        </div>
                                        <div class="col-md-4">
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
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <h5>Account Information</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong><i class="fas fa-book mr-1"></i>User Type</strong>
                                            <p class="text-muted" id="utype">--</p>
                                        </div>
                                        <div class="col-md-8">
                                            <strong><i class="fas fa-book mr-1"></i>Academic Program</strong>
                                            <p class="text-muted" id="acadprog">--</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong><i class="fas fa-book mr-1"></i>Privilege User</strong>
                                            <p class="text-muted" id="privuser">--</p>
                                        </div>
                                    </div>
                          </div>
                    </div>
              </div>
        </div>
      </div>
</section>




<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-timegrid/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-interaction/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>

  $( document ).ready(function() {

  });
</script>
@endsection
