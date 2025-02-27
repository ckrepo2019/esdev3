<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @if(isset(DB::table('schoolinfo')->first()->abbreviation))
            {{DB::table('schoolinfo')->first()->abbreviation}}
        @else
            SCHOOL NAME
        @endif
    </title>

    {{-- <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script> --}}
    <link href="{{asset('assets/css/main.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets\css\login.css')}}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css')}}">
    <script type="text/javascript" src="{{asset('assets/scripts/jquery-3.3.1.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-migrate-1.2.1.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.browser.min.js') }}"></script>
	<link rel="stylesheet" href="{{asset('assets/css/template.main.css')}}">
   

    <script>
          
        if($(window).width()<500){
            $('#repub').text('Protected by Republic Act No. 10173')
        }

        $(document).ready(function(){

            if( $.browser.name != 'chrome'){
                $(document).ready(function(){
                    alert('This page does not support this browser')
                    window.history.back();
                })
            }
        })
   
    </script>
    


    @yield('headerscript')


    @php
        $schoolInfo = DB::table('schoolinfo')->first();
        
    @endphp
        
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('js/app.js') }}" ></script>


    <style>

       
        /* body{
            background-color: {{$schoolInfo->schoolcolor}};
        } */
        /* #app{
           
                background-image: linear-gradient( 405deg, {{$schoolInfo->schoolcolor}} 13%, #fbfbfb 13%, #fbfbfb 87%, {{$schoolInfo->schoolcolor}} 68% );
           
        }
        .submit{
                  background-color: {{$schoolInfo->schoolcolor}} !important;
            }
        .row{
            margin-right: 0;
            margin-left: 0;
        }

        @media only screen and (max-width: 500px) {

            .schoolname {
                font-size: 12px !important;
            }
            .schoollogo {
                max-height: 50px !important;
            }
            .schooladd {
                font-size: 12px !important;
            }

            .navbar-brand{
                margin-right:0;
            }

            .navbar-toggler{
                line-height: 2;
                font-size: 1 rem;
                padding: 1px;

            }

        }
        .card-header{
            background-color: {{$schoolInfo->schoolcolor}};
            color:white
        }
        .btn-success{
            background-color: {{$schoolInfo->schoolcolor}};
            color:white;
            border-color: {{$schoolInfo->schoolcolor}};
        } */

        .footer {
            color: white !important;
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 45px;
            line-height: 45px;
            background-color: {{$schoolInfo->schoolcolor}};
        }
    @media only screen and (max-width: 600px) {
        .footer {
            color: white !important;
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 45px;
            line-height: 45px;
            background-color: {{$schoolInfo->schoolcolor}};
            font-size: 10px;
        }
}
body {
    background-image:url({{url('assets/images/bct/background_bct.png')}});
    background-size: cover;
  background-attachment: fixed;
}

        /* .btn-success:hover {
            color: #fff;
            background-color: {{$schoolInfo->schoolcolor}};
            border-color: {{$schoolInfo->schoolcolor}}';
        }

        .bg-success-perschool {

            color: #fff !important;
            background-color: {{$schoolInfo->schoolcolor}} !important;
            border-color: {{$schoolInfo->schoolcolor}} !important;

        }

        .appadd {
                white-space: nowrap;
                overflow: hidden;
        }

        .btn-success:focus, .btn-success.focus {
            color: #fff;
            background-color: {{$schoolInfo->schoolcolor}};
            border-color: {{$schoolInfo->schoolcolor}};
            box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0);
        } */
    </style>



  
    
</head>
    <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed  pace-primary">
        <div id="app" class=" min-vh-100">
            {{-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm pt-0 pb-0">
                <div class="container ">
                    <a class="navbar-brand" href="http://{{DB::table('schoolinfo')->first()->websitelink}}">
                        
                        @if(isset(DB::table('schoolinfo')->first()->schoolname))
                            <img class="schoollogo" style="max-height: 95px" src="{{asset( DB::table('schoolinfo')->first()->picurl)}}">
                        @else
                            SCHOOL LOGO
                        @endif
                        
                        
                    </a>
                    <span class="appadd">
                        <span style="font-size:35px; margin-top:-10px;  font-family: Tw Cen MT, Times, serif; color: {{$schoolInfo->schoolcolor}}" class="schoolname">
                            @if(isset(DB::table('schoolinfo')->first()->schoolname))
                                {{DB::table('schoolinfo')->first()->schoolname}}
                            @else
                                SCHOOL NAME
                            @endif
                            
                      
                        </span><br>
                        <span style="font-size:25px;font-family: Tw Cen MT, Times, serif; color: {{$schoolInfo->schoolcolor}}" class="schooladd">
                            @if(isset(DB::table('schoolinfo')->first()->address))
                                {{DB::table('schoolinfo')->first()->address}}
                            @else
                                SCHOOL ADDRESS
                            @endif
                        </span>
                    </span>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                
                        <ul class="navbar-nav mr-auto">

                        </ul>
                        
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="http://{{DB::table('schoolinfo')->first()->websitelink}}"><i class="fas fa-home"></i> {{ __('Home') }}</a>
                            </li>
                           
                         
                      </ul>
                  </div>

                </div>
            </nav> --}}

            <main >
                @yield('content')
               
            </main>
            {{-- <footer class="footer">
                <div class="container text-center" id="repub">
                    <span >Information provided here is protected by Republic Act No. 10173, otherwise known as the Data Privacy Act.</span>
                </div>
            </footer>
        </div> --}}

        @yield('footerscript')
    
        <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
        <script src="{{asset('plugins/pace-progress/pace.min.js') }}"></script>
       
        
	<!-- jquery-->
	<script src="{{asset('plugins/tempotemp/js/jquery-3.5.0.min.js')}}"></script>
	<!-- Popper js -->
	<script src="{{asset('plugins/tempotemp/js/popper.min.js')}}"></script>
	<!-- Bootstrap js -->
	<script src="{{asset('plugins/tempotemp/js/bootstrap.min.js')}}"></script>
	<!-- Imagesloaded js -->
	<script src="{{asset('plugins/tempotemp/js/imagesloaded.pkgd.min.js')}}"></script>
	<!-- Validator js -->
	<script src="{{asset('plugins/tempotemp/js/validator.min.js')}}"></script>
	<!-- Custom Js -->
	<script src="{{asset('plugins/tempotemp/js/main.js')}}"></script>
        
      
    
        {{-- <script>
            $(document).ready(function(){

                
                if($(window).width()<500){
                    
                    $('.schoolname').css('font-size','15px')
                    $('.schoollogo').css('max-height','30px')
                    $('.schooladd').css('font-size','11px')
                }

            })
        </script> --}}

        @include('sweetalert::alert') 

    
     

     
       
            
    </body>
</html>