@php
    $getSchoolInfo = DB::table('schoolinfo')->select('region', 'division', 'district', 'schoolname', 'schoolid')->get();
    $syid = DB::table('sy')->where('isactive', '1')->first();
    $getProgname = DB::table('teacher')
        ->select(
            'teacher.id',
            'sections.levelid',
            'gradelevel.levelname',
            'sections.id as sectionid',
            'sections.sectionname',
            'academicprogram.progname',
        )
        ->join('sectiondetail', 'teacher.id', '=', 'sectiondetail.teacherid')
        ->join('sections', 'sectiondetail.sectionid', '=', 'sections.id')
        ->join('gradelevel', 'sections.levelid', '=', 'gradelevel.id')
        ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
        ->where('teacher.userid', auth()->user()->id)
        ->where('sectiondetail.syid', $syid->id)
        ->where('sections.deleted', '0')
        ->get();

@endphp

@php
    $teacher_profile = Db::table('teacher')
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
            'usertype.utype',
        )
        ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
        ->where('teacher.userid', auth()->user()->id)
        ->first();

    $teacher_info = Db::table('employee_personalinfo')
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
            'employee_personalinfo.date_joined',
        )
        ->where('employee_personalinfo.employeeid', $teacher_profile->id)
        ->get();
    $number = rand(1, 3);
    if (count($teacher_info) == 0) {
        $avatar = 'assets/images/avatars/unknown.png';
    } else {
        if (strtoupper($teacher_info[0]->gender) == 'FEMALE') {
            $avatar = 'avatar/T(F) ' . $number . '.png';
        } else {
            $avatar = 'avatar/T(M) ' . $number . '.png';
        }
    }
@endphp
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar">
    <!-- Brand Logo -->
    <a href="/home" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a> -->
    <div class="ckheader">
        <a href="#" class="brand-link sidehead">
            <img src="{{ asset(DB::table('schoolinfo')->first()->picurl) }}" {{-- alt="{{DB::table('schoolinfo')->first()->abbreviation}}" --}}
                class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light"
                style="position: absolute;top: 6%;">{{ DB::table('schoolinfo')->first()->abbreviation }}</span>
            <span class="brand-text font-weight-light"
                style="position: absolute;top: 50%;font-size: 16px!important;color:#ffc107"><b>SCHOOL DOCTOR</b></span>
        </a>
    </div>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="row pt-2">
            <div class="col-md-12">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                        src="{{ asset($teacher_profile->picurl) }}?random={{ \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss') }}"
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
                <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
                <!-- <li class="nav-header text-warning"><h4>TEACHER'S PORTAL</h4></li> -->
                <li class="nav-item">
                    <a href="/home" id="dashboard"
                        class="nav-link {{ Request::url() == url('/home') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-home"></i>
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

                <li class="nav-item">
                    <a href="/user/profile"
                        class="nav-link {{ Request::url() == url('/user/profile') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>
                <li class="nav-header"></li>
                <li class="nav-item">
                    <a href="/clinic/doctor/profile/index" id="dental"
                        class="nav-link {{ Request::url() == url('/clinic/doctor/profile/index') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-user"></i>
                        <p>
                            Doctor Profile
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="/clinic/appointment/index"  id="dental" class="nav-link {{Request::url() == url('/clinic/appointment/index') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-file-medical"></i>
                        <p>
                            Create Appointment 
                        </p>
                    </a>
                </li> --}}
                {{-- @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lchsi')
                    <li class="nav-item has-treeview {{ Request::fullUrl() == url('/administrator/schoolfolders') || Request::fullUrl() == url('/administrator/schoolfolders')? 'menu-open':''}}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>
                                INTRANET
                            <i class="fas fa-angle-left right" style="right: 5%;
                        top: 28%;"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
                            <li class="nav-item">
                                <a class="{{Request::url() == url('/administrator/schoolfolders') ? 'active':''}} nav-link" href="/administrator/schoolfolders">
                                    <i class="nav-icon fa fa-calendar"></i>
                                    <p>
                                        File Directory
                                    </p>
                                </a>
                            </li>
                        </ul>
                        
                    </li>
                @endif --}}

                <li class="nav-item">
                    <a href="/clinic/doctor/availablity/index" id="dental"
                        class="nav-link {{ Request::url() == url('/clinic/doctor/availablity/index') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-calendar"></i>
                        <p>
                            Schedule Availability
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/clinic/complaints/index" id="dental"
                        class="nav-link {{ Request::url() == url('/clinic/complaints/index') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-exclamation-triangle"></i>
                        <p>
                            Complaints
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="/clinic/appointment/index"  id="dental" class="nav-link {{Request::url() == url('/clinic/appointment/index') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-exclamation-triangle"></i>
                        <p>
                            Appointments
                        </p>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a href="/clinic/medicalhistory/index" id="dental"
                        class="nav-link {{ Request::url() == url('/clinic/medicalhistory/index') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Medical History
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="/clinic/records/index"  id="dental" class="nav-link {{Request::url() == url('/clinic/records/index') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-folder"></i>
                        <p>
                            Reports 
                        </p>
                    </a>
                </li> --}}


                <li class="nav-item">
                    <a href="/clinic/patientdashboard/index" id="dental"
                        class="nav-link {{ Request::url() == url('/clinic/patientdashboard/index') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-user-md"></i>
                        <p>
                            Create Appointment
                        </p>
                    </a>
                </li>

                <li class="nav-header text-warning">My Applications</li>
                {{-- <li class="nav-item">
                    <a href="/hr/leaves/index?action=myleave" class="nav-link {{ Request::fullUrl() === url('/hr/leaves/index?action=myleave') ? 'active' : '' }}">
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

                @include('components.privsidenav')
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <br />
    <br />
    <br />
    <!-- /.sidebar -->
</aside>
