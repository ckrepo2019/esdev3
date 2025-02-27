@extends('enrollment.layouts.app')

@section('content')
	<section class="content-header pt-0">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Student Information</h1> -->
		  <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-file-invoice nav-icon"></i>  -->
            <b>EARLY REGISTERED STUDENTS</b></h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Early Registered Students</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
	<section class="content">
			<div class="card">
				<div class="card-body bg-info">	
					<div class="row mb-2">

						@php
							$earlybird_setup = db::table('early_enrollment_setup')
								->where('deleted', 0)
								->where('isactive', 1)
								->where('type', 2)
								->first();

						@endphp

						<div class="col-md-3 col-4 mb-2">
							<select id="filter_sy" class="form-control filter">
								<option>School Year</option>
								@foreach(App\RegistrarModel::getSY() as $sy)	
									@if($earlybird_setup->syid == $sy->id)
										<option value="{{$sy->id}}" selected="">{{$sy->sydesc}}</option>
									@else
										<option value="{{$sy->id}}">{{$sy->sydesc}}</option>
									@endif
								@endforeach
							</select>
						</div>
						<div class="col-md-3 col-4 mb-2">
							<select id="filter_sem" class="form-control filter">
								<option>Semester</option>
								@foreach(App\RegistrarModel::getSem() as $sem)
									@if($earlybird_setup->semid == $sem->id)
										<option value="{{$sem->id}}" selected="">{{$sem->semester}}</option>
									@else
										<option value="{{$sem->id}}">{{$sem->semester}}</option>
									@endif
								@endforeach
							</select>
						</div>
						<div class="input-group mb-1 float-right col-md-6 col-12">
							<select id="glevel" class="form-control filter">
								<option value="0" selected="">Grade Level</option>
								@if(count($level)>0)
									@foreach($level as $l)
										<option value="{{$l->id}}">{{$l->levelname}}</option>
									@endforeach
								@endif
							</select>
							&nbsp;
				        	<input id="studSearch" type="text" class="form-control" placeholder="Search" onkeyup="this.value = this.value.toUpperCase();">
					        <div class="input-group-append">
					        	<span class="input-group-text " style="height:38px"><i class="fas fa-search"></i></span>
					        </div>
			        
			      		</div>
					</div>
                    <div class="row">
                        <div class="col-3">
                            <!--<a type="button" class="btn btn-warning btn-block" href="/earlybirds/index" style=" color: inherit;"><strong class="text-black">Add Student</strong></a>-->
                        </div>
                    </div>
				</div>
				
				<div class="card-body">
					<div class="table-responsive">
						{{-- <table class="mb-0 table table table-striped dataTable" role="grid"> --}}
						<table id="" class="table table-striped table-sm text-sm" role="grid" aria-describedby="example2_info">
				            <thead class="bg-warning">
				            <tr>
				                <th>ID No.</th>
				                <th>Student Name</th>
				                <th>Gender</th>
				                <th>Grade Level</th>
				                <th class="text-center" style="width:30px;"></th>
				                <th></th>
				            </tr>
				            </thead>
				            <tbody id="studlist_body">

					           
				            </tbody>
						</table>
					</div>
				</div>
				<div class="card-footer">
					<div class="mt-3" id="data-container" data-value=""></div>
				</div>
			</div>
	</section>
@endsection

