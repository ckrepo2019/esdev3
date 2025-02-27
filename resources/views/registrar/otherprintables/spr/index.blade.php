
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@extends('registrar.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Student  Permanent Record</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student  Permanent Record</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="card">
        <div class="card-header p-1">
            <div class="row">
                <div class="col-md-6">
                    <label>Select Student</label>
                    <select class="form-control  select2" id="select-student">
                        @foreach($students as $student)
                            <option value="{{$student->id}}">{{$student->lastname}}, {{$student->firstname}}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="col-md-3">
                    <label>Select School Year</label>
                    <select class="form-control" id="select-syid">
                        @foreach(DB::table('sy')->get() as $sy)
                            <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">s
                    <label>Select Semester</label>
                    <select class="form-control" id="select-semid">
                        @foreach(DB::table('semester')->get() as $semester)
                            <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Select Year Level</label>
                    <select class="form-control" id="select-levelid">
                        @foreach(DB::table('gradelevel')->where('acadprogid',6)->where('deleted','0')->get() as $level)
                            <option value="{{$level->id}}">{{$level->levelname}}</option>
                        @endforeach
                    </select>
                </div> --}}
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
        $('#select-student').on('change', function(){
            $('#container-filter').empty()
        })
        $('#btn-generate').on('click', function(){
            var studentid = $('#select-student').val();
            var syid = $('#select-syid').val();
            var semid = $('#select-semid').val();
            var levelid = $('#select-levelid').val();
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            $.ajax({
                url: '/student/permrecord/index',
                type:'GET',
                // dataType: 'json',
                data: {
                    action      :  'getrecords',
                    studentid        :  studentid
                    // syid        :  syid,
                    // semid       :  semid,
                    // levelid     :  levelid
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
        $(document).on('click','.btn-export-pdf', function(){
            var studentid = $('#select-student').val();
            // var syid        = $(this).attr('data-syid');
            // var semid       = $(this).attr('data-semid');
            // var levelid     = $(this).attr('data-levelid');
            var registrar   = $('.registrar-name').val();
            window.open("/student/permrecord/index?studentid="+studentid+"&action=export&registrar="+registrar,'_blank');
        }) 
    </script>
@endsection
