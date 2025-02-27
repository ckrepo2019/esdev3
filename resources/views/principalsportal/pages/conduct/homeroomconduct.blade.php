@php

      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

      if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }else if(Session::get('currentPortal') == 1){
        $extend = 'teacher.layouts.app';
      }else{
            if(isset($check_refid->refid)){
                  if($check_refid->refid == 27){
                        $extend = 'academiccoor.layouts.app2';
                  }
            }else{
                  $extend = 'general.defaultportal.layouts.app';
            }
      }
@endphp

@extends($extend)


@section('pagespecificscripts')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
       .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .nav-link.active {
            background-color: rgb(211, 210, 210) !important;
            color: black
        }

        .inactive{
            background-color: #ffffff!important;
            cursor: pointer;
        }

        .nav-tabs{
            border-bottom: 3px solid gray!important;
        }

</style>
@endsection

@section('content')

<section class="content-header ">
    <div class="container-fluid">
        <div class="row mb-2 pt-3">
            <div class="col-sm-6">
                <h1>Homeroom</h1>
            </div>
            <div class="col-sm-6 pt-3">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Character Grade</li>
            </ol>
            </div>
        </div>
    </div>
</section>

<div class="m-3">
    <ul class="nav nav-tabs row" id="studentInfoTabs" role="tablist">
        <li class="nav-item" style="width: 10%;" role="presentation">
            <a href="/setup/character/grade" style="border-bottom: 1px solid gray;color:black!important" class="nav-link inactive fw-600 text-center"   id="conduct-tab" data-bs-toggle="tab"
                data-bs-target="#conduct" role="tab" aria-controls="conduct">Conduct</a>
        </li>
        <li class="nav-item" style="width: 10%; " role="presentation">
            <a href="/setup/character/grade/homeroom" class="nav-link active fw-600 text-center " style="border-bottom: 1px solid gray;color:black!important" id="homeroom-tab" data-id=""
                data-bs-toggle="tab" data-bs-target="#homeroom" role="tab"
                aria-controls="homeroom" aria-selected="true" >Homeroom</a>
        </li>
    </ul>
</div>
<hr class="mx-2">

