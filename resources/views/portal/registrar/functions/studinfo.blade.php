@extends('registrar.layouts.app')

@section('content')

		<div class="col-lg-12">
			<div class="main-card mb-3 card">
				<div class="card-body">	
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
		        &nbsp;
		        <div class="input-group-button">
		        	<button class="btn btn-primary" onclick="window.location.href='{{route('studentcreate')}}'">New Student</button>
		        </div>
		      </div>
				</div>
				
				<div class="card-body">
					<div class="table-responsive">
						<table class="mb-0 table table table-striped">
	            <thead>
	            <tr>
	                <th>ID No.</th>
	                <th>Student Name</th>
	                <th>Gender</th>
	                <th>Grade Level</th>
	                <th>Section</th>
	                <th>ESC</th>
	                <th></th>
	            </tr>
	            </thead>
	            <tbody id="studlist_body">

		           
	            </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>  
{{-- @endsection --}}

{{-- @section('modal') --}}
{{-- ENROLLMENT --}}

	<div class="modal fade show" id="enrollstud" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Enroll Student</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
	        <div class="row">
	        	<div class="col-2">
	        		<h5>LRN :</h5>	
	        	</div>
	        	<div class="col-4">
	        		<h5 id="lrn">123456789012345</h5>
	        	</div>
	        	<div class="col-2">
	        		<h4 class="text-right">ID NO:</h4>	
	        	</div>
	        	<div class="col-4">
	        		<h4 id="sid"><strong>0000123456789</strong></h4>
	        	</div>
	        </div>

	      
	        <div class="row">
	        	<div class="col-2">
	        		<h5>NAME :</h5>	
	        	</div>
	        	<div class="col-8">
	        		<h5 id="name">ANDREI CABRERA</h5>
	        	</div>
	        </div>

	        <div class="row">
	        	<div class="col-2">
	        		<h5>DOB :</h5>	
	        	</div>
	        	<div class="col-8">
	        		<h5 id="dob">07-31-2019</h5>
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
			        		<select id="sections" class="form-control validation">
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
        		<button type="button" class="btn btn-primary" data-dismiss="modal" style="width: 90px"><i class="fas fa-save"></i> Save</button>
        		<button id="cmdenrollstudent" type="button" class="btn btn-success" data-dismiss="modal" style="width: 120px"><i class="fas fa-share"></i> Enroll</button>
        	</div>
        </div>
      </div>
    </div>
      <!-- /.modal-content -->
  </div>
    <!-- /.modal-dialog -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

    <script>
            $(document).ready(function(){
                searchStud()
                function searchStud(query = '', glevel=0)
                {
                    $.ajax({
                        url:"{{ route('studentsearch') }}",
                        method:'GET',
                        data:{
                            query:query,
                            glevel:glevel
                        },
                        dataType:'json',
                        success:function(data)
                        {
                            $('#studlist_body').html(data.output);	
                        }
                    });
                }
    
                $(document).on('keyup', '#studSearch', function(){
                    
                    var query = $(this).val();
                    var glevel = 0;
                    glevel = $('#glevel').val();
                    console.log(glevel);
                    searchStud(query, glevel);
                });
    
                $(document).on('change', '#glevel', function(){
                    glevel = $(this).val();
                    query = $('#studSearch').val();
                    searchStud(query, glevel);
                });
    
    
                //enroll get stud data
    
                $(document).on('click', '#cmdenroll', function(){
                    validate();
                    var studid = $(this).attr('data-value');
    
                    $.ajax({
                        url:"{{ route('enrollgetinfo') }}",
                        method:'GET',
                        data:{
                            studid:studid
                        },
                        dataType:'json',
                        success:function(data)
                        {
                            $('#sid').text(data.sid);
                            $('#sid').attr('data-value', data.studid);
                            $('#lrn').text(data.lrn);
                            $('#name').text(data.name);
                            $('#dob').text(data.dob);
                            
                            $('#glevelmodal').html(data.level);
                            $('#sections').html(data.section);
                            $('#sy').html(data.syear);
                            $('#studstatus').html(data.studstatus);
                        }
                    });
    
    
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
    
                    $.ajax({
                        url:"{{ route('enrollstud') }}",
                        method:'GET',
                        data:{
                            studid:studid,
                            sid:sid,
                            glevel:glevel,
                            section:section,
                            sy:sy,
                            studstatus:studstatus
                        },
                        dataType:'text',
                        success:function(data)
                        {
                            alert('Saved');
    
                            glevel = $(this).val();
                            query = $('#studSearch').val();
                            
                            searchStud(query, glevel);
    
                        }
                    });
    
    
                    console.log(glevel);
                });
    
    
                //enroll stud
    
    
                // validation
                function validate()
                {
                    $('.validation').each(function(){
    
                        
                        if($(this).val() != '')
                        {
                            $(this).attr('class', 'form-control is-valid validation')
                        }
                        else
                        {
                            $(this).attr('class', 'form-control is-invalid validation')	
                        }	
                        
                    });
                }
                $(document).on('change', '.validation', function(){
                    validate();
                })
                // validation
            });	
        </script> 
@endsection

	
	
	

