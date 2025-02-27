
          
        <input type="hidden" class="form-control" id="edit-studentid" hidden>
        <div class="row">
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mac' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjhsti')
                <div class="col-md-12">
                <div class="profile">
                    <div class="p-2">
                        <div class="">
                            <center>
                                @php
                                    $number = rand(1,3);
                                    if($data->info->gender == null){
                                        $avatar = 'assets/images/avatars/unknown.png';
                                    }
                                    else{
                                        if(strtoupper($data->info->gender) == 'FEMALE'){
                                            $avatar = 'avatar/T(F) '.$number.'.png';
                                        }
                                        else{
                                            $avatar = 'avatar/T(M) '.$number.'.png';
                                        }
                                    }
                                @endphp
                                <div id="upload-demo-i" class="bg-white " style="width:200px;height:200px;">
                                        <img class="elevation-2" src="{{asset($data->info->picurl)}}" id="profilepic" style="width:200px;height:200px;"  onerror="this.onerror = null, this.src='{{asset($avatar)}}'" alt="User Avatar">
                                </div>
                            </center>
                        </div>
                    </div>
                </div>
                <br>
                <center>
                    <a href="#" class="edit-pic-icon" data-toggle="modal" data-target="#edit_profile_pic" style="color: black !important">
                        <i class="fas fa-edit" style="color: black !important"></i> Change profile picture
                    </a>
                </center>
                <br>
                </div>
            @endif
            <div class="col-12">
              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">LRN</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="edit-lrn" value="{{$data->info->lrn}}" placeholder="LRN">
                </div>
              </div>
            </div>
        </div>
          <div class="row">
              <div class="col-12">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">First Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="edit-firstname" value="{{$data->info->firstname}}" placeholder="First Name">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Middle Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="edit-middlename" value="{{$data->info->middlename}}" placeholder="Middle Name">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Last Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="edit-lastname" value="{{$data->info->lastname}}" placeholder="Last Name">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Suffix</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="edit-suffix" value="{{$data->info->suffix}}" placeholder="Suffix">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Gender</label>
                  <div class="col-sm-10">
                      <select class="form-control" id="edit-gender">
                        <option value="">Unspecified</option>
                        <option value="MALE" @if(strtolower($data->info->gender) == 'male') selected @endif>MALE</option>
                        <option value="FEMALE" @if(strtolower($data->info->gender) == 'female') selected @endif>FEMALE</option>
                      </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Birth Date</label>
                  <div class="col-sm-10">
                    <input type="date" class="form-control" id="edit-birthdate" value="{{$data->info->dob}}" placeholder="Birth Date">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Contact No.</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="edit-contactno" value="{{$data->info->contactno}}" placeholder="Contact No." minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Blood Type</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="edit-bloodtype" value="{{$data->info->bloodtype}}" placeholder="Blood Type" >
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Allergies</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" id="edit-allergies" placeholder="Allergies">{{$data->info->allergy}}</textarea>
                  </div>
                </div>
              </div>
          </div>
          <hr/>
          <div class="row">
              <div class="col-4">
                <label>Mother Tongue</label>
                <select class="form-control" id="edit-mothertongue">
                    <option value="">Select Mother Tongue</option>
                    @foreach($data->mothertongues as $mothertongue)
                        <option value="{{$mothertongue->id}}" @if($data->info->mtid == $mothertongue->id) selected @endif>{{$mothertongue->mtname}}</option>
                    @endforeach
                </select>
              </div>
              <div class="col-4">
                <label>IP</label>
                <select class="form-control" id="edit-ethnicgroup">
                    <option value="">Select Ethnic Group</option>
                    @foreach($data->ethnicgroups as $ethnicgroup)
                        <option value="{{$ethnicgroup->id}}" @if($data->info->egid == $ethnicgroup->id) selected @endif>{{$ethnicgroup->egname}}</option>
                    @endforeach
                </select>
              </div>
              <div class="col-4">
                <label>Religion</label>
                <select class="form-control" id="edit-religion">
                    <option value="">Select Religion</option>
                    @foreach($data->religions as $religion)
                        <option value="{{$religion->id}}"@if($data->info->religionid == $religion->id) selected @endif>{{$religion->religionname}}</option>
                    @endforeach
                </select>
              </div>
          </div>
          <hr/>
          <div class="row">
              <div class="col-12">
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">House #/Street/Sitio/Purok</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="edit-street" value="{{$data->info->street}}" placeholder="House #/Street/Sitio/Purok">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Barangay</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="edit-barangay" value="{{$data->info->barangay}}" placeholder="Barangay">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Municipality/City</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="edit-city" value="{{$data->info->city}}" placeholder="Municipality/City">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Province</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="edit-province" value="{{$data->info->province}}" placeholder="Province">
                        </div>
                    </div>
              </div>
          </div>
          <hr/>
          <div class="row mb-2">
            <div class="col-md-12">
              
              <label>Father's Name</label>
            </div>
              <div class="col-md-3">
                <input type="text" class="form-control" id="edit-ffname" value="{{$data->parentsinfo->ffname ?? ''}}" placeholder="First Name">
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" id="edit-fmname" value="{{$data->parentsinfo->fmname ?? ''}}" placeholder="Middle Name">
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" id="edit-flname" value="{{$data->parentsinfo->flname ?? ''}}" placeholder="Last Name">
              </div>
              <div class="col-md-1">
                <input type="text" class="form-control" id="edit-fsuffix" value="{{$data->parentsinfo->fsuffix ?? ''}}" placeholder="Suffix">
              </div>
              <div class="col-md-2">
                <input type="text" class="form-control" id="edit-fcontactno" value="{{$data->info->fcontactno ?? ''}}" placeholder="Mobile #" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true">
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-md-12">
                
                <label>Mother's Name</label>
              </div>
                <div class="col-md-3">
                  <input type="text" class="form-control" id="edit-mfname" value="{{$data->parentsinfo->mfname ?? ''}}" placeholder="First Name">
                </div>
                <div class="col-md-3">
                  <input type="text" class="form-control" id="edit-mmname" value="{{$data->parentsinfo->mmname ?? ''}}" placeholder="Middle Name">
                </div>
                <div class="col-md-3">
                  <input type="text" class="form-control" id="edit-mlname" value="{{$data->parentsinfo->mlname ?? ''}}" placeholder="Last Name">
                </div>
                <div class="col-md-1">
                  <input type="text" class="form-control" id="edit-msuffix" value="{{$data->parentsinfo->msuffix ?? ''}}" placeholder="Suffix">
                </div>
                <div class="col-md-2">
                  <input type="text" class="form-control" id="edit-mcontactno" value="{{$data->info->mcontactno ?? ''}}" placeholder="Mobile #" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true">
                </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              
              <label>Mother's Name</label>
            </div>
              <div class="col-md-3">
                <input type="text" class="form-control" id="edit-gfname" value="{{$data->parentsinfo->gfname ?? ''}}" placeholder="First Name">
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" id="edit-gmname" value="{{$data->parentsinfo->gmname ?? ''}}" placeholder="Middle Name">
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" id="edit-glname" value="{{$data->parentsinfo->glname ?? ''}}" placeholder="Last Name">
              </div>
              <div class="col-md-1">
                <input type="text" class="form-control" id="edit-gsuffix" value="{{$data->parentsinfo->gsuffix ?? ''}}" placeholder="Suffix">
              </div>
              <div class="col-md-2">
                <input type="text" class="form-control" id="edit-gcontactno" value="{{$data->info->gcontactno ?? ''}}" placeholder="Mobile #" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true">
              </div>
        </div>
          <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
          <script>
            $(document).ready(function(){
                
                // $('body').addClass('sidebar-collapse')
                $('#edit-contactno').inputmask({mask: "9999-999-9999"});
                $('#edit-fcontactno').inputmask({mask: "9999-999-9999"});
                $('#edit-mcontactno').inputmask({mask: "9999-999-9999"});
                $('#edit-gcontactno').inputmask({mask: "9999-999-9999"});
                
            });
            </script>