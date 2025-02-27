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
            {{-- <button id="expenses_setup" class="btn btn-default btn-lg" data-toggle="tooltip" title="Expenses Setup">
              <i class="fas fa-cogs"></i>
            </button> --}}
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
                    <th>Reference</th>
                    <th>Supplier</th>
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

  <div class="modal fade" id="modal-disbursement" aria-modal="true" style="display: none; margin-top: -25px; overflow-y: hidden; height: 768px">
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
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group input-group-lg">
                    <label>Supplier</label>
                    {{-- <input id="description" type="text" class="form-control form-control-lg text-lg validate is-invalid" placeholder="Description"> --}}
                    <select id="d_supplier" class="select2" style="width: 100%;">
                      @foreach(db::table('expense_company')->where('deleted', 0)->get() as $supplier)
                        <option value="{{$supplier->id}}">{{$supplier->companyname}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
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
                <div class="col-md-5">
                  <div class="form-group">
                    <label>Date</label>
                    <input id="d_date" type="date" class="form-control" value="{{date_format(App\FinanceModel::getServerDateTime(), 'Y-m-d')}}">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label>Bank</label>
                  <select id="d_bank" class="select2 grp_check" style="width: 100%;">
                    <option value="0">Select Bank</option>
                    @foreach(db::table('acc_bank')->where('deleted', 0)->get() as $bank)
                      <option value="{{$bank->id}}">{{$bank->bankname}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <div class="row">
                    <div class="col-md-6">
                      <label>Check No.</label>
                      <input id="d_checkno" class="form-control grp_check" type="" name="">
                    </div>
                    <div class="col-md-6">
                      <label>Check Date</label>
                      <input id="d_checkdate" class="form-control grp_check" type="date" name="" >
                    </div>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <div class="form-group">
                      <label>Remarks</label>
                      <textarea id="d_remarks" style="width: 100%;" class="form-control" rows="2" placeholder="Notes ..."></textarea>
                    </div>
                  </div>
                </div>
              </div>
              {{-- <hr> --}}
              <div class="row">
                <div class="col-md-7 table-responsive" style="height: 12em;">
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
                    </tfoot> --}}
                  </table>
                </div>
                <div class="col-md-5">
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
              reference = (val.refnum == null) ? '' : val.refnum
              $('#disbursement_list').append(`
                <tr data-id="`+val.id+`">
                  <td>`+moment(val.transdate).format('MM/DD/YYYY')+`</td>
                  <td>`+reference+`</td>
                  <td>`+val.companyname+`</td>
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



      $(document).on('click', '#d_create', function(){
        checkpaytype()
        d_create()
        $.ajax({
          type: "GET",
          url: "{{route('disbursement_loadsupplier')}}",
          data: {

          },
          // dataType: "dataType",
          success: function (data){
            $('#d_supplier').empty()
            $('#d_supplier').append(`
                <option value="0">SELECT SUPPLIER</option>
            `)
            $.each(data, function(index, val) {
              $('#d_supplier').append(`
                <option value="`+val.id+`">`+val.text+`</option>
              `)
            });

            // postdisplay('')
          }
        })

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
        var supplierid = $('#d_supplier').val()
        var date = $('#d_date').val()
        var bankid = $('#d_bank').val()
        var checkno = $('#d_checkno').val()
        var checkdate = $('#d_checkdate').val()
        var remarks = $('#d_remarks').val()
        var id = $(this).attr('data-id')
        var rr_array = []
        var jearray = []

        if(supplierid != 0)
        {
            $('#d_save').prop('disabled', true)

            if($('#d_cash').prop('checked') == true)
            {
            var paytype = 'CASH'
            }
            else{
            var paytype = 'CHEQUE'
            }

            $('#d_rrlist tr').each(function(){
            var rrid = $(this).attr('rr-id')
            var rramount = $(this).find('.d_rrpayment').val()
            var dataid = $(this).attr('data-id')

            var obj = {
                dataid:dataid,
                rrid:rrid,
                rramount:rramount
            }

            rr_array.push(obj)
            })

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

            // console.log(jearray)
            $.ajax({
            url: '{{route('disbursement_save')}}',
            type: 'GET',
            // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
            data: {
                dataid:id,
                supplierid:supplierid,
                voucherno:voucherno,
                date:date,
                bankid:bankid,
                checkno:checkno,
                checkdate:checkdate,
                remarks:remarks,
                paytype:paytype,
                rr_array:rr_array,
                jearray:jearray
            },
            success:function(data)
            {
                $('#lblrefnum').text(data.refnum)
                $('#d_save').attr('data-id', data.dataid)
                $('#d_supplier').trigger('change')

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
            }
            });
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
                title: "No supplier selected"
            });
        }
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
            console.log(data.voucherno)

            $('#voucherno').val(data.voucherno)
            $('#d_date').val(data.transdate)
            $('#d_checkno').val(data.checkno)
            $('#d_checkdate').val(data.checkdate)
            $('#d_remarks').val(data.remarks)
            $('#d_bank').val(data.bankid).trigger('change')

            if(data.paytype == 'CHEQUE')
            {
              $('#d_cheque').prop('checked', true)
            }
            else{
              $('#d_cash').prop('checked', true)
            }

            disbursement_loadje(dataid)

            $('#d_jelist').prop('disabled', true)

            // console.log(data.id)

            $('#d_jelist').empty()

            $('#d_save').attr('data-id', data.id)
            $('#d_supplier').val(data.supplierid)
            $('#d_supplier').trigger('change')

            setTimeout(function(){
              postdisplay(data.trxstatus)
              $('#modal-disbursement').modal('show')
              checkpaytype(data.trxstatus)
              $('#modal-overlay').modal('hide')
            }, 3000)

          }
        })


      })

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
        $('#d_debittotal').text('0.00')
        $('#d_credittotal').text('0.00')
        $('#d_save').attr('data-id', 0)
        $('#d_save').show()
        $('#d_save').prop('disabled', false)

        $('#d_supplier').prop('disabled', false)
        $('#d_date').prop('disabled', false)
        $('#d_remarks').prop('disabled', false)


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

        $('#div_posted').hide()

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
    });
  </script>
@endsection
