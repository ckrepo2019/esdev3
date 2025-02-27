<aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar">
    <!-- Brand Logo -->
    @php
        $getSchoolInfo = DB::table('schoolinfo')
            ->select('region','division','district','schoolname','schoolid')
            ->get();
        $getProgname = DB::table('teacher')
            ->select('teacher.id','sections.levelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname','academicprogram.progname')
            ->join('sections','teacher.id','=','sections.teacherid')
            ->join('gradelevel','sections.levelid','=','gradelevel.id')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('teacher.userid',auth()->user()->id)
            ->get();
        // return $getProgname;
    @endphp
    {{-- <a href="../../index3.html" class="brand-link" style="background-color: #002833">
        <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{$getSchoolInfo[0]->schoolname}}</span>
    </a> --}}
    <div class="ckheader">
        <a href="#" class="brand-link sidehead">
            <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">{{$getSchoolInfo[0]->schoolname}}</span>
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
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block" style="font-size: 80%">{{auth()->user()->name}}<br>{{auth()->user()->email}}</a>
                        </div>
                    </div>
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <!-- Add icons to the links using the .nav-icon class
                            with font-awesome or any other icon font library -->
                            <li class="nav-header text-warning"><h4>TEACHER'S PORTAL</h4></li>
                            <li class="nav-item">
                                <a href="/home"  id="dashboard" class="nav-link {{Request::url() == url('/home') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-th"></i>
                                    <p>
                                        Home
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview {{Request::url() == url('/classAttendance') || Request::url() == url('/beadleAttendance') ? 'menu-open' : ''}}">
                                <a href="#" class="nav-link {{Request::url() == url('/classAttendance') || Request::url() == url('/beadleAttendance') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-chart-pie"></i>
                                    <p>
                                        Attendance
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview ml-3" >
                                    <li class="nav-item">
                                        <a href="/classAttendance" class="nav-link {{Request::url() == url('/classAttendance') ? 'active' : ''}}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Advisory
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/beadleAttendance" class="nav-link {{Request::url() == url('/beadleAttendance') ? 'active' : ''}}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                By Subject
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="/students" class="nav-link {{Request::url() == url('/students') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-users"></i>
                                    <p>
                                            Students
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/grades" class="nav-link {{Request::url() == url('/grades') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-star"></i>
                                    <p>
                                            Grades
                                    </p>
                                </a>
                            </li>
                            <li class="nav-header"></li>
                            {{-- <li class="nav-item">
                                <a href="/announcements" class="nav-link {{Request::url() == url('/announcements') ? 'active' : ''}}">
                                <i class="nav-icon fa fa-envelope"></i>
                                <p>
                                        Announcements
                                </p>
                                </a>
                            </li> --}}
                            <li class="nav-item">
                                @php
                                    $teacher_info = Db::table('teacher')
                                        ->where('teacher.userid',auth()->user()->id)
                                        ->get();
                                        // return $teacher_info;
                                    $announcements = DB::table('announcements')
                                        ->select('announcements.id','announcements.title','announcements.createdby','announcements.created_at','notifications.id as notificationid','notifications.status','teacher.firstname','teacher.middlename','teacher.lastname','teacher.suffix')
                                        ->join('notifications','announcements.id','=','notifications.headerid')
                                        ->join('teacher','announcements.createdby','=','teacher.id')
                                        ->whereIn('announcements.recievertype',['1','2'])
                                        ->where('notifications.recieverid',$teacher_info[0]->id)
                                        ->where('notifications.status','0')
                                        ->get();   
                                @endphp
                                <a href="/mailbox/inbox/{{Crypt::encrypt(auth()->user()->id)}}" class="nav-link {{Request::url() == url('/mailbox/inbox/'.auth()->user()->id.'') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-envelope"></i>
                                    <p>
                                            Mailbox
                                            @if(count($announcements)!=0)
                                            {{-- <i class="fas fa-angle-left right"></i> --}}
                                            <span class="badge badge-warning right">
                                                {{count($announcements)}}
                                            </span>
                                            @endif
                                    </p>
                                </a>
                            </li>
                            @php
                                $checkifExists = Db::table('teacher')
                                    ->join('sections','teacher.id','=','sections.teacherid')
                                    ->where('teacher.userid',auth()->user()->id)
                                    ->get();
                                $countExists = count($checkifExists);
                                    $currentMonth = \Carbon\Carbon::now()->month;
                            @endphp
                            {{-- <li class="nav-header">BEADLE</li> --}}
                            @if($countExists!=0)
                            {{-- <li class="nav-item has-treeview  {{Request::url() == url('/schoolForm_2/show/'.$currentMonth) || Request::url() == url('/schoolForm_4/show') || Request::url() == url('/schoolForm_5/show') || Request::url() == url('/schoolForm_6/show') || Request::url() == url('/form_138')? 'menu-open' : ''}}">
                                <a href="#" class="nav-link {{Request::url() == url('/schoolForm_2/show/'.$currentMonth) || Request::url() == url('/schoolForm_4/show') || Request::url() == url('/schoolForm_5/show') || Request::url() == url('/schoolForm_6/show') || Request::url() == url('/form_138')? 'active' : ''}}"> --}}
                                <li class="nav-header">DOWNLOADABLES</li>
                                <li class="nav-item has-treeview {{Request::url() == url('/schoolForm_2/show/'.$currentMonth) || Request::url() == url('/schoolForm_4/show') || Request::url() == url('/schoolForm_5/show') || Request::url() == url('/schoolForm_6/show') || Request::url() == url('/form_138') || Request::url() == url('/shs_form5a/show')|| Request::url() == url('/shs_form5b/show') ? 'menu-open' : ''}}">
                                    <a href="#" class="nav-link {{Request::url() == url('/schoolForm_2/show/'.$currentMonth) || Request::url() == url('/schoolForm_4/show') || Request::url() == url('/schoolForm_5/show') || Request::url() == url('/schoolForm_6/show') || Request::url() == url('/form_138') || Request::url() == url('/shs_form5a/show')|| Request::url() == url('/shs_form5b/show') ? 'active' : ''}}">
                                        <i class="nav-icon fas fa-chart-pie"></i>
                                        <p>
                                            Reports
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="display: none;">
                                        <li class="nav-item">
                                            <a href="/schoolForm_2/show/{{$currentMonth}}"  id="dashboard" class="nav-link {{Request::url() == url('/schoolForm_2/show/'.$currentMonth.'') ? 'active' : ''}} ">
                                                <i class="nav-icon fa fa-file"></i>
                                                <p>
                                                        SCHOOL FORM 2
                                                </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/schoolForm_4/show"  id="dashboard" class="nav-link {{Request::url() == url('/schoolForm_4/show') ? 'active' : ''}}">
                                                <i class="nav-icon fa fa-file"></i>
                                                <p>
                                                        SCHOOL FORM 4
                                                </p>
                                            </a>
                                        </li>
                                        @if($getProgname[0]->progname=='SENIOR HIGH SCHOOL')
                                            <li class="nav-item has-treeview">
                                                <a href="#" class="nav-link">
                                                <i class="nav-icon fa fa-file"></i>
                                                <p>
                                                    SCHOOL FORM 5
                                                    <i class="right fas fa-angle-left"></i>
                                                </p>
                                                </a>
                                                <ul class="nav nav-treeview ml-3" style="display: none;">
                                                    <li class="nav-item">
                                                        <a href="/shs_form5a/show"  id="dashboard" class="nav-link {{Request::url() == url('/shs_form5a/show') ? 'active' : ''}}">
                                                            <i class="nav-icon fa fa-file"></i>
                                                            <p>
                                                                SCHOOL FORM 5A
                                                            </p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/shs_form5b/show"  id="dashboard" class="nav-link {{Request::url() == url('/shs_form5b/show') ? 'active' : ''}}">
                                                            <i class="nav-icon fa fa-file"></i>
                                                            <p>
                                                                SCHOOL FORM 5B
                                                            </p>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        @else
                                            <li class="nav-item">
                                                <a href="/schoolForm_5/show"  id="dashboard" class="nav-link {{Request::url() == url('/schoolForm_5/show') ? 'active' : ''}}">
                                                    <i class="nav-icon fa fa-file"></i>
                                                    <p>
                                                            SCHOOL FORM 5
                                                    </p>
                                                </a>
                                            </li>
                                        @endif
                                        <li class="nav-item">
                                            <a href="/schoolForm_6/show"  id="dashboard" class="nav-link {{Request::url() == url('/schoolForm_6/show') ? 'active' : ''}}">
                                                <i class="nav-icon fa fa-file"></i>
                                                <p>
                                                        SCHOOL FORM 6
                                                </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/form_138"  id="dashboard" class="nav-link {{Request::url() == url('/form_138') ? 'active' : ''}}">
                                                <i class="nav-icon fa fa-file"></i>
                                                <p>
                                                        SCHOOL FORM 9
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @else
                            @endif
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