<nav class="main-header navbar navbar-expand navbar-white navbar-light navss">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="/home" class="nav-link text-light">Home</a>
        </li>
    </ul>




    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        {{-- <li class="nav-item dropdown user user-menu">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-primary navbar-badge" id="notifbell_count">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notificationBellHolder">
                        <a href="/notificationv2/index" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
            </ul>
        </li> --}}

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge" id="notification_count">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notification_holder">
                <a href="/hr/settings/notification/index" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
        </li>


        <li class="nav-item">
            <a href="#" id="logout" class="nav-link">
                <!-- <i class="nav-icon fa fa-power-off"></i> -->
                <span class="logoutshow" id="logoutshow"> Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</nav>
