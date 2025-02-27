@extends('registrar.layouts.app')

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Student Information - Create</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/registrarIndex">Home</a></li>
            <li class="breadcrumb-item"><a href="/registrar/studentinfo">Student Information</a></li>
            <li class="breadcrumb-item active">Create</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
  	<div class="card card-body">
  		<div class="row">
  			<div class="col-9">
  				<h3 id="studid"  class="col-md-4" data-id="">ID No: </h3>
  			</div>
  			<div class="col-3">
  				<div class="float-sm-right">
  					<button id="btnSave" class="btn btn-primary">Save</button>
  					<button class="btn btn-danger" onclick="window.location.href='/registrar/studentinfo'">Cancel</button>
  				</div>
  				
  			</div>
  		</div>
  		
  	</div>
  	<div class="card card-gray card-outline card-tabs">
      <div class="card-header p-0 pt-1 border-bottom-0">
        <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Student Information</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Parents | Guardian</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-two-messages-tab" data-toggle="pill" href="#custom-tabs-two-messages" role="tab" aria-controls="custom-tabs-two-messages" aria-selected="false">Medical Information</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-two-settings-tab" data-toggle="pill" href="#custom-tabs-two-settings" role="tab" aria-controls="custom-tabs-two-settings" aria-selected="false">Other Information</a>
          </li>
        </ul>
      </div>
      <div class="card-body">
      	<div class="">
	        <div class="tab-content" id="custom-tabs-two-tabContent">
	          <div class="tab-pane fade active show" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
	          	<div class="row">
		        		<div class="form-group col-md-6">
		          		<label for="inputName">LRN</label>
		        			<input type="text" id="lrn" name="lrn" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>
		        		<div class="form-group col-md-6">
		          		<label for="inputName">Grade Level</label>
		        			<select id="glevel" class="form-control is-invalid validation" value="">
		        				<option value="0"></option>
			        			@foreach($glevel as $l)
		        					<option value="{{$l->id}}">{{$l->levelname}}</option>
			        			@endforeach
			        		</select>
		        		</div>	
		        	</div>
	        		<div class="row">
		          	<div class="form-group col-md-3">
		          		<label for="inputName">First Name</label>
		        			<input type="text" id="fname" class="form-control is-invalid validation" value="" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Middle Name</label>
		        			<input type="text" id="mname" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Last Name</label>
		        			<input type="text" id="lname" class="form-control is-invalid validation" value="" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Suffix</label>
		        			<input type="text" id="suffix" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>
	  	      	</div>
	  	      	<div class="row">
	  	      		<div class="form-group col-md-3">
		          		<label for="inputName">Date of Birth</label>
		          		<div class="input-group">
			          		<div class="input-group-prepend">
		                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
		                </div>
			        			<input type="date" id="dob" value="" class="form-control is-invalid validation" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask="" im-insert="false">
		        			</div>
		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Gender</label>
		        			<select id="gender" class="form-control" value="">
		        				<option selected="">MALE</option>
		        				<option>FEMALE</option>			        			
			        		</select>
		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Contact No.</label>
		        			<input type="text" id="contactno" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>
	  	      	</div>
	  	      	<div class="row">
		          	<div class="form-group col-md-3">
		          		<label for="inputName">Religion</label>
		          		<div class="input-group">
			        			<select id="religion" class="form-control" value="">
			        				<option value="0" selected=""></option>
				        			@foreach($religion as $rel)
				        				<option value="{{$rel->id}}">{{$rel->religionname}}</option>
				        			@endforeach
				        		</select>
				        		<div class="input-group-append">
		                  <span class="input-group-text"><i class="fas fa-ellipsis-h"></i></span>
		                </div>
				        	</div>
		        		</div>		

		        		<div class="form-group col-md-3">
		          		<label for="inputName">Mother Tongue</label>
		          		<div class="input-group">
			        			<select id="mt" class="form-control" value="">
			        				<option value="0" selected=""></option>
				        			@foreach($mothertongue as $mt)
			        					<option value="{{$mt->id}}">{{$mt->mtname}}</option>
				        			@endforeach
				        		</select>
				        		<div class="input-group-append">
		                  <span class="input-group-text"><i class="fas fa-ellipsis-h"></i></span>
		                </div>
				        	</div>
		        		</div>	

		        		<div class="form-group col-md-3">
		          		<label for="inputName">Ethnic Group</label>
		          		<div class="input-group">
			        			<select id="eg" class="form-control" value="">
			        				<option value="0" selected=""></option>
			        				@foreach($ethnic as $eg)
			        					<option value="{{$eg->id}}">{{$eg->egname}}</option>
				        			@endforeach
				        		</select>
				        		<div class="input-group-append">
		                  <span class="input-group-text"><i class="fas fa-ellipsis-h"></i></span>
		                </div>
				        	</div>
		        		</div>			
	          	</div>
	          	<div class="row">
	          		<div class="form-group col-md-3">
		          		<label for="inputName">Home Address </label>
		        			<input type="text" id="street" class="form-control" value="" placeholder="House no. | Blk | Lot | Street" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>	

		        		<div class="form-group col-md-3">
		          		<label for="inputName">&nbsp;</label>
		        			<input type="text" id="barangay" class="form-control" value="" placeholder="Barangay" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>	

		        		<div class="form-group col-md-3">
		        			<label for="inputName">&nbsp;</label>
		        			<input type="text" id="city" class="form-control" value="" placeholder="Municipality/City" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>	

		        		<div class="form-group col-md-3">
		          		<label for="inputName">&nbsp;</label>
		        			<input type="text" id="province" class="form-control" value="" placeholder="Province" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>	
	          	</div>
	          </div>

	          <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
	          	<div class="row">
	          		<div class="col-12">
	          			<h5 class="text-navy">Father Information</h5>
	          		</div>
	          	</div>
	          	<div class="row">
	          		<div class="form-group col-md-4">
		          		<label for="inputName">Name</label>
		        			<input type="text" id="fathername" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Occupation</label>
		        			<input type="text" id="foccupation" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Contact No.</label>
		        			<input type="text" id="fcontactno" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-2">
		        			<label for="radioPrimary3">&nbsp;</label>
                  <div class="icheck-primary">
                  	<input type="radio" id="isfather" name="r1">
                    <label for="isfather">
                      Default
                    </label>
                  </div>
                </div>

	          	</div>

	          	<div class="row">
	          		<div class="col-12">
	          			<h5 class="text-navy">Mother Information</h5>
	          		</div>
	          	</div>
	          	<div class="row">
	          		<div class="form-group col-md-4">
		          		<label for="inputName">Name</label>
		        			<input type="text" id="mothername" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Occupation</label>
		        			<input type="text" id="moccupation" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Contact No.</label>
		        			<input type="text" id="mcontactno" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-2">
		        			<label for="radioPrimary3">&nbsp;</label>
                  <div class="icheck-primary">
                  	<input type="radio" id="ismother" name="r1">
                    <label for="ismother">
                      Default
                    </label>
                  </div>
                </div>
	          	</div>

	          	<div class="row">
	          		<div class="col-12">
	          			<h5 class="text-navy">Guardian Information</h5>
	          		</div>
	          	</div>
	          	<div class="row">
	          		<div class="form-group col-md-4">
		          		<label for="inputName">Name</label>
		        			<input type="text" id="guardianname" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Relation</label>
		        			<input type="text" id="guardianrelation" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Contact No.</label>
		        			<input type="text" id="gcontactno" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-2">
		        			<label for="radioPrimary3">&nbsp;</label>
                  <div class="icheck-primary">
                 		<input type="radio" id="isguardian" name="r1">
                    <label for="isguardian">
                      Default
                    </label>
                  </div>
                </div>
	          	</div>
	          </div>
	          <div class="tab-pane fade" id="custom-tabs-two-messages" role="tabpanel" aria-labelledby="custom-tabs-two-messages-tab">
             	<div class="row">
	          		<div class="form-group col-md-8">
		          		<label for="inputName">Blood Type</label>
		        			<input type="text" id="bloodtype" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>
	          	</div>
	          	<div class="row">
	          		<div class="form-group col-md-8">
		          		<label for="inputName">Allergy</label>
		        			<input type="text" id="allergy" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>
	          	</div>
	          	<div class="row">
	          		<div class="form-group col-md-8">
		          		<label for="inputName">Other Medical Information</label>
		        			<input type="text" id="others" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>
	          	</div>
	          </div>
	          <div class="tab-pane fade" id="custom-tabs-two-settings" role="tabpanel" aria-labelledby="custom-tabs-two-settings-tab">
	             <div class="row">
	          		<div class="form-group col-md-8">
		          		<label for="inputName">RFID</label>
		        			<input type="text" id="rfid" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>
	          	</div>
	          </div>
	        </div>
	      </div>  
      </div>
      <!-- /.card -->
    </div>
  </section>
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <script>
		
		$(document).ready(function(){

			$(document).on('change', '.validation', function(){
				if($(this).val() != '')
				{
					$(this).attr('class', 'form-control is-valid validation')
				}
				else
				{
					$(this).attr('class', 'form-control is-invalid validation')	
				}
			});

			$(document).on('click', '#btnSave', function(){
				
				var lrn = $('#lrn').val();
				var	glevel = $('#glevel').val();
				var	fname = $('#fname').val();
				var	mname = $('#mname').val();
				var	lname = $('#lname').val();
				var	suffix = $('#suffix').val();
				var	dob = $('#dob').val();
				var	gender = $('#gender').val();
				var	contactno = $('#contactno').val();
				var	religion = $('#religion').val();
				var	mt = $('#mt').val();
				var	eg = $('#eg').val();
				var	street = $('#street').val();
				var	barangay = $('#barangay').val();
				var	city = $('#city').val();
				var	province = $('#province').val();
				var	fathername = $('#fathername').val();
				var	foccupation = $('#foccupation').val();
				var	fcontactno = $('#fcontactno').val();
				var	mothername = $('#mothername').val();
				var	moccupation = $('#moccupation').val();
				var	mcontactno = $('#mcontactno').val();
				var	guardianname = $('#guardianname').val();
				var	guardianrelation = $('#guardianrelation').val();
				var	gcontactno = $('#gcontactno').val();
				var	bloodtype = $('#bloodtype').val();
				var	allergy = $('#allergy').val();
				var	others = $('#others').val();
				var	rfid = $('#rfid').val();
				
				var isfather =0;
				var ismother =0;
				var isguardian =0;


				if($('#isfather').prop('checked')==true)
				{
					isfather = 1;
				}
				else
				{
					isfather = 0;
				}

				if($('#ismother').prop('checked')==true)
				{
					ismother = 1;
				}
				else
				{
					ismother = 0;
				}

				if($('#isguardian').prop('checked')==true)
				{
					isguardian = 1;
				}
				else
				{
					isguardian = 0;
				}

				console.log(studid);

				$.ajax({
					url:"{{ route('studentinsert') }}",
					method:'GET',
					data:{
						lrn:lrn,
						glevel:glevel,
						fname:fname,
						mname:mname,
						lname:lname,
						suffix:suffix,
						dob:dob,
						gender:gender,
						contactno:contactno,
						religion:religion,
						mt:mt,
						eg:eg,
						street:street,
						barangay:barangay,
						city:city,
						province:province,
						fathername:fathername,
						foccupation:foccupation,
						fcontactno:fcontactno,
						isfather:isfather,
						mothername:mothername,
						moccupation:moccupation,
						mcontactno:mcontactno,
						ismother:ismother,
						guardianname:guardianname,
						guardianrelation:guardianrelation,
						gcontactno:gcontactno,
						isguardian:isguardian,
						bloodtype:bloodtype,
						allergy:allergy,
						others:others,
						rfid:rfid
					},
					dataType:'text',
					success:function(data)
					{
						alert("Saved");
						window.location.href = "{{route('studentinfo')}}"; 
					}
				});
			});

		});

	</script>
@endsection
{{-- @section('js') --}}
	
{{-- @endsection --}}
