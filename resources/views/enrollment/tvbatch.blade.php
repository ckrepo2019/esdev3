@extends('enrollment.layouts.app')

@section('content')
	<section class="content-header">
	    <div class="container-fluid">
	      <div class="row mb-2">
	        <div class="col-sm-6">
	          <!-- <h1>Track List</h1> -->
			  <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
	            <!-- <i class="fa fa-file-invoice nav-icon"></i>  -->
	            <b>TECHNICAL - VOCATIONAL BATCHES</b></h4>
	        </div>
	        <div class="col-sm-6">
	          <ol class="breadcrumb float-sm-right">
	            <li class="breadcrumb-item"><a href="/">Home</a></li>
	            <li class="breadcrumb-item active">Technical - Vocational Batches</li>
	          </ol>
	        </div>
	      </div>
	    </div><!-- /.container-fluid -->
  	</section>
	<section class="content">
		<div class="row">
			@php
				$courses = DB::table('tv_courses')
					->where('deleted', 0)
					->get();
			@endphp

			@foreach($courses as $course)
				<div class="col-md-3">
					<div id="{{$course->id}}" class="card h-100 card-courses" style="cursor: pointer">
						<div class="card-body">
							{{$course->description}} ({{$course->duration}} Months)
						</div>
					</div>
				</div>
			@endforeach
		</div> 

		<div class="row mt-3">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped">
						<thead class="bg-warning">
							<tr>
								<th colspan="3">BATCH</th>
								
							</tr>
						</thead>
						<tbody id="batch-list">
							
						</tbody>
						<tfoot>
							<tr>
								<td><button id="btncreatebatch" class="btn btn-sm btn-primary"> Create Batch</button></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div> 
	</section>
@endsection

@section('modal')
{{-- ENROLLMENT --}}

	<div class="modal fade show" id="modal-batch" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title"><span id="coursedesc"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
			<form id="batchdates">
	        <div class="form-group">
	        	<label for="dtstartdate" class="">Start Date</label>
	        	<input id="dtstartdate" type="date" name="" class="form-control">
	        </div>  

	        <div class="form-group">
	        	<label for="dtenddate" class="">End Date</label>
	        	<input id="dtenddate" type="date" name="" class="form-control">
			</div>  
		</form>
        </div>
        <div class="modal-footer justify-content-between"> 

        	<div class="float-left">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		
        	</div>        	
        	<div class="float-right">
        		<button id="btnsave" type="button" class="btn btn-primary btn-save" style="width: 90px"><i class="fas fa-save"></i> Save</button>
        	</div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('js')
	
	<script>
		$(document).ready(function(){

			showcreatebtn();

			function loadbatch(courseid)
			{
				$.ajax({
					url:"{{ route('loadbatch') }}",
					method:'GET',
					data:{
						courseid:courseid
					},
					dataType:'json',
					success:function(data)
					{
						$('#batch-list').html(data.list);	
					}
				});
			}

			$(document).on('keyup', '#txtfilter', function(){
				var query = $(this).val();
				console.log(query);
				searchCourse(query);
			});

			
			function removeSelection()
			{
				$('.card-courses').removeClass('bg-warning');
			}

			function showcreatebtn(courseid = 0)
			{
				if(courseid == 0)
				{
					$('#btncreatebatch').hide();
				}
				else
				{
					$('#btncreatebatch').show();	
					$('#btncreatebatch').attr('data-id', courseid);
				}
			}
			


			$(document).on('click', '.card-courses', function(){
				removeSelection();
				$(this).addClass('bg-warning');

				var courseid = $(this).attr('id');
				var coursedescription = $(this).find('.card-body').text().trim();

				console.log(coursedescription);
				$('#coursedesc').text(coursedescription);

				showcreatebtn(courseid);
				loadbatch(courseid);

				$('#btncreatebatch').attr('data-course', );

			});

			$(document).on('click', '#btncreatebatch', function(){
				$('#modal-batch').modal('show');
				$('#buttondone').empty()
				$('#buttondone').append('<i class="fas fa-save"></i> Save')
				$('#buttondone').attr('id', 'btnsave')
				$('#batchdates')[0].reset() 
			});

			$(document).on('click', '#btnsave', function(){
				var selection = 0;
				$('#dtstartdate').css('border','1px solid #ddd')
				$('#dtenddate').css('border','1px solid #ddd')
				if( $('#dtstartdate').val().length == 0)
				{
					selection+=1;
					$('#dtstartdate').css('border','1px solid red')
				}
				if( $('#dtenddate').val().length == 0)
				{
					selection+=1;
					$('#dtenddate').css('border','1px solid red')
				}
				if(selection == 0)
				{
					var thiselement = $(this);
					var startdate = $('#dtstartdate').val();
					var enddate = $('#dtenddate').val();
					var courseid = $('#btncreatebatch').attr('data-id');

					$.ajax({
						url:"{{ route('createbatch') }}",
						method:'GET',
						data:{
							courseid:courseid,
							startdate:startdate,
							enddate:enddate
						},
						dataType:'json',
						success:function(data)
						{
							if(data == 1)		
							{
								const Toast = Swal.mixin({
									toast: true,
									position: 'top',
									showConfirmButton: false,
									timer: 3000,
									onOpen: (toast) => {
									toast.addEventListener('mouseenter', Swal.stopTimer)
									toast.addEventListener('mouseleave', Swal.resumeTimer)
									}
								});

								Toast.fire({
									type: 'success',
									title: 'Batch successfully added'
								});

								loadbatch($('#btncreatebatch').attr('data-id'));
								thiselement.text('Done')
								thiselement.attr('data-dismiss','modal')
								thiselement.attr('id','buttondone')
							}
							else
							{
								const Toast = Swal.mixin({
									toast: true,
									position: 'top',
									showConfirmButton: false,
									timer: 3000,
									onOpen: (toast) => {
									toast.addEventListener('mouseenter', Swal.stopTimer)
									toast.addEventListener('mouseleave', Swal.resumeTimer)
									}
								});

								Toast.fire({
									type: 'warning',
									title: 'Batch already exist.'
								});
							}
						}
					});
				}
			});

			$(document).on('click', '.btn-activate', function(){
				var batchid = $(this).attr('data-id');
				var courseid = $('#btncreatebatch').attr('data-id');
				console.log(courseid);
				$.ajax({
					url:"{{ route('activatebatch') }}",
					method:'GET',
					data:{
						batchid:batchid,
						courseid:courseid
					},
					dataType:'',
					success:function(data)
					{
						loadbatch(courseid);
					}
				});
			})


		})	
	</script>
	
	
	
@endsection

