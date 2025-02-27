
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
          <span class="brand-text font-weight-light" style="position: absolute;top: 50%;font-size: 16px!important;color:#ffc107"><b>DEAN'S PORTAL</b></span>
      </a>
    </div>
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
                <li class="nav-item has-treeview {{Request::url() == url('/principalPortalSchoolCalendar') || Request::url() == url('/principal/calendar/setup') ? 'menu-open':''}}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fa fa-calendar-alt "></i>
                        <p>
                        School Calendar
                        <i class="fas fa-angle-left right" style="right: 5%;
                        top:28%;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs ">
                        <li class="nav-item">
                            <a href="/principalPortalSchoolCalendar" class="nav-link {{Request::url() == url('/principalPortalSchoolCalendar') ? 'active':''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Calendar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/principal/calendar/setup" class="nav-link {{Request::url() == url('/principal/calendar/setup') ? 'active':''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Calendar Setup</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @if(Session::get('isSeniorHighPrincipal'))
                    <li class="nav-item has-treeview {{Request::url() == url('/principalPortalSchedule') || Request::url() == url('/prinicipalblockinfo/') || Request::url() == url('/principalPortalBlocks') || Request::url() == url('/principalPortalSectionProfile/?') ? 'menu-open active':''}}">
                        <a href="#" class="nav-link  {{Request::url() == url('/principalPortalSchedule') || Request::url() == url('/prinicipalblockinfo/') || Request::url() == url('/principalPortalBlocks') || Request::url() == url('/principalPortalSectionProfile/?') ? 'active':''}}">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>
                            Section / Block
                            <i class="fas fa-angle-left right" style="right: 5%;
                        top: 28%;"></i>
                            <span class="right badge badge-warning">{{Session::get('blockCount')+Session::get('sectionCount')}}</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
                            <li class="nav-item">
                                <a href="/principalPortalSchedule" class="nav-link 
                                    {{Request::url() == url('/principalPortalSchedule') ||
                                    request()->is('principalPortalSectionProfile/*')
                                    ? 'active':''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Sections <span class="right badge badge-primary">{{Session::get('sectionCount')}}</span></p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/principalPortalBlocks" class="nav-link {{Request::url() == url('/principalPortalBlocks') ? 'active':''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Blocks<span class="right badge badge-primary">{{Session::get('blockCount')}}</span></p>
                                    
                                </a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a  href="/principalPortalSchedule" 
                            class="nav-link 
                            {{Request::url() == url('/principalPortalSchedule')||
                            request()->is('principalPortalSectionProfile/*')
                            ? 'active':''}}">
                            <i class="fas fa-layer-group nav-icon"></i>
                            <p>Sections</p>
                        </a>
                    </li>
                        
                @endif
                <li class="nav-item has-treeview 
                {{     request()->is('principalviewPSSubjects/*') 
                    || request()->is('principalviewGSSubjects/*') 
                    || request()->is('principalviewJHSubjects/*') 
                    || request()->is('principalviewSHSubjects/*') 
                        
                        ? 'menu-open':''}}">
                    <a href="#" class="nav-link  
                    {{  request()->is('principalviewPSSubjects/*') 
                        || request()->is('principalviewGSSubjects/*') 
                        || request()->is('principalviewJHSubjects/*') 
                        || request()->is('principalviewSHSubjects/*') 
                        || request()->is('viewsubjectInfo/*')
                        ? 'active':''}}">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>
                        Subjects
                        <i class="fas fa-angle-left right" style="right: 5%;
                        top: 28%;"></i>
                        <span class="right badge badge-warning">{{Session::get('pssubjectcount')+Session::get('gssubjectcount')+Session::get('jhsubjectcount')+Session::get('shsubjectcount')}}</span>
                        </p>
                    </a>
                    @if(Session::get('isPreSchoolPrinicpal'))
                        <ul class="nav nav-treeview udernavs ">
                            <li class="nav-item">
                                <a href="/principalviewPSSubjects/{{Crypt::encrypt('2')}}" class="nav-link {{request()->is('principalviewPSSubjects/*') ? 'active':''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pre-School
                                        <span class="right badge badge-primary">{{Session::get('pssubjectcount')}}</span>
                                    </p>
                                
                                </a>
                            </li>
                        </ul>
                    @endif
                    @if(Session::get('isGradeSchoolPrinicpal'))
                        <ul class="nav nav-treeview udernavs ">
                            <li class="nav-item">
                                <a href="/principalviewGSSubjects/{{Crypt::encrypt('3')}}" class="nav-link {{request()->is('principalviewGSSubjects/*') ? 'active':''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Grade School
                                        <span class="right badge badge-primary">{{Session::get('gssubjectcount')}}</span>
                                    </p>
                                    
                                </a>
                            </li>
                        </ul>
                    @endif
                    @if(Session::get('isJuniorHighPrinicpal'))
                        <ul class="nav nav-treeview udernavs ">
                            <li class="nav-item">
                                <a href="/principalviewJHSubjects/{{Crypt::encrypt('4')}}" class="nav-link {{request()->is('principalviewJHSubjects/*') ? 'active':''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Junior High School
                                        <span class="right badge badge-primary">{{Session::get('jhsubjectcount')}}</span>
                                    </p>
                                
                                </a>
                            </li>
                        </ul>
                    @endif
                    @if(Session::get('isSeniorHighPrincipal'))
                        <ul class="nav nav-treeview udernavs ">
                            <li class="nav-item">
                                <a href="/principalviewSHSubjects/{{Crypt::encrypt('5')}}" class="nav-link {{request()->is('principalviewSHSubjects/*') ? 'active':''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Senior High School
                                        <span class="right badge badge-primary">{{Session::get('shsubjectcount')}}</span>
                                    </p>
                            
                                </a>
                            </li>
                        </ul>
                    @endif
                </li>
                {{-- <li class="nav-item">
                    <a  class="{{Request::url() == url('/corprintingblade') ? 'active':''}} nav-link" href="/corprintingblade">
                        <i class="nav-icon fa fa-book"></i>
                        <p>
                            COR Printing
                        </p>
                    </a>
                </li> --}}
                <li class="nav-header text-warning">Your Portal</li>
                @php
                    $priveledge = DB::table('faspriv')
                                    ->join('usertype','faspriv.usertype','=','usertype.id')
                                    ->select('faspriv.*','usertype.utype')
                                    ->where('userid', auth()->user()->id)
                                    ->where('faspriv.deleted','0')
                                    ->where('faspriv.privelege','!=','0')
                                    ->get();

                    $usertype = DB::table('usertype')->where('deleted',0)->where('id',auth()->user()->type)->first();

                @endphp

                @foreach ($priveledge as $item)
                    @if($item->usertype != Session::get('currentPortal'))
                        <li class="nav-item">
                            <a class="nav-link portal" href="/gotoPortal/{{$item->usertype}}" id="{{$item->usertype}}">
                                <i class=" nav-icon fas fa-cloud"></i>
                                <p>
                                    {{$item->utype}}
                                </p>
                            </a>
                        </li>
                    @endif
                @endforeach

                @if($usertype->id != Session::get('currentPortal'))
                    <li class="nav-item">
                        <a class="nav-link portal" href="/gotoPortal/{{$usertype->id}}">
                            <i class=" nav-icon fas fa-cloud"></i>
                            <p>
                                {{$usertype->utype}}
                            </p>
                        </a>
                    </li>
                @endif
                
               
            </ul>
        </nav>
    </div>
  
</aside>


