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
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }
    </style>
@endsection


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Billing Information</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Billing Information</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content pt-0">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-filter"></i></span>
                        <div class="info-box-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="info-box-text">Enrollment</label>
                                    <span class="info-box-number">
                                        <select class="form-control form-control-sm select2" id="filter_sy"></select>
                                    </span>
                                </div>
                                <div class="col-md-6" hidden>
                                    <span class="info-box-text">Semester</span>
                                    <span class="info-box-number">
                                        <select class="form-control" id="filter_sem">
                                            @php
                                                $sy = DB::table('semester')->get();
                                            @endphp
                                            @foreach ($sy as $item)
                                                @php
                                                    $selected = '';
                                                    if ($item->isactive == 1) {
                                                        $selected = 'selected="selected"';
                                                    }
                                                @endphp
                                                <option value="{{ $item->id }}" {{ $selected }}
                                                    value="{{ $item->id }}">{{ $item->semester }}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-2" hidden>
                    <div class="info-box">
                        <div class="info-box-content">
                            <span class="info-box-text">Total Annual Fee</span>
                            <span class="info-box-number" id="tuition">
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <div class="info-box mb-3">
                        <div class="info-box-content">
                            <span class="info-box-text">Balance</span>
                            <span class="info-box-number" id="balance"></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-2">
                    <div class="info-box mb-3">
                        <div class="info-box-content">
                            <span class="info-box-text">Amount Paid</span>
                            <span class="info-box-number" id="paid"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fas fa-book"></i> Student Ledger</h3>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-sm font-sm table-head-fixed table-striped" width="100%"
                                style="font-size:.rem; min-width:500px !important">
                                <thead>
                                    <tr>
                                        <th width="50%">Particulars</th>
                                        <th width="25%" class="text-right">Charges</th>
                                        <th width="25%" class="text-right">Payment</th>
                                    </tr>
                                </thead>
                                <tbody id="student_ledger">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" hidden>
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-layer-group"></i> One Time Payables</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm font-sm table-head-fixed  table-striped" style="font-size:.9rem;">
                                <thead>
                                    <tr>
                                        <th width="70%">PARTICULARS</th>
                                        <th width="30%" class="text-right">BALANCE</th>
                                    </tr>
                                </thead>
                                <tbody id="student_onetime">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-calendar-check"></i> Monthly Payables</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm font-sm table-head-fixed table-striped" style="font-size:.9rem;">
                                <thead>
                                    <tr>
                                        <th width="70%">PARTICULARS</th>
                                        <th width="30%" class="text-right">BALANCE</th>
                                    </tr>
                                </thead>
                                <tbody id="student_monthly">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            var enrollmentinfo = []

            $(document).on('change', '#filter_sy', function() {
                load_billing()
                get_ledger()
            })


            get_enrollment_history()

            function get_enrollment_history() {
                $.ajax({
                    type: 'GET',
                    url: '/payment/balanceinfo',
                    success: function(data) {

                        var active_sy = null
                        $.each(data, function(a, b) {
                            if (b.isactive == 1) {
                                active_sy = b
                            }
                        })


                        $('#filter_sy').empty()
                        $('#filter_sy').select2({
                            data: data,
                            placeholder: 'Select Enrollment',
                        })

                        enrollmentinfo = data
                        if (active_sy) {
                            $('#filter_sy').val(active_sy.id).change()
                        }

                        console.log('ACTIVE SY', $('#filter_sy').val());
                        get_ledger()
                    }

                })
            }




            function load_billing() {
                $('#student_monthly').empty();
                $('#student_onetime').empty();
                $('#tuition').empty();


                if ($('#filter_sy').val() != null && $('#filter_sy').val() != '') {
                    var val_info = $('#filter_sy').val().split('-');
                } else {
                    return false;
                }

                var temp_sy = val_info[0]
                var temp_sem = val_info[1]

                var tempinfo = enrollmentinfo.filter(x => x.syid == temp_sy && x.semid == temp_sem)

                console.log(tempinfo)

                $.ajax({
                    type: 'GET',
                    url: '/current/billingassesment',
                    data: {
                        syid: temp_sy,
                        semid: temp_sem,
                        levelid: tempinfo[0].levelid
                    },
                    success: function(data) {
                        console.log('Billing', data);
                        var monthly = data.filter(x => x.duedate != null)
                        var onetime = data.filter(x => x.duedate == null)
                        var total = 0
                        var balance = 0
                        var overall_total = 0
                        $.each(monthly, function(a, b) {
                            total = parseFloat(total) + parseFloat(b.amountdue.replace(",", ""))
                            balance = parseFloat(balance) + parseFloat(b.balance.replace(",",
                                ""))
                            $('#student_monthly').append('<tr><td >' + b.particulars +
                                '</td><td class="text-right align-middle">&#8369; ' + b
                                .balance +
                                '</td></tr>')
                        })

                        $('#student_monthly').append(
                            '<tr class="bg-info"><td>TOTAL BALANCE</td><td class="text-right">&#8369; ' +
                            balance.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,") + '</td></tr>')

                        overall_total = parseFloat(overall_total) + parseFloat(total)

                        var total = parseFloat(0)
                        var balance = parseFloat(0)

                        $.each(onetime, function(a, b) {
                            total = parseFloat(total) + parseFloat(b.amountdue.replace(",", ""))
                            balance = parseFloat(balance) + parseFloat(b.balance.replace(",",
                                ""))
                            $('#student_onetime').append('<tr><td >' + b.particulars +
                                '</td><td class="text-right align-middle">&#8369; ' + b
                                .balance.replace(/(\d)(?=(\d{3})+\.)/g, "$1,") +
                                '</td></tr>')
                        })

                        $('#student_onetime').append(
                            '<tr class="bg-info"><td>TOTAL BALANCE</td><td class="text-right align-middle">&#8369; ' +
                            balance.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,") + '</td></tr>')

                        overall_total = parseFloat(overall_total) + parseFloat(total)

                        $('#tuition')[0].innerHTML = '&#8369; ' + overall_total.toFixed(2).replace(
                            /(\d)(?=(\d{3})+\.)/g, "$1,")

                    }
                })
            }

            // get_ledger()

            function get_ledger() {
                $('#balance').empty();
                $('#paid').empty();
                $('#student_ledger').empty();
                $('#balance')[0].innerHTML = '&#8369; 0.00'
                $('#paid')[0].innerHTML = '&#8369; 0.00'

                if ($('#filter_sy').val() != null && $('#filter_sy').val() != '') {
                    var val_info = $('#filter_sy').val().split('-');
                } else {
                    $('#balance')[0].innerHTML = '&#8369; ' + '0.00';
                    return false;
                }

                var temp_sy = val_info[0]
                var temp_sem = val_info[1]

                var tempinfo = enrollmentinfo.filter(x => x.syid == temp_sy && x.semid == temp_sem)

                if (tempinfo.length == 0) {
                    $('#balance')[0].innerHTML = '&#8369; ' + '0.00';
                    return false;
                }

                $.ajax({
                    type: 'GET',
                    url: '/ledger',
                    data: {
                        syid: temp_sy,
                        semid: temp_sem,
                        levelid: tempinfo[0].levelid
                    },
                    success: function(data) {
                        console.log('Ledger', data);

                        if (data.length > 0) {
                            $('#student_ledger').empty()
                            var total_amount = 0;
                            var total_payment = 0;
                            var total_balance = 0
                            var abalance = parseFloat(0).toFixed(2)

                            var total_payment = parseFloat(0).toFixed(2)

                            var tolal_charges_ledger = parseFloat(0).toFixed(2)
                            var tolal_payment_ledger = parseFloat(0).toFixed(2)
                            var runbal = 0;

                            $.each(data, function(a, b) {

                                var ornum = ''
                                if (b.ornum != '') {
                                    ornum = b.ornum
                                }
                                runbal += parseFloat(b.amount)
                                runbal -= parseFloat(b.payment)

                                if (b.amount > 0) {
                                    aamount = '&#8369;' + parseFloat(b.amount).toFixed(2)
                                        .replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                                } else {
                                    aamount = ''
                                }

                                if (b.payment > 0) {
                                    apayment = '&#8369;' + parseFloat(b.payment).toFixed(2)
                                        .replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                                } else {
                                    apayment = ''
                                }

                                if (b.ornum != null) {
                                    total_payment = parseFloat(total_payment) + parseFloat(b
                                        .payment)
                                }

                                tolal_charges_ledger = parseFloat(tolal_charges_ledger) +
                                    parseFloat(b.amount)
                                tolal_payment_ledger = parseFloat(tolal_payment_ledger) +
                                    parseFloat(b.payment)

                                $('#student_ledger').append('<tr><td >' + b.particulars +
                                    '</td><td class="text-right  align-middle">' + aamount +
                                    '</td><td class="text-right  align-middle">' +
                                    apayment + '</td></tr>')

                            })

                            $('#student_ledger').append(
                                '<tr><td class="text-right">TOTAL</td><td class="text-right  align-middle">&#8369; ' +
                                tolal_charges_ledger.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g,
                                    "$1,") + '</td><td class="text-right  align-middle">&#8369; ' +
                                tolal_payment_ledger.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g,
                                    "$1,") + '</td></tr>')

                            if (data.length != 0 || runbal != 0) {
                                runbal = parseFloat(runbal).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g,
                                    "$1,")
                                $('#student_ledger').append(
                                    '<tr class="bg-info"><td class="text-right">REMAINING BALANCE</td><td class="text-right">&#8369; ' +
                                    runbal + '</td><td></td></tr>')
                            } else {
                                $('#balance')[0].innerHTML = '&#8369; ' + '0.00';
                                runbal = parseFloat(0).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                                $('#student_ledger').append(
                                    '<tr class="bg-info"><td class="text-right">REMAINING BALANCE</td><td class="text-right">&#8369; ' +
                                    runbal + '</td><td></td></tr>')
                            }

                            $('#balance')[0].innerHTML = '&#8369; ' + runbal
                            $('#paid')[0].innerHTML = '&#8369; ' + parseFloat(total_payment).toFixed(2)
                                .replace(/(\d)(?=(\d{3})+\.)/g, "$1,")

                        } else {

                            $('#balance')[0].innerHTML = '&#8369; 0.00'
                            $('#paid')[0].innerHTML = '&#8369; 0.00'
                            runbal = parseFloat(0).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                            $('#student_ledger').append(
                                '<tr class="bg-info"><td class="text-right">REMAINING BALANCE</td><td class="text-right">&#8369; 0.00</td><td></td></tr>'
                            )
                        }
                    }
                })
            }
        })
    </script>
@endsection
