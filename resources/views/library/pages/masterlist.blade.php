@php
    $extends = 'library.layouts.backend';
@endphp

@extends($extends);

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

        #select-branch+.select2-container .select2-selection--multiple {
            background-color: #F0F1F2;
            /* Light gray background color */
            border: none;
            /* No border */
            height: 32px;
            /* Adjust the height as needed */
        }

        #select-branch+.select2-container .select2-selection--multiple .select2-selection__choice {
            background-color: #C8C9CA;
            /* Background color for selected items */
            border: 1px solid #C8C9CA;
            /* Border color for selected items */
            color: #000;
            /* Text color for selected items */
        }

        #select-branch+.select2-container .select2-selection--multiple .select2-selection__choice__remove {
            color: #000;
            /* Color for the 'x' remove icon on selected items */
        }
    </style>
@endsection

@section('content')
    <div class="content">

        <!-- MODAL VIEW BOOK -->
        <div class="modal fade" id="modal-block-popin-view" tabindex="-1" role="dialog"
            aria-labelledby="modal-block-popin-view" aria-hidden="true">
            <div class="modal-dialog modal-dialog-popin modal-xl" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title modal-title-view">Book details</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="block-content font-size-sm pb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="block">
                                        <div class="block-content">
                                            <img src="" alt="Cover Image" id="cover_img"
                                                style="width:100%; height: 280px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <strong>ISBN</strong>
                                    <p class="font-w600 text-primary" id="visbn"></p>
                                    <strong>Author</strong>
                                    <p class="text-muted" id="vauthor"></p>
                                    <strong>Publication</strong>
                                    <p class="text-muted" id="vpublisher"></p>
                                    <strong>Genre</strong>
                                    <p class="text-muted" id="vgenre"></p>
                                    <strong>Date Received</strong>
                                    <p class="text-muted" id="vdatereceived"></p>
                                </div>
                                <div class="col-md-3">
                                    <strong>Title</strong>
                                    <p class="text-muted" id="vtitle"></p>
                                    <strong>Edition</strong>
                                    <p class="text-muted" id="vedition"></p>
                                    <strong>Copyright</strong>
                                    <p class="text-muted" id="vcopyright"></p>
                                    <strong>Category</strong>
                                    <p class="text-muted" id="vcategory"></p>
                                    <strong>Call Number</strong>
                                    <p class="text-muted" id="vcallnumber"></p>
                                </div>
                                <div class="col-md-3">
                                    <strong>Price</strong>
                                    <p class="text-muted" id="vprice"></p>
                                    <strong>Quantity</strong>
                                    <p class="text-muted" id="vquantity"></p>
                                    <strong>Available</strong>
                                    <p class="text-muted" id="vavailable"></p>
                                    <strong>Description</strong>
                                    <p class="text-muted pr-1" id="vdescription"></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MODAL VIEW BOOK -->

        <!-- MODAL ADD BOOK -->
        <div class="modal fade" id="modal-block-popin" tabindex="-1" role="dialog" aria-labelledby="modal-block-popin"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-popin modal-xl" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title modal-title">New Book</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="block-content font-size-sm p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="mb-1">Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-alt font-size-sm"
                                            id="title" onkeyup="this.value = this.value.toUpperCase();">
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Title is required</strong>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-1">Author <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-alt font-size-sm"
                                            id="author" onkeyup="this.value = this.value.toUpperCase();">
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Author is required</strong>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-1">Select Category <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-alt select2 font-size-sm"
                                            id="select-category" style="width: 100%;">
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Category is required</strong>
                                        </span>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-1">Publishing <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-alt font-size-sm"
                                                    id="publisher" onkeyup="this.value = this.value.toUpperCase();">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Publishing is required</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-1">Edition</label>
                                                <input type="text" class="form-control form-control-alt font-size-sm"
                                                    id="edition" onkeyup="this.value = this.value.toUpperCase();">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Edition is required</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-1">Select Genre <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-alt font-size-sm" id="select-genre"
                                            style="width: 100%;">
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Genre is required</strong>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-1">Date Received</label>
                                        <input type="text"
                                            class="js-flatpickr font-size-sm form-control form-control-alt"
                                            id="datereceived" name="datereceived" placeholder="Y-m-d"
                                            data-date-format="Y-m-d">
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Date received is required</strong>
                                        </span>
                                    </div>

                                </div>
                                <div class="col-md-6">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-1">Quantity <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-alt font-size-sm"
                                                    id="quantity" placeholder="0">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Quantity is required</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-1">Available <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-alt font-size-sm"
                                                    id="available" placeholder="0">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Available is required</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-1">Price <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-alt font-size-sm"
                                                    id="price" placeholder="₱ 0.00">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Price is required</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-1">Copyright Year</label>
                                                <input type="text" class="form-control form-control-alt font-size-sm"
                                                    id="copyright" onkeyup="this.value = this.value.toUpperCase();">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Copyright is required</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-1">ISBN <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-alt font-size-sm"
                                            id="isbn" onkeyup="this.value = this.value.toUpperCase();">
                                        <span class="invalid-feedback" role="alert">
                                            <strong>ISBN is required</strong>
                                        </span>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group">
                                                <label class="mb-1">C.N. A1</label>
                                                <input type="text" class="form-control form-control-alt font-size-sm "
                                                    id="cln1" onkeyup="this.value = this.value.toUpperCase();"
                                                    placeholder="e.g.LB">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Callnumber is required</strong>
                                                </span>
                                            </div>

                                            <div class="form-group">
                                                <label class="mb-1">C.N. A2</label>
                                                <input type="text" class="form-control form-control-alt font-size-sm"
                                                    id="cln2" onkeyup="this.value = this.value.toUpperCase();"
                                                    placeholder="e.g.2395">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Callnumber is required</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group">
                                                <label class="mb-1">C.N. Author</label>
                                                <input type="text" class="form-control form-control-alt font-size-sm"
                                                    id="cln3" onkeyup="this.value = this.value.toUpperCase();"
                                                    placeholder="e.g.C65">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Callnumber is required</strong>
                                                </span>
                                            </div>

                                            <div class="form-group">
                                                <label class="mb-1">C.N. Publish</label>
                                                <input type="text" class="form-control form-control-alt font-size-sm"
                                                    id="cln4" onkeyup="this.value = this.value.toUpperCase();"
                                                    placeholder="eg.1991">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Callnumber is required</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-1">Select Branch <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-alt font-size-sm" id="select-branch"
                                            style="width: 100%;" multiple>
                                            <!-- Add your dynamic options here -->
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Branch is required</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Upload Cover Image</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input js-custom-file-input-enabled"
                                                data-toggle="custom-file-input" id="image" name="image">
                                            <label class="custom-file-label" for="image" class="mb-1">Choose
                                                file</label>
                                        </div>
                                        <div class="mt-2">
                                            <img height="110px" width="100px" alt="book image" id="book_cover" hidden
                                                style="object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control form-control-alt font-size-sm" id="description" rows="8"
                                            placeholder="Short description.."></textarea>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Description is required</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="block-content block-content-full text-right border-top">
                            <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">
                                Cancel
                            </button>
                            <button type="button" class="btn btn-primary save_book">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MODAL ADD BOOK -->

        <!-- TABLE BOOK -->
        <div class="block block-rounded">
            <div class="block-header bg-primary">
                <h3 class="block-title font-w700" style="font-size: 16px;"> <i class="fa fa-book mr-1"></i> BOOKS
                    INFORMATION</h3>
                <div class="d-flex align-items-center my-1">
                    <select class="form-control font-size-sm" id="select-library">
                        <option value="">Select Library</option>
                        @foreach (DB::table('libraries')->where('deleted', 0)->get() as $item)
                            <option value="{{ $item->id }}">{{ strtoupper($item->library_name) }}</option>
                        @endforeach
                    </select>
                    <div>
                        <button type="button" class="btn bg-dark text-light newbook ml-2" data-toggle="modal"
                            data-target="#modal-block-popin" style="white-space: nowrap;">
                            <i class="fa fa-fw fa-plus mr-1"></i>ADD BOOK
                        </button>
                    </div>
                </div>
            </div>

            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-vcenter table-borderless js-dataTable-full"
                        id="DataTables_Table_1" style="width: 100%;">
                        <thead class="thead-dark">
                            <tr>
                                <th width="10%"></th>
                                <th width="20%">Book Title</th>
                                <th hidden>Book Author</th>
                                <th hidden>Book Publisher</th>
                                <th width="12%">Genre</th>
                                <th width="12%">Category</th>
                                <th width="12%">Quantity</th>
                                <th width="12%">Available</th>
                                <th width="12%">Price</th>
                                <th width="10%">Action</th>
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
        var purpose = '';
        var currentId;
        var isbn = $('#isbn')
        var description = $('#description')
        var copyright = $('#copyright')
        var edition = $('#edition')
        var datereceived = $('#datereceived')
        var title = $('#title')
        var author = $('#author')
        var publisher = $('#publisher')
        var genre = $('#select-genre')
        var category = $('#select-category')
        var branch = $('#select-branch')
        var price = $('#price')
        var quantity = $('#quantity')
        var available = $('#available')
        $(document).ready(function() {
            books('');
            dropdowns();

            $('#select-library').select2()
            $('#select-library').on('change', function() {
                books($(this).val());
            })

            // Clear validation errors when the modal is shown
            $('#modal-block-popin').on('show.bs.modal', function() {
                clearValidationErrors();
                resetBookCover();
            });

            // Clear validation errors when the modal is hidden
            $('#modal-block-popin').on('hidden.bs.modal', function() {
                clearValidationErrors();
                resetBookCover();
            });

            $('.form-control').on('focus', function() {
                $(this).removeClass('is-invalid');
            });

            $('#select-genre, #select-category, #select-branch').on('select2:open', function() {
                $(this).removeClass('is-invalid');
            });

            $('#image').on('change', function() {
                // Get the file name
                var fileName = $(this).val().split('\\').pop();
                // Update the label text
                $('.custom-file-label').text(fileName);

                var file = this.files[0];

                // Check if a file is selected
                if (file) {
                    // Create a FileReader object to read the file
                    var reader = new FileReader();

                    // Set the callback function when the file is loaded
                    reader.onload = function(e) {
                        // Update the src attribute of the img element with the data URL
                        $('#book_cover').attr('src', e.target.result).show();
                        $('#book_cover').removeAttr('hidden', false);
                    };

                    // Read the file as a data URL
                    reader.readAsDataURL(file);
                } else {
                    // Hide the image if no file is chosen
                    $('#book_cover').attr('src', '').hide();
                }
            });

            price.on('input', function(event) {
                // Get the input value
                let inputValue = $(this).val();

                // Remove non-numeric characters, except for the first period (.)
                let numericValue = inputValue.replace(/[^\d.]/g, function(match, offset, string) {
                    return match === '.' && offset > 0 && string.indexOf('.') === offset ? '' :
                        match;
                });

                // Validate that the result is a valid number
                let isValidNumber = !isNaN(parseFloat(numericValue)) && isFinite(numericValue);

                // Set the sanitized value back to the input only if it's a valid number
                if (isValidNumber) {
                    $(this).val(numericValue);
                } else {
                    // Handle invalid input, e.g., clear the input or display an error message
                    // For simplicity, in this example, we are setting the value to an empty string
                    $(this).val('');
                }
            });

            $(document).on('click', '.newbook', function() {
                clear_inputs();
                purpose = 'add';
                $('#book_cover').prop('hidden', true);
                $('.modal-title').text('Create New Book');
                $('#modal-block-popin').modal();
            });

            $(document).on('click', '.edit_book', function() {
                var id = $(this).attr('data-id');
                purpose = 'edit';
                currentId = id;
                $('.modal-title').text('Edit Book Detail');

                $.ajax({
                    type: 'GET',
                    data: {
                        id: id
                    },
                    url: '{{ route('get.masterlist.book') }}',
                    success: function(data) {
                        console.log(data)

                        var callnumArray = (data.book_callnum || '').split(',');
                        callnumArray = callnumArray.slice(0, 4); // Take the first 4 elements

                        // Extract values into separate variables
                        var [cln1, cln2, cln3, cln4] = callnumArray;

                        // Assign values to form fields
                        $('#cln1').val(cln1 || '');
                        $('#cln2').val(cln2 || '');
                        $('#cln3').val(cln3 || '');
                        $('#cln4').val(cln4 || '');

                        if (data.book_img) {
                            $('#book_cover').prop('hidden', false);
                            $('#book_cover').attr('src', data.book_img);
                        }


                        $('#image').val('');
                        $('#description').val(data.book_description);
                        $('#datereceived').val(data.book_received);
                        $('#copyright').val(data.book_copyright);
                        $('#isbn').val(data.book_isbn);
                        $('#edition').val(data.book_edition);
                        $('#quantity').val(data.book_qty);
                        $('#price').val(parseFloat(data.book_price).toFixed(
                            2));
                        $('#title').val(data.book_title);
                        $('#author').val(data.book_author);
                        $('#available').val(data.book_available);
                        $('#publisher').val(data.book_publisher);
                        $('#select-genre').val(data.book_genre).change();
                        $('#select-category').val(data.book_category).change();
                        $('#select-branch').val(data.branch_index).change();

                        $('#modal-block-popin').modal();
                    }
                });
            })

            $(document).on('click', '.view_book', function() {
                var id = $(this).attr('data-id');
                purpose = 'view';
                $.ajax({
                    type: 'GET',
                    data: {
                        id: id
                    },
                    url: '{{ route('get.masterlist.book') }}',
                    success: function(data) {
                        console.log(data)
                        $('.modal-title-view').text(data.book_title);
                        $('#cover_img').attr('src', data.book_img);
                        $('#visbn').text(data.book_isbn ?? '--');
                        $('#vtitle').text(data.book_title ?? '--');
                        $('#vauthor').text(data.book_author ?? '--');
                        $('#vedition').text(data.book_edition ?? '--');
                        $('#vavailable').text(data.book_available ?? '--');
                        $('#vpublisher').text(data.book_publisher ?? '--');
                        $('#vgenre').text(data.genre_name ?? '--');
                        $('#vcategory').text(data.category_name ?? '--');
                        $('#vbranch').text(data.library_branch ?? '--');
                        $('#vdescription').text(data.book_description_short ?? '--');
                        $('#vdatereceived').text(data.book_received ?? '--');
                        $('#vcopyright').text(data.book_copyright ?? '--');
                        $('#vcallnumber').text(data.book_callnum ?? '--');
                        $('#vquantity').text(data.book_qty ?? '--');
                        $('#vprice').text(parseFloat(data.book_price ?? '--').toFixed(
                            2));

                        $('#modal-block-popin-view').modal();
                    }
                });
            })

            $(document).on('click', '.save_book', function() {
                if (purpose === 'add') {
                    storeBook();
                } else if (purpose === 'edit') {
                    updateBook();
                }
            });

            $(document).on('click', '.delete_book', function() {
                var id = $(this).attr('data-id');
                delete_book(id);
            });
        });
        // Function to hide the book cover image and reset the file input
        function resetBookCover() {
            // $('#book_cover').attr('src', '').hide();
            $('#image').val('');
            $('.custom-file-label').text('Choose file');
        }

        function clearValidationErrors() {
            $('.form-control').removeClass('is-invalid');
        }

        function clear_inputs() {
            isbn.val('');
            title.val('');
            author.val('');
            datereceived.val('');
            edition.val('');
            copyright.val('');
            $('#cln1').val('');
            $('#cln2').val('');
            $('#cln3').val('');
            $('#cln4').val('');
            publisher.val('');
            genre.val('').change();
            category.val('').change();
            branch.val('').change();
            price.val('');
            quantity.val('');
            available.val('');
            description.val('');
        }

        function updateBook() {
            if (!validateInput(title, 'Title') || !validateInput(isbn, 'ISBN') ||
                !validateInput(author, 'Author') || !validateInput(publisher, 'Publisher') ||
                !validateInput(genre, 'Genre') || !validateInput(category, 'Category') ||
                !validateInput(branch, 'Branch') || !validateInput(price, 'Price') ||
                !validateInput(quantity, 'Quantity') || !validateInput(available,
                    'Availability')) {
                return;
            }
            var callnum = $('#cln1').val() + ',' + $('#cln2').val() + ',' +
                $('#cln3').val() + ',' + $('#cln4').val();
            // Create FormData object
            var formData = new FormData();
            formData.append('id', currentId);
            formData.append('isbn', isbn.val().trim());
            formData.append('description', description.val().trim());
            formData.append('copyright', copyright.val().trim());
            formData.append('edition', edition.val().trim());
            formData.append('callnumber', callnum);
            formData.append('datereceived', datereceived.val().trim());
            formData.append('title', title.val().trim());
            formData.append('author', author.val().trim());
            formData.append('publisher', publisher.val().trim());
            formData.append('genre', genre.val().trim());
            formData.append('category', category.val().trim());
            formData.append('branch', branch.val().join(','));
            formData.append('price', price.val().trim());
            formData.append('quantity', quantity.val().trim());
            formData.append('available', available.val().trim());

            // Append the image file if selected
            var imageInput = $('#image')[0].files[0];
            if (imageInput) {
                formData.append('image', imageInput);
            }

            // Ajax request
            $.ajax({
                type: 'POST', // Assuming your route uses POST method
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('update.book') }}',
                success: function(data) {
                    console.log(data);
                    notify(data[0].statusCode, data[0].message);
                    books();
                }
            });
        }

        function storeBook() {

            if (
                !validateInput(title, 'Title') ||
                !validateInput(author, 'Author') ||
                !validateInput(category, 'Category') ||
                !validateInput(publisher, 'Publisher') ||
                !validateInput(genre, 'Genre') ||
                !validateInput(quantity, 'Quantity') ||
                !validateInput(available, 'Availability') ||
                !validateInput(price, 'Price') ||
                !validateInput(isbn, 'ISBN') ||
                !validateInput(branch, 'Branch')
            ) {
                return;
            }

            var callnum = $('#cln1').val() + ',' + $('#cln2').val() + ',' +
                $('#cln3').val() + ',' + $('#cln4').val();

            // Create FormData object
            var formData = new FormData();
            formData.append('isbn', isbn.val().trim());
            formData.append('description', description.val().trim());
            formData.append('copyright', copyright.val().trim());
            formData.append('edition', edition.val().trim());
            formData.append('callnumber', callnum);
            formData.append('datereceived', datereceived.val().trim());
            formData.append('title', title.val().trim());
            formData.append('author', author.val().trim());
            formData.append('publisher', publisher.val().trim());
            formData.append('genre', genre.val().trim());
            formData.append('category', category.val().trim());
            formData.append('branch', branch.val().join(','));
            formData.append('price', price.val().trim());
            formData.append('quantity', quantity.val().trim());
            formData.append('available', available.val().trim());

            // Append the image file if selected
            var imageInput = $('#image')[0].files[0];
            if (imageInput) {
                formData.append('image', imageInput);
            }

            // Ajax request
            $.ajax({
                type: 'POST', // Assuming your route uses POST method
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('store.book') }}',
                success: function(data) {
                    console.log(data);
                    notify(data[0].statusCode, data[0].message);
                    books();
                    clear_inputs();
                }
            });
        }

        function validateInput(value, fieldName) {
            if (fieldName == 'Branch') {
                var libranch = value.val().join(',')
                if (!libranch) {
                    value.addClass('is-invalid')
                    showError(`${fieldName} is required`);
                    return false;
                } else {
                    value.removeClass('is-invalid')
                }
            }
            if (!value.val()) {
                value.addClass('is-invalid')
                showError(`${fieldName} is required`);
                return false;
            } else {
                value.removeClass('is-invalid')
            }
            return true;
        }

        function dropdowns() {
            $.ajax({
                type: 'GET',
                url: '{{ route('dropdowns') }}',
                success: function(data) {
                    var genres = data.genres;
                    var branches = data.branches;
                    var categories = data.categories;
                    console.log(genres);

                    genre.empty();
                    category.empty();
                    branch.empty();
                    // genre.append('<option value="">Select Genre</option>');
                    // category.append('<option value="">Select Category</option>');

                    branch.select2({
                        data: branches,
                        multiple: true,
                        placeholder: '...', // Optional placeholder
                        // Add other configuration options as needed
                    });
                    genre.select2({
                        data: genres,
                        search: true,
                        allowClear: true,
                        placeholder: "...",
                    })
                    category.select2({
                        data: categories,
                        search: true,
                        allowClear: true,
                        placeholder: "...",
                    })

                }
            });
        }

        function delete_book(id) {
            Swal.fire({
                icon: 'warning',
                title: 'You want to delete this book?',
                text: `You can't undo this process.`,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {

                    $.ajax({
                        url: '{{ route('delete.book') }}',
                        type: "GET",
                        data: {
                            id: id,
                        },
                        success: function(data) {
                            notify(data[0].statusCode, data[0].message);
                            books()
                        }
                    });
                }

            })
        }

        function books(params) {
            $.ajax({
                type: 'GET',
                data: {
                    action: 'getall',
                    params: params ?? null
                },
                url: '{{ route('books') }}',
                success: function(data) {
                    console.log(data)
                    load_books_datatable(data);
                }
            });
        }

        function load_books_datatable(data) {
            var table = $('#DataTables_Table_1').DataTable({
                destroy: true,
                responsive: true,
                stateSave: true,
                data: data,
                columns: [{
                        data: "book_img",
                        sortable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            var img =
                                `<img class="shadow" src="${row.book_img}" style="height:60px; width:50px;object-fit: cover;"/>`
                            return img;
                        }
                    },
                    {
                        data: "book_title",
                        render: function(data, type, row) {
                            var capitalizeFirstLetter = function(string) {
                                return string.toLowerCase().replace(/\b\w/g, function(match) {
                                    return match.toUpperCase();
                                });
                            };

                            var author =
                                `<div class="mt-1"><a class="font-size-sm">Author: ${capitalizeFirstLetter(row.book_author)}</a></div>`;
                            var publisher =
                                `<div><a class="font-size-sm">Publisher: ${capitalizeFirstLetter(row.book_publisher)}</a></div>`;
                            var title = `
                                <div><a href="#" class="font-w600 text-dark text-nowrap">${capitalizeFirstLetter(row.book_title)}</a></div>
                                ${author}
                                ${publisher}
                            `;
                            return title;
                        }
                    },
                    {
                        data: "book_author",
                        visible: false
                    },
                    {
                        data: "book_publisher",
                        visible: false
                    },
                    {
                        data: "genre_name",
                        className: 'text-center',
                        render: function(data, type, row) {
                            var renderHtml =
                                `<span class="font-size-sm font-w500"> ${row.genre_name} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: "category_name",
                        className: 'text-center',
                        render: function(data, type, row) {
                            var renderHtml =
                                `<span class="font-size-sm font-w500"> ${row.category_name} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: "book_qty",
                        className: 'text-right',
                        render: function(data, type, row) {
                            var renderHtml =
                                `<span class="font-size-sm font-w600 ${ row.book_qty === 0 ? 'text-danger' : 'text-dark'}"> ${row.book_qty} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: "book_available",
                        className: 'text-right',
                        render: function(data, type, row) {
                            var renderHtml =
                                `<span class="font-size-sm font-w600 ${ row.book_available === 0 ? 'text-danger' : 'text-dark'}"> ${row.book_available} </span>`;
                            return renderHtml;
                        }
                    },
                    {
                        data: "book_price",
                        className: 'text-right',
                        render: function(data, type, row) {
                            var bookPrice = parseFloat(row.book_price).toFixed(2);
                            var renderHtml =
                                `<span class="text-success font-size-sm font-w600">₱${bookPrice}  
                                </span>`;

                            return renderHtml;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        className: 'text-right',
                        render: function(type, data, row) {
                            var renderHtml = ` <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-alt-primary view_book" data-id="${row.id}" data-toggle="tooltip" title="View Book">
                                                            <i class="fa fa-fw far fa-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-alt-primary edit_book" data-id="${row.id}" data-toggle="tooltip" title="Edit Book">
                                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-alt-primary delete_book" data-id="${row.id}" data-toggle="tooltip" title="Remove Book">
                                                            <i class="fa fa-fw fa-times"></i>
                                                        </button>
                                                    </div>`;
                            return renderHtml;
                        }
                    },
                ]
            });
        }

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Please fill all fields!',
                text: message,
                // showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                // confirmButtonText: 'Yes'
            });
            notify('error', message);
        }
    </script>
@endsection
