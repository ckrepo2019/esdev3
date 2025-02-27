@extends('finance.layouts.app')

@section('content')
	
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-info">
            <div class="row">
              <div class="col-md-8">
                <h4 class="text-warning"><b>Setup  </b></h4>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div id="accountingsetup" class="col-md-3" style="cursor: pointer;">
                <div class="small-box bg-olive">
                  <div class="inner">
                    <br>
                    <span class="text-bold text-light">Accounting <br> Setup</span>
                  </div>
                  <div class="icon">
                    <i class="fas fa-cogs"></i>
                  </div>
                  <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>

              {{-- <div id="reportsetup" class="col-md-3" style="cursor: pointer;">
                <div class="small-box bg-warning">
                  <div class="inner">
                    <br>
                    <span class="text-bold text-light">Report <br> Setup</span>
                  </div>
                  <div class="icon">
                    <i class="fas fa-tools"></i>
                  </div>
                  <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div> --}}
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
    

    $(document).ready(function(){

        $('.dtrangepicker').daterangepicker()
      
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        // $('#modal-je').draggable({
        //     handle: '.modal-header'
        // });


        $(document).on('click', '#reportsetup', function(){
            window.location.replace('/finance/accounting/reportsetup');
        });

        $(document).on('click', '#accountingsetup', function(){
            window.location.href="/finance/accsetup/"
        });

      

      

    });

  </script>

@endsection
