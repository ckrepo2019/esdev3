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
        /* .nav-tabs-block .nav-item .nav-link {
                    border-top-left-radius: 10px;
                    border-top-right-radius: 10px;
                } */

        .select2-container--default .select2-selection--single {
            background-color: #F0F1F2;
            /* Light gray background color */
            border: none;
            /* No border */
            height: 32px;
            /* Adjust the height as needed */
        }

        /* Style the arrow in the Select2 dropdown */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 30px;
            /* Adjust the height as needed */
        }

        th {
            white-space: nowrap;
        }
    </style>
@endsection

@section('content')
    <!-- MODAL USERTYPE -->
    <div class="modal fade" id="modal-block-popin-usertype" tabindex="-1" role="dialog"
        aria-labelledby="modal-block-popin-usertype" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title modal-title-genre">New Usertype</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content font-size-sm">
                        <div class="form-group">
                            <label class="mb-1">Type Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control font-size-sm form-control-alt" id="usertype_name"
                                placeholder="">
                            <span class="invalid-feedback" role="alert">
                                <strong>Usertype is required</strong>
                            </span>
                        </div>
                    </div>
                    <div class="block-content block-content-full text-right border-top">
                        <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary save_usertype">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL USERS -->
    <div class="modal fade" id="modal-block-popin-user" tabindex="-1" role="dialog"
        aria-labelledby="modal-block-popin-user" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title modal-title-lib">New User</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content font-size-sm">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="mb-1">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-alt font-size-sm" id="fname"
                                        onkeyup="this.value = this.value.toUpperCase();">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Name is required</strong>
                                    </span>
                                </div>

                                <div class="form-group">
                                    <label class="mb-1">Select Usertype <span class="text-danger">*</span></label>
                                    <select class="form-control custom-light-gray font-size-sm" id="select-usertype"
                                        style="width: 100%;">
                                        @foreach (DB::table('usertype')->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->utype }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Usertype is required</strong>
                                    </span>
                                </div>

                                <div class="form-group">
                                    <label class="mb-1">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-alt font-size-sm" id="username">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Username is required</strong>
                                    </span>
                                </div>

                                <div class="form-group">
                                    <label class="mb-1">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control form-control-alt font-size-sm"
                                        id="password">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Password is required</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="mb-1">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control form-control-alt font-size-sm"
                                        id="confirm_password">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Password Confirmation is required</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="block-content block-content-full text-right border-top">
                        <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary save_user">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL USERS END -->

    <!-- MODAL LIBRARY -->
    <div class="modal fade" id="modal-block-popin" tabindex="-1" role="dialog" aria-labelledby="modal-block-popin"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin modal-lg" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title modal-title-lib">New Library</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content font-size-sm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1">School Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-alt font-size-sm"
                                        id="lib_orgname">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Organization is required</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="mb-1">Library Name <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control font-size-sm form-control-alt"
                                        id="lib_name">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Library name is required</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="mb-1">Library Manager <span class="text-danger">*</span></label>
                                    <select class="form-control" id="lib_manager" style="width: 100%;">
                                        @foreach (DB::table('users')->where('type', 5)->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Manager is required</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="mb-1">Email Address </label>
                                    <input type="email" class="form-control font-size-sm form-control-alt"
                                        placeholder="e.g, sample@gmail.com" id="lib_email">
                                </div>
                                <div class="form-group">
                                    <label class="mb-1">Contact Number</label>
                                    <input type="text" class="form-control font-size-sm form-control-alt"
                                        id="lib_phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1">Website</label>
                                    <input type="text" class="form-control font-size-sm form-control-alt"
                                        id="lib_website" placeholder="www.sample.com">
                                </div>
                                <div class="form-group">
                                    <label class="mb-1">Department</label>
                                    <input type="text" class="form-control font-size-sm form-control-alt"
                                        id="lib_department">
                                </div>

                                <div class="form-group">
                                    <div
                                        class="custom-control custom-checkbox custom-checkbox-square custom-control-danger">
                                        <input type="checkbox" class="custom-control-input" id="checkbox_multiple_staff"
                                            name="checkbox_returned">
                                        <label class="custom-control-label" for="checkbox_multiple_staff">Multiple Staff
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="mb-1">In-Charge</label>
                                    <input type="text" class="form-control font-size-sm form-control-alt"
                                        id="lib_incharge" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="mb-1">Assistant</label>
                                    <input type="text" class="form-control font-size-sm form-control-alt"
                                        id="lib_asst" disabled>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="block-content block-content-full text-right border-top">
                        <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary save_library">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL LIBRARY END -->

    <!-- MODAL CATEGORY -->
    <div class="modal fade" id="modal-block-popin-cat" tabindex="-1" role="dialog"
        aria-labelledby="modal-block-popin-cat" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title modal-title-cat">New Category</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content font-size-sm">
                        <div class="form-group">
                            <label class="mb-1">Category Name <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control font-size-sm form-control-alt"
                                onkeyup="this.value = this.value.toUpperCase();" id="cat_name" placeholder="">
                            <span class="invalid-feedback" role="alert">
                                <strong>Name is required</strong>
                            </span>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="custom-control custom-radio custom-control-success mb-1">
                                    <input value="hard reference" type="radio" class="custom-control-input"
                                        id="example-rd-custom-danger1" name="reference_type" checked>
                                    <label class="custom-control-label" for="example-rd-custom-danger1">Hard
                                        Reference</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-info mb-1">
                                    <input value="e-reference" type="radio" class="custom-control-input"
                                        id="example-rd-custom-danger2" name="reference_type">
                                    <label class="custom-control-label" for="example-rd-custom-danger2">E-
                                        Reference</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="block-content block-content-full text-right border-top">
                        <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary save_category">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL CATEGORY END -->

    <!-- MODAL GENRE -->
    <div class="modal fade" id="modal-block-popin-genre" tabindex="-1" role="dialog"
        aria-labelledby="modal-block-popin-genre" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title modal-title-genre">New Genre</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content font-size-sm">
                        <div class="form-group">
                            <label class="mb-1">Genre Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control font-size-sm form-control-alt" id="genre_name"
                                onkeyup="this.value = this.value.toUpperCase();" placeholder="">
                            <span class="invalid-feedback" role="alert">
                                <strong>Name is required</strong>
                            </span>
                        </div>
                    </div>
                    <div class="block-content block-content-full text-right border-top">
                        <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary save_genre">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL GENRE END -->

    <!-- MODAL BORROWER -->
    <div class="modal fade" id="modal-block-popin-borrower" tabindex="-1" role="dialog"
        aria-labelledby="modal-block-popin-borrower" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin modal-lg" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title modal-title-borrower">New Borrower</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content font-size-sm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1">Card ID # <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control font-size-sm form-control-alt"
                                        id="borrower_cardno" placeholder="">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>ID Number is required</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="mb-1">Borrower's Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control font-size-sm form-control-alt"
                                        id="borrower_name" placeholder="">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Name is required</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="mb-1">Class/Position <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control font-size-sm form-control-alt"
                                        id="borrower_class" placeholder="">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Class is required</strong>
                                    </span>
                                </div>

                                <div class="form-group">
                                    <div
                                        class="custom-control custom-checkbox custom-checkbox-square custom-control-lg custom-control-success">
                                        <input type="checkbox" class="custom-control-input" id="checkbox_allow_borrower"
                                            name="checkbox_allow_borrower">
                                        <label class="custom-control-label" for="checkbox_allow_borrower"> Enable Library
                                            Access to this Borrower </label>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1">Email Ad <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control font-size-sm form-control-alt"
                                        id="borrower_email" placeholder="">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Email is required</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="mb-1">Contact Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control font-size-sm form-control-alt"
                                        id="borrower_phone" placeholder="">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Contact is required</strong>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="mb-1">Address</label>
                                    <input type="text" class="form-control font-size-sm form-control-alt"
                                        id="borrower_address" placeholder="">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Address is required</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control font-size-sm form-control-alt"
                                        id="borrower_username" placeholder="Enter Username">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Username is required</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 borrow_pass">
                                <div class="form-group">
                                    <label class="mb-1">Password</label>
                                    <input type="password" class="form-control font-size-sm form-control-alt"
                                        id="borrower_password" placeholder="Default (Card Id No.)">
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Password is required</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block-content block-content-full text-right border-top">
                        <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary save_borrower">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL BORROWER END -->

    <div class="content">

        <!-- USERTYPE -->
        {{-- <div class="block block-rounded" id="tb_usertype" hidden>

            <div class="block-header bg-primary">
                <h3 class="block-title font-w700" style="font-size: 16px;">  <i class="fa fa-wrench mr-1"></i> GENERAL SETUP</h3>
                <button type="button" class="btn ml-auto bg-dark newusertype font-size-sm text-light my-1" data-toggle="modal"
                    data-target="#modal-block-popin-usertype">
                    <i class="fa fa-fw fa-plus mr-1"></i>ADD USERTYPE
                </button>
            </div>

            <ul class="nav nav-tabs nav-tabs-alt">
                @if (auth()->user()->type == 5)
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/users?action=users">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/usertype?action=usertype">Usertype</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/libraries?action=lib">Library</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/category?action=cat">Category</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/genre?action=genre">Genre</a></li>
                @endif
                <li class="nav-item"><a class="nav-link" href="/admin/setup/borrower?action=borrower">Borrower</a></li>
            </ul>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-striped table-vcenter js-dataTable-full" id="table_usertype"
                        style="width: 100%;">
                        <thead >
                            <tr>
                                <th width="10%">ID</th>
                                <th >Usertype</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div> --}}

        <!--  USERS -->
        {{-- <div class="block block-rounded" id="tb_users" hidden>

            <div class="block-header bg-primary">
                <h3 class="block-title font-w700" style="font-size: 16px;"> <i class="fa fa-wrench mr-1"></i> GENERAL SETUP</h3>
                <button type="button" class="btn ml-auto bg-dark newuser font-size-sm text-light my-1" data-toggle="modal"
                    data-target="#modal-block-popin-user">
                    <i class="fa fa-fw fa-plus mr-1"></i>ADD USER
                </button>
            </div>

            <ul class="nav nav-tabs nav-tabs-alt">
                @if (auth()->user()->type == 5)
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/users?action=users">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/usertype?action=usertype">Usertype</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/libraries?action=lib">Library</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/category?action=cat">Category</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/genre?action=genre">Genre</a></li>
                @endif
                <li class="nav-item"><a class="nav-link" href="/admin/setup/borrower?action=borrower">Borrower</a></li>
            </ul>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-hover table-vcenter table-striped table-borderless js-dataTable-full" id="table_users"
                        style="width: 100%;">
                        <thead class=" ">
                            <tr>
                                <th width="10%"></th>
                                <th width="20%">Name</th>
                                <th width="30%">Username</th>
                                <th width="30%">Usertype</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div> --}}

        <!--  LIBRARIES -->
        <div class="block block-rounded" id="tb_lib" hidden>

            <div class="block-header bg-primary">
                <h3 class="block-title font-w700" style="font-size: 16px;"> <i class="fa fa-wrench mr-1"></i> GENERAL
                    SETUP</h3>
                <button type="button" class="btn ml-auto bg-dark newlib font-size-sm text-light my-1"
                    data-toggle="modal" data-target="#modal-block-popin">
                    <i class="fa fa-fw fa-plus mr-1"></i>ADD LIBRARY
                </button>
            </div>

            <ul class="nav nav-tabs nav-tabs-alt">
                @if (auth()->user()->type == 5)
                    {{-- <li class="nav-item"><a class="nav-link" href="/admin/setup/users?action=users">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/usertype?action=usertype">Usertype</a></li> --}}
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/libraries?action=lib">Library</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/category?action=cat">Category</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/genre?action=genre">Genre</a></li>
                @endif
                <li class="nav-item"><a class="nav-link" href="/admin/setup/borrower?action=borrower">Borrower</a></li>
            </ul>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-striped table-vcenter js-dataTable-full"
                        id="table_library" style="width: 100%;">
                        <thead class="thead-dark">
                            <tr>
                                <th width="10%">ID</th>
                                <th width="20%">Branch Name</th>
                                <th width="20%">In-Charge</th>
                                <th width="20%">Email Ad</th>
                                <th width="20%">Phone</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!--  CATEGORY -->
        <div class="block block-rounded" id="tb_cat" hidden>
            <div class="block-header bg-primary">
                <h3 class="block-title font-w700" style="font-size: 16px;"> <i class="fa fa-wrench mr-1"></i> GENERAL
                    SETUP</h3>
                <button type="button" class="btn ml-auto bg-dark newcat font-size-sm text-light my-1"
                    data-toggle="modal" data-target="#modal-block-popin-cat">
                    <i class="fa fa-fw fa-plus mr-1"></i>ADD CATEGORY
                </button>
            </div>

            <ul class="nav nav-tabs nav-tabs-alt">
                @if (auth()->user()->type == 5)
                    {{-- <li class="nav-item"><a class="nav-link" href="/admin/setup/users?action=users">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/usertype?action=usertype">Usertype</a></li> --}}
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/libraries?action=lib">Library</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/category?action=cat">Category</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/genre?action=genre">Genre</a></li>
                @endif
                <li class="nav-item"><a class="nav-link" href="/admin/setup/borrower?action=borrower">Borrower</a></li>
            </ul>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-borderless table-vcenter js-dataTable-full"
                        id="table_category" style="width: 100%;">
                        <thead class="thead-dark">
                            <tr>
                                <th width="10%">ID</th>
                                <th>Category name</th>
                                <th>Reference</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!--  GENRES -->
        <div class="block block-rounded" id="tb_genre" hidden>
            <div class="block-header bg-primary">
                <h3 class="block-title font-w700" style="font-size: 16px;"> <i class="fa fa-wrench mr-1"></i> GENERAL
                    SETUP</h3>
                <button type="button" class="btn ml-auto bg-dark newgen font-size-sm text-light my-1"
                    data-toggle="modal" data-target="#modal-block-popin-genre">
                    <i class="fa fa-fw fa-plus mr-1"></i>ADD GENRE
                </button>
            </div>
            <ul class="nav nav-tabs nav-tabs-alt">
                @if (auth()->user()->type == 5)
                    {{-- <li class="nav-item"><a class="nav-link" href="/admin/setup/users?action=users">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/usertype?action=usertype">Usertype</a></li> --}}
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/libraries?action=lib">Library</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/category?action=cat">Category</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/genre?action=genre">Genre</a></li>
                @endif
                <li class="nav-item"><a class="nav-link" href="/admin/setup/borrower?action=borrower">Borrower</a></li>
            </ul>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-borderless table-vcenter js-dataTable-full"
                        id="table_genre" style="width: 100%;">
                        <thead class="thead-dark">
                            <tr>
                                <th width="5%">ID</th>
                                <th>Genre</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- BORROWER -->
        <div class="block block-rounded" id="tb_borrower" hidden>
            <div class="block-header bg-primary">
                <h3 class="block-title font-w700" style="font-size: 16px;"> <i class="fa fa-wrench mr-1"></i> GENERAL
                    SETUP</h3>
            </div>
            <ul class="nav nav-tabs nav-tabs-alt">
                @if (auth()->user()->type == 5)
                    {{-- <li class="nav-item"><a class="nav-link" href="/admin/setup/users?action=users">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/usertype?action=usertype">Usertype</a></li> --}}
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/libraries?action=lib">Library</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/category?action=cat">Category</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/setup/genre?action=genre">Genre</a></li>
                @endif
                <li class="nav-item"><a class="nav-link" href="/admin/setup/borrower?action=borrower">Borrower</a></li>
            </ul>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-borderless table-vcenter js-dataTable-full"
                        id="table_borrower" style="width: 100%;">
                        <thead class="thead-dark">
                            <tr>
                                <th>Card #</th>
                                <th>Borrower's Name</th>
                                <th>Class/Position</th>
                                <th>Email Ad</th>
                                <th>Contact No.</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
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
        var currentId;
        var purpose = '';
        var jsonData = {!! json_encode($jsonData) !!};
        $(document).ready(function() {
            $('.form-control').on('focus', function() {
                $(this).removeClass('is-invalid');
            });
            // Clear validation errors when the modal is shown
            $('#modal-block-popin').on('show.bs.modal', function() {
                clearValidationErrors();
            });
            $('#modal-block-popin-user').on('show.bs.modal', function() {
                clearValidationErrors();
            });
            $('#modal-block-popin-cat').on('show.bs.modal', function() {
                clearValidationErrors();
            });
            $('#modal-block-popin-genre').on('show.bs.modal', function() {
                clearValidationErrors();
            });

            // Clear validation errors when the modal is hidden
            $('#modal-block-popin-borrower').on('hidden.bs.modal', function() {
                clearValidationErrors();
            });

            $('#select-usertype').select2();
            $('#confirm_password').on('input', function() {
                if (!validatePasswordMatch()) {
                    notify('error', 'Password confirmation doesn\'t match')
                } else {
                    notify('success', 'Password confirmation match')
                }
            });
            $('.save_user').on('click', function() {
                if (validateForm()) {
                    saveUser()
                }
            });
            $('#lib_manager').select2({
                search: true,
                allowClear: true,
                placeholder: "...",
            });
            if ('{{ request()->is('library/admin/setup/libraries') }}') {
                $('#tb_lib').prop('hidden', false)
                // $('.content-heading').text('LIBRARY INFORMATION')
                load_library_datatable(jsonData)
                $('a[href="/library/admin/setup/libraries?action=lib"]').addClass('active');
            } else if ('{{ request()->is('library/admin/setup/category') }}') {
                // $('.content-heading').text('CATEGORIES')
                $('#tb_cat').prop('hidden', false)
                load_category_datatable(jsonData)
                $('a[href="/library/admin/setup/category?action=cat"]').addClass('active');
            } else if ('{{ request()->is('library/admin/setup/usertype') }}') {
                $('#tb_usertype').prop('hidden', false)
                load_usertype_datatable(jsonData)
                $('a[href="/library/admin/setup/usertype?action=usertype"]').addClass('active');
            } else if ('{{ request()->is('library/admin/setup/genre') }}') {
                // $('.content-heading').text('GENRES')
                $('#tb_genre').prop('hidden', false)
                load_genre_datatable(jsonData)
                $('a[href="/library/admin/setup/genre?action=genre"]').addClass('active');
            } else if ('{{ request()->is('library/admin/setup/borrower') }}') {
                // $('.content-heading').text('BORROWERS')
                $('#tb_borrower').prop('hidden', false)
                load_borrower_datatable(jsonData)
                $('a[href="/library/admin/setup/borrower?action=borrower"]').addClass('active');
            } else {
                // $('.content-heading').text('USERS INFORMATION')
                $('#tb_users').prop('hidden', false)
                console.log(jsonData);
                load_users_datatable(jsonData)
                $('a[href="/library/admin/setup/users?action=users"]').addClass('active');
            }

            // USERTYPE

            $(document).on('click', '.newusertype', function() {
                purpose = 'store';
            });

            $(document).on('click', '.save_usertype', function() {
                if (!$('#usertype_name').val()) {
                    $('#usertype_name').addClass('is-invalid');
                } else {
                    $.ajax({
                        type: 'GET',
                        data: {
                            id: currentId,
                            purpose: purpose,
                            usertype: $('#usertype_name').val(),
                        },
                        url: '{{ route('add.usertype') }}',
                        success: function(data) {
                            notify(data.status, data.message);
                            load_usertype();
                        }
                    });
                }
            });

            $(document).on('click', '.delete_type', function() {
                var id = $(this).attr('data-id');
                Swal.fire({
                    icon: 'warning',
                    title: 'You want to delete this usertype?',
                    text: `You can't undo this process.`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            url: '{{ route('delete.usertype') }}',
                            type: "GET",
                            data: {
                                id: id,
                            },
                            success: function(data) {
                                notify(data.status, data.message);
                                load_usertype();
                            }
                        });
                    }

                })
            });

            $(document).on('click', '.edit_type', function() {
                var id = $(this).attr('data-id');
                purpose = 'edit';
                currentId = id;
                $.ajax({
                    url: '{{ route('get.usertype') }}',
                    type: "GET",
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        console.log(data);
                        $('#usertype_name').val(data.usertype);
                        $('#modal-block-popin-usertype').modal();
                    }
                });

            });

            // USERS

            $(document).on('click', '.reset_password', function() {
                var id = $(this).attr('data-id');
                $.ajax({
                    type: 'GET',
                    data: {
                        id: id,
                    },
                    url: '{{ route('reset.password') }}',
                    success: function(data) {
                        console.log(data)
                        notify(data.status, data.message);
                        load_users();
                    }
                })
            })

            $(document).on('click', '.delete_user', function() {
                var id = $(this).attr('data-id');
                Swal.fire({
                    icon: 'warning',
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
                                load_users()
                            }
                        });
                    }

                })
            });

            // LIBRARIES

            $('#checkbox_multiple_staff').change(function() {
                // Enable or disable inputs based on checkbox state
                $('#lib_incharge, #lib_asst').prop('disabled', !$(this).prop('checked'));
            });

            $(document).on('click', '.delete_lib', function() {
                var id = $(this).attr('data-id');
                Swal.fire({
                    icon: 'warning',
                    title: 'You want to delete this library?',
                    text: `You can't undo this process.`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            url: '{{ route('delete.library') }}',
                            type: "GET",
                            data: {
                                id: id,
                            },
                            success: function(data) {
                                notify(data[0].statusCode, data[0].message);
                                load_libraries()
                            }
                        });
                    }

                })
            });

            $(document).on('click', '.newlib', function() {
                purpose = 'store'
                $('.save_library').show();
                $('#lib_name').val('');
                $('#lib_orgname').val('');
                $('#lib_manager').val('').change();
                $('#lib_phone').val('');
                $('#lib_website').val('');
                $('#lib_department').val('');
                $('#lib_email').val('');
                $('#lib_incharge').val('');
                $('#lib_asst').val('');

                $('.form-control').removeClass('is-invalid');

                $('#lib_name').prop("readonly", false);
                $('#lib_orgname').prop("readonly", false);
                $('#lib_manager').prop('disabled', false);
                $('#lib_manager').trigger('change.select2');
                $('#lib_phone').prop("readonly", false);
                $('#lib_website').prop("readonly", false);
                $('#lib_department').prop("readonly", false);
                $('#lib_email').prop("readonly", false);
                $('.modal-title-lib').text('New Library');
            });

            $(document).on('click', '.edit_lib', function() {
                purpose = 'edit';
                var id = $(this).attr('data-id');
                currentId = id
                $('#lib_incharge').prop("readonly", false);
                $('#lib_asst').prop("readonly", false);
                $('#lib_orgname').prop("readonly", false);
                $('#lib_name').prop("readonly", false);
                $('#lib_manager').prop('disabled', false);
                $('#lib_manager').trigger('change.select2');
                $('#lib_phone').prop("readonly", false);
                $('#lib_website').prop("readonly", false);
                $('#lib_department').prop("readonly", false);
                $('#lib_email').prop("readonly", false);
                // $('.save_library').removeClass(' btn-alt-dark')
                // $('.save_library').addClass('btn-success')
                $('.save_library').show();
                $('.modal-title-lib').text('Edit Library');
                $.ajax({
                    type: 'GET',
                    data: {
                        id: id
                    },
                    url: '{{ route('get.library') }}',
                    success: function(data) {
                        console.log(data)
                        currentId = data.id;
                        $('#lib_name').val(data.library_name);
                        $('#lib_orgname').val(data.library_orgname);
                        $('#lib_incharge').val(data.library_incharge);
                        $('#lib_asst').val(data.library_asst);
                        $('#lib_manager').val(data.library_manager).change();
                        $('#lib_phone').val(data.library_phone);
                        $('#lib_website').val(data.library_website);
                        $('#lib_department').val(data.library_department);
                        $('#lib_email').val(data.library_email);
                        $('#modal-block-popin').modal();
                    }
                })
            });

            $(document).on('click', '.view_lib', function() {
                purpose = 'view';
                var id = $(this).attr('data-id');
                currentId = id

                $('.save_library').hide();
                // $('.save_library').removeClass('btn-success')
                $('.modal-title-lib').text('Library Details');

                $('#lib_name').prop("readonly", true);
                $('#lib_orgname').prop("readonly", true);
                $('#lib_incharge').prop("readonly", true);
                $('#lib_asst').prop("readonly", true);
                $('#lib_manager').prop('disabled', true);
                $('#lib_manager').trigger('change.select2');
                $('#lib_phone').prop("readonly", true);
                $('#lib_website').prop("readonly", true);
                $('#lib_department').prop("readonly", true);
                $('#lib_email').prop("readonly", true);
                $.ajax({
                    type: 'GET',
                    data: {
                        id: id
                    },
                    url: '{{ route('get.library') }}',
                    success: function(data) {
                        currentId = data.id;
                        $('#lib_name').val(data.library_name);
                        $('#lib_orgname').val(data.library_orgname);
                        $('#lib_incharge').val(data.library_incharge);
                        $('#lib_asst').val(data.library_asst);
                        $('#lib_manager').val(data.library_manager).change();
                        $('#lib_phone').val(data.library_phone);
                        $('#lib_website').val(data.library_website);
                        $('#lib_department').val(data.library_department);
                        $('#lib_email').val(data.library_email);
                        $('#modal-block-popin').modal();
                    }
                })
            });

            $(document).on('click', '.save_library', function() {
                var lib_name = $('#lib_name');
                var lib_orgname = $('#lib_orgname');
                var lib_manager = $('#lib_manager');
                var lib_phone = $('#lib_phone');
                var lib_website = $('#lib_website');
                var lib_department = $('#lib_department');
                var lib_email = $('#lib_email');
                var lib_incharge = $('#lib_incharge');
                var lib_asst = $('#lib_asst');


                console.log(lib_incharge.val())

                if (!lib_orgname.val()) {
                    lib_orgname.addClass('is-invalid')
                    notify('error', 'Organization name is required!')
                    return
                }
                if (!lib_name.val()) {
                    lib_name.addClass('is-invalid')
                    notify('error', 'Library name is required!')
                    return
                }
                if (!lib_manager.val()) {
                    lib_manager.addClass('is-invalid')
                    notify('error', 'Library manager is required!')
                    return
                }

                if (purpose === 'edit') {
                    console.log(currentId)
                    $.ajax({
                        type: 'GET',
                        data: {
                            id: currentId,
                            lib_name: lib_name.val(),
                            lib_orgname: lib_orgname.val(),
                            lib_manager: lib_manager.val(),
                            lib_phone: lib_phone.val(),
                            lib_website: lib_website.val(),
                            lib_department: lib_department.val(),
                            lib_email: lib_email.val(),
                            lib_incharge: lib_incharge.val(),
                            lib_asst: lib_asst.val(),
                        },
                        url: '{{ route('update.library') }}',
                        success: function(data) {
                            notify(data[0].statusCode, data[0].message);
                            load_libraries();
                        }
                    });
                } else if (purpose === 'store') {
                    console.log(lib_incharge.val())
                    $.ajax({
                        type: 'GET',
                        data: {
                            lib_name: lib_name.val(),
                            lib_orgname: lib_orgname.val(),
                            lib_manager: lib_manager.val(),
                            lib_phone: lib_phone.val(),
                            lib_website: lib_website.val(),
                            lib_department: lib_department.val(),
                            lib_email: lib_email.val(),
                            lib_incharge: lib_incharge.val(),
                            lib_asst: lib_asst.val(),
                        },
                        url: '{{ route('add.library') }}',
                        success: function(data) {
                            notify(data[0].statusCode, data[0].message);
                            load_libraries();
                        }
                    });
                }
            })

            // CATEGORIES

            $(document).on('click', '.delete_cat', function() {
                var id = $(this).attr('data-id');
                Swal.fire({
                    icon: 'warning',
                    title: 'You want to delete this category?',
                    text: `You can't undo this process.`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('delete.category') }}',
                            type: "GET",
                            data: {
                                id: id,
                            },
                            success: function(data) {
                                notify(data[0].statusCode, data[0].message);
                                load_categories()
                            }
                        });
                    }

                })
            });

            $(document).on('click', '.edit_cat', function() {
                purpose = 'edit';
                var id = $(this).attr('data-id');
                currentId = id
                $('.modal-title-cat').text('Edit Category');
                $.ajax({
                    type: 'GET',
                    data: {
                        id: id
                    },
                    url: '{{ route('get.category') }}',
                    success: function(data) {
                        console.log(data)
                        currentId = data.id;
                        $('#cat_name').val(data.category_name);
                        // $('#cat_reference').val(data.category_reference);
                        $('input[name="reference_type"]').val([data.category_reference])
                        $('#modal-block-popin-cat').modal();
                    }
                })
            });

            $(document).on('click', '.newcat', function() {
                purpose = 'store'
                $('.modal-title-cat').text('New Category');
                $('#cat_name').val('');
                $('#cat_reference').val('');
                clearValidationErrors();

            });

            $(document).on('click', '.save_category', function() {
                var cat_name = $('#cat_name').val();
                var cat_reference = $('input[name="reference_type"]:checked').val();

                if (!cat_name) {
                    $('#cat_name').addClass('is-invalid')
                    notify('error', 'Category name is required!')
                    return
                }
                if (!cat_reference) {
                    notify('error', 'Select Reference type!')
                    return
                }

                if (purpose === 'edit') {
                    $.ajax({
                        type: 'GET',
                        data: {
                            id: currentId,
                            cat_name: cat_name,
                            cat_reference: cat_reference,
                        },
                        url: '{{ route('update.category') }}',
                        success: function(data) {
                            notify(data[0].statusCode, data[0].message);
                            load_categories();
                        }
                    });
                } else if (purpose === 'store') {
                    $.ajax({
                        type: 'GET',
                        data: {
                            cat_name: cat_name,
                            cat_reference: cat_reference,
                        },
                        url: '{{ route('add.category') }}',
                        success: function(data) {
                            notify(data[0].statusCode, data[0].message);
                            load_categories();
                        }
                    })
                }
            })

            // GENRES

            $(document).on('click', '.delete_genre', function() {
                var id = $(this).attr('data-id');
                Swal.fire({
                    icon: 'warning',
                    title: 'You want to delete this genre?',
                    text: `You can't undo this process.`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('delete.genre') }}',
                            type: "GET",
                            data: {
                                id: id,
                            },
                            success: function(data) {
                                notify(data[0].statusCode, data[0].message);
                                load_genres()
                            }
                        });
                    }

                })
            });

            $(document).on('click', '.edit_genre', function() {
                purpose = 'edit';
                var id = $(this).attr('data-id');
                currentId = id
                $('.modal-title-genre').text('Edit Genre');
                $.ajax({
                    type: 'GET',
                    data: {
                        id: id
                    },
                    url: '{{ route('get.genre') }}',
                    success: function(data) {
                        currentId = data.id;
                        $('#genre_name').val(data.genre_name);
                        $('#modal-block-popin-genre').modal();
                    }
                })
            });

            $(document).on('click', '.newgen', function() {
                purpose = 'store'
                clearValidationErrors()
                $('.modal-title-genre').text('New Genre');
                $('#genre_name').val('');

            });

            $(document).on('click', '.save_genre', function() {
                var genre_name = $('#genre_name').val();

                if (!genre_name) {
                    $('#genre_name').addClass('is-invalid')
                    notify('error', 'Genre name is required!')
                    return
                }

                if (purpose === 'edit') {
                    $.ajax({
                        type: 'GET',
                        data: {
                            id: currentId,
                            genre_name: genre_name,
                        },
                        url: '{{ route('update.genre') }}',
                        success: function(data) {
                            notify(data[0].statusCode, data[0].message);
                            load_genres();
                        }
                    });
                } else if (purpose === 'store') {
                    $.ajax({
                        type: 'GET',
                        data: {
                            genre_name: genre_name,
                        },
                        url: '{{ route('add.genre') }}',
                        success: function(data) {
                            notify(data[0].statusCode, data[0].message);
                            load_genres();
                        }
                    })
                }
            })

            // BORROWERS

            $(document).on('click', '.delete_borrower', function() {
                var id = $(this).attr('data-id');
                Swal.fire({
                    icon: 'warning',
                    title: 'You want to delete this borrower?',
                    text: `You can't undo this process.`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            url: '{{ route('delete.borrower') }}',
                            type: "GET",
                            data: {
                                id: id,
                            },
                            success: function(data) {
                                notify(data[0].statusCode, data[0].message);
                                load_borrowers()
                            }
                        });
                    }

                })
            });

            $(document).on('click', '.newborrower', function() {
                purpose = 'store'
                $('.save_borrower').show();
                $('#borrower_cardno').val('');
                $('#borrower_name').val('');
                $('#borrower_class').val('');
                $('#borrower_email').val('');
                $('#borrower_phone').val('');
                $('#borrower_username').val('');
                $('#borrower_password').val('');

                $('#borrower_cardno').prop("readonly", false);
                $('#borrower_name').prop('disabled', false);
                $('#borrower_class').prop('readonly', false);
                $('#borrower_email').prop("readonly", false);
                $('#borrower_phone').prop("readonly", false);
                $('#borrower_username').prop("disabled", false);
                $('#borrower_address').prop("readonly", false);
                $('.borrow_pass').show();
                $('.save_borrower').removeClass('btn-alt-dark')
                $('.save_borrower').addClass('btn-success')
                $('.modal-title-borrower').text('New Borrower');
            });

            $(document).on('click', '.edit_borrower', function() {
                purpose = 'edit';
                var id = $(this).attr('data-id');
                currentId = id
                console.log(id)
                $('.save_borrower').show();
                $('#borrower_cardno').prop("readonly", false);
                $('#borrower_name').prop('disabled', false);
                $('#borrower_class').prop('readonly', false);
                $('#borrower_email').prop("readonly", false);
                $('#borrower_phone').prop("readonly", false);
                $('#borrower_address').prop("readonly", false);
                $('#borrower_username').prop("disabled", true);
                $('.borrow_pass').hide();
                $('.save_borrower').removeClass(' btn-alt-dark')
                $('.save_borrower').addClass('btn-success')
                $('.modal-title-borrower').text('Edit Borrower');
                $.ajax({
                    type: 'GET',
                    data: {
                        id: id
                    },
                    url: '{{ route('get.borrower') }}',
                    success: function(data) {
                        currentId = data.id;
                        console.log(data);

                        $('#borrower_cardno').val(data.borrower_cardno);
                        $('#borrower_name').val(data.borrower_name);
                        $('#borrower_class').val(data.borrower_class);
                        $('#borrower_email').val(data.borrower_email);
                        $('#borrower_phone').val(data.borrower_phone);
                        $('#borrower_username').val(data.borrower_username);
                        $('#checkbox_allow_borrower').prop('checked', data.borrower_status)

                        $('#modal-block-popin-borrower').modal();
                    }
                })
            });

            $(document).on('click', '.view_borrower', function() {
                purpose = 'view';
                var id = $(this).attr('data-id');
                currentId = id
                $('#borrower_cardno').prop("readonly", true);
                $('#borrower_name').prop('disabled', true);
                $('#borrower_class').prop('readonly', true);
                $('#borrower_email').prop("readonly", true);
                $('#borrower_phone').prop("readonly", true);
                $('#borrower_username').prop("readonly", true);
                $('#borrower_address').prop("readonly", true);
                $('.borrow_pass').hide();
                $('.save_borrower').hide();
                $('.save_borrower').addClass(' btn-alt-dark')
                $('.save_borrower').removeClass('btn-success')
                $('.modal-title-borrower').text('Borrower Details');
                $.ajax({
                    type: 'GET',
                    data: {
                        id: id
                    },
                    url: '{{ route('get.borrower') }}',
                    success: function(data) {
                        currentId = data.id;
                        $('#borrower_cardno').val(data.borrower_cardno);
                        $('#borrower_name').val(data.borrower_name);
                        $('#borrower_class').val(data.borrower_class);
                        $('#borrower_email').val(data.borrower_email);
                        $('#borrower_phone').val(data.borrower_phone);
                        $('#borrower_username').val(data.borrower_username);
                        $('#checkbox_allow_borrower').prop('checked', data.borrower_status)


                        $('#modal-block-popin-borrower').modal();
                    }
                })
            });

            $(document).on('click', '.save_borrower', function() {
                var borrower_cardno = $('#borrower_cardno');
                var borrower_name = $('#borrower_name');
                var borrower_class = $('#borrower_class');
                var borrower_email = $('#borrower_email');
                var borrower_phone = $('#borrower_phone');
                var borrower_username = $('#borrower_username');
                var borrower_password = $('#borrower_password');

                // Reset previous validation
                $('#borrower_cardno, #borrower_name, #borrower_class, #borrower_email, #borrower_phone, #borrower_username, #borrower_password')
                    .removeClass(
                        'is-invalid');

                if (!borrower_cardno.val()) {
                    borrower_cardno.addClass('is-invalid')
                    notify('error', 'Borrower Card No. is required!')
                    return
                }
                if (!borrower_name.val()) {
                    borrower_name.addClass('is-invalid')
                    notify('error', 'Borrower Name is required!')
                    return
                }
                if (!borrower_class.val()) {
                    borrower_class.addClass('is-invalid')
                    notify('error', 'Borrower Class is required!')
                    return
                }
                if (!borrower_email.val()) {
                    borrower_email.addClass('is-invalid')
                    notify('error', 'Borrower Email is required!')
                    return
                }
                if (!borrower_phone.val()) {
                    borrower_phone.addClass('is-invalid')
                    notify('error', 'Borrower Phone is required!')
                    return
                }
                if (!borrower_username.val()) {
                    borrower_username.addClass('is-invalid')
                    notify('error', 'Borrower Username is required!')
                    return
                }

                if (purpose === 'edit') {
                    console.log('hello')
                    $.ajax({
                        type: 'GET',
                        data: {
                            id: currentId,
                            borrower_cardno: borrower_cardno.val(),
                            borrower_name: borrower_name.val(),
                            borrower_class: borrower_class.val(),
                            borrower_email: borrower_email.val(),
                            borrower_phone: borrower_phone.val(),
                            borrower_status: $('#checkbox_allow_borrower').prop('checked') ? 1 : 0,
                            borrower_username: borrower_username.val(),
                            // borrower_password: borrower_password.val() ?? borrower_cardno.val(),
                        },
                        url: '{{ route('update.borrower') }}',
                        success: function(data) {
                            console.log(data)
                            notify(data[0].statusCode, data[0].message);
                            load_borrowers();
                            return;
                        }
                    });
                } else if (purpose === 'store') {
                    $.ajax({
                        type: 'GET',
                        data: {
                            borrower_cardno: borrower_cardno.val(),
                            borrower_name: borrower_name.val(),
                            borrower_class: borrower_class.val(),
                            borrower_email: borrower_email.val(),
                            borrower_phone: borrower_phone.val(),
                            borrower_status: $('#checkbox_allow_borrower').prop('checked') ? 1 : 0,
                            borrower_username: borrower_username.val(),
                            borrower_password: borrower_password.val(),
                        },
                        url: '{{ route('add.borrower') }}',
                        success: function(data) {
                            notify(data[0].statusCode, data[0].message);
                            load_borrowers();
                            return;
                        }
                    });
                }
            });
        });

        function clearValidationErrors() {
            $('.form-control').removeClass('is-invalid');
        }

        // USER
        function saveUser() {
            $.ajax({
                url: '{{ route('saveUser') }}',
                method: 'GET',
                data: {
                    name: $('#fname').val(),
                    usertype: $('#select-usertype').val(),
                    username: $('#username').val(),
                    password: $('#password').val(),
                },
                success: function(response) {
                    notify(response.status, response.message);
                    load_users();
                },
                error: function(xhr, status, error) {
                    notify('error', 'Error saving user. Please try again.', 'error');
                }
            });
        }

        function validatePasswordMatch() {
            // Check if passwords match
            var password = $('#password').val().trim();
            var confirmPassword = $('#confirm_password').val().trim();

            if (password !== confirmPassword) {
                return false;
            }

            return true;
        }

        function validateForm() {
            var isValid = true;

            // Reset previous validation
            $('#fname, #username, #password, #confirm_password, #select-usertype').removeClass('is-invalid');

            // Check if any field is empty
            if ($('#fname').val().trim() === '') {
                $('#fname').addClass('is-invalid');
                notify('error', 'Name is required.');

                isValid = false;
            }

            if ($('#username').val().trim() === '') {
                $('#username').addClass('is-invalid');
                notify('error', 'Username is required.');

                isValid = false;
            }

            if ($('#select-usertype').val().trim() === '') {
                $('#select-usertype').addClass('is-invalid');
                notify('error', 'Usertype is required.');

                isValid = false;
            }

            if ($('#password').val().trim() === '') {
                $('#password').addClass('is-invalid');
                notify('error', 'Password is required.');

                isValid = false;
            }

            if ($('#confirm_password').val().trim() === '') {
                $('#confirm_password').addClass('is-invalid');
                notify('error', 'Confirm password is required.');
                isValid = false;
            }

            // Check if passwords match
            if ($('#password').val().trim() !== $('#confirm_password').val().trim()) {
                $('#password, #confirm_password').addClass('is-invalid');
                notify('error', 'Passwords do not match.');
                isValid = false;
            }

            return isValid;
        }

        // LIBRARIES
        function load_libraries() {
            $.ajax({
                url: '{{ route('libraries') }}',
                type: "GET",
                success: function(data) {
                    console.log()
                    load_library_datatable(data)
                }
            });
        }

        // CATEGORIES
        function load_categories() {
            $.ajax({
                url: '{{ route('categories') }}',
                type: "GET",
                success: function(data) {
                    console.log()
                    load_category_datatable(data)
                }
            });
        }

        // GENRES
        function load_genres() {
            $.ajax({
                url: '{{ route('genres') }}',
                type: "GET",
                success: function(data) {
                    console.log()
                    load_genre_datatable(data)
                }
            });
        }

        // BORROWERS
        function load_borrowers() {
            $.ajax({
                url: '{{ route('borrowers') }}',
                type: "GET",
                success: function(data) {
                    load_borrower_datatable(data)
                }
            });
        }

        // USERS
        function load_users() {
            $.ajax({
                url: '{{ route('users') }}',
                type: "GET",
                success: function(data) {
                    load_users_datatable(data)
                }
            });
        }

        // USERTYPE
        function load_usertype() {
            $.ajax({
                url: '{{ route('usertypes') }}',
                type: "GET",
                success: function(data) {
                    load_usertype_datatable(data)
                }
            });
        }

        // DATATABLES
        function load_library_datatable(data) {
            $("#table_library").DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                stateSave: true,
                columns: [{
                        data: 'id',
                        sortable: false,
                        render: function(data, type, row) {
                            return `<span class="font-size-sm font-w600"> #${row.id} </span>`;
                        }
                    },
                    {
                        data: 'library_name',
                        render: function(data, type, row) {
                            return `<a class="font-size-sm font-w600"> ${row.library_name.toUpperCase()} </a>`;
                        }
                    },
                    {
                        data: 'name',
                        render: function(data, type, row) {
                            var capitalizeFirstLetter = function(string) {
                                return string.toLowerCase().replace(/\b\w/g, function(match) {
                                    return match.toUpperCase();
                                });
                            };
                            return `<a class="font-w600 font-size-sm text-primary-dark"> ${ capitalizeFirstLetter(row.name) } </a>`;
                        }
                    },
                    {
                        data: 'library_email',
                        className: 'font-size-sm text-center',
                    },
                    {
                        data: 'library_phone',
                        className: 'font-size-sm text-center'
                    },
                    {
                        data: null,
                        sortable: false,
                        className: 'text-right',
                        render: function(type, data, row) {
                            var renderHtml = ` <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-alt-primary view_lib" data-id="${row.id}" data-toggle="tooltip" title="View Library">
                                                        <i class="fa fa-fw far fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-alt-primary edit_lib" data-id="${row.id}" data-toggle="tooltip" title="Edit Library">
                                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-alt-primary delete_lib" data-id="${row.id}" data-toggle="tooltip" title="Remove Library">
                                                        <i class="fa fa-fw fa-times"></i>
                                                    </button>
                                                </div>`;
                            return renderHtml;
                        }
                    },
                ],
            });
        }

        function load_category_datatable(data) {
            $("#table_category").DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                stateSave: true,
                columns: [{
                        data: 'id',
                        render: function(data, type, row) {
                            return `<span class="font-size-sm font-w600"> #${row.id} </span>`;
                        }
                    },
                    {
                        data: 'category_name',
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<a class="font-size-sm font-w600"> ${row.category_name.toUpperCase()} </a>`;
                        }
                    },
                    {
                        data: 'category_reference',
                        className: ' font-size-sm text-center',
                        render: function(data, type, row) {
                            return `<span class="font-size-sm"> ${row.category_reference.toUpperCase()} </span>`;
                        }
                    },
                    {
                        data: null,
                        className: 'text-right',
                        sortable: false,
                        render: function(type, data, row) {
                            var renderHtml = ` <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-alt-primary edit_cat" data-id="${row.id}" data-toggle="tooltip" title="Edit Category">
                                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-alt-primary delete_cat" data-id="${row.id}" data-toggle="tooltip" title="Remove Category">
                                                        <i class="fa fa-fw fa-times"></i>
                                                    </button>
                                                </div>`;
                            return renderHtml;
                        }
                    },
                ],
            });
        }

        function load_genre_datatable(data) {
            $("#table_genre").DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                stateSave: true,
                columns: [{
                        data: 'id',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-w600 font-size-sm">#${row.id} </span>`;
                            return renderHtml;
                        }
                    },

                    {
                        data: 'genre_name',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<a class="font-w600 font-size-sm"> ${row.genre_name.toUpperCase()} </a>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: null,
                        className: 'text-right',
                        sortable: false,
                        render: function(type, data, row) {
                            var renderHtml = ` <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-alt-primary edit_genre" data-id="${row.id}" data-toggle="tooltip" title="Edit Genre">
                                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-alt-primary delete_genre" data-id="${row.id}" data-toggle="tooltip" title="Remove Genre">
                                                        <i class="fa fa-fw fa-times"></i>
                                                    </button>
                                                </div>`;
                            return renderHtml;
                        }
                    },
                ],

            });
        }

        function load_borrower_datatable(data) {
            console.log(data)
            $("#table_borrower").DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                stateSave: true,
                columns: [{
                        data: 'cardid',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-w600 font-size-sm"> ${row.cardid} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'max_circulation_name',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<a class="font-w600 font-size-sm"> ${row.max_circulation_name} </a>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'class',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-size-sm"> ${row.class ?? 'Not Specified'} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'email',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<em class="font-size-sm"> ${row.email ?? 'Not Specified'} </em>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'phone',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-size-sm"> ${row.phone ?? 'Not Specified'} </span>`;
                            return renderHtml;
                        }
                    },

                ],

            });
        }

        function load_users_datatable(data) {
            $("#table_users").DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                stateSave: true,
                columns: [{
                        data: null,
                        sortable: false,
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                // `<span class="font-w600 font-size-sm"> ${row.id} </span>`;
                                `<img class="img-avatar img-avatar-thumb" src="{{ asset('media/avatars/avatar13.jpg') }}" alt="profile" style="height:40px; width:40px;">`
                            return renderHtml;
                        }
                    },
                    {
                        data: 'name',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<div class="font-w600 font-size-sm ${row.isreset ? 'text-danger' : ''} ">${row.name.toUpperCase()}</div>`;
                            return renderHtml;
                        }
                    },

                    {
                        data: 'email',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<div> <em class="font-size-sm"> ${row.email} </em> </div>
                                <a href="#" class="${row.isreset ? 'text-danger' : ''} font-size-sm font-w600 ${row.isdefault ? 'text-gray' : 'reset_password' } " data-id="${row.id}" > ${ row.isdefault? 'Default Password': 'Reset Password'} </a>`;
                            return renderHtml;
                        }
                    },

                    {
                        data: 'usertype',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<a class="font-size-sm font-w600"> ${row.usertype} </a>`;
                            return renderHtml;
                        }
                    },

                    {
                        data: null,
                        className: 'text-right',
                        orderable: false,
                        render: function(type, data, row) {
                            var renderHtml = ` <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-alt-primary edit_user" data-id="${row.id}" data-toggle="tooltip" title="Edit User">
                                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-alt-primary delete_user" data-id="${row.id}" data-toggle="tooltip" title="Remove User">
                                                        <i class="fa fa-fw fa-times"></i>
                                                    </button>
                                                </div>`;
                            return renderHtml;
                        }
                    },

                ],

            });
        }

        function load_usertype_datatable(data) {
            $("#table_usertype").DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                stateSave: true,
                columns: [{
                        data: 'id',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<span class="font-size-sm font-w600">#${row.id}</span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: 'usertype',
                        className: 'text-center',
                        render: function(type, data, row) {
                            var renderHtml =
                                `<a class="font-size-sm font-w600"> ${row.usertype} </a>`;
                            return renderHtml;
                        }
                    },

                    {
                        data: null,
                        className: 'text-right',
                        orderable: false,
                        render: function(type, data, row) {
                            var renderHtml = ` <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-alt-primary edit_type" data-id="${row.id}" data-toggle="tooltip" title="Edit">
                                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-alt-primary delete_type" data-id="${row.id}" data-toggle="tooltip" title="Remove">
                                                        <i class="fa fa-fw fa-times"></i>
                                                    </button>
                                                </div>`;
                            return renderHtml;
                        }
                    },

                ],

            });
        }
    </script>
@endsection
