@extends('enrollment.layouts.app')

@section('content')
<style>
	.error{
		border: 1px solid red !important;
	}
</style>
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Student Information</h1> -->
		  <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-file-invoice nav-icon"></i>  -->
            <b>STUDENT INFORMATION</b></h4>
        </div>
        <div class="col-sm-6 pt-0">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Student List</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
	<section class="content pt-0">
		<div class="col-lg-12">
			<div class="main-card mb-3 card">
				<div class="card-body bg-info">	
					<div class="row">
						<div class="col-md-6">
							{{-- <div class="btn-group">
								<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-download"></i> Export
								</button>
								<div class="dropdown-menu">
									<a class="dropdown-item export" href="#"
									onclick="exportreport('pdf')" >PDF</a>
									<a class="dropdown-item export" onclick="exportreport('excel')">EXCEL</a>
								</div>
						  	</div> --}}
							<div class="row">
								<div class="col-md-6">
									<div class="input-group">
										<select id="selectcourse" class="form-control">
											@if(count($courses) == 0)
												<option selected>No courses found</option>
											@else
												<option value="">Select course</option>
												@foreach($courses as $course)
													<option value="{{$course->id}}">{{$course->description}}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="input-group">
										<select id="selectbatch" class="form-control" disabled>
											<option value="">Select a course first</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 float-right">
							<div class="input-group mb-1">
								
								&nbsp;
								<input id="studSearch" type="text" class="form-control" placeholder="Search" onkeyup="this.value = this.value.toUpperCase(); ">
								<div class="input-group-append">
									<span class="input-group-text"><i class="fas fa-search"></i></span>
								</div>
								<div class="input-group-append">
									<button id="btnenrollstud" class="btn btn-warning">Enroll Student</button>
								</div>
							  </div>
						</div>
						<div class="col-md-4 float-right">

						</div>
						<div class="col-md-8">
							{{-- <select class="select2bs4" multiple="multiple" data-placeholder="Select a State"
								   >
							  <option>Alabama</option>
							  <option>Alaska</option>
							  <option>California</option>
							  <option>Delaware</option>
							  <option>Tennessee</option>
							  <option>Texas</option>
							  <option>Washington</option>
							</select>
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-search"></i></span>
							</div> --}}
						</div>
						<div class="col-md-6"></div>
					</div>
				</div>
				
				<div class="card-body">
					<div class="btn-group">
						<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-download"></i> Export
						</button>
						<div class="dropdown-menu">
							<a class="dropdown-item export" href="#"
							onclick="exportreport('pdf')" >PDF</a>
							<a class="dropdown-item export" onclick="exportreport('excel')">EXCEL</a>
						</div>
					  </div> 
					<div class="table-responsive">
						{{-- <table class="mb-0 table table table-striped dataTable" role="grid"> --}}
						<table id="example2" class="table table-striped table-hover dataTable" role="grid" aria-describedby="example2_info">
				            <thead class="bg-warning">
				            <tr>
								<th>ID No.</th>
								<th>Student Name</th>
								<th>Gender</th>
								<th>Course</th>
								<th>Batch</th>
								<th>Status</th>
				            </tr>
				            </thead>
				            <tbody id="tvlist">
								@if(count($techvocstudents)>0)
									@foreach($techvocstudents as $techvocstudent)
										<tr class="studentrow" data-toggle="modal" data-target="#viewei">
											<td>{{$techvocstudent->sid}}</td>
											<td>{{$techvocstudent->lastname}}, {{$techvocstudent->firstname}} {{$techvocstudent->middlename}} {{$techvocstudent->suffix}}</td>
											<td>{{$techvocstudent->gender}}</td>
											<td>{{$techvocstudent->coursename}}</td>
											<td>{{$techvocstudent->startdate}} - {{$techvocstudent->enddate}}</td>
											<td>
												@if($techvocstudent->status == 1)
													<button id="{{$techvocstudent->techvocid}}" class="btn btn-success btn-block btn-sm viewinfo">Enrolled</button>
												@endif
											</td>
										</tr>
									@endforeach
								@endif
				            </tbody>
						</table>
						<div class="modal fade" id="viewei" aria-hidden="true" style="display: none;">
							<div class="modal-dialog modal-lg modal-dialog-centered">
							  <div class="modal-content">
								<div class="modal-header">
								  <h4 class="modal-title" id="viewinfostudname"></h4>
								  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								  </button>
								</div>
								<div class="modal-body">
									<form>
									<div class="row mb-2">
										<div class="col-md-6">
											<label>SID</label>
											<input type="text" class="form-control" id="viewinfosid" disabled>
										</div>
										<div class="col-md-6">
											<label>GENDER</label>
											<input type="text" class="form-control" id="viewinfogender" disabled>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-md-6">
											<label>Course</label>
											<input type="text" class="form-control" id="viewinfocourse" disabled>
										</div>
										<div class="col-md-6">
											<label>Batch</label>
											<input type="text" class="form-control" id="viewinfobatch" disabled>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>ENROLLED DATE</label>
											<input type="text" class="form-control" id="viewinfoenrolleddate" disabled>
										</div>
										<div class="col-md-6">
											<label>ENROLLED BY</label>
											<input type="text" class="form-control" id="viewinfoenrolledby" disabled>
										</div>
									</div>
								</form>
								</div>
								<div class="modal-footer justify-content-between">
								  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								  {{-- <div class="btn-group dropleft">
									<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									  Export
									</button>
									<div class="dropdown-menu">
										<a class="dropdown-item export" href="#"
										onclick="exportreport('pdf')" >PDF</a>
										<a class="dropdown-item export" onclick="exportreport('excel')">EXCEL</a>
									</div>
								  </div> --}}
								</div>
							  </div>
							  <!-- /.modal-content -->
							</div>
							<!-- /.modal-dialog -->
						  </div>
					</div>
				</div>
				<div class="card-footer">
					<div class="mt-3" id="data-container" data-value=""></div>
				</div>
			</div>
		</div>  
	</section>
