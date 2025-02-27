@extends('finance.layouts.app')

@section('content')
  <section class="content">
    
  	<div class="main-card card">
  		<div class="card-header text-lg bg-info">
      <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
          <i class="fa fa-file-invoice nav-icon"></i> 
          <b>STUDENT LEDGER</b></h4>
  		</div>
  		<div class="card-body">
        <div class="row">
          <div class="col-md-5">
            <div class="form-group">
              <select id="studName" name="studid" class="text-secondary form-control select2bs4 updq" value="">
                <option>SELECT STUDENT</option>
                @php
                  $student = db::table('studinfo')
                    ->select('id', 'sid', 'lastname', 'firstname', 'middlename')
                    ->where('deleted', 0)
                    ->orderBy('lastname')
                    ->orderBy('firstname')
                    ->get();
                @endphp
                @foreach($student as $stud)
                  <option value="{{$stud->id}}">{{$stud->sid}} - {{$stud->lastname}}, {{$stud->firstname}} {{$stud->middlename}}</option>
                @endforeach
              </select> 
            </div>
          </div>
          <div class="col-md-1">
            
          </div>

          <div class="col-md-3 filters">
            <select id="sem" class="select2bs4 updq" style="width: 100%;">
                @php 
                  $semid = App\RegistrarModel::getSemID();
                @endphp

                @foreach(App\RegistrarModel::getSem() as $sem)
                  @if($sem->id == $semid)
                    <option selected value="{{$sem->id}}">{{$sem->semester}}</option>
                  @else
                    <option value="{{$sem->id}}">{{$sem->semester}}</option>

                  @endif

                @endforeach
              </select>
            </div>
          
          <div class="col-md-3 filters">
            <select id="sy" class="form-control updq select2bs4" style="width: 100%;">
            </select>
          </div>

          <div class="col-md-6 tv_filters" style="display: none;">
            <div class="input-group row">
              <label for="tvl_batch" class="mt-2">Batch:</label>
              <div class="col-9">
                <div class="input-group mb-2">
                  <select id="tvlbatch" class="w-100 select2bs4 updq">
                    <option></option>
                    @foreach(db::table('tv_batch')->where('deleted', 0)->get() as $batch)
                      @php
                        $sdate = date_create($batch->startdate);
                        $sdate = date_format($sdate, 'm/d/Y');
                        $edate = date_create($batch->enddate);
                        $edate = date_format($edate, 'm/d/Y');
                      @endphp


                      @if($batch->isactive == 1)
                        <option value="{{$batch->id}}" selected="">{{$sdate . ' - ' . $edate}}</option>
                      @else
                        <option value="{{$batch->id}}">{{$sdate . ' - ' . $edate}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-7">
            <span id="ledger_info">Level|Section: </span>
          </div>
          <div class="col-md-5 text-right">
            <button class="btn btn-info text-sm btn-sm div_studyload" id="btnstudyload" style="display: none;" formtarget="_blank" data-level="" data-status=""><i class="fas fa-book-open">
                </i> Study Load
              </button>
            <span class="btn btn-success btn-sm text-sm" id="btnadjustledger"><i class="fas fa-compress-arrows-alt"></i> Adjustment</span>
            <button class="btn btn-primary btn-sm text-sm" id="btnprint" formtarget="_blank"><i class="fas fa-print"></i> Print</button>
            <span class="btn btn-outline-secondary btn-sm text-sm" id="btnreloadledger"><i class="fas fa-sync"></i> Update Ledger</span>
          </div>

        </div>
        <div class="row form-group mt-1">
          <div class="col-md-3">
            <span id="ledger_info_status">Status: </span>
          </div>
          <div class="col-md-9 text-right">
            Current Fees: <span id="feesname" class="text-bold" data-id=""></span>
          </div>
        </div>
        
        
  			<div class="row">
          <div class="col-12">
            <div id="table_main" class="table-responsive">
              <table class="table table-striped table-sm text-sm">
                <thead class="bg-warning">
                  <tr>
                    <th>DATE</th>
                    <th class="">PARTICULARS</th>
                    <th class="text-center">CHARGES</th>
                    <th class="text-center">PAYMENT</th>
                    <th class="text-center">BALANCE</th>
                  </tr>  
                </thead> 
                <tbody id="ledger-list">
                  
                </tbody>             
              </table>
            </div>
          </div>          
        </div>
  		</div>
  	</div>
  </section>
@endsection

@section('modal')
  <div class="modal fade show" id="modal-studlist" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Select Student</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <div class="col-sm-10 input-group">
                  <input id="txtsearch" type="text" class="form-control validation" id="item-code" placeholder="SEARCH STUDENT" onkeyup="this.value = this.value.toUpperCase();">
                  <span class="input-group-append">
                    <span type="button" class="btn btn-info btn-flat"><i class="fas fa-search"></i></span>
                  </span>
                </div>
              </div>
              <div class="row">
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ID NO.</th>
                        <th>NAME</th>
                        <th>GRADE | SECTION</th>
                        <th>ESC</th>
                      </tr>
                    </thead>
                    <tbody id="stud-list">
                      
                    </tbody>
                  </table>
                </div>
              </div>
              
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </form>
        </div>
        <div class="modal-footer ">
          <button id="savestud" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-fees" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-secondary">
          <h4 class="modal-title">Select Fees</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="loadfeelist" class="row">
            
          </div>
            
        </div>
        <div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          <button id="btnreloadproceed" type="button" class="btn btn-primary" data-dismiss="modal">PROCEED</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-adjustment" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h4 class="modal-title"><i class="fas fa-compress-arrows-alt"></i> Adjustment</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-1">
              <button id="adj_btndebit" class="btn btn-outline-primary active btn-block">DEBIT</button>
            </div>
            <div class="col-md-1">
              <button id="adj_btncredit" class="btn btn-outline-success btn-block">CREDIT</button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <hr>
              <div class="row mt-2">
                <div id="divname" class="col-md-6">
                  <label>Name: </label> <span class="studname"></span>
                </div>
                <div id="divname" class="col-md-3">
                  <label>LEVEL: </label> <span class="levelname"></span>
                </div>
                <div id="divname" class="col-md-3">
                  <label>GRANTEE: </label> <span class="grantee"></span>
                </div>
                <div id="divname" class="col-md-3">
                  <label id="divstrand">Strand: </label> <span class="strand"></span>
                </div>
              </div>
              <hr>
              <div id="row_debit" class="row" style="display: block">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-4">
                      <input id="debit_description" type="" name="" class="form-control w-100 debit_field is-invalid" autofocus="" placeholder="Debit Description"> 
                    </div> 

                    <div class="col-md-3">
                      <select class="select2bs4 form-control debit_field is-invalid" id="debit_classid">
                        <option value="0">Classification</option>
                        @foreach(db::table('itemclassification')->where('deleted', 0)->get() as $class)
                          <option value="{{$class->id}}">{{$class->description}}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-md-2">
                      <input id="debit_amount" type="text" name="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" autocomplete="off" class="form-control debit_field is-invalid" placeholder="0.00">
                    </div>

                    <div class="col-md-3">
                      <select class="select2bs4 form-control debit_field is-invalid" id="debit_mop">
                        <option value="0">Mode of Payment</option>
                        @foreach(db::table('paymentsetup')->where('deleted', 0)->get() as $mop)
                          <option value="{{$mop->id}}">{{$mop->paymentdesc}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>

                <div class="modal-footer ">
                  <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                  <button id="debit_save" type="button" class="btn btn-primary" disabled="">POST</button>
                </div>
              </div>
              <div id="row_credit" class="row" style="display: none">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-4">
                      <input id="credit_description" type="" name="" class="form-control w-100 credit_field is-invalid" autofocus="" placeholder="Credit Description"> 
                    </div> 

                    <div class="col-md-3">
                      <select class="select2bs4 form-control" id="credit_classid">
                        
                      </select>
                    </div>

                    <div class="col-md-2">
                      <input id="credit_amount" type="text" name="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" autocomplete="off" class="form-control credit_field is-invalid" placeholder="0.00">
                    </div>
                  </div>
                  <div class="modal-footer ">
                    <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                    <button id="credit_save" type="button" class="btn btn-success" disabled="">POST</button>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
        {{-- <div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          <button id="btnreloadproceed" type="button" class="btn btn-primary" data-dismiss="modal">PROCEED</button>
        </div> --}}
      </div>
    </div> {{-- dialog --}}
  </div> 
  <div class="modal fade show" id="modal-reminder" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h4 class="modal-title">Reminder Slip</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="" class="row">
            <div class="col-md-2">
              <label>Due Date</label>
            </div>
            <div class="col-md-6">
              <input id="reminder_due" class="form-control" type="date" name="">
            </div>
          </div>
          <hr>
          <div class="row" style="height: 20em;">
            <div class="col-md-12 table-responsive">
              <table class="table table-borderless table-sm text-sm">
                <tbody id="reminder_list">
                  
                </tbody>
              </table>
            </div>
            
          </div>
            
        </div>
        <div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          <button id="reminder_print" type="button" class="btn btn-primary">PRINT</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>
  
  <div class="modal fade show" id="modal-studyload" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Study Load</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="" class="row">
            <div class="col-md-2">Course: </div>
            <div class="col-md-9 text-bold" id="sl_course"></div>
          </div>
          <div id="" class="row form-group">
            <div class="col-md-2">School Year: </div>
            <div class="col-md-2 text-bold" id="sl_sy"></div>
            <div class="col-md-2">Semester: </div>
            <div class="col-md-2 text-bold" id="sl_sem"></div>
          </div>
          
          <div class="row" style="">
            <div class="col-md-12 table-responsive">
              <table class="table table-sm text-sm">
                <thead>
                  <tr>
                    <th>CODE</th>
                    <th>DESCRIPTION</th>
                    <th>UNITS</th>
                    
                  </tr>
                </thead>
                <tbody id="sl_list">
                  
                </tbody>
              </table>
            </div>
            
          </div>
            
        </div>
        <div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          {{-- <button id="reminder_print" type="button" class="btn btn-primary">PRINT</button> --}}
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>
  
@endsection

@section('js')
  <script type="text/javascript">

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

  function forceKeyPressUppercase(e)
  {
    var charInput = e.keyCode;
    if((charInput >= 97) && (charInput <= 122)) { // lowercase
      if(!e.ctrlKey && !e.metaKey && !e.altKey) { // no modifier key
        var newChar = charInput - 32;
        var start = e.target.selectionStart;
        var end = e.target.selectionEnd;
        e.target.value = e.target.value.substring(0, start) + String.fromCharCode(newChar) + e.target.value.substring(end);
        e.target.setSelectionRange(start+1, start+1);
        e.preventDefault();
      }
    }
  }

  document.getElementById("debit_description").addEventListener("keypress", forceKeyPressUppercase, false);
  document.getElementById("credit_description").addEventListener("keypress", forceKeyPressUppercase, false);
    
    $(document).ready(function(){
      
      loadSY();
      // $('#studName').val(null).trigger('change');
      // searchStud();

      $('.select2bs4').select2({
        theme: 'bootstrap4'

      });

      $(window).resize(function(){
            screenadjust();
        })

        screenadjust();
        function screenadjust()
        {
            var screen_height = $(window).height() - 324;
            $('#table_main').css('height', screen_height);
        }

      function loadSY()
      {
        $.ajax({
          url:"{{route('loadSY')}}",
          method:'GET',
          data:{

          },
          dataType:'',
          success:function(data)
          {
            // console.log(data);
            $('#sy').html(data);
          }
        }); 
      }

      function searchStud(text='')
      {
        // var query = $('#txtsearch').val();
        $.ajax({
          url:"{{route('searchStud')}}",
          method:'GET',
          data:{
            query:text
          },
          dataType:'json',
          success:function(data)
          {
            // $('#stud-list').html(data.list);

            $('#studName').html(data.list);
            
            $('#studName').val('');

          }
        }); 
      }

      $(document).on('click', '#btnsearch', function(){
        // $('#txtsearch').focus();
        // console.log('test');
      });

      // $(document).on('keyup', '.select2-search__field', function(){
      //   var text = $(this).val();
      //   console.log(text);
      //   searchStud(text);
      // });

      $(document).on('click', '.btnsel', function(){
        $('#modal-studlist').modal('hide');

        var syid = $('#sy').val();
        var studid = $(this).attr('data-id');
        var batchid = $('#tvlbatch').val();

        $('#studid').val(studid);
        $('#syid').val(syid);

        // console.log(studid);
        // console.log($('#n-' + studid).text());

        $('#studName').text($('#n-' + studid).text());
        $('#studName').removeClass('text-secondary');
        $('#glevel').text($('#g-' + studid).text());

        $.ajax({
          url:"{{route('getStudLedger')}}",
          method:'GET',
          data:{
            syid:syid,
            studid:studid,
            batchid:batchid
          },
          dataType:'json',
          success:function(data)
          {
            $('#ledger-list').html(data.list);
            $('#btnstudyload').attr('data-level', data.levelid);
            $('#btnstudyload').attr('data-status', data.studstatus);
            $('#feesname').text(data.feesname);
            $('#feesname').attr('data-id', data.feesid);
          }
        }); 
      });



      // $('#studName').focus(function(){
      //   console.log('test');
      //   searchStud();
      // })

      $(document).on('change', '.updq', function(){
        var syid = $('#sy').val();
        var semid = $('#sem').val(); 
        var studid = $('#studName').val();
        var batchid = $('#tvlbatch').val();

        $('#studid').val(studid);
        $('#syid').val(syid);

        // console.log(studid);
        $.ajax({
          url:"{{route('getStudLedger')}}",
          method:'GET',
          data:{
            syid:syid,
            studid:studid,
            semid:semid,
            batchid:batchid
          },
          dataType:'json',
          success:function(data)
          {
            $('#ledger-list').html(data.list);
            
            $('#btnstudyload').attr('data-level', data.levelid);
            $('#btnstudyload').attr('data-status', data.studstatus);
            
            if(data.levelid >= 17 && data.levelid <= 21)
            {
              $('.div_studyload').show();
            }
            else
            {
              $('.div_studyload').hide();
            }
            
            if(data.istvl == 1)
            {
              $('.filters').hide();
              $('.tv_filters').show();
            }
            else
            {
              $('.filters').show();
              $('.tv_filters').hide(); 
            }

            if(data.levelid == 14 || data.levelid == 15)
            {
              $('#ledger_info').text('Level|Section: ' + data.levelname + ' - ' + data.section +' | '+ data.strand +' | '+ data.grantee);
            }
            else if(data.levelid >=17 && data.levelid <= 21)
            {
              $('#ledger_info').text('Level|Course: ' + data.levelname +' '+ data.section);
            }
            else
            {
              $('#ledger_info').text('Level|Section: ' + data.levelname +' '+data.section +' | ' + data.grantee);
            }

            $('#ledger_info_status').text('Status: ' + data.studstatus);
            $('#feesname').text(data.feesname);
            $('#feesname').attr('data-id', data.feesid);
            
          }
        }); 
      });

      $(document).on('click', '#btnreloadledger', function(){
        var studid = $('#studName').val();
        var syid = $('#sy').val();
        var semid = $('#sem').val();

        $.ajax({
          url:"{{route('loadfees')}}",
          method:'GET',
          data:{
            studid:studid,
            syid:syid,
            semid:semid
          },
          dataType:'json',
          success:function(data)
          {

            $('#loadfeelist').html(data.feelist);
            $('#modal-fees').modal('show');

            selectFees($('#feesname').attr('data-id'));
          }
        });        

      });

      function selectFees(id=0)
      {
        $('.col-fees').each(function(){
          if($(this).attr('data-id') == id)
          {
            $(this).trigger('click');
          }
        })
      }

      $(document).on('click', '.col-fees', function(){
        dataid = $(this).attr('data-id');

        $('.col-fees').each(function(){
          if($(this).attr('data-id') == dataid)
          {
            $(this).find('.card-header').removeClass('bg-info');
            $(this).find('.card-header').addClass('bg-success');            
            $(this).find('.card-body').addClass('bg-light');
          }
          else
          {
            $(this).find('.card-header').removeClass('bg-success');
            $(this).find('.card-header').addClass('bg-info');
            $(this).find('.card-body').removeClass('bg-light');
          }
            
        });

        $('#btnreloadproceed').attr('data-id', dataid);

      });

      $(document).on('click', '#btnreloadproceed', function(){
        var studid = $('#studName').val();
        var feesid = $(this).attr('data-id');
		    var syid = $('#sy').val();
        var semid = $('#sem').val();

        Swal.fire({
          title: 'Reset Payment Account?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Reload it!'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"{{route('resetpayment_v3')}}",
              method:'GET',
              data:{
                studid:studid,
                feesid:feesid,
				syid:syid,
                semid:semid
              },
              dataType:'',
              success:function(data)
              {
                $('.updq').trigger('change');
                Swal.fire(
                  'Success!',
                  'Account has been Reloaded',
                  'success'
                );
              }
            });      
          }
        });
      });

      $(document).on('keyup', '#debit_description', function(){
        if($(this).val() != '')
        {
          $(this).removeClass('is-invalid');
          $(this).addClass('is-valid');
        }
        else
        {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid'); 
        }

        debit_validation();
      })

      $(document).on('keyup', '#debit_amount', function(){
        if($(this).val() != '')
        {
          $(this).removeClass('is-invalid');
          $(this).addClass('is-valid');
        }
        else
        {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid'); 
        }

        debit_validation();
      });

      $(document).on('change', '#debit_classid', function(){
        if($(this).val() != 0)
        {
          $(this).removeClass('is-invalid');
          $(this).addClass('is-valid');
        }
        else
        {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid'); 
        }

        debit_validation();
      })

      $(document).on('change', '#debit_mop', function(){
        if($(this).val() != 0)
        {
          $(this).removeClass('is-invalid');
          $(this).addClass('is-valid');
        }
        else
        {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid'); 
        }

        debit_validation();
      });

      $(document).on('keyup', '#credit_description', function(){
        if($(this).val() != '')
        {
          $(this).removeClass('is-invalid');
          $(this).addClass('is-valid');
        }
        else
        {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid'); 
        }

        credit_validation();
      });

      $(document).on('keyup', '#credit_amount', function(){
        if($(this).val() != '')
        {
          $(this).removeClass('is-invalid');
          $(this).addClass('is-valid');
        }
        else
        {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid'); 
        }

        credit_validation();
      });

      $(document).on('click', '#debit_save', function(){
        var desc = $('#debit_description').val();
        var classid = $('#debit_classid').val();
        var amount = $('#debit_amount').val();
        var mop = $('#debit_mop').val();
        var studid = $('#studName').val();
        var syid = $('#sy').val();
        var semid = $('#sem').val();

        if(debit_validation() == 0)
        {
		  $('#debit_save').prop('disabled', true);
		  
          $.ajax({
            url: '{{route('ledgeradj_debitsave')}}',
            type: 'GET',
            dataType: '',
            data: {
              studid:studid,
              desc:desc,
              classid:classid,
              amount:amount,
              mop:mop,
              syid:syid,
              semid:semid
            },
            success:function(data)
            {
              $('.updq').trigger('change');
              $('#modal-adjustment').modal('hide');
            }
          })
        }
        else
        {
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
            type: 'warning',
            title: 'Please fill all the required fields'
          }) 
        }
      });
	  
	  $(document).on('hidden.bs.modal', 'modal-adjustment', function(){
        $("#debit_save").prop('disabled', false); 
      });

      $(document).on('click', '#btnadjustledger', function(){
        if($('#studName').val() > 0)
        {
          $('#row_debit').show();
          $('#row_credit').hide();
          $('#adj_btndebit').addClass('active');
          $('#adj_btncredit').removeClass('active');

          $('#debit_description').val('');
          $('#debit_amount').val('');
          $('#debit_classid').val(0);
          $('#debit_mop').val(0);
          $('#debit_mop').trigger('change');
          $('#debit_classid').trigger('change');
          $('#debit_description').trigger('keyup');
          $('#debit_amount').trigger('keyup');

          $('#credit_description').val('');
          $('#credit_classid').val(0);
          $('#credit_amount').val('');
          $('#credit_classid').trigger('change');
          $('#credit_description').trigger('keyup');
          $('#credit_amount').trigger('keyup');


          loadadjinfo($('#studName').val());
        }
      });

      function loadadjinfo(studid)
      {
        var syid = $('#sy').val();
        var semid = $('#sem').val();

        $.ajax({
          url: '{{route('ledgeradj_loadadjinfo')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            studid:studid,
            syid:syid,
            semid:semid
          },
          success:function(data)
          {
            $('.studname').text(data.name);
            $('.levelname').text(data.levelname);
            $('.grantee').text(data.grantee);
            $('.strand').text(data.strand);

            if(data.strand == null)
            {
              $('#divstrand').hide();
              $('.strand').hide();
            }
            else
            {
              $('#divstrand').show();
              $('.strand').show();
            }

            $('#credit_classid').html(data.class);
            $('#modal-adjustment').modal('show');
          }
        });
      }

      $(document).on('click', '#adj_btndebit', function(){
        
        $(this).addClass('active');
        $('#adj_btncredit').removeClass('active');
        debit_validation();
        $('#row_debit').show();
        $('#row_credit').hide();
      });

      $(document).on('click', '#adj_btncredit', function(){

        $('#row_debit').hide();
        $('#row_credit').show();
        $(this).addClass('active');
        $('#adj_btndebit').removeClass('active');

        credit_validation();
      });

      $(document).on('click', '#credit_save', function(){
        var studid = $('#studName').val();
        var desc = $('#credit_description').val();
        var classid = $('#credit_classid').val();
        var amount = $('#credit_amount').val();
        var syid = $('#sy').val();
        var semid = $('#sem').val();

        if(credit_validation() == 0)
        {
          $.ajax({
            url: '{{route('ledgeradj_creditsave')}}',
            type: 'GET',
            dataType: '',
            data: {
              studid:studid,
              desc:desc,
              classid:classid,
              amount:amount,
              syid:syid,
              semid:semid
            },
            success:function(data)
            {
              $('.updq').trigger('change');
              $('#modal-adjustment').modal('hide');
            }
          });
        }
        else
        {
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
            type: 'warning',
            title: 'Please fill all the required fields'
          }) 
        }
      });

      function debit_validation()
      {
        var count = 0;
        $('.debit_field').each(function(){
          if($(this).hasClass('is-invalid'))
          {
            count += 1;
          }
        });

        if(count > 0)
        {
          $('#debit_save').prop('disabled', true);
        }
        else
        {
          $('#debit_save').prop('disabled', false); 
        }

        return count;
      }

      function credit_validation()
      {
        var count = 0;
        $('.credit_field').each(function(){
          if($(this).hasClass('is-invalid'))
          {
            count += 1;
          }
        });

        if(count > 0)
        {
          $('#credit_save').prop('disabled', true);
        }
        else
        {
          $('#credit_save').prop('disabled', false); 
        }

        return count;
      }

      $(document).on('click', '#btnprint', function(){
		if($('#studName').val() > 0)
		{
			var syid = $('#sy').val();
			var semid = $('#sem').val();
			var studid = $('#studName').val();

			// window.open('/finance/pdfledger/' + studid + '/' + syid + '/' + semid , '_blank');
			window.open('/finance/pdfledger?studid=' + studid + '&syid=' + syid + '&semid='+ semid,'_blank');
		}
		else{
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
            type: 'error',
            title: 'Please select a student'
          })
		}
      })
	  
	  $(document).on('click', '#btnreminder', function(){
        $('#modal-reminder').modal('show');
      });

      $(document).on('change', '#reminder_due', function(){
        var studid = $('#studName').val();
        var syid = $('#sy').val();
        var semid = $('#sem').val();
        var duedate = $(this).val();

        $.ajax({
          url: '{{route('ledger_reminder')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            studid:studid,
            syid:syid,
            semid:semid,
            duedate:duedate,
            action:'generate'
          },
          success:function(data)
          {
            $('#reminder_list').html(data.list);
          }
        });
      });

      $(document).on('click', '#reminder_print', function(){
        var studid = $('#studName').val();
        var syid = $('#sy').val();
        var semid = $('#sem').val();
        var duedate = $('#reminder_due').val();


        window.open('/finance/ledger_reminder?duedate='+duedate+'&action=print&studid='+studid+'&syid='+syid+'&semid='+semid, '_blank');
      })
      
      $(document).on('click', '#btnstudyload', function(){
        var syid = $('#sy').val();
        var semid = $('#sem').val();
        var studid = $('#studName').val();
        // var date = '{{date_format(date_create(App\RegistrarModel::getServerDateTime()), 'Y-m-d')}}'


        // window.open('printable/certification/generate?export=pdf&template=jhs&syid='+syid+'&studid='+studid+'&givendate='+date+'&schoolregistrar=', '_blank');
        if(studid != null)
        {
          // window.open('/printcor/'+studid+'?syid='+syid+'&semid='+semid+'&studid='+studid, '_blank');

          $.ajax({
            type: "GET",
            url: "{{route('ledger_studyload')}}",
            data: {
              syid:syid,
              semid:semid,
              studid:studid
            },
            dataType: "json",
            success: function (data) {
              $('#sl_list').html(data.list)
              $('#sl_sy').text(data.sydesc)
              $('#sl_sem').text(data.semdesc)
              $('#sl_course').text(data.course)
            }
          });

          $('#modal-studyload').modal('show')
        }
        else
        {
          const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })

          Toast.fire({
            type: 'warning',
            title: 'Please Select a Student'
          })
        }
      });

      $(document).on('click', '.adj_delete', function(){
        var dataid = $(this).attr('data-id');
        var studid = $('#studName').val();
        var feesid = $('#feesname').attr('data-id');
        var syid = $('#sy').val();
        var semid = $('#sem').val();

        Swal.fire({
          title: 'Remove Adjustment?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Remove'
        }).then((result) => {
          if (result.value == true) {
            $.ajax({
              url: '{{route('ledgeradj_delete')}}',
              type: 'GET',
              dataType: '',
              data: {
                dataid:dataid,
                studid:studid,
                feesid:feesid,
                syid:syid,
                semid:semid
              },
              success:function(data)
              {
                $('.updq').trigger('change');
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top',
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
                  title: 'Adjustment removed'
                })
              }
            });
            
          }
        })
      });

      $(document).on('click', '.discount_delete', function(){
        var dataid = $(this).attr('data-id');
        var studid = $('#studName').val();
        var feesid = $('#feesname').attr('data-id');
        var syid = $('#sy').val();
        var semid = $('#sem').val();

        Swal.fire({
          title: 'Remove Discount?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Remove'
        }).then((result) => {
          if (result.value == true) {
            $.ajax({
              url: '{{route('ledgerdiscount_delete')}}',
              type: 'GET',
              dataType: '',
              data: {
                dataid:dataid,
                studid:studid,
                feesid:feesid,
                syid:syid,
                semid:semid
              },
              success:function(data)
              {
                $('.updq').trigger('change');
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top',
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
                  title: 'Discount removed'
                })
              }
            });
            
          }
        })
      });
      
      $(document).on('click', '.ledgeroa_delete', function(){
        var dataid = $(this).attr('data-id');
        var studid = $('#studName').val();
        var feesid = $('#feesname').attr('data-id');
        var syid = $('#sy').val();
        var semid = $('#sem').val();

        Swal.fire({
          title: 'Remove Old account?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Remove'
        }).then((result) => {
          if (result.value == true) {
            $.ajax({
              url: '{{route('ledgeroa_delete')}}',
              type: 'GET',
              dataType: '',
              data: {
                dataid:dataid,
                studid:studid,
                feesid:feesid,
                syid:syid,
                semid:semid
              },
              success:function(data)
              {
                $('.updq').trigger('change');
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top',
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
                  title: 'Old account removed'
                })
              }
            });
            
          }
        })
      });

      // $(document).on('mouseover', '#ledger-list tr', function(){
      //   if($(this).hasClass('t_hover'))
      //   {
      //     $(this).addClass('bg-primary');
      //   }
      // });

      // $(document).on('mouseout', '#ledger-list tr', function(){
      //   if($(this).hasClass('t_hover'))
      //   {
      //     $(this).removeClass('bg-primary');
      //   }
      // });

      // $(document).on('click', '#adj_btncredit', function(){
      //   var studid = $('#studName').val();

      //   $.ajax({
      //     url: '{route('ledgeradj_loadcreditinfo')}}',
      //     type: 'GET',
      //     dataType: 'json',
      //     data: {
      //       studid:studid
      //     },
      //     success:function(data)
      //     {
      //       $('#row_debit').hide();
      //       $('#row_credit').show();
      //       $(this).addClass('active');
      //       $('#adj_btndebit').removeClass('active');   
      //       $('#credit_class_list').html(data.list);
      //       $('#credit_totalbalance').text(data.totalbalance);

      //     }
      //   });
      // });

      // $(document).on('change', '.credit_adjamount', function(){
      //   var totalamount = 0;
      //   var _amount = 0;

      //   $('.credit_adjamount').each(function(){
      //     if($(this).val() != null && $(this).val() != '')
      //     {
            
      //       _amount = $(this).val().replace(',', '');
      //       console.log(_amount);
      //       totalamount += parseFloat(_amount);
      //     }
      //   });

      //   $('#credit_totaladj').val(totalamount);
      //   $('#credit_totaladj').focus();
      //   $(this).focus();
      // })
    });

  </script>
@endsection