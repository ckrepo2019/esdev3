@php
$getSchoolInfo = DB::table('schoolinfo')
    ->select('region','division','district','schoolname','schoolid')
    ->get();
$syid = DB::table('sy')
    ->where('isactive','1')
    ->first();
$getProgname = DB::table('teacher')
    ->select(
        'sectiondetail.syid',
        'academicprogram.id as acadprogid',
        'academicprogram.progname'
        )
    ->join('sectiondetail','teacher.id','=','sectiondetail.teacherid')
    ->join('sections','sectiondetail.sectionid','=','sections.id')
    ->join('gradelevel','sections.levelid','=','gradelevel.id')
    ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
    ->where('teacher.userid',auth()->user()->id)
    ->where('sections.deleted','0')
    ->where('sectiondetail.deleted','0')
    ->where('gradelevel.deleted','0')
    ->distinct('progname')
    ->get();
    
    
@endphp
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
   
    <div >
        <a href="#" class="brand-link nav-bg">
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
     
         @php
            if(Auth::check()) {

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
            }else{
                $avatar = 'assets/images/avatars/unknown.png';
                $teacher_profile = (object)[
                    'picurl'=>$avatar
                ];
            }
        @endphp
        <div class="row pt-2">
            <div class="col-md-12">
              <div class="text-center">
                <img class="profile-user-img img-fluid img-circle" src="{{asset($teacher_profile->picurl)}}?random={{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')}}" onerror="this.onerror = null, this.src='{{asset($avatar)}}'" alt="User Image" width="100%" style="width:130px; border-radius: 12% !important;">
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
        @php
            $urls_setups = Db::table('urls_setup')
                            ->leftJoin('urls',function($join){
                                $join->on('urls_setup.url_id','=','urls.id');
                            })
                            ->where('usertype',Session::get('currentPortal'))
                            ->where('urls_setup.deleted',0)
                            ->where('urls_setup.active',1)
                            ->orderBy('sort')
                            ->select(
                                'urls_setup.*',
                                'url',
                                'desc',
                                'header_desc',
                                'url_active'
                            )
                            ->get();

          
            foreach($urls_setups as $item){
                $item->fullurl = url($item->url);
                if($item->url_id == null){
                    $item->url_active = 1;
                }
            }

            $urls_setups = collect($urls_setups)->where('url_active',1)->values();

            $urls_setups_2 = collect($urls_setups)->toArray();
        @endphp

        @foreach(collect($urls_setups)->whereNull('url_group')->values() as $urls_setup)
            @if($urls_setup->url_id == null)
                @php
                    $check_details = collect( $urls_setups)
                                        ->where('url_group',$urls_setup->id)
                                        ->toArray();

                @endphp
                @if(count($check_details) > 0)
                    <li class="nav-item has-treeview {{ in_array(Request::url(), array_column($check_details, 'fullurl')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ in_array(Request::url(), array_column($check_details, 'fullurl')) ? 'active' : '' }}"  >
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>
                                {{$urls_setup->header_desc}}
                            <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview ">
                            @foreach($check_details as $check_detail)
                                @php
                                    $check_details_2 = collect( $urls_setups)
                                                        ->where('url_group',$check_detail->id)
                                                        ->toArray();
                
                                @endphp

							

                                @if(count($check_details_2) > 0)
                                    <li class="nav-item has-treeview {{ in_array(Request::url(), array_column($check_details_2, 'fullurl')) ? 'menu-open' : '' }}">
                                        <a href="#" class="nav-link {{ in_array(Request::url(), array_column($check_details_2, 'fullurl')) ? 'active' : '' }}"  >
                                            <i class="nav-icon far fa-circle nav-icon"></i>
                                            <p>
                                                {{$check_detail->header_desc}}
                                            <i class="fas fa-angle-left right"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview ">
                                            @foreach($check_details_2 as $check_detail_2)
                                                <li class="nav-item">
                                                    <a  class="{{Request::fullUrl() == url($check_detail_2->url) ? 'active':''}} nav-link" href="{{$check_detail_2->url}}">
                                                        <i class="nav-icon far fa-dot-circle nav-icon"></i>
                                                        <p>
                                                            {{$check_detail_2->desc}}
                                                        </p>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else 
                                    <li class="nav-item">
                                        <a  class="{{Request::fullUrl() == url($check_detail->url) ? 'active':''}} nav-link" href="{{$check_detail->url}}">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                {{$check_detail->desc}}
                                            </p>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li class="nav-header">{{$urls_setup->header_desc}}</li>
                @endif
            @else
                <li class="nav-item">
                    <a  class="{{Request::fullUrl() == url($urls_setup->url) ? 'active':''}} nav-link" href="{{$urls_setup->url}}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            {{$urls_setup->desc}}
                        </p>
                    </a>
                </li>
            @endif
            
        @endforeach
		@include('components.privsidenav')
    </ul>
</nav>
  
    </div>
   
  </aside>