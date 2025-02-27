
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@extends('registrar.layouts.app')
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

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
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Online Enrolled Students</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Online Enrolled Students</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    {{-- <div class="row mb-2">
      <div class="col-12">
        <!-- Custom Tabs --> --}}
        <div class="card">
          <div class="card-header">
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label>Select School Year</label>
                        <select class="form-control" id="syid">
                            @foreach(DB::table('sy')->get() as $sy)
                            <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Select Semester</label>
                        <select class="form-control" id="semid">
                            @foreach(DB::table('semester')->get() as $semester)
                            <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Select Department</label>
                        <select class="form-control" id="department">
                            <option value="all">All</option>
                            <option value="basiced">Basid Education</option>
                            @foreach(DB::table('academicprogram')->get() as $eachacad)
                            <option value="{{$eachacad->id}}">{{$eachacad->acadprogcode}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 text-right">
                        <label>&nbsp;</label><br/>
                        <button type="button" class="btn btn-default" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                    </div>
                </div>
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
        </div>
        <!-- ./card -->
      {{-- </div>
      <!-- /.col -->
    </div> --}}
    <div id="container-filter">
    </div>
    
    @endsection
    @section('footerjavascript')

    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#btn-generate').on('click', function(){
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/registrar/oe?action=generate',
                    type:'GET',
                    data: {
                        syid        :  $('#syid').val(),
                        semid       :  $('#semid').val(),
                        department  :  $('#department').val()
                    },
                    success:function(data) {
                        $('#container-filter').empty()
                        $('#container-filter').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        var table = $("#results-table").DataTable({
                            "ordering": false
                            // retreive: true,
                            // pageLength : 10,
                            // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']],
                            // "bPaginate": false,
                            // "bInfo" : false,
                            // "bFilter" : false,
                            // "order": [[ 1, 'asc' ]]
                        });
                    }
                })
            })
            $('#syid').on('change', function(){
                $('#container-filter').empty()
            })
            $('#semid').on('change', function(){
                $('#container-filter').empty()
            })
            $('#department').on('change', function(){
                $('#container-filter').empty()
            })
            $(document).on('click','#btn-export-pdf', function(){
                
                window.open('/registrar/oe?action=exportpdf&syid='+$('#syid').val()+'&semid='+$('#semid').val()+'&department='+$('#department').val(),'_blank')
            })
        })
    </script>
@endsection
