
@extends('registrar.layouts.app')
@section('headerjavascript')
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
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
        .icheck-primary[class*="icheck-"] > label {
            padding-left: 22px !important;
            line-height: 18px;
        }

        .icheck-primary[class*="icheck-"] > input:first-child + input[type="hidden"] + label::before, .icheck-primary[class*="icheck-"] > input:first-child + label::before {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            margin-left: -22px;
        }

        .icheck-primary[class*="icheck-"] > input:first-child:checked + input[type="hidden"] + label::after,
        .icheck-primary[class*="icheck-"] > input:first-child:checked + label::after {
            top: 0px;
            width: 4px;
            height: 8px;
            left: 0px;
        }
        
        .table td, .table th{
            padding: 2px !important;
        }
    </style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">Grade Weighted Average Ranking</h1>
                </div><!-- /.col -->
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">GWA</li>
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
                            <select class="form-control" id="select-syid">
                                @foreach($schoolyears as $sy)
                                    <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Select Semester</label>
                            <select class="form-control" id="select-semid">
                                @foreach($semesters as $semester)
                                    <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Select Grade Level</label>
                            <select class="form-control" id="select-levelid">
                                <option value="0">All college levels</option>
                                @foreach(collect($gradelevels)->where('acadprogid','6')->values() as $gradelevel)
                                    <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-3">
                            <label>Select Month</label>
                            <select class="form-control" id="select-monthid">
                                @for($x = 1; $x <= 12; $x++)
                                    <option value="{{$x}}">{{date('F', strtotime(date('Y-'.$x)))}}</option>
                                @endfor
                            </select>
                        </div> --}}
                        <div class="col-md-3 text-right">
                            <label>&nbsp;</label>
                            <br/>
                            <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> &nbsp;Generate</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="results-container"></div>
    



    <!-- jQuery -->
    @endsection
    @section('footerjavascript')
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#container-results').hide();
            $('#btn-generate').on('click', function(){
                var syid = $('#select-syid').val();
                var semid = $('#select-semid').val();
                var levelid = $('#select-levelid').val();
                var monthid = $('#select-monthid').val();
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/printable/gwaranking?action=generate',
                    type:'GET',
                    // dataType: 'json',
                    data: {
                        syid        :  syid,
                        semid       :  semid,
                        levelid      :  levelid,
                        monthid      :  monthid
                    },
                    success:function(data) {
                        $('#results-container').empty()
                        $('#results-container').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        var table = $("#example").DataTable({
        scrollY:        true,
        scrollX:       true,
                            bSort: false,
        scrollCollapse: true,
        // paging:         false,
                            pageLength : 20,
        fixedColumns:   {
            left: 1,
            right: 1
        }
                            // retreive: true,
                            // pageLength : 10,
                            // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']],
                            // bSort: false
                            // "bPaginate": false,
                            // "bInfo" : false,
                            // "bFilter" : false,
                            // "order": [[ 1, 'asc' ]]
                        });
                    }
                })
            })
            $(document).on('click', '#btn-exporttopdf', function(){
                var syid = $('#select-syid').val();
                var semid = $('#select-semid').val();
                var levelid = $('#select-levelid').val();
                var monthid = $('#select-monthid').val();

                window.open("/printable/gwaranking?action=export&exporttype=pdf&syid="+syid+"&semid="+semid+"&levelid="+levelid+"&monthid="+monthid,"_blank");

            })
        })
    </script>
@endsection
