@extends('clinic_doctor.layouts.app')



<style>
             .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }


    
</style>
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Doctor Profile</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

            <div class="card shadow">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <form method="POST" action="/doctors" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                            <label for="degree">Degree</label>
                            @if($info)
                            <input type="text" class="form-control" id= "degree" name="degree" value = '{{$info->degree}}'/>
                            @else
                            <select class="form-control select2bs4" multiple="multiple" id="degree" name="degree[]">
                                <option value="">Select 1 or multiple</option>
                                <option value="M.B.B.S">M.B.B.S</option>
                                <option value="MD">MD</option>
                                <option value="MS">MS</option>
                                <option value="PhD">PhD</option>
                            </select>
                            @endif

                            </div>
                            
                            <div class="form-group">
                            @if($info)
                            <input type="text" class="form-control" id= "specialty"  name=specialty value = {{$info->specialty}}>
                            @else
                                <select class="form-control select2bs4" style="width: 100%;" name=specialty id="select-specialty">
                                    <option value="">Select Field</option>
                                    <option value="Cardiology">Cardiology</option>
                                    <option value="Dermatology">Dermatology</option>
                                    <option value="Endocrinology">Endocrinology</option>
                                    <option value="Gastroenterology">Gastroenterology</option>
                                    <option value="Neurology">Neurology</option>
                                    <option value="Oncology">Oncology</option>
                                    <option value="Orthopedics">Orthopedics</option>
                                    <option value="Pediatrics">Pediatrics</option>
                                    <option value="Psychiatry">Psychiatry</option>
                                    <option value="Surgery">Surgery</option>
                                    <option value="Dentist">Dentist</option>
                                </select>
                            @endif
                            </div>
                            <div class="form-group">
                                <label for="reg">Registration Number</label>
                                @if($info)
                                    <input  class="form-control" id="reg" name="reg" value = {{$info->regnum}} required oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                @else
                                    <input class="form-control" id="reg" name="reg"  required oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                @if($info)
                                <input data-inputmask-mask="9999-999-9999" type="tel" class="form-control" id="phone" name="phone" value = {{$info->phone}} required>
                                @else
                                <input data-inputmask-mask="9999-999-9999" type="tel" class="form-control" id="phone" name="phone"  required>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                @if($info)
                                <input type="email" class="form-control" id="email" name="email" value = {{$info->email}} required>
                                @else
                                <input type="email" class="form-control" id="email" name="email" required>
                                @endif
                                
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                @if($info)
                                <input type="text" class="form-control" id="address" name="address" value = '{{$info->address}}' required>
                                @else
                                <input type="text" class="form-control" id="address" name="address" required>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="signature">Signature</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="signature" name="signature" accept="image/*">
                                        @if($info)
                                        <label class="custom-file-label" id = "signature-label" for="signature">Choose new image</label>
                                        @else
                                        <label class="custom-file-label" id = "signature-label" for="signature">Choose image</label>
                                        @endif
                                    </div>
                                </div>
                                <small class="form-text text-muted">Upload an image of your signature</small>
									@if(isset($info))
										@if($info->signature != null)
											<img  src="{{asset($info->signature)}}" alt="Signature" style="max-width: 200px; max-height: 200px;"><br/>
											<small class="form-text text-muted">New image will appear below</small>
										@else
											<img id="signature-preview" src="http://sppv2.ck/avatar/T(M) 1.png" alt="Preview of selected image" style="max-width: 200px; max-height: 200px;">
											<div id="signature-preview">
										@endif
									@else
										<img id="signature-preview" src="http://sppv2.ck/avatar/T(M) 1.png" alt="Preview of selected image" style="max-width: 200px; max-height: 200px;">
										<div id="signature-preview">
									@endif
                               
                                

                            </div>

                            <button type="submit" class="btn btn-primary mt-4">Submit</button>
                        </form>
                        {{-- <img class="img-circle" src="{{asset('storage/' . $info->signature)}}" alt="Signature"> --}}


                        


                </div>
            </div>

        </div>



	<script src="{{asset('plugins/inputmask-date/jquery.inputmask.bundle.js')}}"></script>

    <script>

        
        function isImage(file) {
            return file.type.startsWith("image/");
        }

        
		$(":input[data-inputmask-mask]").inputmask();
		$(":input[data-inputmask-alias]").inputmask();
		
        $(document).ready(function () {


            $('#signature').change(function() {
                var file = $(this).prop('files')[0];
                var fileType = file.type;
                var file = document.getElementById("signature").files[0];
                if (isImage(file)) {
                console.log("image")
                } else {
                alert("File is not an image");
                window.location.reload();
                }

                
            });
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            const signatureInput = document.getElementById('signature');
            const signatureLabel = document.getElementById('signature-label');
            const signaturePreview = document.getElementById('signature-preview');
            signatureInput.addEventListener('change', function() {
            const file = signatureInput.files[0];


            


    if (file) {
        signatureLabel.textContent = file.name;

        const reader = new FileReader();

        reader.addEventListener('load', function() { 
            signaturePreview.src = reader.result;
        });

        reader.readAsDataURL(file);
        } else {
        signatureLabel.textContent = 'Please select a file';
        signaturePreview.src = '#';
        }

    });

                });

    </script>
@endsection
@section('footerjavascript')


