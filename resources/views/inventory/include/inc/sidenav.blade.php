
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
          <span class="brand-text font-weight-light" style="position: absolute;top: 50%;font-size: 16px!important;color:#ffc107"><b>INVENTORY CLERK</b></span>
      </a>
    </div>
    <div class="sidebar">
         @php
            $randomnum = rand(1, 4);
            $avatar = 'assets/images/avatars/unknown.png'.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss').'"';
            $picurl = DB::table('teacher')->where('userid',auth()->user()->id)->first()->picurl;
            $picurl = str_replace('jpg','png',$picurl).'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss').'"';
        @endphp
        <div class="row">
            <div class="col-md-12">
            <div class="text-center">
                <img class="profile-user-img img-fluid img-circle" src="{{asset($picurl)}}"" onerror="this.onerror=null; this.src='{{asset($avatar)}}'" alt="User Image" width="100%" style="width:130px; border-radius: 12% !important;">
            </div>
            </div>
        </div>
        <div class="row  user-panel">
            <div class="col-md-12 info text-center">
            <a class=" text-white mb-0 ">{{auth()->user()->name}}</a>
            <h6 class="text-warning text-center">{{auth()->user()->email}}</h6>
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

                @if (isset(DB::table('schoolinfo')->first()->withschoolfolder))
                    @if (DB::table('schoolinfo')->first()->withschoolfolder == 1)
                        <li class="nav-item">
                            <a class="{{ Request::url() == url('/schoolfolderv2/index') ? 'active' : '' }} nav-link"
                                href="/schoolfolderv2/index">
                                <i class="nav-icon fa fa-folder"></i>
                                <p>
                                    @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                        BCT Commons
                                    @else
                                        File Directory
                                    @endif
                                </p>
                            </a>
                        </li>
                    @endif
                @endif

                <li class="nav-item">
                    <a href="/hr/settings/notification/index" class="nav-link {{Request::url() == url('/hr/settings/notification/index') ? 'active' : ''}}">
                        <i class="nav-icon  fas fa-exclamation"></i>
                        <p>
                            Notification & Request
                            {{-- <span class="ml-2 badge badge-primary">2</span> --}}
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

                <li class="nav-item">
                    <a href="/inventory/inventory/view" class="nav-link {{(Request::Is('inventory/inventory/view')) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            Inventory
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview
                    {{(Request::Is('finance/purchasing/*')) ? 'menu-open' : ''}}
                    ">
                    <a href="" class="nav-link">
                        <i class="fas fa-calculator nav-icon"></i>
                        <p>
                        Purchasing
                        <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        {{-- <li class="nav-item">
                        <a href="{!! route('vendor')!!}" class="nav-link {{(Request::Is('finance/purchasing/vendor')) ? 'active' : ''}}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                            Vendor
                            </p>
                        </a>
                        </li> --}}
                        <li class="nav-item">
                        <a href="{!! route('purchaseorder')!!}" class="nav-link {{(Request::Is('finance/purchasing/purchaseorder')) ? 'active' : ''}}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                            Purchase Order
                            </p>
                        </a>
                        </li>
                        <li class="nav-item">
                        <a href="{!! route('receiving')!!}" class="nav-link {{(Request::Is('finance/purchasing/receiving')) ? 'active' : ''}}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                            Receiving
                            </p>
                        </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="/finance/stockcard/view" class="nav-link {{(Request::Is('finance/stockcard/view')) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>
                            Stock Card
                        </p>
                    </a>
                </li>

                <li class="nav-header text-warning">DOCUMENT TRACKING</li>
                <li class="nav-item">
                    <a href="/documenttracking"
                        class="nav-link {{ Request::url() == url('/documenttracking') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Document Tracking
                        </p>
                    </a>
                </li>



                <li class="nav-header text-warning">Employee Requirements</li>
                <li class="nav-item">
                    <a href="/hr/requirements/index"
                        class="nav-link {{ Request::fullUrl() === url('/hr/requirements/index') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-folder-open"></i>
                        <p>
                            Requirements
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
                                    ->where('type_active',1)
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


