<div class="modal fade" id="subjectModal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title">COURSE FORM</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                  </div>
                  <form method="GET" action="{{Request::url() == url('/subjects/college')?route('subject.college.create'):route('subject.college.update', [Str::slug($subjectInfo->subjDesc)]) }}">
                        @csrf
                        <div class="modal-body">
                              <div class="form-group">
                                    <label>COURSE</label>
                                    <select class="form-control select2" id="course" name="courseDesc" data-placeholder="SELECT COURSE" style="width: 100%;">
                                         <option value="">SELECT COURSE</option>
                                         @if(Request::url() == url('/subjects/college'))
                                                @foreach (DB::table('college_courses')->where('deleted','0')->get() as $course)
                                                      <option value="{{Str::slug($course->courseDesc,'-')}}">{{$course->courseDesc}}</option>
                                                @endforeach
                                         @else
                                                @foreach (DB::table('college_courses')->where('deleted','0')->get() as $course)
                                                      @if($course->courseDesc == $subjectInfo->courseDesc)
                                                            <option selected value="{{Str::slug($course->courseDesc,'-')}}">{{$course->courseDesc}}</option>
                                                      @else
                                                            <option value="{{Str::slug($course->courseDesc,'-')}}">{{$course->courseDesc}}</option>
                                                      @endif
                                                @endforeach
                                         @endif
                                    </select>
                              </div>
                              <div class="form-group">
                                    <label>SUBJECT DESCRIPTION</label>
                                    <input value="{{Request::url() == url('/subjects/college')?'':$subjectInfo->subjDesc }}" placeholder="SUBJECT DESCRIPTION" class="form-control" name="subjDesc" onkeyup="this.value = this.value.toUpperCase();" >
                              </diV>
                              <div class="form-group">
                                    <label>SUBJECT CODE</label>
                                    <input value="{{Request::url() == url('/subjects/college')?'':$subjectInfo->subjCode }}" placeholder="SUBJECT CODE" class="form-control" name="subjCode" onkeyup="this.value = this.value.toUpperCase();" >
                              </diV>
                              <div class="form-group">
                                    <label>SUBJECT UNITS</label>
                                    <input value="{{Request::url() == url('/subjects/college')?'':$subjectInfo->subjUnit }}" placeholder="SUBJECT UNITS" class="form-control" name="subjUnit" onkeyup="this.value = this.value.toUpperCase();" >
                              </diV>
                        </div>

                        <div class="modal-footer justify-content-between">
                              <button 
                              onClick="this.form.submit this.disabled=true;"
                              class="btn {{Request::url() == url('/subjects/college')?'btn-primary':'btn-success' }} ">
                              {{Request::url() == url('/subjects/college')?'CREATE':'UPDATE' }}
                              </button>
                        </div>
                  <form>
            </div>
      </div>
</div>