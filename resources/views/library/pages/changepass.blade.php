<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" id="css-main" href="{{ asset('css/oneui.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/sweetalert2/sweetalert2.min.css') }}">

</head>

<body>
    <!-- Page Content -->
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-4">
                <!-- Sign In Block -->
                <div class="block block-rounded block-themed mb-0">
                    <div class="block-header bg-primary-dark">
                         <h3 class="block-title"><i class="fa fa-key mr-1"></i> New Password</h3>
                    </div>

                    <div class="block-content">
                        <div class="p-sm-3 px-lg-4 py-lg-3">
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" class="form-control form-control-alt" id="current_password"
                                    name="current_password">
                                <span class="invalid-feedback" role="alert">
                                    <strong>Current Password is required</strong>
                                </span>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="new_password">New Password</label>
                                    <input type="password" class="form-control form-control-alt" id="new_password"
                                        name="new_password">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>New Password is required</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="new_password_confirm">Confirm New Password</label>
                                    <input type="password" class="form-control form-control-alt"
                                        id="new_password_confirm"
                                        name="new_password_confirm">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Password Confirmation is required</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-block btn-alt-primary btn_update_password mt-3" style="white-space: nowrap">
                                        <i class="far fa-paper-plane mr-1"></i> Change Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- END Sign In Block -->
            </div>
        </div>
    </div>
    <div class="content content-full font-size-sm text-muted text-center">
        <strong>CK Publishing 1.0</strong> &copy; <span data-toggle="year-copy"></span>
    </div>
    <!-- END Page Content -->

    <script src="{{ asset('js/oneui.app.js') }}"></script>
    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/plugins/es6-promise/es6-promise.auto.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });

        function notify(code, message) {
            Toast.fire({
                icon: code,
                title: message,
            });
        }

        $(document).ready( function() {
            $(document).on('click', '.btn_update_password', function() {
                if (!$('#current_password').val() ) {
                    $('#current_password').addClass('is-invalid');
                    notify('error', 'Current password is required!');
                    return;
                }

                if (!$('#new_password').val() ) {
                    $('#new_password').addClass('is-invalid');
                    notify('error', 'New Password is required!');
                    return;
                }

                if (!$('#new_password_confirm').val()) {
                    $('#new_password_confirm').addClass('is-invalid');
                    notify('error', 'Confirm password is required!');
                    return;
                }

                if ($('#new_password').val().trim() !== $('#new_password_confirm').val().trim()) {
                    $('#new_password, #new_password_confirm').addClass('is-invalid');
                    notify('error', 'Passwords do not match!');
                    return;
                }
                
                $.ajax({
                    url: '{{ route('update.password') }}',
                    method: 'POST',
                    data: {
                        current_password: $('#current_password').val(),
                        new_password: $('#new_password').val(),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        notify(response.status, response.message);
                        if(response.status == 'success'){
                            // Redirect to the new URL
                            window.location.href = '/';
                        }
                    },
                    error: function(xhr, status, error) {
                        notify('error', 'Error saving user. Please try again.', 'error');
                    }
                });
            });

            $('#new_password_confirm').on('input', function() {
                if($('#new_password').val()){
                    if (!validatePasswordMatch()) {
                        notify('error', 'Password confirmation doesn\'t match')
                    } else {
                        notify('success', 'Password confirmation match')
                    }
                }
            });
        });

        function validatePasswordMatch() {
            // Check if passwords match
            var password = $('#new_password').val().trim();
            var confirmPassword = $('#new_password_confirm').val().trim();

            if (password !== confirmPassword) {
                return false;
            }

            return true;
        }
    </script>

</body>

</html>
