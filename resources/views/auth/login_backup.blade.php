@extends('layouts.app')

@section('headerscript')


    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    
@endsection

@section('content')

@php
    $nowInLondonTz = Carbon\Carbon::parse('Asia/Manila');
    $time =  $nowInLondonTz->isoFormat('h:m:ss');
    $hour =  $nowInLondonTz->isoFormat('h');
    $minute =  $nowInLondonTz->isoFormat('m');
    $second =  $nowInLondonTz->isoFormat('ss');
@endphp


@php
    $schoolInfo = DB::table('schoolinfo')->first();
@endphp


    <div class="content">
        <div class="row">
            <div class="col-md-4 mt-3">
                <div class="message text-center">
                    <h1 style="font-size:45px!important"> 
                        @if(isset(DB::table('schoolinfo')->first()->tagline))
                            {{DB::table('schoolinfo')->first()->tagline}}
                        @else
                            SCHOOL TAG LINE
                        @endif
                    </h1>
                </div>
                <div class="card">
                    <div class="card-header loginheadercard" style="background-color: {{$schoolInfo->schoolcolor}}; color: #fff">{{ __('Login') }} 
                
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right"  style="font-size: 15px;" >{{ __('Username') }}</label>

                                <div class="col-md-7 pr-0"">
                                    <div class="input-group">
                                        <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        {{-- <span class="input-group-append">
                                          <button type="button" class="btn btn-primary btn-flat"><i class="fas fa-eye"></i></button>
                                        </span> --}}
                                      </div>
                                    {{-- </div> --}}
                                    {{-- <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror --}}
                                </div>
                            </div>

                            <div class="form-group row">

                                <label for="password" class="col-md-4 col-form-label text-md-right"  style="font-size: 15px;" >{{ __('Password') }}</label>
                                <div class="col-md-7 pr-0">
                                    {{-- <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror --}}
                                    <div class="input-group">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <span class="input-group-append">
                                          <button type="button" onclick=" return false;" class="btn btn-primary btn-flat" id="view_pass"><i class="fas fa-eye"></i></button>
                                        </span>
                                      </div>
                                    </div>
                                    <script>
                                        $(document).ready(function(){
                                            $(document).on('click','#view_pass',function(){
                                                if($('#password').attr('type') == 'password'){
                                                    $('#password').removeAttr('type')
                                                }else{
                                                    $('#password').attr('type','password')
                                                }
                                            })
                                        })
                                    </script>
                               

                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-10 offset-md-4">
                                    <button type="submit" class="btn submit text-white">
                                        {{ __('Login') }}
                                    </button>
                                    {{-- @if (Route::has('password.request'))
                                        <a class="btn btn-link" style="color: white" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif --}}
                                </div>
                            </div>
                        </form>
						<div class="row mt-3">
							<div class="col-md-4"></div>
							<div class="col-md-7 text-center"><a href="/coderecovery">Get Credentials</a></div>
						</div>
                    </div>
                </div>
                <div class="row col-md-12 datetime" >
                    <div class="col-md-7 col-7 navbar-brand mr-0 pl-0" style="font-size: 18px; padding-top">
                            @php
                                $date = Carbon\Carbon::parse('Asia/Manila');
                                echo $date->isoFormat('ddd MMMM DD, YYYY');
                            @endphp
                    </div>
                    <div class="col-md-5 col-5">
                        <div class="navbar-brand float-left mr-0" id="time" style="font-size: 18px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                @php
                    $adimages = DB::table('adimages')->where('isactive','1')->get()

                @endphp
               

                <div id="carouselExampleIndicators " class="carousel slide mt-1" data-ride="carousel" style="box-shadow: 2px 2px 5px #737373c9;">
                    
                    <ol class="carousel-indicators">
                        @for($x = 0; $x < count($adimages); $x++)
                            @if($x == 0)
                                <li data-target="#carouselExampleIndicators" data-slide-to="{{$x}}" class="active"></li>
                            @else
                                <li data-target="#carouselExampleIndicators" data-slide-to="{{$x}}"></li>
                            @endif
                        @endfor
                    </ol>

                    <div class="carousel-inner">

                        @foreach ($adimages as $key=>$item)

                            @if( $key == 0)

                                <div class="carousel-item active ">
                                    <img class="d-block w-100 adsimage" src="{{asset($item->picurl)}}" alt="First slide">
                                </div>

                            @else

                                <div class="carousel-item ">
                                    <img class="d-block w-100 adsimage" src="{{asset($item->picurl)}}" alt="First slide">
                                </div>

                            @endif
                       

                        @endforeach

                        {{-- <div class="carousel-item active ">
                            <img class="d-block w-100 adsimage" src="{{asset('advertisements\school building  (3).png')}}" alt="First slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100 adsimage" src="{{asset('advertisements\school building  (4).png')}}" alt="Second slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100 adsimage" src="{{asset('advertisements\library 2.png')}}" alt="Third slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100 adsimage" src="{{asset('advertisements\AVR 5.png')}}" alt="Third slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100 adsimage" src="{{asset('advertisements\CLINIC 6.png')}}" alt="Third slide">
                        </div> --}}
                    </div>
                </div>
                <div class="row mt-2">
                    
                    <div class="col-md-4 col-4 text-center">
                        <img src="{{asset('assets/images/essentiel.png')}}" alt="" style="max-height: 55px">
                       
                    </div>
                    <div class="col-md-4 col-4 text-center">
                        {{-- <img src="{{asset('assets/images/broken_shire_logo.png')}}" alt="" style="max-height: 80px"> --}}
                    </div>
                    <div class="col-md-4 col-4 text-center">
                       
                        <img src="{{asset('assets/images/CK_Logo.png')}}" alt="" style="max-height: 80px">
                    </div>
                </div>
            </div>
           
        </div>
    </div>


    {{-- <div class="row">
        <div class="col-md-4  col-12 mt-5" >
            <div class="message text-center">
                <h1 style="font-size:50px!important"> FIDES ET SERVITIUM!</h1>
            </div>
            <div class="card">
                <div class="card-header loginheadercard" style="background-color: #88b14b; color: #fff">{{ __('Login') }} 
                
                </div>
                    <div class="card-body ">
                        
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right"  style="font-size: 15px;" >{{ __('Username') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>`
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right"  style="font-size: 15px;" >{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn submit text-white">
                                        {{ __('Login') }}
                                    </button>
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" style="color: white" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
            </div>
        <div class="row col-md-12 datetime" >
            <div class="col-md-9 col-9 navbar-brand mr-0 pl-0" style="font-size: 18px; padding-top">
                    @php
                        $date = Carbon\Carbon::parse('Asia/Manila');
                        echo $date->isoFormat('dddd MMMM DD, YYYY');
                    @endphp
            </div>
            <div class="col-md-3 col-3">
                <div class="navbar-brand float-left mr-0" id="time" style="font-size: 18px;"></div>
            </div>
        </div>

        </div>
        <div class="col-lg-8 loginsec2" >
    
            <div id="carouselExampleIndicators " class="carousel slide mt-4" data-ride="carousel" style="box-shadow: 2px 2px 5px #737373c9;">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active ">
                    <img class="d-block w-100 adsimage" src="{{asset('assets\images\How-Your-Office-Space-Affects-Your-Employees.jpg')}}" alt="First slide">
                    </div>
                    <div class="carousel-item">
                    <img class="d-block w-100 adsimage" src="{{asset('assets\images\venue1.jpg')}}" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                    <img class="d-block w-100 adsimage" src="{{asset('assets\images\modern-school-building.jpg')}}" alt="Third slide">
                    </div>
                </div>
            </div>
        </div>
        
    </div> --}}

<script type="text/javascript">
        var $ = jQuery;

        function showTime() {

            var time = '{{$time}}';
            var date = new Date(),
            utc = new Date(
                date.getFullYear(),
                date.getMonth(),
                date.getDate(),
                date.getHours('{{$hour}}'),
                date.getMinutes('{{$minute}}'),
                date.getSeconds('{{$hour}}')
            );
            var datetime = new Date().toLocaleString("en-US", {timeZone: "Asia/Manila"})
            // moment(datetime).format('MMM DD, YYYY hh:mm')


            document.getElementById('time').innerHTML =  moment(datetime).format('hh : mm : ss A');
        //   document.getElementById('timeMobile').innerHTML = utc.toLocaleTimeString();
        
        }
  
    setInterval(showTime, 1000);
</script>
@endsection
