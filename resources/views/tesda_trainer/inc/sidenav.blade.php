<style>
    .nav-link,
    .nav-header {
        color: #343A30 !important;
    }

    .nav-link.active {
        color: #F0F0F0 !important;
        background: #787c7f !important;
    }

    .text-clr {
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
                    class="brand-image img-circle elevation-3" style="opacity: .8"
                    onerror="this.src='{{ asset('assets/images/department_of_Education.png') }}'">
            @else
                <img src="{{ asset('assets/images/department_of_Education.png') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
            @endif
            <span class="brand-text text-clr" style="position: absolute;top: 6%; font-weight: 500 !important">
                {{ DB::table('schoolinfo')->first()->abbreviation }}
            </span>
            <span class="brand-text font-weight-light text-clr"
                style="position: absolute;top: 50%;font-size: 16px!important;"><b>TESDA Trainer
                </b></span>
        </a>
    </div>
    <div class="sidebar"
        style="overflow-x: hidden; overflow-y: scroll; scrollbar-width: none; -ms-overflow-style: none;
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
                        onerror="this.onerror=null; this.src='{{ asset($avatar) }}'" alt="User Image" width="100%"
                        style="width:130px; border-radius: 12% !important;">
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

                {{-- <li class="nav-item">
                    <a class=" nav-link {{ Request::url() == url('/userguide/setup') ? 'active' : '' }} "
                        href="/userguide/setup">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            User Guide
                        </p>
                    </a>
                </li> --}}

                <li class="nav-item">
                    <a href="/hr/settings/notification/index"
                        class="nav-link {{ Request::url() == url('/hr/settings/notification/index') ? 'active' : '' }}">
                        <i class="nav-icon  fas fa-exclamation"></i>
                        <p>
                            Notification & Request
                            {{-- <span class="ml-2 badge badge-primary">2</span> --}}
                        </p>
                    </a>
                </li>
        
                <li class="nav-header text-warning">SECTION</li>
                <li class="nav-item">
                    <a class="{{ Request::fullUrl() == url('/tesda/tesda_trainer/scheduling') ? 'active' : '' }} nav-link"
                        href="/tesda/tesda_trainer/scheduling">
                        <i class="nav-icon fa fa-clipboard-list"></i>
                        <p>
                            Class Schedule Details
                        </p>
                    </a>
                </li>
        
        
                <li class="nav-header text-warning">GRADING AND ATTENDANCE</li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/college/attendance-showpage') ? 'active' : '' }} nav-link"
                        href="/college/attendance-showpage">
                        <i class="nav-icon fas fa-user-check "></i>
                        <p>
                            Attendance <span class="right badge badge-warning" id="pending_grade_count"></span>
                        </p>
                    </a>
                </li>
        
                {{-- <li class="nav-item">
                    <a class="{{ Request::url() == url('/college/teacher/student/grades') ? 'active' : '' }} nav-link"
                        href="/college/teacher/student/grades">
                        <i class="nav-icon fas fa-chart-bar text-success"></i>
                        <p>
                            Student Grades <span class="right badge badge-warning" id="pending_grade_count"></span>
                        </p>
                    </a>
                </li> --}}
        
        
                {{-- //////////////////////////////////// --}}
        
                @php
                    $temp_url_grade = [
                        (object) ['url' => url('/college/teacher/student/excelupload')],
                        (object) ['url' => url('/college/teacher/student/grades')],
                        (object) ['url' => url('/college/teacher/student/systemgrading')],
                        // (object) ['url' => url('/student/loading')],
                    ];
                @endphp
                <li
                    class="nav-item has-treeview {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>
                            Grading
                            <i class="fas fa-angle-left right" style="right: 5%;
                                top: 28%;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        
        
          
                    <li class="nav-item">
                        <a class="{{ Request::url() == url('/tesda/trainer/grading') ? 'active' : '' }} nav-link"
                            href="/tesda/trainer/grading">
                            <i
                                class="nav-icon far fa-circle {{ Request::fullUrl() == url('/college/teacher/student/collegesystemgrading') ? 'text-dark' : '' }}"></i>
                            <p>
                                System Grading
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="{{ Request::url() == url('/college/teacher/student/excelupload') ? 'active' : '' }} nav-link"
                            href="/college/teacher/student/excelupload">
                            <i
                                class="nav-icon far fa-circle {{ Request::fullUrl() == url('/college/teacher/student/excelupload') ? 'text-dark' : '' }}"></i>
                            <p>
                                Excel Upload
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="{{ Request::url() == url('/college/teacher/student/grades') ? 'active' : '' }} nav-link"
                            href="/college/teacher/student/grades">
                            <i
                                class="nav-icon far fa-circle {{ Request::fullUrl() == url('/college/teacher/student/grades') ? 'text-dark' : '' }}"></i>
                            <p>
                                Final Grading
                            </p>
                        </a>
                    </li>
                      
                        {{-- @if (($schoolinfo->projectsetup == 'online' && $schoolinfo->processsetup == 'hybrid1') || $schoolinfo->processsetup == 'all')
                            <li class="nav-item">
                                <a href="/college/grade/monitoring/teacher"
                                    class="nav-link {{ Request::fullUrl() == url('/college/grade/monitoring/teacher') ? 'active' : '' }}">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>Student Grades</p>
                                </a>
                            </li>
                        @endif --}}

                    
        
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

</aside>
