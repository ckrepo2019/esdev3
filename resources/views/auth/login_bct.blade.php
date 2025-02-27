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
<style>
    .school-details{
        font-size: 14px;
    }
    .visit-website
    {
        font-size: 12px;
    }
    .tagline {
        font-size: 20px;
    }
    @media only screen and (max-width: 600px) {
  .school-details{
      font-size: 10px;
  }
    .visit-website
    {
        font-size: 10px;
    }
    .tagline {
        font-size: 10px;
    }
}


/* .text {
  position: absolute;
  width: 450px;
  left: 50%;
  margin-left: -225px;
  height: 40px;
  top: 50%;
  margin-top: -20px;
}

p {
  display: inline-block;
  vertical-align: top;
  margin: 0;
}

.word {
  position: absolute;
  width: 220px;
  opacity: 0;
}

.letter {
  display: inline-block;
  position: relative;
  float: left;
  transform: translateZ(25px);
  transform-origin: 50% 50% 25px;
}

.letter.out {
  transform: rotateX(90deg);
  transition: transform 0.32s cubic-bezier(0.55, 0.055, 0.675, 0.19);
}

.letter.behind {
  transform: rotateX(-90deg);
}

.letter.in {
  transform: rotateX(0deg);
  transition: transform 0.38s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.wisteria {
  color: #8e44ad;
}

.belize {
  color: #2980b9;
}

.pomegranate {
  color: #c0392b;
}

.green {
  color: #16a085;
}

.midnight {
  color: #2c3e50;
} */
</style>

                
@php
$adimages = DB::table('adimages')->where('isactive','1')->get()

@endphp

