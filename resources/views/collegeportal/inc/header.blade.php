<div class="app-header header-shadow header-text-light" style="background-color:#2bbf74 !important">
            <div class="app-header__logo">
               
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>    
            <div class="app-header__content">
                <div class="app-header-left">
                     
                </div>
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group mr-3">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            <i class="pe-7s-bell fsize-3"> </i>
                                            <span class="badge badge-danger pull-top" style="top: -11px;font-size:10px;padding:5px;position: absolute;margin-left: -12px;margin-bottom: 44px;">{{Session::get('unread')}}</span>

                                                    
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="min-width: 28rem !important; position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-180px, 44px, 0px);">
                                            @php
                                                $bg = '';
                                            @endphp
                                            @foreach (Session::get('announcement') as $item)
                                                @if($item->status==0)
                                                    @php
                                                        $bg = 'bg-light';
                                                    @endphp
                                                @else
                                                    @php
                                                        $bg = 'bg-white';
                                                    @endphp
                                                @endif
                                            <a href="/viewAnnouncement/{{$item->id}}" tabindex="0" class="dropdown-item align-center {{$bg}}" style="white-space:normal !important; word-break:break-all">{{$item->name}} Posted an announcement <br>last
                                             {{-- {{(new Carbon\Carbon($item->created_at))->diffInSeconds((new Carbon\Carbon)->now())}} --}}
                                             {{(new Carbon\Carbon($item->created_at))->isoFormat('MMM DD, YYYY')}}
                                            </a>
                                             
                                            @endforeach
                                            
                                            {{-- <div tabindex="-1" class="dropdown-divider"></div> --}}
                                            <a href="/viewAllAnnouncement" tabindex="0" class="text-center" style="display: block;padding: 10px;">View All</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content-left  ml-3 header-user-info">
                                    <div class="widget-heading">
                                        Alina Mclourd
                                    </div>
                                    <div class="widget-subheading">
                                        VP People Manager
                                    </div>
                                </div>
                                <div class="widget-content-right header-user-info ml-3">
                                    <button type="button" class="btn-shadow p-1 btn btn-primary btn-sm show-toastr-example">
                                        <i class="fa text-white fa-calendar pr-1 pl-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>        
                </div>
                
            </div>
        </div>