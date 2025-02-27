<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>Applicant Form</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}">
<link rel="stylesheet" href="{{asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css')}}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables/DataTables/css/jquery.dataTables.css')}}">
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}"> 

<style>
.shadow {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    border: 0 !important;
}

.center-form {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh; /* Adjust the height as needed */
    }


</style>
</head>

<body>
            <div class="container">
                <!-- Center the form using a flex container -->
                <div class="row center-form">
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-body">
                                <!-- Form Title -->
                                <h3 class="text-center mb-4">Applicant Form</h3>

                                <!-- The Form -->
                                <form action="{{ route('submit_applicant') }}" id="applicantForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="applicantName">Applicant Name</label>
                                        <input id="applicantName" name="applicantName" class="form-control" placeholder="Applicant Name" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                    <div class="form-group">
                                        <label for="applicantAddress">Address</label>
                                        <input id="applicantAddress" placeholder="Applicant Address" name="applicantAddress" class="form-control" onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                    <div class="form-group">
                                        <label for="course">Desired Program</label>
                                        @php
                                                
                                                $courses = DB::table('college_courses')
                                                                ->where('deleted', 0)
                                                                ->get()
                                        
                                        @endphp
                                        <select name="course" id="course" class="form-control select2">
                                            @foreach($courses as $course)
                                                <option value="{{$course->id}}">{{$course->courseabrv}} - {{$course->courseDesc}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="shsgrades">SHS GWA</label>
                                        <input id="shsgrades" placeholder="Senior High General Weighted Average" name="shsgrades" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="jhsgrades">JHS GWA</label>
                                        <input id="jhsgrades" placeholder="Junior High General Weighted Average" name="jhsgrades" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="birthday">Birthday</label>
                                        <input type="date" id="birthday" name="birthday" class="form-control">
                                    </div>
                                    <!-- File Input for Documents -->
                                    <div class="form-group">
                                        <label for="documents">Upload Documents</label>
                                        <input type="file" id="documents" name="documents[]" class="form-control-file" multiple>
                                    </div>
                                    <button type="submit" id="submitbtn" class="btn btn-primary">Save <i class="fas fa-save"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    
    <script>


            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            })


			$(document).ready(function(){

                
				
			});

            $(document).on('click','#submitbtn',function(e){
                    
                e.preventDefault();

                var formData = new FormData($('#applicantForm')[0]); // Create FormData object

                $.ajax({
                    url: $('#applicantForm').attr('action'), // URL from the form action attribute
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        var data = response; // Assuming the response is directly the JSON data
                        console.log(data); //
                        console.log(data.code); //
                        // Check if data[0] exists (assuming an array is returned)
                        if (data) {
                            var code = data.code; // Accessing code from the returned data
                            var name = data.name; // Accessing name from the returned data

                            // Construct the URL using code and name
                            var newURL = `/applicant/form/success?code=${code}&name=${name}`;

                            // Redirect to the new URL
                            window.location.href = newURL;
                        } else {

                            Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong'
                            });

                            console.error('Invalid data format received.');
                        }



                        
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                        Toast.fire({
                                    type: 'danger',
                                    title: 'Something went wrong'
                            });
                    }
                });

            });
		</script>

  </body>
</html>
