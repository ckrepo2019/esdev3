

@if(count($courses) > 0)
    @foreach($courses as $course)
            <div class="card mb-4 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Program: {{$course->courseDesc}}</h5>
                </div>
                <div class="card-body">
                    <p>Overall Percentage: {{$course->overallpoints}}</p>
                    <ul class="list-group">
                        @foreach($course->setups as $setup)
                        <li class="list-group-item">{{$setup->setup}}: {{$setup->result}}% ({{$setup->status}})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
    @endforeach
@else


<div class="card mb-4 shadow">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">No Data</h5>
    </div>
</div>





@endif




        