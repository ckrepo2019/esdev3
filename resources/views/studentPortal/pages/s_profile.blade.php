@php
      if(auth()->user()->type == 7){
            $extend = 'studentPortal.layouts.app2';
      }else if(auth()->user()->type == 9){
            $extend = 'parentsportal.layouts.app2';
      }
@endphp

@extends($extend)


@section('pagespecificscripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/croppie/croppie.css')}}">
    <style>
        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            border: 0 !important;
        }
    </style>

@endsection


@section('content')



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
                    <input type="file" name="studpic" id="studpic" class="form-control mb-2" accept=".png, .jpg, .jpeg" required>
                    <span class="mt-4"><i ><b>Allowed File Type:</b> png, jpg, jpeg</i></span>
            </div>
            <div class="modal-footer justify-content-between">
                <button  id="updateimage" class="btn btn-info savebutton">Update</button>
            </div>
        </div>
    </div>
</div>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Student Profile</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Student Profile</li>
            </ol>
            </div>
        </div>
    </div>
</section>
<section class="content pt-0">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                  <div class="card shadow">
                        <div class="card-body box-profile">
                              <div class="text-center" id="image_holder">
                              
                              </div>
                              <p></p>
                              <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                      <b>Student ID</b> <a class="float-right" id="label_sid"></a>
                                    </li>
                              </ul>
                              <button data-toggle="modal"  data-target="#profile-modal" class="btn btn-primary btn-block mt-2"><b>Update Student Photo</b></button>
                        </div>
                  </div>
            </div>
            <div class="col-md-9">
                  <div class="card shadow">
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="mb-3">Enrollment Information</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong><i class="fas fa-book mr-1"></i>Grade Level</strong>
                                        <p class="text-muted" id="grade_level"></p>
                                    </div>
                                    <div class="col-md-3" id="lrn_holder" hidden>
                                        <strong><i class="fas fa-book mr-1"></i>LRN</strong>
                                        <p class="text-muted" id="lrn"></p>
                                    </div>
                                    <div class="col-md-6" hidden  id="strand_holder">
                                        <strong><i class="fas fa-book mr-1"></i>Strand</strong>
                                        <p class="text-muted" id="input_strand"></p>
                                    </div>
                                    <div class="col-md-6" hidden  id="course_holder">
                                        <strong><i class="fas fa-book mr-1"></i>Course</strong>
                                        <p class="text-muted" id="course"></p>
                                    </div>
                                    
                                </div>
                                <hr  class="mt-0">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <h5>Personal Information</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>First Name</strong>
                                        <p class="text-muted" id="first_name">--</p>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Middle Name</strong>
                                        <p class="text-muted" id="middle_name">--</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Last Name</strong>
                                        <p class="text-muted" id="last_name">--</p>
                                    </div>
                                    <div class="col-md-1">
                                        <strong>Suffix</strong>
                                        <p class="text-muted" id="suffix">--</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Date of birth</strong>
                                        <p class="text-muted" id="dob">--</p>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fas fa-book mr-1"></i>Gender</strong>
                                        <p class="text-muted" id="gender">--</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-book mr-1"></i>Nationality</strong>
                                        <p class="text-muted" id="nationality">--</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-book mr-1"></i>Mobile Number</strong>
                                        <p class="text-muted" id="contact_number">--</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-book mr-1"></i>Email Address</strong>
                                        <p class="text-muted" id="email">--</p>
                                    </div>
                                </div>
                                <hr  class="mt-0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="mb-3">Address</h5>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-book mr-1"></i>Street</strong>
                                        <p class="text-muted" id="street">--</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-book mr-1"></i>Barangay</strong>
                                        <p class="text-muted" id="barangay">--</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-book mr-1"></i>City/Municipality</strong>
                                        <p class="text-muted" id="city">--</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-book mr-1"></i>City</strong>
                                        <p class="text-muted" id="province">--</p>
                                    </div>
                                </div>
                                <hr   class="mt-0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="mb-3">Parent / Guardian Information</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-book mr-1"></i>Father's Full Name</strong>
                                        <p class="text-muted" id="father_name">--</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-book mr-1"></i>Father's Occupation</strong>
                                        <p class="text-muted" id="father_occupation">--</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-book mr-1"></i>Father's Contact Number</strong>
                                        <p class="text-muted" id="father_contact_number">--</p>
                                    </div>
                              </div>
                              <hr class="mt-0">
                              <div class="row">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-book mr-1"></i>Mother's Full Maiden Name</strong>
                                        <p class="text-muted" id="mother_name">--</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-book mr-1"></i>Mother's Occupation</strong>
                                        <p class="text-muted" id="mother_occupation">--</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-book mr-1"></i>Mother's Contact Number</strong>
                                        <p class="text-muted" id="mother_contact_number">--</p>
                                    </div>
                                </div>
                                <hr  class="mt-0">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-book mr-1"></i>Guardian's Full Name</strong>
                                        <p class="text-muted" id="guardian_name">--</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-book mr-1"></i>Relationship to Student</strong>
                                        <p class="text-muted" id="guardian_relation">--</p>
                                    </div>
                                    <div class="col-md-4">
                                        <strong><i class="fas fa-book mr-1"></i>Guardian's Contact Number</strong>
                                        <p class="text-muted" id="guardian_contact_number">--</p>
                                    </div>
                                </div>
                                <hr  class="mt-0">
                                <div class="row" id="incaseholder">
                                    <div class="col-md-12 ">
                                            <label>In case of emergency ( Recipient for News, Announcement and School Info)</label>
                                    </div>
                                    <div class="col-md-4">
                                            <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="father" name="incase" value="1" disabled>
                                                <label for="father">Father
                                                </label>
                                            </div>
                                    </div>
                                    <div class="col-md-4">
                                            <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="mother" name="incase" value="2" disabled>
                                                <label for="mother">Mother
                                                </label>
                                            </div>
                                    </div>
                                    <div class="col-md-4">
                                            <div class="icheck-success d-inline">
                                                <input class="form-control" type="radio" id="guardian" name="incase" value="3" disabled>
                                                <label for="guardian">Guardian
                                                </label>
                                            </div>
                                    </div>
                                </div>
                        </div>
                  </div>
            </div>
      </div>
    </div>
