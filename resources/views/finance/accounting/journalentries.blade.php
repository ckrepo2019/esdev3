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
            <li class="breadcrumb-item active">Journal Entries</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section> --}}
  <section class="content pt-0">
    <div class="row form-group">
      <div class="col-md-12">
        <h1 class="">GENERAL JOURNAL</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-7">

      </div>
      <div class="col-md-5">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">
                <i class="far fa-calendar-alt"></i>
              </span>
            </div>
            <input type="text" class="form-control float-right dtrangepicker" id="dtrange" style="cursor: pointer">
            <div class="input-group-append">
                <button id="btncreateJE" class="btn btn-primary input-group-button">
                  <i class="fas fa-plus-square"></i> Create
                </button>
            </div>
            <div class="input-group-append">
              <button id="btngenerateJE" class="btn btn-warning input-group-button">
                <i class="fas fa-recycle"></i> Generate JE
              </button>
          </div>
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
              
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div id="main_table" class="table-responsive">
                  <table class="table table-striped table-sm text-sm">
                    <thead>
                      <tr>
                        <th>DATE</th>
                        <th>REFERENCE NUMBER</th>
                        <th>ACCOUNT</th>
                        <th class="text-center">DEBIT</th>
                        <th class="text-center">CREDIT</th>
                        <th>REMARKS</th>
                      </tr>
                    </thead>
                    <tbody id="jehead" style="cursor: pointer;">
                      
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="3" class="text-right text-bold">TOTAL: </td>
                        <td class="text-right text-bold" id="headtotaldebit"></td>
                        <td class="text-right text-bold" id="headtotalcredit"></td>
                        <td></td>
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
  </section>

