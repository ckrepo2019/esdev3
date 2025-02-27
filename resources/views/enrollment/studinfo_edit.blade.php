@extends('enrollment.layouts.app')

@section('content')
	<section class="content-header pt-0">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Student Information - Edit</h1> -->
		  <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-file-invoice nav-icon"></i>  -->
            <b>STUDENT INFORMATION - EDIT</b></h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/registrar/studentinfo">Student Information</a></li>
            <li class="breadcrumb-item active">Edit</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
  	<div class="card card-body bg-info">
  		<div class="row">
  			<div class="col-9">
  				<h3 id="studid"  class="col-md-4" data-id="{{$stud->id}}">ID No: {{$stud->sid}}</h3>
  			</div>
  			<div class="col-3">
  				<div class="float-sm-right">
					<form action="/registrar/studentinfo/print" method="get" class="m-0 p-0" style="display: inline;" target="_blank">
						<input type="hidden" name="studid" value="{{$stud->id}}"/>
						<button type="submit" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
					</form>
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
		        		<div class="form-group col-md-3">
		          		<label for="inputName">LRN</label>
		        			<input type="text" id="lrn" name="lrn" class="form-control" value="{{$stud->lrn}}" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Grade Level</label>
		        			<select id="glevel" class="form-control is-invalid validation" value="">
			        			@foreach($glevel as $l)
			        				@if($l->id == $stud->levelid)
			        					<option value="{{$l->id}}" selected="">{{$l->levelname}}</option>
			        				@else
			        					<option value="{{$l->id}}">{{$l->levelname}}</option>
			        				@endif
			        			@endforeach
			        		</select>
		        		</div>	

		        		<div class="form-group col-md-3">
		        			<label for="inputName">Grantee</label>
		        			<select id="grantee" class="form-control" value="">
			        			@foreach($grantee as $g)
			        				@if($g->id == $stud->grantee)
			        					<option value="{{$g->id}}" selected>{{$g->description}}</option>
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
		        			<input type="text" id="fname" class="form-control validation" value="{{$stud->firstname}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Middle Name</label>
		        			<input type="text" id="mname" class="form-control" value="{{$stud->middlename}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Last Name</label>
		        			<input type="text" id="lname" class="form-control validation" value="{{$stud->lastname}}" onkeyup="this.value = this.value.toUpperCase();">

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
		                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
		                </div>
			        			<input type="date" id="dob" value="{{$stud->dob}}" class="form-control validation" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask="" im-insert="false">
		        			</div>
		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Gender</label>
		        			<select id="gender" class="form-control" value="{{$stud->gender}}">
			        			@if($stud->gender == 'MALE')
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
		        			<input type="text" id="contactno" class="form-control" value="{{$stud->contactno}}" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>
	  	      	</div>
	  	      	<div class="row">
		          	<div class="form-group col-md-3">
		          		<label for="inputName">Religion</label>
		          		<div class="input-group">
			        			<select id="religion" class="form-control" value="">
				        			@foreach($religion as $rel)
				        				@if($rel->id == $stud->religionid)
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
		        			<input type="text" id="fathername" class="form-control" value="{{$stud->fathername}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Occupation</label>
		        			<input type="text" id="foccupation" class="form-control" value="{{$stud->foccupation}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Contact No.</label>
		        			<input type="text" id="fcontactno" class="form-control" value="{{$stud->fcontactno}}" onkeyup="this.value = this.value.toUpperCase();">

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
		        			<input type="text" id="mothername" class="form-control" value="{{$stud->mothername}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Occupation</label>
		        			<input type="text" id="moccupation" class="form-control" value="{{$stud->moccupation}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Contact No.</label>
		        			<input type="text" id="mcontactno" class="form-control" value="{{$stud->mcontactno}}" onkeyup="this.value = this.value.toUpperCase();">

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
		        			<input type="text" id="guardianname" class="form-control" value="{{$stud->guardianname}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Relation</label>
		        			<input type="text" id="guardianrelation" class="form-control" value="{{$stud->guardianrelation}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-3">
		          		<label for="inputName">Contact No.</label>
		        			<input type="text" id="gcontactno" class="form-control" value="{{$stud->gcontactno}}" onkeyup="this.value = this.value.toUpperCase();">

		        		</div>
		        		<div class="form-group col-md-2">
		        			<label for="radioPrimary3">&nbsp;</label>
                  <div class="icheck-primary">
                  	@if($stud->isguardannum == 1)
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
		        			<input type="text" id="bloodtype" class="form-control" value="{{$stud->bloodtype}}" onkeyup="this.value = this.value.toUpperCase();">
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
		        	{{-- @if(db::table('schoolinfo')->first()->snr == 'MAC') --}}
		        		@if($stud->pantawid == 1)
				        	<div class="col-md-4" style="margin-top: 36px">
				        		<div class="icheck-primary d-inline">
			                        <input type="checkbox" id="pantawid" checked="">
			                        <label for="pantawid">
			                        	4P's (Pantawid)
			                        </label>
			                      </div>
				        	</div>
				        @else
				        	<div class="col-md-4" style="margin-top: 36px">
				        		<div class="icheck-primary d-inline">
			                        <input type="checkbox" id="pantawid">
			                        <label for="pantawid">
			                        	4P's (Pantawid)
			                        </label>
			                      </div>
				        	</div>
				        @endif
			        {{-- @endif --}}
	          	</div>
	          	<div class="row">
	          		<div class="form-group col-md-4">
		          		<label for="lastschool">Last School Attended</label>
		        		<input type="text" id="lastschool" class="form-control" value="{{$stud->lastschoolatt}}" onkeyup="this.value = this.value.toUpperCase();">
		        	</div>
		        	<div class="form-group col-md-2">
		          		<label for="lastschoolsy">School Year (YYYY-YYYY)</label>
		        			<input type="text" id="lastschoolsy" class="form-control" value="{{$stud->lastschoolsy}}" onkeyup="this.value = this.value.toUpperCase();">
		        	</div>
	          	</div>
	             <div class="row">
	          		<div class="form-group col-md-4">
		          		<label for="inputName">RFID</label>
		        		<input type="text" id="rfid" class="form-control" value="{{$stud->rfid}}" onkeyup="this.value = this.value.toUpperCase();">
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


