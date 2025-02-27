<style>
	.select2-container--default .select2-selection--single {
		height: 37px!important;
	}
	html {
    scroll-behavior: smooth!important;
  }
  /* Add this style to your stylesheet or in a style tag in the head of your HTML */
  .date-table {
      width: 100%;
      border-collapse: collapse;
  }

  .date-table th {
      white-space: nowrap; /* Prevent text wrapping in headers */
      min-width: 100px; /* Set a minimum width for each header cell */
      text-align: left; /* Adjust text alignment as needed */
  }

  .overloadcontainer {
      overflow-x: auto; /* Enable horizontal scrolling */
      max-width: 100%; /* Ensure the container does not exceed the viewport width */
  }
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
    /* opacity: 0.5; */
  }
  .opacityy {
    opacity: 0.5;
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

  .sticky-header {
    width: 400px;
    position: fixed;
    top: 85px;
    right: 25px;
    z-index: 1000;
  }

  .card-nav-header {
    border-radius: 10px;
    box-shadow: 0 0 1px rgb(0 0 0), 0 1px 3px rgb(113 105 105) !important;
  }
</style>
<div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
    <div id="emp_profile" class="pro-overview tab-pane fade active show">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<fieldset class="form-group border p-2 mb-2">
							<legend class="w-auto m-0" style="font-size: 18px; font-weight: bold;">Basic Details</legend><input id="employeeid" type="hidden" value="{{$profileinfo->id}} ">
							<div class="row">
								@php
									$title = $profileinfo->title ?? '';
									$firstName = $profileinfo->firstname ?? '';
									$middleName = $profileinfo->middlename ?? '';
									$lastName = $profileinfo->lastname ?? '';
									$suffix = $profileinfo->suffix ?? '';
									$gender = $profileinfo->gender ?? '';
									$dob = $profileinfo->dob ?? '';
									$maritalStatus = $profileinfo->maritalstatusid ?? '';
									$religionId = $profileinfo->religionid ?? '';
									$nationalityId = $profileinfo->nationalityid ?? '';
									$numOfChildren = $profileinfo->numberofchildren ?? '';
									$spouseEmployment = $profileinfo->spouseemployment ?? '';
									$presentAddress = $profileinfo->address ?? '';
									$primaryAddress = $profileinfo->primaryaddress ?? '';
									$email = $profileinfo->email ?? '';
									$contactNum = $profileinfo->contactnum ?? '';
									$licenseNo = $profileinfo->licno ?? '';
									$sssid = $profileinfo->sssid ?? '';
									$philid = $profileinfo->philhealtid ?? '';
									$pagibigid = $profileinfo->pagibigid ?? '';
									$tinid = $profileinfo->tinid ?? '';
									$yos = $yearsOfService ?? '';
								@endphp
								<div class="col-md-2">
									<div class="form-group">
										<small class="text-bold" style="font-size: 15px;">Title</small>
										<input type="text" class="form-control form-control-sm" name="title" id="profiletitle"
											   value="{{ $title }}"
											   oninput="this.value = this.value.toUpperCase()" style="height: 37px!important;">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<small class="text-bold" style="font-size: 15px;">First Name</small>
										<input type="text" class="form-control form-control-sm" name="fname" id="profilefname"
											   value="{{ $firstName }}"
											   oninput="this.value = this.value.toUpperCase()" style="height: 37px!important;">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<small class="text-bold" style="font-size: 15px;">Middle Name</small>
										<input type="text" class="form-control form-control-sm" name="mname" id="profilemname"
											   value="{{ $middleName }}"
											   oninput="this.value = this.value.toUpperCase()" style="height: 37px!important;">
									</div>
								</div>
								<div class="col-md-3">
									<div class=" form-group">
										<small class="text-bold" style="font-size: 15px;">Last Name</small>
										<input type="text" class="form-control form-control-sm" name="lname" id="profilelname"
											   value="{{ $lastName }}"
											   oninput="this.value = this.value.toUpperCase()" style="height: 37px!important;">
									</div>
								</div>
								<div class="col-md-1">
									<div class="form-group">
										<small class="text-bold" style="font-size: 15px;">Suffix</small>
										<input type="text" class="form-control form-control-sm" name="suffix" id="profilesuffix"
											   value="{{ $suffix }}"
											   oninput="this.value = this.value.toUpperCase()" style="height: 37px!important;">
									</div>
								</div>
								<div class="col-md-1">
									<div class="form-group">
									  <small class="text-bold" style="font-size: 15px;">Acad Title</small>
									  <input type="text" class="form-control form-control-sm" name="suffix" id="profileacadtitle" value="{{$employeeinfo->acadtitle}}" oninput="this.value = this.value.toUpperCase()" style="height: 37px!important;">
									</div>
								  </div>
								<div class="col-md-2">
									<div class="form-group">
										<small class="text-bold" style="font-size: 15px;">Gender</small>
										@if ($gender == null)
											<select class="select form-control form-control-sm text-uppercase" name="gender" id="profilegender">
												<option value="male">Male</option>
												<option value="female">Female</option>
											</select>
										@else
											<select class="select form-control form-control-sm text-uppercase select2" name="gender" id="profilegender">
												<option value="male" {{ "male" == strtolower($gender) ? 'selected' : '' }}>Male</option>
												<option value="female" {{ "female" == strtolower($gender) ? 'selected' : '' }}>Female</option>
											</select>
										@endif
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<small class="text-bold" style="font-size: 15px;">Birth Date</small>
										<div class="cal-icon">
											<input class="form-control datetimepicker form-control-sm" type="date" name="dob" id="profiledob"
												   value="{{ $dob }}" style="height: 37px!important;" required/>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<small class="text-bold" style="font-size: 15px;">Civil Status</small>
										<select class="select form-control text-uppercase form-control-sm" name="civilstatusid" id="profilecivilstatusid">
											@if ($maritalStatus == 0)
												@foreach ($civilstatus as $cstatus)
													<option value="{{ $cstatus->id }}">{{ $cstatus->civilstatus }}</option>
												@endforeach
											@else
												@foreach ($civilstatus as $cstatus)
													<option value="{{ $cstatus->id }}" {{ $cstatus->id == $maritalStatus ? 'selected' : '' }}>{{ $cstatus->civilstatus }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
								<div class="col-md-5">
									<div class="form-group">
										<small class="text-bold" style="font-size: 15px;">Religion</small>
										<select class="select2 form-control text-uppercase form-control-sm" name="religionid" id="profilereligionid">
											@if ($religionId == null || $religionId == 0)
												@foreach ($religions as $religioneach)
													<option value="{{ $religioneach->id }}">{{ $religioneach->religionname }}</option>
												@endforeach
											@else
												@foreach ($religions as $religioneach)
													<option value="{{ $religioneach->id }}" {{ $religioneach->id == $religionId ? 'selected' : '' }}>{{ $religioneach->religionname }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<small class="text-bold" style="font-size: 15px;">Nationality</small>
										<select class="select form-control text-uppercase form-control-sm" name="nationalityid" id="profilenationalityid">
											@if ($nationalityId == null || $nationalityId == '0')
												@foreach ($nationality as $nationalityeach)
													<option value="{{ $nationalityeach->id }}" {{ strtolower($nationalityeach->nationality) == 'filipino' ? 'selected' : '' }}>{{ $nationalityeach->nationality }}</option>
												@endforeach
											@else
												@foreach ($nationality as $nationalityeach)
													<option value="{{ $nationalityeach->id }}" {{ $nationalityeach->id == $nationalityId ? 'selected' : '' }}>{{ $nationalityeach->nationality }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<small class="text-bold" style="font-size: 15px;">Number of children</small>
										<input type="number" class="form-control form-control-sm" name="numofchildren" id="profilenumofchildren" value="{{ $numOfChildren }}" min="0" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" style="height: 37px!important;">
									</div>
								</div>
								<div class="col-md-7">
									<div class="form-group">
										<small class="text-bold" style="font-size: 15px;">Employment of Spouse</small>
										<input type="text" class="form-control text-uppercase form-control-sm" name="spouseemployment" id="profilespouseemployment" value="{{ $spouseEmployment }}" style="height: 37px!important;">
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
							<input type="text" class="form-control text-uppercase form-control-sm" name="address" id="profileaddress" value="{{ $presentAddress }}" style="height: 37px!important;">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<small class="text-bold" style="font-size: 15px;">Primary Address</small>
							<input type="text" class="form-control text-uppercase form-control-sm" name="address" id="profileprimaryaddress" value="{{ $primaryAddress }}" style="height: 37px!important;">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<small class="text-bold" style="font-size: 15px;">Email Address</small>
							<input type="email" class="form-control form-control-sm" name="email" id="profileemail" value="{{ $email }}" style="height: 37px!important;">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<small class="text-bold" style="font-size: 15px;">Contact Number</small>
							<input type="text" class="form-control form-control-sm" id="contactnum" name="contactnum" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" value="{{ $contactNum }}" style="height: 37px!important;">
						</div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<small class="text-bold" style="font-size: 15px;">SSS ID</small>
							<input type="text" name="sssid" class="form-control form-control-sm" id="profilesssid" value="{{ $sssid }}" style="height: 37px!important;">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<small class="text-bold" style="font-size: 15px;">Philhealth ID</small>
							<input type="text" name="philid" class="form-control form-control-sm" id="profilephilid" value="{{ $philid }}" style="height: 37px!important;">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<small class="text-bold" style="font-size: 15px;">Pag-Ibig ID</small>
							<input type="text" name="pagibigid" class="form-control form-control-sm" id="profilepagibigid" value="{{ $pagibigid }}" style="height: 37px!important;">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<small class="text-bold" style="font-size: 15px;">TIN ID</small>
							<input type="text" name="tinid" class="form-control form-control-sm" id="profiletinid" value="{{$tinid}}" style="height: 37px!important;">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<small class="text-bold" style="font-size: 15px;">License No.</small>
							<input type="text" name="licenseno" class="form-control form-control-sm" id="profilelicenseno" value="{{ $licenseNo }}" style="height: 37px!important;">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<small class="text-bold" style="font-size: 15px;">Date Hired</small>
							<input type="date" name="datehired" class="form-control datetimepicker form-control-sm" id="profiledatehired" value="{{ optional($employeeinfo)->date_joined ? \Carbon\Carbon::parse($employeeinfo->date_joined)->toDateString() : '' }}" style="height: 37px!important;">
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group">
							<small class="text-bold" style="font-size: 15px;">Y.O.S</small>
							<input type="number" name="dateofservice" class="form-control form-control-sm" id="profiledateofservice" value="{{$yos}}" style="height: 37px!important;">
						  </div>
					  </div>
					<div class="col-md-3">
						<div class="form-group">
							<small class="text-bold" style="font-size: 15px;">Department</small>
							<select class="form-control form-control-sm select2" id="select-department"></select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<small class="text-bold" style="font-size: 15px;">Designation</small>
							<select class="form-control form-control-sm select2" id="select-designation"></select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="parttimer">
                                <label for="parttimer">
                                    Part Timer
                                </label>
                            </div>
                        </div>
					</div>
				</div>
				<div class="submit-section">
					<button type="button" class="btn btn-primary btn-sm submit-btn float-right" data-dismiss="modal" id="updatepersonalinformation">Update</button>
				</div>
			</div>
		</div>

		<div class="card" id="basic_salary_info">
			<div class="card-header bg-dark" id="basic_info">
			  <h5 class="" style="color: rgb(235, 235, 235);"><i class="fas fa-layer-group" style="padding-top: 10px; padding-right: 5px;"></i>BASIC SALARY INFORMATION</h5>
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
						<input type="number" step="any" class="form-control" name="amountperday" id="amountperday" min="0"/>
						<span class="invalid-feedback" role="alert">
						<strong>Salary amount <span id="saltypename"></span> is required</strong>
						</span>
						</div>
				
						<div class="col-md-4">
						<label>No. of hours per day</label>
						<input type="number" step="any" class="form-control" name="hoursperday" id="hoursperday" min="0"/>
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
					<div class="col-md-8">
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
					<div class="col-md-2" hidden>
					<div class="form-group mb-0 text-right" style="" id="">
						<div class="form-check pr-3">
						<input class="form-check-input flexitime" id="" type="checkbox" @if($basicsalaryinfo) @if ($basicsalaryinfo->flexitime == 1) checked @endif @endif>
						<small style="font-size: 18px;"><label class="form-check-label text-info">&nbsp;Flexi Time</label></small>
						</div>
					</div>
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
					<div class="col-md-5"></div>
					<div class="col-md-4 text-right" style="display: inline-grid;" hidden>
						<b style="font-size: 18px;"><label class="form-check-label">Half Day Saturday</label></b>
						<div class="form-group mb-0 text-right" style="" id="">
							<div class="form-check pr-3">
								<input class="form-check-input halfdaysat" id="halfdaysatam" halfdaystatus="1" type="checkbox" @if($basicsalaryinfo) @if ($basicsalaryinfo->halfdaysat == 1) checked @endif @endif>
								<small style="font-size: 18px;"><label class="form-check-label text-info">&nbsp;AM</label></small>
							</div>
						</div>
						<div class="form-group mb-0 text-right" style="" id="">
							<div class="form-check pr-3">
								<input class="form-check-input halfdaysat" id="halfdaysatpm" halfdaystatus="2" type="checkbox" @if($basicsalaryinfo) @if ($basicsalaryinfo->halfdaysat == 2) checked @endif @endif>
								<small style="font-size: 18px;"><label class="form-check-label text-info">&nbsp;PM</label></small>
							</div>
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
				
				<div class="row mt-4">
					<div class="col-md-12">
						<label><i class="fas fa-calendar"></i> WORK DAY</label>
					</div>
					{{-- <div class="col-md-4">
						
					</div>
					<div class="col-md-2">
						<label>AM IN</label>
						<input type="time" id="timepickeramin"  class="timepick form-control form-control-sm" name="am_in"/>
						<span class="invalid-feedback" role="alert">
						<strong>AM In is required</strong>
					</div>
					<div class="col-md-2">
						<label>AM OUT</label>
						<input type="time" id="timepickeramout" class="timepick form-control form-control-sm" name="am_out"/>
						<span class="invalid-feedback" role="alert">
						<strong>AM Out is required</strong>
					</div>
					<div class="col-md-2">
						<label>PM IN</label>
						<input type="time" id="timepickerpmin"  class="timepick form-control form-control-sm" name="pm_in"/>
						<span class="invalid-feedback" role="alert">
						<strong>PM In is required</strong>
					</div>
					<div class="col-md-2">
						<label>PM OUT</label>
						<input type="time" id="timepickerpmout"  class="timepick form-control form-control-sm" name="pm_out"/>
						<span class="invalid-feedback" role="alert">
						<strong>PM Out is required</strong>
					</div> --}}
				</div>
				<hr>
				<div class="row mt-2">
					<div class="col-md-10"></div>
					<div class="col-md-2"  style=" display: grid;" id="submitbuttoncontainer">
					@if (count(collect($basicsalaryinfo)) == 0)
						<button type="button" class="btn btn-primary" id="basicsalary_submitbutton">Save Changes</button>
					@else
						{{-- <button type="button" class="btn btn-warning" id="basicsalary_editbutton">Edit Changes</button> --}}
						<button type="button" class="btn btn-primary btn-sm submit-btn float-right" id="basicsalary_updatebutton">Update</button>
					@endif
					</div>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header bg-dark" id="basic_info">
			  <h5 class="" style="color: rgb(235, 235, 235);"><i class="fas fa-layer-group" style="padding-top: 10px; padding-right: 5px;"></i>COLLEGE LOAD</h5>
			</div>
			<div class="card-body">
			  <div class="row">
				<div class="col-md-3">
				  <div class="form-group" style="">
					<label for="inputState">Select School Year</label>
					<select class="form-control form-control-sm select2" id="profileselect-sy"></select>
				  </div>
				</div>
				<div class="col-md-3">
				  <div class="form-group" style="">
					<label for="inputState">Select Semester</label>
					<select class="form-control form-control-sm select2" id="profileselect-semester"></select>
				  </div>
				</div>
				<div class="col-md-3">
				  
				</div>
			  </div>
			  <div class="row">
				<div class="col-md-12 collegeloadtable">
		
				</div>
			  </div>
			</div>
		</div>
	
		
		<div class="card border-primary mb-3">
			<div class="card-header bg-dark" id="basic_info" style="display: flex; align-items: center;">
				<h5 class="" style="color: rgb(235, 235, 235;">
					<i class="fas fa-layer-group" style="padding-top: 10px; padding-right: 5px;"></i>EMERGENCY CONTACT
				</h5>
				<button type="button" class="btn btn-sm btn-primary ml-auto" data-toggle="modal" data-target="#edit_emergency_contact">
					<i class="fa fa-plus"></i> Add
				</button>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
                        <table width="100%" id="emergency_contact_dt" class="table table-sm" style="font-size: 15px; table-layout: fixed;">
							<thead>
								<tr>
									<td class="p-0" style="width: 50%; font-weight: bold;">Name</td>
									<td class="p-0 text-left" style="width: 25%; font-weight: bold;">Relationship</td>
									<td class="p-0 text-center" style="width: 20%; font-weight: bold;">Phone</td>
									<td class="p-0 text-center" style="width: 5%; font-weight: bold;"></td>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
                    </div>
				</div>
				<div class="row">
					<div class="col-md-12 text-right"></div>
				</div>
			</div>
		</div>


		
		<div class="card border-primary mb-3">
			<div class="card-header bg-dark" id="basic_info" style="display: flex; align-items: center;">
				<h5 class="" style="color: rgb(235, 235, 235;">
					<i class="fas fa-layer-group" style="padding-top: 10px; padding-right: 5px;"></i>PERSONAL CONTACTS
				</h5>
				<button type="button" class="btn btn-sm btn-warning ml-auto" data-toggle="modal" data-target="#edit_accounts">
					<i class="fa fa-edit"></i> Edit
				</button>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<table class="table table-sm" style="font-size: 15px; table-layout: fixed;">
							<thead>
								<tr>
									<th class="p-0" style="width: 50%;" >Description</th>
									<th class="p-0"style="width: 40%;">Account #</th>
									<th class="p-0" style="width: 10%;"></th>
								</tr>
							</thead>
							<tbody>
								@if(count($profileinfo->accounts) > 0)
									@foreach($profileinfo->accounts as $account)
										<tr id="{{$account->id}}">
											<td class="p-0">
												{{$account->accountdescription}}
											</td>
											<td class="p-0">
												{{$account->accountnum}}
											</td>
											<td class="text-right p-0">
												<button type="button" class="btn btn-sm deleteaccount" accountdescription="{{$account->accountdescription}}" accountnumber="{{$account->accountnum}}" ><i class="fa fa-trash text-danger"></i></button>
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 text-right"></div>
				</div>
			</div>
		</div>
		
		
		<div class="card border-primary mb-3">
			<div class="card-header bg-dark" id="basic_info" style="display: flex; align-items: center;">
				<h5 class="" style="color: rgb(235, 235, 235;">
					<i class="fas fa-layer-group" style="padding-top: 10px; padding-right: 5px;"></i>FAMILY INFORMATION
				</h5>
				<button type="button" class="btn btn-sm btn-warning ml-auto" data-toggle="modal" data-target="#family_info_modal">
					<i class="fa fa-edit"></i> Edit
				</button>
			</div>
			<div class="card-body">
				<div class="row">
					@if(session()->has('messageUpdated'))
						<div class="col-md-12">
								<div class="alert alert-success alert-dismissible col-12">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h5><i class="icon fas fa-check"></i> Alert!</h5>
									{{ session()->get('messageUpdated') }}
								</div>
						</div>
					@endif
					<div class="col-md-12">
						<table class="table table-sm" style="font-size: 15px; table-layout: fixed;">
							<thead>
								<tr>
									<th class="p-0" style="width: 50%;" >Name</th>
									<th class="p-0" style="width: 30%;" >Phone</th>
									<th class="p-0" style="width: 20%;" ></th>
								</tr>
							</thead>
							<tbody>
								@if(count($profileinfo->familyinfo)==0)
								@else
									@foreach($profileinfo->familyinfo as $familyinfo)
										<tr>
											<td class="text-uppercase p-0">
												<div style="display: inline-grid;">
													{{$familyinfo->famname}}
													<span class="text-muted" style="font-size: 10px;">{{$familyinfo->famrelation}}</span>
												</div>
											</td>
											<td class="text-uppercase p-0" style="vertical-align: middle;">{{$familyinfo->contactnum}}</td>
											<td class="float-right p-0">
												<button type="button" class="btn btn-sm deletefamilymember" familyid="{{$familyinfo->id}}" familymembername="{{$familyinfo->famname}}" id="{{$profileinfo->id}}">
													<i class="fas fa-trash text-danger"></i>
												</button>
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 text-right"></div>
				</div>
			</div>
		</div>
		
		<div class="card border-primary mb-3">
			<div class="card-header bg-dark" id="basic_info" style="display: flex; align-items: center;">
				<h5 class="" style="color: rgb(235, 235, 235;">
					<i class="fas fa-layer-group" style="padding-top: 10px; padding-right: 5px;"></i>EDUCATIONAL BACKGROUND
				</h5>
				<button type="button" class="btn btn-sm btn-primary ml-auto" data-toggle="modal" data-target="#profile_addeducbg">
					<i class="fa fa-plus"></i> Add
				</button>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
                        @if(count($profileinfo->educationalbackground)>0)
                            <table class="table table-sm" style="font-size: 15px; table-layout: fixed;">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;">School Year</th>
                                        {{-- <th>University</th>
                                        <th>Address</th>
                                        <th>Course</th>
                                        <th>Major</th>
                                        <th>Awards</th> --}}
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
								@foreach($profileinfo->educationalbackground as $educinfo)
									<tr class="tr-each-educinfo">
										<td class="p-0"><input type="text" class="form-control form-control-sm m-0 input-educbg-sy" value="{{$educinfo->schoolyear}}"/></td>
										<td class="p-0">
											<table class="table">
												<tr>
													<td class="cell-label"><b class="text-muted">Course</b> <span style="float: inline-end;">:<span></td>
													<td class="p-0">
														<input type="text" class="form-control form-control-sm m-0 input-educbg-course" style="border: none; outline: none;" value="{{$educinfo->coursetaken}}"/>
													</td>
													<td class="cell-label"><b class="text-muted">Major</b> <span style="float: inline-end;">:<span></td>
													<td class="p-0">
														<input type="text" class="form-control form-control-sm m-0 input-educbg-major" style="border: none; outline: none;" value="{{$educinfo->major}}"/>
													</td>
												</tr>
												<tr>
													<td class="cell-label"><b class="text-muted">Awards</b> <span style="float: inline-end;">:<span></td>
													<td class="p-0" colspan="4">
														<input type="text" class="form-control form-control-sm m-0 input-educbg-awards" style="border: none; outline: none;" value="{{$educinfo->awards}}"/>
													</td>
												</tr>
												<tr>
													<td class="cell-label"><b class="text-muted">University</b> <span style="float: inline-end;">:<span></td>
													<td class="p-0">
														<input type="text" class="form-control form-control-sm m-0 input-educbg-schoolname" style="border: none; outline: none;" value="{{$educinfo->schoolname}}"/>
													</td>
													<td class="cell-label"><b class="text-muted">Address</b> <span style="float: inline-end;">:<span></td>
													<td class="p-0">
														<input type="text" class="form-control form-control-sm m-0 input-educbg-schooladdress" style="border: none; outline: none;" value="{{$educinfo->schooladdress}}"/>
													</td>
												</tr>
												<tr>
													<td class="text-right" colspan="4">
														<button type="button" class="btn btn-sm btn-default btn-edit-educinfo"><i class="fa fa-edit"></i> Edit</button>
														<button type="button" class="btn btn-sm btn-default btn-update-educinfo" data-id="{{$educinfo->id}}"><i class="fa fa-share"></i> Save Changes</button>
														<button type="button" class="btn btn-sm btn-default btn-delete-educinfo" data-id="{{$educinfo->id}}"><i class="text-danger fa fa-trash-alt"></i> Delete</button>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								@endforeach
							</tbody>
                            </table>
                        @endif
                    </div>
				</div>
				<div class="row">
					<div class="col-md-12 text-right"></div>
				</div>
			</div>
		</div>
		
		<div class="card border-primary mb-3">
			<div class="card-header bg-dark" id="basic_info" style="display: flex; align-items: center;">
				<h5 class="" style="color: rgb(235, 235, 235;">
					<i class="fas fa-layer-group" style="padding-top: 10px; padding-right: 5px;"></i>WORK EXPERIENCE
				</h5>
				<button type="button" class="btn btn-sm btn-primary ml-auto" data-toggle="modal" data-target="#experience_info">
					<i class="fa fa-plus"></i> Add
				</button>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
                        @if(count($profileinfo->experiences)>0)
                            <table class="table table-sm" style="font-size: 15px; table-layout: fixed;">
                                <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>Job Position</th>
                                        <th style="width: 15%;">Period</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($profileinfo->experiences as $workexpe)
                                        <tr class="tr-each-workexpe tr-each-workexpe{{$workexpe->id}}">
                                            <td class="p-0 pl-5">
                                                <table class="table">
                                                    <tr>
                                                        <td>Name</td>
                                                        <td class="p-0">
                                                        <input type="text" class="form-control form-control-sm m-0 input-workexpe-companyname" style="border: none;" value="{{$workexpe->companyname}}"/>
                                                    </tr>
                                                    <tr>
                                                        <td>Address</td>
                                                        <td class="p-0">
                                                        <input type="text" class="form-control form-control-sm m-0 input-workexpe-companyaddress" style="border: none;" value="{{$workexpe->companyaddress}}"/>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td class="p-0">
                                                <input type="text" class="form-control form-control-sm m-0 input-workexpe-position" style="border: none;" value="{{$workexpe->position}}"/>
                                            </td>
                                            <td class="p-0">
                                                <table class="table">
                                                    <tr>
                                                        <td>From</td>
                                                        <td class="p-0">
                                                        <input type="date" class="form-control form-control-sm m-0 input-workexpe-periodfrom" style="border: none;" value="{{$workexpe->periodfrom}}"/>
                                                    </tr>
                                                    <tr>
                                                        <td>To</td>
                                                        <td class="p-0">
                                                        <input type="date" class="form-control form-control-sm m-0 input-workexpe-periodto" style="border: none;" value="{{$workexpe->periodto}}"/>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            
                                            <td class="text-right" colspan="3">
                                                <button type="button" class="btn btn-sm btn-default btn-edit-workexpe" data-id="{{$workexpe->id}}"><i class="fa fa-edit"></i> Edit</button>
                                                <button type="button" class="btn btn-sm btn-default btn-update-workexpe" data-id="{{$workexpe->id}}"><i class="fa fa-share"></i> Save</button>
                                                <button type="button" class="btn btn-sm btn-default btn-delete-workexpe" data-id="{{$workexpe->id}}"><i class="text-danger fa fa-trash-alt"></i> Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
				</div>
				<div class="row">
					<div class="col-md-12 text-right"></div>
				</div>
			</div>
		</div>
		
		
		{{--Emergency Contact Modal--}}
		
		<div id="edit_emergency_contact" class="modal custom-modal fade" role="dialog" style="display: none;">
			<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header bg-info">
						<h5 class="modal-title"><strong>Emergency Contact</strong></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
					<!--<form {{--action="/hr/updateemergencycontact" method="get"--}}>
							{{-- <input type="hidden" class="form-control" name="linkid" value="custom-content-above-home" /> --}}
							<label>Name</label>
							@if($profileinfo->emercontactname == null)
							<input type="text" class="form-control form-control-sm" name="emergencyname" id="emergencyname" required/>
							@else
							<input type="text" class="form-control form-control-sm" name="emergencyname" id="emergencyname" value="{{$profileinfo->emercontactname}}" required/>
							@endif
							<br>
							<label>Relationship</label>
							@if($profileinfo->emercontactname == null)
							<input type="text" class="form-control form-control-sm" name="relationship" id="emergencyrelationship" required/>
							@else
							<input type="text" class="form-control form-control-sm" name="relationship" id="emergencyrelationship" value="{{$profileinfo->emercontactrelation}}" required/>
							@endif
							<br>
							<label>Contact Number</label>
							@if($profileinfo->emercontactnum == null)
							<input type="text" class="form-control form-control-sm" id="emergencycontactnumber" name="contactnumber" minlength="11" maxlength="11" data-inputmask-clearmaskonlostfocus="true" required/>
							@else
							<input type="text" class="form-control form-control-sm" id="emergencycontactnumber" name="contactnumber" value="{{$profileinfo->emercontactnum}}" minlength="11" maxlength="11" data-inputmask-clearmaskonlostfocus="true" required/>
							@endif
							<br>
							<div class="submit-section">
								<button type="button" class="btn btn-primary submit-btn float-right" data-dismiss="modal" id="updateemergencycontact">Update</button>
							</div>
					</form>-->
						<form {{--action="/hr/updateemergencycontact" method="get"--}}>
							<label>Name</label>
							<input type="text" class="form-control form-control-sm" name="emergencyname" id="emergencyname" required/>
							<br>
							<label>Relationship</label>
							<input type="text" class="form-control form-control-sm" name="relationship" id="emergencyrelationship" required/>
							<br>
							<label>Contact Number</label>
							<input type="text" class="form-control form-control-sm" id="emergencycontactnumber" name="contactnumber" minlength="11" maxlength="11" data-inputmask-clearmaskonlostfocus="true" required/>
							<br>
							<div class="submit-section">
								<button type="button" class="btn btn-primary submit-btn float-right" data-dismiss="modal" id="updateemergencycontact">Add</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		
		{{--Personal Contact Modal--}}
		<div id="edit_accounts" class="modal custom-modal fade" role="dialog" style="display: none;">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header bg-info">
						<h5 class="modal-title"><strong>Update Personal Accounts</strong></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<form {{--action="/hr/updateaccounts" method="get"--}}>
							<div class="addrowaccounts">
								<table width="100%" class="table table-sm table-bordered">
									<thead>
										<tr>
											<th width="56%">&nbsp;Description <span class="text-danger">*</span></th>
											<th width="40%">&nbsp;Account # <span class="text-danger">*</span></th>
											<th width="4%"></th>
										</tr>
									</thead>
									<tbody>
										@if(count($profileinfo->accounts) == 0)
											<tr>
												<td class="p-0">
													<input type="text" name="newaccountdescription[]" class="form-control form-control-sm" style="border: none;" required/>
												</td>
												<td class="p-0">
													<input type="text" name="newaccountnumber[]" class="form-control form-control-sm" style="border: none;" required/>
												</td>
												<td class="p-0 text-center bg-danger">
													<button type="button" class="btn btn-danger btn-sm removeaddaccountrow"><i class="fa fa-times"></i></button>
												</td>
											</tr>
										@else
											@foreach($profileinfo->accounts as $employee_account)
												<tr>
													<td class="p-0">
														<input type="text" name="oldaccountdescription[]" class="form-control form-control-sm" value="{{$employee_account->accountdescription}}" style="border: none;" required/>
													</td>
													<td class="p-0">
														<input type="text" name="oldaccountnumber[]" class="form-control form-control-sm" value="{{$employee_account->accountnum}}" style="border: none;" required/>
													</td>
													<td class="p-0">
														<input type="hidden" name="oldaccountid[]" class="form-control form-control-sm" value="{{$employee_account->id}}" style="border: none;" required/>
													</td>
												</tr>
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
							<br>
							<div class="submit-section">
								<button type="button" class="btn btn-primary btn-sm float-left addrowaccountsbutton">Add Account</button>
								<button type="submit" class="btn btn-primary btn-sm float-right" data-dismiss="modal" id="updateaccounts">Update</button>
							</div>
						
						</form>
					</div>
				</div>
			</div>
		</div> 
		
		
		{{--Family Information Modal--}}
		<div id="family_info_modal" class="modal fade" role="dialog" style="display: none;">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<form {{--action="/hr/updatefamilyinfo" method="get"--}}>
						<div class="modal-header bg-info">
							<h5 class="modal-title"><strong>Family Information</strong></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body" style="overflow-x: scroll;">
							<div class="row">
								<div class="col-md-12">
									{{-- <input type="hidden" class="form-control" name="id" value="{{$profileinfo->id}}" required/> --}}
									@if(count($profileinfo->familyinfo)==0)
										<table width="100%" class="table table-sm table-bordered" style="table-layout: fixed;">
											<thead>
												<tr>
													<th style="width: 40%;">&nbsp;Name</th>
													<th style="width: 31%;">&nbsp;Relationship</th>
													<th style="width: 25%;">&nbsp;Contact Number</th>
													<th style="width: 4%;"></th>
												</tr>
											</thead>
											<tbody id="familytbody">
												<tr>
													<td class="p-0 text-uppercase"><input class="form-control form-control-sm text-uppercase" type="text" name="familyname[]"  style="border: none!important;" required/></td>
													<td class="p-0 text-uppercase"><input class="form-control form-control-sm text-uppercase" type="text" name="familyrelation[]"  style="border: none!important;"/></td>
													<td class="p-0 text-uppercase"><input class="form-control form-control-sm text-uppercase familycontactnum" type="text" name="familynum[]" minlength="11" maxlength="13" data-inputmask-clearmaskonlostfocus="true" style="border: none!important;"/></td>
													<td class="p-0 bg-danger"><button type="button" class="btn btn-sm btn-danger btn-block deleterow"><i class="fa fa-times"></i></button></td>
												</tr>
											</tbody>
										</table>
									@else
										<table width="100%" class="table table-sm table-bordered" style="table-layout: fixed;">
											<thead>
												<tr>
													<th style="width: 40%;">&nbsp;Name</th>
													<th style="width: 31%;">&nbsp;Relationship</th>
													<th style="width: 25%;">&nbsp;Contact Number</th>
													<th style="width: 4%;"></th>
												</tr>
											</thead>
											<tbody id="familytbody">
												@foreach($profileinfo->familyinfo as $family)
													<tr>
														<td class="p-0"><input class="form-control form-control-sm" type="hidden" name="oldfamilyid[]" value="{{$family->id}}"/><input class="form-control" type="text" name="oldfamilyname[]" value="{{$family->famname}}" style="border: none!important;" required/></td>
														<td class="p-0"><input class="form-control form-control-sm" type="text" name="oldfamilyrelation[]" value="{{$family->famrelation}}" style="border: none!important;" /></td>
														<td class="p-0"><input class="form-control form-control-sm familycontactnum" type="text" name="oldfamilynum[]" value="{{$family->contactnum}}" minlength="11" maxlength="13" data-inputmask-clearmaskonlostfocus="true"  style="border: none!important;"/></td>
														<td class="p-0">&nbsp;</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									@endif
									
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-12">
							<span class="float-left ml-3">
							<button type="button" class="btn btn-sm btn-info pr-4 pl-4 addrow"><i class="fa fa-plus"></i>&nbsp; Add More</button>
							</span>
							</div>
						</div>
						<br>
						<div class="modal-footer justify-content-between">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary" id="updatefamilyinfo" data-dismiss="modal" >Update</button>
						</div>
					</form>
				</div>
			</div>
		</div> 
		
		
		{{--Educational Background Modal--}}
		<div id="profile_addeducbg" class="modal custom-modal fade" role="dialog" style="display: none;">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header bg-info">
						<h5 class="modal-title"><strong>Educational Background</strong></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row mb-2">
							<div class="col-md-12">
								<label>School Year</label>
								<input type="text" class="form-control form-control-sm" id="input-educbg-sy"/>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-12">
								<label>University</label>
								<input type="text" class="form-control form-control-sm" id="input-educbg-university"/>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-12">
								<label>Address</label>
								<input type="text" class="form-control form-control-sm" id="input-educbg-address"/>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-12">
								<label>Course</label>
								<input type="text" class="form-control form-control-sm" id="input-educbg-course"/>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-12">
								<label>Major</label>
								<input type="text" class="form-control form-control-sm" id="input-educbg-major"/>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-12">
								<label>Awards</label>
								<input type="text" class="form-control form-control-sm" id="input-educbg-awards"/>
							</div>
						</div>
					</div>
					<div class="modal-footer justify-content-between">
					  <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close-addeducbg">Close</button>
					  <button type="button" class="btn btn-primary" id="btn-submit-addeducbg">Submit</button>
					</div>
				</div>
			</div>
		</div> 
		
        {{--Work Experience Modal--}}
		<div id="experience_info" class="modal custom-modal fade" role="dialog" style="display: none;">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header bg-info">
						<h5 class="modal-title"><strong>Work Experience</strong></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row mb-2">
							<div class="col-md-12">
								<label>Company Name</label>
								<input type="text" class="form-control form-control-sm" id="input-workex-companyname"/>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-12">
								<label>Location</label>
								<input type="text" class="form-control form-control-sm" id="input-workex-location"/>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-12">
								<label>Job Position</label>
								<input type="text" class="form-control form-control-sm" id="input-workex-jobposition"/>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-12">
								<label>Period from</label>
								<input type="date" class="form-control form-control-sm" id="input-workex-periodfrom"/>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-md-12">
								<label>Period to</label>
								<input type="date" class="form-control form-control-sm" id="input-workex-periodto"/>
							</div>
						</div>
					</div>
					<div class="modal-footer justify-content-between">
					  <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close-addworexperience">Close</button>
					  <button type="button" class="btn btn-primary" id="btn-submit-addworexperience">Submit</button>
					</div>
				</div>
			</div>
		</div> 
        
        
    </div>
</div>
<script>
	const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2000,
    });

	var syid = @json($sy->id);
  	var semid = @json($semester->id);
	var personalinfo = [];
	var emergencylist = [];
	var salarytypes = @json($salarytypes);
	loadsy()
  	loadsemester()
	select_departments()
	select_designations()
	emergencycontact_list()
	load_shift()
	loadsalaryinfo()
	load_salarytype()
	loademployee_clloadsubjects()
	loadworkingday()
	$('#select-shift').select2()
    // save personal info
    $('#updatepersonalinformation').on('click', function(){
		var valid_data = true;
		var empid = $('#employeeid').val()
		var profiletitle = $('#profiletitle').val();
		var profilefname = $('#profilefname').val();
		var profilemname = $('#profilemname').val();
		var profilelname = $('#profilelname').val();
		var profilesuffix = $('#profilesuffix').val();
		var profileacadtitle = $('#profileacadtitle').val();
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
		var profilesssid = $('#profilesssid').val();
		var profilephilid = $('#profilephilid').val();
		var profilepagibigid = $('#profilepagibigid').val();
		var profiletinid = $('#profiletinid').val();
		var profiledatehired = $('#profiledatehired').val();
		var profiledateofservice = $('#profiledateofservice').val();
		var departmentid = $('#select-department').val();
		var designationid = $('#select-designation').val();
		var designationname = $('#select-designation option:selected').text();

		$('#designationss').text(designationname)


		if (valid_data) {
			Swal.fire({
				title: 'Are you sure you want to Update?',
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes Update'
			}).then((result) => {
				if (result.value) {
					$.ajax({
					type: "GET",
					url: "/hr/employees/profile/tabprofile/updatepersonalinfo",
					data: {
						employeeid: empid,
						profiletitle : profiletitle,
						profilefname : profilefname,
						profilemname : profilemname,
						profilelname : profilelname,
						profilesuffix : profilesuffix,
						profileacadtitle : profileacadtitle,
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
						designationid : designationid,
						profilesssid : profilesssid,
						profilephilid : profilephilid,
						profilepagibigid : profilepagibigid,
						profiletinid : profiletinid,
						profiledateofservice : profiledateofservice
					},
					success: function (data) {

							Toast.fire({
								type: 'success',
								title: 'Personal Information updated successfully!'
							})
						// toastr.success('Personal Information updated successfully!')
						// $('#custom-content-above-tabContent').empty()
						// $('#custom-content-above-profile-tab').click()
						
					}
					});
				}
			})
		}
    })
	
	$(document).on('click', '#basicsalary_submitbutton', function(){
		basic_salaryinfo()
	})
	$(document).on('click', '#halfdaysatam', function(){
		$('#halfdaysatpm').prop('checked', false)
	})
	$(document).on('click', '#halfdaysatpm', function(){
		$('#halfdaysatam').prop('checked', false)
	})

	// update basic salary information
	$(document).on('click', '#basicsalary_updatebutton', function(){
		
		var checkedHalfdaysat = $('.halfdaysat:checked');
		var halfdaysat = 0; // Array to store values

		checkedHalfdaysat.each(function() {
			
			halfdaystatus = $(this).attr('halfdaystatus');

			if (halfdaystatus == '1') {
				halfdaysat = 1;
			} else if(halfdaystatus == '2'){
				halfdaysat = 2;
			}
		});


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
		}

		} else {
		attendance_switch = attendance_switch
		}

		if (workshift == '' || workshift == null) {
		workshift = 0
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
				days : days,
				halfdaysat : halfdaysat
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
			}
		})
		}
		
	})

	$(document).on('click', '#parttimer', function(){
		var empid = $('#employeeid').val()

		if ($(this).is(':checked')) {
			var partimerstatus = 1;
		} else {
			var partimerstatus = 0;
		}
		
		$.ajax({
			type: "GET",
			url: "/payrollclerk/employees/profile/v2_updateparttimerstatus",
			data: {
				partimerstatus : partimerstatus,
				empid : empid
			},
			success: function (data) {
				if(data[0].status == 0){
					Toast.fire({
						type: 'error',
						title: data[0].message
					})
				}else{
					// loadsalaryinfo()
					// window.location.reload()
					Toast.fire({
						type: 'success',
						title: data[0].message
					})
				}
			}
		});
	})

	// save basic salary information
	function basic_salaryinfo(){
		var checkedHalfdaysat = $('.halfdaysat:checked');
		var halfdaysat = 0; // Array to store values

		checkedHalfdaysat.each(function() {
			
			halfdaystatus = $(this).attr('halfdaystatus');

			if (halfdaystatus == '1') {
				halfdaysat = 1;
			} else if(halfdaystatus == '2'){
				halfdaysat = 2;
			}
		});

		valid_data = true

		$('#hoursperday').removeClass('is-invalid')
		$('#amountperday').removeClass('is-invalid')

		$('#timepickeramin').removeClass('is-invalid')
		$('#timepickeramout').removeClass('is-invalid')
		$('#timepickerpmin').removeClass('is-invalid')
		$('#timepickerpmout').removeClass('is-invalid')
		var shiftid = $('#select-shift').val()
		var salarytype = $('#select-salarytype').val()
		var empid = '{{$profileinfo->id}}';
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
		}

		} else {
		attendance_switch = attendance_switch
		}

		if (workshift == '' || workshift == null) {
		workshift = 0
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
			days : days,
			halfdaysat : halfdaysat
			},
			success: function (data) {
			if(data[0].status == 0){
				Toast.fire({
				type: 'error',
				title: data[0].message
				})
			}else{
				loadsalaryinfo()
				window.location.reload()
				Toast.fire({
				type: 'success',
				title: data[0].message
				})
			}
			}
		});
		}
	}

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

	$(document).on('click', '.flexitime', function(){
		if ($(this).is(':checked')) {
		var flexitime = 1;
		flexitimedata(flexitime)
		} else {
		var flexitime = 0;
		flexitimedata(flexitime)
		}
	})

	function flexitimedata(flexitime) {
		var empid = '{{$profileinfo->id}}';

		$.ajax({
		type: "GET",
		url: "/payrollclerk/employees/profile/v2_updateflexitime",
		data: {
			flexitime : flexitime,
			empid : empid
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

	$(document).on('change', '#profileselect-sy', function(){
		var activesyid = $('#profileselect-sy').val()
		var semesterid = $('#profileselect-semester').val()

		loadteachersubjload(activesyid, semesterid)

	});

	$(document).on('change', '#profileselect-semester', function(){
		var activesyid = $('#profileselect-sy').val()
		var semesterid = $('#profileselect-semester').val()

		loadteachersubjload(activesyid, semesterid)

	});

	$(document).on('change', '.subjecttypechange', function(){
		var subjid = $(this).attr('datasubjid')
		var syid = $(this).attr('datasyid')
		var semid = $(this).attr('datasemid')
		var subjtype = $('#subjecttype_'+subjid).val()
		var empid = $('#employeeid').val()
		$.ajax({
		type: "GET",
		url: "/payrollclerk/setup/parttime/updatesubjecttype",
		data: {
			subjid : subjid,
			syid : syid,
			semid : semid,
			subjtype : subjtype,
			empid : empid
		},
		success: function (data) {
			if(data[0].status == 0){
			Toast.fire({
			type: 'error',
			title: data[0].message
			})
			}else{
			var activesyid = $('#profileselect-sy').val()
			var semesterid = $('#profileselect-semester').val()

			// loadteachersubjload(activesyid, semesterid)
			Toast.fire({
			type: 'success',
			title: data[0].message
			})
			}
		}
		});
	});

	$(document).on('click', '.updaterowcomputation', function(){
		var empid = $('#employeeid').val()
		var subjid = $(this).attr('datasubjid')
		var syid = $('#profileselect-sy').val()
		var semid = $('#profileselect-semester').val()
		var lechours = parseFloat($('#lechoursinput' + subjid).val()) || 0;
		var labhours = parseFloat($('#labhoursinput' + subjid).val()) || 0;
		var perhour = parseFloat($('#lechoursinput' + subjid).attr('perhour')) || 0;
		var totalpersemString  = $('#totalpersem' + subjid).text()
		var totalpersemWithoutCommas = totalpersemString.replace(/,/g, '');
		var totalpersem = parseFloat(totalpersemWithoutCommas);
		var interval = $(this).attr('interval')

		var per15 = totalpersem / interval
		per15 = per15.toFixed(2)


		$.ajax({
		type: "GET",
		url: "/payrollclerk/setup/parttime/update/persubject",
		data: {
			empid : empid,
			subjid : subjid,
			syid : syid,
			semid : semid,
			lechours : lechours,
			labhours : labhours,
			perhour : perhour,
			totalpersem : totalpersem,
			interval : interval,
			per15 : per15
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
	$(document).on('input', '.lechoursinput', function(){
		var subjid = $(this).attr('datasubjid')
		computepersubj(subjid)
	})

	$(document).on('input', '.labhoursinput', function(){
		var subjid = $(this).attr('datasubjid')
		computepersubj(subjid)
	})

	function computepersubj(subjid) {
		var lechours = parseFloat($('#lechoursinput' + subjid).val()) || 0;
		var labhours = parseFloat($('#labhoursinput' + subjid).val()) || 0;
		var perhour = parseFloat($('#lechoursinput' + subjid).attr('perhour')) || 0;


		var lectotal = lechours * perhour;
		var labtotal = labhours * perhour;


		var alltotal = labtotal + lectotal;
		alltotal = alltotal.toFixed(2);

		$('#totalpersem' + subjid).text(number_format(alltotal, 2));
	}

	function number_format(number, decimals, dec_point, thousands_sep) {
		number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		var n = !isFinite(+number) ? 0 : +number,
			prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
			sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
			dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
			s = '',
			toFixedFix = function (n, prec) {
				var k = Math.pow(10, prec);
				return '' + Math.round(n * k) / k;
			};

		// Fix for IE parseFloat(0.55).toFixed(0) = 0;
		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}
		if ((s[1] || '').length < prec) {
			s[1] = s[1] || '';
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}

		return s.join(dec);
	}
	// load saved salary info
	function loadsalaryinfo(){
		var empid = '{{$profileinfo->id}}';
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

				if (data[0].parttimer == 1) {
					$('#parttimer').prop('checked', true)
				} else {
					$('#parttimer').prop('checked', false)
				}
			
			}
		
		}
		});
	}
	
	// load personal Info 
	function loadpersonalinfo(){
		var empid = '{{$profileinfo->id}}';
		$.ajax({
			type: "GET",
			url: "/payrollclerk/employees/profile/v2_loadpersonalinfo",
			data: {
			empid : empid
			},
			success: function (data) {
			personalinfo = data.personalinfo[0];
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
	
	//added by Gian
	function select_departments(){
		$.ajax({
		type: "GET",
		url: "/payrollclerk/employees/profile/select_departments",
		success: function (data) {
				console.log(data);
				$('#select-department').empty()
				$('#select-department').append('<option value="">Select Department</option>')
				$('#select-department').select2({
					data: data,
					allowClear : true,
					placeholder: 'Select Department'
				});
				
				loadpersonalinfo()
			}
		});
	}
	
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

	function load_shift(){
		$.ajax({
		type: "GET",
		url: "/payrollclerk/employees/profile/v2_loadshifts",
		success: function (data) {
			shifts = data
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
		var empid = '{{$profileinfo->id}}';

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
	//load all emergency contact list
	function emergencycontact_list(){
		var empid = '{{$profileinfo->id}}';
		$.ajax({
		  type: "GET",
		  url: "/payrollclerk/employees/profile/v2_emergencycontactlist",
		  data: {
			empid : empid
		  },
		  success: function (data) {
			emergencylist = data;  
			emergency_contact()
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

	function loadteachersubjload(activesyid){
		var empid = $('#employeeid').val()
		var activesy = activesyid
		var employeesubjects = [];
		
		var schoolyearid = $('#profileselect-sy').val()
		var semesterid = $('#profileselect-semester').val()

		// return false;
		$.ajax({
		type: "GET",
		url: "/payrollclerk/setup/parttime/loading/perteacher",
		data: {
			syid : activesyid,
			semid : semesterid,
			empid : empid
		},
		success: function (data) {
			console.log(data);

			var table = $('<table width="100%" class="table table-bordered">');
			table.append(`<thead>
							<tr>
							<th class="" width="37%">SUBJECTS</th>
							<th class="text-center" width="13%"># OF HOURS <br> LECTURE</th>
							<th class="text-center" width="13%"># OF HOURS <br> LAB</th>
							<th class="text-center" width="10%">HOURLY <br> RATE</th>
							<th class="text-center" width="14%">TYPE</th>
							<th class="text-center" width="10%">TOTAL</th>
							<th class="text-center" width="3%"></th>
							</tr>
						</thead>`);
			var body = $('<tbody>');
			$.each(data, function (index, item) {
				var filtersubjtype = clsubjects.filter(x=>x.subjid == item.subjid && x.syid == item.syid && x.semid == item.semid)[0]
				if (filtersubjtype) {
				if (filtersubjtype.subjtype != null) {
					var fsubjectype = filtersubjtype.subjtype;
				} else {
					var fsubjectype = 1;
				}

				var labhours = filtersubjtype.numberofhourslab
				var lechours = filtersubjtype.numberofhourslec
				// var persem = filtersubjtype.totalpemers

				if (labhours == 0 && lechours != 0) {
					var persem = lechours * filtersubjtype.hourlyrate
				} else if(lechours == 0 && labhours != 0){
					var persem = labhours * filtersubjtype.hourlyrate
				} else if(labhours == 0 && lechours == 0) {
					var persem = 0
				} else {
					totalhours = labhours + lechours
					var persem = totalhours * filtersubjtype.hourlyrate
				}
				
				if (item.intervals != null || item.intervals != 0) {
					var per15 = persem / item.intervals
				} else {
					var per15 = 0
				}
				var per15 = persem / item.intervals
				} else {
				var fsubjectype = 1;
				var labhours = 0
				var lechours = item.totalhourss
				var persem = item.amountinasem
				if (item.intervals != null || item.intervals != 0) {
					var per15 = item.amountinasem / item.intervals
				} else {
					var per15 = 0
				}
				}

				
				// var formattedAmountpersem = new Intl.NumberFormat().format(item.amountinasem);

				// body.append('<tr>' +
				//     '<td><span style="font-size: 14px;">' + item.subjdesc + '</span></td>' +
				//     '<td class="text-center"><span style="font-size: 14px;">' + item.totalhourss + '</span></td>' +
				//     '<td></td>' +
				//     '<td class="text-center"><span style="font-size: 14px;">' + item.clsubjperhour + '</span></td>' +
				//     '<td>'+
				//         '<select class="select2 form-control form-control-sm text-uppercase subjecttypechange" ' +
				//         'datasubjid="'+item.subjid+'" datasyid="'+item.syid+'" datasemid="'+item.semid+'" ' +
				//         'id="subjecttype_'+item.subjid+'" style="margin: auto!important;">'+
				//             '<option value="1" ' + (fsubjectype == 1 ? 'selected' : '') + '>Regular</option>'+
				//             '<option value="2" ' + (fsubjectype == 2 ? 'selected' : '') + '>Overload</option>'+
				//             '<option value="3" ' + (fsubjectype == 3 ? 'selected' : '') + '>Part Time</option>'+
				//         '</select>'+
				//     '</td>' +
				//     '<td class="text-center"><span style="font-size: 14px;">' + formattedAmountpersem + '</span></td>' +

				//   '</tr>');

				//   // Accumulate data in the array
				//   employeesubjects.push({
				//   empid : empid,
				//   activesy : activesy,
				//   semid : item.semid,
				//   subjid : item.subjid,
				//   subjdesc: item.subjdesc,
				//   totalhours: item.totalhourss,
				//   clsubjperhour: item.clsubjperhour,
				//   amountpersem : item.amountinasem,
				//   subjtype : $('#subjecttype_'+item.subjid).val()

				// });
				var selectElement = $('<select>', {
				class: 'select2 form-control form-control-sm text-uppercase subjecttypechange',
				datasubjid: item.subjid,
				datasyid: item.syid,
				datasemid: item.semid,
				id: 'subjecttype_' + item.subjid,
				style: 'margin: auto!important;'

				
				
				}).append(
				$('<option>', {
					value: '0',
					text: '',
					selected: fsubjectype == 1
				}),
				$('<option>', {
					value: '1',
					text: 'Regular',
					selected: fsubjectype == 1
				}),
				$('<option>', {
					value: '2',
					text: 'Overload',
					selected: fsubjectype == 2
				}),
				$('<option>', {
					value: '3',
					text: 'Part Time',
					selected: fsubjectype == 3
				})
				).on('change', function() {
				employeesubjects[index].subjtype = $(this).val();
				});

				var updateIcon = $('<a>', {
				class: 'fas fa-edit text-primary text-center updaterowcomputation', // Assuming you are using Font Awesome
				style: 'font-size: 18px; cursor: pointer;',
				id: 'updaterowcomputation'+item.subjid,
				datasubjid: item.subjid,
				interval:item.intervals

				});

				body.append($('<tr>').append(
					$('<td>').append($('<span>', {
						style: 'font-size: 14px;',
						text: item.subjdesc
					})),
					// $('<td>', {
					//     class: 'text-center',
					//     style: 'font-size: 14px;',
					//     text: item.totalhourss
					// }),
					$('<td>').append($('<input>', {
						type: 'number',
						class: 'form-control form-control-sm text-center lechoursinput',
						id: 'lechoursinput'+item.subjid,
						datasubjid: item.subjid,
						perhour: item.clsubjperhour,
						totalpersem: item.amountinasem,
						interval:item.intervals,
						'data-subjid': item.subjid, // Add your custom data attributes if needed
						'data-syid': item.syid,
						'data-semid': item.semid,
						value: lechours,
						style: 'border: none!important'
					})),
					$('<td>').append($('<input>', {
						type: 'number',
						class: 'form-control form-control-sm text-center labhoursinput',
						id: 'labhoursinput'+item.subjid,
						datasubjid: item.subjid,
						perhour: item.clsubjperhour,
						totalpersem: item.amountinasem,
						interval:item.intervals,
						'data-subjid': item.subjid, // Add your custom data attributes if needed
						'data-syid': item.syid,
						'data-semid': item.semid,
						value: labhours,
						style: 'border: none!important'
					})),
					$('<td>', {
						class: 'text-center',
						style: 'font-size: 14px;',
						text: item.clsubjperhour
					}),
					$('<td>').append(selectElement),
					$('<td>', {
						class: 'text-center',
						id: 'totalpersem'+item.subjid,
						style: 'font-size: 14px;',
						text: number_format(persem,2)
					}),
					$('<td>').append(updateIcon)
				));

			// Accumulate data in the array
				employeesubjects.push({
				empid: empid,
				activesy: activesy,
				semid: item.semid,
				subjid: item.subjid,
				subjdesc: item.subjdesc,
				labhours: labhours,
				lechours: lechours,
				clsubjperhour: item.clsubjperhour,
				amountpersem: persem,
				subjtype: fsubjectype,
				per15 : per15
				}); 
			});

			table.append(body);
			$('.collegeloadtable').empty().append(table);
			saveAllData(employeesubjects);
			console.log();
		}
		});

		function saveAllData(employeesubjects) {
		var csrfToken = $('meta[name="csrf-token"]').attr('content');

		// return false;
		$.ajax({
			type: "POST",
			url: "/payrollclerk/setup/parttime/saveempsubjects",
			headers: {
				'X-CSRF-TOKEN': csrfToken,
			},
			data: JSON.stringify({
				employeesubjects: employeesubjects
			}),
			contentType: "application/json",
			success: function (data) {
				console.log(data);
			},
			error: function (error) {
				console.error('Error saving data:', error);
			}
		});
		}
	}

	// overload load payrolldate
	function loadsy(){
		var semesterid = $('#profileselect-semester').val()
		$.ajax({
			type: "GET",
			url: "/payrollclerk/employees/profile/profileloadsy",
			success: function (data) {
			
			sy = data;
			var activesy = sy.filter(x=>x.isactive == 1)[0]
			var activesyid = activesy.id
			
			$('#profileselect-sy').empty();
			$('#profileselect-sy').append('<option value="">Select School Year</option>')
			$('#profileselect-sy').select2({
				data: sy,
				allowClear : true,
				placeholder: 'Select School Year'
			});
			$('#profileselect-sy').val(activesy.id).change();
			}
		});
	}
	// overload load payrolldate
	function loadsemester(){
		$.ajax({
		type: "GET",
		url: "/payrollclerk/employees/profile/profileloadsemester",
		success: function (data) {
			
			semester = data;

			var activesem = semester.filter(x=>x.id == 1)[0]
			$('#profileselect-semester').empty();
			$('#profileselect-semester').append('<option value="">Select Semester</option>')
			$('#profileselect-semester').select2({
			data: semester,
			allowClear : true,
			placeholder: 'Select Semester'
			});
			$('#profileselect-semester').val(activesem.id).change();
		}
		});
	}

	function loademployee_clloadsubjects(){
		var empid = $('#employeeid').val()
		var activesyid = $('#profileselect-sy').val()
		var semesterid = 1

		$.ajax({
		type: "GET",
		url: "/payrollclerk/employees/profile/loadclloadsubjects",
		data: {
			syid : syid,
			semid : semesterid,
			empid : empid
		},
		success: function (data) {
			clsubjects = data
		}
		});
	}
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

	function emergency_contact(){
		console.log('Function called'); // Add this line for debugging
		$('#emergency_contact_dt').DataTable({
			lengthMenu: false,
		    info: false,
		    paging: false,
		    searching: true,
		    destroy: true,
		    lengthChange: false,
		    // scrollX: true,
		    autoWidth: false,
		    order: false,
		    data : emergencylist,
		    columns : [
			  {"data" : 'name'},
			  {"data" : null},
			  {"data" : null},
			  {"data" : null}
		    ], 
		    columnDefs: [
				{
					'targets': 0,
					'orderable': false, 
					'createdCell':  function (td, cellData, rowData, row, col) {
					  var text = '<span data-id="'+rowData.id+'">'+rowData.name+'</span>';
					  $(td)[0].innerHTML =  text
					  $(td).addClass('align-middle  text-left  p-0')
					  $(td).css('vertical-align', 'middle !important')
					}
				},
				{
					'targets': 1,
					'orderable': false, 
					'createdCell':  function (td, cellData, rowData, row, col) {
					  var text = '<span>'+rowData.relationship+'</span>';
					  $(td)[0].innerHTML =  text
					  $(td).addClass('align-middle  text-left p-0')
					  $(td).css('vertical-align', 'middle !important')
					}
				},
				{
					'targets': 2,
					'orderable': false, 
					'createdCell':  function (td, cellData, rowData, row, col) {
					  var text = '<span>'+rowData.contactno+'</span>';
					  $(td)[0].innerHTML =  text
					  $(td).addClass('align-middle  text-center p-0')
					  $(td).css('vertical-align', 'middle !important')
					}
				},
				{
					'targets': 3,
					'orderable': false, 
					'createdCell':  function (td, cellData, rowData, row, col) {
					  var text = '<button type="button" class="btn btn-sm deleteemergencycontact" data-id="'+rowData.id+'"><i class="fa fa-trash text-danger"></i></button>';
					  $(td)[0].innerHTML =  text
					  $(td).addClass('align-middle  text-right p-0')
					  $(td).css('vertical-align', 'middle !important')
					}
				}
			]
		});
	}

	function loadworkingday(){
		var empid = $('#employeeid').val()

		$.ajax({
			type: "GET",
			url: "/payrollclerk/employees/profile/v2_loadworkingday",
			data:{
				empid: empid
			},
			success: function (data) {
				console.log('working day loaded');
			}
		});
	}
	
	// delete emergency contact
	$(document).on('click', '.deleteemergencycontact', function (){
		console.log('delete emergency contact')
		var emergencyid = $(this).attr('data-id');
		Swal.fire({
            title: 'Are you sure you want to delete the selected Emergency Contact?',
            // text: "You won't be able to revert this!",
            html: '',
                //"Account Description: <strong>" + accountdesc + '</strong>'+
                //'<br>'+ 
                //"Account #: <strong>" + accountnum + '</strong>'+
                //'<br>'+
                //"You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/hr/employees/profile/tabprofile/deleteemergency',
                    type:"GET",
                    data:{
                        emergencyid: emergencyid,
                    },
                    success: function(data){
                        if(data == 1)
                        {
                            toastr.success('Emergency Contact Deleted Successfully!')
                            $('#custom-content-above-tabContent').empty()
                            $('#custom-content-above-profile-tab').click()
                        }else{
                            toastr.error('Soemthng went wrong!')
                        }
                    }
                })
            }
        })
	})
	
    $(document).on('click','#updateemergencycontact', function(){
        var emergencyname = $('#emergencyname').val()
        var emergencyrelationship = $('#emergencyrelationship').val()
        var emergencycontactnumber = $('#emergencycontactnumber').val()
        $.ajax({
            url: "/hr/employees/profile/tabprofile/updateemercon",
            type: "get",
            data: {
                employeeid: '{{$profileinfo->id}}',
                emergencyname:emergencyname,
                emergencyrelationship:emergencyrelationship,
                emergencycontactnumber:emergencycontactnumber
            },
            success: function (data) {
                toastr.success('Emergency Contact updated successfully!')
                $('#custom-content-above-tabContent').empty()
                $('#custom-content-above-profile-tab').click()
            }
        });
      })
        $(document).on('click','#updatedesignationid', function(){
            var departmentid = $('#departmentid').val()
            var designationid = $('#designationid').val()
            $.ajax({
                url: "/hr/updatedesignation",
                type: "get",
                data: {
                    employeeid: '{{$profileinfo->id}}',
                    departmentid:departmentid,
                    designationid:designationid
                },
                success: function (data) {
                    toastr.success('Emergency Contact updated successfully!')
                    $('#custom-content-above-tabContent').empty()
                    $('#custom-content-above-profile-tab').click()
                }
            });
      })
    

      var clickedaccountrows = 1;

    $(document).on('click', '.addrowaccountsbutton', function() {
		$('.addrowaccounts table tbody').append(
			'<tr>' +
				'<td width="56%" class="p-0"><input type="text" name="newaccountdescription[]" class="form-control form-control-sm"  style="border: none;" required/></td>' +
				'<td width="40%" class="p-0"><input type="text" name="newaccountnumber[]" class="form-control form-control-sm"  style="border: none;" required/></td>' +
				'<td width="4%" class="p-0 text-center bg-danger"><button type="button" class="btn btn-sm btn-danger removeaddaccountrow"><i class="fa fa-times"></i></button></td>' +
			'</tr>'
		);
		clickedaccountrows += 1;
	});
	// Use event delegation to handle the removal of rows
	$(document).on('click', '.removeaddaccountrow', function () {
		// Find the closest row and remove it
		 $(this).closest('tr').remove();
		clickedaccountrows -= 1;
	});
    $(document).on('click','#updateaccounts', function(){
        var newdescriptions = [];
        var oldaccountdescription = [];
        var newaccountnumber = [];
        var oldaccountnumber = [];
        var oldaccountid = [];
        var emptyelements = [];
        $('input[name="newaccountdescription[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                newdescriptions.push($(this).val())
            }
        })
        $('input[name="newaccountnumber[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                newaccountnumber.push($(this).val())
            }
        })
        if($(this).closest('form').find('input[name="oldaccountdescription[]"]').length > 0)
        {
            $('input[name="oldaccountdescription[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    oldaccountdescription.push($(this).val())
                }
            })
            $('input[name="oldaccountnumber[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    oldaccountnumber.push($(this).val())
                }
            })
            $('input[name="oldaccountid[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    oldaccountid.push($(this).val())
                }
            })
        }
        
        if(emptyelements.length == 0)
        {
            $.ajax({
                url: "/hr/employees/profile/tabprofile/updateaccounts",
                type: "GET",
                data: {
                    employeeid:   '{{$profileinfo->id}}',
                    newdescriptions:newdescriptions,
                    newaccountnumber:newaccountnumber,
                    oldaccountdescription:oldaccountdescription,
                    oldaccountnumber:oldaccountnumber,
                    oldaccountid:oldaccountid
                    },
                success: function (data) {
                    // $('#profilepic').attr('src',data)
                    toastr.success('Accounts updated successfully!')
                    $('#custom-content-above-tabContent').empty()
                    $('#custom-content-above-profile-tab').click()
                }
            });
        }else{
            $.each(emptyelements,function(){
                $(this).css('border','1px solid red')
            })
        }
          
      })
    $(document).on('click','.deleteaccount',function() {
        var accountid       = $(this).closest('tr').attr('id');
        var accountdesc     = $(this).attr('accountdescription');
        var accountnum      = $(this).attr('accountnumber');
        var thistr          = $(this).closest('tr');
        Swal.fire({
            title: 'Are you sure you want to delete the selected account info?',
            // text: "You won't be able to revert this!",
            html:
                "Account Description: <strong>" + accountdesc + '</strong>'+
                '<br>'+ 
                "Account #: <strong>" + accountnum + '</strong>'+
                '<br>'+
                "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/hr/employees/profile/tabprofile/deleteaccount',
                    type:"GET",
                    dataType:"json",
                    data:{
                        accountid: accountid,
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    success: function(data){
                        if(data == 1)
                        {
                            thistr.remove();
                            toastr.success('Account deleted successfully!')
                            $('#custom-content-above-tabContent').empty()
                            $('#custom-content-above-profile-tab').click()
                        }else{
                            toastr.error('Soemthng went wrong!')
                        }
                    }
                })
            }
        })
    });
      
        
      $('.addrow').on('click', function(){
            $('#familytbody').append(
                '<tr>'+
                    '<td class="p-0"><input class="form-control form-control-sm text-uppercase" type="text" name="familyname[]" style="border: none;" required/></td>'+
                    '<td class="p-0"><input class="form-control form-control-sm text-uppercase" type="text" name="familyrelation[]" style="border: none;" /></td>'+
                    // '<td class="p-0"><input class="form-control text-uppercase" type="date" name="familydob[]"/></td>'+
                    '<td class="p-0"><input class="form-control form-control-sm text-uppercase familycontactnum" type="text" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" name="familynum[]" style="border: none;"/></td>'+
                    '<td class="p-0 bg-danger" style="vertical-align: middle;"><button type="button" class="btn btn-sm btn-danger btn-block deleterow"><i class="fa fa-times"></i></button></td>'+
                '</tr>'
            );
            $(".familycontactnum").inputmask({mask: "9999-999-9999"});
        });
        $(document).on('click','.deleterow', function(){
            $(this).closest('tr').remove();
        })
        

        $(document).on('click','#updatefamilyinfo', function(){
            var thiselement = $(this);
            var familyname = [];
            var familyrelation = [];
            var familynum = [];
            var oldid = [];
            var oldfamilyname = [];
            var oldfamilyrelation = [];
            var oldfamilynum = [];
            var emptyelements = [];
        
            $('input[name="familyname[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    familyname.push($(this).val())
                }
            })
            $('input[name="familyrelation[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    familyrelation.push($(this).val())
                }
            })
            $('input[name="familynum[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    familynum.push($(this).val())
                }
            })

            if($(this).closest('form').find('input[name="oldfamilyid[]"]').length > 0)
            {
                $('input[name="oldfamilyid[]"]').each(function(){
                    $(this).css('border','1px solid #ddd')
                    if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        emptyelements.push($(this))
                    }else{

                        oldid.push($(this).val())
                    }
                })
                $('input[name="oldfamilyname[]"]').each(function(){
                    $(this).css('border','1px solid #ddd')
                    if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        emptyelements.push($(this))
                    }else{

                        oldfamilyname.push($(this).val())
                    }
                })
                $('input[name="oldfamilyrelation[]"]').each(function(){
                    $(this).css('border','1px solid #ddd')
                    if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        emptyelements.push($(this))
                    }else{

                        oldfamilyrelation.push($(this).val())
                    }
                })
                $('input[name="oldfamilynum[]"]').each(function(){
                    $(this).css('border','1px solid #ddd')
                    if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        emptyelements.push($(this))
                    }else{

                        oldfamilynum.push($(this).val())
                    }
                })
            }
            if(emptyelements.length == 0)
            {
                
                $.ajax({
                    url: "/hr/employees/profile/tabprofile/updatefamilyinfo",
                    type: "GET",
                    data: {
                        employeeid:   '{{$profileinfo->id}}',
                        familyname:familyname,
                        familyrelation:familyrelation,
                        familynum:familynum,
                        oldid:oldid,
                        oldfamilyname:oldfamilyname,
                        oldfamilyrelation:oldfamilyrelation,
                        oldfamilynum:oldfamilynum
                        },
                    success: function (data) {
                        // $('#profilepic').attr('src',data)
                        toastr.success('Accounts updated successfully!')
                        $('#custom-content-above-tabContent').empty()
                        $('#custom-content-above-profile-tab').click()
                    }
                });
            }else{
                $.each(emptyelements,function(){
                    $(this).css('border','1px solid red')
                })
            }
      })
        $('.deletefamilymember').click(function() {
            var familymemberid      = $(this).attr('familyid');
            var familymembername    = $(this).attr('familymembername');
            var employeeid          = $(this).attr('id');
            
            Swal.fire({
                title: 'Are you sure you want to delete this family member?',
                // text: "You won't be able to revert this!",
                html:
                    "Family member: <strong>" + familymembername + '</strong>'+
                    '<br>'+
                    "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/hr/employees/profile/tabprofile/deletefamilyinfo',
                        type:"GET",
                        dataType:"json",
                        data:{
                            familymemberid: familymemberid,
                            employeeid: employeeid
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            
                            toastr.success('Accounts updated successfully!')
                            $('#custom-content-above-tabContent').empty()
                            $('#custom-content-above-profile-tab').click()
                        }
                    })
                }
            })
        });
        $('#btn-submit-addeducbg').on('click', function(){
            var sy              = $('#input-educbg-sy').val();
            var university      = $('#input-educbg-university').val();
            var address         = $('#input-educbg-address').val();
            var course          = $('#input-educbg-course').val();
            var major           = $('#input-educbg-major').val();
            var awards          = $('#input-educbg-awards').val();
            if(university.replace(/^\s+|\s+$/g, "").length == 0)
            {
                $('#input-educbg-university').css('border','1px solid red')
                toastr.warning('Please fill in required field!')
            }else{
                    $.ajax({
                        url: '/hr/employees/profile/tabprofile/addeducationinfo',
                        type:"GET",
                        dataType:"json",
                        data:{
                            employeeid  :   '{{$profileinfo->id}}',
                            sy          :   sy,
                            university  :   university,
                            address     :   address,
                            course      :   course,
                            major       :   major,
                            awards      :   awards
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Added Successfully!')
                            $('#btn-close-addeducbg').click()
                            $('.modal-backdrop').remove()
                            $('body').removeClass('modal-open')
                            $('#custom-content-above-tabContent').empty()
                            $('#custom-content-above-profile-tab').click()
                        }
                    })
            }
        })
    $(document).ready(function(){
        $('.tr-each-educinfo').find('input').prop('disabled',true)
        $('.btn-update-educinfo').prop('disabled',true)
        $('.btn-edit-educinfo').on('click', function(){
            $(this).closest('.tr-each-educinfo').find('input').prop('disabled',false)
            $(this).closest('tr').find('.btn-update-educinfo').prop('disabled',false)
        })
        $('.btn-update-educinfo').on('click', function(){
            var id = $(this).attr('data-id');
            var sy = $(this).closest('.tr-each-educinfo').find('.input-educbg-sy').val();
            var course = $(this).closest('.tr-each-educinfo').find('.input-educbg-course').val();
            var major = $(this).closest('.tr-each-educinfo').find('.input-educbg-major').val();
            var awards = $(this).closest('.tr-each-educinfo').find('.input-educbg-awards').val();
            var schoolname = $(this).closest('.tr-each-educinfo').find('.input-educbg-schoolname').val();
            var schooladdress = $(this).closest('.tr-each-educinfo').find('.input-educbg-schooladdress').val();

            if(schoolname.replace(/^\s+|\s+$/g, "").length == 0)
            {
                $(this).closest('.tr-each-educinfo').find('.input-educbg-schoolname').css('border','1px solid red')
                toastr.warning('Please fill in required field!')
            }else{
                $.ajax({
                    url: '/hr/employees/profile/tabprofile/updateeducationinfo',
                    type:"GET",
                    dataType:"json",
                    data:{
                        id          :   id,
                        sy          :   sy,
                        course  :   course,
                        major     :   major,
                        awards      :   awards,
                        schoolname       :   schoolname,
                        schooladdress      :   schooladdress
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    complete: function(){
                        toastr.success('Updated Successfully!')
                        $('#custom-content-above-tabContent').empty()
                        $('#custom-content-above-profile-tab').click()
                    }
                })
            }
        })
        $('.btn-delete-educinfo').on('click', function(){
            var id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure you want to delete this info?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/hr/employees/profile/tabprofile/deleteeducationinfo',
                        type:"GET",
                        dataType:"json",
                        data:{
                                id          :   id
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Deleted successfully!')
                            $('#custom-content-above-tabContent').empty()
                            $('#custom-content-above-profile-tab').click()
                        }
                    })
                }
            })
        })
        $('.tr-each-workexpe').find('input').prop('disabled',true)
        $('.btn-update-workexpe').prop('disabled',true)
        $('.btn-edit-workexpe').on('click', function(){
            var id = $(this).attr('data-id');
            var currenttr = $(this).closest('tr');
            var thistr = $(this).closest('tbody').find('.tr-each-workexpe'+id);
            thistr.find('input').prop('disabled',false)
            currenttr.find('.btn-update-workexpe').prop('disabled',false)
        })
        $('#btn-submit-addworexperience').on('click', function(){
            var companyname = $('#input-workex-companyname').val();
            var location = $('#input-workex-location').val();
            var jobposition = $('#input-workex-jobposition').val();
            var periodfrom = $('#input-workex-periodfrom').val();
            var periodto = $('#input-workex-periodto').val();

            if(companyname.replace(/^\s+|\s+$/g, "").length == 0)
            {
                $('#input-workex-companyname').css('border','1px solid red')
                toastr.warning('Please fill in required field!')
            }else{
                $.ajax({
                    url: '/hr/employees/profile/tabprofile/addworexperience',
                    type:"GET",
                    dataType:"json",
                    data:{
                        employeeid      :   '{{$profileinfo->id}}',
                        companyname     :   companyname,
                        location        :   location,
                        jobposition     :   jobposition,
                        periodfrom      :   periodfrom,
                        periodto        :   periodto
                    },
                    complete: function(){
                        toastr.success('Updated Successfully!')
                        $('#btn-close-addworexperience').click()
                        $('.modal-backdrop').remove()
                        $('body').removeClass('modal-open')
                        $('#custom-content-above-tabContent').empty()
                        $('#custom-content-above-profile-tab').click()
                    }
                })
            }
        })
        $('.btn-update-workexpe').on('click', function(){
            var id = $(this).attr('data-id');
            var thistr = $(this).closest('tbody').find('.tr-each-workexpe'+id);
            var companyname = thistr.find('.input-workexpe-companyname').val();
            var companyaddress = thistr.find('.input-workexpe-companyaddress').val();
            var position = thistr.find('.input-workexpe-position').val();
            var periodfrom = thistr.find('.input-workexpe-periodfrom').val();
            var periodto = thistr.find('.input-workexpe-periodto').val();

            if(companyname.replace(/^\s+|\s+$/g, "").length == 0)
            {
                thistr.find('.input-workexpe-companyname').css('border','1px solid red')
                toastr.warning('Please fill in required field!')
            }else{
                $.ajax({
                    url: '/hr/employees/profile/tabprofile/updateworkexperience',
                    type:"GET",
                    dataType:"json",
                    data:{
                        id              :   id,
                        companyname     :   companyname,
                        location        :   companyaddress,
                        jobposition     :   position,
                        periodfrom      :   periodfrom,
                        periodto        :   periodto
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    complete: function(){
                        toastr.success('Updated Successfully!')
                        $('#custom-content-above-tabContent').empty()
                        $('#custom-content-above-profile-tab').click()
                    }
                })
            }
        })
        $('.btn-delete-workexpe').on('click', function(){
            var id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure you want to delete this info?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                    url: '/hr/employees/profile/tabprofile/deleteworkexperience',
                        type:"GET",
                        dataType:"json",
                        data:{
                                id          :   id
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Deleted successfully!')
                            $('#custom-content-above-tabContent').empty()
                            $('#custom-content-above-profile-tab').click()
                        }
                    })
                }
            })
        })
		
    })
</script>
