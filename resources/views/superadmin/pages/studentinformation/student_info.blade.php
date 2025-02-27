<div class="modal fade" id="profile-modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header bg-info">
              <h5 class="modal-title">CHANGE STUDENT PHOTO</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
              </button>
          </div>
              <div class="modal-body">
                      <div id="demo"></div>
                      <input type="file" name="studpic" id="studpic" class="form-control" accept=".png, .jpg, .jpeg" required>
                      <span class="invalid-feedback" role="alert" hidden>
                      </span>
              </div>
              <div class="modal-footer justify-content-between">
                  <button  id="updateimage" class="btn btn-info savebutton">UPDATE STUDENT PICTURE</button>
              </div>
          </div>
      </div>
</div>

  
<div class="col-md-3">
      <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                  <div class="text-center">
                  @php
                        $randomnum = rand(1, 4);
                        if($student_profile->gender == 'FEMALE'){
                            $avatar = 'avatars/S(F) '.$randomnum.'.png';
                        }
                        else{
                            $avatar = 'avatars/S(M) '.$randomnum.'.png';
                        }
                  @endphp
                  <img class="profile-user-img img-fluid img-circle" 
                        src="{{asset($student_profile->picurl)}}?random={{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHMMSS')}}" 
                        onerror="this.onerror=null; this.src='{{asset($avatar)}}'"
                        alt="User profile picture"
                        id="studentpicture">
                  </div>
                  <h3 class="profile-username text-center">{{$student_profile->firstname}}</h3>
                  <p class="text-muted text-center">{{$student_profile->lastname}}</p>
                  <hr>
                  <button data-toggle="modal"  data-target="#profile-modal" class="btn btn-primary btn-block mt-2">UPDATE STUDENT PHOTO</button>
                  <hr>
                  {{-- <p>{{asset($student_profile->picurl)}</p> --}}
            </div>
            </div>
</div>
<div class="col-md-9">
      <div class="card">
            <div class="card-header p-2">
            <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">Profile</a></li>
                  <li class="nav-item"><a class="nav-link " href="#enrollment" data-toggle="tab">Enrollment</a></li>
                  <li class="nav-item"><a class="nav-link" href="#grades" data-toggle="tab">Grades</a></li>
                  <li class="nav-item"><a class="nav-link" href="#billing" data-toggle="tab">Billing</a></li>
            </ul>
            </div>
            <div class="card-body">
                  <div class="tab-content">
                        <div class="tab-pane" id="enrollment">
                              <table class="table">
                                    <thead>
                                          <tr>
                                                <th>School Year</th>
                                                <th>Grade Level</th>
                                                <th>Section</th>
                                                <th>Enrollment</th>
                                                <th>Promotion</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          @foreach ($student_enrollment as $item)
                                                <tr>
                                                      <td>{{$item->sydesc}}</td>
                                                      <td>{{$item->levelname}}</td>
                                                      <td>{{$item->sectionname}}</td>
                                                      <td>{{$item->description}}</td>
                                                      <td>
                                                            @if($item->promotionstatus == 0)
                                                                  NOT PROMOTED
                                                            @elseif($item->promotionstatus == 1)
                                                                  PROMOTED
                                                            @elseif($item->promotionstatus == 2)
                                                                  RETAINED
                                                            @elseif($item->promotionstatus == 3)
                                                                  FAILED
                                                            @endif
                                                      </td>
                                                </tr>
                                          @endforeach
                                       
                                    </tbody>
                              </table>
                        </div>
                        <div class="tab-pane active" id="profile">
                              <h5>Personal Information</h5>
                              <hr>  
                              <div class="row">
                                    <div class="text-muted col-md-3">
                                          <p class="text-sm">First Name
                                                <b class="d-block">{{$student_profile->firstname}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Mother's Occupation
                                                <b class="d-block">{{$student_profile->middlename}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Mother's Contact Number
                                                <b class="d-block">{{$student_profile->lastname}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-1">
                                          <p class="text-sm">Suffix
                                                <b class="d-block">{{$student_profile->suffix}}</b>
                                          </p>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="text-muted col-md-3">
                                          <p class="text-sm">Gender
                                                <b class="d-block">{{$student_profile->gender}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Birthdate
                                                <b class="d-block">{{\Carbon\Carbon::create($student_profile->dob)->isoFormat('MMMM DD, YYYY')}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Contact Number
                                                <b class="d-block">{{$student_profile->contactno}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-1">
                                    </div>
                              </div>
                              <div class="row">
                                  
                                    <div class="text-muted col-md-3">
                                          <p class="text-sm">Religion
                                                <b class="d-block">{{$student_profile->student_religion}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Nationality
                                                <b class="d-block">{{$student_profile->student_nationality}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Email
                                                <b class="d-block">{{$student_profile->semail}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-1">
                                    </div>
                              </div>
                              <hr>
                              <h5>Address</h5>
                              <hr>  
                              <div class="row">
                                    <div class="text-muted col-md-6">
                                          <p class="text-sm">Street
                                                <b class="d-block">{{$student_profile->street}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-6">
                                          <p class="text-sm">Barangay
                                                <b class="d-block">{{$student_profile->barangay}}</b>
                                          </p>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="text-muted col-md-6">
                                          <p class="text-sm">City
                                                <b class="d-block">{{$student_profile->city}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-6">
                                          <p class="text-sm">Province
                                                <b class="d-block">{{$student_profile->province}}</b>
                                          </p>
                                    </div>
                              </div>
                              <hr>
                              <h5>Parent Information</h5>
                              <hr>  
                              <div class="row">
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Default
                                                <b class="d-block">
                                                      @if($student_profile->ismothernum == 1)
                                                            MOTHER
                                                      @elseif($student_profile->isfathernum == 1)
                                                            FATHER
                                                      @elseif($student_profile->isguardannum == 1)
                                                            GUARDIAN
                                                      @else
                                                            NOT SPECIFIED

                                                      @endif
                                                </b>
                                          </p>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Mother's Name
                                                <b class="d-block">{{$student_profile->mothername}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Mother's Occupation
                                                <b class="d-block">{{$student_profile->moccupation}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Mother's Contact Number
                                                <b class="d-block">{{$student_profile->mcontactno}}</b>
                                          </p>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Father's Name
                                                <b class="d-block">{{$student_profile->fathername}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Father's Occupation
                                                <b class="d-block">{{$student_profile->foccupation}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Father's Contact Number
                                                <b class="d-block">{{$student_profile->fcontactno}}</b>
                                          </p>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Guardian's Name
                                                <b class="d-block">{{$student_profile->guardianname}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Guardian's Occupation
                                                <b class="d-block">{{$student_profile->foccupation}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Guardian's Contact Number
                                                <b class="d-block">{{$student_profile->gcontactno}}</b>
                                          </p>
                                    </div>
                              </div>
                              <hr>
                              <h5>Enrollment Information</h5>
                              <hr>  
                              <div class="row">
                                    <div class="text-muted col-md-3">
                                          <p class="text-sm">SID
                                                <b class="d-block">{{$student_profile->sid}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-3">
                                          <p class="text-sm">Grade Level
                                                <b class="d-block">{{$student_profile->levelname}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Section
                                                <b class="d-block">{{$student_profile->sectionname}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md-2">
                                          <p class="text-sm">Status
                                                <b class="d-block">{{$student_profile->studstatus}}</b>
                                          </p>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="text-muted col-md-4">
                                          <p class="text-sm">Strand
                                                <b class="d-block">{{$student_profile->strandname}}</b>
                                          </p>
                                    </div>
                                    <div class="text-muted col-md">
                                          <p class="text-sm">Block
                                                <b class="d-block">{{$student_profile->blockname}}</b>
                                          </p>
                                    </div>
                                   
                              </div>
                              <div class="row">
                                    <div class="text-muted col-md-12">
                                          <p class="text-sm">Course
                                                <b class="d-block">{{$student_profile->courseDesc}}</b>
                                          </p>
                                    </div>
                              </div>
                              
                              
                        </div>
                  </div>
            </div>
      </div>
</div>


<script>
      $(document).ready(function(){

          
          
          $(function () {
              $('.select2').select2()
          });
          

          var studid = '{{$student_profile->id}}'
          var sid = '{{$student_profile->sid}}'

          $uploadCrop = $('#demo').croppie({
              enableExif: true,
              viewport: {
                  width: 304,
                  height: 289,
              },
              boundary: {
                  width: 304,
                  height: 289
              }
          });
          $("#studpic").change(function(){
              var selectedFile = this.files[0];
              var idxDot = selectedFile.name.lastIndexOf(".") + 1;
              var extFile = selectedFile.name.substr(idxDot, selectedFile.name.length).toLowerCase();
              if (extFile == "jpg" || extFile == "jpeg" || extFile == "png") {
                  var reader = new FileReader();
                  reader.onload = function (e) {
                      $uploadCrop.croppie('bind', {
                          url: e.target.result
                      }).then(function(){
                          console.log('jQuery bind complete');
                      });
                  }
                  reader.readAsDataURL(this.files[0]);
              } else {
                  Swal.fire({
                      title: 'INVALID FORMAT',
                      type: 'error',
                      showConfirmButton: false,
                      timer: 1500
                  })
                  $(this).val('')
              }
          });
          $('#updateimage').click(function (ev) {
              $uploadCrop.croppie('result', {
                  type: 'canvas',
                  size: 'viewport'
              }).then(function (resp) {
                  $.ajax({
                      url: "/student/information/upload/idpic",
                      type: "POST",
                      data: {
                              "_token": "{{ csrf_token() }}",
                              "image"     :   resp,
                              "studid"    :   studid,
                              "sid"       :   sid,
                          },
                      success: function (data) {

                        $("#studentpicture")[0].src = '{{asset($student_profile->picurl)}}'+"?random="+new Date().getTime()
                        console.log('{{asset($student_profile->picurl)}}'+"?random="+new Date().getTime())
                        console.log()
                        $('#profile-modal').modal('hide')
                        // $.ajax({
                        //       type:'GET',
                        //       url:'/student/information/profile',
                        //       data:{
                        //             studid: studid,
                        //       },
                        //       success:function(data){
                        //             $('#studen_information').empty()
                        //             $('#studen_information').append(data)
                                  
                        //       }
                        // })

                        //   if(data[0].status == 0){
                        //       $('#studpic').addClass('is-invalid')
                        //       $('.invalid-feedback').removeAttr('hidden')
                        //       $('.invalid-feedback')[0].innerHTML = '<strong>'+data[0].errors.image[0]+'</strong>'
                        //   }
                        //   else{
                        //       // window.location.reload(true);
                        //   }
                      },
                  });
              });
          });
        
      })
  </script>