@endsection

@section('modal')
{{-- ENROLLMENT --}}

	<div class="modal fade show" id="modal-enroll" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div id="enroll-modal-header" class="modal-header bg-info">
        	<div class="row" style="width: 100%">
				<div class="col-md-6">
					<h4 class="modal-title">Enroll Student</h4>	
				</div>
			</div>
			
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            {{-- <span aria-hidden="true">×</span> --}}
          </button>
        </div>

        <div class="modal-body">

        	<div class="row">
        		<div class="col-md-6">
        			<select id="cbostud" class="form-control select2bs4">
        				<option value="0">SELECT STUDENT</option>
        				@foreach($studinfo as $stud)
        					{{$studname = $stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename . ' ' . $stud->suffix}}
        					<option value="{{$stud->id}}">{{$studname}}</option>
        				@endforeach
        			</select>
        		</div>
        		<div class="col-md-3">
        			<button class="btn btn-primary" data-toggle="modal" data-target="#modal-newstud">Create Student</button>
        		</div>
        	</div>

        	<div class="row">
        		<div class="col-md-12">
        			<div class="row">
        				<div class="col-md-3 mt-3">
        					<select id="cbogender" class="form-control select2bs4">
        						<option value="">GENDER</option>
        						<option value="MALE">MALE</option>
        						<option value="FEMALE">FEMALE</option>
        					</select>
        				</div>
        				<div class="col-md-3 mt-3">
        					<input id="dob" type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask placeholder="Date of Birth">
        				</div>
        				<div class="col-md-3 mt-3">
        					<select id="cbonationality" class="form-control select2bs4">
        						<option value="0">Select Nationality</option>
        						@foreach($nationality as $nat)
        							<option value="{{$nat->id}}">{{$nat->nationality}}</option>
        						@endforeach
        					</select>
        				</div>
        				<div class="col-md-3 mt-3">
        					<input id="txtcontactno" type="text" name="" class="form-control" placeholder="Contact Number">
        				</div>
        			</div>
        			
        			<div class="row mt-3">
        				<div class="col-md-3">
        					<input id="txtstreet" class="form-control" type="text" name="" placeholder="Street/Blk/Lot">
        				</div>
        				<div class="col-md-3">
        					<input id="txtbarangay" class="form-control" type="text" name="" placeholder="Barangay">
        				</div>
        				<div class="col-md-3">
        					<input id="txtcity" class="form-control" type="text" name="" placeholder="City/Municipality">
        				</div>
        				<div class="col-md-3">
        					<input id="txtprovince" class="form-control" type="text" name="" placeholder="Province">
        				</div>
        			</div>
        		</div>
        	</div>

        	<hr>

        	<div class="row">
        		<div class="col-md-6">
        			<label>Course</label>
        			<select class="form-control select2bs4" id="courseselection">
        				@php
        					$courses = DB::table('tv_courses')
        						->where('deleted', 0)
        						->get();
        				@endphp

        				@foreach($courses as $course)
        					<option value="{{$course->id}}">{{$course->description}}</option>
        				@endforeach
        			</select>
        		</div>
        		<div class="col-md-6">
        			<label>Batch</label>
        			<select class="form-control select2bs4"id="batchselection">
        				
        			</select>
        		</div>
        	</div>

        	<div class="row mt-2">
        	</div>
	        
        </div>
        <div class="modal-footer justify-content-between"> 
        	{{--  --}}

        	<div class="float-left">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		
        	</div>        	
        	<div class="float-right">
        		<button id="cmdEnroll" type="button" class="btn btn-primary" {{--data-dismiss="modal"--}} style="width: 90px"><i class="fas fa-save"></i> Enroll</button>
        	</div>
        </div>
      </div>
    </div>
      <!-- /.modal-content -->
  </div>
    <!-- /.modal-dialog -->

    <div class="modal fade show" id="modal-newstud" aria-modal="true" style="padding-right: 17px; display: none;">
    	<div class="modal-dialog modal-xl">
      		<div class="modal-content">
        		<div id="enroll-modal-header" class="modal-header bg-primary">
	        		<div class="row" style="width: 100%">
						<div class="col-md-6">
							<h4 class="modal-title">Create Student</h4>	
						</div>
					</div>
				
		         	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		            {{-- <span aria-hidden="true">×</span> --}}
		         	</button>
        		</div>

        		<div class="modal-body">
        			<div class="row">
        				<div class="col-md-6">
        					<label>Personal Information</label>
        				</div>
        			</div>
        			<div class="row mt-2">
        				<div class="col-md-3">
        					<input id="new_lastname" type="text" name="" class="form-control validate is-invalid" placeholder="LASTNAME">
        				</div>
        				<div class="col-md-3">
        					<input id="new_firstname" type="text" name="" class="form-control validate is-invalid" placeholder="FIRSTNAME">
        				</div>
        				<div class="col-md-3">
        					<input id="new_middlename" type="text" name="" class="form-control" placeholder="MIDDLENAME">
        				</div>
        				<div class="col-md-3">
        					<input id="new_suffix" type="text" name="" class="form-control" placeholder="SUFFIX eg. JR/SR">
        				</div>
        			</div>
        			<div class="row">
        				<div class="col-md-3 mt-3">
        					<select id="cbonew_gender" class="form-control select2bs4 validate is-invalid">
        						<option value="">GENDER</option>
        						<option value="MALE">MALE</option>
        						<option value="FEMALE">FEMALE</option>
        					</select>
        				</div>
        				<div class="col-md-3 mt-3">
        					<input id="new_dob" type="text" class="form-control validate is-invalid" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask placeholder="Date of Birth">
        				</div>
        				<div class="col-md-3 mt-3">
        					<select id="cbonew_nationality" class="form-control select2bs4">
        						<option value="0">Select Nationality</option>
        						@foreach($nationality as $nat)
        							<option value="{{$nat->id}}">{{$nat->nationality}}</option>
        						@endforeach
        					</select>
        				</div>
        				<div class="col-md-3 mt-3">
        					<input id="txtnew_contactno" type="text" name="" class="form-control validate is-invalid" placeholder="Contact Number">
        				</div>
        			</div>
        			<div class="row mt-3">
        				<div class="col-md-6">
        					<label>Address</label>
        				</div>
        			</div>
        			<div class="row mt-2">
        				<div class="col-md-3">
        					<input id="txtnew_street" class="form-control" type="text" name="" placeholder="Street/Blk/Lot">
        				</div>
        				<div class="col-md-3">
        					<input id="txtnew_barangay" class="form-control" type="text" name="" placeholder="Barangay">
        				</div>
        				<div class="col-md-3">
        					<input id="txtnew_city" class="form-control" type="text" name="" placeholder="City/Municipality">
        				</div>
        				<div class="col-md-3">
        					<input id="txtnew_province" class="form-control" type="text" name="" placeholder="Province">
        				</div>
        			</div>
        			
        			<div class="row mt-3">
        				<div class="col-md-6">
        					<label>Parent/Guardian Information</label>
        				</div>
        			</div>
        			<div class="row mt-2">
        				<div class="col-md-3">
        					<input id="txtnew_fname" class="form-control" type="text" name="" placeholder="Father's Name">
        				</div>
        				<div class="col-md-3">
        					<input id="txtnew_foccupation" class="form-control" type="text" name="" placeholder="Occupation">
        				</div>	
        				<div class="col-md-3">
        					<input id="txtnew_fcontactno" class="form-control" type="text" name="" placeholder="Contact No.">
        				</div>
        				<div class="col-md-3 mt-2">
        					<div class="form-group clearfix">
		                    	<div class="icheck-danger d-inline">
			                        <input type="radio" id="f_rademergency" name="r1" >
			                        <label for="f_rademergency">
			                        	In Case of Emergency
			                        </label>
		                      	</div>
		                    </div>
        				</div>
        			</div>
        			<div class="row mt-2">
        				<div class="col-md-3">
        					<input id="txtnew_mname" class="form-control" type="text" name="" placeholder="Mother's Name">
        				</div>
        				<div class="col-md-3">
        					<input id="txtnew_moccupation" class="form-control" type="text" name="" placeholder="Occupation">
        				</div>	
        				<div class="col-md-3">
        					<input id="txtnew_mcontactno" class="form-control" type="text" name="" placeholder="Contact No.">
        				</div>
        				<div class="col-md-3 mt-2">
        					<div class="form-group clearfix">
		                    	<div class="icheck-danger d-inline">
			                        <input type="radio" id="m_rademergency" name="r1" >
			                        <label for="m_rademergency">
			                        	In Case of Emergency
			                        </label>
		                      	</div>
		                    </div>
        				</div>
        			</div>
        			<div class="row mt-2">
        				<div class="col-md-3">
        					<input id="txtnew_gname" class="form-control" type="text" name="" placeholder="Guardian's Name">
        				</div>
        				<div class="col-md-3">
        					<input id="txtnew_relation" class="form-control" type="text" name="" placeholder="Relation">
        				</div>
        				<div class="col-md-3">
        					<input id="txtnew_gcontactno" class="form-control" type="text" name="" placeholder="Contact No.">
        				</div>	
        				<div class="col-md-3 mt-2">
        					<div class="form-group clearfix">
		                    	<div class="icheck-danger d-inline">
			                        <input type="radio" id="g_rademergency" name="r1" >
			                        <label for="g_rademergency">
			                        	In Case of Emergency
			                        </label>
		                      	</div>
		                    </div>
        				</div>
        			</div>
	        
        		</div>
        		<div class="modal-footer justify-content-between"> 
        	{{--  --}}

	        		<div class="float-left">
	        			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		
	        		</div>        	
		        	<div class="float-right">
		        		<button id="cmdstudsave" type="button" class="btn btn-primary" data-dismiss="modal" style="width: 190px"><i class="fas fa-save"></i> Create Student</button>
		        	</div>
        		</div>
      		</div>
   		</div>
  	</div>
  