<section class="fxt-template-animation fxt-template-layout20 loaded m-0">
    <div class="container">
        <div class="row">
            
            <div class="col-xl-7 col-lg-6 col-12  fxt-none-991 text-center h-100" @if(count($adimages) == 0) style="background-image: url({{asset( DB::table('schoolinfo')->first()->picurl)}}); background-repeat:no-repeat;
                background-size:contain;
                background-position:cover;"@endif>
                @if(count($adimages) == 0)
                {{-- <img class="schoollogo" style="width: 100%:" src="{{asset( DB::table('schoolinfo')->first()->picurl)}}"> --}}
                @else
                    <div id="carouselExampleIndicators " class="carousel slide" data-ride="carousel" style="border-radius: 25px 0px 0px 25px;" >
                        
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
                                        <img class="d-block w-100 adsimage" src="{{asset($item->picurl)}}" alt="First slide" onerror="this.onerror = null, this.src='{{asset('assets/images/background.jpg')}}'">
                                    </div>

                                @else

                                    <div class="carousel-item ">
                                        <img class="d-block w-100 adsimage" src="{{asset($item->picurl)}}" alt="First slide" onerror="this.onerror = null, this.src='{{asset('assets/images/background.jpg')}}'">
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
                @endif
            </div>
            <div class="col-xl-5 col-lg-6 col-12 fxt-bg-color pt-0" style="border-radius: 20px;">
                <div class="fxt-content mt-0 pt-5" >
                    <div class="fxt-header">
                        <div class="row mb-2">
                            <div class="col-xl-12 col-lg-12 col-12 ">
                                <a class="navbar-brand m-0" href="http://{{DB::table('schoolinfo')->first()->websitelink}}">
                                    
                                    @if(isset(DB::table('schoolinfo')->first()->schoolname))
                                        <img class="schoollogo" style="width: 25%;" src="{{asset( DB::table('schoolinfo')->first()->picurl)}}">
                                    @else
                                        SCHOOL LOGO
                                    @endif
                                    
                                    
                                </a>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-12 text-left school-details text-center">
                                <a class="nav-link p-0 visit-website" href="http://{{DB::table('schoolinfo')->first()->websitelink}}"><i class="fas fa-home"></i> Visit our school website</a>
                                @if(isset(DB::table('schoolinfo')->first()->schoolname))
                                    {{DB::table('schoolinfo')->first()->schoolname}}
                                @else
                                    SCHOOL NAME
                                @endif
                                <br/>
                                <small>
                                    @if(isset(DB::table('schoolinfo')->first()->address))
                                        {{DB::table('schoolinfo')->first()->address}}
                                    @else
                                        SCHOOL ADDRESS
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-12 tagline text-center" >
                                @php
                                    $taglinefirst = strtok(DB::table('schoolinfo')->first()->tagline, " ");
                                    $taglinesecond = str_replace($taglinefirst, '',DB::table('schoolinfo')->first()->tagline);
                                @endphp
                                    <em>{{DB::table('schoolinfo')->first()->tagline}}</em>
                                    {{-- <div class="tag-line-animation">{{$taglinefirst}}</div> 
                                    <div class="tag-line-animation"> 
                                    <span>{{$taglinesecond}}</span>
                                    </div> --}}
                                    {{-- <div class="text">
                                        <p>
                                          <span class="word wisteria">{{strtr(DB::table('schoolinfo')->first()->tagline, ' ', '   ')}}</span>
                                          <span class="word belize">{{DB::table('schoolinfo')->first()->tagline}}</span>
                                          <span class="word pomegranate">{{DB::table('schoolinfo')->first()->tagline}}</span>
                                          <span class="word green">{{DB::table('schoolinfo')->first()->tagline}}</span>
                                          <span class="word midnight">{{DB::table('schoolinfo')->first()->tagline}}</span>
                                        </p>
                                      </div> --}}
                            </div>
                        </div>
                        <div class="fxt-style-line">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                        </div>
                    </div>
                    <div class="fxt-form">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <div class="fxt-transformY-50 fxt-transition-delay-1">
                                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Username" style="border-radius: 25px;"/>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"  placeholder="Password"  style="border-top-left-radius: 25px !important; border-bottom-left-radius: 25px !important;">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <span class="input-group-append">
                                      <button type="button" onclick=" return false;" class="btn btn-default btn-flat border" id="view_pass" style="border-top-right-radius: 25px !important; border-bottom-right-radius: 25px !important;"><i class="fas fa-eye"></i></button>
                                    </span>
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
                            {{-- <div class="form-group">
                                <div class="fxt-transformY-50 fxt-transition-delay-3">
                                    <div class="fxt-checkbox-area">
                                        <div class="checkbox">
                                            <input id="checkbox1" type="checkbox">
                                            <label for="checkbox1">Keep me logged in</label>
                                        </div>
                                        <a href="https://affixtheme.com/html/xmee/demo/forgot-password-20.html" class="switcher-text">Forgot Password</a>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="form-group">
                                <div class="fxt-transformY-50 fxt-transition-delay-4">
                                    <button type="submit" class="btn submit fxt-btn-fill" style="background-color: {{$schoolInfo->schoolcolor}}; border-radius: 25px;"> {{ __('Login') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="fxt-footer">
                        <div class="fxt-transformY-50 fxt-transition-delay-5" style="font-size: 9px;">Information provided here is protected by Republic Act No. 10173, otherwise known as the Data Privacy Act of 2012.
                        </div>
                        <div class="row">
                            <div class='col-md-6'>
                                
                                <img src="{{asset('assets/images/essentiel.png')}}" alt="" style="max-height: 55px">
                            </div>
                            <div class='col-md-6'>                                
                                <img src="{{asset('assets/images/CK_Logo.png')}}" alt="" style="max-height: 80px">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">var words = document.getElementsByClassName('word');
    // var wordArray = [];
    // var currentWord = 0;
    
    // words[currentWord].style.opacity = 1;
    // for (var i = 0; i < words.length; i++) {
    //   splitLetters(words[i]);
    // }
    
    // function changeWord() {
    //   var cw = wordArray[currentWord];
    //   var nw = currentWord == words.length-1 ? wordArray[0] : wordArray[currentWord+1];
    //   for (var i = 0; i < cw.length; i++) {
    //     animateLetterOut(cw, i);
    //   }
      
    //   for (var i = 0; i < nw.length; i++) {
    //     nw[i].className = 'letter behind';
    //     nw[0].parentElement.style.opacity = 1;
    //     animateLetterIn(nw, i);
    //   }
      
    //   currentWord = (currentWord == wordArray.length-1) ? 0 : currentWord+1;
    // }
    
    // function animateLetterOut(cw, i) {
    //   setTimeout(function() {
    //     cw[i].className = 'letter out';
    //   }, i*80);
    // }
    
    // function animateLetterIn(nw, i) {
    //   setTimeout(function() {
    //     nw[i].className = 'letter in';
    //   }, 340+(i*80));
    // }
    
    // function splitLetters(word) {
    //   var content = word.innerHTML;
    //   word.innerHTML = '';
    //   var letters = [];
    //   for (var i = 0; i < content.length; i++) {
    //     var letter = document.createElement('span');
    //     letter.className = 'letter';
    //     letter.innerHTML = content.charAt(i);
    //     word.appendChild(letter);
    //     letters.push(letter);
    //   }
      
    //   wordArray.push(letters);
    // }
    
    // changeWord();
    // setInterval(changeWord, 4000);
</script>
@endsection
