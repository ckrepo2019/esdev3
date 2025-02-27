@extends('finance.layouts.app')

@section('content')
	{{-- <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Reports</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section> --}}
  <section class="content pt-0">
    <div class="row">
      <div class="col-md-12">
        <h1 class="">Reports</h1>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">
                <i class="far fa-calendar-alt"></i>
              </span>
            </div>
            <input type="text" class="form-control float-right dtrangepicker" id="dtrange" style="cursor: pointer">
            {{-- <div class="input-group-append">
                <button id="btn_rptgenerate" class="btn btn-warning input-group-button">
                  <i class="fas fa-sync"></i> Generate
                </button>
            </div> --}}
          </div>
          <!-- /.input group -->
        </div>
      </div>
    </div>
        <div class="card">
          <div class="card-header bg-info">
            <div class="row">
              <div class="col-md-8">
                   
              </div>
              <div class="col-md-4">
                
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div id="btn_gl" class="col-md-2" style="cursor: pointer;">
                <div class="small-box bg-purple">
                  <div class="inner pt-3 text-center">
                    <h4 class="">General Ledger</h4>
                  </div>
                  <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
              <div id="btn_sl" class="col-md-3" style="cursor: pointer;">
                <div class="small-box bg-info">
                  <div class="inner pt-3 text-center">
                    <h4 class="">Subsidiary Ledger</h4>
                  </div>
                  <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
              <div id="btn_tb" class="col-md-2" style="cursor: pointer;">
                <div class="small-box bg-pink">
                  <div class="inner pt-3 text-center">
                    <h4 class="">Trial Balance</h4>
                  </div>
                  <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
              <div id="btn_bs" class="col-md-2" style="cursor: pointer;">
                <div class="small-box bg-primary">
                  <div class="inner pt-3 text-center">
                    <h4 class="">Balance Sheet</h4>
                  </div>
                  <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
              <div id="btn_is" class="col-md-3" style="cursor: pointer;">
                <div class="small-box bg-success">
                  <div class="inner pt-3 text-center">
                    <h4 class="">Income Statement</h4>
                  </div>
                  <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>  
        </div>
        
      </div>
    </div>
  </section>

@endsection
@section('modal')

<div class="modal fade show" id="modal-rpt_is" aria-modal="true" style="display: none; margin-top: -25px;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 id="rpt_title" class="modal-title">Income Statement</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 table-responsive">
            <table class="table table-strippd table-sm text-sm" style="width: 100%">
              <thead>
                <tr>
                  <th style="width: 70%">&nbsp</th>
                  <th class="text-center">Balances</th>
                </tr>
              </thead>
              <tbody id="is_body">

              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer float-right">
        <div class="">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            Close
          </button>
          <button id="is_print" type="button" class="btn btn-primary" data-dismiss="modal">
            <i class="fas fa-print"></i> Print
          </button>
        </div>
        <div>
          {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
            <i class="fas fa-thumbs-down"></i>
          </button>
          <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
            <i class="fas fa-thumbs-up"></i>
          </button> --}}
        </div>
      </div>
    </div>
  </div> {{-- dialog --}}
</div>

<div class="modal fade show" id="modal-rpt_is" aria-modal="true" style="display: none; margin-top: -25px;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 id="rpt_title" class="modal-title">Income Statement</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 table-responsive">
            <table class="table table-strippd table-sm text-sm" style="width: 100%">
              <thead>
                <tr>
                  <th style="width: 70%">&nbsp</th>
                  <th class="text-center">Balances</th>
                </tr>
              </thead>
              <tbody id="is_body">

              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer float-right">
        <div class="">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            Close
          </button>
          <button id="btnselaccount" type="button" class="btn btn-primary" data-dismiss="modal">
            <i class="fas fa-print"></i> Print
          </button>
        </div>
        <div>
          {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
            <i class="fas fa-thumbs-down"></i>
          </button>
          <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
            <i class="fas fa-thumbs-up"></i>
          </button> --}}
        </div>
      </div>
    </div>
  </div> {{-- dialog --}}
</div>

