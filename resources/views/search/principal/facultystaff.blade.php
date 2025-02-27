<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

@if($data[0]->count != 0 )
    <div class="row d-flex align-items-stretch">
        @foreach ($data[0]->data as $teacher)
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch ">
                <div class="card bg-light w-100">
                    <div class="card-body pt-2 pb-2">
                    <div class="row">
                        <div class="col-7">
                        <h2 class="lead"><b> {{strtoupper(explode(' ',trim($teacher->lastname))[0])}}<span class="h6"><br>{{strtoupper($teacher->firstname)}}</span></b></h2>
                        <p class="text-muted text-sm"><b>Employee ID</b><br>{{$teacher->tid}} </p>
                        {{-- @if($teacher->usertypeid == 1)
                            <span class="text-warning" style="text-shadow: 1px 1px 2px #000"><b>TEACHER</b></span>
                        @elseif($teacher->usertypeid == 2)
                            <span class="text-warning" style="text-shadow: 1px 1px 2px #000"><b>PRINCIPAL</b></span>
                        @endif --}}
                        <span class="text-warning" style="text-shadow: 1px 1px 2px #000"><b>{{$teacher->utype}}</b></span>
                        <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li class="small"><span class="fa-li"></span></li>
                        </ul>
                        </div>
                        <div class="col-5 text-center"  >
                            <img
                             src="{{asset($teacher->picurl)}}" 
                             onerror="this.src='{{asset('dist/img/download.png')}}'"
                             alt="" 
                             class="img-circle img-fluid">
                        </div>
                    </div>
                    </div>
                    <a href="/principalPortalTeacherProfile/{{$teacher->id}}" class="card-footer bg-info text-center p-0"><span class="text-white">More info </span><i class=" text-white fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endforeach 
    </div>
@else

    <p class="w-100 text-center">No Results Found</p>

@endif