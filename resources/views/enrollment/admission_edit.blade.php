@extends('enrollment.layouts.app')

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Admission - Edit</h1> -->
		  <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-file-invoice nav-icon"></i>  -->
            <b>ADMISSION - EDIT</b></h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/registrarIndex">Home</a></li>
            <li class="breadcrumb-item"><a href="/admission/">Admission</a></li>
            <li class="breadcrumb-item active">Edit</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
  	<div class="card card-body bg-info">
  		<div class="row">
  			<div class="col-5">
  				
  			</div>
  			<div class="col-7">
  				<div class="form-group">
  					<button class="btn btn-danger float-right" onclick="window.location.href='/admission'">Cancel</button>

  					<button id="btnSave" data-code="{{$stud->queing_code}}" data-strand="{{$stud->strandid}}" class="btn btn-primary float-right mr-1">Register</button>

  					<button id="btnrequirements" class="btn btn-warning float-right mr-1">Requirements </button> 
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
	        		<div class="form-group col-md-3">
	          		<label for="inputName">LRN</label>
	        			<input type="text" id="lrn" name="lrn" class="form-control" value="" onkeyup="this.value = this.value.toUpperCase();">
	        		</div>
	        		<div class="form-group col-md-3">
	          			<label for="inputName">Grade Level</label>
	        			<select id="glevel" class="form-control is-invalid validation" value="">
	        				<option value=""></option>
		        			@foreach($glevel as $l)
		        				@if($l->id == $stud->gradelevelid)
	        						<option selected="" value="{{$l->id}}">{{$l->levelname}}</option>
	        					@else
	        						<option value="{{$l->id}}">{{$l->levelname}}</option>
	        					@endif
		        			@endforeach
		        		</select>
	        		</div>	
	        		<div class="form-group col-md-3">
	          			<label for="inputName">Grantee</label>
	        			<select id="grantee" class="form-control " value="">
		        			@foreach($grantee as $g)
		        				@if($g->id == $stud->grantee)
	        						<option selected="" value="{{$g->id}}">{{$g->description}}</option>
	        					@else
	        						<option value="{{$g->id}}">{{$g->description}}</option>
	        					@endif
		        			@endforeach
		        		</select>
	        		</div>	
	        		<div class="form-group col-md-3">
	          			<label for="inputName">Mode of Learning</label>
	        			<select id="mol" class="form-control " value="">
	        				<option value=""></option>
		        			@foreach($modeoflearning as $mol)
		        				@if($mol->id == $stud->mol)
	        						<option selected="" value="{{$mol->id}}">{{$mol->description}}</option>
	        					@else
	        						<option value="{{$mol->id}}">{{$mol->description}}</option>
	        					@endif
		        			@endforeach
		        		</select>
	        		</div>	
		        	</div>
	        		<div class="row">
		          	<div class="form-group col-md-3">
		          		<label for="inputName">First Name</label>
		        			<input type="text" id="fname" class="form-control validation" value="{{$stud->first_name}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Middle Name</label>
		        			<input type="text" id="mname" class="form-control" value="{{$stud->middle_name}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Last Name</label>
		        			<input type="text" id="lname" class="form-control validation" value="{{$stud->last_name}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Suffix</label>
		        			<input type="text" id="suffix" class="form-control" value="{{$stud->suffix}}" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>
	  	      	</div>
	  	      	<div class="row">
	  	      		<div class="form-group col-md-3">
		          		<label for="inputName">Date of Birth</label>
		          		<div class="input-group">
			          		<div class="input-group-prepend">
		                  <span class="input-group-text border-secondary"><i class="far fa-calendar-alt"></i></span>
		                </div>
			        			<input type="date" id="dob" value="{{$stud->dob}}" class="form-control validation" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask="" im-insert="false">
		        			</div>
		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Gender</label>
		        			<select id="gender" class="form-control" value="{{$stud->gender}}">
			        			@if($stud->gender == 'MALE' || $stud->gender == 'Male')
			        				<option selected="">MALE</option>
			        				<option>FEMALE</option>
			        			@else
			        				<option>Male</option>
			        				<option selected="">FEMALE</option>
			        			@endif
			        		</select>
		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Contact No.</label>
		        			<input type="text" id="contactno" class="form-control" value="{{$stud->contact_number}}" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>
	  	      	</div>
	  	      	<div class="row">
		          	<div class="form-group col-md-3">
		          		<label for="inputName">Religion</label>
		          		<div class="input-group">
			        			<select id="religion" class="form-control" value="">
			        				<option value="0" selected=""></option>
				        			@foreach($religion as $rel)
				        				@if($rel->id == $stud->relegionid)
				        					<option value="{{$rel->id}}" selected="">{{$rel->religionname}}</option>
				        				@else
				        					<option value="{{$rel->id}}">{{$rel->religionname}}</option>
				        				@endif
				        			@endforeach
				        		</select>
				        		<div class="input-group-append">
		                  <button id="cmdAddReligion" class="btn btn-primary" data-toggle="modal" data-target="#religionmodal"><i class="fas fa-ellipsis-h"></i></button>
		                </div>
				        	</div>
		        		</div>		

		        		<div class="form-group col-md-3">
		          		<label for="inputName">Mother Tongue</label>
		          		<div class="input-group">
			        			<select id="mt" class="form-control" value="">
			        				<option value="0" selected=""></option>
				        			@foreach($mothertongue as $mt)
				        				@if($mt->id == $stud->mtid)
				        					<option value="{{$mt->id}}" selected="">{{$mt->mtname}}</option>
				        				@else
				        					<option value="{{$mt->id}}">{{$mt->mtname}}</option>
				        				@endif
				        			@endforeach
				        		</select>
				        		<div class="input-group-append">
		                  <button id="cmdAddMT" class="btn btn-primary" data-toggle="modal" data-target="#mtmodal"><i class="fas fa-ellipsis-h"></i></button>
		                </div>
				        	</div>
		        		</div>	

		        		<div class="form-group col-md-3">
			          		<label for="inputName">Ethnic Group</label>
			          		<div class="input-group">
			        			<select id="eg" class="form-control" value="">
			        				<option value="0" selected=""></option>
				        			@foreach($ethnic as $eg)
				        				@if($eg->id == $stud->egid)
				        					<option value="{{$eg->id}}" selected="">{{$eg->egname}}</option>
				        				@else
				        					<option value="{{$eg->id}}">{{$eg->egname}}</option>
				        				@endif
				        			@endforeach
				        		</select>
				        		<div class="input-group-append">
		                  			<button id="cmdAddEG" class="btn btn-primary" data-toggle="modal" data-target="#egmodal"><i class="fas fa-ellipsis-h"></i></button>
		                		</div>
					        </div>
		        		</div>			

		        		<div class="form-group col-md-3">
			          		<label for="inputName">Nationality</label>
			          		<div class="input-group">
			        			<select id="nationality" class="form-control select2bs4" value="">
			        				<option value="0" selected=""></option>
				        			@foreach($nationality as $nat)
				        				@if($nat->id == $stud->nationality)
				        					<option value="{{$nat->id}}" selected="">{{$nat->nationality}}</option>
				        				@else
				        					<option value="{{$nat->id}}">{{$nat->nationality}}</option>
				        				@endif
				        			@endforeach
				        		</select>
				        		
					        </div>
		        		</div>			
	          	</div>
	          	<div class="row">
	          		<div class="form-group col-md-3">
		          		<label for="inputName">Home Address </label>
		        			<input type="text" id="street" class="form-control" value="{{$stud->street}}" placeholder="House no. | Blk | Lot | Street" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>	

		        		<div class="form-group col-md-3">
		          		<label for="inputName">&nbsp;</label>
		        			<input type="text" id="barangay" class="form-control" value="{{$stud->barangay}}" placeholder="Barangay" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>	

		        		<div class="form-group col-md-3">
		        			<label for="inputName">&nbsp;</label>
		        			<input type="text" id="city" class="form-control" value="{{$stud->city}}" placeholder="Municipality/City" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>	

		        		<div class="form-group col-md-3">
		          		<label for="inputName">&nbsp;</label>
		        			<input type="text" id="province" class="form-control" value="{{$stud->province}}" placeholder="Province" onkeyup="this.value = this.value.toUpperCase();">
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
		        			<input type="text" id="fathername" class="form-control" value="{{$stud->father_name}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Occupation</label>
		        			<input type="text" id="foccupation" class="form-control" value="{{$stud->father_occupation}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Contact No.</label>
		        			<input type="text" id="fcontactno" class="form-control" value="{{$stud->father_contact_number}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-2">
		        			<label for="radioPrimary3">&nbsp;</label>
	              <div class="icheck-primary">
	              	@if($stud->isfathernum == 1)
	                	<input type="radio" id="isfather" name="r1" checked="">
	                @else
	                	<input type="radio" id="isfather" name="r1">
	                @endif
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
		        			<input type="text" id="mothername" class="form-control" value="{{$stud->mother_name}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Occupation</label>
		        			<input type="text" id="moccupation" class="form-control" value="{{$stud->mother_occupation}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Contact No.</label>
		        			<input type="text" id="mcontactno" class="form-control" value="{{$stud->mother_contact_number}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-2">
		        			<label for="radioPrimary3">&nbsp;</label>
	              <div class="icheck-primary">
	              	@if($stud->ismothernum == 1)
	                	<input type="radio" id="ismother" name="r1" checked="">
	                @else
	                	<input type="radio" id="ismother" name="r1">
	                @endif
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
		        		<input type="text" id="guardianname" class="form-control" value="{{$stud->guardian_name}}" onkeyup="this.value = this.value.toUpperCase();">
		        	</div>
		        	<div class="form-group col-md-3">
		          		<label for="inputName">Relation</label>
		        		<input type="text" id="guardianrelation" class="form-control" value="{{$stud->guardian_relation}}" onkeyup="this.value = this.value.toUpperCase();">
		        	</div>
		        	<div class="form-group col-md-3">
		          		<label for="inputName">Contact No.</label>
		        		<input type="text" id="gcontactno" class="form-control" value="{{$stud->guardian_contact_number}}" onkeyup="this.value = this.value.toUpperCase();">

		        	</div>
		        	<div class="form-group col-md-2">
		        			<label for="radioPrimary3">&nbsp;</label>
	              			<div class="icheck-primary">
			                  	@if($stud->isguardian == 1)
			                   		<input type="radio" id="isguardian" name="r1" checked="">
			                   	@else
			                   		<input type="radio" id="isguardian" name="r1">
			                   	@endif
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
		        		<input type="text" id="bloodtype" class="form-control" value="{{$stud->bloodytype}}" onkeyup="this.value = this.value.toUpperCase();">
		        	</div>
	          	</div>
	          	<div class="row">
	          		<div class="form-group col-md-8">
		          		<label for="inputName">Allergy</label>
		        		<input type="text" id="allergy" class="form-control" value="{{$stud->allergy}}" onkeyup="this.value = this.value.toUpperCase();">
		        	</div>
	          	</div>
	          	<div class="row">
	          		<div class="form-group col-md-8">
		          		<label for="inputName">Other Medical Information</label>
		        			<input type="text" id="others" class="form-control" value="{{$stud->others}}" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>
	          	</div>
	        </div>
	        <div class="tab-pane fade" id="custom-tabs-two-settings" role="tabpanel" aria-labelledby="custom-tabs-two-settings-tab">
	        	<div class="row">
	          		<div class="form-group col-md-4">
		          		<label for="inputName">Student Type</label>
		        		<select id="studtype" class="form-control select2bs4">
		        			<option value="new">NEW</option>
		        			<option value="old">OLD</option>
		        			<option value="transferee">TRANSFEREE</option>
		        			<option value="returnee">RETURNEE</option>
		        		</select>
		        	</div>
	          	</div>
	        	<div class="row">
	          		<div class="form-group col-md-4">
		          		<label for="inputName">Last School Attended</label>
		        		<input type="text" id="lastschool" class="form-control" value="{{$stud->lastschoolatt}}" onkeyup="this.value = this.value.toUpperCase();">
		        	</div>
	          	</div>
	           	<div class="row">
	          		<div class="form-group col-md-4">
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
@endsection
@section('modal')
	<div class="modal fade show" id="religionmodal" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Religion</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
	        
	        <div class="row">
	        	<div class="col-12">
		        	<div class="form-group row">
		        		<label for="txtreligion" class="col-3">Religion</label>
		        		<div class="col-9">
			        		<input type="text" class="form-control" name="" id="txtreligion" placeholder="Religion" onkeyup="this.value = this.value.toUpperCase();">
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
	        		<button id="cmdSaveReligion" type="button" class="btn btn-primary" style="width: 90px"><i class="fas fa-save"></i> Save</button>
	        	</div>
	        </div>
	      </div>
      </div>
    </div>
      <!-- /.modal-content -->
  </div>


  <div class="modal fade show" id="mtmodal" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Mother Tongue</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
	        
	        <div class="row">
	        	<div class="col-12">
		        	<div class="form-group row">
		        		<label for="txtmt" class="col-4">Mother Tongue</label>
		        		<div class="col-8">
			        		<input type="text" class="form-control" name="" id="txtmt" placeholder="Mother Tongue" onkeyup="this.value = this.value.toUpperCase();">
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
        		<button id="cmdSaveMT" type="button" class="btn btn-primary" style="width: 90px"><i class="fas fa-save"></i> Save</button>
        	</div>
        </div>
      </div>
    </div>
      <!-- /.modal-content -->
  </div>

  <div class="modal fade show" id="egmodal" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Ethnic Group</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
	        
	        <div class="row">
	        	<div class="col-12">
		        	<div class="form-group row">
		        		<label for="txteg" class="col-4">Ethnic Group</label>
		        		<div class="col-8">
			        		<input type="text" class="form-control" name="" id="txteg" placeholder="Ethnic Group" onkeyup="this.value = this.value.toUpperCase();">
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
        		<button id="cmdSaveEG" type="button" class="btn btn-primary" style="width: 90px"><i class="fas fa-save"></i> Save</button>
        	</div>
        </div>
      </div>
    </div>
      <!-- /.modal-content -->
  </div>

  <div class="modal fade" id="modal-requirements" style="display: none;" aria-modal="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary">
			  <h4 class="modal-title">Submitted Requirements</h4>
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">×</span>
			  </button>
			</div>
			<div class="modal-body">
				<div class="row" id="req-img">
					
				</div>
			</div>
			<div class="modal-footer justify-content-between">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
