

@extends('hr.layouts.app')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
.btn-circle {
  width: 45px;
  height: 45px;
  line-height: 45px;
  text-align: center;
  padding: 0;
  border-radius: 50%;
}

.btn-circle i {
  position: relative;
  top: -1px;
}

.btn-circle-sm {
  width: 35px;
  height: 35px;
  line-height: 35px;
  font-size: 0.9rem;
}

.btn-circle-lg {
  width: 55px;
  height: 55px;
  line-height: 55px;
  font-size: 1.1rem;
}

.btn-circle-xl {
  width: 70px;
  height: 70px;
  line-height: 70px;
  font-size: 1.3rem;
}
.edit-icon {
    background-color: #ffc107;
    border: 1px solid #e3e3e3;
    border-radius: 24px;
    color: #bbb;
    float: right;
    font-size: 12px;
    /* line-height: 24px; */
    /* min-height: 26px; */
    text-align: center ;
    width: 26px;
    padding: 5px;
}
.edit-pic-icon {
    background-color: #ffc107;
    border: 1px solid #e3e3e3;
    border-radius: 24px;
    color: #bbb;
    /* float: right; */
    font-size: 12px;
    line-height: 24px;
    min-height: 26px;
    text-align: center ;
    /* width: 26px; */
    padding: 5px;
    /* position: absolute; */
    /* right: 10px; */
    /* left: 175px; */

/* bottom: 7px; */
}
.profile-view .pro-edit {
    position: absolute;
    right: 0;
    top: 0;
}
/* .fas {
    display: inline-block;
    font-size: inherit;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
} */
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.ribbon-wrapper {
    height: 45px;
    overflow: hidden;
    position: absolute;
    right: -2px;
    top: -2px;
    width: 55px;
    z-index: 10;
}
.ribbon-wrapper .ribbon {
    box-shadow: 0 0 3px rgba(0,0,0,.3);
    font-size: .8rem;
    line-height: 12%;
    padding: .375rem 0;
    position: relative;
    right: -2px;
    text-align: center;
    text-shadow: 0 -1px 0 rgba(0,0,0,.4);
    text-transform: uppercase;
    top: 10px;
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg);
    width: 75px;
}
/* Firefox */
input[type=number] {
  -moz-appearance:textfield;
}
.alert {
  font-family: sans-serif;
      padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-success {
  color: #3c763d;
    background-color: #dff0d8;
    border-color: #d6e9c6;
}


/*DEMO*/
.preview {
  margin: 10px;
  display: none;
}
.preview--rounded {
  width: 160px;
  height: 160px;
  border-radius: 50%;
}
/* IMMUTABLE */
.hide {
  display: none !important;
}
* {
  box-sizing: border-box;
}
.photo__zoom {
  position: relative;
  padding-left: 22px;
  padding-right: 22px;
/**
    * Zoom
    */
/**
    * Zoom handler
    */
/**
    * FOCUS
    */
/**
    * Zoom track
    */
/**
    * ICONS
    */
}
.photo__zoom input[type=range] {
  -webkit-appearance: none;
  width: 100%;
  background: transparent;
  height: 18px;
}
.photo__zoom input[type=range]::-webkit-slider-thumb {
  -webkit-appearance: none;
}
.photo__zoom input[type=range]:focus {
  outline: none;
}
.photo__zoom input[type=range]::-ms-track {
  width: 100%;
  cursor: pointer;
  background: transparent;
  border-color: transparent;
  color: transparent;
}
.photo__zoom input[type=range]:focus::-ms-thumb {
  border-color: #268eff;
  box-shadow: 0 0 1px 0px #268eff;
}
.photo__zoom input[type=range]:focus::-moz-range-thumb {
  border-color: #268eff;
  box-shadow: 0 0 1px 0px #268eff;
}
.photo__zoom input[type=range]:focus::-webkit-slider-thumb {
  border-color: #268eff;
  box-shadow: 0 0 1px 0px #268eff;
}
.photo__zoom input[type=range]::-webkit-slider-thumb {
  -webkit-appearance: none;
  margin-top: -9px;
  box-sizing: border-box;
  cursor: pointer;
  width: 18px;
  height: 18px;
  display: block;
  border-radius: 50%;
  background: #eee;
  border: 1px solid #ddd;
}
.photo__zoom input[type=range]::-webkit-slider-thumb:hover {
  border-color: #c1c1c1;
}
.photo__zoom input[type=range]::-ms-thumb {
  margin-top: 0;
  box-sizing: border-box;
  cursor: pointer;
  width: 18px;
  height: 18px;
  display: block;
  border-radius: 50%;
  background: #eee;
  border: 1px solid #ddd;
}
.photo__zoom input[type=range]::-ms-thumb:hover {
  border-color: #c1c1c1;
}
.photo__zoom input[type=range]::-moz-range-thumb {
  margin-top: 0;
  box-sizing: border-box;
  cursor: pointer;
  width: 18px;
  height: 18px;
  display: block;
  border-radius: 50%;
  background: #eee;
  border: 1px solid #ddd;
}
.photo__zoom input[type=range]::-moz-range-thumb:hover {
  border-color: #c1c1c1;
}
.photo__zoom input[type=range]::-webkit-slider-runnable-track {
  width: 100%;
  height: 1px;
  cursor: pointer;
  background: #eee;
  border: 0;
}
.photo__zoom input[type=range]::-moz-range-track {
  width: 100%;
  height: 1px;
  cursor: pointer;
  background: #eee;
  border: 0;
}
.photo__zoom input[type=range]::-ms-track {
  width: 100%;
  height: 1px;
  cursor: pointer;
  background: #eee;
  border: 0;
}
.photo__zoom input[type=range].zoom--minValue::before,
.photo__zoom input[type=range].zoom--maxValue::after {
  color: #f8f8f8;
}
.photo__zoom input[type=range]::before,
.photo__zoom input[type=range]::after {
  position: absolute;
  content: "\f03e";
  display: block;
  font-family: 'FontAwesome';
  color: #aaa;
  transition: color 0.3s ease;
}
.photo__zoom input[type=range]::after {
  font-size: 18px;
  right: -2px;
  top: 2px;
}
.photo__zoom input[type=range]::before {
  font-size: 14px;
  left: 4px;
  top: 4px;
}
/**
* FRAME STYLE
*/
.photo__frame--circle {
  border: 1px solid #e2e2e2;
  border-radius: 50%;
}
.photo__helper {
  position: relative;
  background-repeat: no-repeat;
  background-color: transparent;
  padding: 15px 0;
}
.photo__helper .canvas--helper {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}
.photo__frame img,
.photo__helper {
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
.profile {
  position: relative;
  font-family: 'HelveticaNeueLTPro-Roman', sans-serif;
  font-size: 85%;
  /* width: 300px; */
}
.photo {
  text-align: center;
  margin-bottom: 15px;
}
.photo input[type=file] {
  display: none;
}
.photo__options {
  margin-top: 15px;
  position: relative;
  text-align: left;
}
.photo__options .remove {
  padding: 0;
  padding: 0;
  display: inline-block;
  text-decoration: none;
  color: #ddd;
  font-size: 18px;
  width: 20%;
  text-align: center;
  vertical-align: middle;
}
.photo__options .remove:hover {
  color: #000;
}
.photo__zoom {
  vertical-align: middle;
  width: 80%;
  display: inline-block;
}
.photo__frame {
  cursor: move;
  overflow: hidden;
  position: relative;
  display: inline-block;
  width: 160px;
  height: 160px;
}
.photo__frame img,
.photo__helper img {
  position: relative;
}
.photo__frame .message {
  position: absolute;
  left: 5px;
  right: 5px;
  top: 50%;
  transform: translateY(-50%);
  display: inline-block;
  color: #268eff;
  z-index: 3;
}
.photo__frame .is-dragover {
  display: none;
}
.message p {
  font-size: 0.9em;
}
.photo__options {
  list-style: none;
}
.photo__options li {
  display: inline-block;
  text-align: center;
  width: 50%;
}
.photo--empty .photo__frame {
  cursor: pointer;
}
/**
* IMG states
*/
.profile.is-dragover .photo__frame img,
.photo--empty img,
.photo--error img,
.photo--error--file-type img,
.photo--error--image-size img,
.photo--loading img {
  display: none;
}
/**
* States
*/
/** SELECT PHOTO MESSAGE */
.message--desktop,
.message--mobile {
  display: none;
}
/* MOBILE */
.is-mobile .message--mobile {
  display: inline-block;
}
.is-mobile .message--desktop {
  display: none;
}
/* DESKTOP */
.is-desktop .message--desktop {
  display: inline-block;
}
.is-desktop .message--mobile {
  display: none;
}
/* DEFAULT */
.message.is-empty,
.message.is-loading,
.message.is-wrong-file-type,
.message.is-wrong-image-size,
.message.is-something-wrong,
.message.is-dragover {
  display: none;
}
/* EMPTY */
.photo--empty .photo__options {
  display: none;
}
.photo--empty .message.is-empty {
  display: inline-block;
}
.photo--empty .photo__frame:hover {
  background: #268eff;
}
.photo--empty .photo__frame:hover .message {
  color: #fff;
}
/* LOADING */
.photo--loading .message.is-loading {
  display: inline-block;
}
.photo--loading .message.is-empty,
.photo--loading .message.is-wrong-file-type,
.photo--loading .message.is-dragover,
.photo--loading .message.is-wrong-image-size,
.photo--loading .photo__options {
  display: none;
}
/* ERROR */
/* UNKNOWN */
.photo--error .message.is-empty,
.photo--error .message.is-loading,
.photo--error .message.is-dragover,
.photo--error .message.is-wrong-image-size,
.photo--error .photo__options {
  display: none;
}
.photo--error .message.is-something-wrong {
  display: inline-block;
}
/* FILE TYPE*/
.photo--error--file-type .message.is-empty,
.photo--error--file-type .message.is-loading,
.photo--error--file-type .message.is-dragover,
.photo--error--file-type .message.is-wrong-image-size,
.photo--error--file-type .photo__options {
  display: none;
}
.photo--error--file-type .message.is-wrong-file-type {
  display: inline-block;
}
/* IMAGE SIZE */
.photo--error--image-size .message.is-empty,
.photo--error--image-size .message.is-loading,
.photo--error--image-size .message.is-dragover,
.photo--error--image-size .message.is-wrong-file-type,
.photo--error--image-size .photo__options {
  display: none;
}
.photo--error--image-size .message.is-wrong-image-size {
  display: inline-block;
}
/* DRAGOVER */
.profile.is-dragover .photo__frame .is-dragover {
  display: inline-block;
}
.profile.is-dragover .message.is-empty,
.profile.is-dragover .message.is-loading,
.profile.is-dragover .message.is-wrong-file-type,
.profile.is-dragover .message.is-wrong-image-size {
  display: none;
}

</style>
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-chart-line nav-icon"></i> Employee  Profile</h4>
          <!-- <h1>Employee  Profile</h1> -->
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item"><a href="/employeeslist">Employees</a></li>
            <li class="breadcrumb-item active">Employee Profile</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
<div class="row">
    <div class="col-md-12">
    <div class="card">
        <div class="row">
    <div class="col-md-4">

        <div class="profile">
            <div class="p-2">
                <div class="">
                    <center>
                        @php
                            $number = rand(1,3);
                            if(count($employee_info)==0){
                                $avatar = 'assets/images/avatars/unknown.png';
                            }
                            else{
                                if(strtoupper($employee_info[0]->gender) == 'FEMALE'){
                                    $avatar = 'avatar/T(F) '.$number.'.png';
                                }
                                else{
                                    $avatar = 'avatar/T(M) '.$number.'.png';
                                }
                            }
                        @endphp
                        <div id="upload-demo-i" class="bg-white " style="width:200px;height:200px;">
                                <img class="elevation-2" src="{{asset($profile->picurl)}}" style="width:200px;height:200px;"  onerror="this.onerror = null, this.src='{{asset($avatar)}}'" alt="User Avatar">
                        </div>
                    </center>
                </div>
            </div>
        </div>
        <br>
        <center><a href="#" class="edit-pic-icon" data-toggle="modal" data-target="#edit_profile_pic" style="color: black !important"><i class="fas fa-edit" style="color: black !important"></i> Change profile picture</a></center>
        <br>
    </div>
    <div class="col-md-8 p-3 text-center text-uppercase">
            <h1 class="text-info text-center">{{$profile->firstname}} {{$profile->middlename}} {{$profile->lastname}} {{$profile->suffix}}</h1>
        {{-- <div class="row"> --}}
            <h3>{{$profile->utype}}</h3>
            <div class="row">
                <div class="col-md-6 text-right">
                    
                    Employee ID : 
                </div>
                <div class="col-md-6">
                    {{$profile->tid}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 text-right">
                    
                    License NO : 
                </div>
                <div class="col-md-6">
                    {{$profile->licno}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 text-right">
                    
                    Date Hired : 
                </div>
                <div class="col-md-6">
                    @if(count($employee_info)==0)
                    &nbsp;
                    @else
                        {{$employee_info[0]->datehiredstring}}
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 text-right">
                    
                    Status : 
                </div>
                <div class="col-md-6">
                    @if($profile->isactive==1)
                        <span class="right badge badge-success">Active</span>
                    @else
                        <span class="right badge badge-secondary">Inactive</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6  text-right">
                    
                    RFID : 
                </div>
                <div class="col-md-6">
                    <form action="/employeeprofile/updaterfid" method="get" name="updaterfid">
                        <input type="text"  value="{{$profile->rfid}}" name="rfid" class="form-control form-control-sm col-10" style="display: inline;" disabled/><a class="edit-icon col-2 rfidedit">
                        <input type="hidden" class="form-control" name="employeeid" value="{{$profile->id}}" required/><i class="fas fa-edit" style="color: black !important"></i></a>
                        @if(session()->has('rfidexists'))
                            <span class="text-danger">{{session()->get('rfidexists')}}</span>
                        @endif
                    </form>
                </div>
            </div>
    </div>
    </div>
</div>
</div>
</div>
<div class="row">
    <div class="col-12">
        {{-- <div class="card card-primary card-outline"> --}}
            {{-- <div class="card-body"> --}}
              {{-- <h4 class="mt-5 ">Custom Content Above</h4> --}}
              

                <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                    @if(session()->has('linkid'))
                        @if( session()->get('linkid') == 'custom-content-above-home')
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Profile</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Profile</a>
                            </li>
                        @endif
                        @if(session()->get('linkid') == 'custom-content-above-profile')
                            <li class="nav-item">

                                <a class="nav-link active" id="custom-content-above-profile-tab" data-toggle="pill" href="#custom-content-above-profile" role="tab" aria-controls="custom-content-above-profile" aria-selected="false">Basic Salary Information</a>
                            </li>
                        @else
                            <li class="nav-item">

                                <a class="nav-link" id="custom-content-above-profile-tab" data-toggle="pill" href="#custom-content-above-profile" role="tab" aria-controls="custom-content-above-profile" aria-selected="false">Basic Salary Information</a>
                            </li>
                        @endif
                        @if(session()->get('linkid') == 'custom-content-above-contributions')
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-content-above-contributions-tab" data-toggle="pill" href="#custom-content-above-contributions" role="tab" aria-controls="custom-content-above-contributions" aria-selected="false">Deductions</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" id="custom-content-above-contributions-tab" data-toggle="pill" href="#custom-content-above-contributions" role="tab" aria-controls="custom-content-above-contributions" aria-selected="false">Deductions</a>
                            </li>
                        @endif
                        @if(session()->get('linkid') == 'custom-content-above-allowance')
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-content-above-allowance-tab" data-toggle="pill" href="#custom-content-above-allowance" role="tab" aria-controls="custom-content-above-allowance" aria-selected="false">Allowance</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" id="custom-content-above-allowance-tab" data-toggle="pill" href="#custom-content-above-allowance" role="tab" aria-controls="custom-content-above-allowance" aria-selected="false">Allowance</a>
                            </li>
                        @endif
                        @if(session()->get('linkid') == 'custom-content-above-credentials')
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-content-above-credentials-tab" data-toggle="pill" href="#custom-content-above-credentials" role="tab" aria-controls="custom-content-above-credentials" aria-selected="false">Credentials</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" id="custom-content-above-credentials-tab" data-toggle="pill" href="#custom-content-above-credentials" role="tab" aria-controls="custom-content-above-credentials" aria-selected="false">Credentials</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Profile</a>
                        </li>
                        <li class="nav-item">

                            <a class="nav-link" id="custom-content-above-profile-tab" data-toggle="pill" href="#custom-content-above-profile" role="tab" aria-controls="custom-content-above-profile" aria-selected="false">Basic Salary Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-content-above-contributions-tab" data-toggle="pill" href="#custom-content-above-contributions" role="tab" aria-controls="custom-content-above-contributions" aria-selected="false">Deductions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-content-above-allowance-tab" data-toggle="pill" href="#custom-content-above-allowance" role="tab" aria-controls="custom-content-above-allowance" aria-selected="false">Allowance</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-content-above-credentials-tab" data-toggle="pill" href="#custom-content-above-credentials" role="tab" aria-controls="custom-content-above-credentials" aria-selected="false">Credentials</a>
                        </li>
                    @endif
                </ul>
              <div class="tab-content" id="custom-content-above-tabContent">
                    
                    @if(session()->has('linkid'))
                        @if( session()->get('linkid') == 'custom-content-above-home')
                            <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                        @else
                            <div class="tab-pane fade" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                        @endif
                    @else
                        <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                    @endif
                    <div id="emp_profile" class="pro-overview tab-pane fade active show">
                        <div class="row">
                            <div class="col-md-12 d-flex">
                                <div class="card profile-box flex-fill">
                                    {{-- <span class="float-right mr-4 mt-2 text-center">
                                    </span> --}}
                                    <div class="row">
                                    <div class="col-md-6 p-4">
                                        <a href="#" class="edit-icon" data-toggle="modal" data-target="#edit_profile_info"><i class="fas fa-edit" style="color: black !important"></i></a>
                                        <h3 class="card-title"><strong>Personal Information</strong></h3>
                                        <br>
                                        <table class="table">
                                            <tr>
                                                <td class="p-1">Phone</td>
                                                <td  class="p-1">
                                                    @if(count($employee_info)==0)
                                                    &nbsp;
                                                    @else
                                                        {{$employee_info[0]->contactnum}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-1">Email</td>
                                                <td class="p-1">
                                                    @if(count($employee_info)==0)
                                                    &nbsp;
                                                    @else
                                                        {{$employee_info[0]->email}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-1">Birthday</td>
                                                <td class="p-1 text-uppercase">
                                                    @if(count($employee_info)==0)
                                                    &nbsp;
                                                    @else
                                                        {{$employee_info[0]->dobstring}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-1">Address</td>
                                                <td class="p-1 text-uppercase">
                                                    @if(count($employee_info)==0)
                                                    &nbsp;
                                                    @else
                                                        {{$employee_info[0]->address}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-1">Gender</td>
                                                <td class="p-1 text-uppercase">
                                                    @if(count($employee_info)==0)
                                                    &nbsp;
                                                    @else
                                                        {{$employee_info[0]->gender}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-1">Nationality</td>
                                                <td class="p-1 text-uppercase">
                                                    @if(count($employee_info)==0)
                                                    &nbsp;
                                                    @else
                                                        {{$employee_info[0]->nationality}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-1">Religion</td>
                                                <td class="p-1 text-uppercase">
                                                    @if(count($employee_info)==0)
                                                    &nbsp;
                                                    @else
                                                        {{$employee_info[0]->religionname}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-1">Marital status</td>
                                                <td class="p-1 text-uppercase">
                                                    @if(count($employee_info)==0)
                                                    &nbsp;
                                                    @else
                                                        {{$employee_info[0]->civilstatus}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-1">Employment of spouse</td>
                                                <td class="p-1 text-uppercase">
                                                    @if(count($employee_info)==0)
                                                    &nbsp;
                                                    @else
                                                        {{$employee_info[0]->spouseemployment}}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-1">No. of children</td>
                                                <td class="p-1 text-uppercase">
                                                    @if(count($employee_info)==0)
                                                    &nbsp;
                                                    @else
                                                        {{$employee_info[0]->numberofchildren}}
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6 p-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="#" class="edit-icon" data-toggle="modal" data-target="#edit_emergency_contact"><i class="fas fa-edit" style="color: black !important"></i></a>
                                                <h3 class="card-title"><strong>Emergency Contact</strong> </h3>
                                                <br>
                                                <ul class="personal-info">
                                                    <li>
                                                        <div class="title">Name</div>
                                                        <div class="text">
                                                            @if(count($employee_info)==0)
                                                            &nbsp;
                                                            @else
                                                                {{$employee_info[0]->emercontactname}}
                                                            @endif
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Relationship</div>
                                                        <div class="text">
                                                            @if(count($employee_info)==0)
                                                            &nbsp;
                                                            @else
                                                                {{$employee_info[0]->emercontactrelation}}
                                                            @endif
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Phone </div>
                                                        <div class="text">
                                                            @if(count($employee_info)==0)
                                                            &nbsp;
                                                            @else
                                                                {{$employee_info[0]->emercontactnum}}
                                                            @endif
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-md-12">
                                                <br>
                                                <a href="#" class="edit-icon" data-toggle="modal" data-target="#edit_designation"><i class="fas fa-edit" style="color: black !important"></i></a>
                                                <h3 class="card-title"><strong>Department & Designation</strong> </h3>
                                                <br>
                                                <ul class="personal-info">
                                                    <li>
                                                        <div class="title">Department</div>
                                                        <div class="text">
                                                            @if(count($employee_info)==0)
                                                            &nbsp;
                                                            @else
                                                                @foreach($department as $dept)
                                                                    @if($dept->id == $employee_info[0]->departmentid)
                                                                        {{strtoupper($dept->department)}}
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Designation</div>
                                                        <div class="text">
                                                        <!-- {{$designations}} -->
                                                            @if(count($employee_info)==0)
                                                            &nbsp;
                                                            @else
                                                                @foreach($designations as $designation)
                                                                    @if($designation->id == $employee_info[0]->designationid)
                                                                        {{$designation->designation}}
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                            @if(session()->has('messageUpdated'))
                                <div class="alert alert-success alert-dismissible col-12">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                    {{ session()->get('messageUpdated') }}
                                </div>
                            @endif
                            </div>
                            <div class="col-md-12 d-flex">
                                <div class="card profile-box flex-fill">
                                    <div class="card-header bg-success">
                                    <h3 class="card-title col-12"><strong>Family Information</strong> <a href="#" class="edit-icon" data-toggle="modal" data-target="#family_info_modal"><i class="fas fa-edit" style="color: black !important"></i></a></h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-nowrap">
                                                <thead class="text-secondary bg-warning">
                                                    <tr>
                                                        <th style="width: 30%;" >Name</th>
                                                        <th style="width: 20%;">Relationship</th>
                                                        <!-- <th>Date of Birth</th> -->
                                                        <th>Phone</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($employee_familyinfo)==0)
                                                    @else
                                                        @foreach($employee_familyinfo as $family)
                                                            <tr>
                                                                <td class="text-uppercase">{{$family->famname}}</td>
                                                                <td class="text-uppercase">{{$family->famrelation}}</td>
                                                                <!-- <td class="text-uppercase">{{$family->dob}}</td> -->
                                                                <td class="text-uppercase">{{$family->contactnum}}</td>
                                                                <td class="float-right">
                                                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deletefamily{{$family->id}}"><i class="fas fa-trash-alt"></i></button>
                                                                    <div id="deletefamily{{$family->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title"><strong>Family Info</strong></h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">×</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <center>
                                                                                        Are you sure you want to <span class="text-danger"><strong>delete {{$profile->lastname}}'s {{$family->famrelation}}, {{$family->famname}}</strong></span>?
                                                                                    </center>
                                                                                    <br>
                                                                                    <form action="/employeefamily/delete" method="get">
                                                                                        <div class="submit-section">
                                                                                            <input type="hidden" class="form-control" name="employeeid" value="{{$profile->id}}" required/>
                                                                                            <input type="hidden" class="form-control" name="familyid" value="{{$family->id}}" required/>
                                                                                            <input type="hidden" class="form-control" name="familyname" value="{{$family->famname}}" required/>
                                                                                            <button type="submit" class="btn btn-danger submit-btn float-right">Delete</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 d-flex">
                                <div class="card profile-box flex-fill">
                                    <div class="card-body">
                                        <h3 class="card-title col-12"><strong>Educational Background</strong> <a href="#" class="edit-icon" data-toggle="modal" data-target="#education_info"><i class="fas fa-edit" style="color: black !important"></i></a></h3>
                                        <br>
                                        <br>
                                        <div class="experience-box">
                                            <ul class="experience-list">
                                                @if(count($employee_educationinfo)==0)
                                                &nbsp;
                                                @else
                                                    @foreach($employee_educationinfo as $educinfo)
                                                        <li>
                                                            <div class="experience-user">
                                                                <div class="before-circle"></div>
                                                            </div>
                                                            <div class="experience-content">
                                                                <div class="timeline-content">
                                                                    <a href="#/" class="name">{{$educinfo->schoolname}}</a>
                                                                    <div>{{$educinfo->coursetaken}}</div>
                                                                    <span class="time">{{$educinfo->schoolyear}}</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex">
                                <div class="card profile-box flex-fill">
                                    <div class="card-body">
                                        <h3 class="card-title col-12"><strong>Experience</strong> <a href="#" class="edit-icon" data-toggle="modal" data-target="#experience_info"><i class="fas fa-edit" style="color: black !important"></i></a></h3>
                                        <br>
                                        <br>
                                        <div class="experience-box">
                                            <ul class="experience-list">
                                                @if(count($employee_experience)==0)
                                                &nbsp;
                                                @else
                                                    @foreach($employee_experience as $experience)
                                                        <li>
                                                            <div class="experience-user">
                                                                <div class="before-circle"></div>
                                                            </div>
                                                            <div class="experience-content">
                                                                <div class="timeline-content">
                                                                    <a href="#/" class="name">{{$experience->companyname}}</a>
                                                                    <div>{{$experience->position}}</div>
                                                                    <span class="time">{{$experience->periodfrom}} - {{$experience->periodto}}</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(session()->has('linkid'))
                    @if( session()->get('linkid') == 'custom-content-above-profile')
                        <div class="tab-pane fade show active" id="custom-content-above-profile" role="tabpanel" aria-labelledby="custom-content-above-profile-tab">
                    @else
                        <div class="tab-pane fade" id="custom-content-above-profile" role="tabpanel" aria-labelledby="custom-content-above-profile-tab">
                    @endif
                @else
                    <div class="tab-pane fade" id="custom-content-above-profile" role="tabpanel" aria-labelledby="custom-content-above-profile-tab">
                @endif
                    <div class="card">
                        <div class="card-body">
                            <form action="/employeebasicsalaryinfo" method="get">
                                <input type="hidden" name="employeeid" value="{{$profile->id}}">
                                <input type="hidden" class="form-control" name="linkid" value="custom-content-above-profile" />
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label">Salary basis <span class="text-danger m-0 p-0">*</span></label>
                                            <br>
                                            @if(count($employee_basicsalaryinfo)==0)
                                            <select class="form-control" name="salarybasistype" required>
                                                @foreach($salarybasistypes as $salarybasistype)
                                                    <option value="{{$salarybasistype->id}}" type="{{$salarybasistype->type}}">{{$salarybasistype->type}}</option>
                                                @endforeach
                                            </select>
                                            @else
                                            <select class="form-control" name="salarybasistype" required>
                                                @foreach($salarybasistypes as $salarybasistype)
                                                    <option value="{{$salarybasistype->id}}" {{$salarybasistype->id == $employee_basicsalaryinfo[0]->basistypeid ? "selected" : ""}} type="{{$salarybasistype->type}}">{{$salarybasistype->type}}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4" id="generalsalaryamouncontainer">
                                        @if(count($employee_basicsalaryinfo) == 0)
                                            <div class="form-group">
                                                <label class="col-form-label">Salary amount</label>
                                                <br>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">&#8369;</span>
                                                    </div>
                                                        <input type="text" class="form-control groupOfTexbox" name="salaryamount" placeholder="Type your salary amount" value="0.00" required>
                                                </div>
                                            </div>
                                        @elseif(count($employee_basicsalaryinfo) > 0)
                                            @if($employee_basicsalaryinfo[0]->type == 'Project')
                                            @else
                                                <div class="form-group">
                                                    <label class="col-form-label">Salary amount</label>
                                                    <br>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">&#8369;</span>
                                                        </div>
                                                        <input type="text" class="form-control groupOfTexbox" name="salaryamount" value="{{$employee_basicsalaryinfo[0]->amount}}" placeholder="Type your salary amount" value="0.00" required>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-sm-4 mb-2" id="noofhours">
                                        @if(count($employee_basicsalaryinfo) == 0)
                                            <label class="col-form-label">No. working hours per day</label>
                                            <input type="number" name="hoursperday" class="form-control mb-2" value="0"placeholder="No. working hours per day" required/>
                                        @elseif(count($employee_basicsalaryinfo) > 0)
                                            @if($employee_basicsalaryinfo[0]->type == 'Hourly')
                                            <label class="col-form-label">No. working hours per week</label>
                                            <input type="number" name="hoursperweek" class="form-control mb-2" value="{{$employee_basicsalaryinfo[0]->hoursperweek}}" placeholder="No. working hours per week" required/>
                                            @elseif($employee_basicsalaryinfo[0]->type == 'Project')
                                            @else
                                                <label class="col-form-label">No. working hours per day</label>
                                                <input type="number" name="hoursperday" class="form-control mb-2" value="{{$employee_basicsalaryinfo[0]->hoursperday}}" placeholder="No. working hours per day" required/>
                                            @endif
                                        @endif
                                    </div>
                                    @if(strtolower($tardinesssetup[0]->type) == 'custom')
                                        <div class="col-sm-12 mb-2" >
                                            <div class="row" >
                                                @if(count($employee_timeschedule) == 0)
                                                    <div class="col-md-3">
                                                        <label>AM IN</label>
                                                        <input id="timepickeramin"  employeeid="{{$profile->id}}" class="timepick form-control" value="07:30" name="am_in"/>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>AM OUT</label>
                                                        <input id="timepickeramout"  employeeid="{{$profile->id}}" class="timepick form-control" value="12:00" name="am_out"/>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>PM IN</label>
                                                        <input id="timepickerpmin"  employeeid="{{$profile->id}}" class="timepick form-control" value="01:30" name="pm_in"/>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>PM OUT</label>
                                                        <input id="timepickerpmout"  employeeid="{{$profile->id}}" class="timepick form-control" value="04:30" name="pm_out"/>
                                                    </div>
                                                @else
                                                    <div class="col-md-3">
                                                        <label>AM IN</label>
                                                        <input id="timepickeramin"  employeeid="{{$profile->id}}" class="timepick form-control" value="{{$employee_timeschedule[0]->amin}}" name="am_in"/>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>AM OUT</label>
                                                        <input id="timepickeramout"  employeeid="{{$profile->id}}" class="timepick form-control" value="{{$employee_timeschedule[0]->amout}}" name="am_out"/>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>PM IN</label>
                                                        <input id="timepickerpmin"  employeeid="{{$profile->id}}" class="timepick form-control" value="{{$employee_timeschedule[0]->pmin}}" name="pm_in"/>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>PM OUT</label>
                                                        <input id="timepickerpmout"  employeeid="{{$profile->id}}" class="timepick form-control" value="{{$employee_timeschedule[0]->pmout}}" name="pm_out"/>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div id="othersalarysettingcontainer">
                                    @if(count($employee_basicsalaryinfo)==0)
                                    @else
                                        @if($employee_basicsalaryinfo[0]->type == 'Hourly')
                                            <div class="row mt-2">
                                                <div class="col-md-2 col-5">
                                                    <div class="form-group clearfix">
                                                        <div class="icheck-primary d-inline col-md-5">
                                                            @if($employee_basicsalaryinfo[0]->mondays == 1)
                                                                <input type="checkbox" name="daysrender[]" value="monday" id="daymon" checked>
                                                            @else
                                                                <input type="checkbox" name="daysrender[]" value="monday" id="daymon">
                                                            @endif
                                                            <label class="mr-5" for="daymon">
                                                            M
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-5">
                                                    @if($employee_basicsalaryinfo[0]->mondays == 1)
                                                    <input type="number" class="form-control form-control-sm monday daysrender" value="{{$employee_basicsalaryinfo[0]->mondayhours}}" name="nodaysrender[]" value="0" readonly>
                                                    @else
                                                    <input type="number" class="form-control form-control-sm monday" name="nodaysrender[]" value="0" readonly disabled>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-2 col-5">
                                                    <div class="form-group clearfix">
                                                        <div class="icheck-primary d-inline col-md-5">
                                                            @if($employee_basicsalaryinfo[0]->tuesdays == 1)
                                                                <input type="checkbox" name="daysrender[]" value="tuesday" id="daytue" checked>
                                                            @else
                                                                <input type="checkbox" name="daysrender[]" value="tuesday" id="daytue">
                                                            @endif
                                                            <label class="mr-5" for="daytue">
                                                            T
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-5">
                                                    @if($employee_basicsalaryinfo[0]->tuesdays == 1)
                                                        <input type="number" class="form-control form-control-sm tuesday daysrender" value="{{$employee_basicsalaryinfo[0]->tuesdayhours}}" name="nodaysrender[]" value="0" readonly>
                                                    @else
                                                        <input type="number" class="form-control form-control-sm tuesday" name="nodaysrender[]" value="0" readonly disabled>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-2 col-5">
                                                    <div class="form-group clearfix">
                                                        <div class="icheck-primary d-inline col-md-5">
                                                            @if($employee_basicsalaryinfo[0]->wednesdays == 1)
                                                                <input type="checkbox" name="daysrender[]" value="wednesday" id="daywed" checked>
                                                            @else
                                                                <input type="checkbox" name="daysrender[]" value="wednesday" id="daywed" >
                                                            @endif
                                                            <label class="mr-5" for="daywed">
                                                            W
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-5">
                                                    @if($employee_basicsalaryinfo[0]->wednesdays == 1)
                                                        <input type="number" class="form-control form-control-sm wednesday daysrender" name="nodaysrender[]" value="{{$employee_basicsalaryinfo[0]->wednesdayhours}}" readonly>
                                                    @else
                                                        <input type="number" class="form-control form-control-sm wednesday" name="nodaysrender[]" value="0" readonly disabled>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-2 col-5">
                                                    <div class="form-group clearfix">
                                                        <div class="icheck-primary d-inline col-md-5">
                                                            @if($employee_basicsalaryinfo[0]->thursdays == 1)
                                                                <input type="checkbox" name="daysrender[]" value="thursday" id="daythu" checked>
                                                            @else
                                                                <input type="checkbox" name="daysrender[]" value="thursday" id="daythu">
                                                            @endif
                                                            <label class="mr-5" for="daythu">
                                                            Th
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-5">
                                                    @if($employee_basicsalaryinfo[0]->thursdays == 1)
                                                        <input type="number" class="form-control form-control-sm thursday daysrender" name="nodaysrender[]" value="{{$employee_basicsalaryinfo[0]->thursdayhours}}" readonly>
                                                    @else
                                                        <input type="number" class="form-control form-control-sm thursday" name="nodaysrender[]" value="0" readonly disabled>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-2 col-5">
                                                    <div class="form-group clearfix">
                                                        <div class="icheck-primary d-inline col-md-5">
                                                            @if($employee_basicsalaryinfo[0]->fridays == 1)
                                                                <input type="checkbox" name="daysrender[]" value="friday" id="dayfri" checked>
                                                            @else
                                                                <input type="checkbox" name="daysrender[]" value="friday" id="dayfri">
                                                            @endif
                                                            <label class="mr-5" for="dayfri">
                                                            F
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-5">
                                                    @if($employee_basicsalaryinfo[0]->fridays == 1)
                                                        <input type="number" class="form-control form-control-sm friday daysrender" name="nodaysrender[]" value="{{$employee_basicsalaryinfo[0]->fridayhours}}" readonly>
                                                    @else
                                                        <input type="number" class="form-control form-control-sm friday" name="nodaysrender[]" value="0" readonly disabled>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-2 col-5">
                                                    <div class="form-group clearfix">
                                                        <div class="icheck-primary d-inline col-md-5">
                                                            @if($employee_basicsalaryinfo[0]->saturdays == 1)
                                                                <input type="checkbox" name="daysrender[]" value="saturday" id="daysat" checked>
                                                            @else
                                                                <input type="checkbox" name="daysrender[]" value="saturday" id="daysat">
                                                            @endif
                                                            <label class="mr-5" for="daysat">
                                                            Sat
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-5">
                                                    @if($employee_basicsalaryinfo[0]->saturdays == 1)
                                                        <input type="number" class="form-control form-control-sm saturday daysrender" name="nodaysrender[]" value="{{$employee_basicsalaryinfo[0]->saturdayhours}}" readonly>
                                                    @else
                                                        <input type="number" class="form-control form-control-sm saturday" name="nodaysrender[]" value="0" readonly disabled>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-2 col-5">
                                                    <div class="form-group clearfix">
                                                        <div class="icheck-primary d-inline col-md-5">
                                                            @if($employee_basicsalaryinfo[0]->sundays == 1)
                                                                <input type="checkbox" name="daysrender[]" value="sunday" id="daysun" checked>
                                                            @else
                                                                <input type="checkbox" name="daysrender[]" value="sunday" id="daysun">
                                                            @endif
                                                            <label class="mr-5" for="daysun">
                                                            Sun
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-5">
                                                    @if($employee_basicsalaryinfo[0]->sundays == 1)
                                                        <input type="number" class="form-control form-control-sm sunday daysrender" name="nodaysrender[]" value="{{$employee_basicsalaryinfo[0]->sundayhours}}" readonly>
                                                    @else
                                                        <input type="number" class="form-control form-control-sm sunday" name="nodaysrender[]" value="0" readonly disabled>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($employee_basicsalaryinfo[0]->type == 'Project')
                                            @if($employee_basicsalaryinfo[0]->projectbasedtype == 'perday')
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="projectradiosettingtype1" name="projectradiosettingtype" value="perday" checked>
                                                                <label for="projectradiosettingtype1">Per day</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control form-control-sm projectamount" name="perdayamount" value="{{$employee_basicsalaryinfo[0]->amount}}" placeholder="Amount per day" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control form-control-sm projecthours" name="perdayhours"  value="{{$employee_basicsalaryinfo[0]->hoursperday}}"placeholder="No. of hours per day" required>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="projectradiosettingtype1" name="projectradiosettingtype" value="perday">
                                                                <label for="projectradiosettingtype1">Per day</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control form-control-sm projectamount" name="perdayamount" placeholder="Amount per day" disabled required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control form-control-sm projecthours" name="perdayhours" placeholder="No. of hours per day" disabled required>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($employee_basicsalaryinfo[0]->projectbasedtype == 'persalaryperiod')
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="projectradiosettingtype2" name="projectradiosettingtype" value="persalaryperiod" checked>
                                                                <label for="projectradiosettingtype2">Per salary period</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control form-control-sm projectamount" name="persalaryperiodamount" value="{{$employee_basicsalaryinfo[0]->amount}}" placeholder="Amount per salary period" required>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="projectradiosettingtype2" name="projectradiosettingtype" value="persalaryperiod">
                                                                <label for="projectradiosettingtype2">Per salary period</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control form-control-sm projectamount" name="persalaryperiodamount" disabled placeholder="Amount per salary period" required disabled>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($employee_basicsalaryinfo[0]->projectbasedtype == 'permonth')
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="projectradiosettingtype3" name="projectradiosettingtype" value="permonth" checked>
                                                                <label for="projectradiosettingtype3">Per month</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control form-control-sm projectamount" name="permonthamount" value="{{$employee_basicsalaryinfo[0]->amount}}" placeholder="Amount per month" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control form-control-sm projecthours" name="permonthhours" value="{{$employee_basicsalaryinfo[0]->hoursperday}}" placeholder="No. of hours per day" required>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="projectradiosettingtype3" name="projectradiosettingtype" value="permonth">
                                                                <label for="projectradiosettingtype3">Per month</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control form-control-sm projectamount" name="permonthamount" placeholder="Amount per month" required disabled>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" class="form-control form-control-sm projecthours" name="permonthhours" placeholder="No. of hours per day" required disabled>
                                                    </div>
                                                </div>  
                                            @endif
                                        @endif
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label">Payment type</label>
                                            <br>
                                            @if(count($employee_basicsalaryinfo) == 0)
                                            <select class="form-control" name="paymenttype" required>
                                                <option value="cash">Cash</option>
                                                <option value="check">Check</option>
                                                <option value="banktransfer">Bank deposit</option>
                                            </select>
                                            @elseif(count($employee_basicsalaryinfo) > 0)
                                                <select class="form-control" name="paymenttype" required>
                                                    <option value="cash" {{"cash" == $employee_basicsalaryinfo[0]->paymenttype ? "selected" : ""}}>Cash</option>
                                                    <option value="check" {{"check" == $employee_basicsalaryinfo[0]->paymenttype ? "selected" : ""}}>Check</option>
                                                    <option value="banktransfer" {{"banktransfer" == $employee_basicsalaryinfo[0]->paymenttype ? "selected" : ""}}>Bank Deposit</option>
                                                </select>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            <div class="submit-section">
                                
                                @if(count($employee_basicsalaryinfo) == 0)
                                <button class="btn btn-primary submit-btn basicsalarybutton" type="submit">Save</button>
                                @elseif(count($employee_basicsalaryinfo) > 0)
                                <button class="btn btn-warning submit-btn basicsalarybutton" type="submit">Update</button>
                                @endif
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                @if(session()->has('linkid'))
                    @if( session()->get('linkid') == 'custom-content-above-contributions')
                        <div class="tab-pane fade show active" id="custom-content-above-contributions" role="tabpanel" aria-labelledby="custom-content-above-contributions-tab">
                    @else
                        <div class="tab-pane fade" id="custom-content-above-contributions" role="tabpanel" aria-labelledby="custom-content-above-contributions-tab">
                    @endif
                @else
                    <div class="tab-pane fade" id="custom-content-above-contributions" role="tabpanel" aria-labelledby="custom-content-above-contributions-tab">
                @endif
                    <div class="card">
                        <div class="card-body">
                            <fieldset>
                              <legend><strong>Standard Deductions</strong></legend>
                            
                            <br>
                            <br>
                            <form action="/employeecontributions" method="get">
                                <input type="hidden" name="employeeid" value="{{$profile->id}}">
                                <input type="hidden" class="form-control" name="linkid" value="custom-content-above-contributions" />
                                <div style="width:100%;overflow: scroll">
                                <table class="table"  >
                                    <thead class="text-center">
                                        <tr>
                                            <th style="width:23%;">Particulars</th>
                                            <th>Employer's Share / month</th>
                                            <th>Employee's Share / month</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach ($deductiontypes as $deductiontype)
                                        <tr class="standarddeductiondetails">
                                            <td >
                                                <input type="hidden" name="deductiontypes[]" value="{{$deductiontype->id}}" disabled>
                                                @if(count($mycontributions) == 0)
                                                
                                                <button type="button" class="btn btn-warning btn-sm contributionscheckbox" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                    {{-- <div class="form-group clearfix">
                                                        <div class="icheck-primary d-inline"> --}}

                                                            {{-- <input type="checkbox" class="contributionscheckbox" id="checkboxPrimary{{$deductiontype->id}}" > --}}
                                                            <label for="checkboxPrimary{{$deductiontype->id}}" style="display: inline">
                                                                {{$deductiontype->description}}
                                                            </label>
                                                        {{-- </div>
                                                    </div> --}}
                                                @else
                                                            @foreach ($mycontributions as $mycontribution)
                                                                @if($deductiontype->id == $mycontribution->contributionid)
                                                                <button type="button" class="btn btn-warning btn-sm contributionscheckbox" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                    {{-- <input type="checkbox" class="contributionscheckbox" id="checkboxPrimary{{$deductiontype->id}}"> --}}
                                                                    <label for="checkboxPrimary{{$deductiontype->id}}" style="display: inline">
                                                                        {{$deductiontype->description}}
                                                                    </label>
                                                                @endif
                                                            @endforeach
                                                @endif
                                            </td>
                                            <td class="ersamountscontainer">
                                                @if(count($mycontributions) == 0)
                                                    <input type="number" class="form-control" name="ersamounts[]" placeholder="Employer Share / month" readonly="readonly" required/>
                                                @else
                                                    @foreach ($mycontributions as $mycontribution)
                                                        @if($deductiontype->id == $mycontribution->contributionid)
                                                            <input type="number" class="form-control" name="ersamounts[]" value="{{$mycontribution->ersamount}}" placeholder="Employer Share / month" readonly="readonly" required/>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="eesamountscontainer">
                                                @if(count($mycontributions) == 0)
                                                    <input type="number" class="form-control" name="eesamounts[]" placeholder="Employee Share / month" readonly="readonly" required/>
                                                @else
                                                    @foreach ($mycontributions as $mycontribution)
                                                        @if($deductiontype->id == $mycontribution->contributionid)
                                                            <input type="number" class="form-control" name="eesamounts[]" value="{{$mycontribution->eesamount}}"placeholder="Employee Share / month" readonly="readonly" required/>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="contributionsradioboxcontainer">
                                                @if(count($mycontributions) == 0)
                                                    <div class="icheck-success d-inline mr-3">
                                                        <input type="radio" id="{{$deductiontype->id}}1" value="active" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}" disabled="disabled">
                                                        <label for="{{$deductiontype->id}}1">
                                                            Active
                                                        </label>
                                                    </div>
                                                    <div class="icheck-secondary d-inline">
                                                        <input type="radio" id="{{$deductiontype->id}}2" value="inactive" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}" disabled="disabled" checked="">
                                                        <label for="{{$deductiontype->id}}2">
                                                            Inactive
                                                        </label>
                                                    </div>
                                                @else
                                                    @foreach ($mycontributions as $mycontribution)
                                                        @if($deductiontype->id == $mycontribution->contributionid)
                                                            @if($mycontribution->status == '1')
                                                                <div class="icheck-success d-inline mr-3">
                                                                    <input type="radio" id="{{$deductiontype->id}}1" value="active" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}" disabled="disabled" checked>
                                                                    <label for="{{$deductiontype->id}}1">
                                                                        Active
                                                                    </label>
                                                                </div>
                                                                <div class="icheck-secondary d-inline">
                                                                    <input type="radio" id="{{$deductiontype->id}}2" value="inactive" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}"disabled="disabled" >
                                                                    <label for="{{$deductiontype->id}}2">
                                                                        Inactive
                                                                    </label>
                                                                </div>
                                                            @else
                                                                <div class="icheck-success d-inline mr-3">
                                                                    <input type="radio" id="{{$deductiontype->id}}1" value="active" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}" disabled="disabled">
                                                                    <label for="{{$deductiontype->id}}1">
                                                                        Active
                                                                    </label>
                                                                </div>
                                                                <div class="icheck-secondary d-inline">
                                                                    <input type="radio" id="{{$deductiontype->id}}2" value="inactive" class="contributionsradiobox" name="contributionstatus{{$deductiontype->id}}" disabled="disabled" checked="">
                                                                    <label for="{{$deductiontype->id}}2">
                                                                        Inactive
                                                                    </label>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif

                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                </div>
                                <div class="row  mt-3">
                                    <div class="col-12 updatecontributionbutton">
                                        <button type="submit" class="btn btn-warning updatecontributionsbuttonstandard float-right">Update</button>
                                    </div>
                                </div>
                            </form>
                            </fieldset>
                            <br>
                            <br>
                            <fieldset >
                              <legend><strong>Other Deductions</strong></legend>
                                <div class="row" >
                                    <div class="col-md-4 col-12">
                                        <button type="button" class="btn btn-sm text-success float-left" id="adddeduction" clicked="0"><i class="fa fa-plus"></i>&nbsp; Add</button>
                                        <br>
                                        <br>
                                        <form action="/employeeotherdeductionsinfo" method="get">
                                            <input type="hidden" name="employeeid" value="{{$profile->id}}">
                                            <input type="hidden" class="form-control" name="linkid" value="custom-content-above-contributions" />
                                            <div class="adddeductioncontainer">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-8 col-12">
                                        <table class="table table-bordered text-center" style="font-size: 14px;">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Description
                                                    </th>
                                                    <th>
                                                        Total Amount
                                                    </th>
                                                    {{-- <th>
                                                        Amount Paid
                                                    </th> --}}
                                                    <th>
                                                        Term
                                                    </th>
                                                    <th>
                                                        Date Issued
                                                    </th>
                                                    <th style="width:18%">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="deductiondetails">
                                                @foreach ($myotherdeductions as $myotherdeduction)
                                                    <tr>
                                                        <td valign="middle">
                                                            {{$myotherdeduction->description}}
                                                        </td>
                                                        <td valign="middle">
                                                            &#8369; {{$myotherdeduction->amount}}
                                                        </td>
                                                        {{-- <td valign="middle">
                                                            &#8369;{{$myotherdeduction->amountpaid}}
                                                        </td> --}}
                                                        <td valign="middle">
                                                            {{$myotherdeduction->term}} month/s
                                                        </td>
                                                        <td valign="middle">
                                                            {{$myotherdeduction->dateissued}}
                                                        </td>
                                                        <td class="p-1" valign="middle deductiondetailbuttonscontainer" style="vertical-align: middle !important;text-align: center;">
                                                            @if($myotherdeduction->status == 0)
                                                            <button type="button" class="btn btn-sm btn-warning editdeductiondetail p-1 m-0" data-toggle="modal" data-target="#editotherdeductiondetail{{$myotherdeduction->id}}">Edit</button>
                                                                <div id="editotherdeductiondetail{{$myotherdeduction->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                                    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"><strong>Other Deduction</strong></h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">×</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <form action="/employeeotherdeductionsinfoedit" method="get">
                                                                                    <input type="hidden" name="employeeid" value="{{$profile->id}}">
                                                                                    <input type="hidden" name="otherdeductionid" value="{{$myotherdeduction->id}}">
                                                                                    <input type="hidden" class="form-control" name="linkid" value="custom-content-above-contributions" />
                                                                                    <label>Description</label>
                                                                                    <input type="text" class="form-control  mb-2" name="description" value="{{$myotherdeduction->description}}">
                                                                                    <label>Amount</label>
                                                                                    <input type="text" class="form-control mb-2" name="amount"  lang="en-150" value="{{$myotherdeduction->amount}}">
                                                                                    <label>Term (No. of months)</label>
                                                                                    <input type="number" class="form-control mb-2" name="term" value="{{$myotherdeduction->term}}">
                                                                                    <br>
                                                                                    <div class="submit-section">
                                                                                        <button type="submit" class="btn btn-primary submit-btn float-right">Update</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <button  type="button"class="btn btn-sm btn-danger deletedeductiondetail p-1 m-0" data-toggle="modal" data-target="#deletedotherdeductiondetail{{$myotherdeduction->id}}">Delete</button>
                                                                <div id="deletedotherdeductiondetail{{$myotherdeduction->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                                    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"><strong>Other Deduction</strong></h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">×</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <h3 class="text-danger">Are you sure you want to delete this other deduction item ?</h3>
                                                                                <br>
                                                                                <form action="/employeeotherdeductionsinfodelete" method="get">
                                                                                    <input type="hidden" name="employeeid" value="{{$profile->id}}">
                                                                                    <input type="hidden" name="otherdeductionid" value="{{$myotherdeduction->id}}">
                                                                                    <input type="hidden" class="form-control" name="linkid" value="custom-content-above-contributions" />
                                                                                    <label>Description</label>
                                                                                    <input type="text" class="form-control  mb-2" name="description" value="{{$myotherdeduction->description}}" disabled>
                                                                                    <label>Amount</label>
                                                                                    <input type="text" class="form-control mb-2" name="amount"  lang="en-150" value="{{$myotherdeduction->amount}}" disabled>
                                                                                    <label>Term (No. of months)</label>
                                                                                    <input type="number" class="form-control mb-2" name="term" value="{{$myotherdeduction->term}}" disabled>
                                                                                    <br>
                                                                                    <div class="submit-section">
                                                                                        <button type="submit" class="btn btn-primary submit-btn float-right">Update</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @elseif($myotherdeduction->status == 1)
                                                                <button class="btn btn-sm btn-secondary btn-block p-1 m-0">Paid</button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                @if(session()->has('linkid'))
                    @if( session()->get('linkid') == 'custom-content-above-allowance')
                        <div class="tab-pane fade show active" id="custom-content-above-allowance" role="tabpanel" aria-labelledby="custom-content-above-allowance-tab">
                    @else
                        <div class="tab-pane fade" id="custom-content-above-allowance" role="tabpanel" aria-labelledby="custom-content-above-allowance-tab">
                    @endif
                @else
                    <div class="tab-pane fade" id="custom-content-above-allowance" role="tabpanel" aria-labelledby="custom-content-above-allowance-tab">
                @endif
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset>
                                        <legend><strong>Standard Allowances</strong></legend>
                                        <form action="/employeestandardallowances" method="get">
                                            <input type="hidden" name="employeeid" value="{{$profile->id}}">
                                            <input type="hidden" class="form-control" name="linkid" value="custom-content-above-allowance" />
                                            <div style="width:100%;overflow: scroll">
                                            <table class="table"  >
                                                <thead class="text-center">
                                                    <tr>
                                                        <th>Particulars</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($standardallowances as $standardallowance)
                                                        <tr class="standardallowancedetails">
                                                            <td>
                                                                <input type="hidden" name="allowanceid[]" value="{{$standardallowance->id}}" disabled>
                                                                @if(count($mystandardallowances) == 0)
                                                                    {{-- <div class="form-group clearfix">
                                                                        <div class="icheck-primary d-inline"> --}}
                                                                            
                                                                            <button type="button" class="btn btn-warning btn-sm allowancescheckbox" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                                                <i class="fa fa-edit"></i>
                                                                            </button>
                                                                            {{-- <input type="checkbox" class="allowancescheckbox" id="checkboxPrimary{{$standardallowance->id}}" > --}}
                                                                            <label for="checkboxPrimary{{$standardallowance->id}}" style="display: inline;">
                                                                                {{$standardallowance->description}}
                                                                            </label>
                                                                        {{-- </div>
                                                                    </div> --}}
                                                                @else
                                                                    {{-- <div class="form-group clearfix">
                                                                        <div class="icheck-primary d-inline"> --}}
                                                                            @foreach ($mystandardallowances as $mystandardallowance)
                                                                                @if($standardallowance->id == $mystandardallowance->allowance_standardid)
                                                                                <button type="button" class="btn btn-warning btn-sm allowancescheckbox" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                                                    <i class="fa fa-edit"></i>
                                                                                </button>
                                                                                    <label for="checkboxPrimary{{$standardallowance->id}}" style="display: inline;">
                                                                                        {{$standardallowance->description}}
                                                                                    </label>
                                                                                @endif
                                                                            @endforeach
                                                                        {{-- </div>
                                                                    </div> --}}
                                                                @endif
                                                            </td>
                                                            <td class="standardallowanceamount">
                                                                @if(count($mystandardallowances) == 0)
                                                                    <input type="number" class="form-control" name="amounts[]" placeholder="Amount / month" disabled required/>
                                                                @else
                                                                    @foreach ($mystandardallowances as $mystandardallowance)
                                                                        @if($standardallowance->id == $mystandardallowance->allowance_standardid)
                                                                                <input type="number" class="form-control" name="amounts[]" value="{{$mystandardallowance->amount}}" placeholder="Amount / month" disabled required/>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td class="allowancesradioboxcontainer text-center">
                                                                @if(count($mystandardallowances) == 0)
                                                                    <div class="icheck-success d-inline mr-3">
                                                                        <input type="radio" id="allowance{{$standardallowance->id}}1" value="active" class="allowanceradiobox" name="allowancestatus{{$standardallowance->id}}" disabled="disabled">
                                                                        <label for="allowance{{$standardallowance->id}}1">
                                                                            Active
                                                                        </label>
                                                                    </div>
                                                                    <div class="icheck-secondary d-inline">
                                                                        <input type="radio" id="allowance{{$standardallowance->id}}2" value="inactive" class="allowanceradiobox" name="allowancestatus{{$standardallowance->id}}" disabled="disabled" checked="">
                                                                        <label for="allowance{{$standardallowance->id}}2">
                                                                            Inactive
                                                                        </label>
                                                                    </div>
                                                                @else
                                                                    @foreach ($mystandardallowances as $mystandardallowance)
                                                                        @if($standardallowance->id == $mystandardallowance->allowance_standardid)
                                                                            @if($mystandardallowance->status == '1')
                                                                                <div class="icheck-success d-inline mr-3">
                                                                                    <input type="radio" id="allowance{{$standardallowance->id}}1" value="active" class="allowanceradiobox" name="allowancestatus{{$standardallowance->id}}" disabled="disabled" checked="">
                                                                                    <label for="allowance{{$standardallowance->id}}1">
                                                                                        Active
                                                                                    </label>
                                                                                </div>
                                                                                <div class="icheck-secondary d-inline">
                                                                                    <input type="radio" id="allowance{{$standardallowance->id}}2" value="inactive" class="allowanceradiobox" name="allowancestatus{{$standardallowance->id}}" disabled="disabled" >
                                                                                    <label for="allowance{{$standardallowance->id}}2">
                                                                                        Inactive
                                                                                    </label>
                                                                                </div>
                                                                            @else
                                                                                <div class="icheck-success d-inline mr-3">
                                                                                    <input type="radio" id="allowance{{$standardallowance->id}}1" value="active" class="allowanceradiobox" name="allowancestatus{{$standardallowance->id}}" disabled="disabled">
                                                                                    <label for="allowance{{$standardallowance->id}}1">
                                                                                        Active
                                                                                    </label>
                                                                                </div>
                                                                                <div class="icheck-secondary d-inline">
                                                                                    <input type="radio" id="allowance{{$standardallowance->id}}2" value="inactive" class="allowanceradiobox" name="allowancestatus{{$standardallowance->id}}" disabled="disabled" checked="">
                                                                                    <label for="allowance{{$standardallowance->id}}2">
                                                                                        Inactive
                                                                                    </label>
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row  mt-3">
                                            <div class="col-12 updatecontributionbutton">
                                                <button type="submit" class="btn btn-warning updatecontributionsbutton float-right">Update</button>
                                            </div>
                                        </div>
                                        </form>
                                    </fieldset>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset>
                                        <div class="row">
                                        <legend><strong>Other Allowances</strong></legend>
                                        <div class="col-md-4 col-12">
                                            <button type="button" class="btn btn-sm text-success float-left" id="addallowance" clicked="0"><i class="fa fa-plus"></i>&nbsp; Add allowance/s</button>
                                            <br>
                                            <br>
                                            <form action="/employeeallowanceinfo" method="get" name="formotherallowance">
                                                <input type="hidden" name="employeeid" value="{{$profile->id}}">
                                                <input type="hidden" class="form-control" name="linkid" value="custom-content-above-allowance" />
                                                <div class="addallowancecontainer">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-8 col-12">
                                            <table class="table table-bordered text-center" style="font-size: 12px;">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Description
                                                        </th>
                                                        <th>
                                                            Amount
                                                        </th>
                                                        <th>
                                                            Term
                                                        </th>
                                                        <th style="width:18%">
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="deductiondetails">
                                                    @foreach ($myallowances as $myallowance)
                                                        <tr>
                                                            <td valign="middle">
                                                                {{$myallowance->description}}
                                                            </td>
                                                            <td valign="middle">
                                                                &#8369;{{$myallowance->amount}}
                                                            </td>
                                                            <td valign="middle">
                                                                {{$myallowance->term}} month/s
                                                            </td>
                                                            <td class="p-1" valign="middle" style="vertical-align: middle !important;text-align: center;">
                                                                @if($myallowance->status == 0)
                                                                    <button class="btn btn-sm btn-warning editallowancedetail p-1 m-0" data-toggle="modal" data-target="#editotherallowancedetail{{$myallowance->id}}">Edit</button>
                                                                    <div id="editotherallowancedetail{{$myallowance->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                                        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title"><strong>Other Allowance</strong></h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">×</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <form action="/employeeotherallowanceinfoedit" method="get">
                                                                                        <input type="hidden" name="employeeid" value="{{$profile->id}}">
                                                                                        <input type="hidden" name="otherallowanceid" value="{{$myallowance->id}}">
                                                                                        <input type="hidden" class="form-control" name="linkid" value="custom-content-above-allowance" />
                                                                                        <label>Description</label>
                                                                                        <input type="text" class="form-control  mb-2" name="description" value="{{$myallowance->description}}">
                                                                                        <label>Amount</label>
                                                                                        <input type="text" class="form-control mb-2" name="amount"  lang="en-150" value="{{$myallowance->amount}}">
                                                                                        <label>Term (No. of months)</label>
                                                                                        <input type="number" class="form-control mb-2" name="term" value="{{$myallowance->term}}">
                                                                                        <br>
                                                                                        <div class="submit-section">
                                                                                            <button type="submit" class="btn btn-primary submit-btn float-right">Update</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button class="btn btn-sm btn-danger deleteallowancedetail p-1 m-0" data-toggle="modal" data-target="#deleteotherallowancedetail{{$myallowance->id}}">Delete</button>
                                                                    <div id="deleteotherallowancedetail{{$myallowance->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                                        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title"><strong>Other Allowance</strong></h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">×</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <h3 class="text-danger">Are you sure you want to delete this other allowance item ?</h3>
                                                                                    <br>
                                                                                    <form action="/employeeotherallowanceinfodelete" method="get">
                                                                                        <input type="hidden" name="employeeid" value="{{$profile->id}}">
                                                                                        <input type="hidden" name="otherallowanceid" value="{{$myallowance->id}}">
                                                                                        <input type="hidden" class="form-control" name="linkid" value="custom-content-above-allowance" />
                                                                                        <label>Description</label>
                                                                                        <input type="text" class="form-control  mb-2" name="description" value="{{$myallowance->description}}" disabled>
                                                                                        <label>Amount</label>
                                                                                        <input type="text" class="form-control mb-2" name="amount"  lang="en-150" value="{{$myallowance->amount}}" disabled>
                                                                                        <label>Term (No. of months)</label>
                                                                                        <input type="number" class="form-control mb-2" name="term" value="{{$myallowance->term}}" disabled>
                                                                                        <br>
                                                                                        <div class="submit-section">
                                                                                            <button type="submit" class="btn btn-danger submit-btn float-right">Delete</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @elseif($myallowance->status == 1)
                                                                    <button class="btn btn-sm btn-secondary btn-block p-1 m-0">Paid</button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(session()->has('linkid'))
                    @if( session()->get('linkid') == 'custom-content-above-credentials')
                        <div class="tab-pane fade show active" id="custom-content-above-credentials" role="tabpanel" aria-labelledby="custom-content-above-credentials-tab">
                    @else
                        <div class="tab-pane fade" id="custom-content-above-credentials" role="tabpanel" aria-labelledby="custom-content-above-credentials-tab">
                    @endif
                @else
                    <div class="tab-pane fade show active" id="custom-content-above-credentials" role="tabpanel" aria-labelledby="custom-content-above-credentials-tab">
                @endif
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Credentials</strong>
                                </div>
                            </div>
                        </div>
                        @if(count($credentials) > 0)
                            <div class="card-body">
                                <table class="table table-bordered" style="table-layout: fixed;">
                                    <tbody>
                                        @foreach($credentials as $credential)
                                            <tr>
                                                <td>{{$credential->description}}</td>
                                                <td>
                                                    @if(count($employeecredentials) > 0)
                                                        @php
                                                            $match = 0;   
                                                            $extension = "";   
                                                            $filepath = "";   
                                                        @endphp
                                                        @foreach($employeecredentials as $employeecredential)
                                                            @if($credential->id == $employeecredential->credentialtypeid)
                                                                @php
                                                                    $match = 1;
                                                                    $extension = $employeecredential->extension;
                                                                    $filepath = $employeecredential->filepath;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                        @if($match == 0)
                                                            <button type="button" class="btn btn-sm text-success text-center" data-toggle="modal" data-target="#addcredential{{$credential->id}}">
                                                                <i class="fa fa-plus"></i>&nbsp; Add {{$credential->description}}
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-sm  text-center btn-primary btn-block " data-toggle="modal" data-target="#viewCredential{{$credential->id}}">
                                                                View
                                                            </button>
                                                            <div id="viewCredential{{$credential->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"><strong>{{$credential->description}}</strong></h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">×</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            @if($extension == 'pdf')
                                                                                <iframe src="{{asset($filepath)}}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                                                                            @else
                                                                            Word document is not yet supported.
                                                                            @endif
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <a href="{{asset($filepath)}}" class="btn btn-primary float-right btn-sm">Download {{$credential->description}}</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <button type="button" class="btn btn-sm text-success text-center" data-toggle="modal" data-target="#addcredential{{$credential->id}}">
                                                            <i class="fa fa-plus"></i>&nbsp; Add {{$credential->description}}
                                                        </button>
                                                    @endif
                                                    <div id="addcredential{{$credential->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"><strong>Add {{$credential->description}}</strong></h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <br>
                                                                    <form action="/employeecredential/{{Crypt::encrypt('add')}}" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <input type="hidden" name="credentialid" value="{{$credential->id}}"/>
                                                                        <input type="hidden" name="employeeid" value="{{$profile->id}}"/>
                                                                        <input type="file" name="credential" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,text/plain, application/pdf">
                                                                        <br>
                                                                        <br>
                                                                        <br>
                                                                            <button type="submit" class="btn btn-primary btn-sm float-right ">Upload {{$credential->description}}</button>   
                                                                    </form>                                                                 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('hr.employeeprofilemodals')
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/croppie/croppie.js')}}"></script>
<link rel="stylesheet" href="{{asset('plugins/croppie/croppie.css')}}">

<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
{{-- <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script> --}}
<script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script type="text/javascript">


    
    
var $ = jQuery;



$(document).ready(function(){
    $.ajaxSetup({
    
    headers: {
    
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    
    }
    
    });
    
    
    $uploadCrop = $('#upload-demo').croppie({
    
        enableExif: true,
    
        viewport: {
    
            width: 200,
    
            height: 200,
    
            type: 'circle'
    
        },
    
        boundary: {
    
            width: 300,
    
            height: 300
    
        }
    
    });
    
    
    $('#upload').on('change', function () { 
    
        var reader = new FileReader();
    
        reader.onload = function (e) {
    
            $uploadCrop.croppie('bind', {
    
                url: e.target.result
    
            }).then(function(){
    
                console.log('jQuery bind complete');
    
            });
    
        }
    
        reader.readAsDataURL(this.files[0]);
    
    });
    
    
    $('.upload-result').on('click', function (ev) {
    
        $uploadCrop.croppie('result', {
    
            type: 'canvas',
    
            size: 'viewport'
    
        }).then(function (resp) {
    
            $.ajax({
    
                url: "/image-crop",
    
                type: "POST",
    
                data: {
                    "image"     :   resp,
                    "employeeid":   '{{$profile->id}}',
                    "lastname"  :   '{{$profile->lastname}}',
                    "username"  :   '{{$profile->tid}}'

                    },
    
                success: function (data) {
                    console.log(data)
                    window.location.reload();
                    // html = '<img class="elevation-2" src="' + resp + '" />';

                    // $("#upload-demo-i").html(html);
                }
    
            });
    
        });
    
    });
    // console.log($('#timepickeramin'))
    $('#timepickeramin').timepicker({ modal: false, header: false, footer: false, format: 'HH:MM'});
    $('#timepickeramin').on('change', function(){
        var timevalue = $(this).val().split(':');
        if(timevalue[0] == '00'){
            $(this).val('12:'+timevalue[1])
        }
        $.ajax({
            url: '/employeecustomtimesched/{{Crypt::encrypt('am_in')}}',
            type:"GET",
            dataType:"json",
            data:{
                employeeid:$(this).attr('employeeid'),
                am_in:$(this).val()
            },
            success:function(data) {
            }
        });
    })
    $('#timepickeramout').timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});
    $('#timepickeramout').on('change', function(){
        var timevalue = $(this).val().split(':');
        if(timevalue[0] == '00'){
            $(this).val('12:'+timevalue[1])
        }
        $.ajax({
            url: '/employeecustomtimesched/{{Crypt::encrypt('am_out')}}',
            type:"GET",
            dataType:"json",
            data:{
                employeeid:$(this).attr('employeeid'),
                am_out:$(this).val()
            },
            success:function(data) {
            }
        });
    })
    $('#timepickerpmin').timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});
    $('#timepickerpmin').on('change', function(){
        var timevalue = $(this).val().split(':');
        if(timevalue[0] == '00'){
            $(this).val('12:'+timevalue[1])
        }
        $.ajax({
            url: '/employeecustomtimesched/{{Crypt::encrypt('pm_in')}}',
            type:"GET",
            dataType:"json",
            data:{
                employeeid:$(this).attr('employeeid'),
                pm_in:$(this).val()
            },
            success:function(data) {
            }
        });
    })
    $('#timepickerpmout').timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});
    $('#timepickerpmout').on('change', function(){
        var timevalue = $(this).val().split(':');
        if(timevalue[0] == '00'){
            $(this).val('12:'+timevalue[1])
        }
        $.ajax({
            url: '/employeecustomtimesched/{{Crypt::encrypt('pm_out')}}',
            type:"GET",
            dataType:"json",
            data:{
                employeeid:$(this).attr('employeeid'),
                pm_out:$(this).val()
            },
            success:function(data) {
            }
        });
    })
})




    // $('button.updatecontributionbutton').hide();
        $(document).ready(function(){
            
            $("#contactnum").inputmask({mask: "9999-999-9999"});
            $("#emergencycontactnumber").inputmask({mask: "9999-999-9999"});
            $(".familycontactnum").inputmask({mask: "9999-999-9999"});
        });
    $(document).on('change','select[name=departmentid]', function(){
        $.ajax({
            url: '/employeeinfo/getdesignations',
            type:"GET",
            dataType:"json",
            data:{
                departmentid:$(this).val()
            },
            success:function(data) {
                $('select[name=designationid]').empty();
                if(data == 0){

                }else{
                $.each(data, function(key, value){
                    $('select[name=designationid]').append(
                        '<option value="'+value.id+'">'+value.designation+'</option>'
                    )
                });
                }
            }
        });
    })
   $(document).ready(function () {
            $('#custom-content-above-tab a[href="#{{ old('linkid') }}"]').tab('show')
    });
   $(document).ready(function() {
        $('#currentDate').datepicker({
            format: 'dd-mm-yyyy'
        });
        window.setTimeout(function () {
            $(".alert-success").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
        window.setTimeout(function () {
            $(".alert-danger").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
        $("button[name='updatebenefits']").prop("type", "button");
        var checkedcheckboxes = 0;
        var candelete = 0;
        $('input[type=checkbox]').on('click',function (){
            if($(this)[0].checked == true){
                $(this).next().next().attr('disabled',false);
                checkedcheckboxes+=1;
                if(checkedcheckboxes == 0){
                        $("button[name='updatebenefits']").prop("type", "button");
                }else{
                        $("button[name='updatebenefits']").prop("type", "submit");
                }
            }
            else if($(this)[0].checked == false){
                $(this).next().next().attr('disabled','disabled');
                checkedcheckboxes-=1;
                if(checkedcheckboxes == 0){
                        $("button[name='updatebenefits']").prop("type", "button");
                }else{
                        $("button[name='updatebenefits']").prop("type", "submit");
                }
                if(candelete == 1){
                        $("button[name='updatebenefits']").prop("type", "submit");
                }
            }
        })
        $('input[type=checkbox]').each(function(){
            if($(this)[0].checked == true){
                checkedcheckboxes+=1;
            }
        })
        if(checkedcheckboxes == 0){
            $("button[name='updatebenefits']").prop("type", "button");
        }else if(checkedcheckboxes >= 1){
            candelete+=1;
        }else{
            $("button[name='updatebenefits']").prop("type", "submit");
        }

        // ===========================================================================
            // PROFILE 
        // ===========================================================================
        
        $('.addrow').on('click', function(){
            $('#familytbody').append(
                '<tr>'+
                    '<td class="p-0"><input class="form-control text-uppercase" type="text" name="familyname[]" required/></td>'+
                    '<td class="p-0"><input class="form-control text-uppercase" type="text" name="familyrelation[]"/></td>'+
                    // '<td class="p-0"><input class="form-control text-uppercase" type="date" name="familydob[]"/></td>'+
                    '<td class="p-0"><input class="form-control text-uppercase familycontactnum" type="text" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" name="familynum[]"/></td>'+
                    '<td class="p-0 bg-danger" style="vertical-align: middle;"><button type="button" class="btn btn-sm btn-danger btn-block deleterow"><i class="fa fa-times"></i></button></td>'+
                '</tr>'
            );
            $(".familycontactnum").inputmask({mask: "9999-999-9999"});
        });
        $(document).on('click','.deleterow', function(){
            $(this).closest('tr').remove();
        })
        
        $(document).on('click','.addeducationcard', function(){
            $(".modal-content").scrollTop(0);
            $('#educationalbackgroundcontainer').prepend(
                '<div class="card p-4">'+
                    '<div class="row">'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Institution</label>'+
                                '<input type="text" style="border:none" name="schoolname[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase" required/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Address</label>'+
                                '<input type="text" style="border:none" name="address[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Course Taken</label>'+
                                '<input type="text" style="border:none" name="coursetaken[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Major</label>'+
                                '<input type="text" style="border:none" name="major[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Date Completed</label>'+
                                '<input type="date" style="border:none" name="datecompleted[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-lg-6 mb-2 pb-0" >'+
                                '<div class="col-12"style="position:absolute;bottom:0;left:0; "><button type="button" class="btn btn-default btn-sm float-right deletecard">Delete &nbsp;<i class="fas fa-trash-alt text-danger"></i></button></div>'+
                        '</div>'+
                    '</div>'+
                '</div>'
            );
        });
        $(document).on('click','.deletecard', function(){
            $(this).closest('div.card').remove();
        }) 
        $(document).on('click','.addexperiencecard', function(){
            $(".modal-content").scrollTop(0);
            $('#experiencecontainer').prepend(
                '<div class="card p-4">'+
                    '<div class="row">'+
                        '<div class="col-lg-12 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Company Name</label>'+
                                '<input type="text" style="border:none" name="companyname[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase" required/>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Location</label>'+
                                '<input type="text" style="border:none" name="location[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Job Position</label>'+
                                '<input type="text" style="border:none" name="jobposition[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Period from</label>'+
                                '<input type="date" style="border:none" name="periodfrom[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-lg-6 mb-2 pb-0">'+
                            '<div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">'+
                                '<label class="mb-0">Period to</label>'+
                                '<input type="date" style="border:none" name="periodto[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-lg-12 mb-2 pb-0" >'+
                            '<div class="col-12"style="position:absolute;top:0;right:0;"><button type="button" class="btn btn-default btn-sm float-right deletecard">Delete &nbsp;<i class="fas fa-trash-alt text-danger"></i></button></div><br>&nbsp;'+
                        '</div>'+
                    '</div>'+
                '</div>'
            );
        });
        // ===========================================================================
            // Salary Details 
        // ===========================================================================
        $('.basicsalarybutton').hide();
        // var salarybasistype             = 0;
        // var salaryamount                = 0;
        // var hoursperweek                = 0;
        // var hoursperday                 = 0;
        // var projectradiosettingtype     = 0;
        // var perdayamount                = 0;
        // var perdayhours                 = 0;
        // var persalaryperiodamount       = 0;
        // var permonthamount              = 0;
        // var permonthhours               = 0;
        $('select[name=salarybasistype]').on('change', function(){
                $('.basicsalarybutton').show();
        });
        $('input[name=hoursperweek]').on('input', function(){
                $('.basicsalarybutton').show();
        });
        $('input[name=hoursperday]').on('input', function(){
                $('.basicsalarybutton').show();
        });
        $('input[name=salaryamount]').on('input', function(){
                $('.basicsalarybutton').show();
        });
        $('input[name=projectradiosettingtype').on('click', function(){
                $('.basicsalarybutton').show();
        });
        $('input[name=perdayamount]').on('input', function(){
                $('.basicsalarybutton').show();
        });
        $('input[name=perdayhours]').on('input', function(){
                $('.basicsalarybutton').show();
        });
        $('input[name=persalaryperiodamount]').on('input', function(){
                $('.basicsalarybutton').show();
        });
        $('input[name=permonthamount]').on('input', function(){
                $('.basicsalarybutton').show();
        });
        $('input[name=permonthhours]').on('input', function(){
                $('.basicsalarybutton').show();
        });
        $('.timepick').on('change', function(){
                $('.basicsalarybutton').show();
        })
        
        var clickeddays = 0;
        $('input[name="daysrender[]"]').each(function(){
            if($(this).prop('checked') == true){
            clickeddays+=1;
            }
        });
        console.log(clickeddays)
        $(document).on('change','select[name="salarybasistype"]', function(){
                $('#generalsalaryamouncontainer').empty();
                $('#noofhours').empty();
                $('#othersalarysettingcontainer').empty();
            if($(this).val() == '7'){
                $('#othersalarysettingcontainer').append(
                    '<div class="col-md-4">'+
                    '<label class="col-form-label">No. of months</label>'+
                    '<input type="number" name="noofmonthscontractual" class="form-control" placeholder="No. of months" required/>'+
                    '</div>'
                );
            }
            else if($(this).val() == '4'){
                $('#generalsalaryamouncontainer').append(
                    '<div class="form-group">'+
                        '<label class="col-form-label">Salary amount</label>'+
                        '<br>'+
                        '<div class="input-group">'+
                            '<div class="input-group-prepend">'+
                                '<span class="input-group-text">&#8369;</span>'+
                            '</div>'+
                            '<input type="number" class="form-control" name="salaryamount" placeholder="Type your salary amount" value="0.00" required>'+
                        '</div>'+
                    '</div>'
                );
                $('#noofhours').append(
                    '<label class="col-form-label">No. working hours per day</label>'+
                    '<input type="number" name="hoursperday" class="form-control mb-2" value="0"placeholder="No. working hours per day" required/>'
                );                    
            }
            else if($(this).val() == '5'){
                $('#generalsalaryamouncontainer').append(
                    '<div class="form-group">'+
                        '<label class="col-form-label">Salary amount</label>'+
                        '<br>'+
                        '<div class="input-group">'+
                            '<div class="input-group-prepend">'+
                                '<span class="input-group-text">&#8369;</span>'+
                            '</div>'+
                            '<input type="text" class="form-control groupOfTexbox" name="salaryamount" placeholder="Type your salary amount" value="0.00" required>'+
                        '</div>'+
                    '</div>'
                );
                $('#noofhours').append(
                    '<label class="col-form-label">No. working hours per day</label>'+
                    '<input type="number" name="hoursperday" class="form-control mb-2" value="0"placeholder="No. working hours per day" required/>'
                );                    
            }
            else if($(this).val() == '6'){
                $('#generalsalaryamouncontainer').append(
                    '<div class="form-group">'+
                        '<label class="col-form-label">Salary amount</label>'+
                        '<br>'+
                        '<div class="input-group">'+
                            '<div class="input-group-prepend">'+
                                '<span class="input-group-text">&#8369;</span>'+
                            '</div>'+
                            '<input type="text" class="form-control groupOfTexbox" name="salaryamount" placeholder="Type your salary amount" value="0.00" required>'+
                        '</div>'+
                    '</div>'
                );
                $('#othersalarysettingcontainer').prepend(
                    
                    // '<label class="col-form-label">Days to render</label><br>'+
                    '<div class="row">'+
                        '<div class="col-md-4">'+
                            '<label class="col-form-label">No. working hours per week</label>'+
                            '<input type="number" name="hoursperweek" class="form-control" value="0"placeholder="No. working hours per week" required/>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row mt-2">'+
                        '<div class="col-md-2 col-5">'+
                            '<div class="form-group clearfix">'+
                                '<div class="icheck-primary d-inline col-md-5">'+
                                    '<input type="checkbox" name="daysrender[]" value="monday" id="daymon" checked>'+
                                    '<label class="mr-5" for="daymon">'+
                                    'M'+
                                    '</label>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2 col-5">'+
                            '<input type="number" class="form-control form-control-sm monday daysrender" name="nodaysrender[]" value="0" readonly>'+
                        '</div>'+
                        '</div>'+
                        '<div class="row mt-2">'+
                        '<div class="col-md-2 col-5">'+
                            '<div class="form-group clearfix">'+
                                '<div class="icheck-primary d-inline col-md-5">'+
                                    '<input type="checkbox" name="daysrender[]" value="tuesday" id="daytue" checked>'+
                                    '<label class="mr-5" for="daytue">'+
                                    'T'+
                                    '</label>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2 col-5">'+
                            '<input type="number" class="form-control form-control-sm tuesday daysrender" name="nodaysrender[]" value="0" readonly>'+
                        '</div>'+
                        '</div>'+
                        '<div class="row mt-2">'+
                        '<div class="col-md-2 col-5">'+
                            '<div class="form-group clearfix">'+
                                '<div class="icheck-primary d-inline col-md-5">'+
                                    '<input type="checkbox" name="daysrender[]" value="wednesday" id="daywed" checked>'+
                                    '<label class="mr-5" for="daywed">'+
                                    'W'+
                                    '</label>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2 col-5">'+
                            '<input type="number" class="form-control form-control-sm wednesday daysrender" name="nodaysrender[]" value="0" readonly>'+
                        '</div>'+
                        '</div>'+
                        '<div class="row mt-2">'+
                        '<div class="col-md-2 col-5">'+
                            '<div class="form-group clearfix">'+
                                '<div class="icheck-primary d-inline col-md-5">'+
                                    '<input type="checkbox" name="daysrender[]" value="thursday" id="daythu" checked>'+
                                    '<label class="mr-5" for="daythu">'+
                                    'Th'+
                                    '</label>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2 col-5">'+
                            '<input type="number" class="form-control form-control-sm thursday daysrender" name="nodaysrender[]" value="0" readonly>'+
                        '</div>'+
                        '</div>'+
                        '<div class="row mt-2">'+
                        '<div class="col-md-2 col-5">'+
                            '<div class="form-group clearfix">'+
                                '<div class="icheck-primary d-inline col-md-5">'+
                                    '<input type="checkbox" name="daysrender[]" value="friday" id="dayfri" checked>'+
                                    '<label class="mr-5" for="dayfri">'+
                                    'F'+
                                    '</label>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2 col-5">'+
                            '<input type="number" class="form-control form-control-sm friday daysrender" name="nodaysrender[]" value="0" readonly>'+
                        '</div>'+
                        '</div>'+
                        '<div class="row mt-2">'+
                        '<div class="col-md-2 col-5">'+
                            '<div class="form-group clearfix">'+
                                '<div class="icheck-primary d-inline col-md-5">'+
                                    '<input type="checkbox" name="daysrender[]" value="saturday" id="daysat" checked>'+
                                    '<label class="mr-5" for="daysat">'+
                                    'Sat'+
                                    '</label>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2 col-5">'+
                            '<input type="number" class="form-control form-control-sm saturday daysrender" name="nodaysrender[]" value="0" readonly>'+
                        '</div>'+
                        '</div>'+
                        '<div class="row mt-2">'+
                        '<div class="col-md-2 col-5">'+
                            '<div class="form-group clearfix">'+
                                '<div class="icheck-primary d-inline col-md-5">'+
                                    '<input type="checkbox" name="daysrender[]" value="sunday" id="daysun" checked>'+
                                    '<label class="mr-5" for="daysun">'+
                                    'Sun'+
                                    '</label>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-2 col-5">'+
                            '<input type="number" class="form-control form-control-sm sunday daysrender" name="nodaysrender[]" value="0" readonly>'+
                        '</div>'+
                        '</div>'
                );
            }
            else if($(this).val() == '8'){
                $('#othersalarysettingcontainer').append(
                    '<div class="row">'+
                        '<div class="col-md-3">'+
                            '<div class="form-group clearfix">'+
                                '<div class="icheck-primary d-inline">'+
                                    '<input type="radio" id="projectradiosettingtype1" name="projectradiosettingtype" value="perday" checked>'+
                                    '<label for="projectradiosettingtype1">Per day</label>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-3">'+
                            '<input type="number" class="form-control form-control-sm projectamount" name="perdayamount" placeholder="Amount per day" required>'+
                        '</div>'+
                        '<div class="col-md-3">'+
                            '<input type="number" class="form-control form-control-sm projecthours" name="perdayhours" placeholder="No. of hours per day" required>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-md-3">'+
                            '<div class="form-group clearfix">'+
                                '<div class="icheck-primary d-inline">'+
                                    '<input type="radio" id="projectradiosettingtype2" name="projectradiosettingtype" value="persalaryperiod">'+
                                    '<label for="projectradiosettingtype2">Per salary period</label>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-3">'+
                            '<input type="number" class="form-control form-control-sm projectamount" name="persalaryperiodamount" placeholder="Amount per salary period" required disabled>'+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-md-3">'+
                            '<div class="form-group clearfix">'+
                                '<div class="icheck-primary d-inline">'+
                                    '<input type="radio" id="projectradiosettingtype3" name="projectradiosettingtype" value="permonth">'+
                                    '<label for="projectradiosettingtype3">Per month</label>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-3">'+
                            '<input type="number" class="form-control form-control-sm projectamount" name="permonthamount" placeholder="Amount per month" required disabled>'+
                        '</div>'+
                        '<div class="col-md-3">'+
                            '<input type="number" class="form-control form-control-sm projecthours" name="permonthhours" placeholder="No. of hours per day" required disabled>'+
                        '</div>'+
                    '</div>'
                );
                // $('#othersalarysettingcontainer').append(
                //     '<div class="row">'+
                //         '<div class="col-md-4">'+
                //             ''
                //         '</div>'+
                //     '</div>'
                // )
            }
            $('input[name="daysrender[]"]').each(function(){
                clickeddays+=1;
            });
        });
        $(document).ready(function() {
        $('.groupOfTexbox').keypress(function (event) {
            return isNumber(event, this)
        });
    });
    // THE SCRIPT THAT CHECKS IF THE KEY PRESSED IS A NUMERIC OR DECIMAL VALUE.
    function isNumber(evt, element) {

        var charCode = (evt.which) ? evt.which : event.keyCode

        if (
            (charCode != 45 || $(element).val().indexOf('-') != -1) &&      // “-” CHECK MINUS, AND ONLY ONE.
            (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
            (charCode < 48 || charCode > 57))
            return false;

        return true;
    }    
        $(document).on('click','input[name=projectradiosettingtype]', function(){
            $('input.projectamount').attr('disabled',true);
            $('input.projecthours').attr('disabled',true);
            $(this).closest('.row').find('input.projectamount').attr('disabled',false);
            $(this).closest('.row').find('input.projecthours').attr('disabled',false);
        })
        
        $(document).on('input','input[name="hoursperweek"]',function(){
            console.log($(this).val())
            var valueeach = ($(this).val() / clickeddays).toFixed(1);
            $('input.daysrender').val(valueeach)
        })
        $(document).on('click','input[name="daysrender[]"]',function(){
            if($(this).prop('checked') == true){
                clickeddays+=1;
                $(this).closest('.row').find('input[name="nodaysrender[]"').addClass('daysrender');
                $(this).closest('.row').find('input[name="nodaysrender[]"').attr('disabled',false);
            }else{
                clickeddays-=1;
                $(this).closest('.row').find('.daysrender').val(0);
                $(this).closest('.row').find('.daysrender').removeClass('daysrender');
                $(this).closest('.row').find('input[name="nodaysrender[]"').attr('disabled',true);
            }
            console.log(clickeddays)
            $('input.daysrender').val($('input[name="hoursperweek"]').val()/clickeddays)

        })
        // ===========================================================================
            // Deduction Standard Details 
        // ===========================================================================


        $(document).on('click','.contributionscheckbox', function(){
            if($(this).hasClass('active') == false){
                //activeradio
                $(this).addClass('active');
                $(this).removeClass('bg-warning');
                $(this).addClass('bg-secondary');
                // console.log( $(this).closest('.standarddeductiondetails').find('.ersamountscontainer'))
                $(this).closest('.standarddeductiondetails').find('input[name="deductiontypes[]"]').attr('disabled',false)
                $(this).closest('.standarddeductiondetails').find('.contributionsradioboxcontainer')[0].children[0].children[0].readOnly = false;
                //inactiveradio
                $(this).closest('.standarddeductiondetails').find('.contributionsradioboxcontainer')[0].children[1].children[0].disabled = false;
                $(this).closest('.standarddeductiondetails').find('.contributionsradioboxcontainer')[0].children[0].children[0].disabled = false;
                //ersinput
                $(this).closest('.standarddeductiondetails').find('.ersamountscontainer')[0].children[0].readOnly = false;
                //eesinput
                $(this).closest('.standarddeductiondetails').find('.eesamountscontainer')[0].children[0].readOnly = false;
            }
            else if($(this).hasClass('active') == true){
                $(this).removeClass('active')
                $(this).addClass('bg-warning');
                $(this).removeClass('bg-secondary');
                // $('input').attr('readonly',true)
                $(this).closest('.standarddeductiondetails').find('input[name="deductiontypes[]"]').attr('disabled',true)
                $(this).closest('.standarddeductiondetails').find('.contributionsradioboxcontainer')[0].children[0].children[0].readOnly = true;
                
                $(this).closest('.standarddeductiondetails').find('.contributionsradioboxcontainer')[0].children[1].children[0].disabled = true;
                $(this).closest('.standarddeductiondetails').find('.contributionsradioboxcontainer')[0].children[0].children[0].disabled = true;
                //ersinput
                $(this).closest('.standarddeductiondetails').find('.ersamountscontainer')[0].children[0].readOnly = true;
                //eesinput
                $(this).closest('.standarddeductiondetails').find('.eesamountscontainer')[0].children[0].readOnly = true;
            }
        })
        // ===========================================================================
            // Deduction Details 
        // ===========================================================================
        
        $('.editdeductiondetail').on('click', function(){
            $()
        })
        var adddeductiondetailrow = 0;
        $(document).on('click','#adddeduction', function(){
            if(adddeductiondetailrow == 0){
                $('.adddeductioncontainer').append(
                    '<div class="card">'+
                        '<button type="submit" class="btn btn-block btn-success savedeductionbutton">Save</button>'+
                    '</div>'
                );
                $('.adddeductioncontainer').prepend(
                    '<div class="card">'+
                        '<div class="card-header">'+
                            '<div class="card-tools">'+
                                // '<button type="button" class="btn btn-tool" data-card-widget="collapse">'+
                                //     '<i class="fas fa-minus"></i>'+
                                // '</button>'+
                                '<button type="button" class="btn btn-tool removedeductioncard" data-card-widget="remove">'+
                                    '<i class="fas fa-times"></i>'+
                                '</button>'+
                            '</div>'+
                        '</div>'+
                        '<div class="card-body">'+
                        
                            '<small><strong>Description</strong></small>'+
                            '<input type="text" name="description[]" class="form-control form-control-sm mb-2" placeholder="Description" required/>'+
                        
                            '<small><strong>Total Amount</strong></small>'+
                            '<input type="number" name="totalamount[]" class="form-control form-control-sm mb-2" placeholder="Total Amount" required/>'+

                            '<small><strong>Payable for (no. of months)</strong></small>'+
                            '<input type="number" name="term[]" class="form-control form-control-sm" placeholder="No. of months" required/>'+
                            // '<small><strong>Select deduction type</strong></small>'+
                            // '<div class="deductiondetailcontainer"></div>'+
                            // '<small><strong>Enter Amount</strong></small>'+
                            // '<input type="number" name="amount[]" class="form-control form-control-sm" placeholder="Amount" required/>'+
                        '</div>'+
                    '</div>'
                );
            }
            else if(adddeductiondetailrow > 0){
                $('.adddeductioncontainer').prepend(
                    '<div class="card">'+
                        '<div class="card-header">'+
                            '<div class="card-tools">'+
                                '<button type="button" class="btn btn-tool removedeductioncard" data-card-widget="remove">'+
                                    '<i class="fas fa-times"></i>'+
                                '</button>'+
                            '</div>'+
                        '</div>'+
                        '<div class="card-body">'+
                        
                        '<small><strong>Description</strong></small>'+
                        '<input type="text" name="description[]" class="form-control form-control-sm mb-2" placeholder="Description" required/>'+
                    
                        '<small><strong>Total Amount</strong></small>'+
                        '<input type="number" name="totalamount[]" class="form-control form-control-sm mb-2" placeholder="Total Amount" required/>'+

                        '<small><strong>Payable for (no. of months)</strong></small>'+
                        '<input type="number" name="term[]" class="form-control form-control-sm" placeholder="No. of months" required/>'+
                            // '<small><strong>Select deduction type</strong></small>'+
                            // '<small><strong>Enter Amount</strong></small>'+
                            // '<input type="number" name="amount[]" class="form-control form-control-sm" placeholder="Amount" required/>'+
                        '</div>'+
                    '</div>'
                );
            }
                adddeductiondetailrow+=1;
        });
        // $('select[name="deductiontypeid[]"]').on('change', function(){
        // $(document).on('change','select[name="deductiontypeid"]', function(){
        //         $.ajax({
        //             url: '/employeededuction/getdeductiondetail',
        //             type:"GET",
        //             dataType:"json",
        //             data:{
        //                 deductiontypeid:$(this).val()
        //             },
        //             success:function(data) {
        //                 console.log(data)
        //                 $('.deductiondetailcontainer').empty();
        //                 if(data.length == 0){
        //                     $('.deductiondetailcontainer').append(
        //                         '<span class="text-danger">No deduction details shown!</span>'
        //                     );
        //                 }else{
        //                     $('.deductiondetailcontainer').append(
        //                         '<select name="deductiondetail" class="form-control form-control-sm deductiondetailselection" required>'+
        //                             '<option>Select Item</option>'+
        //                         '</select>'
        //                     );

        //                     $.each(data, function(key, value){
        //                         $('.deductiondetailselection').append(
        //                             '<option value="'+value.id+'">'+value.bracketname+'</option>'
        //                         );
        //                     })
        //                 }
        //             }
        //         });
        // })
        $(document).on('click','.removedeductioncard', function(){
            adddeductiondetailrow-=1;
            if(adddeductiondetailrow == 0){
                $('.adddeductioncontainer').empty();
            }
        })
        // ===========================================================================
            // Allowance Details 
        // ===========================================================================
        $(document).on('click','.allowancescheckbox', function(){
            if($(this).hasClass('active') == false){
                //activeradio
                $(this).addClass('active');
                $(this).removeClass('bg-warning');
                $(this).addClass('bg-secondary');
                // $(this).closest('.standardallowancedetails').find('.allowancesradioboxcontainer')[0].children[0].children[0].readOnly = false;
                //inactiveradio
                $(this).closest('.standardallowancedetails').find('input[name="allowanceid[]"]').attr('disabled',false)
                $(this).closest('.standardallowancedetails').find('.allowancesradioboxcontainer')[0].children[1].children[0].disabled = false;
                $(this).closest('.standardallowancedetails').find('.allowancesradioboxcontainer')[0].children[0].children[0].disabled = false;
                //eesinput
                $(this).closest('.standardallowancedetails').find('.standardallowanceamount')[0].children[0].disabled = false;
            }
            else if($(this).hasClass('active') == true){
                $(this).removeClass('active')
                $(this).addClass('bg-warning');
                $(this).removeClass('bg-secondary');
                $(this).closest('.standardallowancedetails').find('input[name="allowanceid[]"]').attr('disabled',true);
                $(this).closest('.standardallowancedetails').find('.allowancesradioboxcontainer')[0].children[0].children[0].readOnly = true;
                
                $(this).closest('.standardallowancedetails').find('.allowancesradioboxcontainer')[0].children[1].children[0].disabled = true;
                $(this).closest('.standardallowancedetails').find('.allowancesradioboxcontainer')[0].children[0].children[0].disabled = true;
                //ersinput
                $(this).closest('.standardallowancedetails').find('.standardallowanceamount')[0].children[0].disabled = true;
            }
        })
        var addallowancedetailrow = 0;
        $(document).on('click','#addallowance', function(){
            if(addallowancedetailrow == 0){
                $('.addallowancecontainer').append(
                    '<div class="card">'+
                        '<button type="submit" class="btn btn-block btn-success saveallowancebutton">Save</button>'+
                    '</div>'
                );
                $('.addallowancecontainer').prepend(
                    '<div class="card">'+
                        '<div class="card-header">'+
                            '<div class="card-tools">'+
                                // '<button type="button" class="btn btn-tool" data-card-widget="collapse">'+
                                //     '<i class="fas fa-minus"></i>'+
                                // '</button>'+
                                '<button type="button" class="btn btn-tool removeallowancecard" data-card-widget="remove">'+
                                    '<i class="fas fa-times"></i>'+
                                '</button>'+
                            '</div>'+
                        '</div>'+
                        '<div class="card-body">'+
                        
                            '<small><strong>Description</strong></small>'+
                            '<input type="text" name="description[]" class="form-control form-control-sm mb-2" placeholder="Description" required/>'+
                        
                            '<small><strong>Total Amount</strong></small>'+
                            '<input type="number" name="amount[]" class="form-control form-control-sm mb-2" placeholder="Total Amount" required/>'+

                            '<small><strong>Term</strong></small>'+
                            '<input type="number" name="term[]" class="form-control form-control-sm mb-2" placeholder="No. of months" required/>'+

                            // '<small><strong>Payable for (no. of months)</strong></small>'+
                            // '<input type="number" name="term[]" class="form-control form-control-sm" placeholder="No. of months" required/>'+
                            // '<small><strong>Select deduction type</strong></small>'+
                            // '<div class="deductiondetailcontainer"></div>'+
                            // '<small><strong>Enter Amount</strong></small>'+
                            // '<input type="number" name="amount[]" class="form-control form-control-sm" placeholder="Amount" required/>'+
                        '</div>'+
                    '</div>'
                );
            }
            else if(addallowancedetailrow > 0){
                $('.addallowancecontainer').prepend(
                    '<div class="card">'+
                        '<div class="card-header">'+
                            '<div class="card-tools">'+
                                '<button type="button" class="btn btn-tool removeallowancecard" data-card-widget="remove">'+
                                    '<i class="fas fa-times"></i>'+
                                '</button>'+
                            '</div>'+
                        '</div>'+
                        '<div class="card-body">'+
                        
                        '<small><strong>Description</strong></small>'+
                        '<input type="text" name="description[]" class="form-control form-control-sm mb-2" placeholder="Description" required/>'+
                    
                        '<small><strong>Total Amount</strong></small>'+
                        '<input type="number" name="amount[]" class="form-control form-control-sm mb-2" placeholder="Total Amount" required/>'+

                        '<small><strong>Term</strong></small>'+
                        '<input type="number" name="term[]" class="form-control form-control-sm mb-2" placeholder="No. of months" required/>'+

                        // '<small><strong>Payable for (no. of months)</strong></small>'+
                        // '<input type="number" name="term[]" class="form-control form-control-sm" placeholder="No. of months" required/>'+
                            // '<small><strong>Select deduction type</strong></small>'+
                            // '<small><strong>Enter Amount</strong></small>'+
                            // '<input type="number" name="amount[]" class="form-control form-control-sm" placeholder="Amount" required/>'+
                        '</div>'+
                    '</div>'
                );
            }
            addallowancedetailrow+=1;
        });
        $(document).on('click','.removeallowancecard', function(){
            addallowancedetailrow-=1;
            if(addallowancedetailrow == 0){
                $('.addallowancecontainer').empty();
            }
        })
        // var addcashadvancedetailrow = 0;
        // $(document).on('click','#addcashadvance', function(){
        //     if(addcashadvancedetailrow == 0){
        //         $('.addcashadvancecontainer').append(
        //             '<div class="card">'+
        //                 '<button type="submit" class="btn btn-block btn-success savedeductionbutton">Save</button>'+
        //             '</div>'
        //         );
        //         $('.addcashadvancecontainer').prepend(
        //             '<div class="card">'+
        //                 '<div class="card-header">'+
        //                     '<div class="card-tools">'+
        //                         // '<button type="button" class="btn btn-tool" data-card-widget="collapse">'+
        //                         //     '<i class="fas fa-minus"></i>'+
        //                         // '</button>'+
        //                         '<button type="button" class="btn btn-tool removecashadvancecard" data-card-widget="remove">'+
        //                             '<i class="fas fa-times"></i>'+
        //                         '</button>'+
        //                     '</div>'+
        //                 '</div>'+
        //                 '<div class="card-body">'+
        //                     '<small><strong>Enter Amount</strong></small>'+
        //                     '<input type="number" name="amount[]" class="form-control form-control-sm mb-2" placeholder="Amount" required/>'+
        //                     '<small><strong>Select deduction basis type</strong></small>'+
        //                     '<select class="form-control form-control-sm mb-2" name="basistypeids[]" required>'+
        //                         @foreach($salarybasistypes as $salarybasistype)
        //                             '<option value="{{$salarybasistype->id}}">{{$salarybasistype->type}}</option>'+
        //                         @endforeach
        //                     '</select>'+
        //                 '</div>'+
        //             '</div>'
        //         );
        //     }
        //     else if(addcashadvancedetailrow > 0){
        //         $('.addcashadvancecontainer').prepend(
        //             '<div class="card">'+
        //                 '<div class="card-header">'+
        //                     '<div class="card-tools">'+
        //                         '<button type="button" class="btn btn-tool removecashadvancecard" data-card-widget="remove">'+
        //                             '<i class="fas fa-times"></i>'+
        //                         '</button>'+
        //                     '</div>'+
        //                 '</div>'+
        //                 '<div class="card-body">'+
        //                     '<small><strong>Enter Amount</strong></small>'+
        //                     '<input type="number" name="amount[]" class="form-control form-control-sm mb-2" placeholder="Amount" required/>'+
        //                     '<small><strong>Select deduction basis type</strong></small>'+
        //                     '<select class="form-control form-control-sm mb-2" name="basistypeids[]" required>'+
        //                         @foreach($salarybasistypes as $salarybasistype)
        //                             '<option value="{{$salarybasistype->id}}">{{$salarybasistype->type}}</option>'+
        //                         @endforeach
        //                     '</select>'+
        //                 '</div>'+
        //             '</div>'
        //         );
        //     }
        //     addcashadvancedetailrow+=1;
        // });
        // $(document).on('click','.removecashadvancecard', function(){
        //     addcashadvancedetailrow-=1;
        //     if(addcashadvancedetailrow == 0){
        //         $('.addcashadvancecontainer').empty();
        //     }
        // })
        
   });
   $(document).on('click', '.rfidedit', function(){
        $('input[name=rfid]').attr('disabled', false);
        $(this).css('backgroundColor','green');
        // $(this).closest('i').removeClass('fa-edit');
        $(this).find('i').remove();
        $(this).append('<i class="fa fa-upload text-white"></i>');
        $(this).addClass('updaterfid');
   })
  </script>
@endsection