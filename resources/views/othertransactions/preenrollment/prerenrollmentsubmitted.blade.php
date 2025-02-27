{{-- <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pre-registation</title>

 
    <link href="{{asset('assets/css/gijgo.min.css')}}" rel="stylesheet" />
<link href="{{asset('assets/css/main.css')}}" rel="stylesheet">

    <script type="text/javascript" src="{{asset('assets/scripts/jquery-3.3.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
    <script src="{{asset('assets/scripts/bootstrap.min.js')}}" ></script>
    
    <script type="text/javascript" src="{{ asset('js/dataTable/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/dataTable/dataTables.fixedColumns.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('css/dataTable/jquery.dataTables.min.css')}}" type="text/css" media="all">
    <link rel="stylesheet" href="{{ asset('css/dataTable/fixedColumns.dataTables.min.css')}}" type="text/css" media="all">

    <style>
        label{
            font-size: 12px;
        }
        .chevron {
            display: inline-block;
            min-width: 150px;
            text-align: center;
            padding: 15px 0;
            margin-right: -30px;
            background: #2bbf74 ;
            -webkit-clip-path: polygon(0 0, 100% 0%, 75% 100%, 0% 100%);
            clip-path: polygon(0 0, 100% 0%, 75% 100%, 0% 100%);
        }
        .fixed-top{
            position: sticky;
            padding-top: 0px;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            /* display: none; <- Crashes Chrome on hover */
            -webkit-appearance: none;
            margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
        }

        input[type=number] {
            -moz-appearance:textfield; /* Firefox */
        }
        @media screen and (max-width: 1000px) {
            #code{
                font-size: 50px !important;
                                  
            }
            #gotIt{
                margin: 10px !important;
                                  
            }
            .fixed-top{
                position: sticky;
            }
            .chevron {
                display: inline-block;
                min-width: 150px;
                text-align: center;
                margin: 0px !important;
                margin-right: -30px;
                -webkit-clip-path: polygon(0 0, 100% 0%, 100% 100%, 0% 100%);
                clip-path: polygon(0 0, 100% 0%, 100% 100%, 0% 100%);
            }
        .next {
                display: inline-block;
                min-width: 150px;
                text-align: center;
                padding: 15px 0;
                margin: 0px !important;
                margin-right: -30px;
                -webkit-clip-path: polygon(0 0, 100% 0%, 100% 100%, 0% 100%);
                clip-path: polygon(0 0, 100% 0%, 100% 100%, 0% 100%);
            }
        }
    </style>
</head>
<body>
    <div class="app-container body-tabs-shadow" style="background-color: #f6f3e5a1">
        <div class="app-main">
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <form action="/login" method="POST" class="needs-validation" >
                        @csrf
                        <div class="app-page-title fixed-top">
                            <div class="page-title-wrapper " style="background-color:#dcabb0bf ">
                                <div class="chevron col-md-10 col-xs-10 tag-wrap" >
                                    <div class="page-title-heading " style="padding:30px">
                                        <div class="page-title-icon" style="color:black">
                                            <i class="fa fa-exclamation-circle" >
                                            </i>
                                        </div>
                                        <div class="text-white">
                                            <h1>GET YOUR QUEUEING CODE !</h1>
                                            <div class="page-title-subheading">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="main-card mb-3 card">
                                    <div class="card-header text-white" style="background: #2bbf74;">
                                            {{$fullname}}
                                    </div>
                                    <div class="card-body">
                                    <br>
                                    <span id="code" style="font-size: 100px" class="text-uppercase"><center>{{$code[0]->queing_code}}</center></span>
                                  
                                    <center><a id="gotIt" href="/login" style="font-size:30px" class="btn btn-warning btn-lg btn-block">Got it!</a></center>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
</body>
</html> --}}

@extends('layouts.app')

@section('headerscript')

    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    
@endsection

