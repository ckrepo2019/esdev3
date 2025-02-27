
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@extends('registrar.layouts.app')

@section('content')

    <style>
        
        .donutTeachers{
            margin-top: 90px;
            margin: 0 auto;
            background: transparent url("{{asset('assets/images/corporate-grooming-20140726161024.jpg')}}") no-repeat  28% 60%;
            background-size: 30%;
        }
        .donutStudents{
            margin-top: 90px;
            margin: 0 auto;
            background: transparent url("{{asset('assets/images/student-cartoon-png-2.png')}}") no-repeat  28% 60%;
            background-size: 30%;
        }
        #studentstable{
            font-size: 13px;
        }
        @media (min-width: 768px) {
            .modal-xl {
                width: 90%;
                max-width:1200px;
            }
        }
    </style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-7">
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                    <h1 class="m-0 text-dark">Enrollment Statistics</h1>
                    @else
                    <h1 class="m-0 text-dark">Student List & Enrollment Summary</h1>
                    @endif
                </div><!-- /.col -->
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                        <li class="breadcrumb-item active">Enrollment Statistics</li>
                        @else
                        <li class="breadcrumb-item active">Student List & Enrollment Summary</li>
                        @endif
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="row mb-2">
      <div class="col-12">
        <!-- Custom Tabs -->
        <div class="card">
          <div class="card-header d-flex p-0">
            {{-- <h3 class="card-title p-3">Tabs</h3> --}}
            <ul class="nav nav-pills ml-auto p-2">
              <li class="nav-item"><a class="nav-link active text-sm" href="#tab_1" data-toggle="tab">Enrollment Summary</a></li>
              <li class="nav-item"><a class="nav-link text-sm" href="#tab_2" data-toggle="tab">Student List (College)</a></li>
              <li class="nav-item"><a class="nav-link text-sm" href="#tab_3" data-toggle="tab">NSTP Form Enrollment List</a></li>
              {{-- <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                  Dropdown <span class="caret"></span>
                </a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" tabindex="-1" href="#">Action</a>
                  <a class="dropdown-item" tabindex="-1" href="#">Another action</a>
                  <a class="dropdown-item" tabindex="-1" href="#">Something else here</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" tabindex="-1" href="#">Separated link</a>
                </div>
              </li> --}}
            </ul>
          </div><!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'pcc')
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label>Select School Year</label>
                            <select class="form-control" id="es-syid">
                                @foreach(DB::table('sy')->get() as $sy)
                                <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Select Semester</label>
                            <select class="form-control" id="es-semid">
                                @foreach(DB::table('semester')->get() as $semester)
                                <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Select Department</label>
                            <select class="form-control" id="es-department">
                                <option value="">All</option>
                                @foreach(DB::table('academicprogram')->get() as $eachacad)
                                <option value="{{$eachacad->id}}">{{$eachacad->acadprogcode}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Select College</label>
                            <select class="form-control" id="es-collegeid">
                                <option value="0">All</option>
                                @foreach(DB::table('college_colleges')->get() as $eachcollege)
                                <option value="{{$eachcollege->id}}">{{$eachcollege->collegeDesc}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label>Select Course</label>
                            <select class="form-control" id="es-courseid">
                                <option value="0">All</option>
                            </select>
                        </div>
                        <div class="col-md-9 text-right mt-2 align-self-end">
                            <button type="button" class="btn btn-primary" id="btn-generate-es"><i class="fa fa-sync"></i> Generate</button>
                        </div>
                    </div>
                @else
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label>Select School Year</label>
                            <select class="form-control" id="es-syid">
                                @foreach(DB::table('sy')->get() as $sy)
                                <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Select Semester</label>
                            <select class="form-control" id="es-semid">
                                @foreach(DB::table('semester')->get() as $semester)
                                <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Select Department</label>
                            <select class="form-control" id="es-department">
                                <option value="">All</option>
                                @foreach(DB::table('academicprogram')->get() as $eachacad)
                                <option value="{{$eachacad->id}}">{{$eachacad->acadprogcode}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 text-right">
                            <label>&nbsp;</label><br/>
                            <button type="button" class="btn btn-primary" id="btn-generate-es"><i class="fa fa-sync"></i> Generate</button>
                        </div>
                    </div>
                @endif
                  <div id="es-results-container"></div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label>Select School Year</label>
                        <select class="form-control" id="sl-syid">
                            @foreach(DB::table('sy')->get() as $sy)
                              <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Select Semester</label>
                        <select class="form-control" id="sl-semid">
                            @foreach(DB::table('semester')->get() as $semester)
                              <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 text-right">
                        <label>&nbsp;</label><br/>
                        <button type="button" class="btn btn-primary" id="btn-generate-sl"><i class="fa fa-sync"></i> Generate</button>
                    </div>
                </div>
                <div id="sl-results-container"></div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label>Select School Year</label>
                        <select class="form-control" id="nstpel-syid">
                            @foreach(DB::table('sy')->get() as $sy)
                              <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                    <div class="col-md-3">
                        <label>Select Semester</label>
                        <select class="form-control" id="nstpel-semid">
                            @foreach(DB::table('semester')->get() as $semester)
                              <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Select Report Type</label>
                        <select class="form-control" id="nstpel-reporttype">
                            <option value="listofgraduates">List of Graduates</option>
                            <option value="enrollmentlist">Enrollment List</option>
                            <option value="promotional">Promotional Report</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-right">
                        <label>&nbsp;</label><br/>
                        <button type="button" class="btn btn-primary" id="btn-generate-nstpel"><i class="fa fa-sync"></i> Generate</button>
                    </div>
                    @else
                    <div class="col-md-9 text-right">
                        <label>&nbsp;</label><br/>
                        <button type="button" class="btn btn-primary" id="btn-generate-nstpel"><i class="fa fa-sync"></i> Generate</button>
                    </div>
                    @endif

                </div>
                <div id="nstpel-results-container"></div>
              </div>
            </div>
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
        </div>
        <!-- ./card -->
      </div>
      <!-- /.col -->
    </div>
    <div id="container-filter">
    </div>
    
    @endsection
    @section('footerjavascript')
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,  
            timer: 3000
        });
        $(document).ready(function(){
            $('#es-collegeid').on('change', function(){
                $.ajax({
                    url: '/registrar/ctbd/getcourses',
                    type:'GET',
                    datatype:'json',
                    data: {
                        collegeid        :  $(this).val()
                    },
                    success:function(data) {
                        $('#es-courseid').empty()
                        $('#es-courseid').append('<option value="0">All</option>')

                        if(data.length > 0)
                        {
                            $.each(data, function(key, value){
                                $('#es-courseid').append('<option value="'+value.id+'">'+value.courseDesc+'</option>')
                            })
                        }
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                })
            })
            $('#btn-generate-es').on('click', function(){
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/registrar/studentlist',
                    type:'GET',
                    data: {
                        type        :  'es',
                        action      :  'filter',
                        collegeid        :  $('#es-collegeid').val(),
                        courseid        :  $('#es-courseid').val(),
                        syid        :  $('#es-syid').val(),
                        semid       :  $('#es-semid').val(),
                        department  :  $('#es-department').val()
                    },
                    success:function(data) {
                        $('#es-results-container').empty()
                        $('#es-results-container').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                })
            })
            $(document).on('click','#btn-export-es', function(){
                window.open('/registrar/studentlist/pdf?type=es&action=exportpdf&format=enrollmentsum&syid='+$('#es-syid').val()+'&semid='+$('#es-semid').val()+'&department='+$('#es-department').val(),'_blank')
            })
            $(document).on('click','#btn-export-estable', function(){
                window.open('/registrar/studentlistpdf?type=es&action=exportpdf&format=enrollmenttable&syid='+$('#es-syid').val()+'&semid='+$('#es-semid').val()+'&department='+$('#es-department').val(),'_blank')
            })
            $(document).on('click','.btn-export-es-ched', function(){
                var reporttype = $(this).attr('data-reporttype');
                var collegeid  =  $('#es-collegeid').val();
                var courseid   =  $('#es-courseid').val();
                window.open('/registrar/studentlist?type=es&action=exportexcel&reporttype='+reporttype+'&format=ched&syid='+$('#es-syid').val()+'&semid='+$('#es-semid').val()+'&department='+$('#es-department').val()+'&collegeid='+collegeid+'&courseid='+courseid,'_blank')
            })
            $(document).on('click','#btn-export-es-enrollment-stat', function(){
                window.open('/registrar/studentlist?type=es&action=exportpdf&format=enrollmentstats&syid='+$('#es-syid').val()+'&semid='+$('#es-semid').val()+'&department='+$('#es-department').val(),'_blank')
            })
            $('#btn-generate-sl').on('click', function(){
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/registrar/studentlist',
                    type:'GET',
                    data: {
                        type        :  'sl',
                        action      :  'filter',
                        syid        :  $('#sl-syid').val(),
                        semid       :  $('#sl-semid').val()
                    },
                    success:function(data) {
                        $('#sl-results-container').empty()
                        $('#sl-results-container').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                })
            })
            $(document).on('click','#btn-export-sl', function(){
                window.open('/registrar/studentlist?type=sl&action=exportpdf&syid='+$('#es-syid').val()+'&semid='+$('#es-semid').val(),'_blank')
            })
            $(document).on('click','#btn-export-sl-excell', function(){
                var syid = $('#es-syid').val()
                var action = 'excellstudentlist'
                var semid = $('#es-semid').val()
                var type = 'sl'
                // console.log(syid,action,semid);

                // $.ajax({
                //     type: "get",
                //     url: "/registrar/studentlist",
                //     data: {
                //         action      :  action,
                //         syid        :  syid,
                //         semid       :  semid,
                //         type        :  type
                //     },
                //     success: function (data) {
                //         console.log(data);
                //     }
                // });
                 // Construct the URL for the download
                    var url = `/registrar/studentlist?action=${action}&syid=${syid}&semid=${semid}&type=${type}`;
                    
                    // Trigger a direct download by redirecting to the URL
                    window.location.href = url;

            })
            $('#btn-generate-nstpel').on('click', function(){
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                
                $.ajax({
                    url: '/registrar/studentlist',
                    type:'GET',
                    data: {
                        type        :  'nstpel',
                        action      :  'filter',
                        syid        :  $('#nstpel-syid').val(),
                        semid       :  $('#nstpel-semid').val(),
                        reporttype       :  $('#nstpel-reporttype').val()
                    },
                    success:function(data) {
                        $('#nstpel-results-container').empty()
                        $('#nstpel-results-container').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                })
            })
            $(document).on('click','#btn-savesignatories', function(){            
                var reg_consultant = $('#reg_consultant').val()
                $.ajax({
                    url: '/registrar/studentlist',
                    type: 'GET',
                    dataType: 'json',
                    data:{
                        action      :  'updatesignatory',
                        formid: 'nstpel_reg_consultant',
                        reg_consultant: reg_consultant,
                        // formid: 'summaryofallstudents',
                        syid        :  $('#nstpel-syid').val(),
                        acadprogid: '0',
                        levelid: '0'
                        // dataid: dataid,
                        // title: title,
                        // name: name,
                        // label: label
                    },
                    success: function(data){
                        if(data == 1)
                        {
                            toastr.success('Updated successfully!')
                        }else{
                            toastr.error('Something went wrong!')
                        }
                    }
                })    

            })
            $(document).on('click','#btn-saveessignatories', function(){            
                var reg_consultant = $('#reg_consultant').val()
                var syid = $('#es-syid').val();
                var semid = $('#es-semid').val();
                var department = $('#es-department').val();
                var registrar = $('#es_registrar').val();
                var president = $('#es_president').val();
                

                $.ajax({
                    url: '/registrar/studentlist',
                    type: 'GET',
                    dataType: 'json',
                    data:{
                        action          : 'updatesignatory',
                        formid          : 'es',
                        reg_consultant  : reg_consultant,
                        syid            : syid,
                        semid           : semid,
                        acadprogid      : department,
                        registrar       : registrar,
                        president       : president,
                        levelid         : '0'
                    },
                    success: function(data){
                        if(data == 1)
                        {
                            toastr.success('Updated successfully!')
                        }else{
                            toastr.error('Something went wrong!')
                        }
                    }
                })    

            })
            $(document).on('click','.btn-export-nstpel-sy', function(){
                var nstpcomp = $(this).attr('data-nstpcomp');
                var exporttype = 'persy';
                var reporttype =  $('#nstpel-reporttype').val()
                var filetype = $(this).attr('data-exporttype');
                window.open('/registrar/studentlist?type=nstpel&action=exportexcel&filetype='+filetype+'&reporttype='+reporttype+'&syid='+$('#nstpel-syid').val()+'&semid='+$('#nstpel-semid').val()+'&nstpcomp='+nstpcomp+'&exporttype='+exporttype,'_blank')
            })
            
        })
    </script>
    
@endsection
