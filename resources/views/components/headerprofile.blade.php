<li class="nav-item dropdown user user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
      <img src="{{asset($picurl)}}" onerror="this.onerror=null; this.src='{{asset($avatar)}}'" class="user-image img-circle elevation-2 alt="User Image">
      <span class="hidden-xs">{{strtoupper(auth()->user()->name)}}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
      <li class="user-header nav-bg" style="height:auto !important">
        <img src="{{asset($picurl)}}" onerror="this.onerror=null; this.src='{{asset($avatar)}}'" class="img-circle elevation-2" alt="User Image">
        <p>
          {{strtoupper(auth()->user()->name)}} - SADMIN
        </p>
      </li>
      <li class="user-footer">
            <a href="/user/profile" class="btn btn-default ">Profile</a>
            <a class="nav-link btn btn-default float-right" href="#" id="logout" >
              Logout
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </a>
      </li>
    </ul>
</li>