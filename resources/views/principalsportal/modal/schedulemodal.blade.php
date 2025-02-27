<div class="card-header card-header-tab-animation ">
    <ul class="nav nav-justified">
        @foreach($days[0] as $key=>$day)
            @if($key==0)
                <li class="nav-item"><a data-toggle="tab" href="#tab-eg115-{{$key}}" class="nav-link show active">{{$day}}</a></li>
            @else 
                <li class="nav-item"><a data-toggle="tab" href="#tab-eg115-{{$key}}" class="nav-link">{{$day}}</a></li>
            @endif
        @endforeach
    </ul>
</div>
<div class="card-body">
    <div class="tab-content">
        @foreach($days[0] as $key=>$day)
            @if($key==0)
                <div class="tab-pane show active" id="tab-eg115-{{$key}}" role="tabpanel">
            @else
                <div class="tab-pane" id="tab-eg115-{{$key}}" role="tabpanel">
            @endif
                <div class="row">
                @foreach($values as $value)
                    @foreach($value['schedule'] as $schedule)
                        @if($day==$schedule->day_name)
                            <div class="col-md-6 col-xl-4">
                                <div class="card mb-3 widget-content">
                                    <div class="widget-content-outer">
                                            <div class="widget-numbers text-warning fsize-2">{{$value['subjectName']}}</div>
                                            <div class="widget-subheading"><i class="pe-7s-clock mr-2"> </i>{{$schedule->stime}}</div>
                                            <div class="widget-subheading"><i class="pe-7s-map-marker mr-2">  </i>{{$schedule->roomname}}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>


