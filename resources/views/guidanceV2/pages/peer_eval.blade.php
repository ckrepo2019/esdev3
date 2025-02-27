@extends('guidanceV2.layouts.app2')

@section('pagespecificscripts')
    <style>
        .align-middle td {
            vertical-align: middle;
        }
    </style>
@endsection



@section('content')
    <!-- Modal -->
    <div class="modal fade" id="modalPeerEvaluation" index="-1"
        style="font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Peer Evaluation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-danger" id="peer_eval_pdf_btn"><i
                                        class="far fa-file-pdf"></i>
                                    (PDF)
                                </button>
                                <button type="button" class="btn btn-success" id="peer_eval_excel_btn"><i
                                        class="far fa-file-excel"></i>
                                    (EXCEL)
                                </button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <table class="table table-bordered table-striped table-valign-middle" id="tbl_peer_eval"
                        style="width: 100%;">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Teacher Name</th>
                                <th>Designation</th>
                                <th>Evaluated</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Peer Evaluation Form -->
    <div class="modal fade" id="peerEvaluationForm" index="100"
        style="font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Peer Evaluation Form</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-sm table-valign-middle">
                        <thead>
                            <tr class="text-center">
                                <th rowspan="2" width="50%">
                                    <h6 id="teacher_name">CLYDE BACONGUIS</h6>
                                    <span>(Criteria)</span>
                                </th>
                            </tr>

                            <tr class="text-center">
                                @foreach (DB::table('guidance_likert_scale')->where('deleted', 0)->orderBy('sorting')->get() as $item)
                                    <th>{{ $item->text }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody id="table-peer-evaluation">
                            @foreach (DB::table('guidance_eval_setup')->where('deleted', 0)->get() as $item)
                                <tr>
                                    <td>{{ $item->text }}
                                    </td>
                                    @foreach (DB::table('guidance_likert_scale')->where('deleted', 0)->orderBy('sorting')->get() as $scale)
                                        <td class="text-center"><input type="radio" name="rd_{{ $item->id }}"
                                                data-id="{{ $scale->id }}" value="{{ $scale->id }}"
                                                data-desc="{{ $scale->text }}"></td>
                                    @endforeach
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>




    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Peer Evaluation</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "> <a href="/home">Home</a> </li>
                        <li class="breadcrumb-item active">Peer Evaluation</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card shadow">
                <div class="card-header">
                    <button type="button" class="btn btn-primary mr-2 btn_modal_peer_evaluation">
                        + Evaluate Peer
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table-hover table table-striped align-middle" id="tbl_sections" style="width: 100%;">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Section Name</th>
                                    <th>Grade Level</th>
                                    <th>Class Type</th>
                                    <th>Adviser</th>
                                    <th>Room</th>
                                    <th>Enrolled</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Section A</td>
                                    <td>Grade 1</td>
                                    <td>Regular</td>
                                    <td>John Doe</td>
                                    <td>Room 101</td>
                                    <td>30</td>
                                    <td><a href="#" class="btn btn-primary btn-sm">View</a></td>
                                </tr>
                                <tr>
                                    <td>Section B</td>
                                    <td>Grade 2</td>
                                    <td>Special</td>
                                    <td>Jane Smith</td>
                                    <td>Room 201</td>
                                    <td>25</td>
                                    <td><a href="#" class="btn btn-primary btn-sm">View</a></td>
                                </tr>
                                <tr>
                                    <td>Section C</td>
                                    <td>Grade 3</td>
                                    <td>Regular</td>
                                    <td>Mike Johnson</td>
                                    <td>Room 301</td>
                                    <td>35</td>
                                    <td><a href="#" class="btn btn-primary btn-sm">View</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('footerjavascript')
    <script>
        $(document).ready(function() {
            $('.btn_modal_peer_evaluation').on('click', function() {
                $('#modalPeerEvaluation').modal('show');
            })

            $(document).on('click', '.btn_evaluate_peer', function() {
                let name = $(this).data('name');
                $('#teacher_name').text(name);
                $('#peerEvaluationForm').modal()
            })

            loadTeachers()
        });




        function loadTeachers(data) {
            $.ajax({
                type: "GET",
                url: "/guidance/getAllTeachers",
                data: {
                    data: data
                },
                success: function(data) {
                    console.log(data);
                    $('#tbl_peer_eval').DataTable({
                        autowidth: false,
                        destroy: true,
                        data: data,
                        lengthChange: false,
                        columns: [{
                                data: "userid",
                                className: 'text-center',
                            },
                            {
                                data: null,
                                render: function(type, data, row) {
                                    return (row.firstname || row.lastname) ?
                                        row.firstname + (row.lastname ? ' ' + row.lastname :
                                            '') :
                                        'N/A'

                                }
                            },

                            {
                                data: "utype",
                            },
                            {
                                data: null,
                                className: 'text-center',
                                render: function(type, data, row) {
                                    return row.evaluated ?
                                        '<i class="fas fa-check text-success"></i>' :
                                        '<i class="fas fa-times text-danger"></i>'
                                }
                            },
                            {
                                data: null,
                                className: 'text-center',
                                render: function(type, data, row) {
                                    if (row.evaluated) {
                                        return `<button class="btn btn-block btn-sm btn-primary" style="width: 100%;" >View</button>`;
                                    } else {
                                        return `<button class="btn btn-block btn-sm btn-warning btn_evaluate_peer" data-name="${row.firstname} ${row.lastname}" style="width: 100%;" >Evaluate</button>`;
                                    }
                                }
                            },
                        ],
                    })
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching teacher data:', error);
                    alert(
                        'An error occurred while fetching the teacher data. Please try again.'
                    );
                }
            })
        }


        function load_appointments(data) {
            console.log(data)
            $('#tbl_appointment').DataTable({
                autowidth: false,
                destroy: true,
                data: data,
                lengthChange: false,
                columns: [{
                        data: "studname",
                    },
                    {
                        data: "formatted_filleddate",
                    },
                    {
                        data: 'formatted_counselingdate',
                    },
                    {
                        data: 'processingofficer',
                    },
                    {
                        data: 'status',
                        render: function(type, data, row) {
                            return `<span class="badge ${row.status === 0 ? 'badge-primary' : row.status === 1 ? 'badge-warning' : 'badge-success'}">${row.status === 0 ? 'New' : row.status === 1 ? 'Pending' : 'Done'}</span>`;
                        }
                    },
                    {
                        data: null,
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<div class="btn-group">
                                    <a type="button" href="javascript:void(0)" class="btn btn-default"> <i class="far fa-edit text-primary"></i> </a>
                                    <a type="button" href="javascript:void(0)" class="btn btn-default"> <i class="far fa-trash-alt text-danger"></i> </a> 
                                </div>`;
                        }
                    }

                ],
            })
        }
    </script>
@endsection
