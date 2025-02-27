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
            <li class="breadcrumb-item active">utilities</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content pt-0">
  	<div class="main-card card">
  		<div class="card-header bg-secondary">
        <div class="row">
          <div class="text-lg col-md-12">
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
              <b>Utilities</b>
            </h4>
          </div>
        </div>  
  		</div>
      <div class="card-body">
        <div class="row">
          <div id="resetpaymentaccount" class="col-md-3" style="cursor: pointer;">
            <div class="small-box bg-orange">
              <div class="inner">
                <br>
                <span class="text-bold text-light">Reset <br> Student Account</span>
              </div>
              <div class="icon">
                <i class="fas fa-undo-alt"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          

          <div id="tsi" class="col-md-3" style="cursor: pointer;">
            <div class="small-box bg-warning">
              <div class="inner">
                <br>
                <span class="text-bold">TransItems/ <br> StudledgerItemized</span>
              </div>
              <div class="icon">
                <i class="fas fa-money-check"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div id="adjO" class="col-md-3" style="cursor: pointer;">
            <div class="small-box bg-primary">
              <div class="inner">
                <br>
                <span class="text-bold">Adjustment<br>Overide</span>
              </div>
              <div class="icon">
                <i class="fas fa-sliders-h"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div id="balfwdfix" class="col-md-3" style="cursor: pointer;">
            <div class="small-box bg-olive">
              <div class="inner">
                <br>
                <span class="text-bold">Balance<br>Forwarding Fixes</span>
              </div>
              <div class="icon">
                <i class="fas fa-tools"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div id="trxitemized" class="col-md-3" style="cursor: pointer;">
            <div class="small-box bg-dark">
              <div class="inner">
                <br>
                <span class="text-bold">Transaction<br>Itemized</span>
              </div>
              <div class="icon">
                <i class="fas fa-clock"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div id="dpfixer" class="col-md-3" style="cursor: pointer;">
            <div class="small-box bg-pink">
              <div class="inner">
                <br>
                <span class="text-bold">Payment<br>Fixer</span>
              </div>
              <div class="icon">
                <i class="fas fa-cogs"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div id="disbal" class="col-md-3" style="cursor: pointer;">
            <div class="small-box bg-danger">
              <div class="inner">
                <br>
                <span class="text-bold">Disbalance<br>Account</span>
              </div>
              <div class="icon">
                <i class="fas fa-balance-scale-right"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div id="tlf" class="col-md-3" style="cursor: pointer;">
            <div class="small-box bg-secondary">
              <div class="inner">
                <br>
                <span class="text-bold">Transactions/Ledger<br>Fixer</span>
              </div>
              <div class="icon">
                <i class="fas fa-retweet"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div id="discountfixer" class="col-md-3" style="cursor: pointer;">
            <div class="small-box bg-success">
              <div class="inner">
                <br>
                <span class="text-bold">Discount<br>Fixer</span>
              </div>
              <div class="icon">
                <i class="fas fa-tags"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div id="dcc" class="col-md-3" style="cursor: pointer;">
            <div class="small-box bg-info">
              <div class="inner">
                <br>
                <span class="text-bold">Daily Cash<br>Collection Setup</span>
              </div>
              <div class="icon">
                <i class="fas fa-ruler-combined"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div id="bookentriessetup" class="col-md-3" style="cursor: pointer;">
            <div class="small-box bg-indigo">
              <div class="inner">
                <br>
                <span class="text-bold">Book<br>Entry Setup</span>
              </div>
              <div class="icon">
                <i class="fas fa-book-open"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div id="cashiersetup" class="col-md-3" style="cursor: pointer;">
            <div class="small-box bg-teal">
              <div class="inner">
                <br>
                <span class="text-bold">Cashier<br>Setup</span>
              </div>
              <div class="icon">
                <i class="fas fa-cash-register"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      
  		
  	</div>
  </section>
@endsection

