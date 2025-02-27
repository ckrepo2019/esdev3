{{-- <style>
    .nav-font ul li a i,
    p {
        color: rgb(212, 209, 209);
    }
</style>
<nav class="mt-2 nav-font">
    <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
            <a href="/idprinting/home" class="nav-link {{ Request::url() == url('/idprinting/home') ? 'active' : '' }} ">
                <i class="nav-icon fa fa-home"></i>
                <p>
                    Home
                </p>
            </a>

        </li>

        <li class="nav-item">
            <a href="/idprinting/view/templates"
                class="nav-link {{ Request::url() == url('/idprinting/view/templates') ? 'active' : '' }}">
                <i class="nav-icon fas fa-id-card"></i>
                <p>
                    Templates
                </p>
            </a>
        </li>
        @if (auth()->user()->type === 17)
            <li class="nav-item">
                <a href="/idprinting/manageuser"
                    class="nav-link {{ Request::url() == url('/idprinting/manageuser') ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-users-cog"></i>
                    <p>
                        Manage Staff
                    </p>
                </a>

            </li>
        @endif


    </ul>
</nav> --}}
