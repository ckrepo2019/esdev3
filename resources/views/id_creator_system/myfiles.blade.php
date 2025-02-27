@php
    $check_refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->select('refid', 'resourcepath')
        ->first();

    if (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (Session::get('currentPortal') == 8) {
        $extend = 'admission.layouts.app2';
    } elseif (Session::get('currentPortal') == 1) {
        $extend = 'teacher.layouts.app';
    } elseif (Session::get('currentPortal') == 2) {
        $extend = 'principalsportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 4) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 15) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 18) {
        $extend = 'ctportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 10) {
        $extend = 'hr.layouts.app';
    } elseif (Session::get('currentPortal') == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } elseif (auth()->user()->type == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } else {
        if (isset($check_refid->refid)) {
            if ($check_refid->resourcepath == null) {
                $extend = 'general.defaultportal.layouts.app';
            } elseif ($check_refid->refid == 27) {
                $extend = 'academiccoor.layouts.app2';
            } elseif ($check_refid->refid == 22) {
                $extend = 'principalcoor.layouts.app2';
            } elseif ($check_refid->refid == 29) {
                $extend = 'idmanagement.layouts.app2';
            } elseif ($check_refid->refid == 23) {
                $extend = 'clinic.index';
            } elseif ($check_refid->refid == 24) {
                $extend = 'clinic_nurse.index';
            } elseif ($check_refid->refid == 25) {
                $extend = 'clinic_doctor.index';
            } else {
                $extend = 'general.defaultportal.layouts.app';
            }
        } else {
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp
@extends($extend)
@section('content')
    <div class="card m-2">
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="image">Image File </label>
                    <input type="file" name="image" class="form-control" id="image">
                    <br>
                    <button type="button" class="btn btn-success upload">Upload Image</button>
                </div>
            </div>
        </form>
    </div>

    <div class="container-fluid">
        <div class="row" id="img-wrapper">

        </div>
    </div>
@endsection
@section('footerjavascript')
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });

        $(document).ready(function() {
            load_images();

            $(document).on('click', '.upload', function(event) {
                upload();
            });
        });

        function load_images() {
            $.ajax({
                type: 'GET',
                url: '{{ route('file.load') }}',
                success: function(data) {
                    console.log(data);
                    var result = data[0].data;
                    $('#img-wrapper').empty();
                    $.each(result, function(index, item) {
                        console.log(item);

                        var elem = `<div class="col-md-3 p-2">
                                    <center>
                                        <img class="card shadow" style="width: auto; height: 200px; border-radius: 10px;"
                                        src="{{ asset('dist/img/templates/') }}/${item}" alt="Uploaded Image">
                                    </center>
                                </div>`;

                        $('#img-wrapper').append(elem);
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                    // Handle error if needed
                }
            });
        }


        function upload() {
            // Get the file input element
            var fileInput = $('#image')[0];
            console.log(fileInput)

            // Check if the file input has a file selected
            if (!fileInput.files.length) {
                notify("warning", "Please select a file for upload")

                return;
            }
            var formData = new FormData($('#uploadForm')[0]);
            console.log(formData)
            $.ajax({
                type: 'POST', // Make sure this is set to POST
                data: formData,
                processData: false,
                contentType: false,
                url: '{{ route('file.upload') }}',
                success: function(data) {
                    console.log(data);
                    load_images();
                    notify(data[0].statusCode, data[0].message)

                }
            });
        }

        function notify(code, message) {
            Toast.fire({
                type: code,
                title: message,
            });
        }
    </script>
@endsection
