@extends('guidanceV2.layouts.app2')

@section('pagespecificscripts')
    <style>
        .align-middle td {
            vertical-align: middle;
        }
    </style>
@endsection



@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Classroom Instruction Evaluation</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "> <a href="/home">Home</a> </li>
                        <li class="breadcrumb-item active">Classroom Instruction Evaluation</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card shadow">
                <div class="card-header">
                    <button type="button" class="btn btn-primary mr-2 ">
                        <i class="fas fa-plus"></i>New User
                    </button>
                    <button type="button" class="btn btn-danger mr-2 btn_archive">
                        <i class="far fa-trash-alt"></i> Archive
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table-hover table-bordered table table-striped align-middle" id="tbl_instruction_eval"
                            style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Designations</th>
                                    <th>Email Ad</th>
                                    <th>Phone Number</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Bridgette May Gerardo
                                    </td>
                                    <td>Guidance Associate
                                    </td>
                                    <td>bmaygerardo@gmail.com</td>
                                    <td>09768990789</td>
                                    <td>offline</td>
                                    <td>
                                        <div class="btn-group">
                                            <a type="button" href="javascript:void(0)" class="btn btn-default"> <i
                                                    class="far fa-edit text-primary"></i> </a>
                                            <a type="button" href="javascript:void(0)" class="btn btn-default"> <i
                                                    class="far fa-trash-alt text-danger"></i> </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Bridgette May Gerardo
                                    </td>
                                    <td>Guidance Associate
                                    </td>
                                    <td>bmaygerardo@gmail.com</td>
                                    <td>09768990789</td>
                                    <td>offline</td>
                                    <td>
                                        <div class="btn-group">
                                            <a type="button" href="javascript:void(0)" class="btn btn-default"> <i
                                                    class="far fa-edit text-primary"></i> </a>
                                            <a type="button" href="javascript:void(0)" class="btn btn-default"> <i
                                                    class="far fa-trash-alt text-danger"></i> </a>
                                        </div>
                                    </td>
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
        $(document).ready(function() {});

        function load_appointments(data) {
            console.log(data)
            $('#').DataTable({
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
