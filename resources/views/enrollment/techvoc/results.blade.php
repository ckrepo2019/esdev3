<div class="card" style="border: none !important;">
	@if($courseid>0 && $batchid>0)
		<div class="card-header">
			<div class="row">
				<div class="col-md-6"><button type="button" class="btn btn-outline-success" id="btn-enrollastudent"><i class="fa fa-user"></i> &nbsp;&nbsp;Enroll a student</button></div>
				<div class="col-md-6 text-right"><button type="button" class="btn btn-default" id="btn-exporttopdf"><i class="fa fa-file-pdf"></i> &nbsp;&nbsp;Export to PDF</button></div>
			</div>
		</div>
	@endif
    <div class="card-body">
        {{-- <div class="row"></div> --}}

        <table class="table table-hover" style="font-size: 13px;" id="example2">
            <thead>
                <tr>
                    {{-- <th style="width: 8%;"></th> --}}
                    <th style="width: 12%;">ID No.</th>
                    <th style="width: 30%;">Student Name</th>
                    <th style="width: 25%;">Batch</th>
                    <th style="width: 12%;">Date Enrolled</th>
                    <th style="width: 21%;"></th>
                    {{-- <th style="width: 10%;"></th> --}}
                </tr>
            </thead>
            <tbody>
                @if(count($students)>0)
                    @foreach($students as $student)
                        <tr>
                            {{-- <td style="vertical-align: middle;"><button type="button" class="btn btn-sm btn-outline-warning btn-studinfo p-1" data-id="{{$student->studid}}"><i class="fa fa-id-card"></i> Edit</button></td> --}}
                            <td style="vertical-align: middle;">{{$student->sid}}</td>
                            <td style="vertical-align: middle;"><a href="#" class="btn-studinfo" data-id="{{$student->studid}}">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</a></td>
                            <td style="vertical-align: middle;">{{date('M d, Y', strtotime($student->startdate))}} - {{date('M d, Y', strtotime($student->enddate))}}</td>
                            <td style="vertical-align: middle;">{{date('M d, Y', strtotime($student->dateenrolled))}}</td>
                            {{--<td class="text-right">
								@if($student->status == 1)
								<button type="button" class="btn btn-sm btn-outline-success btn-changestatus p-1" data-status="0" data-id="{{$student->id}}">Enrolled</button>
								@else
								<button type="button" class="btn btn-sm btn-outline-secondary btn-changestatus p-1" data-status="1" data-id="{{$student->id}}">Enroll</button>
								@endif --}}
							</td>
                            <td class="text-right" style="vertical-align: middle;">
								<button type="button" class="btn btn-sm btn-outline-info btn-viewinfo p-1" data-id="{{$student->id}}">View Info</button>
								<button type="button" class="btn btn-sm btn-outline-danger fo btn-unenroll p-1" data-id="{{$student->id}}">Unenroll</button>
							</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade show" id="modal-enrollment" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div id="enroll-modal-header" class="modal-header bg-warning">
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
        		<div class="col-md-9">
        			<select id="cbostud" class="form-control select2bs4">
        				<option value="0">SELECT STUDENT</option>
        				@foreach($allstudents as $stud)
        					{{$studname = $stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename . ' ' . $stud->suffix}}
        					<option value="{{$stud->id}}">{{$studname}}</option>
        				@endforeach
        			</select>
        		</div>
        		<div class="col-md-3 text-right">
        			<button class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-newstud"><i class="fa fa-plus"></i>&nbsp;&nbsp;Create Student</button>
        		</div>
        	</div>

        	<div class="row" id="row-form">
        		<div class="col-md-12">
        			<div class="row">
        				<div class="col-md-3 mt-3">
        					<select id="cbogender" class="form-control select2bs4">
        						<option value="0">GENDER</option>
        						<option value="MALE">MALE</option>
        						<option value="FEMALE">FEMALE</option>
        					</select>
        				</div>
        				<div class="col-md-3 mt-3">
        					<input id="dob" type="date" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask placeholder="Date of Birth">
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
        					<input id="txtcontactno" type="text" name="" class="form-control input-contact-num" placeholder="Contact Number">
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
        		<div class="col-md-6" id="container-coursesenrolled" style="height: 200px; overflow: scroll;">
					
        		</div>
        		<div class="col-md-6">
        			<label>Course</label>
        			<em id="validation-selectcourse" class="text-red">Please select a course!</em>
        			<select class="form-control select2bs4" id="courseselection" readonly>
        				@php
        					$courses = DB::table('tv_courses')
        						->where('deleted', 0)
        						->get();
        				@endphp

						<option value="0">Select Course</option>
        				@foreach($courses as $course)
        					<option value="{{$course->id}}">{{$course->description}}</option>
        				@endforeach
        			</select>
        			<label>Batch</label>
        			<em id="validation-selectbatch" class="text-red">Please select a batch!</em>
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
        		<button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-enrollstudent-close">Close</button>		
        	</div>        	
        	<div class="float-right">
        		<button id="btn-enrollstudent" type="button" class="btn btn-primary" {{--data-dismiss="modal"--}} style="width: 90px"><i class="fas fa-save"></i> Enroll</button>
        	</div>
        </div>
      </div>
    </div>
      <!-- /.modal-content -->
  </div>
  
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
						<input id="new_dob" type="date" class="form-control validate is-invalid" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask placeholder="Date of Birth">
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
						<input id="txtnew_contactno" type="text" name="" class="form-control validate is-invalid input-contact-num" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" placeholder="Contact Number">
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
						<input id="txtnew_fcontactno" class="form-control input-contact-num" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" type="text" name="" placeholder="Contact No.">
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
						<input id="txtnew_mcontactno" class="form-control input-contact-num" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" type="text" name="" placeholder="Contact No.">
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
						<input id="txtnew_gcontactno" class="form-control input-contact-num" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" type="text" name="" placeholder="Contact No.">
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
				<div class="float-left">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		
				</div>        	
				<div class="float-right">
					<button id="btn-save-student" type="button" class="btn btn-primary" data-dismiss="modal" style="width: 190px"><i class="fas fa-save"></i> Create Student</button>
				</div>
			</div>
		  </div>
	   </div>
	</div>

<script>
    
	$(document).ready(function(){
		
	$(".input-contact-num").inputmask({mask: "9999-999-9999"});
	})
			@if(count($students)>0)
				toastr.success('{{count($students)}}'+' students are found!')
			@else
				toastr.warning('No students found!')
			@endif
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });
		
        $(function () {
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": false,
            });
        })
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
            document.getElementById("new_dob").addEventListener("keypress", forceKeyPressUppercase, false);

			$('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
		    //Datemask2 mm/dd/yyyy
		    $('#dob').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });
		    $('#new_dob').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });

			function checkval()
			{
				$('.validate').each(function(){
					if($(this).hasClass('is-invalid'))
					{
						$('#btn-save-student').prop('disabled', true);
					}
					else
					{
						$('#btn-save-student').prop('disabled', false);
					}
				})
			}
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
						$('#btn-save-student').prop('disabled', true);
					}
					else
					{
						$('#btn-save-student').prop('disabled', false);
					}
				})
			}
			$('#validation-selectcourse').hide()
			$('#validation-selectbatch').hide()
</script>