@extends('hr.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
    .floatinginput {
        position: relative;
        width: 100%;
    } 

    .floatinginput input {
        z-index: 1;
        width: 100%;
        padding-top: 4px;
        padding-bottom: 4px;
        padding-left: 17px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        background: rgb(255, 255, 255);
        outline: none;
        color: rgb(66, 66, 66);
        font-size: 15px;
    }

    .floatinginput span {
        font-weight: bold;
        position: absolute;
        left: 0;
        padding: 5px;
        padding-left: 8px;
        font-size: 13px;
        pointer-events: none;
        color: #495057;
        text-transform: capitalize;
        transition: 0.2s;
    }

    .floatinginput input:valid ~ span, 
    .floatinginput input:focus ~ span {
        color: #007bff;
        transform: translateX(9px) translateY(-7px);
        font-size: 11px;
        padding: 0 10px;
        background: #fff;
    }
    input {
        outline: none!important;
        border: 1px solid #b7b7b7!important;
    }
    .percentageInput{
        text-align: center;
    }
</style>
<section class="content-header p-0" style="padding-top: 15px!important;">
    <div class="container-fluid">
        <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Holiday Setup</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Holiday Setup</li>
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
<div class="modal fade" tabindex="-1" role="dialog" id="modal_addholidaytype">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><span id="modal_desc">Add Holiday Type</span> </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <input type="hidden" id="moddescriptionid">
          <form>
            <div class="form-group">
              <label for="particular">Description / Title</label>
              <input type="email" class="form-control modpercentageInput" id="moddescription" placeholder="Enter Holiday Type Description" >
              <span class="invalid-feedback" role="alert">
                <strong>Description is required</strong>
              </span>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="floatinginput">
                            {{-- <label>IF WORK</label> --}}
                            <input type="number" id="modifwork"  class="form-control ifwork percentageInput modpercentageInput" name="modifwork"/>
                            <span>IF WORK</span>
                            <span class="invalid-feedback" role="alert">
                            <strong>If Work is required</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="floatinginput">
                            {{-- <label>IF NOT WORK</label> --}}
                            <input type="number" id="modifnotwork"  class="form-control ifnotwork percentageInput modpercentageInput" name="modifnotwork"/>
                            <span>IF NOT WORK</span>
                            <span class="invalid-feedback" role="alert">
                            <strong>If Not Work is required</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <span><b>REST DAY</b></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="floatinginput">
                            {{-- <label>IF WORK</label> --}}
                            <input type="number" id="modrestdayifwork"  class="form-control ifwork percentageInput modpercentageInput" name="modrestdayifwork"/>
                            <span>IF WORK</span>
                            <span class="invalid-feedback" role="alert">
                            <strong>If Work is required</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- <div class="floatinginput">
                            <input type="number" id="modrestdayifnotwork"  class="form-control ifnotwork percentageInput modpercentageInput" name="modrestdayifnotwork"/>
                            <span>IF NOT WORK</span>
                            <span class="invalid-feedback" role="alert">
                            <strong>If Not Work is required</strong>
                        </div> --}}
                    </div>
                </div>
            </div>
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-primary btn-sm" id="btn_saveholidaytype"><i class="fas fa-plus"></i> Add</button>
          <button type="button" class="btn btn-success btn-sm" id="btn_updateholidaytype"><i class="fas fa-plus"></i> Save</button>
          <button type="button" class="btn btn-danger  btn-sm" data-dismiss="modal">Close</button>
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
                    {{-- <table width="100%" class="table table-bordered table-head-fixed " id="overtime_datatable"  style="font-size: 16px">
                        <thead>
                            <tr>
                                <th width="21%" rowspan="2" style="vertical-align: middle;">Holiday</th>
                                <th width="16%" rowspan="2" class="text-center" style="vertical-align: middle;">If Work</th>
                                <th width="16%" rowspan="2" class="text-center" style="vertical-align: middle;">If Not Work</th>
                                <th colspan="2" class="text-center p-0" style="vertical-align: middle">
                                    <span>Rest Day</span>
                                </th>
                                <th width="10%" rowspan="2" class="text-center" style="vertical-align: middle; border: 1px solid #dee2e6;">Action</th>
                            </tr>
                            <tr>
                                <th width="16%" class="text-center">If Work</th>
                                <th width="16%" class="text-center" style="border-right: 1px solid #dee2e6;">If Not Work</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table> --}}
                    <table width="100%" class="table table-bordered table-head-fixed " id="overtime_datatable"  style="font-size: 16px">
                        <thead>
                            <tr>
                                <th width="30%" style="vertical-align: middle;">Holiday</th>
                                <th width="20%" class="text-center" style="vertical-align: middle;">If Work</th>
                                <th width="20%" class="text-center" style="vertical-align: middle;">If Not Work</th>
                                <th width="20%" class="text-center p-0" style="vertical-align: middle"><span>Rest Day If Work</span></th>
                                <th width="10%" class="text-center" style="vertical-align: middle; border: 1px solid #dee2e6;">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>

      <div class="card border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table width="100%" class="table table-head-fixed " id="holiday_datatable"  style="font-size: 16px">
                        <thead>
                            <tr>
                                <th width="30%" style="vertical-align: middle;">Holiday</th>
                                <th width="10%" class="text-left" style="vertical-align: middle;">Date</th>
                                <th width="10%" class="text-center" style="vertical-align: middle;">With Pay</th>
                                <th width="50%" class="text-center" style="vertical-align: middle;"></th>
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

        $('#btn_saveholidaytype').show()
        $('#btn_updateholidaytype').hide()


        holidaysfunction()
        loadallholiday()
        // Modal Close
        $('#modal_addholidaytype').on('hide.bs.modal', function (e) {
            $('#moddescription').val('')
            $('.modpercentageInput').val('')
        })

        // Events
        // Click Add Holiday Type Button
        $(document).on('click', '#btn_addholidaytype', function(){
            $('#modal_desc').text('Add Holiday Type')
            $('#btn_saveholidaytype').show()
            $('#btn_updateholidaytype').hide()
            $('#modal_addholidaytype').modal('show')
        })

        // Click Trash Icon to delete
        $(document).on('click', '#delete_holidaytype', function(){
            var holidaytypeid = $(this).attr('holidaytypeid')

            Swal.fire({
            text: 'Are you sure you want to remove Holiday Setup?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                    type: "GET",
                    url: "/payrollclerk/setup/deleteholidaytype",
                    data: {
                        holidaytypeid : holidaytypeid
                    },
                    success: function (data) {
                        if(data[0].status == 0){
                            Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                        }else{
                            holidaysfunction()
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

        // Edit Holiday Type / Click edit icon
        $(document).on('click', '#edit_holidaytype', function(){
            $('#modal_desc').text('Edit Holiday Type')
            $('#btn_saveholidaytype').hide()
            $('#btn_updateholidaytype').show()

            var holidaytypeid = $(this).attr('holidaytypeid')
            var data_desc = $(this).attr('data-desc')
            var ifwork = $(this).attr('data-iw')
            var ifnotwork = $(this).attr('data-inw')
            var restdayifwork = $(this).attr('data-riw')
            var restdayifnotwork = $(this).attr('data-rinw')

            $('#moddescriptionid').val(holidaytypeid)
            $('#moddescription').val(data_desc)
            $('#modifwork').val(ifwork)
            $('#modifnotwork').val(ifnotwork)
            $('#modrestdayifwork').val(restdayifwork)
            $('#modrestdayifnotwork').val(restdayifnotwork)

            $('#modal_addholidaytype').modal('show')

        })


        // CLick Save Button inside Modal
        $(document).on('click', '#btn_updateholidaytype', function(){
            var valid_data = true;

            var holidaytypeid = $('#moddescriptionid').val()
            var description = $('#moddescription').val()
            var ifwork = $('#modifwork').val()
            var ifnotwork = $('#modifnotwork').val()
            var restdayifwork = $('#modrestdayifwork').val()
            var restdayifnotwork = $('#modrestdayifnotwork').val()

            if (description == '') {
                $('#moddescription').addClass('is-invalid')
                Toast.fire({
                    type: 'error',
                    title: 'No Shift Selected'
                })
                valid_data = false;
            } else {
                $('#moddescription').removeClass('is-invalid')
            }
            
            if (ifwork == '') {
                ifwork = 0
            }
            if (ifnotwork == '') {
                ifnotwork = 0
            }
            if (restdayifwork == '') {
                restdayifwork = 0
            }
            if (restdayifnotwork == '') {
                restdayifnotwork = 0
            }

            if (valid_data) {
                $.ajax({
                    type: "GET",
                    url: "/payrollclerk/setup/updateholidaytype",
                    data: {
                        holidaytypeid : holidaytypeid,
                        description : description,
                        ifwork : ifwork,
                        ifnotwork : ifnotwork,
                        restdayifwork : restdayifwork,
                        restdayifnotwork : restdayifnotwork
                    },
                    success: function (data) {
                        if(data[0].status == 0){
                            Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                        }else{
                            holidaysfunction()
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                        }
                    }
                });
            }
        })

        // Click Add inside Modal (Add Holiday Type)
        $(document).on('click', '#btn_saveholidaytype', function(){
            var valid_data = true;
            var description = $('#moddescription').val()
            var ifwork = $('#modifwork').val()
            var ifnotwork = $('#modifnotwork').val()
            var restdayifwork = $('#modrestdayifwork').val()
            var restdayifnotwork = $('#modrestdayifnotwork').val()

            if (description == '') {
                $('#moddescription').addClass('is-invalid')
                Toast.fire({
                    type: 'error',
                    title: 'No Shift Selected'
                })
                valid_data = false;
            } else {
                $('#moddescription').removeClass('is-invalid')
            }
            
            if (ifwork == '') {
                ifwork = 0
            }
            if (ifnotwork == '') {
                ifnotwork = 0
            }
            if (restdayifwork == '') {
                restdayifwork = 0
            }
            if (restdayifnotwork == '') {
                restdayifnotwork = 0
            }

            if (valid_data) {
                $.ajax({
                    type: "GET",
                    url: "/payrollclerk/setup/addholidaytype",
                    data: {
                        description : description,
                        ifwork : ifwork,
                        ifnotwork : ifnotwork,
                        restdayifwork : restdayifwork,
                        restdayifnotwork : restdayifnotwork
                    },
                    success: function (data) {
                        if(data[0].status == 0){
                            Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                        }else{
                            $('.modpercentageInput').val('')
                            holidaysfunction()
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                        }
                    }
                });
            }
        })

        // Click Checkbox
        $(document).on('click', '.checkwithpay', function(){
            var holidayid = $(this).attr('data-id')
            var withpay = 0;
            if ($(this).is(':checked')) {
                withpay = 1
            } else {
                withpay = 0
            }

            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/statuswithpay",
                data: {
                    holidayid : holidayid,
                    withpay : withpay
                },
                success: function (data) {
                    if(data[0].status == 0){
                        Toast.fire({
                        type: 'error',
                        title: data[0].message
                    })
                    }else{
                        loadallholiday()
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    }
                }
            });
        })
      
        // this function will load all the holiday types
        function holidaysfunction() {
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/loadallholidaytype",
                success: function (data) {
                    console.log(data);
                    holidayTypes = data 
                    overtime_datatable()
                }
            });
        }

        function overtime_datatable(){
            $('#overtime_datatable').DataTable({
                lengthMenu: false,
                info: false,
                paging: true,
                searching: true,
                destroy: true,
                lengthChange: false,
                scrollX: true,
                autoWidth: true,
                order: false,
                paging: false,
                data: holidayTypes,
                columns : [
                    {"data" : 'description'},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null}
                ], 
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            var content = '<span>' + rowData.description + '</span>';
                            $(td).html(content);
                            $(td).addClass('text-left align-middle');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            var content = '<div class="input-group">' +
                                                '<input type="number" class="percentageInput form-control" placeholder="Enter %" style="width: 100px;" min="0" value="'+rowData.ifwork+'">' +
                                                '<div class="input-group-append">' +
                                                '<span class="input-group-text">%</span>' +
                                                '</div>' +
                                            '</div>';
                            $(td).html(content);
                            $(td).addClass('text-center align-middle');
                            $(td).css('padding', '0 !important');
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            var content = '<div class="input-group">' +
                                                '<input type="number" class="percentageInput form-control" placeholder="Enter %" min="0" value="'+rowData.ifnotwork+'">' +
                                                '<div class="input-group-append">' +
                                                '<span class="input-group-text">%</span>' +
                                                '</div>' +
                                            '</div>';
                            $(td).html(content);
                            $(td).addClass('text-center align-middle');
                            $(td).css('padding', '0 !important');
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            var content = '<div class="input-group">' +
                                                '<input type="number" class="percentageInput form-control" placeholder="Enter %" style="width: 100px;" min="0" value="'+rowData.restdayifwork+'">' +
                                                '<div class="input-group-append">' +
                                                '<span class="input-group-text">%</span>' +
                                                '</div>' +
                                            '</div>';
                            $(td).html(content);
                            $(td).addClass('text-center align-middle');
                            $(td).css('padding', '0 !important');
                        }
                    },
                    {
                        'targets': 4,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            var content = '<a href="javascript:void(0)" id="delete_holidaytype" holidaytypeid="'+rowData.id+'"><i class="fas fa-trash text-danger" style="font-size: 18px;"></i></a>&nbsp;&nbsp;&nbsp;'+
                                          '<a href="javascript:void(0)"  id="edit_holidaytype" type-action="update" data-desc="'+rowData.description+'" data-iw="'+rowData.ifwork+'"  data-inw="'+rowData.ifnotwork+'"  data-riw="'+rowData.restdayifwork+'"  data-rinw="'+rowData.restdayifnotwork+'" holidaytypeid="'+rowData.id+'"><i class="far fa-edit icon_stanallowance" id="icon_stanallowance'+rowData.id+'" style="font-size: 18px;"></i></a>'
                            $(td).html(content);
                            $(td).addClass('text-center align-middle');
                        }
                    },
                ]
            });

            $('.percentageInput').on('input', function() {
                var inputValue = $(this).val();

                // Remove any non-numeric characters except % symbol
                inputValue = inputValue.replace(/[^0-9.%]/g, '');

                // Check if the input value ends with a percentage symbol
                if (inputValue.endsWith('%')) {
                    inputValue = inputValue.slice(0, -1); // Remove the percentage symbol
                }

                // Parse the input value as a number
                var numericValue = parseFloat(inputValue);

                // Check if the numeric value is valid and restrict it to 100
                if (!isNaN(numericValue)) {
                    // numericValue = Math.min(numericValue, 100); // Restrict to a maximum of 100
                    
                    // Convert to integer if it's a whole number, otherwise format with 2 decimal places
                    var formattedValue = numericValue === parseInt(numericValue) ? numericValue : numericValue.toFixed(2);
                    $(this).val(formattedValue);
                }
            });

            var label_text = $($('#overtime_datatable_wrapper')[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = '<div class="row" style="padding-bottom: 10px!important;"><div class="col-md-12 col-sm-12"><button class="btn btn-primary btn-sm" id="btn_addholidaytype"><i class="fas fa-plus"></i> Add Holiday Type</button></div></div>'
        }




        //load all Holidays
        var holidays = [];
        function loadallholiday(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/loadallholiday",
                success: function (data) {
                    holidays = data
                    console.log(holidays);
                    holiday_datatable() 
                }
            });
        }

        function holiday_datatable(){
            $('#holiday_datatable').DataTable({
                lengthMenu: false,
                info: false,
                paging: true,
                searching: true,
                destroy: true,
                lengthChange: false,
                scrollX: true,
                autoWidth: true,
                order: false,
                paging: false,
                data: holidays,
                columns : [
                    {"data" : 'typename'},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null}
                ], 
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            var content = '<span>' + rowData.typename + '</span>';
                            $(td).html(content);
                            $(td).addClass('text-left align-middle');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            
                            //calculation of how many days hehe
                            start = new Date(rowData.start);
                            var end = new Date(rowData.end);
                            // Calculate the difference in milliseconds between start and end
                            var timeDiff = Math.abs(end - start);
                            // Convert milliseconds to days
                            var daysDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
                            // end calculation

                            // Format the start date
                            var formattedStartDate = start.toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });

                            var content =  formattedStartDate ;
                            $(td).html(content);
                            $(td).html(content);
                            $(td).addClass('text-left align-middle');
                            $(td).css('vertical-align', 'middle!important');
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {

                            if (rowData.withpay == 1) {
                                var content = '<input class="checkwithpay" type="checkbox" data-id="'+rowData.id+'" id="checkwithpay'+rowData.id+'" style="width: 18px; height: 18px" checked>';
                            } else {
                                var content = '<input class="checkwithpay" type="checkbox" data-id="'+rowData.id+'" id="checkwithpay'+rowData.id+'" style="width: 18px; height: 18px">';
                            }
                            
                            $(td).html(content);
                            $(td).addClass('text-center align-middle');
                            $(td).css('padding', '0 !important');
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        createdCell: function (td, cellData, rowData, row, col) {
                            var content = '';
                            $(td).html(content);
                            $(td).addClass('text-center align-middle');
                            $(td).css('padding', '0 !important');
                        }
                    }
                ]
            });
        }
        
    })
</script>
@endsection


