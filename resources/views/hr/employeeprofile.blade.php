

@extends('hr.layouts.app')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .credentialimg{
        border-radius: 0% !important;
    }
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

@media screen and (max-width : 1920px){
  .div-only-mobile{
  visibility:hidden;
  }
}
@media screen and (max-width : 906px){
 .desk{
  visibility:hidden;
  }
 .div-only-mobile{
  visibility:visible;
  }
  .credentialsviewbutton{
    width: 100%; display:block;
  }
  .credentialsdeletebutton{
    width: 100%; display:block;
  }
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
                    <li class="breadcrumb-item"><a href="/employeeslist/dashboard">Employees</a></li>
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
                    <center>
                        <a href="#" class="edit-pic-icon" data-toggle="modal" data-target="#edit_profile_pic" style="color: black !important">
                            <i class="fas fa-edit" style="color: black !important"></i> Change profile picture
                        </a>
                    </center>
                    <br>
                </div>
                <div class="col-md-8 p-3 text-center text-uppercase">
                    <h1 class="text-info text-center">{{$profile->firstname}} {{$profile->middlename}} {{$profile->lastname}} {{$profile->suffix}}</h1>
                    <h3>{{$profile->utype}}</h3>
                    <div class="row">
                        <div class="col-md-6 col-6 text-right">
                            
                            Employment Status : 
                        </div>
                        <div class="col-md-6 col-6">
                            @if($profile->employmentstatus==1)
                                <span class="right badge badge-success">Casual</span>
                            @elseif($profile->employmentstatus==2)
                                <span class="right badge badge-success">Probationary</span>
                            @elseif($profile->employmentstatus==3)
                                <span class="right badge badge-success">Regular</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-6 text-right">
                            
                            Employee ID : 
                        </div>
                        <div class="col-md-6 col-6">
                            {{$profile->tid}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-6 text-right">
                            
                            License NO : 
                        </div>
                        <div class="col-md-6 col-6">
                            {{$profile->licno}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-6 text-right">
                            
                            Date Hired : 
                        </div>
                        <div class="col-md-6 col-6">
                            @if(count($employee_info)==0)
                            &nbsp;
                            @else
                                {{$employee_info[0]->datehiredstring}}
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-6 text-right">
                            
                            Status : 
                        </div>
                        <div class="col-md-6 col-6">
                            @if($profile->isactive==1)
                                <span class="right badge badge-success">Active</span>
                            @else
                                <span class="right badge badge-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-6 text-right">
                            
                            RFID : 
                        </div>
                        <div class="col-md-6 col-6">
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
        <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
            @if(session()->has('linkid'))
                @if(session()->get('linkid') == 'custom-content-above-home')
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
                @if(session()->get('linkid') == 'custom-content-above-dtr')
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-content-above-dtr-tab" data-toggle="pill" href="#custom-content-above-dtr" role="tab" aria-controls="custom-content-above-dtr" aria-selected="false">Credentials</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-above-dtr-tab" data-toggle="pill" href="#custom-content-above-dtr" role="tab" aria-controls="custom-content-above-dtr" aria-selected="false">DTR</a>
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
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-above-dtr-tab" data-toggle="pill" href="#custom-content-above-dtr" role="tab" aria-controls="custom-content-above-dtr" aria-selected="false">DTR</a>
                </li>
            @endif
        </ul>
        {{-- <div class="tab-content" id="custom-content-above-tabContent">
            @include('hr.employeeprofile.employeepersonalinfo')
            @include('hr.employeeprofile.employeebasicsalaryinfo')
            @include('hr.employeeprofile.employeedeductionsinfo')
            @include('hr.employeeprofile.employeeallowanceinfo')
            @include('hr.employeeprofile.employeecredentialsinfo')
            @include('hr.employeeprofile.employeedailytimerecord')
        </div> --}}
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
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script type="text/javascript">

    var $ = jQuery;

    $(document).ready(function(){
       $('body').addClass('sidebar-collapse')

        // ------------------------------------------------------------------------------------ CHANGE PROFILE PICTURE
        $.ajaxSetup({
        
            headers: {
            
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            
            }
        
        });
        
        
        $uploadCrop = $('#upload-demo').croppie({
        
            enableExif: true,
        
            viewport: {
        
                width: 304,
        
                height: 289,
        
                // type: 'circle'
        
            },
        
            boundary: {
        
                width: 304,
        
                height: 289
        
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
                        // console.log(data)
                        window.location.reload();
                        
                    }
        
                });
        
            });
        
        });
        
        // ------------------------------------------------------------------------------------ CUSTOM TIMESCHED
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

    });



        // ------------------------------------------------------------------------------------ INPUT MASKS
    $(document).ready(function(){
        
        $("#contactnum").inputmask({mask: "9999-999-9999"});
        $("#emergencycontactnumber").inputmask({mask: "9999-999-9999"});
        $(".familycontactnum").inputmask({mask: "9999-999-9999"});

    });

    
        // ------------------------------------------------------------------------------------ DEPARTMENT & DESIGNATIONS
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

    
        // ------------------------------------------------------------------------------------ ACTIVE TAB
   $(document).ready(function () {
        $('#custom-content-above-tab a[href="#{{ old('linkid') }}"]').tab('show')
    });

    
   $(document).ready(function() {
        // ------------------------------------------------------------------------------------ DATEPICKERS & ALERTS
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

        
        // ------------------------------------------------------------------------------------ ACCOUNTS

        var clickedaccountrows = 1;

        $(document).on('click','.addrowaccountsbutton', function(){

            $('.addrowaccounts')
            .prepend(
                '<div class="row">'+
                    '<div class="col-md-5">'+
                        '<label>Description <span class="text-danger">*</span></label>'+
                        '<input type="text" name="newaccountdescription[]" class="form-control form-control-sm" required/>'+
                    '</div>'+
                    '<div class="col-md-5">'+
                        '<label>Account # <span class="text-danger">*</span></label>'+
                        '<input type="text" name="newaccountnumber[]" class="form-control form-control-sm" required/>'+
                    '</div>'+
                    '<div class="col-md-2 text-left">'+
                        '<label>&nbsp;</label>'+
                        '<br>'+
                        '<button type="button" class="btn btn-danger removeaddaccountrow"><i class="fa fa-times"></i></button>'+
                    '</div>'+
                    '<hr class="col-md-12"/>'+
                '</div>'
            )

            clickedaccountrows+=1;

        });

        $(document).on('click', '.removeaddaccountrow', function(){
            clickedaccountrows-=1;
            if(clickedaccountrows == 0){
                $('#edit_accounts').modal('hide');
            }
            $(this).closest('.row').remove();
        })

        $('.deleteaccount').click(function() {
            var accountid       = $(this).closest('tr').attr('id');
            var accountdesc     = $(this).attr('accountdescription');
            var accountnum      = $(this).attr('accountnumber');
            
            Swal.fire({
                title: 'Are you sure you want to delete the selected account info?',
                // text: "You won't be able to revert this!",
                html:
                    "Account Description: <strong>" + accountdesc + '</strong>'+
                    '<br>'+ 
                    "Account #: <strong>" + accountnum + '</strong>'+
                    '<br>'+
                    "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/employeeinfo/deleteaccount',
                        type:"GET",
                        dataType:"json",
                        data:{
                            accountid: accountid,
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            Swal.fire({
                                title: 'Deleted!',
                                text: "The selected account has been deleted.",
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK!',
                                allowOutsideClick: false
                            }).then((confirm) => {
                                if (confirm.value) {
                                    window.location.reload();
                                }
                            })
                        }
                    })
                }
            })
        });

        
        // ===========================================================================
            // PROFILE 
        // ===========================================================================
        // ------------------------------------------------------------------------------------ FAMILY INFORMATION
        
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
        

        $('.deletefamilymember').click(function() {
            var familymemberid      = $(this).attr('familyid');
            var familymembername    = $(this).attr('familymembername');
            var employeeid          = $(this).attr('id');
            
            Swal.fire({
                title: 'Are you sure you want to delete this family member?',
                // text: "You won't be able to revert this!",
                html:
                    "Family member: <strong>" + familymembername + '</strong>'+
                    '<br>'+
                    "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/employeefamily/delete',
                        type:"GET",
                        dataType:"json",
                        data:{
                            familymemberid: familymemberid,
                            employeeid: employeeid
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            Swal.fire({
                                title: 'Deleted!',
                                text: "The selected account has been deleted.",
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK!',
                                allowOutsideClick: false
                            }).then((confirm) => {
                                if (confirm.value) {
                                    window.location.reload();
                                }
                            })
                        }
                    })
                }
            })
        });
        
        // ------------------------------------------------------------------------------------ EDUCATIONAL BACKGROUND
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

        // ------------------------------------------------------------------------------------ WORK EXPERIENCE
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
        
        $('.deleteexperience').click(function() {
            var experienceid        = $(this).attr('experienceid');
            var experiencecompany   = $(this).attr('experiencecompany');
            var experienceposition  = $(this).attr('experienceposition');
            var employeeid          = '{{$profile->id}}';


            Swal.fire({
                title: 'Are you sure you want to delete this work experience?',
                // text: "You won't be able to revert this!",
                html:
                    "Company: <strong>" + experiencecompany + '</strong>'+
                    '<br>'+
                    "Position: <strong>" + experienceposition + '</strong>'+
                    '<br>'+
                    "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/employeeexperience/delete',
                        type:"GET",
                        dataType:"json",
                        data:{
                            experienceid: experienceid,
                            employeeid: employeeid
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            Swal.fire({
                                title: 'Deleted!',
                                text: "The selected experience information has been deleted.",
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK!',
                                allowOutsideClick: false
                            }).then((confirm) => {
                                if (confirm.value) {
                                    window.location.reload();
                                }
                            })
                        }
                    })
                }
            })
        });
        // ===========================================================================
            // Salary Details 
        // ===========================================================================
        // ------------------------------------------------------------------------------------ BASIC SALARY
        @if(count($employee_basicsalaryinfo) == 0)
            var salaryamount = 0;
        @else
            var salaryamount = '{{$employee_basicsalaryinfo[0]->amount}}';
        @endif
        parseFloat(salaryamount);
        console.log(salaryamount)
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
        $('#workonsaturdays').on('click', function(){
                $('.basicsalarybutton').show();
        })
        $('#workonsundays').on('click', function(){
                $('.basicsalarybutton').show();
        })
        
        var clickeddays = 0;
        $('input[name="daysrender[]"]').each(function(){
            if($(this).prop('checked') == true){
            clickeddays+=1;
            }
        });
        
        $('.additionalworkondays').hide();
        if($('select[name="salarybasistype"]').val() == '4'){
            $('.additionalworkondays').show();
        }
        $(document).on('change','select[name="salarybasistype"]', function(){
                $('#generalsalaryamouncontainer').empty();
                $('#noofhours').empty();
                $('#othersalarysettingcontainer').empty();
                $('.additionalworkondays').hide();
            if($(this).val() == '7'){
                $('#othersalarysettingcontainer').append(
                    '<div class="col-md-4">'+
                    '<label class="col-form-label">No. of months</label>'+
                    '<input type="number" name="noofmonthscontractual" class="form-control" placeholder="No. of months" required/>'+
                    '</div>'
                );
            }
            else if($(this).val() == '4'){
                $('.additionalworkondays').show()
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
        // console.log($(this).val())
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
        // console.log(clickeddays)
        $('input.daysrender').val($('input[name="hoursperweek"]').val()/clickeddays)

    })
        // ===========================================================================
            // Deduction Standard Details 
        // ===========================================================================

        // ------------------------------------------------------------------------------------ DEDUCTIONS
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
        
        // $('.editdeductiondetail').on('click', function(){
        //     $()
        // })
        var adddeductiondetailrow = 0;
        $(document).on('click','#adddeduction', function(){
            if(adddeductiondetailrow == 0){
                $('.adddeductioncontainer').append(
                    '<div class="card">'+
                        '<button type="button" class="btn btn-block btn-success savedeductionbutton">Save</button>'+
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
                            '<input type="number" name="term[]" class="form-control form-control-sm mb-2" placeholder="No. of months" required/>'+
                        '<small><strong>Start date</strong></small>'+
                        '<input type="date" name="startdates[]" class="form-control form-control-sm" required/>'+
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

                        '<small><strong>Start date</strong></small>'+
                        '<input type="date" name="startdates[]" class="form-control form-control-sm" required/>'+
                            // '<small><strong>Select deduction type</strong></small>'+
                            // '<small><strong>Enter Amount</strong></small>'+
                            // '<input type="number" name="amount[]" class="form-control form-control-sm" placeholder="Amount" required/>'+
                        '</div>'+
                    '</div>'
                );
            }
                adddeductiondetailrow+=1;
        });
        $(document).on('click','.removedeductioncard', function(){
            adddeductiondetailrow-=1;
            if(adddeductiondetailrow == 0){
                $('.adddeductioncontainer').empty();
            }
        })
        $(document).on('click','.savedeductionbutton', function(){
            $('form[name=otherdeductionform]').submit();
        })
        
        $('.updateotherdeductionstatus').click(function() {
            var otherdeductionid            = $(this).attr('otherdeductionid');
            var otherdeductiondescription   = $(this).attr('otherdeductiondescription');
            var employeeid                  = '{{$profile->id}}';

            if($(this).attr('currentstatus') == 1){
                status = 'deactivate';
                newstatus = '0';
            }else{
                status = 'activate';
                newstatus = '1';
            }
            Swal.fire({
                title: 'Are you sure you want to '+status+' this deduction?',
                // text: "You won't be able to revert this!",
                html:
                    "Description: <strong>" + otherdeductiondescription + '</strong>'+
                    '<br>'+
                    "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, '+status+' it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/employeeotherdeductionsinfostatusupdate',
                        type:"GET",
                        dataType:"json",
                        data:{
                            otherdeductionid: otherdeductionid,
                            employeeid: employeeid,
                            newstatus: newstatus
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            Swal.fire({
                                title: status+'d!',
                                text: "The selected deduction has been "+status+"ed.",
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK!',
                                allowOutsideClick: false
                            }).then((confirm) => {
                                if (confirm.value) {
                                    window.location.reload();
                                }
                            })
                        }
                    })
                }
            })
        });
        $('.updatecontributionsbuttonstandard').hide()
        $(document).on('input','input[name="ersamounts[]"]', function(){

            $('.updatecontributionsbuttonstandard').show();
        })
        $(document).on('input','input[name="eesamounts[]"]', function(){

            $('.updatecontributionsbuttonstandard').show();
        })
        $(document).on('change','input[class="contributionsradiobox"]', function(){

            $('.updatecontributionsbuttonstandard').show();
        })
        if($('input[name="ersamounts[]"]')){

        }
        if($('input[name="eesamounts[]"]')){
            
        }
        if($('input[class="contributionsradiobox"]')){
            
        }
        


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
        $('.updatecontributionsbutton').hide()
        $(document).on('input','input[name="amounts[]"]', function(){

            $('.updatecontributionsbutton').show();
        })
        $(document).on('change','input[class="allowanceradiobox"]', function(){

            $('.updatecontributionsbutton').show();
        })
        
        
   });

   
        // ------------------------------------------------------------------------------------ RFID
   $(document).on('click', '.rfidedit', function(){
        $('input[name=rfid]').attr('disabled', false);
        $(this).css('backgroundColor','green');
        // $(this).closest('i').removeClass('fa-edit');
        $(this).find('i').remove();
        $(this).append('<i class="fa fa-upload text-white"></i>');
        $(this).addClass('updaterfid');
   })

