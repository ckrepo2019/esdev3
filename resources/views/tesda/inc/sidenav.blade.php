<style>
    .nav-link, .nav-header {
        color: #343A30 !important;
    }

    .nav-link.active{
        color: #F0F0F0 !important;
        background: #787c7f !important;
    }

    .text-clr{
        color: #343A30 !important;
    }

    .user-panel {
        border-bottom: 1px solid #dee2e6 !important;
    }

    .brand-link {
        border-bottom: 1px solid #dee2e6 !important;
    }
</style>

<aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar" style="background-color: white !important">
    <div class="">
        <a href="#" class="brand-link" style="background-color: white !important;">
            @if (DB::table('schoolinfo')->first()->picurl != null)
                <img src="{{ asset(DB::table('schoolinfo')->first()->picurl) }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" height="100%" width="100%" style="opacity: .8; height: 30px; width: 30px;"
                    onerror="this.src='{{ asset('assets/images/department_of_Education.png') }}'">
            @else
                <img src="{{ asset('assets/images/department_of_Education.png') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
            @endif
            <span class="brand-text text-clr" style="position: absolute;top: 6%; font-weight: 500 !important">
                {{ DB::table('schoolinfo')->first()->abbreviation }}
            </span>
            <span class="brand-text font-weight-light text-clr"
                style="position: absolute;top: 50%;font-size: 16px!important;"><b>TESDA/Techvoc</b></span>
        </a>
    </div>
    <div id="sidenav-container" class="sidebar"
        style="overflow-x: hidden; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;
        background-color: white;">
        @php
            $randomnum = rand(1, 4);
            $avatar =
                'assets/images/avatars/unknown.png' .
                '?random="' .
                \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss') .
                '"';
            $picurl = DB::table('teacher')
                ->where('userid', auth()->user()->id)
                ->first()->picurl;
            $picurl =
                str_replace('jpg', 'png', $picurl) .
                '?random="' .
                \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss') .
                '"';
        @endphp
        <div class="row">
            <div class="col-md-12">
                <div class="text-center mt-2">
                    <img class="profile-user-img img-fluid img-circle" src="{{ asset($picurl) }}"
                        onerror="this.onerror=null; this.src='{{ asset($avatar) }}'" alt="User Image" width="100%" height="100%"
                        style="width:130px;  border-radius: 12% !important;">
                </div>
            </div>
        </div>
        <div class="row user-panel">
            <div class="col-md-12 info text-center">
                <a class="mb-0 text-clr" style="font-weight: 500">{{ auth()->user()->name }}</a>
                <h6 class="text-clr text-center" style="font-weight: 400">{{ auth()->user()->email }}</h6>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/home') ? 'active' : '' }} nav-link" href="/home">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/user/profile"
                        class="nav-link {{ Request::url() == url('/user/profile') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/school-calendar"
                        class="nav-link {{ Request::url() == url('/school-calendar') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-calendar"></i>
                        <p>
                            Calendar
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/hr/settings/notification/index"
                        class="nav-link {{ Request::url() == url('/hr/settings/notification/index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>
                            Notification & Request
                            {{-- <span class="ml-2 badge badge-primary">2</span> --}}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/schoolfolderv2/index') ? 'active' : '' }} nav-link"
                        href="/schoolfolderv2/index">
                        <i class="nav-icon fa fa-folder"></i>
                        <p>
                            TESDA File Directory
                        </p>
                    </a>
                </li>

                <li class="nav-header">
                    SETUP
                </li>

                <li class="nav-item has-treeview {{ Request::url() == url('/tesda/setup/gradetransmutation/index') || Request::url() == url('/tesda/setup') || Request::url() == url('/tesda/coursesSetup') || Request::url() == url('/tesda/gradeTransmutation') || Request::url() == url('/tesda/gradingSetup') || Request::url() == url('/tesda/batches') ? 'menu-open' : '' }}">
                    <a href="/tesda/setup"
                       class="nav-link {{ Request::url() == url('/tesda/setup/gradetransmutation/index') || Request::url() == url('/tesda/gradeTransmutation') || Request::url() == url('/tesda/setup') ||  Request::url() == url('/tesda/coursesSetup') || Request::url() == url('/tesda/gradingSetup') || Request::url() == url('/tesda/batches') ? 'active' : '' }}"
                       onclick="if(!event.target.closest('.right')) { window.location.href='/tesda/setup'; }"
                    >
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            General Configuration
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/tesda/coursesSetup"
                                class="nav-link {{ Request::url() == url('/tesda/coursesSetup') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Courses Setup
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/tesda/gradingSetup"
                                class="nav-link {{ Request::url() == url('/tesda/gradingSetup') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Grading Setup
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/tesda/gradeTransmutation"
                                class="nav-link {{ Request::url() == url('/tesda/gradeTransmutation') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Grade Transmutation
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/tesda/batches"
                                class="nav-link {{ Request::url() == url('/tesda/batches') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Batches
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header nav-item"> STUDENT MANAGEMENT</li>

                <li class="nav-item has-treeview {{ Request::url() == url('/tesda/studentmanagement') ||Request::url() == url('/tesda/enrollment') || Request::url() == url('/tesda/studentInfo') ? 'menu-open' : ''}}">
                    <a href="/tesda/studentmanagement"
                    class="nav-link {{ Request::url() == url('/tesda/studentmanagement') || Request::url() == url('/tesda/enrollment') || Request::url() == url('/tesda/studentInfo') ? 'active' : ''}}"
                    onclick="if(!event.target.closest('.right')) { window.location.href='/tesda/studentmanagement'; }"
                 >
                     <i class="nav-icon fas fa-cog"></i>
                     <p>
                         Student
                         <i class="right fas fa-angle-left"></i>
                     </p>
                 </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/tesda/enrollment"
                                class="nav-link {{ Request::url() == url('/tesda/enrollment') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Enrollment</p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="/student/preregistration"
                                class="nav-link {{ Request::url() == url('/student/preregistration') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Enrollment</p>
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a href="/tesda/studentInfo"
                                class="nav-link {{ Request::url() == url('/tesda/studentInfo') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Student Information</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview {{ Request::url() == url('/tesda/gradestatus_nav') ||  Request::url() == url('/tesda/gradeStatus') ? 'menu-open' : ''}}">
                    <a href="/tesda/gradestatus_nav"
                    class="nav-link {{ Request::url() == url('/tesda/gradestatus_nav') || Request::url() == url('/tesda/gradeStatus') ? 'active' : ''}}"
                    onclick="if(!event.target.closest('.right')) { window.location.href='/tesda/gradestatus_nav'; }"
                 >
                     <i class="nav-icon fas fa-cog"></i>
                     <p>
                         Grade Status
                         <i class="right fas fa-angle-left"></i>
                     </p>
                 </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/tesda/gradeStatus"
                                class="nav-link {{ Request::url() == url('/tesda/gradeStatus') ? 'active' : '' }} ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Grade Status Summary</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview {{ Request::url() == url('/tesda/reports') || Request::url() == url('/tesda/cor') || Request::url() == url('/tesda/tor') || Request::url() == url('/tesda/attendanceSummary') || Request::url() == url('/tesda/enrollmentSummary') || Request::url() == url('/tesda/applicationForGraduation') || Request::url() == url('/tesda/certifications') ? 'menu-open' : ''}}">
                    <a href="/tesda/reports"
                    class="nav-link {{ Request::url() == url('/tesda/reports') || Request::url() == url('/tesda/cor') || Request::url() == url('/tesda/tor') || Request::url() == url('/tesda/attendanceSummary') || Request::url() == url('/tesda/enrollmentSummary') || Request::url() == url('/tesda/applicationForGraduation') || Request::url() == url('/tesda/certifications') ? 'active' : ''}}"
                    onclick="if(!event.target.closest('.right')) { window.location.href='/tesda/reports'; }"
                 >
                     <i class="nav-icon fas fa-cog"></i>
                     <p>
                         Reports
                         <i class="right fas fa-angle-left"></i>
                     </p>
                 </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/tesda/cor"
                                class="nav-link {{ Request::url() == url('/tesda/cor') ? 'active' : '' }} ">
                                <i class="far fa-circle nav-icon"></i>
                                <p style="font-size: 14px">Certificate of Registration</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/tesda/tor"
                                class="nav-link {{ Request::url() == url('/tesda/tor') ? 'active' : '' }} ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Transcript of Records</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/tesda/attendanceSummary"
                                class="nav-link {{ Request::url() == url('/tesda/attendanceSummary') ? 'active' : '' }} ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Attendance Summary</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/tesda/enrollmentSummary"
                                class="nav-link {{ Request::url() == url('/tesda/enrollmentSummary') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Enrollment Summary</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/tesda/applicationForGraduation"
                                class="nav-link {{ Request::url() == url('/tesda/applicationForGraduation') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p style="font-size: 14px">Application for Graduation</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/tesda/certifications"
                                class="nav-link {{ Request::url() == url('/tesda/certifications') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Certifications</p>
                            </a>
                        </li>
                    </ul>
                </li>



                <li class="nav-header">Utility</li>
                <li class="nav-item">
                    <a href="/textblast" class="nav-link {{ Request::url() == url('/textblast') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-paper-plane"></i>
                        <p>
                            Textblast
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/documenttracking"
                        class="nav-link {{ Request::url() == url('/documenttracking') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-image"></i>
                        <p>
                            Document Tracking
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/hr/requirements/employee"
                        class="nav-link {{ Request::fullUrl() === url('/hr/requirements/employee') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            My Requirements
                        </p>
                    </a>
                </li>

                <li class="nav-header">My Account</li>
                <li class="nav-item">
                    <a href="/dtr/attendance/index" id="dashboard"
                        class="nav-link {{ Request::url() == url('/dtr/attendance/index') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            DTR
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/leaves/apply/index" id="dashboard"
                        class="nav-link {{ Request::url() == url('/leaves/apply/index') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Leave Applications
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/overtime"
                        class="nav-link {{ Request::fullUrl() === url('/overtime') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Overtime
                        </p>
                    </a>
                </li>


                <li class="nav-header text-warning">My Other Portal</li>

                @php
                    $priveledge = DB::table('faspriv')
                        ->join('usertype', 'faspriv.usertype', '=', 'usertype.id')
                        ->select('faspriv.*', 'usertype.utype')
                        ->where('userid', auth()->user()->id)
                        ->where('faspriv.deleted', '0')
                        ->where('type_active', 1)
                        ->where('faspriv.privelege', '!=', '0')
                        ->get();

                    $usertype = DB::table('usertype')
                        ->where('deleted', 0)
                        ->where('id', auth()->user()->type)
                        ->first();

                @endphp

                @foreach ($priveledge as $item)
                    @if ($item->usertype != Session::get('currentPortal'))
                        <li class="nav-item">
                            <a class="nav-link portal" href="/gotoPortal/{{ $item->usertype }}"
                                id="{{ $item->usertype }}">
                                <i class=" nav-icon fas fa-cloud"></i>
                                <p>
                                    {{ $item->utype }}
                                </p>
                            </a>
                        </li>
                    @endif
                @endforeach

                @if ($usertype->id != Session::get('currentPortal'))
                    <li class="nav-item">
                        <a class="nav-link portal" href="/gotoPortal/{{ $usertype->id }}">
                            <i class=" nav-icon fas fa-cloud"></i>
                            <p>
                                {{ $usertype->utype }}
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
<<<<<<< HEAD

=======
>>>>>>> 71f92aa1bf21dbc2aae5f1472a3ab3d99798970f
</aside>
