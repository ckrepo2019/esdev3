
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
          <span class="brand-text font-weight-light" style="position: absolute;top: 50%;font-size: 16px!important;color:#ffc107"><b>GUIDANCE</b></span>
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
                <img class="profile-user-img img-fluid img-circle" src="{{asset($picurl)}}" onerror="this.onerror=null; this.src='{{asset($avatar)}}'" alt="User Image" width="100%" style="width:130px; border-radius: 12% !important;">
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
                <li class="nav-item">
                <li class="nav-header">Admssion Test</li>
        

                    <li class="nav-item">
						<a class="{{Request::url() == url('/admissiontest/index') ? 'active':''}} nav-link" href="/admissiontest/index">
							<i class="nav-icon fa fa-id-card"></i>
							<p>
                                Activate Test
							</p>
						</a>
					</li>


                    <li class="nav-item">
						<a class="{{Request::url() == url('/admissiontest/createblade') ? 'active':''}} nav-link" href="/admissiontest/createblade">
							<i class="nav-icon fa fa-id-card"></i>
							<p>
                                Create Test
							</p>
						</a>
					</li>

                    <li class="nav-item">
						<a class="{{Request::url() == url('/admissiontest/activate') ? 'active':''}} nav-link" href="/admissiontest/activate">
							<i class="nav-icon fa fa-id-card"></i>
							<p>
                                Applicant 
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


