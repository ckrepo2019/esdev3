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
                    <h1 class="m-0">Peer Evaluation Setup</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "> <a href="/home">Home</a> </li>
                        <li class="breadcrumb-item active">Peer Evaluation Setup</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card shadow">
                <div class="card-header">
                    {{-- <button type="button" class="btn btn-primary mr-2 btn_modal_peer_evaluation">
                        + Add Criteria
                    </button> --}}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered table-valign-middle" style="width: 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Criteria</th>
                                            <th class="text-center" style="width: 96px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl_criterias">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered table-valign-middle" style="width: 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Scale</th>
                                            <th class="text-center" style="width: 96px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl_likert_scale">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script>
        $(document).ready(function() {
            getAllEvalCriteria()
            getAllEvalLikertScale()

            $(document).on('click', '.btn_remove_criteria', function() {
                console.log('sfsdfsdf');
                var url = "{{ route('removeCriteria', ['id' => '']) }}" + $(this).data('id');

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        notify(response.status, response.message);
                        getAllEvalCriteria()
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                    }
                })
            })

            $(document).on('click', '.btn_remove_likert', function() {
                console.log('sfsdfsdf');
                var url = "{{ route('removeLikert', ['id' => '']) }}" + $(this).data('id');

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        notify(response.status, response.message);
                        getAllEvalLikertScale()
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                    }
                })
            })

            $(document).on('click', '.btn_add_criteria', function() {
                console.log('sfsdfsdf');
                if (!$('#criteria_name').val()) {
                    $('#criteria_name').addClass('is-invalid');
                    notify('error', 'Criteria Name is required!');
                    return
                }

                $.ajax({
                    type: "GET",
                    url: "{{ route('storeEvalCriteria') }}",
                    data: {
                        text: $('#criteria_name').val(),
                    },
                    success: function(response) {
                        notify(response.status, response.message);
                        getAllEvalCriteria()
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                    }
                })
            })

            $(document).on('click', '.btn_add_likert', function() {
                console.log('sfsdfsdf');
                if (!$('#likertName').val()) {
                    $('#likertName').addClass('is-invalid');
                    notify('error', 'Likert Name is required!');
                    return
                }
                if (!$('#sorting').val()) {
                    $('#sorting').addClass('is-invalid');
                    notify('error', 'Sorting is required!');
                    return
                }

                $.ajax({
                    type: "GET",
                    url: "{{ route('storeLikert') }}",
                    data: {
                        text: $('#likertName').val(),
                        sorting: $('#sorting').val()
                    },
                    success: function(response) {
                        notify(response.status, response.message);
                        getAllEvalLikertScale()
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                    }
                })
            })
        });

        function getAllEvalCriteria() {
            $.ajax({
                type: "GET",
                url: "{{ route('getAllEvalCriteria') }}",
                success: function(response) {
                    console.log(response)
                    if (response) {
                        $('#tbl_criterias').empty()
                        response.forEach(element => {
                            $('#tbl_criterias').append(`
                            <tr>
                                <td>${element.text}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm btn_edit_criteria" data-id="${element.id}"><i class="fas fa-edit"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm btn_remove_criteria" data-id="${element.id}"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            `);
                        });
                        $('#tbl_criterias').append(`
                            <tr>
                                <td>
                                    <form >
                                        @csrf
                                        <div class="form-group">
                                            <label for="criteria_name">Criteria Name</label>
                                            <input type="text" class="form-control" id="criteria_name"
                                                name="criteria_name" required>
                                        </div>
                                        </form>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary btn_add_criteria" style="width: 100%;">+ Add</button>
                                </td>
                            </tr>
                        `);


                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON);
                }
            })
        }

        function getAllEvalLikertScale() {
            $.ajax({
                type: "GET",
                url: "{{ route('getAllEvalLikert') }}",
                success: function(response) {
                    console.log(response)
                    if (response) {
                        $('#tbl_likert_scale').empty()
                        response.forEach(element => {
                            $('#tbl_likert_scale').append(`
                            <tr>
                                <td>${element.text}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm btn_edit_likert" data-id="${element.id}"><i class="fas fa-edit"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm btn_remove_likert" data-id="${element.id}"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            `);
                        });
                        $('#tbl_likert_scale').append(`
                            <tr>
                                <td>
                                    <form method="POST">
                                        @csrf
                                        <div class="row">
                                           
                                                <div class="form-group col-md-8">
                                                    <label for="criteria_name">Scale Name</label>
                                                    <input type="text" class="form-control" id="likertName"
                                                        name="likertName" required>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="criteria_name">Sorting</label>
                                                    <input type="number" class="form-control" min="0" max="100" id="sorting"
                                                        name="sorting" required>
                                                </div>
                                        </div>

                                    </form>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary btn_add_likert" style="width: 100%;">+ Add</button>
                                </td>
                            </tr>
                        `);


                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON);
                }
            })
        }
    </script>
@endsection
