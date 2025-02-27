
<aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar">
    <div class="ckheader">
      <a href="#" class="brand-link sidehead">
        @if( DB::table('schoolinfo')->first()->picurl !=null)
            <img src="{{asset(DB::table('schoolinfo')->first()->picurl)}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"  onerror="this.src='{{asset('assets/images/department_of_Education.png')}}'">
        @else
            <img src="{{asset('assets/images/department_of_Education.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"  >
        @endif
          <span class="brand-text font-weight-light" style="position: absolute;top: 6%;">
              {{DB::table('schoolinfo')->first()->abbreviation}}
            </span>
          <span class="brand-text font-weight-light" style="position: absolute;top: 50%;font-size: 16px!important;color:#ffc107"><b>Scholarchip Coordinator</b></span>
      </a>
    </div>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            <img src="../../dist/img/download.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info pt-0">
                <a href="#" class="d-block">{{strtoupper(auth()->user()->name)}}</a>
            <h6 class="text-white m-0 text-warning">Scholarchip Coordinator</h6>
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
                    <a  class="{{Request::fullUrl() == url('/collegeStudentMasterlist?blade=blade') ? 'active':''}} nav-link" href="/collegeStudentMasterlist?blade=blade">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Student Master List
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a  class="{{Request::fullUrl() == url('/collge/report/enrollment?blade=blade') ? 'active':''}} nav-link" href="/collge/report/enrollment?blade=blade">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                           Enrollment Report
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a  class="{{Request::url() == url('/corprintingblade') ? 'active':''}} nav-link" href="/corprintingblade">
                        <i class="nav-icon fa fa-book"></i>
                        <p>
                            COR Printing
                        </p>
                    </a>
                </li>
                <li class="nav-header text-warning">Your Portal</li>
                
                @php

                    $priveledge = DB::table('faspriv')
                                    ->join('usertype','faspriv.usertype','=','usertype.id')
                                    ->select('faspriv.*','usertype.utype')
                                    ->where('userid', auth()->user()->id)
                                    ->where('faspriv.deleted','0')
                                    ->where('faspriv.privelege','!=','0')
                                    ->get();

                @endphp

                @foreach ($priveledge as $item)
                    @if($item->usertype != Session::get('currentPortal'))
                        <li class="nav-item">
                            <a class="nav-link portal" href="/gotoPortal/{{$item->usertype}}" id="{{$item->usertype}}">
                                <i class="nav-icon fa fa-link"></i>
                                <p>
                                    {{$item->utype}}
                                </p>
                            </a>
                        </li>
                    @endif
                @endforeach
                
               
            </ul>
        </nav>
    </div>
  
</aside>


