@php

    $syid = DB::table('sy')->where('isactive', 1)->first()->id;

    $teacherid = DB::table('teacher')
        ->where('tid', auth()->user()->email)
        ->select('id')
        ->first()->id;

    $teacheradprogid = DB::table('teacheracadprog')
        ->where('teacherid', $teacherid)
        ->where('syid', $syid)
        ->whereIn('acadprogutype', [3, 8])
        ->where('deleted', 0)
        ->get();

    $isjs = collect($teacheradprogid)->where('acadprogid', 4)->count() > 0 ? true : false;
    $issh = collect($teacheradprogid)->where('acadprogid', 5)->count() > 0 ? true : false;
    $iscollege = collect($teacheradprogid)->where('acadprogid', 6)->count() > 0 ? true : false;
    $isgs = collect($teacheradprogid)->where('acadprogid', 3)->count() > 0 ? true : false;
    $isps = collect($teacheradprogid)->where('acadprogid', 3)->count() > 0 ? true : false;

    $activeacadprogs = DB::table('gradelevel')->select('acadprogid')->where('deleted', '0')->get();

@endphp
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
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    @php
        $getSchoolInfo = DB::table('schoolinfo')->select('schoolname', 'projectsetup')->get();
    @endphp
    <a href="#" class="brand-link  nav-bg">
        <img src="{{ asset(DB::table('schoolinfo')->first()->picurl) }}?" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"
            style="position: absolute;top: 6%;">{{ DB::table('schoolinfo')->first()->abbreviation }}</span>

        @php
            $utype = db::table('usertype')->where('id', 3)->first()->utype;

        @endphp

        <span class="brand-text font-weight-light"
            style="position: absolute;top: 50%;font-size: 16px!important;"><b>{{ $utype }}'S PORTAL</b></span>
    </a>

    @php
        $registrar_profile = Db::table('teacher')
            ->select(
                'teacher.id',
                'teacher.lastname',
                'teacher.middlename',
                'teacher.firstname',
                'teacher.suffix',
                'teacher.licno',
                'teacher.tid',
                'teacher.deleted',
                'teacher.isactive',
                'teacher.picurl',
                'usertype.utype'
            )
            ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('teacher.userid', auth()->user()->id)
            ->first();

        $registrar_info = Db::table('employee_personalinfo')
            ->select(
                'employee_personalinfo.id as employee_personalinfoid',
                'employee_personalinfo.nationalityid',
                'employee_personalinfo.religionid',
                'employee_personalinfo.dob',
                'employee_personalinfo.gender',
                'employee_personalinfo.address',
                'employee_personalinfo.contactnum',
                'employee_personalinfo.email',
                'employee_personalinfo.maritalstatusid',
                'employee_personalinfo.spouseemployment',
                'employee_personalinfo.numberofchildren',
                'employee_personalinfo.emercontactname',
                'employee_personalinfo.emercontactrelation',
                'employee_personalinfo.emercontactnum',
                'employee_personalinfo.departmentid',
                'employee_personalinfo.designationid',
                'employee_personalinfo.date_joined'
            )
            ->where('employee_personalinfo.employeeid', $registrar_profile->id)
            ->get();
        $number = rand(1, 3);
        if (count($registrar_info) == 0) {
            $avatar = 'assets/images/avatars/unknown.png';
        } else {
            if (strtoupper($registrar_info[0]->gender) == 'FEMALE') {
                $avatar = 'avatar/T(F) ' . $number . '.png';
            } else {
                $avatar = 'avatar/T(M) ' . $number . '.png';
            }
        }
        $basicedacadprogs = DB::table('academicprogram')->where('id', '<', 6)->get();
        // echo
    @endphp
    <div class="sidebar">

        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    @php
                        $randomnum = rand(1, 4);
                        $avatar = 'assets/images/avatars/unknown.png'.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss').'"';
                        $picurl = DB::table('teacher')->where('userid',auth()->user()->id)->first()->picurl;
                        $picurl = str_replace('jpg','png',$picurl).'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss').'"';
                    @endphp
                    <img class="profile-user-img img-fluid img-circle" src="{{asset($picurl)}}"
                        onerror="this.onerror = null, this.src='{{ asset($avatar) }}'" alt="User Image" width="100%"
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
                {{-- <li class="nav-header text-warning"><h4>{{$utype}}'S PORTAL</h4></li> --}}
                <li class="nav-item">
                    <a href="/home" id="dashboard"
                        class="nav-link {{ Request::url() == url('/home') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-th"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                @if (isset(DB::table('schoolinfo')->first()->withschoolfolder))
                    @if (DB::table('schoolinfo')->first()->withschoolfolder == 1)
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/schoolfolderv2/index') ? 'active' : '' }} nav-link"
                                href="/schoolfolderv2/index">
                                <i class="nav-icon fa fa-calendar"></i>
                                <p>
                                    @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                        BCT Commons
                                    @else
                                        Doc Con
                                    @endif
                                </p>
                            </a>
                        </li>
                    @endif
                @endif
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
                    <a class="{{ Request::url() == url('/school-calendar') ? 'active' : '' }} nav-link"
                        href="/school-calendar">
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
                    $countapproval = DB::table('hr_leaveemployeesappr')
                        ->where('appuserid', auth()->user()->id)
                        ->where('deleted', '0')
                        ->count();
                    // $countapproval = DB::table('hr_leavesappr')
                    //     ->where('employeeid', $hr_profile->id)
                    //     ->where('deleted','0')
                    //     ->count();
                @endphp

                @if ($countapproval > 0)
                    <li class="nav-item">
                        <a href="/hr/leaves/index"
                            class="nav-link {{ Request::url() == url('/hr/leaves/index') ? 'active' : '' }}">
                            <i class="fa fa-file-contract nav-icon"></i>
                            <p>
                                Filed Leaves
                            </p>
                        </a>
                    </li>
                @endif

                @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait' ||
                        strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lchsi')
                    <li
                        class="nav-item has-treeview {{ Request::fullUrl() == url('/administrator/schoolfolders') || Request::fullUrl() == url('/administrator/schoolfolders') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>
                                INTRANET
                                <i class="fas fa-angle-left right"
                                    style="right: 5%;
                                    top: 28%;"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
                            <li class="nav-item">
                                <a class="{{ Request::url() == url('/administrator/schoolfolders') ? 'active' : '' }} nav-link"
                                    href="/administrator/schoolfolders">
                                    <i class="nav-icon fa fa-calendar"></i>
                                    <p>
                                        Doc Con
                                    </p>
                                </a>
                            </li>
                        </ul>

                    </li>
                @endif

                <li class="nav-header">STUDENTS</li>
                <li
                    class="nav-item has-treeview
                                {{ Request::Is('student/promotion') ||
                                Request::Is('student/requirements') ||
                                Request::Is('student/loading') ||
                                Request::Is('/student/contactnumber') ||
                                Request::Is('student/medinfo') ||
                                Request::Is('registrar/studentmanagement') ||
                                Request::Is('student/quarter') ||
                                Request::url() == url('/student/preregistration')
                                    ? 'menu-open'
                                    : '' }}
                                {{ Request::Is('registrar/leaf') ? 'menu-open' : '' }}
                                {{ Request::Is('student/requirements') ? 'menu-open' : '' }}
                                {{ Request::Is('student/loading') ? 'menu-open' : '' }}
								{{ Request::Is('student/promotion') ? 'menu-open' : '' }}
								{{ Request::Is('student/requirements') ? 'menu-open' : '' }}
								{{ Request::Is('student/contactnumber') ? 'menu-open' : '' }}
                                {{ Request::Is('student/quarter') ? 'menu-open' : '' }}
                            ">
                    <a href="#"
                        class="nav-link  {{ Request::url() == url('/student/preregistration') || Request::url() == url('/student/quarter')
                            ? 'active'
                            : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>
                            Student Management
                            <i class="fas fa-angle-left right" style="right: 5%; top: 28%;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        <li class="nav-item" hidden>
                            <a href="/registrar/studentmanagement?sstatus=1"
                                class="nav-link {{ Request::url() == url('/registrar/studentmanagement') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Student Information
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/student/preregistration') ? 'active' : '' }} nav-link"
                                href="/student/preregistration">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Student Enrollment
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/registrar/leaf"
                                class="nav-link {{ Request::url() == url('/registrar/leaf') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    LEASF
                                </p>
                            </a>
                        </li>
                        <li class="nav-item" hidden>
                            <a class="{{ Request::url() == url('/student/medinfo') ? 'active' : '' }} nav-link"
                                href="/student/medinfo">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Medical Information
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/student/requirements"
                                class="nav-link {{ Request::url() == url('/student/requirements') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Student Requirements
                                </p>
                            </a>
                        </li>

                        @if ($iscollege)
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
                        <li class="nav-item">
                            <a class="{{ Request::fullUrl() == url('/student/contactnumber') ? 'active' : '' }} nav-link"
                                href="/student/contactnumber">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Contact Information
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/student/promotion') ? 'active' : '' }} nav-link"
                                href="/student/promotion">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Student Promotion
                                </p>
                            </a>
                        </li>
                        {{-- @if ($issh || $isjs || $isgs || $isps)
										<li class="nav-item">
											<a class="{{Request::url() == url('/student/quarter') ? 'active':''}} nav-link" href="/student/quarter">
												<i class="nav-icon far fa-circle"></i>
												<p>
													Student Grade Quarter
												</p>
											</a>
										</li>    
									@endif --}}
                    </ul>
                </li>

                {{-- @if ($issh || $isjs || $isgs || $isps)
								
								@if ($isjs)
									<li class="nav-item">
										<a href="/student/specialization" class="nav-link {{Request::url() == url('/student/specialization') ? 'active' : ''}}">
											<i class="nav-icon fas fa-user-graduate"></i>
										<p>
											TLE Specialization
										</p>
										</a>
									</li>
								@endif
								<li class="nav-item">
									<a class="{{Request::fullUrl() == url('/transferedin/grades') ? 'active':''}} nav-link" href="/transferedin/grades">
										<i class="nav-icon fas fa-user-graduate"></i>
										<p>
											Transferred In Grades
										</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="/basiced/student/specialclass" class="nav-link {{Request::url() == url('/basiced/student/specialclass') ? 'active' : ''}}">
										<i class="nav-icon fas fa-user-graduate"></i>
									<p>
										Special/Remedial Class 
									</p>
									</a>
								</li>
								<li class="nav-item">
									<a class="{{Request::fullUrl() == url('/basiced/student/excludedsubj') ? 'active':''}} nav-link" href="/basiced/student/excludedsubj">
										<i class="nav-icon fas fa-user-graduate"></i>
										<p>
											Excluded Subject
										</p>
									</a>
								</li>
								
							@endif --}}
                @php
                    $temp_url_grade = [
                        (object) ['url' => url('/setup/subject')],
                        (object) ['url' => url('/setup/subject/plot')],
                        (object) ['url' => url('/grade/preschool/setup')],
                        (object) ['url' => url('/grade/prekinder/setup')],
                        (object) ['url' => url('/setup/attendance')],
                        (object) ['url' => url('/setup/observed/values')],
                        (object) ['url' => url('/student/specialization')],
                        (object) ['url' => url('/basiced/student/specialclas')],
                        (object) ['url' => url('/basiced/student/excludedsubj')],
                        (object) ['url' => url('/transferedin/grades')],
                        (object) ['url' => url('/student/quarter')],
                    ];
                @endphp

                <li
                    class="nav-item has-treeview {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'active' : '' }}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Report Card
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right">{{ count($temp_url_grade) }}</span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::url() == url('/setup/subject') ? 'active' : '' }}"
                                href="/setup/subject">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Subjects
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::url() == url('/setup/subject/plot') ? 'active' : '' }}"
                                href="/setup/subject/plot">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Subject Plot
                                </p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
									<a class="nav-link" href="/grade/preschool/setup">
										<i class="nav-icon far fa-circle"></i>
										<p>
											Pre-school Checklist
										</p>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="/grade/prekinder/setup">
										<i class="nav-icon far fa-circle"></i>
										<p>
											Pre-Kinder Checklist
										</p>
									</a>
								</li> --}}
                        <li class="nav-item">
                            <a class="nav-link" href="/setup/attendance">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    School Days
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/setup/observed/values">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Observed Values
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ Request::fullUrl() == url('/student/specialization') ? 'active' : '' }} nav-link"
                                href="/student/specialization">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Subject Specialization
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ Request::fullUrl() == url('/basiced/student/specialclass') ? 'active' : '' }} nav-link"
                                href="/basiced/student/specialclass">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Remedial Subject
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ Request::fullUrl() == url('/basiced/student/excludedsubj') ? 'active' : '' }} nav-link"
                                href="/basiced/student/excludedsubj">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Excluded Subject
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ Request::fullUrl() == url('/transferedin/grades') ? 'active' : '' }} nav-link"
                                href="/transferedin/grades">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Transferred In Grades
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ Request::fullUrl() == url('/student/quarter') ? 'active' : '' }} nav-link"
                                href="/student/quarter">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Student Quarter
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <li class="nav-item has-treeview {{Request::url() == url('/registrar/enrolled') || Request::url() == url('/registrar/registered') || Request::url() == url('/registrar/oe') || Request::url() == url('/registrar/studentinfo') ? 'menu-open' : ''}}">
                                <a href="#"class="nav-link pr-1 {{Request::url() == url('/registrar/enrolled') || Request::url() == url('/registrar/registered') || Request::url() == url('/registrar/oe') || Request::url() == url('/registrar/studentinfo')  ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p>
                                       <span style="font-size: 15px;">STUDENT MANAGEMENT</span>
                                       <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    <li class="nav-item">
                                        <a href="/registrar/studentmanagement" class="nav-link {{Request::url() == url('/registrar/studentmanagement') ? 'active' : ''}}">
                                        <i class="nav-icon fas fa-user-graduate"></i>
                                        <p>
                                            Student Information
                                        </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/registrar/enrolled" class="nav-link {{Request::url() == url('/registrar/enrolled') ? 'active' : ''}}">
                                          <i class="nav-icon fas fa-user-graduate"></i>
                                        <p>
                                            Enrolled Students
                                        </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/registrar/registered" class="nav-link {{Request::url() == url('/registrar/registered') ? 'active' : ''}}">
                                          <i class="nav-icon fas fa-user-graduate"></i>
                                        <p>
                                            Students For Enrollment
                                        </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-user-graduate"></i>
                                        <p>Online Registered Students</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/registrar/oe?syid={{DB::table('sy')->where('isactive','1')->first()->id}}&semid={{DB::table('semester')->where('isactive','1')->first()->id}}" class="nav-link {{Request::url() == url('/registrar/oe') ? 'active' : ''}}">
                                        <i class="nav-icon fas fa-user-graduate"></i>
                                        <p>Online Enrolled Students</p>
                                        </a>
                                    </li>
									<li class="nav-item 1" >
										<a href="/registrar/leaf" class="nav-link {{Request::url() == url('/registrar/leaf') ? 'active' : ''}}">
											<i class="nav-icon fas fa-user-graduate"></i>
										<p>
											LEASF
										</p>
										</a>
									</li>
									
									<li class="nav-item 1" >
										<a href="/registrar/studentrequirements" class="nav-link {{Request::url() == url('/registrar/studentrequirements') ? 'active' : ''}}">
										    <i class="nav-icon fas fa-user-graduate"></i>
										<p>
											Student Requirements 
										</p>
										</a>
									</li>
									<li class="nav-item 1" >
										<a class="{{Request::url() == url('/student/promotion') ? 'active':''}} nav-link" href="/student/promotion">
											<i class="nav-icon fas fa-user-graduate"></i>
											<p>
												Student Promotion
											</p>
										</a>
									</li>
									
										<li class="nav-item 1" >
											<a href="/basiced/student/specialclass" class="nav-link {{Request::url() == url('/basiced/student/specialclass') ? 'active' : ''}}">
												<i class="nav-icon fas fa-user-graduate"></i>
											<p>
												Student Special Class 
											</p>
											</a>
										</li>
										@if (!$iscollege)
											
											<li class="nav-item 1" >
												<a href="/student/specialization" class="nav-link {{Request::url() == url('/student/specialization') ? 'active' : ''}}">
													<i class="nav-icon fas fa-user-graduate"></i>
												<p>
													TLE Specialization
												</p>
												</a>
											</li>
											<li class="nav-item 1" >
												<a class="{{Request::fullUrl() == url('/transferedin/grades') ? 'active':''}} nav-link" href="/transferedin/grades">
													<i class="nav-icon far fa-circle"></i>
													<p>
														Transfered In Grades
													</p>
												</a>
											</li>
										@endif

									
                                </ul>
                            </li> --}}

                <li
                    class="nav-item has-treeview {{ Request::url() == url('/techvocv2/courses') || Request::url() == url('/techvocv2/enrollment') ? 'menu-open' : '' }}">
                    <a
                        href="#"class="nav-link pr-1 {{ Request::url() == url('/techvocv2/courses') || Request::url() == url('/techvocv2/enrollment') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            <span style="font-size: 15px;">TECH-VOC</span>
                            <i class="fas fa-angle-left right" style="right: 5%; top: 28%;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        <li class="nav-item">
                            <a href="/techvocv2/courses"
                                class="nav-link {{ Request::url() == url('/techvocv2/courses') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-microscope"></i>
                                <p>
                                    Courses & Batching
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/techvocv2/enrollment"
                                class="nav-link {{ Request::url() == url('/techvocv2/enrollment') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Enrollment
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">SETUP</li>


                @if ($iscollege)
                    <li
                        class="nav-item has-treeview {{ Request::fullUrl() == url('/college/section') || Request::fullUrl() == url('/setup/prospectus') || Request::fullUrl() == url('/setup/college') || Request::fullUrl() == url('/setup/course') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ Request::fullUrl() == url('/college/section') || Request::fullUrl() == url('/setup/prospectus') || Request::fullUrl() == url('/setup/college') || Request::fullUrl() == url('/setup/course') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>
                                College
                                <i class="fas fa-angle-left right" style="right: 5%;
									top: 28%;"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
                            <li class="nav-item">
                                <a href="/setup/college"
                                    class="nav-link {{ Request::url() == url('/setup/college') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Colleges
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/setup/course"
                                    class="nav-link {{ Request::url() == url('/setup/course') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Courses
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="{{ Request::url() == url('/setup/prospectus') ? 'active' : '' }} nav-link"
                                    href="/setup/prospectus">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Prospectus
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/college/section"
                                    class="nav-link {{ Request::url() == url('/college/section') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>College Sections</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="{{ Request::url() == url('/college/grade/access') ? 'active' : '' }} nav-link"
                                    href="/college/grade/access">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Grade Access
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item"> {{-- sbc --}}
                                <a href="/setup/subjgrouping"
                                    class="nav-link {{ Request::url() == url('/setup/subjgrouping') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Subject Groupings
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if ($issh || $isjs || $isgs || $isps)
                    @php
                        $temp_url_grade = [
                            (object) ['url' => url('/setup/sections')],
                            (object) ['url' => url('/setup/attendance')],
                            (object) ['url' => url('/setup/subject')],
                            (object) ['url' => url('/setup/subject/plot')],
                            (object) ['url' => url('/setup/track')],
                            (object) ['url' => url('/setup/strand')],
                            (object) ['url' => url('/setup/student/clearance/monitoring')],
                            (object) ['url' => url('/setup/student/clearance/approval')],
                            (object) ['url' => url('/setup/student/clearance/signatories')],
                            (object) ['url' => url('/setup/acadterm')],
                        ];
                    @endphp
                    <li
                        class="nav-item has-treeview {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'active' : '' }}">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>
                                Basic Ed.
                                <i class="fas fa-angle-left right" style="right: 5%; top: 28%;"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
                            @if ($issh)
                                <li
                                    class="nav-item has-treeview {{ Request::fullUrl() == url('/setup/track') || Request::fullUrl() == url('/setup/strand') ? 'menu-open' : '' }}">
                                    <a href="#"
                                        class="nav-link {{ Request::fullUrl() == url('/setup/track') || Request::fullUrl() == url('/setup/strand') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Senior High
                                            <i class="fas fa-angle-left right"
                                                style="right: 5%;
												top: 28%;"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview udernavs">
                                        <li class="nav-item">
                                            <a href="/setup/track"
                                                class="nav-link {{ Request::url() == url('/setup/track') ? 'active' : '' }}">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>
                                                    SHS Track Name
                                                </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/setup/strand"
                                                class="nav-link {{ Request::url() == url('/setup/strand') ? 'active' : '' }}">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>
                                                    Strand
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            <li
                                class="1 nav-item has-treeview {{ Request::fullUrl() == url('/setup/subject') || Request::fullUrl() == url('/setup/subject/plot') ? 'menu-open' : '' }}">
                                <a href="#"
                                    class="nav-link {{ Request::fullUrl() == url('/setup/subject') || Request::fullUrl() == url('/setup/subject/plot') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Subject Setup
                                        <i class="fas fa-angle-left right"
                                            style="right: 5%;
												top: 28%;"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    <li class="nav-item">
                                        <a class="nav-link {{ Request::url() == url('/setup/subject') ? 'active' : '' }}"
                                            href="/setup/subject">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Add Subject
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Request::url() == url('/setup/subject/plot') ? 'active' : '' }}"
                                            href="/setup/subject/plot">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Plot Subject
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item 1">
                                <a class="{{ Request::url() == url('/setup/sections') ? 'active' : '' }} nav-link"
                                    href="/setup/sections">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Sections
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item 1">
                                <a class="nav-link {{ Request::url() == url('/setup/attendance') ? 'active' : '' }}"
                                    href="/setup/attendance">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        School Days
                                    </p>
                                </a>
                            </li>
                            <li
                                class="1 nav-item has-treeview {{ Request::fullUrl() == url('/setup/acadterm') || Request::fullUrl() == url('/setup/student/clearance/signatories') || Request::fullUrl() == url('/setup/student/clearance/monitoring') || Request::fullUrl() == url('/setup/student/clearance/approval') ? 'menu-open' : '' }}">
                                <a href="#"
                                    class="nav-link {{ Request::fullUrl() == url('/setup/acadterm') || Request::fullUrl() == url('/setup/student/clearance/signatories') || Request::fullUrl() == url('/setup/student/clearance/monitoring') || Request::fullUrl() == url('/setup/student/clearance/approval') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Clearance
                                        <i class="fas fa-angle-left right"
                                            style="right: 5%;
                                            top: 28%;"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    <li class="nav-item">
                                        <a class="{{ Request::url() == url('/setup/acadterm') ? 'active' : '' }} nav-link"
                                            href="/setup/acadterm">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Academic Term
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Request::url() == url('/setup/student/clearance/signatories') ? 'active' : '' }}"
                                            href="/setup/student/clearance/signatories">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Signatories
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Request::url() == url('/setup/student/clearance/monitoring') ? 'active' : '' }}"
                                            href="/setup/student/clearance/monitoring">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Monitoring
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Request::url() == url('/setup/student/clearance/approval') ? 'active' : '' }}"
                                            href="/setup/student/clearance/approval">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Approval
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </li>


                @endif

                <li
                    class="nav-item has-treeview {{ Request::fullUrl() == url('/admission/setup') || Request::fullUrl() == url('/setup/modeoflearning') || Request::fullUrl() == url('/superadmin/enrollmentsetup') || Request::fullUrl() == url('/setup/signatories') || Request::fullUrl() == url('/setup/signatories') || Request::fullUrl() == url('/setup/schoolyear') || Request::fullUrl() == url('/setup/document') || Request::url() == url('/setup/admissiondate') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ Request::fullUrl() == url('/admission/setup') || Request::fullUrl() == url('/setup/modeoflearning') || Request::fullUrl() == url('/superadmin/enrollmentsetup') || Request::fullUrl() == url('/setup/signatories') || Request::fullUrl() == url('/setup/schoolyear') || Request::fullUrl() == url('/setup/document') || Request::url() == url('/setup/admissiondate') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            School Configuration
                            <i class="fas fa-angle-left right" style="right: 5%;
								top: 28%;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        <li class="nav-item 1">
                            <a class="{{ Request::url() == url('/setup/schoolyear') ? 'active' : '' }} nav-link"
                                href="/setup/schoolyear">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    School Year
                                </p>
                            </a>
                        </li>
                        <li class="nav-item 1">
                            <a class="{{ Request::url() == url('/admission/setup') ? 'active' : '' }} nav-link"
                                href="/admission/setup">
                                <i class="nav-icon fas fa-layer-group"></i>
                                <p>
                                    Admission Date Setup
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/setup/modeoflearning') ? 'active' : '' }} nav-link"
                                href="/setup/modeoflearning">
                                <i class="nav-icon  far fa-circle"></i>
                                <p>
                                    Mode of Learning
                                </p>
                            </a>
                        </li>
                        <li class="nav-item 1">
                            <a class="nav-link {{ Request::url() == url('/setup/document') ? 'active' : '' }}"
                                href="/setup/document">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Docum. Requirements
                                </p>
                            </a>
                        </li>
                        @if ($issh || $isjs || $isgs || $isps)
                            <li class="nav-item">
                                <a class="nav-link {{ Request::url() == url('/setup/signatories') ? 'active' : '' }}"
                                    href="/setup/signatories">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        School Form Signatories
                                    </p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>


                <li class="nav-header ">REPORTS</li>
                <li
                    class="nav-item has-treeview {{ Request::url() == url('/reportssummariesallstudentsnew/dashboard') || Request::url() == url('/registrar/reports/notenrolled') || Request::url() == url('/registrar/studentlist') || Request::url() == url('/printable/numofstudents/index') || Request::url() == url('/printable/studentvacc/index') ? 'menu-open' : '' }}">
                    <a
                        href="#"class="nav-link {{ Request::url() == url('/reportssummariesallstudentsnew/dashboard') || Request::url() == url('/registrar/reports/notenrolled') || Request::url() == url('/registrar/studentlist') || Request::url() == url('/printable/numofstudents/index') || Request::url() == url('/printable/studentvacc/index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-caret-right"></i>
                        <p>
                            Enrollment Statistics
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        <li class="nav-item">
                            <a href="/reportssummariesallstudentsnew/dashboard"
                                class="nav-link {{ Request::url() == url('/reportssummariesallstudentsnew/dashboard') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                                    <p>Enrolled Student List</p>
                                @else
                                    <p>Students Summary</p>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/registrar/reports/notenrolled"
                                class="nav-link {{ Request::url() == url('/registrar/reports/notenrolled') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Not Enrolled Students</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/registrar/studentlist"
                                class="nav-link {{ Request::url() == url('/registrar/studentlist') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                                    <p>
                                        Enrollment Statistics
                                    </p>
                                @else
                                    <p>
                                        Student List & Enrollment<br />Summary
                                    </p>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/printable/numofstudents/index"
                                class="nav-link {{ Request::url() == url('/printable/numofstudents/index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                {{-- <p>Number of Students</p> --}}
                                <p>Population Summary</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/printable/studentvacc/index"
                                class="nav-link {{ Request::url() == url('/printable/studentvacc/index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Students Vaccination<br />Statistics</p>{{-- apmc --}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/registrar/reports/monthly"
                                class="nav-link {{ Request::url() == url('/registrar/reports/monthly') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Monthly Enrollment Statistics</p>{{-- gbbc --}}
                            </a>
                        </li>
                        @if ($iscollege)
                            <li class="nav-item">
                                <a href="/registrar/summaries/alphaloading/index"
                                    class="nav-link {{ Request::url() == url('/registrar/summaries/alphaloading/index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Alpha Loading</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @if ($issh || $isjs || $isgs || $isps)
                    <li class="nav-item has-treeview">
                        <a href="#"class="nav-link">
                            <i class="nav-icon fas fa-caret-right"></i>
                            <p>
                                IBED Grades
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
                            <li class="nav-item">
                                <a href="/posting/grade" id="dashboard"
                                    class="nav-link {{ Request::url() == url('/posting/grade') ? 'active' : '' }}">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Grade Summary
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/registrar/student/awards" id="dashboard"
                                    class="nav-link {{ Request::url() == url('/registrar/student/awards') ? 'active' : '' }}">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Student Awards
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li
                    class="nav-item has-treeview {{ Request::url() == url('/college/completiongrades') || Request::url() == url('/student/cor/printing') || Request::url() == url('/printable/certification/index') || Request::url() == url('/printable/cor') || Request::url() == url('/printable/gwaranking') || Request::url() == url('/printable/coranking') || Request::url() == url('/printable/studentacademicrecord') || Request::url() == url('/printable/masterlist') || Request::url() == url('/printable/othercertification/index') ? 'menu-open' : '' }}">
                    <a
                        href="#"class="nav-link {{ Request::url() == url('/college/completiongrades') || Request::url() == url('/student/cor/printing') || Request::url() == url('/printable/certification/index') || Request::url() == url('/printable/cor') || Request::url() == url('/printable/gwaranking') || Request::url() == url('/printable/coranking') || Request::url() == url('/printable/studentacademicrecord') || Request::url() == url('/printable/masterlist') || Request::url() == url('/printable/othercertification/index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-caret-right"></i>
                        <p>
                            Printables
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        <li
                            class="nav-item has-treeview {{ Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=1' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=2' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=3' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=4' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=5' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=6' ? 'menu-open' : '' }}">
                            <a
                                href="#"class="nav-link {{ Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=1' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=2' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=3' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=4' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=5' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=6' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file"></i>
                                <p>
                                    Masterlist
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul
                                class="nav nav-treeview udernavs {{ Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=1' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=2' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=3' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=4' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=5' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=6' ? 'menu-open' : '' }}">
                                @foreach ($basicedacadprogs as $eachacadprog)
                                    @if (collect($activeacadprogs)->where('acadprogid', $eachacadprog->id)->count() > 0)
                                        <li class="nav-item">
                                            <a href="/printable/masterlist?sf=0&acadprogid={{ $eachacadprog->id }}"
                                                class="nav-link {{ Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=' . $eachacadprog->id ? 'active' : '' }}">
                                                <i class="nav-icon  far fa-circle"></i>
                                                <p>
                                                    {{ $eachacadprog->progname }}
                                                </p>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="/printable/certifications"
                                class="nav-link {{ Request::getRequestUri() == '/printable/certifications' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Certifications</p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                                        <a href="/printable/certification/index" class="nav-link {{Request::getRequestUri() == '/printable/certification/index' ? 'active' : ''}}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Certificate of Enrollment</p>
                                        </a>
                                    </li> --}}
                        @if ($iscollege)
                            <li class="nav-item">
                                <a class="{{ Request::fullUrl() == url('/student/cor/printing') ? 'active' : '' }} nav-link"
                                    href="/student/cor/printing">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        COR Printing
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="{{ Request::fullUrl() == url('/college/completiongrades') ? 'active' : '' }} nav-link"
                                    href="/college/completiongrades">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Grade Completion
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="/printable/gwaranking"
                                    class="nav-link {{ Request::url() == url('/printable/gwaranking') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>GWA Ranking</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/printable/coranking"
                                    class="nav-link {{ Request::url() == url('/printable/coranking') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Certification of Ranking</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/printable/studentacademicrecord"
                                    class="nav-link {{ Request::url() == url('/printable/studentacademicrecord') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Student Academic Record</p>
                                </a>
                            </li>
                        @endif
                        {{-- <li class="nav-item">
                                        <a href="/printable/certification/index?type=goodmoral" class="nav-link {{Request::getRequestUri() == '/printable/certification/index?type=goodmoral' ? 'active' : ''}}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Certificate of Good Moral</p>
                                        </a>
                                    </li> --}}
                        @if ($iscollege)
                            <li class="nav-item" hidden>
                                <a href="/printable/certification/index?type=graduation"
                                    class="nav-link {{ Request::getRequestUri() == '/printable/certification/index?type=graduation' ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Certification of Graduation</p> {{-- sbc --}}
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="nav-header ">FORMS</li>

                @if ($issh || $isjs || $isgs || $isps)
                    <li
                        class="nav-item has-treeview {{ Request::url() == url('/registar/schoolforms/index') || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=1' || Request::getRequestUri() == '/reports_schoolform4/dashboard' || Request::getRequestUri() == '/registar/schoolforms/index?sf=4' || Request::getRequestUri() == '/reports_schoolform6/dashboard' || Request::getRequestUri() == '/schoolform10/index' ? 'menu-open' : '' }}">
                        <a
                            href="#"class="nav-link {{ Request::url() == url('/registar/schoolforms/index') || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=1' || Request::getRequestUri() == '/reports_schoolform4/dashboard' || Request::getRequestUri() == '/registar/schoolforms/index?sf=4' || Request::getRequestUri() == '/reports_schoolform6/dashboard' || Request::getRequestUri() == '/schoolform10/index' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-caret-right"></i>
                            <p>
                                Basic Ed School Forms
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
                            <li
                                class="nav-item has-treeview {{ Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=5' ? 'menu-open' : '' }}">
                                <a
                                    href="#"class="nav-link {{ Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=5' ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        SF 1
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    @foreach (collect($basicedacadprogs)->where('id', '!=', 6) as $eachacadprog)
@if (collect($activeacadprogs)->where('acadprogid', $eachacadprog->id)->count() > 0)
<li class="nav-item">
                                                    <a href="/registar/schoolforms/index?sf=1&acadprogid={{ $eachacadprog->id }}" class="nav-link {{ Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=' . $eachacadprog->id ? 'active' : '' }}">
                                                    <i class="nav-icon  far fa-circle"></i>
                                                    <p>
                                                        {{ $eachacadprog->progname }}
                                                    </p>
                                                    </a>
                                                </li>
