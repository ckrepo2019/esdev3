<nav class="main-header navbar navbar-expand navbar-dark pace-primary nav-bg">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown sideright logout">
            <a href="#" id="logout" class="nav-link">
			<span class="logoutshow" id="logoutshow"> Logout</span>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </a>
      </li>
    </ul>
</nav>
