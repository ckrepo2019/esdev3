
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label>School ID</label>
                        <input type="number" class="form-control" id="editinput-schoolid" value="{{$recordinfo->schoolid}}"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label>School Name</label>
                        <input type="text" class="form-control" id="editinput-schoolname" value="{{$recordinfo->schoolid}}"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label>School Address</label>
                        <input type="text" class="form-control" id="editinput-schooladdress" value="{{$recordinfo->schooladdress}}"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label>Select School Year</label>
                        <select class="form-control select2" id="editselect-sy">
                            <option value="0" @if($recordinfo->syid == 0) selected @endif>Not on this selection</option>
                            @foreach($schoolyears as $schoolyear)
                                <option value="{{$schoolyear->syid}}" @if($recordinfo->syid == $schoolyear->syid) selected @endif>{{$schoolyear->sydesc}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6" id="editdiv-customsy">
                        <label>Custom School Year</label>
                        <input type="text" class="form-control" id="editinput-sy" @if($recordinfo->syid == 0) value="{{$recordinfo->sydesc}}" @endif/>
                        <small id="editsmall-inputsy" class="text-danger">*Please fill in custom school year</small>
                    </div>
                    <small id="editsmall-selectsy" class="text-danger col-md-12">*Please select school year. If not on the selection, please specify in the next highlighted field</small>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label>Select Semester</label>
                        <select class="form-control select2" id="editselect-sem">
                            @foreach(DB::table('semester')->where('deleted','0')->get() as $semester)
                                <option value="{{$semester->id}}" @if($recordinfo->syid == $semester->id) selected @endif>{{$semester->semester}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label>Select Course</label>
                        <select class="form-control select2" id="editselect-course">
                            <option value="0"@if($recordinfo->courseid == 0) selected @endif>Not on this selection</option>
                            @foreach($courses as $course)
                                <option value="{{$course->id}}"@if($recordinfo->courseid == $course->id) selected @endif>{{$course->courseDesc}}</option>
                            @endforeach
                        </select>
                        <small id="editsmall-selectcourse" class="text-danger">*Please select course. If not on the selection, please specify in the next highlighted field </small>
                    </div>
                </div>
                <div class="row mb-2" id="editdiv-customcourse">
                    <div class="col-md-12">
                        <label>Custom Course</label>
                        <input type="text" class="form-control" id="editinput-coursename" @if($recordinfo->courseid == 0) value="{{$recordinfo->coursename}}" @endif/>
                        <small id="editsmall-inputcoursename">*Please fill in custom course</small>
                    </div>
                </div>
                <script>
                    @if($recordinfo->syid == 0)
                        $('#editsmall-selectsy').show();
                        $('#editsmall-inputsy').show();
                        $('#editdiv-customsy').show();
                    @else
                        $('#editsmall-selectsy').hide();
                        $('#editsmall-inputsy').hide();
                        $('#editdiv-customsy').hide();
                    @endif
                    @if($recordinfo->courseid == 0)
                        $('#editsmall-selectcourse').show();
                        $('#editsmall-inputcoursename').show();
                        $('#editdiv-customcourse').show();
                    @else
                        $('#editsmall-selectcourse').hide();
                        $('#editsmall-inputcoursename').hide();
                        $('#editdiv-customcourse').hide();
                    @endif
                </script>