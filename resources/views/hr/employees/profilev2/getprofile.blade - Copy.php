@php
    $number = rand(1,3);
    if($employeeinfo->gender == null){
        $avatar = 'assets/images/avatars/unknown.png';
    }
    else{
        if(strtoupper($employeeinfo->gender) == 'FEMALE'){
            $avatar = 'avatar/T(F) '.$number.'.png';
        }
        else{
            $avatar = 'avatar/T(M) '.$number.'.png';
        }
    }

    
    $days = (object)[
      1 => 'Sunday',
      2 => 'Monday',
      3 => 'Tuesday',
      4 => 'Wednesday',
      5 => 'Thursday',
      6 => 'Friday',
      7 => 'Saturday'
    ];

    $ispc = DB::table('usertype')
    ->where('id', Session::get('currentPortal'))
    ->first()->refid == 26 ? true : false;
    // echo date('D', strtotime("Sunday +1 days"));
@endphp
<style>
  /* input:disabled {
    background-color: #fff!important;
    opacity: 1;
    border: 1px solid #a1a1a1!important;
  }
  .form-control:disabled, .form-control[readonly] {
    background-color: #fff!important;
    opacity: 1;
  } */
  .modal-xl {
    max-width: 80%!important;
  }
  .floatinginput {
    position: relative;
    width: 100%;
  } 

  .floatinginput input {
    z-index: 1;
    width: 100%;
    padding-top: 4px;
    padding-bottom: 4px;
    padding-left: 17px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    background: rgb(255, 255, 255);
    outline: none;
    color: rgb(66, 66, 66);
    font-size: 15px;
  }

  .floatinginput span {
    font-weight: bold;
    position: absolute;
    left: 0;
    padding: 5px;
    padding-left: 8px;
    font-size: 13px;
    pointer-events: none;
    color: #495057;
    text-transform: capitalize;
    transition: 0.2s;
  }

  .floatinginput input:valid ~ span, 
  .floatinginput input:focus ~ span {
    color: #007bff;
    transform: translateX(9px) translateY(-7px);
    font-size: 11px;
    padding: 0 10px;
    background: #fff;
  }
  .disabled {
    pointer-events: none; /* Disable mouse events */
    opacity: 0.5; /* Apply visual style for disabled state */
  }
  /* .interestselect2 , .select2-container{
    text-align: left!important;
    width: 150px!important;
  } */
  .highlighted {
    background-color: rgba(30, 200, 245, 0.07);
  }
  .custom-checkbox {
    pointer-events: none!important;
  }
  .red-border {
    border: 2px solid red;
  }
  .g-3, .gy-3 {
      --bs-gutter-y: 1rem;
  }
  .g-3, .gx-3 {
      --bs-gutter-x: 1rem;
  }
  .w220 {
      width: 220px;
  }
  @media (min-width: 768px)
  {
  .pe-md-2 {
      padding-right: 0.5rem !important;
  }
  }
  @media (min-width: 576px)
  {
    
  .pe-sm-4 {
      padding-right: 1.5rem !important;
  }
  .text-center {
      text-align: center !important;
  }
  .pe-4 {
      padding-right: 1.5rem !important;
  }
  }
  *, *::before, *::after {
      box-sizing: border-box;
  }
  #table-deductions-standard th, #table-deductions-standard td 
  {
    padding: 3px;
  }
  .table-personal-information td, .table-personal-information th{
    border: 1px solid #ddd;
  }
  #table-personal-accounts td, #table-personal-accounts th{
    padding: 2px;
  }
  .card {
    border: none!important;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px !important;
  }

  .medium {
    font-size: 15px!important;
  }

  .select2-container .select2-selection--single {
    height: 37px !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    margin-top: -4px!important;
  }
  #sc_amount {
    position: relative;
    top: 20%;
  }

  input[type=checkbox]{
    -ms-transform: scale(1.3);
    -moz-transform: scale(1.3);
    -webkit-transform: scale(1.3);
    -o-transform: scale(1.3);
    transform: scale(1.3);
    padding: 20px;  
    }

  input[type="checkbox"]{
    content: "\2713";
    display: inline-block;
    font-size: 18px;
    color: #28a745!important; /* Replace with your desired success hex color */
    width: .9em;
    height: .9em;
    vertical-align: middle;
    margin-right: 0.5em;
  }

  input[type="checkbox"]:checked + label:before {
    font-weight: bold;
  }
  .non-clickable-checkbox {
    pointer-events: none !important; /* Disable click events */
    opacity: 1 !important; /* Maintain full opacity */
  }
