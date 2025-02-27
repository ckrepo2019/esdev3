@extends('admission.layouts.test')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v3.10.2/main.min.css') }}" />
    <style>
        .list_style li {
            list-style: circle !important;
        }

        .align-middle td {
            vertical-align: middle;
        }

        .radius-custom-header {
            color: white;
            border-top-left-radius: .0rem !important;
            border-top-right-radius: .0rem !important;
        }

        .copy-button-class {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .copy-button-class:hover {
            color: #fff;
            background-color: #0056b3;
            border-color: #004085;
        }

        .copy-button-class:focus,
        .copy-button-class.focus {
            color: #fff;
            background-color: #0056b3;
            border-color: #004085;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
        }

        .copy-button-class:disabled,
        .copy-button-class.disabled {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
@endsection

@section('content')
    <div class="content-header">
    </div>

    <div class="content mt-4">
        <div class="container">
            <div class="card shadow">
                <div class="ribbon-wrapper ribbon-lg">
                    <div class="ribbon bg-warning text-md">ADMISSION</div>
                </div>
                <div class="card-header bg-custom-light-grey bg-secondary" style="font-size: 18px; color: #000000;">
                    PRE-REGISTRATION FORM <span class="badge badge-warning ml-2" id="preregStatus"></span>
                </div>
                <form id="regForm" action="{{ route('student.info.save') }}" method="POST" enctype="multipart/form-data"
                    autocomplete="off">
                    @csrf
                    <div class="card-body p-0">
                        <div class="card shadow-none mb-0">
                            <div class="card-header radius-custom-header" style="font-size: 17px; color: #000000;">
                                <i class="fas fa-layer-group" style="padding-right: 5px;"></i>STUDENT INFORMATION
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="mb-1">Pre-registration Type <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="preregistrationType" name="prereg_status"
                                            style="width: 100%;">
                                            <option value="Continuing Student">CONTINUING STUDENT</option>
                                            <option value="New Student" selected>NEW STUDENT</option>
                                            <option value="Transferee">TRANSFEREE</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">First Name <span class="text-danger">*</span> </label>
                                        <input type="text" class="form-control"
                                            onkeyup="this.value = this.value.toUpperCase();" id="fname" name="fname"
                                            required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>First Name is required!</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">Middle Name </label>
                                        <input type="text" class="form-control"
                                            onkeyup="this.value = this.value.toUpperCase();" id="mname" name="mname">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">Last Name <span class="text-danger">*</span> </label>
                                        <input type="text" class="form-control"
                                            onkeyup="this.value = this.value.toUpperCase();" id="lname" name="lname"
                                            required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Last Name is required!</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">Suffix</label>
                                        <select class="form-control" id="suffix" name="suffix" style="width: 100%;">
                                            <option value="">Select Suffix</option>
                                            <option value="Jr.">Jr.</option>
                                            <option value="II">II</option>
                                            <option value="III">III</option>
                                            <option value="IV">IV</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">Date of Birth<span class="text-danger">*</span> </label>
                                        <input type="date" class="form-control" id="dob" name="dob" required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Date of birth is required!</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">Gender <span class="text-danger">*</span> </label>
                                        <select class="form-control" id="select-gender" name="gender" style="width: 100%;"
                                            required>
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Gender is required!</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">Age<span class="text-danger">*</span> </label>
                                        <input type="number" min="0" class="form-control" id="age"
                                            name="age" required readonly>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Age is required!</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1"><b>Phone Number <span
                                                    class="text-danger">*</span></b></label>
                                        <input class="form-control" placeholder="09XX-XXXX-XXXX " name="contact_number"
                                            id="contact_number" minlength="13" maxlength="13" autocomplete="off"
                                            required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong id="mobileError">Mobile number is required</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-control-label"><b>Address <span
                                                    class="text-danger">*</span></b></label>
                                        <input type="text" class="form-control" placeholder="Present address"
                                            name="present_address" autocomplete="off" required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong> Address is required</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-control-label"><b>Email Address <span
                                                    class="text-danger">*</span></b></label>
                                        <input type="email" class="form-control" placeholder="Email address"
                                            name="email" autocomplete="off" required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong id="emailError">Email Address is required</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-none mb-0">
                            <div class="card-header radius-custom-header"
                                style="font-size: 17px; color: #000000; border-top: 1px solid #d3d3d3;">
                                <i class="fas fa-layer-group" style="padding-right: 5px;"></i>SCHOOL INFORMATION
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label class="mb-1">Last School Attended <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="last_school_attended"
                                            name="last_school_attended" required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Last School Attended is required!</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1"> Grade Level Completed <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="last_grade_level_completed"
                                            name="last_grade_level_completed" required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Last Grade Level Completed is required!</strong>
                                        </span>
                                    </div>

                                    <div class="form-group col-md-3 last_course_completed_container">
                                        <label class="mb-1">Course/Strand (Optional)
                                        </label>
                                        <input type="text" class="form-control" id="last_course_completed"
                                            name="last_course_completed">
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Last Course/Strand Completed is required!</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="mb-1"> Last School Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="last_school_mailing_address"
                                            name="last_school_mailing_address" required>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Last School Address is required!</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="mb-1"> Academic Program <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control" id="acadprog" name="acadprog_id"
                                            style="width: 100%;" required>
                                            <option value=""> Select AcadProg</option>
                                            @foreach (DB::table('academicprogram')->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->progname }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Academic Program is required!</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="mb-1">Level to Enroll <span class="text-danger">*</span> </label>
                                        <select class="form-control" id="select-level" name="gradelevel_id"
                                            style="width: 100%;" required>
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Level to Enroll is required!</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-4" id="course-wrapper" hidden>
                                        <label class="mb-1"> Desired Course <span class="text-danger">*</span> </label>
                                        <select class="form-control" id="select-course" name="course_id"
                                            style="width: 100%;" required>
                                            <option value="">Select Course</option>
                                            @foreach (DB::table('college_courses')->where('deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->courseDesc }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Desired Course is required!</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-4" id="strand-wrapper" hidden>
                                        <label class="mb-1"> Desired Strand <span class="text-danger">*</span> </label>
                                        <select class="form-control" id="select-strand" name="strand_id"
                                            style="width: 100%;" required>
                                            <option value="">Select Strand</option>
                                            @foreach (DB::table('sh_strand')->where('deleted', 0)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->strandname }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Desired Strand is required!</strong>
                                        </span>
                                    </div>
                                    <div class="col-12 my-3"></div>
                                    <div class="form-group col-md-6">
                                        <label class="mb-1">Select Exam Setup <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control" id="select-exam" name="exam_setup_id"
                                            style="width: 100%;" required>
                                            <option value="">Select Exam</option>
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Exam Setup is required!</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="mb-1">Examination Date <span class="text-danger">*</span> </label>
                                        <select class="form-control" id="select-examdate" name="examdate_id"
                                            style="width: 100%;" required>
                                            <option value="">Select ExamDate</option>
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Examination Date is required!</strong>
                                        </span>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-1">Campus</label>
                                            <input type="text" class="form-control" id="venue"
                                                onkeyup="this.value=this.value.toUpperCase();" placeholder="Enter Campus"
                                                disabled>
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Venue is required!</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-1">Available Slot</label>
                                            <input type="number" min="0" class="form-control" id="takers"
                                                placeholder="" disabled>
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Participant is required!</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-1">Building</label>
                                            <input type="text" class="form-control" id="building"
                                                onkeyup="this.value=this.value.toUpperCase();"
                                                placeholder="Enter Building" disabled>
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Building is required!</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-1">Room</label>
                                            <input type="text" class="form-control" id="room"
                                                onkeyup="this.value=this.value.toUpperCase();" placeholder="Enter Room"
                                                disabled>
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Room is required!</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex">
                        <button type="submit" class="btn btn-primary ml-auto" id="btn_submit"><i
                                class="far fa-paper-plane mr-1"></i>Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <!-- fullCalendar 2.2.5 -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-v3.10.2/main.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            initCalendar()

            $('#preregStatus').text($('#preregistrationType').val())

            $('#preregistrationType').select2({
                theme: 'bootstrap4',
                placeholder: 'Select Preregistration Type'
            })

            $('#preregistrationType').on('change', function() {
                $('#preregStatus').text($(this).val())
            })

            $('#select-exam').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Exam Setup'
            })

            $('#select-level').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Level'
            })

            $('#suffix').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Suffix'
            })

            $('#select-course').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Course'
            })

            $('#select-strand').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Strand'
            })

            $('#acadprog').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select AcadProg'
            })

            $('#select-level').on('change', function() {
                filter_examsetup()
            })
            $('#select-course').on('change', function() {
                filter_examsetup()
            })
            $('#select-exam').on('change', function() {
                filter_examdates()
            })
            $('#select-examdate').on('change', function() {
                find_examdate()
            })
            $('#acadprog').on('change', function() {
                var id = $(this).val()
                console.log(id);
                $('#course-wrapper').prop('hidden', true);
                if (id > 0) {
                    if (id == 6) {
                        $('#course-wrapper').prop('hidden', false);
                        $('#strand-wrapper').prop('hidden', true);
                        console.log('Selected acadprog ' + id + ' is college');
                    } else if (id == 5) {
                        $('#select-course').val("").change();
                        $('#course-wrapper').prop('hidden', true);
                        $('#strand-wrapper').prop('hidden', false);
                        console.log('Selected acadprog ' + id + ' is SHS');
                    } else {
                        $('#select-course').val("").change();
                        $('#course-wrapper').prop('hidden', true);
                        $('#strand-wrapper').prop('hidden', true);
                        console.log('Selected acadprog ' + id + ' is not college or SHS');
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
                            filter_examsetup()

                        }
                    })
                }
            });

            $("#contact_number").inputmask({
                mask: "9999-999-9999"
            });

            $('#select-gender').select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Gender'
            })

            $('#select-examdate').empty().select2({
                allowClear: true,
                theme: 'bootstrap4',
                placeholder: 'Select Exam Date'
            })

            $('#dob').on('change', function() {
                var dob = $(this).val();
                calculate_age(dob)
            })


            $('#regForm input, #regForm select').on('click change', function() {
                $(this).removeClass('is-invalid');
            });

            $(document).on('click', '#btn_submit', function(e) {
                e.preventDefault(); // Prevent the default form submission
                var isvalid = true;

                if ($('#acadprog').val() == 6) {
                    $('#select-course').attr('required', true);
                    $('#select-strand').attr('required', false);
                } else if ($('#acadprog').val() == 5) {
                    $('#select-strand').attr('required', true);
                    $('#select-course').attr('required', false);
                } else {
                    $('#select-course').attr('required', false);
                    $('#select-strand').attr('required', false);
                }

                // Iterate through each required input field
                $('#regForm').find('input[required], select[required]').each(function() {
                    var $this = $(this);
                    if (!$this.val()) {
                        isvalid = false;
                        $this.addClass('is-invalid');
                        notify('error', $this.siblings('.invalid-feedback').text().trim());
                    } else {
                        $this.removeClass('is-invalid');
                    }
                });

                if (isvalid) {
                    var $btn = $(this); // Cache the button element
                    $btn.prop('disabled', true); // Disable the button to prevent duplication

                    var formdata = new FormData($('#regForm')[0]);
                    $.ajax({
                        url: $('#regForm').attr('action'),
                        type: 'POST',
                        data: formdata,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            // Handle success response
                            $btn.prop('disabled',
                                false); // Re-enable the button after the request is finished
                            if (response.status == "success") {
                                $('#regForm')[0].reset();
                            }
                            console.log(response);
                            notify(response.status, response.message);
                            dialogue_pooling(response.poolingnumber);
                        },
                        error: function(xhr, status, error) {
                            // Handle error
                            console.log(xhr.responseText);
                            $btn.prop('disabled',
                                false); // Re-enable the button after the request is finished

                            try {
                                var response = JSON.parse(xhr
                                    .responseText); // Try parsing the JSON response
                                if (response && response.errors) {
                                    // Access the errors object and display the messages
                                    var errors = response.errors;
                                    // Iterate through the errors object and display the messages
                                    for (var key in errors) {
                                        if (errors.hasOwnProperty(key)) {
                                            var errorMessage = errors[key][
                                                0
                                            ]; // Get the first error message for each field
                                            console.error(
                                                errorMessage
                                            ); // Log the error message to the console
                                            notify('error', errorMessage);
                                            return;
                                        }
                                    }
                                } else {
                                    console.error('No errors found in the response.');
                                }
                            } catch (e) {
                                console.error('Error parsing JSON response:', e);
                            }
                        }
                    });
                }
            });


        })

        function calculate_age(params) {
            var dob = new Date(params);
            var today = new Date();
            var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
            $('#age').val(age);
        }

        function filter_examsetup() {
            $('#select-examdate').empty()
            $.ajax({
                type: 'GET',
                data: {
                    id: $('#acadprog').val(),
                    levelid: $('#select-level').val(),
                    courseid: $('#select-course').val()
                },
                url: '{{ route('filter.examsetup') }}',
                success: function(data) {
                    console.log(data);
                    $('#select-exam').empty()
                    $('#select-exam').select2({
                        data: data,
                        allowClear: true,
                        theme: 'bootstrap4',
                        placeholder: 'Select Exam Setup'
                    })
                    filter_examdates()
                }
            })
        }

        function filter_examdates() {
            $('#select-examdate').empty()
            if ($('#select-exam').val()) {
                $.ajax({
                    type: 'GET',
                    data: {
                        id: $('#acadprog').val(),
                        examid: $('#select-exam').val(),
                    },
                    url: '{{ route('filter.examdate') }}',
                    success: function(data) {
                        console.log(data);
                        $('#select-examdate').empty().select2({
                            data: data,
                            allowClear: true,
                            theme: 'bootstrap4',
                            placeholder: 'Select Exam Date'
                        })
                        find_examdate()
                    }
                })
            }
        }

        function find_examdate() {
            console.log($('#select-examdate').val());
            if ($('#select-examdate').val()) {
                $.ajax({
                    type: 'GET',
                    data: {
                        id: $('#select-examdate').val()
                    },
                    url: '{{ route('find.examdate') }}',
                    success: function(data) {
                        console.log(data);
                        $('#venue').val(data.venue)
                        $('#takers').val(data.available)
                        $('#building').val(data.building)
                        $('#room').val(data.room)
                    }
                })
            } else {
                $('#venue').val('')
                $('#takers').val('')
                $('#building').val('')
                $('#room').val('')
            }
        }

        function initCalendar() {
            var calendarEl = $('#calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                contentHeight: 'auto', // Set the height of the content area (events, day cells)
                initialView: 'dayGridMonth',
                selectable: true, // Enable selection
                select: function(info) {
                    handleDateClick(info)
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [{
                        title: 'Booked',
                        start: '2024-04-05',
                        end: '2024-04-05',
                        color: 'red'
                    },
                    {
                        title: 'Available',
                        start: '2024-04-08',
                        end: '2024-04-08',
                        color: 'green'
                    },
                    // Add more events as needed
                ],

            });
            calendar.render();
        }

        function handleDateClick(info) {
            // console.log("Selected Cell:", info);
            const timestamp = info._i; // Assuming this is your timestamp
            const date = new Date(timestamp);
            const realDate = date.toISOString().split('T')[0];
            console.log(realDate);
            var events = calendar.clientEvents()
            const eventsOnClickedDate = events.filter(event => {
                // console.log(event)
                // console.log(event.start._i)
                if (event.start._i == realDate) {
                    var title = event.title;
                    // console.log(event.title);
                    // console.log(event.color);
                    if (title == 'Available') {
                        console.log('Available!')
                        available_dialogue(realDate)
                    } else {
                        console.log('Sorry Fully Booked!')
                        fullybook_dialogue()
                    }
                }
            });
        }

        function available_dialogue(date) {
            Swal.fire({
                type: 'success',
                title: 'Are you sure to booked this date ?',
                text: `Please ensure you attend on the designated date assigned to you.`,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.value) {
                    $('#examdate').val(date)
                    notify('success', 'Selected Successfully!')
                }
            });
        }

        function fullybook_dialogue() {
            Swal.fire({
                type: 'warning',
                title: 'I\'m sorry, but this slot is currently fully booked!',
                text: `Please choose another date.`,
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Okay'
            })

        }

        function dialogue_pooling(pooling) {
            if (!pooling) {
                return
            }

            Swal.fire({
                type: 'success',
                title: pooling,
                text: `Remember to copy and save your Pooling Number to be used for the Entrance Examination.`,
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Okay',
                allowOutsideClick: false, // Prevents closing when clicking outside
                backdrop: 'static',
                customClass: {
                    confirmButton: 'copy-button-class' // Custom class for the confirm button
                },
                buttonsStyling: false, // Disable default button styling
                onOpen: (modal) => {
                    // Add event listener to the custom copy button
                    modal.querySelector('.copy-button-class').addEventListener('click', () => {
                        // Create a hidden textarea element
                        const textarea = document.createElement('textarea');
                        textarea.value = pooling; // Set the text to be copied
                        textarea.setAttribute('readonly',
                            ''); // Make it readonly to prevent focus
                        textarea.style.position = 'absolute';
                        textarea.style.left = '-9999px'; // Move off-screen
                        document.body.appendChild(textarea);

                        // Select and copy the text
                        textarea.select();
                        document.execCommand('copy');

                        // Clean up
                        document.body.removeChild(textarea);

                        // Show a message to the user that the text has been copied
                        Swal.fire({
                            type: 'success',
                            title: 'Copied!',
                            text: 'The Code has been copied to the clipboard.',
                            showConfirmButton: false,
                            timer: 1500 // Auto-close the success message after 1.5 seconds
                        });
                    });
                }
            });
        }
    </script>
@endsection
