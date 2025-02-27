<aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar">
    <!-- Brand Logo -->
    @php
        $getSchoolInfo = DB::table('schoolinfo')
            ->select('schoolname')
            ->get();
    @endphp
    <div class="ckheader">
        <a href="#" class="brand-link sidehead">
          <img src="{{asset(DB::table('schoolinfo')->first()->picurl)}}"
               alt="AdminLTE Logo"
               class="brand-image img-circle elevation-3"
               style="opacity: .8">
          <span class="brand-text font-weight-light" style="position: absolute;top: 6%;">{{DB::table('schoolinfo')->first()->abbreviation}}</span>
          {{-- <span class="brand-text font-weight-light" style="position: absolute;top: 50%;font-size: 16px!important;color:#ffc107"><b>REGISTRAR'S PORTAL</b></span> --}}

          @php
            $utype = db::table('usertype')
                ->where('id', auth()->user()->type)
                ->first()->utype

          @endphp

          <span class="brand-text font-weight-light" style="position: absolute;top: 50%;font-size: 16px!important;color:#ffc107"><b>{{$utype}}'S PORTAL</b></span>
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
                    <div class="user-panel mt-4 pb-3 mb-3 d-flex">
                            <div class="image">
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
                                    ->join('usertype','teacher.usertypeid','=','usertype.id')
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
                                    ->where('employee_personalinfo.employeeid',$registrar_profile->id)
                                    ->get();
                                    $number = rand(1,3);
                                    if(count($registrar_info)==0){
                                        $avatar = 'assets/images/avatars/unknown.png';
                                    }
                                    else{
                                        if(strtoupper($registrar_info[0]->gender) == 'FEMALE'){
                                            $avatar = 'avatar/T(F) '.$number.'.png';
                                        }
                                        else{
                                            $avatar = 'avatar/T(M) '.$number.'.png';
                                        }
                                    }
                                    // echo 
                                @endphp
                            <img src="{{asset($registrar_profile->picurl)}}" onerror="this.onerror = null, this.src='{{asset($avatar)}}'" class="img-circle elevation-2" alt="User Image">
                            </div>
                            <div class="info pt-0" style="margin-top: -7px;">
                                <a href="#" class="d-block">{{strtoupper(auth()->user()->name)}} </a>
                                <h6 class="text-white m-0 text-warning">{{auth()->user()->email}}</h6>
                            </div>
                            
                            
                        </div>
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                        with font-awesome or any other icon font library -->


                            {{-- <li class="nav-header text-warning"><h4>REGISTRAR'S PORTAL</h4></li> --}}
                            <li class="nav-header text-warning"><h4>{{$utype}}'S PORTAL</h4></li>
                            <li class="nav-item">
                                <a href="/home"  id="dashboard" class="nav-link {{Request::url() == url('/home') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-th"></i>
                                    <p>
                                        Home
                                    </p>
                                </a>
                            </li>
                            @php
                                $countapproval = DB::table('hr_leaveemployeesappr')
                                    ->where('appuserid', auth()->user()->id)
                                    ->where('deleted','0')
                                    ->count();
                                // $countapproval = DB::table('hr_leavesappr')
                                //     ->where('employeeid', $hr_profile->id)
                                //     ->where('deleted','0')
                                //     ->count();
                            @endphp
                            @if($countapproval > 0)
                                <li class="nav-item">
                                    <a href="/hr/leaves/index" class="nav-link {{Request::url() == url('/hr/leaves/index') ? 'active' : ''}}">
                                        <i class="fa fa-file-contract nav-icon"></i>
                                        <p>
                                            Filed Leaves
                                        </p>
                                    </a>
                                </li>
                            @endif

<!--                             
                            <li class="nav-header">
                                ENROLLED STUDENTS
                            </li> -->
                            <li class="nav-item">
                                <a href="/registrar/studentinfo" class="nav-link {{Request::url() == url('/registrar/studentinfo') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Student Information
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/registrar/earlyregistration" class="nav-link {{Request::url() == url('/registrar/earlyregistration') ? 'active' : ''}}">
                                  <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Early Registration
                                </p>
                                </a>
                            </li>
                            <!-- <li class="nav-header">
                                PRE-ENROLLED STUDENTS
                            </li> -->
                            <li class="nav-item">
                                <a href="/admission" class="nav-link {{Request::url() == url('/admission') ? 'active' : ''}}">
                                  <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Admitted Students
                                </p>
                                </a>
                            </li>
                            <!--  -->
                            @if(auth()->user()->type == 3 || Session::get('currentPortal') == 3)
                            <li class="nav-item">
                                <a href="/registrar/registered" class="nav-link {{Request::url() == url('/registrar/registered') ? 'active' : ''}}">
                                  <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Registered Students
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/registrar/preenrolled" class="nav-link {{Request::url() == url('/registrar/preenrolled') ? 'active' : ''}}">
                                  <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Pre-Enrolled Students <span class="badge badge-warning studpaid">0</span>
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
                                <a href="/student/promotion" class="nav-link {{Request::url() == url('/student/promotion') ? 'active' : ''}}">
                                  <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Student Promotion
                                </p>
                                </a>
                            </li>
							
							<li class="nav-header">
                                SETUP
                            </li>
                            <li class="nav-item">
                                <a href="/setup/document" class="nav-link {{Request::fullUrl() == url('/setup/document')? 'active':''}}">
                                    <i class="fas fa-layer-group nav-icon"></i>
                                    <p>Required Documents</p>
                                </a>
                            </li>
							<li class="nav-item has-treeview {{ Request::fullUrl() == url('/setup/track') || Request::fullUrl() == url('/setup/strand')? 'menu-open':''}}">
                                <a href="#" class="nav-link {{ Request::fullUrl() == url('/setup/track') || Request::fullUrl() == url('/setup/strand') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-layer-group"></i>
                                    <p>
                                        Senior High
                                    <i class="fas fa-angle-left right" style="right: 5%;
                                top: 28%;"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    <li class="nav-item">
                                        <a href="/setup/track" class="nav-link {{Request::url() == url('/setup/track') ? 'active' : ''}}">
                                            <i class="nav-icon fa fa-circle"></i>
                                            <p>
                                                Track
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/setup/strand" class="nav-link {{Request::url() == url('/setup/strand') ? 'active' : ''}}">
                                            <i class="nav-icon fa fa-circle"></i>
                                            <p>
                                                Strand
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview {{ Request::fullUrl() == url('/setup/college') || Request::fullUrl() == url('/setup/course')? 'menu-open':''}}">
                                <a href="#" class="nav-link {{ Request::fullUrl() == url('/setup/college') || Request::fullUrl() == url('/setup/course') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-layer-group"></i>
                                    <p>
                                        College
                                    <i class="fas fa-angle-left right" style="right: 5%;
                                top: 28%;"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    <li class="nav-item">
                                        <a href="/setup/college" class="nav-link {{Request::url() == url('/setup/college') ? 'active' : ''}}">
                                            <i class="nav-icon fa fa-circle"></i>
                                            <p>
                                                Colleges
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/setup/course" class="nav-link {{Request::url() == url('/setup/course') ? 'active' : ''}}">
                                            <i class="nav-icon fa fa-circle"></i>
                                            <p>
                                                Courses
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
							<li class="nav-item has-treeview {{ Request::fullUrl() == url('/setup/attendance')? 'menu-open':''}}">
                                <a href="#" class="nav-link {{ Request::fullUrl() == url('/setup/attendance') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-layer-group"></i>
                                    <p>
                                        Report Card
                                    <i class="fas fa-angle-left right" style="right: 5%;
                                top: 28%;"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    <li class="nav-item">
                                        <a href="/setup/attendance" class="nav-link {{Request::url() == url('/setup/attendance') ? 'active' : ''}}">
                                            <i class="nav-icon fa fa-circle"></i>
                                            <p>
                                                Days per Month
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
							
                            <li class="nav-header">TECHNICAL VOCATIONAL</li>
                            <li class="nav-item">
                                <a href="/techvoc/tvstudinfo" class="nav-link {{Request::url() == url('/techvoc/tvstudinfo') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-user-graduate"></i>
                                    <p>
                                        Student List | Enrollment
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/techvoc/courses" class="nav-link {{Request::url() == url('/techvoc/courses') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-microscope"></i>
                                    <p>
                                        Courses
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/techvoc/batch" class="nav-link {{Request::url() == url('/techvoc/batch') ? 'active' : ''}}">
                                    <i class="fas fa-swatchbook nav-icon"></i>
                                    <p>
                                        Batch
                                    </p>
                                </a>
                            </li>
                            

                            <li class="nav-header">
                                COLLEGE
                            </li>
                            {{-- <li class="nav-item">
                                <a href="/college/adddrop/index" class="nav-link {{Request::url() == url('/college/adddrop/index') ? 'active' : ''}}">
                                    <span class="nav-icon">
                                        <i class="fa fa-arrow-up"></i><i class="fa fa-arrow-down"></i>
                                    </span>
                                    <p>
                                        Adding/Dropping
                                    </p>
                                </a>
                            </li>
                             <li class="nav-item">
                                <a href="/registrar/college/student/loading" class="nav-link {{Request::url() == url('/registrar/college/student/loading') ? 'active' : ''}}">
                                    <span class="nav-icon">
                                        <i class="fa fa-arrow-up"></i><i class="fa fa-arrow-down"></i>
                                    </span>
                                    <p>
                                        Student Loading
                                    </p>
                                </a>
                            </li> --}}
                            <li class="nav-item">
                                <a  class="{{Request::url() == url('/registrar/report/promotional') ? 'active':''}} nav-link" href="/registrar/report/promotional">
                                    <i class="nav-icon fa fa-book"></i>
                                    <p>
                                        Promotional Report
                                    </p>
                                </a>
                            </li>
                            <li class="nav-header">REPORTS</li>
                            <li class="nav-item">
                                <a href="/registrar/leaf" class="nav-link {{Request::url() == url('/registrar/leaf') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    LEAF
                                </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview {{Request::url() == url('/reportssummariesallstudents/dashboard') || Request::url() == url('/reportssummariesspecialclass/dashboard') || Request::url() == url('/registrar/studentrequirements') || Request::url() == url('/registrar/studentlist') ? 'menu-open' : ''}}">
                                <a href="#"class="nav-link {{Request::url() == url('/reportssummariesallstudents/dashboard') || Request::url() == url('/reportssummariesspecialclass/dashboard') || Request::url() == url('/registrar/studentrequirements') || Request::url() == url('/registrar/studentlist') || Request::url() == url('/registrar/oe') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        SUMMARIES
                                    <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    <li class="nav-item">
                                        <a href="/reportssummariesallstudentsnew/dashboard" class="nav-link {{Request::url() == url('/reportssummariesallstudentsnew/dashboard') ? 'active' : ''}}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Students Summary</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/registrar/oe?action=index" class="nav-link {{Request::url() == url('/registrar/oe?action=index') ? 'active' : ''}}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Online Enrolled Students</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/registrar/studentlist" class="nav-link {{Request::url() == url('/registrar/studentlist') ? 'active' : ''}}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Student List & <br/>Enrollment Summary
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/registrar/studentrequirements" class="nav-link {{Request::url() == url('/registrar/studentrequirements') ? 'active' : ''}}">
                                            <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Student Reqs 
                                        </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/registrar/sla/index" class="nav-link {{Request::url() == url('/registrar/sla/index') ? 'active' : ''}}">
                                            <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            School Last Attended
                                        </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/registrar/summaries/alphaloading/index" class="nav-link {{Request::url() == url('/registrar/summaries/alphaloading/index') ? 'active' : ''}}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Alpha Loading</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview {{Request::url() == url('/printable/certification/index') || Request::url() == url('/printable/numofstudents/index') || Request::url() == url('/printable/cor') ? 'menu-open' : ''}}">
                                <a href="#"class="nav-link {{Request::url() == url('/printable/certification/index') || Request::url() == url('/printable/numofstudents/index') || Request::url() == url('/printable/cor') ? 'active' : ''}}">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        Other Printables
                                    <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                        <li class="nav-item">
                                            <a href="/printable/certification/index" class="nav-link {{Request::url() == url('/printable/certification/index') ? 'active' : ''}}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Certificate of Enrollment</p>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a href="/printable/numofstudents/index" class="nav-link {{Request::url() == url('/printable/numofstudents/index') ? 'active' : ''}}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Number of Students</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/printable/cor" class="nav-link {{Request::url() == url('/printable/cor') ? 'active' : ''}}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Certificate of Registration</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-header text-warning">Basic Ed School Forms</li>
                            @php
                                $basicedacadprogs = DB::table('academicprogram')
                                    ->where('id','!=',6)
                                    ->get();
                            @endphp
                            <li class="nav-item has-treeview">
                                <a href="#"class="nav-link">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        SF 1
                                    <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    @foreach($basicedacadprogs as $eachacadprog)
                                        <li class="nav-item">
                                            <a href="/registar/schoolforms/index?sf=1&acadprogid={{$eachacadprog->id}}" class="nav-link">
                                            <i class="nav-icon  far fa-circle"></i>
                                            <p>
                                                {{$eachacadprog->progname}}
                                            </p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#"class="nav-link">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        SF 2
                                    <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    @foreach($basicedacadprogs as $eachacadprog)
                                        <li class="nav-item">
                                            <a href="/registar/schoolforms/index?sf=2&acadprogid={{$eachacadprog->id}}" class="nav-link">
                                            <i class="nav-icon  far fa-circle"></i>
                                            <p>
                                                {{$eachacadprog->progname}}
                                            </p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="/reports_schoolform4/dashboard"  id="dashboard" class="nav-link {{Request::url() == url('/reports_schoolform4/dashboard') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        SF 4
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview ">
                                <a href="#"class="nav-link">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        SF 5
                                    <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    @foreach(collect($basicedacadprogs)->where('id','!=',5) as $eachacadprog)
                                        <li class="nav-item">
                                            <a href="/registar/schoolforms/index?sf=5&acadprogid={{$eachacadprog->id}}" class="nav-link">
                                            <i class="nav-icon  far fa-circle"></i>
                                            <p>
                                                {{$eachacadprog->progname}}
                                            </p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="/registar/schoolforms/index?sf=5a&acadprogid=5"  id="dashboard" class="nav-link {{Request::url() == url('/registar/schoolforms/index?sf=5a') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        SF 5A
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/registar/schoolforms/index?sf=5b&acadprogid=5"  id="dashboard" class="nav-link {s{Request::url() == url('/registar/schoolforms/index?sf=5b') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        SF 5B
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/reports_schoolform6/dashboard"  id="dashboard" class="nav-link {{Request::url() == url('/reports_schoolform6/dashboard') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        SF 6
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/reports_schoolform10/index"  id="dashboard" class="nav-link {{Request::url() == url('/reports_schoolform10/index') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        SF 10
                                    </p>
                                </a>
                            </li>
									<li class="nav-item">
										<a href="/registrar/student/awards"  id="dashboard" class="nav-link {{Request::url() == url('/registrar/student/awards') ? 'active' : ''}}">
											<i class="nav-icon fa fa-file"></i>
											<p>
													STUDENT AWARDS
											</p>
										</a>
									</li>
                            @endif
                            {{-- <li class="nav-item">
                                <a href="/registrargoodmoralcertificate"  id="dashboard" class="nav-link {{Request::url() == url('/registrargoodmoralcertificate') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                            GOOD MORAL CERTIFICATE
                                    </p>
                                </a>
                            </li> --}}
                            {{-- <li class="nav-header">ENTRANCE EXAM</li>
                            <li class="nav-item">
                                <a href="/entranceexamquestions" class="nav-link {{Request::url() == url('/entranceexamquestions') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-graduation-cap"></i>
                                    <p>
                                        EE Questions
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/entranceexamresults/{{Crypt::encrypt('dashboard')}}" class="nav-link {{Request::url() == url('/entranceexamresults/'.Crypt::encrypt('dashboard')) ? 'active' : ''}}">
                                <i class="nav-icon fas fa-graduation-cap"></i>
                                <p>
                                    EE Results
                                </p>
                                </a>
                            </li> --}}
                            
                            <li class="nav-header text-warning">Your Portal</li>

                            @php
                                $priveledge = DB::table('faspriv')
                                                ->join('usertype','faspriv.usertype','=','usertype.id')
                                                ->select('faspriv.*','usertype.utype')
                                                ->where('userid', auth()->user()->id)
                                                ->where('faspriv.deleted','0')
                                                ->where('faspriv.privelege','!=','0')
                                                ->get();

                                $usertype = DB::table('usertype')->where('deleted',0)->where('id',auth()->user()->type)->first();

                            @endphp

                            @foreach ($priveledge as $item)
                                @if($item->usertype != Session::get('currentPortal'))
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

                            @if($usertype->id != Session::get('currentPortal'))
                                <li class="nav-item">
                                    <a class="nav-link portal" href="/gotoPortal/{{$usertype->id}}">
                                        <i class=" nav-icon fas fa-cloud"></i>
                                        <p>
                                            {{$usertype->utype}}
                                        </p>
                                    </a>
                                </li>
                            @endif
                            @if(auth()->user()->usertype == '3' || auth()->user()->usertype == '8')
                            <li class="nav-item">
                                <a href="/employeedailytimerecord/dashboard" class="nav-link {{Request::url() == url('/employeedailytimerecord/dashboard') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        Daily Time Record
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/applyleave/{{Crypt::encrypt('dashboard')}}"  id="dashboard" class="nav-link {{Request::url() == url('/applyleave/dashboard') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        Apply Leave
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/applyovertimedashboard/{{Crypt::encrypt('dashboard')}}"  id="dashboard" class="nav-link {{Request::url() == url('/applyovertimedashboard/dashboard') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        Apply Overtime
                                    </p>
                                </a>
                            </li>
                            @endif
                            <li class="nav-header">REGISTRAR</li>
                            <!-- <li class="nav-header"></li> -->
                            <li class="nav-item">
                                <a href="/enrollment/spclass" class="nav-link {{Request::url() == url('/enrollment/spclass') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-graduation-cap"></i>
                                <p>
                                    Special Class
                                </p>
                                </a>
                            </li>
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
                            <li class="nav-item">
                                <a href="/employeepayrolldetails"  id="employeepayrolldetails" class="nav-link {{Request::url() == url('/employeepayrolldetails') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-clock"></i>
                                    <p>
                                        Payroll Details
                                    </p>
                                </a>
                            </li>
                            <li class="nav-header">PORTALS</li>
                            @php
                            $priveledge = DB::table('faspriv')
                                ->join('usertype','faspriv.usertype','=','usertype.id')
                                ->select('faspriv.*','usertype.utype')
                                ->where('userid', auth()->user()->id)
                                ->where('faspriv.privelege','!=','0')
                                ->where('faspriv.deleted','0')
                                ->get();
                          @endphp

                          @foreach ($priveledge as $item)
                            @if($item->usertype != Session::get('currentPortal'))
                              <li class="nav-item">
                                <a class="nav-link portal" href="/gotoPortal/{{$item->usertype}}" id="{{$item->usertype}}">
                                  <i class="nav-icon fa fa-calendar-alt"></i>
                                  <p>
                                      {{$item->utype}}'S PORTAL
                                  </p>
                                </a>
                              </li>
                            @endif
                          @endforeach
                        </nav>
                        <br>
                        <br>
                        <br>
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