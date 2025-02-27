@extends('enrollment.layouts.app')

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Strand List</h1> -->
		  <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-file-invoice nav-icon"></i>  -->
            <b>STRAND LIST</b></h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/registrarIndex">Home</a></li>
            <li class="breadcrumb-item active">Strand List</li>
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
        		<input id="strandSearch" type="text" class="form-control" placeholder="Search" onkeyup="this.value = this.value.toUpperCase();">
		        <div class="input-group-append">
		        	<span class="input-group-text"><i class="fas fa-search"></i></span>
		        </div>
		        <div class="input-group-button">
		        	<button id="cmdnewStrand" class="btn btn-primary" data-toggle="modal" data-target="#addstrandmodal">New</button>
		        </div>
      		</div>
				</div>
				
				<div class="card-body">
					<div class="table-responsive">
						{{-- <table class="mb-0 table table table-striped dataTable" role="grid"> --}}
						<table id="example2" class="table table-striped dataTable" role="grid" aria-describedby="example2_info">
	            <thead class="bg-warning">
	            <tr>
	                <th>Code</th>
	                <th>Name</th>
	                <th>Track</th>
	                <th>Active</th>
	                <th></th>
	            </tr>
	            </thead>
	            <tbody id="strand_body">

		           
	            </tbody>
						</table>
					</div>
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

	<div class="modal fade show" id="addstrandmodal" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title"><b>Strand</b> - New</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">

        	<div class="row">
	        	<div class="col-12">
		        	<div class="form-group row">
		        		<label for="strandcode" class="col-3">Code</label>
		        		<div class="col-9">
			        		<input id="strandcode" class="form-control validation" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>
		        	</div>
		        </div>
	        </div>

	        <div class="row">
	        	<div class="col-12">
		        	<div class="form-group row">
		        		<label for="strandname" class="col-3">Name</label>
		        		<div class="col-9">
			        		<input id="strandname" class="form-control validation" onkeyup="this.value = this.value.toUpperCase();">
		        		</div>
		        	</div>
		        </div>
	        </div>

	        <div class="row">
	        	<div class="col-12">
		        	<div class="form-group row">
		        		<label for="trackmodal" class="col-3">Track</label>
		        		<div class="col-9">
			        		<select id="trackmodal" class="form-control validation">
			        			
		        			</select>
		        		</div>
		        	</div>
		        </div>
	        </div>

	        <div class="row">
	        	<div class="col-12">
		        	<div class="form-group row clearfix">
		        		<div class="col-3">
		        			
		        		</div>
		        		<div class="icheck-info d-inline col-9">
                  <input type="checkbox" id="isactive" >
                  <label for="isactive">
                    Active
                  </label>
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
        		<button id="cmdsaveStrand" type="button" class="btn btn-primary" data-dismiss="modal" style="width: 90px"><i class="fas fa-save"></i> Save</button>
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

			searchStrand();

			function searchStrand(query = '')
			{
				$.ajax({
					url:"{{ route('searchstrand') }}",
					method:'GET',
					data:{
						query:query
					},
					dataType:'json',
					success:function(data)
					{
						$('#strand_body').html(data.output);	
					}
				});
			}

			$(document).on('keyup', '#strandSearch', function(){
				var query = $(this).val();
				console.log(query);
				searchStrand(query);
			});

			$(document).on('click', '#cmdnewStrand', function(){
				
				$.ajax({
					url:"{{ route('loadtrack') }}",
					method:'GET',
					data:{
						
					},
					dataType:'json',
					success:function(data)
					{
						$('#trackmodal').html(data.output);	
					}
				});
				
			});

			$(document).on('click', '#cmdsaveStrand', function(){
				var query = $('#strandSearch').val();
				var code = $('#strandcode').val();
				var strandname = $('#strandname').val();
				var trackid = $('#trackmodal').val();
				var isactive = 0;
				console.log(trackid);

				if($('#isactive').is(':checked'))
				{
					isactive = 1;
				}
				else
				{
					isactive = 0;
				}

				$.ajax({
					url:"{{ route('insertstrand') }}",
					method:'GET',
					data:{
						code:code,
						strandname:strandname,
						trackid:trackid,
						isactive:isactive
					},
					dataType:'text',
					success:function(data)
					{
						searchstrand(query);
					}
				});

			});

		})	
	</script>
	
	
@endsection

