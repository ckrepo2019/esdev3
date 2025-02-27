@extends('finance.layouts.app')

@section('content')
	
  <section class="content">
    <div class="row">
      
      <div class="col-md-12">
        <h3 class="m-0 text-dark">
          EXPENSES
        </h3>  
        <br>
        <div class="row mb-3">
          <div class="col-md-2">
            <select id="expense_filter" class="form-control">
              <option selected>ALL</option>
              <option>SUBMITTED</option>
              <option>APPROVED</option>
              <option>DISAPPROVED</option>
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
              <input id="search" type="text" class="form-control" placeholder="Search Expenses">
              <div class="input-group-append">
                <span class="input-group-text"><i class="fa fa-search"></i></span>    
              </div>
            </div>
          </div>
          <div class="col-md-2">
            <button id="expense-new" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-expense">Create</button>
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
                <table class="table table-striped table-sm text-sm">
                  <thead class="">
                    <th>Reference</th>
                    <th>Purpose</th>
                    <th>Date</th>
                    <th>Pay To</th>
                    <th class="text-right">Amount</th>
                    <th></th>
                    
                    
                  </thead>
                  <tbody id="expense-list" style="cursor: pointer">
                    
                  </tbody>
                  <tfoot>
                    <tr id="expense-total">
                      
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

  <div class="modal fade show" id="modal-expense" aria-modal="true" style="display: none; margin-top: -25px; overflow-y: hidden; height: 768px">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Expenses - <span id="action" class="text-bold"></span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <h1 id="lblrefnum" class="text-secondary" data-toggle="tooltip" title="Reference Number"></h1>
            </div>
            <div class="col-md-6">
              <button id="expense_print" class="btn btn-primary float-right"><i class="fas fa-print"></i> Print</button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5">
              <div class="form-group input-group-lg">
                <label>Purpose</label>
                <input id="description" type="text" class="form-control form-control-lg text-lg validate is-invalid" placeholder="Description">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group input-group-lg">
                <label>Date</label>
                <input id="transDate" type="date" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Remarks</label>
                <textarea id="remarks" class="form-control" rows="2" placeholder="Notes ..."></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label>Paid By</label>
                <div class="row">
                  <div class="icheck-primary col-md-6">
                    <input id="employee" class="form-check-input" type="radio" name="paidby">
                    <label for="employee" class="form-check-label">Employee (to reimburse)</label>
                  </div>
                  <div class="icheck-primary col-md-6">
                    <input id="company" class="form-check-input" type="radio" name="paidby">
                    <label for="company" class="form-check-label">Company</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Pay To</label>
                <div class="input-group">
                  <select id="requestby" class="select2bs4 validate is-invalid">
                  </select>
                  <span class="input-group-append">
                    <button id="company_new" class="btn btn-primary" data-toggle="tooltip" title="Add Company/Person"><i class="fas fa-external-link-alt"></i></button>      
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <label>Department</label>
              <div id="dept" class="text-bold text-primary"></div>
            </div>
          </div>
        </div>
        <div class="modal-body" style="height: 230px; overflow-y: auto;">
          <div class="row">
            <div class="col-md-12 table-responsive p-0">
              <table class="table table-striped table-head-fixed">
                <thead>
                  <th>Particulars</th>
                  <th class="text-right">Amount</th>
                  <th class="text-center">QTY</th>
                  <th class="text-right">Total</th>
                </thead>
                <tbody id="expense-detail" style="cursor: pointer">
                  
                </tbody>
                <tfoot>
                  <tr>
                   <td colspan="4">
                    <u><a href="#" id="additem">Add item</a></u>
                  </td>
                  <tr id="gtotal">
                    
                  </tr>
                 </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
          <div>
            <button id="btndisapprove" type="button" class="btn btn-danger" data-id="">
              <i class="fas fa-thumbs-down"></i> Disapprove
            </button>
            <button id="btnapprove" type="button" class="btn btn-success" data-id="">
              <i class="fas fa-thumbs-up"></i> Approve
            </button>
            <button id="btnsaveheader" type="button" class="btn btn-primary" data-id="">
              <i class="fas fa-save"></i> Save
            </button>
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show mt-5" id="modal-item" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-md">
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
                  <select id="itemdesc" class="select2bs4">
                    @foreach(App\FinanceModel::expenseitems() as $item)
                      <option value="{{$item->id}}">{{$item->description}}</option>
                    @endforeach
                  </select>
                  <span class="input-group-append">
                    <button id="btncreateItem" class="btn btn-primary" data-toggle="tooltip" title="Create Items"><i class="fas fa-external-link-alt"></i></button>      
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Amount</label>
                <input id="itemamount" type="text" class="form-control calc"  name="currency-field" id="fc-txtamount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>QTY</label>
                <input id="itemqty" type="number" class="form-control calc">
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Total</label>
                <input id="totalamount" type="text" class="form-control" placeholder="0.00" disabled="" name="currency-field" id="fc-txtamount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency">
              </div>
            </div>
          </div>
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

  <div class="modal fade show" id="modal-company" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Company</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body text-sm">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="class-desc" class="col-sm-3 col-form-label">Company</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control form-control-sm validation" id="company_name" placeholder="Pay To" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-desc" class="col-sm-3 col-form-label">Address</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control form-control-sm validation" id="company_address" placeholder="Address" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-desc" class="col-sm-3 col-form-label">Department</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control form-control-sm validation" id="company_department" placeholder="Department" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="company_save" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>


