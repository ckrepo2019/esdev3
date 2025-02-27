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
  #switchlabel{
    top: -5px;
    position: relative;
  }
  input[type="radio"] {
      width: 20px;
      height: 20px;
    }
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
  
  input[type="checkbox"] {
    margin-right: 0em !important;
  }
  table.dataTable.table-sm > thead > tr > th {
    padding-right: 0px !important;
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

  {{-- MODAL ADD FOR ALLOWANCES SETUPs --}}
  <div class="modal fade" tabindex="-1" role="dialog" id="modal_allallowances">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Apply Allowances</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row p-0"  style="overflow-x: scroll!important;">
            <div class="col-md-12">
              <table width="100%" class="table table-sm" style="table-layout: fixed; font-size: 15px;" id="table_all_allowances">
                <thead>
                  <tr>
                    <th width="60%" class="">Description</th>
                    <th width="15%" class="text-center"></th>
                    <th width="15%" class="text-center">Amount</th>
                    <th width="10%" class="text-center">Action</th>
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
              <input type="hidden" id="boa_employeeid">
              <div class="form-group">
                <label for="particular">Particulars</label>
                <input type="text" class="form-control" id="standardallowance_desc" placeholder="Enter Description" onkeyup="this.value = this.value.toUpperCase();" >
              </div>
            </div>
            <div class="col-md-7">
              <div class="form-group mb-0" style="padding-left: 4px!important;">
                <div class="form-check pr-3">
                  <input class="form-check-input baseonattendance" type="checkbox">
                  <label class="form-check-label text-info">&nbsp;<b>Base on Attendance</b></label>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="floatinginput">
                  <input type="number" id="boa_amountperday"  class="form-control" name="boa_amountperday"/>
                  <span class="text-info"><b>Amount Per Day</b></span>
                  <span class="invalid-feedback" role="alert">
                  {{-- <strong>Amount per day is required</strong> --}}
              </div>
            </div>
            <div class="col-md-12" style="margin-top: -9px;">
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
              <table width="100%" class="table table-sm" style="table-layout: fixed; font-size: 15px;" id="modal_other_deduction">
                <thead>
                  {{-- <tr>
                    <th width="23%">Description</th>
                    <th width="12%" class="text-center">Interest Rate</th>
                    <th width="11%" class="text-center">Loan Amount</th>
                    <th width="11%" class="text-center">Start Date</th>
                    <th width="11%" class="text-center">End Date</th>
                    <th width="7%" class="text-center">Terms</th>
                    <th width="13%" class="text-center">Amount / Period</th>
                    <th width="10%" class="text-center">Action</th>
                  </tr> --}}
                  <tr>
                    <th width="20%">Description</th>
                    <th width="10%" class="text-center"><span class="slant">Interest Rate</span></th>
                    <th width="10%" class="text-center"><span class="slant">Loan Amount</span></th>
                    <th width="13%" class="text-center"><span class="slant">Start Date</span></th>
                    <th width="13%" class="text-center"><span class="slant">End Date</span></th>
                    <th width="7%" class="text-center"><span class="slant">Terms</span></th>
                    {{-- <th width="10%" class="text-center"><span class="slant">Period</span></th> --}}
                    <th width="15%" class="text-center"><span class="slant">Amount per Period</span></th>
                    <th width="12%" class="text-center"><span class="slant">Action</span></th>
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
              <input type="hidden" id="od_otherdeduction_id">
              <input type="text" class="form-control" id="od_particular" placeholder="Enter Particular" onkeyup="this.value = this.value.toUpperCase();" >
              <span class="invalid-feedback" role="alert">
                <strong>Particular is required</strong>
              </span>
            </div>
            <div class="row">
              <div class="col-md-5">
                <div class="form-group">
                  <label for="amount">Amount</label>
                  <input type="number" class="form-control" id="mod_odamount" placeholder="0.00">
                  <span class="invalid-feedback" role="alert">
                    <strong>Amount is required</strong>
                  </span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="amount">Interest Rate (%)</label>
                  <input type="number" class="form-control" id="mod_odineterst" placeholder="%">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="amount">Term</label>
                  <input type="number" class="form-control" id="mod_odterms" placeholder="0.00">
                </div>
              </div>
            </div>
            {{-- <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="amount">Term</label>
                  <input type="number" class="form-control" id="mod_odterms" placeholder="0.00">
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label for="amount">Period</label>
                  <select class="select2 form-control form-control-sm text-uppercase" name="period" placeholder="Select Period" id="mod_period">
                    <option value="1">Monthly</option>
                    <option value="2">Every 15 Days</option>
                  </select>
                </div>
              </div>
            </div> --}}
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
                  <input type="date" id="mod_oddateto" class="form-control searchcontrol mod_oddateto" data-toggle="tooltip" title="Date To">
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
              <button type="button" class="btn btn-success" id="btn_updateotherdeduction"><span><i class="fas fa-plus"></i></span> Save</button>
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
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Other Deduction ( <span id="desc" style="font-weight: bold;"></span> )</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {{-- <div class="row">
            <div class="col-md-4 col-sm-12 text-left">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input applyall_oddeductsetup" type="checkbox">
                  &nbsp;&nbsp;<label>Apply to All Department</label>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-sm-12 text-left">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input applyall_oddeductsetup" type="checkbox">
                  &nbsp;&nbsp;<label>Apply to All Department</label>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-sm-12 text-left">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input applyperdep_oddeductsetup" type="checkbox" data-assign="">
                  &nbsp;&nbsp;<label>Per Department</label>
                </div>
              </div>
            </div>
          </div> --}}
          <div class="row">
            <div class="col-md-12">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="tab_peremployee" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Per Employee</a>
                </li>
                {{-- <li class="nav-item">
                  <a class="nav-link" id="tab_perdepartment" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Per Department</a>
                </li> --}}
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="tab_peremployee">
                  <div class="row"  style="overflow-x: scroll!important; margin-top: 17px;">
                    <div class="col-md-12">
                      <table width="100%" class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px;" id="modal_preemployee">
                        <input type="hidden" id="otherdeductionid">
                        <input type="hidden" id="mod_interestrate">
                        <input type="hidden" id="mod_loanamount">
                        <input type="hidden" id="mod_sdate">
                        <input type="hidden" id="mod_edate">
                        <input type="hidden" id="mod_terms">
                        <input type="hidden" id="mod_amounttobededuct">

                        <thead>
                          <tr>
                            <th width="55%">Employee Name</th>
                            <th width="35%" class="text-center">Remarks</th>
                            <th width="10%" class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="tab_perdepartment">
                  <div class="row"  style="overflow-x: scroll!important; margin-top: 17px;">
                    <div class="col-md-12">
                      <table width="100%" class="table table-sm mb-0" style="font-size: 15px;" id="modal_perdepartment">
                        <thead>
                          <tr>
                            <th width="55%">Department Name</th>
                            <th width="35%" class="text-center">Remarks</th>
                            <th width="10%" class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                  {{-- <div class="row">
                    <div class="col-md-12 col-sm-12 select2departments" hidden>
                      <select id="select2_departments" class="js-select-multiple form-control form-control-sm" multiple data-placeholder="Select Department"></select>
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-12 justify-content-between" style="display: flex;">
              <button type="button" class="btn btn-primary btn-sm assigning assign_peremployee"><i class="fas fa-plus"></i> Assign</button>
              <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- MODAL ADD PER DEP OR APPLY ALL STANDARD ALLOWANCE --}}
  <div class="modal fade" tabindex="-1" role="dialog" id="modal_addstandardallwoanceperdep">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Standard Allowance ( <span id="desc_sa" style="font-weight: bold;"></span> )</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="tab_peremployee" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Per Employee</a>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="tab_peremployee">
                  <div class="row"  style="overflow-x: scroll!important; margin-top: 17px;">
                    <div class="col-md-12">
                      <table width="100%" class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px;" id="modal_permployeestandardallowance">
                        <input type="hidden" id="mod_amountperday">
                        <input type="hidden" id="mod_baseonattendance">
                        <input type="hidden" id="mod_amount">
                        <input type="hidden" id="mod_standarallowancedid">
                        <thead>
                          <tr>
                            <th width="55%">Employee Name</th>
                            <th width="35%" class="text-center">Remarks</th>
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
          </div>
          <hr>
          <div class="row">
            <div class="col-md-12 justify-content-between" style="display: flex;">
              <button type="button" class="btn btn-primary btn-sm assigning_sa assign_peremployee_sa"><i class="fas fa-plus"></i> Assign</button>
              <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- MODAL VIEW SPECIFIC EMPLOYEE OTHER DEDUCTION --}}
  <div class="modal fade" tabindex="-1" role="dialog" id="modal_view_oddeduct">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><span class="text-info" id="otherdeductionname" style="font-weight: bold"></span> OTHER DEDUCTION</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12"> 
              <span>Loan Amount <span id="loadnamount" style="padding-left: 19px;"></span></span><br>
              <span>Interest Amount <span id="interestamount"></span></span><br>
              <div style="height: 2px;
              border: 1px solid #000;
              width: 300px;"></div>
              <span>Total <span class="tet-info" id="overall" style="padding-left: 81px;"></span></span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12" id="viewdeductionledger"></div>
            <div class="col-md-12 justify-content-between" style="display: flex;">
              <button type="button" class="btn btn-primary btn-sm" id="btn_savestandardallowance" style="visibility: hidden"><i class="fas fa-plus"></i> Add</button>
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
                    <span class="py-1 fw-bold mb-0 mt-1 text-muted medium"><span><b>Designation :</b></span><span class="ms-2 text-muted medium" id="designationss"> {{$employeeinfo->designation}}</span></span><br/>
                  </div>
                  <div class="row g-2">
                    <span class="text-muted medium"><span class="ms-2 text-muted medium"><b>Teacher ID. :</b> {{$employeeinfo->tid}}</span>
                  </div>
                  <div class="row g-2">
                    <span class="text-muted medium"><b>Employment Status :</b> &nbsp;</span> @if($employeeinfo->employmentstatus == null || $employeeinfo->employmentstatus == "")<span class="badge badge-secondary"> Unset</span> @else <span class="badge badge-primary">{{$employeeinfo->employmentstatus}}</span> @endif
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
                    <input type="text" class="form-control form-control-sm " name="title" id="profiletitle" value="{{$employeeinfo->title}}" oninput="this.value = this.value.toUpperCase()" style="height: 37px!important;">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                    <small class="text-bold" style="font-size: 15px;">First Name</small>
                    <input type="text" class="form-control form-control-sm" name="fname" id="profilefname" value="{{$employeeinfo->firstname}}" oninput="this.value = this.value.toUpperCase()" style="height: 37px!important;">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                    <small class="text-bold" style="font-size: 15px;">Middle Name</small>
                    <input type="text" class="form-control form-control-sm" name="mname" id="profilemname" value="{{$employeeinfo->middlename}}" oninput="this.value = this.value.toUpperCase()" style="height: 37px!important;">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <small class="text-bold" style="font-size: 15px;">Last Name</small>
                  <input type="text" class="form-control form-control-sm" name="lname" id="profilelname" value="{{$employeeinfo->lastname}}" oninput="this.value = this.value.toUpperCase()" style="height: 37px!important;">
                </div>
              </div>
              <div class="col-md-1">
                <div class="form-group">
                  <small class="text-bold" style="font-size: 15px;">Suffix</small>
                  <input type="text" class="form-control form-control-sm" name="suffix" id="profilesuffix" value="{{$employeeinfo->suffix}}" oninput="this.value = this.value.toUpperCase()" style="height: 37px!important;">
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
                        <input class="form-control datetimepicker form-control-sm" type="date" name="dob"  id="profiledob" value="{{ \Carbon\Carbon::parse($employeeinfo->dob)->toDateString() }}" style="height: 37px!important;" required/>
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
                      <select class="select2 form-control text-uppercase form-control-sm" name="religionid"  id="profilereligionid">
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
                      !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" style="height: 37px!important;">
                  </div>
              </div>
              <div class="col-md-7">
                  <div class="form-group">
                      <small class="text-bold" style="font-size: 15px;">Employment of Spouse</small>
                      <input type="text" class="form-control text-uppercase form-control-sm" name="spouseemployment"  id="profilespouseemployment" value="{{$employeeinfo->spouseemployment}}" style="height: 37px!important;">
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
                <input type="text" class="form-control text-uppercase form-control-sm" name="address"  id="profileaddress" value="{{$employeeinfo->address}}" style="height: 37px!important;">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <small class="text-bold" style="font-size: 15px;">Primary Address</small>
                <input type="text" class="form-control text-uppercase form-control-sm" name="address"  id="profileprimaryaddress" value="{{$employeeinfo->primaryaddress}}" style="height: 37px!important;">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <small class="text-bold" style="font-size: 15px;">Email Address</small>
                <input type="email" class="form-control form-control-sm" name="email" id="profileemail" value="{{$employeeinfo->email}}" style="height: 37px!important;">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <small class="text-bold" style="font-size: 15px;">Contact Number</small>
                <input type="text" class="form-control form-control-sm" id="contactnum" name="contactnum" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" value="{{$employeeinfo->contactnum}}" style="height: 37px!important;">
            </div>
        </div>
      </div>
      <hr/>
      <div class="row">
          <div class="col-md-3">
              <div class="form-group">
                  <small class="text-bold" style="font-size: 15px;">Lincense No.</small>
                  <input type="text" name="licenseno" class="form-control form-control-sm" id="profilelicenseno" value="{{$employeeinfo->licno}}" style="height: 37px!important;">
              </div>
          </div>
          <div class="col-md-2">
              <div class="form-group">
                  <small class="text-bold" style="font-size: 15px;">Date Hired</small>
                  <input type="date" name="datehired" class="form-control datetimepicker form-control-sm" id="profiledatehired" value="{{ \Carbon\Carbon::parse($employeeinfo->date_joined)->toDateString() }}" style="height: 37px!important;">
              </div>
          </div>
          <div class="col-md-2">
              <div class="form-group">
                  <small class="text-bold" style="font-size: 15px;">Department</small>
                  <select class="form-control form-control-sm select2" id="select-department"></select>
              </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
                <small class="text-bold" style="font-size: 15px;">Designation</small>
                <select class="form-control form-control-sm select2" id="select-designation"></select>
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
                <span class="invalid-feedback" role="alert">
                  <strong>Salary Type is required</strong>
                </span>
            </div>
        </div>
        <div class="col-md-9" id="daily_section">
          <div class="row">
            <div class="col-md-4">
              <label id="label_salaryamount">Salary amount  <span id="saltypename"></span></label>
              <input type="number" step="any" class="form-control" name="amountperday" id="amountperday"/>
              <span class="invalid-feedback" role="alert">
              <strong>Salary amount <span id="saltypename"></span> is required</strong>
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
        <div class="col-md-12">
          <label><i class="fas fa-asterisk"></i> SALARY BASED ON ATTENDANCE</label>
          <br/>
        </div>
        <div class="col-md-2 mb-2" >
          <label class="radio-inline">
            <input class="attendancebased" type="radio" name="switch" value="1" @if($basicsalaryinfo) @if ($basicsalaryinfo->attendancebased == 1) checked @endif @endif>
            <span id="switchlabel">ON</span>
          </label> &nbsp;&nbsp;&nbsp;
        
          <!-- Radio button for Female with Bootstrap styles -->
          <label class="radio-inline">
            <input class="attendancebased" type="radio" name="switch" value="0" @if($basicsalaryinfo) @if ($basicsalaryinfo->attendancebased == 0) checked @endif @endif>
            <span id="switchlabel">OFF</span>
          </label>
          {{-- <input type="checkbox" id="attendancebased" name="my-checkbox" data-off-color="danger" data-on-color="success"  @if($basicsalaryinfo) @if ($basicsalaryinfo->attendancebased == 1) value="1" @endif @endif checked> --}}
          <input type="hidden" id="attendance_switch"  @if($basicsalaryinfo) @if ($basicsalaryinfo->attendancebased == 1) value="1" @endif value="0" @endif >
        </div>
        <div class="col-md-10">
          <div class="form-group mb-0" style="display: -webkit-box;" id="days_selected">
            <div class="form-check pr-3">
              <input class="form-check-input workingdayscheck" id="mon" type="checkbox" @if($basicsalaryinfo) @if ($basicsalaryinfo->mondays == 1) checked @endif @endif value="1">
              <small style="font-size: 18px;"><label class="form-check-label text-info">&nbsp;M</label></small>
            </div>
            <div class="form-check pr-3">
              <input class="form-check-input workingdayscheck" id="tue" type="checkbox" @if($basicsalaryinfo) @if($basicsalaryinfo->tuesdays == 1) checked @endif @endif value="2">
              <small style="font-size: 18px;"><label class="form-check-label text-info">&nbsp;T</label></small>
            </div>
            <div class="form-check pr-3">
              <input class="form-check-input workingdayscheck" id="wed" type="checkbox" @if($basicsalaryinfo) @if($basicsalaryinfo->wednesdays == 1) checked @endif @endif value="3">
              <small style="font-size: 18px;"><label class="form-check-label text-info">&nbsp;W</label></small>
            </div>
            <div class="form-check pr-3">
              <input class="form-check-input workingdayscheck" id="thu" type="checkbox" @if($basicsalaryinfo) @if($basicsalaryinfo->thursdays == 1) checked @endif @endif value="4">
              <small style="font-size: 18px;"><label class="form-check-label text-info">&nbsp;Th</label></small>
            </div>
            <div class="form-check pr-3">
              <input class="form-check-input workingdayscheck" id="fri" type="checkbox" @if($basicsalaryinfo) @if($basicsalaryinfo->fridays == 1) checked @endif @endif value="5">
              <small style="font-size: 18px;"><label class="form-check-label text-info">&nbsp;F</label></small>
            </div>
            <div class="form-check pr-3">
              <input class="form-check-input workingdayscheck" id="sat" type="checkbox" @if($basicsalaryinfo) @if($basicsalaryinfo->saturdays == 1) checked @endif @endif value="6">
              <small style="font-size: 18px;"><label class="form-check-label text-info">&nbsp;Sat</label></small>
            </div>
            <div class="form-check pr-3">
              <input class="form-check-input workingdayscheck" id="sun" type="checkbox" @if($basicsalaryinfo) @if($basicsalaryinfo->sundays == 1) checked @endif @endif value="7">
              <small style="font-size: 18px;"><label class="form-check-label text-info">&nbsp;Sun</label></small>
            </div>
          </div>
          <span class="invalid-feedback" role="alert" id="days_error">
            <strong>Days are is required</strong>
          </span>
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
              <span class="invalid-feedback" role="alert">
                <strong>Shift is required</strong>
              </span>
          </div>
      </div>
      </div>
      <div class="row">
        {{-- @if(count($timeschedule) == 0) --}}
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
        {{-- @else
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
        @endif --}}
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
                <th width="29%">Description</th>
                <th width="10%" class="text-center"><span class="slant">Interest Rate</span></th>
                <th width="11%" class="text-center"><span class="slant">Loan Amount</span></th>
                <th width="10%" class="text-center"><span class="slant">Start Date</span></th>
                <th width="10%" class="text-center"><span class="slant">End Date</span></th>
                <th width="7%" class="text-center"><span class="slant">Terms</span></th>
                {{-- <th width="10%" class="text-center"><span class="slant">Period</span></th> --}}
                <th width="11" class="text-center"><span class="slant">Amount per Period</span></th>
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
  {{-- <div class="card">
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
  </div> --}}

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
                <th width="60%">Description</th>
                <th width="15%" class="text-center"></th>
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

