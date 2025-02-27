<aside class="main-sidebar sidebar-dark-primary elevation-4 ">
    <div class="ckheader">
        <a href="/home" class="brand-link nav-bg">
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
                style="position: absolute;top: 50%;font-size: 16px!important;"><b>SADMIN PORTAL</b></span>
        </a>
    </div>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../../dist/img/download.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info pt-0">
                <a href="#" class="d-block">{{ strtoupper(auth()->user()->name) }}</a>
                <h6 class="text-white m-0 text-warning">SADMIN</h6>
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
                <li
                    class="nav-item has-treeview {{ Request::fullUrl() == url('/superadmin/student/promotion') ||
                    Request::fullUrl() == url('/superadmin/student/information') ||
                    // Request::fullUrl() == url('/superadmin/student/grade/evaluation') ||
                    // Request::fullUrl() == url('/student/loading') ||
                    Request::fullUrl() == url('/student/medinfo') ||
                    Request::fullUrl() == url('/student/information') ||
                    Request::fullUrl() == url('/student/promotion') ||
                    Request::fullUrl() == url('/student/specialization') ||
                    Request::fullUrl() == url('/transferedin/grades') ||
                    Request::fullUrl() == url('/teacher/student/credential') ||
                    Request::fullUrl() == url('/basiced/student/specialclass') ||
                    Request::fullUrl() == url('/student/contactnumber') ||
                    Request::fullUrl() == url('/student/preregistration') ||
                    Request::fullUrl() == url('/student/homeroom/setup1') ||
                    Request::fullUrl() == url('/basiced/student/excludedsubj') ||
                    Request::fullUrl() == url('/registrar/leaf') ||
                    Request::fullUrl() == url('/student/requirements')
                        ? 'menu-open'
                        : '' }}">
                    <a href="#"
                        class="nav-link {{ Request::fullUrl() == url('/superadmin/student/promotion') ||
                        Request::fullUrl() == url('/superadmin/student/information') ||
                        // Request::fullUrl() == url('/superadmin/student/grade/evaluation') ||
                        // Request::fullUrl() == url('/student/loading') ||
                        Request::fullUrl() == url('/student/medinfo') ||
                        Request::fullUrl() == url('/student/information') ||
                        Request::fullUrl() == url('/student/promotion') ||
                        Request::fullUrl() == url('/student/specialization') ||
                        Request::fullUrl() == url('/transferedin/grades') ||
                        Request::fullUrl() == url('/teacher/student/credential') ||
                        Request::fullUrl() == url('/basiced/student/specialclass') ||
                        Request::fullUrl() == url('/student/contactnumber') ||
                        Request::fullUrl() == url('/student/preregistration') ||
                        Request::fullUrl() == url('/student/homeroom/setup1') ||
                        Request::fullUrl() == url('/basiced/student/excludedsubj') ||
                        Request::fullUrl() == url('/registrar/leaf') ||
                        Request::fullUrl() == url('/student/requirements')
                            ? 'active'
                            : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Students
                            <i class="fas fa-angle-left right" style="right: 5%; top: 28%;"></i>
                            <span class="badge badge-info right">7</span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        <li class="nav-item">
                            <a class="{{ Request::fullUrl() == url('/student/preregistration') ? 'active' : '' }} nav-link"
                                href="/student/preregistration">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Student Enrollment
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/student/information') ? 'active' : '' }} nav-link"
                                href="/student/information">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Information
                                </p>
                            </a>
                        </li>
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
                            <a href="/registrar/leaf"
                                class="nav-link {{ Request::url() == url('/registrar/leaf') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    LEASF
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
                        <li class="nav-item">
                            <a class="{{ Request::fullUrl() == url('/teacher/student/credential') ? 'active' : '' }} nav-link"
                                href="/teacher/student/credential">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Student Credentials
                                </p>
                            </a>
                        </li>
                        <li class="nav-item" hidden>
                            <a class="{{ Request::url() == url('/student/homeroom/setup1') ? 'active' : '' }} nav-link"
                                href="/student/homeroom/setup1">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Home Room Input
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
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/student/requirements') ? 'active' : '' }} nav-link"
                                href="/student/requirements">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Student Requirements
                                </p>
                            </a>
                        </li>
                        <li class="nav-item" hidden>
                            <a class="{{ Request::url() == url('/student/updateinfo/request') ? 'active' : '' }} nav-link"
                                href="/student/updateinfo/request">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Update Info. Request
                                </p>
                            </a>
                        </li>
                        <li class="nav-item" hidden>
                            <a class="{{ Request::url() == url('/student/viewaccess') ? 'active' : '' }} nav-link"
                                href="/student/viewaccess">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    View Access
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
                    </ul>
                </li>
                <li
                    class="nav-item has-treeview {{ Request::fullUrl() == url('/classschedule') ||
                    Request::fullUrl() == url('/scheduling/teacher') ||
                    Request::fullUrl() == url('/manageaccounts') ||
                    Request::fullUrl() == url('/esignature')
                        ? 'menu-open'
                        : '' }}">
                    <a href="#"
                        class="nav-link {{ Request::fullUrl() == url('/classschedule') ||
                        Request::fullUrl() == url('/scheduling/teacher') ||
                        Request::fullUrl() == url('/manageaccounts') ||
                        Request::fullUrl() == url('/esignature')
                            ? 'active'
                            : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Faculty & Staff
                            <i class="fas fa-angle-left right" style="right: 5%; top: 28%;"></i>
                            <span class="badge badge-info right">2</span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/classschedule') ? 'active' : '' }} nav-link"
                                href="/classschedule">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Class Record
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/teacher/profile') ? 'active' : '' }} nav-link"
                                href="/teacher/profile">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Teacher Profile
                                </p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="{{Request::url() == url('/setup/useracadprog') ? 'active':''}} nav-link" href="/setup/useracadprog">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    FAS Acad. Prog.
                                </p>
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/manageaccounts') ? 'active' : '' }} nav-link"
                                href="/manageaccounts">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Accounts
                                </p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li
                    class="nav-item has-treeview {{ Request::fullUrl() == url('/college/schedule/coding') || Request::fullUrl() == url('/student/loading') || Request::fullUrl() == url('/student/cor/printing') || Request::fullUrl() == url('/setup/prospectus') || Request::fullUrl() == url('/setup/college') || Request::fullUrl() == url('/setup/course') || Request::fullUrl() == url('/college/completiongrades') || Request::fullUrl() == url('/college/section') || Request::fullUrl() == url('/college/grade/monitoring/teacher') || Request::fullUrl() == url('/student/loading') || Request::fullUrl() == url('/setup/college/subjects') ? 'menu-open' : '' }} ">
                    <a href="#"
                        class="nav-link {{ Request::fullUrl() == url('/college/schedule/coding') || Request::fullUrl() == url('/student/loading') || Request::fullUrl() == url('/student/cor/printing') || Request::fullUrl() == url('/setup/prospectus') || Request::fullUrl() == url('/setup/college') || Request::fullUrl() == url('/setup/course') || Request::fullUrl() == url('/college/completiongrades') || Request::fullUrl() == url('/college/section') || Request::fullUrl() == url('/college/grade/monitoring/teacher') || Request::fullUrl() == url('/student/loading') || Request::fullUrl() == url('/setup/college/subjects') ? 'active' : '' }}  ">
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
                                class="nav-link {{ Request::url() == url('/college/section') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sections</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/setup/college/subjects"
                                class="nav-link {{ Request::url() == url('/setup/college/subjects') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Subjects
                                </p>
                            </a>
                        </li>
                        <li class="nav-item" hidden>
                            <a href="/college/schedule/coding"
                                class="nav-link {{ Request::url() == url('/college/schedule/coding') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Schedule Coding</p>
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
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/semester-setup') ? 'active' : '' }} nav-link"
                                href="/semester-setup">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Grading Setup
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ Request::fullUrl() == url('/student/cor/printing') ? 'active' : '' }} nav-link"
                                href="/student/cor/printing">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    COR Printing
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/college/grade/monitoring/teacher"
                                class="nav-link {{ Request::fullUrl() == url('/college/grade/monitoring/teacher') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Grades (Teacher)</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/college/grade/access') ? 'active' : '' }} nav-link"
                                href="/college/grade/access">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Grade Access
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/college/completiongrades') ? 'active' : '' }} nav-link"
                                href="/college/completiongrades">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Grade Completion
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ Request::fullUrl() == url('/college/grade/ecr/monitoring') ? 'active' : '' }} nav-link"
                                href="/college/grade/ecr/monitoring">
                                <i class="nav-icon fa fa-clipboard-list"></i>
                                <p>
                                    Grade Monitoring
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/reportcard/quarterremarks') ? 'active' : '' }} nav-link"
                        href="/reportcard/quarterremarks">

                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Quarter Remarks
                        </p>
                    </a>
                </li>
                <li class="nav-header">SETUP</li>


                <li class="nav-item">
                    <a class="{{ Request::url() == url('/superadmin/view/schoolinfo') ? 'active' : '' }} nav-link"
                        href="/superadmin/view/schoolinfo">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            School Information
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/project/setup') ? 'active' : '' }} nav-link"
                        href="/project/setup">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Project Setup
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/gradelevel') ? 'active' : '' }} nav-link"
                        href="/gradelevel">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Grade Level
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/setup/usertype') ? 'active' : '' }} nav-link"
                        href="/setup/usertype">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            User Types
                        </p>
                    </a>
                </li>
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
                    <a class="{{ Request::url() == url('/setup/payment/options') ? 'active' : '' }} nav-link"
                        href="/setup/payment/options">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Payment Information
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="{{Request::url() == url('/teacherevaluation/setup') ? 'active':''}} nav-link" href="/teacherevaluation/setup">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Teacher Evaluation
                        </p>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::url() == url('/teacher/evaluation/setup') ? 'active' : '' }}"
                        href="/teacher/evaluation/setup">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Teacher Evaluation Setup
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::url() == url('/teacher/evaluation/monitoring') ? 'active' : '' }}"
                        href="/teacher/evaluation/monitoring">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Evaluation Monitoring
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/teacher/evaluation/monitoring/teacher">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                            Eval. Mon. Teacher
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/queuing-setup') ? 'active' : '' }} nav-link"
                        href="/queuing-setup">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Queuing Setup
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/faqs/setup') ? 'active' : '' }} nav-link"
                        href="/faqs/setup">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            User Guide
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/schoolist/setup') ? 'active' : '' }} nav-link"
                        href="/schoolist/setup">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            School List
                        </p>
                    </a>
                </li>
                <li
                    class="nav-item has-treeview {{ Request::fullUrl() == url('/setup/track') || Request::fullUrl() == url('/setup/strand') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ Request::fullUrl() == url('/setup/track') || Request::fullUrl() == url('/setup/strand') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Senior High
                            <i class="fas fa-angle-left right" style="right: 5%;
                    top: 28%;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        <li class="nav-item">
                            <a href="/setup/track"
                                class="nav-link {{ Request::url() == url('/setup/track') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    SHS Track Name
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/setup/strand"
                                class="nav-link {{ Request::url() == url('/setup/strand') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Strand
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                @php
                    $temp_url = [
                        (object) ['url' => url('/setup/document')],
                        (object) ['url' => url('/admission/setup')],
                        (object) ['url' => url('/setup/sections')],
                        // (object)['url'=>url('/college/section')],
                        // (object)['url'=>url('/college/schedule/coding')],
                        (object) ['url' => url('/setup/modeoflearning')],
                    ];
                @endphp


                <li
                    class="nav-item has-treeview {{ collect($temp_url)->where('url', Request::url())->count() > 0 || request()->is('principalPortalSectionProfile/*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ collect($temp_url)->where('url', Request::url())->count() > 0 || request()->is('principalPortalSectionProfile/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Enrollment
                            <i class="fas fa-angle-left right" style="right: 5%; top: 28%;"></i>
                            <span class="badge badge-info right">5</span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        <li class="nav-item">
                            <a class="nav-link" href="/admission/setup">
                                <i class="nav-icon  far fa-circle"></i>
                                <p>
                                    Admission Date Setup
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/setup/modeoflearning">
                                <i class="nav-icon  far fa-circle"></i>
                                <p>
                                    Mode of Learning
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/setup/document">
                                <i class="nav-icon  far fa-circle"></i>
                                <p>
                                    Docum. Requirements
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ request()->is('/setup/sections') ? 'active' : '' }}"
                                href="/setup/sections">
                                <i class="nav-icon  far fa-circle"></i>
                                <p>
                                    Sections
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/rooms">
                                <i class="nav-icon  far fa-circle"></i>
                                <p>
                                    Rooms
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/buildings">
                                <i class="nav-icon  far fa-circle"></i>
                                <p>
                                    Buildings
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                @php
                    $temp_url_grade = [
                        (object) ['url' => url('/setup/subject')],
                        (object) ['url' => url('/setup/subject/plot')],
                        (object) ['url' => url('/grade/preschool/setup')],
                        (object) ['url' => url('/grade/prekinder/setup')],
                        (object) ['url' => url('/setup/attendance')],
                        (object) ['url' => url('/setup/observed/values')],
                    ];
                @endphp

                <li
                    class="nav-item has-treeview {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'active' : '' }}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Report Card
                            <i class="fas fa-angle-left right" style="right: 5%; top: 28%;"></i>
                            <span class="badge badge-info right">{{ count($temp_url_grade) }}</span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
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
                        <li class="nav-item">
                            <a class="nav-link" href="/grade/preschool/setup">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Pre-school Setup
                                </p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
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
                            <a class="nav-link" href="/setup/reportcard/layouts">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Report Card Layouts
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- <script>
                $(document).ready(function(){
                    $('a[href="/'+uri+'"]').addClass('active')
                })
            </script> --}}


                <li class="nav-header" hidden>FIXER</li>
                <li class="nav-item has-treeview {{ Request::fullUrl() == url('/fixer/sectiondetail?blade=blade') ||
                Request::fullUrl() == url('/nocrestudents?blade=blade')
                    ? 'menu-open'
                    : '' }}""
                    hidden>
                    <a href="#"
                        class="nav-link  
                {{ Request::fullUrl() == url('/fixer/sectiondetail?blade=blade') ||
                Request::fullUrl() == url('/nocrestudents?blade=blade')
                    ? 'active'
                    : '' }}">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            Fixer
                            <i class="fas fa-angle-left right" style="right: 5%;
                top: 28%;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/sp/credentials') ? 'active' : '' }} nav-link"
                                href="/sp/credentials">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    S / P Credential
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>







                <li class="nav-header">UTILITY</li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/grade/monitoring') ? 'active' : '' }} nav-link"
                        href="/grade/monitoring">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            Grade monitoring
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/sadtr') ? 'active' : '' }} nav-link" href="/sadtr">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            Tap monitoring
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/textblast') ? 'active' : '' }} nav-link" href="/textblast">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            Text Blast
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/url') ? 'active' : '' }} nav-link" href="/url">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            URLS
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/sf1/tosystem') ? 'active' : '' }} nav-link"
                        href="/sf1/tosystem">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            SF1 Upload
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/setup/sf9/excel') ? 'active' : '' }} nav-link"
                        href="/setup/sf9/excel">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            SF9 Excel
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/esignature') ? 'active' : '' }} nav-link"
                        href="/esignature">
                        <i class="nav-icon far fa-circle"></i>
                        <p>
                            Parent's E-Signature
                        </p>
                    </a>
                </li>

                @php
                    $temp_url_grade = [
                        (object) ['url' => url('/rfidblade'), 'desc' => 'Registration'],
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

                @php
                    $temp_url_grade = [
                        (object) ['url' => url('/truncanator/v2')],
                        (object) ['url' => url('/truncanator/setup')],
                    ];
                @endphp
                <li
                    class="nav-item has-treeview {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'active' : '' }}">
                        <i class="nav-icon fas fa-keyboard"></i>
                        <p>
                            Clear Data
                            <i class="fas fa-angle-left right" style="right: 5%;
                    top: 28%;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        <li class="nav-item">
                            <a href="/truncanator/v2" class="nav-link">
                                <i class="nav-icon fa fa-keyboard"></i>
                                <p>Clear Data</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/truncanator/setup" class="nav-link">
                                <i class="nav-icon fa fa-keyboard"></i>
                                <p>Clear Data Setup</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>

</aside>
