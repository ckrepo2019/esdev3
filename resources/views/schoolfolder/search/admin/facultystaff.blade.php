<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

<div class="row d-flex align-items-stretch">
    @foreach ($data[0]->data as $teacher)
        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch ">
            <div class="card bg-light w-100">
                {{-- <div class="card-header text-muted border-bottom-0">
                  @if($teacher->usertypeid == 2 ) --}}
                      {{-- @php 
                          $acadprog = App\Models\Principal\SPP_AcademicProg::getPrincipalAcadProg($teacher->id)
                      @endphp
                      @if(count($acadprog))
                        @foreach ($acadprog  as $acadprogitem)
                          {{$acadprogitem->acadprogcode}} PRINCIPAL<br>
                        @endforeach
                      @else
                        NOT ASSIGNED
                      @endif --}}
                      {{-- {{$teacher->utype}}
                  @elseif($teacher->usertypeid != null && $teacher->usertypeid != 2)
                    {{$teacher->utype}}
                  @else
                    NOT ASSIGNED
                  @endif
                </div> --}}
                @if($teacher->isactive == 0)
                  <div class="ribbon-wrapper ribbon-lg">
                    <div class="ribbon bg-danger">                           
                      INACTIVE            
                    </div>
                  </div>
                @endif
                <div class="card-body pt-2 pb-2">
                  <div class="row">
                      <div class="col-7">
                      @php
                        $countAcadprog = 0;
                      @endphp
                      @foreach (DB::table('teacheracadprog')->where('syid',DB::table('sy')->where('isactive',1)->first()->id)->where('teacherid',$teacher->id)->where('deleted','0')->get() as $item)
                        @if($item->acadprogid == 2)
                          <span class="badge badge-pill badge-primary right">P</span>
                        @elseif($item->acadprogid == 3)
                          <span class="badge badge-pill badge-danger right">G</span>
                        @elseif($item->acadprogid == 4)
                          <span class="badge badge-pill badge-secondary right">J</span>
                        @elseif($item->acadprogid == 5)  
                          <span class="badge badge-pill badge-success right">S</span>
                        @elseif($item->acadprogid == 6)  
                          <span class="badge badge-pill badge-warning right">C</span>
                        @endif
                        @php
                          $countAcadprog += 1;
                        @endphp
                      @endforeach

                      @if($countAcadprog == 0)
                        <span class="badge badge-pill badge-primary right">Non Teaching</span>
                      @endif
                       
                      <h2 class="lead"><b>{{strtoupper($teacher->lastname)}}<span class="h6"><br>{{strtoupper($teacher->firstname)}}</span></b></h2>
                      <p class="text-muted mb-0">
                        @if($teacher->usertypeid == 2 )
                          {{$teacher->utype}} 
                        @elseif($teacher->usertypeid != null && $teacher->usertypeid != 2)
                          {{$teacher->utype}}
                        @else
                          NOT ASSIGNED
                        @endif
                        <br>
                        {{$teacher->tid}}
                      </p>
                      {{-- <p class="text-muted text-sm"><b>License No. </b><br>{{$teacher->tid}} </p> --}}
                      {{-- <ul class="ml-4 mb-0 fa-ul text-muted">
                          <li class="small"><span class="fa-li"></span></li>
                      </ul> --}}
                      </div>
                      <div class="col-5 text-center"  >
                          <img
                          src="{{asset($teacher->picurl)}}" 
                          onerror="this.src='{{asset('dist/img/download.png')}}'"
                          alt="" class="img-circle img-fluid">
                      </div>
                  </div>
                </div>
                <a href="viewFacutlyInfo/{{$teacher->id}}" class="card-footer bg-info text-center p-0" id="{{$teacher->id}}"><span class="text-white">View info </span><i class=" text-white fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    @endforeach 
  </div>