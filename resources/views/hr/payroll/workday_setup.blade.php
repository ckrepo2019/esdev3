@extends('hr.layouts.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <section class="content-header p-0" style="padding-top: 15px!important;">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Workday</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Workday</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    @php
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;
    @endphp
    <style>
        .select2-container .select2-selection--single {
            height: 29px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -8px !important;
        }

        #workday_datatables_filter {
            padding-top: 10px;
        }
    </style>
    {{-- MODALS --}}
    <div class="modal fade modal_addworkday" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modal_desc">CREATE WORKDAY</span> <input type="text" id="workdayid"
                            hidden></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="workday_description">Workday Description</label>
                                <input type="text" class="form-control" id="workday_description"
                                    aria-describedby="descriptionHelp" placeholder="Enter Description"
                                    oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table width="100%" class="table table-bordered text-center" style="table-layout: fixed;">
                                    <thead>
                                        <tr>
                                            <th width="8%">Day</th>
                                            <th width="20%">Status</th>
                                            <th width="18%">AM In</th>
                                            <th width="18%">AM Out</th>
                                            <th width="18%">PM In</th>
                                            <th width="18%">PM Out</th>
                                        </tr>
                                    </thead>
                                    <tbody id="scheduleTable"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-primary btn-sm" id="btn_saveworkday"><i class="fas fa-plus"></i>
                        Add</button>
                    <button type="button" class="btn btn-success btn-sm" id="btn_updateworkday" hidden><i
                            class="fas fa-save"></i> Save</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL --}}

    <section class="content">
        <div class="card border-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table width="100%" class="table table-sm table-bordered table-head-fixed" id="workday_datatables"
                            style="font-size: 16px;">
                            <thead>
                                <tr>
                                    <th width="90%">Description</th>
                                    <th class="text-center" width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('footerjavascript')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var workdays = []

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            });

            // variable declarations and function call
            load_workday()
            $('.days').select2()

            // Modal Close
            $('.modal_addworkday').on('hidden.bs.modal', function() {
                $('#workday_description').val('')
                $('.modal_addworkday').find('input').val('')
                $('#scheduleTable').empty()
            });

            // Events
            $(document).on('click', '#btn_addworkday', function() {
                workday_select()
                $('#modal_desc').text('CREATE WORKDAY')
                $('#btn_updateworkday').prop('hidden', true);
                $('#btn_saveworkday').prop('hidden', false);
                $('.modal_addworkday').modal('show')
            })

            $(document).on('change', '.days', function() {
                const selectedDay = $(this).attr('id').split('_')[0];
                const dayType = $(this).val();

                updateTimeFields(selectedDay, dayType);
                Toast.fire({
                    type: 'info',
                    title: `${selectedDay.charAt(0).toUpperCase() + selectedDay.slice(1)} updated to ${$(this).find('option:selected').text()}`,
                });
            });

            $(document).on('click', '#btn_saveworkday ', function() {
                const scheduleData = {};
                const description = $('#workday_description').val();

                // Gather schedule data
                const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                days.forEach(day => {
                    scheduleData[day] = {
                        dayType: $(`#${day}_select`).val(),
                        amin: $(`#${day}_amin_time`).val() || null,
                        amout: $(`#${day}_amout_time`).val() || null,
                        pmin: $(`#${day}_pmin_time`).val() || null,
                        pmout: $(`#${day}_pmout_time`).val() || null,
                        daydesc: day.charAt(0).toUpperCase() + day.slice(1),
                        dayType: $(`#${day}_select`).val()
                    };
                });

                $.ajax({
                    url: '/hr/setup/store_workday',
                    method: 'GET',
                    data: {
                        schedule: scheduleData,
                        description: description
                    },
                    success: function(data) {
                        load_workday()
                        Toast.fire({
                            type: 'success',
                            title: 'Workday Saved Successfully!'
                        });
                    },
                    error: function(error) {
                        Toast.fire({
                            type: 'error',
                            title: 'Failed to save workday!'
                        });
                    }
                });
            });

            $(document).on('click', '#btn_editworkday', function() {
                workday_select();
                var id = $(this).attr('data-id')
                $('.modal_addworkday').modal('show');
                $('#modal_desc').text('EDIT WORKDAY');
                $('#btn_updateworkday').prop('hidden', false);
                $('#btn_saveworkday').prop('hidden', true);
                $('#workdayid').val(id);

                const dataid = $(this).attr('data-id');

                $.ajax({
                    url: '/hr/setup/edit_workday',
                    method: 'GET',
                    data: {
                        id: dataid
                    },
                    success: function(data) {
                        if (data) {
                            $('#workday_description').val(data.description);

                            // Days of the week
                            const days = ['monday', 'tuesday', 'wednesday', 'thursday',
                                'friday', 'saturday', 'sunday'
                            ];
                            days.forEach(day => {
                                $(`#${day}_select`).val(data[day]).trigger('change');

                                // AM and PM times
                                $(`#${day}_amin_time`).val(data[`${day}_amin`]);
                                $(`#${day}_amout_time`).val(data[`${day}_amout`]);
                                $(`#${day}_pmin_time`).val(data[`${day}_pmin`]);
                                $(`#${day}_pmout_time`).val(data[`${day}_pmout`]);
                            });

                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'No data found!'
                            });
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed to load data!'
                        });
                    }
                });
            });

            $(document).on('click', '#btn_updateworkday ', function() {
                const scheduleData = {};
                const description = $('#workday_description').val();
                const workdayid = $('#workdayid').val();

                // Gather schedule data
                const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                days.forEach(day => {
                    scheduleData[day] = {
                        dayType: $(`#${day}_select`).val(),
                        amin: $(`#${day}_amin_time`).val() || null,
                        amout: $(`#${day}_amout_time`).val() || null,
                        pmin: $(`#${day}_pmin_time`).val() || null,
                        pmout: $(`#${day}_pmout_time`).val() || null,
                        daydesc: day.charAt(0).toUpperCase() + day.slice(1),
                        dayType: $(`#${day}_select`).val()
                    };
                });

                $.ajax({
                    url: '/hr/setup/update_workday',
                    method: 'GET',
                    data: {
                        schedule: scheduleData,
                        description: description,
                        workdayid: workdayid
                    },
                    success: function(data) {
                        load_workday()
                        Toast.fire({
                            type: 'success',
                            title: 'Workday Saved Successfully!'
                        });
                    },
                    error: function(error) {
                        Toast.fire({
                            type: 'error',
                            title: 'Failed to save workday!'
                        });
                    }
                });
            });

            $(document).on('click', '#btn_deleteworkday', function() {
                var id = $(this).attr('data-id')

                Swal.fire({
                    title: 'Are you sure you want to remove Workday?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Remove'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/hr/setup/delete_workday',
                            method: 'GET',
                            data: {
                                id: id
                            },
                            success: function(data) {
                                load_workday()
                                Toast.fire({
                                    type: 'success',
                                    title: 'Workday Deleted Successfully!'
                                });
                            },
                            error: function(error) {
                                Toast.fire({
                                    type: 'error',
                                    title: 'Failed to delete workday!'
                                });
                            }
                        });
                    }
                })
            });

            // Functions
            function workday_select() {
                // Days of the week
                const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

                // Reference to table body
                const scheduleTable = document.getElementById('scheduleTable');

                // Generate rows dynamically
                days.forEach(day => {
                    scheduleTable.innerHTML += `
                    <tr>
                        <td>${day.charAt(0)}${day.charAt(1)}${day.charAt(2)}</td>
                        <td>
                            <select class="form-control days form-control-sm" id="${day.toLowerCase()}_select" name="${day.toLowerCase()}_select">
                                <option value="1" selected>Full Day</option>
                                <option value="2">Half AM</option>
                                <option value="3">Half PM</option>
                                <option value="0">Day Off</option>
                            </select>
                        </td>
                        <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_amin_time" name="${day.toLowerCase()}_amin_time" value="08:00"></td>
                        <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_amout_time" name="${day.toLowerCase()}_amout_time" value="12:00"></td>
                        <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_pmin_time" name="${day.toLowerCase()}_pmin_time" value="13:00"></td>
                        <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_pmout_time" name="${day.toLowerCase()}_pmout_time" value="17:00"></td>
                    </tr>
                `;
                });
            }


            function updateTimeFields(day, dayType) {
                const morningIn = $(`#${day}_amin_time`);
                const morningOut = $(`#${day}_amout_time`);
                const afternoonIn = $(`#${day}_pmin_time`);
                const afternoonOut = $(`#${day}_pmout_time`);

                if (dayType === '1') {
                    morningIn.val('08:00').prop('disabled', false).css('pointer-events', 'auto');
                    morningOut.val('12:00').prop('disabled', false).css('pointer-events', 'auto');
                    afternoonIn.val('13:00').prop('disabled', false).css('pointer-events', 'auto');
                    afternoonOut.val('17:00').prop('disabled', false).css('pointer-events', 'auto');

                } else if (dayType === '2') {
                    morningIn.val('08:00').prop('disabled', false).css('pointer-events', 'auto');
                    morningOut.val('12:00').prop('disabled', false).css('pointer-events', 'auto');
                    afternoonIn.val('').prop('disabled', true).css('pointer-events', 'none');
                    afternoonOut.val('').prop('disabled', true).css('pointer-events', 'none');

                } else if (dayType === '3') {
                    morningIn.val('').prop('disabled', true).css('pointer-events', 'none');
                    morningOut.val('').prop('disabled', true).css('pointer-events', 'none');
                    afternoonIn.val('13:00').prop('disabled', false).css('pointer-events', 'auto');
                    afternoonOut.val('17:00').prop('disabled', false).css('pointer-events', 'auto');

                } else {
                    morningIn.val('').prop('disabled', true).css('pointer-events', 'none');
                    morningOut.val('').prop('disabled', true).css('pointer-events', 'none');
                    afternoonIn.val('').prop('disabled', true).css('pointer-events', 'none');
                    afternoonOut.val('').prop('disabled', true).css('pointer-events', 'none');
                }
            }

            // Load Functions
            function load_workday() {
                $.ajax({
                    type: "GET",
                    url: "/hr/setup/load_all_workday",
                    success: function(data) {
                        workdays = data
                        workday_datatables()
                    }
                });
            }

            // datatable Functions
            function workday_datatables() {
                $('#workday_datatables').DataTable({
                    destroy: true,
                    lengthChange: false,
                    scrollX: true,
                    autoWidth: true,
                    order: false,
                    data: workdays,
                    columns: [{
                            "data": 'description'
                        },
                        {
                            "data": null
                        }
                    ],
                    columnDefs: [{
                            'targets': 0,
                            'orderable': false,
                            createdCell: function(td, cellData, rowData, row, col) {

                                var content = '<span>' + rowData.description + '</span>';

                                $(td).html(content);
                                $(td).addClass('text-left align-middle');
                                $(td).css('padding', '0 !important');
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            createdCell: function(td, cellData, rowData, row, col) {
                                var content =
                                    `<button class="btn btn-primary btn-sm" id="btn_editworkday" data-id="` +
                                    rowData.id + `"><i class="fas fa-pencil-alt"></i></button>
                                    
                                    <button class="btn btn-danger btn-sm" id="btn_deleteworkday" data-id="` +
                                    rowData.id + `"><i class="fas fa-trash-alt"></i></button>`;

                                $(td).html(content);
                                $(td).addClass('text-center align-middle');
                                $(td).css('padding', '0 !important');
                            }
                        }

                    ]
                });

                var label_text = $($('#workday_datatables_wrapper')[0].children[0])[0].children[0]
                $(label_text)[0].innerHTML =
                    '<button class="btn btn-primary btn-sm" id="btn_addworkday"><i class="fas fa-plus"></i> Create Workday</button>'
            }

        })
    </script>
@endsection