<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>
<script>
  $(document).ready(function() {
    function getLastDayOfMonth() {
      var currentDate = new Date();
      var lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
      var lastDayFormatted = lastDay.toISOString().slice(0, 10);
      return lastDayFormatted;
    }

    // Set the value of the input field to the last day of the current month
    $('#mod_oddateto').val(getLastDayOfMonth());
    
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
<script>
  $(document).ready(function() {
    const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000,
  });
  
  var salarytypes = @json($salarytypes);
  var attendancebased = @json($attendancebased);

  console.log(attendancebased);
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
  var allemployee = []
  var alldep_otherdeduction = []
  var allemployeeotherdeduction = []
  // getperemployeelist()
  // load_emshiftsched()
  getallemployeeotherdeduction()
  getallemployeestandardallowance()
  // getalldepartments()
  load_shift()
  loadsalaryinfo()
  load_salarytype()
  loadstandarddeduction()
  
  load_employeestandarddeduction()
  loadotherdeduction()
  loademployeeotherdeduction()
  load_list_interest()
  load_allbrackets()
  loademployeestandardallowance()
  getall_allowance()
  select_designations()
  select_departments()
  loadpersonalinfo()
 
  // ----------------- variable declaration --------------------
  // var basicsalaryinfo = @json($basicsalaryinfo);

  $('#btn_saveotherdeduction').show()
  $('#btn_updateotherdeduction').hide()
  
  $('#manualentry').attr('disabled', true)
  $('#mod_period').select2()
  $('#profilenationalityid').select2()
  $('#select-shift').select2()
  $('#profilegender').select2()
  $('#profilecivilstatusid').select2()
  $('#profilereligionid').select2()
  $('#paymenttype').select2()

  // $('#attendancebased').bootstrapSwitch();
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
    var currentDate = new Date();

    // Format the date as "yyyy-mm-dd"
    var formattedDate = currentDate.toISOString().split('T')[0];

    $('#od_particular').val('')
    $('#mod_odamount').val('')
    $('#mod_odterms').val('')
    $('#mod_oddatefrom').val(formattedDate);
    $('#mod_oddateto').val(formattedDate)
    $('#mod_odineterst').val('')
    $('#mod_odamountdeduct').val('0.00')
  })
 // close adding department modal
  $('#modal_adddepartment').on('hide.bs.modal', function (e) {
    $('#department_desc').val('')
  })
  // close adding department modal
  $('#modal_addstandardallowance').on('hide.bs.modal', function (e) {
    $('#standardallowance_desc').val('')
    $('#standardallowance_amount').val('')
    $('#boa_amountperday').val('')
    $('#baseonattendance').removeAttr('checked');
  })

  $('#modal_addotherdeductionperdep').on('hide.bs.modal', function (e) {
    var otherdeductionid = 0;
    $('.select2departments').prop('hidden', true)
    $('.applyperdep_oddeductsetup').prop('checked', false)
    
    getalldepartments(otherdeductionid)
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
    var departmentid = $('#select-department').val();
    var designationid = $('#select-designation').val();
    var designationname = $('#select-designation option:selected').text();

    $('#designationss').text(designationname)
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
        profiledatehired : profiledatehired,
        departmentid : departmentid,
        designationid : designationid
      },
      success: function (data) {
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

   // Click Edit Icon in Modal Apply Other Deduction
  $(document).on('click', '.edit_otherdeduction', function(){
    var odid = $(this).attr('data-id')
    $('#mod_oddatefrom').val("");

    $('#btn_saveotherdeduction').hide()
    $('#btn_updateotherdeduction').show()
    $('#mod_odamount').attr('disabled', true)
    $('#mod_odineterst').attr('disabled', true)
    $('#mod_odterms').attr('disabled', true)
    $('#mod_oddatefrom').attr('disabled', true)
    $('#mod_oddateto').attr('disabled', true)
    $('#mod_period').attr('disabled', true)
    $('#mod_odamountdeduct').attr('disabled', true)
    
    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_editotherdeduction",
      data: {
        odid : odid
      },
      success: function (data) {
        var endDateStr = data[0].enddate; // Store the date string "2023-08-31 00:00:00"
        var startDateStr = data[0].startdate;
        var endDate = new Date(endDateStr);
        var startDate = new Date(startDateStr);

        var endYear = endDate.getFullYear();
        var endMonth = String(endDate.getMonth() + 1).padStart(2, "0");
        var endDay = String(endDate.getDate()).padStart(2, "0");
        var enddate = `${endYear}-${endMonth}-${endDay}`;

        var startYear = startDate.getFullYear();
        var startMonth = String(startDate.getMonth() + 1).padStart(2, "0");
        var startDay = String(startDate.getDate()).padStart(2, "0");
        var startDateFormatted = `${startYear}-${startMonth}-${startDay}`;
        
        $('#od_otherdeduction_id').val(data[0].id)
        $('#od_particular').val(data[0].description)
        $('#mod_odamount').val(data[0].amount)
        $('#mod_odineterst').val(data[0].interest_rate)
        $('#mod_odterms').val(data[0].terms)
        $('#mod_oddatefrom').val(startDateFormatted)
        $('#mod_oddateto').val(enddate)
        $('#mod_period').val(data[0].periodid).change()
        $('#mod_odamountdeduct').val(data[0].amounttobededuct)

        $('#modal_otherdeduction').modal('show')
      }
    });
  })

  $(document).on('click', '#btn_updateotherdeduction', function(){
    var otherdeduction_id = $('#od_otherdeduction_id').val()
    var otherdeduction_desc = $('#od_particular').val()

    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_updateotherdeduction",
      data: {
        otherdeduction_id : otherdeduction_id,
        otherdeduction_desc : otherdeduction_desc
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
          Toast.fire({
            type: 'success',
            title: data[0].message
          })
        }
      }
    });
  })

  

  // click save changes in basic salary info
  $(document).on('click', '#basicsalary_submitbutton', function(){
    basic_salaryinfo()
  })

  // select salary type
  
  $(document).on('change', '#select-salarytype', function(){
    var saltype = $('#select-salarytype').val()

    if (saltype == 5) {
		$('#saltypename').text('per day')
      $('#daily_section').show();
    } else if(saltype == 6){
		  $('#saltypename').text('')
      $('#daily_section').show();
    }
    else if(saltype == 7){
		  $('#saltypename').text('')
      $('#daily_section').show();
    }
     else if(saltype == 4) {
		$('#saltypename').text('')
      $('#daily_section').show();
    } else {
      $('#daily_section').hide();
    }
  })

  $(document).on('change', '#select-shift', function(){
    var shiftid = $('#select-shift').val()
    
    if (shiftid == '') {
      $('#timediv').hide()
    } else {

      var shift_data = shifts.filter(x=>x.id == shiftid)[0]
      
      $('#timepickeramin').val(shift_data.first_in);
      $('#timepickeramout').val(shift_data.first_out);
      $('#timepickerpmin').val(shift_data.second_in);
      $('#timepickerpmout').val(shift_data.second_out);


      
      $('#timediv').show()

    }
  })

  //click days 

  function workingdayscheck() {
    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_updatesalaryinfodays",
      data: {
        day : day,
        status : status
      },
      success: function (data) {
        console.log(data);
      }
    });
  }
  
  // salary based on attendance switch

  $(document).on('click', '.attendancebased', function(){
    if ($(this).val() == 1) {
      var switchValue = 1;
      $('#attendance_switch').val(switchValue)
      
    } else {
      var switchValue = 0;
      $('#attendance_switch').val(switchValue)
      
    }
  })

  
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
    var shiftid = $('#select-shift').val()
    var workshift = $('input[name="workshift"]:checked').val()
    var am_in = $('#timepickeramin').val()
    var am_out = $('#timepickeramout').val()
    var pm_in = $('#timepickerpmin').val()
    var pm_out = $('#timepickerpmout').val()
    var attendance_switch = $('#attendance_switch').val()

    var days = [];

    $('.workingdayscheck').each(function (){
        if ($(this).is(":checked")){
            days.push($(this).val());
        }
    })

    if (attendance_switch == null || attendance_switch == '') {
      attendance_switch = 0
    } else if (attendance_switch == 1){

      if (shiftid == '' || shiftid == null) {
        $('#select-shift').addClass('is-invalid')
        Toast.fire({
            type: 'error',
            title: 'No Shift Selected'
        })
        valid_data= false
      } else {
        $('#select-shift').removeClass('is-invalid')
        
        if (days.length == 0) {
          $('#days_selected').addClass('is-invalid')
          Toast.fire({
            type: 'error',
            title: 'No Days Selected'
          })
          valid_data = false
        } else {
          $('#days_selected').removeClass('is-invalid')
        }
        
        // if (am_in == '' || am_in == null) {
        //   $('#timepickeramin').addClass('is-invalid')
        //   Toast.fire({
        //       type: 'error',
        //       title: 'AM In is empty'
        //   })
        //   valid_data= false
        // }
        // if (am_out == '' || am_out == null) {
        //   $('#timepickeramout').addClass('is-invalid')
        //   Toast.fire({
        //       type: 'error',
        //       title: 'AM Out is empty'
        //   })
        //   valid_data= false
        // }

        // if (pm_in == '' || pm_in == null) {
        //   $('#timepickerpmin').addClass('is-invalid')
        //   Toast.fire({
        //       type: 'error',
        //       title: 'PM In is empty'
        //   })
        //   valid_data= false
        // }

        // if (pm_out == '' || pm_out == null) {
        //   $('#timepickerpmout').addClass('is-invalid')
        //   Toast.fire({
        //       type: 'error',
        //       title: 'PM Out is empty'
        //   })
        //   valid_data= false
        // }
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
      $('#select-salarytype').addClass('is-invalid')
      Toast.fire({
          type: 'error',
          title: 'No Salary Selected'
      })
      valid_data= false
    } else {
      $('#select-salarytype').removeClass('is-invalid')
    }


    console.log(am_in);
    console.log(am_out);
    console.log(pm_in);
    console.log(pm_out);
    console.log(attendance_switch);
    console.log(days);
    console.log(workshift);
    console.log(attendance_switch);
    
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
              workshift : workshift,
              shiftid : shiftid,
              attendance_switch : attendance_switch,
              am_in : am_in,
              am_out : am_out,
              pm_in : pm_in,
              pm_out : pm_out,
              days : days
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
                // thisdiv.find('input, select').prop('disabled', true)
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
  $(document).on('click', '#btn_allstandardallowance', function(){
    $('#modal_allallowances').modal('show')
  })
  // Check Checkbox Apply Standard Allowance
  $(document).on('change', '.checkstandardallowance', function(){
    var said = $(this).attr('data-id');

    if ($(this).is(':checked')) {
      $('#mod_stan_allowance'+said+'').removeClass('disabled')
      $('#add_employeestandardallowance'+said+'').removeClass('disabled')
      $('#applyall_standardallowance'+said+'').removeClass('disabled')
      $('#mod_stan_allowanceperday'+said+'').removeClass('disabled')
      
      $('#edit_sallowance'+said+'').removeClass('disabled')
    } else {
      $('#mod_stan_allowance'+said+'').addClass('disabled')
      $('#add_employeestandardallowance'+said+'').addClass('disabled')
      $('#applyall_standardallowance'+said+'').addClass('disabled')
      $('#mod_stan_allowanceperday'+said+'').addClass('disabled')
      $('#edit_sallowance'+said+'').addClass('disabled')
    }
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
    
    // console.log(sc_me);
    // return false;
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
    $('#btn_saveotherdeduction').show()
    $('#btn_updateotherdeduction').hide()
    $('#modal_otherdeduction').modal('show')

    $('#mod_odamount').attr('disabled', false)
    $('#mod_odineterst').attr('disabled', false)
    $('#mod_odterms').attr('disabled', false)
    $('#mod_oddatefrom').attr('disabled', false)
    $('#mod_oddateto').attr('disabled', false)
    $('#mod_period').attr('disabled', false)
    $('#mod_odamountdeduct').attr('disabled', false)
  })
  
  // Mao ning sa Modal nga part pag input sa interest og sa terms 
  $(document).on('input', '#mod_odineterst , #mod_odterms', function(){
    mod_odautocalculateamount()
  })
  
  // ma change ang term depende sa start date og end date
  $(document).on('change', '.mod_oddatefrom, .mod_oddateto', function () {
    var startDate = new Date($('#mod_oddatefrom').val());
    var endDate = new Date($('#mod_oddateto').val());

    // Calculate the difference in months (inclusive)
    var diffYears = endDate.getFullYear() - startDate.getFullYear();
    var diffMonths = endDate.getMonth() - startDate.getMonth();
    var totalMonths = diffYears * 12 + diffMonths;

    // Adjust the months based on the day of the month
    if (endDate.getDate() >= startDate.getDate()) {
      totalMonths += 1;
    }
    // Log or use the totalMonths variable as needed
    console.log("Total months:", totalMonths);
    $('#mod_odterms').val(totalMonths); // Update the term input
      mod_odautocalculateamount()
    
  });
  
  $(document).on('input', '#mod_odamount', function(){
    mod_odautocalculateamount()
  })
  function mod_odautocalculateamount(){
    
    var mod_loanamount = parseFloat($('#mod_odamount').val())
    var mod_odineterst = parseFloat($('#mod_odineterst').val())
    console.log(mod_odineterst);
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
    if (mod_odterms == null || mod_terms =='') {
      mod_odterms = 1
    }

    var mod_odamountdeduct = mod_loanamount

    mod_odineterst = mod_odineterst/100
    mod_odineterst = mod_odineterst * mod_loanamount
    
    if (!isNaN(mod_odineterst)) {
      // mod_odineterst / mod_odterms
      // mod_odineterst
      mod_odamountdeduct += mod_odineterst
    }


    if (!isNaN(mod_odterms)) {
      var startDate = $('#mod_oddatefrom').val(); // Get the start date
      if (startDate) {
          startDate = new Date(startDate); // Convert to a JavaScript Date object

          // Calculate the end date
          var endDate = new Date(startDate);

          // If mod_odterms is greater than 1, set the end date based on the terms
          if (mod_odterms > 1) {
              endDate.setMonth(startDate.getMonth() + mod_odterms);
              endDate.setDate(0); // Set to last day of the previous month
          } else {
              // Set the end date to the last day of the current month (mod_odterms = 1)
              endDate.setMonth(startDate.getMonth() + 1, 0);
          }

          var formattedEndDate = endDate.toISOString().slice(0, 10); // Convert back to YYYY-MM-DD format
          $('#mod_oddateto').val(formattedEndDate); // Update the end date input

          mod_odamountdeduct = mod_loanamount / mod_odterms;
          mod_odamountdeduct += mod_odineterst;
      }
    }
    console.log(mod_odamountdeduct);
    if (isNaN(mod_odterms) && isNaN(mod_loanamount)) {
      $('#mod_odamountdeduct').val(mod_odamountdeduct)
    }
    $('#mod_odamountdeduct').val(mod_odamountdeduct)

  }
  // click Add in Other Deduction
  $(document).on('click', '#btn_saveotherdeduction', function(){
    var valid_data = true
    var od_particular = $('#od_particular').val()
    var mod_odamount = $('#mod_odamount').val()
    var mod_odineterst = $('#mod_odineterst').val()
    var mod_odterms = $('#mod_odterms').val()
    var mod_oddatefrom = $('#mod_oddatefrom').val()
    var mod_oddateto = $('#mod_oddateto').val()
    var mod_odamountdeduct = $('#mod_odamountdeduct').val()
    var mod_period = $('#mod_period').val()
    
    var empid = $('#employeeid').val()

    if (mod_odamount == 0) {
      $('#mod_odamount').addClass('is-invalid')
      toastr.warning('Zero Amount!', 'Other Deduction')
      valid_data = false
    } else {
      $('#mod_odamount').removeClass('is-invalid')
    }

    if (mod_odamount == '' || mod_odamount == null) {
      $('#mod_odamount').addClass('is-invalid')
      toastr.warning('Empty Amount!', 'Other Deduction')
      valid_data = false
    } else {
      $('#mod_odamount').removeClass('is-invalid')
    }

    if (od_particular == '' || od_particular == null) {
      $('#od_particular').addClass('is-invalid')
      toastr.warning('Empty Particular!', 'Other Deduction')
      valid_data = false
    } else {
      $('#od_particular').removeClass('is-invalid')
    }

    if (mod_odineterst == '' || mod_odineterst == null) {
      mod_odineterst = 0
    }
    if (mod_odterms == '' || mod_odterms == null) {
      mod_odterms = 0
    }
    
    // return false;
    if (valid_data) {
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
          empid : empid,
          mod_period : mod_period
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
    }
    
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
      // $('#od_amountdeducted'+od+'').val(fullamount)
      $('#save_otherdeduct'+od+'').removeClass('disabled')
      $('#applyall_oddeduct'+od+'').removeClass('disabled')
      $('#edit_otherdeduction'+od+'').removeClass('disabled')
      
      $('#od_interest'+od+'').prop('disabled', false)
      $('#od_datefrom'+od+'').prop('disabled', false)
      $('#od_dateto'+od+'').prop('disabled', false)
      $('#od_terms'+od+'').prop('disabled', false)
      // $('#od_amountdeducted'+od+'').prop('disabled', false)
      $('#od_loanamount'+od+'').prop('disabled', false)
      
    } else {
      $('#save_otherdeduct'+od+'').addClass('disabled')
      $('#applyall_oddeduct'+od+'').addClass('disabled')
      $('#edit_otherdeduction'+od+'').addClass('disabled')

      $('#od_interest'+od+'').prop('disabled', true)
      $('#od_datefrom'+od+'').prop('disabled', true)
      $('#od_dateto'+od+'').prop('disabled', true)
      $('#od_terms'+od+'').prop('disabled', true)
      $('#od_loanamount'+od+'').prop('disabled', true)
    }
  })
  
  // Adding ni nga part pag input sa interest Rate og sa terms
  $(document).on('input', '.od_interest , .od_terms', function(){
    var od = $(this).attr('data-id') 
    od_autocalculateamount(od)
  })

  // ma change ang term depende sa start date og end date
  $(document).on('change', '.od_datefrom, .od_dateto', function () {
    var od = $(this).attr('data-id') 

    var startDate = new Date($('#od_datefrom'+od+'').val());
    var endDate = new Date($('#od_dateto'+od+'').val());

    // Calculate the difference in months (inclusive)
    var diffYears = endDate.getFullYear() - startDate.getFullYear();
    var diffMonths = endDate.getMonth() - startDate.getMonth();
    var totalMonths = diffYears * 12 + diffMonths;

    // Adjust the months based on the day of the month
    if (endDate.getDate() >= startDate.getDate()) {
      totalMonths += 1;
    }
    // Log or use the totalMonths variable as needed
    console.log("Total months:", totalMonths);
    $('#od_terms'+od+'').val(totalMonths)
    od_autocalculateamount(od)


  });

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
    var terms = parseFloat($('.od_terms[data-id="'+od+'"]').val())
    if (terms == null || terms =='') {
      terms = 1
    }

    var amounttobededuct = od_loanamount

    od_interest = od_interest / 100
    od_interest = od_interest * od_loanamount
    
    if (!isNaN(od_interest)) {
      // od_interest / terms
      amounttobededuct += od_interest
    }

    // if (!isNaN(terms)) {
    //   if (terms != 0) {
    //   amounttobededuct =  od_loanamount/terms
    //   } else {
    //     amounttobededuct = amounttobededuct
    //   }
    //   amounttobededuct = amounttobededuct + od_interest
    // }

    if (!isNaN(terms)) {
      var startDate = $('#od_datefrom' + od).val(); // Get the start date
      if (startDate) {
          startDate = new Date(startDate); // Convert to a JavaScript Date object

          // Calculate the end date
          var endDate = new Date(startDate);

          // If mod_odterms is greater than 1, set the end date based on the terms
          if (terms > 1) {
              endDate.setMonth(startDate.getMonth() + terms);
              endDate.setDate(0); // Set to last day of the previous month
          } else {
              // Set the end date to the last day of the current month (mod_odterms = 1)
              endDate.setMonth(startDate.getMonth() + 1, 0);
          }

          var formattedEndDate = endDate.toISOString().slice(0, 10); // Convert back to YYYY-MM-DD format
          $('#od_dateto' + od).val(formattedEndDate); // Update the end date input

          amounttobededuct = od_loanamount / terms;
          amounttobededuct += od_interest;
      }
    }

    if (isNaN(terms) && isNaN(od_interest)) {
      $('#od_amountdeducted'+od+'').val(amounttobededuct)
    }
    $('#od_amountdeducted'+od+'').val(amounttobededuct)
  }

  // edit other deduction 
  $(document).on('click', '.edit_oddeduct', function(){
    var od = $(this).attr('data-id') 
    $('.edit_oddeduct').addClass('save_oddeduct')
    $('.edit_oddeduct').removeClass('edit_oddeduct')
    $('#iconsave_oddeduct'+od+'').addClass('text-success')
    $('#edit_od_interest'+od+'').prop('disabled', false)
    $('#edit_od_interest'+od+'').prop('readonly', false)
    $('#edit_od_loanamount'+od+'').prop('disabled', false)
    $('#edit_od_datefrom'+od+'').prop('disabled', false)
    $('#edit_od_dateto'+od+'').prop('disabled', false)
    $('#edit_od_terms'+od+'').prop('disabled', false)
    $('#edit_period'+od+'').prop('disabled', false)
    $('#edit_od_amountdeducted'+od+'').prop('disabled', false)
  })

  // Edit ni nga part pag input sa interest Rate og sa terms
  $(document).on('input', '.edit_od_interest , .edit_od_terms', function(){
    var od = $(this).attr('data-id') 
    edit_od_autocalculateamount(od)
  })
  
  // ma change ang term depende sa start date og end date
  $(document).on('change', '.edit_od_datefrom, .edit_od_dateto', function () {
    var od = $(this).attr('data-id') 

    var startDate = new Date($('#edit_od_datefrom'+od+'').val());
    var endDate = new Date($('#edit_od_dateto'+od+'').val());

    // Calculate the difference in months (inclusive)
    var diffYears = endDate.getFullYear() - startDate.getFullYear();
    var diffMonths = endDate.getMonth() - startDate.getMonth();
    var totalMonths = diffYears * 12 + diffMonths;

    // Adjust the months based on the day of the month
    if (endDate.getDate() >= startDate.getDate()) {
      totalMonths += 1;
    }
    // Log or use the totalMonths variable as needed
    console.log("Total months:", totalMonths);
    $('#edit_od_terms'+od+'').val(totalMonths)
    edit_od_autocalculateamount(od)

  });

  $(document).on('input', '.edit_od_loanamount', function(){
    var od = $(this).attr('data-id') 
    edit_od_autocalculateamount(od)
  })

  
  function edit_od_autocalculateamount(od){
    
    var od_loanamount = parseFloat($('.edit_od_loanamount[data-id="'+od+'"]').val())
    var od_interest = parseFloat($('.edit_od_interest[data-id="'+od+'"]').val())

    if (isNaN(od_interest)) {
      od_interest = 0
    } 
    var terms = parseFloat($('.edit_od_terms[data-id="'+od+'"]').val())
    if (terms == null || terms =='') {
      terms = 1
    }
    var amounttobededuct = od_loanamount

    od_interest = od_interest / 100
    od_interest = od_interest * od_loanamount
    
    if (!isNaN(od_interest)) {
      // od_interest / terms
      // od_interest / terms
      amounttobededuct += od_interest
    }

    if (!isNaN(terms)) {
      var startDate = $('#edit_od_datefrom' + od).val(); // Get the start date
      if (startDate) {
          startDate = new Date(startDate); // Convert to a JavaScript Date object

          // Calculate the end date
          var endDate = new Date(startDate);

          // If mod_odterms is greater than 1, set the end date based on the terms
          if (terms > 1) {
              endDate.setMonth(startDate.getMonth() + terms);
              endDate.setDate(0); // Set to last day of the previous month
          } else {
              // Set the end date to the last day of the current month (mod_odterms = 1)
              endDate.setMonth(startDate.getMonth() + 1, 0);
          }

          var formattedEndDate = endDate.toISOString().slice(0, 10); // Convert back to YYYY-MM-DD format
          $('#edit_od_dateto' + od).val(formattedEndDate); // Update the end date input

          amounttobededuct = od_loanamount / terms;
          amounttobededuct += od_interest;
      }
    }

    if (isNaN(terms) && isNaN(od_interest)) {
      $('#edit_od_amountdeducted'+od+'').val(amounttobededuct)
    }
    $('#edit_od_amountdeducted'+od+'').val(amounttobededuct)
  }
  
  $(document).on('click', '.save_oddeduct', function(){
    var od = $(this).attr('data-id') 

    var action = $(this).attr('type-action') 
    
    $('#iconsave_oddeduct'+od+'').addClass('text-info')
    $('#iconsave_oddeduct'+od+'').removeClass('text-success')
    var thistd = $(this).closest('td')
    var thistr = $(this).closest('tr')  
    var empid = $('#employeeid').val()

    if (action == 'update') {
      // mao ning data makuha if mag edit siya other deduction
      var od_interest =  parseFloat($('.edit_od_interest[data-id="'+od+'"]').val())
      var od_loanamount = parseFloat($('.edit_od_loanamount[data-id="'+od+'"]').val())
      var od_datefrom = $('.edit_od_datefrom[data-id="'+od+'"]').val()
      var od_dateto = $('.edit_od_dateto[data-id="'+od+'"]').val()
      var od_terms =  parseFloat($('.edit_od_terms[data-id="'+od+'"]').val())
      var od_periodid =  $('.edit_period[data-id="'+od+'"]').val()
      var amounttobededuct =  parseFloat($('.edit_od_amountdeduct[data-id="'+od+'"]').val())

    } else {
      // mao ning mga data makuha if add lang siya og other deduction
      var od_interest =  parseFloat($('.od_interest[data-id="'+od+'"]').val())
      var od_loanamount = parseFloat($('.od_loanamount[data-id="'+od+'"]').val())
      var od_datefrom = $('.od_datefrom[data-id="'+od+'"]').val()
      var od_dateto = $('.od_dateto[data-id="'+od+'"]').val()
      var od_terms =  parseFloat($('.od_terms[data-id="'+od+'"]').val())
      var od_periodid =  $('.period[data-id="'+od+'"]').val()
      var amounttobededuct =  parseFloat($('.od_amountdeduct[data-id="'+od+'"]').val())
      var od_particular = $(this).attr('od_particular') 

    }
    
    console.log(od_interest);
    // console.log(od_loanamount);
    // console.log(od_datefrom);
    // console.log(od_dateto);
    // console.log(od_terms);
    // console.log(amounttobededuct);
    // console.log(od_particular);
    
    console.log(od_periodid);

    if (od_terms == null || od_terms == '') {
      od_terms = 0
    }

    if (od_interest == null || od_interest == '') {
      od_interest = 0
    }

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
        od_periodid : od_periodid,
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
          getperemployeelist()
          thistr.find('input').prop('disabled', true)
          thistd.empty()
          // thistd.append('<button class="btn btn-success btn-sm save_odeduct" data-id="'+od+'"  od_particular="'+od_particular+'"  id="save_otherdeduct'+od+'" disabled><i class="fas fa-share-square" style="font-size: 18px!important;" ></i></button> <a href="javascript:void(0)"  class="btn delete_odeduct"  id="delete_employeeotherdedect'+od+'" emotherdeductid-id="'+od+'" ><i class="fas fa-trash text-danger" style="font-size: 18px!important;"></i></a>')
          thistd.append('<a href="javascript:void(0)"  class="btn delete_odeduct"  id="delete_employeeotherdedect'+od+'" emotherdeductid-id="'+od+'" data-toggle="tooltip" title="Remove"><i class="fas fa-trash text-danger" style="font-size: 18px!important;"></i></a>')
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
          getperemployeelist()
          Toast.fire({
            type: 'success',
            title: data[0].message
          })
        }
      }
    });
  })

  // lock employee other deduction para dili ma apektahan if mag update didto sa deduction other nga list
  $(document).on('click', '.lock_oddeduct', function(){
    var em_odid = $(this).attr('emotherdeductid-id')
    var empid = $('#employeeid').val()
    var lock = $(this).attr('data-lock')

    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_lockemployeeotherdeduction",
      data: {
        em_odid : em_odid,
        empid : empid,
        lock : lock
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
                        '<div class="col-md-2">'+
                          '<div class="floatinginput">'+
                            '<input type="number" class="input-amount-new" placeholder="" required="required">'+
                            '<span>% or Amount</span>'+
                          '</div>'+
                            // '<input type="number" class="input-amount-new form-control form-control-sm"/>'+
                        '</div>'+
                        '<div class="col-md-2 text-center" style="cursor: pointer">'+
                            '<a href="javascript:void(0)" class=""  data-toggle="tooltip" title="Add"><i class="fa fa-plus text-info" id="btn-submit" style="font-size: 18px;"></i></a>&nbsp;&nbsp;&nbsp;'+
                            '<a href="javascript:void(0)" class=""  data-toggle="tooltip" title="Remove"><i class="fa fa-times bracket-remove text-danger" style="font-size: 18px;"></i></a>'+ 
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
          select_departments()
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
  $(document).on('click', '.baseonattendance', function(){
    if ($(this).is(':checked')) {
      console.log('na check');
      $('#boa_amountperday').removeClass('disabled')
      $('#standardallowance_amount').addClass('disabled')
    } else {
      console.log('wala na check');
      $('#boa_amountperday').val('')
      $('#boa_amountperday').addClass('disabled')
      $('#standardallowance_amount').removeClass('disabled')
    }
  });

  $(document).on('input', '#standardallowance_amount', function(){
    if ($(this).val() === '') {
        $('.baseonattendance').removeClass('disabled');
    } else {
        $('.baseonattendance').addClass('disabled');
    }
  });

  $(document).on('click', '#btn_addstandardallowance', function(){
    var empid = $('#employeeid').val()

    $('#boa_employeeid').val(empid)
    $('#modal_addstandardallowance').modal('show')
  })

  $(document).on('click', '#btn_savestandardallowance', function(){
    var valid_data = true;
    var particular = $('#standardallowance_desc').val()
    var amount = $('#standardallowance_amount').val()
    var boa_amount = $('#boa_amountperday').val()

    if ($('.baseonattendance').is(':checked')) {
      var baseonattendance = 1;
      amount = 0;
      if (boa_amount == null || boa_amount == '') {
        toastr.warning('Amount Per Day is Empty');
        valid_data = false;
      }
    } else {
      var baseonattendance = 0;
      boa_amount = 0;
      if (amount == null || amount == '') {
        toastr.warning('Amount is Empty');
        valid_data = false;
      }
    }
    
    if (particular == null || particular == '') {
      toastr.warning('Particulars is Empty');
      valid_data = false;
    }
    
    // return false;
    if (valid_data) {
      $.ajax({
        type: "GET",
        url: "/hr/employees/profile/addstandardallowance",
        data: {
          particular : particular,
          amount : amount,
          boa_amount,
          baseonattendance
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
  })
  

  // Click Plus Icon adding standard allowance per employee
  $(document).on('click', '.add_employeestandardallowance', function(){
    var action = $(this).attr('type-action')
    var empid = $('#employeeid').val()
    var stanallowance_id = $(this).attr('stan-id')
    var amount = parseFloat($('#mod_stan_allowance'+stanallowance_id+'').val())
    var amountperday = parseFloat($(this).attr('mod_data-amountperday'));
    var baseonattendance = $(this).attr('data-baseonattendance')

    if (action == 'update') {
      if (baseonattendance == 1) {
        amountperday = parseFloat($('#stan_allowance'+stanallowance_id+'').val());

        amount = 0;
      } else{
        amountperday = 0;
        amount = parseFloat($('#stan_allowance'+stanallowance_id+'').val());
      }
    } else {
      if (baseonattendance == 1) {
        amount = 0;
        amountperday = parseFloat($('#mod_stan_allowanceperday'+stanallowance_id+'').val());
      } else{
        amountperday = 0;
        amount = parseFloat($('#mod_stan_allowance'+stanallowance_id+'').val());
      }
    }

    // if (baseonattendance == 1) {
    //   if (action == 'update') {
    //     amountperday = parseFloat($('#stan_allowance'+stanallowance_id+'').val());
    //     amount = 0;
    //   } else {
    //     amount = 0;
    //     amountperday = parseFloat($('#mod_stan_allowanceperday'+stanallowance_id+'').val());
    //   }
      
    // } else {
    //   if (action == 'update') {
    //     amountperday = parseFloat($('#stan_allowance'+stanallowance_id+'').val());
    //     amount = parseFloat($('#stan_allowance'+stanallowance_id+'').val());
    //   } else {
    //     amountperday = 0;
    //     amount = parseFloat($('#mod_stan_allowance'+stanallowance_id+'').val());
    //   }
    // }


    console.log(amountperday);
    console.log(baseonattendance);
    console.log(amount);

    if (empid == null || empid == '') {
      toastr.warning('Employee Id is Empty');
      valid_data = false;
    }

    // return false;
    $.ajax({
      type: "GET",
      url: "/hr/employees/profile/addemployeestandardallowance",
      data: {
        empid : empid,
        stanallowance_id : stanallowance_id,
        amount : amount,
        action : action,
        amountperday : amountperday,
        baseonattendance : baseonattendance
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
    // $('#attendancebased').prop('disabled', true)
  }

  function showsalaryfields(){
    $('#select-salarytype').prop('disabled', false)
    $('#amountperday').prop('disabled', false)
    $('#hoursperday').prop('disabled', false)
    $('#paymenttype').prop('disabled', false)
    // $('#attendancebased').prop('disabled', false)
  }
    

  // ----------------- Select2 functions -----------------------
  function select_designations(){
    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/select_designations",
      success: function (data) {
        $('#select-designation').empty()
        $('#select-designation').append('<option value="">Select Designation</option>')
        $('#select-designation').select2({
            data: data,
            allowClear : true,
            placeholder: 'Select Designation'
        });
      }
    });
  }

  function select_departments(){
    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/select_departments",
      success: function (data) {
        $('#select-department').empty()
        $('#select-department').append('<option value="">Select Department</option>')
        $('#select-department').select2({
            data: data,
            allowClear : true,
            placeholder: 'Select Department'
        });
      }
    });
  }

  function load_salarytype(){
    $('#select-salarytype').empty()
    $('#select-salarytype').append('<option value="">Select Salary Type</option>')
    $('#select-salarytype').select2({
        data: salarytypes,
        allowClear : true,
        placeholder: 'Select Salary Type'
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
                                '<a href="javascript:void(0)" class="btn-delete" data-id="'+item.id+'"  data-toggle="tooltip" title="Remove"><i class="fa fa-trash text-danger" style="font-size: 18px;"></i></a>&nbsp;&nbsp;&nbsp;'+
                                '<a href="javascript:void(0)" class="btn-update" data-id="'+item.id+'"  data-toggle="tooltip" title="Edit"><i class="far fa-edit" style="font-size: 18px;"></i></a>'+ 

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
        console.log(shifts);
        load_shift_select2()
        load_emshiftsched()
      }
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

  function load_emshiftsched(){
    var empid = $('#employeeid').val()

    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_loademployeeshifts",
      data: {
        empid : empid
      },
      success: function (data) {
        $('#select-shift').val(data.shiftid).change()
      }
    });
  }

  // load personal Info 
  function loadpersonalinfo(){
    var empid = $('#employeeid').val()

    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_loadpersonalinfo",
      data: {
        empid : empid
      },
      success: function (data) {
        if (data.personalinfo[0] != null) {
          if (data.personalinfo[0].departmentid != null) {
            $('#select-department').val(data.personalinfo[0].departmentid).change()
          }
        }
        if (data.teacherinfo[0] != null) {
          if (data.teacherinfo[0].usertypeid != null) {
            $('#select-designation').val(data.teacherinfo[0].usertypeid).change()
          }
        }
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
      autoWidth: false,
      order: false,
      data: other_deduction,
      columns : [
        {"data" : 'description'},
        {"data" : null},
        {"data" : null},
        {"data" : null},
        {"data" : null},
        {"data" : null},
        // {"data" : null},
        {"data" : null},
        {"data" : null}
      ],
        columnDefs: [
          {
              'targets': 0,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                var text = '<input type="checkbox" id="checkotherdeduction" odid="'+rowData.id+'" fullamount="'+rowData.amount+'">&nbsp;&nbsp;<a href="javascript:void(0)"  class="text-info" data-id="'+rowData.id+'" style="font-size: 13px!important;"><b>'+ rowData.description +'</b></a><br>';
                $(td)[0].innerHTML =  text
                $(td).addClass('align-middle  text-left')
                $(td).css('vertical-align', 'middle !important')
              }
            },
            {
              'targets': 1,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input class="text-center od_interest" id="od_interest'+rowData.id+'" data-id="'+rowData.id+'" value="'+rowData.interest_rate+'%" placeholder="%" type="text" style="width: 80px;" disabled >'
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 2,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input type="number" href="javascript:void(0)"  class="text-center od_loanamount" value="'+rowData.amount+'" data-id="'+rowData.id+'" id="od_loanamount'+rowData.id+'" disabled style="width: 90px;">';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 3,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input type="date" id="od_datefrom'+rowData.id+'" data-id="'+rowData.id+'" class="form-control searchcontrol od_datefrom" data-toggle="tooltip" title="Date From" value="{{\Carbon\Carbon::now('+rowData.startdate+')->toDateString()}}"  style="width: 140px; margin: auto!important;" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 4,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var dateParts = rowData.enddate.split('-'); // Split the date string into year, month, and day
                  var year = parseInt(dateParts[0]);
                  var month = parseInt(dateParts[1]) - 1; // JavaScript months are zero-based (January is 0)
                  var day = parseInt(dateParts[2]);

                  var formattedEndDate = new Date(Date.UTC(year, month, day)).toISOString().slice(0, 10);
                  var text = '<input type="date" id="od_dateto' + rowData.id + '" data-id="' + rowData.id + '" class="form-control searchcontrol od_dateto" data-toggle="tooltip" title="Date To" value="' + formattedEndDate + '" style="width: 140px; margin: auto!important;" disabled>';
                  // var text = '<input type="date" id="od_dateto'+rowData.id+'" data-id="'+rowData.id+'"  class="form-control searchcontrol od_dateto" data-toggle="tooltip" title="Date To" value="{{\Carbon\Carbon::now('+rowData.enddate+')->toDateString()}}"  style="width: 140px; margin: auto!important;" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 5,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input class="text-center od_terms" id="od_terms'+rowData.id+'" data-id="'+rowData.id+'" value="'+rowData.terms+'"  placeholder="0" type="text" style="width: 50px;" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            // {
            //   'targets': 6,
            //   'orderable': false, 
            //   'createdCell':  function (td, cellData, rowData, row, col) {
            //       if (rowData.periodid == 2) {
            //         var text = '<select class="select2 form-control form-control-sm text-uppercase period"  data-id="'+rowData.id+'" name="period" placeholder="Select Period" id="period'+rowData.id+'" style="margin: auto!important;">'+
            //                       '<option value="2">Every 15 Days</option>'+
            //                       '<option value="1">Monthly</option>'+
            //                     '</select>';
            //       } else {
            //         var text = '<select class="select2 form-control form-control-sm text-uppercase period"  data-id="'+rowData.id+'" name="period" placeholder="Select Period" id="period'+rowData.id+'" style="margin: auto!important;">'+
            //                     '<option value="1">Monthly</option>'+
            //                     '<option value="2">Every 15 Days</option>'+
            //                   '</select>';
            //       }
            //       $(td)[0].innerHTML =  text
            //       $(td).addClass('align-middle  text-center')
            //     }
            // },
            {
              'targets': 6,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input class="text-center od_amountdeduct" id="od_amountdeducted'+rowData.id+'" data-id="'+rowData.id+'" value="'+rowData.amounttobededuct+'" type="text" style="width: 110px;" disabled readonly>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 7,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<a href="javascript:void(0)" class="save_oddeduct disabled" data-id="'+rowData.id+'" od_particular="'+rowData.description+'" id="save_otherdeduct'+rowData.id+'" type-action="save" disabled  data-toggle="tooltip" title="Add"><i class="fas fa-plus" style="font-size: 18px!important;"></i></a>&nbsp;&nbsp;&nbsp;'+
                  '<a href="javascript:void(0)"  class="applyall_oddeduct disabled" id="applyall_oddeduct'+rowData.id+'" data-id="'+rowData.id+'" data-desc="'+rowData.description+'"  data-toggle="tooltip" title="Apply per Employee"><i class="fas fa-users text-primary" tyle="font-size: 18px!important;"></i></a>&nbsp;&nbsp;&nbsp;'+
                  '<a href="javascript:void(0)"  class="edit_otherdeduction disabled" id="edit_otherdeduction'+rowData.id+'" data-id="'+rowData.id+'" data-desc="'+rowData.description+'"  data-toggle="tooltip" title="Edit"><i class="fas fa-edit text-primary" tyle="font-size: 18px!important;"></i></a>';
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

    console.log(employee_other_deduction);
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
      data: employee_other_deduction,
      columns : [
        {"data" : 'description'},
        {"data" : null},
        {"data" : null},
        {"data" : null},
        {"data" : null},
        {"data" : null},
        // {"data" : null},
        {"data" : null},
        {"data" : null}
      ], 
        columnDefs: [
            {
              'targets': 0,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                var text = '<i class="far fa-check-circle text-success" style="font-size: 20px;"></i>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="text-info" data-id="'+rowData.id+'"><b>'+ rowData.description +'</b></a><br>';
                $(td)[0].innerHTML =  text
                $(td).addClass('align-middle  text-left')
                $(td).css('vertical-align', 'middle !important')
              }
            },
            {
              'targets': 1,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input class="text-center edit_od_interest" id="edit_od_interest'+rowData.id+'" data-id="'+rowData.id+'" value="'+rowData.od_interest+'%" type="text" style="width: 80px;" disabled>'
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 2,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input type="number" href="javascript:void(0)"  class="text-center edit_od_loanamount" value="'+rowData.fullamount+'" data-id="'+rowData.id+'" id="edit_od_loanamount'+rowData.id+'" style="width: 90px;" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 3,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input type="date" id="edit_od_datefrom'+rowData.id+'" data-id="'+rowData.id+'" class="form-control searchcontrol edit_od_datefrom" data-toggle="tooltip" title="Date From" value="{{\Carbon\Carbon::now('+rowData.startdate+')->toDateString()}}" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 4,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                var dateParts = rowData.enddate.split('-'); // Split the date string into year, month, and day
                  var year = parseInt(dateParts[0]);
                  var month = parseInt(dateParts[1]) - 1; // JavaScript months are zero-based (January is 0)
                  var day = parseInt(dateParts[2]);

                  var formattedEndDate = new Date(Date.UTC(year, month, day)).toISOString().slice(0, 10);
                  var text = '<input type="date" id="edit_od_dateto'+rowData.id+'" data-id="'+rowData.id+'"  class="form-control searchcontrol edit_od_dateto" data-toggle="tooltip" title="Date To" value="'+formattedEndDate+'" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 5,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input class="text-center edit_od_terms" id="edit_od_terms'+rowData.id+'" data-id="'+rowData.id+'" value="'+rowData.term+'" type="text" style="width: 50px;" disabled>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            // {
            //   'targets': 6,
            //   'orderable': false, 
            //   'createdCell':  function (td, cellData, rowData, row, col) {
            //       if (rowData.periodid == 2) {
            //         var text = '<select class="select2 form-control form-control-sm text-uppercase edit_period"  data-id="'+rowData.id+'" name="period" placeholder="Select Period" id="edit_period'+rowData.id+'" style="margin: auto!important;" disabled>'+
            //                       '<option value="2">Every 15 Days</option>'+
            //                       '<option value="1">Monthly</option>'+
            //                     '</select>';
            //       } else {
            //         var text = '<select class="select2 form-control form-control-sm text-uppercase edit_period"  data-id="'+rowData.id+'" name="period" placeholder="Select Period" id="edit_period'+rowData.id+'" style="margin: auto!important;" disabled>'+
            //                     '<option value="1">Monthly</option>'+
            //                     '<option value="2">Every 15 Days</option>'+
            //                   '</select>';
            //       }
                 
            //       $(td)[0].innerHTML =  text
            //       $(td).addClass('align-middle  text-center')
            //     }
            // },
            {
              'targets': 6,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  var text = '<input class="text-center edit_od_amountdeduct" id="edit_od_amountdeducted'+rowData.id+'" data-id="'+rowData.id+'" value="'+rowData.amount+'" type="text" style="width: 90px;" disabled readonly>';
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            },
            {
              'targets': 7,
              'orderable': false, 
              'createdCell':  function (td, cellData, rowData, row, col) {
                  if (rowData.lock == 0) {
                    var iconlock = 'fas fa-lock-open'
                  } else {
                    var iconlock = 'fas fa-lock'
                  }

                  if (rowData.nabayad == rowData.fullamountwithinterest) {
                    var text = '<span class="text-success">Paid</span>&nbsp;<a href="javascript:void(0)" class="view_oddeduct" data-id="'+rowData.deductionotherid+'" data-empid="'+rowData.employeeid+'" emotherdeductid-id="'+rowData.id+'" id="view_employeeotherdedect'+rowData.id+'" data-odname="'+rowData.description+'"  data-toggle="tooltip" title="Transaction"><i class="text-primary fas fa-file-alt" style="font-size: 18px!important;"></i></a>';
                  } else {
                    if (rowData.released == 0) {
                      var text = '<a href="javascript:void(0)"  class="delete_odeduct"  id="delete_employeeotherdedect'+rowData.id+'" emotherdeductid-id="'+rowData.id+'"   data-toggle="tooltip" title="Remove"><i class="fas fa-trash text-danger" style="font-size: 18px!important;"></i></a>&nbsp;&nbsp;&nbsp;'+
                        '<a href="javascript:void(0)"  class="edit_oddeduct" data-id="'+rowData.id+'" id="edit_employeeotherdedect'+rowData.id+'" emotherdeductid-id="'+rowData.id+'" type-action="update"   data-toggle="tooltip" title="Edit"><i class="far fa-edit" id="iconsave_oddeduct'+rowData.id+'" style="font-size: 18px!important;"></i></a>&nbsp;&nbsp;&nbsp;'+
                        '<a href="javascript:void(0)" class="view_oddeduct" data-id="'+rowData.deductionotherid+'" data-empid="'+rowData.employeeid+'" emotherdeductid-id="'+rowData.id+'" id="view_employeeotherdedect'+rowData.id+'" data-odname="'+rowData.description+'"  data-toggle="tooltip" title="Transaction"><i class="text-primary fas fa-file-alt" style="font-size: 18px!important;"></i></a>';
                      // '<a href="javascript:void(0)"  data-id="'+rowData.id+'"  class="lock_oddeduct" data-id="'+rowData.id+'" emotherdeductid-id="'+rowData.id+'" data-lock="'+rowData.lock+'" id="edit_employeeotherdedect'+rowData.id+'"><i class="text-warning '+iconlock+'" style="font-size: 18px!important;"></i></a>';
                      } else {
                        var text = '<a href="javascript:void(0)" class="view_oddeduct" data-id="'+rowData.deductionotherid+'" data-empid="'+rowData.employeeid+'" emotherdeductid-id="'+rowData.id+'" id="view_employeeotherdedect'+rowData.id+'" data-odname="'+rowData.description+'"  data-toggle="tooltip" title="Transaction"><i class="text-primary fas fa-file-alt" style="font-size: 18px!important;"></i></a>';
                        // '<a href="javascript:void(0)"  data-id="'+rowData.id+'"  class="lock_oddeduct" data-id="'+rowData.id+'" emotherdeductid-id="'+rowData.id+'" data-lock="'+rowData.lock+'" id="edit_employeeotherdedect'+rowData.id+'"><i class="text-warning '+iconlock+'" style="font-size: 18px!important;"></i></a>';
                      }
                  }

                  
                  
                  $(td)[0].innerHTML =  text
                  $(td).addClass('align-middle  text-center')
                }
            }
          ]
    })

  }


  // Click Icon many users // Apply Other Deduction Per Department
  $(document).on('click', '.applyall_oddeduct', function(){
    var otherdeductionid = $(this).attr('data-id')
    var descname = $(this).attr('data-desc')

    var mod_interestrate = $('#od_interest'+otherdeductionid+'').val()
    var mod_loanamount = $('#od_loanamount'+otherdeductionid+'').val()
    var mod_sdate = $('#od_datefrom'+otherdeductionid+'').val()
    var mod_edate = $('#od_dateto'+otherdeductionid+'').val()
    var mod_terms = $('#od_terms'+otherdeductionid+'').val()
    var mod_amounttobededuct = $('#od_amountdeducted'+otherdeductionid+'').val()

    // console.log(mod_interestrate);
    // console.log(mod_loanamount);
    // console.log(mod_sdate);
    // console.log(mod_edate);
    // console.log(mod_terms);
    // console.log(mod_amounttobededuct);
    
    $('#otherdeductionid').val(otherdeductionid)
    $('#desc').text(descname)
    $('#mod_interestrate').val(mod_interestrate);
    $('#mod_loanamount').val(mod_loanamount);
    $('#mod_sdate').val(mod_sdate);
    $('#mod_edate').val(mod_edate);
    $('#mod_terms').val(mod_terms);
    $('#mod_amounttobededuct').val(mod_amounttobededuct);

    $('#modal_addotherdeductionperdep').modal('show')


    getallemployeeotherdeduction()
    getalldepartments(otherdeductionid)
  })

  // // Click Apply ALl Icon for apply all allowances
  $(document).on('click', '.applyall_standardallowance', function(){
    var descname = $(this).attr('data-desc')
    var standarallowancedid = $(this).attr('stan-id');
    var baseonattendance = $(this).attr('data-baseonattendance');
    if (baseonattendance == 1) {
      var amount = 0
      var amountperday = $('#mod_stan_allowanceperday'+standarallowancedid+'').val()
    } else {
      var amountperday = 0
      var amount = $('#mod_stan_allowance'+standarallowancedid+'').val()

    }
    $('#desc_sa').text(descname)
    $('#mod_amountperday').val(amountperday);
    $('#mod_baseonattendance').val(baseonattendance);
    $('#mod_amount').val(amount);
    $('#mod_standarallowancedid').val(standarallowancedid);
   
    $('#modal_addstandardallwoanceperdep').modal('show')
    getallemployeestandardallowance()
    
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
                      var text = '<a href="javascript:void(0)"  class="btn delete_sdeduct"  id="delete_employeestandarddedect'+rowData.id+'" emstandarddeductid-id="'+filtered_sd_employee.id+'" data-toggle="tooltip" title="Remove"><i class="fas fa-trash text-danger" style="font-size: 18px!important;"></i></a>';
                    } else {
                      var text = '<a href="javascript:void(0)" class="save_sdeduct disabled" data-id="'+rowData.id+'" id="save_standarddedect'+rowData.id+'"  data-toggle="tooltip" title="Add"><i class="fas fa-plus" style="font-size: 18px!important;" data-toggle="tooltip" title="Add Standard Deduction"></i></a>';
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
        all_allowance_setup()
      }
    });
  }

  function allowance_setup(){
    console.log(per_employeestandardallowance);
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
        data: per_employeestandardallowance,
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
                if (rowData.baseonattendance == 1) {
                  var text = '<span class="badge badge-info">Base On Attendance</span>';
                } else {
                  var text = '<span></span>';
                }
                $(td)[0].innerHTML =  text
                $(td).addClass('align-middle  text-right')
            }
          },
          {
            'targets': 2,
            'orderable': false, 
            'createdCell':  function (td, cellData, rowData, row, col) {
                  if (rowData.baseonattendance == 1) {
                    var text = '<input class="text-center stan_allowance" type="number" id="stan_allowance'+rowData.id+'" value="'+rowData.amountperday+'" style="width: 100px;" disabled>';
                  } else {
                    var text = '<input class="text-center stan_allowance" type="number" id="stan_allowance'+rowData.id+'" value="'+rowData.amount+'.00" style="width: 100px;" disabled>';
                  }
                $(td)[0].innerHTML =  text
                $(td).addClass('align-middle  text-center')
            }
          },
          {
            'targets': 3,
            'orderable': false, 
            'createdCell':  function (td, cellData, rowData, row, col) {
                var buttons = '<a href="javascript:void(0)" class="delete_stanallowance" id="delete_stanallowance'+rowData.id+'" data-id="'+rowData.allowance_standardid+'"  data-toggle="tooltip" title="Remove"><i class="fas fa-trash text-danger" style="font-size: 18px;"></i></a>&nbsp;&nbsp;&nbsp;'+
                '<a href="javascript:void(0)"  class="edit_stanallowance" id="edit_stanallowance'+rowData.id+'" data-amountperday="'+rowData.amountperday+'" data-baseonattendance="'+rowData.baseonattendance+'"  data-id="'+rowData.id+'" type-action="update" stan-id="'+rowData.id+'"  data-toggle="tooltip" title="Edit"><i class="far fa-edit icon_stanallowance" id="icon_stanallowance'+rowData.id+'" style="font-size: 18px;"></i></a>';
                // '<a href="javascript:void(0)"  class="applyall_allowance" data-id="'+rowData.id+'"><i class="fas fa-users text-primary" tyle="font-size: 18px!important;"></i></a>';
                $(td)[0].innerHTML =  buttons
                $(td).addClass('text-center')
                $(td).addClass('align-middle')
            }
          }
        ]
    })

    var label_text = $($('#allowance_setup_wrapper')[0].children[0])[0].children[0]
    $(label_text)[0].innerHTML = '<div class="row" style="padding-bottom: 10px!important;"><div class="col-md-12 col-sm-12"><button class="btn btn-info btn-sm" id="btn_allstandardallowance"><i class="far fa-window-restore"></i> All Standard Allowance</button></div></div>'
  }

  // function allowance_setup(){

  //   $('#allowance_setup').DataTable({
  //     lengthMenu: false,
  //       info: false,
  //       paging: true,
  //       searching: true,
  //       destroy: true,
  //       lengthChange: false,
  //       scrollX: true,
  //       autoWidth: true,
  //       order: false,
  //       data: all_allowances,
  //       columns : [
  //           {"data" : 'description'},
  //           {"data" : null},
  //           {"data" : null},
  //           {"data" : null}
  //       ],
  //       columnDefs: [
  //         {
  //           'targets': 0,
  //           'orderable': false, 
  //           'createdCell':  function (td, cellData, rowData, row, col) {
  //               var buttons = '<span href="javascript:void(0)" data-id="'+rowData.id+'">'+rowData.description+'</span>';
  //               $(td)[0].innerHTML =  buttons
  //               $(td).addClass('text-left')
  //               $(td).addClass('align-middle')
  //           }
  //         },
  //         {
  //           'targets': 1,
  //           'orderable': false, 
  //           'createdCell':  function (td, cellData, rowData, row, col) {
  //               if (rowData.baseonattendance == 1) {
  //                 var text = '<span class="badge badge-info">Base On Attendance</span>';
  //               } else {
  //                 var text = '<span></span>';
  //               }
  //               $(td)[0].innerHTML =  text
  //               $(td).addClass('align-middle  text-right')
  //           }
  //         },
  //         {
  //           'targets': 2,
  //           'orderable': false, 
  //           'createdCell':  function (td, cellData, rowData, row, col) {
  //               var filtereddata = per_employeestandardallowance.filter(x=>x.allowance_standardid == rowData.id)[0]
  //               if (filtereddata != null) {
  //                 if (filtereddata.baseonattendance == 1) {
  //                   var text = '<input class="text-center stan_allowance" type="number" id="stan_allowance'+rowData.id+'" value="'+filtereddata.amountperday+'.00" style="width: 100px;" disabled>';
  //                 } else {
  //                   var text = '<input class="text-center stan_allowance" type="number" id="stan_allowance'+rowData.id+'" value="'+filtereddata.amount+'.00" style="width: 100px;" disabled>';

  //                 }
  //               } else {
  //                 if (rowData.baseonattendance == 1) {
  //                   var text = '<input class="text-center stan_allowance" type="number" id="stan_allowanceperday'+rowData.id+'" value="'+rowData.amountperday+'" style="width: 100px;">';
  //                 } else {
  //                   var text = '<input class="text-center stan_allowance" type="number" id="stan_allowance'+rowData.id+'" value="'+rowData.amount+'" style="width: 100px;">';
  //                 }
  //               }
  //               $(td)[0].innerHTML =  text
  //               $(td).addClass('align-middle  text-center')
  //           }
  //         },
  //         {
  //           'targets': 3,
  //           'orderable': false, 
  //           'createdCell':  function (td, cellData, rowData, row, col) {

  //               var filtereddata = per_employeestandardallowance.filter(x=>x.allowance_standardid == rowData.id)[0]
  //               if (filtereddata != null) {
  //                 var buttons = '<a href="javascript:void(0)" class="delete_stanallowance" id="delete_stanallowance'+rowData.id+'" data-id="'+rowData.id+'"><i class="fas fa-trash text-danger" style="font-size: 18px;"></i></a>&nbsp;&nbsp;&nbsp;'+
  //                 '<a href="javascript:void(0)"  class="edit_stanallowance" id="edit_stanallowance'+rowData.id+'" data-amountperday="'+rowData.amountperday+'" data-baseonattendance="'+rowData.baseonattendance+'"  data-id="'+rowData.id+'" type-action="update" stan-id="'+rowData.id+'"><i class="far fa-edit icon_stanallowance" id="icon_stanallowance'+rowData.id+'" style="font-size: 18px;"></i></a>';
  //                 // '<a href="javascript:void(0)"  class="applyall_allowance" data-id="'+rowData.id+'"><i class="fas fa-users text-primary" tyle="font-size: 18px!important;"></i></a>';
  //               } else {
  //                 var buttons = '<a href="javascript:void(0)" class="add_employeestandardallowance" data-amountperday="'+rowData.amountperday+'" data-baseonattendance="'+rowData.baseonattendance+'" data-amount="'+rowData.amount+'" stan-id="'+rowData.id+'" type-action="save" style="margin-left: 30px;"><i class="fas fa-plus" style="font-size: 18px;"></i></a>';
  //               }
  //               $(td)[0].innerHTML =  buttons
  //               $(td).addClass('text-center')
  //               $(td).addClass('align-middle')
  //           }
  //         }
  //       ]
  //   })

  //   var label_text = $($('#allowance_setup_wrapper')[0].children[0])[0].children[0]
  //   $(label_text)[0].innerHTML = '<div class="row" style="padding-bottom: 10px!important;"><div class="col-md-12 col-sm-12"><button class="btn btn-info btn-sm" id="btn_allstandardallowance"><i class="fas fa-plus"></i> All Standard Allowance</button></div></div>'
  //   }

  function all_allowance_setup(){

    $('#table_all_allowances').DataTable({
        lengthMenu: false,
        info: false,
        paging: false,
        searching: true,
        destroy: true,
        lengthChange: false,
        scrollX: false,
        autoWidth: false,
        order: false,
        data: all_allowances,
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
                var buttons = '<input type="checkbox" class="checkstandardallowance" data-id="'+rowData.id+'">&nbsp;&nbsp;<span href="javascript:void(0)" class="text-info" data-id="'+rowData.id+'" style="font-size: 13px;"><b>'+rowData.description+'</b></span>';
                $(td)[0].innerHTML =  buttons
                $(td).addClass('text-left')
                $(td).addClass('align-middle')
            }
          },
          {
            'targets': 1,
            'orderable': false, 
            'createdCell':  function (td, cellData, rowData, row, col) {
                if (rowData.baseonattendance == 1) {
                  var text = '<span class="badge badge-info">Base On Attendance</span>';
                } else {
                  var text = '<span></span>';
                }
                $(td)[0].innerHTML =  text
                $(td).addClass('align-middle  text-right')
            }
          },
          {
            'targets': 2,
            'orderable': false, 
            'createdCell':  function (td, cellData, rowData, row, col) {
                if (rowData.baseonattendance == 1) {
                  var text = '<input class="text-center mod_stan_allowance disabled" type="number" id="mod_stan_allowanceperday'+rowData.id+'" value="'+rowData.amountperday+'" style="width: 100px;">';
                } else {
                  var text = '<input class="text-center mod_stan_allowance disabled" type="number" id="mod_stan_allowance'+rowData.id+'" value="'+rowData.amount+'" style="width: 100px;">';
                }
                $(td)[0].innerHTML =  text
                $(td).addClass('align-middle  text-center')
            }
          },
          {
            'targets': 3,
            'orderable': false, 
            'createdCell':  function (td, cellData, rowData, row, col) {
                var buttons = '<a href="javascript:void(0)" class="add_employeestandardallowance disabled" id="add_employeestandardallowance'+rowData.id+'" disabled" data-amountperday="'+rowData.amountperday+'" data-baseonattendance="'+rowData.baseonattendance+'" data-amount="'+rowData.amount+'" stan-id="'+rowData.id+'" type-action="save"  data-toggle="tooltip" title="Add"><i class="fas fa-plus" style="font-size: 18px;"></i></a>&nbsp;&nbsp;&nbsp;'+
                '<a href="javascript:void(0)"  class="applyall_standardallowance disabled" id="applyall_standardallowance'+rowData.id+'" data-desc="'+rowData.description+'" data-toggle="tooltip" title="Apply per Employee"  data-amountperday="'+rowData.amountperday+'" data-baseonattendance="'+rowData.baseonattendance+'" data-amount="'+rowData.amount+'" stan-id="'+rowData.id+'"><i class="fas fa-users text-primary" tyle="font-size: 18px!important;"></i></a>&nbsp;&nbsp;&nbsp;'+
                '<a href="javascript:void(0)"  class="edit_sallowance disabled" id="edit_sallowance'+rowData.id+'" data-toggle="tooltip" title="Edit"><i class="fas fa-edit text-primary" tyle="font-size: 18px!important;"></i></a>';
                $(td)[0].innerHTML =  buttons
                $(td).addClass('text-center')
                $(td).addClass('align-middle')
            }
          }
        ]
    })

    var label_text = $($('#table_all_allowances_wrapper')[0].children[0])[0].children[0]
    $(label_text)[0].innerHTML = '<div class="row" style="padding-bottom: 10px!important;"><div class="col-md-12 col-sm-12"><button class="btn btn-primary btn-sm" id="btn_addstandardallowance"  data-toggle="tooltip" title="Add"><i class="fas fa-plus"></i> Add Standard Allowance</button></div></div>'
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
        allowance_setup()
      }
    });
  }

  // load all employee standard allowance
  function getallemployeestandardallowance(){
    $.ajax({
      type: "GET",
      url: "/hr/employees/profile/getallemployeestandardallowance",
      success: function (data) {
        allallowancestandard = data
        getperemployeelist()
      }
    });
  }

  // Click Check All Employee Checkbox
  $(document).on('click', '#checkallemployee', function(){
    console.log('checkbox all Employee');
    var isChecked = $(this).prop('checked');
    $('.checkallemployee').prop('checked', isChecked);
  })

  // Click Check All Employee Checkbox
  $(document).on('click', '#checkallemployee_sa', function(){
    console.log('checkbox all Employee');
    var isChecked = $(this).prop('checked');
    $('.checkallemployee_sa').prop('checked', isChecked);
  })

  

  // get Employee List para mabutang sa modal nga apply other deduction
  function getperemployeelist(){
    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/getperemployeelist",
      success: function (data) {
        allemployee = data
        // getallemployeeotherdeduction()
        preemployeelist()
        peremployeelistallowance()
      }
    });
  }

  // Per Employee List Function
  function preemployeelist(){
    var otherdeduction_id = $('#otherdeductionid').val()
    var allempod = allemployeeotherdeduction
    $('#modal_preemployee').DataTable({
      lengthMenu: false,
      info: false,
      paging: true,
      searching: true,
      destroy: true,
      lengthChange: false,
      scrollX: false,
      autoWidth: false,
      order: false,
      data: allemployee,
      columns : [
        {"data" : 'firstname'},
        {"data" : null},
        {"data" : null}
      ],
      columnDefs: [
        {
          'targets': 0,
          'orderable': false, 
          'createdCell':  function (td, cellData, rowData, row, col) {
            var text = '<span>'+rowData.lastname+', '+rowData.firstname+'</span>';
            $(td)[0].innerHTML =  text
            $(td).addClass('align-middle  text-left')
            $(td).css('vertical-align', 'middle !important')
          }
        },
        {
          'targets': 1,
          'orderable': false, 
          'createdCell':  function (td, cellData, rowData, row, col) {
              if (rowData.withbasicsalaryinfo == 1) {
                var assignedod = allempod.filter(x=>x.employeeid == rowData.id && x.deductionotherid == otherdeduction_id)[0]
                if (assignedod == null) {
                  var text = '<span class="text-danger">Not Assign</span>'
                } else {
                  var text = '<span class="text-success">Assigned</span>'
                }
              } else {
                var text = '<span class="text-danger">No Basic Salary Info</span>'
              }
              $(td)[0].innerHTML =  text
              $(td).addClass('align-middle  text-center')
            }
        },
        {
          'targets': 2,
          'orderable': false, 
          'createdCell':  function (td, cellData, rowData, row, col) {
              if (rowData.withbasicsalaryinfo == 1) {
                var assignedod = allempod.filter(x=>x.employeeid == rowData.id && x.deductionotherid == otherdeduction_id)[0]
                if (assignedod == null) {
                  var text = '<input type="checkbox" class="checkallemployee" id="checkallemployee'+rowData.id+'" em-id="'+rowData.id+'">';
                } else {
                  var text = '<span class="delete_peremployee" id="delete_peremployee'+rowData.id+'" em-id="'+rowData.id+'"><i class="fas fa-trash text-danger" style="font-size: 18px!important;"></i></span>';
                }
              } else {
                var text = '<input type="checkbox" class="" id="checkallemployee'+rowData.id+'" em-id="'+rowData.id+'" disabled>';
              }
              $(td)[0].innerHTML =  text
              $(td).addClass('align-middle  text-center')
            }
        }
      ]
    })

    var label_text = $($('#modal_preemployee_wrapper')[0].children[0])[0].children[0]
    $(label_text)[0].innerHTML = '<input class="text-center" type="checkbox" id="checkallemployee"><span>&nbsp;&nbsp;Check All Employee</span>'
  }

  // Per Employee List Function ng Standard Allowance
  function peremployeelistallowance(){
    var standardallowance_id = $('#mod_standarallowancedid').val()
    console.log(standardallowance_id);
    var allempsa = allallowancestandard
    $('#modal_permployeestandardallowance').DataTable({
      lengthMenu: false,
      info: false,
      paging: true,
      searching: true,
      destroy: true,
      lengthChange: false,
      scrollX: false,
      autoWidth: false,
      order: false,
      data: allemployee,
      columns : [
        {"data" : 'firstname'},
        {"data" : null},
        {"data" : null}
      ],
      columnDefs: [
        {
          'targets': 0,
          'orderable': false, 
          'createdCell':  function (td, cellData, rowData, row, col) {
            var text = '<span>'+rowData.lastname+', '+rowData.firstname+'</span>';
            $(td)[0].innerHTML =  text
            $(td).addClass('align-middle  text-left')
            $(td).css('vertical-align', 'middle !important')
          }
        },
        {
          'targets': 1,
          'orderable': false, 
          'createdCell':  function (td, cellData, rowData, row, col) {
              if (rowData.withbasicsalaryinfo == 1) {
                var assignedsa = allempsa.filter(x=>x.employeeid == rowData.id && x.allowance_standardid == standardallowance_id)[0]
                if (assignedsa == null) {
                  var text = '<span class="text-danger">Not Assign</span>';
                } else {
                  console.log('assigned');
                  var text = '<span class="text-success">Assigned</span>';
                }
              } else {
                var text = '<span class="text-danger">No Basic Salary Info</span>'
              }
              $(td)[0].innerHTML =  text
              $(td).addClass('align-middle  text-center')
            }
        },
        {
          'targets': 2,
          'orderable': false, 
          'createdCell':  function (td, cellData, rowData, row, col) {
              if (rowData.withbasicsalaryinfo == 1) {
                var assignedsa = allempsa.filter(x=>x.employeeid == rowData.id && x.allowance_standardid == standardallowance_id)[0]
                if (assignedsa == null) {
                  var text = '<input type="checkbox" class="checkallemployee_sa" id="checkallemployee_sa'+rowData.id+'" em-id="'+rowData.id+'">';
                } else {
                  var text = '<span class="delete_peremployee_sa" id="delete_peremployee_sa'+rowData.id+'" em-id="'+rowData.id+'"><i class="fas fa-trash text-danger" style="font-size: 18px!important;"></i></span>';
                }
              } else {
                var text = '<input type="checkbox" class="" id="checkallemployee_sa'+rowData.id+'" em-id="'+rowData.id+'" disabled>';
              }
              $(td)[0].innerHTML =  text
              $(td).addClass('align-middle  text-center')
            }
        }
      ]
    })

    var label_text = $($('#modal_permployeestandardallowance_wrapper')[0].children[0])[0].children[0]
    $(label_text)[0].innerHTML = '<input class="text-center" type="checkbox" id="checkallemployee_sa"><span>&nbsp;&nbsp;Check All Employee</span>'

  }


  // Click Check All Department Checkbox
  $(document).on('click', '#checkalldepartment', function(){
    console.log('checkbox all department');
    var isChecked = $(this).prop('checked');
    $('.checkperdepartment').prop('checked', isChecked);
  })

  function getalldepartments(otherdeductionid){
    $.ajax({
      type: "GET",
      data: {
        otherdeductionid : otherdeductionid
      },
      url: "/payrollclerk/employees/profile/getalldepartments",
      success: function (data) {
        alldep_otherdeduction = data
        perdepartment()
      }
    });
  }

  // Per Employee List Function
  function perdepartment(){
    var otherdeduction_id = $('#otherdeductionid').val()  
    
    $('#modal_perdepartment').DataTable({
      lengthMenu: false,
      info: false,
      paging: true,
      searching: true,
      destroy: true,
      lengthChange: false,
      scrollX: false,
      autoWidth: false,
      order: false,
      data: alldep_otherdeduction,
      columns : [
        {"data" : 'department'},
        {"data" : null},
        {"data" : null}
      ],
      columnDefs: [
        {
          'targets': 0,
          'orderable': false, 
          'createdCell':  function (td, cellData, rowData, row, col) {
            var text = '<span>'+rowData.department+'</span>';
            $(td)[0].innerHTML =  text
            $(td).addClass('align-middle')
              $(td).addClass('text-left')
          }
        },
        {
          'targets': 1,
          'orderable': false, 
          'createdCell':  function (td, cellData, rowData, row, col) {

              if (rowData.has_match == true) {
                var text = '<span class="text-success">Assigned</span>'
              } else {
                var text = '<span class="text-danger">Not Assigned</span>'
              }
              $(td)[0].innerHTML =  text
              $(td).addClass('align-middle')
              $(td).addClass('text-center')
            }
        },
        {
          'targets': 2,
          'orderable': false, 
          'createdCell':  function (td, cellData, rowData, row, col) {
              if (rowData.has_match == true) {
                var text = '<span class="delete_perdepartment" id="delete_perdepartment'+rowData.id+'" dep-id="'+rowData.id+'"><i class="fas fa-trash text-danger" style="font-size: 18px!important;"></i></span>';
              } else {
                var text = '<input type="checkbox" class="checkperdepartment" id="checkperdepartment'+rowData.id+'" dep-id="'+rowData.id+'">';
              }
              $(td)[0].innerHTML =  text
              $(td).addClass('align-middle')
              $(td).addClass('text-center')
            }
        }
      ]
    })

    var label_text = $($('#modal_perdepartment_wrapper')[0].children[0])[0].children[0]
    $(label_text)[0].innerHTML = '<input class="text-center" type="checkbox" id="checkalldepartment"><span>&nbsp;&nbsp;Check All Department</span>'
  }

  function getallemployeeotherdeduction(){
    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/getallemployeeotherdeduction",
      success: function (data) {
        allemployeeotherdeduction = data
        getperemployeelist()
      }
    });
  }

  // Click Every Tabs In Other Deduction Modal
  $(document).on('click', '#tab_peremployee', function(){
    $('.assigning').addClass('assign_peremployee')
    $('.assigning').removeClass('assign_perdepartment')
  })

  $(document).on('click', '#tab_perdepartment', function(){
    $('.assigning').addClass('assign_perdepartment')
    $('.assigning').removeClass('assign_peremployee')
  })

  // click assign in per department
  $(document).on('click', '.assign_perdepartment', function(){
    console.log('assign perdepartment');
    var checkeddepartments = [];
    var setuptype = 'Other deduction'
    var setup = 1
    var valid_data = true
    var setupid = $('#otherdeductionid').val();

    $('.checkperdepartment').each(function(){
      if ( $(this).is(':checked') ) {
        var deptid = $(this).attr('dep-id')
        obj = {
          setuptype : setuptype,
          setup : setup,
          deptid : deptid,
          setupid : setupid
        }
        checkeddepartments.push(obj);
      } 
    })
    
    // return false;
    if (valid_data) {
      $.ajax({
        type: "GET",
        url: "/payrollclerk/employees/profile/savealldepartments",
        data:{
            setups: JSON.stringify(checkeddepartments)
        },
        success: function (data) {
          if(data[0].status == 0){
            Toast.fire({
              type: 'error',
              title: data[0].message
            })
          }else{
            getalldepartments(setupid)
            Toast.fire({
              type: 'success',
              title: data[0].message
            })
          }
        }
      });
    }
  })

  // Click checkbox per employee
  $(document).on('click', '.assign_peremployee', function(){
    var checkedperemployee = [];
    var valid_data = true
    var otherdeductionid = $('#otherdeductionid').val()
    var desc = $('#desc').text()
    var mod_interestrate = parseFloat($('#mod_interestrate').val())
    var mod_loanamount = parseFloat($('#mod_loanamount').val())
    var mod_sdate = $('#mod_sdate').val()
    var mod_edate = $('#mod_edate').val()
    var mod_terms = $('#mod_terms').val()
    var mod_amounttobededuct = parseFloat($('#mod_amounttobededuct').val())

    $('.checkallemployee').each(function(){
      if ( $(this).is(':checked') ) {
        var employeeid = $(this).attr('em-id')
        obj = {
          employeeid : employeeid,
          otherdeductionid : otherdeductionid,
          mod_loanamount : mod_loanamount,
          desc : desc,
          mod_amounttobededuct : mod_amounttobededuct,
          mod_terms : mod_terms,
          mod_edate : mod_edate,
          mod_sdate : mod_sdate,
          mod_interestrate : mod_interestrate
        }
        checkedperemployee.push(obj)
      }
    })

    if (valid_data) {
      $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/saveallperemployee",
      data: {
        per_employeedata: JSON.stringify(checkedperemployee)
      },
      success: function (data) {
        loademployeeotherdeduction()
        // getperemployeelist()
        getallemployeeotherdeduction()
        if(data == 2){
            toastr.warning('Already Exist!', 'Per Employee');
          }else if(data == 1) {
            
            
            toastr.success('Assign Successfully!', 'Per Employee');
          } else {
            ('Invalid Data!', 'Per Employee')
          } 
      }
    });
    }
    
  })

  // Click checkbox per employee Standard Allowance
  $(document).on('click', '.assign_peremployee_sa', function(){
    var checkedperemployee = [];
    var valid_data = true
    var standardallowance_id = $('#mod_standarallowancedid').val()
    var desc = $('#desc_sa').text()

    var mod_amountperday = parseFloat($('#mod_amountperday').val())
    var mod_baseonattendance = $('#mod_baseonattendance').val()
    var mod_amount = parseFloat($('#mod_amount').val())
    var mod_standarallowancedid = $('#mod_standarallowancedid').val()

    $('.checkallemployee_sa').each(function(){
      if ( $(this).is(':checked') ) {
        var employeeid = $(this).attr('em-id')
        obj = {
          employeeid : employeeid,
          standardallowance_id : standardallowance_id,
          mod_amountperday : mod_amountperday,
          mod_baseonattendance : mod_baseonattendance,
          mod_amount : mod_amount,
          desc : desc
        }
        checkedperemployee.push(obj)
      }
    })

    // console.log(checkedperemployee);
    if (valid_data) {
      $.ajax({
      type: "GET",
      url: "/hr/employees/profile/saveperemployeestandardallowance",
      data: {
        per_employeedata: JSON.stringify(checkedperemployee)
      },
      success: function (data) {
        loademployeestandardallowance()
        getallemployeestandardallowance()
        if(data == 2){
            toastr.warning('Already Exist!', 'Per Employee');
          }else if(data == 1) {
            
            
            toastr.success('Assign Successfully!', 'Per Employee');
          } else {
            ('Invalid Data!', 'Per Employee')
          } 
      }
    });
    }
    
  })


  // Click View Other Deduction
  $(document).on('click', '.view_oddeduct', function(){
    var otherdeduction_name = $(this).attr('data-odname')
    var otherdeduction_id = $(this).attr('data-id')
    var empid = $(this).attr('data-empid')
    $('#otherdeductionname').text(otherdeduction_name)

    // console.log(otherdeduction_id);
    // return false;
    console.log(empid);
    $.ajax({
      type: "GET",
      url: "/payrollclerk/employees/profile/v2_viewemployeeotherdeduction",
      data: {
        otherdeduction_id : otherdeduction_id,
        empid : empid
      },
      success: function (data) {
        console.log(data);
        $('#desc').text(data.empotherdeduction.description)
        $('#loadnamount').text('= '+ data.fullamount)
        $('#interestamount').text('= '+ data.interestamount)
        $('#overall').text('= '+ parseFloat(data.totalamount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
        $('#viewdeductionledger').empty()

          var html = `<table class="table table-sm" width="100%">
                        <thead>
                            <th class="text-left" width="55%">Particulars</th>
                            <th class="text-right" width="20%">Charges</th>
                            <th class="text-right" width="15%"></th>
                            <th class="text-right" width="10%">Payments</th>
                        </thead>
                        <tbody>`;

                          data.payrolldetails.forEach(function(item) {
                          html += `<tr>
                              <td class="text-left"><span style="font-weight: bold;">Payroll Date</span> ${item.payrolldate}</td>
                              <td class="text-right"></td>
                              <td class="text-right"></td>
                              <td class="text-right">${parseFloat(item.amountpaid).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            </tr>`;
                          });

                          html += ` <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td class="text-right"><b>Total :</b></td>
                                      <td class="text-right"><b>${parseFloat(data.totalamount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</></td>
                                      <td class="text-center"><b>-</b></td>
                                      <td class="text-right"><b>${parseFloat(data.totalotherdeductionpaid).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</b></td>
                                    </tr>
                                    <tr style="background-color: #17a2b8">
                                      <td class="text-right text-light"></td>
                                      <td class="text-right text-light"><b>Balance :</b></td>
                                      <td class="text-center text-light"><b>${parseFloat(data.balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</b></td>
                                      <td class="text-right"></td>
                                  </tr>
                              </tbody>
                      </table>`;
        $('#viewdeductionledger').append(html)
        $('#modal_view_oddeduct').modal('show')
      }
    });
  })

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

    var days = [];

    $('.workingdayscheck').each(function (){
        if ($(this).is(":checked")){
            days.push($(this).val());
        }
    })

    console.log(am_in);
    console.log(am_out);
    console.log(pm_in);
    console.log(pm_out);
    console.log(attendance_switch);
    console.log(days);


    if (attendance_switch == null || attendance_switch == '') {
      attendance_switch = 0
    } else if (attendance_switch == 1){

      if (shiftid == '' || shiftid == null) {
        $('#select-shift').addClass('is-invalid')
        Toast.fire({
            type: 'error',
            title: 'No Shift Selected'
        })
        valid_data= false
      } else {
        $('#select-shift').removeClass('is-invalid')
        
        if (days.length == 0) {
          $('#days_selected').addClass('is-invalid')
          $('#days_error').show();
          Toast.fire({
            type: 'error',
            title: 'No Days Selected'
          })
          valid_data = false
        } else {
          $('#days_error').hide();
          $('#days_selected').removeClass('is-invalid')
        }
        
        // if (am_in == '' || am_in == null) {
        //   $('#timepickeramin').addClass('is-invalid')
        //   Toast.fire({
        //       type: 'error',
        //       title: 'AM In is empty'
        //   })
        //   valid_data= false
        // }
        // if (am_out == '' || am_out == null) {
        //   $('#timepickeramout').addClass('is-invalid')
        //   Toast.fire({
        //       type: 'error',
        //       title: 'AM Out is empty'
        //   })
        //   valid_data= false
        // }

        // if (pm_in == '' || pm_in == null) {
        //   $('#timepickerpmin').addClass('is-invalid')
        //   Toast.fire({
        //       type: 'error',
        //       title: 'PM In is empty'
        //   })
        //   valid_data= false
        // }

        // if (pm_out == '' || pm_out == null) {
        //   $('#timepickerpmout').addClass('is-invalid')
        //   Toast.fire({
        //       type: 'error',
        //       title: 'PM Out is empty'
        //   })
        //   valid_data= false
        // }
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
      $('#select-salarytype').addClass('is-invalid')
      Toast.fire({
          type: 'error',
          title: 'No Salary Selected'
      })
      valid_data= false
    } else {
      $('#select-salarytype').removeClass('is-invalid')
    }

    // return false;
    
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
          pm_out : pm_out,
          days : days
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
