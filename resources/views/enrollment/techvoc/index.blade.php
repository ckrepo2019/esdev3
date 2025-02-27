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
	            <b>TECHNICAL - VOCATIONAL Enrollment</b></h2>
	        </div>
	        <div class="col-sm-5">
	          <ol class="breadcrumb float-sm-right">
	            <li class="breadcrumb-item"><a href="/">Home</a></li>
	            <li class="breadcrumb-item active">Technical - Vocational Enrollment</li>
	          </ol>
	        </div>
	      </div>
	    </div><!-- /.container-fluid -->
  	</section>
	<section class="content">
        <div class="card" style="border: none !important;">
            <div class="card-header">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label>Select a course</label>
                        <select class="form-control" id="select-course">
                            <option value="0">All</option>
                            @if(count($courses)>0)
                                @foreach($courses as $course)
                                    <option value="{{$course->id}}">{{$course->description}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Select a batch</label>
                        <select class="form-control" id="select-batch">
                            <option value="0">All</option>
                        </select>
                    </div>
                    <div class="col-md-4 text-right">
                        <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate Results</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="container-results"></div>
	</section>
@endsection

@section('modal')


<div class="modal fade" id="modal-studinfo" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Student Information</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body" id="container-studinfo-edit">
            
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="btn-updatestudinfo">Save Changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  

  <div class="modal fade" id="modal-viewinfo" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="viewinfostudname"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <form>
            <div class="row mb-2">
                <div class="col-md-6">
                    <label>SID</label> &nbsp;: <span id="viewinfosid" style="font-weight: bold;"></span>
                    {{-- <input type="text" class="form-control" id="viewinfosid" disabled> --}}
                </div>
                <div class="col-md-6">
                    <label>GENDER</label> &nbsp;: <span id="viewinfogender" style="font-weight: bold;"></span>
                    {{-- <input type="text" class="form-control" id="viewinfogender" disabled> --}}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6">
                    <label>Course</label>
                    <select class="form-control" id="viewinfocourse">

                    </select>
                    {{-- <input type="text" class="form-control" id="viewinfocourse" disabled> --}}
                </div>
                <div class="col-md-6">
                    <label>Batch</label>
                    <select class="form-control" id="viewinfobatch">

                    </select>
                    {{-- <input type="text" class="form-control" id="viewinfobatch" disabled> --}}
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-6">
                    <label>ENROLLED DATE</label> &nbsp;: <span id="viewinfoenrolleddate" style="font-weight: bold;"></span>
                    {{-- <input type="text" class="form-control" id="viewinfoenrolleddate" disabled> --}}
                </div>
                <div class="col-md-6">
                    <label>ENROLLED BY</label> &nbsp;: <span id="viewinfoenrolledby" style="font-weight: bold;"></span>
                    {{-- <input type="text" class="form-control" id="viewinfoenrolledby" disabled> --}}
                </div>
            </div>
        </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="btn-updatechanges">Save Changes</button>
          {{-- <div class="btn-group dropleft">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Export
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item export" href="#"
                onclick="exportreport('pdf')" >PDF</a>
                <a class="dropdown-item export" onclick="exportreport('excel')">EXCEL</a>
            </div>
          </div> --}}
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
{{-- <div class="modal fade show" id="modal-viewinfo" aria-modal="true" style="padding-right: 17px; display: none;">
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
  </div> --}}

@endsection

@section('js')
<script>
    $(document).ready(function(){
        $('#select-course').on('change', function(){
            $('#select-batch').empty()
            var courseid = $(this).val();
            if(courseid > 0)
            {
				$.ajax({
					url:"{{ route('tvv2enrollment') }}",
					method:'GET',
					data:{
						action:'getbatches',
                        courseid: courseid
					},
					success:function(data)
					{
                        if(data.length == 0)
                        {
                            $('#select-batch').append('<option value="0">All</option>')
                        }else{
                            $('#select-batch').append('<option value="0">All</option>')
                            $.each(data, function(key, value){                                
                                $('#select-batch').append('<option value="'+value.id+'">'+value.startdatestring+' - '+value.enddatestring+'</option>')
                            })
                        }
					}
				});
            }else{
                $('#select-batch').append('<option value="0">All</option>')
            }
            $('#container-results').empty()
        })
        $('#select-course').trigger('change')
        $('#select-batch').on('change', function(){
            $('#container-results').empty()
        })
        $('#btn-generate').on('click', function(){
            var courseid = $('#select-course').val()
            var batchid = $('#select-batch').val()
            $.ajax({
                url:"{{ route('tvv2enrollment') }}",
                method:'GET',
                data:{
                    action:'generate',
                    courseid: courseid,
                    batchid: batchid
                },
                success:function(data)
                {
                    $('#container-results').empty()
                    $('#container-results').append(data)
                }
            });
        })
        $(document).on('click','.btn-changestatus', function(){
            var enrollmentstatus = $(this).attr('data-status');
            var enrolledstudid = $(this).attr('data-id');
            if(enrollmentstatus == 1)
            {
                var textstatus1 = 'Enrolling';
                var textstatus2 = 'Enroll';
            }else{
                var textstatus1 = 'Unenrolling';
                var textstatus2 = 'Unenroll';
            }
            Swal.fire({
                title: textstatus1+' student...',
                html:
                    "Would you like to continue?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: textstatus2,
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url:"{{ route('tvv2enrollment') }}",
                        type:"GET",
                        dataType:"json",
                        data:{
                            action : 'updateenrollmentstatus',
                            enrollmentstatus: enrollmentstatus,
                            enrolledstudid: enrolledstudid
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){
                            
                            if(data == 1)
                            {
                                // toastr.success('Course deleted successfully!')
                                // window.location.reload()
                            }

                        }
                    })
                }
            })
        })
        $(document).on('click','.btn-studinfo', function(){
            var studentid = $(this).attr('data-id');
            $('#modal-studinfo').modal('show')
            $('#container-studinfo-edit').empty()
            $.ajax({
                url:"{{ route('tvgetstudinfo') }}",
                method:'GET',
                data:{
                    id:studentid,
                    action: 'info-edit'
                },
                success:function(data)
                {
                    $('#container-studinfo-edit').append(data)
                }
            })
        })
        $(document).on('click','.btn-viewinfo', function(){
            $('#modal-viewinfo').modal('show')
            var enrolledstudid = $(this).attr('data-id');
            $('#modal-viewinfo').find('form')[0].reset()
            $('#viewinfostudname').text('')
            $.ajax({
                url:"{{ route('tvgetstudinfo') }}",
                method:'GET',
                data:{
                    id:enrolledstudid,
                },
                dataType:'json',
                success:function(data)
                {
                    $('#btn-updatechanges').attr('data-id',data.enrollmentinfo.enrolledstudid);
                    $('#btn-updatechanges').attr('data-studid',data.enrollmentinfo.studid);
                    $('#viewinfostudname').text(data.enrollmentinfo.lastname+', '+data.enrollmentinfo.firstname+' '+data.enrollmentinfo.middlename+' '+data.enrollmentinfo.suffix)
                    $('#viewinfosid').text(data.enrollmentinfo.sid)
                    $('#viewinfogender').text(data.enrollmentinfo.gender)
                    
                    $('#viewinfocourse').empty()
                    // $('#viewinfocourse').append(
                    //     '<option value="">Select a course</option>'
                    // )
                    if((data.courses).length > 0)
                    {
                        $.each(data.courses, function(key, value){
                            // if(data.enrollmentinfo.courseid == value.id)
                            // {
                            // 	$('#viewinfocourse').append(
                            // 		'<option value="'+value.id+'" selected>'+value.description+'</option>'
                            // 	)
                            // }else{
                            // 	$('#viewinfocourse').append(
                            // 		'<option value="'+value.id+'">'+value.description+'</option>'
                            // 	)
                            // }
                                $('#viewinfocourse').append(
                                    '<option value="'+value.id+'">'+value.description+'</option>'
                                )
                        })
                    }
                    $('#viewinfobatch').attr('disabled')
                    $('#viewinfobatch').empty()
                    // $('#viewinfobatch').append(
                    //     '<option value="">Select a batch</option>'
                    // )
                    if((data.batches).length > 0)
                    {
                        $.each(data.batches, function(key, value){
                                $('#viewinfobatch').append(
                                    '<option value="'+value.id+'">'+value.batchdates+'</option>'
                                )
                            // if(data.enrollmentinfo.batchid == value.id)
                            // {
                            // 	$('#viewinfobatch').append(
                            // 		'<option value="'+value.id+'" selected>'+value.batchdates+'</option>'
                            // 	)
                            // }else{
                            // 	$('#viewinfobatch').append(
                            // 		'<option value="'+value.id+'">'+value.batchdates+'</option>'
                            // 	)
                            // }
                        })
                    }
                    if(data.enrollmentinfo.courseid == null)
                    {
                        $('#viewinfocourse').val("")
                    }else{
                        $('#viewinfocourse').val(data.enrollmentinfo.courseid)
                    }
                    if(data.enrollmentinfo.batchid == null)
                    {
                        $('#viewinfobatch').val("")
                    }else{
                        console.log(data.enrollmentinfo.batchid)
                        $('#viewinfobatch').val(data.enrollmentinfo.batchid)
                    }
                    $('#viewinfoenrolleddate').text(data.enrollmentinfo.createddatetime)
                    $('#viewinfoenrolledby').text(data.enrollmentinfo.name)

                }
            })
        })
        $(document).on('click','#btn-updatechanges', function(){
            var courseid = $('#viewinfocourse').val();
            var batchid = $('#viewinfobatch').val();
            var enrolledstudid = $(this).attr('data-id');
            var studentid = $(this).attr('data-studid');
            $.ajax({
                url:"{{ route('tvupdateenrolmentinfo') }}",
                method:'GET',
                data:{
                    enrolledstudid:enrolledstudid,
                    studentid:studentid,
                    courseid:courseid,
                    batchid:batchid
                },
                dataType:'json',
                success:function(data)
                {
                    if(data == 0)
                    {
                        toastr.warning('Enrollment Info. Failed to update!')
                    }else{
                        $('[data-dismiss="modal"]').click()
                        toastr.success('Enrollment Info updated!')
                        $('#btn-generate').click()
                    }
                }
            })

        })
        $(document).on('click','.btn-unenroll', function(){
            var enrolledstudid = $(this).attr('data-id');
            var thisrow = $(this).closest('tr');
            Swal.fire({
                title: 'Unenroll',
                html:
                    "Would you like to continue?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Unenroll',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url:"{{ route('tvv2enrollment') }}",
                        type:"GET",
                        dataType:"json",
                        data:{
                            action : 'unenrollstudent',
                            enrolledstudid: enrolledstudid
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){
                            
                            if(data == 1)
                            {
                                toastr.success('Unenrolled successfully!')
                                // window.location.reload()
                                thisrow.remove()
                            }

                        }
                    })
                }
            })
        })
        $('#btn-updatestudinfo').on('click', function(){
            var studid          = $('#input-studid').val();
            var firstname       = $('#input-firstname').val();
            var middlename      = $('#input-middlename').val();
            var lastname        = $('#input-lastname').val();
            var suffix          = $('#input-suffix').val();
            var gender          = $('#select-gender').val();
            var dob             = $('#input-dob').val();
            var nationalityid   = $('#select-nationality').val();
            var contactno       = $('#input-contactno').val();
            var street          = $('#input-street').val();
            var barangay        = $('#input-barangay').val();
            var city            = $('#input-city').val();
            var province        = $('#input-province').val();
            var fathername      = $('#input-fathername').val();
            var foccupation     = $('#input-foccupation').val();
            var fcontactno      = $('#input-fcontactno').val();
            var mothername      = $('#input-mothername').val();
            var moccupation     = $('#input-moccupation').val();
            var mcontactno      = $('#input-mcontactno').val();
            var guardianname        = $('#input-guardianname').val();
            var guardianrelation        = $('#input-guardianrelation').val();
            var gcontactno      = $('#input-gcontactno').val();

            var formvalidation = 0;

            var ismother = 0;
            var isfather = 0;
            var isguardian = 0;
            if($('#radioPrimary1').is(':checked'))
            {
                ismother = 1
            }
            if($('#radioPrimary2').is(':checked'))
            {
                isfather = 1
            }
            if($('#radioPrimary3').is(':checked'))
            {
                isguardian = 1
            }

            if(firstname.replace(/^\s+|\s+$/g, "").length == 0)
            {
                formvalidation+=1;
                $('#input-firstname').css('border','1px solid red')
                toastr.warning('Please fill in required field!')
            }
            if(lastname.replace(/^\s+|\s+$/g, "").length == 0)
            {
                formvalidation+=1;
                $('#input-lastname').css('border','1px solid red')
                toastr.warning('Please fill in required field!')
            }
            if(dob.replace(/^\s+|\s+$/g, "").length == 0)
            {
                formvalidation+=1;
                $('#input-dob').css('border','1px solid red')
                toastr.warning('Please fill in required field!')
            }

            if(formvalidation == 0)
            {
                $.ajax({
                    url:"{{ route('tvv2studinfo') }}",
                    method:'GET',
                    data:{
                        action:'studinfoupdate',
                        studid:studid,
                        firstname:firstname,
                        middlename:middlename,
                        lastname:lastname,
                        suffix:suffix,
                        gender:gender,
                        dob:dob,
                        nationalityid:nationalityid,
                        contactno:contactno,
                        street:street,
                        barangay:barangay,
                        city:city,
                        province:province,
                        fathername:fathername,
                        foccupation:foccupation,
                        fcontactno:fcontactno,
                        mothername:mothername,
                        moccupation:moccupation,
                        mcontactno:mcontactno,
                        guardianname:guardianname,
                        guardianrelation:guardianrelation,
                        gcontactno:gcontactno,
                        ismother:ismother,
                        isfather:isfather,
                        isguardian:isguardian
                    },
                    dataType:'json',
                    success:function(data)
                    {
                        if(data == 1)
                        {
                            toastr.success('Updated successfully!')
                        }else{
                            // $('[data-dismiss="modal"]').click()
                            // toastr.success('Enrollment Info updated!')
                            // $('#btn-generate').click()
                        }
                    }
                })
            }
        })
        function getcoursesenrolled()
        {
            var studid = $('#cbostud').val();
            $.ajax({
                url:"{{ route('tvv2enrollment') }}",
                method:'GET',
                data:{
                    action:'getcoursesenrolled',
                    studid: studid
                },
                dataType:'json',
                success:function(data)
                {
                    $('#container-coursesenrolled').empty();
                    if(data.length == 0)
                    {
                    $('#container-coursesenrolled').append('<label>Not enrolled in any courses.</label>');
                    }else{
                        var displayhtml = '<label>Enrollment History</label>';
                        displayhtml += '<ul style="font-size: 13px;" class="pl-1">';

                        $.each(data, function(key, value){
                            displayhtml+='<li>Course: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>'+value.coursename+'</u></li>';
                            displayhtml+='<li>Batch: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>'+value.startdate+' - '+value.enddate+'</u></li>';
                            displayhtml+='<li>Date Enrolled:&nbsp; <u>'+value.dateenrolled+'</u></li>';
                            displayhtml+='<li><hr/></li>';
                        })
                        displayhtml+='</ul>';
                        $('#container-coursesenrolled').append(displayhtml);
                    }
                }
            });
        }
        function getstudinfo()
        {
            var studid = $('#cbostud').val();
            if(studid>0)
            {
                $('#btn-enrollstudent').show();

                $.ajax({
                    url:"{{ route('tvv2enrollment') }}",
                    method:'GET',
                    data:{
                        action:'getstudinfo',
                        studid:studid	
                    },
                    dataType:'json',
                    success:function(data)
                    {
                        if(data.gender == '' || data.gender == ' ' || data.gender == null)
                        {
                            data.gender = 0;
                        }
                        $('#cbogender').val(data.gender);
                        $('#cbogender').trigger('change');
                        if(data.nationality == '' || data.nationality == ' ' || data.nationality == null)
                        {
                            data.nationality = 0;
                        }
                        $('#dob').val(data.dob);
                        $('#cbonationality').select2("trigger", "select", {
                            data: { id: data.nationality }
                        })
                        $('#txtcontactno').val(data.contactno);
                        $('#txtstreet').val(data.street);
                        $('#txtbarangay').val(data.barangay);
                        $('#txtcity').val(data.city);
                        $('#txtprovince').val(data.province);
                    }
                });
                $("#courseselection").removeAttr("disabled"); 
                $("#batchselection").removeAttr("disabled"); 
                $('#row-form').find('select').removeAttr("disabled"); 
                $('#row-form').find('input').removeAttr('readonly')
            }else{
                $("#courseselection").val(0); 
                $("#batchselection").empty(); 
                var selections = $('#row-form').find('select'); 
                selections.each(function(){
                    $(this).val(0)
                    $(this).trigger('change');
                })
                $('#row-form').find('input').val('')

                $("#courseselection").attr("disabled", true); 
                $("#batchselection").attr("disabled", true); 
                $('#row-form').find('select').attr("disabled", true); 
                $('#row-form').find('input').attr('readonly',true)
                $('#btn-enrollstudent').hide();
            }
                getcoursesenrolled()
        }
        $(document).on('click','#btn-enrollastudent', function(){
            $('#modal-enrollment').modal('show')
            getstudinfo()
        })

        $(document).on('select2:close', '#cbostud', function(){
            getstudinfo()
        });
        $(document).on('click','#btn-enrollstudent',function(){
            $('#validation-selectcourse').hide()

            $('#validation-selectbatch').hide()
            var selection = 0;
            
            var courseid = $('#courseselection').val();
            var batchid = $('#batchselection').val();

            var gender = $('#cbogender').val();
            var dob = $('#dob').val();
            var nationality = $('#cbonationality').val();
            var contactnum = $('#txtcontactno').val();

            var street = $('#txtstreet').val();
            var barangay = $('#txtbarangay').val();
            var city = $('#txtcity').val();
            var province = $('#txtprovince').val();
            if(courseid > 0 && batchid>0 )
            {
                var thiselement = $(this);
                var id = $('#cbostud').val();
                $.ajax({
                    url:"{{ route('tvv2enrollment') }}",
                    method:'GET',
                    data:{
                        action:'enrollstudent',
                        studid:id,
                        courseid:courseid,
                        batchid:batchid,
                        gender:gender,
                        dob:dob,
                        nationality:nationality,
                        contactnum:contactnum,
                        street:street,
                        barangay:barangay,
                        city:city,
                        province:province
                    },
                    dataType:'json',
                    success:function(data)
                    {
                        // console.log(data)
                        if(data == 0)
                        {
                            toastr.warning('Student is already enrolled!')
                        }else{
                            $('#btn-enrollstudent-close').click()
                            
                            toastr.success('Student is enrolled successfully!')
                            $('#btn-generate').click()
                        }
                    }
                })
            }else{
                if(courseid < 1)
                {
                    toastr.warning('Please select a course!');
                    $('#validation-selectcourse').show()
                }
                if(batchid < 1)
                {
                    toastr.warning('Please select a batch!');
                    $('#validation-selectbatch').show()
                }
            }
        })
        $(document).on('change','#courseselection', function(){
            var id = $(this).val();
            $.ajax({
                url:"{{ route('tvgetbatch') }}",
                method:'GET',
                data:{
                    id:id
                },
                dataType:'json',
                success:function(data)
                {
                    // console.log(data)
                    $('#batchselection').empty();
                    if(data.length > 0)
                    {
                        $.each(data, function(key, value){
                            $('#batchselection').append(
                                '<option value="'+value.id+'">'+value.startdate_str+' - '+value.enddate_str+'</option>'
                            )
                        })
                    }
                }
            })
        })
        $(document).on('click', '#btn-save-student', function(){

            if($('#f_rademergency').prop('checked') == true)
            {
                var fnum = 1;
            }
            else
            {
                var fnum = 0;
            }

            if($('#m_rademergency').prop('checked') == true)
            {
                var mnum = 1;
            }
            else
            {
                var mnum = 0;
            }

            if($('#g_rademergency').prop('checked') == true)
            {
                var gnum = 1;
            }
            else
            {
                var gnum = 0;
            }
            
            $.ajax({
                url:"{{ route('tvv2enrollment') }}",
                method:'GET',
                data:{
                        action:'createstudent',
                    lastname:$('#new_lastname').val(),
                    firstname:$('#new_firstname').val(),
                    middlename:$('#new_middlename').val(),
                    suffix:$('#new_suffix').val(),

                    gender:$('#cbonew_gender').val(),
                    dob:$('#new_dob').val(),
                    nationality:$('#cbonew_nationality').val(),
                    contactno:$('#txtnew_contactno').val(),

                    street:$('#txtnew_street').val(),
                    barangay:$('#txtnew_barangay').val(),
                    city:$('#txtnew_city').val(),
                    province:$('#txtnew_province').val(),

                    fname:$('#txtnew_fname').val(),
                    foccupation:$('#txtnew_foccupation').val(),
                    fcontactno:$('#txtnew_fcontactno').val(),
                    fnum:fnum,

                    mname:$('#txtnew_mname').val(),
                    moccupation:$('#txtnew_moccupation').val(),
                    mcontactno:$('#txtnew_mcontactno').val(),
                    mnum:mnum,

                    gname:$('#txtnew_gname').val(),
                    goccupation:$('#txtnew_goccupation').val(),
                    grelation:$('#txtnew_relation').val(),
                    gcontactno:$('#txtnew_gcontactno').val(),
                    gnum:gnum

                },
                dataType:'json',
                success:function(data)
                {
                    if(data == 0)
                    {

                        toastr.warning('Student already exists!')
                    }
                    else
                    {
                        toastr.success('Student created successfully!')
                        $('#cbostud').append(
                            '<option value="'+data.id+'" selected>'+data.lastname+', '+data.firstname+'</option>'
                        )
                        $('#cbostud').trigger('select2:close');
                        getstudinfo()
                        // $('#btn-enrollastudent').click();
                    }
                }
            });
        });
        $(document).on('click','#btn-exporttopdf', function(){
            var courseid = $('#courseselection').val();
            var batchid = $('#batchselection').val();
            window.open("/techvocv2/enrollment?courseid="+courseid+"&batchid="+batchid+"&action=export",'_blank');
        })
    })
</script>
@endsection

