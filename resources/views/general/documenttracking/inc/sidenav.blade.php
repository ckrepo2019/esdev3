
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar">
    <div class="ckheader">
        <a href="#" class="brand-link sidehead">
            <img src="/dist/img/AdminLTELogo.png"
               class="brand-image img-circle elevation-3"
               style="opacity: .8">
			<span class="brand-text font-weight-light" style="position: absolute;top: 6%;"></span>

			
			<span class="brand-text font-weight-light" style="position: absolute;top: 50%;font-size: 16px!important;"><b>HELLO PORTAL</b></span>
        </a>
    </div>
    <!-- Sidebar -->
    <div class="sidebar">
		<div class="row">
            <div class="col-md-12">
            <div class="text-center">
                <img class="profile-user-img img-fluid img-circle" src="/assets/images/avatars/unknown.png"  alt="User Image" width="100%" style="width:130px; border-radius: 12% !important;">
            </div>
            </div>
        </div>
        <div class="row  user-panel">
            <div class="col-md-12 info text-center">
            <a class=" text-white mb-0 ">Clyde</a>
            <h6 class="text-warning text-center">loverBoy@gmail.com</h6>
            </div>
        </div>

        <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item">
                                <a href="/home"  id="dashboard" class="nav-link {{Request::url() == url('/home') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-home"></i>
                                    <p>
                                        Home 
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/user/profile" class="nav-link {{Request::url() == url('/user/profile') ? 'active' : ''}}">
                                    <i class="nav-icon fa fa-user"></i>
                                    <p>
                                        Profile
                                    </p>
                                </a>
                            </li>

                        </nav>
      <!-- /.sidebar-menu -->
    </div>
    <br/>
    <br/>
    <br/>
    <!-- /.sidebar -->
  </aside>