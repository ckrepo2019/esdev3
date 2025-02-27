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
        <li class="nav-header text-warning">SCHOOL SETUP</li>
        <li class="nav-item">
            <a  class="{{Request::url() == url('/colleges') ? 'active':''}} nav-link" href="/colleges">
                <i class="nav-icon fa fa-home"></i>
                <p>
                    COLLEGES
                </p>
            </a>
        </li>
        {{-- <li class="nav-item">
            <a  class="{{Request::url() == url('/courses') ? 'active':''}} nav-link" href="/courses">
                <i class="nav-icon fa fa-home"></i>
                <p>
                    COURSES
                </p>
            </a>
        </li> --}}
        {{-- <li class="nav-item">
            <a  class="{{Request::url() == url('/subjects/college') ? 'active':''}} nav-link" href="/subjects/college">
                <i class="nav-icon fa fa-home"></i>
                <p>
                    SUBJECTS
                </p>
            </a>
        </li> --}}
        {{-- <li class="nav-item">
            <a  class="{{Request::url() == url('/enrollement/college') ? 'active':''}} nav-link" href="/enrollement/college">
                <i class="nav-icon fa fa-home"></i>
                <p>
                    ENROLLMENT
                </p>
            </a>
        </li> --}}
        {{-- <li class="nav-item">
            <a  class="{{Request::url() == url('/prospectus/college') ? 'active':''}} nav-link" href="/prospectus/college">
                <i class="nav-icon fa fa-home"></i>
                <p>
                    PROSPECTUS
                </p>
            </a>
        </li> --}}
        {{-- <li class="nav-item">
            <a  class="{{Request::url() == url('/facultystaff/college') ? 'active':''}} nav-link" href="/facultystaff/college">
                <i class="nav-icon fa fa-home"></i>
                <p>
                    FACULTY
                </p>
            </a>
        </li> --}}
		<li class="nav-item">
			<a  class="{{Request::url() == url('/school-calendar') ? 'active':''}} nav-link" href="/school-calendar">
				<i class="nav-icon fas fa-calendar"></i>
				<p>
				School Calendar
				</p>
			</a>
		</li>
       
    </ul>
</nav>