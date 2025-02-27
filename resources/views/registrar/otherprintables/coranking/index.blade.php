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
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
@extends('registrar.layouts.app')

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

        .alert-pale-green {
            background-color: white;
            border-color: #c3e6cb;
            border-radius: 15px;
        }
    </style>
    {{-- <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Certification of Ranking</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Certification of Ranking</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section> --}}
    <section class="content-header">
        <div class="container-fluid">
            <h1><i class="fas fa-cog"></i>Certification of Ranking</h1>
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">{{ isset($page) ? $page : 'Certification of Ranking' }}</li>
            </ol>
        </div>
    </section><br>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">

                        <table id="studentList" class="table table-sm table-bordered table-valign-middle table-hover w-100">
                            <thead class="thead-light">
                                <tr>
                                    {{-- <th></th> --}}
                                    <th width="5">Student ID</th>
                                    <th width="35">Student Name</th>
                                    <th width="35">Grade Level</th>
                                    {{-- <th class="text-center">Status</th> --}}
                                    <th width="5" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="studentListBody">
                            </tbody>
                        </table>

                    </div>
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

        studentListTable()

        function studentListTable() {

            $("#studentList").DataTable({
                destroy: true,
                searching: true,
                initComplete: function() {
                    var api = this.api();
                    var searchBox = $(
                            '<input type="search" class="form-control form-control-sm" placeholder="Type to search...">'
                        )
                        .on('input', function() {
                            api.search(this.value).draw();
                        });
                    $('.dataTables_filter label').html('<span class="me-2"></span>').append(
                        searchBox);
                },
                info: false,
                lengthChange: false,
                autoWidth: false,
                paging: false,
                ajax: {
                    url: '/printable/student_coranking',
                    type: 'GET',
                    dataSrc: function(json) {
                        console.log(json)
                        return json.students;
                    }
                },
                columns: [{
                        "data": "sid"
                    },
                    {
                        "data": "lastname"
                    },
                    {
                        "data": "levelname"
                    },
                    {
                        "data": "firstname"
                    },
                ],
                order: [
                    [1, 'asc']
                ], // Sort by the second column (lastname), ascending
                columnDefs: [

                    {
                        'targets': 0,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(rowData.sid).addClass('align-middle');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(rowData.lastname + ', ' + rowData.firstname).addClass(
                                'align-middle');
                        }
                    },

                    {
                        'targets': 2,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(rowData.levelname).addClass('align-middle');
                        }
                    },

                    {
                        'targets': 3,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var buttons =
                                '<button type="button" class="btn btn-primary btn-sm btn-generate" id="btn-generate" data-id="' +
                                rowData.id +
                                '"><i class="fa fa-sync"></i> Generate</button>';
                            $(td)[0].innerHTML = buttons;
                            $(td).addClass('text-center align-middle');
                        }
                    }

                ],

            });
        }

        $(document).on('click', '#btn-generate', function() {
            // var studid = $('#select-student').val();
            var studid = $(this).data('id');
            // var syid = $('#select-syid').val();
            // var semid = $('#select-semid').val();
            // var template = $('#select-template').val();
            // var givendate = $('#givendate').val();
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            $.ajax({
                url: '/printable/coranking',
                type: 'GET',
                // dataType: 'json',
                data: {
                    action: 'generate',
                    studid: studid
                    // syid        :  syid,
                    // semid       :  semid,
                    // givendate       :  givendate,
                    // template    :  template
                },
                success: function(data) {
                    $('#container-filter').empty()
                    $('#container-filter').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    $('#corankingModal').modal('show');
                }
            })
        })
    </script>
@endsection