@section('modal')
{{-- ENROLLMENT --}}

	<div class="modal fade show" id="enrollstud" aria-modal="true" style="padding-right: 17px; display: none; height: 768px; margin-top: -25px">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div id="enroll-modal-header" class="modal-header bg-info">
        	<div class="row" style="width: 100%">
				<div class="col-md-6">
					<h4 class="modal-title">Enroll Student</h4>	
				</div>
				<div class="col-md-6 text-right">
					<h4 id="promotestatus" class="modal-title"></h4>
				</div>
			</div>
			<div id="paidConfirm" class="ribbon-wrapper ribbon-lg d-none">
			  <div class="ribbon bg-success text-lg">
			    DP - PAID
			  </div>
			</div>
			<div id="nodp" class="ribbon-wrapper ribbon-lg d-none">
			  <div class="ribbon bg-success text-lg">
			    <i class="fas fa-thumbs-up"></i> NO - DP
			  </div>
			</div>
          	

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            {{-- <span aria-hidden="true">×</span> --}}
          </button>
        </div>
        <div class="modal-body" style="height: 480px; overflow-y: auto;">
        	<div class="row">
		        <div class="col-md-12">
			        <div class="row">
			        	<div class="col-2">
			        		<h5>LRN :</h5>	
			        	</div>
			        	<div class="col-4">
			        		<h5 id="lrn"></h5>
			        	</div>
			        	<div class="col-2">
			        		<h4 class="text-right">ID NO:</h4>	
			        	</div>
			        	<div class="col-3">
			        		<h4 id="sid"><strong></strong></h4>
			        	</div>
			        </div>

			      
			        <div class="row">
			        	<div class="col-2">
			        		<h5>NAME :</h5>	
			        	</div>
			        	<div class="col-7">
			        		<h5 id="name"></h5>
			        	</div>
			        </div>

			        <div class="row">
			        	<div class="col-2">
			        		<h5>Date Enrolled :</h5>	
			        	</div>
			        	<div class="col-7">
			        		<h5 id="dateenrolled"></h5>
			        	</div>
			        </div>

			        <hr>
			    </div>
			</div>
			<div class="row">
				<div class="col-md-8">
			        <div class="row">
			        	<div class="col-12">
				        	<div class="form-group row">
				        		<label for="glevelmodal" class="col-3">Grade Level</label>
				        		<div class="col-9">
					        		<select id="glevelmodal" class="form-control validation b-ed sh college">
					        			
				        			</select>
				        		</div>
				        	</div>
				        </div>
			        </div>

			        <div id="divcourse" class="row">
			        	<div class="col-12">
				        	<div class="form-group row">
				        		<label id="lblstrand" for="strand" class="col-3">Course</label>
				        		<div class="col-9">
					        		<select id="coursemodal" class="select2bs4 validation college" hidden="" disabled="">
					        			
				        			</select>
				        		</div>
				        	</div>
				        </div>
			        </div>

			        <div class="row">
			        	<div class="col-12">
				        	<div class="form-group row">
				        		<label for="sections" class="col-3">Section</label>
				        		<div class="col-9">
					        		<select id="sections" class="form-control validation selectpicker b-ed sh" disabled="">
					        			<option selected="" value="0"></option>
				        			</select>
				        		</div>
				        	</div>
				        </div>
			        </div>

			        <div id="divstrand" class="row">
			        	<div class="col-12">
				        	<div class="form-group row">
				        		<label id="lblstrand" for="strand" class="col-3">Strand</label>
				        		<div class="col-9">
					        		<select id="strand" class="form-control validation selectpicker sh">
					        			<option selected="" value="0"></option>
				        			</select>
				        		</div>
				        	</div>
				        </div>
			        </div>

			        <div id="divblock" class="row">
			        	<div class="col-12">
				        	<div class="form-group row">
				        		<label id="lblblock" for="block" class="col-3">Block</label>
				        		<div class="col-9">
					        		<select id="block" class="form-control validation selectpicker sh">
					        			<option selected=""></option>
				        			</select>
				        		</div>
				        	</div>
				        </div>
			        </div>

			        <div id="divsem" class="row">
			        	<div class="col-12">
				        	<div class="form-group row">
				        		<label id="lblsem" for="sem" class="col-3">Semester</label>
				        		<div class="col-9">
					        		<select id="sem" class="form-control validation selectpicker sh college">
					        			<option selected=""></option>
				        			</select>
				        		</div>
				        	</div>
				        </div>
			        </div>

			        <div class="row">
			        	<div class="col-12">
				        	<div class="form-group row">
				        		<label for="sy" class="col-3">School Year</label>
				        		<div class="col-9">
					        		<select id="sy" class="form-control validation b-ed sh college">
					        			<option selected=""></option>
				        			</select>
				        		</div>
				        	</div>
				        </div>
			        </div>

			        <div class="row">
			        	<div class="col-12">
				        	<div class="form-group row">
				        		<label for="studstatus" class="col-3">Enrollment Status</label>
				        		<div class="col-9">
					        		<select id="studstatus" class="form-control validation b-ed sh college">
					        			<option selected="">Enrolled</option>
				        			</select>
				        		</div>
				        	</div>
				        </div>
			        </div>

			        {{-- ------update 06022020 requirements------ --}}
			        <div class="row">
			        	<div class="col-3">
			        		
			        	</div>
			        	<div class="col-4">

				        	<button id="btn-req" class="btn btn-outline-primary btn-block">
				        		View Requirements
				        	</button>
				        </div>
			        
			        	<div id="collsched" class="col-5">
				        	<div class="form-group row">
				        		<label for="sy" class="col-3"></label>
				        		<div class="col-9">
					        		<button id="btnsched" class="btn btn-primary btn-block">VIEW SCHEDULE | Units: <span id="nounits" class=""></span></button>
				        		</div>
				        	</div>
				        </div>
			        </div>
			  	</div>
			  	<div class="col-md-4">
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-body bg-info">
									GRANTEE
									<select id="cbograntee" class="form-control">
										{{$grantee = DB::table('grantee')->get()}}
										@foreach($grantee as $g)
											<option value="{{$g->id}}">{{$g->description}}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						@if($schoolinfo->withMOL == 1)
							<div class="col-md-12">
								<div class="card">
									<div class="card-body bg-info">
										MODE OF LEARNING
										<select id="cbomol" class="form-control">
											<option></option>
											{{$modeoflearning = DB::table('modeoflearning')->where('deleted', 0)->get()}}
											@foreach($modeoflearning as $mol)										
												<option value="{{$mol->id}}">{{$mol->description}}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
						@endif
						@if($schoolinfo->withSTUDCLASS == 1)
							<div class="col-md-12">
								<div class="card">
									<div class="card-body bg-info">
										STUDENT CLASSIFICATION
										<select id="cbostudclass" class="form-control">
											{{$studclass = DB::table('studclassifications')->where('deleted', 0)->get()}}
											<option></option>
											@foreach($studclass as $class)
												<option value="{{$class->id}}">{{$class->classification}}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
						@endif
					</div>
				</div>
		  	</div>
        </div>
        <div class="modal-footer justify-content-between"> 
        	{{--  --}}

        	<div class="float-left">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		
        	</div>        	<div class="float-right">
        		<button id="cmdSave" type="button" class="btn btn-primary" data-dismiss="modal" style="width: 90px"><i class="fas fa-save"></i> Save</button>
        		<button id="cmdenrollstudent" type="button" class="btn btn-success" style="width: 120px"><i class="fas fa-share"></i> Enroll</button>
        	</div>
        </div>
      </div>
    </div>
      <!-- /.modal-content -->
  </div>
    <!-- /.modal-dialog -->

