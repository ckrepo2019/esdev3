
  <nav class="main-header navbar navbar-expand navbar-white navbar-light navss">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index3.html" class="nav-link">Home</a>
      </li> -->
    </ul>

    <div class="form-inline menunav" style="height: 50px!important">
        <div class="input-group input-group-sm">
          <ul class="nicemenu">
            <li>
              <a href="/home">
                <div class="icon">
                  <i class="fas fa-home"></i>
                  <i class="fas fa-home"></i>
                </div>
                <div class="name"><span  data-text="Home">Home</span></div>
              </a>
            </li>

          </ul>
        </div>
      </div>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      {{-- <li class="nav-item">
        <a class="nav-link" id="show-notifications" clicked="0">
          <i class="fa fa-bell" style="line-height: unset;"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
      </li> --}}
      @php
      $notifications = DB::table('clinic_notfication')
            ->where('headerid', auth()->user()->id)
            ->where('status', 0)
            ->where('deleted', 0)
            ->get();

      $notifications2 = DB::table('clinic_notfication')
            ->where('headerid', auth()->user()->id)
            ->where('status', 1)
            ->where('deleted', 0)
            ->get();
      @endphp
      <li class="nav-item dropdown">
          <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-bell" style="margin-top: 6px"></i>
          @if(count($notifications)!=0)
          <span class="badge badge-danger navbar-badge">{{count($notifications)}}</span>
          @endif
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          @if(count($notifications2)==0 && count($notifications)==0)
          <a class="dropdown-item" href="#">No notification</a>
          @else
          <a class="dropdown-item" style="color:black">New</a>
          @if(count($notifications)==0)
          <a class="dropdown-item" style="color:gray">No new notification</a>
          @else
          @foreach($notifications as $notification)
          <hr>
          <small class="dropdown-item" style="color:gray"> <i class="fa fa-laptop-medical" style="color:black"></i> Prescription</small>
          <a class="dropdown-item" href="/clinic/complaints/index">{{$notification->descripton}}</a>
          <small class="dropdown-item" style="color:gray">{{date('M d, Y h:m A', strtotime($notification->createddatetime))}}</small>

          @endforeach
          @endif
          <hr>
          <a class="dropdown-item" style="color:black">Other</a>
          @foreach($notifications2 as $notification1)
          <hr>
          <small class="dropdown-item" style="color:gray"> <i class="fa fa-laptop-medical" style="color:black"></i> Prescription</small>
          <a class="dropdown-item" href="/clinic/complaints/index">{{$notification1->descripton}} </a>
          @if(isset($notification1->updateddateime))
          <small class="dropdown-item" style="color:gray">{{date('M d, Y h:m A', strtotime($notification1->updateddateime))}}</small>
          @else
          <small class="dropdown-item" style="color:gray">{{date('M d, Y h:m A', strtotime($notification1->createddatetime))}}</small>
          @endif
          @endforeach
          @endif
          </div>
          </li>

    <li class="nav-item dropdown sideright">
      <a href="#" id="logout" class="nav-link">
          <!-- <i class="fas fa-sign-out-alt logouthover" style="margin-right: 7px; color: #fff"></i> -->
          <span class="logoutshow" id="logoutshow"> Logout</span>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </a>

      </li>
    </ul>
  </nav>

  <script>
    $(document).ready(function(){
      $(document).on('click','#navbarDropdown', function(){
        console.log("Hello!");

      $.ajax({
                    url:'/clinic_notifications',
                            type:'GET',
                            dataType: 'json',
                            success:function(data1) {
                                console.log('Done');  
                            }
            })
      })

    })
</script>


