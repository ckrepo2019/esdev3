<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">
    <li class="nav-item">
        <a  class="{{Request::url() == url('/home') ? 'active':''}} nav-link" href="/home">
            <i class="nav-icon fa fa-home"></i>
            <p>
            Home
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a class="{{Request::url() == url('/manageaccounts') ? 'active':''}} nav-link" href="/manageaccounts">
            <i class="nav-icon fa fa-chart-line"></i>
            <p>
                Faculty and Staff
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a class="{{Request::url() == url('/manageschoolyear') ? 'active':''}} nav-link" href="/manageschoolyear">
            <i class="nav-icon fa fa-chart-line"></i>
            <p>
                School Year
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a class="{{Request::url() == url('/admingetrooms') ? 'active':''}} nav-link" href="/admingetrooms">
            <i class="nav-icon fa fa-chart-line"></i>
            <p>
                Rooms
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a class="{{Request::url() == url('/adminloadholidays') ? 'active':''}} nav-link" href="/adminloadholidays">
            <i class="nav-icon fa fa-chart-line"></i>
            <p>
                School Calendar
            </p>
        </a>
    </li>
    <li class="nav-item has-treeview ">
        <a href="#" class="nav-link ">
            <i class="nav-icon far fa-envelope"></i>
            <p>
            Debugger
            <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview udernavs">
            <li class="nav-item">
                <a href="/studentUserDebugger" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Student</p>
                </a>
            </li>
        </ul>
    </li>
    {{-- <li class="nav-item has-treeview ">
        <a href="#" class="nav-link ">
            <i class="nav-icon far fa-calendar-times"></i>
            <p>
            Calendar
            <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview ">
            <li class="nav-item">
                <a href="/adminloadholidays" class="nav-link ">
                    <i class="fas fa-glass-cheers nav-icon"></i>
                    <p>Holidays</p>
                </a>
            </li>
        </ul>
        <ul class="nav nav-treeview ">
            <li class="nav-item">
                <a href="/adminloadholidays" class="nav-link ">
                    <i class="fas fa-glass-cheers nav-icon"></i>
                    <p>Activity</p>
                </a>
            </li>
        </ul>
    </li> --}}
</ul>
</nav>


