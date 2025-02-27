<aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar">
    <!-- Brand Logo -->
    @php
        $getSchoolInfo = DB::table('schoolinfo')
            ->select('schoolname')
            ->get();
    @endphp
    <!-- <a href="../../index3.html" class="brand-link">
        <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{$getSchoolInfo[0]->schoolname}}</span>
        
    </a> -->
    <div class="ckheader">
        <a href="#" class="brand-link sidehead">
          <img src="../../dist/img/CK Logo for ico.png"
               alt="AdminLTE Logo"
               class="brand-image img-circle elevation-3"
               style="opacity: .8">
          <span class="brand-text font-weight-light" style="position: absolute;top: 6%;">{{$getSchoolInfo[0]->schoolname}}</span>
          <span class="brand-text font-weight-light" style="position: absolute;top: 50%;font-size: 16px!important;color:#ffc107"><b>REGISTRAR'S PORTAL</b></span>
        </a>
    </div>
    <!-- Sidebar -->
    <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition">
        <div class="os-resize-observer-host">
            <div class="os-resize-observer observed" style="left: 0px; right: auto;"></div>
        </div>
        <div class="os-size-auto-observer" style="height: calc(100% + 1px); float: left;">
            <div class="os-resize-observer observed"></div>
        </div>
        <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 858px;"></div>
        <div class="os-padding">
            <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll; right: 0px; bottom: 0px;">
                <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
                    <!-- Sidebar user (optional) -->
                    <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block" style="font-size: 80%;text-transform: uppercase">{{auth()->user()->name}}<br>{{auth()->user()->email}}</a>
                        </div>
                    </div> -->
                    <div class="user-panel mt-4 pb-3 mb-3 d-flex">
                            <div class="image">
                            <img src="../../dist/img/download.png" class="img-circle elevation-2" alt="User Image">
                            </div>
                            <div class="info pt-0" style="margin-top: -7px;">
                                <a href="#" class="d-block">{{strtoupper(auth()->user()->name)}}</a>
                                <h6 class="text-white m-0 text-warning">{{auth()->user()->email}}</h6>
                            </div>
                        </div>
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                        with font-awesome or any other icon font library -->
                            <li class="nav-header text-warning"><h4>REGISTRAR'S PORTAL</h4></li>
                            <li class="nav-item">
                                <a href="/home"  id="dashboard" class="nav-link {{Request::url() == url('/home') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-th"></i>
                                    <p>
                                        Home
                                    </p>
                                </a>
                            </li>
                            <li class="nav-header">
                                SENIOR HIGHSCHOOL
                            </li>
                            <li class="nav-item">
                                <a href="/shsetup/track" class="nav-link {{Request::url() == url('/shsetup/track') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-chart-bar"></i>
                                    <p>
                                        Track List
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/shsetup/strand" class="nav-link {{Request::url() == url('/shsetup/strand') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-bars"></i>
                                    <p>
                                        Strand List
                                    </p>
                                </a>
                            </li>
                            <li class="nav-header">
                                STUDENTS
                            </li>
                            <li class="nav-item">
                                <a href="/registrar/studentinfo" class="nav-link {{Request::url() == url('/registrar/studentinfo') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Student Information
                                </p>
                                </a>
                            </li>
                            <li class="nav-header">
                                ENROLL STUDENT
                            </li>
                            <li class="nav-item">
                                <a href="/admission" class="nav-link {{Request::url() == url('/admission') ? 'active' : ''}}">
                                  <i class="nav-icon fas fa-graduation-cap"></i>
                                <p>
                                    Admission
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/enrollment/spclass" class="nav-link {{Request::url() == url('/enrollment/spclass') ? 'active' : ''}}">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <p>
                                    Special Class
                                </p>
                                </a>
                            </li>
                            <li class="nav-header">REPORTS</li>
                            <li class="nav-item has-treeview {{Request::url() == url('/reports/elementary') || Request::url() == url('/reports/juniorhighschool') || Request::url() == url('/reports/seniorhighschool') || Request::url() == url('/reports/preschool') ? 'menu-open' : ''}}">
                                <!-- <a href="#"class="nav-link {{Request::url() == url('/reports/elementary') || Request::url() == url('/reports/juniorhighschool') || Request::url() == url('/reports/seniorhighschool') || Request::url() == url('/reports/preschool') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-tree"></i>
                                    <p>
                                        SCHOOL FORMS
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a> -->
                                <a href="#"class="nav-link {{Request::url() == url('/reports/elementary') || Request::url() == url('/reports/juniorhighschool') || Request::url() == url('/reports/seniorhighschool') || Request::url() == url('/reports/preschool') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-tree"></i>
                                    <p>
                                    SCHOOL FORMS
                                    <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    <li class="nav-item pl-3">
                                        <a href="/reports/preschool" class="nav-link {{Request::url() == url('/reports/preschool') ? 'active' : ''}}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pre-school</p>
                                        </a>
                                    </li>
                                    <li class="nav-item pl-3">
                                        <a href="/reports/elementary" class="nav-link {{Request::url() == url('/reports/elementary') ? 'active' : ''}}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Elementary</p>
                                        </a>
                                    </li>
                                    <li class="nav-item pl-3">
                                        <a href="/reports/juniorhighschool" class="nav-link {{Request::url() == url('/reports/juniorhighschool') ? 'active' : ''}}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Junior High School</p>
                                        </a>
                                    </li>
                                    <li class="nav-item pl-3">
                                        <a href="/reports/seniorhighschool" class="nav-link {{Request::url() == url('/reports/seniorhighschool') ? 'active' : ''}}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Senior High School</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="/show_enrollees/blade/0"  id="dashboard" class="nav-link {{Request::url() == url('/show_enrollees/blade/0') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                            NUMBER OF ENROLLEES
                                    </p>
                                </a>
                            </li>
                        </nav>
                        <!-- /.sidebar-menu -->
                    </div>
                </div>
            </div>
        <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
                <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
            </div>
        </div>
        <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
                <div class="os-scrollbar-handle" style="height: 61.9322%; transform: translate(0px, 0px);"></div>
            </div>
        </div>
        <div class="os-scrollbar-corner"></div>
    </div>
    <!-- /.sidebar -->
</aside>