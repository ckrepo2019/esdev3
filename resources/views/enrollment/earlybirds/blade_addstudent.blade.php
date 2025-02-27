
<div class="card">
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-md-5 col-10 mb-2">
                <label>Select student</label>
                <select class="form-control" id="selectedstudent">
                    <option value="">Select a student</option>
                    @if(count($students)>0)
                        @foreach($students as $student)
                            <option value="{{$student->id}}">{{$student->name_showlast}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-1 col-2 mb-2">
                <label>&nbsp;</label><br/>
                <button type="button" class="btn btn-info btn-block" id="btn-createstudent">&nbsp;<i class="fa fa-plus"></i>&nbsp;</button>
            </div>
            <div class="col-md-3 col-4 mb-2">
                <label>S.Y</label>
                <select class="form-control" id="selectedstudentsy">
                        @foreach ($schoolyears as $schoolyear)
                            <option value="{{$schoolyear->id}}" @if($schoolyear->isactive == 1) selected @endif>{{$schoolyear->sydesc}}</option>
                        @endforeach
                </select>
            </div>
            <div class="col-md-3 col-4 mb-2">
                <label>Semester</label>
                <select class="form-control" id="selectedstudentsem">
                    {{-- <option value="">Select semester</option> --}}
                    @foreach ($semesters as $semester)
                        <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                    @endforeach
                </select>
            </div>
        {{-- </div>
        <div class="row mb-2"> --}}
            <div class="col-md-3 col-4 mb-2">
                <label>Select Grade Level</label>
                <select class="form-control" id="selectedstudentlevel" readonly="true">
                    <option value="">Select Grade Level</option>
                    @foreach ($gradelevels as $gradelevel)
                        <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <label>Select Strand</label>
                <select class="form-control" id="selectedstudentstrand">

                </select>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <label>Select Course</label>
                <select class="form-control" id="selectedstudentcourse">

                </select>
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label><br/>
                <button type="button" class="btn btn-success btn-block" id="btn-addsubmit"><i class="fa fa-share"></i> Submit</button>
            </div>
        </div>
    </div>
</div>