@extends('enrollment.layouts.app')

@section('content')
<style>
	.modal-dialog,
.modal-content {
    /* 80% of window height */
     max-height: calc(121vh - 200px);
    overflow-y: auto;
}


</style>
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Special Class</h1> -->
		  <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-file-invoice nav-icon"></i>  -->
            <b>SPECIAL CLASS</b></h4>
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
					<div class="input-group mb-1 float-right col-12">

						<select id="glevel" class="form-control glevellist h-form">
							
						</select>
						&nbsp;
						<select id="sylist" class="form-control syearlist h-form">
							<option value="0" selected="">School Year</option>
						</select>
						&nbsp;
						<select id="semlist" class="form-control h-form semlist" disabled="">
							<option value="0" selected="">Semester</option>
						</select>
						&nbsp;
		        <input id="studSearch" type="text" class="form-control h-form" placeholder="Search" onkeyup="this.value = this.value.toUpperCase();">
		        <div id="btn-search" class="input-group-append">
		        	<span class="input-group-text"><i class="fas fa-search"></i></span>
		        </div>
		        &nbsp;
		        <div class="input-group-button">
		        	<button id="btn-spclass-new" class="btn btn-primary" data-target="#modal-spclass-new" data-toggle="modal">New</button>
		        </div>
		      </div>
				</div>
				
				<div class="card-body">
					<div class="table-responsive">
						{{-- <table class="mb-0 table table table-striped dataTable" role="grid"> --}}
						<table id="example2" class="table table-striped dataTable" role="grid" aria-describedby="">
	            <thead class="bg-warning">
	            <tr>
                <th>ID No.</th>
                <th>Student Name</th>
                <th>Grade Level</th>
                <th>Semester</th>
                <th>School Year</th>
                <th></th>
	            </tr>
	            </thead>
	            <tbody id="spclass_body">

		           
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

	<div class="modal fade show" id="modal-spclass-new" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Special Class - New</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
	        <div class="row">
	        	<div class="col-12">
		        	<div class="form-group row">
		        		<label for="stud-new" class="col-3">Student</label>
		        		<div class="col-9">
			        		<select id="studNew" class="form-control select2bs4 vldn stud-list">
			        			
		        			</select>
		        		</div>
		        	</div>
		        	<div class="form-group row">
		        		<label for="glevel-new" class="col-3">Grade Level</label>
		        		<div class="col-9">
			        		<select id="modallevelnew" class="form-control glevellist vldn">
			        			
		        			</select>
		        		</div>
		        	</div>

		        	<div class="form-group row">
		        		<label for="sy-new" class="col-3">School Year</label>
		        		<div class="col-9">
			        		<select id="modalsynew" class="form-control syearlist vldn">
			        			
		        			</select>
		        		</div>
		        	</div>

		        	<div class="form-group row">
		        		<label for="sem-new" class="col-3">Semester</label>
		        		<div class="col-9">
			        		<select id="modalsemnew" class="form-control semlist vldn">
			        			
		        			</select>
		        		</div>
		        	</div>

	        		<div class="row">
	        			<div class="col-10"></div>	
	        			<div class="col-2 mb-1">
	        				<button id="addDetail" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modal-class" disabled="r">
	        					<i class="fa fa-plus" aria-hidden="true"></i>
	        				</button>
	        			</div>
	        		</div>
	        		<hr>
	        		<div id="spdetail" class="row">
	        			<div class="table-responsive">
	        				<table class="table table-striped">
	        					<thead>
	        						<tr>
	        							<th>SUBJECT</th>
	        							<th>TEACHER</th>
	        						</tr>
	        						<tbody id="detailList">
	        							
	        						</tbody>
	        					</thead>
	        				</table>
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
        		<button id="save-spclass" type="button" class="btn btn-primary" data-dismiss="modal" style="width: 90px"><i class="fas fa-save"></i> Save</button>
        	</div>
        </div>
      </div>
    </div>
      <!-- /.modal-content -->
  </div>
    <!-- /.modal-dialog -->

  <div class="modal fade show" id="modal-spclass-edit" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg" >
      <div class="modal-content" >
        <div class="modal-header">
          <h4 class="modal-title">Special Class - Update</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body" style="overflow-y: auto">
	        <div class="row">
	        	<div class="col-12">
		        	<div class="form-group row">
		        		<label for="studedit" class="col-3">Student</label>
		        		<div class="col-9">
			        		<select id="studedit" class="form-control select2bs4 vldn stud-list" disabled="">
			        			
		        			</select>
		        		</div>
		        	</div>
		        	<div class="form-group row">
		        		<label for="glevel-edit" class="col-3">Grade Level</label>
		        		<div class="col-9">
			        		<select id="modalleveledit" class="form-control glevellist vldn">
			        			
		        			</select>
		        		</div>
		        	</div>

		        	<div class="form-group row">
		        		<label for="sy-new" class="col-3">School Year</label>
		        		<div class="col-9">
			        		<select id="modalsyedit" class="form-control syearlist vldn" disabled="">
			        			
		        			</select>
		        		</div>
		        	</div>

		        	<div class="form-group row">
		        		<label for="sem-new" class="col-3">Semester</label>
		        		<div class="col-9">
			        		<select id="modalsemedit" class="form-control semlist vldn" disabled="">
			        			
		        			</select>
		        		</div>
		        	</div>

	        		<div class="row">
	        			<div class="col-10"></div>	
	        			<div class="col-2 mb-1">
	        				<button id="addDetail-update" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modal-class-update">
	        					<i class="fa fa-plus" aria-hidden="true"></i>
	        				</button>
	        			</div>
	        		</div>
	        		<hr>
	        		<div id="spdetail" class="row">
	        			<div class="table-responsive">
	        				<table class="table table-striped">
	        					<thead>
	        						<tr>
	        							<th>SUBJECT</th>
	        							<th>TEACHER</th>
	        						</tr>
	        						<tbody id="detailList-edit" style="cursor: pointer;">
	        							
	        						</tbody>
	        					</thead>
	        				</table>
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
        		<button id="update-spclass" type="button" class="btn btn-primary" data-dismiss="modal" style="width: 90px"><i class="fas fa-save"></i> Save</button>
        	</div>
        </div>
      </div>
    </div>
      <!-- /.modal-content -->
  </div>
    <!-- /.modal-dialog -->


 	<div class="modal fade show" id="modal-class" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md mt-5">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Class - Add</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
	        <div class="row">
	        	<div class="col-12">
		        	<div class="form-group">
		        		<label for="stud-new" class="col-4">Subject</label>
		        		<div class="col-12">
			        		<select id="cbosubject" class="form-control select2bs4">
			        			
		        			</select>
		        		</div>
		        	</div>
		        	<div class="form-group">
		        		<label for="glevel-new" class="col-4">Teacher</label>
		        		<div class="col-12">
			        		<select id="cboteacher" class="form-control select2bs4">
			        			
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
        		<button id="cmdDetailAdd" type="button" class="btn btn-primary" data-dismiss="modal" style="width: 90px"><i class="far fa-plus-square mr-2"></i>Add</button>
        	</div>
        </div>
      </div>
    </div>
      <!-- /.modal-content -->
  </div>
    <!-- /.modal-dialog -->

  <div class="modal fade show" id="modal-class-update" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md mt-5">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Class - Update</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
	        <div class="row">
	        	<div class="col-12">
		        	<div class="form-group">
		        		<label for="stud-new" class="col-4">Subject</label>
		        		<div class="col-12">
			        		<select id="cbosubject-edit" class="form-control select2bs4">
			        			
		        			</select>
		        		</div>
		        	</div>
		        	<div class="form-group">
		        		<label for="glevel-new" class="col-4">Teacher</label>
		        		<div class="col-12">
			        		<select id="cboteacher-edit" class="form-control select2bs4">
			        			
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
        	</div>        	
        	<div class="float-right">
        		<button id="cmdDetaildelete" type="button" class="btn btn-danger" data-id="" data-dismiss="modal" style="width: 90px">Delete</button>
        		<button id="cmdDetailupdate" type="button" class="btn btn-primary" data-id="" data-dismiss="modal" style="width: 90px">Add</button>
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
			studsearch();
			loadList();
			loadstud();

			function loadstud()
			{
				$.ajax({
						url:"loadStud",
						method:'GET',
						data:{
							
						},
						dataType:'json',
						success:function(data)
						{
							$('.stud-list').html(data.studlist);
							$('.stud-list').val('');
							$('modallevelnew option:eq(0)').prop('selected', true);
						}
					});	
			}

			$('.select2bs4').select2({
     	 theme: 'bootstrap4'
    	});

			function loadList()
			{
				$.ajax({
						url:"LoadLists",
						method:'GET',
						data:{
							
						},
						dataType:'json',
						success:function(data)
						{

							console.log(data.semlist);
							$('.glevellist').html(data.glevelList);
							$('.syearlist').html(data.sylist);
							$('.semlist').html(data.semlist);
						}
					});		
			}
				
			function studsearch()
			{
				var sval = $('#studSearch').val();
				var levelid = $('#glevel').val();
				var syid = $('#sylist').val();
				var semid = $('#semlist').val();

				$.ajax({
						url:"spsearch",
						method:'GET',
						data:{
							sval:sval,
							levelid:levelid,
							syid:syid,
							semid:semid
						},
						dataType:'json',
						success:function(data)
						{
							$('#spclass_body').html(data.spList);
						}
					});	
			}

			$(document).on('change', '.h-form', function(){

				if($('#glevel').val() == 14 || $('#glevel').val() == 15)
				{
					$('#semlist').prop('disabled', false);
				}
				else
				{
					$('#semlist').prop('disabled', true);	
				}

				studsearch();
			});

			$(document).on('click', '#btn-search', function(){
				studsearch();
			});

			


			function validateEntry()
			{
				console.log($('#studNew').val());
				if($('#studNew').val() != null)
    		{
    			if($('#modallevelnew').val() != 0)
    			{
    				if($('#modalsynew').val() != '' || $('#modalsynew').val() != 0)
    				{
    					if($('#modallevelnew').val() == 14 || $('#modallevelnew').val() == 15)
    					{
    						if($('#modalsemnew').val() != '' || $('#modalsemnew').val() != 0)
    						{
    							$('#addDetail').prop('disabled', false);								
    						}
    						else
    						{
    							$('#addDetail').prop('disabled', true);						
    						}
    					}
    					else
    					{
    						$('#addDetail').prop('disabled', false);						
    					}
    				}
    				else
    				{
    					$('#addDetail').prop('disabled', true);				
    				}
    			}
    			else
    			{
    				$('#addDetail').prop('disabled', true);		
    			}
    		}
    		else
    		{
    			$('#addDetail').prop('disabled', true);
    		}
			}

			function loadDetailedit()
			{
				glevel = $('#modalleveledit').val();

				$.ajax({
					url:"loadDetail",
					method:'GET',
					data:{
						glevel:glevel
					},
					dataType:'json',
					success:function(data)
					{
						$('#cbosubject-edit').html(data.students);
						$('#cboteacher-edit').html(data.teachers);
						$('#cbosubject-edit').val('');
						$('#cboteacher-edit').val('');
					}
				});
			}

			function loadDtail(studid, levelid, syid, semid, selector)
			{
				$.ajax({
					url:"loadDtail",
					method:'GET',
					data:{
						studid:studid,
						levelid:levelid,
						syid:syid,
						semid:semid
					},
					dataType:'json',
					success:function(data)
					{
						$(selector).html(data.dTail);
					}
				});
			}


			$(document).on('change', '.vldn', function(){
				validateEntry();
			})

    	$(document).on('click', '#btn-spclass-new', function(){
    		$('#studNew').prop('disabled', false);
    		$('#modalsynew').prop('disbaled', false);
    		$('modalsemnew').val('');
    	});

    	$(document).on('click', '#addDetail', function(){
				glevel = $('#modallevelnew').val();

				$.ajax({
					url:"loadDetail",
					method:'GET',
					data:{
						glevel:glevel
					},
					dataType:'json',
					success:function(data)
					{
						$('#cbosubject').html(data.students);
						$('#cboteacher').html(data.teachers);
						$('#cbosubject').val('');
						$('#cboteacher').val('');
					}
				});	

    	});

    	$(document).on('click', '#addDetail-update', function(){
				glevel = $('#modalleveledit').val();
				$('#cmdDetailupdate').text('Add');

				$.ajax({
						url:"loadDetail",
						method:'GET',
						data:{
							glevel:glevel
						},
						dataType:'json',
						success:function(data)
						{
							console.log(data);
							$('#cbosubject-edit').html(data.students);
							$('#cboteacher-edit').html(data.teachers);
							$('#cbosubject-edit').val('');
							$('#cboteacher-edit').val('');
						}
					});	

    	});

    	$(document).on('click', '#cmdDetailAdd', function(){
    		var subjid = $('#cbosubject').val();
    		var teacherid= $('#cboteacher').val();
    		var studid = $('#studNew').val();
    		var levelid = $('#modallevelnew').val();
    		var syid = $('#modalsynew').val();
    		var semid = $('modalsemnew').val();

    		
    		$.ajax({
						url:"appendDetail",
						method:'GET',
						data:{
							subjid:subjid,
							teacherid:teacherid,
							studid:studid,
							levelid:levelid,
							semid:semid,
							syid:syid
						},
						dataType:'json',
						success:function(data)
						{
							$('#detailList').html(data.detail);
							$('#studNew').prop('disabled', true);
							
							$('#modalsynew').prop('disabled', true);
							$('#modalsemnew').prop('disabled', true);
						}
				});	
    	});

    	$(document).on('click', '#cmdDetailupdate', function(){
    		var subjid = $('#cbosubject-edit').val();
    		var teacherid= $('#cboteacher-edit').val();
    		var studid = $('#studedit').val();
    		var levelid = $('#modalleveledit').val();
    		var syid = $('#modalsyedit').val();
    		var semid = $('modalsemedit').val();
    		var dataid = $(this).attr('data-id');
    		console.log(dataid);

    		if(dataid == '')
    		{

    			console.log('blank');
	    		$.ajax({
							url:"appendDetail",
							method:'GET',
							data:{
								subjid:subjid,
								teacherid:teacherid,
								studid:studid,
								levelid:levelid,
								semid:semid,
								syid:syid
							},
							dataType:'json',
							success:function(data)
							{
								loadDtail(studid, levelid, syid, semid, '#detailList-edit')
								$('#studedit').prop('disabled', true);
								
								$('#modalsyedit').prop('disabled', true);
								$('#modalsemedit').prop('disabled', true);
								// $('#detailList-edit').html(data.)
							}
					});	
	    	}
	    	else
	    	{
	    		console.log('val');
	    		$.ajax({
							url:"updateDetail",
							method:'GET',
							data:{
								dataid:dataid,
								subjid:subjid,
								teacherid:teacherid,
								studid:studid,
								semid:semid,
								syid:syid
							},
							dataType:'json',
							success:function(data)
							{
								// $('#detailList-edit').html(data.detail);
								loadDtail(studid, levelid, syid, semid, '#detailList-edit')
								$('#studedit').prop('disabled', true);
								
								$('#modalsyedit').prop('disabled', true);
								$('#modalsemedit').prop('disabled', true);
							}
					});	
	    	}
    	});

    	$(document).on('click', '#save-spclass', function(){
    		var studid = $('#studNew').val();
    		var levelid = $('#modallevelnew').val();
    		var syid = $('#modalsynew').val();
    		var semid = $('modalsemnew').val();


    		$.ajax({
					url:"savespClass",
					method:'GET',
					data:{
						studid:studid,
						levelid:levelid,
						semid:semid,
						syid:syid
					},
					dataType:'',
					success:function(data)
					{
						studsearch();
					}
				});	
  		});

  		$(document).on('click', '#update-spclass', function(){
    		var studid = $('#studedit').val();
    		var levelid = $('#modalleveledit').val();
    		var syid = $('#modalsyedit').val();
    		var semid = $('modalsemedit').val();


    		$.ajax({
					url:"savespClass",
					method:'GET',
					data:{
						studid:studid,
						levelid:levelid,
						semid:semid,
						syid:syid
					},
					dataType:'',
					success:function(data)
					{
						studsearch();
					}
				});	
  		});

  		$(document).on('click', '.btn-edit', function(){

  			studid = $(this).attr('s-id');
  			levelid = $(this).attr('l-id');
  			syid = $(this).attr('sy-id');
  			semid = $(this).attr('sem-id');

  			$.ajax({
					url:"editspClass",
					method:'GET',
					data:{
						studid:studid,
						levelid:levelid,
						semid:semid,
						syid:syid
					},
					dataType:'json',
					success:function(data)
					{
						$('#studedit').val(data.studid);
						$('#studedit').trigger('change');
						$('#modalleveledit').val(data.levelid);
						$('#modalsyedit').val(data.syid);
						$('#modalsemedit').val(data.semid);
						$('#detailList-edit').html(data.dTail);
						loadDetailedit();
					}
				});	
  		});

  		$(document).on('click', '#detailList-edit tr', function(){
  			dataid = $(this).attr('id');
  			
  			$('#cmdDetailupdate').attr('data-id', dataid);
  			$('#cmdDetaildelete').attr('data-id', dataid);
  			$('#modal-class-update').modal('show');
  			
  			$.ajax({
					url:"editDetail",
					method:'GET',
					data:{
						dataid:dataid
					},
					dataType:'json',
					success:function(data)
					{
						$('#cbosubject-edit').val(data.subjid);
						$('#cbosubject-edit').trigger('change');
						$('#cboteacher-edit').val(data.teacherid);
						$('#cboteacher-edit').trigger('change');
						$('#cmdDetailupdate').text('Update');
					}
				});

  		});


  		$(document).on('click', '#cmdDetaildelete', function(){

  			var studid = $('#studedit').val();
    		var levelid = $('#modalleveledit').val();
    		var syid = $('#modalsyedit').val();
    		var semid = $('modalsemedit').val();

  			Swal.fire({
          title: 'Delete selected Item?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"deleteDetail",
              method:'GET',
              data:{
                dataid:dataid
              },
              dataType:'',
              success:function(data)
              {


              	// $('#detailList-edit').empty();
              	loadDtail(studid, levelid, syid, semid, '#detailList-edit')
              	$('#btn-edit').trigger('click');

                Swal.fire(
                  'Deleted!',
                  'Item has been deleted.',
                  'success'
                );

                
              }
            }); 
          }
        });
  		});



		});	
	</script> 
	
	
@endsection

