@php
                                                
    $coursesitem = DB::table('college_courses')
                    ->where('deleted', 0)
                    ->get()

@endphp



<div class="card">
    <div class="card-header">
        <h3 class="card-title">Accept Applicant</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
        <label for="courseName">Course Name</label>
        <select class="form-control form-control-alt select2" id="selectCollegeCourse"> 
            @foreach($coursesitem as $item)
                <option value="{{ $item->id }}" > {{ $item->courseDesc}} </option>
            @endforeach
        </select>
        </div>
        <div class="form-group">
        <label for="courseName">Status</label>
        <select class="form-control form-control-alt select2" id="selectStatus"> 
                <option value="0" > Permanent </option>
                <option value="1" > Probationary </option>
        </select>
        </div>
        <div class="form-group">
        <label for="courseDescription">Remarks</label>
        <textarea class="form-control" id="remarks" placeholder="Enter course remarks" rows="3"></textarea>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="button" class="btn btn-primary" data-id="0" id="saveChangesBtn">Save Changes</button>
    </div>
</div>


<h5 class="mb-2"> Program Recommended </h5>
@if(count($courses) > 0)
    <div id="setupDiv" class="mt-2" style="max-height: 300px; overflow-y: auto;  overflow-x: hidden;"> 
    @foreach($courses as $course)
            <div class="card mb-4 shadow">
                <div class="card-header bg-success text-white" data-toggle="collapse" data-target="#collapseCard{{$course->id}}">
                    <h5 class="mb-0">Program: {{$course->courseDesc}} <span class="float-right collapse-indicator">+</span></h5>
                </div>
                <div id="collapseCard{{$course->id}}" class="collapse">
                    <div class="card-body">
                        <p>Overall Percentage: {{$course->overallpoints}}</p>
                        <ul class="list-group">
                            @foreach($course->setups as $setup)
                                <li class="list-group-item">{{$setup->setup}}: {{$setup->result}}% ({{$setup->status}})</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
    @endforeach
    </div>
@else


<div class="card mb-4 shadow">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">No Data</h5>
    </div>
</div>





@endif

<script type="text/javascript">

        $('#selectCollegeCourse').select2({
                        width: '100%',
        });


</script>




        