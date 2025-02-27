<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>    <div class="scrollbar-sidebar ps">
        <div class="app-sidebar__inner">
                
           
           
            <ul class="vertical-nav-menu metismenu">
                <li>
                    <div class="widget-content p-0 mt-2 ">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left mr-3">
                                <img width="42" class="rounded-circle" src="assets/images/avatars/1.jpg" alt="">
                            </div>
                            <div class="widget-content-left">
                                <div class="widget-heading">Ella-Rose Henry</div>
                                <div class="widget-subheading">Web Developer</div>
                            </div>
                            
                        </div>
                    </div>
                </li>
             
                <li class="app-sidebar__heading fsize-2">TEACHER'S PORTAL</li>
                                <li >
                                    <a href="/" class="{{Request::url() == url('/') ? 'mm-active' : ''}}" id="dashboard"  style="color: orange;" ><i class="fa fa-th"></i>&nbsp;&nbsp;Dashboard</a>
                                </li>
                                    <li >
                                        <a class="{{Request::url() == url('/students') ? 'mm-active' : ''}}" href="/students" id="students" >
                                            <i class="fa fa-users"></i>&nbsp;&nbsp;
                                            Students
                                        </a>
                                    </li>
                                    <li>
                                        <a class="{{Request::url() == url('/attendance') ? 'mm-active' : ''}}" href="/attendance" id="attendance" >
                                            <i class="fa fa-list">
                                            </i>&nbsp;&nbsp;Attendance
                                        </a>
                                    </li>
                                    <li>
                                        <a class="{{Request::url() == url('/grades') ? 'mm-active' : ''}}" href="/grades" id="grades" >
                                            <i class="fa fa-star">
                                            </i>&nbsp;&nbsp;Grades
                                        </a>
                                    </li>
                                </li>
                            </ul>
                        </div>
                    <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
                </div>