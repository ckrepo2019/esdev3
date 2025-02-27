

@extends('hr.layouts.app')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Toastr -->
  <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
@include('hr.employeeprofile.profilecss')
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
                                        if($profileinfo->gender == null){
                                            $avatar = 'assets/images/avatars/unknown.png';
                                        }
                                        else{
                                            if(strtoupper($profileinfo->gender) == 'FEMALE'){
                                                $avatar = 'avatar/T(F) '.$number.'.png';
                                            }
                                            else{
                                                $avatar = 'avatar/T(M) '.$number.'.png';
                                            }
                                        }
                                    @endphp
                                    <div id="upload-demo-i" class="bg-white " style="width:200px;height:200px;">
                                            <img class="elevation-2" src="{{asset($profileinfo->picurl)}}" id="profilepic" style="width:200px;height:200px;"  onerror="this.onerror = null, this.src='{{asset($avatar)}}'" alt="User Avatar">
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
                    <h1 class="text-info text-center">{{$profileinfo->title}}. {{$profileinfo->firstname}} {{$profileinfo->middlename}} {{$profileinfo->lastname}} {{$profileinfo->suffix}}</h1>
                    <h3>{{$profileinfo->utype}}</h3>
                    <div class="row">
                        <div class="col-md-6 col-6 text-right">
                            
                            Employment Status : 
                        </div>
                        <div class="col-md-6 col-6">
                            @if($profileinfo->employmentstatus==1)
                                <span class="right badge badge-success">Casual</span>
                            @elseif($profileinfo->employmentstatus==2)
                                <span class="right badge badge-success">Provisionary</span>
                            @elseif($profileinfo->employmentstatus==3)
                                <span class="right badge badge-success">Regular</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-6 text-right">
                            
                            Employee ID : 
                        </div>
                        <div class="col-md-6 col-6">
                            {{$profileinfo->tid}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-6 text-right">
                            
                            License NO : 
                        </div>
                        <div class="col-md-6 col-6">
                            {{$profileinfo->licno}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-6 text-right">
                            
                            Date Hired : 
                        </div>
                        <div class="col-md-6 col-6">
                            {{$profileinfo->datehiredstring}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-6 text-right">
                            
                            Status : 
                        </div>
                        <div class="col-md-6 col-6">
                            @if($profileinfo->isactive==1)
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
                        <div class="col-md-6 col-6" id="rfidcontainer">
                            <input type="text"  value="{{$profileinfo->rfid}}" name="rfid" id="setrfid" class="form-control form-control-sm col-10" style="display: inline;" disabled/><a class="edit-icon col-2 rfidedit"><i class="fas fa-edit" style="color: black !important"></i></a>
                                {{-- @if(session()->has('rfidexists'))
                                    <span class="text-danger rfidexists">{{session()->get('rfidexists')}}</span>
                                @endif --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@php
    $refid = DB::table('usertype')
        ->where('id', auth()->user()->type)
        ->first()->refid;
@endphp
<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="custom-content-above-profile-tab" data-toggle="pill" href="#custom-content-above-profile" role="tab" aria-controls="custom-content-above-profile" aria-selected="true">Profile</a>
            </li>
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
                @if($refid == 26)
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-above-salary-tab" data-toggle="pill" href="#custom-content-above-profile" role="tab" aria-controls="custom-content-above-salary" aria-selected="false">Basic Salary Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-above-contributions-tab" data-toggle="pill" href="#custom-content-above-contributions" role="tab" aria-controls="custom-content-above-contributions" aria-selected="false">Deductions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-above-allowance-tab" data-toggle="pill" href="#custom-content-above-allowance" role="tab" aria-controls="custom-content-above-allowance" aria-selected="false">Allowance</a>
                    </li>
                @endif
            @else
            <li class="nav-item">
                <a class="nav-link" id="custom-content-above-salary-tab" data-toggle="pill" href="#custom-content-above-profile" role="tab" aria-controls="custom-content-above-salary" aria-selected="false">Basic Salary Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-content-above-contributions-tab" data-toggle="pill" href="#custom-content-above-contributions" role="tab" aria-controls="custom-content-above-contributions" aria-selected="false">Deductions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-content-above-allowance-tab" data-toggle="pill" href="#custom-content-above-allowance" role="tab" aria-controls="custom-content-above-allowance" aria-selected="false">Allowance</a>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" id="custom-content-above-credentials-tab" data-toggle="pill" href="#custom-content-above-credentials" role="tab" aria-controls="custom-content-above-credentials" aria-selected="false">Credentials</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-content-above-dtr-tab" data-toggle="pill" href="#custom-content-above-dtr" role="tab" aria-controls="custom-content-above-dtr" aria-selected="false">DTR</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-content-above-others-tab" data-toggle="pill" href="#custom-content-above-others" role="tab" aria-controls="custom-content-above-others" aria-selected="false">Others</a>
            </li>
        </ul>
        <div class="tab-content" id="custom-content-above-tabContent">
            {{-- @include('hr.employeeprofile.basicprofile') --}}
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

<!-- Toastr -->
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Bootstrap Switch -->
<script src="{{asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
<script type="text/javascript">
    var $ = jQuery;
    $(document).ready(function(){
        $('body').addClass('sidebar-collapse')
       
        // $('#custom-content-above-profile-tab').click()
       
        const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
        });
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
                    url: "/hr/employeeprofilechangepic",
                    type: "POST",
                    data: {
                        "image"     :   resp,
                        "employeeid":   '{{$profileinfo->id}}',
                        "lastname"  :   '{{$profileinfo->lastname}}',
                        "username"  :   '{{$profileinfo->tid}}'
                        },
                    success: function (data) {
                        console.log(data)
                        window.location.reload();
                        // $('#profilepic').attr('src',data)
                    }
                });
            });        
        });
        $(document).on('click', '.rfidedit', function(){
            $(this).removeClass('rfidedit')
            $('input[name=rfid]').attr('disabled', false);
            $(this).css('backgroundColor','green');
            $(this).find('i').remove();
            $(this).append('<i class="fa fa-upload text-white"></i>');
            $(this).addClass('updaterfid');
        })
        $(document).on('click', '.updaterfid', function(){
            var thiselement = $(this);
            var newrfid = $('#setrfid').val();
            $('.rfidexists').remove()
            $.ajax({
                url: "/hr/employeeprofileupdaterfid",
                type: "get",
                data: {
                    rfid: newrfid,
                    id: '{{$profileinfo->id}}'
                },
                success: function (data) {
                    console.log(data)
                    if(data == 1)
                    {
                        thiselement.removeClass('updaterfid')
                        thiselement.addClass('rfidedit')
                        thiselement.css('backgroundColor','#ffc107');
                        $('#setrfid').attr('disabled', true);
                        // $(this).closest('i').removeClass('fa-edit');
                        thiselement.find('i').remove();
                        thiselement.append('<i class="fa fa-edit"></i>');
                    }else if(data == 0){
                        $('#rfidcontainer').append('<span class="text-danger rfidexists">RFID EXISTS</span>')
                    }else if(data == 2){
                        $('#rfidcontainer').append('<span class="text-danger rfidexists">RFID IS NOT YET REGISTERED</span>')
                    }
                }
            });
        })
        
    })
    

  </script>
  <script>
      
      $(document).on('click','#custom-content-above-profile-tab', function(){
        $.ajax({
            url: "/hr/employeeprofiletab",
            type: "get",
            data: {
                employeeid: '{{$profileinfo->id}}'
            },
            success: function (data) {
                $('#custom-content-above-tabContent').empty()
                $('#custom-content-above-tabContent').append(data)
            }
        });
      })
  </script>
  @include('hr.employeeprofile.scripts.basicprofile_js')
  <script>
      $(document).on('click','#custom-content-above-salary-tab', function(){
        $.ajax({
            url: "/hr/employeebasicsalarytab",
            type: "get",
            data: {
                employeeid: '{{$profileinfo->id}}'
            },
            success: function (data) {
                $('#custom-content-above-tabContent').empty()
                $('#custom-content-above-tabContent').append(data)
            }
        });
      })
  </script>
  <script>
      $(document).on('click','#custom-content-above-contributions-tab', function(){
        $('.adddeductioncontainer').empty();
        $.ajax({
            url: "/hr/employeedeductionstab",
            type: "get",
            data: {
                employeeid: '{{$profileinfo->id}}'
            },
            success: function (data) {
                $('#custom-content-above-tabContent').empty()
                $('#custom-content-above-tabContent').append(data)
            }
        });
      })
  </script>
  <script>
      $(document).on('click','#custom-content-above-allowance-tab', function(){
        $.ajax({
            url: "/hr/employeeallowancetab",
            type: "get",
            data: {
                employeeid: '{{$profileinfo->id}}'
            },
            success: function (data) {
                $('#custom-content-above-tabContent').empty()
                $('#custom-content-above-tabContent').append(data)
                $('#addallowancecontainer').empty();
            }
        });
      })
  </script>
  <script>
      $(document).on('click','#custom-content-above-credentials-tab', function(){
        $.ajax({
            url: "/hr/employeecredentialstab",
            type: "get",
            data: {
                employeeid: '{{$profileinfo->id}}'
            },
            success: function (data) {
                $('#custom-content-above-tabContent').empty()
                $('#custom-content-above-tabContent').append(data)
            }
        });
      })
  </script>
  <script>
      $(document).on('click','#custom-content-above-dtr-tab', function(){
        $.ajax({
            url: "/hr/employeedtrtab/index",
            type: "get",
            data: {
                employeeid: '{{$profileinfo->id}}'
            },
            success: function (data) {
                $('#custom-content-above-tabContent').empty()
                $('#custom-content-above-tabContent').append(data)
            }
        });
      })

    @if(session()->has('linkid'))
        @if( session()->get('linkid') == 'custom-content-above-basicsalary')
        $('#custom-content-above-salary-tab').click();
        @endif
    @else
        $('#custom-content-above-profile-tab').click()
    @endif
  </script>
  <script>
      $(document).on('click','#custom-content-above-others-tab', function(){
        $.ajax({
            url: "/hr/employeeotherstab/index",
            type: "get",
            data: {
                employeeid: '{{$profileinfo->id}}'
            },
            success: function (data) {
                $('#custom-content-above-tabContent').empty()
                $('#custom-content-above-tabContent').append(data)
            }
        });
      })
  </script>
@endsection