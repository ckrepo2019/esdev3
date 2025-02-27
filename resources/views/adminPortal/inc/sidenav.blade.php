<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="ckheader">
        <a href="#" class="brand-link  nav-bg">
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
                style="position: absolute;top: 50%;font-size: 16px!important;color:#ffc107"><b>ADMIN'S PORTAL</b></span>
        </a>
    </div>
    @php
        $schoolinfo = DB::table('schoolinfo')->first();
    @endphp
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            @php
                $randomnum = rand(1, 4);

                // Default avatar with a cache-busting query string
                $avatar = 'assets/images/avatars/unknown.png?random=' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss');

                // Fetch the teacher's picture URL from the database
                $teacher = DB::table('teacher')
                    ->where('userid', auth()->user()->id)
                    ->first();

                if (!empty($teacher) && !empty($teacher->picurl)) {
                    // Replace '.jpg' with '.png' if applicable and add a cache-busting query string
                    $picurl = str_replace('.jpg', '.png', $teacher->picurl) . 
                        '?random=' . \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss');
                } else {
                    // Fallback to default avatar
                    $picurl = $avatar;
                }
            @endphp
            

            <div class="image">
                <img src="{{ file_exists(public_path($picurl)) ? asset($picurl) : asset($avatar) }}"
                    class="img-circle elevation-2" alt="User Image"
                    onerror="this.onerror=null; this.src='{{ asset($avatar) }}'">
            </div>
            <div class="info pt-0">
                <a href="#" class="d-block">{{ strtoupper(auth()->user()->name) }}</a>
                <h6 class="text-white m-0 text-warning">IT</h6>
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
                    <a class="{{ Request::url() == url('/school-calendar') ? 'active' : '' }} nav-link"
                        href="/school-calendar">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            School Calendar
                        </p>
                    </a>
                </li>

                @if (isset(DB::table('schoolinfo')->first()->withschoolfolder))
                    @if (DB::table('schoolinfo')->first()->withschoolfolder == 1)
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/schoolfolderv2/index') ? 'active' : '' }} nav-link"
                                href="/schoolfolderv2/index">
                                <i class="nav-icon fa fa-folder"></i>
                                <p>
                                    @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                        BCT Commons
                                    @else
                                        File Directory
                                    @endif
                                </p>
                            </a>
                        </li>
                    @endif
                @endif



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

                <li class="nav-header text-warning">DOCUMENT TRACKING</li>
                <li class="nav-item">
                    <a href="/documenttracking"
                        class="nav-link {{ Request::url() == url('/documenttracking') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Document Tracking
                        </p>
                    </a>
                </li>

                <li class="nav-header text-warning">Employee Requirements</li>
                <li class="nav-item">
                    <a href="/hr/requirements/employee"
                        class="nav-link {{ Request::fullUrl() === url('/hr/requirements/employee') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-folder-open"></i>
                        <p>
                            My Requirements
                        </p>
                    </a>
                </li>


                <li class="nav-header">SETUP</li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/viewschoolinfo') ? 'active' : '' }} nav-link"
                        href="/viewschoolinfo">
                        <i class="nav-icon fab fa-pushed"></i>
                        <p>
                            School Information
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/setup/payment/options') ? 'active' : '' }} nav-link"
                        href="/setup/payment/options">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Payment Options
                        </p>
                    </a>
                </li>
                @if ($schoolinfo->projectsetup == 'offline' || $schoolinfo->processsetup == 'all')
                    <li class="nav-item">
                        <a class="{{ Request::url() == url('/setup/schoolyear') ? 'active' : '' }} nav-link"
                            href="/setup/schoolyear">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>
                                School Year
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::url() == url('/admission/setup') ? 'active' : '' }}"
                            href="/admission/setup">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>
                                Admission Date Setup
                            </p>
                        </a>
                    </li>
                @endif
                @if ($schoolinfo->projectsetup == 'online' || $schoolinfo->processsetup == 'all')
                    <li class="nav-item">
                        <a class="{{ Request::url() == url('/buildings') ? 'active' : '' }} nav-link"
                            href="/buildings">
                            <i class="nav-icon fa fa-door-open"></i>
                            <p>
                                Buildings
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="{{ Request::url() == url('/rooms') ? 'active' : '' }} nav-link" href="/rooms">
                            <i class="nav-icon fa fa-door-open"></i>
                            <p>
                                Rooms
                            </p>
                        </a>
                    </li>
                @endif
                @if ($schoolinfo->projectsetup == 'offline' || $schoolinfo->processsetup == 'all')
                    <li class="nav-item">
                        <a class="{{ Request::url() == url('/manageaccounts') ? 'active' : '' }} nav-link"
                            href="/manageaccounts">
                            <i class="nav-icon fa fa-users"></i>
                            <p>
                                Faculty and Staff
                            </p>
                        </a>
                    </li>
                @endif


                <li class="nav-item">
                    <a class="{{ Request::url() == url('/teacher/student/credential') ? 'active' : '' }} nav-link"
                        href="/teacher/student/credential">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Student User Account
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="{{ Request::url() == url('/schoolfolderv2/blade') ? 'active' : '' }} nav-link"
                        href="/schoolfolderv2/blade">
                        <i class="nav-icon fa fa-folder"></i>
                        <p>
                            School Folder Setup
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="{{ Request::url() == url('/college/grade/access') ? 'active' : '' }} nav-link"
                        href="/college/grade/access">
                        <i class="nav-icon fas fa-lock"></i>
                        <p>
                            Grade Access
                        </p>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a class="{{Request::url() == url('/student/contactnumber') ? 'active':''}} nav-link" href="/student/contactnumber">
                        <i class="nav-icon fa fa-user"></i>
                        <p>
                            Student Contact Info.
                        </p>
                    </a>
                </li>
				<li class="nav-item">
                    <a class="{{Request::url() == url('/sp/credentials') ? 'active':''}} nav-link" href="/sp/credentials">
                        <i class="nav-icon fa fa-user"></i>
                        <p>
                            Student Credentials
                        </p>
                    </a>
                </li> --}}
                @if ($schoolinfo->projectsetup == 'offline' || $schoolinfo->processsetup == 'all')
                    <li class="nav-header">UTILITY</li>
                    @php
                        $temp_url_grade = [
                            (object) ['url' => url('/adminstudentrfidassign/index'), 'desc' => 'Student Assignment'],
                            (object) ['url' => url('/adminemployeesetup/index'), 'desc' => 'FAS Assignment'],
                        ];
                    @endphp
                    <li
                        class="nav-item has-treeview {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'active' : '' }}">
                            <i class="nav-icon fa fa-credit-card"></i>
                            <p>
                                RFID
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview ">
                            @foreach ($temp_url_grade as $item)
                                <li class="nav-item">
                                    <a href="{{ $item->url }}"
                                        class="nav-link {{ Request::fullUrl() == $item->url ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>{{ $item->desc }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif

                <li class="nav-header text-warning">My Applications</li>
                {{-- <li class="nav-item">
                    <a href="/hr/leaves/index?action=myleave"
                        class="nav-link {{ Request::fullUrl() === url('/hr/leaves/index?action=myleave') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-calendar-alt"></i>
                        <p>
                            Leave Applications
                        </p>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a href="/leaves/apply/index" id="dashboard"
                        class="nav-link {{ Request::url() == url('/leaves/apply/index') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Leave Applications
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
