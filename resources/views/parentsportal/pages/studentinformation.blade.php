@extends('parentsportal.layouts.app2')

@section('pagespecificscripts')

    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .select2-selection{
            height: calc(2.25rem + 2px) !important;
        }
    </style>

@endsection


<meta name="csrf-token" content="{{ csrf_token() }}">

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


<div class="modal fade" id="update_student_info_modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">UPDATE STUDENT INFO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class=" row bg-primary pt-2 pb-1 pl-2 mb-2">
                    <h5>Student Guardian Information</h5>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Father's Name</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" id="fname" placeholder="Father's Name  ex. Lastname, Firstname" value="{{$sinfo->fathername}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Father's Occupation</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" id="foccu" placeholder="Father's Occupation" value="{{$sinfo->foccupation}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Father's Contact #</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" id="fcontactno" placeholder="Father's Contact #" value="{{$sinfo->fcontactno}}">
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Mother's Name</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" id="mname" placeholder="Mother's Name  ex. Lastname, Firstname" value="{{$sinfo->mothername}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Mother's Occupation</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" id="moccu" placeholder="Mother's Occupation" value="{{$sinfo->moccupation}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Mother's Contact #</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" id="mcontactno" placeholder="Mother's Contact #" value="{{$sinfo->mcontactno}}">
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Guardian's Name</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" id="gname" placeholder="Guardian's Name ex. Lastname, Firstname" value="{{$sinfo->guardianname}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Guardian's Relationship</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" id="grelation" placeholder="Guardian's Relationship" value="{{$sinfo->guardianrelation}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Guardian's Contact #</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" id="gcontactno" placeholder="Guardian's Contact #" value="{{$sinfo->gcontactno}}">
                    </div>
                </div>
               
                <div class=" row bg-primary pt-2 pb-1 pl-2 mb-2">
                    <h5>Student Address Information</h5>
                </div>
            
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Region</label>
                    <div class="col-sm-8">
                      <select class="form-control select2" name="region" id="region">
                         <option name="" id="">Select Region</option>
                          @foreach (DB::table('lib_region')->orderBy('prosort')->get() as $item)
                                <option value="{{$item->LHIO}}">{{$item->regiondesc}}</option>
                          @endforeach
                      </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Province</label>
                    <div class="col-sm-8">
                      <select class="form-control select2" name="province" id="province">
                          <option name="" id="">No Region selected</option>
                        
                      </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">City / Municipality / Subdivision</label>
                    <div class="col-sm-8">
                      <select class="form-control select2" name="citymun" id="citymun">
                            <option name="" id="">No Province selected</option>
                      </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Barangay</label>
                    <div class="col-sm-8">
                      <select class="form-control select2" name="barangay" id="barangay">
                            <option name="" id="">No City / Municipality selected</option>
                      </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Street / Lot & Block No.</label>
                    <div class="col-sm-8">
                        <input type="street" class="form-control" id="street" placeholder="Street / Lot & Block No.">
                    </div>
                </div>
               
            </div>
            <div class="modal-footer justify-content-between">
                <button  id="updateStudentInfo" class="btn btn-primary savebutton">UPDATE STUDENT INFO</button>
                <button type="button" class="btn btn-secondary"  data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@section('content')
<style>
    .imageprofile img {
        width: 200px;
        height: 200px;
    }
    .labell1 {
        border:none;
    }
    .labell {
        color: green;
        border-bottom: 1px solid #000;
    }
    label:not(.form-check-label):not(.custom-file-label) {
    font-weight: 400;
}
</style>


<div class="m-3 card">
    <div class="card-header border-0">
        <div class="d-flex justify-content-between">
            <h3 class="card-title"><i class="fas fa-edit"></i> Student Information</h3>
            <a class="btn btn-primary" href="#" id="update_student_info"> <i class="fas fa-edit"></i> Update Student Information</a>
        </div>
    </div>
    <div class="card-body">
    <div class="row">
    <div class="col-md-4">
        <div class="card card-success card-outline">
            <div class="card-body box-profile">
                <div class="text-center mb-3 imageprofile">
                    @php
                        $randomnum = rand(1, 4);
                        if(Session::get('studentInfo')->gender == 'FEMALE'){
                            $avatar = 'avatars/S(F) '.$randomnum.'.png';
                        }
                        else{
                            $avatar = 'avatars/S(M) '.$randomnum.'.png';
                        }
                    @endphp
                    <img class="profile-user-img img-fluid img-circle" 
                            src="{{asset(DB::table('studinfo')->where('id',Session::get('studentInfo')->id)->first()->picurl)}}" 
                            onerror="this.onerror=null; this.src='{{asset($avatar)}}'"
                            alt="User profile picture"
                            id="studentpicture">
                </div>
                <h3 class="profile-username text-center mb-0">{{strtoupper(Session::get('studentInfo')->firstname)}} {{strtoupper(Session::get('studentInfo')->lastname)}}</h3>

                <p class="text-muted text-center">GRADE - Section</p>
                <button data-toggle="modal"  data-target="#profile-modal" class="btn btn-primary btn-block mt-2">UPDATE STUDENT PHOTO</button>
                
            </div>
        </div>
    </div>
    <div class="col-md-8" style="font-size: 13px; font-weight: 600">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Student Information</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="address-tab" data-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="false">Address Information</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="pg-tab" data-toggle="tab" href="#pg" role="tab" aria-controls="profile" aria-selected="false">Parents/Guardian</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="mi-tab" data-toggle="tab" href="#mi" role="tab" aria-controls="contact" aria-selected="false">Medical Information</a>
    </li>
    {{-- <li class="nav-item">
        <a class="nav-link" id="ot-tab" data-toggle="tab" href="#ot" role="tab" aria-controls="contact" aria-selected="false">Other Information</a>
    </li> --}}
    </ul>
    <div class="tab-content" id="myTabContent">
    {{-- @foreach($sinfo as $item) --}}

        
        <div class="tab-pane fade show " id="address" role="tabpanel" aria-labelledby="home-tab">
            <div class="form-group row pt-2">
                <div class="col-sm-5 pl-2">
                    <label class="labell1 form-control"><b>Province:</b></label>
                </div>
                <div class="col-sm-7 pl-2 ">
                    <label class="labell form-control">{{$sinfo->province}}</label>
                </div>

                <div class="col-sm-5 pl-2">
                    <label class="labell1 form-control"><b>City:</b></label>
                </div>
                <div class="col-sm-7 pl-2 ">
                    <label class="labell form-control">{{$sinfo->city}}</label>
                </div>

                <div class="col-sm-5 pl-2 ">
                    <label class="labell1 form-control"><b>Brangay:</b></label>
                </div>
                <div class="col-sm-7 pl-2 ">
                    <label class="labell form-control">{{$sinfo->barangay}}</label>
                </div>

                <div class="col-sm-5 pl-2 ">
                    <label class="labell1 form-control"><b>Street / Lot & Block No.:</b></label>
                </div>
                <div class="col-sm-7 pl-2 ">
                    <label class="labell form-control">{{$sinfo->street}}</label>
                </div>
                

            </div>
        </div>
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="form-group row pt-2">
                <div class="col-sm-5 pl-2">
                    <label class="labell1 form-control"><b>LRN:</b></label>
                </div>
                <div class="col-sm-7 pl-2 ">
                    <label class="labell form-control">{{$sinfo->lrn}}</label>
                </div>

                <div class="col-sm-5 pl-2">
                    <label class="labell1 form-control"><b>Date of Birth:</b></label>
                </div>
                <div class="col-sm-7 pl-2 ">
                    <label class="labell form-control">{{$sinfo->dob}}</label>
                </div>

                <div class="col-sm-5 pl-2 ">
                    <label class="labell1 form-control"><b>Contact #:</b></label>
                </div>
                <div class="col-sm-7 pl-2 ">
                    <label class="labell form-control">{{$sinfo->contactno}}</label>
                </div>

                <div class="col-sm-5 pl-2 ">
                    <label class="labell1 form-control"><b>Gender:</b></label>
                </div>
                <div class="col-sm-7 pl-2 ">
                    <label class="labell form-control">{{$sinfo->gender}}</label>
                </div>

                <div class="col-sm-5 pl-2 ">
                    <label class="labell1 form-control"><b>Religion:</b></label>
                </div>
                <div class="col-sm-7 pl-2 ">
                    <label class="labell form-control">{{$sinfo->religionname}}</label>
                </div>

                <div class="col-sm-5 pl-2 ">
                    <label class="labell1 form-control"><b>Mother Tongue:</b></label>
                </div>
                <div class="col-sm-7 pl-2 ">
                    <label class="labell form-control">{{$sinfo->mtname}}</label>
                </div>
            
                <div class="col-sm-5 pl-2 ">
                    <label class="labell1 form-control"><b>Ethnic Group:</b></label>
                </div>
                <div class="col-sm-7 pl-2 ">
                    <label class="labell form-control">{{$sinfo->egname}}</label>
                </div>

            </div>
        </div>
    <div class="tab-pane fade" id="pg" role="tabpanel" aria-labelledby="pg-tab">
    
        <div class="form-group row pt-2">
            <div class="col-sm-5 pl-2 ">
                <label class="labell1 form-control"><b>Mother's Name:</b></label>
            </div>
            <div class="col-sm-7 pl-2 ">
                <label class="labell form-control">{{$sinfo->mothername}}</label>
            </div>
            <div class="col-sm-5 pl-2 ">
                <label class="labell1 form-control"><b>Occupation:</b></label>
            </div>
            <div class="col-sm-7 pl-2 ">
                <label class="labell form-control">{{$sinfo->moccupation}}</label>
            </div>
            <div class="col-sm-5 pl-2 ">
                <label class="labell1 form-control"><b>Mother's Contact #:</b></label>
            </div>
            <div class="col-sm-7 pl-2 ">
                <label class="labell form-control">{{$sinfo->mcontactno}}</label>
            </div>
           
        </div>
        <hr>
        <div class="form-group row pt-2">
            <div class="col-sm-5 pl-2 ">
                <label class="labell1 form-control"><b>Father's Name:</b></label>
            </div>
            <div class="col-sm-7 pl-2 ">
                <label class="labell form-control">{{$sinfo->fathername}}</label>
            </div>
            <div class="col-sm-5 pl-2 ">
                <label class="labell1 form-control"><b>Father's Occupation:</b></label>
            </div>
            <div class="col-sm-7 pl-2 ">
                <label class="labell form-control">{{$sinfo->foccupation}}</label>
            </div>
            <div class="col-sm-5 pl-2 ">
                <label class="labell1 form-control"><b>Father's Contact #:</b></label>
            </div>
            <div class="col-sm-7 pl-2 ">
                <label class="labell form-control">{{$sinfo->fcontactno}}</label>
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <div class="col-sm-5 pl-2 ">
                <label class="labell1 form-control"><b>Guardian Name:</b></label>
            </div>
            <div class="col-sm-7 pl-2 ">
                <label class="labell form-control">{{$sinfo->guardianname}}</label>
            </div>
            <div class="col-sm-5 pl-2 ">
                <label class="labell1 form-control"><b>Guardian Relation:</b></label>
            </div>
            <div class="col-sm-7 pl-2 ">
                <label class="labell form-control">{{$sinfo->guardianrelation}}</label>
            </div>
            <div class="col-sm-5 pl-2 ">
                <label class="labell1 form-control"><b>Guardian Contact #:</b></label>
            </div>
            <div class="col-sm-7 pl-2 ">
                <label class="labell form-control">{{$sinfo->gcontactno}}</label>
            </div>

        </div>
    
    </div>
    <div class="tab-pane fade" id="mi" role="tabpanel" aria-labelledby="mi-tab">
        <div class="form-group row pt-2">
            <div class="col-sm-5 pl-2 ">
                <label class="labell1 form-control"><b>Blood Type:</b></label>
            </div>
            <div class="col-sm-7 pl-2 ">
                <label class="labell form-control">{{$sinfo->bloodtype}}</label>
            </div>

            <div class="col-sm-5 pl-2 ">
                <label class="labell1 form-control"><b>Allergy:</b></label>
            </div>
            <div class="col-sm-7 pl-2 ">
                <label class="labell form-control">{{$sinfo->allergy}}</label>
            </div>

            <div class="col-sm-5 pl-2 ">
                <label class="labell1 form-control"><b>Other Medical Information:</b></label>
            </div>
            <div class="col-sm-7 pl-2 ">
                <label class="labell form-control">{{$sinfo->others}}</label>
            </div>
        </div>
    </div>
    {{-- <div class="tab-pane fade" id="ot" role="tabpanel" aria-labelledby="ot-tab">
        <div class="form-group row pt-2">
            <div class="col-sm-5 pl-2 ">
                <label class="labell1 form-control"><b>RFID:</b></label>
            </div>
            <div class="col-sm-7 pl-2 ">
                <label class="labell form-control">{{$sinfo->rfid}}</label>
            </div>
        </div>
    </div> --}}
    {{-- @endforeach --}}
    </div>
    </div>
    </div>
    </div>
</div>



@endsection


@section('footerscript')

    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/croppie/croppie.js')}}"></script>
    <link rel="stylesheet" href="{{asset('plugins/croppie/croppie.css')}}">
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>


    


    <script>

        $(document).ready(function(){
            
            $(function () {
                $('.select2').select2()
            });

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
                        url: "/parent/update/studpic",
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


    <script>
        $(document).ready(function(){

            $(document).on('change','#region',function(){

                var regionid = $(this).val();
                
                $.ajax({
                        type:'GET',
                        url:'/getProvince/'+regionid,
                        success:function(data) {

                            $('#province').empty()
                            $('#province').append('<option value="" >Select a Province</option>')

                            $.each(data,function(a,b){

                                $('#province').append('<option value="'+b.provincecode+'">'+b.provincename+'</option>')

                            })

                            @if(isset($province->provincecode))
                                $('#province').val('{{$province->provincecode}}').change();
                            @endif
                            
                        },
                })    

             
            })
            $(document).on('change','#province',function(){

                var provinceid = $(this).val();

                $.ajax({
                        type:'GET',
                        url:'/getCityMun/'+provinceid,
                        success:function(data) {

                            $('#citymun').empty()
                            $('#citymun').append('<option value="" >Select a City / Municipality</option>')

                            $.each(data,function(a,b){

                                $('#citymun').append('<option value="'+b.id+'">'+b.municipalityname+'</option>')

                            })

                            @if(isset($municipality->municipality))
                                $('#citymun').val('{{$municipality->id}}').change();
                            @endif
                   
                            
                        },
                })    


            })

            $(document).on('change','#citymun',function(){

                var municipalityid = $(this).val();

                $.ajax({
                        type:'GET',
                        url:'/getBarangay/'+municipalityid,
                        success:function(data) {

                            $('#barangay').empty()
                            $('#barangay').append('<option value="" >Select a Barangay</option>')

                            $.each(data,function(a,b){

                                $('#barangay').append('<option value="'+b.id+'">'+b.barangayname+'</option>')

                            })

                            @if(isset($barangay->id))
                                $('#barangay').val('{{$barangay->id}}').change();
                            @endif
                            
                        },
                })    


            })


            $(document).on('click','#updateStudentInfo',function(){

                $.ajax({
                        type:'GET',
                        url:'/updateStudentInfo',
                        data:{
                            region:$('#region').val(),
                            province:$('#province').val(),
                            citymun:$('#citymun').val(),
                            barangay:$('#barangay').val(),
                            street:$('#street').val(),
                            fname:$('#fname').val(),
                            mname:$('#mname').val(),
                            gname:$('#gname').val(),
                            fcontactno:$('#fcontactno').val(),
                            gcontactno:$('#gcontactno').val(),
                            mcontactno:$('#mcontactno').val(),
                            foccu:$('#foccu').val(),
                            moccu:$('#moccu').val(),
                            grelation:$('#grelation').val(),
                        },
                        success:function(data) {
                            if(data == 1){

                                Swal.fire({
                                    type: 'success',
                                    title: 'UPDATED SUCCESSFULLY',
                                    showConfirmButton: false,
                                    timer: 1500,
                                })

                            }
                            else{

                                Swal.fire({
                                    type: 'error',
                                    title: 'SOMETHING WENT WRONG',
                                    showConfirmButton: false,
                                    timer: 1500,
                                })
                            }
                        },
                    })   

            })


        })

        
    </script>

    <script>
        $(document).ready(function(){

            @if(isset($region->LHIO))
                $('#region').val('{{$region->LHIO}}').change();
            @endif
            @if(isset($street))
                $('#street').val('{{$street}}');
            @endif
         
         
        })
    </script>


@endsection
