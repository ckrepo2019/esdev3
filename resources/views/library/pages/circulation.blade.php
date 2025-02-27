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

    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/dropzone/dist/min/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/flatpickr/flatpickr.min.css') }}">

    <style>
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
    <div class="content">

        {{-- MODAL ADD CIRCULATION --}}
        <div class="modal fade" id="modal-block-popin" tabindex="-1" role="dialog" aria-labelledby="modal-block-popin"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-popin modal-xl" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title modal-title">New Circulation</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="block-content font-size-sm">
                            <h5 class="">Member's Information <i class="fa fa-info-circle text-primary"></i> </h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="mb-1">Search Name <span class="text-danger">*</span> <i
                                                class="fa fa-search ml-1"></i></label>
                                        <select class="form-control font-size-sm" id="select-borrowers"
                                            style="width: 100%;">
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Borrower is required</strong>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" id="borrower_utype" hidden>
                                        <label class="mb-1">Card ID #.</label>
                                        <input type="text" class="form-control font-size-sm form-control-alt"
                                            id="borrower_cardno" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1">Borrower's Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control font-size-sm form-control-alt"
                                            id="borrower_name" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1">Class/Position</label>
                                        <input type="text" class="form-control font-size-sm form-control-alt"
                                            id="borrower_class" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="mb-1">Date Borrowed <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="js-flatpickr font-size-sm form-control form-control-alt"
                                            id="circulation_date_borrowed" name="circulation_date_borrowed"
                                            placeholder="Y-m-d" data-date-format="Y-m-d">
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Date borrowed is required</strong>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1">Due Date <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="js-flatpickr font-size-sm form-control form-control-alt"
                                            id="circulation_due_date" name="circulation_due_date" placeholder="Y-m-d"
                                            data-date-format="Y-m-d">
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Due Date is required</strong>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-1">Penalty After Due Date <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control font-size-sm form-control-alt"
                                            id="circulation_penalty" placeholder="0.00">
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Penalty is required</strong>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-1">Circulation Status <span class="text-danger">*</span></label>
                                        <select class="form-control font-size-sm form-control-sm" id="select-status-form"
                                            style="width: 100%;">
                                            @foreach (DB::table('library_status')->where('status_deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->status_name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Status is required</strong>
                                        </span>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mark_returned">
                                        <div
                                            class="custom-control custom-checkbox custom-checkbox-square custom-control-danger">
                                            <input type="checkbox" class="custom-control-input" id="checkbox_returned"
                                                name="checkbox_returned">
                                            <label class="custom-control-label" for="checkbox_returned">Mark as
                                                <strong>RETURNED</strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group" id="date_returned_wrapper" hidden>
                                        <label class="mb-1">Date Returned</label>
                                        <input type="text" class="form-control font-size-sm form-control-alt"
                                            id="date_returned" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1">Email Ad</label>
                                        <input type="text" class="form-control form-control-alt font-size-sm"
                                            id="borrower_email" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1">Contact No.</label>
                                        <input type="text" class="form-control form-control-alt font-size-sm"
                                            id="borrower_phone" readonly>
                                    </div>

                                    {{-- <button class="btn btn-info font-size-sm view_borrower mb-3">
                                        See Borrower Books
                                    </button> --}}

                                    <div class="form-group">
                                        <div
                                            class="custom-control custom-checkbox custom-checkbox-square custom-control-info mb-1">
                                            <input type="checkbox" class="custom-control-input" id="checkbox_notif"
                                                name="checkbox_notif">
                                            <label class="custom-control-label" for="checkbox_notif">Email
                                                Notification on Due Date
                                            </label>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-warning view_borrower px-">
                                            <i class="si si-info mr-1"></i> See Borrower Books
                                        </button>
                                    </div>

                                    <div class="form-group mt-2 requested_book_wrapper" hidden>
                                        <label class="mb-1 text-warning">Requested Book <i
                                                class="si si-pin ml-2 text-dark"></i> </label>
                                        <select class="form-control font-size-sm form-control-sm"
                                            id="select_requested_book" style="width: 100%;">
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong> This book is out stock </strong>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <h5 class="">Books Information <i class="fa fa-info-circle text-primary"></i> </h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="mb-1">Search Book <i class="fa fa-search ml-1"></i></label>
                                        <select class="form-control font-size-sm" id="select-books" style="width: 100%;">
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Book is required</strong>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1">Title <span class="text-danger">*</span> </label>
                                        <input type="text" class="form-control form-control-alt font-size-sm"
                                            id="title" readonly>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Title is required</strong>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-1">Author</label>
                                        <input type="text" class="form-control form-control-alt font-size-sm"
                                            id="author" readonly>
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label class="mb-1">Category</label>
                                        <select class="form-control select2 font-size-sm" id="select-category"
                                            style="width: 100%;">
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1">Call Number</label>
                                        <input type="text" class="form-control form-control-alt font-size-sm "
                                            id="callnumber" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1">Publication</label>
                                        <input type="text" class="form-control form-control-alt font-size-sm"
                                            id="publisher" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="mb-1">ISBN</label>
                                        <input type="text" class="form-control form-control-alt font-size-sm"
                                            id="isbn" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-1">Copyright Year</label>
                                        <input type="text" class="form-control form-control-alt font-size-sm"
                                            id="copyright" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1">Genre</label>
                                        <select class="form-control font-size-sm" id="select-genre" style="width: 100%;">
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="block-content block-content-full text-right border-top">
                            <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">
                                Cancel
                            </button>
                            <button type="button" class="btn btn-primary save_circulation">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- END MODAL ADD CIRCULATION --}}

        <div class="block block-rounded">
            <div class="block-header bg-primary">
                <h3 class="block-title font-w700" style="font-size: 16px;"> <i
                        class="fa fa-layer-group mr-1"></i>CIRCULATIONS</h3>
                <div class="d-flex align-items-center my-1">
                    {{-- <div>
                        <select class="form-control" id="select-status">
                            @foreach (DB::table('library_status')->where('status_deleted', 0)->get() as $item)
                                <option value="{{ $item->id }}">{{ $item->status_name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="dropdown">
                        <button type="button" class="btn btn-alt-primary dropdown-toggle" id="dropdown-align-primary"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        </button>
                        <div class="dropdown-menu dropdown-menu-right font-size-sm"
                            aria-labelledby="dropdown-align-primary">
                            @foreach (DB::table('library_status')->where('status_deleted', 0)->get() as $item)
                                {{-- <option value="{{ $item->id }}">{{ $item->status_name }}</option> --}}
                                <a class="dropdown-item" href="#"
                                    onclick="onChangeStatus('{{ $item->id }}', '{{ $item->status_name }}' )">{{ $item->status_name }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn bg-dark new_circulation text-light ml-2" data-toggle="modal"
                            data-target="#modal-block-popin" style="white-space: nowrap;">
                            <i class="fa fa-fw fa-plus mr-1"></i>ADD CIRCULATION
                        </button>
                    </div>
                </div>
            </div>

            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-borderless table-vcenter js-dataTable-full"
                        id="DataTables_Table_1" style="width: 100%;">
                        <thead class="thead-dark">
                            <tr>
                                <th width="5%">ID</th>
                                <th>Book Title</th>
                                <th>Member's Name</th>
                                <th>Date Borrowed</th>
                                <th>Due Date</th>
                                <th>Date Returned</th>
                                <th>Penalty</th>
                                <th>Status</th>
                                <th width="5%">Action</th>
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

    <script src="{{ asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery.maskedinput/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ asset('js/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('js/plugins/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script>
        jQuery(function() {
            One.helpers(['flatpickr', 'datepicker', 'colorpicker', 'maxlength', 'select2', 'masked-inputs',
                'rangeslider'
            ]);
        });
    </script>

    <script>
        var jsonData = {!! json_encode($jsonData) !!};
        var action = {!! json_encode($action) !!};
        var str_status = {!! json_encode($text) !!};
        var circulation_members_id = $('#select-borrowers');
        var circulation_book_id = $('#select-books');
        var circulation_penalty = $('#circulation_penalty');
        var circulation_due_date = $('#circulation_due_date');
        var circulation_date_borrowed = $('#circulation_date_borrowed');
        var circulation_status = $('#select-status-form');
        var purpose = '';
        var currentId = 0;
        var current_book_id = 0;
        var current_member_id = 0;

        $(document).ready(function() {
            load_circulation_datatable(jsonData);
            $('#select-genre').prop('disabled', true);
            $('#select-category').prop('disabled', true);
            load_borrowers();
            dropdowns();

            $('.form-control').on('focus', function() {
                $(this).removeClass('is-invalid');
            });

            // $('#select-borrowers').on('select2:open', function() {
            //     // Remove the 'is-invalid' class when the Select2 dropdown is opened
            //     $(this).removeClass('is-invalid');
            // });

            // Clear validation errors when the modal is shown
            $('#modal-block-popin').on('show.bs.modal', function() {
                clearValidationErrors();
            });

            // Clear validation errors when the modal is hidden
            $('#modal-block-popin').on('hidden.bs.modal', function() {
                clearValidationErrors();
            });
            // $('#select-status').select2();
            // $('#select-status').val(action).change();
            $('#dropdown-align-primary').text(str_status);
            $('#select-status-form').select2({
                placeholder: '...'
            });

            // $(document).on('change', '#select-status', function() {
            //     // Get the selected text
            //     var selectedText = $(this).find('option:selected').text().toLowerCase();
            //     var svalue = $(this).val();

            //     // Construct the new URL based on the selected text and the fixed action parameter
            //     var newUrl = '/admin/circulation/' + selectedText + '?action=' + svalue;
            //     console.log(newUrl)

            //     // Redirect to the new URL
            //     window.location.href = newUrl;
            // });

            $('.view_borrower').on('click', function() {
                var select_borrower = $('#select-borrowers').val()
                var sutype = $('#borrower_utype').val();
                if (select_borrower) {
                    console.log(select_borrower);
                    window.location.href = `/view/borrowerdetail?id=${select_borrower}&utype=${sutype}`;
                } else {
                    notify('error', 'No Borrower Selected!')
                }
            });

            $(document).on('click', '.delete_item', function() {
                var id = $(this).attr('data-id');
                Swal.fire({
                    icon: 'warning',
                    title: 'You want to delete this Circulation?',
                    text: `You can't undo this process.`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            url: '{{ route('delete.circulation') }}',
                            type: "GET",
                            data: {
                                id: id,
                            },
                            success: function(data) {
                                notify(data[0].statusCode, data[0].message);
                                circulations($('#select-status').val())
                            }
                        });
                    }

                })
            });

            $(document).on('click', '.new_circulation', function() {
                // load_books('available')

                purpose = 'new';
                circulation_members_id.val('').change();
                circulation_members_id.prop('disabled', false);
                $('#select-books').val('').change();
                $('#select-books').prop('disabled', false);
                $('.mark_returned').hide();

                $('.modal-title').text('New Circulation');
                // Clear input fields
                $('#select-borrowers').val('').trigger('change'); // Assuming it's a select2 element
                $('#borrower_cardno').val('');
                $('#borrower_name').val('');
                $('#borrower_class').val('');
                $('#circulation_date_borrowed').val('');
                $('#circulation_due_date').val('');
                $('#circulation_penalty').val('');
                $('#select-status-form').val('').trigger('change'); // Assuming it's a select2 element

                // Uncheck checkbox and hide date_returned_wrapper
                $('#checkbox_returned').prop('checked', false);
                $('#date_returned_wrapper').hide();

                // Clear borrower information
                $('#borrower_email').val('');
                $('#borrower_phone').val('');
                $('#checkbox_notif').prop('checked', false);

                // Clear book information
                $('#select-books').val('').change();
                $('#author').val('');
                $('#select-category').val('').trigger('change'); // Assuming it's a select2 element
                $('#callnumber').val('');
                $('#publisher').val('');
                $('#isbn').val('');
                $('#title').val('');
                $('#copyright').val('');
                $('#select-genre').val('').trigger('change'); // Assuming it's a select2 element
            });

            $(document).on('click', '.edit_item', function() {
                purpose = 'update'
                var str_utype = $(this).attr('data-utype');
                currentId = $(this).attr('data-id');
                $('.mark_returned').show();
                $('#select-books').prop('disabled', true)
                $('.modal-title').text('Edit Circulation');

                circulation_members_id.prop('disabled', false);
                circulation_penalty.prop('readonly', false);
                circulation_due_date.prop("readonly", false);
                circulation_date_borrowed.prop("readonly", false);
                circulation_status.prop("disabled", false);

                console.log(str_utype)

                $.ajax({
                    type: 'GET',
                    data: {
                        utype: str_utype,
                        id: currentId,
                    },
                    url: '{{ route('get.circulation') }}',
                    success: function(data) {
                        console.log(data);
                        current_book_id = data.library_book_id;
                        current_member_id = data.circulation_members_id;
                        circulation_status.val(data.circulation_status).change();
                        circulation_members_id.val('').change();
                        circulation_members_id.prop('disabled', true);
                        circulation_penalty.val(data.circulation_penalty);
                        circulation_due_date.val(data.circulation_due_date);
                        circulation_date_borrowed.val(data.circulation_date_borrowed);

                        $('#borrower_name').val(data.circulation_name);

                        $('#borrower_class').val(data.class);
                        $('#borrower_email').val(data.email);
                        $('#borrower_phone').val(data.contactno);
                        $('#borrower_utype').val(data.circulation_utype);

                        $('#title').val(data.book_title);
                        $('#author').val(data.book_author);
                        $('#select-category').val(data.book_category).change();
                        $('#callnumber').val(data.book_callnum);
                        $('#isbn').val(data.book_isbn);
                        $('#publisher').val(data.book_publisher);
                        $('#copyright').val(data.book_copyright);
                        $('#select-genre').val(data.book_genre).change();


                        if (data.circulation_date_returned) {
                            $('#checkbox_returned').prop('checked', true)
                            $('#checkbox_returned').prop('disabled', true)
                            $('#date_returned_wrapper').prop('hidden', false);
                            $('#date_returned').val(data.circulation_date_returned);
                        } else {
                            $('#checkbox_returned').prop('checked', false)
                            $('#checkbox_returned').prop('disabled', false)
                            $('#date_returned_wrapper').prop('hidden', true);
                            $('#date_returned').val('');
                        }

                        $('#modal-block-popin').modal();
                    }
                });
            });

            $(document).on('click', '.save_circulation', function() {
                if (purpose === 'new') {
                    if (!circulation_members_id.val()) {
                        circulation_members_id.addClass('is-invalid')
                        notify('error', 'No borrower selected!');
                        return
                    } else {
                        circulation_members_id.removeClass('is-invalid')
                    }

                    if (!$('#title').val()) {
                        $('#title').addClass('is-invalid')
                        notify('error', 'No book selected!');
                        return
                    } else {
                        $('#title').removeClass('is-invalid')
                    }

                    if (!circulation_date_borrowed.val()) {
                        circulation_date_borrowed.addClass('is-invalid')
                        notify('error', 'Date borrowed is empty!');
                        return
                    } else {
                        circulation_date_borrowed.removeClass('is-invalid')
                    }

                    if (!circulation_due_date.val()) {
                        circulation_due_date.addClass('is-invalid')
                        notify('error', 'Due date is empty!');
                        return
                    } else {
                        circulation_due_date.removeClass('is-invalid')
                    }

                    if (!circulation_penalty.val()) {
                        circulation_penalty.addClass('is-invalid')
                        notify('error', 'Penalty is empty!');
                        return
                    } else {
                        circulation_penalty.removeClass('is-invalid')
                    }

                    if (!circulation_status.val()) {
                        circulation_status.addClass('is-invalid')
                        notify('error', 'Select Status is empty!');
                        return
                    } else {
                        circulation_status.removeClass('is-invalid')
                    }

                }
                // Create a new Date object representing the current date
                var currentDate = new Date();
                // Get various components of the date
                var year = currentDate.getFullYear();
                var month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                var day = String(currentDate.getDate()).padStart(2, '0');

                // Format the date as 'YYYY-MM-DD'
                var formattedDate = year + '-' + month + '-' + day;

                console.log(formattedDate);

                var tempoID;
                if (circulation_book_id.val()) {
                    tempoID = circulation_book_id.val();
                } else {
                    tempoID = $('#select_requested_book').val()
                }

                $.ajax({
                    type: 'GET',
                    data: {
                        id: currentId,
                        circulation_utype: $('#borrower_utype').val(),
                        circulation_name: $('#borrower_name').val(),
                        circulation_members_id: purpose == 'new' ? circulation_members_id.val() :
                            current_member_id,
                        circulation_book_id: purpose === 'new' ? tempoID : current_book_id,
                        circulation_penalty: circulation_penalty.val(),
                        circulation_due_date: circulation_due_date.val(),
                        circulation_date_borrowed: circulation_date_borrowed.val(),
                        circulation_date_returned: $('#checkbox_returned').prop('checked') ?
                            formattedDate : null,
                        circulation_status: $('#checkbox_returned').prop('checked') && purpose ==
                            'update' ?
                            3 : circulation_status.val(),
                    },
                    url: purpose == 'new' ? '{{ route('store.circulation') }}' :
                        '{{ route('update.circulation') }}',
                    success: function(data) {
                        console.log(data);
                        notify(data[0].statusCode, data[0].message)
                        circulations($('#select-status').val())
                    }
                });
            });

            $('#modal-block-popin').on('show.bs.modal', function() {
                if (purpose == 'new') {
                    $('#select-borrowers').val(null).trigger('change');
                    $('#select-books').val(null).trigger('change');
                    $(this).find('input').val('');
                }
            });
        });

        function onChangeStatus(value, text) {
            console.log(value);
            $('#dropdown-align-primary').text(text);
            var newUrl = `/admin/circulation/${text.toLowerCase()}?action=${value}`;
            console.log(newUrl)

            // Redirect to the new URL
            window.location.href = newUrl;
        }

        function clearValidationErrors() {
            $('.form-control').removeClass('is-invalid');
        }

        function circulations(value) {
            $.ajax({
                type: 'GET',
                data: {
                    action: value,
                },
                url: '{{ route('circulations') }}',
                success: function(data) {
                    console.log(data);
                    load_circulation_datatable(data)
                }
            });
        }

        function dropdowns() {
            $.ajax({
                type: 'GET',
                url: '{{ route('dropdowns') }}',
                success: function(data) {
                    var genres = data.genres;
                    var categories = data.categories;
                    console.log(genres);

                    $('#select-genre').empty();
                    $('#select-category').empty();
                    // $('#select-genre').append('<option value="">Book Genre</option>');
                    // $('#select-category').append('<option value="">Book Category</option>');

                    $('#select-genre').select2({
                        data: genres,
                        search: true,
                        placeholder: "...",
                    })

                    $('#select-category').select2({
                        data: categories,
                        search: true,
                        placeholder: "...",
                    });

                    load_books('available');
                }
            });
        }

        function load_borrowers() {
            $.ajax({
                url: '{{ route('borrowers') }}',
                type: "GET",
                success: function(data) {
                    console.log(data);

                    // Initialize Select2
                    $('#select-borrowers').empty();
                    // $('#select-borrowers').append('<option value="">Search Person\'s Name</option>');
                    $('#select-borrowers').select2({
                        data: data,
                        search: true,
                        allowClear: true,
                        placeholder: '...',
                    });

                    // Add change event listener
                    $('#select-borrowers').on('change', function() {
                        var selectedValue = $(this).val();
                        console.log('Selected Value:', selectedValue);
                        if (selectedValue) {
                            var user = data[this.selectedIndex]
                            console.log(user)
                            // $('#borrower_cardno').val(data.lib.sid);
                            $('#borrower_name').val(user.text);
                            $('#borrower_class').val(user.class);
                            $('#borrower_email').val(user.email);
                            $('#borrower_phone').val(user.phone);
                            $('#borrower_utype').val(user.utype);

                            // get_borrowers_info(user);
                        } else {
                            clearBorrowerInfo();
                        }
                    });
                }
            });
        }

        function get_borrowers_info(id) {
            $.ajax({
                type: 'GET',
                data: {
                    id: id
                },
                url: '{{ route('get.circulation.borrower') }}',
                success: function(data) {
                    console.log(data);
                    if (data.lib) {
                        // Populate borrower-related form fields
                        $('#borrower_cardno').val(data.lib.sid);
                        $('#borrower_name').val(data.lib.borrower_name);
                        $('#borrower_class').val(data.lib.levelname);
                        $('#borrower_email').val(data.lib.sid);
                        $('#borrower_phone').val(data.lib.contactno);
                    } else {
                        // Clear borrower-related form fields when no data
                        clearBorrowerInfo();
                    }

                    if (data.request && purpose == 'new') {
                        // Handle the case when there is request data
                        $('.requested_book_wrapper').prop('hidden', false);
                        // $('#select_requested_book').empty();
                        $('#select_requested_book').append(
                            '<option value="" selected>Choose Requested Book</option>');
                        $('#select_requested_book').select2({
                            data: data.request,
                            allowClear: true,
                            placeholder: 'Select Pinned book'
                        });

                        $('#select_requested_book').on('change', function() {
                            var selectedValue = $(this).val();
                            if (selectedValue) {
                                get_books_info(selectedValue);
                            } else {
                                clearBookInfo();
                                $('#select-books').val('').change();
                            }
                        });
                    } else {
                        // Hide requested_book_wrapper when there is no request data
                        $('.requested_book_wrapper').prop('hidden', true);
                    }
                }
            });
        }

        function clearBorrowerInfo() {
            // Clear borrower-related form fields
            $('#borrower_cardno').val('');
            $('#borrower_name').val('');
            $('#borrower_class').val('');
            $('#borrower_email').val('');
            $('#borrower_phone').val('');
        }

        function get_books_info(id) {
            $.ajax({
                type: 'GET',
                data: {
                    id: id
                },
                url: '{{ route('get.book') }}',
                success: function(data) {
                    console.log(data)
                    if (data.status) {
                        $('#select_requested_book').addClass('is-invalid');
                        notify(data.status, data.message);
                        clearBookInfo();
                        $('#select-books').val('').change();
                        return;
                    }
                    // $('#select-books').val(id).change();
                    $('#copyright').val(data.book_copyright);
                    $('#isbn').val(data.book_isbn);
                    $('#callnumber').val(data.book_callnum);
                    $('#title').val(data.book_title);
                    $('#author').val(data.book_author);
                    $('#publisher').val(data.book_publisher);
                    $('#select-genre').val(data.book_genre).change();
                    $('#select-category').val(data.book_category).change();
                }
            })
        }

        function clearBookInfo() {
            $('#copyright').val('');
            $('#isbn').val('');
            $('#callnumber').val('');
            $('#title').val('');
            $('#author').val('');
            $('#publisher').val('');
            $('#select-genre').val('').change();
            $('#select-category').val('').change();
        }

        function load_books(str) {
            $.ajax({
                type: 'GET',
                data: {
                    action: str
                },
                url: '{{ route('books') }}',
                success: function(data) {
                    console.log(data);
                    $('#select-books').empty();
                    // $('#select-books').append('<option value="">Search Book title</option>');
                    $('#select-books').select2({
                        data: data,
                        search: true,
                        allowClear: true,
                        placeholder: '...',
                    });

                    // Add change event listener
                    $('#select-books').on('change', function() {
                        var selectedValue = $(this).val();

                        if (selectedValue) {
                            get_books_info(selectedValue);
                        } else {
                            // Clear the form fields when no book is selected
                            clearBookInfo();
                        }
                    });

                    $('#select-books, #select_requested_book, #select-borrowers, #select-status-form').on(
                        'select2:open',
                        function() {
                            // Remove the 'is-invalid' class when the Select2 dropdown is opened
                            $(this).removeClass('is-invalid');
                        });
                }
            });
        }

        function load_circulation_datatable(data) {
            console.log(data)
            var table = $('#DataTables_Table_1').DataTable({
                autowidth: false,
                destroy: true,
                responsive: true,
                stateSave: true,
                data: data,
                columns: [{
                        data: 'id',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm font-w600">#${row.id}</span>`
                        }
                    },
                    {
                        data: 'book_title',
                        render: function(type, data, row) {
                            // var capitalizeFirstLetter = function (string) {
                            //     return string.toLowerCase().replace(/\b\w/g, function (match) {
                            //         return match.toUpperCase();
                            //     });
                            // };
                            return `<span class="font-size-sm font-w600" >${ row.book_title}</span>`
                        }
                    },
                    {
                        data: 'circulation_name',
                        render: function(type, data, row) {
                            // var capitalizeFirstLetter = function (string) {
                            //     return string.toLowerCase().replace(/\b\w/g, function (match) {
                            //         return match.toUpperCase();
                            //     });
                            // };
                            return `<a class="font-size-sm font-w600" >${ row.circulation_name }</a>`
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
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_due_date}</span>`
                        }
                    },
                    {
                        data: "circulation_date_returned",
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.circulation_date_returned ?? ''}</span>`
                        }
                    },
                    {
                        data: "circulation_penalty",
                        className: 'text-center',
                        render: function(data, type, row) {
                            var penalty = parseFloat(row.circulation_penalty).toFixed(2);
                            var renderHtml =
                                `<span class="font-w600 font-size-sm text-success"> ₱${penalty} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: "status_name",
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<a class="font-size-sm font-w600" >${row.status_name}</a>`
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        className: 'text-right',
                        render: function(type, data, row) {
                            var renderHtml = `<div class="btn-group">
                                                
                                                <button type="button" class="btn btn-sm btn-alt-primary edit_item" data-utype="${row.circulation_utype}" data-id="${row.id}" data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-alt-primary delete_item" data-id="${row.id}" data-toggle="tooltip" title="Remove">
                                                    <i class="fa fa-fw fa-times"></i>
                                                </button>
                                            </div>`;
                            return renderHtml;
                        }
                    },
                    // <button type="button" class="btn btn-sm btn-alt-primary view_item" data-id="${row.id}" data-toggle="tooltip" title="View">
                    //                                 <i class="fa fa-fw far fa-eye"></i>
                    //                             </button>
                ],
            });
        }
    </script>
@endsection
