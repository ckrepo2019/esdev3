
<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

<div class="row d-flex align-items-stretch p-4">
    @if($data[0]->count > 0)
        @foreach ($data[0]->data as $section)
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
               
                <div class="card bg-light w-100 mb-2">
                    <div class="ribbon-wrapper ribbon-lg">
                        @if($section->session == 1)
                            <div class="ribbon bg-primary">
                        @elseif($section->session == 2)
                            <div class="ribbon bg-warning">
                        @elseif($section->session == 3)
                            <div class="ribbon bg-danger">
                        @else
                            <div class="ribbon bg-success">                           
                        @endif
                      
                            @if($section->session == 1)
                                Morning
                            @elseif($section->session == 2)
                                Afternoon
                            @elseif($section->session == 3)
                                Evening
                            @else
                                Whole day                         
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-2">
                    <div class="row">
                        <div class="col-7">
                        <h2 class="lead">
                            <b> {{$section->levelname}}
                                <br>
                                <span class="h6">{{Str::limit($section->sectionname, $limit = 15, $end = '...')}}<span>
                            </b>
                        </h2>
                        @if($section->lastname!=null)
                            <p class="text-muted text-sm"><b>Class Adviser</b><br>{{$section->lastname}}, {{explode(' ',trim($section->firstname))[0]}} <br>{{$section->roomname}}</p>
                        @else
                            <p class="text-muted text-sm"><b>Class Adviser</b><br>No Adviser Assigned<br>{{$section->roomname}}</p>
                        @endif
                        
                        </div>
                        <div class="col-5 text-center p-2" >
                         
                        </div>
                    </div>
                    </div>
                    <a href="/principalPortalSectionProfile/{{Crypt::encrypt($section->id)}}" class="card-footer bg-info text-center pt-2 pb-2"><span class="text-white">More info </span><i class=" text-white fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endforeach 
    @else
        <p class="w-100 text-center">No Results Found</p>
    @endif
</div>