@endsection
@section('js')
  
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
 
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      screenadjust();

      function screenadjust()
      {
          var screen_height = $(window).height();
          $('.table_main').css('height', screen_height - 300);

      }

      $(window).resize(function(){
          screenadjust();
      })

      $('#item-class').val('');
      $('#item-class').trigger('change');
      searchexpense();
      function searchexpense()
      {
        var filter = $('#search').val();
        var status = $('#expense_filter').val();
        var datefrom = $('#datefrom').val();
        var dateto = $('#dateto').val();

        $.ajax({
          url:"{{route('searchexpenses')}}",
          method:'GET',
          data:{
            filter:filter,
            status:status,
            datefrom:datefrom,
            dateto:dateto
          },
          dataType:'json',
          success:function(data)
          {
            // console.log(data.gtotal);
            $('#expense-list').html(data.list);
            // $('#expense-total').html(data.gtotal);
          }
        });         
      }

      $(document).on('change', '#expense_filter', function(){
        searchexpense();
      })

      function loadexpensedetail(headerid)
      {
        $.ajax({
          url:"{{route('loadexpensedetail')}}",
          method:'GET',
          data:{
            headerid:headerid
          },
          dataType:'json',
          success:function(data)
          {
            $('#expense-detail').html(data.list);
            $('#gtotal').html(data.gtotal);
          }
        });          
      }

      function saveExpense(trans)
      {
        var description = $('#description').val();
        var transdate = $('#transDate').val();
        var requestby = $('#requestby').val();
        var remarks = $('#remarks').val();
        var dataid = $('#btnsaveheader').attr('data-id');

        // console.log(dataid);
        if($('#company').prop('checked') == true)
        {
          var paidby = 'COMPANY';
        }
        else
        {
          var paidby = 'EMPLOYEE';
        }

        $.ajax({
          url:"{{route('saveexpense')}}",
          method:'GET',
          data:{
            description:description,
            transdate:transdate,
            requestby:requestby,
            paidby:paidby,
            remarks:remarks,
            trans:trans,
            dataid:dataid
          },
          dataType:'',
          success:function(data)
          {

            // console.log(trans);
            if(trans == 1)
            {

              const Toast = Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                onOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'success',
                  title: 'Expense Saved.'
                });

              searchexpense();
            }
            else
            {
              // console.log(data);
              $('#btnsaveheader').attr('data-id', data);
              saveexpensedetail($('#btnsaveheader').attr('data-id'))
            }
          }
        }); 
      }

      function saveexpensedetail(headerid)
      {
        var itemid = $('#itemdesc').val();
        var itemprice = $('#itemamount').val();
        var qty = $('#itemqty').val();
        var total = $('#totalamount').val();
        var detailid = $('#btnadd').attr('data-id');

        $.ajax({
          url:"{{route('saveexpensedetail')}}",
          method:'GET',
          data:{
            headerid:headerid,
            detailid:detailid,
            itemid:itemid,
            itemprice:itemprice,
            qty:qty,
            total:total
          },
          dataType:'',
          success:function(data)
          {
            loadexpensedetail($('#btnsaveheader').attr('data-id'));
          }
        }); 
      }

      function validate()
      {
        var valCount = 0;
        $('.validate').each(function(){
          if($(this).hasClass('is-invalid'))
          {
            valCount += 1;
          }

          if(valCount > 0)
          {
            $('#btnsaveheader').prop('disabled', true);
          }
          else
         {
            $('#btnsaveheader').prop('disabled', false);           
         } 

        });
      }

      function loaditems(itemid)
      {
        $.ajax({
          url:"{{route('loadexpenseitems')}}",
          method:'GET',
          data:{
            itemid:itemid
          },
          dataType:'json',
          success:function(data)
          {
            $('#itemdesc').html(data.list);
          }
        }); 
      }

      $(document).on('change', '.validate', function(){
        
          if($(this).val() != '' && $(this).val() != null)
          {
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid');
          }
          else
          {
            $(this).addClass('is-invalid');
            $(this).removeClass('is-valid'); 
          }

        validate();
      });

      $(document).on('change', '.calc', function(){
        var qty = $('#itemqty').val();
        var itemamount = $('#itemamount').val().replace(',', '');
        console.log(itemamount);
        var total =  qty * itemamount;

        $('#totalamount').val(total);
        $('#totalamount').trigger('keyup');
        // $('#totalamount').number(true, 2);
      });

      $(document).on('keyup', '#search', function(){
        searchexpense();
      });

      $(document).on('click', '#expense-new', function(){
        var dateNow = $('#datenow').val();

        $('#btnapprove').hide();
        $('#btndisapprove').hide();
        
        $('#description').val('');
        $('#employee').prop('checked', false);
        $('#company').prop('checked', false);
        $('#remarks').val('');
        $('#action').text('New');
        $('#transDate').val(dateNow);
        $('#requestby').val('');
        $('#requestby').trigger('change');
        $('#expense-detail').empty();
        $('#btnsaveheader').attr('data-id', '');
        $('#gtotal td').empty();
        $('#company').prop('checked', true);
        $('#lblrefnum').text('Reference Number');
        $('#lblrefnum').addClass('text-default');
        // console.log($('#requestby').val());
        $('.validate').trigger('change');
        $('#additem').text('Add item');

        $('#expense_print').prop('disabled', true);

        validate();
      });
      
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

      $(document).on('click', '#btnsaveheader', function(){
        saveExpense(1);
      });

      $(document).on('click', '#btnadd', function(){
        
        if($('#btnsaveheader').attr('data-id') == '')
        {
          saveExpense(0);
        }
        else
        {
          saveexpensedetail($('#btnsaveheader').attr('data-id'))
        }
      });

      $(document).on('mouseenter', '.expense-tr', function(){
        $(this).addClass('bg-info');
      });
      $(document).on('mouseout', '.expense-tr', function(){
        $(this).removeClass('bg-info');
      });

      $(document).on('click', '.expense-tr', function(){
        var headerid = $(this).attr('data-id');

        // $('#btnapprove').show();

        @if(count(App\FinanceModel::loadElevatedUser(auth()->user()->id)) > 0)
          $('#btnapprove').show();
          $('#btndisapprove').show();
        @else
          $('#btnapprove').hide();
          $('#btndisapprove').hide();
        @endif
        

        $.ajax({
          url:"{{route('loadexpense')}}",
          method:'GET',
          data:{
            headerid:headerid,
          },
          dataType:'json',
          success:function(data)
          {
            $('#description').val(data.description);
            $('#transDate').val(data.transdate);
            $('#remarks').val(data.remarks);
            $('#requestby').val(data.requestedbyid);
            $('#requestby').trigger('change');
            $('#btnsaveheader').attr('data-id', headerid);
            $('#lblrefnum').text(data.refnum);

            if(data.paidby == 'EMPLOYEE')
            {
              $('#employee').prop('checked', true);
            }
            else
            {
              $('#company').prop('checked', true);
            }

            loadexpensedetail(headerid);
            $('.validate').trigger('change');
            validate();

            $('#action').text('Edit')

            $('#lblrefnum').attr('data-status', data.status);

            if(data.status == 'APPROVED')
            {
              $('#lblrefnum').addClass('text-success');
              $('#lblrefnum').removeClass('text-secondary text-danger');
              $('#modal-expense .modal-header').removeClass('bg-info bg-danger');
              $('#modal-expense .modal-header').addClass('bg-success');
              $('#btndisapprove').prop('disabled', true);
              $('#btnapprove').prop('disabled', true);
              $('#btnsaveheader').prop('disabled', true)
              $('#additem').text('');
              $('#expense_print').prop('disabled', false);
            }
            else if(data.status == 'DISAPPROVED')
            {
              $('#lblrefnum').addClass('text-danger');
              $('#lblrefnum').removeClass('text-secondary text-success');
              $('#modal-expense .modal-header').removeClass('bg-info bg-success');
              $('#modal-expense .modal-header').addClass('bg-danger');
              $('#btndisapprove').prop('disabled', true);
              $('#btnapprove').prop('disabled', true);
              $('#btnsaveheader').prop('disabled', true) 
              $('#additem').text('');
              $('#expense_print').prop('disabled', true);
            }
            else
            {
              $('#lblrefnum').addClass('text-secondary');
              $('#lblrefnum').removeClass('text-success text-danger');
              $('#modal-expense .modal-header').removeClass('bg-danger bg-success');
              $('#modal-expense .modal-header').addClass('bg-info');
              $('#btndisapprove').prop('disabled', false);
              $('#btnapprove').prop('disabled', false);
              $('#btnsaveheader').prop('disabled', false)
              $('#additem').text('Add Item');
              $('#expense_print').prop('disabled', true);
            }

            $('#modal-expense').modal('show');
          }
        }); 

      });

      $(document).on('change', '#requestby', function(){
        var dept = '';
        dept = $(this).find('option:selected').attr('data-dept');
        // dept = dept
        $('#dept').text(dept);
        // $('#dept').text($(this).find('option:selected').attr('data-dept').toUpperCase());
      });
      
      $(document).on('click', '#btncreateItem', function(){
        $('#modal-item-new').modal('show');
      });

      $(document).on('click', '#saveNewItem', function(){
        var itemcode = $('#item-code').val();
        var itemdesc = $('#item-desc').val();
        var classid = $('#item-class').val();
        var amount = $('#item-amount').val();
        var isexpense = $('#chkexpense-new').val();

        // console.log($('#chkexpense-new').val());

        $.ajax({
          url:"{{route('saveNewItem')}}",
          method:'GET',
          data:{
            itemcode:itemcode,
            itemdesc:itemdesc,
            claassid:classid,
            amount:amount,
            isexpense:isexpense
          },
          dataType:'',
          success:function(data)
          {
            // console.log(data);
            loaditems(data);
          }
        });
      });

      $(document).on('click', '#btnapprove', function(){

        var dataid = $('#btnsaveheader').attr('data-id');

        Swal.fire({
          title: 'Approve Expenses?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Approve'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"{{route('approveexpense')}}",
              method:'GET',
              data:{
                dataid:dataid
              },
              dataType:'',
              success:function(data)
              {
                if(data == 1)
                {
                  Swal.fire(
                    'Approved!',
                    'Expenses has been approved.',
                    'success'
                  );    
                }
                else if(data == 2)
                {
                  Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong.',
                    footer: ''
                  }); 
                }
                else
                {
                  Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'User not elevated.',
                    footer: ''
                  });
                }
                searchexpense();
                $('#modal-expense').modal('hide');
              }
            });      
          }
        })
      });

      $(document).on('click', '#btndisapprove', function(){

        var dataid = $('#btnsaveheader').attr('data-id');

        Swal.fire({
            title: 'Disapprove Expenses?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Disapprove'
          }).then((result) => {
            if (result.value) {

              $.ajax({
                url:"{{route('disapproveexpense')}}",
                method:'GET',
                data:{
                  dataid:dataid
                },
                dataType:'',
                success:function(data)
                {
                  if(data == 1)
                  {
                    Swal.fire(
                      'Disapprove!',
                      'Expenses has been disapproved.',
                      'success'
                    );    
                  }
                  else if(data == 2)
                  {
                    Swal.fire({
                      type: 'error',
                      title: 'Oops...',
                      text: 'Something went wrong.',
                      footer: ''
                    }); 
                  }
                  else
                  {
                    Swal.fire({
                      type: 'error',
                      title: 'Oops...',
                      text: 'User not elevated.',
                      footer: ''
                    });
                  }

                  $('#modal-expense').modal('hide');
                }
              }); 
            }
          });
      });

      $(document).on('mouseover', '#expense-detail tr', function(){
        $(this).addClass('bg-secondary')
      });

      $(document).on('mouseout', '#expense-detail tr', function(){
        $(this).removeClass('bg-secondary')
      });

      $(document).on('click', '#expense-detail tr', function(){
        
        var detailid = $(this).attr('data-id');

        if($('#lblrefnum').attr('data-status') != 'APPROVED' && $('#lblrefnum').attr('data-status') != 'DISAPPROVED')
        {
          $('#itemaction').text('EDIT')
          $('#btnadd').attr('data-id', detailid);

          $.ajax({
            url:"{{route('expenseItemInfo')}}",
            method:'GET',
            data:{
              detailid:detailid
            },
            dataType:'json',
            success:function(data)
            {
              $('#itemdesc').val(data.itemid);
              $('#itemdesc').trigger('change');
              $('#itemamount').val(data.itemprice);
              $('#itemamount').trigger('keyup');
              $('#itemqty').val(data.qty);
              $('#totalamount').val(data.total);
              $('#totalamount').trigger('keyup');
              $('#btndel').show();
              $('#modal-item').modal('show');
            }
          }); 
        }

      });

      $(document).on('click', '#company_new', function(){
        $('#modal-company').modal('show');
        setTimeout(function(){
          $('#company_name').focus();
        }, 300)
      });

      $(document).on('click', '#company_save', function(){
        var company = $('#company_name').val();
        var address = $('#company_address').val();
        var department = $('#company_department').val();

        $.ajax({
          url: '{{route('company_create')}}',
          type: 'GET',
          data: {
            company:company,
            address:address,
            department:department
          },
          success:function(data)
          {
            if(data == 'done')
            {
              const Toast = Swal.mixin({
              toast: true,
              position: 'top',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
              })

              Toast.fire({
                type: 'success',
                title: 'Company Saved.'
              });

              loadcompany();
              $('#requestby').trigger('change');
            }
            else
            {
              const Toast = Swal.mixin({
              toast: true,
              position: 'top',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
              })

              Toast.fire({
                type: 'error',
                title: 'Company already exist'
              }); 
            }
          }
        });
      });

      loadcompany();

      function loadcompany()
      {
        $.ajax({
          url: '{{route('company_load')}}',
          type: 'GET',
          dataType: 'json',
          success:function(data)
          {
            $('#requestby').html(data.list);
          }
        });
      }

      $(document).on('click', '#btndel', function(){
        var dataid = $('#btnadd').attr('data-id');

        $.ajax({
          url: '{{route('expese_deletedetail')}}',
          type: 'GET',
          data: {
            dataid:dataid
          },
          success:function(data)
          {
            loadexpensedetail($('#btnsaveheader').attr('data-id'));
          }
        });
        
      });



    });
  </script>
@endsection
