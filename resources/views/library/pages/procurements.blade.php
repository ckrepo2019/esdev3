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
        <!-- MODAL ADD BOOK -->
        <div class="modal fade" id="modal-block-popin" tabindex="-1" role="dialog" aria-labelledby="modal-block-popin"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-popin modal-xl" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title modal-title">Add New Purchase</h3>
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
                                        <label class="mb-1">Book Name <span class="text-danger">*</span></label>
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

                                    <div class="row">
                                        <div class="col-md-6 wrapper_category">
                                            <div class="form-group">
                                                <label class="mb-1">Select Category <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control form-control-alt select2 font-size-sm"
                                                    id="select-category" style="width: 100%;">
                                                </select>
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Category is required</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 wrapper_donor" hidden>
                                            <div class="form-group">
                                                <label class="mb-1">Donated by <span class="text-danger">*</span> </label>
                                                <input type="text" class="form-control form-control-alt font-size-sm"
                                                    id="donor" onkeyup="this.value = this.value.toUpperCase();">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Donor is required</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-1">Publishing <span class="text-danger">*</span></label>
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
                                        <textarea class="form-control form-control-alt font-size-sm" id="description" rows="5"
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

        <!-- SELECT BRANCH -->
        <div class="row justify-content-center">
            <div class="col-md-6 ">
                <div class="block block-rounded">
                    <div class="block-header bg-secondary">
                        <div class="block-title text-light"><i class="fa fa-archway mr-1"></i> Select Library Branch</div>
                    </div>
                    <div class="block-content block-content-full">
                        <select id="select-library" class="form-control form-control-alt select2 font-size-sm"
                            style="width: 100%;">
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SELECT BRANCH -->

        <!-- TABLE BOOK -->
        <div class="row justify-content-center">
            <div class="col-md-6" hidden id="tb_purchase">
                <div class="block block-rounded">
                    <div class="block-header bg-primary">
                        <h3 class="block-title"> <i class="fa fa-shopping-basket mr-1"></i> PURCHASES </h3>
                        <button type="button" class="btn btn-sm ml-auto btn-dark new_purchase my-1" data-toggle="modal"
                            data-target="#modal-block-popin">
                            <i class="fa fa-fw fa-plus mr-1"></i> Add Purchase
                        </button>
                    </div>

                    <div class="block-content block-content-full">
                        <div class="table-responsive">
                            <table class="table table-hover table-vcenter table-striped table-borderless js-dataTable-full"
                                id="tbl_purchase" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Categories</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" hidden id="tb_donation">
                <div class="block block-rounded">
                    <div class="block-header bg-primary">
                        <h3 class="block-title"> <i class="nav-main-link-icon fa fa-gift"></i> DONATION </h3>
                        <button type="button" class="btn btn-sm ml-auto btn-dark new_donation my-1" data-toggle="modal"
                            data-target="#modal-block-popin">
                            <i class="fa fa-fw fa-plus mr-1"></i> Add Donation
                        </button>
                    </div>

                    <div class="block-content block-content-full">
                        <div class="table-responsive">
                            <table class="table table-hover table-vcenter js-dataTable-full" id="tbl_donation"
                                style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Categories</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
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
        var isbn = $('#isbn');
        var title = $('#title');
        var author = $('#author');
        var datereceived = $('#datereceived');
        var edition = $('#edition');
        var copyright = $('#copyright');
        var cln1 = $('#cln1');
        var cln2 = $('#cln2');
        var cln3 = $('#cln3');
        var cln4 = $('#cln4');
        var publisher = $('#publisher');
        var genre = $('#select-genre');
        var category = $('#select-category');
        var branch = $('#select-branch');
        var price = $('#price');
        var quantity = $('#quantity');
        var available = $('#available');
        var description = $('#description');
        var procurement_type;
        $(document).ready(function() {
            hideOrShowTable()
            dropdowns()
            load_purchase_datatable(jsonData);
            load_donation_datatable(jsonData);

            // procurements($('#select-library').val())

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
                console.log('sldfslkdfkdjk');
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

            $('#price').on('input', function(event) {
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

            $(document).on('click', '.new_purchase', function() {
                clear_inputs();
                purpose = 'add';
                procurement_type = 'purchase';
                $('.wrapper_donor').removeClass('col-md-6')
                $('.wrapper_category').removeClass('col-md-6')
                $('.wrapper_category').addClass('col-12')
                $('#book_cover').prop('hidden', true);
                $('#modal-block-popin').modal();
            });

            $(document).on('click', '.new_donation', function() {
                clear_inputs();
                purpose = 'add';
                procurement_type = 'donation';
                $('.wrapper_donor').prop('hidden', false)

                $('#book_cover').prop('hidden', true);
                $('#modal-block-popin').modal();
            });

            $(document).on('click', '.save_book', function() {
                if (purpose === 'add') {
                    storeBook();
                }
                // else if (purpose === 'edit') {
                //     updateBook();
                // }
            });
        });

        function hideOrShowTable() {
            switch (action) {
                case 'store':
                    break;

                case 'purchase':
                    $('#tb_purchase').prop('hidden', false);
                    break;

                case 'donation':
                    $('#tb_donation').prop('hidden', false);
                    break;

                default:
                    break;
            }
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
            formData.append('procurement_type', procurement_type);
            formData.append('procurement_donor', $('#donor').val());

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
                url: '{{ route('store.procurement') }}',
                success: function(data) {
                    console.log(data);
                    notify(data[0].statusCode, data[0].message);
                    // books();
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

        function clear_inputs() {
            isbn.val('');
            title.val('');
            author.val('');
            datereceived.val('');
            edition.val('');
            copyright.val('');
            cln1.val('');
            cln2.val('');
            cln3.val('');
            cln4.val('');
            publisher.val('');
            genre.val('').change();
            category.val('').change();
            branch.val('').change();
            price.val('');
            quantity.val('');
            available.val('');
            description.val('');
            $('#donor').val('');
        }

        function resetBookCover() {
            // $('#book_cover').attr('src', '').hide();
            $('#image').val('');
            $('.custom-file-label').text('Choose file');
        }

        function clearValidationErrors() {
            $('.form-control').removeClass('is-invalid');
        }

        function dropdowns() {
            $.ajax({
                type: 'GET',
                url: '{{ route('dropdowns') }}',
                success: function(data) {
                    var genres = data.genres;
                    var branches = data.branches;
                    var categories = data.categories;
                    $('#select-genre').empty();
                    $('#select-branch').empty();
                    $('#select-category').empty();

                    $('#select-library').select2({
                        data: branches,
                        search: true,
                        allowClear: true,
                        placeholder: 'Select Library Branch', // Optional placeholder
                        // Add other configuration options as needed
                    });

                    $(document).on('change', '#select-library', function() {
                        var branch = $(this).val();
                        var selectedText = $(this).find('option:selected').text().toLowerCase();
                        var newUrl =
                            `/library/admin/procurements/${action}?action=${action}&library_branch=${branch}`;
                        console.log(newUrl);
                        if (branch) {
                            window.location.href = newUrl;
                        }
                    });

                    $('#select-genre').select2({
                        data: genres,
                        search: true,
                        allowClear: true,
                        placeholder: "...",
                    })

                    $('#select-branch').select2({
                        data: branches,
                        multiple: true,
                        placeholder: '...', // Optional placeholder
                        // Add other configuration options as needed
                    });

                    $('#select-category').select2({
                        data: categories,
                        allowClear: true,
                        placeholder: "...",
                    })

                }
            });
        }

        function load_purchase_datatable(data) {
            var table = $('#tbl_purchase').DataTable({
                destroy: true,
                responsive: true,
                stateSave: true,
                data: data,
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 15, 20],
                    [5, 10, 15, 20]
                ],
                columns: [{
                        data: 'category_name',
                        render: function(data, type, row) {
                            var renderHtml = `
                                <div>
                                    <div> <span class="font-w600 text-center">${row.category_name}</span> </div>
                                    <div> <img src="${row.imageUrl}" style="height:60px; width:60px;"/> </div>
                                </div>`;
                            return renderHtml;
                        }
                    },

                    {
                        data: 'category_total',
                        sortable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            var renderHtml =
                                `
                                <div> <span > Total </span> </div> 
                                <span class="h3 ${row.total_proc_purchase == 0 ? 'text-danger': '' }">${row.total_proc_purchase}</span>
                            `;
                            return renderHtml;
                        }
                    },

                ]
            });
        }

        function load_donation_datatable(data) {
            var table = $('#tbl_donation').DataTable({
                destroy: true,
                responsive: true,
                stateSave: true,
                data: data,
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 15, 20],
                    [5, 10, 15, 20]
                ],
                columns: [{
                        data: 'category_name',
                        render: function(data, type, row) {
                            var renderHtml = `
                                <div>
                                    <div> <span class="font-w600 text-center">${row.category_name}</span> </div>
                                    <div> <img src="${row.imageUrl}" style="height:60px; width:60px;"/> </div>
                                </div>`;

                            return renderHtml;
                        }
                    },

                    {
                        data: 'category_total',
                        sortable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            var renderHtml =
                                `
                                <div> <span> Total </span> </div> 
                                <span class="h3 ${row.total_proc_donation == 0 ? 'text-danger': '' }">${row.total_proc_donation}</span>
                            `;
                            return renderHtml;
                        }
                    },

                ]
            });
        }
    </script>
@endsection
