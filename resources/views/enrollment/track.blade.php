@extends('enrollment.layouts.app')

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Track List</h1> -->
		  <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-file-invoice nav-icon"></i>  -->
            <b>TRACK LIST</b></h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Track List</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
	<section class="content">
		<div class="col-lg-12">
			<div class="main-card mb-3 card">
				<div class="card-body bg-info">	
					<div class="input-group mb-1 float-right col-6 col-lg-4">
        		<input id="trackSearch" type="text" class="form-control" placeholder="Search" onkeyup="this.value = this.value.toUpperCase();">
		        <div class="input-group-append">
		        	<span class="input-group-text"><i class="fas fa-search"></i></span>
		        </div>
		        <div class="input-group-button">
		        	<button id="btnnew" class="btn btn-primary" data-toggle="modal" data-target="#modal-track">New</button>
		        </div>
      		</div>
				</div>
				
				<div class="card-body table-responsive p-0" style="height: 350px">
						{{-- <table class="mb-0 table table table-striped dataTable" role="grid"> --}}
						<table id="example2" class="table table-striped " role="grid" aria-describedby="example2_info">
	            <thead class="bg-warning">
	            <tr>
	                <th>Track Name</th>
	                <th></th>
	                <th></th>
	            </tr>
	            </thead>
	            <tbody id="track_body">

		           
	            </tbody>
						</table>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="dataTables_info col-4" id="info" role="status" aria-live="polite"></div>
						<div class="col-8">
							<div class="float-right" id="studinfo_paginate">
								{{-- <ul class="pagination" id="paginate"> --}}
									
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>  
	</section>
@endsection

@section('modal')
{{-- ENROLLMENT --}}

	<div class="modal fade show" id="modal-track" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Track - <span id="op"></span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">

	        <div class="form-group row">
	        	<label for="trackname" class="col-md-3 col-form-label">Track Name</label>
	        	<div class="col-md-9">
	        		<input id="trackname" type="text" name="" class="form-control">
	        	</div>
	        </div>

	        
        </div>
        <div class="modal-footer justify-content-between"> 
        	{{--  --}}

        	<div class="float-left">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		
        	</div>        	
        	<div class="float-right">
        		<button id="btnsave" type="button" class="btn btn-primary btn-save" data-dismiss="modal" style="width: 90px"><i class="fas fa-save"></i> Save</button>
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

			searchTrack();

			function searchTrack(query = '')
			{
				$.ajax({
					url:"{{ route('searchtrack') }}",
					method:'GET',
					data:{
						query:query
					},
					dataType:'json',
					success:function(data)
					{
						$('#track_body').html(data.output);	
					}
				});
			}

			$(document).on('keyup', '#trackSearch', function(){
				var query = $(this).val();
				console.log(query);
				searchTrack(query);
			});

			$(document).on('click', '#btnnew', function(){
				$('#op').text('NEW');
				$('#trackname').focus();
				$('#btnsave').removeClass('btn-upd');
				$('#btnsave').addClass('btn-save');
				$('#btnsave').text('Save');
				$('#trackname').val('');
			});

			$(document).on('click', '.btn-save', function(){

				var trackname = $('#trackname').val();

				$.ajax({
					url:"{{ route('savetrack') }}",
					method:'GET',
					data:{
						trackname:trackname
					},
					dataType:'',
					success:function(data)
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
			        title: 'Track successfully saved'
			      }); 

			      searchTrack();

					}
				});
			});

			$(document).on('click', '.btn-edit', function(){
				$('#op').text('EDIT');
				$('#modal-track').modal('show');
				$('#btnsave').removeClass('btn-save');
				$('#btnsave').addClass('btn-upd');
				$('#btnsave').text('Update');

				var dataid = $(this).attr('data-id');
				$('#btnsave').attr('data-id', dataid);

				$.ajax({
					url:"{{ route('edittrack') }}",
					method:'GET',
					data:{
						dataid:dataid,
					},
					dataType:'json',
					success:function(data)
					{
						$('#trackname').val(data.trackname);
					}
				});


			});

			$(document).on('click', '.btn-upd', function(){
				var dataid = $(this).attr('data-id');
				var trackname = $('#trackname').val();

				$.ajax({
					url:"{{ route('updatetrack') }}",
					method:'GET',
					data:{
						dataid:dataid,
						trackname:trackname
					},
					dataType:'',
					success:function(data)
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
			        title: 'Track successfully updated'
			      }); 

			      searchTrack();
					}
				});
			});


		})	
	</script>
	
	
@endsection

