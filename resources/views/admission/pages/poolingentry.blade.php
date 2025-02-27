@extends('admission.layouts.test')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center" style="height: 100vh;align-items: center;">
                <div class="col-lg-3 col-md-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="form-group mt-2 mx-1">
                                <label for="" class="mb-1 text-dark text-center">Pooling Number</label>
                                <input type="text" class="form-control" onkeyup="this.value = this.value.toUpperCase();"
                                    id="poolingnumber" name="poolingnumber" placeholder="Enter Pooling Number" required
                                    style="text-align: center">
                                <span class="invalid-feedback" role="alert">
                                    <strong>Pooling Number is required!</strong>
                                </span>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <div class="row justify-content-center">
                                <button type="submit" class="btn btn-primary" id="submit_pooling"><i
                                        class="far fa-paper-plane mr-1"></i>Join Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('footerjavascript')
    <script>
        $(document).ready(function() {
            $('#submit_pooling').on('click', function() {
                var isvalid = true;
                var poolingnumber = $('#poolingnumber').val()
                if (!poolingnumber) {
                    $('#poolingnumber').addClass('is-invalid')
                    notify('warning', 'Pooling Number is Required!')
                    isvalid = false;
                } else {
                    $('#poolingnumber').removeClass('is-invalid')
                }

                if (isvalid) {
                    $.ajax({
                        type: 'GET',
                        data: {
                            poolingnumber: poolingnumber
                        },
                        url: '{{ route('submit.pooling') }}',
                        success: function(response) {
                            console.log(response);
                            notify(response.status, response.message)
                            if (response.status == 'success') {
                                $('#poolingnumber').addClass('is-valid')
                                window.location.href =
                                    `/admission/diagnostictest?poolingnumber=${poolingnumber}`;
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseJSON);
                        }
                    });

                }
            })

        })
    </script>
@endsection
