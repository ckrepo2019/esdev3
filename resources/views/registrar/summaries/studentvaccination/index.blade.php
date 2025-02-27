<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
@extends('registrar.layouts.app')

@section('headerjavascript')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('content')
    <style>
        .donutTeachers {
            margin-top: 90px;
            margin: 0 auto;
            background: transparent url("{{ asset('assets/images/corporate-grooming-20140726161024.jpg') }}") no-repeat 28% 60%;
            background-size: 30%;
        }

        .donutStudents {
            margin-top: 90px;
            margin: 0 auto;
            background: transparent url("{{ asset('assets/images/student-cartoon-png-2.png') }}") no-repeat 28% 60%;
            background-size: 30%;
        }

        #studentstable {
            font-size: 13px;
        }

        @media (min-width: 768px) {
            .modal-xl {
                width: 90%;
                max-width: 1200px;
            }
        }

        .tableFixHead {
            overflow-y: auto;
            height: 500px;
        }

        td,
        th {
            padding: 3px !important;
        }

        /* #studentstable th    { position: sticky; top: 0; background-color: #eee; z-index: 100;}
    #studentstable thead{
        background-color: #eee !important;
        z-index: 100;
    } */

        /* #studentstable                    {width:100%; font-size:12px;; stext-transform: uppercase; }

    #studentstable thead{

    position: sticky;
    top: 0;
    }
    #studentstable thead th:first-child  {
    position: sticky;
    width: 150px !important;
    left: -20;
    background-color: #fff;
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    z-index: 999 !important
    }
    #studentstable thead th:last-child  {
    position: sticky !important;
    right: -20;
    background-color: #fff;
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    z-index: 999 !important
    }
    #studentstable tbody td:last-child  {
    position: sticky;
    right: -20;
    background-color: #fff;
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    }


    #studentstable tbody td:first-child  {
    position: sticky;
    left: -20;
    background-color: #fff;
    width: 150px !important;
    background-color: #fff;
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    } */
    </style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Students Vaccination Stats</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Students Vaccination Stats</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="card">
        <div class="card-header" style="border: none !important;">
            <div class="row">
                <div class="col-md-3">
                    <label>Select School Year</label>
                    <select class="form-control  select2" id="select-syid">
                        @foreach (DB::table('sy')->get() as $sy)
                            <option value="{{ $sy->id }}" @if ($sy->isactive == 1) selected @endif>
                                {{ $sy->sydesc }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="col-md-3">
                    <label>Select Semester</label>
                    <select class="form-control  select2" id="select-semid">
                        @foreach (DB::table('semester')->get() as $semester)
                            <option value="{{$semester->id}}" @if ($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="col-md-3">
                    <label>Select Grade Level</label>
                    <select class="form-control  select2" id="select-levelid">
                        <option value="0">All</option>
                        @foreach (DB::table('gradelevel')->where('deleted', '0')->orderBy('sortid')->get() as $gradelevel)
                            <option value="{{ $gradelevel->id }}">{{ $gradelevel->levelname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3" hidden>
                    <label>Learning Modality</label>
                    <select class="form-control  select2" id="select-mod">
                        <option value="0">All</option>
                        @foreach (DB::table('modeoflearning')->where('deleted', '0')->get() as $mol)
                            <option value="{{ $mol->id }}">{{ $mol->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Vaccination Status</label>
                    <select class="form-control  select2" id="select-status">
                        <option value="all">All</option>
                        <option value="1" selected>Vaccinated</option>
                        <option value="0">Not vaccinated</option>
                        {{-- <option value="1">Vaccinated</option> --}}
                    </select>
                </div>
                <div class="col-md-12 text-right mt-2">
                    {{-- <label>&nbsp;</label><br/> --}}
                    <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i>
                        Generate</button>
                </div>
            </div>
        </div>
    </div>
    <div id="container-results"></div>
@endsection
@section('footerjavascript')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    {{-- <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script> --}}
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        })

        function generate() {
            var levelid = $('#select-levelid').val()
            var semid = $('#select-semid').val()
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            $.ajax({
                url: '/registrar/reports/notenrolled',
                type: 'GET',
                data: {
                    action: 'filter',
                    levelid: levelid,
                    semid: semid
                },
                success: function(data) {
                    $('#container-results').empty()
                    $('#container-results').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')

                }
            })
        }
        $(document).ready(function() {
            // $('#select-levelid').on('change', function(){
            //     if($(this).val() == 0 || $(this).val() == 14 || $(this).val() == 15 || $(this).val() == 17 || $(this).val() == 18 || $(this).val() == 19 || $(this).val() == 20 || $(this).val() == 21)
            //     {
            //         $('#container-semid').show();
            //     }else{
            //         $('#container-semid').hide();
            //     }
            // })          

            $('#btn-generate').on('click', function() {
                var levelid = $('#select-levelid').val()
                var syid = $('#select-syid').val()
                var mod = $('#select-mod').val()
                var status = $('#select-status').val()
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/printable/studentvacc/index',
                    type: 'GET',
                    data: {
                        action: 'filter',
                        levelid: levelid,
                        syid: syid,
                        mod: mod,
                        status: status
                    },
                    success: function(data) {
                        $('#container-results').empty()
                        $('#container-results').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        $('.table').DataTable({
                            "paging": true,
                            "destroy": true,
                            "lengthChange": false,
                            pageLength: 20,
                            "searching": true,
                            "ordering": false,
                            "info": false,
                            "autoWidth": false,
                            "responsive": false,
                        });
                    }
                })
            })
        })
        $(document).on('click', '#btn-export-pdf', function() {
            var levelid = $('#select-levelid').val()
            var syid = $('#select-syid').val()
            var mod = $('#select-mod').val()
            var status = $('#select-status').val()
            window.open('/printable/studentvacc/index?action=export&exporttype=pdf&levelid=' + levelid + '&syid=' +
                syid + '&mod=' + mod + '&status=' + status, '_blank')
        })
    </script>
@endsection
