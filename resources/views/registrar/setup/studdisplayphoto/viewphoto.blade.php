<div class="card">
    <div class="card-body">
        @php
            $avatar = 'avatars/unknown.png';
            if(strtolower($studinfo->gender) == 'female'){
                $avatar = "avatars/S(F) 1.png";
            }
            else{
                $avatar = "avatars/S(M)1.png";
            }
        @endphp
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="row">
                    <div class="col-md-12">
                        <div id="upload-demo-i" class="bg-white ">
                            @if($getphoto)
                            <img src="{{URL::asset($getphoto->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}" onerror="this.onerror = null, this.src='{{URL::asset($avatar)}}'" class="elevation-2" alt="User Image" style="width: 100%; border-radius: unset;">
                        @else
                        <img src="{{URL::asset($avatar)}}" onerror="this.onerror = null, this.src='{{URL::asset($avatar)}}'" class="elevation-2" alt="User Image" style="width: 100%; border-radius: unset;">
                        @endif
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <button type="button" class="btn btn-primary btn-block edit-pic-icon" data-toggle="modal" data-target="#edit_profile_pic" >
                            <i class="fa fa-upload"></i> Upload Photo
                        </button>
                        <div id="edit_profile_pic" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><strong>Profile Photo</strong></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">                                                
                                            <div class="col-md-12 text-center">                                
                                                <div id="upload-demo"></div>                                    
                                            </div>                                    
                                        </div>
                                        <input type="file" id="upload" class="form-control form-control-sm" style="overflow: hidden;">
                                        <br>
                                        <br>
                                        <button class="btn btn-success upload-result">Upload Image</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tr>
                        <th>Name:</th>
                        <td>{{$studinfo->firstname}} {{$studinfo->middlename}} {{$studinfo->lastname}} {{$studinfo->suffix}}</td>
                    </tr>
                    <tr>
                        <th>Gender:</th>
                        <td>{{$studinfo->gender}}</td>
                    </tr>
                    <tr>
                        <th>Date of Birth:</th>
                        <td>{{$studinfo->dob}}</td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td>{{$studinfo->street}} {{$studinfo->barangay}} {{$studinfo->city}} {{$studinfo->province}}</td>
                    </tr>
                    <tr>
                        <th>Current Grade Level:</th>
                        <td>{{$currentenrollmentdetails[0]->levelname ?? null}}</td>
                    </tr>
                    <tr>
                        <th>Current Section:</th>
                        <td>{{$currentenrollmentdetails[0]->sectionname ?? null}}</td>
                    </tr>
                    <tr>
                        <th>Adviser:</th>
                        <td>{{$currentenrollmentdetails[0]->teachernane ?? null}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div> 
<script>
    $uploadCrop = $('#upload-demo').croppie({
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
    $('#upload').on('change', function () { 
        var reader = new FileReader();
        reader.onload = function (e) {
            $uploadCrop.croppie('bind', {
                url: e.target.result
            }).then(function(){
                console.log('jQuery bind complete');
            });
        }
        reader.readAsDataURL(this.files[0]);
    });
    $('.upload-result').on('click', function (ev) {
        var studid = $('#select-studentid').val();
        $uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (resp) {
            $.ajax({
                url: "/setup/studdisplayphoto/uploadphoto",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "image"     :   resp,
                    "studid":   studid
                    },
                success: function (data) {
                    window.location.reload();
                }
            });
        });        
    });
    </script>