</section>



<script>
     $(document).ready(function(){

        get_student_info()
        function get_student_info(){

            $.ajax({
                    type:'GET',
                    url:'/student/enrollment/record/profile/info',
                    success:function(data) {

                        $('#student_fullname').text(data[0].student)
                        $('#cur_glevel').text(data[0].levelname)

                        $('#grade_level').text(data[0].levelname)
                        $('#lrn').text(data[0].lrn)

                        $('#first_name').text(data[0].firstname != null ? data[0].firstname : '--')
                        $('#middle_name').text(data[0].middlename  !=  null ? data[0].middlename : '--')
                        $('#last_name').text(data[0].lastname  !=  null ? data[0].lastname : '--')
                        $('#dob').text(data[0].dob  !=  null ? data[0].dob : '--')
                        $('#nationality').text(data[0].nationalitytext  !=  null ? data[0].nationalitytext : '--')
                        $('#gender').text(data[0].gender  !=  null ? data[0].gender : '--')
                        $('#suffix').text(data[0].suffix  !=  null ? data[0].suffix : '--')
                        $('#email').text(data[0].semail  !=  null ? data[0].semail : '--')
                        $('#contact_number').text(data[0].contactno  !=  null ? data[0].contactno : '--')

                        $('#street').text(data[0].street  != null ? data[0].street : '--')
                        $('#barangay').text(data[0].barangay  != null ? data[0].barangay : '--')
                        $('#city').text(data[0].city  != null ? data[0].city : '--')
                        $('#province').text(data[0].province  != null ? data[0].province : '--')
                       
                        $('#father_name').text(data[0].fathername  != null ? data[0].fathername : '--')
                        $('#father_occupation').text(data[0].foccupation  != null ? data[0].foccupation : '--')
                        $('#father_contact_number').text(data[0].fcontactno  != null ? data[0].fcontactno : '--')
                        $('#mother_name').text(data[0].mothername  != null ? data[0].mothername : '--')
                        $('#mother_occupation').text(data[0].moccupation  != null ? data[0].moccupation : '--')
                        $('#mother_contact_number').text(data[0].mcontactno  != null ? data[0].mcontactno : '--')
                        $('#guardian_name').text(data[0].guardianname  != null ? data[0].guardianname : '--')
                        $('#guardian_relation').text(data[0].guardianrelation  != null ? data[0].guardianrelation : '--')
                        $('#guardian_contact_number').text(data[0].gcontactno  != null ? data[0].gcontactno : '--')

                        $('#label_sid').text(data[0].sid)
                        $('#label_lrn').text(data[0].lrn)

                        if(data[0].levelid == 14 || data[0].levelid == 15){
                            $('#strand_holder').removeAttr('hidden')
                            $('#input_strand').text(data[0].strandname  != null ? data[0].strandname : '--')
                        }

                        if(data[0].levelid >= 17 && data[0].levelid <= 21){
                            $('#course_holder').removeAttr('hidden')
                            $('#course').text(data[0].courseDesc  != null ? data[0].courseDesc : '--')
                        }else{
                            $('#lrn_holder').removeAttr('hidden')
                        }

                        var onerror_url = @json(asset('dist/img/download.png'));
                       
                        if(data[0].picurl != null){
                            var picurl = data[0].picurl.replace('jpg','png')+"?random="+new Date().getTime()
                            var image = '<img width="100%" src="/'+picurl+'" onerror="this.src=\''+onerror_url+'\'" alt="" class="img-circle img-fluid" >'
                        }else{

                            if(data[0].gender == 'MALE'){
                                var image = '<img width="100%" src="/'+ 'avatars/T(M) 2.png'+"?random="+new Date().getTime()+'" alt="" class="img-circle img-fluid" >'
                            }else{
                                var image = '<img width="100%" src="/'+ 'avatars/T(F) 4.png'+"?random="+new Date().getTime()+'" alt="" class="img-circle img-fluid" >'
                            }
                            
                        }

                        console.log(image)
                    
                        $('#image_holder')[0].innerHTML = image

                        if(data[0].ismothernum == 1){
                                $("#mother").prop("checked", true)
                                $('#mother_contact_number').attr('required')
                        }
                        else if(data[0].isfathernum == 1){
                                $("#father").prop("checked", true)
                                $('#father_contact_number').attr('required')
                        }
                        else{
                                $("#guardian").prop("checked", true)
                                $('#guardian_contact_number').attr('required')
                        }
                    }
            })
        }
       
    })
</script>

<script src="{{asset('plugins/croppie/croppie.js')}}"></script>


<script>

    $(document).ready(function(){
        
        // $(function () {
        //     $('.select2').select2()
        // });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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

        $(document).on('click','#updateimage', function (ev) {
            $uploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (resp) {
                $.ajax({
                    url: "/student/enrollment/record/profile/update/photo",
                    type: "POST",
                    data: {
                            "image"     :   resp,
                        },
                    success: function (data) {
                        if(data[0].status == 0){
                            $('#studpic').addClass('is-invalid')
                            $('.invalid-feedback').removeAttr('hidden')
                            $('.invalid-feedback')[0].innerHTML = '<strong>'+data[0].errors.image[0]+'</strong>'
                        }
                        else{
                            window.location.reload(true);
                        }
                    },
                });
            });
        });
        
        $(document).on('click','#update_student_info',function(){
            $('#update_student_info_modal').modal()
        })
    })
</script>

@endsection
