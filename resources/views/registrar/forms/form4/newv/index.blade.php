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
    .alert-warning {
    color: #856404;
    background-color: #fff3cd;
    border-color: #ffeeba;
}
    </style>
    @php
     
     $activeacadprogs = DB::table('gradelevel')
            ->select('academicprogram.*')
            ->join('academicprogram','gradelevel.acadprogid','=','academicprogram.id')
            ->where('gradelevel.deleted','0')
            ->where('academicprogram.id','!=','2')
            ->where('academicprogram.id','!=','6')
            ->distinct()
            ->orderBy('academicprogram.id')
            ->get();
               
    @endphp
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">School Form 4</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">School Form 4</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="card">
        <div class="card-header">
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
                    <label>Select Academic Program</label>
                    <select class="form-control" id="select-acadprogid">
                        @foreach($activeacadprogs as $activeacadprog)
                            <option value="{{$activeacadprog->id}}">{{$activeacadprog->progname}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Select Year</label>
                    <select class="form-control" id="select-year">
                        @for($to = date('Y'); 2019<$to; $to--)
                          <option value="{{$to}}">{{$to}}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Month</label>
                    <select id="select-month" class="form-control form-control">
                        {{-- <select id="currentmonth" name="selectedmonth" class="col-md-12" style="text-transform:uppercase;"> --}}
                            <option value="01">January</option>
                            <option value="02" >February</option>
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
                        {{-- </select> --}}
                        
                    </select>
                </div>
                {{-- <div class="col-md-9 text-right mt-2">
                    <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate SF5</button>
                </div> --}}
            </div>
            </div>
        </div>
    </div>
    <div id="container-filter" class="container-fluid">
    </div>
    
    @endsection
    @section('footerjavascript')
    <script>
        $('#setup-container').hide();
        $('.select2').select2({
          theme: 'bootstrap4'
        })
        function getresults(){
            var syid = $('#select-syid').val();
            var acadprogid = $('#select-acadprogid').val();
            var selectyear = $('#select-year').val();
            var selectmonth = $('#select-month').val();
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            $.ajax({
                url: '/registar/schoolforms/index',
                type:'GET',
                data: {
                    action        :  'getsf4results',
                    sf        :  4,
                    acadprogid        :  acadprogid,
                    syid        :  syid,
                    selectyear        :  selectyear,
                    selectmonth        :  selectmonth
                },
                success:function(data) {
                    $('#select-section').empty()
                    $('#container-filter').empty()
                        $('#btn-generate').show()
                        $('#container-filter').append(data)

                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                },
                error: function(request, status, error){
                    // toastr.error('Something went wrong!',error)
                    $('#select-section').empty()
                    $('#container-filter').empty()
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        }
        getresults()
        $('#select-syid').on('change', function(){
            getresults()
        })
        $('#select-acadprogid').on('change', function(){
            getresults()
        })
        $('#select-year').on('change', function(){
            getresults()
        })
        $('#select-month').on('change', function(){
            getresults()
        })
        $(document).ready(function(){
            $(document).on('click','#btn-export', function(){
                var syid = $('#select-syid').val();
                var acadprogid = $('#select-acadprogid').val();
                
                window.open("/registar/schoolforms/index?action=getsf6results&syid="+syid+"&acadprogid="+acadprogid+"&export=pdf");
            })
        })
    </script>
@endsection
