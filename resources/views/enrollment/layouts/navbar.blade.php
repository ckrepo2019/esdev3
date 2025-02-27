  <nav class="main-header navbar navbar-expand navbar-dark navbar-info">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="/registrar" class="nav-link">Home</a>
      </li> -->
    </ul>
    <div class="form-inline ml-3 menunav" style="height: 50px!important">
          <div class="input-group input-group-sm">
            <ul class="nicemenu">
              <li>
                <a href="/home">
                  <div class="icon">
                    <i class="fas fa-home"></i>
                    <i class="fas fa-home"></i>
                  </div>
                  <div class="name"><span  data-text="Home">Home</span></div>
                </a>
              </li>
            </ul>
          </div>
        </div>
    <!-- SEARCH FORM -->

    <!-- Right navbar links -->

  </nav>
  <aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar">
    <!-- Brand Logo -->
    <a href="/bookcenter" class="brand-link">
      <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">ESSENTIEL</span>
    </a>
    
    <!-- Sidebar -->
    <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition"><div class="os-resize-observer-host"><div class="os-resize-observer observed" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer observed"></div></div><div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 848px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll; right: 0px; bottom: 0px;"><div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('dist/img/avatar04.png')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div>


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">
            STUDENTS
          </li>
          <li class="nav-item">
            <a href="{!! route('studentinfo')!!}" class="nav-link {{(Request::Is('studentinfo')) ? 'active' : ''}}">
              <i class="nav-icon fas fa-graduation-cap"></i>
              <p>
                Student Information
              </p>
            </a>
          </li>
          <li class="nav-header">
            ENROLLED STUDENT
          </li>
          <li class="nav-item">
            <a href="{!! route('admission')!!}" class="nav-link {{(Request::Is('admission')) ? 'active' : ''}}">
              <i class="nav-icon fas fa-graduation-cap"></i>
              <p>
                Admission
              </p>
            </a>
          </li>

      
          <li class="nav-item has-treeview udernavs">
            <a href="#" class="nav-link {{(Request::Is('rptpayables'))? 'active' : ''}}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Reports
                <i class="right fas fa-angle-{{(Request::Is('rptpayables'))? 'down' : 'left'}}"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: {{(Request::Is('rptpayables'))? 'block' : 'none'}};">
              <li class="nav-item">
                <a href="charts/chartjs.html" class="nav-link">
                  <i class="far fa-circle text-info nav-icon"></i>
                  <p>Purchases</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{!! route('rptpayables') !!}" class="nav-link {{(Request::Is('rptpayables'))? 'active' : ''}}">
                  <i class="far fa-circle text-info nav-icon"></i>
                  <p>Payables</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="charts/inline.html" class="nav-link">
                  <i class="far fa-circle text-info nav-icon"></i>
                  <p>Inventory</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div></div></div><div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar os-scrollbar-vertical"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="height: 61.2112%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar-corner"></div></div>
    <!-- /.sidebar -->
  </aside>