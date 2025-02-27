@extends('finance.layouts.app')

@section('content')

  	<section class="content">
    	<div class="row">

      		<div class="col-md-12">
                <div class="row">
                    <div class="col-md-10">
                        <h1 class="m-0 text-dark">
                        Disbursement
                        </h1>
                    </div>
                    <div class="col-md-2 text-right">
                    </div>
                </div>
                <br>
                <div class="row mb-3">
                    <div class="col-md-2">
                        <select id="filter_status" class="form-control filters">
                        <option value="all" selected>ALL</option>
                        <option value="submitted">SUBMITTED</option>
                        <option value="posted">POSTED</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                        <input id="datefrom" type="date" name="" class="form-control" value="{{date('Y-m-01', strtotime(App\FinanceModel::getServerDateTime()))}}">

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                        <input id="dateto" type="date" name="" class="form-control" value="{{date('Y-m-d', strtotime(App\FinanceModel::getServerDateTime()))}}">
                        <input id="datenow" type="date" hidden="" class="form-control" value="{{date('Y-m-d', strtotime(App\FinanceModel::getServerDateTime()))}}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group">
                            <input id="filter_search" type="text" class="form-control filters" placeholder="Search">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button id="d_create" class="btn btn-primary btn-block">Create</button>
                    </div>
                </div>
                <div class="main-card card">
                    <div class="card-header text-lg bg-primary">
                        <div class="row">
                            <div class="col-md-8">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 table-responsive table_main">
                                <table class="table table-hover table-sm text-sm">
                                    <thead class="">
                                        <th>Date</th>
                                        <th>Voucher No.</th>
                                        <th>Pay To</th>
                                        <th class="">Payment Type</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                    </thead>
                                    <tbody id="disbursement_list" style="cursor: pointer">

                                    </tbody>
                                    <tfoot>
                                        <tr id="d_list">

                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('modal')

    <div class="modal fade" id="modal-disbursement" aria-modal="true" style="display: none; margin-top: -25px; overflow-y: auto; height: 768px">
        <div class="modal-dialog" style="max-width: 83em;">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Disbursement</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                {{-- <h1 id="lblrefnum" class="text-secondary" data-toggle="tooltip" title="Reference Number"> Reference Number</h1> --}}
                                <input id="voucherno" type="text" class="form-control border-0 text-xl text-bold" placeholder="Voucher No.">
                                </div>
                                <div class="col-md-6">
                                <button id="d_print" class="btn btn-primary float-right"><i class="fas fa-print"></i> Print</button>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-2">
                                    <label for="">Source</label>
                                    <select id="d_source" name="" class="form-control select2">
                                        <option value="cash">Cash</option>
                                        <option value="pcash">Petty Cash</option>
                                        <option value="po">Purchase Order</option>

                                    </select>
                                </div>
                                <div id="div_ponumber" class="col-md-2" style="display: none">
                                    <label for="">PO Number</label>
                                    <select id="d_ponumber" name="" class="form-control select2">
                                    </select>
                                </div>
                                <div id="div_cash" class="col-md-2" style="display: block">
                                    <label for="">Reference No.</label>
                                    <input id="d_sourceref" name="" class="form-control">
                                </div>
                                <div id="" class="col-md-2" style="display: block">
                                    <label for="">Disburse to</label>
                                    <select id="d_payto" name="" class="form-control select2">
                                        <option value="supplier">Supplier</option>
                                        <option value="employee">Employee</option>
                                        <option value="others">Others</option>
                                    </select>
                                </div>
                                <div id="div_s_supplier" class="col-md-3" style="display: block">
                                    <div class="form-group input-group-lg">
                                        <label>Supplier</label>
                                        {{-- <input id="description" type="text" class="form-control form-control-lg text-lg validate is-invalid" placeholder="Description"> --}}
                                        <select id="d_supplier" class="select2" style="width: 100%;">
                                            <option value="0">SELECT SUPPLIER</option>
                                            @foreach(DB::table('expense_company')->where('deleted', 0)->get() as $supplier)
                                                <option value="{{$supplier->id}}">{{$supplier->companyname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @php
                                    $employees = DB::table('teacher')
                                        ->select('id', 'lastname', 'firstname', 'middlename', 'suffix')
                                        ->where('deleted', 0)
                                        ->orderBy('lastname')
                                        ->orderBy('firstname')
                                        ->get();
                                @endphp
                                <div id="div_s_employee" class="col-md-3" style="display: none">
                                    <div class="form-group input-group-lg">
                                        <label>Employee</label>
                                        {{-- <input id="description" type="text" class="form-control form-control-lg text-lg validate is-invalid" placeholder="Description"> --}}
                                        <select id="d_s_employee" class="select2" style="width: 100%;">
                                        @foreach($employees as $employee)
                                            <option value="{{$employee->id}}">{{$employee->lastname}}, {{ $employee->firstname }} {{ $employee->middlename }} {{ $employee->suffix }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="div_s_oth" class="col-md-3" style="display: none">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input id="d_s_oth" type="text" class="form-control" placeholder="">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input id="d_date" type="date" class="form-control" value="{{date_format(App\FinanceModel::getServerDateTime(), 'Y-m-d')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-2">
                                    <label>Invoice No.</label>
                                    <input id="d_invoceno" type="text" class="form-control">
                                </div>
                                    <div class="col-md-2">
                                    <label>Amount</label>
                                    <input id="d_amount" type="text" class="form-control text-right text-bold text-lg">
                                </div>

                                <div class="col-md-2">
                                    <label>Payment Type</label><br>
                                    <div class="form-group clearfix mt-2">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="d_cash" name="r1" checked="">
                                            <label for="d_cash">
                                                CASH
                                            </label>
                                        </div>&nbsp;&nbsp;&nbsp;
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="d_cheque" name="r1">
                                            <label for="d_cheque">
                                                CHEQUE
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label>Bank</label>
                                    <select id="d_bank" class="select2 grp_check" style="width: 100%;">
                                        <option value="0">Select Bank</option>
                                        @foreach(DB::table('acc_bank')->where('deleted', 0)->get() as $bank)
                                        <option value="{{$bank->id}}">{{$bank->bankname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Check No.</label>
                                    <input id="d_checkno" class="form-control grp_check" type="" name="">
                                </div>
                                <div class="col-md-2">
                                    <label>Check Date</label>
                                    <input id="d_checkdate" class="form-control grp_check" type="date" name="" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>ITEMS</label>
                                    <table class="table table-head-fixed table-sm text-sm">
                                        <thead>
                                        <th>Particulars</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-center">QTY</th>
                                        <th class="text-right">Total</th>
                                        <th></th>
                                        </thead>
                                        <tbody id="d_itemlist">
                                            <tr>
                                                <td colspan="5">
                                                    <u><a href="#" id="additem">Add item</a></u>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                               <th colspan="3" class="text-right text-bold">TOTAL:</th>
                                               <th class="text-right text-bold" id="d_itemtotalamount">0.00</th>
                                               <th style="width: 1px"></th>
                                            </tr>

                                        </tfoot>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>Remarks</label>
                                                    <textarea id="d_remarks" style="width: 100%;" class="form-control" rows="2" placeholder="Notes ..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-8"><h4>Journal Entry</h4></div>
                                                <div class="col-md-4 text-sm text-right">
                                                    <button id="d_addentry" class="btn btn-primary btn-sm">Add Entry</button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 table-responsive" style="height: 11em;">
                                                    <table class="table table-hover table-sm text-sm">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 60%;">ACCOUNT</th>
                                                                <th style="width: 20%;" class="text-center" >DEBIT</th>
                                                                <th style="width: 20%;" class="text-center" >CREDIT</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="d_jelist">

                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th class="text-right">TOTAL: </th>
                                                                <th id="d_debittotal" class="text-right">0.00</th>
                                                                <th id="d_credittotal" class="text-right">0.00</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                {{-- <div class="col-md-7 table-responsive" style="height: 12em;">
                                <table class="table table-hover table-sm text-sm">
                                    <thead>
                                    <tr>
                                        <th>RR No</th>
                                        <th>INVOICE</th>
                                        <th>RR DATE</th>
                                        <th>PAYMENT</th>
                                        <th>AMT. DUE</th>
                                        <th>AMT. PAID</th>
                                        <th>BALANCE</th>
                                        <th>PAYMENT</th>
                                    </tr>
                                    </thead>
                                    <tbody id="d_rrlist" style="cursor: pointer;"></tbody>
                                    {{-- <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">TOTAL:</th>
                                        <th class="text-right">0.00</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                </div> --}}
                                <div class="col-md-5">
                                    <div class="col-md-12">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="col-md-3">
                        <button class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    </div>

                    <div class="col-md-9 text-right">
                        <div id="div_posted" class="row" style="display: none">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <button class="btn btn-success btn-block" id="btn_posted" disabled><i class="fas fa-check-circle"></i> POSTED</button>
                            </div>
                                {{-- <div id="div_print" class="col-md-4" style="display: none">
                                    <button class="btn btn-primary btn-block" id="btn_print" ><i class="fas fa-print"></i> Print</button>
                                </div> --}}
                        </div>

                            {{-- <button class="btn btn-danger" id="btn_delete"><i class="fas fa-trash"></i> Delete</button> --}}
                        <button class="btn btn-success" id="d_post"><i class="fas fa-thumbtack"></i> Post</button>
                        <button class="btn btn-primary" id="d_save" po-id="0" r-id="0"><i class="fas fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-item" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-md" style="margin-top: 5px">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Item - <span id="itemaction" class="text-bold"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Item</label>
                                <div class="input-group">
                                    <select id="itemdesc" class="select2 form-control form-control-sm">
                                        @foreach(App\FinanceModel::expenseitems() as $item)
                                            <option value="{{$item->id}}">{{$item->description}}</option>
                                        @endforeach
                                    </select>

                                    {{-- <div class="input-group-append">
                                        <button id="btncreateItem" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Create Items">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="form-group">
                            <label>Amount</label>
                            <input id="itemamount" type="text" class="form-control form-control-sm calc">
                        </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>QTY</label>
                                <input id="itemqty" type="number" class="form-control form-control-sm calc">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Total</label>
                                <input id="totalamount" type="text" class="form-control form-control-sm" placeholder="0.00" disabled="">
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Remarks/Explanation</label>
                                <textarea id="item_remarks" type="text" class="form-control form-control-sm" placeholder=""></textarea>
                            </div>
                        </div>
                    </div> --}}
                    <hr>
                    <div class="row form-group">
                        <div class="col-md-8">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        <div class="col-md-2">
                            <button id="btndel" type="button" data-id="0" class="btn btn-danger" data-dismiss="modal">Delete</button>
                        </div>
                        <div class="col-md-2">
                            <button id="btnadd" type="button" data-id="0" class="btn btn-primary" data-dismiss="modal">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-item-new" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Expense Items - New</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-2 col-form-label">Item Code</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control validation" id="item-code" placeholder="Item Code" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control validation" id="item-desc" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-2 col-form-label">Classification</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2bs4" id=item-class>
                                        @foreach(App\FinanceModel::loadItemClass() as $itemclass)
                                        <option value="{{$itemclass->id}}">{{$itemclass->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-2 col-form-label">Amount</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control validation" id="item-amount" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <!-- /.card-footer -->
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="saveNewItem" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade" id="modal-overlay" data-backdrop="static" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content bg-gray-dark" style="opacity: 78%; margin-top: 15em">
                <div class="modal-body" style="height: 250px">
                    <div class="row">
                        <div class="col-md-12 text-center text-lg text-bold b-close">
                            Please Wait
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="loader"></div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: -30px">
                        <div class="col-md-12 text-center text-lg text-bold">
                            Processing...
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
  </div>



@endsection
@section('js')

    <style>
        .loader{
            width: 100px;
            height: 100px;
            margin: 50px auto;
            position: relative;
        }
        .loader:before,
        .loader:after{
            content: "";
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: solid 8px transparent;
            position: absolute;
            -webkit-animation: loading-1 1.4s ease infinite;
            animation: loading-1 1.4s ease infinite;
        }
        .loader:before{
            border-top-color: #d72638;
            border-bottom-color: #07a7af;
        }
        .loader:after{
            border-left-color: #ffc914;
            border-right-color: #66dd71;
            -webkit-animation-delay: 0.7s;
            animation-delay: 0.7s;
        }
        @-webkit-keyframes loading-1{
            0%{
                -webkit-transform: rotate(0deg) scale(1);
                transform: rotate(0deg) scale(1);
            }
            50%{
                -webkit-transform: rotate(180deg) scale(0.5);
                transform: rotate(180deg) scale(0.5);
            }
            100%{
                -webkit-transform: rotate(360deg) scale(1);
                transform: rotate(360deg) scale(1);
            }
        }
        @keyframes loading-1{
            0%{
                -webkit-transform: rotate(0deg) scale(1);
                transform: rotate(0deg) scale(1);
            }
            50%{
                -webkit-transform: rotate(180deg) scale(0.5);
                transform: rotate(180deg) scale(0.5);
            }
            100%{
                -webkit-transform: rotate(360deg) scale(1);
                transform: rotate(360deg) scale(1);
            }
        }
    </style>

    <script>
    // Jquery Dependency

        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });


        function formatNumber(n) {
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }


        function formatCurrency(input, blur) {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.

            // get input value
            var input_val = input.val();

            // don't validate empty input
            if (input_val === "") { return; }

            // original length
            var original_len = input_val.length;

            // initial caret position
            var caret_pos = input.prop("selectionStart");

            // check for decimal
            if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);

            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }

            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val = left_side + "." + right_side;

            } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);
            input_val = input_val;

            // final formatting
            if (blur === "blur") {
                input_val += ".00";
            }
            }

            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
    </script>

    <script type="text/javascript">

        $(document).ready(function(){

            $('.select2').select2({
                theme: 'bootstrap4'
            });

            function formatnumber(number)
            {
                return parseFloat(number, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
            }

            function padWithLeadingZeros(number, totalLength) {
                return String(number).padStart(totalLength, '0');
            }

            // $('.d_rrpayment').keypress(function(event) {
            $(document).on('keypress', '.currency', function(event){
                var allowedCharacters = /[0-9.,]/;
                var input = String.fromCharCode(event.which);

                if (!allowedCharacters.test(input)) {
                event.preventDefault();
                return false;
                }
            })

            var acc_coa = '';

            getcoa()

            function getcoa()
            {
                $.ajax({
                type: "GET",
                url: "{{route('expenses_gencoa')}}",
                // data: "data",
                // dataType: "dataType",
                success: function (data) {
                    acc_coa = ''
                    acc_coa = data
                }
                });
            }

            function disbursement_load()
            {
                d_status = $('#filter_status').val()
                datefrom = $('#datefrom').val()
                dateto = $('#dateto').val()
                search = $('#filter_search').val()
                filter = $('#filter_status').val()

                $.ajax({
                type: "GET",
                url: "{{route('disbursement_load')}}",
                data: {
                    d_status:d_status,
                    datefrom:datefrom,
                    dateto:dateto,
                    search:search,
                    filter:filter
                },
                // dataType: "dataType",
                success: function (data){
                    $('#disbursement_list').empty()

                    $.each(data, function(index, val) {
                        remarks = (val.remarks == null) ? '' : val.remarks
                        reference = (val.voucherno == null) ? '' : val.voucherno
                        $('#disbursement_list').append(`
                            <tr data-id="`+val.id+`">
                            <td>`+moment(val.transdate).format('MM/DD/YYYY')+`</td>
                            <td>`+padWithLeadingZeros(reference, 6)+`</td>
                            <td>`+val.supplier+`</td>
                            <td>`+val.paytype+`</td>
                            <td>`+val.trxstatus+`</td>
                            <td>`+remarks+`</td>
                            </tr>
                        `)
                    });
                }
                })
            }

            disbursement_load()

            $(document).on('change', '.currency', function() {
                var amount = $(this).val().replace(/,/g, '')
                // console.log('amount: ' + amount)
                $(this).val(parseFloat(amount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
            });

            $('.select2rr').select2({
                theme: 'bootstrap4',
            });

            screenadjust();

            function screenadjust()
            {
                var screen_height = $(window).height();
                $('.table_main').css('height', screen_height - 300);
                $('.table_setup').css('height', screen_height - 450);

            }

            $(window).resize(function(){
                screenadjust();
            })

            $('#d_source').trigger('change')

            $(document).on('change', '#d_source', function(){
                var source = $(this).val();

                if($(this).val() == 'po')
                {
                    $('#div_ponumber').show();
                    $('#div_cash').hide()

                    $('#modal-overlay').modal('show')

                    $.ajax({
                        type: "GET",
                        url: "{{ route('disbursement_getponumber') }}",
                        data: {
                            source:source
                        },
                        // dataType: "dataType",
                        success: function (data) {
                            $('#d_ponumber').empty()
                            $('#d_payto').val('supplier')
                            $('#d_payto').trigger('change')

                            $('#d_ponumber').append(`
                                <option value="0">SELECT PO NUMBER</option>
                            `)

                            $.each(data, function (indexInArray, value) {
                                $('#d_ponumber').append(`
                                    <option value="`+value.id+`">`+value.refno+`</option>
                                `)
                            });

                            setTimeout(() => {
                                $('#modal-overlay').modal('hide')
                            }, 500);
                        }
                    });
                }
                else{
                    $('#div_ponumber').hide();
                    $('#div_cash').show()
                }


            })

            $(document).on('click', '#additem', function(){
                $('#modal-item').modal('show');
                $('#itemaction').text('Add');
                $('#itemdesc').val('');
                $('#itemdesc').trigger('change');
                $('#itemqty').val('1');
                $('#itemamount').val('');
                $('#totalamount').val('');
                $('#btnadd').attr('data-id', 0);
                $('#btndel').hide();

            });



            $(document).on('click', '#d_create', function(){
                checkpaytype()
                d_create()
                disablePOfields('false')
                // $.ajax({
                // type: "GET",
                // url: "{{route('disbursement_loadsupplier')}}",
                // data: {

                // },
                // // dataType: "dataType",
                // success: function (data){
                //     $('#d_supplier').empty()
                //     $('#d_supplier').append(`
                //         <option value="0">SELECT SUPPLIER</option>
                //     `)
                //     $.each(data, function(index, val) {
                //     $('#d_supplier').append(`
                //         <option value="`+val.id+`">`+val.text+`</option>
                //     `)
                //     });

                //     // postdisplay('')
                // }
                // })

                $('#modal-disbursement').modal('show')
            })



            $(document).on('change', '#d_supplier', function(){
                var supplierid = $(this).val()
                var disbursementid = $('#d_save').attr('data-id')

                $.ajax({
                type: "GET",
                url: "{{route('disbursement_loadrr')}}",
                data: {
                    supplierid:supplierid,
                    disbursementid:disbursementid
                },
                // dataType: "dataType",
                success: function (data){
                    $('#d_rrlist').empty()
                    $.each(data, function(index, val) {
                    balance = val.balance.replace(/,/g, '')

                    $('#d_rrlist').append(`
                        <tr data-id="`+val.ddid+`" rr-id="`+val.id+`" headerid="`+val.headerid+`">
                        <td class="align-middle">`+val.refnum+`</td>
                        <td class="align-middle">`+val.invoice+`</td>
                        <td class="align-middle">`+val.rrdate+`</td>
                        <td class="align-middle">`+val.paytype+`</td>
                        <td class="text-right align-middle">`+val.amount+`</td>
                        <td class="text-right align-middle">`+val.paidamount+`</td>
                        <td class="text-right align-middle balance">`+val.balance+`</td>
                        <td class="text-right" style="width: 8em">
                            <input class="form-control form-control-sm d_rrpayment currency" data-maxpay="`+balance+`" value="`+val.payment+`" />
                        </td>
                        </tr>
                    `)
                    });
                }
                })
            })

            $(document).on('click', '#d_save', function(){
                var voucherno = $('#voucherno').val()
                var id = $(this).attr('data-id')

                var source =  $('#d_source').val()
                var poid = $('#d_ponumber').val()
                var sourceref = $('#d_sourceref').val()

                var payto = $('#d_payto').val()
                var supplierid = $('#d_supplier').val()
                var employeeid = $('#d_s_employee').val()
                var othname = $('#d_s_oth').val()

                var invoiceno = $('#d_invoceno').val()
                var amount = $('#d_amount').val()
                var date = $('#d_date').val()
                var bankid = $('#d_bank').val()
                var checkno = $('#d_checkno').val()
                var checkdate = $('#d_checkdate').val()
                var remarks = $('#d_remarks').val()

                var rr_array = []
                var jearray = []
                var itemarray = []

                $('#d_save').prop('disabled', true)

                if($('#d_cash').prop('checked') == true)
                {
                    var paytype = 'CASH'
                }
                else{
                    var paytype = 'CHEQUE'
                }

                $('#d_jelist tr').each(function(){
                    var djeid = $(this).attr('data-id')
                    var glid = $(this).find('.d_account').val()
                    var debit = $(this).find('.d_debit').val()
                    var credit = $(this).find('.d_credit').val()

                    var jeobj = {
                        djeid:djeid,
                        glid:glid,
                        debit:debit,
                        credit:credit
                    }

                    if(glid != null)
                    {
                        jearray.push(jeobj)
                    }
                })

                $('#d_itemlist tr').each(function(){
                    var dataid = $(this).attr('data-id')
                    var itemid = $(this).attr('item-id')
                    var qty = $(this).attr('item-qty')
                    var amount = $(this).attr('item-amount')


                    var items = {
                        dataid:dataid,
                        itemid:itemid,
                        qty:qty,
                        amount:amount
                    }

                    itemarray.push(items)

                })

                $('#modal-overlay').modal('show');

                $.ajax({
                    url: '{{route('disbursement_save')}}',
                    type: 'GET',
                    // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                    data: {
                        id:id,
                        voucherno:voucherno,
                        source:source,
                        poid:poid,
                        sourceref:sourceref,
                        payto:payto,
                        supplierid:supplierid,
                        employeeid:employeeid,
                        othname:othname,
                        date:date,
                        invoiceno:invoiceno,
                        amount:amount,
                        paytype:paytype,
                        bankid:bankid,
                        checkno:checkno,
                        checkdate:checkdate,
                        remarks:remarks,
                        jearray:jearray,
                        itemarray:itemarray

                    },
                    success:function(data)
                    {
                        $('#lblrefnum').text(data.refnum)
                        $('#d_save').attr('data-id', data.dataid)
                        $('#d_supplier').trigger('change')
                        $('#d_post').show();

                        disbursement_load()
                        disbursement_loadje(data.dataid)

                        const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                        })

                        Toast.fire({
                        type: 'success',
                        title: 'Disbursement Successfully Saved'
                        })

                        $('#d_save').prop('disabled', false)
                        setTimeout(() => {
                            $('#modal-overlay').modal('hide')
                        }, 500);
                    }
                });


            })

            $(document).on('click', '#disbursement_list tr', function(){
                var dataid = $(this).attr('data-id')

                $.ajax({
                // async: false,
                type: "GET",
                url: "{{route('disbursement_read')}}",
                data: {
                    dataid:dataid
                },
                // dataType: "dataType",
                success: function (data){
                    $('#modal-overlay').modal('show')
                    // $('#lblrefnum').text(data.refnum)

                    $('#voucherno').val(padWithLeadingZeros(data.voucherno, 6))
                    $('#d_date').val(data.transdate)
                    $('#d_checkno').val(data.checkno)
                    $('#d_checkdate').val(data.checkdate)
                    $('#d_remarks').val(data.remarks)
                    $('#d_bank').val(data.bankid).trigger('change')

                    $('#d_source').val(data.source).change();

                    if(data.source == 'po')
                    {
                        setTimeout(() => {
                            $('#d_ponumber').val(data.poid).change()
                            console.log('AMOUNT: ' + data.amount)
                        }, 2000);
                    }
                    else{
                        disbursement_loaditems(dataid)
                    }

                    setTimeout(() => {
                        $('#d_payto').val(data.disburseto).change();
                    }, 2000);

                    $('#d_invoceno').val(data.invoiceno)
                    $('#d_amount').val(formatnumber(data.amount))
                    $('#d_sourceref').val(data.sourceref)
                    $('#d_s_oth').val(data.othname)


                    if(data.paytype == 'CHEQUE')
                    {
                        $('#d_cheque').prop('checked', true)
                    }
                    else{
                        $('#d_cash').prop('checked', true)
                    }

                    disbursement_loadje(dataid)


                    $('#d_jelist').prop('disabled', true)



                    $('#d_jelist').empty()
                    $('#d_itemlist').empty()

                    if(data.source != 'po' && data.trxstatus != 'POSTED')
                    {
                        $('#d_itemlist').append(`
                            <tr>
                                <td colspan="4">
                                    <u><a href="#" id="additem">Add item</a></u>
                                </td>
                            </tr>
                        `)
                    }

                    $('#d_save').attr('data-id', data.id)
                    $('#d_save').attr('po-id', data.poid)
                    $('#d_supplier').val(data.supplierid)
                    $('#d_supplier').trigger('change')

                    setTimeout(function(){
                        postdisplay(data.trxstatus)
                        $('#modal-disbursement').modal('show')
                        $('#modal-overlay').modal('hide')
                        // $('#d_post').show()
                        disablePOfields('true')
                    }, 3000)



                }
                })


            })

            function disablePOfields(prop)
            {
                prop = prop === 'true'
                $('#d_source').prop('disabled', prop)
                $('#d_ponumber').prop('disabled', prop)
                $('#d_payto').prop('disabled', prop)
                $('#d_supplier').prop('disabled', prop)
                $('#d_amount').prop('disabled', prop)
            }

            function appendje()
            {
                $('#d_jelist').append(`
                <tr data-id="0">
                    <td>
                    <select class="select2 d_account">
                        `+acc_coa+`
                    </select>
                    </td>
                    <td>
                    <input class="form-control form-control-sm d_debit text-right currency" value="0.00">
                    </td>
                    <td>
                    <input class="form-control form-control-sm d_credit text-right currency" value="0.00">
                    </td>
                    <td class="text-sm">
                    <button class="btn btn-danger btn-sm btn_remove"><i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
                `)

                $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
                });
            }

            $(document).on('focusin', '.currency', function(){
                $(this).select()
            })

            $(document).on('change', '.d_debit', function(){
                totaldebit = 0
                $('#d_jelist tr').each(function(){
                camount = $(this).find('.d_debit').val()

                // console.log('d_debit: ' + $(this).find('.d_debit').val() )

                if(camount == null || camount == '')
                {
                    camount = 0.00
                }
                else{
                    camount = camount.toString().replace(/,/g, '')
                }

                // console.log('camount: ' + camount )

                // camount = parseFloat(camount)
                totaldebit += parseFloat(camount)
                // totaldebit = totaldebit.replace(/,/g, '')

                // console.log('totaldebit: ' + totaldebit )

                $('#d_debittotal').text(parseFloat(totaldebit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
                })
            })

            $(document).on('change', '.d_credit', function(){
                var totalcredit = 0
                $('#d_jelist tr').each(function(){
                var camount = $(this).find('.d_credit').val()

                if(camount == null || camount == '')
                {
                    camount = 0.00
                }
                else{
                    camount = camount.toString().replace(/,/g, '')
                }

                totalcredit += parseFloat(camount)
                //   camount = camount.toString().replace(/,/g, '')
                //   totalcredit += parseFloat(camount)
                //   totalcredit = totalcredit.toString().replace(/,/g, '')

                // console.log('totalcredit: ' + totalcredit)

                $('#d_credittotal').text(parseFloat(totalcredit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
                })
            })

            $(document).on('click', '#d_addentry', function(){
                appendje()
            })

            function disbursement_loadje(headerid)
            {
                $.ajax({
                // async: false,
                url: '{{route('disbursement_loadje')}}',
                type: 'GET',
                // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                data: {
                    headerid:headerid
                },
                success:function(data)
                {
                    $('#d_jelist tr').empty()
                    $('#d_debittotal').text('0.00')
                    $('#d_credittotal').text('0.00')

                    $.each(data, function(index, val) {
                    $('#d_jelist').append(`
                        <tr data-id="`+val.id+`">
                        <td>
                            <select class="select2 d_account">
                            `+acc_coa+`
                            </select>
                        </td>
                        <td>
                            <input class="form-control form-control-sm d_debit text-right currency" value="`+val.debit+`">
                        </td>
                        <td>
                            <input class="form-control form-control-sm d_credit text-right currency" value="`+val.credit+`">
                        </td>
                        <td class="text-sm">
                            <button class="btn btn-danger btn-sm btn_remove"><i class="far fa-trash-alt"></i></button>
                        </td>
                        </tr>
                    `)

                    $('.select2').select2({
                        theme: 'bootstrap4',
                        width: '100%'
                    });
                    });

                    $.each(data, function(index, val){
                        $('#d_jelist tr[data-id="'+val.id+'"]').find('.d_account').val(val.glid).trigger('change')
                        // $('#d_jelist tr').find('.d_account').trigger('change')
                    })

                    // $('.debit').trigger('change')
                    // $('.credit').trigger('change')
                    $('.currency').trigger('change')

                    // postdisplay('POSTED')

                }
                });

            }

            function disbursement_loaditems(headerid)
            {
                $.ajax({
                    type: "GET",
                    url: "{{ route('disbursement_loaditem') }}",
                    data: {
                        headerid:headerid
                    },
                    success: function (data) {
                        $.each(data, function (indexInArray, val) {
                            $('#d_itemlist').append(`
                                <tr data-id="`+val.id+`" item-id="`+val.itemid+`" item-amount="`+val.amount+`" item-qty="`+val.qty+`">
                                    <td>`+val.itemname+`</td>
                                    <td class="text-right">`+formatnumber(val.amount)+`</td>
                                    <td class="text-center">`+val.qty+`</td>
                                    <td class="text-right">`+formatnumber(val.totalamount)+`</td>
                                    <td><button class="btn btn-danger btn-sm btn_remove"><i class="far fa-trash-alt"></i></button></td>
                                </tr>
                            `)
                        });

                        getitemtotalamount()
                    }
                });
            }

            function d_create()
            {
                var date = moment().format('YYYY-MM-DD')
                $('#lblrefnum').text('Reference Number')
                $('#d_date').val(date)
                $('#d_checkno').val('')
                $('#d_checkdate').val('')
                $('#d_remarks').val('')
                $('#d_rrlist tr').empty()
                $('#d_jelist tr').empty()
                $('#d_sourceref').val('')
                $('#d_debittotal').text('0.00')
                $('#d_credittotal').text('0.00')
                $('#d_save').attr('data-id', 0)
                $('#d_save').show()
                $('#d_save').prop('disabled', false)

                $('#d_supplier').prop('disabled', false)
                $('#d_date').prop('disabled', false)
                $('#d_remarks').prop('disabled', false)
                $('#d_source').val('cash').change()
                $('#d_s_oth').prop('disabled', false)
                $('#d_s_employee').prop('disabled', false)

                $('#d_sourceref').prop('disabled', false)
                $('#d_invoceno').prop('disabled', false)
                $('#d_cash').prop('disabled', false)
                $('#d_cheque').prop('disabled', false)
                $('#voucherno').prop('disabled', false)
                $('#d_addentry').prop('disabled', false)

                $('#d_amount').val('')
                $('#d_invoceno').val('')
                $('#d_itemlist').empty();
                $('#d_itemlist').append(`
                    <tr>
                        <td colspan="5">
                            <u><a href="#" id="additem">Add item</a></u>
                        </td>
                    </tr>
                `)

                $('#div_posted').hide()


                $('#d_bank').val(0).trigger('change')

                var paytype = '';

                if($('#d_cash').prop('checked') == true)
                {
                    paytype = "CASH"
                }
                else{
                    paytype = "CHEQUE"
                }

                getVoucherNo('DSMT', paytype)

                $('#d_post').hide()

                setTimeout(() => {
                checkpaytype()
                }, 500);

            }

            $(document).on('click', '#d_post', function(){
                var id = $('#d_save').attr('data-id')

                if(id != 0)
                {


                    Swal.fire({
                    title: 'Post Disbursement?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'POST'
                    }).then((result) => {
                    if (result.value == true) {
                        $('#d_save').trigger('click')

                        $.ajax({
                        url: '{{route('disbursement_post')}}',
                        type: 'GET',
                        data: {
                            id:id
                        },
                        success:function(data){
                            Swal.fire(
                            'Posted',
                            'Disbursement has been posted.',
                            'success'
                            )

                            disbursement_load()
                            postdisplay(data)
                        }
                        });
                    }
                    })
                }
                else{
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        type: "error",
                        title: "Can't post unsave disbursement"
                    });
                }
            })

            function postdisplay(trxstatus)
            {
                if(trxstatus == 'POSTED')
                {
                // $('select').prop('disabled', true)
                // $('input').prop('disabled', true)
                $('#modal-disbursement').find('input').prop('disabled', true)
                $('#modal-disbursement').find('select').prop('disabled', true)

                $('.btn_remove').prop('disabled', true)
                $('#d_addentry').prop('disabled', true)
                $('#d_remarks').prop('disabled', true)
                $('#div_posted').show()
                $('#d_save').hide()
                $('#d_post').hide()
                }
                else{
                $('#modal-disbursement').find('input').prop('disabled', false)
                $('#modal-disbursement').find('select').prop('disabled', false)

                $('.btn_remove').prop('disabled', false)
                $('#d_addentry').prop('disabled', false)
                $('#d_remarks').prop('disabled', false)
                $('#div_posted').hide()
                $('#d_save').show()
                $('#d_post').show()
                }
            }

            $(document).on('click', '.btn_remove', function(){
                var id = $(this).closest('tr').attr('data-id')
                var headerid = $('#d_save').attr('data-id')

                if(id != 0)
                {
                $.ajax({
                    type: "GET",
                    url: "{{route('disbursement_removeje')}}",
                    data: {
                    id:id
                    },
                    // dataType: "dataType",
                    success: function (data){
                    disbursement_loadje(headerid)
                    }
                })
                }
                else{
                $(this).closest('tr').remove()
                }

                calc_je()
            })

            $(document).on('click', '#d_print', function(){
                var id = $('#d_save').attr('data-id')

                if(id != 0)
                {
                    window.open('/finance/disbursement_read?dataid='+id+'&action=print', '_blank');
                }
                else{
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        type: "error",
                        title: "Please save disbursement before printing."
                    });
                }
            })

            function calc_je()
            {
                var totaldebit = 0
                var totalcredit = 0

                $('#d_jelist tr').each(function(){
                var debit = $(this).find('.d_debit').val()
                var credit = $(this).find('.d_credit').val()

                totaldebit = totaldebit.toString().replace(/,/g, '')
                totalcredit = totalcredit.toString().replace(/,/g, '')

                totaldebit += debit
                totalcredit += credit


                })

                $('#d_debittotal').text(parseFloat(totaldebit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
                $('#d_credittotal').text(parseFloat(totalcredit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())

            }

            $(document).on('change', '.filters', function(){
                disbursement_load()
            })

            $(document).on('change', '.d_rrpayment', function(){
                var balance = $(this).attr('data-maxpay')
                var curvalue = $(this).val().replace(/,/g, '')

                console.log(curvalue)

                if(parseFloat(curvalue) > parseFloat(balance))
                {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    type: "error",
                    title: "Can't pay greater than current balance"
                });

                $(this).val(parseFloat(balance, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
                }
            })

            $(document).on('keydown', '.d_rrpayment', function(e){
                if(e.which == 13)
                {
                $(this).trigger('change')
                }
            })

            function checkpaytype(status="")
            {
                if(status != 'POSTED')
                {
                if($('#d_cheque').is(':checked'))
                {
                    $('.grp_check').prop('disabled', false)
                    getVoucherNo('DSMT', 'CHEQUE')
                }
                else{
                    $('.grp_check').prop('disabled', true)
                    getVoucherNo('DSMT', 'CASH')
                }
                }
            }

            $(document).on('click', '#d_cash', function(){
                checkpaytype()
            })

            $(document).on('click', '#d_cheque', function(){
                checkpaytype()
            })

            function getVoucherNo(type, paytype)
            {
                $.ajax({
                    type: "GET",
                    url: "{{ route('expenses_getvoucherno') }}",
                    data: {
                        type:type,
                        paytype:paytype
                    },
                    success: function (data) {

                        $('#voucherno').val(data);
                    }
                });
            }

            $(document).on('click', '#btncreateItem', function(){
                $('#modal-item-new').modal('show');
            });

            $(document).on( 'change', '#d_payto', function(){
                var source = $(this).val();

                if(source == 'supplier')
                {
                $('#div_s_supplier').show();
                $('#div_s_employee').hide();
                $('#div_s_oth').hide();
                }
                else if(source == 'employee')
                {
                $('#div_s_supplier').hide();
                $('#div_s_employee').show();
                $('#div_s_oth').hide();
                }
                else{
                $('#div_s_supplier').hide();
                $('#div_s_employee').hide();
                $('#div_s_oth').show();
                }


            })

            $(document).on('change', '#d_ponumber', function(){
                var poid = $(this).val();

                $('#modal-overlay').modal('show');

                $.ajax({
                    type: "GET",
                    url: "{{ route('disbursement_getpoinfo') }}",
                    data: {
                        poid:poid
                    },
                    success: function (data) {
                        $('#d_supplier').val(data.supplierid).change()
                        $('#d_amount').val(formatnumber(data.totalamount))
                        $('#d_remarks').val(data.remarks)

                        $('#d_itemlist').empty()

                        var grandtotal = 0;


                        $.each(data.items, function (indexInArray, value) {

                            grandtotal += parseFloat(value.itemtotalamount)

                            $('#d_itemlist').append(`
                                <tr>
                                    <td>`+value.itemname+`</td>
                                    <td class="text-right">`+formatnumber(value.itemamount)+`</td>
                                    <td class="text-center">`+value.qty+`</td>
                                    <td class="text-right">`+formatnumber(value.itemtotalamount)+`</td>
                                </tr>
                            `)
                        });

                        $('#d_itemtotalamount').text(formatnumber(grandtotal))

                        // $('#d_itemlist').append(`
                        //     <tr>
                        //         <td class="text-bold" colspan="3">TOTAL</td>
                        //         <td class="text-bold text-right">`+formatnumber(grandtotal)+`</td>
                        //     </tr>
                        // `)

                        setTimeout(() => {
                            $('#modal-overlay').modal('hide');
                            // getitemtotalamount()
                        }, 500);
                    }
                });
            })

            $('#itemdesc').on('select2:select', function(e){
                const itemname = $(this).find(':selected').text()
            })

            $(document).on('click', '#btnadd', function(){
                const itemid = $('#itemdesc').val()
                const amount = $('#itemamount').val()
                const qty = $('#itemqty').val()
                let totalamount = 0;

                let itemname = $('#itemdesc').find(':selected').text();

                $('#d_itemlist').append(`
                    <tr data-id="0" item-id="`+itemid+`" item-amount="`+amount+`" item-qty="`+qty+`">
                        <td>`+itemname+`</td>
                        <td class="text-right">`+formatnumber(amount)+`</td>
                        <td class="text-center">`+qty+`</td>
                        <td class="text-right">`+formatnumber(amount*qty)+`</td>
                        <td><button class="btn btn-danger btn-sm btn_remove"><i class="far fa-trash-alt"></i></button></td>
                    </tr>
                `)

                setTimeout(() => {
                    getitemtotalamount()
                }, 300);


            })

            $(document).on('change', '.calc', function(){
                calcitems()
            })

            function getitemtotalamount()
            {
                let totalamount = 0;

                $('#d_itemlist tr').each(function(){
                    if($(this).attr('item-amount'))
                    {
                        const qty = $(this).attr('item-qty')
                        const amount = $(this).attr('item-amount')



                        totalamount += parseFloat(qty) * parseFloat(amount)
                        console.log('totalamount: ' + totalamount);
                    }
                })

                $('#d_itemtotalamount').text(formatnumber(totalamount))

                if(totalamount > 0)
                {
                    $('#d_amount').val(formatnumber(totalamount))
                }

                if(totalamount > 0)
                {
                    $('#d_amount').prop('disabled', true)
                }
                else{
                    $('#d_amount').prop('disabled', false)
                }
            }

            function calcitems()
            {
                let amount = $('#itemamount').val()
                let qty = $('#itemqty').val()
                let totalamount = parseFloat(amount) * parseFloat(qty)

                $('#totalamount').val(formatnumber(totalamount))
            }

            $(document).on('click', '.btn_remove', function(){
                $(this).closest('tr').remove()
                getitemtotalamount()
            })



        });
    </script>
@endsection
