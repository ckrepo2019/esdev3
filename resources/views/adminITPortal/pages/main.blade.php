<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Schools </title>
    
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <link href="{{asset('assets/css/gijgo.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/jqvmap/jqvmap.min.css')}}">
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css')}}">
    <link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="{{asset('plugins/dropzone/min/dropzone.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}?v=3.2.0">
  </head>
  <body class="hold-transition layout-top-nav">
    <div class="wrapper">    
      <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
        <div class="container">
          <a href="#" class="navbar-brand">
            <span class="brand-text font-weight-light">School Monitoring</span>
          </a>
          <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse order-3" id="navbarCollapse"></div>
          <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
            <li class="nav-item"></li>
                <a class="nav-link" style="color: gray;" data-toggle="dropdown" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >
                  <i class="fas fa-sign-out-alt logouthover" style="margin-right: 6px; "></i>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                  </form>
                </a>
            </li>
          </ul> 
        </div>
      </nav>    
      <div class="content-wrapper">      
        <div class="content-header">
          <div class="container"></div>
        </div>
        <div class="content">
          <div class="container">         
            <div class="row">
              <div class="col-md-12 mb-2">
                <input class="filter form-control" placeholder="Search school" />
              </div>
            </div>     
              <div class="row">
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
                      $avatar = 'assets/images/department_of_Education.png';        
                    @endphp
                    {{-- <div class="col-3 each-school" data-string="{{$item->schoolname}}<">
                      <a href="/viewschool/{{$item->id}}" class="small-box-footer">
                          <div class="card shadow">
                              <div class="card-header">
                                  <center><img src="{{$item->schoollogo}}" onerror="this.onerror = null, this.src='{{asset($avatar)}}'"  alt="" width="50%"></center>
                                  {{$item->schoolname}}
                              </div>
                          </div>
                      </a>
                    </div> --}}

					<div class="col-3 col-md-3 col-sm-12 each-school schoolcard" data-string="{{$item->schoolname}} && {{$item->abbr}}">
						<a href="/viewschool/{{$item->id}}" class="small-box-footer">
							{{-- <div class="card shadow">
						    <div class="card-header" style="height: 300px">
									  
									  <center><img src="{{$item->schoollogo}}" onerror="this.onerror = null, this.src='{{asset($avatar)}}'" alt="" width="200px" height="200px"></center>
									  <center><h1 style="font-size: 30px;">{{$item->abbr}}</h1></center>
									  {{$item->schoolname}}
								  </div>
							  </div> --}}
							  <div class="card">
								<div class="card-body" style="height: 250px">
									  <center><img src="{{$item->schoollogo}}" onerror="this.onerror = null, this.src='{{asset($avatar)}}'"  alt="" width="50%"></center>
									  <center><h1 style="font-size: 30px;">{{$item->abbr}}</h1></center>
									  <center><p style="font-size: 15px;">{{$item->schoolname}}</p></center>
								</div>
							</div>
						</a>
                    </div>
                  @endforeach
              </div>
              <div class="row cards-container">
              </div>
          </div>
        </div>      
      </div>           
      <aside class="control-sidebar control-sidebar-dark"></aside>
      <footer class="main-footer">
        <strong>Developed by <a href="http://ckgroup.ph">CK.dev</a>
      </footer>
    </div>      
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('plugins/croppie/croppie.js')}}"></script>
    <link rel="stylesheet" href="{{asset('plugins/croppie/croppie.css')}}">
    <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script> 
    <script src="{{asset('dist/js/adminlte.js')}}"></script>
    <script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
    <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('dist/js/demo.js')}}"></script>
    <script src="{{asset('dist/js/pages/dashboard3.js')}}"></script>
    <script src="{{asset('plugins/pace-progress/pace.min.js')}}"></script>
    <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
    <!-- DataTables -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
    <!-- dropzonejs -->
    <script src="{{asset('plugins/dropzone/min/dropzone.min.js')}}"></script>
    <script>
        
        $(document).ready(function(){
            $(".filter").on("keyup", function() {
                var input = $(this).val().toUpperCase();
                var visibleCards = 0;
                var hiddenCards = 0;
    
                // $(".cards-container").append($("<div class='card-group card-group-filter'></div>"));
    
    
                $(".each-school").each(function() {
                    if ($(this).data("string").toUpperCase().indexOf(input) < 0) {
    
                    $(".card-group.card-group-filter:first-of-type").append($(this));
                    $(this).hide();
                    hiddenCards++;
    
                    } else {
    
                    $(".card-group.card-group-filter:last-of-type").prepend($(this));
                    $(this).show();
                    visibleCards++;
    
                    if (((visibleCards % 4) == 0)) {
                        $(".cards-container").append($("<div class='card-group card-group-filter'></div>"));
                    }
                    }
                });
    
            });
        })
    
    </script>
    @include('sweetalert::alert') 
  </body>
</html>