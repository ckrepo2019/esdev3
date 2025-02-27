@extends('enrollment.layouts.app')

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Admission</h1> -->
		  <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-file-invoice nav-icon"></i>  -->
            <b>ADMISSION</b></h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Admission</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
  	<div class="main-card card">
  		<div class="card-header bg-info">
  			<div class="row">
  				

  				<div class="input-group input-group-lg mb-3 col-md-6">
            
            <!-- /btn-group -->
            <input id="txtcode" type="text" class="form-control" placeholder="Search" onkeyup="this.value = this.value.toUpperCase()">
            <div class="input-group-append">
              <button id="btncode" class="btn btn-primary">CODE</button>
            </div>
          </div>
  			</div>
  		</div>
  		<div class="card-body">
  			<div class="row">
  				<div class="table-responsive">
  					<table class="table table-striped">
		  				<thead class="bg-warning">
		  					<tr>
		  						<th>Name</th>
		  						<th>Date of Birth</th>
		  						<th>Contact No.</th>
		  						<th>CODE</th>
		  						<th>Date Registered</th>
		  						<th></th>
		  						<th></th>
		  					</tr>
		  				</thead>
		  				<tbody id="prereg_body">
		  					
		  				</tbody>
		  			</table>
  				</div>
  			</div>
  		</div>
  	</div>
  </section>

@endsection

@section('js')
	<script>
		$(document).ready(function(){
			
			$('#txtcode').focus();
			preregSearch();

			function preregSearch(code = '')
			{
				$.ajax({
					url:"{{ route('searchPreReg') }}",
					method:'GET',
					data:{
						code:code
					},
					dataType:'json',
					success:function(data)
					{
						$('#prereg_body').html(data.output);
					}
				});
			}

			$(document).on('keyup', '#txtcode', function(){
				var code = $(this).val();
				preregSearch(code);
				console.log(code);
			});

			$(document).on('click','.preregdel', function(){
				var dataid = $(this).attr('data-id');
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
							url:"{{ route('preregdel') }}",
							method:'GET',
							data:{
								dataid:dataid
							},
							dataType:'',
							success:function(data)
							{
								if(data == 0)
								{
									preregSearch();
									Swal.fire(
							      'Deleted!',
							      'Pre-registration has been deleted.',
							      'success'
							    );
							  }
							  else
							  {
							  	preregSearch();
									Swal.fire(
							      'Warning!',
							      'Student cannot be deleted.',
							      'warning'
							    );		
							  }
							}
						});
				  }
				})
			});

			// $(document).on('click', '#btncode', function(){
			// 	$.ajax({
			// 		url:"{{ route('sync') }}",
			// 		method:'GET',
			// 		data:{

			// 		},
			// 		dataType:'',
			// 		success:function(data)
			// 		{

			// 		}
			// 	});
		 //  });

		});
	</script>
@endsection