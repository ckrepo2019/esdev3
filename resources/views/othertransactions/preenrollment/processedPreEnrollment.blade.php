

@extends('layouts.app')

@section('headerscript')

 
    <link href="{{asset('assets/css/main.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets\css\login.css')}}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('js/app.js') }}" ></script>
@endsection

@section('content')
      <section class="content ">
            <div class="row justify-content-center">
                  <div class="col-md-6">
                        <div class="card">
                              @if($preEnrolledStatus == 1)
                                    <div class="card-header">
                                          Pre-enrollment Success!
                                    </div>
                                    <div class="card-body">
                                          <ul style="font-size:15px">
                                                <li>Visit <a href="/prereg/inquiry/form">{{Request::root()}}/prereginquiry</a> to view your pre-enrollment and payment status</li>
                                                <li>Upload payment receipt at <a href="/payment/online">{{Request::root()}}/payment/online</a></li>
                                          </ul>
                                    </div>
                              @else
                                    <div class="card-header">
                                          Pre-enrollment Failed
                                    </div>
                                    <div class="card-body">
                                          <div class="callout callout-danger">
                                                <h5>{{$message}}</h5>
                                                @if($preEnrolledStatus == 2)
                                                      <ul style="font-size:15px">
                                                            <li>Visit <a href="/prereg/inquiry/form">{{Request::root()}}/prereginquiry</a> to view your pre-enrollment and payment status</li>
                                                            <li>Upload payment receipt at <a href="/payment/online">{{Request::root()}}/payment/online</a></li>
                                                      </ul>
                                                @else

                                                
                                                @endif
                                          
                                          
                                          </div>

                              @endif
                             
                        </div>
                  </div>
              
               
            </div>
      </section>
    
@endsection
      
            