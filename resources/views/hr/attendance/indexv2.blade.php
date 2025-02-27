@extends('hr.layouts.app')
@section('content')
    <!-- DataTables -->
    {{-- <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}"> --}}

    <style>
        td {
            padding: 2px !important;
        }
        .loader {
        /* text-align: center; */
        --w:10ch;
        font-weight: bold;
        font-family: monospace;
        font-size: 50px;
        letter-spacing: var(--w);
        width:var(--w);
        overflow: hidden;
        white-space: nowrap;
        text-shadow: 
            calc(-1*var(--w)) 0, 
            calc(-2*var(--w)) 0, 
            calc(-3*var(--w)) 0, 
            calc(-4*var(--w)) 0,
            calc(-5*var(--w)) 0, 
            calc(-6*var(--w)) 0, 
            calc(-7*var(--w)) 0, 
            calc(-8*var(--w)) 0, 
            calc(-9*var(--w)) 0;
            animation: l16 2s infinite, dots 2s linear infinite;

        }
        .loader:before {
        content:"Processing...";
        }
        @keyframes l16 {
        20% {text-shadow: 
            calc(-1*var(--w)) 0, 
            calc(-2*var(--w)) 0 red, 
            calc(-3*var(--w)) 0, 
            calc(-4*var(--w)) 0 #ffa516,
            calc(-5*var(--w)) 0 #63fff4, 
            calc(-6*var(--w)) 0, 
            calc(-7*var(--w)) 0, 
            calc(-8*var(--w)) 0 green, 
            calc(-9*var(--w)) 0;}
        40% {text-shadow: 
            calc(-1*var(--w)) 0, 
            calc(-2*var(--w)) 0 red, 
            calc(-3*var(--w)) 0 #e945e9, 
            calc(-4*var(--w)) 0,
            calc(-5*var(--w)) 0 green, 
            calc(-6*var(--w)) 0 orange, 
            calc(-7*var(--w)) 0, 
            calc(-8*var(--w)) 0 green, 
            calc(-9*var(--w)) 0;}
        60% {text-shadow: 
            calc(-1*var(--w)) 0 lightblue, 
            calc(-2*var(--w)) 0, 
            calc(-3*var(--w)) 0 #e945e9, 
            calc(-4*var(--w)) 0,
            calc(-5*var(--w)) 0 green, 
            calc(-6*var(--w)) 0, 
            calc(-7*var(--w)) 0 yellow, 
            calc(-8*var(--w)) 0 #ffa516, 
            calc(-9*var(--w)) 0 red;}
        80% {text-shadow: 
            calc(-1*var(--w)) 0 lightblue, 
            calc(-2*var(--w)) 0 yellow, 
            calc(-3*var(--w)) 0 #63fff4, 
            calc(-4*var(--w)) 0 #ffa516,
            calc(-5*var(--w)) 0 red, 
            calc(-6*var(--w)) 0, 
            calc(-7*var(--w)) 0 grey, 
            calc(-8*var(--w)) 0 #63fff4, 
            calc(-9*var(--w)) 0 ;}
        }
        .loader1holder {
            padding-top: 59px;
            padding-bottom: 44px;
            padding-left: 176px;
        }
        .loaderholder {
            padding-left: 42px;
        }
        .loader1 {
            --d:28px;
            width: 3px;
            height: 3px;
            border-radius: 50%;
            color: #25b09b;
            box-shadow: 
                calc(1*var(--d))      calc(0*var(--d))     0 0,
                calc(0.707*var(--d))  calc(0.707*var(--d)) 0 1px,
                calc(0*var(--d))      calc(1*var(--d))     0 2px,
                calc(-0.707*var(--d)) calc(0.707*var(--d)) 0 3px,
                calc(-1*var(--d))     calc(0*var(--d))     0 4px,
                calc(-0.707*var(--d)) calc(-0.707*var(--d))0 5px,
                calc(0*var(--d))      calc(-1*var(--d))    0 6px;
            animation: l27 1s infinite steps(8);
            }
            @keyframes l27 {
            100% {transform: rotate(1turn)}
        }
    </style>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>ATTENDANCE</h1>
                    <!-- <h1>Attendance</h1> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Attendance</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="card shadow" style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
        <div class="card-header">
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Select Date</label>
                            <input type="date" id="select-date" class="form-control" value="{{ date('Y-m-d') }}" />
                        </div>
                        @if (isset(DB::table('schoolinfo')->first()->servertype))
                            @if (DB::table('schoolinfo')->first()->servertype == 1)
                                <div class="col-md-4 align-self-end">
                                    <label>Tapping Station:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <span id="text-tap-count"></span>
                                </div>
                                <div class="col-md-3 align-self-end">
                                    <label>Server:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <span id="text-server-count"></span>
                                </div>
                                <div class="col-md-2 align-self-end">
                                    <button type="button" class="btn btn-primary btn-sm btn-block"
                                        id="display-sync-info">Sync</button>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Upload Attendance</label><br>
                            <form 
                                action="/attendance/upload" 
                                id="upload_attendance" 
                                method="POST" 
                                enctype="multipart/form-data"
                                >
                                @csrf
                                <div class="row">
                                        <div class="input-group input-group-sm">
                                            <input type="file" class="form-control" name="input_attendance" id="input_attendance ">
                                            <span class="input-group-append">
                                            <button class="btn btn-info btn-flat" id="upload_attendance" >Update ECR</button>
                                            </span>
                                        </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow" style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
        <div class="card-body">
            <table id="example2" class="table table-hover" style="font-size: 12.5px;">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th style="width: 10%;" class="text-center">AM In</th>
                        <th style="width: 10%;" class="text-center">AM Out</th>
                        <th style="width: 10%;" class="text-center">PM In</th>
                        <th style="width: 10%;" class="text-center">PM Out</th>
                        <th style="width: 20%;">REMARKS</th>
                        <th style="width: 5%;"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>
    </div>

    <div class="modal fade" id="modal-timelogs" aria-hidden="true" style="display: none;"data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog modal-md">
            <div class="modal-content" id="timelogsdetails">
            </div>
        </div>
    </div>
@endsection
@section('footerscripts')
    <script>
        var $ = jQuery;
        $(document).ready(function() {
            var approvedleaves = [];
            getapproveleaves()

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            });


            function getapproveleaves() {
                $.ajax({
                    url: '/hr/attendance/getapproveleaves',
                    type: "GET",
                    success: function(data) {
                        approvedleaves = data;
                        getemployees()
                    }
                });
            }

            $(".filter").on("keyup", function() {
                var input = $(this).val().toUpperCase();
                var visibleCards = 0;
                var hiddenCards = 0;

                $(".container").append($("<div class='card-group card-group-filter'></div>"));


                $(".card-each-emp").each(function() {
                    if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                        $(".card-group.card-group-filter:first-of-type").append($(this));
                        $(this).hide();
                        hiddenCards++;

                    } else {

                        $(".card-group.card-group-filter:last-of-type").prepend($(this));
                        $(this).show();
                        visibleCards++;

                        if (((visibleCards % 4) == 0)) {
                            $(".container").append($(
                                "<div class='card-group card-group-filter'></div>"));
                        }
                    }
                });

            });

            var onerror_url = @json(asset('dist/img/download.png'));

            function convertTo12HourFormat(time) {
                if (!time) {
                    return ' '; // Return a placeholder if the time is null or empty
                }

                const [hours, minutes] = time.split(':');
                let period = 'AM';
                let hour = parseInt(hours);

                if (hour >= 12) {
                    period = 'PM';
                    if (hour > 12) {
                        hour -= 12;
                    }
                } else if (hour === 0) {
                    hour = 12;
                }

                return `${hour}:${minutes} ${period}`;
            }

            function getemployees() {
                var sdate = $('#select-date').val();
                console.log(sdate);
                $('#example2').DataTable({
                    // "paging": false,
                    // "lengthChange": false,
                    "searching": true,
                    "ordering": false,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "destroy": true,
                    serverSide: true,
                    processing: true,
                    // ajax:'/student/preregistration/list',
                    ajax: {
                        url: '/hr/attendance/indexv2',
                        type: 'GET',
                        data: {
                            action: 'getemployees',
                            changedate: $('#select-date').val()
                        },
                        dataSrc: function(json) {
                            console.log(json); // Check the structure here
                            return json.data; // Adjust if necessary
                        }
                    },
                    columns: [{
                            "data": null
                        }, // For Employee
                        {
                            "data": "amin"
                        }, // For AM In
                        {
                            "data": "amout"
                        }, // For AM Out
                        {
                            "data": "pmin"
                        }, // For PM In
                        {
                            "data": "pmout"
                        }, // For PM Out
                        {
                            "data": null
                        }, // For REMARKS
                        {
                            "data": null
                        } // For Action button
                    ],

                    columnDefs: [{
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = ' <div class="row">' +
                                    '<div class="col-md-3">' +
                                    '<img src="/' + rowData.picurl +
                                    '" class="" alt="User Image" onerror="this.src=\'' +
                                    onerror_url + '\'" width="40px"/>' +

                                    '</div>' +
                                    '<div class="col-md-9">' +
                                    '<div class="row">' +
                                    '<div class="col-md-12">' + rowData.lastname + ', ' + rowData
                                    .firstname + '</div>   ' +
                                    '<div class="col-md-12">' + '<small class="text-primary">' +
                                    rowData.tid + '</small></div>   ' +
                                    '</div>' +


                                    '</div>' +
                                    '</div>'
                                // $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = convertTo12HourFormat(rowData.amin)
                                $(td).addClass('align-middle text-center')
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = convertTo12HourFormat(rowData.amout)
                                $(td).addClass('align-middle text-center')
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = convertTo12HourFormat(rowData.pmin)
                                $(td).addClass('align-middle text-center')
                            }
                        },
                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = convertTo12HourFormat(rowData.pmout)
                                $(td).addClass('align-middle text-center')
                            }
                        },
                        {
                            'targets': 5,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                              
                                var text = '';

                                // Check if leavesapplied exists and has values
                                if (rowData.leavesapplied && rowData.leavesapplied.length > 0) {
                                    rowData.leavesapplied.forEach(function(leave) {
                                        text += '<span class="badge badge-info" style="font-size: 12px!important;">' +
                                            leave.remarks + '</span><br><span>' + leave.daycoverd + '</span><br>';
                                    });
                                }
                                // Check if holiday exists and has values
                                if (rowData.holiday && Object.keys(rowData.holiday).length > 0) {
                                    text += '<span class="badge badge-info" style="font-size: 12px!important;">' +
                                    rowData.holiday.type + '</span><br><small>' + rowData.holiday.holidayname + '</small><br>';
                                } else {
                                    text = '';
                                } 

                                // If both leavesapplied and holiday are null/empty, leave the cell empty
                                $(td)[0].innerHTML = text;
                                $(td).addClass('align-middle', 'text-center')
                            }
                        },
                        {
                            'targets': 6,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML =
                                    '<button type="button" class="btn btn-sm btn-default timelogs" data-id="' +
                                    rowData.id + '">' +

                                    'Logs</button>'
                                $(td).addClass('align-middle')
                            }
                        }
                    ]
                });
                $(document).on('click', '.timelogs', function() {
                    var employeeid = $(this).attr('data-id')
                    $('#modal-timelogs').modal('show')
                    var selecteddate = $('#select-date').val()
                    $.ajax({
                        url: '/hr/attendance/gettimelogs',
                        type: "GET",
                        data: {
                            employeeid: employeeid,
                            selecteddate: selecteddate
                        },
                        success: function(data) {
                            $('#timelogsdetails').empty()
                            $('#timelogsdetails').append(data)
                        }
                    });
                })

            }

            $('#select-date').on('change', function() {
                getapproveleaves()
            })
            $(document).on('keypress', '.employee-remarks', function(e) {
                if (e.which == 13) {
                    var selecteddate = $('#select-date').val()

                    $.ajax({
                        url: "/hr/attendance/updateremarks",
                        type: "get",
                        data: {
                            id: $(this).attr('data-id'),
                            selecteddate: selecteddate,
                            remarks: $(this).val()
                        },
                        success: function(data) {
                            if (data == 1) {
                                toastr.success('Updated successfully!')
                            }
                        }
                    });
                    return false; //<---- Add this line
                }
            });
            var newlogscounter = 1;
            $(document).on('click', '#buttonaddnewlog', function() {
                $('#newlogscontainer').append(
                    '<div class="row mb-2">' +
                    '<div class="col-1">' +
                    '<button type="button" class="btn btn-sm btn-default p-0 mt-1 btn-block savenewtimelog' +
                    newlogscounter + '">&nbsp;<i class="fa fa-check"></i>&nbsp;</button>' +
                    '</div>' +
                    '<div class="col-6">' +
                    '<input type="time" class="form-control form-control-sm" name="newtimelog"/>' +
                    // '<input id="newtimepick'+newlogscounter+'" class="timepick timepickerinputs form-control form-control-sm" name="newtimelog" readonly/>'+
                    '</div>' +
                    '<div class="col-4  p-0 text-center">' +

                    '<input type="checkbox" id="newlogstate' + newlogscounter +
                    '" name="logstate" checked data-bootstrap-switch data-off-color="warning" data-on-text="IN"  data-off-text="OUT" data-on-color="success" data-size="sm">' +
                    '</div>' +
                    // '<div class="col-2  p-0 text-center">'+

                    //     '<input type="checkbox" id="newlog'+newlogscounter+'" name="dayshift" checked data-bootstrap-switch data-off-color="warning" data-on-text="AM"  data-off-text="PM" data-on-color="success" data-size="sm">'+
                    // '</div>'+
                    '<div class="col-1">' +
                    '<button type="button" class="btn btn-sm btn-default p-0 mt-1 btn-block removenewtimelog">&nbsp;<i class="fa fa-times"></i>&nbsp;</button>' +
                    '</div>' +
                    '</div>'
                )

                var logstate = 'in';
                $('#newlogstate' + newlogscounter).bootstrapSwitch('state', true);
                $('#newlogstate' + newlogscounter).on('switchChange.bootstrapSwitch', function() {
                    var check = $(this).closest('.bootstrap-switch-on')
                    if (check.length > 0) {
                        logstate = 'out';
                    } else {
                        logstate = 'in';
                    }
                });
                $(document).on('click', '.savenewtimelog' + newlogscounter, function() {
                    var thisrow = $(this).closest('.row');
                    var thissavebutton = $(this);
                    var timelog = thisrow.find('input[name="newtimelog"]').val();
                    var employeeid = $('#newlogscontainer').attr('employeeid');
                    var usertypeid = $('#newlogscontainer').attr('usertypeid');
                    var selecteddate = $('#select-date').val()
                    if (timelog.replace(/^\s+|\s+$/g, "").length == 0) {

                        toastr.warning('Please set a time first!', 'Time Logs')

                    } else {
                        $.ajax({
                            url: '/hr/attendance/addtimelog',
                            type: "GET",
                            data: {
                                employeeid: employeeid,
                                usertypeid: usertypeid,
                                timelog: timelog,
                                // timeshift   : timeshift,
                                tapstate: logstate,
                                selecteddate: selecteddate
                            },
                            success: function(data) {
                                if (data == '1') {
                                    toastr.success('Added successfully!', 'Time Logs')
                                    thissavebutton.attr('disabled', true)
                                    thisrow.find('.clock').remove()
                                    thisrow.find('.removenewtimelog').remove()
                                    thisrow.find("[name='dayshift']").bootstrapSwitch(
                                        'disabled', true);
                                } else {
                                    toastr.danger('Something went wrong!', 'Time Logs')
                                }
                            }
                        });
                    }
                })
                newlogscounter += 1;

                $('.removenewtimelog').on('click', function() {
                    $(this).closest('.row').remove()
                })
            })
            $(document).on('click', '.deletelog', function() {
                var thisrow = $(this).closest('.row');
                var logid = $(this).attr('id');
                Swal.fire({
                    title: 'Are you sure you want to delete this log?',
                    type: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Delete',
                    showCancelButton: true,
                    allowOutsideClick: false
                }).then((confirm) => {
                    if (confirm.value) {

                        $.ajax({
                            url: '/hr/attendance/deletetimelog',
                            type: 'get',
                            dataType: 'json',
                            data: {
                                id: logid
                            },
                            complete: function(data) {
                                thisrow.remove()
                                toastr.success('Time log deleted successfully!',
                                    'Time Logs')
                            }
                        })
                    }
                })
            })
            $(document).on('click', '.deletelogtap', function() {
                var thisrow = $(this).closest('.row');
                var logid = $(this).attr('id');

                console.log(thisrow);
                console.log(logid);
                Swal.fire({
                    title: 'Are you sure you want to delete this log?',
                    type: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Delete',
                    showCancelButton: true,
                    allowOutsideClick: false
                }).then((confirm) => {
                    if (confirm.value) {

                        $.ajax({
                            url: '/hr/attendance/deletetimelogtapping',
                            type: 'get',
                            dataType: 'json',
                            data: {
                                id: logid
                            },
                            complete: function(data) {
                                thisrow.remove()
                                toastr.success('Time log deleted successfully!',
                                    'Time Logs')
                            }
                        })
                    }
                })
            })

            $('#modal-timelogs').on('hidden.bs.modal', function(e) {
                getemployees();
            })

            @if (isset(DB::table('schoolinfo')->first()->servertype))
                @if (DB::table('schoolinfo')->first()->servertype == 1)
                    var school_setup = @json(DB::table('schoolinfo')->first());
                    var tablename = 'taphistory';

                    function get_last_index(tablename) { //table-to
                        $.ajax({
                            type: 'GET',
                            url: '/monitoring/tablecount',
                            data: {
                                tablename: tablename
                            },
                            success: function(data) {
                                lastindex = data[0].lastindex
                                update_local_table_display(tablename, lastindex)
                            },
                        })
                    }

                    function get_last_index_fromtap(tablename) { //table-from
                        $.ajax({
                            type: 'GET',
                            // url: school_setup.es_cloudurl+'/monitoring/tablecount',
                            url: 'http://tapapp.ck/monitoring/tablecount',
                            data: {
                                tablename: tablename
                            },
                            success: function(data) {
                                var lastindextap = data[0].lastindex
                                $('#text-tap-count').text(lastindextap)
                            },
                            error: function() {
                                $('#display-sync-info')[0].innerHTML = 'Connection Problem'
                                $('#display-sync-info').prop('disabled', true)
                            }
                        })
                    }
                    get_last_index_fromtap(tablename)

                    function get_last_index_fromserver(tablename) { //table-to
                        $.ajax({
                            type: 'GET',
                            url: '/monitoring/tablecount',
                            data: {
                                tablename: tablename
                            },
                            success: function(data) {
                                var lastindexserver = data[0].lastindex
                                if (data[0].tablecount == 0) {
                                    $('#text-server-count').text(0)
                                } else {
                                    $('#text-server-count').text(lastindexserver)
                                }
                            },
                            error: function() {
                                $('#display-sync-info')[0].innerHTML = 'Connection Problem'
                                $('#display-sync-info').prop('disabled', true)
                            }
                        })
                    }
                    get_last_index_fromserver(tablename)

                    function update_local_table_display(tablename, lastindex) { //table-from
                        $.ajax({
                            type: 'GET',
                            // url: school_setup.es_cloudurl+'/monitoring/table/data',
                            url: 'http://tapapp.ck/monitoring/table/data',
                            data: {
                                tablename: tablename,
                                tableindex: lastindex
                            },
                            success: function(syncdata) {
                                if (syncdata.length > 0) {
                                    process_create(tablename, 0, syncdata)
                                }
                            },
                            error: function() {
                                $('#display-sync-info')[0].innerHTML = 'Connection Problem'
                            }
                        })
                    }
                    // get_last_index(tablename)
                    function process_create(tablename, process_count, createdata) {
                        console.log(createdata)
                        var countcreatedtap = parseInt($('#text-tap-count').text())
                        var countcreatedserver = parseInt($('#text-server-count').text())
                        if (createdata.length == 0) {
                            $('#display-sync-info').removeClass('btn-warning')
                            $('#display-sync-info').addClass('btn-primary')
                            $('#display-sync-info').text('Synced')
                            return false;
                        } else {
                            $('#display-sync-info').addClass('btn-warning')
                            $('#display-sync-info').removeClass('btn-primary')
                            $('#display-sync-info').text('Syncing...')

                        }
                        var b = createdata[0]
                        $.ajax({
                            type: 'GET',
                            url: '/synchornization/insert',
                            data: {
                                tablename: tablename,
                                data: b
                            },
                            success: function(data) {
                                $('#text-server-count').text(countcreatedserver + 1)
                                process_count += 1
                                createdata = createdata.filter(x => x.id != b.id)
                                process_create(tablename, process_count, createdata)
                                // $('#text-tap-count').text(process_count)

                            },
                            error: function() {
                                process_count += 1
                                createdata = createdata.filter(x => x.id != b.id)
                                process_create(tablename, process_count, createdata)
                                $('#display-sync-info').prop('disabled', true)
                            }
                        })
                    }

                    $('#display-sync-info').on('click', function() {
                        get_last_index(tablename)
                        $(this).prop('disabled', true)
                        getemployees()
                    })
                @endif
            @endif


            $(document).on('click', '#upload_attendance', function(){
                console.log('masaya');
            })

            $('#upload_attendance').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                // Gather form data
                var inputs = new FormData(this);

                Swal.fire({
                    title: 'Please wait...',
                    html: `
                        <div class="row" style="justify-content: center !important; display: grid !important;">
                            <div class="loader1holder">
                                <div class="loader1"></div>
                            </div>
                            <div class="loaderholder">
                                <div class="loader"></div>
                            </div>
                            <div class="note text-danger">
                                <small><strong>Note!!! Do not refresh while the process is ongoing...</strong></small>
                            </div>
                        </div>`,
                    didOpen: () => {
                        // You can perform additional actions before the modal is opened
                    },
                    didClose: () => {
                        // You can perform additional actions after the modal is closed
                    },
                    allowOutsideClick: false,
                    showConfirmButton: false,
                });
                
                // AJAX request
                $.ajax({
                    url: '/attendance/upload', // URL for your Laravel route
                    type: 'POST',
                    data: inputs,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        Swal.close(); // Close the loading modal
                        if (data == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Attendance uploaded successfully!'
                            });
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.close(); // Close the loading modal in case of error
                        console.error('Error:', error);
                        Toast.fire({
                            type: 'error',
                            title: 'Failed to upload attendance'
                        });
                    }
                });
            });


        })
    </script>
@endsection
