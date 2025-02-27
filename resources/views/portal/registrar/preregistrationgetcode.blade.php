<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pre-registation</title>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <link href="{{asset('assets/css/gijgo.min.css')}}" rel="stylesheet" />
<link href="{{asset('assets/css/main.css')}}" rel="stylesheet">
{{-- <link href="{{asset('assets/icons/fontawesome/css/all.css')}}" rel="stylesheet"> --}}
    <script type="text/javascript" src="{{asset('assets/scripts/jquery-3.3.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
    <script src="{{asset('assets/scripts/bootstrap.min.js')}}" ></script>
    {{-- <script type="text/javascript" src="{{ asset('js/dataTable/jquery-3.3.1.js')}}"></script> --}}
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
                    <form action="/prereg" method="POST" class="needs-validation" >
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
                                  
                                    <center><a id="gotIt" href="/prereg" style="font-size:30px" class="btn btn-warning btn-lg btn-block">Got it!</a></center>
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
</html>
{{-- @endsection --}}
