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

    </style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Certificate of Enrollment</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Certificate of Enrollment</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="card">
        <div class="card-header">
            <div class="row mb-2">
                <div class="col-md-6">
                    <label>Select Student</label>
                    <select class="form-control  select2" id="select-student">
                        @foreach($students as $student)
                            <option value="{{$student->id}}">{{$student->lastname}}, {{$student->firstname}}</option>
                        @endforeach
                    </select>
                </div>
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
            </div>
            <div class="row mb-2">
                <div class="col-md-3">
                    <label>Select Template</label>
                    <select class="form-control" id="select-template">
                        <option value="elem">Elem</option>
                        <option value="jhs">JHS</option>
                        <option value="shs">SHS</option>
                        <option value="college">College</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Given this date</label>
                    <input type="date" class="form-control" id="givendate" value="{{date('Y-m-d')}}"/>
                </div>
                <div class="col-md-6 text-right">
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                </div>
            </div>
        </div>
    </div>
    <div id="container-filter">
    </div>
    
    {{-- <div class="modal fade" id="modal-uploadphoto" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="student-name"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body"  id="reqs-container">
                    
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-modal-close">Close</button>
                    <button type="button" class="btn btn-primary" id="submit-newprogram">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div> --}}



    <!-- jQuery -->
    @endsection
    @section('footerjavascript')
    <script>
        $('.select2').select2({
          theme: 'bootstrap4'
        })
        $('#btn-generate').on('click', function(){
            var studid = $('#select-student').val();
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            var template = $('#select-template').val();
            var givendate = $('#givendate').val();
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            $.ajax({
                url: '/printable/certification/generate',
                type:'GET',
                // dataType: 'json',
                data: {
                    studid      :  studid,
                    syid        :  syid,
                    semid       :  semid,
                    givendate   :  givendate,
                    template    :  template
                },
                success:function(data) {
                    $('#container-filter').empty()
                    $('#container-filter').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        })
    </script>
@endsection