<section class="content">   
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="mx-2 my-3">
                            <table width=100% class="table table-striped table-bordered table-sm"  id="behavior_table">
                                <thead>
                                    <tr>
                                        <th width="10%" class="text-center">Sort</th>
                                        <th width="20%">Traits</th>
                                        <th width="55%">Description</th>
                                        <th width="5%" class="text-center">%</th>
                                        <th width="10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        <div class="mx-2 my-3">
                            <table width=100% class="table table-striped table-bordered table-sm"  id="behavior_grade_table">
                                <thead>
                                    <tr>
                                        <th width="10%" class="text-center">Sort</th>
                                        <th width="30%">Descriptive Grades</th>
                                        <th width="15%">Non-Numeric Equivalence</th>
                                        <th width="15%" class="">Numeric Equivalence</th>
                                        <th width="15%" class="text-center">Remarks</th>
                                        <th width="10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="behavior_modal" style="display: none;" aria-hidden="true" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                <h4 class="modal-title" style="font-size: 1.1rem !important">Add Subject</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body ">
                <div class="row ">
                    <div class="col-md-12 form-group">
                        <label for="">Sort</label>
                        <input type="number" class="form-control form-control-sm behavior_form" id="behavior_sort">
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-12 form-group">
                        <label for="">Trait Description</label>
                        <input  class="form-control form-control-sm behavior_form" id="behavior_trait">
                    </div>
                </div>
                <div class="row  ">
                    <div class="col-md-12 form-group">
                        <label for="">Conduct Description</label>
                        <input  class="form-control form-control-sm behavior_form" id="behavior_conduct">
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-6 form-group">
                        <div class=" d-inline pt-2">
                            <input type="checkbox" id="behavior_ispercentage" >
                            <label for="ispercentage" style="font-size: 14px">With Percentage
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <input type="number" class="form-control form-control-sm behavior_form" id="behavior_percentage" placeholder="100%" hidden>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-primary w-100" id="behavior_create_button"><i class="fas fa-save"></i> Create</button>
                        <button class="btn btn-sm btn-success w-100" id="behavior_save_button" hidden><i class="fas fa-save" ></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="behavior_grade_modal" style="display: none;" aria-hidden="true" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                <h4 class="modal-title" style="font-size: 1.1rem !important">Values Grade & Equivalence</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body ">
                <div class="row ">
                    <div class="col-md-12 form-group">
                        <label for="">Sort</label>
                        <input type="number" class="form-control form-control-sm behavior_form" id="behavior_grade_sort">
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-12 form-group">
                        <label for="">Descriptive Grades</label>
                        <input  class="form-control form-control-sm behavior_form" id="behavior_grade_description">
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-12 form-group">
                        <label for="">Non-Numeric Equivalence</label>
                        <input  class="form-control form-control-sm behavior_form" id="behavior_nonnumeric">
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-12 form-group">
                        <label for="">Numeric Equivalence</label>
                        <input  class="form-control form-control-sm behavior_form" id="behavior_numeric">
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-12 form-group">
                        <label for="">Remarks</label>
                        <input  class="form-control form-control-sm  behavior_form" id="behavior_remarks">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-primary w-100" id="behavior_grade_create_button"><i class="fas fa-save"></i> Create</button>
                        <button class="btn btn-sm btn-success w-100" id="behavior_grade_save_button" hidden><i class="fas fa-save" ></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('footerjavascript')    
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

    <script>

        $(document).ready(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            });
            get_behaviors()
            get_behavior_grades()
            //create & save behavior
            $(document).on('click', '#behavior_create_button', function() {
                var traits = $('#behavior_trait').val()
                var description = $('#behavior_conduct').val()
                var trait_ispercentage = $('#behavior_ispercentage').is(':checked') ? 1 : 0
                var trait_percentage = $('#behavior_percentage').val()
                var sort = $('#behavior_sort').val()
                $.ajax({
                    type:'GET',
                    url:'/setup/character/grade/traits/create',
                    data:{
                        traits: traits,
                        description: description,
                        trait_ispercentage: trait_ispercentage,
                        trait_percentage: trait_percentage,
                        sortid: sort,
                    },
                    success:function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Behavioral Trait has been created'
                        })
                        get_behaviors()
                    }
                })
            })

            $(document).on('click', '#behavior_save_button', function() {
                var traits = $('#behavior_trait').val()
                var description = $('#behavior_conduct').val()
                var trait_ispercentage = $('#behavior_ispercentage').is(':checked') ? 1 : 0
                var trait_percentage = $('#behavior_percentage').val()
                var sort = $('#behavior_sort').val()
                $.ajax({
                    type:'GET',
                    url:'/setup/character/grade/traits/edit',
                    data:{
                        traits: traits,
                        description: description,
                        trait_ispercentage: trait_ispercentage,
                        trait_percentage: trait_percentage,
                        sortid: sort,
                        id: behavior_id
                    },
                    success:function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Behavioral Trait has been saved'
                        })
                        get_behaviors()
                    }
                })
            })

            //get behavior & get edit behavior
            function get_behaviors(){
                $.ajax({
                    type:'GET',
                    url:'/setup/character/grade/traits/get',
                    success:function(data) {
                        behavior = data
                        load_behavior_table(behavior)
                    }
                })
            }
            var behavior_id;
            $(document).on('click', '#edit_behavior', function() {
                behavior_id = $(this).data('id')
                $.ajax({
                    type:'GET',
                    url:'/setup/character/grade/traits/select',
                    data:{
                        id: behavior_id
                    },
                    success:function(data) {
                        console.log(data)
                        $('#behavior_trait').val(data.traits)
                        $('#behavior_conduct').val(data.description)
                        $('#behavior_ispercentage').prop('checked', data.ispercentage == 1 ? true : false).trigger('change')
                        if(data.ispercentage == 1){
                            $('#behavior_percentage').val(data.percentage)
                        }
                        $('#behavior_sort').val(data.sortid)
                    }
                })
            })

            //delete behavior
            $(document).on('click', '#delete_behavior', function() {
                var id = $(this).data('id')
                Swal.fire({
                        title: 'Delete Conduct Grade',
                        text: "Are you sure you want to delete this trait?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type:'GET',
                                url:'/setup/character/grade/traits/delete',
                                data:{
                                    id: id
                                },
                                success:function(data) {
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Behavioral Trait has been deleted'
                                    })
                                    get_behaviors()
                                }
                            })
                        }
                })
                
            })

            //create & save grade behavior
            $(document).on('click', '#behavior_grade_create_button', function() {
                var description = $('#behavior_grade_description').val()
                var nonnumeric = $('#behavior_nonnumeric').val()
                var numeric = $('#behavior_numeric').val()
                var remarks = $('#behavior_remarks').val()
                var sortid = $('#behavior_grade_sort').val()
                $.ajax({
                    type:'GET',
                    url:'/setup/character/grade/traits/grade/create',
                    data:{
                        description: description,
                        nonnumeric: nonnumeric,
                        numeric: numeric,
                        remarks: remarks,
                        sortid: sortid
                    },
                    success:function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Grade Behavior has been created'
                        })
                        get_behavior_grades()
                    }
                })
            })

            $(document).on('click', '#behavior_grade_save_button', function() {
                var description = $('#behavior_grade_description').val()
                var nonnumeric = $('#behavior_nonnumeric').val()
                var numeric = $('#behavior_numeric').val()
                var remarks = $('#behavior_remarks').val()
                var sortid = $('#behavior_grade_sort').val()
                $.ajax({
                    type:'GET',
                    url:'/setup/character/grade/traits/grade/edit',
                    data:{
                        description: description,
                        nonnumeric: nonnumeric,
                        numeric: numeric,
                        remarks: remarks,
                        sortid: sortid,
                        id: behavior_grade_id
                    },
                    success:function(data) {
                        Toast.fire({
                            type: 'success',
                            title: 'Grade Behavior has been saved'
                        })
                        get_behavior_grades()
                    }
                })
            })

            //get behavior grade & get edit behavior grade
            var behavior_grade_id;
            $(document).on('click', '#edit_grade_behavior', function() {
                behavior_grade_id = $(this).data('id')
                $.ajax({
                    type:'GET',
                    url:'/setup/character/grade/traits/grade/select',
                    data:{
                        id: behavior_grade_id
                    },
                    success:function(data) {
                        console.log(data)
                        $('#behavior_grade_description').val(data.description)
                        $('#behavior_nonnumeric').val(data.nonnumeric)
                        $('#behavior_numeric').val(data.numeric)
                        $('#behavior_remarks').val(data.remarks)
                        $('#behavior_grade_sort').val(data.sortid)
                    }
                })
            })

            function get_behavior_grades(){
                $.ajax({
                    type:'GET',
                    url:'/setup/character/grade/traits/grade/get',
                    success:function(data) {
                        behavior = data
                        load_behavior_grade_table(behavior)
                    }
                })  
            }

            //delete behavior grade
            $(document).on('click', '#delete_grade_behavior', function() {
                var id = $(this).data('id')
                Swal.fire({
                        title: 'Delete Conduct Grade',
                        text: "Are you sure you want to delete this grade?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type:'GET',
                                url:'/setup/character/grade/traits/grade/delete',
                                data:{
                                    id: id
                                },
                                success:function(data) {
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Behavioral Grade has been deleted'
                                    })
                                    get_behavior_grades()
                                }
                            })
                        }
                })
            })

            function load_behavior_grade_table(data){
                $('#behavior_grade_table').DataTable({
                    destroy: true,
                    searching: true,
                    data: data,
                    paging: false,
                    info: false,
                    stateSave: true,
                    columns: [
                        { data: 'sortid' },
                        { data: 'description' },
                        { data: 'nonnumeric' },
                        { data: 'numeric' },
                        { data: 'remarks' },
                        { data: null },
                    ],
                    columnDefs: [
                        {
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 mb-0">${rowData.sortid}</p>`)
                                $(td).addClass('text-center align-middle')

                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 mb-0">${rowData.description}</p>`)
                                $(td).addClass(' align-middle')
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 mb-0">${rowData.nonnumeric}</p>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 mb-0">${rowData.numeric}</p>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 mb-0">${rowData.remarks}</p>`)
                                $(td).addClass('align-middle text-center')
                            }
                        },
                        {
                            'targets': 5,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(
                                    `<a href="javascript:void(0)" class="section_link mb-0" style="white-space: nowrap" data-id="">
                                    <button class="btn btn-sm btn-primary" id="edit_grade_behavior" data-toggle="modal" data-target="#behavior_grade_modal" data-id="${rowData.id}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" id="delete_grade_behavior"  data-id="${rowData.id}"><i class="fas fa-trash"></i></button></a>`
                                ).addClass('text-center align-middle')
                            }
                        },
                    ]
                })
            }

            function load_behavior_table(behavior){
                $('#behavior_table').DataTable({
                    destroy: true,
                    searching: true,
                    data: behavior,
                    paging: false,
                    info: false,
                    stateSave: true,
                    columns: [
                        { data: 'sortid' },
                        { data: 'traits' },
                        { data: 'description' },
                        { data: 'percentage' },
                        { data: null },
                    ],
                    columnDefs: [
                        {
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 mb-0">${rowData.sortid}</p>`)
                                $(td).addClass('text-center align-middle')

                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 mb-0">${rowData.traits}</p>`)
                                $(td).addClass(' align-middle')
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 mb-0">${rowData.description}</p>`)
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(`<p class="p-0 mb-0">${rowData.percentage ? rowData.percentage + '%' : ''}</p>`)
                                $(td).addClass('text-center align-middle')
                            }
                        },
                        {
                            'targets': 4,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td).html(
                                    `<a href="javascript:void(0)" class="section_link mb-0" style="white-space: nowrap" data-id="">
                                    <button class="btn btn-sm btn-primary" id="edit_behavior" data-toggle="modal" data-target="#behavior_modal" data-id="${rowData.id}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" id="delete_behavior"  data-id="${rowData.id}"><i class="fas fa-trash"></i></button></a>`
                                ).addClass('text-center align-middle')
                            }
                        },
                    ]
                })
                var label_text = $($("#behavior_table_wrapper")[0].children[0])[0].children[0]
                $(label_text)[0].innerHTML =
                    '<div class="mb-2">' +
                    '   <button class="btn btn-primary btn-sm mt-1" id="behavior_button" data-toggle="modal" data-target="#behavior_modal"><i class="fas fa-plus"></i> Behavioral Traits</button>' +
                    '   <button class="btn btn-success btn-sm mt-1 ml-2" id="behavior_grade_button" data-toggle="modal" data-target="#behavior_grade_modal"><i class="fas fa-plus"></i> Values Grade & Equivalence</button>' +
                    '</div>'
            }

            $('#behavior_ispercentage').change(function() {
                if ($(this).is(':checked')) {
                    $('#behavior_percentage').prop('hidden', false);
                } else {
                    $('#behavior_percentage').prop('hidden', true);
                    $('#behavior_percentage').val('')
                }
            });
            $(document).on('click', '#behavior_button', function() {
                $('#behavior_create_button').attr('hidden', false)
                $('#behavior_save_button').attr('hidden', true)
            })
            $(document).on('click', '#edit_behavior', function() {
                $('#behavior_create_button').attr('hidden', true)
                $('#behavior_save_button').attr('hidden', false)
            })
            $(document).on('click', '#behavior_grade_button', function() {
                $('#behavior_grade_create_button').attr('hidden', false)
                $('#behavior_grade_save_button').attr('hidden', true)
            })
            $(document).on('click', '#edit_grade_behavior', function() {
                $('#behavior_grade_create_button').attr('hidden', true)
                $('#behavior_grade_save_button').attr('hidden', false)
            })

            $('#behavior_modal').on('hidden.bs.modal', function() {
                $('.behavior_form').val('')
                $('#behavior_ispercentage').prop('checked', false).trigger('change');
            })
            $('#behavior_grade_modal').on('hidden.bs.modal', function() {
                $('.behavior_form').val('')
            })


        })

    </script>
@endsection