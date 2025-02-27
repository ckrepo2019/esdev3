@extends('enrollment.layouts.app')
@section('content')
<style>
    .card {
        box-shadow:  0 .5rem 1rem rgba(0,0,0,.15) !important;;
        border: none !important;
    }
    td, th {
        padding: 2px !important;
    }
</style>
	<section class="content-header">
	    <div class="container-fluid">
	      <div class="row mb-2">
	        <div class="col-sm-7">
	          <!-- <h1>Track List</h1> -->
			  <h2>
	            <!-- <i class="fa fa-file-invoice nav-icon"></i>  -->
	            <b>TECHNICAL - VOCATIONAL COURSES</b></h2>
	        </div>
	        <div class="col-sm-5">
	          <ol class="breadcrumb float-sm-right">
	            <li class="breadcrumb-item"><a href="/">Home</a></li>
	            <li class="breadcrumb-item active">Technical - Vocational Courses</li>
	          </ol>
	        </div>
	      </div>
	    </div><!-- /.container-fluid -->
  	</section>
	<section class="content">
        <div class="card shadow">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12 text-right">                        
				        	<button id="btnnew" class="btn btn-primary" data-toggle="modal" data-target="#modal-course"><i class="fa fa-plus"></i> Add New</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4" style="height: 600px; overflow: scroll;">
                        @if(count($courses)>0)
                        <input type="text" class="form-control mb-2" id="input-filter-courses" placeholder="Search Course"/>
                        @endif
                        <label>Courses</label>
                        @if(count($courses)>0)
                        @foreach($courses as $course)
                        {{-- <div style="border: 1px solid green; border-radius: 5px;" class="mb-2 p-2"> --}}
                            <button type="button" class="btn btn-outline-success btn-block text-left btn-each-course" data-id="{{$course->id}}" data-string="{{$course->description}}<">{{$course->description}}</button>
                            {{-- {{$course->description}} --}}
                        {{-- </div> --}}
                        @endforeach
                        @endif
                    </div>
                    <div class="col-md-8" style="height: 600px; overflow: scroll;" id="container-courseinfo">
                    </div>
                </div>
            </div>
            {{-- <div id="tv-courses-container">
                @if(count($courses)>0)
                <div class="card-body">
					<table id="example2" class="table table-hover">
			            <thead>
			            <tr>
			                <th>Course</th>
			                <th>Duration</th>
			                <th></th>
			                <th></th>
			            </tr>
			            </thead>
			            <tbody>
                            @foreach($courses as $course)
                                <tr>
                                    <td>{{$course->description}}</td>
                                    <td>{{$course->duration}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
			            </tbody>
					</table>
                </div>
                @endif
            </div> --}}
        </div>
		{{-- <div class="col-lg-12">
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
		</div>   --}}
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
            $("#input-filter-courses").on("keyup", function() {
                var input = $(this).val().toUpperCase();
                var visibleCards = 0;
                var hiddenCards = 0;

                $(".container").append($("<div class='card-group card-group-filter'></div>"));


                $(".btn-each-course").each(function() {
                    if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                    $(".card-group.card-group-filter:first-of-type").append($(this));
                    $(this).hide();
                    hiddenCards++;

                    } else {

                    $(".card-group.card-group-filter:last-of-type").prepend($(this));
                    $(this).show();
                    visibleCards++;

                    if (((visibleCards % 4) == 0)) {
                        $(".container").append($("<div class='card-group card-group-filter'></div>"));
                    }
                    }
                });

            });

            $('#example2').DataTable({
                "paging": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            })
			var courseid;
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
						// $('#batch-list').html(data.list);	
					}
				});
			}
            $('.btn-each-course').on('click', function(){
                var courseid = $(this).attr('data-id');
                $('.btn-each-course').removeClass('btn-success')
                $('.btn-each-course').addClass('btn-outline-success')
                $(this).removeClass('btn-outline-success')
                $(this).addClass('btn-success')
				$.ajax({
					url:"{{ route('tvv2courses') }}",
					method:'GET',
					data:{
						action:'getcourseinfo',
                        courseid: courseid
					},
					success:function(data)
					{
						$('#container-courseinfo').empty();	
						$('#container-courseinfo').append(data);	
					}
				});
            })
            $(document).on('click', '#btn-update-courseinfo', function(){
                var courseid = $(this).attr('data-id');
                var coursetitle  = $('#input-course-title').val()
                var courseduration   = $('#input-course-duration').val()
                var validation = 0;
                if(coursetitle.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $('#input-course-title').css('border','1px solid red');
                    toastr.warning('Course Title is empty!')
                    validation+=1;
                }
                if(courseduration.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $('#input-course-duration').css('border','1px solid red');
                    toastr.warning('Course Duration is empty!')
                    validation+=1;
                }
                if(validation == 0)
                {
                    $.ajax({
                        url:"{{ route('tvv2courses') }}",
                        method:'GET',
                        data:{
                            action:'updatecourseinfo',
                            courseid: courseid,
                            coursetitle: coursetitle,
                            courseduration: courseduration
                        },
                        success:function(data)
                        {
                            if(data == 1)
                            {
                                toastr.warning('Course Info updated successfully!')
                                $('.btn-each-course[data-id="'+courseid+'"]').text(coursetitle)
                            }
                        }
                    });
                }
            })
            $(document).on('click','#btn-delete-courseinfo', function(){
                var courseid = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure you want to delete this batch?',
                    html:
                        "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url:"{{ route('tvv2courses') }}",
                            type:"GET",
                            dataType:"json",
                            data:{
                                action : 'coursedelete',
                                courseid: courseid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                
                                if(data == 1)
                                {
                                    toastr.success('Course deleted successfully!')
                                    window.location.reload()
                                }

                            }
                        })
                    }
                })
            })

            $(document).on('click', '#btncreatebatch', function(){
                $('#modal-batch').modal('show');
                $('#buttondone').empty()
                $('#buttondone').append('<i class="fas fa-save"></i> Save')
                $('#buttondone').attr('id', 'btnsave')
                $('#batchdates')[0].reset() 
            });
            
			$(document).on('click', '#btn-save-batch', function(){
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
                    var courseid = $(this).attr('data-id');

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
                                toastr.success('Batch created successfully!')
                                $('.btn-close-modal').click()
                                $('.btn-each-course[data-id="'+courseid+'"]').click()
							}
							else
							{
                                toastr.error('Batch already exists!')
							}
						}
					});
				}
			});
            $(document).on('keydown','.input-startdate', function(e){
                e.preventDefault();
            })
            $(document).on('keydown','.input-enddate', function(e){
                e.preventDefault();
            })
            $(document).on('click', '.btn-edit-batch', function(){
                var courseid = $(this).attr('data-courseid');
                var batchid = $(this).attr('data-id');
                var thistr = $(this).closest('tr');
                var startdate = thistr.find('.input-startdate').val()
                var enddate = thistr.find('.input-enddate').val()
                $.ajax({
                    url:"{{ route('tvv2batches') }}",
                    type:"GET",
                    dataType:"json",
                    data:{
                        action : 'batchedit',
                        batchid: batchid,
                        startdate: startdate,
                        enddate: enddate
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    success: function(data){
                        
                        if(data == 1)
                        {
                            toastr.success('Batch updated successfully!')
                            $('.btn-each-course[data-id="'+courseid+'"]').click()
                        }

                    }
                })
            })
            $(document).on('click','.btn-delete-batch', function(){
                var courseid = $(this).attr('data-courseid');
                var batchid = $(this).attr('data-id');
                var thistr = $(this).closest('tr');
                Swal.fire({
                    title: 'Are you sure you want to delete this batch?',
                    html:
                        "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url:"{{ route('tvv2batches') }}",
                            type:"GET",
                            dataType:"json",
                            data:{
                                action : 'batchdelete',
                                batchid: batchid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                
                                if(data == 1)
                                {
                                    toastr.success('Batch deleted successfully!')
                                    $('.btn-each-course[data-id="'+courseid+'"]').click()
                                }

                            }
                        })
                    }
                })
            })
            $(document).on('click','.btn-batch-activation', function(){
                var courseid = $(this).attr('data-courseid');
                var batchid = $(this).attr('data-id');
                var batchstatus = $(this).attr('data-active');
                if(batchstatus == 1)
                {
                    var newstatus = 0;
                    var textstatus = 'deactivate';
                }else{
                    var newstatus = 1;
                    var textstatus = 'activate';
                }
                Swal.fire({
                    title: 'Are you sure you want to '+textstatus+' this batch?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, '+textstatus+' it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url:"{{ route('tvv2batches') }}",
                            type:"GET",
                            dataType:"json",
                            data:{
                                action : 'batchupdateactivation',
                                newstatus: newstatus,
                                batchid: batchid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                
                                if(data == 1)
                                {
                                    toastr.success('Batch '+textstatus+'d successfully!')
                                    $('.btn-each-course[data-id="'+courseid+'"]').click()
                                }

                            }
                        })
                    }
                })
            })
			// searchCourse();

			// function searchCourse(filter = '')
			// {
			// 	$.ajax({
			// 		url:"{{ route('tvsearch') }}",
			// 		method:'GET',
			// 		data:{
			// 			filter:filter
			// 		},
			// 		dataType:'json',
			// 		success:function(data)
			// 		{
			// 			$('#course_list').html(data.list);	
			// 		}
			// 	});
			// }

			// $(document).on('keyup', '#txtfilter', function(){
			// 	var query = $(this).val();
			// 	console.log(query);
			// 	searchCourse(query);
			// });

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

							window.location.reload()
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