@section('modal')
  

  <div class="modal fade" id="modal-rsa" data-backdrop="static" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content" style="margin-top: -28px">
        <div id="modal-adj-header" class="modal-header bg-primary">
          RESET PAYMENT ACCOUNT
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div id="body_box" class="modal-body overflow-auto" style="height: 511px">
          <div  class="row">
            <div class="col-md-4">
              <div class="form-group">
                <select id="selstud" class="select2bs4 form-control">
                  @php
                    $studinfo = DB::table('studinfo')
                      ->select('studinfo.id', 'lastname', 'firstname', 'middlename', 'suffix', 'levelid', 'levelname')
                      ->join('gradelevel', 'studinfo.levelid', 'gradelevel.id')
                      ->where('studinfo.deleted', 0)
                      ->get();
                  @endphp
                  <option value="0">Select Student</option>
                  @foreach($studinfo as $stud)
                    {{$name = $stud->lastname . ', ' . $stud->firstname. ' ' . $stud->middlename. ' ' . $stud->suffix . ' | ' . $stud->levelname}}
                    <option value="{{$stud->id}}">{{$name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <select id="cbosy" class="bootstrap4 form-control filter">
                @foreach(App\FinanceModel::getSY() as $sy)
                  @if($sy->isactive == 1)
                    <option value="{{$sy->id}}" selected="">{{$sy->sydesc}}</option>
                  @else
                    <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <select id="cbosem" class="bootstrap4 form-control filter">
                @foreach(App\FinanceModel::getSem() as $sem)
                  @if($sem->isactive == 1)
                    <option value="{{$sem->id}}" selected="">{{$sem->semester}}</option>
                  @else
                    <option value="{{$sem->id}}">{{$sem->semester}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <select id="fees" class="select2bs4 form-control">
                
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-2">
              <button id="btnreset" class="btn btn-info btn-block">RESET LEDGER</button>
            </div>
            <div class="col-md-2">
              <button id="btnreset_v3" class="btn btn-warning btn-block">RESET LEDGER V3</button>
            </div>
            <div class="col-md-2">
              <button id="btnclearledger" class="btn btn-primary btn-block">CLEAR LEDGER</button>
            </div>
            <div id="divresetpaysched" class="col-md-2" style="display: none;">
              <button id="tvl_btnresetpaysched" class="btn btn-warning btn-block">RESET PAYSCHED</button>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-6">
              <div class="card">
                <div class="card-header bg-secondary">
                  Student Ledger - <span id="spanstudid"></span> | <span id="spangrantee"></span>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-stripped table-sm text-xs">
                      <thead>
                        <tr>
                          <th style="width: 80px">DATE</th>
                          <th>PARTICULARS</th>
                          <th>CHARGES</th>
                          <th>PAYMENT</th>
                          <th>BALANCE</th>
                        </tr>
                      </thead>
                      <tbody id="ledger">
                        
                      </tbody>
                      <tfoot id="ledgerfoot">
                        <tr>
                          <td class="text-right text-bold" colspan="2">TOTAL: </td>
                          <td id="totalamount" class="text-right text-bold"></td>
                          <td id="totalpayment" class="text-right text-bold"></td>
                          <td id="rbal" class="text-right text-bold"></td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card">
                <div class="card-header bg-primary">
                  
                </div>
                <div class="card-body table-responsive pt-0">
                  <table class="table table-striped table-hover table-sm text-xs" data-order="id">
                    <thead>
                      <tr>
                        <th id="paysched_sort_id" class="text-center" style="cursor: pointer;">No</th>
                        <th class="" style="width: 21em">Particulars</th>
                        <th id="paysched_sort_due" class="" style="width: 80px; cursor: pointer;">Due</th>
                        <th class=" text-center">Amount</th>
                        <th class=" text-center">Paid</th>
                        <th class=" text-center">Balance</th>
                      </tr>
                    </thead>
                    <tbody id="paysched-list" class="cursor-pointer">
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="3" class="text-right">TOTAL: </th>
                        <th class="text-right paysched_total-amount">0.00</th>
                        <th class="text-right paysched_total-paid">0.00</th>
                        <th class="text-right paysched_total-balance">0.00</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="body_list" class="modal-body overflow-auto" style="height: 511px" hidden="">
          <div class="row">
            <div class="col-md-4">
              <select id="list_levelid" class="select2bs4 form-control list_filter">
                <option value="0">Select Grade Level</option>
                @php
                  $gradelevel = DB::table('gradelevel')
                    ->where('deleted', 0)
                    ->orderBy('sortid', 'ASC')
                    ->get();
                @endphp
                @foreach($gradelevel as $level)
                  <option value="{{$level->id}}">{{$level->levelname}}</option>
                @endforeach
              </select>
            </div>
            <div id="div_grantee" class="col-md-3" hidden="">
              <select id="list_grantee" class="select2bs4 form-control list_filter">
                <option value="0">Select Grantee</option>

                @foreach(DB::table('grantee')->get() as $grantee)
                  <option value="{{$grantee->id}}">{{$grantee->description}}</option>
                @endforeach

              </select>
            </div>
            <div id="div_courses" class="col-md-3" hidden="">
              <select id="list_courses" class="select2bs4 form-control list_filter">
                <option value="0">Select Courses</option>
                @foreach(db::table('college_courses')->where('deleted', 0)->orderBy('courseabrv', 'ASC')->get() as $course)
                  <option value="{{$course->id}}">{{$course->courseabrv}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <select id="list_paymentplan" class="select2bs4 form-control">
              </select>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-3">
              STUD COUNT: <span id="studcount" class="text-bold"></span>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-md-12 table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>STUDENT NAME</th>
                    <th>AMOUNT</th>
                    <th>BALANCE</th>
                    <th><button id="resetall" class="btn btn-outline-primary btn-block">RESET ALL</button></th>
                  </tr>
                </thead>
                <tbody id="list_stud">
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-body bg-light">
          <div class="row">
            <div class="col-md-6">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
            </div>
            <div class="col-md-6 text-right">
              <button id="btnbox" class="btn btn-warning"><i class="fas fa-th-list"></i></button>
              <button id="btnlist" class="btn btn-warning"><i class="fas fa-list"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade" id="modal-tsi" data-backdrop="static" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content" style="margin-top: -28px">
        <div id="modal-adj-header" class="modal-header bg-primary">
          TransItems and Studledger Itemized
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div id="body_box" class="modal-body overflow-auto" style="height: 511px">
          <div  class="row">
            <div class="col-md-6">
              <select class="select2bs4 form-control" id="itemizedselectstud">
                <option value="0">SELECT STUDENT</option>
                @php
                  $studinfo = DB::table('studinfo')
                    ->select('studinfo.id', 'lastname', 'firstname', 'middlename', 'suffix', 'levelname')
                    ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                    ->where('studinfo.deleted', 0)
                    ->orderBy('lastname', 'ASC')
                    ->orderBy('firstname', 'ASC')
                    ->get();
                @endphp
                @foreach($studinfo as $stud)
                  {{$name = $stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename . ' ' . $stud->suffix . ' - ' . $stud->levelname}}
                  <option value="{{$stud->id}}">{{$name}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3"> 
              <label id="sid"></label>
            </div>
            <div class="col-md-3"> 
              <label id="grantee"></label>
            </div>
          </div>
          <hr>

          <div class="row mb-3">
            <div class="col-md-6">
              <button id="tuncatetransitems" class="btn btn-outline-primary">TRUNCATE TRANS ITEMS</button>
            </div>
            <div class="col-md-6">
              <button id="truncatestudledgeritemized" class="btn btn-outline-secondary">TRUNCATE STUDLEDGER ITEMIZED</button>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
                <div class="card">
                  <div class="card-header bg-secondary">
                    <div class="row">
                      <div class="col-md-6">
                        TRANS ITEMS    
                      </div>
                      <div class="col-md-6 text-right">
                        <button id="btntransitemreset" class="btn btn-warning">RESET</button>
                        <button id="btntransitemclear" class="btn btn-primary">CLEAR</button>
                      </div>
                    </div>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <td>STUDID</td>
                          <td>ORNUM</td>
                          <td>ITEMID</td>
                          <td>CLASSID</td>
                          <td class="text-center">AMOUNT</td>
                          
                        </tr>
                      </thead>
                      <tbody id="transitembody">
                        
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                  <div class="card-header bg-primary">
                    <div class="row">
                      <div class="col-md-6">
                        STUDLEDGER  
                      </div>
                      <div class="col-md-6 text-right">
                        <button id="btnledgeritemizedreset" class="btn btn-warning">RESET</button>
                        <button id="btnledgeritemizedclear" class="btn btn-secondary">CLEAR</button>
                      </div>
                    </div>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>STUDID</th>
                          <th>ITEMID</th>
                          <th>CLASSID</th>
                          <th class="text-center">TOTAL AMOUNT</th>
                          <th class="text-center">ITEM AMOUNT</th>
                        </tr>
                      </thead>
                      <tbody id="ledgeritemizedbody">
                        
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade" id="modal-adjoveride" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          ADJUSTMENTS
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <select id="adj_levelid" class="select2bs4 form-control searchcontrol">
                <option value="0">Grade Level</option>
                @foreach(DB::table('gradelevel')->where('deleted', 0)->orderBy('sortid', 'ASC')->get() as $level)
                  <option value="{{$level->id}}">{{$level->levelname}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <input type="text" id="adj_filter" class="form-control" placeholder="Reference No | Description">
            </div>
            <div class="col-md-2">
              <button id="adj_btngosearch" class="btn btn-block btn-primary">Search</button>
            </div>
            <div class="col-md-3">
              Count: <span id="adj_count"></span>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-12">
              <div class="card-body table-responsive p-0" style="height:380px">
                <table class="table table-striped">
                  <thead class="bg-warning">
                    <tr>
                      <th>SID</th>
                      <th>NAME</th>
                      <th>REFNUM</th>
                      <th>DESCRIPTION</th>
                      <th>AMOUNT</th>
                      <th><button id="adj_removealldetail" class="btn btn-block btn-secondary btn-sm">REMOVE ALL</button></th>
                    </tr>  
                  </thead> 
                  <tbody id="adj-list" style="cursor: pointer">
                    
                  </tbody>             
                </table>
              </div>
            </div> 
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade" id="modal-balfrwardfix" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content" style="height: 616px; overflow-y: auto;">
        <div class="modal-header bg-primary">
          Balance Forward
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <button id="fwdfixneg" class="btn btn-block btn-warning">Fix Negative Amount</button>
            </div>
            <div class="col-md-3">
              <button id="fwdvoid" class="btn btn-block btn-primary">Void Balance forwarding</button>
            </div>
            <div class="col-md-3">
              <button id="fwdvpbf" class="btn btn-block btn-danger">View Paid Balance Forwading</button>
            </div>
          </div>
          <hr>
          <div id="balfwdcard" class="row balfwd-page" style="display: none;">
            <div class="col-md-12">
              <div class="cards">
                <div class="card-header bg-warning">
                  <div class="row">
                    <div class="col-md-6">
                      Fix Negative Amount  
                    </div>
                    <div class="col-md-6 text-right">
                      <button id="btnfixfwdneg" class="btn btn-primary ">FIX Negative Amount</button>
                    </div>
                  </div>
                    
                </div>
                <div class="card-body bg-light">
                  <div class="row">
                    <div class="col-md-6">
                      <select id="fwdnegstudlist" class="select2bs4 form-control text-sm">
                        
                      </select>
                    </div>
                  </div>
                  <div class="row text-sm">
                    <div class="col-md-6">
                      <div class="table-responsive">
                        <table class="table table-striped" style="overflow-y: auto;">
                          <thead>
                            <tr>
                              <th>DATE</th>
                              <th>PARTICULARS</th>
                              <th>CHARGES</th>
                              <th>PAYMENT</th>
                              <th>BALANCE</th>
                            </tr>
                          </thead>
                          <tbody id="fwdnegstudledger">
                            
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="table-responsive">
                        <table class="table table-striped" style="overflow-y: auto;">
                          <thead>
                            <tr>
                              <th>CLASSID</th>
                              <th>PARTICULARS</th>
                              <th>AMOUNT</th>
                              <th>PAYMENT</th>
                              <th>BALANCE</th>
                              <th>DUE</th>
                              <th>NO.</th>
                            </tr>
                          </thead>
                          <tbody id="fwdnegpaysched">
                            
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> 
          </div>
          <div id="balfwd_vpbf" class="row balfwd-page" style="display: none;">
            <div class="col-md-12">
              <div class="cards">
                <div class="card-header bg-danger">
                  <div class="row">
                    <div class="col-md-6">
                      View Paid Balance Forwarding
                    </div>
                    <div class="col-md-6 text-right">
                      {{-- <button id="btnfixfwdneg" class="btn btn-primary ">FIX Negative Amount</button> --}}
                    </div>
                  </div>
                    
                </div>
                <div class="card-body bg-light">
                  <div class="row">
                    <div class="col-md-4">
                      <select id="vpbf_glevel" class="select2bs4 form-control text-sm">
                        <option value="0">Select Grade Level</option>
                        @foreach(DB::table('gradelevel')->where('deleted', 0)->orderBy('sortid', 'ASC')->get() as $glevel)
                          <option value="{{$glevel->id}}">{{$glevel->levelname}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-4">                      
                      <button id="vpbf_gen" class="btn btn-primary">Generate</button>
                    </div>
                  </div>
                  <div class="row mt-2 text-sm">
                    <div class="col-md-12">
                      <div class="table-responsive">
                        <table class="table table-striped table-sm" style="overflow-y: auto;">
                          <thead>
                            <tr>
                              <th></th>
                              <th>ID NO</th>
                              <th>NAME</th>
                              <th>OR NO</th>
                              <th>TOTAL PAYMENT</th>
                              <th>LEDGER BALFWD</th>
                              <th>PAYSCHED BAL</th>
                            </tr>
                          </thead>
                          <tbody id="vpbflist">
                            
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> 
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade" id="modal-dpfixer" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          PAYMENT FIXER
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-7">
              <div class="form-group">
                <select id="dpfix_selstud" class="select2bs4 form-control">
                  @php
                    $studinfo = DB::table('studinfo')
                      ->select('studinfo.id', 'sid', 'lastname', 'firstname', 'middlename', 'suffix', 'levelid', 'levelname', 'grantee.description as grantee')
                      ->join('gradelevel', 'studinfo.levelid', 'gradelevel.id')
                      ->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
                      ->where('studinfo.deleted', 0)
                      ->get();
                  @endphp
                  <option value="0">Select Student</option>
                  @foreach($studinfo as $stud)
                    {{$name = $stud->sid . ' - ' . $stud->lastname . ', ' . $stud->firstname. ' ' . $stud->middlename. ' ' . $stud->suffix . ' | ' . $stud->levelname . ' | ' . $stud->grantee}}
                    <option value="{{$stud->id}}">{{$name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group clearfix mt-2">
                <div class="icheck-primary d-inline mr-2">
                  <input type="checkbox" id="passtoPaysched" checked="">
                  <label for="passtoPaysched">
                    Pay Sched
                  </label>
                </div>
                <div class="icheck-primary d-inline">
                  <input type="checkbox" id="passtoLedger" checked="">
                  <label for="passtoLedger">
                    Ledger
                  </label>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <button id="dpfix_fixit" data-id="0" class="btn btn-danger btn-block">FIX IT</button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <button id="btncheckdisbalance" class="btn btn-outline-primary mb-3">Check Disbalance Accounts</button>
            </div>
            <div class="col-md-2">
              <select id="dpfix_syid" class="filters form-control">
                @php 
                  // return App\FinanceModel::getSYID();
                @endphp
                @foreach(App\FinanceModel::getSY() as $sy)
                  @if($sy->id != App\FinanceModel::getSYID())
                    <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                  @else
                    <option value="{{$sy->id}}" selected="">{{$sy->sydesc}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <select id="dpfix_semid" class="filters form-control">
                @php 
                  // return App\FinanceModel::getSYID();
                @endphp
                @foreach(App\FinanceModel::getSem() as $sem)
                  @if($sem->id != App\FinanceModel::getSemID())
                    <option value="{{$sem->id}}">{{$sem->semester}}</option>
                  @else
                    <option value="{{$sem->id}}" selected="">{{$sem->semester}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            {{-- <div class="col-md-6 text-right">
              <span id="disbalance_row"></span>
            </div> --}}
          </div>
          <div id="divdpfix">
            <div class="row">
              <div class="col-md-4"><label>TRANSACTIONS</label></div>
              <div class="col-md-8"><label>STUDENT LEDGER</label></div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="card-body table-responsive p-0" style="height:380px">
                  <table class="table table-striped text-sm">
                    <thead class="bg-warning">
                      <tr>
                        <th style="width: 110px">DATE</th>
                        <th>OR</th>
                        <th class="text-center">AMOUNT</th>
                      </tr>  
                    </thead> 
                    <tbody id="dpfix_trans-list" style="cursor: pointer">
                      
                    </tbody>             
                  </table>
                </div>
              </div> 
              <div class="col-md-8">
                <div class="card-body table-responsive p-0" style="height:380px">
                  <table class="table table-striped text-sm">
                    <thead class="bg-info">
                      <tr>
                        <th style="width: 110px">DATE</th>
                        <th>PARTICULARS</th>
                        <th>DEBIT</th>
                        <th>CREDIT</th>
                        <th>BALANCE</th>
                      </tr>  
                    </thead> 
                    <tbody id="dpfix_studledger" style="cursor: pointer">
                      
                    </tbody>             
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div id="divdisbalance" style="height: 380px" hidden="" class="overflow-auto">
            <div class="row">
              <div class="col-md-12 table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>SID</th>
                      <th>Name</th>
                      <th>Total Transactions</th>
                      <th>Total Ledger Payment</th>
                      <th><button id="generatedisbaltrans" class="btn btn-success btn-block">Generate</button></th>
                    </tr>
                  </thead>
                  <tbody id="disbaltrans_list" style="cursor: pointer">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade mt-3" id="modal-dpfix_push" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content mt-2" style="height: 516px; overflow-y: auto;">
        <div class="modal-header bg-warning">
          Payment
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              OR: <span id="dpfix-push_ornum" class="text-bold text-primary text-lg" data-id="0"></span>
            </div>
            <div class="col-md-6">
              <select id="dpfix-push_classid" class="select2bs4 form-control">
              </select>
            </div>
            <div class="col-md-3">
              <div class="form-group clearfix mt-2">
                <div class="icheck-primary d-inline">
                  <input type="checkbox" id="pushall">
                  <label for="pushall">
                    Push All
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-12 table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Particulars</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody id="dpfix_transitem" style="cursor: pointer;">
                  
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-md-12">
            
          </div>
        </div>
        <div class="modal-footer">
          <button id="dpfix_btnpush" class="btn btn-block btn-outline-secondary" data-id="0">PUSH</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade mt-3" id="modal-disbal" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content mt-2" style="height: 676px; overflow-y: auto;">
        <div class="modal-header bg-warning">
          Fix Disbalance
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              Number of Student(s): <span id="disbal_studcount">0</span>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-3">
                <select id="disbal_levelid" class="select2bs4">
                  <option value="0">Grade Level</option>
                  @foreach(db::table('gradelevel')->where('deleted', 0)->orderBy('sortid')->get() as $glevel)
                    <option value="{{$glevel->id}}">{{$glevel->levelname}}</option>
                  @endforeach
                </select>
            </div>
              
            <div class="col-md-3">
              <select id="disbal_sy" class="select2bs4">
                <option>School Year</option>
                @foreach(db::table('sy')->get() as $sy)
                  @if($sy->id == App\FinanceModel::getSYID())
                    <option value="{{$sy->id}}" selected="">{{$sy->sydesc}}</option>
                  @else
                    <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            
            <div class="col-md-3">
              <select id="disbal_sem" class="select2bs4">
                <option>Semester</option>
                @foreach(db::table('semester')->where('deleted', 0)->get() as $sem)
                  @if($sem->id == App\FinanceModel::getSemID())
                    <option value="{{$sem->id}}" selected="">{{$sem->semester}}</option>
                  @else
                    <option value="{{$sem->id}}" selected="">{{$sem->semester}}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-12">
             <div class="col-md-12 table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>NAME</th>
                      <th>Ledger Balance</th>
                      <th>Payment Sched Balance</th>
                      <th><button id="generatedisbalance" class="btn btn-primary btn-block">Generate</button></th>
                    </tr>
                  </thead>
                  <tbody id="listdisbalance">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
        </div>
        <div class="modal-footer">
          
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
          <div class="row">
            <div class="col-md-12">
              <span id="disbal_feesinfo" class="text-bold text-lg mb-3"></span>
            </div>
          </div>

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

  <div class="modal fade" id="modal-tlf" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-secondary">
          <h4 class="modal-title">Transaction/Ledger Fixer</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body" style="height: 34em">
          <div class="row">
            <div class="col-md-2 mb-3">
              <button id="gentlf" class="btn btn-primary">GENERATE <span id="tlfcount" class="badge badge-warning">0</span></button>
            </div> 
            <div class="col-md-3 mb-3">
              <button id="ltid" class="btn btn-warning">No Ledger Trans ID <span id="ltid_count" class="badge badge-primary"></span></button>
            </div>
            <div class="col-md-3 mb-3">
              <button id="transdetail_fix" class="btn btn-danger">Fix Transaction Detail</button>
            </div>
          </div>
          <div id="tlfmain" class="row">
            <div class="col-md-12 table-responsive" style="height: 479px">
              <table class="table table-striped text-sm">
                <thead>
                  <tr>
                    <th>SID</th>
                    <th>NAME</th>
                    <th style="width: 98px">DATE</th>
                    <th>OR</th>
                    <th>PARTICULARS</th>
                    <th>AMOUNT</th>
                    <th>CANCELLED</th>
                    <th>VOID</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="tlflist">
                  
                </tbody>
              </table>
            </div>
          </div>            
          <div id="ltidmain" class="row" style="display: none">
            <div class="col-md-12" style="height: 479px; overflow-y: auto;">
              <div class="row">
                <div class="col-md-12 mb-3">
                  <button id="ltid_gen" class="btn btn-success">Generate</button>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 table-responsive">
                  <table class="table table-striped text-sm table-sm">
                    <thead>
                      <tr>
                        <th>STUD NAME</th>
                        <th>PARTICULARS</th>
                        <th style="width: 80px">LEDGER OR</th>
                        <th style="width: 75px">TRANS OR</th>
                        <th class="text-center">LEDGER AMOUNT</th>
                        <th class="text-center">TRANS AMOUNT</th>
                        <th>TRANS ID</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody id="ltidlist" style="cursor: pointer;">
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div id="transdetailmain" class="row" style="display: none">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header bg-danger">
                      <div class="row">
                        <div class="col-md-6">
                          Cashier Transactions    
                        </div>
                        <div class="col-md-2">
                          
                        </div>
                        <div class="col-md-2 text-right">
                          <input type="search" id="ftd_searchornum" class="form-control" placeholder="Search OR number">
                        </div>
                        <div class="col-md-2 text-right">
                          <button id="ftd_generate" class="btn btn-default btn-block">Generate</button>
                        </div>
                      </div>
                      
                    </div>
                    <div class="card-body table-responsive" style="height: 8em">
                      <table class="table table-hover table-sm text-sm">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>NAME</th>
                            <th>OR NUMBER</th>
                            <th>TRANS DATE</th>
                            <th>AMOUNT</th>
                          </tr>
                        </thead>
                        <tbody id="ftd_cashiertransbody" style="cursor: pointer"></tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="card">
                    <div class="card-header bg-purple">
                      Transaction Details
                    </div>
                    <div class="card-body table-responsive pt-0" style="max-height: 14em">
                      <table class="table table-hover table-sm text-xs">
                        <thead>
                          <tr>
                            <th>ITEMS</th>
                            <th class="text-center">AMOUNT</th>
                          </tr>
                        </thead>
                        <tbody id="ftd_detailbody" style="cursor: pointer"></tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="card">
                    <div id="ftd_transid" data-id="0" class="card-header bg-olive">
                      Transaction Bunker
                    </div>
                    <div class="card-body table-responsive pt-0" style="max-height: 14em">
                      <table class="table table-hover table-sm text-xs">
                        <thead>
                          <tr>
                            <th>TRANSNO</th>
                            <th>TRANS DATE</th>
                            <th>PARTICULARS</th>
                            <th>AMOUNT</th>
                          </tr>
                        </thead>
                        <tbody id="ftd_tbunkerbody" style="cursor: pointer"></tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
              
          </div>
        </div>
        <!--<div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          {{-- <button id="btnreloadproceed" type="button" class="btn btn-primary" data-dismiss="modal">PROCEED</button> --}}
        </div>-->
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade" id="modal-dcc" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Daily Cash Collection Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-3">
              <input id="dcchead_description" type="text" class="form-control" placeholder="Description">
            </div>
            <div class="col-md-2">
              <select id="dcchead_type" class="form-control select2bs4">
                <option>TUITION</option>
                <option>MISC</option>
                <option>BOOKS</option>
                <option>OTHERS</option>
              </select>
            </div>
            <div class="col-md-1">
              <input id="dcchead_width" type="number" class="form-control" placeholder="Width">
            </div>

            <div class="col-md-2">
              <button id="dcc_insertheader" class="btn btn-primary btn-block text-sm">Add Header</button>
            </div> 
            <div class="col-md-2">
              <button id="dcc_insertbody" class="btn btn-warning btn-block text-sm">Add Item</button>
            </div>
            <div class="col-md-2">
              <button id="dcc_miscitem" class="btn btn-info btn-block text-sm">MISC Item</button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 table-responsive" style="height: 479px">
              <table class="table table-striped text-sm">
                <thead>
                  <tr id="dcc_headlist">
                    
                  </tr>
                </thead>
                <tbody id="dcc_bodylist">
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          {{-- <button id="btnreloadproceed" type="button" class="btn btn-primary" data-dismiss="modal">PROCEED</button> --}}
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade" id="modal-dcc_item" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md" style="margin-top: 10em">
      <div class="modal-content">
        <div class="modal-header bg-secondary" style="cursor: move;">
          <h4 class="modal-title">Daily Cash Collection Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8">
              <select id="dcc_headerid" class="form-control select2bs4">
                <option value="0">Select Header</option>
                @foreach(db::table('dcc_header')->where('deleted', 0)->get() as $header)
                  <option value="{{$header->id}}">{{$header->description}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <button id="dcc_insbody" class="btn btn-primary btn-block" data-header="" data-type="ITEMS" data-id="">ADD</button>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-6">
              <button class="btn btn-success btn-block" id="dcc_itemlist">ITEMS</button>
            </div>
            <div class="col-md-6">
              <button class="btn btn-info btn-block" id="dcc_classlist">CLASSIFICATION</button>
            </div>
          </div>
          <div class="row mt-3 item-list">
            <div class="col-md-12" style="height: 259px;overflow-y: auto;">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Desciption</th>
                  </tr>
                </thead>
                <tbody class="dcc_listitembody" style="cursor: pointer;">
                  @foreach(db::table('items')->where('deleted', 0)->get() as $item)
                    <tr data-id="{{$item->id}}">
                      <td class="desc">{{$item->description}}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <div class="row mt-3 itemclass-list" style="display: none">
            <div class="col-md-12" style="height: 259px;overflow-y: auto;">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Desciption</th>
                  </tr>
                </thead>
                <tbody class="dcc_listitembody" style="cursor: pointer;">
                  @foreach(db::table('itemclassification')->where('deleted', 0)->get() as $item)
                    <tr data-id="{{$item->id}}">
                      <td class="desc">{{$item->description}}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          {{-- <button id="btnreloadproceed" type="button" class="btn btn-primary" data-dismiss="modal">PROCEED</button> --}}
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade" id="modal-dcc_misc" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md" style="margin-top: 10em">
      <div class="modal-content">
        <div class="modal-header bg-secondary" style="cursor: move;">
          <h4 class="modal-title">Miscellaneous Items</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8">
              <select type="" name="" class="form-control select2bs4" id="dcc_miscclass">
                @foreach(db::table('itemclassification')->where('deleted', 0)->get() as $class)
                  <option value="{{$class->id}}">{{$class->description}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <button class="btn btn-primary" id="dcc_addmiscitem">Add</button>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-12 table-responsive">
              <table class="table table-striped">
                <head>
                  <tr>
                    <td>Classification</td>
                  </tr>
                </head>
                <tbody id="dcc_misclist">
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          {{-- <button id="btnreloadproceed" type="button" class="btn btn-primary" data-dismiss="modal">PROCEED</button> --}}
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade" id="modal-bookentry" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-indigo" style="cursor: move;">
          <h4 class="modal-title">Book Entry</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <button id="be_entries" class="btn btn-primary btn-block">
                 Book Entries
              </button>
            </div> 
            <div class="col-md-3">
              <button id="be_setup" class="btn btn-warning btn-block">
                 Book Entry Setup
              </button>
            </div> 
          </div>
          <div id="divbelist">
            <div class="row mt-3">
              <div class="col-md-6">
                <select id="be_stud" class="select2bs4 form-control">
                  <option value="0">Select Student</option>
                  @foreach(db::table('studinfo')->where('deleted', 0)->where('studstatus', '!=', 0)->orderBy('lastname')->get() as $stud)
                    <option value="{{$stud->id}}">{{$stud->sid}} - {{$stud->lastname}}, {{$stud->firstname}} {{$stud->middlename}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-2">
                Grade level:
              </div>
              <div class="col-md-4">
                <span id="be_glevel" data-id="0"></span>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-12 table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="be_bodylist"></tbody>
                </table>
              </div>
            </div>
          </div>
          <div id="divbesetup" class="row mt-3" style="display: none">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-2 text-sm">
                  <label>Class ID</label>
                  <select id="beclass" class="form-control select2bs4">
                    <option class="0">Select Classification</option>
                    @foreach(db::table('itemclassification')->where('deleted', 0)->orderBy('description')->get() as $class)
                      @if($class->id == DB::table('bookentrysetup')->first()->classid)
                        <option value="{{$class->id}}" selected="">{{$class->description}}</option>
                      @else
                        <option value="{{$class->id}}">{{$class->description}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                <div class="col-md-2 text-sm">
                  <label>ITEM</label>
                  <select id="beitem" class="form-control text-sm select2bs4">
                    <option class="0">Select Item</option>
                    @foreach(db::table('items')->where('deleted', 0)->orderBy('description')->get() as $item)
                      @if($item->id == DB::table('bookentrysetup')->first()->classid)
                        <option value="{{$item->id}}" selected="">{{$item->description}}</option>
                      @else
                        <option value="{{$item->id}}">{{$item->description}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                <div class="col-md-2 text-sm">
                  <label>ITEM</label>
                  <select id="bemop" class="form-control text-sm select2bs4">
                    <option class="0">Mode of Payment</option>
                    @foreach(db::table('paymentsetup')->where('deleted', 0)->orderBy('paymentdesc')->get() as $mop)
                      @if($mop->id == DB::table('bookentrysetup')->first()->mopid)
                        <option value="{{$mop->id}}" selected="">{{$mop->paymentdesc}}</option>
                      @else
                        <option value="{{$mop->id}}">{{$mop->paymentdesc}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-md-6">
                  <button id="besetup_save" class="btn btn-primary btn-block">SAVE</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          {{-- <button id="btnreloadproceed" type="button" class="btn btn-primary" data-dismiss="modal">PROCEED</button> --}}
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade" id="modal-cashier" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content" style="height: 700px">
        <div class="modal-header bg-secondary" style="cursor: move;">
          <h4 class="modal-title">Cashier Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <div class="row">
                <div class="col-md-12">
                  <label id="addreg" class="addclass" data-id="REG" style="cursor: pointer;">Registration</label>    
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 table table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Classification</th>
                      </tr>
                    </thead>
                    <tbody id="chrng_reglist"></tbody>
                  </table>
                </div>
              </div>
              
            </div>
            <div class="col-md-3">
              <div class="row">
                <div class="col-md-12">
                  <label id="addtui" class="addclass" data-id="TUI" style="cursor: pointer;">Tuition</label>    
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 table table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Classification</th>
                      </tr>
                    </thead>
                    <tbody id="chrng_tuilist"></tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="row">
                <div class="col-md-12">
                  <label id="addmisc" class="addclass" data-id="MISC" style="cursor: pointer;">Miscellaneous</label>    
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 table table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Classification</th>
                      </tr>
                    </thead>
                    <tbody id="chrng_misclist"></tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="row">
                <div class="col-md-12">
                  <label id="addoth" class="addclass" data-id="OTH" style="cursor: pointer;">Other Fees</label>    
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 table table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Classification</th>
                      </tr>
                    </thead>
                    <tbody id="chrng_othlist"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          {{-- <button id="btnreloadproceed" type="button" class="btn btn-primary" data-dismiss="modal">PROCEED</button> --}}
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade" id="modal-cashier-addclass" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content mt-5">
        <div class="modal-header bg-dark" style="cursor: move;">
          <h4 class="modal-title">Cashier Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <label id="groupname" data-id="" class="text-bold text-lg"></label>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-7">
                      <option value="0"></option>
                      <select id="classid" class="form-control select2bs4">
                        @foreach(db::table('itemclassification')->where('deleted', 0)->get() as $class)
                          <option value="{{$class->id}}">{{$class->description}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-5 mt-4">
                      <div class="form-group clearfix">
                        <div class="icheck-primary d-inline">
                          <input type="checkbox" id="chrngaddclassitemized">
                          <label for="chrngaddclassitemized">
                            Itemized
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          <button id="btnchrngaddclass" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <!--<div class="modal fade" id="modal-paysched" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content mt-2" style="height: 38em">
        <div class="modal-header bg-dark" style="cursor: move;">
          <h4 class="modal-title">Paysched Detail</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="col-md-8">
                      @php
                        $studinfo = db::table('studinfo')  
                          ->select('studinfo.id', 'sid', 'lastname', 'firstname', 'middlename', 'levelname', 'sectionname', 'grantee.description as grantee')
                          ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                          ->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
                          ->where('studinfo.deleted', 0)
                          
                          ->orderBy('lastname', 'ASC')
                          ->orderBy('firstname', 'ASC')
                          ->get();
                      @endphp
                      <select id="paysched_studid" class="form-control select2bs4">
                            <option value="0">Select Student</option>
                            @foreach($studinfo as $stud)
                              {{$name = $stud->sid . ' - ' .  $stud->lastname . ', '. $stud->firstname . ' ' . $stud->middlename . ' | ' . $stud->levelname . ' - ' . $stud->sectionname}}
                              <option value="{{$stud->id}}">{{$name}}</option>
                            @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="card-body" style="height: 22em; overflow-y: scroll;">
                  <div class="row">
                    <div class="col-md-12 table-responsive tableFixHead h-100">
                      <table class="table table-striped table-hover table-sm text-sm" data-order="id">
                        <thead>
                          <tr>
                            <th class="bg-primary">ID</th>
                            <th class="bg-primary" style="width: 15em">Classification</th>
                            <th class="bg-primary text-center">Payment No</th>
                            <th class="bg-primary" style="width: 21em">Particulars</th>
                            <th class="bg-primary">Duedate</th>
                            <th class="bg-primary text-center">Amount</th>
                            <th class="bg-primary text-center">Paid</th>
                            <th class="bg-primary text-center">Balance</th>
                          </tr>
                        </thead>
                        <tbody id="paysched-list" class="cursor-pointer">
                        </tbody>
                        <tfoot>
                          <tr>
                            <th colspan="5" class="text-right">TOTAL: </th>
                            <th class="text-right paysched_total-amount">0.00</th>
                            <th class="text-right paysched_total-paid">0.00</th>
                            <th class="text-right paysched_total-balance">0.00</th>
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
        <div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          {{-- <button id="btnchrngaddclass" type="button" class="btn btn-primary" data-dismiss="modal">Save</button> --}}
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>-->

  <div class="modal fade" id="modal-paysched-edit" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content mt-5">
        <div class="modal-header bg-olive" style="cursor: move;">
          <h4 class="modal-title">Payched Detail - Edit</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <label>Classification</label>
            </div>
            <div class="col-md-8">
              <select id="paysched-edit_classid" class="select2bs4 form-control">
                
              </select>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-4">
              <label>Particulars</label>
            </div>
            <div class="col-md-8">
              <input id="paysched-edit_particulars" type="text" name="" class="form-control">
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-4">
              <label>Duedate</label>
            </div>
            <div class="col-md-8">
              <input id="paysched-edit_duedate" type="date" name="" class="form-control">
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-4">
              <label>Amount</label>
            </div>
            <div class="col-md-8">
              <input id="paysched-edit_amount" type="text" name="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" autocomplete="off" class="form-control">
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-4">
              <label>Paid</label>
            </div>
            <div class="col-md-8">
              <input id="paysched-edit_paid" type="text" name="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" autocomplete="off" class="form-control">
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-4">
              <label>Balance</label>
            </div>
            <div class="col-md-8">
              <input id="paysched-edit_balance" type="text" name="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" autocomplete="off" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          <button id="paysched-edit_save" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade" id="modal-ftd-detailedit" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content mt-5">
        <div class="modal-header bg-secondary" style="cursor: move;">
          <h4 class="modal-title">Transaction Detail - Edit</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <label>Amount</label>
            </div>
            <div class="col-md-8">
              <input id="detail-edit_amount" type="text" name="currency-field" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" autocomplete="off" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          <button id="ftd_detail-edit_save" type="button" class="btn btn-primary" data-id="0" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-trxitemized" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content mt-5">
        <div class="modal-header bg-dark" style="cursor: move;">
          <h4 class="modal-title">Transaction Itemized</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-2">
              <select id="trxitemized_sy" class="bootstrap4 form-control filter">
                @foreach(App\FinanceModel::getSY() as $sy)
                  @if($sy->isactive == 1)
                    <option value="{{$sy->id}}" selected="">{{$sy->sydesc}}</option>
                  @else
                    <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <select id="trxitemized_sem" class="bootstrap4 form-control filter">
                @foreach(App\FinanceModel::getSem() as $sem)
                  @if($sem->isactive == 1)
                    <option value="{{$sem->id}}" selected="">{{$sem->semester}}</option>
                  @else
                    <option value="{{$sem->id}}">{{$sem->semester}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <input type="text" class="form-control float-right daterange" id="trxitemized_daterange">
            </div>
            <div class="col-md-3">
              <button id="trxitemized_generatetrx" class="btn btn-primary btn-block">GENERATE</button>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-7">
              <div class="card">
                <div class="card-header bg-primary">
                  Transactions
                </div>
                <div class="card-body table-responsive" style="height:19em">
                  <table class="table table-hover table-sm text-sm">
                    <thead>
                      <tr>
                        <th>OR</th>
                        <th>DATE</th>
                        <th>SY</th>
                        <th>SEM</th>
                        <th class="text-center">AMOUNT</th>
                      </tr>
                    </thead>
                    <tbody id="trxitemized_trxlist"></tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="card">
                <div class="card-header bg-warning">
                  <div class="row">
                    <div class="col-md-8">
                      Transaction Itemized    
                    </div>
                    <div class="col-md-4 text-right">
                      <button id="trxitemized_savetrxitems" class="btn btn-primary btn-sm btn-block" data-items="">Save</button>
                    </div>
                  </div>
                  
                </div>
                <div class="card-body table-responsive" style="height:19em">
                  <table class="table table-hover table-sm text-sm">
                    <thead>
                      <tr>
                        <th>OR</th>
                        <th>DESCRIPTION</th>
                        <th class="text-center">AMOUNT</th>
                      </tr>
                    </thead>
                    <tbody id="trxitemized_trxitemizedlist"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer ">
          <button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          {{-- <button id="ftd_detail-edit_save" type="button" class="btn btn-primary" data-id="0" data-dismiss="modal">Save</button> --}}
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

  <div class="modal fade" id="modal-overlay2" data-backdrop="static" aria-modal="true" style="display: none;">
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
                            <div class="progress mb-3">
                              <div class="progress-bar progress-bar-striped active bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="" style="width:0">
                                <span class="sr-only">20% Complete</span>
                              </div>
                            </div>
                        </div>
                    </div>
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

    .tableFixHead{ 
        overflow-y: auto; height: 100% !important; 
    }
    .tableFixHead thead th { 
        position: sticky; top: -1 !important; 
        z-index: 100 !important;
    }
    .side-boder{
        border-right: solid 1px #dee2e6 !important; border-left: solid 1px #dee2e6 !important;
    }
    .center-border{
        border-right: solid 1px #dee2e6;
    }
  </style>
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

  // document.getElementById("txtstudent").addEventListener("keypress", forceKeyPressUppercase, false);
  // document.getElementById("txtdescription").addEventListener("keypress", forceKeyPressUppercase, false);

  </script>
  <style type="text/css">
    .cursor-pointer{
      cursor: pointer;

    }

    .Div-hide{
      display: none !important;
    }

    .Div-show{
      display: block;
    }

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


  <script type="text/javascript">
    
    var studlistarray;
    $(document).ready(function(){
        
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      $('.daterange').daterangepicker();

      // $('#modal-dcc_item').draggable({
        // handle:".modal-header"
      // });

      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      });

      $(document).on('click', '#resetpaymentaccount', function(){
        $('#modal-rsa').modal('show');
      });

      $(document).on('select2:close', '#selstud', function(){
        var studid = $('#selstud').val();
        genpayinfo();
        paysched_loaddetails(studid, 'id')
      });

      $(document).on('change', '.filter', function(){
        var studid = $('#selstud').val();
        genpayinfo();
        paysched_loaddetails(studid, 'id')
      });

      function genpayinfo()
      {
        var studid = $('#selstud').val();
        var syid = $('#cbosy').val();
        var semid = $('#cbosem').val();

        $.ajax({
          url: '{{route('genpayinfo')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            studid:studid,
            syid:syid,
            semid:semid
          },
          success:function(data)
          {
            $('#ledger').html(data.ledgerlist);
            $('#totalamount').text(data.totalamount);
            $('#totalpayment').text(data.totalpayment);
            $('#rbal').text(data.rbal);
            $('#spanstudid').text(data.studid);
            $('#fees').html(data.feelist);
            $('#spangrantee').text(data.grantee);
            $('#btnreset').attr('data-level', data.levelid);

            if(data.levelid == 21)
            {
              $('#divresetpaysched').show();
            }
            else
            {
              $('#divresetpaysched').hide(); 
            }
          }
        })
        
      }

      function genstud()
      {
        var levelid = $('#list_levelid').val();
        var grantee = $('#list_grantee').val();
        var course = $('#list_courses').val();
		var syid = $('#cbosy').val();
        var semid = $('#cbosem').val();

        $.ajax({
          url: '{{route('genstud')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            levelid:levelid,
            grantee:grantee,
            course:course,
			syid:syid,
            semid:semid
          },
          success:function(data)
          {
            $('#list_paymentplan').html(data.feelist);
            $('#list_stud').html(data.studlist);
            $('#studcount').text(data.studcount)

            if(levelid >= 17 && levelid <= 21)
            {
              $('#div_grantee').prop('hidden', true);
              $('#div_courses').prop('hidden', false);
            }
            else
            {
              $('#div_grantee').prop('hidden', false);
              $('#div_courses').prop('hidden', true); 
            }
          }
        });
      }

      function calcLedger(studid)
      {
        $.ajax({
          url: '{{route('calcLedger')}}',
          type: 'GET',
          dataType: '',
          data: {
            studid:studid
          },
          success:function(data)
          {
            $('#list_stud tr').each(function(){
              if($(this).attr('data-id') == studid)
              {
                $('.td-amount', this).text(data)
                $('.btn-reset[data-id="'+studid+'"]').removeClass('btn-secondary');
                $('.btn-reset[data-id="'+studid+'"]').addClass('btn-success');
              }
            })
          }
        }); 
      }

      function searchfilter()
      {
        var levelid = $('#cbogradelevel').val();
        var grantee = $('#cbograntee').val();
        var mol = $('#cbomol').val();
        var sc = $('#sc').val();
        var stud = $('#txtstudent').val();
        var acadprog = $('#cboacadprog').val()

        $.ajax({
          url:"{{route('adjfilter')}}",
          method:'GET',
          data:{
            levelid:levelid,
            grantee:grantee,
            mol:mol,
            sc:sc,
            stud:stud,
            acadprog:acadprog
          },
          dataType:'json',
          success:function(data)
          {
            $('#filter-list').html(data.list);
            $('.stud-count').attr('data-count', data.studcount);
            $('#studcount').text(data.studcount);
            studlistarray = data.studlistarray;
          }
        });  
      }

      $(document).on('click', '#btnreset', function(){
        var studid = $('#selstud').val();
        var feesid = $('#fees').val();
        var levelid = $(this).attr('data-level');

        Swal.fire({
          title: 'Reset Payment Account?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, reset it!'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"{{route('resetpayment_v2')}}",
              method:'GET',
              data:{
                studid:studid,
                feesid:feesid
              },
              dataType:'',
              success:function(data)
              {
                genpayinfo();
                paysched_loaddetails(studid, 'id');
                Swal.fire(
                  'Success!',
                  'Acount has been resetted',
                  'success'
                );
              }
            });      
          }
        })
      });

      $(document).on('click', '#btnlist', function(){
        $('#body_box').prop('hidden', true);
        $('#body_list').prop('hidden', false);
      });

      $(document).on('click', '#btnbox', function(){
        $('#body_box').prop('hidden', false);
        $('#body_list').prop('hidden', true);
      });

      $(document).on('select2:close', '.list_filter', function(){
        genstud();
      });

      function restfunc(btnresets)
      {
        var studid = btnresets.attr('data-id');
        var feesid = $('#list_paymentplan').val();
		var syid = $('#cbosy').val();
		var semid = $('#cbosem').val();
		
        btnresets.prop('disabled', true);
        btnresets.removeClass('btn-warning');
        btnresets.addClass('btn-secondary');

        $.ajax({
            async:true,
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
              // console.log(studid);
              
              calcLedger(studid);
            }
          });
      }


      $(document).on('click', '.btn-reset', function(){
        
        restfunc($(this))

        
      });

      $(document).on('click', '#btnclearledger', function(){
        var studid = $('#selstud').val();
        Swal.fire({
          title: 'Reset Payment Account?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Clear it!'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"{{route('clearledger')}}",
              method:'GET',
              data:{
                studid:studid
              },
              dataType:'',
              success:function(data)
              {
                genpayinfo();
                Swal.fire(
                  'Success!',
                  'Acount has been resetted',
                  'success'
                );
              }
            });      
          }
        })
      });

      $(document).on('click', '.rpa-itemfix', function(){
        var studid = $('#selstud').val();
        var ledgerid = $(this).attr('data-id');

        if(ledgerid > 0)
        {
          Swal.fire({
            title: 'Fix Row Ledger?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Fix it!'
          }).then((result) => {
            if (result.value) {

              $.ajax({
                url:"{{route('fixledgerrow')}}",
                method:'GET',
                data:{
                  studid:studid,
                  ledgerid:ledgerid
                },
                dataType:'',
                success:function(data)
                {
                  genpayinfo();
                  Swal.fire(
                    'Success!',
                    'Ledger Row has been fixed',
                    'success'
                  );
                }
              });      
            }
          })
        }

      });

      $(document).on('click', '.rpa-itemremove', function(){
        var studid = $('#selstud').val();
        var ledgerid = $(this).attr('data-id');

        if(ledgerid > 0)
        {
          Swal.fire({
            title: 'Remove Row Ledger?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Remove it!'
          }).then((result) => {
            if (result.value) {

              $.ajax({
                url:"{{route('removeledgerrow')}}",
                method:'GET',
                data:{
                  studid:studid,
                  ledgerid:ledgerid
                },
                dataType:'',
                success:function(data)
                {
                  genpayinfo();
                  Swal.fire(
                    'Success!',
                    'Ledger Row has been Removed',
                    'success'
                  );
                }
              });      
            }
          })
        }        
      });

      // $('.rpa-itemremove').on('click', function(){
      //   alert('remove');
      //   console.log('remove');
      // })

      // $(document).on('click', '#ledger tr', function(){
      //   var studid = $('#selstud').val();
      //   var ledgerid = $(this).attr('data-id');

      //   // console.log(ledgerid);

        // if(ledgerid > 0)
        // {
        //   Swal.fire({
        //     title: 'Fix Row Ledger?',
        //     text: "You won't be able to revert this!",
        //     type: 'warning',
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     confirmButtonText: 'Yes, Fix it!'
        //   }).then((result) => {
        //     if (result.value) {

        //       $.ajax({
        //         url:"",
        //         method:'GET',
        //         data:{
        //           studid:studid,
        //           ledgerid:ledgerid
        //         },
        //         dataType:'',
        //         success:function(data)
        //         {
        //           genpayirnfo();
        //           Swal.fire(
        //             'Success!',
        //             'Ledger Row has been fixed',
        //             'success'
        //           );
        //         }
        //       });      
        //     }
        //   })
        // }
      // });

      $(document).on('click', '#tsi', function(){
        $('#modal-tsi').modal('show')
      });

      $(document).on('select2:close', '#itemizedselectstud', function(){
        var studid = $(this).val();
        $.ajax({
          url: '{{route('genitemizedinfo')}}',
          type: 'GET',
          data: {
            studid:studid
          },
          dataType: 'json',
          success:function(data)
          {
            $('#transitembody').html(data.itemlist);
            $('#ledgeritemizedbody').html(data.ledgerlist);
            $('#sid').text(data.sid)
            $('#grantee').text(data.grantee)
          }
        });
      });

      $(document).on('click', '#btnledgeritemizedreset', function(){
        var studid = $('#itemizedselectstud').val()
        Swal.fire({
          title: 'Reset StudledgerItemized?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Reset it!'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"{{route('ledgeritemizedreset')}}",
              method:'GET',
              data:{
                studid:studid
              },
              dataType:'',
              success:function(data)
              {
                $('#itemizedselectstud').trigger('select2:close');
                
                Swal.fire(
                  'Success!',
                  'StudledgerItemized has been resetted',
                  'success'
                );
              }
            });      
          }
        })
      });

      $(document).on('click', '#btntransitemreset', function(){
        var studid = $('#itemizedselectstud').val();

        Swal.fire({
          title: 'Reset Chrngtransitems?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Reset it!'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"{{route('transitemsreset')}}",
              method:'GET',
              data:{
                studid:studid
              },
              dataType:'',
              success:function(data)
              {
                $('#itemizedselectstud').trigger('select2:close');
                
                Swal.fire(
                  'Success!',
                  'Chrngtransitems has been resetted',
                  'success'
                );
              }
            });      
          }
        });


      });

      $(document).on('click', '#tuncatetransitems', function(){
        Swal.fire({
          title: 'Truncate Chrngtransitems?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Trucnate it!'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"{{route('transitemstruncate')}}",
              method:'GET',
              data:{
                
              },
              beforeSend:function(){
                $('#modal-overlay').modal('show');
              },
              dataType:'',
              success:function(data)
              {
                $('#modal-overlay').modal('hide');
                
                Swal.fire(
                  'Success!',
                  'Chrngtransitems has been resetted',
                  'success'
                );
              }
            });      
          }
        });
      });

      $(document).on('click', '#truncatestudledgeritemized', function(){
        Swal.fire({
          title: 'Truncate Studledger Itemized?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Trucnate it!'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"{{route('ledgeritemizedtruncate')}}",
              method:'GET',
              data:{
                
              },
              beforeSend:function(){
                $('#modal-overlay').modal('show');
              },
              dataType:'',
              success:function(data)
              {
                $('#modal-overlay').modal('hide');
                
                Swal.fire(
                  'Success!',
                  'Studledger Itemized has been resetted',
                  'success'
                );
              }
            });      
          }
        });
      });

      $(document).on('click', '#resetall', function(){
        Swal.fire({
          title: 'Reset All?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Reset it!'
        }).then((result) => {
          if (result.value) {
            
            $('.btn-reset').each(function(){
              restfunc($(this));
            });

          }
        });
      });

      $(document).on('click', '#adjO', function(){
        searchadj();
        $('#modal-adjoveride').modal('show');
      });

      $(document).on('select2:close', '.searchcontrol', function(){
        searchadj();
      });

      $(document).on('click', '#adj_btngosearch', function(){
        searchadj();
      })

      function searchadj()
      {
        var levelid = $('#adj_levelid').val();
        var filter = $('#adj_filter').val();

        $.ajax({
          url:"{{route('adj_search')}}",
          method:'GET',
          data:{
            levelid:levelid,
            filter:filter
          },
          beforeSend:function(){
            // $('#modal-overlay').modal('show');
          },
          dataType:'json',
          success:function(data)
          {
            $('#adj-list').html(data.list);
            $('#adj_count').text(data.studcount);
          }
        });
      }

      $(document).on('click', '#adj-list tr', function(){
        Swal.fire({
          title: 'Remove Adjustment?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Remove it!'
        }).then((result) => {
          if (result.value) {
            removeadj($(this).attr('data-id'));
          }
        });
      });

      $(document).on('click', '#adj_removealldetail', function(){
        Swal.fire({
          title: 'Remove All Adjustment?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Remove it!'
        }).then((result) => {
          if (result.value) {
            $('#adj-list tr').each(function(){
              removeadj($(this).attr('data-id'));
            });    
          }
        });
      });

      function removeadj(dataid)
      {
        $.ajax({
          url:"{{route('adj_removedetail')}}",
          method:'GET',
          data:{
            dataid:dataid
          },
          beforeSend:function(){
            // $('#modal-overlay').modal('show');
          },
          dataType:'',
          success:function(data)
          {
            searchadj();
          }
        });
      }

      $(document).on('click', '#fwdfixneg', function(){

       $.ajax({
          url:"{{route('fwd_gennegstud')}}",
          method:'GET',
          data:{
            
          },
          beforeSend:function(){
            // $('#modal-overlay').modal('show');
          },
          dataType:'json',
          success:function(data)
          {
            $('#fwdnegstudlist').html(data.list);
            $('#balfwdcard').prop('hidden', false);    
          }
        }); 
      });

      $(document).on('select2:close', '#fwdnegstudlist', function(){
        studid = $(this).val();

        $.ajax({
          url:"{{route('fwd_genstudinfo')}}",
          method:'GET',
          data:{
            studid:studid
          },
          beforeSend:function(){
            // $('#modal-overlay').modal('show');
          },
          dataType:'json',
          success:function(data)
          {
            $('#fwdnegstudledger').html(data.ledgerlist);
            $('#fwdnegpaysched').html(data.payschedlist);
          }
        }); 
      });

      $(document).on('click', '#btnfixfwdneg', function(){
        var studid = $('#fwdnegstudlist').val();

        Swal.fire({
          title: 'Fix negative Balance Forwarding?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Fix it!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url:"{{route('fwd_fixnegativebal')}}",
              method:'GET',
              data:{
                studid:studid
              },
              beforeSend:function(){
                // $('#modal-overlay').modal('show');
              },
              dataType:'json',
              success:function(data)
              {
                $('#fwdnegstudlist').trigger('select2:close');

                Swal.fire(
                  'Success!',
                  'Negative Balance Forwarding has been fixed.',
                  'success'
                );
              }
            }); 
          }
        });

      });

      $(document).on('click', '#balfwdfix', function(){
        $('#modal-balfrwardfix').modal('show');
      });

      $(document).on('click', '#dpfixer', function(){
        $('#modal-dpfixer').modal('show');
      });

      $(document).on('select2:close', '#dpfix_selstud', function(){
        var studid = $(this).val();
        var syid = $('#dpfix_syid').val();
        var semid = $('#dpfix_semid').val();
        console.log(semid);
        $.ajax({
          url:"{{route('dpfix_geninfo')}}",
          method:'GET',
          data:{
            studid:studid,
            syid:syid,
            semid:semid
          },
          beforeSend:function(){
            // $('#modal-overlay').modal('show');
          },
          dataType:'json',
          success:function(data)
          {
            $('#dpfix_trans-list').html(data.translist);
            $('#dpfix_studledger').html(data.ledgerlist);

          }
        }); 
      });

      $(document).on('click', '#dpfix_trans-list tr', function(){

        $('#dpfix_trans-list tr').removeClass('bg-info');

        $('.trans[trans-id='+$(this).attr('trans-id')+']').addClass('bg-info');
        $('#dpfix_fixit').attr('data-id', $(this).attr('trans-id'));
      });

      $(document).on('click', '#btncheckdisbalance', function(){

        $('#disbalance_row').text('');
        if($('#divdpfix').prop('hidden') == true)
        {
          $('#divdpfix').prop('hidden', false);
          $('#divdisbalance').prop('hidden', true);
          $('#btncheckdisbalance').text('Check Disbalance Accounts');
        }
        else
        {
          $('#divdpfix').prop('hidden', true);
          $('#divdisbalance').prop('hidden', false); 
          $('#btncheckdisbalance').text('DP Fixer');
        }
      });

      function disbal_genstud(studid, maxcount, curcount, loadstyle)
      {
        var syid = $('#disbal_sy').val();
        var semid = $('#disbal_sem').val();

        $.ajax({
          url:"{{route('disbalance_genstud')}}",
          method:'GET',
          data:{
            studid:studid,
            syid:syid,
            semid:semid
          },
          dataType:'json',
          success:function(data)
          {
            var disbal_count = 0;
            if(loadstyle == '')
            {
              if(data.list != '')
              {
                $('#listdisbalance').append(data.list);
              }

              mcount = maxcount;
              ccount = curcount;

              percent = parseFloat(ccount)/parseFloat(mcount) * 100;

              $('.progress-bar').attr('style', 'width: ' + percent + '%');
              disbal_count = $('#listdisbalance tr').length;
              $('#disbal_studcount').text(disbal_count);

              if(percent == 100)
              {
                $('#modal-overlay2').modal('hide');
              }
            }
            else
            {
              $('#listdisbalance tr').each(function(){
                if($(this).attr('data-id') == studid)
                {
                  $(this).replaceWith(data.list);
                  disbal_count = $('#listdisbalance tr').length;
                  $('#disbal_studcount').text(disbal_count);
                }
              })
            }
          }
        }); 
      }

      $(document).on('click', '#generatedisbalance', function(){
        var count = 0;
        var levelid = $('#disbal_levelid').val();

        $('#listdisbalance tr').empty();

        $('.progress-bar').attr('style', 'width:0%');
        $.ajax({
          url:"{{route('disbalance_genstud_list')}}",
          method:'GET',
          data:{
            levelid:levelid
          },
          beforeSend:function(){
            $('#modal-overlay2').modal('show');
          },
          dataType:'json',
          success:function(data)
          {
            studcount = data.studcount;
            $('.progress-bar').attr('aria-valuemax', data.count);

            $.each(data.studlist, function(k, v){
              count += 1;
              disbal_genstud(v.studid, data.count, count, '');
            })
          }
        }); 

        

        // $.ajax({
        //   url:"{route('disbalance_genstud')}}",
        //   method:'GET',
        //   data:{
            
        //   },
        //   beforeSend:function(){
        //     $('#modal-overlay').modal('show');
        //   },
        //   dataType:'json',
        //   success:function(data)
        //   {
        //     // console.log('aaa');
        //     $('#listdisbalance').html(data.list);
        //     $('#disbal_studcount').text(data.datacount);
        //     $('#modal-overlay').modal('hide');
        //   }
        // }); 
      });



      $(document).on('click', '#dpfix_fixit', function(){
        var dataid = $('#dpfix_fixit').attr('data-id');
        var studid = $('#dpfix_selstud').val();
        var semid = $('#dpfix_semid').val();
        var syid = $('#dpfix_syid').val();

        $.ajax({
          url:"{{route('dpfix_loadorinfo')}}",
          method:'GET',
          data:{
            dataid:dataid,
            studid:studid,
            syid:syid,
            semid:semid
          },
          beforeSend:function(){
            // $('#modal-overlay').modal('show');
          },
          dataType:'json',
          success:function(data)
          {
            $('#dpfix_transitem').html(data.list);
            $('#dpfix-push_ornum').text(data.ornum);
            $('#dpfix-push_classid').html(data.ledgerlist);
            $('#dpfix-push_ornum').attr('data-id', data.transid)

            $('#modal-dpfix_push').modal('show');
            $('#modal-overlay').modal('hide');
          }
        }); 
      });

      $(document).on('click', '#dpfix_transitem tr', function(){
        $('#dpfix_transitem').find('tr').removeClass('bg-primary');
        $(this).addClass('bg-primary');
        $('#dpfix_btnpush').attr('data-id', $(this).attr('data-id'))
      });

      $(document).on('click', '#dpfix_btnpush', function(){
        var studid = $('#dpfix_selstud').val();
        var classid = $('#dpfix-push_classid').val();
        var transdetailid = $('#dpfix_btnpush').attr('data-id');
        var transid = $('#dpfix-push_ornum').attr('data-id');
        var ornum = $('#dpfix-push_ornum').text();
        var topaysched = $('#passtoPaysched').prop('checked');
        var toledger = $('#passtoLedger').prop('checked');

        var syid = $('#dpfix_syid').val();
        var semid = $('#dpfix_semid').val();

        if($('#pushall').prop('checked') == true)
        {
          var pushall = 1;
        }
        else
        {
          pushall = 0;
        }

        // console.log(toledger);

        if(($(this).attr('data-id') > 0 && $('#dpfix-push_classid').val() > 0)||($('#pushall').prop('checked') == true))
        {
          Swal.fire({
            title: 'Push to Accouts?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'PUSH'
          }).then((result) => {
            if (result.value) {
              $.ajax({
                url:"{{route('dpfix_pushtoledger')}}",
                method:'GET',
                data:{
                  studid:studid,
                  classid:classid,
                  transdetailid:transdetailid,
                  transid:transid,
                  ornum:ornum,
                  topaysched:topaysched,
                  toledger:toledger,
                  pushall:pushall,
                  semid:semid,
                  syid:syid
                },
                beforeSend:function(){
                  //$('#modal-overlay').modal('show'); 
                },
                dataType:'',
                success:function(data)
                {
                  $('#modal-overlay').modal('hide');
                  
                  if(data == 1)
                  {
                    $('#modal-dpfix_push').modal('hide');
                    $('#dpfix_selstud').trigger('select2:close');

                    Swal.fire(
                      'Pushed!',
                      '',
                      'success'
                    );
                  }
                  else
                  {
                    Swal.fire(
                      'Transaction already pushed!',
                      '',
                      'error'
                    ); 
                  }
                }
              }); 
            }
          });
        }
        else
        {
          Swal.fire(
            'Incomplete Selection',
            '',
            'error'
          ); 
        }

      });

      $(document).on('click', '#disbal', function(){
        $('#modal-disbal').modal('show');
      });

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

      $(document).on('click', '.disbal-reloadledger', function(){
        var studid = $(this).attr('data-id');
        var did = $(this).attr('data-id');
        $.ajax({
          url:"{{route('loadfees')}}",
          method:'GET',
          data:{
            studid:studid
          },
          dataType:'json',
          success:function(data)
          {
            
            // console.log(did);
            feesinfo = $('tr[data-id="'+did+'"]').find('.trname').text();  //$(this).closest('td').prev('.trname').text();

            console.log(feesinfo);

            $('#disbal_feesinfo').text(feesinfo)
            $('#loadfeelist').html(data.feelist);
            $('#btnreloadproceed').attr('data-stud', studid);
            $('#modal-fees').modal('show');
          }
        });        

      });

      $(document).on('click', '#btnreloadproceed', function(){
        var studid = $(this).attr('data-stud')
        var feesid = $(this).attr('data-id');
        var syid = $('#disbal_sy').val();
        var semid = $('#disbal_sem').val();

        // console.log(studid);

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
              url:"{{route('resetpayment_v2')}}",
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

                disbal_genstud(studid, 0, 0, 'reload');

                $('.disbal-reloadledger[data-id="'+studid+'"]').removeClass('bg-info');
                $('.disbal-reloadledger[data-id="'+studid+'"]').addClass('bg-success');
                $('.disbal-reloadledger[data-id="'+studid+'"]').prop('disabled', true);

                Swal.fire(
                  'Success!',
                  'Acount has been Reloaded',
                  'success'
                );
              }
            });      
          }
        });
      });

      $(document).on('click', '#generatedisbaltrans', function(){
        var syid = $('#dpfix_syid').val();
        var semid = $('#dpfix_semid').val();
        
        $.ajax({
          url:"{{route('disbaltrans_genstud')}}",
          method:'GET',
          data:{
            syid:syid,
            semid:semid
          },
          dataType:'json',
          beforeSend:function(){
            $('#modal-overlay').modal('show'); 
          },
          success:function(data)
          {
           $('#disbaltrans_list') .html(data.list);
           $('#modal-overlay').modal('hide');
          }
        });
      });

      $(document).on('mouseenter', '#disbaltrans_list tr', function(){
        $(this).addClass('bg-secondary');
      });

      $(document).on('mouseout', '#disbaltrans_list tr', function(){
        $(this).removeClass('bg-secondary');
      });

      $(document).on('click', '#disbaltrans_list tr', function(){
        $('#dpfix_selstud').val($(this).attr('data-id'));
        $('#dpfix_selstud').trigger('change');
        $('#dpfix_selstud').trigger('select2:close');
        $('#btncheckdisbalance').trigger('click');
      });

      $(document).on('click', '#tlf', function(){
        $('#modal-tlf').modal('show');
      });

      $(document).on('click', '#gentlf', function(){
        $.ajax({
          url: '{{route('tlf_generate')}}',
          type: 'GET',
          dataType: 'json',
          data: {

          },
          beforeSend:function()
          {
            $('#modal-overlay').modal('show');
          },
          success:function(data)
          {
            $('#tlflist').html(data.list);
            $('#tlfcount').text($('#tlflist tr').length);
            $('#ltidmain').hide();
            $('#tlfmain').show();
            $('#modal-overlay').modal('hide');
          }
        });
      });

      $(document).on('click', '.btn-tlffix', function(){
        var transid = $(this).attr('data-trans');
        var ledgerid = $(this).attr('data-ledger');

        $.ajax({
          url: '{{route('tlf_fix')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            transid:transid,
            ledgerid:ledgerid
          },
          beforeSend:function()
          {

          },
          complete:function()
          {
            $('#gentlf').trigger('click');
          }
        });
      });

      function dcc_headerview()
      {
        $.ajax({
          url: '{{route('dcc_view')}}',
          type: 'GET',
          dataType: 'json',
          data: {
          },
          beforeSend:function()
          {
          },
          success:function(data)
          {
            $('#dcc_headlist').html(data.headerlist)
            $('#dcc_bodylist').html(data.bodylist);
            var row = 1;
            $.each(data.bodyarray, function(k, v){
              console.log(v);

              if($('td[data-col="'+v.headerid+'"][data-row="'+row+'"]').text() == '')
              {
                $('td[data-col="'+v.headerid+'"][data-row="'+row+'"]').text(v.itemdesc);
              }
              else
              {
                row += 1;
                $('td[data-col="'+v.headerid+'"][data-row="'+row+'"]').text(v.itemdesc);
              }

            });
          }
        });
      }

      $(document).on('click', '#dcc', function(){
        dcc_headerview();
        $('#modal-dcc').modal('show');
      });

      $(document).on('click', '#dcc_insertheader', function(){
        var description = $('#dcchead_description').val();
        var type = $('#dcchead_type').val();
        var width = $('#dcchead_width').val();

        $.ajax({
          url: '{{route('dcc_headinsert')}}',
          type: 'GET',
          dataType: '',
          data: {
            description:description,
            type:type,
            width:width
          },
          beforeSend:function()
          {

          },
          success:function(data)
          {
            $('#dcchead_description').val('');
            $('#dcchead_width').val('');
            dcc_headerview();
          }
        });
      });

      $(document).on('click', '#dcc_insertbody', function(){
        $('#modal-dcc_item').modal('show');
      });

      $(document).on('click', '#dcc_classlist', function(){
        $(this).removeClass('btn-info');
        $(this).addClass('btn-success');

        $('#dcc_itemlist').removeClass('btn-success');
        $('#dcc_itemlist').addClass('btn-secondary');

        $('.itemclass-list').show();
        $('.item-list').hide();

        $('#dcc_insbody').attr('data-type', 'CLASSIFICATION');
      });

      $(document).on('click', '#dcc_itemlist', function(){
        $(this).removeClass('btn-secondary');
        $(this).addClass('btn-success');  

        $('#dcc_classlist').removeClass('btn-success');
        $('#dcc_classlist').addClass('btn-info');

        $('.itemclass-list').hide();
        $('.item-list').show();
        $('#dcc_insbody').attr('data-type', 'ITEMS');
      });

      $(document).on('select2:close', '#dcc_headerid', function(){
        $('#dcc_insbody').attr('data-header', $(this).val());
      });

      $(document).on('click', '.dcc_listitembody tr', function(){
        $('.dcc_listitembody tr').removeClass('bg-primary');
        $(this).addClass('bg-primary');

        $('#dcc_insbody').attr('data-id', $(this).attr('data-id'));
        $('#dcc_insbody').attr('data-desc', $(this).find('.desc').text());
      });

      $(document).on('click', '#dcc_insbody', function(){
        var headerid = $(this).attr('data-header');
        var itemid = $(this).attr('data-id');
        var idtype = $(this).attr('data-type');
        var itemdesc =  $(this).attr('data-desc');

        $.ajax({
          url: '{{route('dcc_bodyinsert')}}',
          type: 'GET',
          dataType: '',
          data: {
            headerid:headerid,
            itemid:itemid,
            idtype:idtype,
            itemdesc:itemdesc
          },
          beforeSend:function()
          {

          },
          success:function(data)
          {
            dcc_headerview();
          }
        });

      });

      function loaddccmisc()
      {
        $.ajax({
          url: '{{route('dcc_loadmiscitems')}}',
          type: 'GET',
          dataType: 'json',
          data: {
          },
          success:function(data)
          {
            $('#dcc_misclist').html(data.list);
          }
        });        
      }

      $(document).on('click', '#dcc_miscitem', function(){
        loaddccmisc();
        $('#modal-dcc_misc').modal('show');
      });

      $(document).on('click', '#dcc_addmiscitem', function(){
        var classid = $('#dcc_miscclass').val();

        $.ajax({
          url: '{{route('dcc_addmiscitems')}}',
          type: 'GET',
          dataType: '',
          data: {
            classid:classid
          },
          success:function(data)
          {
            loaddccmisc();
          }
        });         
      });

      $(document).on('click', '#bookentriessetup', function(){
        $('#modal-bookentry').modal('show');
      });

      function be_search()
      {
        var studid = $('#be_stud').val();

        $.ajax({
          url: '{{route('be_search')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            studid:studid
          },
          success:function(data)
          {
            $('#be_bodylist').html(data.list)
            $('#be_glevel').text(data.levelname + ' - ' + data.grantee)
          }
        });
      }

      $(document).on('select2:close', '#be_stud', function(){
        be_search();
      });

      $(document).on('click', '.be-remove', function(){
        var dataid = $(this).attr('data-id');

        Swal.fire({
          title: 'Remove Book Entry?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Remove it!'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"{{route('be_remove')}}",
              method:'GET',
              data:{
                dataid:dataid
              },
              dataType:'',
              complete:function(data)
              {
                be_search();

                Swal.fire(
                  'Success!',
                  'Book Entry has been Removed',
                  'success'
                );
              }
            });      
          }
        });
      });

      function chrngsetup_load()
      {
        $.ajax({
          url:"{{route('chrng_loadclass')}}",
          method:'GET',
          data:{
          },
          dataType:'json',
          success:function(data)
          {
            $('#chrng_reglist').html(data.reg);
            $('#chrng_tuilist').html(data.tui);
            $('#chrng_misclist').html(data.misc);
            $('#chrng_othlist').html(data.oth);
          }
        });
      }

      $(document).on('click','#cashiersetup', function(){
        chrngsetup_load();
        $('#modal-cashier').modal('show');
      });

      $(document).on('click', '.addclass', function(){
        $('#groupname').text($(this).text());
        $('#groupname').attr('data-id', $(this).attr('data-id'));
        $('#modal-cashier-addclass').modal('show');
      });

      $(document).on('click', '#btnchrngaddclass', function(){
        var classid = $('#classid').val();
        var itemized = $('#chrngaddclassitemized').prop('checked');
        var groupname = $('#groupname').attr('data-id');

        $.ajax({
          url:"{{route('chrng_addclass')}}",
          method:'GET',
          data:{
            classid:classid,
            itemized:itemized,
            groupname:groupname
          },
          dataType:'',
          complete:function(data)
          {
            chrngsetup_load();

            Swal.fire(
              'Success!',
              'SAVED',
              'success'
            );
          }
        });
      });

      function paysched_loaddetails(studid, order)
      {
        var syid = $('#cbosy').val();
        var semid = $('#cbosem').val();

        $.ajax({
          url:"{{route('paysched_loaddetails')}}",
          method:'GET',
          data:{
            studid:studid,
            order:order,
            syid:syid,
            semid:semid
          },
          dataType:'json',
          success:function(data)
          {
            $('#paysched-list').html(data.list);
            $('.paysched_total-amount').text(data.totalamount)
            $('.paysched_total-paid').text(data.totalpaid)
            $('.paysched_total-balance').text(data.totalbalance)
          }
        });
      }

      $(document).on('select2:close', '#paysched_studid', function(){
        var studid = $('#paysched_studid').val();
        paysched_loaddetails(studid, 'id');
      });

      $(document).on('click', '#paysched-list tr', function(){
        var dataid = $(this).attr('data-id');

        $('#paysched-edit_save').attr('data-id', dataid);

        $.ajax({
          url: '{{route('paysched_edit')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            dataid:dataid
          },
          success:function(data)
          {
            $('#paysched-edit_classid').html(data.classlist);
            $('#paysched-edit_particulars').val(data.particulars);
            $('#paysched-edit_duedate').val(data.duedate);
            $('#paysched-edit_amount').val(data.amount)
            $('#paysched-edit_paid').val(data.paid);
            $('#paysched-edit_balance').val(data.balance);

            $('#paysched_edit-amount').focus();
            $('#paysched_edit-amount').focusout();
            $('#paysched-edit_amount').trigger('change');
            $('#paysched-edit_paid').trigger('change');
            $('#paysched-edit_balance').trigger('change');



            $('#modal-paysched-edit').modal('show');
          }
        }) 
      });

      $(document).on('click', '#paysched-edit_save', function(){
        var dataid = $(this).attr('data-id');
        var classid = $('#paysched-edit_classid').val();
        var particulars = $('#paysched-edit_particulars').val();
        var duedate = $('#paysched-edit_duedate').val();
        var amount = $('#paysched-edit_amount').val();
        var paid = $('#paysched-edit_paid').val();
        var balance = $('#paysched-edit_balance').val();
        var studid = $('#selstud').val();

        $.ajax({
          url: '{{route('paysched_update')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            studid:studid,
            dataid:dataid,
            classid:classid,
            particulars:particulars,
            duedate:duedate,
            amount:amount,
            paid:paid,
            balance:balance,
          },
          success:function(data)
          {
            setTimeout(function(){
              paysched_loaddetails('id');
            }, 1000);
              

            $('#modal-paysched-edit').modal('hide');
          }
        }) 
      });

      $(document).on('click', '#pschedule', function(){
        $('#modal-paysched').modal();
      });

      $(document).on('click', '#fwdvpbf', function(){
        $('.balfwd-page').hide();
        $('#balfwd_vpbf').show();
      });

      $(document).on('click', '#vpbf_gen', function(){
        var levelid = $('#vpbf_glevel').val();

        $.ajax({
          url: '{{route('fwd_vpbf_generate')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            levelid:levelid
          },
          success:function(data)
          {
            $('#vpbflist').html(data.list);
          }
        })

      });

      $(document).on('click', '#ltid', function(){
        $('#ltidmain').show();
        $('#tlfmain').hide();
      });

      $(document).on('click', '#ltid_gen', function(){
        $.ajax({
          url: '{{route('ltid_generate')}}',
          type: 'GET',
          dataType: 'json',
          data: {

          },
          success:function(data)
          {
            $('#ltidlist').html(data.list);
            ltidcount = $('#ltidlist tr').length;
            $('#ltid_count').text(ltidcount);
          }
        })
      });

      $(document).on('mouseenter', '#ltidlist tr', function(){
        $(this).addClass('bg-primary');
      });

      $(document).on('mouseout', '#ltidlist tr', function(){
        $(this).removeClass('bg-primary');
      });

      $(document).on('click', '#ltidlist tr', function(){
        dataid = $(this).attr('data-id');
        transid = $(this).attr('data-trans');
        console.log('dataid: ' + dataid);
        console.log('transid: ' + transid);
        $.ajax({
          url: '{{route('ltid_copytransid')}}',
          type: 'GET',
          dataType: '',
          data: {
            dataid:dataid,
            transid:transid
          },
          success:function(data)
          {
            $('#ltidlist tr').each(function(){
              if($(this).attr('data-id') == dataid)
              {
                $(this).remove();
              }
            });
            ltidcount = $('#ltidlist tr').length;
            $('#ltid_count').text(ltidcount);
          }
        });
      });

      $(document).on('click', '#paysched_sort_due', function(){
        var studid = $('#selstud').val();
        paysched_loaddetails(studid, 'duedate');
      });

      $(document).on('click', '#paysched_sort_id', function(){
        var studid = $('#selstud').val();
        paysched_loaddetails(studid, 'id');
      });

      $(document).on('click', '#transdetail_fix', function(){
        $('#transdetailmain').show();
        $('#ltidmain').hide();
        $('#tlfmain').hide();
      })

      $(document).on('click', '#ftd_generate', function(){
        var ornum = $('#ftd_searchornum').val();

        $('#modal-overlay2').modal('show');

        $.ajax({
          url: '{{route('ftd_generate')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            ornum:ornum
          },
          success:function(data)
          {
            $('#ftd_cashiertransbody').html(data.list);
            $('#modal-overlay2').modal('hide');
          }
        });
      });

      $(document).on('click', '#ftd_cashiertransbody tr', function(){
        $('.trans-item').removeClass('bg-primary');
        $(this).addClass('bg-primary');

        var transid = $(this).attr('data-id');

        $('#ftd_transid').attr('data-id', transid);

        $.ajax({
          url: '{{route('ftd_trans')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            transid:transid
          },
          success:function(data)
          {
            $('#ftd_tbunkerbody').html(data.list);
            ftd_cashiertdetail(transid)
          }
        });
      });

      function ftd_cashiertdetail(transid)
      {
        $.ajax({
          url: '{{route('ftd_cashiertdetail')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            transid:transid
          },
          success:function(data)
          {
            $('#ftd_detailbody').html(data.list);
          }
        });
      }

      $(document).on('click', '#ftd_tbunkerbody tr', function(){
        var bunkerid = $(this).attr('data-id');
        var transid = $('#ftd_transid').attr('data-id');

        $(this).prop('disabled', true);

        $.ajax({
          url: '{{route('ftd_bunkertotd')}}',
          type: 'GET',
          dataType: '',
          data: {
            bunkerid:bunkerid,
            transid:transid
          },
          success:function(data)
          {
            $('#ftd_tbunkerbody').find('data-id');
            ftd_cashiertdetail(transid);
          }
        });
      });

      $(document).on('click', '#ftd_detailbody tr', function(){
        var detailid = $(this).attr('data-id');

        $.ajax({
          url: '{{route('ftd_cashiertdetail_edit')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            detailid:detailid
          },
          success:function(data)
          {
            $('#ftd_detail-edit_save').attr('data-id', data.detailid);
            $('#modal-ftd-detailedit').modal('show');
            $('#detail-edit_amount').val(data.amount);
          }
        });
      })

      $(document).on('click', '#ftd_detail-edit_save', function(){
        var detailid = $(this).attr('data-id');
        var amount = $('#detail-edit_amount').val();
        var transid = $('#ftd_transid').attr('data-id');

        $.ajax({
          url: '{{route('ftd_cashiertdetail_update')}}',
          type: 'GET',
          dataType: '',
          data: {
            detailid:detailid,
            amount:amount
          },
          success:function(data)
          {
            ftd_cashiertdetail(transid); 
          }
        });
        

      });

      $(document).on('click', '#tvl_btnresetpaysched', function(){
        var studid = $('#selstud').val();

        $.ajax({
          url: '{{route('tvl_resetpaysched')}}',
          type: 'GET',
          dataType: '',
          data: {
            studid:studid
          },
          success:function(data)
          {
            genpayinfo();
            paysched_loaddetails(studid, 'id');
          }
        })
      });

      $(document).on('click', '#btnreset_v3', function(){
        var studid = $('#selstud').val();
        var feesid = $('#fees').val();
        var syid = $('#cbosy').val();
        var semid = $('#cbosem').val();

        Swal.fire({
          title: 'Reset Payment Account?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, reset it!'
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
                genpayinfo();
                paysched_loaddetails(studid, 'id');
                Swal.fire(
                  'Success!',
                  'Acount has been resetted',
                  'success'
                );
              }
            });      
          }
        })
      });

      $(document).on('click', '#be_setup', function(){
        $('#divbesetup').show();
        $('#divbelist').hide();
      })

      $(document).on('click', '#be_entries', function(){
        $('#divbesetup').hide();
        $('#divbelist').show();
      })

      $(document).on('click', '#besetup_save', function(){
        var classid = $('#beclass').val();
        var itemid = $('#beitem').val();
        var mopid = $('#bemop').val();

        $.ajax({
          url: '{{route('besetup_save')}}',
          type: 'GET',
          data: {
            classid:classid,
            itemid:itemid,
            mopid:mopid
          },
          success:function(data)
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
              title: 'Saved'
            })
          }
        })
        
      });

      $(document).on('click', '#trxitemized_generatetrx', function(){
        var syid = $('#trxitemized_sy').val();
        var semid = $('#trxitemized_sem').val();
        var daterange = $('#trxitemized_daterange').val();

        $.ajax({
          url: '{{route('trxitemized_generatetrx')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            syid:syid,
            semid:semid,
            daterange:daterange
          },
          success:function(data)
          {
            $('#trxitemized_trxlist').html(data.list);
          }
        });
      });

      $(document).on('click', '#trxitemized_trxlist tr', function(){
        var transid = $(this).attr('data-id');
        var syid = $('#trxitemized_sy').val();
        var semid = $('#trxitemized_sem').val();

        $.ajax({
          url: '{{route('trxitemized_generatetrxitemized')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            transid:transid,
            syid:syid,
            semid:semid
          },
          success:function(data)
          {
            $('#trxitemized_trxitemizedlist').html(data.list);
            $('#trxitemized_savetrxitems').attr('data-list', data.trxitems);
          }
        })
      })

      $(document).on('click', '#trxitemized_savetrxitems', function(){
        $('#trxitemized_trxitemizedlist tr').each(function(){
          var transid = $(this).attr('data-transid');
          var ornum = $(this).attr('data-ornum');
          var itemid = $(this).attr('data-itemid');
          var classid = $(this).attr('data-classid');
          var amount = $(this).attr('data-amount');
          var studid = $(this).attr('data-studid');
          var syid = $(this).attr('data-syid');
          var semid = $(this).attr('data-semid');

          $.ajax({
            url: '{{route('trxitemized_savetrxitems')}}',
            type: 'GET',
            data: {
              transid:transid,
              ornum:ornum,
              itemid:itemid,
              classid:classid,
              amount:amount,
              studid:studid,
              syid:syid,
              semid:semid
            },
            success:function(data)
            {
              $('#trxitemized_trxitemizedlist tr [data-itemid='+itemid+']').addClass('bg-success');
            }
          });
        })
      });

      $(document).on('click', '#trxitemized', function(){
        $('#modal-trxitemized').modal('show');
      })

    });
  </script>
@endsection
