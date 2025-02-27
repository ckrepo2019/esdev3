  <nav class="main-header navbar navbar-expand navbar-dark navbar-info">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/finance/index" class="nav-link">Home</a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      
      <!-- Notifications Dropdown Menu -->
      
      <li class="nav-item">
        <a class="nav-link " href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" id="dashboard">
          
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>

          <i class="fas fa-sign-out-alt"></i>
        </a>
      </li>
    </ul>

    <!-- SEARCH FORM -->

    <!-- Right navbar links -->

    @php
      // use db;
      $schoolinfo = DB::table('schoolinfo')
        ->first();
    @endphp

  </nav>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/bookcenter" class="brand-link">
      <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{$schoolinfo->schoolname}}</span>
    </a>
    
    <!-- Sidebar -->
    <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition"><div class="os-resize-observer-host"><div class="os-resize-observer observed" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer observed"></div></div><div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 848px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll; right: 0px; bottom: 0px;"><div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('dist/img/avatar04.png')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{Auth::user()->name}}</a>
        </div>
      </div>


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">
            FINANCE
          </li>
          <li class="nav-item">
            <a href="{!! route('studledger')!!}" class="nav-link {{(Request::Is('finance/studledger')) ? 'active' : ''}}">
              <i class="nav-icon fas fa-file-invoice"></i>
              <p>
                Student Ledger
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{!! route('financeindex')!!}" class="nav-link {{(Request::Is('finance/')) ? 'active' : ''}}">
              <i class="fas fa-bell"></i>
              <p>
                SMS Notification
              </p>
            </a>
          </li>
      
          <li class="nav-item has-treeview 
            {{(Request::Is('finance/itemclassification')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/payitems')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/modeofpayment')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/mopnew')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/mopedit/*')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/fees')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/feesnew')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/feesedit/*')) ? 'menu-open' : ''}}
            ">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-wallet"></i>
              <p>
                Payment Setup
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{!! route('itemclassification')!!}" class="nav-link {{(Request::Is('finance/itemclassification')) ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Item Classification</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{!! route('payitems')!!}" class="nav-link {{(Request::Is('finance/payitems')) ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Payment Items</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{!! route('modeofpayment')!!}" class="nav-link 
                  {{(Request::Is('finance/modeofpayment')) ? 'active' : ''}}
                  {{(Request::Is('finance/mopnew')) ? 'active' : ''}}
                  {{(Request::Is('finance/mopedit/*')) ? 'active' : ''}}
                ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mode of Payment</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{!! route('fees')!!}" class="nav-link 
                  {{(Request::Is('finance/fees')) ? 'active' : ''}}
                  {{(Request::Is('finance/feesnew')) ? 'active' : ''}}
                ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Fees and Collection</p>
                </a>
              </li>
            </ul>
          </li>
          
        </ul>

        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">
            Reports
          </li>
          <li class="nav-item">
            <a href="{{-- {!! route('crs')!!} --}}" class="nav-link {{(Request::Is('finance/crs')) ? 'active' : ''}}">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Cash Receipt Summary
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{-- {!! route('crs')!!} --}}" class="nav-link {{(Request::Is('finance/crs')) ? 'active' : ''}}">
              <i class="fas fa-coins"></i>
              <p>
                Account Receivable
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div></div></div><div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar os-scrollbar-vertical"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="height: 61.2112%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar-corner"></div></div>
    <!-- /.sidebar -->
  </aside>