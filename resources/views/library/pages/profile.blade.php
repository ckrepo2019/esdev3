@php
    $extends = 'library.layouts.borrower';

    $check_refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->select('refid', 'resourcepath')
        ->first();

    if (isset($check_refid->refid) && $check_refid->refid == 34) {
        $extends = 'library.layouts.backend';
    }

@endphp

@extends($extends)

@section('css_before')
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
    <style>
        th {
            white-space: nowrap;
        }
    </style>
@endsection

@section('content')
    <!-- Main Container -->
    <main>
        @php
            // $url = app()->environment('local') ? 'http://es_ldcu.ck/' : secure('http://es_ldcu.ck/');
            $picurl = DB::table('teacher')
                ->where('userid', auth()->user()->id)
                ->value('picurl');
            // $domain = $url . $picurl;
            $picurl = $picurl ? $picurl : '/media/avatars/avatar0.jpg';
            // $isvalid = $picurl ? asset : asset('/media/avatars/avatar0.jpg');
        @endphp
        <!-- Hero -->
        <div class="bg-image" style="background-image: url('{{ asset('media/photos/photo8@2x.jpg') }}');">
            <div class="bg-black-75">
                <div class="content content-full text-center">
                    <div class="my-3">
                        <img class="img-avatar img-avatar-thumb" src="{{ asset($picurl) }}" alt="profile"
                            style="object-fit: cover;">
                    </div>
                    {{-- <h1 class="h2 text-white mb-0">Edit Account</h1> --}}
                    <h1 class=" h2 text-white mb-0">
                        {{ auth()->user()->name }}
                    </h1>
                </div>
            </div>
        </div>
        <!-- END Hero -->

        <!-- Page Content -->
        <div class="content content-boxed">
            <!-- User Profile -->
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">User Profile</h3>
                    <div class="block-options"> <i class="far fa-edit btn_edit"></i> </div>
                </div>
                <div class="block-content">
                    <div class="row push">
                        <div class="col-lg-4">
                            <p class="font-size-sm text-muted">
                                Your account’s vital info. Your username will be publicly visible.
                            </p>
                        </div>
                        <div class="col-lg-8 col-xl-5">
                            <div class="form-group">
                                <label for="one-profile-edit-username">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Enter your username.." value="{{ auth()->user()->email }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter your name.." disabled value="{{ auth()->user()->name }}">
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter your email.." value="{{ auth()->user()->email }}" disabled>
                            </div>
                            <div class="form-group">
                                <label>Your Avatar</label>
                                <div class="push">
                                    <img class="img-avatar" src="{{ asset($picurl) }}" alt=""
                                        style="object-fit: cover;">
                                </div>
                                <div class="custom-file">
                                    <!-- Populating custom file input label with the selected filename (data-toggle="custom-file-input" is initialized in Helpers.coreBootstrapCustomFileInput()) -->
                                    <input type="file" class="custom-file-input" data-toggle="custom-file-input"
                                        id="image" name="image">
                                    <label class="custom-file-label" for="image">Choose a new
                                        avatar</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-alt-primary btn_update" disabled>
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END User Profile -->

            <!-- Change Password -->
            <div class="block block-rounded">
                <div class="block-header">
                    <h3 class="block-title">Change Password</h3>
                </div>
                <div class="block-content">
                    <div class="row push">
                        <div class="col-lg-4">
                            <p class="font-size-sm text-muted">
                                Changing your sign in password is an easy way to keep your account secure.
                            </p>
                        </div>
                        <div class="col-lg-8 col-xl-5">
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                                <span class="invalid-feedback" role="alert">
                                    <strong>Current Password is required</strong>
                                </span>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="new_password">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>New Password is required</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="new_password_confirm">Confirm New Password</label>
                                    <input type="password" class="form-control" id="new_password_confirm"
                                        name="new_password_confirm">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Password Confirmation is required</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-alt-primary btn_update_password">
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Change Password -->

            <!-- MY BORROWED BOOKS -->
            @if (auth()->user()->usertype === 7)
                <div class="block block-rounded">
                    <div class="block-header">
                        <h3 class="block-title">MY BOOKS</h3>
                        <div class="block-options d-flex align-items-center">
                            <select class="form-control form-control-sm" id="select-status">
                                @foreach (DB::table('library_status')->where('status_deleted', 0)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->status_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="block-content pb-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-vcenter js-dataTable-full"
                                id="DataTables_Table_1" style="width: 100%;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Book Title</th>
                                        <th>Date Borrowed</th>
                                        <th>Due Date</th>
                                        <th>Date Returned</th>
                                        <th>Penalty</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            <!-- END BORROWED BOOKS -->


        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->
@endsection

@section('js_after')
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            circulations($('#select-status').val());
            $(document).on('change', '#select-status', function() {
                // Get the selected text
                var selectedText = $(this).find('option:selected').text().toLowerCase();
                var svalue = $(this).val();
                circulations(svalue);
            });

            $(document).on('click', '.btn_edit', function() {
                var nameInput = $('#name');
                var editIcon = $(this);

                // Toggle the 'disabled' property of the name input
                nameInput.prop('disabled', function(i, value) {
                    return !value;
                });

                $('.btn_update').prop('disabled', function(i, value) {
                    return !value;
                });

                // Toggle the text of the edit icon between "Edit" and "Cancel"
                if (nameInput.prop('disabled')) {
                    editIcon.text('Edit');
                } else {
                    editIcon.text('Cancel');
                }
            });

            $(document).on('click', '.btn_update', function() {
                var imageInput = $('#image')[0].files[0];
                var name = $('#name').val().trim();

                // Create FormData object
                var formData = new FormData();
                if (imageInput) {
                    formData.append('image', imageInput);
                }
                formData.append('name', name);

                // Fetch CSRF token from meta tag
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Create headers object with CSRF token
                var headers = {
                    'X-CSRF-TOKEN': csrfToken
                };

                // Send the AJAX request with CSRF token included
                $.ajax({
                    url: '{{ route('update.profile') }}',
                    type: 'POST',
                    headers: headers, // Include headers object
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log(data);
                    },
                    error: function(xhr, status, error) {
                        console.error('There was a problem with the AJAX request:', error);
                    }
                });

            });

            $(document).on('click', '.btn_update_password', function() {
                if (!$('#current_password').val()) {
                    $('#current_password').addClass('is-invalid');
                    notify('error', 'Current password is required!');
                    return;
                }

                if (!$('#new_password').val()) {
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
                        notify(response.status, response.message);
                    },
                    error: function(xhr, status, error) {
                        notify('error', 'Error saving user. Please try again.', 'error');
                    }
                });
            });

            $('#new_password_confirm').on('input', function() {
                if (!validatePasswordMatch()) {
                    notify('error', 'Password confirmation doesn\'t match')
                } else {
                    notify('success', 'Password confirmation match')
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

        function circulations(value) {
            $.ajax({
                type: 'GET',
                data: {
                    action: value,
                },
                url: '{{ route('getCirculationByBorrower') }}',
                success: function(data) {
                    console.log(data);
                    load_circulation_datatable(data)
                }
            });
        }

        function load_circulation_datatable(data) {
            var table = $('#DataTables_Table_1').DataTable({
                autowidth: false,
                destroy: true,
                responsive: true,
                stateSave: true,
                data: data,
                columns: [{
                        data: 'book_title',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.book_title}</span>`
                        }
                    },
                    {
                        data: "circulation_date_borrowed",
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_date_borrowed}</span>`
                        }
                    },
                    {
                        data: "circulation_due_date",
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_due_date}</span>`
                        }
                    },
                    {
                        data: "circulation_date_returned",
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_date_returned ?? ''}</span>`
                        }
                    },
                    {
                        data: "circulation_penalty",
                        className: 'text-right font-size-sm',
                        render: function(data, type, row) {
                            var penalty = parseFloat(row.circulation_penalty).toFixed(2);
                            var renderHtml =
                                `<span class="text-danger font-w600">₱${penalty}  
                            </span>`;

                            return renderHtml;
                        }
                    },
                    {
                        data: "status_name",
                        className: 'text-right',
                        render: function(type, data, row) {
                            return `<a class="font-size-sm font-w600" >${row.status_name}</a>`
                        }
                    },

                ],
            });
        }
    </script>
@endsection