<div class="modal fade show " id="studsched" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      	<div class="modal-content mt-0">
	        <div id="enroll-modal-header" class="modal-header bg-primary">
	        	<div class="row" style="width: 100%">
					<div class="col-md-6">
						<h4 class="modal-title">Student Schedule</h4>	
					</div>
					<div class="col-md-6 text-right">
						<h4 id="promotestatus" class="modal-title"></h4>
					</div>
				</div>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
	        </div>
	        <div class="modal-body">
	        	<div class="row">
	        		<div class="col-md-12">
	        			SECTION: <span id="modalsection" class="text-bold"></span> 
	        		</div>
	        	</div>
	        	<div class="row">
	        		<div class="col-md-12 table-responsive" style="height: 403px">
	        			<table class="table table-striped table-head-fixed">
	        				<thead>
	        					<tr>
	        						<th>SUBJECT</th>
	        						<th></th>
	        						<th class="text-center">LEC UNIT</th>
	        							<th class="text-center">LAB UNIT</th>
	        						<th>START TIME</th>	
	        						<th>END TIME</th>
	        					</tr>
	        				</thead>
	        				<tbody id="schedlist">
	        					
	        				</tbody>
	        			</table>
	        		</div>
	        	</div>
	        </div>
	        <div class="modal-footer justify-content-between"> 
	        	{{--  --}}

	        	<div class="float-left">
	        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		
	        	</div>        	<div class="float-right">
	        		{{-- <button id="cmdSave" type="button" class="btn btn-primary" data-dismiss="modal" style="width: 90px"><i class="fas fa-save"></i> Save</button> --}}
	        		{{-- <button id="cmdenrollstudent" type="button" class="btn btn-success" style="width: 120px"><i class="fas fa-share"></i> Enroll</button> --}}
	        	</div>
	        </div>
	    </div>
    </div>
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

<div class="modal fade" id="modal-studpaid" style="display: none;" aria-modal="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary">
			  <h4 class="modal-title">Students</h4>
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">×</span>
			  </button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>LRN</th>
										<th>STUDENT NAME</th>
										<th>GRADE LEVEL</th>
										<th>STATUS</th>
									</tr>
								</thead>
								<tbody id="studpaidlist" style="cursor: pointer">
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
			</div>
		</div>
	</div>
