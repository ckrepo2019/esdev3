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
            <li class="breadcrumb-item active">Fees and Collection</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content pt-0">
  	<div class="main-card card">
  		<div class="card-header bg-info">
        <div class="row">
          <div class="text-lg col-md-4">
            <!-- Fees and Collection     -->
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            <b>FEES AND COLLECTION</b></h4>
          </div>
          <div class="col-md-4"></div>
          <div class="col-md-4">
                  
          </div>  
        </div>
        <div class="row">
          <div class="col-md-4">
            
          </div>
          <div class="col-md-2">
            <div class="form-group mb-3">
              <select id="cbosy" class="form-control searchcontrol" data-toggle="tooltip" title="School Year">
                @foreach(App\FinanceModel::getSY() as $sy)
                  @if($sy->isactive == 1)
                    <option value="{{$sy->id}}" selected="">{{$sy->sydesc}}</option>
                  @else
                    <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group mb-3">
              <select id="cbosem" class="form-control searchcontrol" data-toggle="tooltip" title="Semester">
                @foreach(App\FinanceModel::getsem() as $sem)
                  @if($sem->isactive == 1)
                    <option value="{{$sem->id}}" selected="">{{$sem->semester}}</option>
                  @else
                    <option value="{{$sem->id}}">{{$sem->semester}}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="input-group mb-3">
              {{-- <input id="txtsearch" type="text" class="form-control" placeholder="Search" onkeyup="this.value = this.value.toUpperCase();"> --}}
              <select id="cboglevel" class="form-control searchcontrol" data-toggle="tooltip" title="Grade Level">
                <option value="">Grade Level</option>
                @foreach(App\FinanceModel::loadGlevel() as $glevel)
                  <option value="{{$glevel->id}}">{{$glevel->levelname}}</option>
                @endforeach
              </select>
              <div class="input-group-append">
                @if(DB::table('schoolinfo')->first()->lockfees == 0)
                  <button class="btn btn-primary" id="btnitem-new" {{-- onclick="window.location= '{!! route('feesnew')!!}'" --}} data-toggle="tooltip" title="New">New</button>
                @else
                  <button class="btn btn-primary" id="btnitem-new" {{-- onclick="window.location= '{!! route('feesnew')!!}'" --}} data-toggle="tooltip" title="New" disabled="">New</button>
                @endif
              </div>
              <div class="input-group-append">
                @if(DB::table('schoolinfo')->first()->lockfees == 0)
                  <button class="btn btn-warning" id="btndupAll" data-toggle="tooltip" title="Duplicate Entire Setup">Duplicate</button>
                @else
                  <button class="btn btn-warning" id="btndupAll" data-toggle="tooltip" title="Duplicate Entire Setup" disabled="">Duplicate</button>
                @endif
              </div>
            </div>
          </div>
        </div>
  		</div>
      
  		<div class="card-body table-responsive p-0" style="height:380px">
        <table class="table table-striped">
          <thead class="bg-warning">
            <tr>
              <th>DESCRIPTION</th>
              @if(DB::table('schoolinfo')->first()->paymentplan == 1)
                <th class="">PLAN</th>
              @endif
              <th class="">GRADE LEVEL</th>
              <th class="">SCHOOL YEAR</th>
              <th>SEMESTER</th>
              <th>GRANTEE</th>
              <th>AMOUNT</th>
            </tr>  
          </thead> 
          <tbody id="fees-list" data-lock="{{DB::table('schoolinfo')->first()->lockfees}}">
            
          </tbody>             
        </table>
  		</div>
  	</div>
  </section>
@endsection

