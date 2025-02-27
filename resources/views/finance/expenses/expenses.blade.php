@extends('finance.layouts.app')

@section('content')
	
  <section class="content">
    <div class="row">
      
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-10">
            <h1 class="m-0 text-dark">
              Expenses
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
                    <th>Voucher No.</th>
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
              {{-- <h1 id="lblrefnum" class="text-secondary" data-toggle="tooltip" title="Reference Number"></h1> --}}
              <input id="voucherno" type="text" class="form-control border-0 text-xl text-bold" placeholder="Voucher No.">
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
            <div class="col-md-3">
              <div class="form-group input-group-lg">
                <label>Date</label>
                <input id="transDate" type="date" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Remarks</label>
                <textarea id="remarks" class="form-control" rows="2" placeholder="Notes ..."></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Pay To</label>
                <select id="requestby" class="select2bs4 validate is-invalid">
                </select>
                {{-- <div class="input-group"> --}}
                  
                  {{-- <span class="input-group-append">
                    <button id="company_new" class="btn btn-primary text-sm" data-toggle="tooltip" title="Add Company/Person"><i class="fas fa-external-link-alt"></i></button>      
                  </span> --}}
                {{-- </div> --}}
              </div>
            </div>
            <div class="col-md-2">
              <label>Payment Type</label><br>
              <div class="form-group clearfix mt-2">
                <div class="icheck-primary d-inline">
                  <input type="radio" id="expense_cash" name="r1" checked="">
                  <label for="expense_cash">
                    CASH
                  </label>
                </div>&nbsp;&nbsp;&nbsp;
                <div class="icheck-primary d-inline">
                  <input type="radio" id="expense_cheque" name="r1">
                  <label for="expense_cheque">
                    CHEQUE
                  </label>
                </div>
              </div>  
                  
              {{-- <div class="form-group">
                <label>Expense to</label>
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
              </div> --}}
            </div>
            
            <div class="col-md-3">
              <label>Bank</label>
                {{-- <input id="description" type="text" class="form-control form-control-lg text-lg validate is-invalid" placeholder="Description"> --}}
                <select id="expense_bank" class="select2bs4" style="width: 100%;">
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
                  <input id="expense_checkno" class="form-control" type="" name="">  
                </div>
                <div class="col-md-6">
                  <label>Check Date</label>
                  <input id="expense_checkdate" class="form-control" type="date" name="" >  
                </div>
              </div>  
            </div>
          </div>
        </div>
        <div class="modal-body" style="height: 230px; overflow-y: auto;">
          <div class="row">
            <div class="col-md-4 table-responsive p-0" style="height: 12em">
              <label>ITEMS</label>
              <table class="table table-striped table-head-fixed table-sm text-sm">
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
            <div class="col-md-1">&nbsp;</div>
            <div class="col-md-7 table-responsive p-0" style="height: 12em">
              <div class="row form-group">
                <div class="col-md-6">
                  <label>JOURNAL</label>
                </div>
                <div class="col-md-6 text-right">
                  <button id="je_add_account" class="button btn-sm btn-success btn block">
                    <i class="far fa-plus-square"></i> JOURNAL ENTRY
                  </button>
                  {{-- <button id="gen_credit" class="btn btn-primary btn-sm text-sm">GENERATE CREDIT JE</button> --}}
                </div>
                  
              </div>
              <table class="table table-head-fixed table-sm text-sm">
                <thead>
                  <tr>
                    <th>Accounts</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="je_accounts">
                  {{-- <tr>
                    <td class="je_accounts">
                      <select class="form-control select2bs4 je_input je_coa">
                        @foreach(db::table('acc_coa')->where('deleted', 0)->orderBy('code')->get() as $coa)
                          <option value="{{$coa->id}}">{{$coa->code}} - {{$coa->account}}</option>
                        @endforeach
                      </select>
                    </td>
                    <td class="je_debit">
                      <input type="number" class="form-control">
                    </td>
                    <td class="je_credit">
                      <input type="number" class="form-control">
                    </td>
                    <td>
                      <button class="btn btn-primary btn-sm mt-1">
                        <i class="fas fa-download"></i>
                      </button> 
                    </td>
                  </tr> --}}
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="4">
                      <u><a href="#" id="je_add"></a></u>
                    </td>
                  </tr>
                  <tr id="">
                    <td class="text-right text-bold">TOTAL: </td>
                    <td id="totaldebit" class="text-right text-bold">0.00</td>
                    <td id="totalcredit" class="text-right text-bold">0.00</td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
        <div id="div_je_entry" style="position: absolute; width: 40em; left: 30em; top: 15em; display: none">
          <div class="card">
            <div class="card-body bg-light">
              <div class="row">
                <div class="col-md-6">
                  <select id="float_je_account" class="select2bs4 form-control">
                    @foreach(db::table('acc_coa')->where('deleted', 0)->get() as $account)
                      <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->account }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-2">
                  <input id="je_debitamount" type="number" class="form-control calc text-right p-1 text-sm" name="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="" data-toggle="tooltip" title="Press enter to save" placeholder="0.00">
                </div>
                <div class="col-md-2">
                  <input id="je_creditamount" type="number" class="form-control calc text-right p-1 text-sm" name="" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="" data-toggle="tooltip" title="Press enter to save" placeholder="0.00">
                </div>
                <div class="col-md-2" style="padding-top: 3px">
                  <button id="float_je_save" class="btn btn-primary btn-sm"><i class="fas fa-save"></i></button>
                  <button id="float_je_close" class="btn btn-danger btn-sm"><i class="fas fa-ban"></i></button>
                </div>
                
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
          <div>
            <button id="btndisapprove" type="button" class="btn btn-danger" data-id="">
              Cancel
            </button>
            <button id="btnapprove" type="button" class="btn btn-success" data-id="">
              Post
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
                <input id="itemamount" type="text" class="form-control calc">
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
                <input id="totalamount" type="text" class="form-control" placeholder="0.00" disabled="">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Remarks/Explanation</label>
                <textarea id="item_remarks" type="text" class="form-control" placeholder=""></textarea>
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
          <h4 class="modal-title">Supplier</h4>
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

  <div class="modal fade show" id="modal-setup" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Expenses Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body text-sm">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                    <div id="box_supplier" class="small-box bg-primary p-1 text-lg">
                      <div class="inner">
                        <br>
                        <span class="text-bold text-light">Supplier <br> &nbsp; </span>
                      </div>
                      <div class="icon">
                          <i class="fas fa-layer-group"></i>
                      </div>
                    </div>
                </div>
                <div class="col-md-4">
                  <div id="box_items" class="small-box bg-danger p-1 text-lg">
                    <div class="inner">
                      <br>
                      <span class="text-bold text-light">Items <br> &nbsp; </span>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div id='div_supplier' class="row" style="display: block">
            <div class="row">
              <div class="col-md-12">
              <div class="row">
                  <div class="col-md-7">
                    <h3>Supplier Masterlist</h3>
                  </div>
                  <div class="col-md-5">
                    <div class="input-group mb-2">
                      <input id="sup_filter" type="text" class="form-control" placeholder="Search Supplier" onkeyup="this.value = this.value.toUpperCase();">
                      <div class="input-group-append">
                          {{-- <span class="input-group-text"><i class="fas fa-search"></i></span> --}}
                          <button id="sup_create" class="btn btn-primary">Create</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 table-responsive table_setup">
                    <table class="table table-sm text-sm table-striped">
                      <thead>
                        <tr>
                          <th>Supplier Name</th>
                          <th>Address</th>
                        </tr>
                      </thead>
                      <tbody id="supplier_list" style="cursor: pointer;"></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="div_items" class="row" style="display: block">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-7">
                    <h3>Expenses Items</h3>
                  </div>
                  <div class="col-md-5">
                    <div class="input-group mb-2">
                      <input id="item_filter" type="text" class="form-control" placeholder="Search Items" onkeyup="this.value = this.value.toUpperCase();">
                      <div class="input-group-append">
                          {{-- <span class="input-group-text"><i class="fas fa-search"></i></span> --}}
                          <button id="item_create" class="btn btn-primary">Create</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 table-responsive table_setup">
                    <table class="table table-sm text-sm table-striped">
                      <thead>
                        <tr>
                          <th>Item Code</th>
                          <th>Item Name</th>
                          {{-- <th>Address</th> --}}
                        </tr>
                      </thead>
                      <tbody id="item_list" style="cursor: pointer;"></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          {{-- <button id="company_save" type="button" class="btn btn-primary" data-dismiss="modal">Save</button> --}}
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-supplierdetail" aria-modal="true" style="padding-right: 17px; margin-top:15em; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Supplier</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body text-sm">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="class-desc" class="col-sm-3 col-form-label">Supplier</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control form-control-sm validation" id="sup_name" placeholder="Pay To" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-desc" class="col-sm-3 col-form-label">Address</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control form-control-sm validation" id="sup_address" placeholder="Address" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-desc" class="col-sm-3 col-form-label">Department</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control form-control-sm validation" id="sup_department" placeholder="Department" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </form>
        </div>
        <div class="p-3">
          <hr>
          <div class="row">
            <div class="col-md-4">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <div class="col-md-8 text-right">
              <button id="sup_delete" type="button" class="btn btn-danger" style="display: none">Delete</button>
              <button id="sup_save" type="button" class="btn btn-primary" data-id="0">Save</button>
            </div>
          </div>
          
          
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-itemdetail" aria-modal="true" style="padding-right: 17px; margin-top:6em; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h4 class="modal-title">Items</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body text-sm">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="class-desc" class="col-sm-3 col-form-label">Code</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control form-control-sm" id="item_code" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-desc" class="col-sm-3 col-form-label">Description</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control form-control-sm item_val" id="item_description" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-desc" class="col-sm-3 col-form-label">Cost</label>
                <div class="col-sm-9">
                  <input type="number" class="form-control form-control-sm text-right" id="item_cost" placeholder="0.00" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-desc" class="col-sm-3 col-form-label">Classification</label>
                <div class="col-sm-9">
                  <select id="item_class" class="select2bs4" style="width: 100%">
                    <option value="0">SELECT CLASSIFICATION</option>
                    @foreach(db::table('itemclassification')->where('deleted', 0)->orderBy('description')->get() as $class)
                      <option value="{{$class->id}}">{{$class->description}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="class-desc" class="col-sm-3 col-form-label">Account</label>
                <div class="col-sm-9">
                  <select id="item_account" class="select2bs4" style="width: 100%">
                    <option value="0">SELECT ACCOUNT</option>
                    @foreach(db::table('acc_coa')->where('deleted', 0)->orderBy('code')->get() as $coa)
                      <option value="{{$coa->id}}">{{$coa->code}} - {{$coa->account}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              
              
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </form>
        </div>
        <div class="p-3">
          <hr>
          <div class="row">
            <div class="col-md-4">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <div class="col-md-8 text-right">
              <button id="item_delete" type="button" class="btn btn-danger" style="display: none">Delete</button>
              <button id="item_save" type="button" class="btn btn-primary" data-id="0">Save</button>
            </div>
          </div>
          
          
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
          $('.table_setup').css('height', screen_height - 450);

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

      $('[data-toggle="tooltip"]').tooltip({trigger: 'focus'})

      $('#je_debitamount').focus(function() {
          $(this).tooltip('show');
          setTimeout(function() {
              $('#je_debitamount').tooltip('hide');
          }, 3000); // Hide after 3 seconds
      });

      $('#je_creditamount').focus(function() {
          $(this).tooltip('show');
          setTimeout(function() {
              $('#je_creditamount').tooltip('hide');
          }, 3000); // Hide after 3 seconds
      });

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
            var status = data.status

            
            loadje(headerid)
            


            
          }
        });          
      }

      function genje(headerid, status)
      {
        $.ajax({
          type: "GET",
          url: "{{route('expense_genje')}}",
          data: {
            headerid:headerid
          },
          // dataType: "dataType",
          success: function (data) {
            $('#je_add').text('Journal Entry')

            if(status == '')
            {
              loadje(headerid)
            }
          }
        });
      }

      function loadje(headerid)
      {
        $.ajax({
          type: "GET",
          url: "{{route('expense_loadje')}}",
          data: {
            headerid:headerid,
            status:status
          },
          // dataType: "dataType",
          success: function (data) {
            $('#je_accounts').empty()

            console.log(data)
            if(status != 'APPROVED')
            {
              $.each(data.coalist, function (index, val) { 
                // if(val.debit == 0)
                if(1 > 0) //cancell if statement
                {
                  $('#je_accounts').append(`
                    <tr data-id="`+val.id+`">
                      <td class="je_acc" coa-id="`+val.glid+`" style="width: 21em; vertical-align: middle;">
                        `+val.code+` - `+val.account+`
                      </td>
                      <td class="je_debit text-right" debit-val="`+val.debit+`" style="vertical-align: middle;">
                        `+parseFloat(val.debit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`
                      </td>
                      <td class="je_credit text-right" credit-val="`+val.credit+`" style="vertical-align: middle;">
                        `+parseFloat(val.credit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`
                        
                      </td>
                      <td>
                        <button class="btn btn-danger btn-sm text-sm mt-1 je_button" data-button="delete" style="padding:3px">
                          <i class="far fa-trash-alt"></i>
                        </button> 
                      </td>
                    </tr>
                  `)
                }
                else{
                  $('#je_accounts').append(`
                    <tr data-id="`+val.id+`">
                      <td class="je_acc" coa-id="`+val.glid+`" style="width: 21em">
                        `+val.code+` - `+val.account+`
                      </td>
                      <td class="je_debit text-right" debit-val="`+val.debit+`">
                        `+parseFloat(val.debit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`
                      </td>
                      <td class="je_credit text-right" credit-val="`+val.credit+`">
                        `+parseFloat(val.credit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`
                        
                      </td>
                      <td>
                      </td>
                    </tr>
                  `)
                }
              });
            }
            else{
              $.each(data, function (index, val) { 
                $('#je_accounts').append(`
                    <tr data-id="`+val.id+`">
                      <td class="je_acc" coa-id="`+val.glid+`" style="width: 21em">
                        `+val.code+` - `+val.account+`
                      </td>
                      <td class="je_debit text-right" debit-val="`+val.debit+`">
                        `+parseFloat(val.debit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`
                      </td>
                      <td class="je_credit text-right" credit-val="`+val.credit+`">
                        `+parseFloat(val.credit, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`
                        
                      </td>
                      <td>
                        
                      </td>
                    </tr>
                  `)
              });

              $('#je_add').text('')
            }

            var dtotal = 0
            var ctotal = 0

            $('#je_accounts tr').each(function(){
              dtotal += parseFloat($(this).find('.je_debit').attr('debit-val'))
              ctotal += parseFloat($(this).find('.je_credit').attr('credit-val'))
            })

            $('#totaldebit').text(parseFloat(dtotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
            $('#totalcredit').text(parseFloat(ctotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
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
        var bankid = $('#expense_bank').val()
        var checkno = $('#expense_checkno').val()
        var checkdate = $('#expense_checkdate').val()
        var voucherno = $('#voucherno').val()


        if($('#expense_cash').prop('checked') == true)
        {
          var paytype = 'CASH'
        }
        else{
          var paytype = 'CHECK'
        }

        // console.log(dataid);
        if($('#company').prop('checked') == true)
        {
          var paidby = 'COMPANY';
        }
        else
        {
          var paidby = 'EMPLOYEE';
        }

        if($('#totaldebit').text() == $('#totalcredit').text())
        {
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
              dataid:dataid,
              paytype:paytype,
              bankid:bankid,
              checkno:checkno,
              checkdate:checkdate,
              voucherno:voucherno
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

                $('#btnapprove').show()
                $('#btndisapprove').show()

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
        else {
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
            title: 'Please check the journal entries.'
          })
        }
      }

      function saveJE(headerid)
      {
        var jelist = {}
        $('#je_accounts tr').each(function(){
          var glid = $(this).find('.je_acc').attr('coa-id')
          var debit = $(this).find('.je_debit').attr('debit-val')
          var credit = $(this).find('.je_credit').attr('credit-val')
        })

        $.ajax({
          type: "GET",
          url: "{{route('expense_saveje')}}",
          data: {
            headerid:headerid
          },
          // dataType: "dataType",
          success: function (data) {
            
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
        var remarks = $('#item_remarks').val();

        $.ajax({
          url:"{{route('saveexpensedetail')}}",
          method:'GET',
          data:{
            headerid:headerid,
            detailid:detailid,
            itemid:itemid,
            itemprice:itemprice,
            qty:qty,
            total:total,
            remarks:remarks
          },
          // dataType:'',
          success:function(data)
          {
            // genje(headerid, status)
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
        var total =  qty * itemamount;

        console.log("total: " + total);

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

        $('#je_accounts').empty()
        $('#je_add').text('')

        $('#description').prop('disabled', false)
        $('#transDate').prop('disabled', false)
        $('#requestby').prop('disabled', false)
        $('#remarks').prop('disabled', false)
        $('#expense_cash').prop('disabled', false)
        $('#expense_cheque').prop('disabled', false)
        $('#expense_bank').prop('disabled', false)
        $('#expense_checkno').prop('disabled', false)
        $('#expense_checkdate').prop('disabled', false)
        $('#voucherno').prop('disabled', false)
        $('#voucherno').css('background-color', 'white')
        $('#voucherno').val('');


        $('#btnsaveheader').prop('disabled', false)

        $('#expense_bank').val(0).trigger('change')
        $('#expense_checkno').val('')
        $('#expense_checkdate').val('')

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
            $('#voucherno').val(data.voucherno)
            $('#expense_bank').val(data.bankid).trigger('change')
            $('#expense_checkno').val(data.checkno)
            $('#expense_checkdate').val(data.checkdate)

            if(data.paytype == 'CASH')
            {
              $('#expense_cash').prop('checked', true)
            }
            else{
              $('#expense_cheque').prop('checked', true)
            }

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
              $('#description').prop('disabled', true)
              $('#transDate').prop('disabled', true)
              $('#requestby').prop('disabled', true)
              $('#remarks').prop('disabled', true)
              $('#expense_cash').prop('disabled', true)
              $('#expense_cheque').prop('disabled', true)
              $('#expense_bank').prop('disabled', true)
              $('#expense_checkno').prop('disabled', true)
              $('#expense_checkdate').prop('disabled', true)
              $("#voucherno").prop('disabled', true)
              $('#voucherno').css('background-color', 'white')


              $('#lblrefnum').addClass('text-success');
              $('#lblrefnum').removeClass('text-secondary text-danger');
              $('#modal-expense .modal-header').removeClass('bg-info bg-danger');
              $('#modal-expense .modal-header').addClass('bg-success');
              $('#btndisapprove').prop('disabled', true);
              $('#btnapprove').prop('disabled', true);
              $('#btnsaveheader').prop('disabled', true)
              $('#additem').text('');
              $('#expense_print').prop('disabled', false);
              
              setTimeout(function(){
                $('.je_button').prop('disabled', true)
                $('#je_add').prop('disabled', true)
              }, 1000)
                
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

              $('#expense_cash').prop('disabled', true)
              $('#expense_cheque').prop('disabled', true)
              $('#expense_bank').prop('disabled', true)
              $("#expense_checkno").prop('disabled', true)
              $('#expense_checkdate').prop('disabled', true)
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

              $('#expense_cash').prop('disabled', false)
              $('#expense_cheque').prop('disabled', false)
              $('#expense_bank').prop('disabled', false)
              $("#expense_checkno").prop('disabled', false)
              $('#expense_checkdate').prop('disabled', false)
              
              $('#description').prop('disabled', false)
              $('#transDate').prop('disabled', false)
              $('#requestby').prop('disabled', false)
              $('#remarks').prop('disabled', false)
              $('#expense_cash').prop('disabled', false)
              $('#expense_cheque').prop('disabled', false)
              $('#expense_bank').prop('disabled', false)
              $('#expense_checkno').prop('disabled', false)
              $('#expense_checkdate').prop('disabled', false)
              $("#voucherno").prop('disabled', false)

              $('#btnapprove').show()
              $('#btndisapprove').show()

            }

            $('#modal-expense').modal('show');
          }
        }); 

      });

      $(document).on('select2:closing', '#requestby', function(){
        console.log('aaa');
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
          title: 'Post Expenses?',
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
              $('#item_remarks').val(data.remarks);
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

      
      
      function getItems()
      {
        var filter = $('#item_filter').val()

        $.ajax({
          type: "GET",
          url: "{{route('expenses_items')}}",
          data: {
            filter:filter
          },
          // dataType: "dataType",
          success: function (data) {
            $('#item_list').empty()

            $.each(data, function (index, val) { 
              var itemcode = ''
              var itemdesc = ''

              if(val.itemcode != null)
              {
                itemcode = val.itemcode
              }
              else{
                itemcode = ''
              }

              if(val.description != null)
              {
                itemdesc = val.description
              }
              else{
                itemdesc = ''
              }


              $('#item_list').append(`
                <tr data-id="`+val.id+`">
                  <td>`+itemcode+`</td>
                  <td>`+itemdesc+`</td>
                </tr>
              `)
            });
          }
        });
      }

      $(document).on('click', '#expenses_setup', function(){
        getSupplier()
        $('#box_supplier').trigger('click')
        $('#modal-setup').modal('show');
      })

      $(document).on('click', '#sup_create', function(){
        $('#sup_name').val('');
        $('#sup_address').val('')
        $('#sup_department').val('')
        $('#sup_save').attr('data-id', 0)
        $('#sup_delete').hide()

        $('#modal-supplierdetail').modal('show')
      })

      $(document).on('click', '#sup_save', function(){
        var company = $('#sup_name').val()
        var address = $('#sup_address').val()
        var department = $('#sup_department').val()
        var dataid = $('#sup_save').attr('data-id')
        
        if(dataid == 0)
        {
          $.ajax({
            type: "GET",
            url: "{{route('company_create')}}",
            data: {
              company:company,
              address:address,
              department:department
            },
            // dataType: "dataType",
            success: function (data) {
              if(data == 'done')
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
                  type: 'success',
                  title: 'Supplier successfully saved'
                })

                $('#modal-supplierdetail').modal('hide')
                getSupplier()
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
                  title: 'Supplier already exist'
                })
              }
            }
          })
        }
        else{
          $.ajax({
            type: "GET",
            url: "{{route('expenses_supplier_update')}}",
            data: {
              dataid:dataid,
              company:company,
              address:address,
              department:department
            },
            // dataType: "dataType",
            success: function (data) {
              if(data == 'done')
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
                  type: 'success',
                  title: 'Supplier successfully saved'
                })

                $('#modal-supplierdetail').modal('hide')
                getSupplier()
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
                  title: 'Supplier already exist'
                })
              }
            }
          })
        }
      })

      $(document).on('click', '#supplier_list tr', function(){
        var dataid = $(this).attr('data-id')

        $.ajax({
          type: "GET",
          url: "{{route('expenses_supplier_read')}}",
          data: {
            dataid:dataid
          },
          // dataType: "dataType",
          success: function (data) {
            $('#sup_name').val(data.name)
            $('#sup_address').val(data.address)
            $('#sup_department').val(data.department)
            $('#sup_save').attr('data-id', dataid)
            $('#sup_delete').show()

            $('#modal-supplierdetail').modal('show')
          }
        });
      })

      $(document).on('click', '#sup_delete', function(){
        var dataid = $('#sup_save').attr('data-id')

        Swal.fire({
          title: 'Supplier?',
          text: "Delete Supplier?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value == true) {
            $.ajax({
              type: "GET",
              url: "{{route('expenses_supplier_delete')}}",
              data: {
                dataid:dataid
              },
              // dataType: "dataType",
              success: function (data) {
                Swal.fire(
                  'Deleted!',
                  'Supplier has been deleted.',
                  'success'
                )    

                $('#modal-supplierdetail').modal('hide')
                getSupplier()
              }
            })
          }
        })
      })


      $(document).on('click', '#box_supplier', function(){
        $('#div_supplier').show()
        $('#div_items').hide()

        getSupplier()
      })

      $(document).on('click', '#box_items', function(){
        $('#div_supplier').hide()
        $('#div_items').show()

        getItems()
      })

      $(document).on('click', '#item_create', function(){
        $('#item_code').val('')
        $('#item_description').val('')
        $('#item_cost').val('')
        $('#item_class').val(0).trigger('change')
        $('#item_account').val(0).trigger('change')
        $('#item_save').attr('data-id', 0)
        $('#modal-itemdetail').modal('show')
      })

      

      $(document).on('click', '#item_delete', function(){
        var dataid = $('#item_save').attr('data-id')

        Swal.fire({
          title: 'Delete',
          text: "Delete this item?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Delete'
        }).then((result) => {
          if (result.value == true) {
            $.ajax({
              type: "GET",
              url: "{{route('expenses_items_delete')}}",
              data: {
                dataid:dataid
              },
              // dataType: "dataType",
              success: function (data) {
                $('#modal-itemdetail').modal('hide')
                getItems()

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
                  title: 'Item successfully deleted'
                })
              }
            });    
          }
        })

        
      })

      $(document).on('click', '#employee', function(){

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

      function appendje()
      {
        var rowcount = $('#je_accounts').length;
        var totaldebit = $('#totaldebit').text().replace(",", "")

        $('#je_accounts').append(`
          <tr>
            <td class="je_acc" coa-id="0" style="width: 24em">
              <select id="jeid_`+rowcount+`" class="form-control select2bs4 je_input je_coa ">
                `+acc_coa+`
              </select>
            </td>
            <td class="je_debit" debit-val="0" style="width: 8em">
              
            </td>
            <td class="je_credit" credit-val="0" style="width: 8em">
              <input type="number" class="form-control text-right je_input credit" value="`+totaldebit+`">
            </td>
            <td>
              <button class="btn btn-primary btn-sm mt-1 je_button">
                <i class="fas fa-download"></i>
              </button> 
              <button class="btn btn-danger btn-sm mt-1 je_delete">
                <i class="far fa-trash-alt"></i>
              </button> 
            </td>
          </tr>
        `)

        $('.select2bs4').select2({
          theme: 'bootstrap4'
        });
      }

      $(document).on('click', '#je_add', function(){
        console.log(acc_coa)
        appendje()
      })

      $(document).on('click', '.je_button', function(){
        var jeid = $(this).closest('tr').attr('data-id')
        var state = $(this).attr('data-button')

        if(state == 'delete')
        {
          Swal.fire({
            title: 'Delete?',
            text: "Delete this Journal Entry?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.value==true) {
              $.ajax({
                type: "GET",
                url: "{{route('expense_deleteje')}}",
                data: {
                  jeid:jeid
                },
                // dataType: "dataType",
                success: function (data) {
                  loadje(data)
                  
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
                    title: 'Journal successfully deleted'
                  })
                  
                }
              });




              
            }
          })






          $.ajax({
            type: "GET",
            url: "{{route('expense_getje')}}",
            data: {
              jeid:jeid
            },
            success: function (data) {
              
              

              // var rowcount = $('#je_accounts').length;
              // var td = $('#je_accounts tr[data-id="'+jeid+'"]').find('.je_acc')
              
              // var acc = $('<select class="form-control select2bs4 je_input je_coa" coa-id="'+data.glid+'">')
              // acc.append(data[0].accounts)
              // td.html(acc)

              // console.log(data[0].accounts)

              // $('#je_accounts tr [data-id="'+data.id+'"]').closest('td').find('.je_acc').append(`
              //   <select id="jeid_`+rowcount+`" class="form-control select2bs4 je_input je_coa ">
              //     `+data.accounts+`
              //   </select>
              // `)

              // text = $('#je_accounts tr[data-id="'+data.id+'"]').closest('td').find('.je_acc').text()
              // console.log(td)
        
              // $('#je_accounts').append(`
              //   <tr>
              //     <td class="je_acc" coa-id="0" style="width: 21em">
              //       <select id="jeid_`+rowcount+`" class="form-control select2bs4 je_input je_coa ">
              //         `+acc_coa+`
              //       </select>
              //     </td>
              //     <td class="je_debit" debit-val="0">
              //       <input type="number" class="form-control text-right je_input debit">
              //     </td>
              //     <td class="je_credit" credit-val="0">
              //       <input type="number" class="form-control text-right je_input credit">
              //     </td>
              //     <td>
              //       <button class="btn btn-primary btn-sm mt-1 je_button">
              //         <i class="fas fa-download"></i>
              //       </button> 
              //     </td>
              //   </tr>
              // `)

              // $('.select2bs4').select2({
              //   theme: 'bootstrap4'
              // });
            }
          });
        }
        else{
          var headerid = $('#btnsaveheader').attr('data-id');
          var glid = $(this).closest("tr").find('.je_acc').find('.je_coa').val()
          var credit = $(this).closest("tr").find('.je_credit').find('.credit').val()

          $.ajax({
            type: "GET",
            url: "{{route('expense_saveje')}}",
            data: {
              headerid:headerid,
              glid:glid,
              debit:0,
              credit:credit
            },
            // dataType: "dataType",
            success: function (data) {
              genje(headerid, '')
            }
          });
          
        }

        $(document).on('click', '.je_delete', function(){
          $(this).closest('tr').remove()
        })

        

      })

      $(document).on('change', '#datefrom', function(){
        searchexpense()
      })


      $(document).on('click', '#expense_print', function(){
        var id = $('#btnsaveheader').attr('data-id')
        window.open('/finance/expenses_print?id='+id+'&action=print', '_blank');
        
        $('#modal-expense').modal('hide');
        
        setTimeout(() => {
          searchexpense()
        }, 3000);
      })     

      $(document).on('click', '#gen_credit', function(){
        var headerid = $('#btnsaveheader').attr('data-id')
        var amount = $('#gt').text()

        $.ajax({
          type: "GET",
          url: "{{route('expenses_gencredit')}}",
          data: {
            headerid:headerid,
            amount:amount
          },
          // dataType: "dataType",
          success: function (data) {
            if(data == 'done')
            {
              loadje(headerid)
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
                title: 'JE Generated'
              })
            }
            else if(data == 'exist')
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
                title: 'Credit JE already generated'
              })
            }
            else if(data == 'error')
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
                type: 'error',
                title: 'Something went wrong'
              })
            }
          }
        });
      })

      $(document).on('click', '#je_add_account', function(){
        if($('#div_je_entry').css('display') == 'none')
        {
          $('#div_je_entry').show();
        }
        else{
          $('#je_debitamount').val('');
          $('#je_creditamount').val('');
          $('#float_je_account').val('');
          $('#div_je_entry').hide();
        }
          
        
        
        
        setTimeout(() => {
          $('#float_je_account').focus()
        }, 500);
      })

      $(document).on('click', '#float_je_close', function(){
        $('#je_debitamount').val('');
        $('#je_creditamount').val('');
        $('#float_je_account').val('');
        $('#div_je_entry').hide();
      })

      $(document).on('change', '#float_je_account', function(){
        $('#je_debitamount').focus();
      })

      $(document).on('click', '#float_je_save', function(){
        var headerid = $('#btnsaveheader').attr('data-id');
        var glid = $('#float_je_account').val();
        var creditamount = ($('#je_creditamount').val() != '') ? $('#je_creditamount').val() : 0;
        var debitamount = ($('#je_debitamount').val() != '') ? $('#je_debitamount').val() : 0;


        $.ajax({
          type: "GET",
          url: "{{route('expense_saveje')}}",
          data: {
            headerid:headerid,
            glid:glid,
            debit:debitamount,
            credit:creditamount
          },
          // dataType: "dataType",
          success: function (data) {
            // genje(headerid, '')
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
              title: 'Account added'
            })

            $('#je_creditamount').val('')
            $("#je_debitamount").val('')
            
            loadje(headerid)
          }
        });
      })

      $(document).on('focusout', '#je_debitamount', function(){
        var amount = $(this).val();

        if(amount != '')
        {
          $(this).val(parseFloat(amount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
        }
      })

      $(document).on('focusout', '#je_creditamount', function(){
        var amount = $(this).val();

        if(amount != '')
        {
          $(this).val(parseFloat(amount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
        }
      })

      $(document).on('keyup', '#je_debitamount', function(e){
        if(e.keyCode === 13)
        {
          $('#float_je_save').trigger('click')
        }
      })

      $(document).on('keyup', '#je_creditamount', function(e){
        if(e.keyCode === 13)
        {
          $('#float_je_save').trigger('click')
        }
      })

      // function posteddisplay(status) 
      // {
      //   if(status == 'POSTED')
      //   {
      //     $('#description').prop('disabled', true)
      //     $('#transDate').prop('disabled', true)
      //     $('#remarks').prop('disabled', true)
      //     $('#requestby').prop('disabled', true)
      //     $('#expense_cash').prop('disabled', true)
      //     $('#expense_cheque').prop('disabled', true)
      //     $('#je_button').prop('disabled', true)
      //     $('#expense-detail').prop('disabled', true)
      //   }
      //   else{

      //   }
      // }


    });
  </script>
@endsection