//    ----------------------------------------------------------------------------------------- CREDENTIALS

    $(document).on('click','.credentialdelete', function() {
        var employeeid  = '{{$profile->id}}';
        var credentialid  = $(this).attr('credentialid');
        var description  = $(this).attr('description');
        Swal.fire({
            title: 'Are you sure you want to delete the '+description+'?',
            // text: "You won't be able to revert this!",
            html:
                "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/employeecredentialdelete',
                    type:"GET",
                    dataType:"json",
                    data:{
                        credentialid: credentialid,
                        description: description,
                        employeeid: employeeid
                    },
                    complete: function(){
                        Swal.fire({
                            title: 'Deleted!',
                            text: "The "+description+" has been deleted.",
                            type: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!',
                            allowOutsideClick: false
                        }).then((confirm) => {
                            if (confirm.value) {
                                window.location.reload();
                            }
                        })
                    }
                })
            }
        })
    });
        // ------------------------------------------------------------------------------------ DAILY TIME RECORD
   
   $(function () {
        $('#dtrdaterange').daterangepicker({
            locale: {
                format: 'MM-DD-YYYY'
            }
        });
   });

   $(document).on('change','input[name=dtrchangeperiod]', function(){

        $.ajax({
            url: '/employeedtr/{{Crypt::encrypt("changeperiod")}}',
            type:   "GET",
            dataType:"json",
            data:{
                employeeid  :'{{$profile->id}}',
                period      : $(this).val()
            },
            headers: {"Authorization": localStorage.getItem('token')},
            success:function(data) {
                var countrow = 1;
                $('#timerecord').empty();
                $.each(data, function(key, value){
                    $('#timerecord').append(
                        '<tr>'+
                            '<td id="dtr'+countrow+'">'+
                                value.date +
                            '</td>'+
                            '<td class="text-center">'+value.timerecord.amin+'</td>'+
                            '<td class="text-center">'+value.timerecord.amout+'</td>'+
                            '<td class="text-center">'+value.timerecord.pmin+'</td>'+
                            '<td class="text-center">'+value.timerecord.pmout+'</td>'+
                            '<td class="text-center">'+value.undertime+'</td>'+
                            '<td class="text-center">'+value.hoursrendered+'</td>'+
                        '</tr>'
                    )
                    if(value.day.toLowerCase() == 'sunday' || value.day.toLowerCase() == 'saturday'){
                        $('#dtr'+countrow).append(
                            ' <span class="right badge badge-secondary">'+value.day+'</span>'
                        )
                    }else{
                        $('#dtr'+countrow).append(
                            ' <span class="right badge badge-default">'+value.day+'</span>'
                        )
                    }
                    $('#dtr'+countrow).append(
                        ' <span class="right badge badge-danger float-right repunchattendance" tdate="'+value.date+'"><i class="fa fa-sync"></i></span>'
                    )

                    countrow+=1;
                });
            }
        })
   })

    $(document).on('click','.repunchattendance', function() {
        var tdate        = $(this).attr('tdate');
        var employeeid  = '{{$profile->id}}';
        
        Swal.fire({
            title: 'Are you sure you want to delete the record from this date?',
            // text: "You won't be able to revert this!",
            html:
                "Date: <strong>" + tdate + '</strong>'+
                '<br>'+
                "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/employeedtr/{{Crypt::encrypt("delete")}}',
                    type:"GET",
                    dataType:"json",
                    data:{
                        tdate: tdate,
                        employeeid: employeeid
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    complete: function(){
                        Swal.fire({
                            title: 'Deleted!',
                            text: "The record from "+tdate+" has been deleted.",
                            type: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!',
                            allowOutsideClick: false
                        }).then((confirm) => {
                            if (confirm.value) {
                                window.location.reload();
                            }
                        })
                    }
                })
            }
        })
    });
  </script>
@endsection