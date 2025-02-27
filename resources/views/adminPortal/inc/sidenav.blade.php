
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="ckheader">
      <a href="#" class="brand-link  nav-bg">
        @if( DB::table('schoolinfo')->first()->picurl !=null)
            <img src="{{asset(DB::table('schoolinfo')->first()->picurl)}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"  onerror="this.src='{{asset('assets/images/department_of_Education.png')}}'">
        @else
            <img src="{{asset('assets/images/department_of_Education.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"  >
        @endif
          <span class="brand-text font-weight-light" style="position: absolute;top: 6%;">
              {{DB::table('schoolinfo')->first()->abbreviation}}
            </span>
          <span class="brand-text font-weight-light" style="position: absolute;top: 50%;font-size: 16px!important;color:#ffc107"><b>ADMIN'S PORTAL</b></span>
      </a>
    </div>
	@php
		$schoolinfo = DB::table('schoolinfo')->first();
	@endphp
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            <img src="../../dist/img/download.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info pt-0">
                <a href="#" class="d-block">{{strtoupper(auth()->user()->name)}}</a>
            <h6 class="text-white m-0 text-warning">IT</h6>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a  class="{{Request::url() == url('/home') ? 'active':''}} nav-link" href="/home">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                        Home
                        </p>
                    </a>
                </li>
				<li class="nav-item">
                    <a  class="{{Request::url() == url('/school-calendar') ? 'active':''}} nav-link" href="/school-calendar">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                        School Calendar
                        </p>
                    </a>
                </li>
                <li class="nav-header">SETUP</li>
                <li class="nav-item">
                    <a class="{{Request::url() == url('/viewschoolinfo') ? 'active':''}} nav-link" href="/viewschoolinfo">
                        <i class="nav-icon fab fa-pushed"></i>
                        <p>
                            School Information
                        </p>
                    </a>
                </li>
				<li class="nav-item">
                    <a class="{{Request::url() == url('/setup/payment/options') ? 'active':''}} nav-link" href="/setup/payment/options">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Payment Options
                        </p>
                    </a>
                </li>
				@if($schoolinfo->projectsetup == 'offline' || $schoolinfo->processsetup == 'all')
					<li class="nav-item">
						<a class="{{Request::url() == url('/setup/schoolyear') ? 'active':''}} nav-link" href="/setup/schoolyear">
							<i class="nav-icon fas fa-layer-group"></i>
							<p>
								School Year
							</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link {{Request::url() == url('/admission/setup') ? 'active':''}}" href="/admission/setup">
							<i class="nav-icon fas fa-layer-group"></i>
							<p>
								Admission Date Setup
							</p>
						</a>
					</li>
				@endif
				@if($schoolinfo->projectsetup == 'online' || $schoolinfo->processsetup == 'all')
					<li class="nav-item">
						<a class="{{Request::url() == url('/buildings') ? 'active':''}} nav-link" href="/buildings">
							<i class="nav-icon fa fa-door-open"></i>
							<p>
								Buildings
							</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="{{Request::url() == url('/rooms') ? 'active':''}} nav-link" href="/rooms">
							<i class="nav-icon fa fa-door-open"></i>
							<p>
								Rooms
							</p>
						</a>
					</li>
				@endif
                @if($schoolinfo->projectsetup == 'offline' || $schoolinfo->processsetup == 'all')
                  	<li class="nav-item">
                        <a class="{{Request::url() == url('/manageaccounts') ? 'active':''}} nav-link" href="/manageaccounts">
                            <i class="nav-icon fa fa-users"></i>
                            <p>
                                Faculty and Staff
                            </p>
                        </a>
                    </li>
                @endif

			
						<li class="nav-item">
                            <a class="{{Request::url() == url('/teacher/student/credential') ? 'active':''}} nav-link" href="/teacher/student/credential">
                                <i class="nav-icon fa fa-users"></i>
                                <p>
                                    Student User Account
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
				@if($schoolinfo->projectsetup == 'offline' || $schoolinfo->processsetup == 'all')
					<li class="nav-header">UTILITY</li>
					@php
						$temp_url_grade = array(
							(object)['url'=>url('/adminstudentrfidassign/index') ,'desc'=>'Student Assignment'],
							(object)['url'=>url('/adminemployeesetup/index') , 'desc'=>'FAS Assignment']
						);
					@endphp
					<li class="nav-item has-treeview {{ collect($temp_url_grade)->where('url',Request::url())->count() > 0 ? 'menu-open' : ''}}">
						<a href="#" class="nav-link {{ collect($temp_url_grade)->where('url',Request::url())->count() > 0 ? 'active' : ''}}">
							<i class="nav-icon fa fa-credit-card"></i>
							<p>
								RFID
							<i class="fas fa-angle-left right"></i>
							</p>
						</a>
						<ul class="nav nav-treeview ">
							@foreach($temp_url_grade as $item)
								<li class="nav-item">
									<a href="{{$item->url}}" 
										class="nav-link {{Request::fullUrl() == $item->url ? 'active':''}}">
										<i class="nav-icon far fa-circle"></i>
										<p>{{$item->desc}}</p>
									</a>
								</li>
							@endforeach
						</ul>
					</li>
				@endif
            </ul>
        </nav>
    </div>
</aside>


