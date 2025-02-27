@extends('guidanceV2.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    </script>
    <style>
        .align-middle td {
            vertical-align: middle;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        .shadow-none {
            box-shadow: none !important;
            border: 1px solid rgb(224, 222, 222) !important;
        }

        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff !important;
            border-color: #007bff !important;
            color: #fff;
        }

        /* Change options to purple on hover */
        .select2-purple .select2-results__option:hover {
            background-color: #8A2BE2 !important;
            color: #fff !important;
        }


        .btn-purple {
            background-color: #6f42c1;
            color: #fff;
        }

        .btn_custom_group {
            padding: 1px 6px !important;
        }

        .card {
            /* box-shadow: 1px 1px 4px #272727c9 !important; */
            border: none !important;
        }

        .radius-custom-header {
            color: white;
            border-top-left-radius: .0rem !important;
            border-top-right-radius: .0rem !important;
            background-color: rgba(0, 0, 0, .03);
            border: none;
        }

        .select2-container--bootstrap4 .select2-selection,
        #description.form-control {
            border: 1px solid #414242;
            border-radius: 6px;

        }
    </style>
@endsection

@section('content')
    <!-- MODAL ADD CATEGORY SETUP -->
    <div class="modal fade" id="modalAddCategory" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title">New Subject</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="" class="mb-1 ">Subject Name</label>
                                <input type="text" class="form-control" placeholder="e.g. Math, English and IQ TEST"
                                    onkeyup="this.value = this.value.toUpperCase();" id="criteria_name">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="" class="mb-1 ">Passing Rate</label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" placeholder="85"
                                        id="criteria_percent">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="" class="mb-1 ">Total Items</label>
                                <input type="number" min="0" class="form-control" id="total_items">
                            </div>
                            <div class="form-group col-3">
                                <label for="" class="mb-1 ">Required</label>
                                <div class="icheck-success">
                                    <input type="checkbox" id="new_isrequired">
                                    <label for="new_isrequired"></label>
                                </div>
                            </div>

                            <label for="timelimit_hours" class="mb-1 col-md-12"> Time Limit</label>
                            <div class="form-group col-md-6">
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" placeholder="Hours"
                                        id="timelimit_hours">
                                    <div class="input-group-append">
                                        <span class="input-group-text">hours</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" placeholder="Minutes"
                                        id="timelimit_minutes">
                                    <div class="input-group-append">
                                        <span class="input-group-text">minutes</span>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-2 col-12">
                                <button type="button" class="btn btn-purple mb-3" id="btn_add_category">
                                    <i class="fas fa-plus mr-1"></i> ADD CATEGORY
                                </button>
                            </div> --}}
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-purple" id="btn_add_category">Create</button>
                </div>

            </div>

        </div>

    </div>
    <!-- MODAL EDIT CATEGORY SETUP -->
    <div class="modal fade" id="modalEditCategory" tabindex="-1" role="dialog" aria-labelledby="modalEditCategory"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="" class="mb-1 ">Category Name</label>
                                <input type="text" class="form-control" placeholder="e.g. Math, English and IQ TEST"
                                    onkeyup="this.value = this.value.toUpperCase();" id="edit_criteria_name">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="" class="mb-1 ">Passing Rate</label>
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" placeholder="85"
                                        id="edit_criteria_percent">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="" class="mb-1 ">Total Items</label>
                                <input type="number" min="0" class="form-control" id="edit_total_items">
                            </div>

                            <div class="form-group col-3">
                                <label for="" class="mb-1 ">Required</label>
                                <div class="icheck-success">
                                    <input type="checkbox" id="edit_isrequired">
                                    <label for="edit_isrequired"></label>
                                </div>
                            </div>

                            <label for="timelimit_hours" class="mb-1 col-md-12"> Time Limit</label>
                            <div class="form-group col-md-6">
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" placeholder="Hours"
                                        id="edit_timelimit_hours">
                                    <div class="input-group-append">
                                        <span class="input-group-text">hours</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="input-group">
                                    <input type="number" min="0" class="form-control" placeholder="Minutes"
                                        id="edit_timelimit_minutes">
                                    <div class="input-group-append">
                                        <span class="input-group-text">minutes</span>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-2 col-12">
                                <button type="button" class="btn btn-purple mb-3" id="btn_add_category">
                                    <i class="fas fa-plus mr-1"></i> ADD CATEGORY
                                </button>
                            </div> --}}
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-purple" id="btn_update_category">Update</button>
                </div>

            </div>

        </div>

    </div>



    <div class="content-header">
        <div class="container-fluid">
            <div class="card shadow-none">
                <div class="card-header">
                    <div class="row px-2 py-4">
                        <div class="col-sm-6">
                            <h5 class="">ADD EXAM SETUP</h5>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item "> <a href="#">Home</a> </li>
                                <li class="breadcrumb-item "><a
                                        href="/guidance/admission/percentagesetup?page=1">Admission
                                        Management</a> </li>
                                <li class="breadcrumb-item active">Add Passing Rate</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <div class="container-fluid">
            <form>
                <div class="card shadow ">
                    <div class="card-header" style="font-size: 17px; color: #000000">
                        <h5 class="card-title d-flex align-items-center " style="width: 100%;font-weight: 600;">
                            <i class="fas fa-wrench mr-1" style="padding-right: 5px;"></i>NEW EXAM SETUP
                            <button type="button" class="btn btn-primary shadow ml-auto" id="btnModalAddCategory">
                                <i class="fas fa-plus mr-1"></i> <strong>Add Subject</strong>
                            </button>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-5 p-4">
                                <div class="row">
                                    <div class="form-group col-xl-6 col-lg-12">
                                        <label class="mb-1">Description</label>
                                        <input type="text" class="form-control" id="description"
                                            onkeyup="this.value = this.value.toUpperCase();"
                                            placeholder="Add Description Here">
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Description is required!</strong>
                                        </span>
                                    </div>

                                    <div class="form-group col-xl-6 col-lg-12">
                                        <label class="mb-1"> Academic Program </label>
                                        <select class="form-control " id="acadprog" name="acadprog_id"
                                            style="width: 100%;" required>
                                            <option value=""> Select AcadProg</option>
                                            @foreach (DB::table('academicprogram')->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->progname }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label for="" class="mb-1">Select Level</label>
                                        <select class="form-control select2-purple" id="select-level"
                                            style="width: 100%;">
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Grade Level is required!</strong>
                                        </span>
                                    </div>


                                    <div class="form-group col-md-12" id="course-wrapper" hidden>
                                        {{-- <label class="mb-1">Select Courses</label> --}}
                                        <div class="form-group row align-items-center mb-0">
                                            <div class="col-md-6 ">
                                                <label class="">Select Course </label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="icheck-success float-right">
                                                    <input type="checkbox" id="allCourse">
                                                    <label for="allCourse">Select all</label>
                                                </div>
                                            </div>
                                        </div>
                                        <select class="form-control" id="select-course" style="width: 100%;">
                                            @foreach (DB::table('college_courses')->where('deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->courseabrv }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Course is required!</strong>
                                        </span>
                                    </div>

                                    {{-- <div class="form-group col-lg-12" id="course-wrapper" hidden>
                                        <label class="mb-1">Select Courses</label>
                                        <select class="form-control" id="select-course" style="width: 100%;">
                                            @foreach (DB::table('college_courses')->where('deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->courseabrv }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Course is required!</strong>
                                        </span>
                                    </div> --}}

                                    <div class="form-group col-lg-12 strand-wrapper" hidden>
                                        <label class="mb-1">Select Strand</label>
                                        <select class="form-control" id="select-strand" style="width: 100%;">
                                            @foreach (DB::table('sh_strand')->where('deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->strandname }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Strand is required!</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 p-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-valign-middle" id="table_test_categories"
                                        style="width: 100%;">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th style="width: 10px;">#</th>
                                                <th>Subjects</th>
                                                <th>Passing Rate</th>
                                                <th>Time</th>
                                                <th>Items</th>
                                                <th>Required</th>
                                                <th style="text-align: center; width: 20%;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_categories">
                                            <!-- Your table rows will go here -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td> <!-- Leave the first column empty -->
                                                <td class="text-right"><strong>Average:</strong></td>
                                                <!-- Label for Average -->
                                                <td> <span id="average_passing_rate"
                                                        class="text-success font-weight-bold">0%</span>
                                                </td>
                                                <!-- Placeholder for Average Passing Rate -->
                                                <td colspan="4"></td> <!-- Empty cells for Time and Items columns -->

                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex">
                        <button type="button" class="btn btn-default" onclick="goBack()">Cancel</button>
                        <button type="button" class="btn btn-purple  ml-auto" id="btn_save_assessment"><i
                                class="far fa-paper-plane mr-1"></i>Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <!-- Bootstrap Switch -->
    <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        var listCategory = [];
        var listCriteria = [];
        var filteredCourses = []
        var listCoursePercentage = []
        var isCollege = false;
        var FINAL_AVG = 0.00;
        var SERVER_EXAM_SETUP_ID = 0;

        $(document).ready(function() {


            $('#allCourse').on('click', function() {
                if ($('#allCourse').is(':checked')) {
                    $('#select-course option').prop('selected', true);
                    $("#select-course").trigger("change");
                } else {
                    $('#select-course option').prop('selected', false);
                    $("#select-course").trigger("change");
                }
            })

            // $('#btn_update_category').on('click')

            $('#btnModalAddCategory').on('click', function() {
                $('#modalAddCategory').modal();
            })

            // enable_timelimit()
            $('#timelimit_hours').val(0);
            $('#timelimit_minutes').val(0);
            $('#enable_duration').on('change', function() {
                enable_timelimit()
            });

            $('#acadprog').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select AcadProg'
            })

            $('#select-strand').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Strand',
                multiple: true
            })

            $('#acadprog').on('change', function() {
                var id = $(this).val()
                console.log(id);
                $('#course-wrapper').prop('hidden', true);
                if (id > 0) {
                    if (id == 5) {
                        $('#select-course').val("").change();
                        $('.strand-wrapper').prop('hidden', false);
                        $('#course-wrapper').prop('hidden', true);
                    } else if (id == 6) {
                        $('.strand-wrapper').prop('hidden', true);
                        $('#course-wrapper').prop('hidden', false);
                        $('#select-strand').val("").change();
                        console.log('Selected acadprog ' + id + ' is college');
                    } else {
                        $('#select-strand').val("").change();
                        $('.strand-wrapper').prop('hidden', true);
                        $('#select-course').val("").change();
                        $('#course-wrapper').prop('hidden', true);
                        console.log('Selected acadprog ' + id + ' is not college');
                    }

                    $.ajax({
                        type: 'GET',
                        data: {
                            id: id
                        },
                        url: '{{ route('filter.acadprog') }}',
                        success: function(data) {
                            console.log(data);
                            $('#select-level').empty()
                            $('#select-level').select2({
                                data: data,
                                allowClear: true,
                                theme: 'bootstrap4',
                                placeholder: 'Select Level'
                            })


                        }
                    })
                }
            });

            $(document).on('click', '.btn_edit', function() {
                var status = $(this).data('status');
                var idToEdit = $(this).data('id');
                var indexToEdit = listCategory.findIndex(item => item.category === idToEdit)
                var inputGroup = $(this).closest('.input-group');
                var inputFields = inputGroup.find('input');
                inputFields.prop('disabled', false);

                if (status === 'edit') {
                    $(this).find('i').removeClass('far fa-edit text-info').addClass(
                        'far fa-save text-success');
                    $(this).html('<i class="far fa-save text-success mr-1"></i>Save');
                    $(this).data('status', 'save');
                    // Enable input fields for editing
                    $(this).closest('.input-group').find('input').prop('disabled', false);
                } else if (status === 'save') {
                    // Check if input values are not null or empty
                    var categoryValue = $(this).closest('.input-group').find('.category_name').val();
                    var percentageValue = $(this).closest('.input-group').find('.percentage').val();
                    var hrsValue = parseInt($(this).closest('.input-group').find('.timelimit_hrs').val()) ||
                        0;
                    var minvalue = parseInt($(this).closest('.input-group').find('.timelimit_min').val()) ||
                        0;

                    if (categoryValue && percentageValue && !isNaN(hrsValue) && !isNaN(minvalue)) {
                        var isExistingCategory = listCategory.some((item, index) => index !== indexToEdit &&
                            item.category === categoryValue);
                        if (!isExistingCategory) {
                            // Compute total minutes
                            var totalMinutes = hrsValue * 60 + minvalue;

                            if (indexToEdit !== -1) {
                                // Update the item in the array
                                listCategory[indexToEdit].category = categoryValue;
                                listCategory[indexToEdit].percentage = percentageValue;
                                listCategory[indexToEdit].timelimit_hrs = hrsValue;
                                listCategory[indexToEdit].timelimit_min = minvalue;
                                listCategory[indexToEdit].timelimit = totalMinutes;
                                console.log(totalMinutes);

                                // Re-render the list based on the updated array
                                $('.list_category').empty();
                                updateListCategory();
                            }
                        } else {
                            $(this).closest('.input-group').find('.category_name').val(listCategory[
                                indexToEdit].category);
                            $(this).closest('.input-group').find('.percentage').val(listCategory[
                                indexToEdit].percentage)
                            $(this).closest('.input-group').find('.timelimit_hrs').val(listCategory[
                                indexToEdit].timelimit_hrs)
                            $(this).closest('.input-group').find('.timelimit_min').val(listCategory[
                                indexToEdit].timelimit_min)

                            notify('error',
                                'Category name already exists. Please choose a different name.');
                        }
                    } else {
                        alert('Please fill in all fields with valid values before saving.');
                    }

                    $(this).find('i').removeClass('far fa-save text-success').addClass(
                        'far fa-edit text-info');
                    $(this).html('<i class="far fa-edit mr-1 text-info"></i>Edit');
                    $(this).data('status', 'edit');
                    // Disable input fields after saving
                    $(this).closest('.input-group').find('input').prop('disabled', true);

                }
            });

            $('.list_category').on('blur', 'input', function() {
                $(this).prop('disabled', true); // Disable only the input field being edited
            });

            $('#select-level').select2({
                allowClear: true,
                placeholder: "Select Grade Level",
                theme: 'bootstrap4',
                multiple: true,
                containerCssClass: 'select2-purple',
                dropdownCssClass: 'select2-purple',
                selectionCssClass: 'select2-purple',
                language: {
                    errorLoading: function() {
                        return 'The results could not be loaded.';
                    },
                    inputTooLong: function(args) {
                        var overChars = args.input.length - args.maximum;
                        return 'Please delete ' + overChars + ' character' + (overChars != 1 ? 's' :
                            '');
                    },
                    inputTooShort: function(args) {
                        var remainingChars = args.minimum - args.input.length;
                        return 'Please enter ' + remainingChars + ' or more characters';
                    },
                    loadingMore: function() {
                        return 'Loading more results…';
                    },
                    maximumSelected: function(args) {
                        var message = 'You can only select ' + args.maximum + ' item';
                        if (args.maximum != 1) {
                            message += 's';
                        }
                        return message;
                    },
                    noResults: function() {
                        return 'No results found';
                    },
                    searching: function() {
                        return 'Searching…';
                    }
                }
            });

            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));

                $(this).on('switchChange.bootstrapSwitch', function(event, state) {
                    // Call handleCheckboxChange function when checkbox state changes
                    handleCheckboxChange($(this));
                });
            })

            // $('#select-level').on('change', function() {
            //     // generate_headers()
            //     var selectedValues = $(this).val();
            //     // Check if selectedValues is empty
            //     if (!selectedValues || selectedValues.length === 0) {
            //         $('.college_modal').prop('hidden', true);
            //         return; // Exit the function if no values are selected
            //     }

            //     // Check each selected value
            //     $.each(selectedValues, function(index, value) {
            //         if (value >= 17 && value <= 21) {
            //             isCollege = true;
            //             $('.college_modal').prop('hidden', false);
            //             console.log('Selected value ' + value + ' is between 18 and 21');
            //             $('#graders_percent_wrapper').attr('hidden', true)
            //             return;
            //         } else {
            //             $('#graders_percent_wrapper').attr('hidden', false)
            //             isCollege = false
            //             $('.college_modal').prop('hidden', true);
            //             console.log('Selected value ' + value + ' is not in the range of 18-21');
            //         }
            //     });

            //     if (isCollege) {
            //         var collegeArr = selectedValues.filter(item => item >= 17 && item <= 21)
            //         console.log(collegeArr);
            //         $('#select-level').val(collegeArr)
            //         $('.college_modal').prop('hidden', false);
            //         var selectedOptions = $('#select-course').find('option:selected');

            //         var courses = selectedOptions.map(function() {
            //             return {
            //                 id: $(this).val(),
            //                 text: $(this).text()
            //             };
            //         }).get();

            //         var nonEmptyCourses = courses.filter(course => course.value !== '');
            //         filteredCourses = nonEmptyCourses;
            //         console.log(filteredCourses);
            //         // console.log(courses);
            //         onChangeCourse(nonEmptyCourses);
            //     } else {
            //         $('.college_modal').prop('hidden', true);
            //     }

            // });

            // $('#select-course').on('change', function() {
            //     var selectedOptions = $(this).find('option:selected');

            //     var courses = selectedOptions.map(function() {
            //         return {
            //             id: $(this).val(),
            //             text: $(this).text()
            //         };
            //     }).get();

            //     var nonEmptyCourses = courses.filter(course => course.value !== '');
            //     filteredCourses = nonEmptyCourses;
            //     console.log(filteredCourses);
            //     // console.log(courses);
            //     onChangeCourse(nonEmptyCourses);
            // });

            $('#select-level').select2({
                allowClear: true,
                placeholder: "Select Level",
                theme: 'bootstrap4',
                multiple: true
            });

            $('#select-course').select2({
                allowClear: true,
                placeholder: "Select Course",
                theme: 'bootstrap4',
                multiple: true
            });

            $('#select-year').select2({
                allowClear: true,
                placeholder: "Select School Year",
                theme: 'bootstrap4',
            });

            $('#btn_add_category').on('click', function() {
                var obj = {};
                var isvalid = true
                var category = $('#criteria_name').val();
                var total_items = $('#total_items').val();
                var criteria_percent = $('#criteria_percent').val();
                var timelimit_hrs = $('#timelimit_hours').val();
                var timelimit_min = $('#timelimit_minutes').val();
                var new_isrequired = $('#new_isrequired').prop('checked');

                if (!category) {
                    isvalid = false
                    $('#criteria_name').addClass('is-invalid');
                    notify('error', 'Criteria Name is required!');
                    return
                } else {
                    let exists = listCategory.some(item => item.category === category)
                    if (exists) {
                        isvalid = false
                        $('#criteria_name').addClass('is-invalid');
                        notify('error', 'Criteria Name already exist!');
                        return
                    } else {
                        $('#criteria_name').removeClass('is-invalid');
                    }
                }

                if (!criteria_percent) {
                    isvalid = false
                    $('#criteria_percent').addClass('is-invalid');
                    notify('error', 'Criteria Percent is required!');
                    return
                } else {
                    $('#criteria_percent').removeClass('is-invalid');
                }

                if (!total_items) {
                    isvalid = false
                    $('#total_items').addClass('is-invalid');
                    notify('error', 'Total Item is required!');
                    return
                } else {
                    $('#total_items').removeClass('is-invalid');
                }

                if (!timelimit_hrs) {
                    isvalid = false
                    $('#timelimit_hours').addClass('is-invalid');
                    notify('error', 'Time limit hrs is required!');
                    return
                } else {
                    $('#timelimit_hours').removeClass('is-invalid');
                }

                if (!timelimit_min) {
                    isvalid = false
                    $('#timelimit_minutes').addClass('is-invalid');
                    notify('error', 'Time limit min is required!');
                    return
                } else {
                    $('#timelimit_minutes').removeClass('is-invalid');
                }


                let exists = listCategory.some(item => item.category === obj.category)
                if (isvalid) {
                    if (!exists) {
                        var timeInMinutes = 0
                        // if (enable_duration) {
                        //     if (timelimit_hrs && timelimit_min) {
                        //         timeInMinutes = (timelimit_hrs * 60) + parseInt(timelimit_min);
                        //     } else if (timelimit_hrs && !timelimit_min) {
                        //         timeInMinutes = (timelimit_hrs * 60)
                        //     } else if (!timelimit_hrs && timelimit_min) {
                        //         timeInMinutes = parseInt(timelimit_min)
                        //     }

                        // }

                        obj.category = category;
                        obj.percentage = criteria_percent;
                        obj.timelimit = timeInMinutes;
                        obj.timelimit_min = timelimit_min ? timelimit_min : 0;
                        obj.timelimit_hrs = timelimit_hrs ? timelimit_hrs : 0;
                        obj.required = new_isrequired ? 1 : 0,
                            obj.total_items = total_items
                        listCategory.push(obj);
                        $('.list_category').empty();
                        $('#criteria_name').val("");
                        $('#criteria_percent').val("");
                        $('#timelimit_hours').val(0);
                        $('#timelimit_minutes').val(0);
                        $('#total_items').val(0);
                        // updateListCategory();
                        updateTableCategory();
                    } else {
                        console.log('EXIST')
                        notify('warning', 'Criteria Name already exist!')
                    }
                }
            });

            $('.btn_delete_passing_rate').on('click', function() {
                var itemToDelete = $(this).data('id');
                console.log(itemToDelete);
                Swal.fire({
                    type: 'info',
                    title: 'Are you sure you want to delete this Setup ?',
                    text: `You won't be able to revert this! `,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "GET",
                            url: '{{ route('delete.passingrate') }}',
                            data: {
                                id: itemToDelete
                            },
                            success: function(response) {
                                console.log(response)
                                notify(response.status, response.message);
                                getAllAdmissionSetup()
                            }
                        });
                    }
                });

            })

            $('#btn_save_assessment').on('click', function() {
                $('#btn_save_assessment').attr('disabled', true);
                var isvalid = true

                if (!$('#description').val()) {
                    isvalid = false
                    $('#description').addClass('is-invalid');
                    notify('error', 'Description is required!')
                    $('#btn_save_assessment').attr('disabled', false);
                    return
                } else {
                    $('#description').removeClass('is-invalid');
                }

                if (!$('#acadprog').val()) {
                    isvalid = false
                    $('#acadprog').addClass('is-invalid');
                    notify('error', 'AcadProg is required!')
                    $('#btn_save_assessment').attr('disabled', false);
                    return
                } else {
                    $('#acadprog').removeClass('is-invalid');
                }

                if (!$('#select-level').val().length > 0) {
                    isvalid = false
                    $('#select-level').addClass('is-invalid');
                    notify('error', 'Level is required!')
                    $('#btn_save_assessment').attr('disabled', false);
                    return
                } else {
                    $('#select-level').removeClass('is-invalid');
                }

                if ($('#acadprog').val() == 5) {
                    if (!$('#select-strand').val().length > 0) {
                        isvalid = false
                        $('#select-strand').addClass('is-invalid');
                        notify('error', 'Strand is required!')
                        $('#btn_save_assessment').attr('disabled', false);
                        return
                    } else {
                        $('#select-strand').removeClass('is-invalid');
                    }
                }

                if ($('#acadprog').val() == 6) {
                    if (!$('#select-course').val().length > 0) {
                        isvalid = false
                        $('#select-course').addClass('is-invalid');
                        notify('error', 'Course is required!')
                        $('#btn_save_assessment').attr('disabled', false);
                        return
                    } else {
                        $('#select-course').removeClass('is-invalid');
                    }
                }

                if (listCategory.length == 0) {
                    isvalid = false
                    notify('error', 'Add atleast one Category!')
                    $('#btn_save_assessment').attr('disabled', false);
                    return
                }

                // if (isCollege) {
                //     listCoursePercentage = [];
                //     $('#tbl_listCoursePercentage').find('tr').each(function() {
                //         var rowInputs = $(this).find('input');
                //         var courseid = 0;
                //         var general_percentage = 0;
                //         var isprovision = false;
                //         var ArrCategory = [];
                //         rowInputs.each(function() {
                //             var inputId = $(this).attr('id');
                //             var inputValue = $(this).val();
                //             var isChecked = $(this).prop('checked');
                //             var inputClass = $(this).attr('class');

                //             if (!inputValue) {
                //                 $(this).closest('input').addClass(
                //                     'is-invalid'
                //                 );
                //                 notify('error', 'Inputs are required!')
                //                 isvalid = false;
                //             } else {
                //                 $(this).closest('input').removeClass(
                //                     'is-invalid'
                //                 ); // Find closest ancestor input and remove class
                //             }


                //             if (inputClass && inputClass.includes('courseid')) {
                //                 courseid = inputId;
                //             }
                //             if (inputClass && inputClass.includes('isprovision')) {
                //                 isprovision = isChecked
                //             }
                //             if (inputClass && inputClass.includes('input_gen')) {
                //                 general_percentage = inputValue;
                //             }

                //         });

                //         if (isvalid) {
                //             var obj = {
                //                 courseid: courseid,
                //                 isprovision: isprovision,
                //                 general_percentage: general_percentage,
                //             }

                //             listCoursePercentage.push(obj)
                //         }
                //     });
                // } else {
                //     if (!$('#graders_percent_passing').val() || $('#graders_percent_passing').val() <= 0) {
                //         isvalid = false
                //         $('#graders_percent_passing').addClass('is-invalid');
                //         notify('error', 'Graders Passing is required!')
                //         return
                //     } else {
                //         $('#graders_percent_passing').removeClass('is-invalid');
                //     }
                // }



                if (isvalid) {
                    // var graders_passing = $('#graders_percent_passing').val().trim()
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('store.passingrate') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            // graders_percent_passing: graders_passing ?? 0,
                            description: $('#description').val(),
                            gradelevel: $('#select-level').val().join(','),
                            courses: $('#select-course').val().join(','),
                            categories: JSON.stringify(listCategory),
                            // coursepercentage: JSON.stringify(listCoursePercentage),
                            acadprog_id: $('#acadprog').val(),
                            strand: $('#select-strand').val().join(','),
                            average: FINAL_AVG
                        },
                        success: function(response) {
                            console.log(response);
                            notify(response.status, response.message)
                            // if (response.status == 'success') {
                            //     $('#description').val("")
                            // }
                            SERVER_EXAM_SETUP_ID = response.resultId
                            $('#btn_save_assessment').attr('disabled', false);
                            window.location.href =
                                `/guidance/admission/percentagesetup?page=1`;
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseJSON);
                            $('#btn_save_assessment').attr('disabled', false);
                        }
                    });
                } else {
                    $('#btn_save_assessment').attr('disabled', false);
                }

            })

        })

        function enable_timelimit() {
            var isChecked = $('#enable_duration').prop('checked');
            console.log('Checkbox checked:', isChecked);
            if (isChecked) {
                $('#timelimit_hours').prop('disabled', false)
                $('#timelimit_minutes').prop('disabled', false)
            } else {
                $('#timelimit_hours').prop('disabled', true)
                $('#timelimit_minutes').prop('disabled', true)
            }
        }

        function goBack() {
            window.history.back();
        }

        // Function to update the UI with the listCategory items
        // function updateListCategory() {
        //     console.log(listCategory)
        //     listCategory.forEach(element => {
        //         var htmlToAppend = `
    //         <div class="input-group">
    //             <input type="text" class="form-control category_name" value="${element.category}" disabled onkeyup="this.value = this.value.toUpperCase();">
    //             <input type="number" class="form-control text-right percentage" value="${element.percentage}" disabled>
    //             <span class="input-group-text">%</span>
    //             <input type="number" class="form-control text-right timelimit_hrs" value="${element.timelimit_hrs}" disabled>
    //             <span class="input-group-text">Hours</span>
    //             <input type="number" class="form-control text-right timelimit_min" value="${element.timelimit_min}" disabled>
    //             <span class="input-group-text">Minutes</span>
    //             <div class="input-group-append">
    //                 <button type="button" class="btn btn-default btn_edit" data-status="edit" data-id="${element.category}">
    //                     <i class="far fa-edit mr-1 text-info"></i>Edit
    //                 </button>
    //                 <button type="button" class="btn btn-default btn_delete" data-id="${element.category}">
    //                     <i class="far fa-trash-alt mr-1 text-danger"></i>Delete
    //                 </button>
    //             </div>
    //         </div>
    //         `;
        //         $('.list_category').append(htmlToAppend);
        //     });

        //     var selectedOptions = $('#select-course').find('option:selected');

        //     var courses = selectedOptions.map(function() {
        //         return {
        //             id: $(this).val(),
        //             text: $(this).text()
        //         };
        //     }).get();

        //     var nonEmptyCourses = courses.filter(course => course.value !== '');
        //     filteredCourses = nonEmptyCourses;
        //     console.log(filteredCourses);
        //     // console.log(courses);
        //     onChangeCourse(nonEmptyCourses);

        //     // Attach click event handler for delete buttons
        //     $('.btn_delete').on('click', function() {
        //         var idToDelete = $(this).attr('data-id');
        //         listCategory = listCategory.filter(item => item.category !== idToDelete);
        //         $('.list_category').empty();
        //         updateListCategory();
        //         var selectedOptions = $('#select-course').find('option:selected');
        //         var courses = selectedOptions.map(function() {
        //             return {
        //                 id: $(this).val(),
        //                 text: $(this).text()
        //             };
        //         }).get();
        //         onChangeCourse(courses.filter(course => course.value !== ''))
        //     });
        // }

        function updateTableCategory() {
            var AVERAGE = 0;
            FINAL_AVG = 0
            $('#table_categories').empty();
            $('#average_passing_rate').text('');
            if (listCategory.length > 0) {
                listCategory.forEach((element, key) => {
                    AVERAGE += (element.percentage / 100);

                    var htmlToAppend = `
                            <tr>
                                <td><span> ${key + 1}.</span></td>
                                <td><span style="font-weight: 600;"> ${element.category} </span></td>
                                <td>${element.percentage}%</td>
                                <td>${element.timelimit_hrs}hrs ${element.timelimit_min}min</td>
                                <td>${element.total_items}</td>
                                <td class="text-center" >${element.required ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' }</td>
                                
                                <td class="text-center">
                                    <div class="btn-group" style="padding:0px">
                                        <button type="button" class="btn btn-default btn_edit_category btn_custom_group" data-status="edit" data-id="${element.category}">
                                            <i class="far fa-edit mr-1 text-info"></i> 
                                        </button>
                                        <button type="button" class="btn btn-default btn_delete_category btn_custom_group" data-id="${element.category}">
                                            <i class="far fa-trash-alt mr-1 text-danger"></i> 
                                        </button>
                                    </div>
                                    
                                </td>
                                 
                            </tr>
                            `;
                    $('#table_categories').append(htmlToAppend);
                });
                FINAL_AVG = (AVERAGE / listCategory.length * 100).toFixed(2);

            }

            $('#average_passing_rate').html(`${FINAL_AVG} %`)

            $('.btn_delete_category').on('click', function() {
                var idToDelete = $(this).attr('data-id');
                listCategory = listCategory.filter(item => item.category !== idToDelete);
                $('#table_categories').empty();
                updateTableCategory();
                $('#average_passing_rate').text('');
            });

            $('.btn_edit_category').on('click', function() {
                var idToEdit = $(this).attr('data-id');
                var itemToEdit = listCategory.find(item => item.category == idToEdit);
                $('#edit_criteria_name').val(itemToEdit.category)
                $('#edit_criteria_percent').val(itemToEdit.percentage)
                $('#edit_total_items').val(itemToEdit.total_items)
                $('#edit_isrequired').prop('checked', itemToEdit.required ? true : false);
                $('#edit_timelimit_hours').val(itemToEdit.timelimit_hrs)
                $('#edit_timelimit_minutes').val(itemToEdit.timelimit_min)
                $('#modalEditCategory').modal();

            });

        }

        function handleCheckboxChange(checkbox) {
            const rowId = checkbox.data('id');
            const newIsActive = checkbox.prop('checked');
            console.log(newIsActive, rowId)
            $.ajax({
                url: '/guidance/activatePassingrate', // Replace with your server endpoint
                method: 'GET',
                data: {
                    id: rowId,
                    isactive: newIsActive ? 1 : 0
                },
                success: function(response) {
                    // Handle successful response from the server if needed
                    console.log('Server updated successfully');
                    notify(response.status, response.message)
                    getAllAdmissionSetup()
                },
                error: function(xhr, status, error) {
                    // Handle error response from the server if needed
                    console.error('Error updating server:', error);
                }
            });
        }

        function getAllAdmissionSetup() {
            $.ajax({
                type: "GET",
                url: '{{ route('get.all.setups') }}',
                success: function(response) {
                    console.log(response)
                    load_category_datatable(response.jsonCategories);
                    load_examdates_datatable(response.jsonExamDates);
                    load_diagnostictest_datatable(response.jsonDiagnosticTest);
                    $("input[data-bootstrap-switch]").each(function() {
                        $(this).bootstrapSwitch('state', $(this).prop('checked'));

                        $(this).on('switchChange.bootstrapSwitch', function(event, state) {
                            // Call handleCheckboxChange function when checkbox state changes
                            handleCheckboxChange($(this));
                        });
                    })
                }
            })
        }

        function generate_headers() {
            $('.container_categoryhead').empty()
            $('.container_categoryhead').append(`<th></th>`)
            // listCategory.map(each => {
            //     if (each.hastest) {
            //         $('.container_categoryhead').append(`
        //             <th class=" p-1">${each.category}</th>`)
            //     }
            // });
            // $('.container_categoryhead').append(`<th class=" p-1">GEN%</th>`)
            // $('.container_categoryhead').append(`<th class=" p-1">PROBATION</th>`)
            // onChangeCourse();
        }

        function onChangeCourse(ll) {
            $('#tbl_listCoursePercentage').empty();
            // generate_headers();

            if (ll.length === 0) {
                return;
            }

            // ll.forEach(element => {
            //     if (element !== '') {
            //         var renderHtml = `
        //             <tr>
        //                 <td style="white-space:nowrap;">${element.text}</td>
        //                 <td hidden> <input id="${element.id}" class="courseid" value="${element.id}"> </td>
        //                 ${listCategory.map(elem => `<td> <input class="form-control percent" hidden-info="${elem.category}" type="number" placeholder="90%-99%"></td>`).join('')}
        //                 <td><input class="form-control input_gen" type="number" placeholder="90%-99%"></td>
        //                 <td style="text-align: center">
        //                     <div class="icheck-success d-inline">
        //                         <input type="checkbox" id="isprovision_${element.id}" class="isprovision">
        //                          <label for="isprovision_${element.id}"></label>
        //                     </div>
        //                 </td>
        //             </tr>
        //         `;
            //         $('#tbl_listCoursePercentage').append(renderHtml);
            //     }
            // });

            ll.forEach(element => {
                if (element !== '') {
                    var renderHtml = `
                        <tr>
                            <td style="white-space:nowrap;"> <strong> ${element.text} </strong> </td>
                            <td hidden> <input id="${element.id}" class="courseid" value="${element.id}"> </td>
                            <td><input class="form-control input_gen" type="number" placeholder="90%-99%"></td>
                            <td style="text-align: center">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" id="isprovision_${element.id}" class="isprovision">
                                     <label for="isprovision_${element.id}"></label>
                                </div>
                            </td>
                        </tr>
                    `;
                    $('#tbl_listCoursePercentage').append(renderHtml);
                }
            });


            // Assuming you want to bind the change event to the checkboxes
            $('.isprovision').on('change', function() {
                // Handle checkbox change here
                var isChecked = $(this).prop('checked');
                console.log('Checkbox checked:', isChecked);
            });
        }
    </script>
@endsection