<!-- /.modal-dialog -->
</div>

@endsection
@section('js')
	<script>
		
		$(document).ready(function(){

			$('.select2bs4').select2({
		        theme: 'bootstrap4'
		      });

			$('#studtype').val('{{$stud->studtype}}');
			$('#studtype').trigger('change');


			$('.validation').each(function(){

				
				if($(this).val() != '')
				{
					$(this).attr('class', 'form-control is-valid validation')
				}
				else
				{
					$(this).attr('class', 'form-control is-invalid validation')	
				}	
				
			});


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

				if($('#glevel').val() != '')
				{
					var studid = $('#studid').attr('data-id');
					var code = $('#btnSave').attr('data-code');
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
					var lastschool = $('#lastschool').val();
					var	rfid = $('#rfid').val();

					var grantee = $('#grantee').val();
					var mol = $('#mol').val();
					var nationality = $('#nationality').val();

					var strandid = $('#btnSave').attr('data-strand');
					var studtype = $('#studtype').val();
					
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

					$.ajax({
						url:"{{ route('admissionregister') }}",
						method:'GET',
						data:{
							code:code,
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
							strandid:strandid,
							grantee:grantee,
							mol:mol,
							nationality:nationality,
							lastschool:lastschool,
							studtype:studtype
						},
						dataType:'',
						success:function(data)
						{
							// alert("Saved");
							if(data == 1)
							{
								window.location.href = "{{route('admission')}}"; 
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
								  title: 'Student already exist'
								})
							}
						}
					});
				}
				else
				{
					
				}
			});

			$(document).on('click', '#cmdAddReligion', function(){
				$('#txtreligion').focus();
				$('#txtreligion').val('');
				$('#txtreligion').attr('class', 'form-control')
			});

			$(document).on('click', '#cmdSaveReligion', function(){
				console.log($('#txtreligion').val());
				if($('#txtreligion').val() == '')
				{
					$('#txtreligion').attr('class', 'form-control is-invalid');
				}
				else
				{
					var religion = $('#txtreligion').val();
					$.ajax({
						url:"{{ route('addReligion') }}",
						method:'GET',
						data:{
							religion:religion
						},
						dataType:'json',
						success:function(data)
						{
							$('#religion').html(data.output);
							$('#religionmodal').modal('hide');
						}
					});
				}
			});


			$(document).on('click', '#cmdAddMT', function(){
				$('#txtmt').focus();
				$('#txtmt').val('');
				$('#txtmt').attr('class', 'form-control')
			});

			$(document).on('click', '#cmdSaveMT', function(){
				console.log($('#txtmt').val());
				if($('#txtmt').val() == '')
				{
					$('#txtmt').attr('class', 'form-control is-invalid');
				}
				else
				{
					var mt = $('#txtmt').val();
					$.ajax({
						url:"{{ route('addMT') }}",
						method:'GET',
						data:{
							mt:mt
						},
						dataType:'json',
						success:function(data)
						{
							$('#mt').html(data.output);
							$('#mtmodal').modal('hide');
						}
					});
				}
			});


			$(document).on('click', '#cmdAddEG', function(){
				$('#txteg').focus();
				$('#txteg').val('');
				$('#txteg').attr('class', 'form-control')
			});

			$(document).on('click', '#cmdSaveEG', function(){
				console.log($('#txteg').val());
				if($('#txteg').val() == '')
				{
					$('#txteg').attr('class', 'form-control is-invalid');
				}
				else
				{
					var eg = $('#txteg').val();
					$.ajax({
						url:"{{ route('addEG') }}",
						method:'GET',
						data:{
							eg:eg
						},
						dataType:'json',
						success:function(data)
						{
							$('#eg').html(data.output);
							$('#egmodal').modal('hide');
						}
					});
				}
			});

			$(document).on('click', '#btnrequirements', function(){
				var qcode = $('#btnSave').attr('data-code');
				console.log(qcode);
				$.ajax({
					url:"{{ route('preregreq') }}",
					method:'GET',
					data:{
						qcode:qcode
					},
					dataType:'json',
					success:function(data)
					{
						console.log('test');
						$('#req-img').html(data.list);
						$('#modal-requirements').modal('show');			
					}
				});
			});

		});

	</script>
@endsection