</style>
<div class="container1">
  <div class="card card-nav-header" style="border-radius: 10px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
    <div class="card-header p-2" style="font-size: 14px;">
      <div class="row">
        <div class="col-md-6">
          <a href="/hr/employees/index" type="button" class="btn btn-sm btn-default text-black" style="border-radius: 10px;"><i class="fa fa-arrow-left text-black"></i> Back</a>            
        </div>
        <div class="col-md-6 text-right">
          <button type="button" class="btn btn-sm btn-default text-black" id="btn-reload-profile" style="border-radius: 10px;"><i class="fa fa-sync text-black"></i> Reload</button>
        </div>
      </div>
    </div>
  </div>
  
  {{-- MODAL ADDING STANDARD DEDUCTION --}}
  <div class="modal fade" tabindex="-1" role="dialog" id="modal_standarddeduction">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><span id="modal-standard-add-particular-desc">Standard deduction</span> </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="particular">Particular</label>
              <input type="email" class="form-control" id="particular" placeholder="Enter Particular" onkeyup="this.value = this.value.toUpperCase();" >
            </div>
            <div class="form-group">
              <label for="amount">Amount</label>
              <input type="number" class="form-control" id="amount" placeholder="0.00" name="rangeto" step=".01" min="0" onkeydown="return event.keyCode !== 69">
            </div>
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-primary" id="btn_savestandarddeduction"><i class="fas fa-plus"></i> Add</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
      </div>
    </div>
  </div>


  {{-- MODAL INTEREST LIST --}}
  <div class="modal fade" tabindex="-1" role="dialog" id="modal_interest">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><span id="">List of Interest</span> </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <table width="100%" class="table table-bordered table-sm" style="font-size: 16px; table-layout: fixed;" id="list_interest_table">
                <thead>
                  <tr>
                    <th width="70%" class="">Type</th>
                    <th width="30%" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
        {{-- <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div> --}}
      </div>
    </div>
  </div>


  {{-- MODAL ADD INTEREST TYPE --}}
  <div class="modal fade" tabindex="-1" role="dialog" id="modal_addtypeinterest">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title"><span id="">ADD TYPE</span> </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              MASAYA
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-primary btn-sm" id="btn_saveinteresttype"><i class="fas fa-plus"></i> Add</button>
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>
      </div>
    </div>
  </div>

  
{{-- MODAL ADD DEPARTMENT --}}
<div class="modal fade" tabindex="-1" role="dialog" id="modal_adddepartment">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Department</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="particular">Description</label>
              <input type="text" class="form-control" id="department_desc" placeholder="Enter Department Name" onkeyup="this.value = this.value.toUpperCase();" >
            </div>
          </div>
          <div class="col-md-12 justify-content-between" style="display: flex;">
            <button type="button" class="btn btn-primary btn-sm" id="btn_savedepdesc"><i class="fas fa-plus"></i> Add</button>
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


  {{-- MODAL ADD ALLOWANCE --}}
  <div class="modal fade" tabindex="-1" role="dialog" id="modal_addstandardallowance">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Standard Allowance</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="particular">Particulars</label>
                <input type="text" class="form-control" id="standardallowance_desc" placeholder="Enter Description" onkeyup="this.value = this.value.toUpperCase();" >
              </div>
              <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" class="form-control" id="standardallowance_amount" placeholder="0.00" name="rangeto" step=".01" min="0" onkeydown="return event.keyCode !== 69">
              </div>
            </div>
            <div class="col-md-12 justify-content-between" style="display: flex;">
              <button type="button" class="btn btn-primary btn-sm" id="btn_savestandardallowance"><i class="fas fa-plus"></i> Add</button>
              <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  {{-- MODAL ADD FOR DEDUCTION SETUPs --}}
  <div class="modal fade" tabindex="-1" role="dialog" id="modal_otherdeductionsetup">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Apply Other Deduction</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row p-0"  style="overflow-x: scroll!important;">
            <div class="col-md-12">
              <table width="100%" class="table table-sm" style="font-size: 15px;" id="modal_other_deduction">
                <thead>
                  <tr>
                    <th width="22%">Description</th>
                    <th width="20%" class="text-center"><span class="slant">Interest Rate</span></th>
                    <th width="14%" class="text-center"><span class="slant">Loan Amount</span></th>
                    <th width="8%" class="text-center"><span class="slant">Start Date</span></th>
                    <th width="8%" class="text-center"><span class="slant">End Date</span></th>
                    <th width="8%" class="text-center"><span class="slant">Terms</span></th>
                    <th width="10%" class="text-center"><span class="slant">Amount / term</span></th>
                    <th width="10%" class="text-center"><span class="slant">Action</span></th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
          {{-- <hr>
          <div class="row">
            <div class="col-md-12 justify-content-between" style="display: flex;">
              <button type="button" class="btn btn-primary btn-sm" id="btn_savestandardallowanceperdep"><i class="fas fa-plus"></i> Save</button>
              <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
          </div> --}}
        </div>
      </div>
    </div>
  </div>

  {{-- MODAL ADDING OTHER DEDUCTION --}}
  <div class="modal fade" tabindex="-1" role="dialog" id="modal_otherdeduction">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><span id="modal-standard-add-particular-desc">Other deduction</span> </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="particular">Particular</label>
              <input type="text" class="form-control" id="od_particular" placeholder="Enter Particular" onkeyup="this.value = this.value.toUpperCase();" >
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="amount">Amount</label>
                  <input type="number" class="form-control" id="mod_odamount" placeholder="0.00">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="amount">Interest</label>
                  <input type="number" class="form-control" id="mod_odineterst" placeholder="%">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="amount">Term</label>
                  <input type="number" class="form-control" id="mod_odterms" placeholder="">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="amount">Start Date</label>
                  <input type="date" id="mod_oddatefrom" class="form-control searchcontrol mod_oddatefrom" data-toggle="tooltip" title="Date From" value="{{date('Y-m-01')}}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="amount">End Date</label>
                  <input type="date" id="mod_oddateto" class="form-control searchcontrol mod_oddateto" data-toggle="tooltip" title="Date To" value="{{date('Y-m-01')}}">
                </div>
              </div>
            </div>
              <div class="form-group">
                <label for="amount">Amount to be Deduct</label>
                <input type="number" class="form-control mod_odamountdeduct" id="mod_odamountdeduct" placeholder="0.00" readonly>
            </div>
          </form>
          <div class="row">
            <div class="col-md-12 justify-content-between" style="display: flex;">
              <button type="button" class="btn btn-primary" id="btn_saveotherdeduction"><span><i class="fas fa-plus"></i></span> Add</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
        {{-- <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-primary" id="btn_saveotherdeduction"><i class="fas fa-plus"></i> Add</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div> --}}
      </div>
    </div>
  </div>

  {{-- MODAL ADD PER DEP OR APPLY ALL OTHER DEDUCTION --}}
  <div class="modal fade" tabindex="-1" role="dialog" id="modal_addotherdeductionperdep">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Apply Other Deduction</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 text-left">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input applyall_oddeductsetup" type="checkbox">
                  &nbsp;&nbsp;<label>Apply to All Employee</label>
                </div>
              </div>
            </div>
            <div class="col-md-6 text-right">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input applyperdep_oddeductsetup" type="checkbox" data-assign="">
                  &nbsp;&nbsp;<label>Per Department</label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12 select2departments" hidden>
              <select id="select2_departments" class="js-select-multiple form-control form-control-sm" multiple data-placeholder="Select Department"></select>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-12 justify-content-between" style="display: flex;">
              <button type="button" class="btn btn-primary btn-sm" id="btn_savestandardallowanceperdep"><i class="fas fa-plus"></i> Save</button>
              <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- <div class="row">
    <div class="col-md-12">
      <div class="card border-primary mb-3" style="">
        <div class="card-body">
          <div class="row">
            <div class="col-md-2">
              <a href="#">
                <img class="elevation-2" src="{{asset($employeeinfo->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}" id="profilepic" style="width:150px;height:150px;"  onerror="this.onerror = null, this.src='{{asset($avatar)}}'" alt="User Avatar">
              </a>
            </div>
            <div class="col-md-3" style="padding-left: 20px;">
              <h6 class="mb-0 mt-2  fw-bold d-block fs-6">{{$employeeinfo->title != null ? $employeeinfo->title.'. ' : ''}}{{$employeeinfo->firstname}} {{$employeeinfo->middlename != null ? $employeeinfo->middlename.' ' : ''}} {{$employeeinfo->lastname}} {{$employeeinfo->suffix}}</h6>
              <span class="text-muted small">Employee ID : &nbsp;{{$employeeinfo->tid}}</span> <br>
              <span class="text-muted small">RFID : &nbsp;{{($employeeinfo->rfid)?? 'not assign'}}</span> <br>
              <span class="py-1 fw-bold small-11 mb-0 mt-1 text-muted small">Designation : {{$employeeinfo->designation}}</span><br/>
              <small class="text-muted">Employment Status</small> @if($employeeinfo->employmentstatus == null || $employeeinfo->employmentstatus == 0 || $employeeinfo->employmentstatus == "")<span class="badge badge-secondary">Unset</span> @else <span class="badge badge-primary">{{$employeeinfo->employmentstatus}}</span> @endif
            </div>
  
            <div class="col-md-7">
              <div class="row g-2 pt-2">
                <div class="d-flex align-items-center">
                    <i class="fa fa-phone"></i>&nbsp;&nbsp;
                    <span class="ms-2 text-muted small">Contact No. : {{$employeeinfo->contactnum}}</span>
                </div>
              </div>
  
              <div class="row g-2 pt-2">
                <div class="d-flex align-items-center">
                    <i class="fa fa-envelope"></i>&nbsp;&nbsp;
                    <span class="ms-2 text-muted small">Email : {{$employeeinfo->email}}</span>
                </div>
              </div>
  
              <div class="row g-2 pt-2">
                <div class="d-flex align-items-center">
                    <i class="fa fa-birthday-cake"></i>&nbsp;&nbsp;
                    <span class="ms-2 text-muted small">Date of Birth : {{$employeeinfo->dob != null ? date('m/d/Y', strtotime($employeeinfo->dob)) : ''}}</span>
                </div>
              </div>
  
              <div class="row g-2 pt-2">
                <div class="d-flex align-items-center">
                    <i class="fa fa-address-book"></i>&nbsp;&nbsp;
                    <span class="ms-2 text-muted small">Address : {{$employeeinfo->address}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> --}}
  
  
  <div class="row">
    <div class="col-md-12">
      <div class="card border-primary mb-3" style="">
        <div class="card-header bg-dark" id="basic_info" style="">
          <h5 class="" style="color: rgb(235, 235, 235);"><i class="fas fa-layer-group" style="padding-top: 10px; padding-right: 5px;"></i>PERSONAL INFORMATION</h5>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-3 text-center" style="vertical-align: middle; padding-top: 10px;">
              <a href="#">
                <img class="img-circle elevation-2" src="{{asset($employeeinfo->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}" id="profilepic" style="width:160px;height:160px;"  onerror="this.onerror = null, this.src='{{asset($avatar)}}'" alt="User Avatar">
              </a>
              <div style="line-height: 18px;">
                
              </div>
            </div>
            <div class="col-md-9" style="padding-left: 10px!important;">
              
              <div class="row">
                <div class="col-md-12" style="padding-left: 10px!important;">
                  <div class="row g-2">
                    <h6 class="text-muted mb-0 mt-2  fw-bold d-block fs-6"><span class="ms-2 text-muted medium"><b>Name :</b> {{$employeeinfo->title != null ? $employeeinfo->title.'. ' : ''}}{{$employeeinfo->firstname}} {{$employeeinfo->middlename != null ? $employeeinfo->middlename.' ' : ''}} {{$employeeinfo->lastname}} {{$employeeinfo->suffix}}</h6>
                  </div>
                  <div class="row g-2">
                    <span class="py-1 fw-bold mb-0 mt-1 text-muted medium"><span class="ms-2 text-muted medium"><b>Designation :</b> {{$employeeinfo->designation}}</span><br/>
                  </div>
                  <div class="row g-2">
                    <span class="text-muted medium"><span class="ms-2 text-muted medium"><b>Teacher ID. :</b> {{$employeeinfo->tid}}</span>
                  </div>
                  <div class="row g-2">
                    <span class="text-muted medium"><b>Employment Status :</b> &nbsp;</span> @if($employeeinfo->employmentstatus == null || $employeeinfo->employmentstatus == 0 || $employeeinfo->employmentstatus == "")<span class="badge badge-secondary"> Unset</span> @else <span class="badge badge-primary">{{$employeeinfo->employmentstatus}}</span> @endif
                  </div>
                  
                  <div class="row g-2 pt-2">
                    <div class="d-flex align-items-center">
                        <span class="ms-2 text-muted medium"><b>Contact No. :</b> {{$employeeinfo->contactnum}}</span>
                    </div>
                  </div>
      
                  {{-- <div class="row g-2 pt-2">
                    <div class="d-flex align-items-center">
                        <span class="ms-2 text-muted medium"><b>Date of Birth :</b> {{$employeeinfo->dob != null ? date('m/d/Y', strtotime($employeeinfo->dob)) : ''}}</span>
                    </div>
                  </div> --}}
      
                  <div class="row g-2 pt-2">
                    <div class="d-flex align-items-center">
                        <span class="ms-2 text-muted medium"><b>Present Address :</b> {{$employeeinfo->address}}</span>
                    </div>
                  </div>
                  
                  {{-- <div class="row g-2 pt-2">
                    <div class="d-flex align-items-center">
                        <span class="ms-2 text-muted medium"><b>Primary Address :</b> {{$employeeinfo->primaryaddress}}</span>
                    </div>
                  </div> --}}
                  
                </div>
                {{-- <div class="col-md-4" style="padding-left: 10px!important;">
      
                  <div class="row g-2 pt-2">
                    <div class="d-flex align-items-center">
                        <span class="ms-2 text-muted medium"><b>Religion :</b> {{$employeeinfo->religion}}</span>
                    </div>
                  </div>
      
                  <div class="row g-2 pt-2">
                    <div class="d-flex align-items-center">
                        <span class="ms-2 text-muted medium"><b>Marital status :</b> {{$employeeinfo->civilstatus}}</span>
                    </div>
                  </div>
      
                  <div class="row g-2 pt-2">
                    <div class="d-flex align-items-center">
                        <span class="ms-2 text-muted medium"><b>Employment of spouse :</b> {{$employeeinfo->spouseemployment}}</span>
                    </div>
                  </div>
      
                  <div class="row g-2 pt-2">
                    <div class="d-flex align-items-center">
                        <span class="ms-2 text-muted medium"><b>No. of children :</b> {{$employeeinfo->numberofchildren}}</span>
                    </div>
                  </div>
                </div> --}}
              </div>
              {{-- <br>
              <div class="row">
                <h5><i class="fas fa-layer-group" style="padding-right: 5px;"></i>EMERGENCY CONTACT INFORMATION</h5>
              </div>
              <div class="row">
                <table class="table table-sm p-0" style="font-size: 13px;" style="table-layout: fixed;">
                  <tbody>
                      <tr>
                          <td style="width: 10%;"><span class="ms-2 text-muted medium"><b>Name :</b></span></td>
                          <td style="border-bottom: 1px solid #ddd; width: 30%;">{{$employeeinfo->emercontactname}}</td>
                          <td style="width: 10%;"><span class="ms-2 text-muted medium"><b>Relationship :</b></span></td>
                          <td style="border-bottom: 1px solid #ddd;">{{$employeeinfo->emercontactrelation}}</td>
                          <td style="width: 10%;"><span class="ms-2 text-muted medium"><b>Phone :</b></span></td>
                          <td style="border-bottom: 1px solid #ddd;">{{$employeeinfo->emercontactnum}}</td>
                      </tr>
                  </tbody>
              </table>
              </div> --}}
            </div>
            
          </div>
  
          <div class="row">
            <div class="col-md-12 text-right">
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  {{-- <div class="modal" tabindex="-1" id="modal_editpersonalinfo" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Personal Information</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
                <fieldset class="form-group border p-2 mb-2">
                  <legend class="w-auto m-0" style="font-size: 18px; font-weight: bold;">Basic Details</legend>
                  <div class="row">
                      <div class="col-md-2">
                          <div class="form-group">
                              <small class="text-bold" style="font-size: 15px;">Title</small>
                              <input type="text" class="form-control form-control-sm" name="title" id="profiletitle" value="{{$employeeinfo->title}}">
                          </div>
                      </div>
                      <div class="col-md-3">
                          <div class="form-group">
                              <small class="text-bold" style="font-size: 15px;">First Name</small>
                              <input type="text" class="form-control form-control-sm" name="fname" id="profilefname" value="{{$employeeinfo->firstname}}">
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group">
                              <small class="text-bold" style="font-size: 15px;">Middle Name</small>
                              <input type="text" class="form-control form-control-sm" name="mname" id="profilemname" value="{{$employeeinfo->middlename}}">
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-group">
                              <small class="text-bold" style="font-size: 15px;">Last Name</small>
                              <input type="text" class="form-control form-control-sm" name="lname" id="profilelname" value="{{$employeeinfo->lastname}}">
                          </div>
                      </div>
                      <div class="col-md-1">
                          <div class="form-group">
                              <small class="text-bold" style="font-size: 15px;">Suffix</small>
                              <input type="text" class="form-control form-control-sm" name="suffix" id="profilesuffix" value="{{$employeeinfo->suffix}}">
                          </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <small class="text-bold" style="font-size: 15px;">Gender</small>
                          @if($employeeinfo->gender == null)
                            <select class="select form-control form-control-sm text-uppercase" name="gender" id="profilegender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                          @else
                              <select class="select form-control form-control-sm text-uppercase select2" name="gender" id="profilegender" >
                                  <option value="male" {{"male" == strtolower($employeeinfo->gender) ? 'selected' : ''}}>Male</option>
                                  <option value="female" {{"female" == strtolower($employeeinfo->gender) ? 'selected' : ''}}>Female</option>
                              </select>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <small class="text-bold" style="font-size: 15px;">Birth Date</small>
                          <div class="cal-icon">
                              @if($employeeinfo->dob==null)
                                  <input class="form-control datetimepicker form-control-sm" type="date" name="dob"  id="profiledob" required/>
                              @else
                                  <input class="form-control datetimepicker form-control-sm" type="date" name="dob"  id="profiledob"value="{{$employeeinfo->dob}}" required/>
                              @endif
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group">
                              <small class="text-bold" style="font-size: 15px;">Civil Status</small>
                              <select class="select form-control text-uppercase form-control-sm" name="civilstatusid"  id="profilecivilstatusid" >
                                @if($employeeinfo->maritalstatusid == 0)
                                  @foreach($civilstatus as $cstatus)
                                      <option value="{{$cstatus->id}}">{{$cstatus->civilstatus}}</option>
                                  @endforeach
                                @else
                                  @foreach($civilstatus as $cstatus)
                                      <option value="{{$cstatus->id}}" {{$cstatus->id == $employeeinfo->maritalstatusid ? 'selected' : ''}}>{{$cstatus->civilstatus}}</option>
                                  @endforeach
                                @endif
                            </select>
                          </div>
                      </div> 
                      <div class="col-md-5">
                          <div class="form-group">
                              <small class="text-bold" style="font-size: 15px;">Religion</small>
                              <select class="select form-control text-uppercase form-control-sm" name="religionid"  id="profilereligionid">
                                @if($employeeinfo->religionid==null || $employeeinfo->religionid==0)
                                    @foreach($religion as $religioneach)
                                        <option value="{{$religioneach->id}}">{{$religioneach->religionname}}</option>
                                    @endforeach
                                @else
                                @foreach($religion as $religioneach)
                                    <option value="{{$religioneach->id}}" {{$religioneach->id == $employeeinfo->religionid ? 'selected' : ''}}>{{$religioneach->religionname}}</option>
                                @endforeach
                                @endif
                            </select>
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group">
                              <small class="text-bold" style="font-size: 15px;">Nationality</small>
                              <select class="select form-control text-uppercase form-control-sm" name="nationalityid" id="profilenationalityid">
                                @if($employeeinfo->nationalityid == null || $employeeinfo->nationalityid == '0')
                                    @foreach($nationalities as $nationalityeach)
                                        <option value="{{$nationalityeach->id}}" {{strtolower($nationalityeach->nationality) == 'filipino' ? 'selected' : ''}}>{{$nationalityeach->nationality}}</option>
                                    @endforeach
                                @else
                                    @foreach($nationalities as $nationalityeach)
                                        <option value="{{$nationalityeach->id}}" {{$nationalityeach->id == $employeeinfo->nationalityid ? 'selected' : ''}}>{{$nationalityeach->nationality}}</option>
                                    @endforeach
                                @endif
                            </select>
                          </div>
                      </div>
                      <div class="col-md-3">
                          <div class="form-group">
                              <small class="text-bold" style="font-size: 15px;">Number of children</small>
                              <input type="number" class="form-control form-control-sm" name="numofchildren" id="profilenumofchildren" value="{{$employeeinfo->numberofchildren}}" min="0" oninput="this.value = 
                              !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null">
                          </div>
                      </div>
                      <div class="col-md-7">
                          <div class="form-group">
                              <small class="text-bold" style="font-size: 15px;">Employment of Spouse</small>
                              <input type="text" class="form-control text-uppercase form-control-sm" name="spouseemployment"  id="profilespouseemployment" value="{{$employeeinfo->spouseemployment}}">
                          </div>
                      </div>
                    </div>
                </fieldset>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <small class="text-bold" style="font-size: 15px;">Present Address</small>
                    <input type="text" class="form-control text-uppercase form-control-sm" name="address"  id="profileaddress" value="{{$employeeinfo->address}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <small class="text-bold" style="font-size: 15px;">Primary Address</small>
                    <input type="text" class="form-control text-uppercase form-control-sm" name="address"  id="profileprimaryaddress" value="{{$employeeinfo->primaryaddress}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <small class="text-bold" style="font-size: 15px;">Email Address</small>
                    <input type="email" class="form-control form-control-sm" name="email" id="profileemail" value="{{$employeeinfo->email}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <small class="text-bold" style="font-size: 15px;">Contact Number</small>
                    <input type="text" class="form-control form-control-sm" id="contactnum" name="contactnum" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" value="{{$employeeinfo->contactnum}}">
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <small class="text-bold" style="font-size: 15px;">Lincense No.</small>
                    <input type="text" name="licenseno" class="form-control form-control-sm" id="profilelicenseno" value="{{$employeeinfo->licno}}">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <small class="text-bold" style="font-size: 15px;">Date Hired</small>
                    <input type="date" name="datehired" class="form-control form-control-sm" id="profiledatehired" value="{{$employeeinfo->date_joined}}">
                </div>
            </div>
        </div>
        <div class="submit-section">
            <button type="button" class="btn btn-primary btn-sm submit-btn float-right" data-dismiss="modal"  id="updatepersonalinformation">Update</button>
        </div>
        </div>
      </div>
    </div>
  </div> --}}
  
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <fieldset class="form-group border p-2 mb-2">
            <legend class="w-auto m-0" style="font-size: 18px; font-weight: bold;">Basic Details</legend>
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                    <small class="text-bold" style="font-size: 15px;">Title</small>
                    {{-- {{ $employeeinfo->title ? $employeeinfo->title : ''}} --}}
                    <input type="text" class="form-control form-control-sm " name="title" id="profiletitle" value="{{$employeeinfo->title}}" oninput="this.value = this.value.toUpperCase()">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                    <small class="text-bold" style="font-size: 15px;">First Name</small>
                    <input type="text" class="form-control form-control-sm" name="fname" id="profilefname" value="{{$employeeinfo->firstname}}" oninput="this.value = this.value.toUpperCase()">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                    <small class="text-bold" style="font-size: 15px;">Middle Name</small>
                    <input type="text" class="form-control form-control-sm" name="mname" id="profilemname" value="{{$employeeinfo->middlename}}" oninput="this.value = this.value.toUpperCase()">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <small class="text-bold" style="font-size: 15px;">Last Name</small>
                  <input type="text" class="form-control form-control-sm" name="lname" id="profilelname" value="{{$employeeinfo->lastname}}" oninput="this.value = this.value.toUpperCase()">
                </div>
              </div>
              <div class="col-md-1">
                <div class="form-group">
                  <small class="text-bold" style="font-size: 15px;">Suffix</small>
                  <input type="text" class="form-control form-control-sm" name="suffix" id="profilesuffix" value="{{$employeeinfo->suffix}}" oninput="this.value = this.value.toUpperCase()">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <small class="text-bold" style="font-size: 15px;">Gender</small>
                  @if($employeeinfo->gender == null)
                    <select class="select form-control form-control-sm text-uppercase" name="gender" id="profilegender">
                      <option value="male">Male</option>
                      <option value="female">Female</option>
                    </select>
                  @else
                      <select class="select form-control form-control-sm text-uppercase select2" name="gender" id="profilegender" >
                        <option value="male" {{"male" == strtolower($employeeinfo->gender) ? 'selected' : ''}}>Male</option>
                        <option value="female" {{"female" == strtolower($employeeinfo->gender) ? 'selected' : ''}}>Female</option>
                      </select>
                  @endif
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <small class="text-bold" style="font-size: 15px;">Birth Date</small>
                  <div class="cal-icon">
                      @if($employeeinfo->dob==null)
                        <input class="form-control datetimepicker form-control-sm" type="date" name="dob"  id="profiledob" required/>
                      @else
                        <input class="form-control datetimepicker form-control-sm" type="date" name="dob"  id="profiledob"value="{{$employeeinfo->dob}}" required/>
                      @endif
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                  <div class="form-group">
                      <small class="text-bold" style="font-size: 15px;">Civil Status</small>
                      <select class="select form-control text-uppercase form-control-sm" name="civilstatusid"  id="profilecivilstatusid" >
                        @if($employeeinfo->maritalstatusid == 0)
                          @foreach($civilstatus as $cstatus)
                            <option value="{{$cstatus->id}}">{{$cstatus->civilstatus}}</option>
                          @endforeach
                        @else
                          @foreach($civilstatus as $cstatus)
                            <option value="{{$cstatus->id}}" {{$cstatus->id == $employeeinfo->maritalstatusid ? 'selected' : ''}}>{{$cstatus->civilstatus}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
              </div> 
              <div class="col-md-5">
                  <div class="form-group">
                      <small class="text-bold" style="font-size: 15px;">Religion</small>
                      <select class="select form-control text-uppercase form-control-sm" name="religionid"  id="profilereligionid">
                        @if($employeeinfo->religionid==null || $employeeinfo->religionid==0)
                            @foreach($religion as $religioneach)
                              <option value="{{$religioneach->id}}">{{$religioneach->religionname}}</option>
                            @endforeach
                        @else
                        @foreach($religion as $religioneach)
                          <option value="{{$religioneach->id}}" {{$religioneach->id == $employeeinfo->religionid ? 'selected' : ''}}>{{$religioneach->religionname}}</option>
                        @endforeach
                        @endif
                    </select>
                  </div>
              </div>
              <div class="col-md-2">
                  <div class="form-group">
                      <small class="text-bold" style="font-size: 15px;">Nationality</small>
                      <select class="select form-control text-uppercase form-control-sm" name="nationalityid" id="profilenationalityid">
                        @if($employeeinfo->nationalityid == null || $employeeinfo->nationalityid == '0')
                          @foreach($nationalities as $nationalityeach)
                            <option value="{{$nationalityeach->id}}" {{strtolower($nationalityeach->nationality) == 'filipino' ? 'selected' : ''}}>{{$nationalityeach->nationality}}</option>
                          @endforeach
                        @else
                          @foreach($nationalities as $nationalityeach)
                            <option value="{{$nationalityeach->id}}" {{$nationalityeach->id == $employeeinfo->nationalityid ? 'selected' : ''}}>{{$nationalityeach->nationality}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <small class="text-bold" style="font-size: 15px;">Number of children</small>
                      <input type="number" class="form-control form-control-sm" name="numofchildren" id="profilenumofchildren" value="{{$employeeinfo->numberofchildren}}" min="0" oninput="this.value = 
                      !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null">
                  </div>
              </div>
              <div class="col-md-7">
                  <div class="form-group">
                      <small class="text-bold" style="font-size: 15px;">Employment of Spouse</small>
                      <input type="text" class="form-control text-uppercase form-control-sm" name="spouseemployment"  id="profilespouseemployment" value="{{$employeeinfo->spouseemployment}}">
                  </div>
              </div>
            </div>
          </fieldset>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <small class="text-bold" style="font-size: 15px;">Present Address</small>
                <input type="text" class="form-control text-uppercase form-control-sm" name="address"  id="profileaddress" value="{{$employeeinfo->address}}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <small class="text-bold" style="font-size: 15px;">Primary Address</small>
                <input type="text" class="form-control text-uppercase form-control-sm" name="address"  id="profileprimaryaddress" value="{{$employeeinfo->primaryaddress}}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <small class="text-bold" style="font-size: 15px;">Email Address</small>
                <input type="email" class="form-control form-control-sm" name="email" id="profileemail" value="{{$employeeinfo->email}}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <small class="text-bold" style="font-size: 15px;">Contact Number</small>
                <input type="text" class="form-control form-control-sm" id="contactnum" name="contactnum" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" value="{{$employeeinfo->contactnum}}">
            </div>
        </div>
      </div>
      <hr/>
      <div class="row">
          <div class="col-md-3">
              <div class="form-group">
                  <small class="text-bold" style="font-size: 15px;">Lincense No.</small>
                  <input type="text" name="licenseno" class="form-control form-control-sm" id="profilelicenseno" value="{{$employeeinfo->licno}}">
              </div>
          </div>
          <div class="col-md-2">
              <div class="form-group">
                  <small class="text-bold" style="font-size: 15px;">Date Hired</small>
                  <input type="date" name="datehired" class="form-control datetimepicker form-control-sm" id="profiledatehired" value="{{$employeeinfo->date_joined}}">
              </div>
          </div>
      </div>
      <div class="submit-section">
          <button type="button" class="btn btn-primary btn-sm submit-btn float-right" data-dismiss="modal"  id="updatepersonalinformation">Update</button>
      </div>
    </div>
  </div>

  <div class="card" id="basic_salary_info">
    <div class="card-header bg-dark" id="basic_info">
      <h5 class="" style="color: rgb(235, 235, 235);"><i class="fas fa-layer-group" style="padding-top: 10px; padding-right: 5px;"></i>BASIC SALARY INFORMATION</h5> <input id="employeeid" type="hidden" value="{{$employeeid}} ">
      <input type="hidden" id="employee_basicsalaryid">
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-3 col-sm-12 col-12">
            <div class="form-group">
                <label>Select Salary Type</label>
                <select class="form-control form-control-sm select2" id="select-salarytype"></select>
            </div>
        </div>
        <div class="col-md-9" id="daily_section">
          <div class="row">
            <div class="col-md-4">
              <label id="label_salaryamount">Salary amount (per day)</label>
              <input type="number" step="any" class="form-control" name="amountperday" id="amountperday"/>
              <span class="invalid-feedback" role="alert">
              <strong>Salary amount (per day) is required</strong>
              </span>
            </div>
      
            <div class="col-md-4">
              <label>No of hours per day</label>
              <input type="number" step="any" class="form-control" name="hoursperday" id="hoursperday"/>
              <span class="invalid-feedback" role="alert">
              <strong>No. of hours (per day) is required</strong>
              </span>
            </div>
            
            <div class="col-md-4">
              <label>Payment Type</label>
              <select class="form-control" name="paymenttype" id="paymenttype" required>
                <option value="cash">Cash</option>
                <option value="check">Check</option>
                <option value="banktransfer">Bank deposit</option>
              </select>
            </div>
          </div>
        </div>
      </div>
  
      <hr>
      <div class="row">
        <div class="col-sm-12 mb-2" >
          <label><i class="fas fa-asterisk"></i> SALARY BASED ON ATTENDANCE</label>
          <br/>
          <input type="checkbox" id="attendancebased" name="my-checkbox" data-off-color="danger" data-on-color="success">
          <input type="hidden" id="attendance_switch">
        </div>
      </div>
      {{-- <div class="row">
        <div class="col-md-12">
          <label><i class="fas fa-asterisk"></i> WORK SHIFT</label>
                          <br/>
          <div class="form-group clearfix">
            <div class="icheck-primary d-inline mr-5">
              <input type="radio" id="workshift0" name="workshift" value="0">
              <label for="workshift0">
                  Whole day
              </label>
            </div>
            <div class="icheck-primary d-inline mr-5">
              <input type="radio" id="workshift1" name="workshift" value="1">
              <label for="workshift1">
                  Morning Shift
              </label>
            </div>
            <div class="icheck-primary d-inline mr-5">
              <input type="radio" id="workshift2" name="workshift" value="2">
              <label for="workshift2">
                Night Shift
              </label>
            </div>
          </div>
        </div>
      </div> --}}
      <div class="row">
        <div class="col-md-3 col-sm-12 col-12">
          <div class="form-group">
              <label>Select Shift</label>
              <select class="form-control form-control-sm select2" id="select-shift"></select>
          </div>
      </div>
      </div>
      <div class="row">
        @if(count($timeschedule) == 0)
          <div class="row col-md-12" id="timediv">
            <div class="col-md-3">
                <label>AM IN</label>
                <input type="time" id="timepickeramin"  class="timepick form-control" name="am_in"/>
                <span class="invalid-feedback" role="alert">
                <strong>AM In is required</strong>
            </div>
            <div class="col-md-3">
                <label>AM OUT</label>
                <input type="time" id="timepickeramout" class="timepick form-control" name="am_out"/>
                <span class="invalid-feedback" role="alert">
                <strong>AM Out is required</strong>
            </div>
            <div class="col-md-3">
                <label>PM IN</label>
                <input type="time" id="timepickerpmin"  class="timepick form-control" name="pm_in"/>
                <span class="invalid-feedback" role="alert">
                <strong>PM In is required</strong>
            </div>
            <div class="col-md-3">
                <label>PM OUT</label>
                <input type="time" id="timepickerpmout"  class="timepick form-control" name="pm_out"/>
                <span class="invalid-feedback" role="alert">
                <strong>PM Out is required</strong>
            </div>
          </div>
        @else
          <div class="col-md-3">
              <label>AM IN</label>
              <input id="timepickeramin" class="timepick form-control" value="{{$timeschedule[0]->amin}}" name="am_in"/>
              <span class="invalid-feedback" role="alert">
              <strong>AM In is required</strong>
          </div>
          <div class="col-md-3">
              <label>AM OUT</label>
              <input  id="timepickeramout"  class="timepick form-control" value="{{$timeschedule[0]->amout}}" name="am_out"/>
              <span class="invalid-feedback" role="alert">
              <strong>AM Out is required</strong>
          </div>
          <div class="col-md-3">
              <label>PM IN</label>
              <input id="timepickerpmin"  class="timepick form-control" value="{{$timeschedule[0]->pmin}}" name="pm_in"/>
              <span class="invalid-feedback" role="alert">
              <strong>PM In is required</strong>
          </div>
          <div class="col-md-3">
              <label>PM OUT</label>
              <input id="timepickerpmout"  class="timepick form-control" value="{{$timeschedule[0]->pmout}}" name="pm_out"/>
              <span class="invalid-feedback" role="alert">
              <strong>PM Out is required</strong>
          </div>
        @endif
      </div>

      <hr>
      <div class="row mt-2">
        <div class="col-md-10"></div>
        <div class="col-md-2"  style=" display: grid;" id="submitbuttoncontainer">
          @if (count(collect($basicsalaryinfo)) == 0)
            <button type="button" class="btn btn-primary" id="basicsalary_submitbutton">Save Changes</button>
          @else
            <button type="button" class="btn btn-warning" id="basicsalary_editbutton">Edit Changes</button>
          @endif
        </div>
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-header bg-dark" id="basic_info">
      <h5 class="" style="color: rgb(235, 235, 235);"><i class="fas fa-layer-group" style="padding-top: 10px; padding-right: 5px;"></i>DEDUCTION SETUP </h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-3">
              <span class="" style="color: #000;"><label>STANDARD DEDUCTION</label></span>&nbsp;&nbsp;&nbsp;<span class="" style="font-size: 18px;"></span>
            </div>
            <div class="col-md-9">
              {{-- <a href="javascript:void(0)" class="text-primary" id="btn_addstandarddeduction"><i class="fas fa-plus"></i> Add Standard Deduction</a> --}}
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <table width="100%" class="table table-sm" style="font-size: 16px;" id="list_deduction">
            <thead>
              <tr>
                <th width="40%">Description</th>
                <th width="25%" class="text-center">Standard Computation</th>
                <th width="25%" class="text-center">Manual Entry</th>
                <th width="10%" class="text-center">Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          <br>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-2">
              <span style="color: #000;"><label>OTHER DEDUCTION</label></span>
            </div>
            {{-- <div class="col-md-8"><a href="javascript:void(0)" class="btn text-white btn-info btn-sm" id="btn_addotherdeduction"><i class="fas fa-plus"></i> Add Other Deduction</a> <a href="javascript:void(0)" class="btn text-white btn-info btn-sm" href="#" role="button" id="interest_list"><i class="fas fa-percent"></i> List of Interest</a></div> --}}
            <div class="col-md-10">
              <a href="javascript:void(0)" class="btn text-white btn-info btn-sm otherdeductionsetup"><i class="far fa-window-restore" style="font-size: 18px!important;"></i> Other Deductions Setup</a>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <table width="100%" class="table table-sm" style="font-size: 15px" id="other_deduction">
            <thead>
              <tr>
                <th width="22%">Description</th>
                <th width="20%" class="text-center"><span class="slant">Interest Rate</span></th>
                <th width="14%" class="text-center"><span class="slant">Loan Amount</span></th>
                <th width="8%" class="text-center"><span class="slant">Start Date</span></th>
                <th width="8%" class="text-center"><span class="slant">End Date</span></th>
                <th width="8%" class="text-center"><span class="slant">Terms</span></th>
                <th width="10%" class="text-center"><span class="slant">Amount / term</span></th>
                <th width="10%" class="text-center"><span class="slant">Action</span></th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      </div>
      {{-- <div class="row">
        <div class="col-md-12">
          <span style="text-indent: 10px; color: #000;"><label>TARDINESS SETUP</label></span>
        </div>
        <div class="col-md-12">
          
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <span style="text-indent: 10px; color: #000;"><label>UNDERTIME SETUP</label></span>
        </div>
        <div class="col-md-12">
          
        </div>
      </div> --}}
    </div>
  </div>

  {{-- version 1 --}}
  <div class="card">
    <div class="card-header bg-dark" id="basic_info">
      <h5 class="" style="color: rgb(235, 235, 235);"><i class="fas fa-layer-group" style="padding-top: 10px; padding-right: 5px;"></i>TARDINESS SETUP</h5>
    </div>
    <div class="card-body">
      
      <div class="row">
        <div class="col-md-5 col-sm-12">
          <div class="row pb-1">
            <div class="col-md-6 col-sm-12"><button class="btn btn-primary btn-sm" id="btn_adddepartment"><i class="fas fa-plus"></i> Add Department</button></div>
            <div class="col-md-6"></div>
          </div>
          <table width="100%" class="table table-bordered table-sm" style="font-size: 15px" id="department_tardines">
            <thead>
              <tr>
                <th width="70%" class="text-left"><span class="slant">Departments</span></th>
                <th width="30%" class="text-center">Status</th>
              </tr>
            </thead>
            <tbody id="tbody_tardinessdepartments">
              
            </tbody>
          </table>
        </div>
        <div class="col-md-7  col-sm-12 container-brackets">
            <input type="text" id="deptid" hidden>
            <div class="row" style="font-size: 13px; padding-bottom: 2px!important">
              <div class="col-md-12 text-right">
                <button type="button" class="btn btn-sm btn-primary" id="btn-addbracket" data-deptid="0" disabled><i class="fa fa-plus"></i> Bracket</button>
              </div>
            </div>
            <div class="card-body p-1" id="container-brackets"></div>
            <div class="card-body p-1" id="container-addbrackets"></div>
            <div class="text-center p-1" id="container-nodata" hidden></div>
        </div>
      </div>
    </div>
  </div>

  {{-- <div class="card">
    <div class="card-header bg-dark" id="basic_info">
      <h5 class="" style="color: rgb(235, 235, 235);"><i class="fas fa-layer-group" style="padding-top: 10px; padding-right: 5px;"></i>TARDINESS SETUP</h5>
    </div>
    <div class="card-body">
      
      <div class="row">
        <div class="col-md-12  col-sm-12 container-brackets">
            <input type="text" id="deptid" hidden>
            <div class="row" style="font-size: 13px; padding-bottom: 2px!important">
              <div class="col-md-12 text-right">
                <button type="button" class="btn btn-sm btn-primary" id="btn-addbracket" data-deptid="0"><i class="fa fa-plus"></i> Bracket</button>
              </div>
            </div>
            <div class="card-body p-1" id="container-brackets"></div>
            <div class="card-body p-1" id="container-addbrackets"></div>
            <div class="text-center p-1" id="container-nodata" hidden></div>
        </div>
      </div>
    </div>
  </div> --}}
  
  <div class="card">
    <div class="card-header bg-dark" id="basic_info">
      <h5 class="" style="color: rgb(235, 235, 235);"><i class="fas fa-layer-group" style="padding-top: 10px; padding-right: 5px;"></i>ALLOWANCE SETUP</h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table width="100%" class="table table-sm" style="font-size: 15px" id="allowance_setup">
            <thead>
              <tr>
                <th width="75%">Description</th>
                <th width="15%" class="text-center">Amount</th>
                <th width="10%" class="text-center">Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
      
<script>
  $(document).ready(function() {
    const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000,
  });

  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  });

  var salarytypes = @json($salarytypes);
  var personalsalarayinfo = []
  var shifts = []
  var standard_deduction = []
  var other_deduction = []
  var interest_type = []
  var employee_other_deduction = []
  var employee_standarddeduct = []
  var allbrackets = []
  var all_loaddepartments = []
  var all_allowances = []
  var per_employeestandardallowance = []
  var departmenttid = []
  
  loadsalaryinfo()
  load_salarytype()
  loadstandarddeduction()
  load_shift()
  load_employeestandarddeduction()
  loadotherdeduction()
  loademployeeotherdeduction()
  load_list_interest()
  
  
  load_allbrackets()
  loademployeestandardallowance()
  getall_allowance()

  // ----------------- variable declaration --------------------
  // var basicsalaryinfo = @json($basicsalaryinfo);

  $('#timepickeramin').attr('disabled', true)
  $('#timepickeramout').attr('disabled', true)
  $('#timepickerpmin').attr('disabled', true)
  $('#timepickerpmout').attr('disabled', true)
  $('input[name="workshift"]').attr('disabled', true)
  $('#select-shift').prop('disabled', true)
  
  $('#manualentry').attr('disabled', true)
  
  $('#select-shift').select2()
  $('#profilegender').select2()
  $('#profilecivilstatusid').select2()
  $('#profilereligionid').select2()
  $('#paymenttype').select2()

  $('#attendancebased').bootstrapSwitch();
  $('#daily_section').hide();
  $('#timediv').hide()
  $('.select2departments').prop('hidden', true)
  // if edit button exist disabled fields
  if ($('#basicsalary_editbutton').length > 0) {
    hidesalaryfields()
  }

  

  // when modal is close 
  // close adding standard deduction modal
  $('#modal_standarddeduction').on('hide.bs.modal', function (e) {
    $('#particular').val('')
    $('#amount').val('')
  })
  // close adding other deduction modal
  $('#modal_otherdeduction').on('hide.bs.modal', function (e) {
    $('#od_particular').val('')
    $('#od_amount').val('')
  })
 // close adding department modal
  $('#modal_adddepartment').on('hide.bs.modal', function (e) {
    $('#department_desc').val('')
  })
  // close adding department modal
  $('#modal_addstandardallowance').on('hide.bs.modal', function (e) {
    $('#standardallowance_desc').val('')
    $('#standardallowance_amount').val('')
  })

  $('#modal_addotherdeductionperdep').on('hide.bs.modal', function (e) {
    $('.select2departments').prop('hidden', true)
    $('.applyperdep_oddeductsetup').prop('checked', false)
  })

  
  

  // ----------------- Events ---------------------------------
  // save personal info
  $('#updatepersonalinformation').on('click', function(){
    var empid = $('#employeeid').val()
    var profiletitle = $('#profiletitle').val();
    var profilefname = $('#profilefname').val();
    var profilemname = $('#profilemname').val();
    var profilelname = $('#profilelname').val();
    var profilesuffix = $('#profilesuffix').val();
    var profilegender = $('#profilegender').val();
    var profiledob = $('#profiledob').val();
    var profilecivilstatusid = $('#profilecivilstatusid').val();
    var profilereligionid = $('#profilereligionid').val();
    var profilenationalityid = $('#profilenationalityid').val();
    var profilenumofchildren = $('#profilenumofchildren').val();
    var profilespouseemployment = $('#profilespouseemployment').val();
    var profileaddress = $('#profileaddress').val();
    var profileprimaryaddress = $('#profileprimaryaddress').val();
    var profileemail = $('#profileemail').val();
    var contactnum = $('#contactnum').val();
    var profilelicenseno = $('#profilelicenseno').val();
    var profiledatehired = $('#profiledatehired').val();
    
    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_storepersonalinfo",
      data: {
        empid : empid,
        profiletitle : profiletitle,
        profilefname : profilefname,
        profilemname : profilemname,
        profilelname : profilelname,
        profilesuffix : profilesuffix,
        profilegender : profilegender,
        profiledob : profiledob,
        profilecivilstatusid : profilecivilstatusid,
        profilereligionid : profilereligionid,
        profilenationalityid : profilenationalityid,
        profilenumofchildren : profilenumofchildren,
        profilespouseemployment : profilespouseemployment,
        profileaddress : profileaddress,
        profileprimaryaddress : profileprimaryaddress,
        profileemail : profileemail,
        contactnum : contactnum,
        profilelicenseno : profilelicenseno,
        profiledatehired : profiledatehired
      },
      success: function (data) {
        // $('.btn-view-profile[data-id="{{$employeeid}}"]').click();
          // loadsalaryinfo()
        if(data[0].status == 0){
          Toast.fire({
            type: 'error',
            title: data[0].message
          })
        }else{
          
          Toast.fire({
            type: 'success',
            title: data[0].message
          })
        }
      }
    });
  })
  $('#select2_departments').on('change', function() {
    var selectedIds = $(this).val();
    console.log(selectedIds); // Array of selected IDs
  });

  // Check Apply ALl box
  $(document).on('click', '.applyall_oddeductsetup', function(){
    
    if ($(this).is(':checked')) {
      $('.applyperdep_oddeductsetup').prop('checked', false)
      $('.select2departments').prop('hidden', true)

      console.log('checked');
    } else {
      console.log('unchecked');
      $('.select2departments').prop('hidden', false)
      $('#select2_departments').val([]).change();

    }
  })
  // Check Per Department box
  $(document).on('click', '.applyperdep_oddeductsetup', function(){
    if ($(this).is(':checked')) {
      $('.applyall_oddeductsetup').prop('checked', false)
      $('.select2departments').prop('hidden', false)
      console.log('checked');
    } else {
      console.log('unchecked');
      $('.select2departments').prop('hidden', true)
      $('#select2_departments').val([]).change();

    }
    

  })


  // click save changes in basic salary info
  $(document).on('click', '#basicsalary_submitbutton', function(){
    basic_salaryinfo()
  })

  // select salary type
  
  $(document).on('change', '#select-salarytype', function(){
    var saltype = $('#select-salarytype').val()

    if (saltype == 5) {
      $('#daily_section').show();
    } else if(saltype == 6){
      $('#daily_section').show();
    }
    else if(saltype == 7){
      $('#daily_section').show();
    }
     else {
      $('#daily_section').hide();
    }
  })

  
  $(document).on('change', '#select-shift', function(){
    var shiftid = $('#select-shift').val()
    
    if (shiftid == '') {
      $('#timediv').hide()
    } else {

      var shift_data = shifts.filter(x=>x.id == shiftid)

      $('#timepickeramin').val(shift_data[0].first_in);
      $('#timepickeramout').val(shift_data[0].first_out);
      $('#timepickerpmin').val(shift_data[0].second_in);
      $('#timepickerpmout').val(shift_data[0].second_out);

      $('#timediv').show()

    }
  })


  // workshift
  // $(document).on('change', 'input[name="workshift"]:checked', function(){

  //   var workshift = $('input[name="workshift"]:checked').val()

  //   if (workshift == '' || workshift == null) {
  //     workshift = 0
  //   } else if (workshift == 0){
  //     $('#timepickeramin').attr('disabled', false)
  //     $('#timepickeramout').attr('disabled', false)
  //     $('#timepickerpmin').attr('disabled', false)
  //     $('#timepickerpmout').attr('disabled', false)
  //   }
  //   else if (workshift == 1){
  //     $('#timepickeramin').attr('disabled', false)
  //     $('#timepickeramout').attr('disabled', false)
  //     $('#timepickerpmin').attr('disabled', true)
  //     $('#timepickerpmout').attr('disabled', true)
  //     $('#timepickerpmin').removeClass('is-invalid')
  //     $('#timepickerpmout').removeClass('is-invalid')
  //   } else if (workshift == 2){
  //     $('#timepickeramin').removeClass('is-invalid')
  //     $('#timepickeramout').removeClass('is-invalid')
  //     $('#timepickeramin').attr('disabled', true)
  //     $('#timepickeramout').attr('disabled', true)
  //     $('#timepickerpmin').attr('disabled', false)
  //     $('#timepickerpmout').attr('disabled', false)
  //   }
    
  // })
  
  // salary based on attendance switch
  $('#attendancebased').on('switchChange.bootstrapSwitch', function(event, state) {
    if (state) {
      var switchValue = 1;
      $('#select-shift').prop('disabled', false)
      $('#attendance_switch').val(switchValue)

      $('#timepickeramin').attr('disabled', false)
      $('#timepickeramout').attr('disabled', false)
      $('#timepickerpmin').attr('disabled', false)

      $('#timepickerpmout').attr('disabled', false)
      $('input[name="workshift"]').attr('disabled', false)
      
    } else {
      var switchValue = 0;
      $('#attendance_switch').val(switchValue)
      
      $('#timepickeramin').attr('disabled', true)
      $('#timepickeramout').attr('disabled', true)
      $('#timepickerpmin').attr('disabled', true)
      $('#timepickerpmout').attr('disabled', true)

      $('input[name="workshift"]').prop('disabled', true)
      $('#select-shift').prop('disabled', true)
      
    }
  });

  
  // edit basic salary information
  $(document).on('click', '#basicsalary_editbutton', function(){
    showsalaryfields()

    $('#basicsalary_editbutton').attr('id', 'basicsalary_updatebutton')
    $('#basicsalary_updatebutton').addClass('btn-info')
    $('#basicsalary_updatebutton').removeClass('btn-warning')
    $('#basicsalary_updatebutton').html('Update')
  })

  // update basic salary information
  $(document).on('click', '#basicsalary_updatebutton', function(){
    var thisdiv = $(this).closest('.card-body')
    var thisbutton = $(this)
    var valid_data = true
    var empid = $('#employeeid').val()
    var salarytype = $('#select-salarytype').val()
    var amountperday = $('#amountperday').val()
    var hoursperday = $('#hoursperday').val()
    var paymenttype = $('#paymenttype').val()




    // return false;
    if (valid_data) {
      Swal.fire({
          title: 'Are you sure you want to save changes?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes Save it'
      }).then((result) => {
        if (result.value) {
          $.ajax({
            type: "GET",
            url: "/payrollclerk/employees/profile/v2_updatesalaryinfo",
            data: {
              empid : empid,
              salarytype : salarytype,
              amountperday : amountperday,
              hoursperday : hoursperday,
              paymenttype : paymenttype,
            },
            success: function (data) {
              // $('.btn-view-profile[data-id="{{$employeeid}}"]').click();
              // loadsalaryinfo()
              if(data[0].status == 0){
                Toast.fire({
                  type: 'error',
                  title: data[0].message
                })
              }else{
                thisdiv.find('input, select').prop('disabled', true)
                thisbutton.attr('id', 'basicsalary_editbutton')
                thisbutton.removeClass('btn-info')
                thisbutton.addClass('btn-warning')
                thisbutton.html('Edit Changes')
                Toast.fire({
                  type: 'success',
                  title: data[0].message
                })
              }
            }
          });
        }
      })
    }
    
  })

  // add standard deduction
  $(document).on('click', '#btn_addstandarddeduction', function(){
    $('#modal_standarddeduction').modal('show')
  })
  
  // click add button
  $(document).on('click', '#btn_savestandarddeduction', function(){
    var particular = $('#particular').val()
    var amount = $('#amount').val()

    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_addstandarddeduction",
      data: {
        particular : particular,
        amount : amount
      },
      success: function (data) {
        loadstandarddeduction()
        if(data[0].status == 0){
          Toast.fire({
            type: 'error',
            title: data[0].message
          })
        }else{
          Toast.fire({
            type: 'success',
            title: data[0].message
          })
        }
      }
    });
  })

  // check standard deduction
  $(document).on('change', '#checkstandarddeduction', function() {
    
    if (personalsalarayinfo[0] == null) {
      var salaryamount = 0
      $('#basic_salary_info').addClass('is-invalid')
      Toast.fire({
          type: 'error',
          title: 'You Need to Add Basic Salary Information'
      })
      return false;
    } else {
      
      var salaryamount = personalsalarayinfo[0].amount
    }

    var empid = $('#employeeid').val()
    var standeductionid = $(this).attr('sdid')

    if ($(this).is(':checked')) {
      $('.standardrow'+standeductionid+'').css('background-color', '#1ec8f512')
      $.ajax({
        type: "GET",
        url: "/payrollclerk/employees/profile/v2_getstandarddeductionamountdefault",
        data: {
          salaryamount : salaryamount,
          empid : empid,
          standeductionid :standeductionid
        },
        success: function (data) {
          loadsalaryinfo()
          if (data == null || data == '' ||  data == 0) {
            $('#sc_amount'+standeductionid).val('0.00')
            
          } else {
            $('#sc_amount'+standeductionid).val(data)
          }
        }
      });
      
      

      $('#sc_amount'+standeductionid+'').prop('disabled', false)
      // $('#save_standarddedect'+standeductionid+'').prop('disabled', false)
      $('#save_standarddedect'+standeductionid+'').removeClass('disabled')

      
      $('#sc_amount_check'+standeductionid+'').prop('checked', true)
      $('#sc_amount_check'+standeductionid+'').prop('disabled', false)

      $('#me_amount_check'+standeductionid+'').prop('checked', false)
      $('#me_amount_check'+standeductionid+'').prop('disabled', true)
      $('#me_amount'+standeductionid+'').prop('disabled', true)
      
      

    } else {
      $('#sc_amount'+standeductionid+'').val('')
      $('#me_amount'+standeductionid+'').val('')
      $('#save_standarddedect'+standeductionid+'').addClass('disabled')
      $('#standardrow'+standeductionid+'').css('background-color', 'white')

      $('#sc_amount_check'+standeductionid+'').prop('checked', false)
      $('#sc_amount_check'+standeductionid+'').prop('disabled', true)
      $('#sc_amount'+standeductionid+'').prop('disabled', true)
      
      $('#me_amount_check'+standeductionid+'').prop('disabled', true)
      $('#me_amount_check'+standeductionid+'').prop('checked', false)
      $('#me_amount'+standeductionid+'').prop('disabled', true)

    }
  });


  $(document).on('click', '.save_sdeduct', function(){
    
    var standeductionid = $(this).attr('data-id')
    var empid = $('#employeeid').val()
    if ($('.sc_checkbox').is(':checked')) {
      console.log('sc checked');
      var standarddeduction_amount = $('#sc_amount'+standeductionid+'').val()
      var sc_me = 1
    } else {
      console.log('me checked');
      var standarddeduction_amount = $('#me_amount'+standeductionid+'').val()
      var sc_me = 2
    }
    
    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_addemployeestandarddeduction",
      data: {
        standeductionid : standeductionid,
        empid : empid,
        standarddeduction_amount : standarddeduction_amount,
        sc_me : sc_me
      },
      success: function (data) {
        load_employeestandarddeduction()
    
        if(data[0].status == 0){
          Toast.fire({
            type: 'error',
            title: data[0].message
          })
        }else{
          Toast.fire({
            type: 'success',
            title: data[0].message
          })
        }
      }
    });

  })

  $(document).on('click', '.delete_sdeduct', function(){
    var standeductionid = $(this).attr('emstandarddeductid-id')
    var empid = $('#employeeid').val()

    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_deleteemployeestandarddeduction",
      data: {
        standeductionid : standeductionid,
        empid : empid
      },
      success: function (data) {
        load_employeestandarddeduction()
        if(data[0].status == 0){
          Toast.fire({
            type: 'error',
            title: data[0].message
          })
        }else{
          Toast.fire({
            type: 'success',
            title: data[0].message
          })
        }
      }
    });
  })

  // Interest List
  $(document).on('click', '#interest_list', function(){
    $('#modal_interest').modal('show')
  })


  // Click + Add Interest
  $(document).on('click', '#btn_addinterest', function(){
    $('#modal_addtypeinterest').modal('show')
  })

  // Click Other Deduction Setup
  $(document).on('click', '.otherdeductionsetup', function(){
    $('#modal_otherdeductionsetup').modal('show')
  })

  // click Add Other Deduction button
  $(document).on('click', '#btn_addotherdeduction', function(){
    $('#modal_otherdeduction').modal('show')
  })
  

  $(document).on('input', '#mod_odineterst , #mod_odterms', function(){
    mod_odautocalculateamount()
  })
  
  $(document).on('input', '#mod_odamount', function(){
    mod_odautocalculateamount()
  })
  function mod_odautocalculateamount(){
    
    var mod_loanamount = parseFloat($('#mod_odamount').val())
    var mod_odineterst = parseFloat($('#mod_odineterst').val())
    
    if (mod_odineterst > 100) {
      Toast.fire({
          type: 'error',
          title: 'Exceed 100 Percent'
      })
      return false;
    }

    if (isNaN(mod_odineterst)) {
      mod_odineterst = 0
    } 
    
    var mod_odterms = parseFloat($('#mod_odterms').val())

    var mod_odamountdeduct = mod_loanamount

    mod_odineterst = mod_odineterst/100
    mod_odineterst = mod_odineterst * mod_loanamount
    
    if (!isNaN(mod_odineterst)) {
      mod_odamountdeduct += mod_odineterst
    }

    if (!isNaN(mod_odterms)) {
      if (mod_odterms != 0) {
        mod_odamountdeduct =  mod_loanamount/mod_odterms
      } else {
        mod_odamountdeduct = mod_odamountdeduct
      }
      mod_odamountdeduct = mod_odamountdeduct + mod_odineterst
    }

    if (isNaN(mod_odterms) && isNaN(mod_loanamount)) {
      $('#mod_odamountdeduct').val(mod_odamountdeduct)
    }
    console.log(mod_odterms);
    $('#mod_odamountdeduct').val(mod_odamountdeduct)

  }
  // click Add in Other Deduction
  $(document).on('click', '#btn_saveotherdeduction', function(){
    var od_particular = $('#od_particular').val()
    var mod_odamount = $('#mod_odamount').val()
    var mod_odineterst = $('#mod_odineterst').val()
    var mod_odterms = $('#mod_odterms').val()
    var mod_oddatefrom = $('#mod_oddatefrom').val()
    var mod_oddateto = $('#mod_oddateto').val()
    var mod_odamountdeduct = $('#mod_odamountdeduct').val()
    var empid = $('#employeeid').val()

    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_addotherdeduction",
      data: {
        od_particular: od_particular,
        mod_odamount : mod_odamount,
        mod_odineterst : mod_odineterst,
        mod_odterms : mod_odterms,
        mod_oddatefrom : mod_oddatefrom,
        mod_oddateto : mod_oddateto,
        mod_odamountdeduct : mod_odamountdeduct,
        empid : empid
      },
      success: function (data) {
        if(data[0].status == 0){
          Toast.fire({
            type: 'error',
            title: data[0].message
          })
        }else{
          $('#modal_otherdeduction').modal('hide')
          loadotherdeduction()
          Toast.fire({
            type: 'success',
            title: data[0].message
          })
        }
      }
    });
  })

  // when other deduction is checked
  $(document).on('change', '#checkotherdeduction', function(){
    if (personalsalarayinfo[0] == null) {
      var salaryamount = 0
      $('#basic_salary_info').addClass('is-invalid')
      Toast.fire({
          type: 'error',
          title: 'You Need to Add Basic Salary Information'
      })
      return false;
    } else {
      
      var salaryamount = personalsalarayinfo[0].amount
    }
    var od = $(this).attr('odid')
    var fullamount = $(this).attr('fullamount')
    if ($(this).is(':checked')) {
      $('#od_amountdeducted'+od+'').val(fullamount)
      $('#save_otherdeduct'+od+'').removeClass('disabled')
      $('#od_interest'+od+'').prop('disabled', false)
      $('#od_datefrom'+od+'').prop('disabled', false)
      $('#od_dateto'+od+'').prop('disabled', false)
      $('#od_terms'+od+'').prop('disabled', false)
      $('#od_amountdeducted'+od+'').prop('disabled', false)
      $('#od_loanamount'+od+'').prop('disabled', false)
      
    } else {
      $('#save_otherdeduct'+od+'').addClass('disabled')
    }
  })
  

  $(document).on('input', '.od_interest , .od_terms', function(){
    var od = $(this).attr('data-id') 
    od_autocalculateamount(od)
  })
  
  $(document).on('input', '.od_loanamount', function(){
    var od = $(this).attr('data-id') 
    od_autocalculateamount(od)
  })

  
  function od_autocalculateamount(od){
    
    var od_loanamount = parseFloat($('.od_loanamount[data-id="'+od+'"]').val())
    var od_interest = parseFloat($('.od_interest[data-id="'+od+'"]').val())

    if (isNaN(od_interest)) {
      od_interest = 0
    } 
    // var terms = $(this).closest('tr').find('.od_terms').val()
    var terms = parseFloat($('.od_terms[data-id="'+od+'"]').val())

    var amounttobededuct = od_loanamount

    od_interest = od_interest / 100
    od_interest = od_interest * od_loanamount
    
    if (!isNaN(od_interest)) {
      amounttobededuct += od_interest
    }

    if (!isNaN(terms)) {
      if (terms != 0) {
      amounttobededuct =  od_loanamount/terms
      } else {
        amounttobededuct = amounttobededuct
      }
      amounttobededuct = amounttobededuct + od_interest
    }

    if (isNaN(terms) && isNaN(od_interest)) {
      $('#od_amountdeducted'+od+'').val(amounttobededuct)
    }
    // od_amountdeducted
    $('#od_amountdeducted'+od+'').val(amounttobededuct)

  }

  // edit other deduction 
  $(document).on('click', '.edit_oddeduct', function(){
    var od = $(this).attr('data-id') 
    $('.edit_oddeduct').addClass('save_oddeduct')
    $('.edit_oddeduct').removeClass('edit_oddeduct')
    $('#iconsave_oddeduct'+od+'').addClass('text-success')
    $('#od_interest'+od+'').prop('disabled', false)
    $('#od_interest'+od+'').prop('readonly', false)
    $('#od_loanamount'+od+'').prop('disabled', false)
    $('#od_datefrom'+od+'').prop('disabled', false)
    $('#od_dateto'+od+'').prop('disabled', false)
    $('#od_terms'+od+'').prop('disabled', false)
    $('#od_amountdeducted'+od+'').prop('disabled', false)
    console.log(od);
  })
  
  $(document).on('click', '.save_oddeduct', function(){
    var od = $(this).attr('data-id') 

    var action = $(this).attr('type-action') 
    $('#iconsave_oddeduct'+od+'').addClass('text-info')
    $('#iconsave_oddeduct'+od+'').removeClass('text-success')
    var thistd = $(this).closest('td')
    var thistr = $(this).closest('tr')  
    var empid = $('#employeeid').val()

    var od_interest =  parseFloat($('.od_interest[data-id="'+od+'"]').val())
    var od_loanamount = parseFloat($('.od_loanamount[data-id="'+od+'"]').val())
    var od_datefrom = $('.od_datefrom[data-id="'+od+'"]').val()
    var od_dateto = $('.od_dateto[data-id="'+od+'"]').val()
    var od_terms =  parseFloat($('.od_terms[data-id="'+od+'"]').val())
    var amounttobededuct =  parseFloat($('.od_amountdeduct[data-id="'+od+'"]').val())
    var od_particular = $(this).attr('od_particular') 
    
    if (od_terms == null || od_terms == '') {
      od_terms = 0
    }

    if (od_interest == null || od_interest == '') {
      od_interest = 0
    }
    // console.log(od);
    // console.log(od_interest);
    // console.log(od_terms);
    // console.log(od_loanamount);
    // console.log(amounttobededuct);

    // return false;
    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_saveemployeeotherdeduction",
      data: {
        action : action,
        od_interest : od_interest,
        od_loanamount : od_loanamount,
        od_datefrom : od_datefrom,
        od_dateto : od_dateto,
        od_terms : od_terms,
        amounttobededuct : amounttobededuct,
        od : od,
        empid : empid,
        od_particular : od_particular
      },
      success: function (data) {
        if(data[0].status == 0){
          Toast.fire({
            type: 'error',
            title: data[0].message
          })
        }else{
          loademployeeotherdeduction()
          loadotherdeduction()
          thistr.find('input').prop('disabled', true)
          thistd.empty()
          // thistd.append('<button class="btn btn-success btn-sm save_odeduct" data-id="'+od+'"  od_particular="'+od_particular+'"  id="save_otherdeduct'+od+'" disabled><i class="fas fa-share-square" style="font-size: 18px!important;" ></i></button> <a href="javascript:void(0)"  class="btn delete_odeduct"  id="delete_employeeotherdedect'+od+'" emotherdeductid-id="'+od+'" ><i class="fas fa-trash text-danger" style="font-size: 18px!important;"></i></a>')
          thistd.append('<a href="javascript:void(0)"  class="btn delete_odeduct"  id="delete_employeeotherdedect'+od+'" emotherdeductid-id="'+od+'" ><i class="fas fa-trash text-danger" style="font-size: 18px!important;"></i></a>')
          Toast.fire({
            type: 'success',
            title: data[0].message
          })
        }
      }
    });

    
  })


  // delete employee other deduction
  $(document).on('click', '.delete_odeduct', function(){
    var em_odid = $(this).attr('emotherdeductid-id')
    var empid = $('#employeeid').val()

    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_deleteemployeeotherdeduction",
      data: {
        em_odid : em_odid,
        empid : empid
      },
      success: function (data) {
        if(data[0].status == 0){
          Toast.fire({
            type: 'error',
            title: data[0].message
          })
        }else{
          loademployeeotherdeduction()
          Toast.fire({
            type: 'success',
            title: data[0].message
          })
        }
      }
    });
  })
  


  // $(document).on('change', '#checkstandarddeduction', function() {
  //   var standeductionid = $(this).attr('sdid')
  //   if ($(this).is(':checked')) {
      
  //     $('#standardrow'+standeductionid+'').css('background-color', '#1ec8f512')
     
  //     $('#sc_amount_check'+standeductionid+'').prop('checked', true)
  //     $('#me_amount_check'+standeductionid+'').prop('disabled', true)
  //     $('#me_amount'+standeductionid+'').prop('disabled', true)
      
  //     var empid = $('#employeeid').val()
      

  //   } else {
  //     $('#standardrow'+standeductionid+'').css('background-color', 'white')

  //     $('#sc_amount_check'+standeductionid+'').prop('checked', false)
  //     $('#sc_amount_check'+standeductionid+'').prop('disabled', true)
  //     $('#sc_amount'+standeductionid+'').prop('disabled', true)
      
  //     $('#me_amount_check'+standeductionid+'').prop('disabled', true)
  //     $('#me_amount_check'+standeductionid+'').prop('checked', false)
  //     $('#me_amount'+standeductionid+'').prop('disabled', true)

  //   }
  // });

  $(document).on('change', '.sc_checkbox', function() {
    var standeductionid = $(this).attr('sdid')

    if ($(this).is(':checked')) {
      // $('#me_amount'+standeductionid+'').prop('disabled', true)
      // $('#me_amount_check'+standeductionid+'').prop('checked', false)
      // $('#me_amount_check'+standeductionid+'').prop('disabled', true)
    } else {
      $('#sc_amount_check'+standeductionid+'').prop('disabled', true)
      $('#sc_amount'+standeductionid+'').prop('disabled', true)
      
      $('#me_amount_check'+standeductionid+'').prop('disabled', false)
      $('#me_amount_check'+standeductionid+'').prop('checked', true)
      $('#me_amount'+standeductionid+'').prop('disabled', false)
      
    }
  })

  $(document).on('change', '.me_checkbox', function() {
    var standeductionid = $(this).attr('sdid')

    if ($(this).is(':checked')) {
      // $('#me_amount'+standeductionid+'').prop('disabled', true)
      // $('#me_amount_check'+standeductionid+'').prop('checked', false)
      // $('#me_amount_check'+standeductionid+'').prop('disabled', true)
    } else {
      $('#me_amount_check'+standeductionid+'').prop('disabled', true)
      $('#me_amount'+standeductionid+'').prop('disabled', true)
      
      $('#sc_amount_check'+standeductionid+'').prop('disabled', false)
      $('#sc_amount_check'+standeductionid+'').prop('checked', true)
      $('#sc_amount'+standeductionid+'').prop('disabled', false)
      
    }
  })

  $(document).on('click', '.tr_perdep', function(){

    // Remove the background from previously clicked rows
    $('.tr_perdep').removeClass('highlighted');
    
    // Add the background to the clicked row
    $(this).addClass('highlighted');

    // Handle the click event for each row here
    var rowData = $(this).text();
    console.log('Row clicked:', rowData);
    // Add your desired functionality here

     var deptid = $(this).attr('data-deptid')
    $('#btn-addbracket').prop('disabled', false)
    $('#container-nodata').prop('hidden', false)
    $('#container-addbrackets').empty()
   
    $('#deptid').val(deptid)
    load_tardinesssetup()

  })
  
  $(document).on('click','#btn-addbracket', function(){
    $('#container-addbrackets').empty()
    $('#container-nodata').prop('hidden', true)
    var deptid = $(this).attr('data-deptid');
    var appendhtml = '<div class="row mb-2 each-newbracket" data-id="0" data-deptid="'+deptid+'">'+
                        '<div class="col-md-3">'+
                          '<div class="floatinginput">'+
                            '<input type="number" class="input-from-new" placeholder="" required="required">'+
                            '<span>From (mins.)</span>'+
                          '</div>'+
                          // '<input type="number" class="input-from-new form-control form-control-sm"/>'+
                        '</div>'+
                          '<div class="col-md-3">'+
                            '<div class="floatinginput">'+
                              '<input type="number" class="input-to-new" placeholder="" required="required">'+
                              '<span>From (mins.)</span>'+
                            '</div>'+
                            // '<input type="number" class="input-to-new form-control form-control-sm"/>'+
                        '</div>'+
                        '<div class="col-md-2"hidden>'+
                            '<select class="select-timetype-new form-control form-control-sm">'+
                                '<option value="1">mins.</option>'+
                                '<option value="2">hrs.</option>'+
                            '</select>'+
                        '</div>'+
                        '<div class="col-md-2">'+
                            '<select class="select-deducttype-new  form-control form-control-sm">'+
                                '<option value="1">Fixed Amount</option>'+
                                '<option value="2">Daily Rate %</option>'+
                            '</select>'+
                        '</div>'+
                        '<div class="col-md-3">'+
                          '<div class="floatinginput">'+
                            '<input type="number" class="input-amount-new" placeholder="" required="required">'+
                            '<span>% or Amount</span>'+
                          '</div>'+
                            // '<input type="number" class="input-amount-new form-control form-control-sm"/>'+
                        '</div>'+
                        '<div class="col-md-1 text-center" style="cursor: pointer">'+
                            '<a href="javascript:void(0)" class=""><i class="fa fa-plus text-info" id="btn-submit" style="font-size: 18px;"></i></a>&nbsp;&nbsp;&nbsp;'+
                            '<a href="javascript:void(0)" class=""><i class="fa fa-times bracket-remove text-danger" style="font-size: 18px;"></i></a>'+ 
                        '</div>'+
                    // '<div class="col-md-2 pt-2 text-left" style="cursor: pointer">'+
                    //     '<i class="fa fa-times bracket-remove text-danger"></i>'+
                    //     '<i class="fa fa-save text-success" id="btn-submit" style="padding-left: 15px"></i>'+
                    // '</div>'+
                '</div>';
    $('#container-addbrackets').append(appendhtml)
})
$(document).on('click', '.bracket-remove', function(){
    $(this).closest('.row').remove()
    if($('.each-newbracket').length == 0)
    {
        $('#btn-submit').hide()
    }
})

