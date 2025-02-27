@php
$getSchoolInfo = DB::table('schoolinfo')
    ->select('region','division','district','schoolname','schoolid')
    ->get();
$syid = DB::table('sy')
    ->where('isactive','1')
    ->first();
$getProgname = DB::table('teacher')
    ->select('teacher.id','sections.levelid','gradelevel.levelname','sections.id as sectionid','sections.sectionname','academicprogram.progname')
    ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
    ->join('sections','sectiondetail.sectionid','=','sections.id')
    ->join('gradelevel','sections.levelid','=','gradelevel.id')
    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
    ->where('teacher.userid',auth()->user()->id)
    ->where('sectiondetail.syid',$syid->id)
    ->where('sections.deleted','0')
    ->get();
    
@endphp
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar">
    <!-- Brand Logo -->
    <a href="/home" class="brand-link">
      <img src="{{asset('dist/img/AdminLTELogo.png')}}"
           alt="AdminLTE Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a> -->
    <div class="ckheader">
        <a href="#" class="brand-link sidehead">
            <img src="{{asset(DB::table('schoolinfo')->first()->picurl)}}"
               {{-- alt="{{DB::table('schoolinfo')->first()->abbreviation}}" --}}
               class="brand-image img-circle elevation-3"
               style="opacity: .8">
          <span class="brand-text font-weight-light" style="position: absolute;top: 6%;">{{DB::table('schoolinfo')->first()->abbreviation}}</span>
          <span class="brand-text font-weight-light" style="position: absolute;top: 50%;font-size: 16px!important;color:#ffc107"><b>TEACHER'S PORTAL</b></span>
        </a>
    </div>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block">{{auth()->user()->name}}</a>
        </div>
      </div> -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            @php
            $teacher_profile = Db::table('teacher')
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
                
            $teacher_info = Db::table('employee_personalinfo')
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
                ->where('employee_personalinfo.employeeid',$teacher_profile->id)
                ->get();
                $number = rand(1,3);
                if(count($teacher_info)==0){
                    $avatar = 'assets/images/avatars/unknown.png';
                }
                else{
                    if(strtoupper($teacher_info[0]->gender) == 'FEMALE'){
                        $avatar = 'avatar/T(F) '.$number.'.png';
                    }
                    else{
                        $avatar = 'avatar/T(M) '.$number.'.png';
                    }
                }
            @endphp
          <img src="{{asset($teacher_profile->picurl)}}" onerror="this.onerror = null, this.src='{{asset($avatar)}}'" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info pt-0" style="    margin-top: -7px;">
            <a href="#" class="d-block text-uppercase">{{auth()->user()->name}}</a>
            <h6 class="text-white m-0 text-warning">{{auth()->user()->email}}</h6>
        </div>
      </div>
      <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
                <!-- <li class="nav-header text-warning"><h4>TEACHER'S PORTAL</h4></li> -->
                <li class="nav-item">
                    <a href="/home"  id="dashboard" class="nav-link {{Request::url() == url('/home') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                            Home 
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/clinic/appointment/index"  id="dental" class="nav-link {{Request::url() == url('/clinic/appointment/index') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-file-medical"></i>
                        <p>
                            Create Appointment 
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/clinic/dental/index"  id="dental" class="nav-link {{Request::url() == url('/clinic/dental/index') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-teeth"></i>
                        <p>
                            Dental 
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/clinic/patients/index"  id="dental" class="nav-link {{Request::url() == url('/clinic/patients/index') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-id-card-alt"></i>
                        <p>
                            Patients 
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/clinic/medicalhistory/index"  id="medicalhistory" class="nav-link {{Request::url() == url('/clinic/medicalhistory/index') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-laptop-medical"></i>
                        <p>
                            Medical History 
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item has-treeview {{Request::url() == url('/students/advisory') || Request::url() == url('/students/bysubject') ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{Request::url() == url('/students/advisory') || Request::url() == url('/students/bysubject') ? 'active' : ''}}">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                                Students Information
                                <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ml-3 " >
                        <li class="nav-item">
                            <a href="/students/advisory" class="nav-link {{Request::url() == url('/students/advisory') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Advisory
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/students/bysubject" class="nav-link {{Request::url() == url('/students/bysubject') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    By Subject
                                </p>
                            </a>
                        </li>
                    </ul>
                </li> --}}
            </nav>
      <!-- /.sidebar-menu -->
    </div>
    <br/>
    <br/>
    <br/>
    <!-- /.sidebar -->
  </aside>