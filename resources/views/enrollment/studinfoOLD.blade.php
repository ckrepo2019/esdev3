@extends('enrollment.layouts.app')

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Student Information</h1> -->
		  <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-file-invoice nav-icon"></i>  -->
            <b>STUDENT INFORMATION</b></h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Student Information</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
	<section class="content">
		<div class="col-lg-12">
			<div class="main-card mb-3 card">
				<div class="card-body bg-info">	
					<div class="input-group mb-1 float-right col-10 col-lg-7">
						<select id="glevel" class="form-control" >
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
		        	<span class="input-group-text"><i class="fas fa-search"></i></span>
		        </div>
		        <div class="input-group-button">
		        	<button class="btn btn-primary" onclick="window.location.href='{{route('studentcreate')}}'">New Student</button>
		        </div>
		      </div>
				</div>
				
				<div class="card-body">
					<div class="table-responsive">
						{{-- <table class="mb-0 table table table-striped dataTable" role="grid"> --}}
						<table id="example2" class="table table-striped dataTable" role="grid" aria-describedby="example2_info">
	            <thead class="bg-warning">
	            <tr>
                <th>ID No.</th>
                <th>Student Name</th>
                <th>Gender</th>
                <th>Grade Level</th>
                <th>Section</th>
                <th>Grantee</th>
                <th>Status</th>
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
		</div>  
	</section>
@endsection

@section('modal')
{{-- ENROLLMENT --}}

	<div class="modal fade show" id="enrollstud" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
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
          	

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            {{-- <span aria-hidden="true">×</span> --}}
          </button>
        </div>
        <div class="modal-body">

	        <div class="row">
	        	<div class="col-3">
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
	        	<div class="col-3">
	        		<h5>NAME :</h5>	
	        	</div>
	        	<div class="col-7">
	        		<h5 id="name"></h5>
	        	</div>
	        </div>

	        <div class="row">
	        	<div class="col-3">
	        		<h5>Date Enrolled :</h5>	
	        	</div>
	        	<div class="col-7">
	        		<h5 id="dateenrolled"></h5>
	        	</div>
	        </div>

	        <hr>

	        <div class="row">
	        	<div class="col-12">
		        	<div class="form-group row">
		        		<label for="glevelmodal" class="col-3">Grade Level</label>
		        		<div class="col-9">
			        		<select id="glevelmodal" class="form-control validation">
			        			
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
			        		<select id="sections" class="form-control validation selectpicker">
			        			<option selected=""></option>
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
			        		<select id="strand" class="form-control validation selectpicker">
			        			<option selected=""></option>
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
			        		<select id="block" class="form-control validation selectpicker">
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
			        		<select id="sem" class="form-control validation selectpicker">
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
			        		<select id="sy" class="form-control validation">
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
			        		<select id="studstatus" class="form-control validation">
			        			<option selected="">Enrolled</option>
		        			</select>
		        		</div>
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


  


@endsection

@section('js')
	<script>
		$(document).ready(function(){
			var skip = 0;
			var studid='';
			var countValidation = 0;
			var gRun = 0;
			
			searchStud()

			
			function searchStud()
			{
				glevel = $('#glevel').val();
				query = $('#studSearch').val();
				var take = 10;
				$.ajax({
					url:"{{ route('studentsearch') }}",
					method:'GET',
					data:{
						query:query,
						glevel:glevel, 
						take:take,
						skip:skip,
					},
					dataType:'json',
					success:function(data)
					{
						$('#studlist_body').html(data.output);	
						$('#data-container').attr('data-value', data.recCount);
						paginate(data.recCount);
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
							url:"{{ route('studentsearch') }}",
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

			function getDP(studid, levelid)
			{
				$.ajax({
					url:"{{ route('getDP') }}",
					method: 'GET',
					data:{
						studid:studid,
						levelid:levelid
					},
					dataType:'json',
					success:function(data)
					{
						console.log('checkDP ' + data);
						if(data == 1)
						{
							$('#paidConfirm').removeClass('d-none');
							$('#cmdenrollstudent').prop('disabled', false);
						}
						else
						{
							$('#paidConfirm').addClass('d-none');	
							$('#cmdenrollstudent').prop('disabled', true);
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

						if(data.promoteid == 1)
						{
							$('#promotestatus').text('PROMOTED')
							$('#enroll-modal-header').addClass('bg-success');
						}
						else if(data.promoteid == 2)
						{
							$('#promotestatus').text('CONDITIONAL')
							$('#enroll-modal-header').addClass('bg-primary');	
						}
						else if(data.promoteid == 3)
						{
							$('#promotestatus').text('RETAINED')
							$('#enroll-modal-header').addClass('bg-warning');
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

			function studdata(studid)
			{

				$('#enroll-modal-header').removeClass('bg-success');
				$('#enroll-modal-header').removeClass('bg-primary');
				$('#enroll-modal-header').removeClass('bg-warning');
				$('#enroll-modal-header').addClass('bg-light');
				$('#promotestatus').text('');

				$.ajax({
					url:"{{ route('studdata') }}",
					method:'GET',
					data:{
						studid:studid,
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
			}

			$(document).on('click', '#cmdenroll', function(){
				$('#paidConfirm').addClass('d-none');
				studid = $(this).attr('data-value');
				var glevel = '';

				$('#glevelmodal').prop('disabled', false);
				$('#studstatus').prop('disabled', true);
				$('#cmdSave').hide();

				studdata(studid);
				
				validate();

				getDP(studid, $('glevelmodal').val());

				

				

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
				getinfo(studid, $(this).val());


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
				console.log(countValidation);
				
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
							if(countValidation == 0)
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
										semid:semid
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
						else
						{
							if(countValidation <= 3)
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
										semid:semid
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
        });


				
			});


			//enroll stud


			// validation
			function validate()
			{
				countValidation = 0;
				$('.validation').each(function(){

					
					if($(this).val() != '')
					{
						$(this).attr('class', 'form-control is-valid validation')
					}
					else
					{
						$(this).attr('class', 'form-control is-invalid validation')	
						countValidation +=1;
					}	
					
				});

				console.log(countValidation);

				getDP(studid, $('glevelmodal').val());
			}

			$(document).on('change', '.validation', function(){
				validate();
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


		});	
	</script> 
	
	
@endsection

