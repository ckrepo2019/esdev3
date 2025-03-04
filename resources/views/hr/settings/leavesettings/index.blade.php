@extends('hr.layouts.app')
@section('content')
    <style>
        .onoffswitch-inner:before {
            background-color: #55ce63;
            color: #fff;
            content: "ON";
            padding-left: 14px;
        }

        * {
                {
                font-family: Arial;
            }

            .card {
                border: none;
                box-shadow: unset;
            }
    </style>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-chart-line nav-icon"></i>
                        Leave Settings</h4>
                    <!-- <h1>Attendance</h1> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Leave Settings</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row mb-2">
        <div class="col-md-12">
            @if (session()->has('messageExists'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                    {{ session()->get('messageExists') }}
                </div>
            @endif
            @if (session()->has('messageAdd'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                    {{ session()->get('messageAdd') }}
                </div>
            @endif
            @if (session()->has('messageDelete'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                    {{ session()->get('messageDelete') }}
                </div>
            @endif
        </div>
    </div>
    {{-- <div class="card shadow">
    <div class="card-header">
        <h3 class="card-title">APPROVALS</h3>

        <div class="card-tools">
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"  data-target="#addaproval"><i class="fa fa-plus"> </i> Add Approval
        </button>
        <div class="modal fade" id="addaproval" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="/hr/settings/approvals" method="get">
                        @csrf
                        <div class="modal-header">
                        <h4 class="modal-title">Approval</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <input type="text" class="form-control " name="action" value="add" hidden>
                        <label>Employees:</label>
                        <div class="">
                            <select id="select2" class="form-control select2bs4 m-0 text-uppercase" multiple="multiple" data-placeholder="Select employee:" name="employeeids[]" required>
                                <option></option>
                                @foreach ($employees as $employee)
                                    <option value="{{$employee->id}}">
                                        {{strtoupper($employee->lastname)}}, {{strtoupper($employee->firstname)}} {{strtoupper($employee->suffix)}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
            </div>
        </div>
        <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
    @if (count($approvals) > 0)
    <div class="card-body">
        @foreach ($approvals as $approval)
            <button type="button" class="btn btn-default mb-2" data-toggle="modal" data-target="#deleteapproval{{$approval->id}}">{{$approval->lastname}}, {{$approval->firstname}} {{$approval->lastname}} {{$approval->middlename}} {{$approval->suffix}}</button>
            <div class="modal fade" id="deleteapproval{{$approval->id}}" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="/hr/settings/approvals" method="get">
                            @csrf
                            <div class="modal-header">
                            <h4 class="modal-title">Approval</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to remove this Leave Approval Personnel?</p>
                                <input type="text" class="form-control " name="approvalid" value="{{$approval->id}}" hidden>
                                <input type="text" class="form-control " name="action" value="delete" hidden>
                            </div>
                            <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Remove</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
                </div>
        @endforeach
    </div>
    @endif
    <!-- /.card-body -->
</div> --}}
    {{-- <div class="row mb-2">
    <div class="col-md-12">
      <button type="butotn" class="btn btn-primary" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add Leave</button>
      <div class="modal fade" id="add_leave" style="display: none;" aria-hidden="true">
          <div class="modal-dialog modal-md">
            <div class="modal-content text-uppercase">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Add Leave</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Year</label>
                            <input type="number" min="2020" max="{{date('Y')+1}}" step="1" value="{{date('Y')}}" class="form-control text-uppercase mb-2" id="lyear"/>
                        </div>
                        <div class="col-md-12">
                            <label>Leave Type (Title)</label>
                            <input type="text" class="form-control text-uppercase" id="leave_type"/>
                        </div>
                        <div class="col-md-12">
                            <label>No. of applications per employee</label>
                            <input type="number" class="form-control text-uppercase" id="noofapplications"/>
                        </div>
                        <div class="col-md-4 mt-2">
                            <select class="form-control text-uppercase" id="statuspay">
                                <option value="1">With Pay</option>
                                <option value="0">Without Pay</option>
                            </select>
                        </div>
                    </div>
                        
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-close" id="btn-close" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-newleave">Save changes</button>
                </div>
            </div>
          </div>
      </div>
    </div>  
</div> --}}

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-4">
                    <label>Select Year</label>
                    <select class="form-control" id="lyear">
                        @for ($x = date('Y') + 1; $x > '2019'; $x--)
                            <option value="{{ $x }}" @if ($x == date('Y')) selected @endif>
                                {{ $x }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-8 align-self-end">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_leave"
                        id="btn-addleave"><i class="fa fa-plus"></i> Add Leave</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#approvalperdep"
                        id="btn-approvalperdep" hidden><i class="fa fa-plus"></i> Approval Department</button>

                    <div class="modal fade" id="add_leave" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content text-uppercase">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title">Add Leave</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12" hidden>
                                            <label>Year</label>
                                            <input type="text" class="form-control text-uppercase mb-2"
                                                id="input-lyear" />
                                        </div>
                                        <div class="col-md-12">
                                            <label>Leave Type (Title)</label>
                                            <input type="text" class="form-control text-uppercase" id="leave_type"
                                                onkeyup="this.value = this.value.toUpperCase();" />
                                        </div>
                                        {{-- <div class="col-md-12 mt-3">
                                        <label>Leave Days</label>
                                        <input type="number" class="form-control text-uppercase" id="noofapplications"/>
                                    </div> --}}
                                        <div class="col-md-4 mt-3">
                                            <label>Pay</label>

                                            <select class="form-control text-uppercase" id="statuspay">
                                                <option value="1">With Pay</option>
                                                <option value="0">Without Pay</option>
                                            </select>
                                        </div>
                                        {{-- <div class="col-md-4 mt-3">
                                        <label>Year of Service</label>
                                        <input type="number" class="form-control text-uppercase" id="yearstoavail" min="0"/>
                                    </div> --}}
                                        {{-- <div class="col-md-12 mt-3">
                                        <label>Convert to Cash &nbsp;</label>
                                        <input type="checkbox" class="" id="converttocash" style="width: 18px; height: 18px;">
                                    </div> --}}
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default btn-close" id="btn-close"
                                        data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="btn-submit-newleave">Save
                                        changes</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" id="approvalperdep" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content text-uppercase">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title">Approval Department</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <center>WORKING...</center>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default btn-close" id="btn-close"
                                        data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="results-container"></div>
    </div>
    <div class="modal fade" id="modal-addmoredates" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content text-uppercase">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Add Dates</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-default btn-sm mb-2" id="btn-addmoredates"><i
                            class="fa fa-plus"></i> Dates covered</button>
                    <div class="row mb-2">
                        <div class="col-md-5">
                            <input type="date" class="form-control input-adddatefrom" />
                        </div>
                        <div class="col-md-5">
                            <input type="date" class="form-control input-adddateto" />
                        </div>
                        {{-- <div class="col-md-2">
                      <button type="button" class="btn btn-default btn-removedate"><i class="fa fa-times"></i></button>
                  </div> --}}
                    </div>
                    <div id="div-addmoredates"></div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-moredates">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-addmoreemployees" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content text-uppercase">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Add Employees</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <select class="select2bs4" multiple="multiple" style="width: 100%;" id="select-moreemployees">
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ ucwords(strtolower($employee->lastname)) }},
                                {{ ucwords(strtolower($employee->firstname)) }}</option>
                        @endforeach
                    </select>
                    <hr>
                    <label>Who can approve their request?</label>
                    <select class="select2bs4" multiple="multiple" style="width: 100%;" id="select-approvals">
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->userid }}">{{ ucwords(strtolower($employee->lastname)) }},
                                {{ ucwords(strtolower($employee->firstname)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-moreemployees">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-view-approvals" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content text-uppercase">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Approval Settings</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="approval-container">

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-moreapprovals">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        var leavedates = [];
        var leaveemployees = [];

        var leaveid = 0;
        $(document).ready(function() {
            var daysyears = [];

            $('.select2').select2()
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
            $('#btn-addleave').on('click', function() {
                $('#input-lyear').val($('#lyear').val())
            })
            // $('#btn-adddates').on('click', function(){
            //     $('#div-adddates').append(
            //         '<div class="row mb-2">'+
            //             '<div class="col-md-6">'+
            //                 '<input type="date" class="form-control input-adddates"/>'+
            //             '</div>'+
            //             '<div class="col-md-2">'+
            //                 '<button type="button" class="btn btn-default btn-removedate"><i class="fa fa-times"></i></button>'+
            //             '</div>'+
            //         '</div>'
            //     )
            // })
            $('#btn-addmoredates').on('click', function() {
                $('#div-addmoredates').append(
                    '<div class="row mb-2">' +
                    '<div class="col-md-5">' +
                    '<input type="date" class="form-control input-adddatefrom"/>' +
                    '</div>' +
                    '<div class="col-md-5">' +
                    '<input type="date" class="form-control input-adddateto"/>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                    '<button type="button" class="btn btn-default btn-removedate"><i class="fa fa-times"></i></button>' +
                    '</div>' +
                    '</div>'
                )
            })
            $(document).on('click', '.btn-removedate', function() {
                $(this).closest('.row').remove();
            })

            function loaddata() {
                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    closeOnClickOutside: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                })
                var lyear = $('#lyear').val();
                $.ajax({
                    url: '/hr/settings/leaves?action=load',
                    type: 'GET',
                    data: {
                        lyear: lyear
                    },
                    success: function(data) {
                        $('#results-container').empty()
                        $('#results-container').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        leavedates = [];
                        leaveemployees = [];

                    }
                })
            }
            loaddata()

            $('#lyear').on('change', function() {
                loaddata()
            })
            $('#btn-submit-newleave').on('click', function() {
                var leave_type = $('#leave_type').val();
                var lyear = $('#lyear').val();
                var noofapplications = $('#noofapplications').val();
                var statuspay = $('#statuspay').val();
                var thisfooter = $(this).closest('.modal-footer');
                var yearstoavail = $('#yearstoavail').val();
                var cash;

                if ($('#converttocash').prop('checked')) {
                    cash = 1;
                } else {
                    cash = 0;
                }

                if (leave_type.replace(/^\s+|\s+$/g, "").length == 0) {
                    $('#leave_type').css('border', '1px solid red');
                    toastr.warning('Please fill in required field!', 'Leave Settings')
                } else {
                    $('#leave_type').removeAttr('style');
                    // $('.input-adddates').each(function(){
                    //     if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
                    //     {
                    //         leavedates.push($(this).val())
                    //     }
                    // })
                    // leaveemployees = $('#select-employees').val()
                    $.ajax({
                        url: '/hr/settings/leaves?action=create',
                        type: 'GET',
                        data: {
                            // leaveemployees     :   JSON.stringify(leaveemployees),
                            leave_type: leave_type,
                            lyear: lyear,
                            noofapplications: noofapplications,
                            statuspay: statuspay,
                            yearstoavail: yearstoavail,
                            cash: cash
                            // leavedates         :   JSON.stringify(leavedates)
                        },
                        complete: function() {
                            leavedates = [];
                            leaveemployees = [];
                            thisfooter.find('.btn-close').click();
                            $('.modal-backdrop').remove()
                            // $(".swal2-container").remove();
                            // $('body').removeClass('swal2-shown')
                            // $('body').removeClass('swal2-height-auto')
                            toastr.success('Added successfully!', 'Leave Settings')
                            loaddata()
                        }
                    })
                }
            })

            $(document).on('click', 'input.leavename', function() {
                leaveid = $(this).attr('data-id');
            })
            $(document).on('click', 'input.leavedays', function() {
                leaveid = $(this).attr('data-id');
            })
            $(document).on('click', 'input.yearofservice', function() {
                leaveid = $(this).attr('data-id');
            })

            $(document).on('keyup', 'input.leavename', function(e) {
                if (e.which == 13) {
                    var leavename = $('input.leavename[data-id="' + leaveid + '"]').val();
                    $.ajax({
                        url: '/hr/settings/leaves?action=updateleavename',
                        type: "get",
                        data: {
                            leavename: leavename,
                            id: leaveid,
                        },
                        success: function(data) {
                            toastr.success('Updated successfully!')
                            $('.leavename').attr('readonly', true);
                        }
                    });
                    return false; //<---- Add this line
                }
            });
            $(document).on('click', 'input[name="withpay"]', function() {
                if ($(this).is(":checked")) {
                    var withpay = 1;
                } else if ($(this).is(":not(:checked)")) {
                    var withpay = 0;
                }
                leaveid = $(this).attr('data-id');
                $.ajax({
                    url: '/hr/settings/leaves?action=updatewithpay',
                    type: "get",
                    data: {
                        id: leaveid,
                        withpay: withpay
                    },
                    success: function(data) {
                        toastr.success('Updated successfully!')
                    }
                });

            });
            $(document).on('keyup', 'input.leavedays', function(e) {
                if (e.which == 13) {
                    var leavedays = $('input.leavedays[data-id="' + leaveid + '"]').val();
                    $.ajax({
                        url: '/hr/settings/leaves?action=updateleavedays',
                        type: "get",
                        data: {
                            leavedays: leavedays,
                            id: leaveid,
                        },
                        success: function(data) {
                            toastr.success('Updated successfully!')
                            $('.leavedays').attr('readonly', true);
                        }
                    });
                    return false; //<---- Add this line
                }
            });

            // added by gian
            $(document).on('keyup', 'input.yearofservice', function(e) {
                if (e.which == 13) {
                    var years = $('input.yearofservice[data-id="' + leaveid + '"]').val();
                    $.ajax({
                        url: '/hr/settings/leaves?action=updateyearofservice',
                        type: "get",
                        data: {
                            years: years,
                            id: leaveid,
                        },
                        success: function(data) {
                            toastr.success('Updated successfully!')
                            $('.leavedays').attr('readonly', true);
                        }
                    });
                    return false; //<---- Add this line
                }
            });
            $(document).on('click', 'input[name="tocash"]', function() {
                if ($(this).is(":checked")) {
                    var tocash = 1;
                } else if ($(this).is(":not(:checked)")) {
                    var tocash = 0;
                }
                leaveid = $(this).attr('data-id');
                $.ajax({
                    url: '/hr/settings/leaves?action=updatetocash',
                    type: "get",
                    data: {
                        id: leaveid,
                        tocash: tocash
                    },
                    success: function(data) {
                        toastr.success('Updated successfully!')
                    }
                });

            });
            $(document).on('click', '.set_days_years', function() {
                leaveid = $(this).attr('data-id');
                $('#leaveid').val(leaveid)
                loaddaysyears(leaveid)
                $('#modalsetupdayyears').modal('show')
            });
            $(document).on('click', '.addyearsdays', function() {
                $('#modaladddayyears').modal('show')
            });
            $(document).on('click', '#btnadddaysyears', function() {
                var validinput = true
                var leaveid = $('#leaveid').val()
                var yos = $('#modyearsofservice').val()
                var days = $('#moddays').val()

                if (!yos) {
                    validinput = false;
                    $('#modyearsofservice').addClass('is-invalid')
                    toastr.error('Years of Service is required!')
                } else {
                    $('#modyearsofservice').removeClass('is-invalid')
                }

                if (!days) {
                    validinput = false;
                    $('#moddays').addClass('is-invalid')
                    toastr.error('No. of days is required!')
                } else {
                    $('#moddays').removeClass('is-invalid')
                }

                if (validinput) {
                    $.ajax({
                        type: "GET",
                        url: "/leaves/setdaysyears/add",
                        data: {
                            leaveid: leaveid,
                            yos: yos,
                            days: days
                        },
                        success: function(data) {
                            if (data == 0) {
                                toastr.error('Already Exist!')
                            } else {
                                toastr.success('Added successfully!')
                                loaddaysyears(leaveid)
                            }
                        }
                    });
                }

            });
            $(document).on('click', '.deletesetupdaysyears', function() {
                var dataid = $(this).attr('data-id')

                $.ajax({
                    type: "GET",
                    url: "/leaves/setdaysyears/delete",
                    data: {
                        dataid: dataid
                    },
                    success: function(data) {
                        if (data == 0) {
                            toastr.error('Something Wrong!')
                        } else {
                            toastr.success('Deleted successfully!')
                            loaddaysyears(leaveid)
                        }
                    }
                });
            });

            // $(document).on('click', '.add-row-inputs', function () {
            //     var newRow = '<tr>' +
            //         '<td><input type="text" class="input-years" name="years[]" class="form-control"></td>' +
            //         '<td><input type="text" class="input-days" name="days[]" class="form-control"></td>' +
            //         '<td class="text-right"><button class="btn btn-sm btn-primary delete-row"><i class="fas fa-trash"></i></button></td>' +
            //         '</tr>';
            //     $('#daysyears-table').append(newRow);
            // });

            // Delete row when delete icon is clicked
            // $(document).on('click', '.delete-row', function () {
            //     $(this).closest('tr').remove();
            //     return false;
            // });


            $(document).on('click', '.btn-deleteleave', function() {
                var leaveid = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure you want to delete this leave type?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/hr/settings/leaves?action=deleteleave',
                            type: 'GET',
                            data: {
                                id: leaveid
                            },
                            complete: function() {
                                $(".swal2-container").remove();
                                $('body').removeClass('swal2-shown')
                                $('body').removeClass('swal2-height-auto')
                                toastr.success('Deleted successfully!',
                                    'Leave Settings')
                                loaddata()
                            }
                        })
                    }
                })
            })
            $(document).on('click', '.btn-addmoredates', function() {
                $('#modal-addmoredates').modal('show')
                leaveid = $(this).attr('data-leaveid');
            })
            $(document).on('click', '#btn-submit-moredates', function() {
                leavedates = [];
                $('.input-adddatefrom').each(function() {
                    if ($(this).val().replace(/^\s+|\s+$/g, "").length > 0) {
                        obj = {
                            'datefrom': $(this).val(),
                            'dateto': $(this).closest('.row').find('.input-adddateto').val()
                        }
                        leavedates.push(obj)
                    }
                })
                if (leavedates.length > 0) {
                    $.ajax({
                        url: '/hr/settings/leaves?action=addmoredays',
                        type: "get",
                        data: {
                            leavedates: JSON.stringify(leavedates),
                            id: leaveid,
                        },
                        success: function(data) {
                            $('.btn-close').click();
                            toastr.success('Updated successfully!')
                            loaddata()
                            leavedates = [];
                            $('#div-addmoredates').empty()
                        }
                    });
                }
            })
            $(document).on('click', '.btn-addmoreemployees', function() {
                $('#modal-addmoreemployees').modal('show')
                leaveid = $(this).attr('data-leaveid');
            })
            $('#btn-submit-moreemployees').on('click', function() {
                leaveemployees = $('#select-moreemployees').val();
                var eachapprovals = $('#select-approvals').val();

                if (leaveemployees.length > 0) {
                    if (eachapprovals.length == 0) {
                        toastr.warning('Please fill in required fields!')
                        $('#select-approvals').css('border', '1px solid red')
                    } else {
                        $.ajax({
                            url: '/hr/settings/leaves?action=addmoreemployees',
                            type: "get",
                            data: {
                                leaveemployees: JSON.stringify(leaveemployees),
                                eachapprovals: JSON.stringify(eachapprovals),
                                id: leaveid,
                            },
                            success: function(data) {
                                toastr.success('Updated successfully!')
                                loaddata()
                                $('.btn-close').click();
                                leaveemployees = [];
                                $('#select-moreemployees').val('')
                            }
                        });
                    }
                }
            })
            $(document).on('click', '.btn-delete-date', function() {
                var id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure you want to delete this date?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/hr/settings/leaves?action=deletedate',
                            type: 'GET',
                            data: {
                                id: id
                            },
                            complete: function() {
                                $(".swal2-container").remove();
                                $('body').removeClass('swal2-shown')
                                $('body').removeClass('swal2-height-auto')
                                toastr.success('Deleted successfully!',
                                    'Leave Settings')
                                loaddata()
                            }
                        })
                    }
                })
            })
            $(document).on('click', '.btn-delete-employee', function() {
                var id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure you want to delete this employee?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/hr/settings/leaves?action=deleteemployee',
                            type: 'GET',
                            data: {
                                id: id
                            },
                            complete: function() {
                                $(".swal2-container").remove();
                                $('body').removeClass('swal2-shown')
                                $('body').removeClass('swal2-height-auto')
                                toastr.success('Deleted successfully!',
                                    'Leave Settings')
                                loaddata()
                            }
                        })
                    }
                })
            })
            $(document).on('click', '.btn-view-approvals', function() {
                var leaveid = $(this).attr('data-leaveid');
                $('#modal-view-approvals').modal('show')
                $.ajax({
                    url: '/hr/settings/leaves?action=getapprovals',
                    type: 'GET',
                    data: {
                        leaveid: leaveid
                    },
                    success: function(data) {
                        $('#approval-container').empty();
                        $('#approval-container').append(data);
                        $('.select2bs4').select2({
                            theme: 'bootstrap4'
                        })
                        $('#btn-submit-moreapprovals').hide();
                        $('#btn-submit-moreapprovals').attr('data-leaveid', leaveid);
                    }
                })
            })
            $(document).on('change', '#select-moreapprovals', function() {
                if ($(this).val() == "") {
                    $('#btn-submit-moreapprovals').hide();
                } else {
                    $('#btn-submit-moreapprovals').show();
                }
            })
            $(document).on('click', '#btn-submit-moreapprovals', function() {
                var leaveid = $(this).attr('data-leaveid');
                var moreapprovals = $('#select-moreapprovals').val();
                $.ajax({
                    url: '/hr/settings/leaves?action=addmoreapprovals',
                    type: "get",
                    data: {
                        moreapprovals: JSON.stringify(moreapprovals),
                        leaveid: leaveid,
                    },
                    success: function(data) {
                        toastr.success('Updated successfully!')
                        loaddata()
                        $('.btn-close').click();
                        $('#select-moreapprovals').val('')
                        $('.btn-view-approvals[data-leaveid="' + leaveid + '"]').click()
                    }
                });
            })
            $(document).on('click', '.btn-delete-approval', function() {
                var approvalid = $(this).attr('data-id');
                var thisrow = $(this).closest('tr');
                Swal.fire({
                    title: 'Are you sure you want to delete this employee?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/hr/settings/leaves?action=deleteapproval',
                            type: 'GET',
                            data: {
                                approvalid: approvalid
                            },
                            complete: function(data) {
                                $(".swal2-container").remove();
                                $('body').removeClass('swal2-shown')
                                $('body').removeClass('swal2-height-auto')
                                toastr.success('Deleted successfully!',
                                    'Approval Settings')
                                thisrow.remove()
                            }
                        })
                    }
                })
            })

            $(document).on('input', '.inputyears', function() {
                var dataid = $(this).attr('data-id')
                var years = $(this).val()
                if (years == null || years == '') {
                    years = 0
                }

                $.ajax({
                    type: "GET",
                    url: "/leaves/setdaysyears/edityear",
                    data: {
                        dataid: dataid,
                        years: years
                    },
                    success: function(data) {
                        if (data == 0) {
                            toastr.error('Years Already Exist!')
                        } else {
                            toastr.success('Updated successfully!')
                            loaddaysyears(leaveid)
                        }
                    }
                });
            })

            $(document).on('input', '.inputdays', function() {
                var leaveid = $('#leaveid').val()
                var dataid = $(this).attr('data-id')
                var days = $(this).val()
                if (days == null || days == '') {
                    days = 0
                }
                console.log(leaveid);
                $.ajax({
                    type: "GET",
                    url: "/leaves/setdaysyears/editdays",
                    data: {
                        dataid: dataid,
                        days: days
                    },
                    success: function(data) {
                        if (data == 0) {
                            toastr.error('Something Wrong!')
                        } else {
                            toastr.success('Updated successfully!')
                            loaddaysyears(leaveid)
                        }
                    }
                });
            })
            window.setTimeout(function() {
                $(".alert-success").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 5000);
            window.setTimeout(function() {
                $(".alert-danger").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 5000);


            //  function load 

            function loaddaysyears(leaveid) {
                $.ajax({
                    type: "GET",
                    url: "/leaves/setdaysyears/loaddaysyears",
                    data: {
                        leaveid: leaveid
                    },
                    success: function(data) {
                        daysyears = data
                        console.log(daysyears);

                        var table = $('<table width="100%" class="table-sm">');
                        table.append(`<thead>
                                    <tr>
                                        <th width="30%">YEARS</th>
                                        <th width="30%">DAYS</th>
                                        <th class="text-center" width="10%">ACTION</th>
                                    </tr>
                                </thead>`);
                        var tbody = $('<tbody>');
                        $.each(data, function(index, item) {
                            var row = $('<tr>');
                            row.append('<td><input type="text" class="inputyears" value="' +
                                item.years + '" data-id="' + item.id +
                                '" style="border: none; outline: none;" /></td>');
                            row.append('<td><input type="text" class="inputdays" value="' + item
                                .days + '" data-id="' + item.id +
                                '" style="border: none; outline: none;" /></td>');
                            row.append(`<td class="text-center">
                                        <div style="display: inline-flex;">
                                            <button type="button" class="btn btn-danger btn-sm deletesetupdaysyears" data-id="${item.id}"><i class="fas fa-trash"></i></button>
                                        </div>  
                                    </td>`);
                            tbody.append(row);
                        })

                        table.append(tbody);
                        $('.container-daysyears').empty().append(table);
                    }
                });
            }
        });
        $(document).on('click', 'input[name=withpay]', function() {
            $(this).closest('form').submit();
        })
    </script>
@endsection