</div>


@endsection

@section('js')
	<script>
		$(document).ready(function(){

			$('.select2bs4').select2({
		        theme: 'bootstrap4'
		      });

			var skip = 0;
			var studid='';
			var countValidation = 0;
			var gRun = 0;

			var _studid = 0;
			
			searchStud();
			
			function searchStud()
			{
				var syid = $('#filter_sy').val();
				var semid = $('#filter_sem').val();

				levelid = $('#glevel').val();
				query = $('#studSearch').val();
				
				$.ajax({
					url:"{{ route('searchearlyenrollment') }}",
					method:'GET',
					data:{
						query:query,
						levelid:levelid, 
						syid:syid,
						semid:semid
					},
					dataType:'json',
					success:function(data)
					{
						$('#studlist_body').html(data.list);
					}
				});
			}

			function paginate(itemCount)
			{
				var result = [];
		        for (var i = 0; i < itemCount; i++) 
		        {
		        	result.push(i);
		        }


		        $('#data-container').pagination({
		         	dataSource: result,
		         	callback: function(data, pagination) 
		          	{
		          	// searchStud();
		          	// skip = pagination.pageNumber;
		          	// console.log(skip);

			          	$.ajax({
							url:"{{ route('searchPreEnrolledStud') }}",
							method:'GET',
							data:{
								query:$("#studSearch").val(),
								glevel:$('#glevel').val(), 
								take:10,
								skip:pagination.pageNumber,
							},
							dataType:'json',
							success:function(data)
							{
								$('#studlist_body').html(data.output);	
								$('#data-container').attr('data-value', data.recCount);
								// paginate(data.recCount);
							}
						});
		       		},
		            hideWhenLessThanOnePage: true,
		            pageSize: 10,
		        });
			}

			function getDP(studid, levelid, syid, semid)
			{
				console.log('studid: ' + studid);
				$.ajax({
					url:"{{ route('getDP') }}",
					method: 'GET',
					data:{
						studid:studid,
						levelid:levelid,
						syid:syid,
						semid:semid
					},
					dataType:'json',
					success:function(data)
					{
						// console.log('checkDP ' + data);
						if(data.ok == 1)
						{	
							if(data.dp == 1)
							{
								$('#paidConfirm').removeClass('d-none');
								$('#cmdenrollstudent').prop('disabled', false);
							}
							else
							{
								$('#nodp').removeClass('d-none');
								$('#cmdenrollstudent').prop('disabled', false);	
							}
						}
						else
						{
							if(data.dp == 1)
							{
								$('#paidConfirm').removeClass('d-none');
								$('#cmdenrollstudent').prop('disabled', false);	
							}
							else
							{
								$('#paidConfirm').addClass('d-none');	
								$('#nodp').addClass('d-none');	
								$('#cmdenrollstudent').prop('disabled', true);
							}
						}
					}
				})
			}


			$(document).on('click', '.paginationjs-page', function(){
				paginate($('#data-container').attr('data-value'));
			})

			$(document).on('click', 'a', function(){
				console.log();
				var curpage = $(this).attr('data-page');

				if(curpage == 0)
				{
					skip -= 1;		
					curpage = skip + 1;
				}
				else if(curpage == 99)
				{
					skip = parseInt(skip) + 1;
					curpage = skip + 1;
				}
				else
				{
					skip = curpage - 1;
				}
				console.log(skip);
				var query = $('#studSearch').val();
				var glevel = $('glevel').val();
				searchStud(query, glevel, skip, curpage);
			});

			$(document).on('keyup', '#studSearch', function(){
				
				var query = $(this).val();
				var glevel = 0;
				glevel = $('#glevel').val();
				
				searchStud(query, glevel, 0, 1);
			});

			$(document).on('change', '#glevel', function(){
				glevel = $(this).val();
				query = $('#studSearch').val();
				searchStud(query, glevel, 0, 1);
			});


			//enroll get stud data

			function getinfo(studid, glevel)
			{
				$.ajax({
					url:"{{ route('enrollgetinfo') }}",
					method:'GET',
					data:{
						studid:studid,
						glevel:glevel
					},
					dataType:'json',
					success:function(data)
					{
						$('#sid').text(data.sid);
						$('#sid').attr('data-value', data.studid);
						$('#lrn').text(data.lrn);
						$('#name').text(data.name);
						$('#dateenrolled').text(data.dateenrolled);
						
						$('#glevelmodal').html(data.level);
						$('#sections').html(data.section);
						$('#sy').html(data.syear);
						$('#studstatus').html(data.studstatus);

						console.log('promotedid: ' + data.promotedid);

						if(data.promoteid == 1)
						{
							$('#promotestatus').text('PROMOTED')
							$('#enroll-modal-header').addClass('bg-success');
							$('#enroll-modal-header').removeClass('bg-info');
							$('#enroll-modal-header').removeClass('bg-warning');
							$('#enroll-modal-header').removeClass('bg-primary');
						}
						else if(data.promoteid == 2)
						{
							$('#promotestatus').text('CONDITIONAL')
							$('#enroll-modal-header').addClass('bg-primary');
							$('#enroll-modal-header').removeClass('bg-success');
							$('#enroll-modal-header').removeClass('bg-info');
							$('#enroll-modal-header').removeClass('bg-warning');
						}
						else if(data.promoteid == 3)
						{
							$('#promotestatus').text('RETAINED')
							$('#enroll-modal-header').addClass('bg-warning');
							$('#enroll-modal-header').removeClass('bg-primary');
							$('#enroll-modal-header').removeClass('bg-success');
							$('#enroll-modal-header').removeClass('bg-info');
						}
						else
						{
							$('#enroll-modal-header').addClass('bg-info');	
							$('#enroll-modal-header').removeClass('bg-success');
							$('#enroll-modal-header').removeClass('bg-warning');
							$('#enroll-modal-header').removeClass('bg-primary');
						}



						if(data.sstatus == 0)
						{
							$('#cmdenrollstudent').prop('disabled', false);
						}
						else
						{
							$('#cmdenrollstudent').prop('disabled', true);
						}
						
						if($('#glevelmodal').val() == 14 || $('#glevelmodal').val() == 15)
						{
							$('#sem').html(data.sem);
							$('#strand').html(data.strand);
							$('#block').html(data.block);
							$('#divstrand').show();
							$('#divblock').show();
							$('#divsem').show();
							$('#strand')
						}
						else
						{
							$('#divstrand').hide();
							$('#divblock').hide();
							$('#divsem').hide();
						}

						if(data.dateenrolled == 0)
						{
							$('#dateenrolled').hide();	
						}
						else
						{
							$('#dateenrolled').show();
						}

						validate();
						
					}
				});

			};

			function studdata(studid, curlevelid)
			{

				$('#enroll-modal-header').removeClass('bg-success');
				$('#enroll-modal-header').removeClass('bg-primary');
				$('#enroll-modal-header').removeClass('bg-warning');
				$('#enroll-modal-header').addClass('bg-primary');
				$('#promotestatus').text('');

				$.ajax({
					url:"{{ route('studdata') }}",
					method:'GET',
					data:{
						studid:studid,
						curlevelid:curlevelid
					},
					beforeSend: function(){
						$('#cmdenrollstudent').prop('disabled', true);
					},
					dataType:'json',
					success:function(data)
					{
						$('#sid').text(data.sid);
						$('#sid').attr('data-value', data.studid);
						$('#lrn').text(data.lrn);
						$('#name').text(data.name);
						$('#dateenrolled').text(data.dateenrolled);
						
						$('#glevelmodal').html(data.level);
						$('#sections').html(data.section);
						$('#sy').html(data.syear);
						$('#studstatus').html(data.studstatus);

						$('#cbograntee').val(data.grantee);
						$('#cbomol').val(data.mol);
						$('#cbostudclass').val(data.studclass);
						$('#modalsection').text(data.sectionname);

						if(data.sstatus == 0)
						{
							$('#cmdenrollstudent').prop('disabled', false);
						}
						else
						{
							$('#cmdenrollstudent').prop('disabled', true);
						}
						
						// if($('#glevelmodal').val() == 14 || $('#glevelmodal').val() == 15)
						if(curlevelid == 14 || curlevelid == 15)
						{
							$('#sem').html(data.sem);
							$('#strand').html(data.strand);
							$('#block').html(data.block);
							// console.log(data.strand);
							$('#divstrand').show();
							$('#divblock').show();
							$('#divsem').show();
							$('#divcourse').hide();
							$('#collsched').hide();
							$('#divgrantee').show();
							$('#sections').prop('disabled', false);
							// $('#strand')
						}
						// else if($('#glevelmodal').val() >= 17 && $('#glevelmodal').val() <= 20)
						else if(curlevelid >= 17 && curlevelid <= 20)
						{		
							$('#divstrand').hide();
							$('#divblock').hide();
							$('#divcourse').show();
							$('#collsched').show();
							$('#divgrantee').hide();

							$('#sem').html(data.sem);

							@foreach(App\RegistrarModel::loadcourses() as $course)

								$('#coursemodal').append('<option value="{{$course->id}}">{{$course->courseDesc}}</option>');

							@endforeach
							$('#sections').html(data.sectionlist);

							console.log('level: ' + $('#glevelmodal').val());

							setTimeout(function(){
				              	$('#coursemodal').val(data.courseid)
								$('#coursemodal').trigger('change');
				            }, 1000);

				            $('#sections').val(data.section);

				            $('#nounits').text(data.units);

				            $('#schedlist').html(data.subjlist);
				            $('#promotestatus').text('');

				            $('#sections').prop('disabled', true);
						}
						else
						{
							$('#divstrand').hide();
							$('#divblock').hide();
							$('#divsem').hide();
							$('#divcourse').hide();
							$('#collsched').hide();
							$('#divgrantee').show();
							$('#sections').prop('disabled', false);
						}

						if(data.dateenrolled == 0)
						{
							$('#dateenrolled').hide();	
						}
						else
						{
							$('#dateenrolled').show();
						}



						$('#glevelmodal').val(data.curlevelid);

						getDP(_studid, $('#glevelmodal').val(), $('#filter_sy').val(), $('#filter_sem').val());
						
						validate(curlevelid);
						$('#enrollstud').modal('show');
						
					}
				});
			}

			$(document).on('click', '#cmdenroll', function(){
				$('#paidConfirm').addClass('d-none');
				studid = $(this).attr('data-value');
				levelid = $(this).attr('data-glevel');
				var glevel = '';

				$('#glevelmodal').prop('disabled', false);
				$('#studstatus').prop('disabled', true);
				$('#cmdSave').hide();

				studdata(studid, levelid);

				validate();
				
				

				

				

				

			});


			$(document).on('change', '#strand', function(){
				var strandid = $(this).val();

				$.ajax({
					url:"{{ route('loadblock') }}",
					method: 'GET',
					data:{
						strandid:strandid
					},
					dataType:'json',
					success:function(data)
					{
						$('#block').html(data.block);
					}
				})
			});

			$(document).on('change', '#glevelmodal', function(){
				console.log(studid);
				// getinfo(studid, $(this).val());
				studdata(studid, $(this).val());


			});

			//enroll get stud data		

			//enroll stud

			$(document).on('click', '#cmdenrollstudent', function(){
				var glevel = $('#glevelmodal').children('option:selected').val();
				var section = $('#sections').children('option:selected').val();
				var studid = $('#sid').attr('data-value');
				var sid = $('#sid').text();
				var sy =$('#sy').children('option:selected').val();
				var studstatus = $('#studstatus').children('option:selected').val();
				var strandid = $('#strand').val();
				var blockid = $('#block').val();
				var semid = $('#sem').val();
				var grantee = $('#cbograntee').val();
				var mol = $('#cbomol').val();
				var studclass = $('#cbostudclass').val();
				var courseid = $('#coursemodal').val();
				var sectionname = $('#sections option:selected').text();
				var ee_flag = 1;

				Swal.fire({
					title: 'Enroll Student?',
					text: 'Are you sure you want to enroll' + ' ' + $('#name').text() + '?',
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes!'
        		}).then((result) => {
					if (result.value) {
						if($('#glevelmodal').val()==14 || $('#glevelmodal').val()==15)
						{
							if(countValidation == 1)
							{

								$.ajax({
									url:"{{ route('enrollstud') }}",
									method:'GET',
									beforeSend: function(){
										$('#cmdenrollstudent').prop('disabled', true);
									},

									data:{
										studid:studid,
										sid:sid,
										glevel:glevel,
										section:section,
										sy:sy,
										studstatus:studstatus,
										strandid:strandid,
										blockid:blockid,
										semid:semid,
										grantee:grantee,
										mol:mol,
										studclass:studclass,
										ee_flag:ee_flag
									},
										dataType:'text',
										success:function(data)
										{
											// alert('Saved');

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
											title: 'Student successfully enrolled'
											})

											glevel = $('#glevel').val();
											query = $('#studSearch').val();
											$('#enrollstud').modal('hide')
											searchStud(query, glevel);

										}
								});
							}
							else
							{
								// alert('Please fill all the required fields');
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
								});

								Toast.fire({
								type: 'warning',
								title: 'Please fill all the required fields'
								})
							}
						}
						else if($('#glevelmodal').val()>=17 && $('#glevelmodal').val()<=20)
						{
							console.log(countValidation);
							// if(countValidation == 3 || countValidation == 2)
							if(countValidation == 1)
							{

								$.ajax({
									url:"{{ route('enrollstud') }}",
									method:'GET',
									beforeSend: function(){
										$('#cmdenrollstudent').prop('disabled', true);
									},

									data:{
										studid:studid,
										sid:sid,
										glevel:glevel,
										section:section,
										courseid:courseid,
										sectionname:sectionname,
										sy:sy,
										semid:semid,
										studstatus:studstatus,
										ee_flag:ee_flag
									},
									dataType:'',
									success:function(data)
									{
										// alert('Saved');

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
							              title: 'Student successfully enrolled'
							            })

										glevel = $('#glevel').val();
										query = $('#studSearch').val();
										$('#enrollstud').modal('hide')
										searchStud(query, glevel);

									}
								});
							}
							else
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
					              type: 'warning',
					              title: 'Please fill all the required fields'
					            })
							}
						}
						else
						{
							// if(countValidation <= 2)
							if(countValidation == 1)
							{

								$.ajax({
									url:"{{ route('enrollstud') }}",
									method:'GET',
									beforeSend: function(){
										$('#cmdenrollstudent').prop('disabled', true);
									},
									data:{
										studid:studid,
										sid:sid,
										glevel:glevel,
										section:section,
										sy:sy,
										studstatus:studstatus,
										strandid:strandid,
										blockid:blockid,
										semid:semid,
										grantee:grantee,
										mol:mol,
										studclass:studclass,
										ee_flag:ee_flag
									},
									dataType:'text',
									success:function(data)
									{
										// alert('Saved');

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
										title: 'Student successfully enrolled'
										})

											glevel = $('#glevel').val();
											query = $('#studSearch').val();
											$('#enrollstud').modal('hide')
											searchStud(query, glevel);

									}
								});
							}
							else
							{
								// alert('Please fill all the required fields');
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
								type: 'warning',
								title: 'Please fill all the required fields'
								})
							}
						}

					}
				})
      		
        	});


			//enroll stud


			// validation
			function validate(levelid)
			{
				countValidation = 0;
				
				$('.validation').each(function(){

					
					if($(this).val() != '' || $(this).val() != 0)
					{
						// $(this).attr('class', 'form-control is-valid validation')
						$(this).addClass('is-valid');
						$(this).removeClass('is-invalid');
					}
					else
					{
						// $(this).attr('class', 'form-control is-invalid validation')	
						$(this).removeClass('is-valid');
						$(this).addClass('is-invalid');
						// countValidation +=1;
					}	

					if(levelid == 14 || levelid == 15)
					{
						var vcount = 0;
						$('.sh').each(function(){
							if($(this).hasClass('is-invalid'))
							{
								console.log('sh 0');
								vcount += 1;
							}
						});
					}
					else if(levelid >= 17 && levelid <= 20)
					{
						var vcount = 0;
						$('.college').each(function(){
							if($(this).hasClass('is-invalid'))
							{
								console.log('college 0');
								vcount += 1;
							}
						});	
					}
					else
					{
						var vcount = 0;
						$('.b-ed').each(function(){
							if($(this).hasClass('is-invalid'))
							{
								console.log($(this).attr('id') + ' levelid: ' + levelid);
								vcount += 1;
							}
						})
					}
					console.log('vcount: ' +vcount);
					if(vcount > 0)
					{
						countValidation = 0;
						// $('#cmdenrollstudent').prop('disabled', true);
					}
					else
					{
						countValidation = 1;
						// $('#cmdenrollstudent').prop('disabled', false)
					}
					
				});

				console.log('validation: ' + countValidation);

				// getDP(studid, $('#glevelmodal').val());
			}

			$(document).on('change', '.validation', function(){
				validate($('#glevelmodal').val());
			})
			// validation


			$(document).on('click', '#cmdViewEnrollment', function(){
				var studid = $(this).attr('data-id');
				var glevel = $(this).attr('data-glevel');

				getinfo(studid, glevel);

				$('#glevelmodal').prop('disabled', true);
				$('#studstatus').prop('disabled', false);
				$('#cmdSave').show();
				// $.ajax({
				// 	url:"",
				// 	method:'GET',
				// 	data:{
				// 		studid:studid,
				// 		glevel:glevel
				// 	},
				// 	dataType:'json',
				// 	success:function(data)
				// 	{
				// 		$('#dateenrolled').text(data.dateenrolled);
				// 	}
				// });	
			});


			$(document).on('click', '#cmdSave', function(){
				var studid = $('#sid').attr('data-value');
				var levelid = $('#glevelmodal').val();
				var sectionid = $('#sections').val();
				var syid = $('#sy').val();
				var enrollstatusid = $('#studstatus').val();

				var strand = 0;
				var block = 0;
				var semid = 0;


				if(levelid == 14 || levelid == 15)
				{
					strand = $('#strand').val();
					block = $('#block').val();
					semid = $('#sem').val();
				}

				console.log(studid);
				$.ajax({
					url:"{{ route('saveEnroll') }}",
					method:'GET',
					beforeSend: function(){
						$('#cmdenrollstudent').prop('disabled', true);
					},
					data:{
						studid:studid,
						levelid:levelid,
						sectionid:sectionid,
						syid:syid,
						enrollstatusid:enrollstatusid,
						strand:strand,
						block:block,
						semid:semid
					},
					dataType:'',
					success:function(data)
					{
						
						searchStud($('#studSearch').val());

						const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            
            onOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })

            Toast.fire({
              type: 'success',
              title: 'Saved!'
            });

					}
				});

			});	

			$(document).on('click', '#deleteStud', function(){
				var studid = $(this).attr('data-id');
				Swal.fire({
				  title: 'Are you sure?',
				  text: "You won't be able to revert this!",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
				  if (result.value) {
				  	$.ajax({
						url:"{{ route('deletestud') }}",
						method:'GET',
						beforeSend: function(){
							// $('#cmdenrollstudent').prop('disabled', true);
						},
						data:{
							studid:studid
						},
						dataType:'text',
						success:function(data)
						{
							if(data == 1)
							{
								Swal.fire(
							      'Deleted!',
							      'Student has been deleted.',
							      'success'
							    );

								searchStud();
							}
							else
							{
								Swal.fire(
							      'Warning!',
							      'Student cannot be deleted.',
							      'warning'
							    );
						    	searchStud();
							}
						}
					});
				  }
				})
			});

			$(document).on('click', '#btnsched', function(){
				$('#studsched').modal('show');
			});

			$(document).on('click', '#btn-req', function(){
				var studid = $('#sid').attr('data-value');
				$.ajax({
					url:"{{ route('viewreq') }}",
					method:'GET',
					data:{
						studid:studid
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

			$(document).on('click', '.view-img', function(){
				// $('#modal-viewIMG').modal('show');
				// $('#req-img img').attr('src', $('.req-img', this).attr('src'));
				$('[data-magnify=gallery]').magnify({
					draggable:true,
					resizable:true,
					movable:true,
					title:false,
					modalWidth: 520,
   					modalHeight: 520
				});
			});

			$(document).on('click', '#btnstudpaid', function(){
				$('#modal-studpaid').modal('show');
			});

			$(document).on('mouseenter', '#studpaidlist tr', function(){
				$(this).addClass('bg-primary');
			});

			$(document).on('mouseout', '#studpaidlist tr', function(){
				$(this).removeClass('bg-primary');
			});

			$(document).on('click', '#studpaidlist tr', function(){
				$('#modal-studpaid').modal('hide');
				$('#studSearch').val($(this).attr('data-id'))
				$('#studSearch').trigger('keyup');
			});

			$(document).on('change', '.filter', function(){
				searchStud();
			})

			$(document).on('click', '.enroll', function(){
				_studid = $(this).attr('data-id');

				console.log('_studid: ' + _studid);

				studdata(_studid, $(this).attr('data-level'))
			})

			$(document).on('click', '.resitemized', function(){
				$.ajax({
					url: '{{route('resitemized')}}',
					type: 'GET',
					dataType: '',
					success:function(data)
					{

					}
				});

				
				
			})


		});	
	</script> 
	
	
@endsection

