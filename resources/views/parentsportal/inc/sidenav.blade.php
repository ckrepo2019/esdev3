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
        <a class="{{Request::url() == url('/student/enrollment/record/reportcard') ? 'active':''}} nav-link" href="/student/enrollment/record/reportcard">
            <i class="nav-icon far fa-folder-open"></i>
            <p>
                Report Card
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="/student/enrollment/record/classschedule"  id="dashboard" class="nav-link {{Request::url() == url('/student/enrollment/record/classschedule') ? 'active' : ''}}">
            <i class="nav-icon fas fa-clipboard-list"></i>
            <p>
                Class Schedule
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="/student/enrollment/record/billinginformation"  id="dashboard" class="nav-link {{Request::url() == url('/student/enrollment/record/billinginformation') ? 'active' : ''}}">
            <i class="nav-icon fas fa-receipt"></i>
            <p>
                Billing Information
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="/student/enrollment/record/cashier"  id="dashboard" class="nav-link {{Request::url() == url('/student/enrollment/record/cashier') ? 'active' : ''}}">
            <i class="nav-icon fas fa-cash-register"></i>
            <p>
                Payment Transactions
            </p>
        </a>
    </li>
    <li class="nav-item has-treeview {{Request::url() == url('/payment')|| Request::url() == url('/student/enrollment/record/online') || Request::url() == url('/student/enrollment/record/cashier')? 'menu-open' : ''}}" hidden>
        <a href="#" class="nav-link {{Request::url() == url('/payment') || Request::url() == url('/student/enrollment/record/online') || Request::url() == url('/student/enrollment/record/cashier') ? 'active' : ''}}">
        <i class="nav-icon fas fa-cash-register"></i>
        <p>
            Payment
            <i class="right fas fa-angle-left"></i>
        </p>
        </a>
        <ul class="nav nav-treeview " >
            <li class="nav-item">
                <a href="/payment" class="nav-link {{request()->is('payment')  ? 'active' : ''}}">
                    <i class="nav-icon fa fa-circle"></i>
                    <p>
                        Payment Upload
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/student/enrollment/record/online"  id="dashboard" class="nav-link {{Request::url() == url('/student/enrollment/record/online') ? 'active' : ''}}">
                    <i class="nav-icon fa fa-circle"></i>
                    <p>
                        Uploaded Payment
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/student/enrollment/record/cashier"  id="dashboard" class="nav-link {{Request::url() == url('/student/enrollment/record/cashier') ? 'active' : ''}}">
                    <i class="nav-icon fa fa-circle"></i>
                    <p>
                        Payment Transactions
                    </p>
                </a>
            </li>
        </ul>
    </li>
	
	<li class="nav-item">
		<a  class="{{Request::url() == url('/school-calendar') ? 'active':''}} nav-link" href="/school-calendar">
			<i class="nav-icon fa fa-calendar-alt"></i>
			<p>
			School Calendar
			</p>
		</a>
	</li>
   
   {{--<li class="nav-item">
        <a class="{{Request::url() == url('/parentschoolCalendar') ? 'active':''}} nav-link" href="/parentschoolCalendar">
            <i class="nav-icon fa fa-calendar-alt"></i>
            <p>
            School Calendar
            </p>
        </a>
   </li>--}}
	
	
    <li class="nav-item" >
        <a class="{{Request::url() == url('/student/enrollment/record/profile') ? 'active':''}} nav-link" href="/student/enrollment/record/profile">
            <i class="nav-icon fa fa-user-edit"></i>
            <p>
               Student Profile
            </p>
        </a>
    </li>
</ul>
</nav>