@section('modal')
  <div class="modal fade show" id="modal-item-new" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Fees and Collection - New</h4>
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
                  <select class="form-control" id=item-class>
                    <option></option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="class-glid" class="col-sm-2 col-form-label">SL Account</label>
                <div class="col-sm-10">
                  <select class="form-control" id=item-SL>
                    <option></option>
                  </select>
                </div>
              </div>
              
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="saveItem" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-dupAll" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h4 class="modal-title">Duplicate Entire Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              Select School Year
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <select id="dupsyID" class="form-control">
                @foreach(App\FinanceModel::getSY() as $sy)
                  @if($sy->isactive == 1)
                    <option value="{{$sy->id}}" selected="">{{$sy->sydesc}}</option>
                  @else
                    <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="btndup" type="button" class="btn btn-primary" data-dismiss="modal">Duplicate</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-fees" aria-modal="true" style="padding-right: 17px; display: none;" data-backdrop="static">
    <div class="modal-dialog modal-xl mt-0">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">FEES AND COLLECTION - <span id="action"></span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body overflow-auto" style="height: 487px">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="txtnopay">Grade level</label>
                <select class="form-control select2bs4 is-invalid is-val" id="feesglevel">
                  <option></option>
                  @foreach(App\FinanceModel::loadGlevel() as $level)
                    <option value="{{$level->id}}">{{($level->levelname)}}</option>
                  @endforeach
                </select>
              </div>    
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="txtdesc">Description</label>
                <input type="text" class="form-control is-invalid is-val" id="txtdesc" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
              </div>    
            </div>

            <div class="col-md-2 semUI">
              <div class="form-group">
                <label for="txtnopay">Semester</label>
                <select class="form-control" id="feesSem">
                  @foreach(App\FinanceModel::getSem() as $sem)
                    @if($sem->isactive == 1)
                      <option selected="" value="{{$sem->id}}">{{($sem->semester)}}</option>
                    @else
                      <option value="{{$sem->id}}">{{($sem->semester)}}</option>
                    @endif
                  @endforeach
                </select>
              </div>    
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label for="txtnopay">School Year</label>
                <select class="form-control" id="feesSY">
                  @foreach(App\FinanceModel::getSY() as $sy)
                    @if($sy->isactive == 1)
                      <option selected="" value="{{$sy->id}}">{{($sy->sydesc)}}</option>
                    @else
                      <option value="{{$sy->id}}">{{($sy->sydesc)}}</option>
                    @endif
                    
                  @endforeach
                </select>
              </div>    
            </div>
                

            <div class="col-md-2">
              <div class="form-group">
                <label for="">Grantee</label>
                <select class="form-control" id="grantee">
                  <option value="1">REGULAR</option>
                  <option value="2">ESC</option>
                  <option value="3">VOUCHER</option>
                </select>
              </div>    
            </div>
          </div>
          <div class="row strand-ui">
            <div class="col-md-3 mt-1">
              <div class="form-group">
                <label class="">Strand <i>(For Senior High Only)</i></label>
                <select id="strand" class="form-control select2bs4">
                  <option value="0"></option>
                  @foreach(App\FinanceModel::strandlist() as $strand)
                    <option value="{{$strand->id}}">{{$strand->strandcode}}</option>
                  @endforeach
                </select>
              </div>
            </div>           
          </div>

          <div class="row course-ui">
            <div class="col-md-6 mt-1">
              <div class="form-group">
                <label class="">Course</i></label>
                <select id="course" class="form-control select2bs4">
                  <option value="0"></option>
                  @foreach(App\FinanceModel::loadCourses() as $course)
                    <option value="{{$course->id}}" data-value="{{$course->courseabrv}}">{{$course->courseDesc}}</option>
                  @endforeach
                </select>
              </div>
            </div>           
          </div>

          <div class="row">
            <div id="detailui" class="col-md-12">
              <div class="card">
                  <div class="card-header bg-primary">
                    <div class="row">
                      <div class="col-md-6">
                        <span>PAYMENT CLASSIFICATION</span>
                      </div>
                      @if(DB::table('schoolinfo')->first()->paymentplan == 1)
                        <div class="col-md-6 text-right">
                          <span class="text-bold" id="planname">PLAN:</span>
                        </div>
                      @endif
                    </div>
                  </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-8">
                      <button id="viewpaysched" class="btn btn-warning">VIEW PAYMENT SCHEDULE</button>
                      <button id="coladdclass" class="btn btn-primary">ADD CLASSIFICATION</button>
                    </div>
                    @if(DB::table('schoolinfo')->first()->paymentplan == 1)
                      <div class="col-md-4 text-right">
                        <button id="btnpaymentplan" class="btn btn-success">ENTER PAYMENT PLAN</button>
                      </div>
                    @endif
                  </div>
                  <div class="row mt-1">
                    <div class="col-md-12 table-responisve">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <tH>DESCRIPTION</th>
                            <th>MODE OF PAYMENT</th>
                            <th>AMOUNT</th>
                          </tr>
                        </thead>
                        <tbody id="col-classlist" style="cursor: pointer;">
                          
                        </tbody>
                        <tfoot id="col-classlist-foot">
                          
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div id="labfee" class="col-md-5" hidden="">
              <div class="card">
                <div class="card-header bg-success">
                  LABORATORY FEES
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-12">
                      <button class="btn btn-primary">ADD SUBJECT</button>
                    </div>
                  </div>
                  <div class="row mt-1">
                    <div class="col-md-12 table-responisve">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>SUBJECT</th>
                            <th>AMOUNT</th>
                          </tr>
                        </thead>
                        <tbody id="labsubjlist">
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div> 
          </div>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
            </div>
            <div class="col-md-2 dropdown">
              <button class="btn btn-warning btn-block dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a id="headdelete" class="dropdown-item" href="#"><i class="fas fa-trash"></i> Delete</a>
                <a id="headduplicate" class="dropdown-item" href="#"><i class="fas fa-copy"></i> Duplicate</a>
              </div>
            </div>
            <div class="col-md-2">
              <button id="saveFC" type="button" class="btn btn-primary btn-block"><i class="fas fa-save"></i> Save</button>  
            </div>
          </div>
        </div>
        
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-paymentplan" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h4 class="modal-title">PAYMENT PLAN</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 form-group">
              <label>ENTER PAYMENT PLAN</label>
              <input type="text" name="" id="txtpaymentplan" class="form-control" placeholder="REGULAR/WORKING STUDENT/NIGHT CLASS">
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="btnproceed" type="button" class="btn btn-success" data-dismiss="modal">Proceed</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-paysched" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-xl mt-1">
      <div class="modal-content">
        <div id="schedOverlay" class="overlay d-flex justify-content-center align-items-center" style="display: none !important;">
            <i class="fas fa-2x fa-sync fa-spin"></i>
        </div>
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Payment Schedule</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body overflow-auto" style="height: 490px">
          <div id="list-paysched" class="row">
            
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button id="close-paysched" type="button" class="btn btn-default">Close</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-col-class" aria-modal="true" style="padding-right: 17px; display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Payment Classification - <span id="classAction">ADD</span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="col-classification" class="col-sm-4 col-form-label">Classification</label>
                <div class="col-sm-8">
                  <select id="col-classification" class="form-control select2bs4 val-detail is-invalid">
                    
                  </select>
                </div>
                
              </div>

              <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label">Mode of payment</label>
                <div class="col-sm-8">
                  <select id="col-mop" class="form-control val-detail is-invalid">
                    
                  </select>
                </div>
              </div>

              <div class="row mb-2">
                <div class="col-md-4">    
                </div>
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-4">
                      <div id="divTuition" class="icheck-primary d-inline ml-3">
                        <input type="checkbox" id="istuition">
                        <label for="istuition">
                          Per Unit
                        </label>
                      </div>    
                    </div>
                    <div class="col-md-4">
                      <div id="divTuition" class="icheck-primary d-inline ml-3">
                        <input type="checkbox" id="persubj">
                        <label for="persubj">
                          Per Subjects
                        </label>
                      </div>
                    </div>    
                    <div class="col-md-4">
                      <div id="divTuition" class="icheck-primary d-inline ml-3">
                        <input type="checkbox" id="permop">
                        <label for="permop">
                          MOP
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-12 table-responisve overflow-auto" style="height: 243px">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>DESCRIPTION</th>
                        <th>AMOUNT</th>
                      </tr>
                    </thead>
                    <tbody id="col-payclass-list" style="cursor: pointer;">
                      
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="2"><span id="col-add-item" class="text-primary cursor-pointer"><u>Add Item</u></span></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
          </form>
        </div>
        <div class="">
          <div class="row">
            <div class="col-md-8">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
            </div>
            <div class="col-md-2">
              <button id="col-delPayClass" type="button" class="btn btn-danger btn-block" data-id="0">Delete</button>  
            </div>
            <div class="col-md-2">
              <button id="col-savePayClass" type="button" class="btn btn-primary btn-block" data-id="0" data-dismiss="modal">Save</button>  
            </div>
          </div>
        </div>
        {{-- <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="col-savePayClass" type="button" class="btn btn-primary" data-id="0" data-dismiss="modal">Save</button>
        </div> --}}
      </div>
    </div> {{-- dialog --}}
  </div>



  <div class="modal fade show mt-5" id="modal-payitem" aria-modal="true" style="padding-right: 17px; display: none;" data-backdrop="static">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h4 class="modal-title">Items - <span id="col-item-action">ADD</span></h4>
          <button id="modal-payitem_close" type="button" class="close" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body">
            <div class="row">
              <div class="form-group col-md-11">
                <label for="modal-classification" class="col-form-label">Items</label>
                <div class="">
                  <select id="fc-item" class="form-control select2bs4 val-item is-invalid">
                    
                  </select>
                </div>
              </div>
              <div class="form-group col-md-1 mt-1">
                <label for="fc-btnadditem" class="col-form-label">&nbsp;</label>
                <button id="fc-btnadditem" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Add new item">
                  <i class="fas fa-external-link-square-alt"></i>
                </button>
              </div>
            </div>

            <div class="row">
              <div class="form-group col-md-12">
                <label for="modal-classification" class="col-form-label">Amount</label>
                <div class="">
                  <input type="text" class="form-control val-item is-invalid" name="currency-field" id="fc-txtamount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency">
                </div>
              </div> 
            </div>
        
            
          </div>
          <hr>
          <div class="row">
            <div class="col-md-6">
              <button id="close_modal-payitem" type="button" class="btn btn-default">Close</button>  
            </div>
            <div class="col-md-3">
              <button id="fc-deleteItem" type="button" class="btn btn-danger btn-block" data-id=0>Delete</button>  
            </div>
            <div class="col-md-3">
              <button id="fc-appendItem" type="button" class="btn btn-dark btn-block" data-id=0><i class="fas fa-save"></i> Save</button>  
            </div>
          </div>
        </div>
        
        {{-- <div class="modal-footer justify-content-between">
          <button id="close_modal-payitem" type="button" class="btn btn-default">Close</button>
          <button id="fc-appendItem" type="button" class="btn btn-primary" data-id=0>Save</button>
        </div> --}}
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-fc-itemcreate" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md mt-1">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Items - <span>New</span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body">
            <div class="form-group">
              <label for="class-desc" class="col-form-label">Item Code</label>
              <div class="col-sm-12">
                <input type="text" class="form-control validation" id="fc-item-code" placeholder="Item Code" onkeyup="this.value = this.value.toUpperCase();">
              </div>
            </div>
            <div class="form-group">
              <label for="class-desc" class="col-form-label">Description</label>
              <div class="col-sm-12">
                <input type="text" class="form-control validation" id="fc-item-desc" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
              </div>
            </div>
            <div class="form-group">
              <label for="class-glid" class="col-form-label">Classification</label>
              <div class="col-sm-12">
                <select class="form-control select2bs4" id="fc-item-class">
                  <option></option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="class-desc" class="col-form-label">Amount</label>
              <div class="col-sm-12">
                <input type="number" class="form-control validation" id="fc-item-amount" placeholder="0.00">
              </div>
            </div>
            
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button id="close-modal-fc-itemcreate" type="button" class="btn btn-default">Close</button>
          <button id="append-fc-itemcreate" type="button" class="btn btn-primary">Save Item</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-col-class-mopid" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md mt-5">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h4 class="modal-title">Mode of Payment</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body">
            
            <div class="form-group">
              {{-- <label for="class-glid" class="col-form-label">Classification</label> --}}
              <div class="col-sm-12">
                <select class="form-control select2bs4" id="col-class-mopid_mopid">
                  <option value="0"></option>
                  @foreach(db::table('paymentsetup')->where('deleted', 0)->get() as $mop)
                    <option value="{{$mop->id}}">[{{$mop->noofpayment}}] {{$mop->paymentdesc}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button id="col-class-mopid_close" type="button" class="btn btn-default">Close</button>
          {{-- <button id="col-class-mopid_save" type="button" class="btn btn-primary">Save Item</button> --}}
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
  </style>


  <script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      });

    $(document).ready(function(){

      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      shUI($('#glevel').val());
      colUI($('#glevel').val());

      // validation();
      // colValidation();

      searchfees($('#cbosy').val(), $('#cbosem').val(), $('#cboglevel').val());

      function searchfees(sy, sem, levelid)
      {

        // console.log('SY: ' + sy + ' SEM: ' + sem + ' GLEVEL: ' + levelid);
        fees = $('#txtsearch').val();

        $.ajax({
          url:"{{route('searchfees')}}",
          method:'GET',
          data:{
            sy:sy,
            sem:sem,
            levelid:levelid
          },
          dataType:'json',
          success:function(data)
          {
            $('#fees-list').html(data.fees);
          }
        });  

      }

      function validateSY(syid)
      {
        syid = $('#dupsyID').val();
        $.ajax({
          url:"{{route('validatedupSY')}}",
          method:'GET',
          data:{
            syid:syid
          },
          dataType:'',
          success:function(data)
          {
            if(data == 1)
            {
              Swal.fire(
                'Warning',
                'SY has already a setup. Please remove all the Fees and Collection setup of the selected School Year to use the Duplicate Function',
                'warning'
              );  

              $('#btndup').prop('disabled', true);
            }
            else
            {
              $('#btndup').prop('disabled', false); 
            }
          }
        });
      }

      function basicedUI(levelid)
      {
        if(levelid < 14 || levelid == 16)
        {
          $('.semUi').hide();
        }
        else{
          $('.semUi').show();
        }
      }

      function shUI(levelid)
      {
        if(levelid == 14 || levelid == 15)
        {
          $('.strand-ui').show();
        }
        else
        {
          $('.strand-ui').hide();
          // $('.semUI').hide();
          // $('.strand-ui').prop('hidden', true) 
        }
      }

      function colUI(levelid)
      {
        if(levelid >= 17 && levelid <= 20)
        {
          $('.course-ui').show();
          $('.basic-ed').hide();
          $('.college-ed').show();


          // $('#labfee').prop('hidden', false);
          // $('#detailui').removeClass('col-md-12');
          // $('#detailui').addClass('col-md-7');
        }
        else
        {
          $('.course-ui').hide(); 
          $('.basic-ed').show();
          $('.college-ed').hide();
          $('#labfee').prop('hidden', true);
          $('#detailui').addClass('col-md-12');
          $('#detailui').removeClass('col-md-7');
        }
      }

      function semDisplay(levelid)
      {
        if(levelid == 14 || levelid == 15)
        {
          $('.semUI').show();
        }
        else if(levelid >= 17 && levelid <= 21)
        {
          $('.semUI').show();
        }
        else{
          $('.semUI').hide();
        }
      }

      function validation()
      {

        if($('#txtdesc').val() != '' && $('#feesglevel').val() != '')
        {
          $('#addClassification').prop('disabled', false);
        }
        else
        {
          $('#addClassification').prop('disabled', true);  
        }

        if($('#feesglevel').val() == 14 || $('#feesglevel').val() == 15)
        {
          $('#feesSem').prop('disabled', false);
        }
        else if($('#feesglevel').val() >= 17 && $('#feesglevel').val() <= 20)
        {
          $('#feesSem').prop('disabled', false); 
        }
        else
        {
          // $('#feesSem').prop('disabled', true); 
          // $('#feesSem').val('');
        }

        validateClass();
        
      }

      function validateClass()
      {
        var vCount = 0;
        $('.is-val').each(function(){
          if($(this).hasClass('is-invalid'))
          {
            vCount += 1;  
          }
          
        });
        

        if(vCount > 0)
        {
          $('#coladdclass').prop('disabled', true);
          $('#saveFC').prop('disabled', true);
        }
        else
        {
          $('#coladdclass').prop('disabled', false); 
          $('#saveFC').prop('disabled', false);
        }
      }

      function validateDetail()
      {
        var valCount = 0;
        $('.val-detail').each(function(){
          if($(this).hasClass('is-invalid'))
          {
            valCount += 1;
          }

          if(valCount > 0)
          {
            $('#col-savePayClass').prop('disabled', true);
          }
          else
          {
            $('#col-savePayClass').prop('disabled', false);
          }
        });
      }

      function validateItem()
      {
        var vCount = 0;
        $('.val-item').each(function(){

          if($(this).hasClass('is-invalid'))
          {
            vCount += 1;
          }

          if(vCount > 0)
          {
            $('#fc-appendItem').prop('disabled', true);
          }
          else
          {
            $('#fc-appendItem').prop('disabled', false); 
          }
        });
      }

      function colValidation(classification)
      {
        // console.log($('#col-classification').val())

        if($('#col-classification').val() == '' || $('#col-classification').val() == null)
        {
          $('#col-add-item').removeClass('cursor-pointer text-primary');
        }
        else
        {
          $('#col-add-item').addClass('cursor-pointer text-primary');
        }
      }

      function FCHeadInfo(headid)
      {
        $.ajax({
          url:"{{route('FCHeadInfo')}}",
          method:'GET',
          data:{
            headid:headid
          },
          dataType:'json',
          success:function(data)
          {
            $('#feesglevel').val(data.levelid);
            $('#feesglevel').trigger('change');

            $('#strand').val(data.strandid);
            $('#strand').trigger('change');

            $('#course').val(data.courseid);
            $('#course').trigger('change');

            $('#txtdesc').val(data.desc);
            $('#txtdesc').trigger('keyup');
            $('#feesSem').val(data.semid);
            $('#feesSY').val(data.syid);
            $('#grantee').val(data.grantee);
            $('#saveFC').attr('data-id', headid);

            if(data.paymentplan != '') 
            {
              $('#txtpaymentplan').val(data.paymentplan);
              $('#planname').text('PLAN: ' + data.paymentplan);
            }
            else
            {
              $('#txtpaymentplan').val(''); 
              $('#planname').text('PLAN: ');
              // console.log('blank');
            }
            
            validateClass();
          }
        }); 
      }

      function FCClasList(headid)
      {
        $.ajax({
          url:"{{route('FCClasList')}}",
          method:'GET',
          data:{
            headid:headid
          },
          dataType:'json',
          success:function(data)
          {
            $('#col-classlist').html(data.list)
            $('#col-classlist-foot').html(data.listfoot)

            $('#modal-fees').modal({backdrop: 'static', keyboard: false});
          }
        });
      }

      function FCItemList(detailid)
      {
        $.ajax({
          url:"{{route('FCItemList')}}",
          method:'GET',
          data:{
            detailid:detailid
          },
          dataType:'json',
          success:function(data)
          {
            $('#col-payclass-list') .html(data.items);
          }
        }); 
      }

      function loadreceivables(element)
      {
        $.ajax({
          url:"{{route('loadreceivables')}}",
          method:'GET',
          data:{
            
          },
          dataType:'json',
          success:function(data)
          {
            console.log(data.list);
            element.html(data.list);
          }
        });
      }

      $(document).on('change', '#course', function(){
        var course = $('option:selected', this).attr('data-value');
        var levelid = $('#feesglevel').val();

        
        if(levelid >= 17 && levelid <= 20)
        {
          $('#txtdesc').val(course);
          $('#txtdesc').trigger('keyup');
        }
      });

      $(document).on('keyup', '#txtdesc', function(){
        
        if($(this).val() == '')
        {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid');
        }
        else
        {
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid'); 
        }

        validation();
        
      });

      $(document).on('change', '#feesglevel', function(){
        basicedUI($(this).val());
        shUI($(this).val());
        colUI($(this).val());
        semDisplay($(this).val());
        
        if($(this).val() == '')
        {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid');
        }
        else
        {
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid'); 
        }

        if($(this).val() >= 17 && $(this).val() <= 20)
        {
          $('#divTuition').removeClass('Div-hide')
        }
        else
        {
          $('#divTuition').addClass('Div-hide')
        }



        validation();

      });



      $(document).on('keyup', '#txtsearch', function(){
        var fees = $(this).val();

        searchfees(fees);
      });

      $(document).on('click', '#headdelete', function(){
        var headerid = $('#saveFC').attr('data-id');
        console.log(headerid);
        Swal.fire({
          title: 'Are you sure?',
          text: "Enter password to delete.",
          type: 'warning',
          input: 'password',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Delete'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url:"{{route('feesdelete')}}",
              method:'GET',
              data:{
                headerid:headerid,
                pword:result.value
              },
              dataType:'',
              success:function(data)
              {
                if(data == 1)
                {
                  searchfees($('#cbosy').val(), $('#cbosem').val(), $('#cboglevel').val());
                  $('#modal-fees').modal('hide');
                  Swal.fire(
                    'Deleted!',
                    'Item has been deleted.',
                    'success'
                  );
                }
                else
                {
                  Swal.fire(
                    'Error!',
                    'Invalid password.',
                    'error'
                  ); 
                }
              }
            }); 
          }
        });
      });


      

      $(document).on('click', '#headduplicate', function(){
        
        Swal.fire({
          title: 'Duplicate ' + $('#txtdesc').val() + ' ?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes!'
        }).then((result) => {
          if (result.value) {

            var headerid = $('#saveFC').attr('data-id');
            $.ajax({
              url:"{{route('duplicateFC')}}",
              method:'GET',
              data:{
                headerid:headerid
              },
              dataType:'',
              success:function(data)
              {

                FCHeadInfo(data);
                FCClasList(data);

                setTimeout(function(){
                  var description =  $('#txtdesc').val();
                  $('#txtdesc').val(description);
                }, 2000);


                Swal.fire(
                  'Success!',
                  'Item has been duplicated.',
                  'success'
                );
                
              }
            }); 
          }
        });
      });

      $(document).on('change', '.searchcontrol', function(){
        searchfees($('#cbosy').val(), $('#cbosem').val(), $('#cboglevel').val());
      });

      $(document).on('click', '#btndupAll', function(){
        $('#modal-dupAll').modal('show');
        validateSY($('#dupsyID').val());
      });

      $(document).on('change', '#dupsyID', function(){
        validateSY($('#dupsyID').val());
      });

      $(document).on('click', '#btndup', function(){
        var cursy = $('#cbosy').val();
        var syid = $('#dupsyID').val();


        $.ajax({
          url:"{{route('duplicateAll')}}",
          method:'GET',
          data:{
            cursy:cursy,
            syid:syid
          },
          dataType:'',
          success:function(data)
          {
            Swal.fire(
              'Success!',
              'Fees and Collection has been duplicated.',
              'success'
            );
            // window.location.href = '/finance/feesedit/' + data;
          }
        }); 

      });


      //--------------------------Redesign---------------------------------//

      $(document).on('mouseover', '#fees-list tr', function(){
        $(this).addClass('bg-info');
      });

      $(document).on('mouseout', '#fees-list tr', function(){
        $(this).removeClass('bg-info');
      });

      $(document).on('click', '#fees-list tr', function(){
        var headid = $(this).attr('data-id');
        // $('#modal-fees').modal('show');
        if($('#fees-list').attr('data-lock') == 0)
        {
          $('#action').text('EDIT');
          $('#saveFC').attr('data-id', headid);

          FCHeadInfo(headid);
          FCClasList(headid);
        }
        else
        {
          Swal.fire({
            type: 'error',
            title: 'This feature is disabled.',
            text: 'Please contact CK Publishing.',
            //footer: '<a href>Why do I have this issue?</a>'
          })
        }
        
        
      });

      $(document).on('click', '#btnitem-new', function(){
        $('#modal-fees').modal('show');
        $('#action').text('NEW');
        $('#feesglevel').val('');
        $('#feesglevel').trigger('change');
        $('#txtdesc').val('');
        $('#txtdesc').trigger('keyup');
        $('#course').val('');
        $('#course').trigger('change');

        $('#saveFC').attr('data-id', 0);

        $('#col-classlist').empty();
        $('#col-classlist-foot').empty();

        validateClass();
      });

      $(document).on('click', '#coladdclass', function(){
        $('#classAction').text('ADD');
        $('#istuition').prop('checked', false);
        $('#col-savePayClass').attr('data-id', 0);

        $('#col-classification').empty();
        $('#col-mop').empty();
        $('.val-detail').trigger('change');
        $('#col-payclass-list').empty();

        $('#col-delPayClass').prop('disabled', true);

        $('#col-classification').append('<option value=""></option>')
        @foreach(App\FinanceModel::loadItemClass() as $class)
          $('#col-classification').append('<option value="{{$class->id}}">{{$class->description}}</option>')
        @endforeach

        $('#col-mop').append('<option value=""></option>')
        @foreach(App\FinanceModel::loadMOP() as $mop)
          $('#col-mop').append('<option value="{{$mop->id}}">{{$mop->paymentdesc}}</option>')
        @endforeach

        $('#modal-col-class').modal('show');
        $('#col-savePayClass').attr('data-id', 0);
        
        validateDetail();

      });

      $(document).on('change', '.val-detail', function(){
        
        if($(this).val() == null || $(this).val() == 0)
        {
          $(this).addClass('is-invalid');
          $(this).removeClass('is-valid'); 
        }
        else
        {
          $(this).removeClass('is-invalid');
          $(this).addClass('is-valid');
        }

        validateDetail();
        colValidation();
      });

      // $(document).on('click', 'col-add-item', function(){
      //   $('#modal-payitem').modal('show');
      // });

      $(document).on('click', '#col-add-item', function(){
        if($('#col-classification').val() != '' && $('#col-classification').val() != null)
        {
          $('#col-item-action').text('ADD');
          $('#fc-appendItem').attr('data-id', 0);
          $('#modal-payitem').modal('show');
          $('#fc-txtamount').val('');
          $('#fc-item').empty();
          $('#fc-item').append('<option></option>');

          $('#fc-deleteItem').prop('disabled', true);

          @foreach(App\FinanceModel::receivableitems() as $receivable)
            $('#fc-item').append('<option value="{{$receivable->id}}">{{$receivable->description}}</option>');
          @endforeach
          $('.val-item').trigger('change');
          validateItem();
        }
      });

      $(document).on('click', '#close_modal-payitem', function(){
        $('#modal-payitem').modal('hide');
      });

      $(document).on('click', '#fc-appendItem', function(){
        var desc = $('#txtdesc').val();
        var levelid = $('#cboglevel').val();
        var semid = $('#feesSem').val();
        var syid = $('#feesSY').val();
        var grantee = $('#grantee').val();
        var courseid = $('#course').val();
        var headid = $('#saveFC').attr('data-id');
        var appendAct = $(this).attr('data-id');

        var detailid = $('#col-savePayClass').attr('data-id');
        var classid = $('#col-classification').val();
        var mopid = $('#col-mop').val();

        var itemid = $('#fc-item').val();
        var itemamount = $('#fc-txtamount').val().replace(',', '');

        
        
        if($('#istuition').prop('checked') == true)
        {
          var istuition = 1;
        }
        else
        {
          var istuition = 0;
        }

        var _route;

        var vCount = 0;

        $('.val-item').each(function(){
          if($(this).hasClass('is-invalid'))
          {
            vCount += 1;
          }

        });
        
        if(vCount == 0)
        {
          if(appendAct == 0)
          {
            $.ajax({
              url:"{{route('appendcolFC')}}",
              method:'GET',
              data:{
                desc:desc,
                levelid:levelid,
                semid:semid,
                syid:syid,
                grantee:grantee,
                courseid:courseid,
                headid:headid,
                detailid:detailid,
                classid:classid,
                mopid:mopid,
                itemid:itemid,
                itemamount:itemamount,
                istuition:istuition,
              },
              dataType:'json',
              beforesend: function(){
                $('#fc-appendItem').prop('disabled', true);
              },
              success:function(data)
              {
                // console.log(data.itemlist);
                // var detailid = $('#col-savePayClass').attr('data-id');
                console.log(data.detailid);
                FCItemList(data.detailid);
                $('#fc-appendItem').prop('disabled', false);
                $('#modal-payitem').modal('hide');

                $('#saveFC').attr('data-id', data.headid);
                $('#col-savePayClass').attr('data-id', data.detailid);
                // $('#col-payclass-list').html(data.itemlist);


              }
            });
          } 
          else
          {
            var itemid = $('#fc-item').val();
            var amount = $('#fc-txtamount').val();
            var datailid = $('#col-savePayClass').attr('data-id');

            $.ajax({
              url:"{{route('updatecolFCitem')}}",
              method:'GET',
              data:{
                itemid:itemid,
                amount:amount,
                appendAct:appendAct,
                datailid:datailid
              },
              dataType:'json',
              beforesend: function(){
                $('#fc-appendItem').prop('disabled', true);
              },
              success:function(data)
              {
                console.log(detailid);
                FCItemList(detailid);
                $('#col-payclass-list').html(data.items);
                $('#modal-payitem').modal('hide');
              }
            });
          }
        }
        else
        {
          Swal.fire({
            position: 'top',
            type: 'error',
            title: 'Please fill all the required fields',
            showConfirmButton: true,
            timer: 0
          })
        }
      });

      $(document).on('change', '.val-item', function(){
        if($(this).val() == '' || $(this).val() == null || $(this).val() == 0)
        {
          $(this).addClass('is-invalid');
          $(this).removeClass('is-valid');
        }
        else
        {
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid');
        }

        validateItem();
      });

      $(document).on('keyup', '#fc-txtamount', function(){
        if($(this).val() == '' || $(this).val() == null || $(this).val() == 0)
        {
          $(this).addClass('is-invalid');
          $(this).removeClass('is-valid');
        }
        else
        {
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid');
        }

        validateItem();
      });

      $(document).on('click', '#col-savePayClass', function(){
        var headid = $('#saveFC').attr('data-id');
        var detailid = $(this).attr('data-id');
        var classid = $('#col-classification').val();
        var mopid = $('#col-mop').val();
        var classmopid_mopid = $('#col-class-mopid_mopid').val();

        if($('#istuition').prop('checked') == true)
        {
          var istuition = 1;
        }
        else
        {
          var istuition = 0;
        }

        if($('#persubj').prop('checked') == true)
        {
          var persubj = 1;
        }
        else
        {
          var persubj = 0;
        }

        if($('#permop').prop('checked') == true)
        {
          var permop = 1;
        }
        else
        {
          var permop = 0;
        }

        var vCount = 0;

        $('.val-detail').each(function(){
          if($(this).hasClass('is-invalid'))
          {
            vCount += 1;
          }

        });

        if(vCount == 0)
        {
          $.ajax({
            url:"{{route('appendcolFCdetail')}}",
            method:'GET',
            data:{
              headid:headid,
              detailid:detailid,
              classid:classid,
              mopid:mopid,
              istuition:istuition,
              permop:permop,
              persubj:persubj,
              classmopid_mopid:classmopid_mopid
            },
            dataType:'json',
            success:function(data)
            {
              $('#col-classlist').html(data.list);
              $('#col-classlist-foot').html(data.listfoot);
              $(this).attr('data-id', 0);
            }
          }); 
        }
        else
        {
          Swal.fire({
            position: 'top',
            type: 'error',
            title: 'Please fill all the required fields',
            showConfirmButton: true,
            timer: 0
          });
        }
      });

      $(document).on('click', '#saveFC', function(){
        var headerid = $(this).attr('data-id');
        var desc = $('#txtdesc').val();
        var levelid = $('#feesglevel').val();
        var semid = $('#feesSem').val();
        var classID = $('#modal-classification').val();
        var mopid = $('#modal-mop').val();
        var syid = $('#feesSY').val();
        var grantee = $('#grantee').val();
        var esc = '';
        var strandid = $('#strand').val();
        var courseid = $('#course').val();
        var paymentplan = $('#txtpaymentplan').val();


        var vCount = 0;
        $('.is-val').each(function(){
          if($(this).hasClass('is-invalid'))
          {
            vCount += 1;  
          }
          
        });
        

        if(vCount > 0)
        {
          $('#coladdclass').prop('disabled', true);
          $('#saveFC').prop('disabled', true);
        }
        else
        {
          $('#coladdclass').prop('disabled', false); 
          $('#saveFC').prop('disabled', false);
        }
        
        if(vCount == 0)
        {
          $.ajax({
            url:"{{route('saveFC')}}",
            method:'GET',
            data:{
              headerid:headerid,
              desc:desc,
              levelid:levelid,
              syid:syid,
              semid:semid,
              grantee:grantee,
              strandid:strandid,
              courseid:courseid,
              paymentplan:paymentplan
            },
            dataType:'',
            success:function(data)
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
                title: 'Fees and Collection successfully saved.'
              })

              $('#modal-fees').modal('hide');
              searchfees($('#cbosy').val(), $('#cbosem').val(), $('#cboglevel').val());
            }
          });
        }
        else
        {
          Swal.fire({
            position: 'top',
            type: 'error',
            title: 'Please fill all the required fields',
            showConfirmButton: true,
            timer: 0
          });
        }
      });

      $(document).on('mouseover', '#col-classlist tr', function(){
        $(this).addClass('bg-primary');
      });

      $(document).on('mouseout', '#col-classlist tr', function(){
        $(this).removeClass('bg-primary');
      });

      $(document).on('click', '#col-classlist tr', function(){
        $('#col-classification').empty();
        $('#col-mop').empty();
        $('#col-payclass-list').empty();
        $('#classAction').text('EDIT');

        $('#col-classification').append('<option value=""></option>')
        @foreach(App\FinanceModel::loadItemClass() as $class)
          $('#col-classification').append('<option value="{{$class->id}}">{{$class->description}}</option>')
        @endforeach

        $('#col-mop').append('<option value=""></option>')
        @foreach(App\FinanceModel::loadMOP() as $mop)

          $('#col-mop').append('<option value="{{$mop->id}}">{{$mop->paymentdesc}}</option>')
        @endforeach

        $('#modal-col-class').modal('show');
        var detailid = $(this).attr('data-id');

        $('#col-savePayClass').attr('data-id', detailid);

        $.ajax({
          url:"{{route('editcolFCdetail')}}",
          method:'GET',
          data:{
            detailid:detailid,
          },
          dataType:'json',
          success:function(data)
          {
            $('#col-classification').val(data.classid);
            $('#col-classification').trigger('change');

            $('#col-mop').val(data.mopid);
            $('#col-mop').trigger('change');

            if(data.istuition == 1)
            {
              $('#istuition').prop('checked', true)
            }
            else
            {
              $('#istuition').prop('checked', false)
            }

            if(data.persubj == 1)
            {
              $('#persubj').prop('checked', true);
            }
            else
            {
              $('#persubj').prop('checked', false);
            }

            if(data.permop == 1)
            {
              $('#permop').prop('checked', true);
            }
            else
            {
              $('#permop').prop('checked', false);
            }

            $('#col-class-mopid_mopid').val(data.permopid);
            $('#col-class-mopid_mopid').trigger('change');

            $('#col-payclass-list').html(data.items);

          }
        }); 
      });

      $(document).on('click', '#close-modal-fc-itemcreate', function(){
        $('#modal-fc-itemcreate').modal('hide');
      });

      $(document).on('click', '#close_modal-payitem', function(){
        $('#modal-payitem').modal('hide');
      });

      $(document).on('mouseover', '.col-item-list', function(){
        $(this).addClass('bg-info');
      });

      $(document).on('mouseout', '.col-item-list', function(){
        $(this).removeClass('bg-info');
      });

      $(document).on('click', '.col-item-list', function(){
        // $('#fc-item').empty();
        $('#col-item-action').text('EDIT');
        $('#fc-deleteItem').prop('disabled', false);


        
        $('#fc-item').trigger('change');
        $('#fc-txtamount').trigger('keyup')

        

        var itemid = $(this).attr('data-id');

        $('#fc-appendItem').attr('data-id', itemid);
        loadreceivables($('#fc-item'));
        setTimeout(function(){
          $('#modal-payitem').modal('show');
        }, 500)
          

        $.ajax({
          url:"{{route('editcolFCitem')}}",
          method:'GET',
          data:{
            itemid:itemid
          },
          dataType:'json',
          success:function(data)
          { 
            
            setTimeout(function(){
              $('#fc-item').val(data.itemid);
              $("#fc-txtamount").val(data.amount);
              $('.val-item').trigger('change');
            }, 100)
            
          }
        }); 

      });

      $(document).on('click', '#fc-deleteItem', function(){
        var itemid = $('#fc-appendItem').attr('data-id');
        Swal.fire({
          title: 'Are you sure?',
          text: "Enter password to delete.",
          type: 'warning',
          input: 'password',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Delete'
        }).then((result) => {
          if (result.value) {
            
            $.ajax({
              url:"{{route('deletecolFCitem')}}",
              method:'GET',
              data:{
                itemid:itemid,
                pword:result.value
              },
              dataType:'',
              success:function(data)
              {
                if(data == 1)
                {
                  Swal.fire(
                    'Deleted!',
                    'Your data has been deleted',
                    'success'
                  );
                  FCItemList($('#col-savePayClass').attr('data-id'))
                  $('#modal-payitem').modal('hide');

                }
                else
                {
                  
                  Swal.fire(
                    'Error!',
                    'Invalid password',
                    'warning'
                  );
                }
              }
            }); 

            
          }
        })
      });


      $(document).on('click', '#col-delPayClass', function(){
        var detailid = $('#col-savePayClass').attr('data-id');
        Swal.fire({
          title: 'Are you sure?',
          text: "Enter password to delete.",
          type: 'warning',
          input: 'password',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Delete'
        }).then((result) => {
          if (result.value) {
            
            $.ajax({
              url:"{{route('deletecolFCdetail')}}",
              method:'GET',
              data:{
                detailid:detailid,
                pword:result.value
              },
              dataType:'',
              success:function(data)
              {
                if(data == 1)
                {
                  Swal.fire(
                    'Deleted!',
                    'Your data has been deleted',
                    'success'
                  );
                  FCClasList($('#saveFC').attr('data-id'))
                  $('#modal-col-class').modal('hide');

                }
                else
                {
                  
                  Swal.fire(
                    'Error!',
                    'Invalid password',
                    'warning'
                  );
                }
              }
            }); 

            
          }
        })
      });

      $(document).on('click', '#fc-btnadditem', function(){
        $('#modal-fc-itemcreate').modal('show');
        $('#fc-item-code').val('');
        $('#fc-item-desc').val('');
        $('#fc-item-class').val('');
        $('#fc-item-amount').val('');
        $('#fc-item-class').trigger('change');
        $('#fc-item-class').empty();
        $('#fc-item-class').append('<option></option>');       

        @foreach(App\FinanceModel::loadItemClass() as $itemclass)
          $('#fc-item-class').append('<option value="{{$itemclass->id}}">{{$itemclass->description}}</option>')
        @endforeach
      });

      $(document).on('click', '#append-fc-itemcreate', function(){

        var itemcode = $('#fc-item-code').val();
        var itemdesc = $('#fc-item-desc').val();
        var itemclass = $('#fc-item-class').val();
        var itemamount = $('#fc-item-amount').val();
 
        $.ajax({
          url:"{{route('appendFCNewItems')}}",
          method:'GET',
          data:{
            itemcode:itemcode,
            itemdesc:itemdesc,
            itemclass:itemclass,
            itemamount:itemamount
          },
          dataType:'json',
          beforesend: function(){
            $('#append-fc-itemcreate').prop('disabled', true);
          },
          success:function(data)
          {
            console.log(data);
            if(data.dataid > 0)
            {

              $('#fc-item').empty();

              loadreceivables($('#fc-item'));

              setTimeout(function(){
                $('#fc-item').val(data.dataid);
                $('#fc-item').trigger('change');
                $('#fc-txtamount').val(data.amount);
                $('#fc-txtamount').trigger('keyup');

              }, 1000); 
            }
            else
            {
              const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
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
                title: 'Item already exist.'
              })
            }

            $('#modal-fc-itemcreate').modal('hide');
            
          }
        }); 
      });

      $(document).on('click', '#viewpaysched', function(){

        var tuitionid = $('#saveFC').attr('data-id');
        $('#modal-paysched').modal('show');

        $.ajax({
          url:"{{route('viewpaysched')}}",
          method:'GET',
          beforeSend:function(){
            $('#schedOverlay').attr('style', '');
          },
          data:{
            tuitionid:tuitionid
          },
          dataType:'json',
          success:function(data)
          {
            $('#list-paysched').html(data.list);
            // $('#modal-paysched').modal('hide');
            // $('.overlay').show();
          },
          complete:function(){
            $('#schedOverlay').attr('style', 'display: none !important;');
          }
        }); 
      });

      $(document).on('click', '#close-paysched', function(){
          $('#modal-paysched').modal('hide');
      });

      $(document).on('click', '#btnpaymentplan', function(){
        $('#modal-paymentplan').modal('show');
      });

      $(document).on('keyup', '#txtpaymentplan', function(){
        $(this).val($(this).val().toUpperCase());
      });

      $(document).on('click', '#btnproceed', function(){
        $('#planname').text('PLAN: ' + $('#txtpaymentplan').val());
      });

      $(document).on('click', '#permop', function(){
        $('#modal-col-class-mopid').modal('show');
      });

      $(document).on('click', '#col-class-mopid_close', function(){
        $('#modal-col-class-mopid').modal('hide');
        
      });

      $(document).on('hidden.bs.modal', '#modal-col-class-mopid', function(){
        if($('#col-class-mopid_mopid').val() == 0)
        {
          $('#permop').prop('checked', false);
        }
        else
        {
          $('#permop').prop('checked', true);
        }
      })

      $(document).on('click', '#modal-payitem_close', function(){
        $('#modal-payitem').modal('hide');
      })




    });

  </script>
@endsection