@endsection
@section('modal')
  <div class="modal fade" id="modal-je" aria-modal="true" style="display: none; margin-top: -25px;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-info" style="cursor: move">
          <h4 class="modal-title">Journal Entry</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="mb-1 col-md-8">
              <h5>Reference Number: <span id="refid" data-id=""></span> </h5>
            </div>
            <div class="col-md-3 mb-1 ">
              <div class="form-group row">
                <div class="col-md-2 mt-1">
                  Date:
                </div> 
                <div class="col-md-10">
                  <input id="transdate" type="date" name="" class="form-control">   
                </div> 
              </div>
            </div>
          </div>
          <div class="row je-head">
            <div class="col-md-7 text-center">
              <label>Account</label>
            </div>
            <div class="col-md-2 text-center">
              <label>Debit</label>
            </div>
            <div class="col-md-2 text-center">
              <label>Credit</label>
            </div>
          </div>
          <span id="jelist">
            
          </span>
          <div class="row je-foot">
            <div class="col-md-7 p-2 ml-2">
              <span id="addline" class="text-info" style="cursor: pointer"><u>Add a line</u></span>
            </div>
          </div>
          <hr>
          <div class="row je-foot p-2">
            <div class="col-md-7 text-right">
              
            </div>
            <div id="totaldebit" class="col-md-2 text-right text-bold text-lg text-success" data-amount="0">
              0.00
            </div>
            <div id="totalcredit" class="col-md-2 text-right text-bold text-lg text-success">
              0.00
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>Remarks</label>
              <textarea id="je_remarks" class="form-control"></textarea>
            </div>

            <div class="col-md-6" style="margin-top: 2.4em;">
              <button id="je_print" class="btn btn-primary btn-lg">Print</button>
            </div>
          </div>
          <hr>

            {{-- <div class="table-responsive">
              <table class="table">
                <thead>
                  <th>Account</th>
                  <th class="text-right">Debit</th>
                  <th class="text-right">Credit</th>
                  <th class="text-right"></th>
                </thead>
                <tbody id="jelist">
                  
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="4">
                      <span id="addline" class="text-info" style="cursor: pointer">Add a line</span>
                    </td>
                  </tr>
                  <tr>
                    <td></td>
                    <td id="totaldebit" data-value="0" class="text-right text-bold">0.00</td>
                    <td id="totalcredit" data-value="0" class="text-right text-bold">0.00</td>
                  </tr>
                </tfoot>
              </table>
            </div> --}}
          
          <div class="row">
            <div class="col-md-8">
              <input id="txttest" class="text-right" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" name="currency-field" data-type="currency" style="border:none; color: white; width: 1px" autocomplete="off">

              {{-- <input id="txttest" class="text-right" style="border:none; color: white; width: 1px" autocomplete="off"> --}}


              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
            </div>
            <div class="col-md-2 dropdown">
              <button id="btnaction" class="btn btn-warning btn-block dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(8px, -84px, 0px);">
                <a id="deleteje" class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i> Delete</a>
                <a id="postje" class="dropdown-item text-success" href="#"><i class="fas fa-vote-yea"></i></i> Post</a>
              </div>
            </div>
            <div class="col-md-2">
              
              <button id="btnsaveJE" type="button" class="btn btn-primary btn-block" data-id="1" disabled="">
                <span id="spanSpinner" hidden="">
                  <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                  Loading...
                </span>
                <span id="spanSave"><i class="fas fa-save"></i> Save</span>
              </button>
                
            </div>
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-selectAccount" aria-modal="true" style="display: none; margin-top: -25px;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h4 class="modal-title">Select Account</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <select id="cboselcoa" class="select2bs4 form-control">      
          </select>
        </div>
        <div class="modal-footer float-right">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
            <button id="btnselaccount" type="button" class="btn btn-primary" data-dismiss="modal">
              OK
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

  <div class="modal fade" id="modal-selectSY" aria-modal="true" style="display: none; margin-top: 25em;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h4 class="modal-title">Generate JE - Select School Year</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <select id="jeauto_sy" class="select2bs4 form-control">      
            @foreach(db::table('sy')->orderBy('sydesc')->get() as $sy)
              @if($sy->isactive == 1)
                <option value="{{$sy->id}}" selected>{{$sy->sydesc}}</option>
              @else
                <option value="{{$sy->id}}">{{$sy->sydesc}}</option>  
              @endif
            @endforeach
          </select>
        </div>
        <div class="modal-footer float-right">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
            <button id="jeauto_proceed" type="button" class="btn btn-primary" data-dismiss="modal">
              GENERATE
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
      getCOA();

      $('.dtrangepicker').daterangepicker()
      
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      // $('#modal-je').draggable({
      //   handle: '.modal-header'
      // });

      screenadjust()

      function screenadjust()
      {
        var screen_height = $(window).height();

        $('#main_table').css('height', screen_height - 315)
			  $('#setup_table').css('height', screen_height - 150)
            // $('.screen-adj').css('height', screen_height - 223);
      }

      loadcoa();
      loadjeHead();

      @php
        $date = date_create(App\FinanceModel::getServerDateTime());
        $d1 = date_format($date, 'm/d/Y');
        $d2 = date_format($date, 'm/d/Y');
        $dtrange = $d1 . ' - ' . $d2;
      @endphp

      $('#dtrange').val("{{$dtrange}}");

      function loadjeHead()
      {
        var daterange = $('#dtrange').val();

        $.ajax({
          url: "{{route('loadje')}}",
          type: 'GET',
          dataType: 'json',
          data: {
            daterange:daterange
          },
          success:function(data)
          {
            $('#jehead').html(data.jelist);
            $('#headtotaldebit').text(data.totaldebit)
            $('#headtotalcredit').text(data.totalcredit)
          }
        })
      }

      function calcTotals()
      {
        var debit = 0
        var credit = 0;
        var iscredit = 0;
        var isdebit = 0;

        $('.iscredit').each(function(){
          credit = $(this).val();
          if(credit == '')
          {
            credit = 0;
          }
          else
          {
            credit = credit.replace(',', '');  
          }

          iscredit += parseFloat(credit) ;
          console.log('credit: ' + credit);
        });

        console.log('iscredit: ' + iscredit);

        $('#txttest').val(iscredit);
        $('#txttest').trigger('focus');
        $('.iscredit').trigger('focus');
        $('#totalcredit').text($('#txttest').val());

        if($('#totalcredit').text() == $('#totaldebit').text())
        {
          $('#totalcredit').removeClass('text-danger');
          $('#totaldebit').removeClass('text-danger');
          $('#totalcredit').addClass('text-success');
          $('#totaldebit').addClass('text-success');
        }
        else
        {
          $('#totalcredit').addClass('text-danger');
          $('#totaldebit').addClass('text-danger');
          $('#totalcredit').removeClass('text-success');
          $('#totaldebit').removeClass('text-success'); 
        }
        
          
      }

      // $(document).on('change', '.iscredit', function(){
      //   calcTotals();
      // });

      $(document).on('click', '#btncreateJE', function(){
        @php
          $datenow = date_create(App\FinanceModel::getServerDateTime());
          $datenow = date_format($datenow, 'Y-m-d');
        @endphp

        je_enabled();

        $('#refid').text('');
        $('#refid').attr('data-id', '');
        $('#refid').attr('data-action', 'create');
        $('#jelist').empty();
        $('#je_remarks').val('')

        $('#totaldebit').text('0.00');
        $('#totalcredit').text('0.00');

        $('#transdate').val('{{$datenow}}');
        $('#btnaction').hide();

        $('#modal-je').modal('show');
      });

      $(document).on('change', '#txtcoa', function(){
        var val = $(this).val();
        var dataline = $(this).closest('tr').attr('data-line');

        // console.log('dataline: ' + dataline);

        $('#datalistCOA option').each(function(){
          var dataval = $(this).val();
          console.log(dataval + ' = ' + val);
          if(dataval == val)
          {
            // console.log($(this).attr('data-id'));
            // console.log('data-line: ' + dataline);

            $('[data-line='+dataline+']').find('td .iscoa').attr('data-id', $(this).attr('data-id'));


            // $('[data-line='+dataline+'] #txtcoa').attr('data-id', $(this).attr('data-id'));
          }
        });
      })

      $(document).on('click', '#addline', function(){
        lineval += 1;

        $(this).attr('id', 'addline1');

        if($('#refid').attr('data-action') == 'create')
        {
          $('#jelist').append(`

              <div class="row je-body p-1" data-line="`+lineval+`">
                <div class="col-md-7">
                  <select id="je-coa" class="select2bs4 form-control je-coa">
                    `+chartofaccounts+`
                  </select>
                </div>
                <div class="col-md-2 text-right">
                    <input id="txtdebit" type="text" class="text-right isdebit form-control" pattern="^\\$\\d{1,3}(,\\d{3})*(\\.\\d+)?$" name="currency-field" data-type="currency" autocomplete="off">
                  </div>
                  <div class="col-md-2 text-right">
                    <input id="txtcredit" class="text-right iscredit form-control" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" name="currency-field" data-type="currency" autocomplete="off">
                  </div>
                <div class="col-md-1">
                  <button class="btn btn-danger btn-sm btn-linedel" data-line="`+lineval+`"><i class="fas fa-trash"></i></button>
                </div>
              </div>

            `);

          $('.select2bs4').select2({
              theme: 'bootstrap4'
            });

          // $('#jelist').append(`
          //   <tr data-line="`+lineval+`">
          //     <td class="" style="width: 465px; height:40px;">
          //       <input id="txtcoa" list="datalistCOA" class="iscoa is-invalid" placeholder="Select Account">
          //       <datalist id="datalistCOA">
          //         `+chartofaccounts+`
          //       </datalist>
          //     </td>
          //     <td style="width:50px; height:40px">
          //       <input id="txtdebit" type="text" class="text-right isdebit" pattern="^\\$\\d{1,3}(,\\d{3})*(\\.\\d+)?$" name="currency-field" data-type="currency" autocomplete="off">
          //     </td>
          //     <td style="width:50px; height:40px">
          //       <input id="txtcredit" class="text-right iscredit" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" name="currency-field" data-type="currency" autocomplete="off">
          //     </td>
          //     <td class="text-center" style="width:20px">
          //       <button class="btn btn-danger btn-sm btn-linedel" data-line="`+lineval+`"><i class="fas fa-trash"></i></button>
          //     </td>
          //   </tr>
          // `);
        }
        else
        {
          var headerid = $('#refid').attr('data-id');

          $.ajax({
            async:false,
            url: '{{route('appendeditdetail')}}',
            type: 'GET',
            dataType: 'json',
            data: {
              headerid:headerid
            },
            success:function(data)
            {
              
              $('#jelist').append(`
                <div class="row je-body p-1" data-line="`+lineval+`" data-id="`+data.detailid+`">
                  <div class="col-md-7">
                    <select id="je-coa" class="select2bs4 je-coa form-control">
                      `+chartofaccounts+`
                    </select>
                  </div>
                  <div class="col-md-2 text-right">
                    <input id="txtdebit" type="text" class="text-right isdebit form-control" pattern="^\\$\\d{1,3}(,\\d{3})*(\\.\\d+)?$" name="currency-field" data-type="currency" autocomplete="off">
                  </div>
                  <div class="col-md-2 text-right">
                    <input id="txtcredit" class="text-right iscredit form-control" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" name="currency-field" data-type="currency" autocomplete="off">
                  </div>
                  <div class="col-md-1">
                    <button class="btn btn-danger btn-sm btn-linedel" data-line="`+lineval+`" data-id="`+data.detailid+`"><i class="fas fa-trash"></i></button>
                  </div>
                </div>

              `);

              $('.select2bs4').select2({
                theme: 'bootstrap4'
              });




              // $('#jelist').append(`
              //   <tr data-line="`+lineval+`" data-id="`+data.detailid+`">
              //     <td class="" style="width: 465px; height:40px;">
              //       <input id="txtcoa" list="datalistCOA" class="iscoa is-invalid" placeholder="Select Account">
              //       <datalist id="datalistCOA">
              //         `+chartofaccounts+`
              //       </datalist>
              //     </td>
              //     <td style="width:50px; height:40px">
              //       <input id="txtdebit" type="text" class="text-right isdebit" pattern="^\\$\\d{1,3}(,\\d{3})*(\\.\\d+)?$" name="currency-field" data-type="currency" autocomplete="off">
              //     </td>
              //     <td style="width:50px; height:40px">
              //       <input id="txtcredit" class="text-right iscredit" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" name="currency-field" data-type="currency" autocomplete="off">
              //     </td>
              //     <td class="text-center" style="width:20px">
              //       <button class="btn btn-danger btn-sm btn-linedel" data-line="`+lineval+`" data-id="`+data.detailid+`"><i class="fas fa-trash"></i></button>
              //     </td>
              //   </tr>
              // `);     
            }
          });
        }
        $('#addline1').attr('id', 'addline');
        console.log($('.je-body').length);

      })

      function loadcoa()
      {
        // $.ajax({
        //   url:"",
        //   method:'GET',
        //   data:{
            
        //   },
        //   dataType:'json',
        //   success:function(data)
        //   {
        //     // $('#datalistCOA').html(data.coalist);

        //   }
        // });


        // $('#datalistCOA').html(chartofaccounts);
        // console.log($('#datalistCOA').text());

      }

      function getCOA()
      {
        $.ajax({
          url:"{{route('jeloadcoa')}}",
          method:'GET',
          data:{
            
          },
          dataType:'json',
          success:function(data)
          {
            // $('#datalistCOA').html(data.coalist);
            chartofaccounts = data.coalist;

            // console.log(chartofaccounts);
          }
        });
      }

      $(document).on('click', '.selacc', function(){
        $('#btnselaccount').attr('data-line', $(this).attr('data-line'));
        $('#modal-selectAccount').modal('show')
      });


      $(document).on('click', '#btnselaccount', function(){

        selcoa = $('#cboselcoa option:selected').text();

        $('#jelist tr [data-line=' + $(this).attr('data-line') + ']').text(selcoa);

        // console.log(select);

        // $('#jelist td data-line | = ' $(this).attr('data-line')).text('testing');
      });

      function checktotalbal()
      {
        if($('#totalcredit').text() == $('#totaldebit').text())
        {
          $('#totalcredit').removeClass('text-danger');
          $('#totaldebit').removeClass('text-danger');
          $('#totalcredit').addClass('text-success');
          $('#totaldebit').addClass('text-success');

          $('#btnsaveJE').prop('disabled', false);
        }
        else
        {
          $('#totalcredit').addClass('text-danger');
          $('#totaldebit').addClass('text-danger');
          $('#totalcredit').removeClass('text-success');
          $('#totaldebit').removeClass('text-success'); 

          $('#btnsaveJE').prop('disabled', true);
        }
      }

      $(document).on('keyup', '.isdebit', function(e){
        if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 8 || e.keyCode == 46 || e.keyCode == 190)
        {
          // console.log(e.keyCode);
          tDebit = 0;

          $('.isdebit').each(function(){
            var val = $(this).val().replace(/,/g, '');

            // val = val.replace(',', '');

            console.log('val: ' + val);

            if(val == '')
            {
              val = 0;
            }

            tDebit += parseFloat(val);
            console.log('tDebit: ' + tDebit);
          });

          $('#txttest').val(tDebit);
          // $('#txttest').trigger('focus');
          // $(this).trigger('focus');
          $('#totaldebit').text($('#txttest').val());


          $('#txttest').val($(this).val());
          // $('#txttest').trigger('focus');
          // $(this).trigger('focus');
          // $(this).val($('#txttest').val());
          // $('#txttest').val('');
        }
      });

      // $(document).on('keyup', '.isdebit', function(e){
      //   if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 8 || e.keyCode == 46 || e.keyCode == 190)
      //   {
      //     $('.isdebit').each(function(){

      //       var val = $(this).val();

      //       if(val == '')
      //       {
      //         val = 0;
      //       }

      //       tDebit += parseFloat(val);

      //       $.ajax({
      //         url: '{route('je_calcdebit')}}',
      //         type: 'GET',
      //         dataType: '',
      //         data: {
      //           val:val
      //         },
      //         success:function(data)
      //         {
      //           var _totaldebit = $('#totaldebit').attr('data-amount');



      //         }
      //       })
      //     })
      //   }
      // })

      $(document).on('change', '#txtdebit', function(){
        var val = $(this).val();
        
        $('#txttest').val(tDebit);
        $('#txttest').trigger('focus');
        $(this).trigger('focus');
        $('#totaldebit').text($('#txttest').val());

        $('#txttest').val($(this).val());
        $('#txttest').trigger('focus');
        $(this).trigger('focus');
        $(this).val($('#txttest').val());
        $('#txttest').val('');

        checktotalbal();

      });




      $(document).on('keyup', '#txtcredit', function(e){
        if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 8 || e.keyCode == 46 || e.keyCode == 190)
        {
          console.log(e.keyCode);
          tCredit = 0;

          $('.iscredit').each(function(){
            var val = $(this).val();

            val = val.replace(/,/g, '');

            if(val == '')
            {
              val = 0;
            }

            tCredit += parseFloat(val);

            console.log(tCredit);
          });

          $('#txttest').val(tCredit);
          // $('#txttest').trigger('focus');
          // $(this).trigger('focus');
          $('#totalcredit').text($('#txttest').val());


          $('#txttest').val($(this).val());
          // $('#txttest').trigger('focus');
          // $(this).trigger('focus');
          // $(this).val($('#txttest').val());
          // $('#txttest').val('');
        }
      });

      $(document).on('change', '#txtcredit', function(){
        var val = $(this).val();

        
        $('#txttest').val(tCredit);
        $('#txttest').trigger('focus');
        $(this).trigger('focus');
        $('#totalcredit').text($('#txttest').val());

        $('#txttest').val($(this).val());
        $('#txttest').trigger('focus');
        $(this).trigger('focus');
        $(this).val($('#txttest').val());
        $('#txttest').val('');

        checktotalbal();

      });

      // $(document).on('click', '#btnsaveJE', function(){
      //   $('#btnsaveJE').prop('disabled', true);
      // })

      $(document).on('click', '#btnsaveJE', function(){
          // $('#modal-overlay').modal('show');
          
          var totalcredit = $('#totalcredit').text();
          var totaldebit = $('#totaldebit').text();
          var remarks = $('#je_remarks').val()

          if(totalcredit == totaldebit)
          {
            if(!$('#refid').hasClass('text-success'))
            {
              $('.je-body').each(function(){
                var accid = $(this).find('#je-coa').val();
                var iscredit = $(this).find('#txtcredit').val();
                var isdebit = $(this).find('#txtdebit').val();
                var refid = $('#refid').attr('data-id');
                var transdate = $('#transdate').val();
                var action = $('#refid').attr('data-action');
                var detailid = $(this).attr('data-id');

                $('#btnsaveJE').prop('disabled', true);
                
                if(action == 'create'){
                  var data = {
                    accid:accid,
                    iscredit:iscredit,
                    isdebit:isdebit,
                    refid:refid,
                    transdate:transdate,
                    remarks:remarks,
                    action:action
                  }
                }
                else {
                  var data = {
                    accid:accid,
                    iscredit:iscredit,
                    isdebit:isdebit,
                    refid:refid,
                    transdate:transdate,
                    action:action,
                    detailid:detailid,
                    remarks:remarks
                  }
                }

                // console.log('detailid: ' + detailid);
                // console.log('refid: ' + refid);

                $.ajax({
                  async: false,
                  url: '{{route('saveje')}}',
                  type: 'GET',
                  datatype: 'json',
                  data: data,
                  success:function(data)
                  {
                    // console.log('ref: ' + data.refid);
                    $('#refid').text(data.refnum);
                    $('#refid').attr('data-id', data.refid);
                    $('#btnaction').show();
                    // $('#modal-je').modal('hide');
                  }
                });
              });
            }
            else
            {
              Swal.fire(
                'Something went wrong.',
                'Journal Entry already posted',
                'warning'
              );
            }
          loadjeHead();
        }
        else
        {
          Swal.fire(
            'Unbalanced',
            'Please check entries.',
            'error'
          );
        }
      });

      $(document).on('change', '#dtrange', function(){
        loadjeHead();
      });

      $(document).on('mouseenter', '#jehead tr', function(){
        $(this).addClass('bg-secondary');
      });

      $(document).on('mouseout', '#jehead tr', function(){
        $(this).removeClass('bg-secondary');
      });

      $(document).on('click', '#jehead tr', function(){
        var jeid = $(this).attr('data-id')

        // $('#jelist').remove();

        $.ajax({
          url: '{{route('editje')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            jeid:jeid
          },
          success:function(data)
          {
            $('#refid').attr('data-id', data.jeid);
            $('#refid').text(data.refid);
            $('#refid').attr('data-action', 'update');
            $('#transdate').val(data.transdate);
            $('#je_remarks').val(data.remarks)
            // $('#jelist').html(data.jelist);

            var item = data.jearray;

            $('.je-body').remove();

            $('#jelist').append(item);

            // $('.je-coa').each(function(){
            //   $('')
            // })

            // $('#je-coa').select2({
            //   theme: 'bootstrap4'
            // });

            if(data.jestatus == 'Posted')
            {
              je_disabled();
            }
            else
            {
              je_enabled();
            }

            $('#btnaction').show();
            
            $('#totaldebit').text(data.isdebit);
            $('#totalcredit').text(data.iscredit);
            lineval = $('.je-body').length;

            checktotalbal();
            $('#modal-je').modal('show');    

          }
        });
      });

      $(document).on('click', '.btn-linedel', function(){
        console.log('test');
        if($('#refid').attr('data-action') == 'create')
        {
          $('.je-body[data-line='+$(this).attr('data-line')+']').remove();
          calcTotals();
        }
        else
        {
          var detailid = $(this).attr('data-id');
          $('tr[data-id='+detailid+']').remove();
          $('.je-body[data-line='+$(this).attr('data-line')+']').remove();
          calcTotals();
          $.ajax({
            url: '{{route('deletejedetail')}}',
            type: 'GET',
            dataType: '',
            data: 
            {
              detailid:detailid
            },
            success:function(data)
            {

            }
          });
            
        }
      });

      $(document).on('focus', '.je-coa', function(){
        $(this).select2({
          theme: 'bootstrap4'
        });

        $(this).select2('open');
      });

      $(document).on('click', '#postje', function(){
        var refid = $('#refid').attr('data-id');
        
        if(!$('#refid').hasClass('text-success'))
        {
          Swal.fire({
            title: 'Post Journal Entry?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Post'
          }).then((result) => {
            if (result.value) {

              $.ajax({
                url:"{{route('postje')}}",
                method:'GET',
                data:{
                  refid:refid
                },
                dataType:'',
                success:function(data)
                {
                  loadjeHead();
                  $('#modal-je').modal('hide');
                  Swal.fire(
                    'Success!',
                    'Journal Entry has been posted',
                    'success'
                  );
                }
              });      
            }
          });
        }
        else
        {
          Swal.fire(
              'Something went wrong.',
              'Journal Entry already posted',
              'warning'
            );
        }
      });

      $(document).on('keydown', '.iscredit', function(e){
        var code = e.keyCode || e.which;

        if(code === 9)
        {
          e.preventDefault();
          $('#addline').trigger('click');
        }
      });

      function je_disabled()
      {
        $('#refid').addClass('text-success');
        $('.je-coa').prop('disabled', true);
        $('.isdebit').prop('disabled', true);
        $('.iscredit').prop('disabled', true);
        $('.btn-linedel').prop('disabled', true);
        $('#transdate').prop('disabled', true);
        $('#btnaction').prop('hidden', true);
        $('#btnsaveJE').prop('hidden', true);
        $('#addline').prop('hidden', true);
        $('#je_remarks').prop('disabled', true)
      }

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
        $('#je_remarks').prop('disabled', false)
      }

      $(document).on('change', '#txttest', function(){
        var currency = $(this).val();
        var numUSD = new Intl.NumberFormat('en-US', {
          style:"currency",
          currency: "USD"
        });

        console.log(numUSD.format(currency));
      })

      $(document).on('click', '#jeauto_proceed', function(){
        var range = $('#dtrange').val()
        var syid = $('#jeauto_sy').val()

        $.ajax({
          type: "GET",
          url: "{{route('je_autogenerate')}}",
          data: {
            range:range,
            syid:syid
          },
          // dataType: "dataType",
          success: function (data) {
            loadjeHead();
          }
        });
      })

      $(document).on('click', '#btngenerateJE', function(){
        $('#modal-selectSY').modal('show')
      })

      $(document).on('click', '#je_print', function(){
        var id = $('#refid').attr('data-id')

        window.open('/finance/accounting/editje?jeid='+id+'&action=print', '_blank');
      })

    });

  </script>

@endsection
