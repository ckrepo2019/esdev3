@php
    if (auth()->user()->type == 7) {
        $extend = 'studentPortal.layouts.app2';
    } elseif (auth()->user()->type == 9) {
        $extend = 'parentsportal.layouts.app2';
    }
@endphp
@extends($extend)

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Clearance</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Clearance</li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content pt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="info-box">
                        <div class="info-box-content">
                            <span class="info-box-number">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Clearance History</label>
                                        <div class="d-flex align-items-center">
                                            <select class="form-control form-control-sm select2 flex-grow-1"
                                                id="filter_clearancehistory"></select>
                                            <div class="ml-2 flex-shrink-0">
                                                <button type="button" class="btn btn-primary btn-sm" id="btn-generate"
                                                    disabled><i class="fa fa-sync"></i> Generate</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </span>
                        </div>
                        <div class="info-box-content" hidden id="sy_holder">
                            <span class="info-box-text">S.Y</span>
                            <span class="info-box-number">
                                <select class="form-control form-control-sm" id="filter_sy">
                                    @php
                                        $sy = DB::table('sy')->get();
                                    @endphp
                                    @foreach ($sy as $item)
                                        @php
                                            $selected = '';
                                            if ($item->isactive == 1) {
                                                $selected = 'selected="selected"';
                                            }
                                        @endphp
                                        <option value="{{ $item->id }}" {{ $selected }} value="{{ $item->id }}">
                                            {{ $item->sydesc }}</option>
                                    @endforeach
                                </select>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid" id="clearance_view" hidden>
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <span id="cleared_status"></span>
                                    <p style="font-size:.9rem !important"><span class="font-weight-bold"
                                            id="outof"></span></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-sm table-bordered table-hover"
                                        id="clearance_table" width="100%" style="font-size:11px !important">
                                        <thead>
                                            <tr>
                                                <th width="70%">Clearance</th>
                                                <th class="text-center" width="30%">Status</th>
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
    </section>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script>
        $('.select2').select2()
        var clerhistory = null

        $(document).ready(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            })

            $(document).on('click', '#btn-generate', function() {
                getclearnace()
            })

            gethistory()

            function gethistory() {
                var dataArray = [];

                $.ajax({
                    url: '/setup/student/clearance/gethistory',
                    type: 'GET',
                    dataType: 'json',
                    data: {},
                    success: function(data) {
                        clerhistory = data
                        $('#filter_clearancehistory').empty();
                        if (data.length > 0) {
                            $("#filter_clearancehistory").select2({
                                data: clerhistory,
                                placeholder: "Select Academic Term History",
                            })
                            $('#btn-generate').attr('disabled', false)
                        } else {
                            $("#filter_clearancehistory").select2({
                                placeholder: "No Clearance History!",
                            })
                            $('#btn-generate').attr('disabled', true)
                        }
                    }
                })
            }

            function getclearnace() {
                var clerstudid = $('#filter_clearancehistory').val()
                var syid = $('#filter_sy').val()


                var selected = clerhistory.filter(x => x.id == clerstudid)
                var termid = selected[0].termid

                $.ajax({
                    url: '/setup/student/clearance/getdata',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        clerstudid: clerstudid,
                        syid: syid,
                        termid: termid
                    },
                    success: function(data) {
                        console.log('Clearance', data);

                        $('#clearance_view').removeAttr('hidden')
                        clearance_data = data

                        var total_clearance = clearance_data[0].length
                        var cleared_clearance = clearance_data[0].filter(x => x.status === 0).length
                        var outof = '(' + cleared_clearance + ' cleared out of  ' + total_clearance +
                            ')'

                        $("#outof").empty()
                        $("#outof").append(outof)

                        clearance_table()
                    },
                    error: function() {
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        })
                    }
                })
            }

            function clearance_table() {
                $("#clearance_table").DataTable({
                    destroy: true,
                    data: clearance_data[0],
                    lengthChange: true,
                    stateSave: true,
                    sort: false,
                    searching: false,
                    paging: false,
                    info: false,
                    responsive: true,
                    columns: [{
                            "data": null
                        },
                        {
                            "data": null
                        },
                    ],
                    columnDefs: [{
                            'targets': 0,
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var sub = rowData.subjdesc
                                var remarks = rowData.remarks
                                var middlename = rowData.middlename
                                var firstname = rowData.firstname
                                var lastname = rowData.lastname
                                var title = rowData.title

                                if (remarks == null) {
                                    remarks = "No Remarks"
                                }
                                if (title == null) {
                                    title = " "
                                }
                                if (lastname == null) {
                                    lastname = " "
                                }
                                if (firstname == null) {
                                    firstname = " "
                                }
                                if (middlename == null) {
                                    var firstLetter = " "
                                } else {
                                    var firstLetter = middlename.charAt(0) + '.'
                                }
                                var fullname = title + ' ' + firstname + ' ' + firstLetter + ' ' +
                                    lastname

                                if (sub == null) {
                                    subname = rowData.subject_id
                                } else {
                                    subname = rowData.subjdesc
                                }
                                if (subname == null) {
                                    subname = rowData.departmentid
                                }
                                if (subname == null) {
                                    subname = 'Class Adviser'
                                }

                                var subdesc = '<label>' + subname + '<br> ' + fullname +
                                    '</label><br>'

                                var text = subdesc
                                text += remarks

                                $(td)[0].innerHTML = text
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': true,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                var status = rowData.status
                                var badge = ""
                                var date = ""

                                if (status == 0) {
                                    badge =
                                        '<h6><span class="badge badge-success"><i class="fa fa-check"></i> Cleared</span></h6>'
                                    date = rowData.approveddatetime
                                }
                                if (status == 1) {
                                    badge =
                                        '<h6><span class="badge badge-danger"><i class="fa fa-times"></i> Uncleared</span></h6>'
                                    date = rowData.updateddatetime
                                }
                                if (status == 2) {
                                    badge =
                                        '<h6><span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> Pending</span></h6>'
                                    date = rowData.updateddatetime
                                }
                                if (status == null) {
                                    badge =
                                        '<h6><span class="badge badge-warning"><i class="fas fa-ban"></i> No Clearance</span></h6>'
                                    date = null
                                }

                                if (date != null) {
                                    let datetime = new Date(date);
                                    let options = {
                                        year: 'numeric',
                                        month: '2-digit',
                                        day: '2-digit',
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        second: '2-digit',
                                        hour12: true
                                    };
                                    formattedDatetime = datetime.toLocaleString('en-US', options);
                                } else {
                                    formattedDatetime = "..."
                                }

                                var text = '<span style="font-size:.9rem !important">' + badge +
                                    '</span>'
                                text += '<span style="font-size:.7rem !important">' +
                                    formattedDatetime + '</span>'

                                $(td)[0].innerHTML = text
                                $(td).addClass('text-center')
                            }
                        },
                    ],
                    rowCallback: function(row, data) {
                        $(row).attr('data-id', data.id);
                    }
                });

                $("#cleared_status").empty()
                var iscleared = clearance_data && clearance_data[2] && clearance_data[2][0] && clearance_data[2][0]
                    .iscleared;
                if (iscleared == 0) {
                    $("#cleared_status").append(
                        '<h3><span class="badge badge-success"><i class="fa fa-check"></i> Clearance Cleared</span></h3>'
                        );
                } else if (iscleared == 2) {
                    $("#cleared_status").append(
                        '<h3><span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> Clearance Pending</span></h3>'
                        );
                } else {
                    $("#cleared_status").append(
                        '<h3><span class="badge badge-danger"><i class="fa fa-times"></i> Clearance Uncleared</span></h3>'
                        );
                }
            }

        })
    </script>
@endsection