@endsection

@section('js')
	<script>
		$(document).ready(function(){
			var skip = 0;
			var studid='';
			var countValidation = 0;
			var gRun = 0;

			$('.select2bs4').select2({
		   		theme: 'bootstrap4'
		    });

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

			document.getElementById("new_lastname").addEventListener("keypress", forceKeyPressUppercase, false);
            document.getElementById("new_firstname").addEventListener("keypress", forceKeyPressUppercase, false);
            document.getElementById("new_middlename").addEventListener("keypress", forceKeyPressUppercase, false);
            document.getElementById("new_suffix").addEventListener("keypress", forceKeyPressUppercase, false);
			
			checkval();

			$('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
		    //Datemask2 mm/dd/yyyy
		    $('#dob').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });
		    $('#new_dob').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });


			$(document).on('click', '#createstud', function(){
				$('#action').text('New');
				$('#modal-newstud').modal('show');
			});

			$(document).on('select2:close', '#cbostud', function(){
				var studid = $(this).val();

				$.ajax({
					url:"{{ route('tvloadstudinfo') }}",
					method:'GET',
					data:{
						studid:studid	
					},
					dataType:'json',
					success:function(data)
					{
						$('#cbogender').val(data.gender);
						$('#cbogender').trigger('change');

						$('#dob').val(data.dob);
						$('#cbonationality').val(data.nationality);
						$('#txtcontactno').val(data.contactno);
						$('#txtstreet').val(data.street);
						$('#txtbarangay').val(data.barangay);
						$('#txtcity').val(data.city);
						$('#txtprovince').val(data.province);
					}
				});
			});

			
			$(document).on('keyup', '#new_lastname', function(){
				if($(this).val() == 0 || $(this).val() == '')
				{
					$(this).addClass('is-invalid');
					$(this).removeClass('is-valid');
				}
				else
				{
					$(this).addClass('is-valid');
					$(this).removeClass('is-invalid');
				}				

				checkval();
			});

			$(document).on('keyup', '#new_firstname', function(){
				if($(this).val() == 0 || $(this).val() == '')
				{
					$(this).addClass('is-invalid');
					$(this).removeClass('is-valid');
				}
				else
				{
					$(this).addClass('is-valid');
					$(this).removeClass('is-invalid');
				}				

				checkval();
			});

			$(document).on('change', '#cbonew_gender', function(){

				if($(this).val() == 0)
				{
					$(this).addClass('is-invalid');
					$(this).removeClass('is-valid');
				}
				else
				{
					$(this).addClass('is-valid');
					$(this).removeClass('is-invalid');
				}			

				checkval();	
			});

			$(document).on('keyup', '#new_dob', function(){
				if($(this).val() == 0 || $(this).val() == '')
				{
					$(this).addClass('is-invalid');
					$(this).removeClass('is-valid');
				}
				else
				{
					$(this).addClass('is-valid');
					$(this).removeClass('is-invalid');
				}				

				checkval();
			});

			$(document).on('keyup', '#txtnew_contactno', function(){
				if($(this).val() == 0 || $(this).val() == '')
				{
					$(this).addClass('is-invalid');
					$(this).removeClass('is-valid');
				}
				else
				{
					$(this).addClass('is-valid');
					$(this).removeClass('is-invalid');
				}			

				checkval();	
			});

			function checkval()
			{
				$('.validate').each(function(){
					if($(this).hasClass('is-invalid'))
					{
						$('#cmdstudsave').prop('disabled', true);
					}
					else
					{
						$('#cmdstudsave').prop('disabled', false);
					}
				})
			}

			$(document).on('click', '#cmdstudsave', function(){

				if($('#f_rademergency').prop('checked') == true)
				{
					var fnum = 1;
				}
				else
				{
					var fnum = 0;
				}

				if($('#m_rademergency').prop('checked') == true)
				{
					var mnum = 1;
				}
				else
				{
					var mnum = 0;
				}

				if($('#g_rademergency').prop('checked') == true)
				{
					var gnum = 1;
				}
				else
				{
					var gnum = 0;
				}

				$.ajax({
					url:"{{ route('tvcreatestudinfo') }}",
					method:'GET',
					data:{
						lastname:$('#new_lastname').val(),
						firstname:$('#new_firstname').val(),
						middlename:$('#new_middlename').val(),
						suffix:$('#new_suffix').val(),

						gender:$('#cbonew_gender').val(),
						dob:$('#new_dob').val(),
						nationality:$('#cbonew_nationality').val(),
						contactno:$('#txtnew_contactno').val(),

						street:$('#txtnew_street').val(),
						barangay:$('#txtnew_barangay').val(),
						city:$('#txtnew_city').val(),
						province:$('#txtnew_province').val(),

						fname:$('#txtnew_fname').val(),
						foccupation:$('#txtnew_foccupation').val(),
						fcontactno:$('#txtnew_fcontactno').val(),
						fnum:fnum,

						mname:$('#txtnew_mname').val(),
						moccupation:$('#txtnew_moccupation').val(),
						mcontactno:$('#txtnew_mcontactno').val(),
						mnum:mnum,

						gname:$('#txtnew_gname').val(),
						goccupation:$('#txtnew_goccupation').val(),
						gcontactno:$('#txtnew_gcontactno').val(),
						gnum:gnum

					},
					dataType:'json',
					success:function(data)
					{
						if(data == 0)
						{
							const Toast = Swal.mixin({
				            	toast: true,
				             	position: 'top-end',
				              	showConfirmButton: false,
				              	timer: 3000,
				              	timerProgressBar: false,
				              	onOpen: (toast) => {
				                	toast.addEventListener('mouseenter', Swal.stopTimer)
				                	toast.addEventListener('mouseleave', Swal.resumeTimer)
				              	}
				            })

				            Toast.fire({
				             	type: 'warning',
				             	title: 'Student already exist'
				            })
						}
						else
						{
							const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: false,
                                onOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                type: 'success',
                                title: 'Student successfully created.'
                            })
						}
					}
				});
			});
			$(document).on('change','#courseselection', function(){
				var id = $(this).val();
				$.ajax({
					url:"{{ route('tvgetbatch') }}",
					method:'GET',
					data:{
						id:id
					},
					dataType:'json',
					success:function(data)
					{
						// console.log(data)
						if(data.length > 0)
						{
							$('#batchselection').empty();
							$.each(data, function(key, value){
								$('#batchselection').append(
									'<option value="'+value.id+'">'+value.startdate+' - '+value.enddate+'</option>'
								)
							})
						}
					}
				})
			})
            $(document).on('click', '#btnenrollstud', function(){
                $('#modal-enroll').modal('show');
            });

			const Toast = Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 3000,
				timerProgressBar: false,
				onOpen: (toast) => {
					toast.addEventListener('mouseenter', Swal.stopTimer)
					toast.addEventListener('mouseleave', Swal.resumeTimer)
				}
			})
			$(document).on('click','#cmdEnroll',function(){
				var selection = 0;
				$('#courseselection').next().removeClass('error')
				$('#batchselection').next().removeClass('error')
				if( $('#courseselection').children().length == 0)
				{
					selection+=1;
					$('#courseselection').next().addClass('error')
				}else{
					if( $('#courseselection').val().length == 0)
					{
						selection+=1;
						$('#courseselection').next().addClass('error')
					}
				}
				if( $('#batchselection').children().length == 0)
				{
					selection+=1;
					$('#batchselection').next().addClass('error')
				}else{
					if( $('#batchselection').val().length == 0)
					{
						selection+=1;
						$('#batchselection').next().addClass('error')
					}
				}
				
				if(selection == 0)
				{
					var thiselement = $(this);
					var id = $('#cbostud').val();
					var courseid = $('#courseselection').val();
					var batchid = $('#batchselection').val();
					$.ajax({
						url:"{{ route('tvenrollstudent') }}",
						method:'GET',
						data:{
							id:id,
							courseid:courseid,
							batchid:batchid
						},
						dataType:'json',
						success:function(data)
						{
							// console.log(data)
							if(data == '1')
							{
								Toast.fire({
                                type: 'success',
									title: 'Student already enrolled.'
								})
							}else{
								
								Toast.fire({
                                type: 'success',
									title: 'Student successfully enrolled.'
								})
								$('#tvlist').append(
									'<tr>'+
										'<td>'+data.sid+'</td>'+
										'<td>'+data.lastname+', '+data.firstname+' '+data.middlename+' '+data.suffix+'</td>'+
										'<td>'+data.gender+'</td>'+
										'<td>'+data.courseinfo.description+'</td>'+
										'<td>'+data.batchinfo.startdate+' - '+data.batchinfo.enddate+'</td>'+
										'<td><button id="'+data.techvocid+'" class="btn btn-success btn-block btn-sm viewinfo">Enrolled</button></td>'+
									'</tr>'
								)
								thiselement.attr('id','')
								thiselement.text('Done')
								thiselement.attr('data-dismiss','modal')
							}
						}
					})
				}
			})
			$(document).on('click','.studentrow', function(){
				var enrolledstudid = $(this).find('button').attr('id')
				$('#viewei').find('form')[0].reset()
				$('#viewinfostudname').text('')
				$.ajax({
					url:"{{ route('tvgetstudinfo') }}",
					method:'GET',
					data:{
						id:enrolledstudid,
					},
					dataType:'json',
					success:function(data)
					{
						$('#viewinfostudname').text(data.lastname+', '+data.firstname+' '+data.middlename+' '+data.suffix)
						$('#viewinfosid').val(data.sid)
						$('#viewinfogender').val(data.gender)
						$('#viewinfocourse').val(data.coursename)
						$('#viewinfobatch').val(data.startdate+' - '+data.enddate)
						$('#viewinfoenrolleddate').val(data.createddatetime)
						$('#viewinfoenrolledby').val(data.name)
					}
				})
			})
			$(document).on('change','#selectcourse', function(){
				var id = $(this).val();
				$.ajax({
					url:"{{ route('tvgetbatch') }}",
					method:'GET',
					data:{
						id:id,
						withstudents: '1'
					},
					dataType:'json',
					success:function(data)
					{
						$('#selectbatch').empty();
						if(data[0].length == 0)
						{
							$('#selectbatch').append(
								'<option value="">No batch found</option>'
							)
						}else{
							$('#selectbatch').prop('disabled',false);
							$('#selectbatch').append(
								'<option value="">Select batch</option>'
							)
							$.each(data[0], function(key, value){
								$('#selectbatch').append(
									'<option value="'+value.id+'">'+value.startdate+' - '+value.enddate+'</option>'
								)
							})
						}
						$('#tvlist').empty();
						if(data[1].length == 0)
						{
							$('#tvlist').append(
								'<tr>'+
									'<td colspan="6" class="text-center">No students found</td>'+
								'</tr>'
							)
						}else{
							$.each(data[1], function(key, value){
								if(value.status == 1)
								{
									var status = 'Enrolled';
								}else{
									var status = '&nbsp;';
								}
								$('#tvlist').append(
									'<tr class="studentrow" data-toggle="modal" data-target="#viewei">'+
										'<td>'+value.sid+'</td>'+
										'<td>'+value.lastname+', '+value.firstname+' '+value.middlename+' '+value.suffix+'</td>'+
										'<td>'+value.gender+'</td>'+
										'<td>'+value.coursename+'</td>'+
										'<td>'+value.startdate+' - '+value.enddate+'</td>'+
										'<td><button id="'+value.techvocid+'" class="btn btn-success btn-block btn-sm viewinfo">'+status+'</button></td>'+
									'</tr>'
								)
							})
						}
						
					}
				})
			})
			$(document).on('change','#selectbatch', function(){
				var id = $(this).val()
				$.ajax({
					url:"{{ route('tvgetstudbybatch') }}",
					method:'GET',
					data:{
						id:id
					},
					dataType:'json',
					success:function(data)
					{
						$('#tvlist').empty();
						if(data.length == 0)
						{

							$('#tvlist').append(
								'<tr>'+
									'<td colspan="6" class="text-center">No students found</td>'+
								'</tr>'
							)
						}else{
							$.each(data, function(key, value){
								if(value.status == 1)
								{
									var status = 'Enrolled';
								}else{
									var status = '&nbsp;';
								}
								$('#tvlist').append(
									'<tr class="studentrow" data-toggle="modal" data-target="#viewei">'+
										'<td>'+value.sid+'</td>'+
										'<td>'+value.lastname+', '+value.firstname+' '+value.middlename+' '+value.suffix+'</td>'+
										'<td>'+value.gender+'</td>'+
										'<td>'+value.coursename+'</td>'+
										'<td>'+value.startdate+' - '+value.enddate+'</td>'+
										'<td><button id="'+value.techvocid+'" class="btn btn-success btn-block btn-sm viewinfo">'+status+'</button></td>'+
									'</tr>'
								)
							})
						}
						
					}
				})
			})
			$(document).on('keyup','#studSearch', function(){
				var searchstud = $(this).val();
				var courseid = $('#selectcourse').val();
				var batchid = $('#selectbatch').val();
				$.ajax({
					url:"{{ route('tvstudsearch') }}",
					method:'GET',
					data:{
						name: searchstud,
						courseid: courseid,
						batchid: batchid
					},
					dataType:'json',
					complete:function(data)
					{
						console.log(data)
						$('#tvlist').empty()
						$('#tvlist').append(data.responseText)
					}
				});
			})
			
		});	
		function exportreport(exporttype){
			var techvocids = [];
			$('#example2').find('button').each(function(){
				techvocids.push($(this).attr('id'))
			})
			var courseid 	= $('#selectcourse').val();
			var batchid 	= $('#selectbatch').val();
			if(techvocids.length > 0)
			{
				window.open("/techvoc/tvexport?exporttype="+exporttype+"&techvocids="+techvocids+"&courseid="+courseid+"&batchid="+batchid);
			}
			return false;
		}
	</script> 
	
	
@endsection

