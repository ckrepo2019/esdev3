@extends('studentPortal.layouts.app2')


@section('pagespecificscripts')

    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .select2-selection{
            height: calc(2.25rem + 2px) !important;
        }
    </style>

@endsection


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
            {{-- <div class="modal-footer justify-content-between">
                <button  id="updateimage" class="btn btn-info savebutton">UPDATE STUDENT PICTURE</button>
            </div> --}}
        </div>
    </div>
</div>

<div class="m-3 card">
    <div class="card-header bg-primary">
        <h4>Student Profile</h4>
    </div>
    <div class="card-body">
    <div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
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
                            src="{{asset(Session::get('studentInfo')->picurl)}}" 
                            onerror="this.onerror=null; this.src='{{asset($avatar)}}'"
                            alt="User profile picture">

                </div>
                <h3 class="profile-username text-center mb-0">{{$item->firstname}} {{$item->middlename}} {{$item->lastname}} {{$item->suffix != null ? ', '.$item->suffix : ''}}</h3>

                <p class="text-muted text-center">{{$item->sid}}</p>
                {{-- <button data-toggle="modal"  data-target="#profile-modal" class="btn btn-primary btn-block mt-2">UPDATE STUDENT PHOTO</button> --}}
            </div>
          
        </div>
    </div>
    <div class="col-md-8" style="font-size: 13px; font-weight: 600">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Student Information</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="pg-tab" data-toggle="tab" href="#pg" role="tab" aria-controls="profile" aria-selected="false">Parents/Guardian</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="mi-tab" data-toggle="tab" href="#mi" role="tab" aria-controls="contact" aria-selected="false">Address</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="ot-tab" data-toggle="tab" href="#ot" role="tab" aria-controls="contact" aria-selected="false">Other Information</a>
    </li>
    </ul>
    <div class="tab-content" id="myTabContent">
   
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="form-group row pt-2">
            <div class="col-sm-3 pl-2">
                <label class="labell1 form-control"><b>LRN: </b></label>
            </div>
            <div class="col-sm-9 pl-2 ">
                <label class="labell form-control">{{$item->lrn}}</label>
            </div>

            <div class="col-sm-3 pl-2">
                <label class="labell1 form-control"><b>Date of Birth:</b></label>
            </div>
            <div class="col-sm-9 pl-2 ">
                <label class="labell form-control">{{\Carbon\Carbon::create($item->dob)->isoFormat('MMMM DD, YYYY')}}</label>
            </div>

            <div class="col-sm-3 pl-2 ">
                <label class="labell1 form-control"><b>Contact #:</b></label>
            </div>
            <div class="col-sm-9 pl-2 ">
                <label class="labell form-control">{{$item->contactno}}</label>
            </div>

            <div class="col-sm-3 pl-2 ">
                <label class="labell1 form-control"><b>Gender:</b></label>
            </div>
            <div class="col-sm-9 pl-2 ">
                <label class="labell form-control">{{$item->gender}}</label>
            </div>

            <div class="col-sm-3 pl-2 ">
                <label class="labell1 form-control"><b>Religion:</b></label>
            </div>
            <div class="col-sm-9 pl-2 ">
                <label class="labell form-control">{{$item->religionname}}</label>
            </div>

            <div class="col-sm-3 pl-2 ">
                <label class="labell1 form-control"><b>Mother Tongue:</b></label>
            </div>
            <div class="col-sm-9 pl-2 ">
                <label class="labell form-control">{{$item->mtname}}</label>
            </div>
          
            <div class="col-sm-3 pl-2 ">
                <label class="labell1 form-control"><b>Ethnic Group:</b></label>
            </div>
            <div class="col-sm-9 pl-2 ">
                <label class="labell form-control">{{$item->egname}}</label>
            </div>

        </div>
    </div>
    <div class="tab-pane fade" id="pg" role="tabpanel" aria-labelledby="pg-tab">
    
    <div class="form-group row pt-2">
        <div class="col-sm-3 pl-2 ">
            <label class="labell1 form-control"><b>Mother's Name:</b></label>
        </div>
        <div class="col-sm-9 pl-2 ">
            <label class="labell form-control">{{$item->mothername}}</label>
        </div>
        <div class="col-sm-3 pl-2 ">
            <label class="labell1 form-control"><b>Contact Number:</b></label>
        </div>
        <div class="col-sm-9 pl-2 ">
            <label class="labell form-control">{{$item->mcontactno}}</label>
        </div>
        <div class="col-sm-3 pl-2 ">
            <label class="labell1 form-control"><b>Occupation:</b></label>
        </div>
        <div class="col-sm-9 pl-2 ">
            <label class="labell form-control">{{$item->moccupation}}</label>
        </div>
    </div>
    <hr>
    <div class="form-group row pt-2">
        <div class="col-sm-3 pl-2 ">
            <label class="labell1 form-control"><b>Father's Name:</b></label>
        </div>
        <div class="col-sm-9 pl-2 ">
            <label class="labell form-control">{{$item->fathername}}</label>
        </div>
        <div class="col-sm-3 pl-2 ">
            <label class="labell1 form-control"><b>Contact Number:</b></label>
        </div>
        <div class="col-sm-9 pl-2 ">
            <label class="labell form-control">{{$item->fcontactno}}</label>
        </div>
        <div class="col-sm-3 pl-2 ">
            <label class="labell1 form-control"><b>Occupation:</b></label>
        </div>
        <div class="col-sm-9 pl-2 ">
            <label class="labell form-control">{{$item->foccupation}}</label>
        </div>
    </div>
    <hr>
    <div class="form-group row pt-2">
        <div class="col-sm-3 pl-2 ">
            <label class="labell1 form-control"><b>Guardian's Name:</b></label>
        </div>
        <div class="col-sm-9 pl-2 ">
            <label class="labell form-control">{{$item->guardianname}}</label>
        </div>
        <div class="col-sm-3 pl-2 ">
            <label class="labell1 form-control"><b>Contact Number:</b></label>
        </div>
        <div class="col-sm-9 pl-2 ">
            <label class="labell form-control">{{$item->gcontactno}}</label>
        </div>
        <div class="col-sm-3 pl-2 ">
            <label class="labell1 form-control"><b>Guardian Relation:</b></label>
        </div>
        <div class="col-sm-9 pl-2 ">
            <label class="labell form-control">{{$item->guardianrelation}}</label>
        </div>
    </div>
    
    </div>
    <div class="tab-pane fade" id="mi" role="tabpanel" aria-labelledby="mi-tab">
        <div class="form-group row pt-2">
            <div class="col-sm-3 pl-2 ">
                <label class="labell1 form-control"><b>Street:</b></label>
            </div>
            <div class="col-sm-9 pl-2 ">
                <label class="labell form-control">{{$item->street}}</label>
            </div>

            <div class="col-sm-3 pl-2 ">
                <label class="labell1 form-control"><b>Barangay:</b></label>
            </div>
            <div class="col-sm-9 pl-2 ">
                <label class="labell form-control">{{$item->barangay}}</label>
            </div>
            <div class="col-sm-3 pl-2 ">
                <label class="labell1 form-control"><b>City:</b></label>
            </div>
            <div class="col-sm-9 pl-2 ">
                <label class="labell form-control">{{$item->city}}</label>
            </div>
            <div class="col-sm-3 pl-2 ">
                <label class="labell1 form-control"><b>Province:</b></label>
            </div>
            <div class="col-sm-9 pl-2 ">
                <label class="labell form-control">{{$item->province}}</label>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="ot" role="tabpanel" aria-labelledby="ot-tab">
        <div class="form-group row pt-2">
            <div class="col-sm-3 pl-2 ">
                <label class="labell1 form-control"><b>Grade Level:</b></label>
            </div>
            <div class="col-sm-9 pl-2 ">
                <label class="labell form-control">{{$item->levelname}}</label>
            </div>
            @if($item->strandid != null)
                <div class="col-sm-3 pl-2 ">
                    <label class="labell1 form-control"><b>Strand:</b></label>
                </div>
                <div class="col-sm-9 pl-2 ">
                    <label class="labell form-control">{{$item->strandname}}</label>
                </div>
            @endif
            @if($item->courseid != null)
                <div class="col-sm-3 pl-2 ">
                    <label class="labell1 form-control"><b>Course:</b></label>
                </div>
                <div class="col-sm-9 pl-2 ">
                    <label class="labell form-control">{{$item->courseDesc}}</label>
                </div>
            @endif
        </div>
      
    </div>
    </div>
    </div>
    </div>
    </div>
@endsection


@if(Session::get('studentInfo')->acadprogid == 6)

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

                            // type: 'circle'

                        },

                        boundary: {

                            width: 304,

                            height: 289

                        }

                    });
                

                    $("#studpic").change(function(){

                        console.log($(this))
                        var selectedFile = this.files[0];

                        var idxDot = selectedFile.name.lastIndexOf(".") + 1;



                        var extFile = selectedFile.name.substr(idxDot, selectedFile.name.length).toLowerCase();

                        console.log(extFile)

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

    @endsection

@endif