<li class="nav-item dropdown user user-menu">
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell text-white"></i>
                <span class="badge badge-primary navbar-badge" id="notifbell_count">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notificationBellHolder">
                <a href="/notificationv2/index" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>
    </ul>
</li>
<li class="nav-item dropdown user user-menu">
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments text-white"></i>
                <span class="badge badge-danger navbar-badge" id="notification_count">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notification_holder">
                <div class="text-center">
                    <p class="text-muted">No message found</p>
                </div>
                <a href="/hr/settings/notification/index" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
        </li>
    </ul>
</li>
<li class="nav-item dropdown user user-menu">
    @php
        $refid = DB::table('usertype')
            ->where('id', auth()->user()->type)
            ->first()->refid;
    @endphp
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        @php
            $usertype = DB::table('usertype')
                ->where('id', auth()->user()->type)
                ->first();
            if ($usertype->refid == 35) {
                $usertype = $usertype->utype;
            } elseif ($usertype->refid == 36) {
                $usertype = $usertype->utype;
            }
        @endphp
        <div class="d-flex">
            @if ($refid == 35)
                <img src="{{ asset($picurl) }}" onerror="this.onerror=null; this.src='{{ asset($avatar) }}'"
                    class="user-image img-circle elevation-2 alt="User Image">
                <div class="d-flex flex-column justify-content-center text-dark" style="transform: translateY(-7px);">
                    <span class="d-none d-sm-block mt-0 " style="font-weight: 500"
                        style="font-size: 15px !important">{{ strtoupper(auth()->user()->name) }} </span>
                    <span style="font-size: 11px;margin-top: 0px; transform: translateY(-8px);">TESDA
                        Administrator</span>
                </div>
            @elseif ($refid == 36)
                <img src="{{ asset($picurl) }}" onerror="this.onerror=null; this.src='{{ asset($avatar) }}'"
                    class="user-image img-circle elevation-2 alt="User Image">
                <div class="d-flex flex-column justify-content-center text-dark" style="transform: translateY(-7px);">
                    <span class="d-none d-sm-block mt-0 " style="font-weight: 500"
                        style="font-size: 15px !important">{{ strtoupper(auth()->user()->name) }} </span>
                    <span style="font-size: 11px;margin-top: 0px; transform: translateY(-8px);">TESDA
                        Trainer</span>
                </div>
            @else
                <img src="{{ asset($picurl) }}" onerror="this.onerror=null; this.src='{{ asset($avatar) }}'"
                    class="user-image img-circle elevation-2 alt="User Image">
                <div class="d-flex flex-column justify-content-center text-white" style="transform: translateY(-3px); ">
                    <span class="d-none d-sm-block mt-0">{{ strtoupper(auth()->user()->name) }} </span>
                </div>
            @endif
        </div>
    </a>
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

        @if ($refid == 35)
            <li class="user-header nav-bg" style="height:auto !important; display: flex; justify-content: center;">
                <img src="{{ asset($picurl) }}" onerror="this.onerror=null; this.src='{{ asset($avatar) }}'"
                    class="img-circle elevation-2" alt="User Image" style="height: 70px; width: 70px;">
                <div class="d-flex flex-column justify-content-center ml-1">
                    <p style="font-weight: 500;">
                        {{ strtoupper(auth()->user()->name) }}
                    </p>
                    <span style="font-size: 15px">TESDA Administrator</span>
                </div>
            </li>
            <li class="user-footer">
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-id-card text-gray"></i> Profile
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-key text-gray"></i> Change Password
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-calendar-check text-gray"></i> My Leave
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-clock text-gray"></i> My Overtime
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-exclamation-circle text-gray"></i> Report Incident
                </a>
                <a class="nav-link btn btn-dark btn-block" href="#" id="logout"
                    style="color: white !important">
                    <i class="fas fa-sign-out-alt text-white"></i> Logout
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </a>
            </li>
        @elseif ($refid == 36)
            <li class="user-header nav-bg" style="height:auto !important; display: flex; justify-content: center;">
                <img src="{{ asset($picurl) }}" onerror="this.onerror=null; this.src='{{ asset($avatar) }}'"
                    class="img-circle elevation-2" alt="User Image" style="height: 70px; width: 70px;">
                <div class="d-flex flex-column justify-content-center ml-1">
                    <p style="font-weight: 500;">
                        {{ strtoupper(auth()->user()->name) }}
                    </p>
                    <span style="font-size: 15px">TESDA Trainer</span>
                </div>
            </li>
            <li class="user-footer">
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-id-card text-gray"></i> Profile
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-key text-gray"></i> Change Password
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-calendar-check text-gray"></i> My Leave
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-clock text-gray"></i> My Overtime
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-exclamation-circle text-gray"></i> Report Incident
                </a>
                <a class="nav-link btn btn-dark btn-block" href="#" id="logout"
                    style="color: white !important">
                    <i class="fas fa-sign-out-alt text-white"></i> Logout
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </a>
            </li>
        @else
            <li class="user-header nav-bg" style="height:auto !important">
                <img src="{{ asset($picurl) }}" onerror="this.onerror=null; this.src='{{ asset($avatar) }}'"
                    class="img-circle elevation-2" alt="User Image">
                <p style="font-weight: 500">
                    {{ strtoupper(auth()->user()->name) }}
                </p>
                {{-- <span>{{ $usertype }}</span> --}}
            </li>
            <li class="user-footer">
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-user text-gray"></i> Profile
                </a>
                <a class="nav-link btn btn-default btn-block" href="#" id="logout">
                    <i class="fas fa-sign-out-alt text-gray"></i> Logout
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </a>
            </li>
        @endif


    </ul>
</li>
