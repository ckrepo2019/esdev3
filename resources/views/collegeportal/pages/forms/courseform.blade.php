<div class="modal fade" id="courseModal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title">COURSE FORM</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                  </div>
                  <form method="GET" action="{{Request::url() == url('/courses')?route('course.create'):route('course.update', [Str::slug($courseInfo->courseDesc)]) }}">
                        @csrf
                        <div class="modal-body">
                              <div class="form-group">
                                    <label>COLLEGE</label>
                                    <select class="form-control select2" id="college" name="collegeDesc" data-placeholder="SELECT COLLEGE" style="width: 100%;">
                                         <option value="">SELECT COLLEGE</option>
                                         @if(Request::url() == url('/courses'))
                                                @foreach (DB::table('college_colleges')->where('deleted','0')->get() as $college)
                                                      <option value="{{Str::slug($college->collegeDesc,'-')}}">{{$college->collegeDesc}}</option>
                                                @endforeach
                                         @else
                                                @foreach (DB::table('college_colleges')->where('deleted','0')->get() as $college)
                                                      @if($college->collegeDesc == $courseInfo->collegeDesc)
                                                            <option selected value="{{Str::slug($college->collegeDesc,'-')}}">{{$college->collegeDesc}}</option>
                                                      @else
                                                            <option value="{{Str::slug($college->collegeDesc,'-')}}">{{$college->collegeDesc}}</option>
                                                      @endif
                                                @endforeach
                                         @endif
                                    </select>
                              </div>
                              <div class="form-group">
                                    <label>COURSE DESCRIPTION</label>
                                    <input value="{{Request::url() == url('/courses')?'':$courseInfo->courseDesc }}" placeholder="COURSE DESCRIPTION" class="form-control" name="courseDesc" onkeyup="this.value = this.value.toUpperCase();" id="collegeDesc">
                              </diV>
                        </div>

                        <div class="modal-footer justify-content-between">
                              <button 
                              onClick="this.form.submit this.disabled=true;"
                              class="btn {{Request::url() == url('/courses')?'btn-primary':'btn-success' }} ">
                              {{Request::url() == url('/courses')?'CREATE':'UPDATE' }}
                              </button>
                        </div>
                  <form>
            </div>
      </div>
</div>