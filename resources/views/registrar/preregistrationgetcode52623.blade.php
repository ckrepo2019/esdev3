@extends('layouts.app')

@section('headerscript')

    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    
@endsection
@php
    $schoolinfo = DB::table('schoolinfo')->first();
@endphp
@section('content')

<section class="fxt-template-layout20 m-0">
    <div class="container" id="thiscontainer">
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6">
				<div class="fxt-bg-color pt-0" style="border-radius: 20px;">
					<div class="fxt-content mt-0 " style="padding: 40px!important;">
						<div class="fxt-form">
							<div class="row">
								<h2 class="w-100  text-left">{{$fullname}}</h2>
								<h1 class="w-100 text-left" style="font-size:40px">{{$code[0]->queing_code}}</h1>
							</div>  
							<hr>
							<div class="row">
								<div class="col-md-12">
									<div class="callout callout-danger h6">
										<p>Please login to your portal to complete pre-enrollment process.</p>
										<p><a href="/coderecovery" target="_blank">Click here</a> to get your username and password!</p>
									</div>
								</div>
							</div>
							<hr class="mt-0">
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
							</ul>
							<a href="/preregv2" class="btn btn-block btn-success">New Pre-registration</a>
							<a href="/coderecovery" class="btn btn-block btn-primary">Get Credentials</a>
							<a href="{{$schoolinfo->websitelink}}" class="btn btn-block btn-secondary">Website</a>
						</div>
						
					</div>
				</div>
			</div>
			<div class="col-md-3"></div>
		</div>

          
    </div>
</section>

{{-- <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card mb-2">
                <div class="card-header" style="min-height:150px;">
                    <div class="row">
                        <h2 class="w-100">{{$fullname}}</h2>
                        <h1 class="w-100 float-left" style="font-size:40px">{{$code[0]->queing_code}}</h1>
                    </div>  
                        
                </div>
                <div class="card-body ">
                
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout callout-danger h6">
                                <p>Please login to your portal to complete pre-enrollment process.</p>
                                <p><a href="/coderecovery" target="_blank">Click here</a> to get your username and password!</p>
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
                    </ul>
                    <a href="/preregv2" class="btn btn-block btn-success">New Pregistration</a>
					<a href="/coderecovery" class="btn btn-block btn-primary">Get Credentials</a>
					<a href="{{$schoolinfo->websitelink}}" class="btn btn-block btn-secondary">Website</a>
					
                </div>
              
            </div>
        </div>
        <div class="col-md-3"></div>
</div> --}}
@endsection


                        
            