<div class="modal fade" id="modal-rpt_gl" aria-modal="true" style="display: none; margin-top: -25px;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-purple">
        <h5 id="rpt_title" class="modal-title">General Ledger</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row form-group">
          <div class="col-md-5">
            <select id="gl_filter" class="select2bs4" style="width: 100%;">
              <option value="ALL">All</option>
              <option value="CR">Cash Receipt</option>
              <option value="SALES">Sales</option>
              <option value="RECEIVING">Purchasing</option>
              <option value="EXP">Expense</option>
              <option value="DSMT">Disbursement</option>
              <option value="JE">General Journal</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 table-responsive" style="height: 459px">
            <table class="table table-strippd table-sm text-sm" style="width: 100%">
              <thead>
                <tr>
                  <th colspan="2" style="width: 55%; text-align: center;">Accounts</th>
                  <th class="text-center">Debit</th>
                  <th class="text-center">Credit</th>
                  <th class="text-left">Mapping</th>
                </tr>
              </thead>
              <tbody id="gl_body">

              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer float-right">
        <div class="">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            Close
          </button>
          <button id="gl_print" type="button" class="btn btn-primary" data-dismiss="modal">
            <i class="fas fa-print"></i> Print
          </button>
        </div>
        <div>
          {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
            <i class="fas fa-thumbs-down"></i>
          </button>
          <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
            <i class="fas fa-thumbs-up"></i>
          </button> --}}
        </div>
      </div>
    </div>
  </div> {{-- dialog --}}
</div>

<div class="modal fade" id="modal-rpt_sl" aria-modal="true" style="display: none; margin-top: -25px;">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h5 id="rpt_title" class="modal-title">Subsidiary Ledger</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row form-group">
          <div class="col-md-7">
            <select id="sl_filter" class="select2bs4" style="width: 100%;">
              <option value="0">ALL</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 table-responsive" style="height: 459px">
            <table class="table table-strippd table-sm text-sm" style="width: 100%">
              <thead>
                <tr>
                  <th class="text-center">Date</th>
                  <th style="width: 55%; text-align: center;">Accounts</th>
                  <th class="text-center">Debit</th>
                  <th class="text-center">Credit</th>
                  <th class="text-center">Balance</th>
                  <th class="text-left">Mapping</th>
                </tr>
              </thead>
              <tbody id="sl_body">

              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer float-right">
        <div class="">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            Close
          </button>
          <button id="sl_print" type="button" class="btn btn-primary">
            <i class="fas fa-print"></i> Print
          </button>
        </div>
        <div>
          {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
            <i class="fas fa-thumbs-down"></i>
          </button>
          <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
            <i class="fas fa-thumbs-up"></i>
          </button> --}}
        </div>
      </div>
    </div>
  </div> {{-- dialog --}}
</div>



  <div class="modal fade show" id="modal-reports_bs" aria-modal="true" style="display: none; margin-top: -25px;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 id="rpt_title" class="modal-title">Balance Sheet</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
              {{-- <div class="row">
                <div class="col-md-12 text-bold text-center">ASSETS</div>
              </div> --}}
              <div class="row">
                <div class="col-md-12">
                  <table class="table table-sm text-sm" style="width: 100%">
                    <tbody id="bs_assets_list">
                      
                    </tbody>
                  </table>
                </div>
              </div>
              {{-- <div class="row">
                <div class="col-md-12 text-bold text-center">LIABILITIES</div>
              </div>
              <div class="row">
                <div class="col-md-12 text-bold text-center">EQUITY</div>
              </div> --}}
            </div>
            <div class="col-md-2"></div>
          </div>
        </div>
        <div class="modal-footer float-right">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
            <button id="bs_print" type="button" class="btn btn-primary" >
              <i class="fas fa-print"></i> Print
            </button>
          </div>
          <div>
            {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
              <i class="fas fa-thumbs-down"></i>
            </button>
            <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
              <i class="fas fa-thumbs-up"></i>
            </button> --}}
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-reports_is" aria-modal="true" style="display: none; margin-top: -25px;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 id="rpt_title" class="modal-title">Income Statement</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              {{-- <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-clock"></i></span>
                </div>
                <input id="bs_daterange" type="text" class="form-control float-right daterange">
              </div> --}}
            </div>
            <div class="col-md-2">
              {{-- <button id="bs_generate" class="btn btn-primary">Generate</button> --}}
            </div>
          </div>
          <div class="row text-sm">
            <div class="col-md-2">
              
            </div>
            <div class="col-md-7">
              <table class="table table-sm text-sm">
                <tbody id="rpt_is_list">

                </tbody>
              </table>
              
            </div>
          </div>
        </div>
        <div class="modal-footer float-right">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
            <button id="is_print" type="button" class="btn btn-primary">
              <i class="fas fa-print"></i> Print
            </button>
          </div>
          <div>
            {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
              <i class="fas fa-thumbs-down"></i>
            </button>
            <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
              <i class="fas fa-thumbs-up"></i>
            </button> --}}
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-rpt_tb" aria-modal="true" style="display: none; margin-top: -25px;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-pink">
        <h5 id="rpt_title" class="modal-title">Trial Balance</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 table-responsive" style="height: 544px">
            <table class="table table-strippd table-sm text-sm" style="width: 100%">
              <thead>
                <tr>
                  <th colspan="2" style="width: 55%">Accounts</th>
                  <th class="text-center">Debit</th>
                  <th class="text-center">Credit</th>
                  <th class="text-center">Balance</th>
                </tr>
              </thead>
              <tbody id="tb_list">

              </tbody>
              <tfoot>
                <tr>
                  <th colspan="2" class="text-right text-bold">TOTAL: </th>
                  <th class="text-right" id="tb_debittotal"></th>
                  <th class="text-right" id="tb_credittotal"></th>
                  <th class="text-right" id="tb_balancetotal"></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer float-right">
        <div class="">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            Close
          </button>
          <button id="tb_print" type="button" class="btn btn-primary" data-dismiss="modal">
            <i class="fas fa-print"></i> Print
          </button>
        </div>
        <div>
          {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
            <i class="fas fa-thumbs-down"></i>
          </button>
          <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
            <i class="fas fa-thumbs-up"></i>
          </button> --}}
        </div>
      </div>
    </div>
  </div> {{-- dialog --}}
