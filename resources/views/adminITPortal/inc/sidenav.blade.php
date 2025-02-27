
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">
        @if(strtolower(Session::get('schoolinfo')->abbreviation) != 'sbc')
        <li class="nav-item">
            <a  class="{{Request::url() == url('/home') ? 'active':''}} nav-link" href="/home">
                <i class="nav-icon fa fa-list"></i>
                <p>
                    School List
                </p>
            </a>
        </li>
        @endif
        <li class="nav-item">
            <a  class="{{Request::url() == url('/viewschool/'.Session::get('schoolinfo')->id) ? 'active':''}} nav-link" href="/viewschool/{{Session::get('schoolinfo')->id}}">
                <i class="nav-icon fa fa-home"></i>
                <p>
                Home
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview {{Request::url() == url('/director/finance/cashiertransactionsindex') || Request::url() == url('/printable/cor') || Request::url() == url('/printable/gwaranking') || Request::url() == url('/printable/coranking') ||Request::url() == url('/student/cor/printing') || Request::url() == url('/printable/studentacademicrecord') || Request::url() == url('/printable/masterlist') || Request::url() == url('/printable/othercertification/index') ? 'menu-open' : ''}}">
            <a href="#"class="nav-link {{Request::url() == url('/printable/certification/index') || Request::url() == url('/printable/cor') || Request::url() == url('/printable/gwaranking') || Request::url() == url('/printable/coranking') ||Request::url() == url('/student/cor/printing') || Request::url() == url('/printable/studentacademicrecord') || Request::url() == url('/printable/masterlist') || Request::url() == url('/printable/othercertification/index') ? 'active' : ''}}">
                <i class="nav-icon fas fa-caret-right"></i>
                <p>
                    Finance Reports
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview udernavs">
                <li class="nav-item"> 
                    <a href="/director/finance/cashiertransactionsindex" class="nav-link {{Request::getRequestUri() == '/director/finance/cashiertransactionsindex' ? 'active' : ''}}">
                    <i class="nav-icon  far fa-circle"></i>
                    <p>
                        Cashier Transactions
                    </p>
                    </a>
                </li>
                <li class="nav-item"> 
                    <a href="/director/finance/collectionsindex" class="nav-link {{Request::getRequestUri() == '/director/finance/collectionsindex' ? 'active' : ''}}">
                    <i class="nav-icon  far fa-circle"></i>
                    <p>
                        Collection
                    </p>
                    </a>
                </li>
                {{-- <li class="nav-item"> 
                    <a href="/printable/masterlist?sf=0&acadprogid=" class="nav-link {{Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=' ? 'active' : ''}}">
                    <i class="nav-icon  far fa-circle"></i>
                    <p>
                       Year End Collection
                    </p>
                    </a>
                </li> --}}
                {{-- <li class="nav-item"> 
                    <a href="/director/finance/accountreceivablesindex" class="nav-link {{Request::getRequestUri() == '/director/finance/accountreceivablesindex' ? 'active' : ''}}">
                    <i class="nav-icon  far fa-circle"></i>
                    <p>
                       Account Receivables
                    </p>
                    </a>
                </li>
                <li class="nav-item"> 
                    <a href="/director/finance/expensesindex" class="nav-link {{Request::getRequestUri() == '/director/finance/expensesindex' ? 'active' : ''}}">
                    <i class="nav-icon  far fa-circle"></i>
                    <p>
                       Expenses
                    </p>
                    </a>
                </li> --}}
            </ul>
        </li>
        <li class="nav-item has-treeview {{Request::url() == url('/hr/index') ? 'menu-open' : ''}}">
            <a href="#"class="nav-link {{Request::url() == url('/hr/index') ? 'active' : ''}}">
                <i class="nav-icon fas fa-caret-right"></i>
                <p>
                    Human Resource
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview udernavs">
                <li class="nav-item"> 
                    <a href="/hr/index" class="nav-link {{Request::getRequestUri() == '/hr/index' ? 'active' : ''}}">
                    <i class="nav-icon  far fa-circle"></i>
                    <p>
                       Employee Profile
                    </p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="/aadmin/enrollment" class="nav-link {{Request::url() == url('/aadmin/enrollment') ? 'active':''}}">
                <i class="nav-icon fas fa-poll"></i>
                <p>Enrollment Statistics</p>
            </a>
        </li>
    
   {{-- <li class="nav-item">
        <a class="{{Request::url() == url('/adminviewenrolledstudents') ? 'active':''}} nav-link" href="/adminviewenrolledstudents">
            <i class="nav-icon fa fa-users"></i>
            <p>
                Enrolled Students
                
            </p>
        </a>
    </li> --}}
            {{-- <li class="nav-item">
                <a href="/academic/index" class="nav-link {{Request::url() == url('/academic/index') ? 'active':''}}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Academic</p>
                </a>
            </li> --}}
            {{-- <li class="nav-item has-treeview 
            {{ Request::url() == url('/academic/students') 
                || Request::url() == url('/teacher/profile') 
            
            ? 'menu-open':''}}">
                <a href="#" class="nav-link 
                    {{ request()->is('/academic/students') 
                        || request()->is('/teacher/profile') 
                       
                        ? 'active':''}}
                ">
                    <i class="nav-icon far fa-envelope"></i>
                    <p>
                        Academic
                    <i class="fas fa-angle-left right" style="right: 5%;
                    top: 28%;"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview udernavs ">
                    <li class="nav-item">
                        <a href="/academic/students" class="nav-link {{Request::url() == url('/academic/students') ? 'active':''}}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Grades</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/teacher/profile" class="nav-link {{Request::url() == url('/teacher/profile') ? 'active':''}}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Teaching Loads</p>
                        </a>
                    </li>
                </ul>
            </li> --}}
            {{-- <li class="nav-item">
                <a href="/hr/index" class="nav-link {{Request::url() == url('/hr/index') ? 'active':''}}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>HR</p>
                </a>
            </li> --}}
    
    {{-- <li class="nav-item has-treeview 
    {{ Request::url() == url('enrollmentReport') 
        || Request::url() == url('cashtransReport') 
    
    ? 'menu-open':''}}">
        <a href="#" class="nav-link 
            {{ request()->is('enrollmentReport') 
                || request()->is('cashtransReport') 
               
                ? 'active':''}}
        ">
            <i class="nav-icon far fa-envelope"></i>
            <p>
            Reports
            <i class="fas fa-angle-left right" style="right: 5%;
            top: 28%;"></i>
            </p>
        </a>
        <ul class="nav nav-treeview udernavs ">
            <li class="nav-item">
                <a href="/enrollmentReport" class="nav-link {{Request::url() == url('/enrollmentReport') ? 'active':''}}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Enrollment</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/cashtransReport" class="nav-link {{Request::url() == url('/cashtransReport') ? 'active':''}}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Cash Transactions</p>
                </a>
            </li>
        </ul>
    </li> --}}
   
     <!-- <li class="nav-item">
        <a class="{{Request::url() == url('/admingetrooms') ? 'active':''}} nav-link" href="/admingetrooms">
            <i class="nav-icon fa fa-door-open"></i>
            <p>
                Rooms
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a class="{{Request::url() == url('/adminloadholidays') ? 'active':''}} nav-link" href="/adminloadholidays">
            <i class="nav-icon fa fa-thumbtack"></i>
            <p>
                School Calendar
            </p>
        </a>
    </li>
    <li class="nav-header">SETUP</li>
    <li class="nav-item">
        <a class="{{Request::url() == url('/manageschoolyear') ? 'active':''}} nav-link" href="/manageschoolyear">
            <i class="nav-icon fab fa-pushed"></i>
            <p>
                School Year
            </p>
        </a>
    </li>
    {{-- <li class="nav-item">
        <a class=" nav-link" href="#">
            <i class="nav-icon fab fa-pushed"></i>
            <p>
                SCHOOL INFORMATION
            </p>
        </a>
    </li> --}}

    <li class="nav-item">
        <a class="{{Request::url() == url('/truncanator') ? 'active':''}} nav-link" href="/truncanator">
            <i class="nav-icon fa fa-chart-line"></i>
            <p>
                Truncanator
            </p>
        </a>
    </li>
    {{-- <li class="nav-item has-treeview ">
        <a href="#" class="nav-link ">
            <i class="nav-icon far fa-envelope"></i>
            <p>
            Debugger
            <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview ">
            <li class="nav-item">
                <a href="/studentUserDebugger" class="nav-link ">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Student</p>
                </a>
            </li>
        </ul>
    </li> --}}
    {{-- <li class="nav-item has-treeview ">
        <a href="#" class="nav-link ">
            <i class="nav-icon far fa-calendar-times"></i>
            <p>
            Calendar
            <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview ">
            <li class="nav-item">
                <a href="/adminloadholidays" class="nav-link ">
                    <i class="fas fa-glass-cheers nav-icon"></i>
                    <p>Holidays</p>
                </a>
            </li>
        </ul>
        <ul class="nav nav-treeview ">
            <li class="nav-item">
                <a href="/adminloadholidays" class="nav-link ">
                    <i class="fas fa-glass-cheers nav-icon"></i>
                    <p>Activity</p>
                </a>
            </li>
        </ul>
    </li> --}} -->
</ul>
</nav>


