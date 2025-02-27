<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>School List</title>
    <link href="{{asset('assets/icons/fontawesome/css/all.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/main.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>


    <style>
        body {
            height: 100vh;
            padding: 0;
            margin: 0;
        }
        .resethome {
            background-image: linear-gradient( 405deg, #ff9797 13%, #fbfbfb 13%, #fbfbfb 87%, #ff9797 68% );
        }
        .boxx1 img, .boxx2 img, .boxx3 img, .boxx4 img {
            width: 100px;
            height: 100px;
            background: #fff;
            border-radius: 200px;
        }
        .inner {
            height: 150px;
        }
        .schools {
            width: -webkit-fill-available;
            position: absolute;
            top: 35%;
        }
        a {
          padding: .6em!important;
        }
        @media screen and (max-width : 700px){
            .resethome {
                background: none;
                margin-left: 1em;
            }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed resethome">
<div class="wrapper">
  <section class="content">
      <div class="container">
        <div class="row pt-4">
          @foreach(DB::table('schoollist')->where('deleted',0)->get() as $item)
            @php
              $randomNum = rand(1, 5);

              if($randomNum == 1){
                $bgcolor = 'bg-warning';
              }
              else if($randomNum == 2){
                $bgcolor = 'bg-info';
              }
              else if($randomNum == 3){
                $bgcolor = 'bg-primary';
              }
              else if($randomNum == 4){
                $bgcolor = 'bg-success';
              }
              else if($randomNum == 5){
                $bgcolor = 'bg-danger';
              }


            @endphp
            <div class="col-lg-3 col-6 boxx3">
              <div class="small-box {{$bgcolor}}">
                <div class="inner">
                  <center><img src="{{asset($item->schoollogo)}}" alt=""></center>
                  <center><h5>{{$item->schoolname}}</h5></center>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="/viewschool/{{$item->id}}" class="small-box-footer">Visit <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          @endforeach
          <!-- ./col -->
          {{-- <div class="col-lg-3 col-6 boxx2">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <center><img src="{{asset('assets/ckicon.ico')}}" alt=""></center>

                <center><h5>CK SCHOOL 2</h5></center>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="/viewadmiadmin" class="small-box-footer">Visit <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> --}}
          <!-- ./col -->
          {{-- <div class="col-lg-3 col-6 boxx3">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <center><img src="{{asset('assets/ckicon.ico')}}" alt=""></center>

                <center><h5>CK SCHOOL 3</h5></center>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="/viewadmiadmin" class="small-box-footer">Visit <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> --}}
          <!-- ./col -->
          {{-- <div class="col-lg-3 col-6 boxx4">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <center><img src="{{asset('assets/ckicon.ico')}}" alt=""></center>

                <center><h5>CK SCHOOL 4</h5></center>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="/viewadmiadmin" class="small-box-footer">Visit <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> --}}
        </div>
        
      </div>
    </section>
</div>
    @include('sweetalert::alert') 
</body>
</html>