</div>
  

  <div class="modal fade" id="modal-overlay" data-backdrop="static" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" style="opacity: 78%">
        <div class="overlay d-flex justify-content-center align-items-center" style="background-color: white">
          <i class="fas fa-7x fa-circle-notch fa-spin"></i>
        </div>
        {{-- <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div> --}}
        <div class="modal-body" style="height: 450px">
          <h3>Loading...</h3>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>



@endsection
@section('jsUP')
  <style type="text/css">

    table td {
      position: relative;
    }

    table td input {
      position: absolute;
      display: block;
      top:0;
      left:0;
      margin: 0;
      height: 100%;
      width: 100%;
      border: none;
      padding: 10px;
      box-sizing: border-box;
    }

    .jeinput{
      border:none;
      border-width: 0px;
    }
  </style>
@endsection
@section('js')
  
  <script type="text/javascript"></script>
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

    // $('.daterange').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    $('.daterange').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })

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
    var lineval = 0;
    var tDebit = parseFloat($('#totaldebit').attr('data-value'));
    var tCredit = parseFloat($('#totalcredit').attr('data-value'));

    $(document).ready(function(){

      var chartofaccounts;
      
      acc_report();

      $('.dtrangepicker').daterangepicker()
      
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      $('#modal-je').draggable({
        handle: '.modal-header'
      });


      function je_enabled()
      {
        $('#refid').removeClass('text-success');
        $('.je-coa').prop('disabled', false);
        $('.isdebit').prop('disabled', false);
        $('.iscredit').prop('disabled', false);
        $('.btn-linedel').prop('disabled', false);
        $('#transdate').prop('disabled', false);
        $('#btnaction').prop('hidden', false);
        $('#btnsaveJE').prop('hidden', false);
        $('#addline').prop('hidden', false);
      }

      function formatnumber(number)
      {
        return parseFloat(number, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
      }

      $(document).on('change', '#txttest', function(){
        var currency = $(this).val();
        var numUSD = new Intl.NumberFormat('en-US', {
          style:"currency",
          currency: "USD"
        });

        console.log(numUSD.format(currency));
      })

      $(document).on('click', '#bs_generate', function(){
        acc_report();
      })

      function acc_report()
      {
        var daterange = $('#dtrange').val();

        $.ajax({
          url: '{{route('bs_generate')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            daterange:daterange
          },
          success:function(data)
          {
            console.log(data.liabilities);

            $('#rpt_assets1').html(data.assets1);
            $('#bs_totalca').text(data.totalassets1);
            $('#rpt_assets2').html(data.assets2);
            $('#bs_totalassets').text(data.totalassets);

            $('#rpt_liabilities').html(data.liabilities);
            $('#bs_totalliabilities').text(data.totalliabilities);

            $('#rpt_equity').html(data.equity);
            $('#bs_equity').text(data.totalequity);

            $('#rpt_is_revenue').html(data.revenue);
            $('#rpt_is_totalrevenue').text(data.totalrevenue);

            $('#rpt_is_cor').html(data.cor);
            $('#rpt_is_totalcor').text(data.totalcor);

            $('#rpt_is_oth').html(data.oth);
            $('#rpt_is_totaloth').text(data.totaloth);

            $('#rpt_is_genexpenses').html(data.genexpenses);
            $('#rpt_is_totalgenexpenses').text(data.totalgenexpenses);
            $('#rpt_is_btax').text(data.btax);
            $('#rpt_is_taxamount').text(data.taxamount);
            $('#rpt_is_netincome').text(data.netincome);
          }
        });
      }

      $(document).on('change', '#dtrange', function(){
        acc_report();
      });

      $(document).on('click', '#btn_rptgenerate', function(){
        acc_report();
      });

      // $(document).on('click', '#btn_is', function(){
      //   $('#modal-reports_is').modal('show');
      // });

      $(document).on('click', '#btn_is', function(){
        var range = $('#dtrange').val()

        $.ajax({
          type: "GET",
          url: "{{route('is_generate')}}",
          data: {
            range:range
          },
          // dataType: "dataType",
          success: function (data) {
            $('#rpt_is_list').empty()

            $.each(data, function (index, val) { 

              if(val.header == 1)
              {
                $('#rpt_is_list').append(`
                  <tr>
                    <td colspan="3" class="text-bold">`+val.title+`</td>
                  </tr>
                `)  
              }
              else if(val.rpttype == 'subtotal')
              {
                $('#rpt_is_list').append(`
                  <tr>
                    <td colspan="2" class="text-bold">`+val.title+`</td>
                    <td colspan="" class="text-right text-bold">`+val.amount+`</td>
                  </tr>
                `)  
              }
              else if(val.rpttype == 'nettotal')
              {
                if(val.amount > 0)
                {
                  $('#rpt_is_list').append(`
                    <tr>
                      <td colspan="2" class="text-bold">`+val.title+`</td>
                      <td colspan="" class="text-right text-bold">`+val.amount+`</td>
                    </tr>
                  `)  
                }
                else{
                  $('#rpt_is_list').append(`
										<tr>
											<td colspan="2" class="text-bold">`+val.title+`</td>
											<td colspan="" class="text-right text-bold">`+val.amount+`</td>
										</tr>
                	`)  
                }
              }
              else{
                $('#rpt_is_list').append(`
                  <tr>
                    <td colspan="" class=""></td>
                    <td colspan="" class="">`+val.title+`</td>
                    <td colspan="" class="text-right">`+val.amount+`</td>
                  </tr>
                `)  
              }


              
            });

            $('#modal-reports_is').modal('show')
          }
        });
      })

      $(document).on('click','#aaa', function(){

      })

      $(document).on('click', '#btn_bs', function(){
        var range = $('#dtrange').val()

        $.ajax({
          type: "GET",
          url: "{{route('bs_generate')}}",
          data: {
            range:range
          },
          // dataType: "dataType",
          success: function (data) {
            $('#bs_assets_list').empty()

            $.each(data, function (index, val) { 
              if(val.header == 1)
              {
                $('#bs_assets_list').append(`
                  <tr>
                    <td colspan="3" class="text-bold">`+val.title+`</td>
                  </tr>
                `)  
              }
              else if(val.rpttype == 'subtotal')
              {
                $('#bs_assets_list').append(`
                  <tr>
                    <td colspan="2" class="text-bold" style="border-bottom: solid 1px black;">`+val.title+`</td>
                    <td colspan="" class="text-right text-bold" style="border-bottom: solid 1px black;">`+val.amount+`</td>
                  </tr>
                `)  
              }
              else if(val.rpttype == 'totalassets')
              {
                if(val.amount > 0)
                {
                  $('#bs_assets_list').append(`
                    <tr>
                      <td colspan="2" class="text-bold">`+val.title+`</td>
                      <td colspan="" class="text-right text-bold">`+val.amount+`</td>
                    </tr>
                  `)  
                }
                else{
                  $('#bs_assets_list').append(`
                    <tr>
                      <td colspan="2" class="text-bold" style="border-top: solid 1px black; border-bottom: solid 2px black">`+val.title+`</td>
                      <td colspan="" class="text-right text-bold" style="border-top: solid 1px black; border-bottom: solid 2px black">`+val.amount+`</td>
                    </tr>
                  `)  
                }
              }
              else if(val.rpttype == 'eqliability')
              {
                if(val.amount > 0)
                {
                  $('#bs_assets_list').append(`
                    <tr>
                      <td colspan="2" class="text-bold">`+val.title+`</td>
                      <td colspan="" class="text-right text-bold">`+val.amount+`</td>
                    </tr>
                  `)  
                }
                else{
                  $('#bs_assets_list').append(`
                    <tr>
                      <td colspan="2" class="text-bold" style="border-top: solid 1px black; border-bottom: solid 2px black">`+val.title+`</td>
                      <td colspan="" class="text-right text-bold" style="border-top: solid 1px black; border-bottom: solid 2px black">`+val.amount+`</td>
                    </tr>
                  `)  
                }
              }
              else{
                $('#bs_assets_list').append(`
                  <tr>
                    <td colspan="" class=""></td>
                    <td colspan="" class="">`+val.title+`</td>
                    <td colspan="" class="text-right">`+val.amount+`</td>
                  </tr>
                `)  
              }
            });
            $('#modal-reports_bs').modal('show'); 
          }
        })
      })

      $(document).on('click', '#btn_gl', function(){
        var range = $('#dtrange').val()
        var filter = $('#gl_filter').val()

        $.ajax({
          type: "GET",
          url: "{{route('gl_generate')}}",
          data: {
            range:range,
            filter:filter
          },
          // dataType: "dataType",
          success: function (data) {
            $('#gl_body').empty()

            var debittotal = 0
            var credittotal = 0
            var balance = 0
            var totalbalance = 0
            var debit = 0
            var credit = 0

            $.each(data, function (index, val) { 
              // if(parseFloat(val.debit) >= parseFloat(val.credit))
              // {
              //   balance = parseFloat(val.debit) - parseFloat(val.credit)
              // }
              // else
              // {
              //   balance = parseFloat(val.credit) - parseFloat(val.debit)
              // }

              if(val.debit == null)
              {
                debit = 0
              }
              else{
                debit = val.debit
              }

              if(val.credit == null)
              {
                credit = 0
              }
              else{
                credit = val.credit
              }


              
              balance = parseFloat(balance) + parseFloat(debit)
              balance = balance - parseFloat(credit)

              $('#gl_body').append(`
                <tr>
                  <td>`+val.code+`</td>
                  <td>`+val.account+`</td>
                  <td class="text-right">`+parseFloat(debit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
                  <td class="text-right">`+parseFloat(credit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
                  <td>`+val.mapname+`</td>
                </tr>
              `)

              debittotal += parseFloat(debit)
              credittotal += parseFloat(credit)
              // totalbalance += parseFloat(balance)
            });

            $('#gl_body').append(`
              <tr>
                <td colspan=2 class="text-right text-bold">TOTAL: </td>
                <td class="text-right text-bold">`+parseFloat(debittotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
                <td class="text-right text-bold">`+parseFloat(credittotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
                <td></td>
              </tr>
            `)

            $('#modal-rpt_gl').modal('show')
          }
        });
      })

      $(document).on('click', '#btn_tb', function(){
        var range = $('#dtrange').val()

        $.ajax({
          type: "GET",
          url: "{{route('tb_generate')}}",
          data: {
            range:range
          },
          // dataType: "dataType",
          success: function (data){
            $('#tb_list tr').empty()

            $.each(data.tbarray, function(index, val) {
              $('#tb_list').append(`
                <tr>
                  <td>`+val.code+`</td>
                  <td>`+val.account+`</td>
                  <td class="text-right">`+val.debit+`</td>
                  <td class="text-right">`+val.credit+`</td>
                  <td class="text-right">`+val.balance+`</td>
                </tr>
              `)   
            });

            $('#tb_debittotal').text(data.debittotal)
            $('#tb_credittotal').text(data.credittotal)
            $('#tb_balancetotal').text(data.balancetotal)

            $('#modal-rpt_tb').modal('show')    
          }
        })

        
      })

			$(document).on('click', '#is_print', function(){
				var range = $('#dtrange').val()

				window.open('/finance/accounting/is_generate?range='+range+'&action=print', '_blank');
			})

      $(document).on('click', '#bs_print', function(){
				var range = $('#dtrange').val()

				window.open('/finance/accounting/bs_generate?range='+range+'&action=bs_print', '_blank');
			})

      $(document).on('click', '#tb_print', function(){
        var range = $('#dtrange').val()
        // var filter = $('#tb_filter').val()

        window.open('/finance/accounting/tb_generate?range='+range+'&action=print', '_blank');
      })

      $(document).on('change', '#gl_filter', function(){
        $('#btn_gl').trigger('click');
      })

      $(document).on('click', '#gl_print', function(){
        var range = $('#dtrange').val()
        var filter = $('#gl_filter').val()

        window.open('/finance/accounting/gl_generate?range='+range+'&action=print&filter='+filter, '_blank');
      })

      $(document).on('click', '#btn_sl', function(){
        var range = $('#dtrange').val()
        var filter = $('#sl_filter').val()

        $.ajax({
          type: "GET",
          url: "{{ route('sl_generate') }}",
          data: {
            range:range,
            filter:filter
          },
          success: function (data) {
            console.log($('#sl_filter').length)

            if($('#sl_filter option').length == 1)
            {
              $('#sl_filter').empty()

              $('#sl_filter').append(`
                <option value="0">ALL</option>
              `)
              
              $.each(data.filters, function (indexInArray, value) { 
                 $('#sl_filter').append(`
                  <option value="`+value.id+`">`+value.code+` - `+value.account+`</option>
                `)
              });
            }

            $('#sl_body').empty()

            var totaldebit = 0;
            var totalcredit = 0;
            var totalbalance = 0;

            $.each(data.sl, function (indexInArray, value) { 
              date = value.createddatetime
              date = moment(date).format('MM/DD/YYYY')
              
              calcdebit = 0
              calccredit = 0
              

              if(value.debit == null)
              {
                debit = 0.00
                calcdebit = 0
              }
              else if(value.debit < 0)
              {
                totaldebit += parseFloat(value.debit)
                debit = '(' + formatnumber(value.debit * -1) + ')'
                calcdebit = value.debit
              }
              else if(value.debit == 0)
              {
                debit = ''
                calcdebit = 0
              }
              else{
                totaldebit += parseFloat(value.debit)
                debit = formatnumber(value.debit)
                calcdebit = value.debit
              }

              if(value.credit == null)
              {
                credit = ''
                calccredit = 0.00
                console.log('credit1: ' + credit)
              }
              else if(value.credit < 0)
              {
                totalcredit += parseFloat(value.credit)
                credit = '(' + formatnumber(value.credit * -1) + ')'
                calccredit = value.credit
                console.log('credit2: ' + credit)
              }
              else if(value.credit == 0)
              {
                credit = ''
                calccredit = 0.00
                
              }
              else{
                totalcredit += parseFloat(value.credit)
                credit = formatnumber(value.credit)
                calccredit = value.credit
                
              }

              

              balance = parseFloat(calcdebit) - parseFloat(calccredit)

              if(balance < 0)
              {
                totalbalance += parseFloat(balance)
                balance = '(' + formatnumber(balance * -1) + ')'
              }
              else{
                totalbalance += parseFloat(balance)
                balance = formatnumber(balance)
              }

              // credit = parseFloat(value.credit)
              // debit = parseFloat(value.debit)
              // balance = debit - credit

              $('#sl_body').append(`
                <tr>
                  <td>`+date+`</td>
                  <td>`+value.code+` - `+value.account+`</td>
                  <td class="text-right">`+debit+`</td>
                  <td class="text-right">`+credit+`</td>
                  <td class="text-right">`+balance+`</td>
                  <td>`+value.mapname+`</td>
                </tr>
              `)

              // console.log(date)
            });

            $('#sl_body').append(`
              <tr>
                <td colspan="2" class="text-right text-bold">TOTAL: </td>
                <td class="text-right  text-bold">`+formatnumber(totaldebit)+`</td>
                <td class="text-right text-bold">`+formatnumber(totalcredit)+`</td>
                <td class="text-right text-bold">`+formatnumber(totalbalance)+`</td>
                <td></td>
              </tr>
            `)


            $('#modal-rpt_sl').modal('show')    
          }
        });

        
      })

      $(document).on('change', '#sl_filter', function(){
        $('#btn_sl').trigger('click')
      })

      $(document).on('click', '#sl_print', function(){
        var range = $('#dtrange').val()
        var filter = $('#sl_filter').val()

        window.open('/finance/accounting/sl_generate?range='+range+'&action=print&filter='+filter, '_blank');
      })




    });

  </script>

@endsection

