
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
                  {{-- <li class="nav-item">
                      <a  class="{{Request::url() == url('/home') ? 'active':''}} nav-link" href="/home">
                          <i class="nav-icon fa fa-home"></i>
                          <p>
                          Home
                          </p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="{{Request::url() == url('/superadmin/view/studentaccounts') ? 'active':''}} nav-link" href="/superadmin/view/studentaccounts">
                          <i class="nav-icon fa fa-chart-line"></i>
                          <p>
                              STUDENT ACCOUNTS
                          </p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="{{Request::url() == url('/superadmin/view/paymentoptions') ? 'active':''}} nav-link" href="/superadmin/view/paymentoptions">
                          <i class="nav-icon fa fa-chart-line"></i>
                          <p>
                              PAYMENT OPTIONS
                          </p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="{{Request::url() == url('/superadmin/view/schoolinfo') ? 'active':''}} nav-link" href="/superadmin/view/schoolinfo">
                          <i class="nav-icon fa fa-chart-line"></i>
                          <p>
                              SCHOOL INFO
                          </p>
                      </a>
                  </li>
				  <li class="nav-item">
                    <a  class="{{Request::url() == url('/school-calendar') ? 'active':''}} nav-link" href="/school-calendar">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                        School Calendar
                        </p>
                    </a>
                </li>
                  <li class="nav-item">
                      <a class="nav-link" href="/accessibility">
                          <i class="nav-icon fa fa-chart-line"></i>
                          <p>
                              USER ACCESSIBILITY
                          </p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="/superadmin/usersblade?take=10">
                          <i class="nav-icon fa fa-chart-line"></i>
                          <p>
                              PASSWORD RESETER
                          </p>
                      </a>
                  </li>
                  <li class="nav-item has-treeview">
                      <a href="#" class="nav-link">
                          <i class="nav-icon fas fa-layer-group"></i>
                          <p>
                              TRUNCANATOR
                          <i class="fas fa-angle-left right" style="right: 5%;
                      top: 28%;"></i>
                         
                          </p>
                      </a>
                      <ul class="nav nav-treeview udernavs">
                          <li class="nav-item">
                              <a href="/superadmin/truncate/level4" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>LEVEL 4</p>
                              </a>
                          </li>
                      </ul>
                  </li> --}}
                  @foreach (
                              DB::table('useraccessibility')
                                    ->join('useraccesslist',function($join){
                                          $join->on('useraccessibility.access','=','useraccesslist.id');
                                          $join->where('deleted','0');
                                    }) 
                                    ->get()  as $item)
                  
                              <li class="nav-item">
                                    <a class="nav-link" href="{{$item->url}}">
                                    <i class="nav-icon fa fa-chart-line"></i>
                                    <p>
                                          {{$item->module}}
                                    </p>
                                    </a>
                              </li>
                      
                  @endforeach

                 
              </ul>
          </nav>
      </div>
      <li class="nav-item">
      <a class="nav-link" href="/admingetrooms">
          <img class="essentiellogo" src="{{asset('assets\images\essentiel.png')}}" alt="">
      </a>
      </li>
      <li class="nav-item">
      <a class="nav-link" href="/admingetrooms">
          <img class="cklogo" src="{{asset('assets\images\CK_Logo.png')}}" alt="">
      </a>
      </li>
  </aside>
  
  
  