// remove bracket
$(document).on('click', '#btn-removebracket', function(){
  console.log('remove brackets');
})


// save bracket added
// $(document).on('click', '.bracket-save', function(){
//   console.log('masyahin');
// })

$(document).on('click', '#btn-submit', function(){
    var newbrackets = [];
    var validation = 0;
    $('.each-newbracket').each(function(){
        var eachvalidation = 0;

        if($(this).find('.input-from-new').val().replace(/^\s+|\s+$/g, "").length == 0)
        {
            eachvalidation+=1;
            $(this).find('.input-from-new').css('border','1px solid red')
        }
        if($(this).find('.input-to-new').val().replace(/^\s+|\s+$/g, "").length == 0)
        {
            eachvalidation+=1;
            $(this).find('.input-to-new').css('border','1px solid red')
        }
        if($(this).find('.input-amount-new').val().replace(/^\s+|\s+$/g, "").length == 0)
        {
            eachvalidation+=1;
            $(this).find('.input-amount-new').css('border','1px solid red')
        }
        if(eachvalidation == 0)
        {
            obj = {
                deptid : $('#deptid').val(),
                latefrom : $(this).find('.input-from-new').val(),
                lateto : $(this).find('.input-to-new').val(),
                timetype : $(this).find('.select-timetype-new').val(),
                deducttype : $(this).find('.select-deducttype-new').val(),
                amount : $(this).find('.input-amount-new').val()
            }
            newbrackets.push(obj);
        }else{
            validation+=1;
        }

    })
    if(validation == 0)
    {
        $.ajax({
            url: '/hr/tardinesscomp/addbrackets',
            type:"GET",
            data:{
                brackets: JSON.stringify(newbrackets)
            },
            // headers: { 'X-CSRF-TOKEN': token },,
            success: function(data){   

                if(data)
                {                 
                  console.log(data);
                  $('#container-addbrackets').empty()  
                  // $('#tbody_tardinessdepartments').empty();
      
                    toastr.success('Added successfully!', 'Time Braket')
                    // load_allbrackets()
                    load_tardinesssetup()
                }     
            }
        })
    }else{
        toastr.warning('Please fill in the required fields!', 'New Time Brakets')
    }
  })


  // btn update bracket
  $(document).on('click','.btn-update', function(){
    var thisrow = $(this).closest('.row');
    var dataid = $(this).attr('data-id');
    // alert(dataid)
    var eachvalidation = 0;

    if(thisrow.find('.input-from').val().replace(/^\s+|\s+$/g, "").length == 0)
    {
        eachvalidation+=1;
        thisrow.find('.input-from').css('border','1px solid red')
    }
    if(thisrow.find('.input-to').val().replace(/^\s+|\s+$/g, "").length == 0)
    {
        eachvalidation+=1;
        thisrow.find('.input-to').css('border','1px solid red')
    }
    if(thisrow.find('.input-amount').val().replace(/^\s+|\s+$/g, "").length == 0)
    {
        eachvalidation+=1;
        thisrow.find('.input-amount').css('border','1px solid red')
    }
    if(eachvalidation == 0)
    {
        $.ajax({
            url: '/hr/tardinesscomp/updatebracket',
            type:"GET",
            data:{
                dataid      : dataid,
                latefrom    : thisrow.find('.input-from').val(),
                lateto      : thisrow.find('.input-to').val(),
                timetype    : thisrow.find('.select-timetype').val(),
                deducttype  : thisrow.find('.select-deducttype').val(),
                amount      : thisrow.find('.input-amount').val()
            },
            // headers: { 'X-CSRF-TOKEN': token },,
            success: function(data){
                if(data == 1)
                {        
                    thisrow.find('.btn-update').addClass('btn-default')
                    thisrow.find('.btn-update').removeClass('btn-warning')                
                    toastr.success('Updated successfully!', 'Time Braket')
                    $('#container-addbrackets').empty()  
                    // $('#tbody_tardinessdepartments').empty();
                }
            }
        })
    }else{
        toastr.warning('Please fill in the required fields!', 'New Time Brakets')
    }
  })
  

  // remove bracket
  $(document).on('click','.btn-delete', function(){        
    var dataid = $(this).attr('data-id');
    var thisrow = $(this).closest('.row');
    Swal.fire({
        title: 'Are you sure you want to delete this bracket?',
        type: 'warning',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Delete',
        showCancelButton: true,
        allowOutsideClick: false
    }).then((confirm) => {
        if (confirm.value) {

            $.ajax({
            url: '/hr/tardinesscomp/deletebracket',
                type: 'get',
                dataType: 'json',
                data: {
                    id          :   dataid
                },
                success: function(data){
                    $('hr[data-id="'+dataid+'"]').remove()
                    // load_allbrackets()
                    load_tardinesssetup()
                    thisrow.remove()   
                    toastr.success('Deleted successfully!', 'Time Braket')
                    $('#container-addbrackets').empty()  
                    // $('#tbody_tardinessdepartments').empty();
                }
            })
        }
    })
  })



  // activation department tardiness setup
  $(document).on('change','.checkbox-activation', function(){
    var isChecked = $(this).is(':checked');
    var deptid = $(this).closest('.checkbox-activation').data('id');
    var isactive = 0;
    if ( $(this).is(':checked') ) {
        isactive = 1
    } 
    // return false; 
    $.ajax({
        url: '/hr/tardinesscomp/activation',
        type:"GET",
        data:{
            deptid      : deptid,
            isactive    : isactive
        },
        // headers: { 'X-CSRF-TOKEN': token },,
        success: function(data){
            if(data == 1)
            {                              
                toastr.success('Activated Successfully!', 'Activation')
            }else if(data == 3){
                toastr.warning('No brackets found!', 'Activation')
            }
        }
    })
  })


  // // Add Department 
  // $(document).on('click', '#btn_adddepartment', function(){
    
  //   $('#modal_adddepartment').modal('show')
  // })

  // // Save Department Added
  // $(document).on('click', '#btn_savedepdesc', function(){
    
  //   var valid_data = true; 
  //   var departmentname = $('#department_desc').val()
  //   if (departmentname == null || departmentname == '') {
  //     toastr.warning('Department Name is Empty')
  //     valid_data = false;
  //   }
    

  //   if (valid_data) {
      
  //     $.ajax({
  //       type: "GET",
  //       url: "/hr/tardinesscomp/adddepartment",
  //       data: {departmentname : departmentname},
  //       success: function (data) {

  //         $('#tbody_tardinessdepartments').empty()
  //         if(data == 1)
          
  //           {                       
  //             load_allbrackets()       
  //             toastr.success('Added Successfully!', 'Department')
  //             $('#modal_adddepartment').modal('hide')
  //           }else if(data == 3){
  //             toastr.warning('Department Exist!', 'Department')
              
  //           }
  //       }
  //     });
  //   }
  // })


  // Add Department
  $(document).on('click', '#btn_adddepartment', function () {
    $('#modal_adddepartment').modal('show');
  });

  // Save Department Added
  $(document).on('click', '#btn_savedepdesc', function () {
    var valid_data = true;
    var departmentname = $('#department_desc').val();

    if (departmentname == null || departmentname == '') {
      toastr.warning('Department Name is Empty');
      valid_data = false;
    }

    if (valid_data) {
      $.ajax({
        type: "GET",
        url: "/hr/tardinesscomp/adddepartment",
        data: { departmentname: departmentname },
        success: function (data) {
          // load_allbrackets();
          if (data[0].status == 1) {
            var row = '<tr class="tr_perdep" data-deptid="'+data[0].dataid+'" style="cursor:pointer">' +
            '<td class="align-middle"><span class="each-department">'+departmentname+'</span></td>' +
            '<td class="text-center">' +
            '<div class="form-group m-0">' +
            '<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success switch-item"  data-id="'+data[0].dataid+'">' +
            '<input type="checkbox" class="custom-control-input custom-switch-tardiness checkbox-activation" id="switch-2'+data[0].dataid+'" data-id="'+data[0].dataid+'"/>' +
            '<label class="custom-control-label" for="switch-2'+data[0].dataid+'"></label>' +
            '<span class="badge badge-info department_tardinesscount" id="alldep" data-id="'+data[0].dataid+'">0</span>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '</tr>';
          // Append the row to the tbody
          
          $('#tbody_tardinessdepartments').append(row);
            toastr.success('Added Successfully!', 'Department');
            $('#modal_adddepartment').modal('hide');
            
          } else if (data == 3) {
            toastr.warning('Department Exist!', 'Department');
          }
        }
      });
    }
  });

  // ================================================

  // STANDARD ALLOWANCES
  $(document).on('click', '#btn_addstandardallowance', function(){
    $('#modal_addstandardallowance').modal('show')
  })
  $(document).on('click', '#btn_savestandardallowance', function(){
    var valid_data = true;
    var particular = $('#standardallowance_desc').val()
    var amount = $('#standardallowance_amount').val()
    if (amount == null || amount == '') {
      toastr.warning('Amount is Empty');
      valid_data = false;
    }
    if (particular == null || particular == '') {
      toastr.warning('Particulars is Empty');
      valid_data = false;
    }
   

    if (valid_data) {
      $.ajax({
        type: "GET",
        url: "/hr/employees/profile/addstandardallowance",
        data: {
          particular : particular,
          amount : amount
        },
        success: function (data) {
          if (data == 1) {
            $('#modal_addstandardallowance').modal('hide')
            getall_allowance()
            toastr.success('Added Successfully!', 'Standard Allowance');
          } else {
            toastr.warning('Already Exist!', 'Standard Allowance');
          }
        }
      });
    }
    
  })

  // Save Apply Other Deduction per department
  $(document).on('click', '#btn_savestandardallowanceperdep', function(){
    var depIds = $('#select2_departments').val()
    console.log(depIds);
  })
  

  // Click Plus Icon adding standard allowance per employee
  $(document).on('click', '.add_employeestandardallowance', function(){
    var action = $(this).attr('type-action')
    var empid = $('#employeeid').val()
    var stanallowance_id = $(this).attr('stan-id')
    var amount = parseFloat($('#stan_allowance'+stanallowance_id+'').val())

    if (empid == null || empid == '') {
      toastr.warning('Employee Id is Empty');
      valid_data = false;
    }
    // console.log(stanallowance_id);
    // console.log(amount);
    // return false;
    $.ajax({
      type: "GET",
      url: "/hr/employees/profile/addemployeestandardallowance",
      data: {
        empid : empid,
        stanallowance_id : stanallowance_id,
        amount : amount,
        action : action
      },
      success: function (data) {
        loademployeestandardallowance()
        if (data == 1) {
          
          toastr.success('Added Successfully!', 'Standard Allowance');
        } else if (data == 2){
          toastr.success('Updated Successfully!', 'Standard Allowance');
        }
         else {
          toastr.warning('Already Exist!', 'Standard Allowance');
        }
      }
    });
  })
  
  // ================================================
  // other functions
  function hidesalaryfields(){
    $('#select-salarytype').prop('disabled', true)
    $('#amountperday').prop('disabled', true)
    $('#hoursperday').prop('disabled', true)
    $('#paymenttype').prop('disabled', true)
    $('#attendancebased').prop('disabled', true)
  }

  function showsalaryfields(){
    $('#select-salarytype').prop('disabled', false)
    $('#amountperday').prop('disabled', false)
    $('#hoursperday').prop('disabled', false)
    $('#paymenttype').prop('disabled', false)
    $('#attendancebased').prop('disabled', false)
  }
    

  // ----------------- Select2 functions -----------------------

  function load_salarytype(){
    $('#select-salarytype').empty()
    $('#select-salarytype').append('<option value="">Select Salary Type</option>')
    $('#select-salarytype').select2({
        data: salarytypes,
        allowClear : true,
        placeholder: 'Select Salary Type'
    });
  }

  function load_shift_select2(){
    $('#select-shift').empty()
    $('#select-shift').append('<option value="">Select Shift</option>')
    $('#select-shift').select2({
        data: shifts,
        allowClear : true
    });
  }


  function load_interest_select2(){
    $('#select-interesttype').empty()
    $('#select-interesttype').append('<option value="">Type</option>')
    $('#select-interesttype').select2({
        data: interest_type,
        allowClear : true,
        placeholder: 'Type'
    });
  }
  // ----------------- load functions -------------------------

  function append_alldepartments() {
    
    var filteredalldepbrackets = allbrackets.filter(x => x.departmentid == 0);
    
    var countalldepbrackets = filteredalldepbrackets.length;

    if (filteredalldepbrackets) {
      countalldepbrackets = countalldepbrackets;
    } else {
      countalldepbrackets = 0;
    }

    if ($('#button_perdep').length > 0) {
      $('#alldep').html(countalldepbrackets)
      return; // Skip append operation if it exists
    }

  var alldepsetup = '<tr id="button_perdep" class="tr_perdep" data-deptid="0" style="cursor:pointer">' +
    '<td class="align-middle"><span class="each-department">ALL DEPARTMENT</span></td>' +
    '<td class="text-center">' +
    '<div class="form-group m-0">' +
    '<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success switch-item"  data-id="0">' +
    '<input type="checkbox" class="custom-control-input custom-switch-tardiness checkbox-activation" id="switch-10" data-id="0" />' +
    '<label class="custom-control-label" for="switch-10"></label>' +
    '<span class="badge badge-info department_tardinesscount" id="alldep"  data-id="0">' + countalldepbrackets + '</span>' +
    '</div>' +
    '</div>' +
    '</td>' +
    '</tr>';
    $('#tbody_tardinessdepartments').append(alldepsetup);
    
  }

  //load all departments
  function load_alldepartments(){
    
    $.ajax({
      type: "GET",
      url: "/hr/tardinesscomp/loadalldepartments",
      success: function (data) {
        all_loaddepartments = data
        $('#select2_departments').empty();
        $("#select2_departments").select2({
          data: all_loaddepartments,
          allowClear: true,
          placeholder: "Select Department",
          theme: 'bootstrap4'
        });
        // var dynamicTbody = $('#tbody_tardinessdepartments'); // Get the tbody element
        

        // // Clear any existing rows
        // dynamicTbody.empty();
        
        // Iterate over the data and create table rows dynamically
        for (var i = 0; i < data.length; i++) {
          var filteredalldepbrackets = allbrackets.filter(x => x.departmentid == data[i].id);
          var countalldepbrackets = filteredalldepbrackets.length;

          if (filteredalldepbrackets) {
            countalldepbrackets = countalldepbrackets;
          } else {
            countalldepbrackets = 0;
          }

          var row = '<tr class="tr_perdep" data-deptid="'+data[i].id+'" style="cursor:pointer">' +
            '<td class="align-middle"><span class="each-department">'+data[i].department+'</span></td>' +
            '<td class="text-center">' +
            '<div class="form-group m-0">' +
            '<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success switch-item"  data-id="'+data[i].id+'">' +
            '<input type="checkbox" class="custom-control-input custom-switch-tardiness checkbox-activation" id="switch-2'+data[i].id+'" data-id="'+data[i].id+'"/>' +
            '<label class="custom-control-label" for="switch-2'+data[i].id+'"></label>' +
            '<span class="badge badge-info department_tardinesscount" id="alldep" data-id="'+data[i].id+'">' + countalldepbrackets + '</span>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '</tr>';
          // Append the row to the tbody
          
          $('#tbody_tardinessdepartments').append(row);
        }
        
      }
    });
  }


  //Load all tardiness setup
  function load_allbrackets(){
    $('#tbody_tardinessdepartments').empty()
    $.ajax({
      type: "GET",
      url: "/hr/tardinesscomp/getallbrackets",
      success: function (data) {
        allbrackets = data
        append_alldepartments()
        load_alldepartments()
        var filteredbraketsactive = data.filter(x => x.isactive == 1)
        if (filteredbraketsactive.length > 0) {
          console.log(filteredbraketsactive);
        }
      }
    });
  }


  // load all tardiness setup per department
  function load_tardinesssetup(){
    deptid = $('#deptid').val()
    $.ajax({
      type: "GET",
      data: {deptid : deptid},
      url: "/hr/tardinesscomp/getbracketsperdep",
      success: function (data) {
        console.log(data);

        $('.department_tardinesscount[data-id="'+deptid+'"]').text(data.length)
        $('#container-nodata').empty()
        $('#container-brackets').empty()
        if (data.length == 0) {
          var nodata = '<span>no brackets created</span>'
          // $('#countbrackets').html(data.length)
          $('#container-nodata').append(nodata)
        } else {
          $('.custom-switch-tardiness').prop('disabled', false)
          $('#countbrackets').html(data.length)

          $.each(data, function(index, item) {
            console.log(item);
            var row = '<div class="row mb-2" data-id="0" data-deptid="'+item.id+'">'+
                                '<div class="col-md-3">'+
                                  '<div class="floatinginput">'+
                                    '<input type="number" class="input-from" value="'+item.latefrom+'" required="required">'+
                                    '<span>From (mins.)</span>'+
                                  '</div>'+
                                    // '<input type="number" class="input-from form-control form-control-sm" value="'+item.latefrom+'"/>'+
                                '</div>'+
                                '<div class="col-md-3">'+
                                  '<div class="floatinginput">'+
                                    '<input type="number" class="input-to" value="'+item.lateto+'" required="required">'+
                                    '<span>To (mins.)</span>'+
                                  '</div>'+
                                    // '<input type="number" class="input-to form-control form-control-sm"  value="'+item.lateto+'" />'+
                                '</div>'+
                                '<div class="col-md-2"hidden>'+
                                    '<select class="select-timetype form-control form-control-sm">'+
                                        '<option value="1">mins.</option>'+
                                        '<option value="2">hrs.</option>'+
                                    '</select>'+
                                '</div>'+
                                '<div class="col-md-2">'+
                                  '<select class="select-deducttype form-control form-control-sm">'+
                                    '<option value="1"'+ (item.deducttype == '1' ? ' selected' : '') +'>Fixed Amount</option>'+
                                    '<option value="2"'+ (item.deducttype == '2' ? ' selected' : '') +'>Daily Rate %</option>'+
                                  '</select>'+
                                '</div>'+
                                '<div class="col-md-2">'+
                                  '<div class="floatinginput">'+
                                    '<input type="number" class="input-amount" value="'+item.amount+'" required="required">'+
                                    '<span>% or Amount</span>'+
                                  '</div>'+
                                    // '<input type="number" class="input-amount form-control form-control-sm"  value="'+item.amount+'" />'+
                                '</div>'+
                            '<div class="col-md-2 text-center" style="cursor: pointer">'+
                                // '<i class="fa fa-check  bracket-update text-info"></i>'+
                                // '<i class="fa fa-trash text-danger" id="btn-removebracket" style="padding-left: 10px"></i>'+
                                '<a href="javascript:void(0)" class="btn-delete" data-id="'+item.id+'"><i class="fa fa-trash text-danger" style="font-size: 18px;"></i></a>&nbsp;&nbsp;&nbsp;'+
                                '<a href="javascript:void(0)" class="btn-update" data-id="'+item.id+'"><i class="far fa-edit" style="font-size: 18px;"></i></a>'+ 

                            '</div>'+
                        '</div>';
                    
          $('#container-brackets').append(row);
        });
        $('#container-addbrackets').empty()
        }
      }
    });
  }

  // load employe standard deduction
  function load_employeestandarddeduction(){
    var empid = $('#employeeid').val()

    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/load_employeestandarddeduction",
      data: {
        empid : empid
      },
      success: function (data) {
        employee_standarddeduct = data
        loadstandarddeduction()
      }
    });
  }

  // load shift schedules
 
  function load_shift(){
    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_loadshifts",
      success: function (data) {
        shifts = data
        load_shift_select2()
      }
    });
  }

  // load saved salary info
  function loadsalaryinfo(){
    var empid = $('#employeeid').val()
    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_loadsalaryinfo",
      data: {empid:empid},
      success: function (data) {
        personalsalarayinfo = data
        console.log(data[2].departmentid);
        if (data[2].departmentid == null) {
          departmenttid = 0
          $('#deptid').val(0)
        } else {
          departmenttid = data[2].departmentid
          $('#deptid').val(data[2].departmentid)
        }
        load_tardinesssetup()
        if (data[0] == null) {

          $('#hoursperday').val('')
          $('#amountperday').val('')
          $('#employee_basicsalaryid').val('')

        } else {

          $('#select-salarytype').val(data[0].salarybasistype).change()
          $('#paymenttype').val(data[0].paymenttype).change()
          $('#hoursperday').val(data[0].hoursperday)
          $('#amountperday').val(data[0].amount)
          $('#employee_basicsalaryid').val(data[0].employeeid)

        }
       
      }
    });
  }

  // load Standard Deductions
  function loadstandarddeduction(){
    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_loadstandarddeduction",
      success: function (data) {
        standard_deduction = data
        standard_datatable()
      // var html = '';
      //   $.each(data, function(index, item) {
      //   html += '<tr id="standardrow'+item.id+'">' +
      //             '<td style="vertical-align: middle!important;" id=""><input type="checkbox" id="checkstandarddeduction" sdid="'+item.id+'">&nbsp;&nbsp;<a href="javascript:void(0)"  class=" text-muted" data-id="'+item.id+'"><b>'+ item.description +'</b></a><br></td>' +
      //             '<td class="text-center p-0" style="vertical-align: middle!important;" class="text-center"  disabled><input type="checkbox" class="sc_checkbox" sdid="'+item.id+'" id="sc_amount_check'+item.id+'" disabled>&nbsp;&nbsp;<input class="text-center" id="sc_amount'+item.id+'" type="text"  placeholder="00.0" style="width: 100px;"disabled></td>' +
      //             '<td class="text-center" style="vertical-align: middle!important;" class="text-center"><input class="me_checkbox" type="checkbox" sdid="'+item.id+'" id="me_amount_check'+item.id+'" disabled>&nbsp;&nbsp;<input class="text-center" id="me_amount'+item.id+'" placeholder="00.0" type="text" id="manualentry" style="width: 100px;"disabled></td> ' +
      //           '</tr>'
      // });
      // $('#list_deduction').html(html);

      
      }
    });
  }

  function load_list_interest(){
    
    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_loadinterest",
      success: function (data) {
        interest_type = data
        loadotherdeduction()
        interest_datatable()
        load_interest_select2()
      }
    });
  }

  // load Other Deduction
  function loadotherdeduction(){
    var empid = $('#employeeid').val()

    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_loadotherdeduction",
      data: {
        empid : empid
      },
      success: function (data) {
        other_deduction = data
        other_datatable()
        modal_other_datatable()
        load_interest_select2()
      }
    });
  }

  // load employee other dedcution
  function loademployeeotherdeduction(){
    var empid = $('#employeeid').val()

    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_loademployeeotherdeduction",
      data: {
        empid : empid
      },
      success: function (data) {
        employee_other_deduction = data
        console.log(employee_other_deduction);
        other_datatable()
        modal_other_datatable()
      }
    });
  }

  // table list of Interest
  function interest_datatable(){
    $('#list_interest_table').DataTable({
      lengthMenu: false,
      info: false,
      paging: false,
      searching: true,
      destroy: true,
      lengthChange: false,
      // scrollX: true,
      autoWidth: false,
      order: false,
      data : interest_type,
      columns : [
        {"data" : 'text'},
        {"data" : null}
      ], 
      columnDefs: [
        {
          'targets': 0,
          'orderable': false, 
          'createdCell':  function (td, cellData, rowData, row, col) {
            var text = '<a href="javascript:void(0)"  class=" text-muted" data-id="'+rowData.id+'"><b>&nbsp;&nbsp;'+ rowData.text +'</b></a>';
            $(td)[0].innerHTML =  text
            $(td).addClass('align-middle  text-left  p-0')
            $(td).css('vertical-align', 'middle !important')
          }
        },
        {
          'targets': 1,
          'orderable': false, 
          'createdCell':  function (td, cellData, rowData, row, col) {
            var text = '<a href="javascript:void(0)" class="btn" ><i class="fas fa-edit text-primary" style="font-size: 18px!important;"></i></a> <a href="javascript:void(0)"  class="btn" ><i class="fas fa-trash text-danger" style="font-size: 18px!important;"></i></a>';
            $(td)[0].innerHTML =  text
            $(td).addClass('align-middle  text-center p-0')
            $(td).css('vertical-align', 'middle !important')
          }
        }
      ]
    })

    var label_text = $($('#list_interest_table_wrapper')[0].children[0])[0].children[0]
    $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm" id="btn_addinterest"><i class="fas fa-plus"></i> Add Type</button>'
  }
  
  function modal_other_datatable(){
    $('#modal_other_deduction').DataTable({
      lengthMenu: false,
      info: false,
      paging: false,
      searching: true,
      destroy: true,
      lengthChange: false,
      scrollX: false,
      autoWidth: true,
      order: false,
      data: other_deduction,
      columns : [
        {"data" : 'description'},
        {"data" : null},
        {"data" : null},
        {"data" : null},
        {"data" : null},
        {"data" : null},
        {"data" : null},
        {"data" : null}
      ],
        columnDefs: [
            {
              'targets': 0,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                var text = '<input type="checkbox" odid="'+rowData.id+'">&nbsp;&nbsp;<a href="javascript:void(0)"  class=" text-muted" data-id="'+rowData.id+'"><b>'+ rowData.description +'</b></a><br>';
                $(td)[0].innerHTML =  text
                $(td).addClass('align-middle  text-left')
                $(td).css('vertical-align', 'middle !important')
              }
            },
            {
              'targets': 1,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input class="text-center" data-id="'+rowData.id+'" placeholder="%" type="text" style="width: 100px;" value="'+rowData.interest_rate+'%" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 2,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input type="number" href="javascript:void(0)"  class="text-center" value="'+rowData.amount+'" data-id="'+rowData.id+'" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 3,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input type="date" data-id="'+rowData.id+'" class="form-control searchcontrol" data-toggle="tooltip" title="Date From" value="{{date('Y-m-01')}}" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 4,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input type="date" data-id="'+rowData.id+'"  class="form-control searchcontrol" data-toggle="tooltip" title="Date To" value="{{\Carbon\Carbon::now('Asia/Manila')->toDateString()}}" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 5,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input class="text-center" data-id="'+rowData.id+'" placeholder="00.0" type="text" style="width: 100px;" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 6,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input class="text-center" data-id="'+rowData.id+'" placeholder="00.0" type="text" style="width: 100px;" disabled readonly>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 7,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<a href="javascript:void(0)" class="" data-id="'+rowData.id+'"><i class="fas fa-plus" style="font-size: 18px!important;" ></i></a>&nbsp;&nbsp;&nbsp;'+
                  '<a href="javascript:void(0)"  class="applyall_oddeduct" data-id="'+rowData.id+'"><i class="fas fa-users text-primary" tyle="font-size: 18px!important;"></i></a>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            }
          ]
    })
    var label_text = $($('#modal_other_deduction_wrapper')[0].children[0])[0].children[0]
    $(label_text)[0].innerHTML = '<a href="javascript:void(0)" class="btn text-white btn-info btn-sm" id="btn_addotherdeduction"><i class="fas fa-plus"></i> Add Other Deduction</a>'
    
  }


  function other_datatable(){
    $('#other_deduction').DataTable({
      lengthMenu: false,
      info: false,
      paging: false,
      searching: false,
      destroy: true,
      lengthChange: false,
      scrollX: false,
      autoWidth: true,
      order: false,
      data: other_deduction,
      columns : [
        {"data" : 'description'},
        {"data" : null},
        {"data" : null},
        {"data" : null},
        {"data" : null},
        {"data" : null},
        {"data" : null},
        {"data" : null}
      ], 
        columnDefs: [
            {
              'targets': 0,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {

                var employeeotherdeduction = employee_other_deduction.filter(x=>x.deductionotherid == rowData.id)[0]
                if (employeeotherdeduction != null) {
                  // var text = '<input type="checkbox" class="custom-checkbox" checked>&nbsp;&nbsp;<a href="javascript:void(0)" class="text-info" data-id="'+rowData.id+'"><b>'+ rowData.description +'</b></a><br>';
                  var text = '<i class="far fa-check-circle text-success" style="font-size: 20px;"></i>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="text-info" data-id="'+rowData.id+'"><b>'+ rowData.description +'</b></a><br>';
                } else {
                  var text = '<input type="checkbox" id="checkotherdeduction" odid="'+rowData.id+'" fullamount="'+rowData.amount+'">&nbsp;&nbsp;<a href="javascript:void(0)"  class="text-info" data-id="'+rowData.id+'" style="font-size: 13px!important;"><b>'+ rowData.description +'</b></a><br>';
                }

                // var text = '<input type="checkbox" id="checkotherdeduction" odid="'+rowData.id+'">&nbsp;&nbsp;<a href="javascript:void(0)"  class=" text-muted" data-id="'+rowData.id+'"><b>'+ rowData.description +'</b></a><br>';
                $(td)[0].innerHTML =  text
                $(td).addClass('align-middle  text-left')
                $(td).css('vertical-align', 'middle !important')
              }
            },
            {
              'targets': 1,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {

                  var employeeotherdeduction = employee_other_deduction.filter(x=>x.deductionotherid == rowData.id)[0]
                  if (employeeotherdeduction != null) {
                    // var text = '<div class="input-group">'+
                    //               '<div class="">'+
                    //                 '<select class="form-control-sm select2" id="select-interesttypev2">'+
                    //                   '<option value="1" selected>Fixed</option>'+
                    //                   '<option value="2">Diminishing</option>'+
                    //                 '</select>'+
                    //               '</div>'+
                    //               '<input class="text-center od_interest" id="od_interest'+rowData.id+'" data-id="'+rowData.id+'" value="'+employeeotherdeduction.od_interest+'" type="text" style="width: 35px;" readonly>'+
                    //             '</div>'
                    if (employeeotherdeduction.od_interest == null) {
                      var text = '<input class="text-center od_interest" id="od_interest'+rowData.id+'" data-id="'+rowData.id+'" value="'+rowData.interest_rate+'%" type="text" style="width: 100px;"  disabled>'
                    } else {
                      var text = '<input class="text-center od_interest" id="od_interest'+rowData.id+'" data-id="'+rowData.id+'" value="'+employeeotherdeduction.od_interest+'%" type="text" style="width: 100px;"  disabled>'
                    }
                  } else {
                    
                    // var text = '<div class="input-group">'+
                    //               '<div class="">'+
                    //                 '<select class="form-control-sm select2" id="select-interesttypev2">'+
                    //                   '<option value="1" selected>Fixed</option>'+
                    //                   '<option value="2">Diminishing</option>'+
                    //                 '</select>'+
                    //               '</div>'+
                    //               '<input class="text-center od_interest" id="od_interest'+rowData.id+'" data-id="'+rowData.id+'" placeholder="%" type="text" style="width: 35px;" disabled >'+
                    //             '</div>'

                    var text = '<input class="text-center od_interest" id="od_interest'+rowData.id+'" data-id="'+rowData.id+'" value="'+rowData.interest_rate+'" placeholder="%" type="text" style="width: 100px;" disabled >'
                  }
                  // var text = '<input class="text-center od_interest" id="od_interest'+rowData.id+'" data-id="'+rowData.id+'" placeholder="%" type="text" style="width: 100px;" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 2,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                var employeeotherdeduction = employee_other_deduction.filter(x=>x.deductionotherid == rowData.id)[0]

                if (employeeotherdeduction != null) {
                  var text = '<input type="number" href="javascript:void(0)"  class="text-center od_loanamount" value="'+ employeeotherdeduction.fullamount+'" data-id="'+rowData.id+'" id="od_loanamount'+rowData.id+'" disabled>';
                } else {
                  console.log('b');
                  var text = '<input type="number" href="javascript:void(0)"  class="text-center od_loanamount" value="'+rowData.amount+'" data-id="'+rowData.id+'" id="od_loanamount'+rowData.id+'" disabled>';
                }
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 3,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var employeeotherdeduction = employee_other_deduction.filter(x=>x.deductionotherid == rowData.id)[0]
                  if (employeeotherdeduction != null) {
                    
                    // var startDate = new Date(employeeotherdeduction.startdate);
                    // var year = startDate.getFullYear();
                    // var month = (startDate.getMonth() + 1).toString().padStart(2, '0');
                    // var day = startDate.getDate().toString().padStart(2, '0');
                    // var formattedDate = year + '-' + month + '-' + day;
                    
                    var text = '<input type="date" id="od_datefrom'+rowData.id+'" data-id="'+rowData.id+'" class="form-control searchcontrol od_datefrom" data-toggle="tooltip" title="Date From" value="'+employeeotherdeduction.startdate+'" disabled>';
                  } else {
                    var text = '<input type="date" id="od_datefrom'+rowData.id+'" data-id="'+rowData.id+'" class="form-control searchcontrol od_datefrom" data-toggle="tooltip" title="Date From" value="{{date('Y-m-d')}}" disabled>';
                  }
                  // var text = '<input type="date" id="od_datefrom'+rowData.id+'" data-id="'+rowData.id+'" class="form-control searchcontrol od_datefrom" data-toggle="tooltip" title="Date From" value="{{date('Y-m-01')}}" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 4,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var employeeotherdeduction = employee_other_deduction.filter(x=>x.deductionotherid == rowData.id)[0]
                  if (employeeotherdeduction != null) {

                    // var startDate = new Date(employeeotherdeduction.enddate);
                    // var year = startDate.getFullYear();
                    // var month = (startDate.getMonth() + 1).toString().padStart(2, '0');
                    // var day = startDate.getDate().toString().padStart(2, '0');
                    // var formattedDate = year + '-' + month + '-' + day;

                    var text = '<input type="date" id="od_dateto'+rowData.id+'" data-id="'+rowData.id+'"  class="form-control searchcontrol od_dateto" data-toggle="tooltip" title="Date To" value="'+employeeotherdeduction.startdate+'" disabled>';
                  } else {
                    var text = '<input type="date" id="od_dateto'+rowData.id+'" data-id="'+rowData.id+'"  class="form-control searchcontrol od_dateto" data-toggle="tooltip" title="Date To" value="{{\Carbon\Carbon::now('Asia/Manila')->toDateString()}}" disabled>';
                  }
                  // var text = '<input type="date" id="od_dateto'+rowData.id+'" data-id="'+rowData.id+'"  class="form-control searchcontrol od_dateto" data-toggle="tooltip" title="Date To" value="{{\Carbon\Carbon::now('Asia/Manila')->toDateString()}}" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 5,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var employeeotherdeduction = employee_other_deduction.filter(x=>x.deductionotherid == rowData.id)[0]
                  
                  if (employeeotherdeduction != null) {
                    var text = '<input class="text-center od_terms" id="od_terms'+rowData.id+'" data-id="'+rowData.id+'" value="'+employeeotherdeduction.term+'" type="text" style="width: 50px;" disabled>';
                  } else {
                    var text = '<input class="text-center od_terms" id="od_terms'+rowData.id+'" data-id="'+rowData.id+'" value="'+rowData.terms+'"  placeholder="0" type="text" style="width: 50px;" disabled>';
                  }
                  // var text = '<input class="text-center od_terms" id="od_terms'+rowData.id+'" data-id="'+rowData.id+'" placeholder="00.0" type="text" style="width: 100px;" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 6,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var employeeotherdeduction = employee_other_deduction.filter(x=>x.deductionotherid == rowData.id)[0]
                  if (employeeotherdeduction != null) {
                    var text = '<input class="text-center od_amountdeduct" id="od_amountdeducted'+rowData.id+'" data-id="'+rowData.id+'" value="'+employeeotherdeduction.amount+'" type="text" style="width: 100px;" disabled readonly>';
                  } else {
                    var text = '<input class="text-center od_amountdeduct" id="od_amountdeducted'+rowData.id+'" data-id="'+rowData.id+'" value="'+rowData.amounttobededuct+'" placeholder="00.0" type="text" style="width: 100px;" disabled readonly>';
                  }
                  // var text = '<input class="text-center od_amountdeduct" id="od_amountdeducted'+rowData.id+'" data-id="'+rowData.id+'" placeholder="00.0" type="text" style="width: 100px;" disabled readonly>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 7,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {

                  var employeeotherdeduction = employee_other_deduction.filter(x=>x.deductionotherid == rowData.id)[0]
                  if (employeeotherdeduction != null) {
                    // <button class="btn btn-success btn-sm save_odeduct" data-id="'+rowData.id+'"  od_particular="'+rowData.description+'"  id="save_otherdeduct'+rowData.id+'" disabled><i class="fas fa-share-square" style="font-size: 18px!important;" ></i></button> 
                    var text = '<a href="javascript:void(0)"  class="delete_odeduct"  id="delete_employeeotherdedect'+rowData.id+'" emotherdeductid-id="'+employeeotherdeduction.id+'" ><i class="fas fa-trash text-danger" style="font-size: 18px!important;"></i></a>&nbsp;&nbsp;&nbsp;'+
                    '<a href="javascript:void(0)"  class="edit_oddeduct" data-id="'+rowData.id+'" id="edit_employeeotherdedect'+rowData.id+'" emotherdeductid-id="'+employeeotherdeduction.id+'" type-action="update"><i class="far fa-edit" id="iconsave_oddeduct'+rowData.id+'" style="font-size: 18px!important;"></i></a>&nbsp;&nbsp;&nbsp;';
                  } else {
                    var text = '<a href="javascript:void(0)" class="save_oddeduct disabled" data-id="'+rowData.id+'" od_particular="'+rowData.description+'" id="save_otherdeduct'+rowData.id+'" type-action="save" disabled><i class="fas fa-plus" style="font-size: 18px!important;" hidden></i></a>'+
                    '<a href="javascript:void(0)" class="otherdeductionsetup"><i class="far fa-window-restore" style="font-size: 18px!important;"></i></a>';
                  }
                  // var text = '<button class="btn btn-primary btn-sm save_oddeduct" data-id="'+rowData.id+'" od_particular="'+rowData.description+'" id="save_otherdeduct'+rowData.id+'" disabled><i class="fas fa-share-square" style="font-size: 18px!important;" ></i></button>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            }
          ]
    })


    
  }


  // Click Icon many users // Apply Other Deduction Per Department
  $(document).on('click', '.applyall_oddeduct', function(){
    $('#modal_addotherdeductionperdep').modal('show')
  })

  $('#select-interesttypev2').select2();
  function standard_datatable(){
    
    var table = $('#list_deduction').DataTable({
        lengthMenu: false,
        info: false,
        paging: false,
        searching: false,
        destroy: true,
        lengthChange: false,
        scrollX: true,
        autoWidth: true,
        order: false,
        data: standard_deduction,
        columns : [
            {"data" : 'description'},
            {"data" : null},
            {"data" : null},
            {"data" : null}
        ], 
        columnDefs: [
            {
                'targets': 0,
                'orderable': false, 
                'createdCell':  function (td, cellData, rowData, row, col) {
                    var filtered_sd_employee = employee_standarddeduct.filter(x=>x.deduction_typeid == rowData.id)[0]

                    if (filtered_sd_employee != null) {
                      // var text = '<input type="checkbox"  class="custom-checkbox" checked>&nbsp;&nbsp;<label href="javascript:void(0)"  class="text-info" data-id="'+rowData.id+'" style="font-size: 13px!important;"><b>'+ rowData.description +'</b></label>';
                      var text = '<i class="far fa-check-circle text-success" style="font-size: 20px; padding-left: 3px!important;"></i>&nbsp;&nbsp;&nbsp;<label href="javascript:void(0)"  class="text-muted" data-id="'+rowData.id+'" style="font-size: 13px!important;"><b>'+ rowData.description +'</b></label>';
                      
                      
                    } else {
                      var text = '<input type="checkbox" id="checkstandarddeduction" sdid="'+rowData.id+'">&nbsp;&nbsp;<a href="javascript:void(0)"  class="" data-id="'+rowData.id+'" style="font-size: 13px!important;"><b>'+ rowData.description +'</b></a>';
                    }
                    // var text = '<input type="checkbox" id="checkstandarddeduction" sdid="'+rowData.id+'">&nbsp;&nbsp;<a href="javascript:void(0)"  class=" text-muted" data-id="'+rowData.id+'"><b>'+ rowData.description +'</b></a><br>';
                    $(td)[0].innerHTML =  text
                    if (filtered_sd_employee != null) {
                      $(td).addClass('align-middle  text-left notnull p-0')
                    } else {
                      $(td).addClass('align-middle  text-left')
                    }
                    $(td).css('vertical-align', 'middle !important')
                }
            },
            {
                'targets': 1,
                'orderable': false, 
                'createdCell':  function (td, cellData, rowData, row, col) {
                    var filtered_sd_employee = employee_standarddeduct.filter(x=>x.deduction_typeid == rowData.id)[0]
                    
                    if (filtered_sd_employee != null) {
                      if (filtered_sd_employee.sc_me == 1) {
                        if (filtered_sd_employee.eesamount == null) {
                          filtered_sd_employee.eesamount = '0.00'
                        }
                        
                        // var text = '<i class="fas fa-check-square text-primary" style="font-size: 24px;"></i>&nbsp;&nbsp;&nbsp;&nbsp;<input class="text-center" id="sc_amount'+rowData.id+'" value="'+filtered_sd_employee.eesamount+'" type="text" style="width: 100px;" disabled readonly>';
                        var text = '<i class="far fa-check-circle text-success" style="font-size: 20px; padding: 0 !important;"></i>&nbsp;&nbsp;&nbsp;<input class="text-center" id="sc_amount'+rowData.id+'" value="'+filtered_sd_employee.eesamount+'" type="text" style="width: 100px;" disabled readonly>';
                      } else {
                        var text = '<input type="checkbox" class="sc_checkbox" sdid="'+rowData.id+'" id="sc_amount_check'+rowData.id+'" disabled>&nbsp;&nbsp;<input class="text-center" id="sc_amount'+rowData.id+'" placeholder="00.0" type="text" style="width: 100px;" disabled readonly>';
                      }
                      
                    } else {
                      var text = '<input type="checkbox" class="sc_checkbox" sdid="'+rowData.id+'" id="sc_amount_check'+rowData.id+'" disabled>&nbsp;&nbsp;<input class="text-center" id="sc_amount'+rowData.id+'" type="text"  placeholder="00.0" style="width: 100px;" disabled readonly>';

                    }
                    // var text = '<input type="checkbox" class="sc_checkbox" sdid="'+rowData.id+'" id="sc_amount_check'+rowData.id+'" disabled>&nbsp;&nbsp;<input class="text-center" id="sc_amount'+rowData.id+'" type="text"  placeholder="00.0" style="width: 100px;" disabled readonly>';
                    $(td)[0].innerHTML =  text
                    $(td).addClass('align-middle  text-center p-0')
                }
            },
            {
                'targets': 2,
                'orderable': false, 
                'createdCell':  function (td, cellData, rowData, row, col) {
                    var filtered_sd_employee = employee_standarddeduct.filter(x=>x.deduction_typeid == rowData.id)[0]
                    if (filtered_sd_employee != null) {

                      if (filtered_sd_employee.sc_me == 2) {
                        // var text = '<i class="fas fa-check-square text-primary" style="font-size: 24px;"></i>&nbsp;&nbsp;&nbsp;&nbsp;<input class="text-center" type="number" id="me_amount'+rowData.id+'"  value="'+filtered_sd_employee.eesamount+'"  type="text" id="manualentry" style="width: 100px;"disabled>';
                        var text = '<i class="far fa-check-circle text-success" style="font-size: 20px;"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="text-center" type="number" id="me_amount'+rowData.id+'"  value="'+filtered_sd_employee.eesamount+'"  type="text" id="manualentry" style="width: 100px;"disabled>';
                      } else {
                        var text = '<input class="me_checkbox" type="checkbox" sdid="'+rowData.id+'" id="me_amount_check'+rowData.id+'" disabled>&nbsp;&nbsp;&nbsp;&nbsp;<input class="text-center" type="number" id="me_amount'+rowData.id+'" placeholder="00.0" type="text" id="manualentry" style="width: 100px;"disabled>';
                      }
                    } else {
                      var text = '<input class="me_checkbox" type="checkbox" sdid="'+rowData.id+'" id="me_amount_check'+rowData.id+'" disabled>&nbsp;&nbsp;&nbsp;&nbsp;<input class="text-center" type="number" id="me_amount'+rowData.id+'" placeholder="00.0" type="text" id="manualentry" style="width: 100px;"disabled>';
                    }
                    // var text = '<input class="me_checkbox" type="checkbox" sdid="'+rowData.id+'" id="me_amount_check'+rowData.id+'" disabled>&nbsp;&nbsp;<input class="text-center" type="number" id="me_amount'+rowData.id+'" placeholder="00.0" type="text" id="manualentry" style="width: 100px;"disabled>';
                    $(td)[0].innerHTML =  text
                    $(td).addClass('align-middle  text-center')
                }
            },
            {
                'targets': 3,
                'orderable': false, 
                'createdCell':  function (td, cellData, rowData, row, col) {
                    var filtered_sd_employee = employee_standarddeduct.filter(x=>x.deduction_typeid == rowData.id)[0]
                    if (filtered_sd_employee != null) {
                      // <button class="btn btn-success btn-sm save_sdeduct" data-id="'+rowData.id+'" id="save_standarddedect'+rowData.id+'" disabled><i class="fas fa-share-square" style="font-size: 18px!important;" ></i></button> 
                      var text = '<a href="javascript:void(0)"  class="btn delete_sdeduct"  id="delete_employeestandarddedect'+rowData.id+'" emstandarddeductid-id="'+filtered_sd_employee.id+'" ><i class="fas fa-trash text-danger" style="font-size: 18px!important;"></i></a>';
                    } else {
                      var text = '<a href="javascript:void(0)" class="save_sdeduct disabled" data-id="'+rowData.id+'" id="save_standarddedect'+rowData.id+'"><i class="fas fa-plus" style="font-size: 18px!important;"></i></a>';
                    }
                    // var text = '<button class="btn btn-primary btn-sm save_sdeduct" data-id="'+rowData.id+'" id="save_standarddedect'+rowData.id+'" disabled><i class="fas fa-share-square" style="font-size: 18px!important;" ></i></button>';
                    $(td)[0].innerHTML =  text
                    $(td).addClass('align-middle  text-center')
                }
            }
          ]
      });

      var allRows = table.rows().nodes();

      $(allRows).each(function(index) {
        var hasClass = $(this).find('td.notnull').length > 0;
        if (hasClass) {
          $(this).css('background-color', '#1ec8f512');
        }
      });
  }

  // Allowance Setup
  function getall_allowance(){
    $.ajax({
      type: "GET",
      url: "/hr/employees/profile/loadallallowance",
      success: function (data) {
        all_allowances = data
        allowance_setup()
      }
    });
  }

  function allowance_setup(){

    $('#allowance_setup').DataTable({
      lengthMenu: false,
        info: false,
        paging: true,
        searching: true,
        destroy: true,
        lengthChange: false,
        scrollX: true,
        autoWidth: true,
        order: false,
        data: all_allowances,
        columns : [
            {"data" : 'description'},
            {"data" : null},
            {"data" : null}
        ],
        columnDefs: [
          {
            'targets': 0,
            'orderable': false, 
            'createdCell':  function (td, cellData, rowData, row, col) {
                var buttons = '<span href="javascript:void(0)" data-id="'+rowData.id+'">'+rowData.description+'</span>';
                $(td)[0].innerHTML =  buttons
                $(td).addClass('text-left')
                $(td).addClass('align-middle')
            }
          },
          {
            'targets': 1,
            'orderable': false, 
            'createdCell':  function (td, cellData, rowData, row, col) {
                var filtereddata = per_employeestandardallowance.filter(x=>x.allowance_standardid == rowData.id)[0]
                if (filtereddata != null) {
                  var text = '<input class="text-center stan_allowance" type="number" id="stan_allowance'+rowData.id+'" value="'+filtereddata.amount+'.00" style="width: 100px;" disabled>';
                } else {
                  var text = '<input class="text-center stan_allowance" type="number" id="stan_allowance'+rowData.id+'" value="'+rowData.amount+'" style="width: 100px;">';
                }
                $(td)[0].innerHTML =  text
                $(td).addClass('align-middle  text-center')
            }
          },
          {
            'targets': 2,
            'orderable': false, 
            'createdCell':  function (td, cellData, rowData, row, col) {

                var filtereddata = per_employeestandardallowance.filter(x=>x.allowance_standardid == rowData.id)[0]
                console.log('masaya');
                console.log(filtereddata);
                if (filtereddata != null) {
                  var buttons = '<a href="javascript:void(0)" class="delete_stanallowance" id="delete_stanallowance'+rowData.id+'" data-id="'+rowData.id+'"><i class="fas fa-trash text-danger" style="font-size: 18px;"></i></a>&nbsp;&nbsp;&nbsp;'+
                  '<a href="javascript:void(0)"  class="edit_stanallowance" id="edit_stanallowance'+rowData.id+'" data-id="'+rowData.id+'" type-action="update" stan-id="'+rowData.id+'"><i class="far fa-edit icon_stanallowance" id="icon_stanallowance'+rowData.id+'" style="font-size: 18px;"></i></a>&nbsp;&nbsp;&nbsp;'+
                  '<a href="javascript:void(0)"  class="applyall_oddeduct" data-id="'+rowData.id+'"><i class="fas fa-users text-primary" tyle="font-size: 18px!important;"></i></a>';
                } else {
                  var buttons = '<a href="javascript:void(0)" class="add_employeestandardallowance" data-amount="'+rowData.amount+'" stan-id="'+rowData.id+'" type-action="save"><i class="fas fa-plus" style="font-size: 18px;"></i></a>';
                }
                $(td)[0].innerHTML =  buttons
                $(td).addClass('text-center')
                $(td).addClass('align-middle')
            }
          }
        ]
    })

    var label_text = $($('#allowance_setup_wrapper')[0].children[0])[0].children[0]
    $(label_text)[0].innerHTML = '<div class="row" style="padding-bottom: 10px!important;"><div class="col-md-12 col-sm-12"><button class="btn btn-primary btn-sm" id="btn_addstandardallowance"><i class="fas fa-plus"></i> Add Standard Allowance</button></div></div>'
  }
  
  // Edit Employee Standard Allowance
  $(document).on('click', '.edit_stanallowance', function(){
    var emp_stanid = $(this).attr('data-id')
    var empid = $('#employeeid').val()
    var stanallowance_id = $(this).attr('stan-id')
    
    $('.edit_stanallowance').addClass('add_employeestandardallowance')
    $('.edit_stanallowance').removeClass('edit_stanallowance')
    $('#icon_stanallowance'+emp_stanid+'').addClass('text-success')
    $('#stan_allowance'+emp_stanid+'').prop('disabled', false)
    
    console.log(emp_stanid);
  })
  // delete Employee Standard Allowance
  $(document).on('click', '.delete_stanallowance', function(){
    var emp_stanid = $(this).attr('data-id')
    var empid = $('#employeeid').val()
    $.ajax({
      type: "GET",
      url: "/hr/employees/profile/deleteemployeestandardallowance",
      data: {
        emp_stanid : emp_stanid,
        empid : empid
      },
      success: function (data) {
        loademployeestandardallowance()
        if(data[0].status == 0){
            Toast.fire({
              type: 'error',
              title: data[0].message
            })
          }else{
            Toast.fire({
              type: 'success',
              title: data[0].message
            })
          }
      }
    });
  })

  // load all employee standard allowance
  function loademployeestandardallowance(){
    var empid = $('#employeeid').val()

    $.ajax({
      type: "GET",
      url: "/hr/employees/profile/loademployeestandardallowance",
      data: {empid : empid},
      success: function (data) {
        per_employeestandardallowance = data
        getall_allowance()
      }
    });
  }
  // ----------------- ajax functions -------------------------
  // save basic salary information
  function basic_salaryinfo(){
    valid_data = true

    $('#hoursperday').removeClass('is-invalid')
    $('#amountperday').removeClass('is-invalid')

    $('#timepickeramin').removeClass('is-invalid')
    $('#timepickeramout').removeClass('is-invalid')
    $('#timepickerpmin').removeClass('is-invalid')
    $('#timepickerpmout').removeClass('is-invalid')
    var shiftid = $('#select-shift').val()
    var salarytype = $('#select-salarytype').val()
    var empid = $('#employeeid').val()
    var amountperday = $('#amountperday').val()
    var hoursperday = $('#hoursperday').val()
    var paymenttype = $('#paymenttype').val()
    var workshift = $('input[name="workshift"]:checked').val()
    var am_in = $('#timepickeramin').val()
    var am_out = $('#timepickeramout').val()
    var pm_in = $('#timepickerpmin').val()
    var pm_out = $('#timepickerpmout').val()
    var attendance_switch = $('#attendance_switch').val()
    
    var timeamin = $('#timepickeramin').val();
    var timeamout = $('#timepickeramout').val();
    var timepmin = $('#timepickerpmin').val();
    var timepmout = $('#timepickerpmout').val();

    if (attendance_switch == null || attendance_switch == '') {
      attendance_switch = 0
    } else if (attendance_switch == 1){

      if (am_in == '' || am_in == null) {
        $('#timepickeramin').addClass('is-invalid')
        Toast.fire({
            type: 'error',
            title: 'AM In is empty'
        })
        valid_data= false
      }
      if (am_out == '' || am_out == null) {
        $('#timepickeramout').addClass('is-invalid')
        Toast.fire({
            type: 'error',
            title: 'AM Out is empty'
        })
        valid_data= false
      }

      if (pm_in == '' || pm_in == null) {
        $('#timepickerpmin').addClass('is-invalid')
        Toast.fire({
            type: 'error',
            title: 'PM In is empty'
        })
        valid_data= false
      }

      if (pm_out == '' || pm_out == null) {
        $('#timepickerpmout').addClass('is-invalid')
        Toast.fire({
            type: 'error',
            title: 'PM Out is empty'
        })
        valid_data= false
      }

    } else {
      attendance_switch = attendance_switch
    }

    if (workshift == '' || workshift == null) {
      workshift = 0
    }

    if (hoursperday == '' || hoursperday == null) {
      $('#hoursperday').addClass('is-invalid')
      Toast.fire({
          type: 'error',
          title: 'Set hours per Day'
      })
      valid_data= false
    }

    if (amountperday == '' || amountperday == null) {
      $('#amountperday').addClass('is-invalid')
      Toast.fire({
          type: 'error',
          title: 'No Salary Amount Entered'
      })
      valid_data= false
    }


    if (salarytype == "") {
      Toast.fire({
          type: 'error',
          title: 'No Salary Selected'
      })
      valid_data= false
    }

    
    if (valid_data) {
      $.ajax({
        type: "GET",
        url: "/payrollclerk/employees/profile/v2_storesalaryinfo",
        data: {
          shiftid : shiftid,
          empid : empid,
          salarytype : salarytype,
          amountperday : amountperday,
          hoursperday : hoursperday,
          paymenttype : paymenttype,
          workshift : workshift,
          attendance_switch : attendance_switch,
          am_in : am_in,
          am_out : am_out,
          pm_in : pm_in,
          pm_out : pm_out
        },
        success: function (data) {
          loadsalaryinfo()
          $('.btn-view-profile[data-id="{{$employeeid}}"]').click();
          if(data[0].status == 0){
            Toast.fire({
              type: 'error',
              title: data[0].message
            })
          }else{
            
            Toast.fire({
              type: 'success',
              title: data[0].message
            })
          }
        }
      });
    }
  }

  // ----------------- datatable functions --------------------
  $('#btn-reload-profile').on('click', function(){
    $('.btn-view-profile[data-id="{{$employeeid}}"]').click();
    $('#ccontainer-addbrackets').empty()
  })
});

  
</script>
