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
        <div class="col-sm-6 pt-0">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Student Information</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
	<section class="content pt-0">
		<div class="col-lg-12">
			<div class="main-card mb-3 card">
				<div class="card-body bg-info">	
					<div class="input-group mb-1 float-right col-10 col-lg-7">
						
						&nbsp;
				        <input id="studSearch" type="text" class="form-control" placeholder="Search" onkeyup="this.value = this.value.toUpperCase();">
				        <div class="input-group-append">
				        	<span class="input-group-text"><i class="fas fa-search"></i></span>
				        </div>
				        <div class="input-group-button">
				        	<button id="createstud" class="btn btn-primary">New Student</button>
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
			                <th>Course</th>
			                <th>Status</th>
				            </tr>
				            </thead>
				            <tbody id="tvlist">

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

	<div class="modal fade show" id="modal-newstud" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div id="enroll-modal-header" class="modal-header bg-info">
        	<div class="row" style="width: 100%">
				<div class="col-md-6">
					<h4 class="modal-title">Student - <span id="action"></span></h4>	
				</div>
				<div class="col-md-6 text-right">
					<h4 id="promotestatus" class="modal-title"></h4>
				</div>
			</div>
			
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            {{-- <span aria-hidden="true">×</span> --}}
          </button>
        </div>

        <div class="modal-body">
        	<div class="row">
        		<div></div>
        	</div>
	        
        </div>
        <div class="modal-footer justify-content-between"> 
        	{{--  --}}

        	<div class="float-left">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		
        	</div>        	
        	<div class="float-right">
        		<button id="cmdSave" type="button" class="btn btn-primary" data-dismiss="modal" style="width: 90px"><i class="fas fa-save"></i> Save</button>
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
				
				query = $('#studSearch').val();
				// courseid = $();
				
				$.ajax({
					url:"{{ route('tvstudsearch') }}",
					method:'GET',
					data:{
						
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

			$(document).on('click', '#createstud', function(){
				$('#action').text('New');
				$('#modal-newstud').modal('show');
			});

			
		});	
	</script> 
	
	
@endsection

