@extends('registrar.layouts.app')
@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Admission</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Admission</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
  	<div class="main-card card">
  		<div class="card-header">
  			<div class="row">
  				

  				<div class="input-group input-group-lg mb-3 col-md-6">
            
            <!-- /btn-group -->
            <input id="txtcode" type="text" class="form-control" placeholder="Enter Code" onkeyup="this.value = this.value.toUpperCase()">
            <div class="input-group-append">
              <button class="btn btn-info">CODE</button>
            </div>
          </div>
  			</div>
  		</div>
  		<div class="card-body">
  			<div class="row">
  				<div class="table-responsive">
  					<table class="table table-striped">
		  				<thead>
		  					<tr>
		  						<th>Name</th>
		  						<th>Date of Birth</th>
		  						<th>Contact No.</th>
		  						<th>CODE</th>
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

  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
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

		});
	</script>
@endsection