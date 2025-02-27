@extends($extends . '.layouts.app')

<style>
    .dataTable {
        font-size: 80%;
    }

    .tschoolschedule .card-body {
        height: 250px;
    }

    .tschoolcalendar {
        font-size: 12px;
    }

    .tschoolcalendar .card-body {
        height: 250px;
        overflow-x: scroll;
    }

    .teacherd ul li a {
        color: #fff;
        -webkit-transition: .3s;
    }

    .teacherd ul li {
        -webkit-transition: .3s;
        border-radius: 5px;
        background: rgba(173, 177, 173, 0.3);
        margin-left: 2px;
    }

    .sf5 {
        background: rgba(173, 177, 173, 0.3) !important;
        border: none !important;
    }

    .sf5menu a:hover {
        background-color: rgba(173, 177, 173, 0.3) !important;
    }

    .teacherd ul li:hover {
        transition: .3s;
        border-radius: 5px;
        padding: none;
        margin: none;
    }

    .small-box {
        box-shadow: 1px 2px 2px #001831c9;
        overflow-y: auto scroll;
    }

    .small-box h5 {
        text-shadow: 1px 1px 2px gray;
    }

    img {
        border-radius: unset !important;
    }

    .select2-container .select2-selection--single {
        height: 40px;
    }

    th {
        padding: 3px !important;
    }

    td {
        padding: 2px !important;
    }

    .shadow {
        box-shadow: 0rem 0rem rgba(0, 0, 0, 0.05) !important;
        border: 0 !important;
    }
</style>
@section('content')
    @php
        use Carbon\Carbon;
        $now = Carbon::now();
        $comparedDate = $now->toDateString();
    @endphp

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Appointments</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Appointments</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">

        <!-- Default box -->
        <div class="card shadow">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <label>Search</label>
                        <input type="text" class="form-control" id="filtersearch" placeholder="eg. date, name" />
                    </div>
                    <div class="col-md-4">
                        <label>Date range</label>
                        <input type="text" class="form-control" id="input-daterange" />
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label><br />
                        <button class="btn btn-primary" id="btn-filter"><i class="fa fa-sync"></i> Filter</button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects text-center" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th style="width: 8%;">
                                #
                            </th>
                            <th style="width: 20%;">
                                Patient
                            </th>
                            <th style="width: 15%;">
                                Date & Time
                            </th>
                            <th style="width: 20%;">
                                Description
                            </th>
                            <th class="text-center" style="width: 10%;">
                                Status
                            </th>
                            <th style="width: 27%;">
                                Assigned Doctor
                            </th>
                            <th style="width: 27%;">
                                Accept
                            </th>
                        </tr>
                    </thead>
                    <tbody id="resultscontainer">
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
@endsection
@section('footerjavascript')
    <script>
        //$('body').addClass('sidebar-collapse')
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
            $('#input-daterange').daterangepicker()
        })
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        // Define the debounce timer globally to ensure it persists between function calls
        var debounceTimer;

        function filter(daterange) {
            // Clear the previous debounce timer to avoid unnecessary requests
            clearTimeout(debounceTimer);

            // Set the debounce timer with a delay of 300ms
            debounceTimer = setTimeout(function() {
                // Show the loading Swal alert
                Swal.fire({
                    type: 'info',
                    title: 'Fetching data...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading(); // Display the loading spinner
                    }
                });

                // Perform the AJAX request
                $.ajax({
                    url: '/clinic/appointment/getappointments',
                    type: 'GET',
                    data: {
                        selecteddaterange: daterange
                    },
                    success: function(data) {
                        // On success, update the results container with the new data
                        $('#resultscontainer').empty();
                        $('#resultscontainer').append(data);

                        // Close the Swal loading modal
                        Swal.close();
                    },
                    error: function() {
                        // On error, show an error Swal alert
                        Swal.fire('Error', 'Failed to fetch data', 'error');
                    },
                    complete: function() {
                        // Cleanup Swal modal and body classes after request completion
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown swal2-height-auto');
                    }
                });
            }, 300); // Debounce delay of 300ms
        }



        $(document).ready(function() {

            filter($('#input-daterange').val());

            $('#btn-filter').on('click', function() {

                var selecteddaterange = $('#input-daterange').val();
                filter(selecteddaterange);

            })

            $('#btn-create').on('click', function() {

                create();

            })

            $(document).on('click', '.btn-appointmentadmit', function() {
                var appointmentid = $(this).attr('data-id');
                var doctorschedid = 0;

                // Check if a doctor schedule is selected
                if ($(this).closest('tr').find('select').length > 0) {
                    doctorschedid = $(this).closest('tr').find('select').val();
                }

                // Split the selected value to extract ID
                var id1 = doctorschedid ? doctorschedid.split('-')[1] : '0';

                // Check if doctor ID is valid
                if (id1 == '-' || id1 == '0') {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Doctor you selected is unavailable.',
                    });
                } else {
                    Swal.fire({
                        title: 'You are going to accept this appointment.',
                        text: 'Would you like to continue?',
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Continue'
                    }).then((result) => {
                        if (result.value) {
                            // Proceed to accept the appointment
                            console.log('Accepting...');

                            $.ajax({
                                url: '/clinic/appointment/admitaccept',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    id: appointmentid,
                                    doctorschedid: doctorschedid
                                },
                                success: function(data) {
                                    if (data == 1) {
                                        Toast.fire({
                                            type: 'success',
                                            title: 'Admitted successfully!'
                                        });
                                        filter($('#input-daterange').val());
                                    } else if (data == 2) {
                                        Toast.fire({
                                            type: 'warning',
                                            title: 'Appointment is admitted already!'
                                        });
                                        filter($('#input-daterange').val());
                                    } else {
                                        Toast.fire({
                                            type: 'error',
                                            title: 'Something went wrong!'
                                        });
                                    }
                                },
                                error: function() {
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Failed to process the request!'
                                    });
                                }
                            });
                        }
                    });
                }
            });



            $(document).on('click', '.btn-appointmentcancel', function() {
                var appointmentid = $(this).attr('data-id');
                Swal.fire({
                        title: 'You are going to drop this appointment.',
                        text: 'Would you like to continue?',
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Continue'
                    })
                    .then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: '/clinic/appointment/admitcancel',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    id: appointmentid
                                },
                                success: function(data) {
                                    if (data == 1) {
                                        Toast.fire({
                                            type: 'success',
                                            title: 'Dropped successfully!'
                                        })
                                        filter($('#input-daterange').val());
                                    } else {
                                        Toast.fire({
                                            type: 'error',
                                            title: 'Something went wrong!'
                                        })
                                    }
                                }
                            })
                        }
                    })
            })
        })
    </script>
@endsection
