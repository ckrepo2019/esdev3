@extends('layouts.app')

@section('headerscript')

    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    
@endsection
@php
    $schoolinfo = DB::table('schoolinfo')->first();
@endphp
@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card mb-2">
                <div class="card-header" style="min-height:150px;">
                    <div class="row">
                        <h2 class="w-100">{{$fullname}}</h2>
                        <h1 class="w-100 text-white float-left" style="font-size:40px">{{$code[0]->queing_code}}</h1>
                    </div>  
                        
                </div>
                <div class="card-body ">
                
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout callout-danger h6">
                                <p>Please login to your portal to complete pre-enrollment process.</p>
                               
                                {{-- <p><a href="/coderecovery" target="_blank">Click here</a> to get your username and password!</p> --}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout callout-info h6">
                                <h5 class="mb-3">Login Credentials</h5>
                                <div class="row">
                                    <div class="col-md-2">
                                        <b>Username: </b>
                                    </div>
                                    <div class="col-md-8">
                                        {{$user->email}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <b>Password: </b>
                                    </div>
                                    <div class="col-md-8">
                                        {{$user->passwordstr}}
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-danger"><i>Note: Make sure to save this credentials!<i></p>
                                <p ><a href="/login" target="_blank">Click here</a> to login!</p>
                                {{-- <p><a href="/coderecovery" target="_blank">Click here</a> to get your username and password!</p> --}}
                            </div>
                        </div>
                    </div>
                   
                    <hr>
                    <div class="row">
                        <h4 class="underlined">Payment Options:</h4>
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
                                    @elseif($item->paymenttype == 4)
                                        <ul class="mt-2" >
                                            <li>Mobile Number: {{$item->mobileNum}}</li>
                                        </ul>
                                    @else
                                        <ul class="mt-2">
                                            <li>Account Name: {{$item->accountName}}</li>
                                            <li>Account Number:  {{$item->mobileNum}}</li>
                                        </ul>
                                    @endif
                                </li>
                        @endforeach
                        <li>
                            @if(isset($schoolinfo->abbreviation))
                                @if($schoolinfo->abbreviation == 'sait')
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>For students paying to any MLHUILLIER branches nationwide they have to fill the Bills Payment form , pls read below</p>
                                    </div>
                                    <div class="col-md-12">
                                        <ul>
                                            <li>company name: SAN AGUSTIN INSTITUTE OF TECHNOLOGY</li>
                                            <li>account name: NAME OF STUDENT ex. {{$fullname}}</li>
                                            <li>account number: STUDENT ID NUMBER ex. {{$code[0]->queing_code}}</li>
                                            <li>Amount: </li>
                                            <li>contact #: </li>
                                            <li>other details: TUITION FEE, ENROLLMENT FEE ETC.</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-12">
                                        <img  src="{{asset('sait_mlhuillier.png')}}" width="100%">
                                    </div>
                                    <div class="col-md-12">
                                        <img  src="{{asset('sait_mlhuillier_2.png')}}" width="100%">
                                    </div>
                                </div>
                                @endif
                            @endif
                        </li>
                    </ul>
                    <a href="/preregv2" class="btn btn-block btn-success">REGISTER NEW STUDENT</a>
                    <a href="/login" class="btn btn-block btn-primary">LOGIN TO PORTAL</a>
                    @if(isset($schoolinfo->websitelink))
                        <a href="{{$schoolinfo->websitelink}}" class="btn btn-block btn-warning">VISIT SCHOOL WEBSITE</a>
                    @endif
                </div>
              
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
@endsection


                        
            

