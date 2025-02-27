@extends('finance.layouts.app')

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <!-- <h1>Finance</h1> -->
          
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Examination Permit</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content pt-0">
    <div class="container-fluid">
      <div class="row">
        <div class="card col-md-12 p-0">
      		<div class="card-header text-lg bg-info">
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
            <b>Examination Permit</b></h4>
      		</div>
      		<div class="card-body">
            <div class="row">
              <div class="col-md-2">
                <select id="glevel" name="" class="text-secondary form-control select2bs4" style="width: 100% !important" value="">
                  <option value="0">Select Grade Level</option>
                  @foreach(App\FinanceModel::loadGlevel() as $glevel)
                    <option value="{{$glevel->id}}">{{$glevel->levelname}}</option>
                  @endforeach
                </select>
              </div>
              <div id="divcourse" class="col-md-2" style="display: none">
                <select id="courseid" name="" class="text-secondary form-control select2bs4" style="width: 100% !important" value="">
                  <option value="0">Select Course</option>
                  @foreach(App\FinanceModel::loadCourses() as $course)
                    <option value="{{$course->id}}">{{$course->courseabrv}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <input type="search" id="searchstud" class="form-control" autocomplete="off" placeholder="Search Student">  
              </div>
              <div class="col-md-3">
                <div class="form-group row">
                  <label for="lessthanamount" class="col-sm-7 control-label text-right mt-1">Balance less than</label>  
                  <div class="col-sm-5">
                    <input id="lessthanamount" class="form-control" placeholder="0.00" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" name="currency-field" data-type="currency">
                  </div>    
                </div>
              </div>
              <div class="col-md-1">
                <button id="btn-search" data-allow="0" class="btn btn-primary">SEARCH</button>
              </div>
            </div>
            <div class="row">
              <div id="divsetup" class="col-md-2">
                <button id="btn-qsetup" class="btn btn-outline-secondary btn-block"><i class="fas fa-cogs"></i> Setup</button>
              </div>
              <div class="">
                
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-6">
          <div class="row mb-3">
            <div class="col-md-3">
              <button id="btn-notallow" class="btn btn-danger btn-block btn-condition">Not Allowed</button>
            </div>
            <div class="col-md-3">
              <button id="btn-allow" class="btn btn-outline-success btn-block btn-condition">Allowed</button>
            </div>
            <div class="col-md-6">
              
            </div>
          </div>
        </div>
        <div class="col-md-6">

          <div class="row mb-3">
            <div class="col-sm-4">
              <button id="btn-ledger" class="btn btn-outline-primary btn-block">Student Ledger</button>
            </div>
            <div class="col-sm-4">
              <button id="btn-assessment" class="btn btn-outline-danger btn-block">Student Assessment</button>
            </div>
            <div class="col-sm-4" hidden="">
              <button id="btn-payment" class="btn btn-outline-success btn-block">Payment</button>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card card-info">
            <div class="card-header bg-primary">
              <div class="row">
                <div class="col-sm-6">
                  <h3 class="card-title">Student List</h3>    
                </div>
                <div class="col-sm-6 text-right">
                  Students: <span id="studcount" class="text-bold">0</span>
                </div>
              </div>
            </div>
            <div class="card-body table-responsive p-0" style="height: 330px">
              <table class="table table-striped">
                <thead class="bg-warning">
                  <tr>
                    <th>Name</th>
                    <th>Level</th>
                    <th class="text-left" id="table-desc">Balance</th>
                    <th style=""><button id="permit-all" class="btn btn-success btn-sm btn-block">Permit All</button></th>
                  </tr>
                </thead>
                <tbody id="studlist" class="text-sm" style="cursor: pointer;">
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>

      <div class="col-6">
        <div id="divledger" class="card card-info payinfo">
          <div class="card-header bg-info">
            <h3 class="card-title">Student Ledger</h3>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-striped">
              <thead class="bg-warning">
                <tr>
                  <th>Date</th>
                  <th>Particulars</th>
                  <th>Amount</th>
                  <th>Payment</th>
                  <th>Balance</th>
                </tr>
              </thead>
              <tbody id="ledgerlist" class="text-sm">
                
              </tbody>
            </table>
          </div>
        </div>

        <div id="divassessment" class="card card-info payinfo" hidden="">
          <div class="card-header bg-info">
            <h3 class="card-title">Student Assessment</h3>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-striped">
              <thead class="bg-warning">
                <tr>
                  <th>Particulars</th>
                  <th>Amount</th>
                  <th>Payment</th>
                  <th>Balance</th>
                </tr>
              </thead>
              <tbody id="assessmentlist">
                
              </tbody>
            </table>
          </div>
        </div>

        <div id="divpayment" class="card card-info payinfo" hidden="">
          <div class="card-header bg-info">
            <h3 class="card-title">Payment</h3>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-striped">
              <thead class="bg-warning">
                <tr>
                  <th>Date</th>
                  <th>OR number</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody id="paymentlist">
                
              </tbody>
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

  <div class="modal fade show" id="modal-qsetup" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-secondary">
          <h4 class="modal-title">Quarter Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12 table-responsive">
                  <div id="qlist">
                    
                  </div>
                  {{-- <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Description</th>
                        <th>Month</th>
                        <th class="text-center">Active</th>
                      </tr>
                    </thead>
                    <tbody id="qlist" style="cursor: pointer">
                      
                    </tbody>
                  </table> --}}
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          {{-- <button id="savePayClass" type="button" class="btn btn-primary" data-dismiss="modal">Save</button> --}}
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-qsetupdetail" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-secondary">
          <h4 class="modal-title">Quarter Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Description</th>
                        <th class="text-center">Active</th>
                      </tr>
                    </thead>
                    <tbody id="qlist">
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          {{-- <button id="savePayClass" type="button" class="btn btn-primary" data-dismiss="modal">Save</button> --}}
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
    
    $(document).ready(function(){

      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      $('body').addClass('sidebar-collapse');

      $(document).on('click', '#btn-ledger', function(){
        $('.payinfo').prop('hidden', true);
        $('#divledger').prop('hidden', false);
      });

      $(document).on('click', '#btn-assessment', function(){
        $('.payinfo').prop('hidden', true);
        $('#divassessment').prop('hidden', false);
      });

      $(document).on('click', '#btn-payment', function(){
        $('.payinfo').prop('hidden', true);
        $('#divpayment').prop('hidden', false);
      });

      function studfilter()
      {
        var levelid = $('#glevel').val();
        var ispercent = $('ispercent').val();
        var lessthanamount = $('#lessthanamount').val();
        var allow = $('#btn-search').attr('data-allow');
        var searchstud = $('#searchstud').val();
        var courseid = $('#courseid').val();

        $.ajax({
          url:"{{route('permit_studfilter')}}",
          method:'GET',
          data:{
            levelid:levelid,
            ispercent:ispercent,
            lessthanamount:lessthanamount,
            allow:allow,
            searchstud:searchstud,
            courseid:courseid
          },
          dataType:'json',
          beforeSend:function(){
            $('#modal-overlay').modal('show');
          },
          success:function(data)
          {
            $('#studlist').html(data.studlist);
            $('#studcount').text($('#studlist tr').length);
            $('#modal-overlay').modal('hide');
          }
        }); 
      }

      $(document).on('click', '.btn-condition', function(){
        $('#btn-allow').addClass('btn-outline-success');
        $('#btn-allow').removeClass('btn-success');

        $('#btn-notallow').addClass('btn-outline-danger');
        $('#btn-notallow').removeClass('btn-danger');

        if($(this).attr('id') == 'btn-allow')
        {
          $(this).removeClass('btn-outline-success');
          $(this).addClass('btn-success');
          $('#btn-search').attr('data-allow', 1);
          $('#permit-all').prop('hidden', true)
        }
        else
        {
          $(this).removeClass('btn-outline-danger');
          $(this).addClass('btn-danger'); 
          $('#btn-search').attr('data-allow', 0);
          $('#permit-all').prop('hidden', false)
        }
      });

      $(document).on('select2:close', '#glevel', function(){
        
        if($(this).val() >= 17 && $(this).val() <= 21)
        {
          $('#divcourse').show();
        }
        else
        {
          $('#divcourse').hide();
          studfilter();
        }
        
      });

      $(document).on('select2:close', '#courseid', function(){
        studfilter();
      })

      $(document).on('click', '.btn-condition', function(){
        studfilter();
      });

      $(document).on('mouseenter', '#studlist tr', function(){
        $(this).addClass('bg-secondary')
      });

      $(document).on('mouseout', '#studlist tr', function(){
        $(this).removeClass('bg-secondary')
      });

      $(document).on('click', '#studlist tr', function(){
        var studid = $(this).attr('data-id');

        $('#studlist tr').removeClass('bg-info');
        $(this).addClass('bg-info');

        $.ajax({
          url:"{{route('permit_loadinfo')}}",
          method:'GET',
          data:{
            studid:studid
          },
          dataType:'json',
          success:function(data)
          {
            $('#ledgerlist').html(data.ledgerlist);
            $('#assessmentlist').html(data.assessmentlist)
          }
        }); 
      });

      $(document).on('click', '.btn-permit', function(){
        var studid = $(this).attr('data-id')
        
        Swal.fire({
          title: 'Permit this student?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Permit!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url:"{{route('permit_allowtoexam')}}",
              method:'GET',
              data:{
                studid:studid
              },
              dataType:'',
              complete:function()
              {
                studfilter();

                Swal.fire(
                  'Permited!',
                  '',
                  'success'
                );
              }
            }); 
          }
        });
      });

      function loadsetup()
      {
        $.ajax({
          url:"{{route('permit_loadsetup')}}",
          method:'GET',
          data:{
            
          },
          dataType:'json',
          success:function(data)
          {
            $('#qlist').html(data.qlist);
            $('#modal-qsetup').modal('show');
          }
        });
      }

      $(document).on('click', '#btn-search', function(){
        studfilter();
      });

      $(document).on('click', '#btn-qsetup', function(){

        loadsetup();
                 
      });


      $(document).on('click', '.is-active', function(){
        var dataid = $(this).attr('id');

        if($(this).prop('checked') == true)
        {
          var val = 1;
        }
        else
        {
          var val = 0;
        }

        $('.is-active').each(function(){
          $(this).prop('checked', false)
        });

        if(val == 1)
        {
          $('#' + dataid).prop('checked', true);
        }
        else
        {
          $('#' + dataid).prop('checked', false);
        }

        $.ajax({
          url:"{{route('permit_activequarter')}}",
          method:'GET',
          data:{
            dataid:dataid,
            val:val
          },
          dataType:'',
          beforeSend:function(){
            $('#modal-overlay').modal('show');
          },
          success:function(data){
            loadsetup();
          },
          complete:function()
          {
            $('#modal-overlay').modal('hide');
          }
        });
      });

      $(document).on('mouseenter', '#qlist tr', function(){
        $(this).addClass('bg-primary');
      });

      $(document).on('mouseout', '#qlist tr', function(){
        $(this).removeClass('bg-primary');
      });

      $(document).on('click', '#qlist tr', function(){
        
      });    


    });

  </script>


@endsection