@endif
@endforeach
                                        </ul>
                                    </li>
                                    <li class="nav-item has-treeview {{ Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=5' || Request::getRequestUri() == '/setup/forms/sf2' ? 'menu-open' : '' }}">
                                        <a href="#"class="nav-link {{ Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=5' || Request::getRequestUri() == '/setup/forms/sf2' ? 'active' : '' }}">
                                            <i class="nav-icon fas fa-file"></i>
                                            <p>
                                                SF 2
                                            <i class="fas fa-angle-left right"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview udernavs">
                                            @foreach (collect($basicedacadprogs)->where('id', '!=', 6) as $eachacadprog)
@if (collect($activeacadprogs)->where('acadprogid', $eachacadprog->id)->count() > 0)
<li class="nav-item">
                                                    <a href="/registar/schoolforms/index?sf=2&acadprogid={{ $eachacadprog->id }}" class="nav-link {{ Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=' . $eachacadprog->id ? 'active' : '' }}">
                                                    <i class="nav-icon far fa-circle"></i>
                                                    <p>
                                                        {{ $eachacadprog->progname }}
                                                    </p>
                                                    </a>
                                                </li>
@endif
@endforeach
                                            <li class="nav-item">
                                                <a href="/setup/forms/sf2" class="nav-link {{ Request::url() == url('/setup/forms/sf2') ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                <p>
                                                    Lock SF2 
                                                </p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="nav-item {{ Request::url() == '/reports_schoolform4/dashboard' ? 'menu-open' : '' }}">
                                        <a href="/reports_schoolform4/dashboard"  id="dashboard" class="nav-link {{ Request::url() == url('/reports_schoolform4/dashboard') ? 'active' : '' }}">
                                            <i class="nav-icon fa fa-file"></i>
                                            <p>
                                                SF 4
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ Request::url() == '/registar/schoolforms/index?sf=4' ? 'menu-open' : '' }}">
                                        <a href="/registar/schoolforms/index?sf=4"  id="dashboard" class="nav-link {{ Request::url() == url('/registar/schoolforms/index?sf=4') ? 'active' : '' }}">
                                            <i class="nav-icon fa fa-file"></i>
                                            <p>
                                                SF 4 (By AcadProg)
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item has-treeview  {{ Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=5' ? 'menu-open' : '' }}">
                                        <a href="#"class="nav-link {{ Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=5' ? 'active' : '' }}">
                                            <i class="nav-icon fas fa-file"></i>
                                            <p>
                                                SF 5
                                            <i class="fas fa-angle-left right"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview udernavs">
                                            @foreach (collect($basicedacadprogs)->where('id', '!=', 6) as $eachacadprog)
@if (collect($activeacadprogs)->where('acadprogid', $eachacadprog->id)->count() > 0)
<li class="nav-item">
                                                    <a href="/registar/schoolforms/index?sf=5&acadprogid={{ $eachacadprog->id }}" class="nav-link {{ Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=' . $eachacadprog->id ? 'active' : '' }}">
                                                    <i class="nav-icon  far fa-circle"></i>
                                                    <p>
                                                        {{ $eachacadprog->progname }}
                                                    </p>
                                                    </a>
                                                </li>
@endif
@endforeach
                                            
                                            @if (collect($activeacadprogs)->where('acadprogid', 5)->count() > 0)
<li class="nav-item">
                                                <a href="/registar/schoolforms/index?sf=5a&acadprogid=5"  id="dashboard" class="nav-link {{ Request::getRequestUri() == '/registar/schoolforms/index?sf=5a&acadprogid=5' ? 'active' : '' }}">
                                                    <i class="nav-icon fa fa-file"></i>
                                                    <p>
                                                        SF 5A
                                                    </p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/registar/schoolforms/index?sf=5b&acadprogid=5"  id="dashboard" class="nav-link {{ Request::getRequestUri() == '/registar/schoolforms/index?sf=5b&acadprogid=5' ? 'active' : '' }}">
                                                    <i class="nav-icon fa fa-file"></i>
                                                    <p>
                                                        SF 5B
                                                    </p>
                                                </a>
                                            </li>
@endif
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/registar/schoolforms/index?sf=6"  id="dashboard" class="nav-link {{ Request::getRequestUri() == '/registar/schoolforms/index?sf=6' ? 'active' : '' }}">
                                            <i class="nav-icon fa fa-file"></i>
                                            <p>
                                                SF 6 (By AcadProg)
                                            </p>
                                        </a>
                                    </li>
									<li class="nav-item {{ Request::url() == '/schoolform/sf7' ? 'menu-open' : '' }}">
										<a href="/schoolform/sf7"  id="dashboard" class="nav-link {{ Request::url() == url('/schoolform/sf7') ? 'active' : '' }}">
											<i class="nav-icon fa fa-file"></i>
											<p>
												SF 7
											</p>
										</a>
									</li>
                                   {{--  <li class="nav-item">
                                        <a href="/reports_schoolform6/dashboard"  id="dashboard" class="nav-link {{Request::url() == url('/reports_schoolform6/dashboard') ? 'active' : ''}}">
                                            <i class="nav-icon fa fa-file"></i>
                                            <p>
                                                SF 6
                                            </p>
                                        </a>
								    </li> --}}
                                    <li class="nav-item has-treeview {{ Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=5' ? 'menu-open' : '' }}">
                                        <a href="#"class="nav-link {{ Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=5' ? 'active' : '' }}">
                                            <i class="nav-icon fas fa-file"></i>
                                            <p>
                                                SF 9
                                            <i class="fas fa-angle-left right"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview udernavs">
                                            @foreach (collect($basicedacadprogs)->where('id', '!=', 6) as $eachacadprog)
                                        @if (collect($activeacadprogs)->where('acadprogid', $eachacadprog->id)->count() > 0)
                                            <li class="nav-item">
                                                <a href="/registar/schoolforms/index?sf=9&acadprogid={{ $eachacadprog->id }}"
                                                    class="nav-link {{ Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=' . $eachacadprog->id ? 'active' : '' }}">
                                                    <i class="nav-icon  far fa-circle"></i>
                                                    <p>
                                                        {{ $eachacadprog->progname }}
                                                    </p>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                            @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                <li class="nav-item">
                                    <a href="/reports_schoolform10v2/index" id="dashboard"
                                        class="nav-link {{ Request::url() == url('/reports_schoolform10v2/index') ? 'active' : '' }}">
                                        <i class="nav-icon fa fa-file"></i>
                                        <p>
                                            SF 10
                                        </p>
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a href="/schoolform10/index" id="dashboard"
                                        class="nav-link {{ Request::url() == url('/schoolform10/index') ? 'active' : '' }}">
                                        <i class="nav-icon fa fa-file"></i>
                                        <p>
                                            SF 10
                                        </p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ($iscollege)
                    <li
                        class="nav-item has-treeview {{ Request::url() == url('/schoolform/tor/index') || Request::url() == url('/schoolform/rcfg/index') || Request::url() == url('/registrar/reports/promotional') || Request::url() == url('/registrar/reports/enrollment') || Request::url() == url('/student/permrecord/index') || Request::url() == url('/sc/report/promotional') ? 'menu-open' : '' }}">
                        <a
                            href="#"class="nav-link {{ Request::url() == url('/schoolform/tor/index') || Request::url() == url('/registrar/reports/promotional') || Request::url() == url('/registrar/reports/enrollment') || Request::url() == url('/schoolform/rcfg/index') || Request::url() == url('/student/permrecord/index') || Request::url() == url('/sc/report/promotional') ? 'active' : '' }}"><i
                                class="nav-icon fas fa-caret-right"></i>
                            <p>
                                College Forms
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
                            <li class="nav-item">
                                <a href="/schoolform/tor/index" id="dashboard"
                                    class="nav-link {{ Request::url() == url('/schoolform/tor/index') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        Transcript of Records
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/schoolform/rcfg/index"
                                    class="nav-link {{ Request::url() == url('/schoolform/rcfg/index') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                                            Application for Graduation<br />from Collegiate Course
                                        @else
                                            Record of Candidate<br />For Graduation <!--sbc-->
                                        @endif
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="{{ Request::fullUrl() == url('/sc/report/promotional') ? 'active' : '' }} nav-link"
                                    href="/sc/report/promotional">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        Promotional Report
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif
                <li class="nav-header">Utility</li>
                @if ($getSchoolInfo[0]->projectsetup == 'online')
                    <li class="nav-item 1">
                        <a class="{{ Request::url() == url('/textblast') ? 'active' : '' }} nav-link"
                            href="/textblast">
                            <i class="nav-icon far fa-paper-plane"></i>
                            <p>
                                Text Blast
                            </p>
                        </a>
                    </li>
                @endif
                <li class="nav-item 1">
                    <a class="{{ Request::url() == url('/teacher/student/credential') ? 'active' : '' }} nav-link"
                        href="/teacher/student/credential">
                        <i class="nav-icon far fa-paper-plane"></i>
                        <p>
                            Student Credentials
                        </p>
                    </a>
                </li>
                <li class="nav-item 1" hidden>
                    <a class="{{ Request::url() == url('/data/localtocloud') ? 'active' : '' }} nav-link"
                        href="/data/localtocloud">
                        <i class="nav-icon far fa-paper-plane"></i>
                        <p>
                            Data From Online
                        </p>
                    </a>
                </li>

                {{-- @php
                                $priveledge = DB::table('faspriv')
                                                ->join('usertype','faspriv.usertype','=','usertype.id')
                                                ->select(
                                                    'usertype.utype',
                                                    'usertype'
                                                    )
												->where('type_active',1)
                                                ->where('userid', auth()->user()->id)
                                                ->where('faspriv.deleted','0')
                                                ->where('faspriv.privelege','!=','0')
                                                ->get();

                                $usertype = DB::table('usertype')->where('deleted',0)->where('id',auth()->user()->type)->first();

                            @endphp
							<li class="nav-header text-warning" {{count($priveledge) > 0 ? '':'hidden'}}>Your Portal</li>
                            @foreach ($priveledge as $item)
                                @if ($item->usertype != Session::get('currentPortal'))
                                    <li class="nav-item">
                                        <a class="nav-link portal" href="/gotoPortal/{{$item->usertype}}" id="{{$item->usertype}}">
                                            <i class=" nav-icon fas fa-cloud"></i>
                                            <p>
                                                {{$item->utype}}
                                            </p>
                                        </a>
                                    </li>
                                @endif
                            @endforeach


                            @if ($usertype->id != Session::get('currentPortal'))
                                <li class="nav-item">
                                    <a class="nav-link portal" href="/gotoPortal/{{$usertype->id}}">
                                        <i class=" nav-icon fas fa-cloud"></i>
                                        <p>
                                            {{$usertype->utype}}
                                        </p>
                                    </a>
                                </li>
						    @endif --}}

                {{-- <li class="nav-header text-warning">Your Portal</li --}}


                <li class="nav-header text-warning">HR</li>
                @if (isset(DB::table('schoolinfo')->first()->withleaveapp))
                    @if (DB::table('schoolinfo')->first()->withleaveapp == 1)
                        <li class="nav-item">
                            <a href="/leaves/apply/index" id="dashboard"
                                class="nav-link {{ Request::url() == url('/leaves/apply/index') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-file"></i>
                                <p>
                                    Apply Leave
                                </p>
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a href="/leaves/apply/index" id="dashboard"
                            class="nav-link {{ Request::url() == url('/leaves/apply/index') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-file"></i>
                            <p>
                                Apply Leave
                            </p>
                        </a>
                    </li>
                @endif
                @if (isset(DB::table('schoolinfo')->first()->withovertimeapp))
                    @if (DB::table('schoolinfo')->first()->withovertimeapp == 1)
                        <li class="nav-item">
                            <a href="/overtime/apply/index" id="dashboard"
                                class="nav-link {{ Request::url() == url('/overtime/apply/index') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-file"></i>
                                <p>
                                    Apply Overtime
                                </p>
                            </a>
                        </li>
                    @endif
                @endif
                @if (isset(DB::table('schoolinfo')->first()->withundertimeapp))
                    @if (DB::table('schoolinfo')->first()->withundertimeapp == 1)
                        <li class="nav-item">
                            <a href="/undertime/apply" id="dashboard"
                                class="nav-link {{ Request::url() == url('/undertime/apply') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-file"></i>
                                <p>
                                    Apply Undertime
                                </p>
                            </a>
                        </li>
                    @endif
                @endif
                <li class="nav-item">
                    <a href="/dtr/attendance/index"
                        class="nav-link {{ Request::url() == url('/dtr/attendance/index') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Daily Time Record
                        </p>
                    </a>
                </li>

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

                
                @include('components.privsidenav')

        </nav>

    </div>
    <!-- /.sidebar -->
</aside>
