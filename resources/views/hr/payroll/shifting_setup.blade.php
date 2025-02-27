@extends('hr.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

<section class="content-header p-0" style="padding-top: 15px!important;">
    <div class="container-fluid">
        <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shifting</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Shifting</li>
                </ol>
                </div>
        </div>
    </div>
</section>

@php
$refid = DB::table('usertype')
    ->where('id', Session::get('currentPortal'))
    ->first()->refid;
@endphp

{{-- MODALS --}}
<div class="modal fade" tabindex="-1" role="dialog" id="modal_addshift">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><span id="modal-standard-add-particular-desc">Create Shift</span> </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <input type="hidden" id="shiftid">
            <div class="form-group">
              <label for="particular">Description / Title</label>
              <input type="email" class="form-control" id="description" placeholder="Enter Shift Description" >
              <span class="invalid-feedback" role="alert">
                <strong>Description is required</strong>
              </span>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>1<sup>st</sup> IN</label>
                        <input type="time" id="timepicker_first_in"  class="timepick form-control" name="first_in"/>
                        <span class="invalid-feedback" role="alert">
                        <strong>1ST In is required</strong>
                    </div>
                    <div class="col-md-6">
                        <label>1<sup>st</sup> OUT</label>
                        <input type="time" id="timepicker_first_out" class="timepick form-control" name="first_out"/>
                        <span class="invalid-feedback" role="alert">
                        <strong>1ST Out is required</strong>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>2<sup>nd</sup> IN</label>
                        <input type="time" id="timepicker_second_in"  class="timepick form-control" name="second_in"/>
                        <span class="invalid-feedback" role="alert">
                        <strong>1ST In is required</strong>
                    </div>
                    <div class="col-md-6">
                        <label>2<sup>nd</sup> OUT</label>
                        <input type="time" id="timepicker_second_out" class="timepick form-control" name="second_out"/>
                        <span class="invalid-feedback" role="alert">
                        <strong>1ST Out is required</strong>
                    </div>
                </div>
            </div>
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-primary" id="btn_saveshift"><i class="fas fa-plus"></i> Add</button>
          <button type="button" class="btn btn-success" id="btn_updateshift"><i class="fas fa-plus"></i> Save</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
      </div>
    </div>
</div>
{{-- END MODAL --}}

<section class="content">
    <div class="card border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table width="100%" class="table table-sm table-bordered table-head-fixed " id="shifts_datatables"  style="font-size: 16px">
                        <thead>
                              <tr>
                                    <th width="30%">Description</th>
                                    <th class="text-center" width="15%">1<sup>st</sup> Time In</th>
                                    <th class="text-center" width="15%">1<sup>st</sup> Time Out</th>
                                    <th class="text-center" width="15%">2<sup>nd</sup> Time In</th>
                                    <th class="text-center" width="15%">2<sup>nd</sup> Time Out</th>
                                    <th class="text-center" width="10%">Action</th>
                              </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
</section>

  
@endsection
@section('footerjavascript')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>

<script>
    $(document).ready(function(){
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
    $('#btn_saveshift').show()
    $('#btn_updateshift').hide()
    
    // variable declarations and function call
    var allshifts = []
    load_all_shift()
    

    // Events
    $('#modal_addshift').on('hidden.bs.modal', function () {
        $('#timepicker_first_in').val('')
        $('#timepicker_first_out').val('')
        $('#timepicker_second_in').val('')
        $('#timepicker_second_out').val('')
        $('#description').val('')
        $('#btn_saveshift').show()
        $('#btn_updateshift').hide()
    })  

    // Click Create Shift Button
    $(document).on('click', '#btn_addshift', function(){
        $('#modal_addshift').modal('show')
    })

    // Click Add Button in the Create Shift Modal
    $(document).on('click', '#btn_saveshift', function(){
        store_shift()
    })

    // Click Add Button in the Edit Shift Modal
    $(document).on('click', '#edit_shift', function(){
        var shiftid = $(this).attr('data-id')

        $('#modal_addshift').modal('show')
        $('#btn_saveshift').hide()
        $('#btn_updateshift').show()
        edit_shift(shiftid)
    })

    $(document).on('click', '#btn_updateshift', function(){
        var shiftid = $('#shiftid').val()
        update_shift(shiftid)
    })

    // Click Add Button in the Create Shift Modal
    $(document).on('click', '#delete_shift', function(){
        var shiftid = $(this).attr('data-id');

        Swal.fire({
        text: 'Are you sure you want to remove Shift?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Remove'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "GET",
                    url: "/payrollclerk/setup/delete_shift",
                    data: {shiftid:shiftid},
                    success: function (data) {
                        load_all_shift()
                        if(data[0].status == 0){
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                            }else{
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                        }
                    }
                });
            }
        })
        
    })

    
    // Functions


    // Load Functions
    
    function load_all_shift(){
        $.ajax({
            type: "GET",
            url: "/payrollclerk/setup/load_all_shift",
            success: function (data) {
                allshifts = data
                console.log('load_all_shift');
                console.log(data);
                console.log('--------------------------------------------------');
                shifts_datatables()
            }
        });
    }

    // datatable Functions
    function shifts_datatables(){
        $('#shifts_datatables').DataTable({
            destroy: true,
            lengthChange: false,
            scrollX: true,
            autoWidth: true,
            order: false,
            data: allshifts,
            columns : [
                {"data" : "description"},
                {"data" : null},
                {"data" : null},
                {"data" : null},
                {"data" : null},
                {"data" : null}
            ], 
            columnDefs: [
                {
                    'targets': 0,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        $(td).addClass('align-middle')
                        $(td).text(rowData.description)
                    }
                },
                {
                    'targets': 1,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        if (rowData.first_in == null || rowData.first_in == '') {
                            first_in = ''
                        } else {
                            first_in = rowData.first_in
                        }
                        var text = '<a class="mb-0">'+first_in+'</a>';
                        $(td)[0].innerHTML =  text
                        $(td).addClass('align-middle  text-center')
                    }
                },
                {
                    'targets': 2,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        if (rowData.first_out == null || rowData.first_out == '') {
                            first_out = ''
                        } else {
                            first_out = rowData.first_out
                        }
                        var text = '<a class="mb-0">'+first_out+'</a>';
                        $(td)[0].innerHTML =  text
                        $(td).addClass('align-middle  text-center')
                    }
                },
                {
                    'targets': 3,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        if (rowData.second_in == null || rowData.second_in == '') {
                            second_in = ''
                        } else {
                            second_in = rowData.second_in
                        }
                        var text = '<a class="mb-0">'+second_in+'</a>';
                        $(td)[0].innerHTML =  text
                        $(td).addClass('align-middle  text-center')
                    }
                },
                {
                    'targets': 4,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        if (rowData.second_out == null || rowData.second_out == '') {
                            second_out = ''
                        } else {
                            second_out = rowData.second_out
                        }
                        var text = '<a class="mb-0">'+second_out+'</a>';
                        $(td)[0].innerHTML =  text
                        $(td).addClass('align-middle  text-center')
                    }
                },
                {
                    'targets': 5,
                    'orderable': false, 
                    'createdCell':  function (td, cellData, rowData, row, col) {
                        var buttons = '<a href="javascript:void(0)" id="edit_shift" data-id="'+rowData.id+'" data-toggle="tooltip" title="Edit Shift"><i class="far fa-edit text-primary"></i></a> &nbsp;&nbsp;<a href="javascript:void(0)" id="delete_shift" data-id="'+rowData.id+'" data-toggle="tooltip" title="Remove Shift"><i class="far fa-trash-alt text-danger"></i></a>';
                        $(td)[0].innerHTML =  buttons
                        $(td).addClass('text-center')
                        $(td).addClass('align-middle')
                    }
                }
            ]
        });

        var label_text = $($('#shifts_datatables_wrapper')[0].children[0])[0].children[0]
        $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm" id="btn_addshift"><i class="fas fa-plus"></i> Create Shift</button>'
    }
    

    // Ajax Function
    function store_shift(){
        var valid_data = true
        var description = $('#description').val()
        var timepicker_first_in = $('#timepicker_first_in').val()
        var timepicker_first_out = $('#timepicker_first_out').val()
        var timepicker_second_in = $('#timepicker_second_in').val()
        var timepicker_second_out = $('#timepicker_second_out').val()

        if (description == '' || description == null) {
            $('#description').addClass('is-invalid')
            valid_data = false
        } else {
            $('#description').removeClass('is-invalid')
        }

        // if (timepicker_first_in == '' || timepicker_first_in == null) {
        //     $('#timepicker_first_in').addClass('is-invalid')
        //     valid_data = false
        // } else {
        //     $('#timepicker_first_in').removeClass('is-invalid')
        // }

        // if (timepicker_first_out == '' || timepicker_first_out == null) {
        //     $('#timepicker_first_out').addClass('is-invalid')
        //     valid_data = false
        // } else {
        //     $('#timepicker_first_out').removeClass('is-invalid')
        // }

        // if (timepicker_second_in == '' || timepicker_second_in == null) {
        //     $('#timepicker_second_in').addClass('is-invalid')
        //     valid_data = false
        // } else {
        //     $('#timepicker_second_in').removeClass('is-invalid')
        // }

        // if (timepicker_second_out == '' || timepicker_second_out == null) {
        //     $('#timepicker_second_out').addClass('is-invalid')
        //     valid_data = false
        // } else {
        //     $('#timepicker_second_out').removeClass('is-invalid')
        // }

        if (valid_data) {
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/store_shift",
                data: {
                    description : description,
                    timepicker_first_in : timepicker_first_in,
                    timepicker_first_out : timepicker_first_out,
                    timepicker_second_in : timepicker_second_in,
                    timepicker_second_out : timepicker_second_out
                },
                success: function (data) {
                    load_all_shift()
                    

                    console.log(data);
                    if(data[0].status == 0){
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                        }else{
                        $('#modal_addshift').modal('hide')
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    }
                }
            });
        }
    }

    function edit_shift(shiftid){
        
        $.ajax({
            type: "GET",
            url: "/payrollclerk/setup/edit_shift",
            data: {shiftid : shiftid},
            success: function (data) {
                console.log(data);
                
                $('#shiftid').val(data.id)
                $('#description').val(data.description)
                $('#timepicker_first_in').val(data.first_in)
                $('#timepicker_first_out').val(data.first_out)
                $('#timepicker_second_in').val(data.second_in)
                $('#timepicker_second_out').val(data.second_out)
            }
        });
    }

    function update_shift(shiftid){
        var description = $('#description').val()
        var timepicker_first_in = $('#timepicker_first_in').val()
        var timepicker_first_out = $('#timepicker_first_out').val()
        var timepicker_second_in = $('#timepicker_second_in').val()
        var timepicker_second_out = $('#timepicker_second_out').val()

        console.log(timepicker_first_in);
        console.log(timepicker_first_out);
        console.log(timepicker_second_in);
        console.log(timepicker_second_out);

        // return false;
        $.ajax({
            type: "get",
            url: "/payrollclerk/setup/update_shift",
            data: {
                shiftid : shiftid,
                description : description,
                timepicker_first_in : timepicker_first_in,
                timepicker_first_out : timepicker_first_out,
                timepicker_second_in : timepicker_second_in,
                timepicker_second_out : timepicker_second_out
            },
            success: function (data) {
                load_all_shift()
                $('#modal_addshift').modal('hide')

                if(data[0].status == 0){
                    Toast.fire({
                        type: 'error',
                        title: data[0].message
                    })
                    }else{
                    Toast.fire({
                        type: 'success',
                        title: data[0].message
                    })
                }
            }
        });
    }

    // function delete_shift(shifid){

    //     $.ajax({
    //         type: "GET",
    //         url: "/payrollclerk/setup/delete_shift",
    //         data: {shiftid:shifid},
    //         success: function (data) {
    //             load_all_shift()
    //             if(data[0].status == 0){
    //                 Toast.fire({
    //                     type: 'error',
    //                     title: data[0].message
    //                 })
    //                 }else{
    //                 Toast.fire({
    //                     type: 'success',
    //                     title: data[0].message
    //                 })
    //             }
    //         }
    //     });
    // }



})
</script>
@endsection


