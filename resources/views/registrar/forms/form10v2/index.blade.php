<!-- Font Awesome -->
{{-- <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}"> --}}
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@extends($extends.'.layouts.app')

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
        
        .alert {
            position: relative;
            padding: .75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: .25rem;
        }
        .alert-primary {
            color: #004085;
            background-color: #cce5ff;
            border-color: #b8daff;
        }
        .alert-secondary {
            color: #383d41;
            background-color: #e2e3e5;
            border-color: #d6d8db;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .alert-warning {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeeba;
        }
        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }
        .alert-dark {
            color: #1b1e21;
            background-color: #d6d8d9;
            border-color: #c6c8ca;
        }
        .alert-pale-green{
            background-color: white;
            border-color: #c3e6cb;
            border-radius: 15px;
        }

    
        #modal-settings-view .modal {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            overflow: hidden;
        }

        #modal-settings-view .modal-dialog {
            position: fixed;
            margin: 0;
            width: 100%;
            height: 100%;
            padding: 0;
        }
        @media (min-width: 576px)
        {
            #modal-settings-view .modal-dialog {
                max-width:  unset !important;
                margin: unset !important;
            }
        }
        #modal-settings-view .modal-content {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            border: 2px solid #3c7dcf;
            border-radius: 0;
            box-shadow: none;
        }

        #modal-settings-view .modal-header {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 50px;
            padding: 10px;
            background: #6598d9;
            border: 0;
        }

        #modal-settings-view .modal-title {
            font-weight: 300;
            font-size: 2em;
            color: #fff;
            line-height: 30px;
        }

        #modal-settings-view .modal-body {
            position: absolute;
            top: 50px;
            bottom: 60px;
            width: 100%;
            font-weight: 300;
            overflow: auto;
            background-color: rgba(0,0,0,.0001) !important;
        }
        #modal-settings-view .modal-footer {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            height: 60px;
            padding: 10px;
            background: #f1f3f5;
        }
    </style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">School Form 10</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">School Form 10</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="card">
        <div class="card-header">
            {{-- @if($acadprogid == 5)
            <div class="row mb-2">
                <div class="col-md-3">
                    <label>Select School Year</label>
                    <select class="form-control" id="select-syid">
                        @foreach(DB::table('sy')->get() as $sy)
                            <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Select Semester</label>
                    <select class="form-control" id="select-semid">
                        @foreach(DB::table('semester')->get() as $semester)
                            <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Select Grade Level</label>
                    <select class="form-control" id="select-levelid">
                        @foreach(DB::table('gradelevel')->where('acadprogid',$acadprogid)->where('deleted','0')->orderBy('sortid','asc')->get() as $gradelevel)
                            <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 text-right">
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-primary" id="btn-getsections"><i class="fa fa-sync"></i> Get sections</button>
                </div>
            </div>
            @else --}}
            <div class="row mb-2">
                <div class="col-md-5">
                    <label>Select Student</label>
                    <select class="form-control select2" id="select-studentid">
                        @foreach($students as $student)
                            <option value="{{$student->id}}">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Select Academic Program</label>
                    <select class="form-control" id="select-acadprogid">
                        @foreach(DB::table('academicprogram')->where('id','<',6)->get() as $academicprogram)
                            @if(DB::table('gradelevel')->where('acadprogid', $academicprogram->id) ->where('deleted','0')->count()>0)
                                <option value="{{$academicprogram->id}}">{{$academicprogram->acadprogcode}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 text-right">
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-secondary" id="btn-settings" data-toggle="modal" data-target="#modal-settings-view"><i class="fa fa-cogs"></i>&nbsp;&nbsp;&nbsp;&nbsp;Settings</button>
                </div>
                <div class="col-md-12 mt-2">
                    <button type="button" class="btn btn-primary" id="btn-getrecords"><i class="fa fa-sync"></i> Get Records</button>

                </div>
            </div>
            {{-- @endif --}}
        </div>
    </div>
    <div id="container-filter">
    </div>
    
<div class="modal fade" id="modal-settings-view">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="setting-title"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="resultscontainer">

        </div>
        <div class="modal-footer justify-content-between" hidden>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          {{-- <button type="button" class="btn btn-primary" id="btn-edit-submit">Save changes</button> --}}
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
    @endsection
    @section('footerjavascript')
    <script>
        $('.select2').select2({
          theme: 'bootstrap4'
        })
        $(document).ready(function(){
            $('#select-acadprogid').on('change', function(){
                if($(this).val() == '5')
                {
                    $('#btn-settings').hide()
                }else{
                    $('#btn-settings').show()
                }
                $('#container-filter').empty()
            })
            $('#select-studentid').on('change', function(){
                $('#container-filter').empty()
            })
            
            $('#btn-getrecords').on('click', function(){
                var studentid = $('#select-studentid').val();
                var acadprogid = $('#select-acadprogid').val();
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/reports_schoolform10v2/getrecords',
                    type:'GET',
                    // dataType: 'json',
                    data: {
                        studentid        :  studentid,
                        acadprogid       :  acadprogid
                    },
                    success:function(data) {
                        $('#container-filter').empty()
                        $('#container-filter').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        // $('.card-manual').hide()
                    }
                })
            })
            $(document).on('input','.input-header', function(){
                var headerinputs = $(this).closest('.card-header').find('input');
                var inputwithval = 0;
                headerinputs.each(function(){
                    if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
                    {
                        inputwithval+=1;
                    }
                })
                if(inputwithval == 1)
                {
                    $(this).closest('.card').find('.card-manual').prop('hidden',true);
                }else{
                $(this).closest('.card').find('.card-manual').removeAttr('hidden');
                }
            })
            $(document).on('click','.btn-submit-header', function(){           



                var studentid = $('#select-studentid').val();
                var acadprogid = $('#select-acadprogid').val();
                var thiscardheader = $(this).closest('.card').find('.card-header')
                var recordid = $(this).attr('data-recordid');
                var schoolname = thiscardheader.find('.input-schoolname').val();
                var schoolid = thiscardheader.find('.input-schoolid').val();
                var district = thiscardheader.find('.input-district').val();
                var division = thiscardheader.find('.input-division').val();
                var region = thiscardheader.find('.input-region').val();
                var levelid = thiscardheader.find('.input-levelid').attr('data-id');
                var levelname = thiscardheader.find('.input-levelid').val();
                var sydesc = thiscardheader.find('.input-sydesc').val();
                var sectionname = thiscardheader.find('.input-sectionname').val();
                var teachername = thiscardheader.find('.input-adviser').val();

                var trackname = thiscardheader.find('.input-track').val();
                var strandname = thiscardheader.find('.input-strand').val();
                var semid = thiscardheader.find('.input-semid').val();

                var semremarks = thiscardheader.find('.input-semremarks').val();
                var datechecked = thiscardheader.find('.input-datechecked').val();
                var preparedby = thiscardheader.find('.input-preparedby').val();
                var certifiedby = thiscardheader.find('.input-certifiedby').val();

                
                $.ajax({
                    url: '/reports_schoolform10v2/addnewheader',
                    type:'GET',
                    // dataType: 'json',
                    data: {
                        studentid      :  studentid,
                        acadprogid     :  acadprogid,
                        recordid       :  recordid,
                        schoolname     :  schoolname,
                        schoolid       :  schoolid,
                        district       :  district,
                        division       :  division,
                        region         :  region,
                        levelid        :  levelid,
                        levelname      :  levelname,
                        sydesc         :  sydesc,
                        sectionname    :  sectionname,
                        teachername    :  teachername,
                        trackname      :  trackname,
                        strandname     :  strandname,
                        semid          :  semid,
                        semremarks     :  semremarks,
                        datechecked    :  datechecked,
                        preparedby     :  preparedby,
                        certifiedby    :  certifiedby
                    },
                    success:function(data) {
                        $('#btn-getrecords').click()
                    }
                })
            })
            $('#btn-settings').on('click', function(){
                
                var acadprogid = $('#select-acadprogid').val();
                if(acadprogid == 5)
                {
                    $('#setting-title').text('Senior High School Template')
                }
                else if(acadprogid == 4)
                {
                    $('#setting-title').text('Junior High School Template')
                }
                else if(acadprogid == 3)
                {
                    $('#setting-title').text('Grade School Template')
                }
                // $('#modal-settings-view').modal('show');

                $.ajax({
                    url: '/reports_schoolform10v2/gettemplate',
                    type: 'GET',
                    data: {
                        acadprogid   :   acadprogid
                    },
                    // dataType: 'json',
                    success:function(data)
                    {
                        $('#resultscontainer').empty();
                        $('#resultscontainer').append(data);

                    }
                })
            })
        })
    </script>
@endsection
