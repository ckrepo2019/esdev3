<div class="modal fade" id="facultystaffModal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title">FACULTY AND STAFF FORM</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                  </div>
                  <form method="GET" action="{{Request::url() == url('/facultystaff/college')?route('facultystaff.college.create'):route('facultystaff.colleg.update', [Str::slug($facultystaffInfo->lastname.','.$facultystaffInfo->firstname)]) }}">
                        <div class="modal-body">

                              @foreach($inputs as $input)

                                    @if($input->type == 'input')
                                          <label>{{$input->label}}</label>
                                          <div class="form-group">
                                                <input name="{{$input->name}}" class="form-control" placeholder="{{$input->label}}" onkeyup="this.value = this.value.toUpperCase();">
                                          </div>
                                    @else
                                          <label>{{$input->label}}</label>
                                          <select class="form-control select2" id="course" name="{{$input->name}}" data-placeholder="{{$input->label}}" style="width: 100%;">
                                                <label>{{$input->label}}</label>
                                                @foreach (DB::table($input->table)->where('deleted','0')->get() as $courses)
                                                      <option value="{{Str::slug($courses->courseDesc,'-')}}">{{$courses->courseDesc}}</option>
                                                @endforeach
                                          </select>
                                    @endif

                              @endforeach
                              {{-- <div class="form-group">
                                    <label>FIRST NAME</label>
                                    <div class="form-group">
                                          <input value="{{old('firstname')}}" name="firstname" class="form-control" placeholder="firstname" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                    <label>MIDDLE NAME</label>
                                    <div class="form-group">
                                          <input value="{{old('middlename')}}" name="middlename" class="form-control" placeholder="middlename" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                    <label>LAST NAME</label>

                                    <div class="form-group">
                                          <input value="{{old('lastname')!=null?@old('lastname'):}}" name="lastname" class="form-control" placeholder="lastname" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                    <label>COURSE</label>
                                    <select class="form-control select2" id="course" name="collegeDesc" data-placeholder="SELECT COLLEGE" style="width: 100%;">
                                         <option value="">SELECT COLLEGE</option>
                                         @if(Request::url() == url('/facultystaff/college'))
                                                @foreach (DB::table('college_courses')->where('deleted','0')->get() as $courses)
                                                      <option value="{{Str::slug($courses->courseDesc,'-')}}">{{$courses->courseDesc}}</option>
                                                @endforeach
                                         @else
                                                @foreach (DB::table('college_courses')->where('deleted','0')->get() as $courses)
                                                      @if($college->collegeDesc == $facultystaffInfo->collegeDesc)
                                                            <option selected value="{{Str::slug($college->collegeDesc,'-')}}">{{$college->collegeDesc}}</option>
                                                      @else
                                                            <option value="{{Str::slug($college->collegeDesc,'-')}}">{{$college->collegeDesc}}</option>
                                                      @endif
                                                @endforeach
                                         @endif
                                    </select>
                              </div> --}}
                             
                        </div>

                        <div class="modal-footer justify-content-between">
                              <button 
                              onClick="this.form.submit this.disabled=true;"
                              class="btn {{Request::url() == url('/facultystaff/college')?'btn-primary':'btn-success' }} ">
                              {{Request::url() == url('/facultystaff/college')?'CREATE':'UPDATE' }}
                              </button>
                        </div>
                  <form>
            </div>
      </div>
</div>