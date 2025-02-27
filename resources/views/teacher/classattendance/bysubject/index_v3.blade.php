@extends('teacher.layouts.app')

@section('content')
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<style>
    [data-id=shake]
    {
        animation: blinker 0.5s linear infinite;
        /* animation-iteration-count: infinite; */

    }

    [data-id=shake]:hover, [data-id=shake]:focus {
        animation-play-state: paused;
    }

    @keyframes blinker {
        50% {
            opacity: 0.5;
        }
    }
</style>
@php
if(isset($attendance)){
    $count = count($attendance);
    $promoted = 0;
    $female = 0;
    $male = 0;
    foreach ($attendance as $att) {
        if($att->promotionstatus == 1){
            $promoted+=1;
        }
        if(strtoupper($att->gender) == 'FEMALE'){
            $female+=1;
        }
        elseif(strtoupper($att->gender) == 'MALE'){
            $male+=1;
        }
    }
}
    

@endphp
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Attendance</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="active breadcrumb-item">Attendance</li>
                    <li class="active breadcrumb-item" aria-current="page">By Subject</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<section class="content-body">
    <div class="card " style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
        <div class="card-header">
            <div class="row mb-2">
                <div class="col-md-3">
                    <label>School Year</label>
                    <select id="selectedschoolyear" name="selectedschoolyear" class="form-control form-control-sm select2">
                        @foreach($schoolyears as $schoolyear)
                            <option @if($schoolyear->isactive == '1') selected @endif value="{{$schoolyear->id}}" >{{$schoolyear->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Semester</label>
                    <select id="selectedsemester" name="selectedsemester" class="form-control form-control-sm select2">
                        @foreach($semesters as $semester)
                            <option @if($semester->isactive == '1') selected @endif value="{{$semester->id}}" >{{$semester->semester}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Grade Level</label>
                    <select id="selectedgradelevel" name="gradelevel" class="form-control form-control-sm select2">
                        <option value="0">Select Grade Level</option>
                        @if(isset($gradelevel))
                                @foreach($gradelevel as $level)
                                    <option @if($level->id == old('gradelevel')) selected @endif value="{{$level->id}}" >{{$level->levelname}}</option>
                                @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Section</label>
                    <select id="selectedsection" name="section" class="form-control form-control-sm select2">
                        <option value="0">Select Section</option>
                    </select>
                </div>
            </div>
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai')
            <div class="row">
                <div class="col-md-3">
                    <label>Subject</label>
                    <select id="selectedsubject" name="subject" class="form-control form-control-sm select2">
                        <option value="0">Select Subject</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Year</label>
                    <select id="selectedyear" name="selectedyear" class="form-control form-control-sm select2">
					@for($to = (date('Y')+1); 2000<$to; $to--)
                          <option value="{{$to}}">{{$to}}</option>
					@endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Month</label>
                    <select id="selectedmonth" name="selectedmonth" class="form-control form-control-sm select2">
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>  
                    </select>
                </div>
                <div class="col-md-3 text-right">
                    <label>&nbsp;</label><br/>
                    
                    <button type="button" class="btn btn-sm btn-primary" id="btn-pickdates"><i class="fa fa-calendar"></i> Pick Dates</button>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-md-3">
                    <label>Strand</label>
                    <select id="selectedstrand" name="strand" class="form-control form-control-sm select2">
                        <option value="0">Select Strand</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Subject</label>
                    <select id="selectedsubject" name="subject" class="form-control form-control-sm select2">
                        <option value="0">Select Subject</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Year</label>
                    <select id="selectedyear" name="selectedyear" class="form-control form-control-sm select2">
					{{-- @for($to = date('Y'); 2000<$to; $to--)
                          <option value="{{$to}}">{{$to}}</option>
					@endfor --}}
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Month</label>
                    <select id="selectedmonth" name="selectedmonth" class="form-control form-control-sm select2">
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>  
                    </select>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                        <button type="button" class="btn btn-sm btn-primary" id="btn-pickdates"><i class="fa fa-calendar"></i> Pick Dates</button>
                </div>
                <div class="col-md-6 text-right">
                    {{-- <button type="button" class="btn btn-default" id="btn-reload"><i class="fa fa-sync"></i> Generate</button> --}}
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div id="results-container">
    </div>
</section>
<div class="modal fade" id="show-calendar" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Pick Dates</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
          {{-- <div class="row">
              <div class="col-md-12">
                  <em class="text-success">Note: Please click dates to add to the setup!</em>
              </div>
          </div> --}}
          <div class="row">
              <div class="col-md-12" id="calendar-container"></div>
              {{-- <div class="col-md-12" >
                  <label>Selected dates:</label>
                  <br/>
                  <div id="selected-dates-container"></div>
              </div> --}}
          </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-danger btn-close-modal" data-dismiss="modal" >Close</button>
        <button type="button" id="btn-generate" class="btn btn-primary"><i class="fa fa-sync"></i> Generate</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
{{-- <script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script> --}}
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- Toastr -->
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>
    var $ = jQuery;
    $('#btn-pickdates').hide();
    $('#btn-reload').hide();
    $(document).ready(function() {   
	
		var schoolyears = @json($schoolyears);
		
		$(document).on('change','#selectedschoolyear',function(){
			console.log("sdfsd")
			display_year()
		})
		
		display_year()
		function display_year(){
			var tempyear = $('#selectedschoolyear').val()
			var tempyearinfo = schoolyears.filter(x=>x.id == tempyear)[0].sydesc
			var tempyearsplit = tempyearinfo.split('-')
			var syarray = []
			$.each(tempyearsplit,function(a,b){
				syarray.push({
					'id':b,
					'text':b
				})
			})
			
			$('#selectedyear').empty()
			$('#selectedyear').select2({
				theme: 'bootstrap4',
				data:syarray
            })
		}
		
	
	
                    $('.select2').select2({
						theme: 'bootstrap4'
                    })
        $('#selectedgradelevel').on('change', function(){
            $('#selectedsubject').empty();
            $('#selectedsection').empty();
            $('#selectedsubject').append('<option value="0">Select Subject</option>');
            $('#selectedsection').append('<option value="0">Select Section</option>');
            var gradelevelid = $(this).val();
            $.ajax({
                url: '/beadleAttendance/getsections',
                type:"GET",
                dataType:"json",
                data:{
                    gradelevelid:gradelevelid,
                    },
                success:function(data) {
                    $.each(data, function(key, value){
                        $('#selectedsection').append('<option value="'+ value.id +'">' + value.sectionname + '</option>');
                    });
                },
            });
        });
        
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai')
        $('#selectedsection').on('change', function(){
            var sectionid = $(this).val();
            $.ajax({
                url: '/beadleAttendance/getsubjects',
                type:"GET",
                dataType:"json",
                data:{
                    semid:$('#selectedsemester').val(),
                    sectionid: sectionid,
                    strandid:0,
                    levelid:$('#selectedgradelevel').val(),
                    syid:$('#selectedschoolyear').val()

                },
                success:function(data) {
                    $('#selectedsubject').empty();
                    $('#selectedsubject').append('<option value="0">Select Subject</option>');
                    $.each(data, function(key, value){
                        $('#selectedsubject').append('<option value="'+ value.subjid +'">' + value.subjdesc + '</option>');
                    });
                },
                
            });
        });
        @else
        $('#selectedsection').on('change', function(){
            var sectionid = $(this).val();
            $.ajax({
                url: '/beadleAttendance/getstrands',
                type:"GET",
                dataType:"json",
                data:{
                    semid:$('#selectedsemester').val(),
                    sectionid:sectionid,
                    levelid:$('#selectedgradelevel').val(),
                    syid:$('#selectedschoolyear').val()

                },
                success:function(data) {
                    $('#selectedstrand').empty();
                    $('#selectedstrand').append('<option value="0">Select Strand</option>');
                    if(data.length == 0)
                    {
                        $('#selectedstrand').trigger('change')
                    }else{                        
                        $.each(data, function(key, value){
                            $('#selectedstrand').append('<option value="'+ value.id +'">' + value.strandname + '</option>');
                        });
                    }
                },
                
            });
        });
        @endif
        $('#selectedstrand').on('change', function(){
            var strandid = $(this).val();
            $.ajax({
                url: '/beadleAttendance/getsubjects',
                type:"GET",
                dataType:"json",
                data:{
                    semid:$('#selectedsemester').val(),
                    sectionid: $('#selectedsection').val(),
                    strandid:strandid,
                    levelid:$('#selectedgradelevel').val(),
                    syid:$('#selectedschoolyear').val()

                },
                success:function(data) {
                    $('#selectedsubject').empty();
                    $('#selectedsubject').append('<option value="0">Select Subject</option>');
                    $.each(data, function(key, value){
                        $('#selectedsubject').append('<option value="'+ value.subjid +'">' + value.subjdesc + '</option>');
                    });
                },
                
            });
        });

        var selectgl = 0;
        var selectsec = 0;
        var selectsubj = 0;

        $('#selectedgradelevel').on('change', function(){
            if($(this).val() == 0)
            {
                selectgl = 0;
                $('#btn-pickdates').hide();
                $('#btn-reload').hide();
            }else{
                selectgl = 1;
            }
        })
        $('#selectedsection').on('change', function(){
            if($(this).val() == 0)
            {
                selectsec = 0;
                $('#btn-pickdates').hide();
                $('#btn-reload').hide();
            }else{
                selectsec = 1;
            }
        })
		
        $('#selectedsubject').on('change', function(){
            if($(this).val() == 0)
            {
                selectsubj = 0;
                $('#btn-pickdates').hide();
                $('#btn-reload').hide();
            }else{
                selectsubj = 1;
                $('#btn-pickdates').show();
                $('#btn-reload').hide();
            }
        })
		
		$('.select2').on('change', function(){
           selecteddates = []
        })
        
        var selecteddates = [];
        
        $('#btn-pickdates').on('click', function(){
			
			
		
            $('#show-calendar').modal('show')
            var selectedyear = $('#selectedyear').val();
            var selectedmonth = $('#selectedmonth').val();

			$('.active-date').removeClass('btn-success')
			
            $.ajax({
                url: '/beadleAttendance/getcalendar',
                type: 'GET',
                data: {
                    selectedyear    : selectedyear,
                    selectedmonth   : selectedmonth,
                            levelid  : $('#selectedgradelevel').val(),
                            sectionid: $('#selectedsection').val(),
                            subjectid: $('#selectedsubject').val(),
                            syid  : $('#selectedschoolyear').val(),
                            semid: $('#selectedsemester').val(),
                            strandid: $('#selectedstrand').val()

                            


                }, success:function(data){
                    $('#calendar-container').empty()
                    $('#calendar-container').append(data)
					
					$('.selecteddate').each(function(a,b){
						selecteddates.push($(this).attr('data-id'))
					})
					
					console.log(selecteddates)
					
                    if(selecteddates.length>0)
                    {
                        $.each(selecteddates,function(key,value){
                            $('.active-date[data-id='+value+']').addClass('btn-success')
                        })
                        $('#btn-generate').show();
                    }else{
                        
                        //$('#btn-generate').hide();
                    }
                }
            })
        })
        $(document).on('click','.active-date', function(){
            $('#selected-dates-container').empty()
            var idx = $.inArray($(this).attr('data-id'), selecteddates);
            if (idx == -1) {
                selecteddates.push($(this).attr('data-id'));
                $(this).addClass('btn-success')
            } else {
                selecteddates.splice(idx, 1);
                $(this).removeClass('btn-success')
            }
            selecteddates.sort(function(a, b) {
                return a - b;
            });
            if(selecteddates.length == 0)
            {
                $('#btn-generate').hide();
            }else{
                $('#btn-generate').show();
            }
        })
        $(document).on('click','#btn-generate', function(){
            $('.btn-close-modal').click()
            $('body').removeClass('modal-open')
            var selectedyear = $('#selectedyear').val();
            var selectedmonth = $('#selectedmonth').val();
            var selectedschoolyear = $('#selectedschoolyear').val();
            var selectedsemester = $('#selectedsemester').val();
            var selectedstrand = $('#selectedstrand').val();
            var selectedgradelevel = $('#selectedgradelevel').val();
            var selectedsection = $('#selectedsection').val();
            var selectedsubject = $('#selectedsubject').val();
            var mapehattendance = 0;
            if(selectedsubject == 'mapeh')
            {
                mapehattendance = 1;

            }
            if(selectedsubject == 'mapeh')
            {
                $.ajax({
                    url: '/beadleAttendance/getsubjects',
                    type:"GET",
                    // dataType:"json",
                    data:{
                        action:'getstudents',
                        semid:selectedsemester,
                        sectionid: selectedsection,
                        strandid:selectedstrand,
                        levelid:selectedgradelevel,
                        dates           : selecteddates,
                        selectedyear    : selectedyear,
                        selectedmonth   : selectedmonth,
                        syid:$('#selectedschoolyear').val()

                    },
                    success:function(data) {
                        $('#results-container').empty()
                        $('#results-container').append(data)
                        $('#results-container').show()
                        $('#btn-reload').show();
                    }
                    
                });
            }else{
                $.ajax({
                    url: '/beadleAttendance/getstudents',
                    type: 'GET',
                    data: {
                        version: '3',
                        mapehattendance: mapehattendance,
                        selectedschoolyear      : selectedschoolyear,
                        selectedsemester      : selectedsemester,
                        dates           : selecteddates,
                        selectedyear    : selectedyear,
                        selectedstrand    : selectedstrand,
                        selectedmonth   : selectedmonth,
                        selectedgradelevel   : selectedgradelevel,
                        selectedsection   : selectedsection,
                        selectedsubject   : selectedsubject
                    }, success:function(data){
                        $('#results-container').empty()
                        $('#results-container').append(data)
                        $('#results-container').show()
                        $('#btn-reload').show();
                    }
                })
            }
        })
        $(document).on('click','#btn-reload', function(){
            var selectedyear = $('#selectedyear').val();
            var selectedmonth = $('#selectedmonth').val();
            var selectedschoolyear = $('#selectedschoolyear').val();
            var selectedsemester = $('#selectedsemester').val();
            var selectedgradelevel = $('#selectedgradelevel').val();
            var selectedstrand = $('#selectedstrand').val();
            var selectedsection = $('#selectedsection').val();
            var selectedsubject = $('#selectedsubject').val();
            $.ajax({
                url: '/beadleAttendance/getstudents',
                type: 'GET',
                data: {
                    version: '3',
                    selectedschoolyear      : selectedschoolyear,
                    selectedsemester      : selectedsemester,
                    dates           : selecteddates,
                    selectedyear    : selectedyear,
                    selectedmonth   : selectedmonth,
                    selectedgradelevel   : selectedgradelevel,
                    selectedstrand    : selectedstrand,
                    selectedsection   : selectedsection,
                    selectedsubject   : selectedsubject
                }, success:function(data){
                    $('#results-container').empty()
                    $('#results-container').append(data)
                    $('#results-container').show()
                    $('#btn-reload').show();
                }
            })
        })
        var arr = ['present', 'absent', 'late', 'none'];
        i = 0;

        $(document).on('click', 'td[data-class="attstatus"]', function() {
            var controlclicks = $('td[clicked="1"]').length;
            if(controlclicks == 16)
            {
                toastr.warning('Limited. Please save changes first!', 'Class Attendance')
            }else{
                if($(this).attr('clicked') == 0)
                {
                    i = 0;
                }
                $(this).attr('clicked','1');
                if(i === arr.length){
                    i=0;   
                }
                if(arr[i] == 'present')
                {
                    $(this).removeAttr('class')
                    $(this).addClass('bg-success')
                    $(this).text('PRESENT')
                }
                else if(arr[i] == 'absent')
                {
                    $(this).removeAttr('class')
                    $(this).addClass('bg-danger')
                    $(this).text('ABSENT')
                }
                else if(arr[i] == 'late')
                {
                    $(this).removeAttr('class')
                    $(this).addClass('bg-warning')
                    $(this).text('LATE')
                }
                else if(arr[i] == 'cc')
                {
                    $(this).removeAttr('class')
                    $(this).addClass('bg-secondary')
                    $(this).text('CC')
                }else{
                    $(this).removeAttr('class')
                    $(this).text('')
                }
                $(this).attr('data-newstatus',arr[i])
                i++;
                return false;
            }
        });
        $(document).on('click', '#btn-save', function() {
            Swal.fire({
                title: 'Saving changes...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })   
            var selectedyear = $('#selectedyear').val();
            var selectedmonth = $('#selectedmonth').val();
            var selectedschoolyear = $('#selectedschoolyear').val();
            var selectedsemester = $('#selectedsemester').val();
            var selectedgradelevel = $('#selectedgradelevel').val();
            var selectedsection = $('#selectedsection').val();
            var selectedsubject = $('#selectedsubject').val();
            var datavalues = [];

            $('td[clicked="1"]').each(function(){
                
                obj = {
                    studid      : $(this).attr('data-studid'),
                    status      : $(this).attr('data-status'),
                    tdate       : $(this).attr('data-tdate'),
                    newstatus       : $(this).attr('data-newstatus')
                };
                datavalues.push(obj);
            })
                   
            $.ajax({
                url: '/beadleAttendanceUpdate',
                type: 'GET',
                data: {
                    version: '3',
                    selectedschoolyear      : selectedschoolyear,
                    selectedsemester      : selectedsemester,
                    selectedyear    : selectedyear,
                    selectedmonth   : selectedmonth,
                    selectedgradelevel   : selectedgradelevel,
                    selectedsection   : selectedsection,
                    selectedsubject   : selectedsubject,
                    datavalues   : datavalues
                },
                complete:function(data){
                    
                    toastr.success('Updated successfully!')
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    $('#btn-reload').click()
                }
            })
        })
        $(document).on('click','#btn-exportexcel', function(){
            var selectedyear = $('#selectedyear').val();
            var selectedmonth = $('#selectedmonth').val();
            var selectedschoolyear = $('#selectedschoolyear').val();
            var selectedsemester = $('#selectedsemester').val();
            var selectedgradelevel = $('#selectedgradelevel').val();
            var selectedsection = $('#selectedsection').val();
            var selectedstrand = $('#selectedstrand').val();
            var selectedsubject = $('#selectedsubject').val();
            var paramet = {
                version: '3',
                selectedschoolyear      : selectedschoolyear,
                selectedsemester      : selectedsemester,
                dates           : selecteddates,
                selectedyear    : selectedyear,
                selectedmonth   : selectedmonth,
                selectedgradelevel   : selectedgradelevel,
                selectedsection   : selectedsection,
                selectedstrand   : selectedstrand,
                selectedsubject   : selectedsubject
            }
            window.open("/beadleAttendance/getstudents?action=export&"+$.param(paramet));
        })
        $(document).on('click', '.btn-column-null', function(){
            columnid = $(this).closest('th').index();
            var selecteddate = $(this).attr('data-date');
            var studids = []
            $('.eachstud').each(function(){
                studids.push($(this).attr('data-id'));
            })
            Swal.fire({
                title: 'Are you sure you want to delete the attedance from this date?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/beadleAttendance/updatecolumn',
                        type:"GET",
                        dataType:"json",
                        data:{
                            action    :  'delete',
                            tdate    :  selecteddate,
                            studids    : JSON.stringify(studids),
                            levelid  : $('#selectedgradelevel').val(),
                            sectionid: $('#selectedsection').val(),
                            subjectid: $('#selectedsubject').val()
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Reset successfully!')
                            $("tr.eachstud").each(function() {
                                $(this).children("td:eq("+columnid+")").removeAttr('class');
                                $(this).children("td:eq("+columnid+")").removeAttr('style');
                                $(this).children("td:eq("+columnid+")").text('');
                                $(this).children("td:eq("+columnid+")").attr('data-newstatus','none');
                                $(this).children("td:eq("+columnid+")").attr('clicked','0');
                            });
                        }
                    })
                }
            })
        })
        $(document).on('change', '.select-column-att', function(){
            columnid = $(this).closest('th').index();
            var selecteddate = $(this).attr('data-date');
            var valstatus = $(this).val();
            var studids = []
            $('.eachstud').each(function(){
                studids.push($(this).attr('data-id'));
            })
            Swal.fire({
                title: 'Are you sure you want to change the attendance status?',
                // text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        title: 'Saving changes...',
                        allowOutsideClick: false,
                        closeOnClickOutside: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        }
                    }) 
                    $.ajax({
                        url: '/beadleAttendance/updatecolumn',
                        type:"GET",
                        dataType:"json",
                        data:{
                            action    :  valstatus,
                            tdate    :  selecteddate,
                            studids    : JSON.stringify(studids),
                            levelid  : $('#selectedgradelevel').val(),
                            sectionid: $('#selectedsection').val(),
                            subjectid: $('#selectedsubject').val()
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Updated successfully!')
                            $("tr.eachstud").each(function() {
                                $(this).children("td:eq("+columnid+")").removeAttr('class');
                                if(valstatus == 'present')
                                {
                                    $(this).children("td:eq("+columnid+")").addClass('bg-success');
                                    $(this).children("td:eq("+columnid+")").text('PRESENT');
                                    $(this).children("td:eq("+columnid+")").attr('data-newstatus','PRESENT');
                                    $(this).children("td:eq("+columnid+")").attr('data-status','PRESENT');
                                }else if(valstatus == 'late')
                                {
                                    $(this).children("td:eq("+columnid+")").addClass('bg-warning');
                                    $(this).children("td:eq("+columnid+")").text('LATE');
                                    $(this).children("td:eq("+columnid+")").attr('data-newstatus','LATE');
                                    $(this).children("td:eq("+columnid+")").attr('data-status','LATE');
                                }else if(valstatus == 'absent')
                                {
                                    $(this).children("td:eq("+columnid+")").addClass('bg-danger');
                                    $(this).children("td:eq("+columnid+")").text('ABSENT');
                                    $(this).children("td:eq("+columnid+")").attr('data-newstatus','ABSENT');
                                    $(this).children("td:eq("+columnid+")").attr('data-status','ABSENT');
                                }else{
                                    
                                    $(this).children("td:eq("+columnid+")").removeAttr('style');
                                    $(this).children("td:eq("+columnid+")").text('');
                                    $(this).children("td:eq("+columnid+")").attr('data-newstatus','none');
                                }
                                $(this).children("td:eq("+columnid+")").attr('clicked','0');
                            });
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                        }
                    })
                }
            })
        })
        $(document).on('change','.select-row-att', function(){
            var studid = $(this).attr('data-id');
            var thistr = $(this).closest('tr');
            var valstatus = $(this).val();
            var dates = []
            $('.eachdate').each(function(){
                dates.push($(this).attr('data-date'));
            })
            Swal.fire({
                title: 'Are you sure you want to change the attendance status?',
                // text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/beadleAttendance/updaterow',
                        type:"GET",
                        dataType:"json",
                        data:{
                            action    :  valstatus,
                            studid   :  studid,
                            dates    : JSON.stringify(dates),
                            levelid  : $('#selectedgradelevel').val(),
                            sectionid: $('#selectedsection').val(),
                            subjectid: $('#selectedsubject').val()
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Marked successfully!')
                            thistr.find('.eachstuddate').each(function(){
                                    $(this).removeAttr('class');
                                    $(this).addClass('eachstuddate');
                                if(valstatus == 'present')
                                {
                                    $(this).addClass('bg-success');
                                    $(this).removeAttr('style');
                                    $(this).text('PRESENT');
                                    $(this).attr('data-newstatus','PRESENT');
                                    $(this).attr('data-status','PRESENT');
                                }else if(valstatus == 'late')
                                {
                                    $(this).addClass('bg-warning');
                                    $(this).removeAttr('style');
                                    $(this).text('LATE');
                                    $(this).attr('data-newstatus','LATE');
                                    $(this).attr('data-status','LATE');
                                }else if(valstatus == 'absent')
                                {
                                    $(this).addClass('bg-danger');
                                    $(this).removeAttr('style');
                                    $(this).text('ABSENT');
                                    $(this).attr('data-newstatus','ABSENT');
                                    $(this).attr('data-status','ABSENT');
                                }else{
                                        
                                    $(this).addClass('eachstuddate');
                                    $(this).removeAttr('style');
                                    $(this).text('');
                                    $(this).attr('data-newstatus','none');
                                }
                                $(this).attr('clicked','0');
                            })
                            $('#btn-reload').click()
                        }
                    })
                }
            })
        })
        $(document).on('click', '.btn-column-present', function(){
            columnid = $(this).closest('th').index();
            var selecteddate = $(this).attr('data-date');
            var studids = []
            $('.eachstud').each(function(){
                studids.push($(this).attr('data-id'));
            })
            Swal.fire({
                title: 'Are you sure you want to mark this column PRESENT?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        title: 'Saving changes...',
                        allowOutsideClick: false,
                        closeOnClickOutside: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        }
                    }) 
                    $.ajax({
                        url: '/beadleAttendance/updatecolumn',
                        type:"GET",
                        dataType:"json",
                        data:{
                            action    :  'present',
                            tdate    :  selecteddate,
                            studids    : JSON.stringify(studids),
                            levelid  : $('#selectedgradelevel').val(),
                            sectionid: $('#selectedsection').val(),
                            subjectid: $('#selectedsubject').val()
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Updated successfully!')
                            $("tr.eachstud").each(function() {
                                $(this).children("td:eq("+columnid+")").removeAttr('class');
                                $(this).children("td:eq("+columnid+")").addClass('bg-success');
                                $(this).children("td:eq("+columnid+")").text('PRESENT');
                                $(this).children("td:eq("+columnid+")").attr('data-newstatus','PRESENT');
                                $(this).children("td:eq("+columnid+")").attr('data-status','PRESENT');
                                $(this).children("td:eq("+columnid+")").attr('clicked','0');
                            });
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                        }
                    })
                }
            })
        })
        $(document).on('click', '.btn-column-late', function(){
            columnid = $(this).closest('th').index();
            var selecteddate = $(this).attr('data-date');
            var studids = []
            $('.eachstud').each(function(){
                studids.push($(this).attr('data-id'));
            })
            Swal.fire({
                title: 'Are you sure you want to mark this column LATE?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        title: 'Saving changes...',
                        allowOutsideClick: false,
                        closeOnClickOutside: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        }
                    }) 
                    $.ajax({
                        url: '/beadleAttendance/updatecolumn',
                        type:"GET",
                        dataType:"json",
                        data:{
                            action    :  'late',
                            tdate    :  selecteddate,
                            studids    : JSON.stringify(studids),
                            levelid  : $('#selectedgradelevel').val(),
                            sectionid: $('#selectedsection').val(),
                            subjectid: $('#selectedsubject').val()
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Updated successfully!')
                            $("tr.eachstud").each(function() {
                                $(this).children("td:eq("+columnid+")").removeAttr('class');
                                $(this).children("td:eq("+columnid+")").addClass('bg-warning');
                                $(this).children("td:eq("+columnid+")").text('LATE');
                                $(this).children("td:eq("+columnid+")").attr('data-newstatus','LATE');
                                $(this).children("td:eq("+columnid+")").attr('data-status','LATE');
                                $(this).children("td:eq("+columnid+")").attr('clicked','0');
                            });
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                        }
                    })
                }
            })
        })
        $(document).on('click', '.btn-column-absent', function(){
            columnid = $(this).closest('th').index();
            var selecteddate = $(this).attr('data-date');
            var studids = []
            $('.eachstud').each(function(){
                studids.push($(this).attr('data-id'));
            })
            Swal.fire({
                title: 'Are you sure you want to mark this column ABSENT?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        title: 'Saving changes...',
                        allowOutsideClick: false,
                        closeOnClickOutside: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        }
                    }) 
                    $.ajax({
                        url: '/beadleAttendance/updatecolumn',
                        type:"GET",
                        dataType:"json",
                        data:{
                            action    :  'absent',
                            tdate    :  selecteddate,
                            studids    : JSON.stringify(studids),
                            levelid  : $('#selectedgradelevel').val(),
                            sectionid: $('#selectedsection').val(),
                            subjectid: $('#selectedsubject').val()
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Updated successfully!')
                            $("tr.eachstud").each(function() {
                                $(this).children("td:eq("+columnid+")").removeAttr('class');
                                $(this).children("td:eq("+columnid+")").addClass('bg-danger');
                                $(this).children("td:eq("+columnid+")").text('ABSENT');
                                $(this).children("td:eq("+columnid+")").attr('data-newstatus','ABSENT');
                                $(this).children("td:eq("+columnid+")").attr('data-status','ABSENT');
                                $(this).children("td:eq("+columnid+")").attr('clicked','0');
                            });
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                        }
                    })
                }
            })
        })
        $(document).on('click','.btn-row-null', function(){
            var studid = $(this).attr('data-id');
            var thistr = $(this).closest('tr');
            var dates = []
            $('.eachdate').each(function(){
                dates.push($(this).attr('data-date'));
            })
            Swal.fire({
                title: 'Are you sure you want to delete the attedance of the selected student?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/beadleAttendance/updaterow',
                        type:"GET",
                        dataType:"json",
                        data:{
                            action    :  'delete',
                            studid   :  studid,
                            dates    : JSON.stringify(dates),
                            levelid  : $('#selectedgradelevel').val(),
                            sectionid: $('#selectedsection').val(),
                            subjectid: $('#selectedsubject').val()
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Deleted successfully!')
                            thistr.find('.eachstuddate').each(function(){
                                $(this).removeAttr('class');
                                $(this).addClass('eachstuddate');
                                $(this).removeAttr('style');
                                $(this).text('');
                                $(this).attr('data-newstatus','none');
                                $(this).attr('clicked','0');
                            })
                        }
                    })
                }
            })
        })
        $(document).on('click','.btn-row-present', function(){
            var studid = $(this).attr('data-id');
            var thistr = $(this).closest('tr');
            var dates = []
            $('.eachdate').each(function(){
                dates.push($(this).attr('data-date'));
            })
            Swal.fire({
                title: 'Are you sure you want to mark this row PRESENT?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/beadleAttendance/updaterow',
                        type:"GET",
                        dataType:"json",
                        data:{
                            action    :  'present',
                            studid   :  studid,
                            dates    : JSON.stringify(dates),
                            levelid  : $('#selectedgradelevel').val(),
                            sectionid: $('#selectedsection').val(),
                            subjectid: $('#selectedsubject').val()
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Marked successfully!')
                            thistr.find('.eachstuddate').each(function(){
                                $(this).removeAttr('class');
                                $(this).addClass('eachstuddate');
                                $(this).addClass('bg-success');
                                $(this).removeAttr('style');
                                $(this).text('PRESENT');
                                $(this).attr('data-newstatus','PRESENT');
                                $(this).attr('data-status','PRESENT');
                                $(this).attr('clicked','0');
                            })
                            $('#btn-reload').click()
                        }
                    })
                }
            })
        })
        $(document).on('click','.btn-row-late', function(){
            var studid = $(this).attr('data-id');
            var thistr = $(this).closest('tr');
            var dates = []
            $('.eachdate').each(function(){
                dates.push($(this).attr('data-date'));
            })
            Swal.fire({
                title: 'Are you sure you want to mark this row LATE?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/beadleAttendance/updaterow',
                        type:"GET",
                        dataType:"json",
                        data:{
                            action    :  'late',
                            studid   :  studid,
                            dates    : JSON.stringify(dates),
                            levelid  : $('#selectedgradelevel').val(),
                            sectionid: $('#selectedsection').val(),
                            subjectid: $('#selectedsubject').val()
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Marked successfully!')
                            thistr.find('.eachstuddate').each(function(){
                                $(this).removeAttr('class');
                                $(this).addClass('eachstuddate');
                                $(this).addClass('bg-warning');
                                $(this).removeAttr('style');
                                $(this).text('LATE');
                                $(this).attr('data-newstatus','LATE');
                                $(this).attr('data-status','LATE');
                                $(this).attr('clicked','0');
                            })
                        }
                    })
                }
            })
        })
        $(document).on('click','.btn-row-absent', function(){
            var studid = $(this).attr('data-id');
            var thistr = $(this).closest('tr');
            var dates = []
            $('.eachdate').each(function(){
                dates.push($(this).attr('data-date'));
            })
            Swal.fire({
                title: 'Are you sure you want to mark this row ABSENT?',
                // text: "You won't be able to revert this!",
                html: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mark',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/beadleAttendance/updaterow',
                        type:"GET",
                        dataType:"json",
                        data:{
                            action    :  'absent',
                            studid   :  studid,
                            dates    : JSON.stringify(dates),
                            levelid  : $('#selectedgradelevel').val(),
                            sectionid: $('#selectedsection').val(),
                            subjectid: $('#selectedsubject').val()
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Marked successfully!')
                            thistr.find('.eachstuddate').each(function(){
                                $(this).removeAttr('class');
                                $(this).addClass('eachstuddate');
                                $(this).addClass('bg-danger');
                                $(this).removeAttr('style');
                                $(this).text('ABSENT');
                                $(this).attr('data-newstatus','ABSENT');
                                $(this).attr('data-status','ABSENT');
                                $(this).attr('clicked','0');
                            })
                        }
                    })
                }
            })
        })
    });
</script>
@endsection