@endsection


@section('js')
	<script>
		
		$(document).ready(function(){

			$('.select2bs4').select2({
		       theme: 'bootstrap4'
		    });

			if($('.validation').val() != '')
			{
				$('.validation').attr('class', 'form-control is-valid validation')
			}
			else
			{
				$('.validation').attr('class', 'form-control is-invalid validation')	
			}


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

			$('#studtype').val('{{$stud->studtype}}');
			$('#studtype').trigger('change');

			$(document).on('click', '#btnSave', function(){
				var studid = $('#studid').attr('data-id');
				var lrn = $('#lrn').val();
				var grantee = $('#grantee').val();
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
				var grantee = $('#grantee').val();
				var isfather =0;
				var ismother =0;
				var isguardian =0;
				var lastschool = $('#lastschool').val();
				var lastschoolsy = $('#lastschoolsy').val();

				var mol = $('#mol').val();
				var nationality = $('#nationality').val();
				var studtype = $('#studtype').val();
				var pantawid = '';

				if($('#pantawid').prop('checked') == true)
				{
					pantawid = 1;
				}
				else
				{
					pantawid = 0;
				}								

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
					url:"{{ route('studentupdate') }}",
					method:'GET',
					data:{
						studid:studid,
						lrn:lrn,
						grantee:grantee,
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
						rfid:rfid,
						mol:mol,
						nationality:nationality,
						lastschool:lastschool,
						lastschoolsy:lastschoolsy,
						studtype:studtype,
						pantawid:pantawid
					},
					dataType:'text',
					success:function(data)
					{
						window.location.href = "{{route('studentinfo')}}"; 
					}
				});
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

		});


			

	</script>
@endsection