@section('content')
    <div class="modal fade" id="updatemodal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success p-1">
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="callout callout-danger h6 w-100">
                            <p>You are about to finish your pre-enrollment / <br>pre-registration. Please pay your Enrollment Fee <br>through these 				following payment options</p>
                            {{-- <p>You can check you pre-enrollment status from this link <a href="/preenrollment/fillup/form">{{Request::root()}}/preenrollment/fillup/form</a>.</li></p> --}}
                        </div>
                    </div>
                    <div class="row">
                        <h5 class="underlined">Payment Options:  <span class="h6 font-weight-normal"> <i>( Please refer to this list for you to pay for your Enrollment Fee to complete your enrollment )<i> </span> </h5>  
                    </div>
                    <ul style="list-style-type: none;" class="mt-2 mb-4">

                        @foreach(DB::table('onlinepaymentoptions')->where('deleted','0')->where('isActive','1')->get() as $item)
                            <li class="mt-3">
                                <img width="60" src="{{asset($item->picurl)}}" width="60">

                                @if($item->paymenttype == 3 || $item->paymenttype == 5)
                                    <ul class="mt-2">
                                        <li>Account Name: {{$item->accountName}}</li>
                                        <li>Account Number:  {{$item->accountNum}}</li>
                                    </ul>
                                @else
                                    <ul class="mt-2" >
                                        <li>Mobile Number: {{$item->mobileNum}}</li>
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                        <hr>
                        <li>
                            Over the Counter - School Transaction (Cashier)
                        </li>
                        <hr>
                        
                    </ul>
                    <div class="row mt-3">
                        <div class="callout callout-danger h6 w-100">
                            <p><a href="/preenrollment/fillup/form">Click here</a> to view enrollment / preenrollment status or upload proof of payment if you have paid your Enrollment Fee through Bank or Payment Centers.</p>
                        </div>
                    </div>
                    <a href="/login" class="btn-block btn btn-success">
                        Got It!
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card mb-2">
                <div class="card-header p-2" style="height:100px; vertical-align:bottom">
                    <div class="col-md-12">
                        <h4 class="m-0">You are successfully {{$status}}.</h4>
                    </div>
                </div>
                <div class="card-body ">
                  <div class="row">
                        <div class="col-md-4">
                              Student Name:
                        </div>
                        <div class="col-md-8">
                              {{$fullname}}
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-4">
                              Grade Level:
                        </div>
                        <div class="col-md-8">
                              {{$levelname}}
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-4">
                              Enrollment Status:
                        </div>
                        <div class="col-md-8">
                              Pre-enrolled
                        </div>
                  </div>
                   
                    <hr>
                    <div class="row">
                        <h5 class="underlined">Payment Options:  <span class="h6 font-weight-normal"> <i>( Please refer to this list for you to pay for your Enrollment Fee to complete your enrollment )<i> </span> </h5>  
                    </div>
                  
                    <ul style="list-style-type: none;" class="mt-2 mb-4">

                        @foreach(DB::table('onlinepaymentoptions')->where('deleted','0')->where('isActive','1')->get() as $item)
                                <li class="mt-3">
                                <img width="60" src="{{asset($item->picurl)}}" width="60">
                                    @if($item->paymenttype == 3)
                                        <ul class="mt-2">
                                                <li>Account Name: {{$item->accountName}}</li>
                                                <li>Account Number:  {{$item->accountNum}}</li>
                                        </ul>
                                    @elseif($item->paymenttype == 5)
                                        <ul class="mt-2">
                                                <li>Account Name: {{$item->accountName}}</li>
                                                <li>Account Number:  {{$item->mobileNum}}</li>
                                        </ul>
                                    @else
                                        <ul class="mt-2" >
                                                <li>Mobile Number: {{$item->mobileNum}}</li>
                                        </ul>
                                    @endif
                                </li>
                        @endforeach
                        <hr>
                        <li>
                            Over the Counter - School Transaction (Cashier)
                        </li>
                        <hr>
                        
                    </ul>
                    <div class="row mt-3">
                        <div class="callout callout-danger h6 w-100">
                            <p><a href="/preenrollment/fillup/form">Click here</a> to view enrollment / preenrollment status or upload proof of payment if you have paid your Enrollment Fee through Bank or Payment Centers.</p>
                        </div>
                    </div>
                    <button data-toggle="modal" data-target="#updatemodal" id="gotIt" href="#" class="btn btn-block btn-success">PROCEED!</button>
                </div>
              
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>

    
@endsection


                        
            

