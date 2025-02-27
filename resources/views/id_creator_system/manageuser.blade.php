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
@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
    {{-- @if ($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }} You're not authorize to access this school!.
        </div>
    @endif --}}
    {{-- Modal edit user --}}
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-primary">
                        <form>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name2">Fullname</label>
                                    <input type="text" class="form-control" id="name2" placeholder="Enter Fullname">
                                </div>
                                <div class="form-group">
                                    <label for="email2">Email</label>
                                    <input type="email" class="form-control" id="email2" placeholder="Enter Email">
                                </div>
                                <div class="form-group">
                                    <label for="utype2">User type</label>
                                    <input type="number" class="form-control" id="utype2" placeholder="Enter Usertype">
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-success" id="update_user">Save</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- modal add user --}}
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-primary">
                        <form>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Fullname</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Fullname">
                                </div>
                                <div class="form-group">
                                    <label for="myemail">Email</label>
                                    <input type="email" class="form-control" id="myemail" placeholder="Enter Email">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" placeholder="Enter Password">
                                </div>
                                <div class="form-group">
                                    <label for="utype">User type</label>
                                    <input type="number" class="form-control" id="utype" placeholder="Enter Usertype">
                                </div>
                                {{-- <div class="form-group">
                                    <label for="select-school">Designation/s </label>
                                    <select class="form-control select2" id="select-school" style="width: 100%;"
                                        multiple="multiple">
                                    </select>
                                </div> --}}
                                {{-- <div class="form-group">
                                    <label for="picurl">Profile Pic</label>
                                    <input type="text" class="form-control" id="picurl" placeholder="Enter Picurl">
                                </div> --}}
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-success add_user" id="add_user">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="pt-3 px-2">
        <div class="container-fluid">
            <div style="padding: 10px;  overflow: scroll; height: 80vh">
                <div class="d-flex justify-content-between">

                    <button type="button" class="btn btn-success btn-sm new_user">
                        <i class="fas fa-plus"></i> New User
                    </button>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card shadow" style="">
                            <div class="card-body  p-3">
                                <div class="row mt-2">
                                    <div class="col-md-12" style="font-size:.9rem !important">

                                        <table class="table-hover table table-striped table-sm table-bordered"
                                            id="viewusers_datatable" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="10%">UID</th>
                                                    <th width="20%">Name</th>
                                                    <th width="20%">Email</th>
                                                    <th width="10%">Usertype</th>
                                                    <th width="10%" class="align-middle"></th>
                                                    <th width="10%" class="align-middle"></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        var uid = 0;
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });

        $(document).ready(function() {
            get_user_list();
            // load_school_select("");
            $(document).on('click', '.new_user', function(event) {
                $('#addUserModal').modal();
            });

            $(document).on('click', '.add_user', function(event) {
                add_new_user();
            });

            // $(document).on('input', '#select-school', function(event) {
            //     var ss = $('#select-school').val();
            //     console.log(ss);
            // });

            $(document).on('click', '.delete_user', function(event) {
                var id = $(this).attr('data-id');

                delete_user(id);
            });

            $(document).on('click', '.open_edit_user', function(event) {
                var id = $(this).attr('data-id');

                get_edit_user(id);
            });

            $(document).on('click', '#update_user', function(event) {
                update_user();
            });

        });

        function update_user() {

            var name = $('#name2').val();
            var email = $('#email2').val();
            var utype = $('#utype2').val();

            $.ajax({
                type: "GET",
                url: '{{ route('update.user') }}',
                data: {
                    id: uid,
                    name: name,
                    utype: utype,
                    email: email,
                },

                success: function(data) {
                    if (data[0].statusCode == "success") {
                        get_user_list();
                        notify(data[0].statusCode, data[0].message);
                    }

                }
            })
        }

        function get_edit_user(id) {
            $.ajax({
                type: "GET",
                url: '{{ route('edit.user') }}',
                data: {
                    id: id
                },

                success: function(data) {
                    uid = data[0].id;
                    $('#name2').val(data[0].name);
                    $('#email2').val(data[0].email);
                    $('#utype2').val(data[0].utype);

                    $('#editUserModal').modal();
                }
            })
        }

        function delete_user(id) {
            Swal.fire({
                icon: 'info',
                title: 'You want to delete this user?',
                text: `You can't undo this process.`,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {

                    $.ajax({
                        url: '{{ route('delete.user') }}',
                        type: "GET",
                        data: {
                            id: id,
                        },
                        success: function(data) {
                            notify(data[0].statusCode, data[0].message);
                            get_user_list();
                        }
                    });
                }

            })
        }

        function isVariableEmpty(variable) {
            return variable === undefined || variable === null || variable === '';
        }

        function add_new_user() {

            // var utype = $('#utype').val();
            var email = $('#myemail').val();
            var name = $('#name').val();
            var utype = $('#utype').val();
            var password = $('#password').val();

            if (isVariableEmpty(utype), isVariableEmpty(name), isVariableEmpty(email), isVariableEmpty(password)) {
                notify("error", 'Fill all fields!');
                return;
            }

            $.ajax({
                type: "GET",
                url: '{{ route('add.user') }}',
                data: {
                    utype: utype,
                    name: name,
                    email: email,
                    password: password,
                },
                success: function(data) {
                    notify(data[0].statusCode, data[0].message);
                    get_user_list();

                }

            })
        }

        function get_user_list() {
            $.ajax({
                type: 'GET',
                url: '{{ route('get.users') }}',
                success: function(data) {
                    console.log(data);
                    load_users_datatable(data);
                }
            });
        }

        function load_users_datatable(data) {

            $("#viewusers_datatable").DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                lengthChange: false,
                columns: [{
                        "data": "id"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "utype"
                    },
                    {
                        "data": null
                    },
                    {
                        "data": null
                    },
                ],
                columnDefs: [
                    // {
                    //     'targets': 1,
                    //     'orderable': false,
                    //     'createdCell': function(td, cellData, rowData, row, col) {
                    //         var disabled = '';
                    //         var buttons =
                    //             '<a>' + rowData.abbr.toUpperCase() + '</a>';
                    //         $(td)[0].innerHTML = buttons
                    //     }
                    // },
                    // {
                    //     'targets': 2,
                    //     'orderable': false,
                    //     'createdCell': function(td, cellData, rowData, row, col) {
                    //         var disabled = '';
                    //         var buttons =
                    //             '<a>' + rowData.schoolname.toUpperCase() + '</a>';
                    //         $(td)[0].innerHTML = buttons
                    //     }
                    // },
                    // {
                    //     'targets': 3,
                    //     'orderable': false,
                    //     'createdCell': function(td, cellData, rowData, row, col) {
                    //         var disabled = '';
                    //         var buttons =
                    //             '<a style="cursor: pointer"  class="text-primary" data-id="' +
                    //             rowData.id + '">' + rowData.eslink + '</a>';
                    //         $(td)[0].innerHTML = buttons
                    //     }
                    // },

                    {
                        'targets': 4,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var buttons =
                                '<a href="javascript:void(0)" class="open_edit_user" data-id="' +
                                rowData.id + '"><i class="far fa-edit text-primary"></i></a>';
                            $(td)[0].innerHTML = buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    },

                    {
                        'targets': 5,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var buttons =
                                '<a type="button" href="javascript:void(0)" class="delete_user" data-id="' +
                                rowData.id +
                                '"><i class="far fa-trash-alt text-danger"></i></a>';
                            $(td)[0].innerHTML = buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    },
                ]

            });
        }

        function notify(code, message) {
            Toast.fire({
                icon: code,
                title: message,
            });

        }
    </script>
@endsection
