
@extends('registrar.layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Monthly Enrollment Statistics</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Monthly Enrollment Statistics</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    <label>Select School Year</label>
                    <select class="form-control  select2" id="select-syid">
                        @foreach(DB::table('sy')->get() as $eachsy)
                            <option value="{{$eachsy->id}}" @if($eachsy->isactive == 1) selected @endif>{{$eachsy->sydesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Select Semester</label>
                    <select class="form-control  select2" id="select-semid">
                        @foreach(DB::table('semester')->get() as $eachsemester)
                            <option value="{{$eachsemester->id}}">{{$eachsemester->semester}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Select Period</label>
                    
                    <input type="text" class="form-control float-right" id="select-period">
                </div>
                {{-- <div class="col-md-12 mt-4">
                    
                    <div class="form-group clearfix">
                        @foreach(DB::table('studentstatus')->where('id','!=',0)->get() as $eachstatus)
                        <div class="icheck-primary d-inline">
                        <input type="checkbox" id="checkboxPrimary{{$eachstatus->id}}">
                        <label for="checkboxPrimary{{$eachstatus->id}}">
                            {{$eachstatus->description}}
                        </label>
                        &nbsp;
                        </div>
                        @endforeach
                    </div>
                </div> --}}
                <div class="col-md-2 text-right align-self-end">
                    <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
                </div>
            </div>
        </div>
    </div>
    <div id="container-filter">
    </div>
    

    @endsection
    @section('footerjavascript')
    <script>
        $('#select-period').daterangepicker()
        $(document).ready(function(){            
            $('#btn-generate').on('click', function(){
                var dateperiod = $('#select-period').val();
                var syid = $('#select-syid').val();
                var semid = $('#select-semid').val();
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/registrar/reports/monthly',
                    type:'GET',
                    // dataType: 'json',
                    data: {
                        action      :  'getstudents',
                        dateperiod        :  dateperiod,
                        syid        :  syid,
                        semid       :  semid
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
            $(document).on('click','#btn-exportpdf', function(){
                var dateperiod = $('#select-period').val();
                var syid = $('#select-syid').val();
                var semid = $('#select-semid').val();
                window.open('/registrar/reports/monthly?action=export&dateperiod='+dateperiod+'&syid='+syid+'&semid='+semid,'_blank')
            })
        })
    </script>
@endsection
