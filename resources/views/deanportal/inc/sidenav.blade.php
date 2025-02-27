<style>
    @keyframes blink {
        0% { opacity: 1; }
        50% { opacity: 0.5; }  /* Reduce the opacity to make it more subtle */
        100% { opacity: 1; }
    }

    .blink {
        animation: blink 1s ease-in-out infinite;  /* Increase the duration for a smoother effect */
    }
</style>
<aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar ">
    <div class="ckheader">
        <a href="#" class="brand-link nav-bg">
            @if (DB::table('schoolinfo')->first()->picurl != null)
                <img src="{{ asset(DB::table('schoolinfo')->first()->picurl) }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8"
                    onerror="this.src='{{ asset('assets/images/department_of_Education.png') }}'">
            @else
                <img src="{{ asset('assets/images/department_of_Education.png') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
            @endif
            <span class="brand-text font-weight-light" style="position: absolute;top: 6%;">
                {{ DB::table('schoolinfo')->first()->abbreviation }}
            </span>
            <span class="brand-text font-weight-light"
                style="position: absolute;top: 50%;font-size: 16px!important;"><b>DEAN'S PORTAL</b></span>
        </a>
    </div>
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
    <div class="sidebar">
        <div class="row pt-3">
            <div class="col-md-12">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="{{ asset($picurl) }}"
                        onerror="this.onerror=null; this.src='{{ asset($avatar) }}'" alt="User Image" width="100%"
                        style="width:130px; border-radius: 12% !important;">
                </div>
            </div>
        </div>
        <div class="row  user-panel">
            <div class="col-md-12 info text-center">
                <a class=" text-white mb-0 ">{{ auth()->user()->name }}</a>
                <h6 class="text-warning text-center">{{ auth()->user()->email }}</h6>
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
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            School Calendar
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="/user/notification/userview_notifications" class="nav-link">
                        <i class="nav-icon fas fa-exclamation"></i>
                        <p>Notifications</p> &nbsp; 
                        @php
                            $deptid = null;
                            $authid = auth()->user()->id;
                            $userid = DB::table('teacher')
                                ->where('userid', $authid)
                                ->first()->id;
                
                            // Fetch department ID, handle null if not found
                            $dept_userid = DB::table('employee_personalinfo')
                                ->where('employeeid', $userid)
                                ->first();
                            if ($dept_userid) {
                                if ($dept_userid->departmentid == null || $dept_userid->departmentid == '') {
                                    $deptid = null;
                                } else {
                                    $deptid = $dept_userid->departmentid;
                                }
                            }
                            
                            $notifications = DB::table('hr_notifications')
                                ->where('sentrusystem', 1)
                                ->where('deleted', 0)
                                ->get()
                                ->map(function ($notification) use ($userid, $deptid) {
                                    $recipientIds = explode(',', $notification->recipientid);
                                    $acknowledgeIds = explode(',', $notification->acknowledgeby);
                
                                    // Check if the user ID or department ID is in the recipient IDs
                                    $isRecipient = in_array($userid, $recipientIds);
                                    if ($deptid !== null) {
                                        $isRecipient = $isRecipient || in_array($deptid, $recipientIds);
                                    }
                
                                    // Set acknowledge status
                                    $notification->acknowledge_status = in_array($userid, $acknowledgeIds) ? 1 : 0;
                
                                    // Return the notification only if the user or department is a recipient
                                    return $isRecipient ? $notification : null;
                                })
                                ->filter();
                
                            $notacknowledge = count($notifications->where('acknowledge_status', 0));
                        @endphp
                        <span class="badge badge-light {{ $notacknowledge > 0 ? 'blink' : '' }}">{{ $notacknowledge }}</span>
                    </a>
                </li> --}}

                @php
                    $temp_url_grade = [
                        (object) ['url' => url('/college/grade/monitoring/teacher')],
                        (object) ['url' => url('/student/preregistration')],
                        (object) ['url' => url('/student/loading')],
                    ];
                @endphp
                <li
                    class="nav-item has-treeview {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'active' : '' }}">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Student
                            <i class="fas fa-angle-left right" style="right: 5%;
						top: 28%;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">

                        @if ($schoolinfo->projectsetup == 'offline' || $schoolinfo->processsetup == 'all')
                            <li class="nav-item">
                                <a class="{{ Request::fullUrl() == url('/student/preregistration') ? 'active' : '' }} nav-link"
                                    href="/student/preregistration">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Student Information
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="{{ Request::url() == url('/student/loading') ? 'active' : '' }} nav-link"
                                    href="/student/loading">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Student Loading
                                    </p>
                                </a>
                            </li>
                        @endif
                        @if (
                            ($schoolinfo->projectsetup == 'online' && $schoolinfo->processsetup == 'hybrid1') ||
                                $schoolinfo->processsetup == 'all')
                            <li class="nav-item">
                                <a href="/college/grade/monitoring/teacher"
                                    class="nav-link {{ Request::fullUrl() == url('/college/grade/monitoring/teacher') ? 'active' : '' }}">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>Student Grades</p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>

                <li class="nav-item">
                    <a href="/teacher/profile"
                        class="nav-link {{ Request::fullUrl() == url('/teacher/profile') ? 'active' : '' }}">
                        <i class="fas fa-cubes nav-icon"></i>
                        <p>Teaching Loads</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/sadtr" class="nav-link {{ Request::fullUrl() == url('/sadtr') ? 'active' : '' }}">
                        <i class="fa fa-clock nav-icon"></i>
                        <p>Tap Monitoring</p>
                    </a>
                </li>
                @php
                    $temp_url_grade = [
                        (object) ['url' => url('/college/section')],
                        (object) ['url' => url('/setup/prospectus')],
                        (object) ['url' => url('/setup/college')],
                        (object) ['url' => url('/setup/course')],
                    ];
                @endphp
                <li
                    class="nav-item has-treeview {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'active' : '' }}">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            Setup
                            <i class="fas fa-angle-left right" style="right: 5%;
						top: 28%;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        <li class="nav-item">
                            <a href="/setup/college"
                                class="nav-link {{ Request::url() == url('/setup/college') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Colleges
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/setup/course"
                                class="nav-link {{ Request::url() == url('/setup/course') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Courses
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/setup/prospectus') ? 'active' : '' }} nav-link"
                                href="/setup/prospectus">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Prospectus
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/college/section"
                                class="nav-link {{ Request::fullUrl() == url('/college/section') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Sections</p>
                            </a>
                        </li>
                    </ul>
                </li>


                @php
                    $priveledge = DB::table('faspriv')
                        ->join('usertype', 'faspriv.usertype', '=', 'usertype.id')
                        ->select('faspriv.*', 'usertype.utype')
                        ->where('userid', auth()->user()->id)
                        ->where('faspriv.deleted', '0')
                        ->where('faspriv.privelege', '!=', '0')
                        ->get();

                    $usertype = DB::table('usertype')
                        ->where('deleted', 0)
                        ->where('id', auth()->user()->type)
                        ->first();

                @endphp

                <li class="nav-header " {{ count($priveledge) > 0 ? '' : 'hidden' }}>OTHER PORTAL</li>

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

                {{-- <li class="nav-header ">HR</li>
                <li class="nav-item">
                    <a href="/leaves/apply/index"  id="dashboard" class="nav-link {{Request::url() == url('/leaves/apply/index') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Apply Leave
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/overtime/apply/index"  id="dashboard" class="nav-link {{Request::url() == url('/overtime/apply/index') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Apply Overtime
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/employeedailytimerecord/dashboard" class="nav-link {{Request::url() == url('/employeedailytimerecord/dashboard') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Daily Time Record
                        </p>
                    </a>
                </li>
             --}}

                <li class="nav-header">DOCUMENT TRACKING</li>
                <li class="nav-item">
                    <a href="/documenttracking"
                        class="nav-link {{ Request::url() == url('/documenttracking') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Document Tracking
                        </p>
                    </a>
                </li>

                <li class="nav-header"> Notification & Request</li>

                <li class="nav-item">
                    <a href="/blade/notification" 
                        class="nav-link {{Request::url() == url('/blade/notification') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-user"></i>
                        <p>
                            Notification
                            <span class="ml-2 badge badge-primary">2</span>
                        </p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
    {{-- <li class="nav-item">
    <a class="nav-link" href="/admingetrooms">
        <img class="essentiellogo" src="{{asset('assets\images\essentiel.png')}}" alt="">
    </a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="/admingetrooms">
        <img class="cklogo" src="{{asset('assets\images\CK_Logo.png')}}" alt="">
    </a>
    </li> --}}
</aside>
