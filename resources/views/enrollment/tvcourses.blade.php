@extends('enrollment.layouts.app')

@section('content')
	<section class="content-header">
	    <div class="container-fluid">
	      <div class="row mb-2">
	        <div class="col-sm-6">
	          <!-- <h1>Track List</h1> -->
			  <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
	            <!-- <i class="fa fa-file-invoice nav-icon"></i>  -->
	            <b>TECHNICAL - VOCATIONAL COURSES</b></h4>
	        </div>
	        <div class="col-sm-6">
	          <ol class="breadcrumb float-sm-right">
	            <li class="breadcrumb-item"><a href="/">Home</a></li>
	            <li class="breadcrumb-item active">Technical - Vocational Courses</li>
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
		        		<input id="txtfilter" type="text" class="form-control" placeholder="Search" onkeyup="this.value = this.value.toUpperCase();">
				        <div class="input-group-append">
				        	<span class="input-group-text"><i class="fas fa-search"></i></span>
				        </div>
				        <div class="input-group-button">
				        	<button id="btnnew" class="btn btn-primary" data-toggle="modal" data-target="#modal-course">New</button>
				        </div>
      				</div>
				</div>
				
				<div class="card-body table-responsive p-0" style="height: 350px">
						{{-- <table class="mb-0 table table table-striped dataTable" role="grid"> --}}
					<table id="example2" class="table table-striped " role="grid" aria-describedby="example2_info">
			            <thead class="bg-warning">
			            <tr>
			                <th>DESCRIPTION</th>
			                <th>DURATION</th>
			                <th></th>
			                <th></th>
			            </tr>
			            </thead>
			            <tbody id="course_list">

			            </tbody>
					</table>
				</div>
			</div>
		</div>  
	</section>
@endsection

@section('modal')
{{-- ENROLLMENT --}}

	<div class="modal fade show" id="modal-course" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Course - <span id="op"></span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">

	        <div class="form-group">
	        	<label for="txtdesc" class="">Description</label>
	        	<input id="txtdesc" type="text" name="" class="form-control">
	        	
	        </div>  

	        <div class="form-group">
	        	<label for="txtduration" class="">Duration (Months)</label>
	        	<input id="txtduration" type="number" name="" class="form-control">
	        </div>
	        
        </div>
        <div class="modal-footer justify-content-between"> 

        	<div class="float-left">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		
        	</div>        	
        	<div class="float-right">
        		<button id="btnsave" type="button" class="btn btn-primary btn-save" data-dismiss="modal" style="width: 90px"><i class="fas fa-save"></i> Save</button>
        	</div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('js')
	<script>
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

		document.getElementById("txtdesc").addEventListener("keypress", forceKeyPressUppercase, false);
	</script>
	<script>
		$(document).ready(function(){

			searchCourse();

			function searchCourse(filter = '')
			{
				$.ajax({
					url:"{{ route('tvsearch') }}",
					method:'GET',
					data:{
						filter:filter
					},
					dataType:'json',
					success:function(data)
					{
						$('#course_list').html(data.list);	
					}
				});
			}

			$(document).on('keyup', '#txtfilter', function(){
				var query = $(this).val();
				console.log(query);
				searchCourse(query);
			});

			$(document).on('click', '#btnnew', function(){
				$('#op').text('NEW');
				$('#trackname').focus();
				$('#btnsave').removeClass('btn-upd');
				$('#btnsave').addClass('btn-save');
				$('#btnsave').text('Save');
				$('#txtcode').val('');
				$('#txtdesc').val('');
				$('#txtduration').val('');
			});

			$(document).on('click', '.btn-save', function(){

				var code = $('#txtcode').val();
				var description = $('#txtdesc').val();
				var duration = $('#txtduration').val();

				$.ajax({
					url:"{{ route('saveTVCourse') }}",
					method:'GET',
					data:{
						description:description,
						duration:duration
					},
					dataType:'',
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
						        title: 'Course successfully saved'
						   	}); 

				   			searchCourse();
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
						        title: 'Course already exist.'
						   	}); 
				   		}
					}
				});
			});

			$(document).on('click', '.btn-edit', function(){
				$('#op').text('EDIT');
				$('#btnsave').removeClass('btn-save');
				$('#btnsave').addClass('btn-upd');
				$('#btnsave').text('Update');

				var dataid = $(this).attr('data-id');
				$('#btnsave').attr('data-id', dataid);

				$.ajax({
					url:"{{ route('editTVCourse') }}",
					method:'GET',
					data:{
						dataid:dataid
					},
					dataType:'json',
					success:function(data)
					{
						$('#txtdesc').val(data.description);
						$('#txtduration').val(data.duration);
						$('#modal-course').modal('show');
					}
				});


			});

			$(document).on('click', '.btn-upd', function(){
				var dataid = $(this).attr('data-id');
				var description = $('#txtdesc').val();
				var duration = $('#txtduration').val();

				$.ajax({
					url:"{{ route('updateTVCourse') }}",
					method:'GET',
					data:{
						dataid:dataid,
						description:description,
						duration:duration
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
			        		title: 'Course successfully updated'
		      			}); 

			      		searchCourse();
					}
				});
			});

			$(document).on('click', '.btn-delete', function(){

				var dataid = $(this).attr('data-id');

				Swal.fire({
				  title: 'Delete Course?',
				  text: "",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
				  if (result.value) {

				  	$.ajax({
						url:"{{ route('deleteTVCourse') }}",
						method:'GET',
						data:{
							dataid:dataid,
						},
						dataType:'',
						success:function(data)
						{
							searchCourse();

							Swal.fire(
						      'Deleted!',
						      'Course has been deleted.',
						      'success'
						    );		

				      		
						}
					});
				  }
				})
			})


		})	
	</script>
	
	
	
@endsection

