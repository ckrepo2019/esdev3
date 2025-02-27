@extends('hr.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
    #employee_datatables .form-control {
        height: calc(2rem + 1px)!important;
        padding: 0.2rem 1.5rem!important;
    }
    #loademployee2_datatables > thead > tr > th {
        padding-right: 0px;
        padding-left: 0px;
    }

    @import url("https://fonts.googleapis.com/css2?family=Poppins&display=swap");

    .wrapper .icon {
        background-color: none!important;
        position: relative;
        font-size: 18px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 1000;

    }

    .wrapper .tooltip {
        text-align: center;
        width: 150px;
        position: absolute;
        top: 0;
        font-size: 14px;
        padding: 5px 8px;
        border-radius: 5px;
        box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 1000;

    }

    .wrapper .tooltip::before {
        position: absolute;
        content: "";
        height: 8px;
        width: 8px;
        bottom: -3px;
        left: 50%;
        transform: translate(-50%) rotate(45deg);
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 1000;

    }

    .wrapper .icon:hover .tooltip {
        top: -33px;
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        z-index: 1000;

    }

    .wrapper .icon:hover span,
    .wrapper .icon:hover .tooltip {
        text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.1);
        z-index: 1000;

    }

    .wrapper .pdfs:hover .tooltip,
    .wrapper .pdfs:hover .tooltip::before {
        background: #0275d8;
        color: #fff;
        z-index: 1000;
    }
    .select2-container .select2-selection--single {
        height: 37px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border: 1px solid #007bff;
        border-radius: 4px;
        cursor: default;
        float: left;
        margin-right: 5px;
        margin-top: 5px;
        padding: 0 5px;
    }

    .modal.lower-right .modal-dialog {
        position: fixed;
        bottom: 150px;
        right: 20px;
        margin: 0;
        width: 700px;
        max-width: 700px;
        pointer-events: auto;
    }
    .modal-backdrop {
        display: none; /* Hide the backdrop */
    }

    .zoom-in {
        transition: transform 0.3s, font-size 0.3s;
        transform: scale(1.1);
        font-size: 1.2em;
    }

    .zoom-out {
        transition: transform 0.3s, font-size 0.3s;
        transform: scale(1);
        font-size: 1em;
    }
</style>
<section class="content-header p-0" style="padding-top: 15px!important;">
    <div class="container-fluid">
        <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Notifications</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Notifications</li>
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

{{-- MODAL --}}

<div class="modal fade lower-right" id="composemodal_view" tabindex="-1" role="dialog" aria-labelledby="composemodal_viewLabel" aria-hidden="true" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row" id="composemodalbody">
                
            </div>
        </div>
        </div>
    </div>
</div>

<div class="modal fade lower-right" id="composemodal" tabindex="-1" role="dialog" aria-labelledby="composemodalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="composemodalLabel">New Message</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="sendtolabel">Send To :</label>
                        <select class="select2 form-control form-control-sm text-uppercase" name="sendto"  placeholder="Select Recipient" id="recipient_select" style="margin: auto!important;">
                          <option value="0">Select Recipient</option>
                          {{-- <option value="1">All</option> --}}
                          <option value="1">Department</option>
                          <option value="2">Employee</option>
                        </select>
                    </div>
                    <div class="form-group" id="employeediv" hidden>
                        <select class="form-control select2" id="employee_select" multiple="multiple" data-placeholder="Select Employee">
                            <option value="0">All</option>
                            @foreach($employees as $employee)
                                <option value="{{$employee->id}}">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="departmentdiv" hidden>
                        <select class="select2 form-control form-control-sm text-uppercase" multiple="multiple"  placeholder="Select Department" id="department_select" style="margin: auto!important;"></select>
                    </div>
                    <div class="form-group">
                        <label for="inputsubject">Subject</label>
                        <input type="text" class="form-control" id="inputsubject" placeholder="Subject" onkeyup="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="form-group">
                        <label for="inputadditionalmessage">Say Something</label>
                        <textarea class="form-control" id="inputadditionalmessage" rows="3"></textarea>
                    </div>
                    <label for="">Sent Tru:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="truemail">
                        <label class="form-check-label" for="truemail">
                          Email
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="truesystem">
                        <label class="form-check-label" for="truesystem">
                          System Notification
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-start">
            <button type="button" class="btn btn-primary" id="sendnotification" style="border-radius: 10px">Send</button>
        </div>
        </div>
    </div>
</div>

{{-- MODAL END --}}

<section class="content">
    <div class="card border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table width="100%" class="table table-sm table-bordered table-head-fixed " id="notification_datatable"  style="font-size: 16px">
                        <thead>
                              <tr>
                                    <th width="5%">&nbsp;&nbsp;No.</th>
                                    <th width="60%">Description</th>
                                    <th width="20%" class="text-center">Type</th>
                                    <th class="text-center" width="15%">Action</th>
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

        // variable declaration
        var employees = JSON.parse('{!! json_encode($employees) !!}');
        var departments = JSON.parse('{!! json_encode($departments) !!}');

        var valid_data = true
        var notifications_setup = []

        $('#notification_datatables').DataTable()

        
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        });
        
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // function call
        load_departments()
        load_notifications()

        // select2 decalaration
        $('#recipient_select').select2()
        $('#employee_select').select2()
        
        // modal close
        $('#composemodal').on('hide.bs.modal', function(e) {
            $(this).find(':input').val('')
            $(this).find(':input').prop('checked', false)
            $('#recipient_select').val(null).trigger('change')
            $('#department_select').val(null).trigger('change')
            $('#employee_select').val(0).trigger('change')
        })

        // functions
        function load_departments(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/employees/profile/select_departments",
                success: function (data) {
                    $('#department_select').empty()
                    $('#department_select').append('<option value="">Select Department</option>')
                    $('#department_select').append('<option value="0">All</option>')


                    $('#department_select').select2({
                        data: data,
                        allowClear : true,
                        placeholder: 'Select Department'
                    });
                }
            });
        }


        function load_notifications(){
            $.ajax({
                type: "GET",
                url: "/hr/settings/notification/load_notifications",
                success: function (data) {
                    notifications = data
                    notification_table()
                }
            });
        }

        // select2 function call

        // Click Events
        $(document).on('click', '#btn-addnotification', function(){
            $('#composemodal').modal('show');
        })

        // drop down recipient
        $(document).on('change', '#recipient_select', function(){
            var value = $(this).val()

            if (value == 1) {
                $('#employeediv').prop('hidden', true)
                $('#departmentdiv').prop('hidden', false)
                $('#employee_select').val(null).change()

                load_departments()

            } else if (value == 2){
                $('#employeediv').prop('hidden', false)
                $('#departmentdiv').prop('hidden', true)
                $('#department_select').val(null).change()

            } else {
                $('#employeediv').prop('hidden', true)
                $('#departmentdiv').prop('hidden', true)
                $('#department_select').val(null).change()
                $('#employee_select').val(null).change()
            }
        })

        $(document).on('click', '#sendnotification', function(){
            var recipienttype = $('#recipient_select').val()
            var subject = $('#inputsubject').val()
            var additionalmessage = $('#inputadditionalmessage').val()

            if (recipienttype == 1) {

                var recipientid = $('#department_select').val()

            } else if (recipienttype == 2) {

                var recipientid = $('#employee_select').val()
            }

            if ($('#truemail').is(':checked')) {
                var sentruemail = 1
            }

            if ($('#truesystem').is(':checked')) {
                var sentrusystem = 1
            }

            if (recipienttype == 0) {
                Toast.fire({
                    type: 'error',
                    title: 'Please specify at least one recipient.'
                })
                valid_data = false
            }

            if (valid_data) {
                $.ajax({
                    type: "GET",
                    url: "/hr/settings/notification/sendnotification",
                    data: {
                        recipienttype: recipienttype,
                        subject: subject,
                        recipientid: recipientid,
                        sentruemail: sentruemail,
                        sentrusystem: sentrusystem,
                        additionalmessage: additionalmessage
                    },
                    success: function (data) {
                        if(data[0].status == 0){
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }else{
                            load_notifications()
                            $('#composemodal').modal('hide');
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                        }
                    }
                });
            }

        })

        $(document).on('click', '#delete_notification', function(){
            var id = $(this).attr('notification-id')

            Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "GET",
                        url: "/hr/settings/notification/delete_notification",
                        data: {
                            id: id
                        },
                        success: function (data) {
                            if(data[0].status == 0){
                                    Toast.fire({
                                    type: 'error',
                                    title: data[0].message
                                })
                            }else{
                                load_notifications()
                                Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                                })
                            }
                        }
                    });
                }
            });
        })

        $(document).on('click', '#btn_viewnotifaction', function(){
            var subject = $(this).attr('subject')
            var additionalmessage = $(this).attr('additionalmessage')
            var acknowledge_status = $(this).attr('acknowledge_status')
            var acknowledgeby = $(this).attr('acknowledgeby')
            var recipientid = $(this).attr('recipientid')
            var recipienttype = $(this).attr('recipienttype')
            var createddatetime = $(this).attr('createddatetime')
            var filteredDepartments;
            var filteredEmployees;

            var filteredData;


            var recipientArray = recipientid.split(',').map(id => id.trim());
            
            if (recipienttype == 1) {

                var departmentids = recipientArray

                var departmentIds = recipientArray.map(id => parseInt(id, 10)); // Convert to integer
                filteredDepartments = departments.filter(department => departmentIds.includes(department.id));

                var table_departments = filteredDepartments.map(department => `
                    <tr>
                        <td>${department.text}</td>
                        <td></td>
                    </tr>
                `).join('')


                $('#composemodalbody').html(`
                    <div class="col-md-12">
                        <p class="text-right">${createddatetime}</p>
                    </div>
                    <div class="col-md-12">
                        <h3 class="text-left">${subject}</h3>    
                    </div>
                    <div class="col-md-12">
                        <p class="text-left">${additionalmessage}</p>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="80%">Name</th>
                                    <th width="20%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${table_departments}
                            </tbody>
                        </table>
                    </div>
                `)

            } 

            if (recipienttype == 2) {
                var userIds = recipientArray.map(id => parseInt(id, 10)); // Convert to integer
                filteredEmployees = employees.filter(employee => userIds.includes(employee.id));


                var table_employees = filteredEmployees.map(employee => `
                    <tr>
                        <td>${employee.lastname}, ${employee.firstname}</td>
                        <td></td>
                    </tr>
                `).join('')


                $('#composemodalbody').html(`
                    <div class="col-md-12">
                        <p class="text-right">${createddatetime}</p>
                    </div>
                    <div class="col-md-12">
                        <h3 class="text-left">${subject}</h3>    
                    </div>
                    <div class="col-md-12">
                        <p class="text-left">${additionalmessage}</p>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="80%">Name</th>
                                    <th width="20%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${table_employees}
                            </tbody>
                        </table>
                    </div>
                `)
            }

            if(acknowledge_status == 1){
                $('.acknowledge_status').addClass('btn-success')
                $('.acknowledge_status').removeClass('btn-secondary')
            } else {
                $('.acknowledge_status').addClass('btn-secondary')
                $('.acknowledge_status').removeClass('btn-success')
            }

            $('#notification_id').val($(this).attr('notification-id'))

            
            $('#composemodal_view').modal('show');
        })

        $(document).on('mouseenter', '.btn_viewnotifaction', function() {
            $(this).addClass('zoom-in').removeClass('zoom-out');
        });

        $(document).on('mouseleave', '.btn_viewnotifaction', function() {
            $(this).addClass('zoom-out').removeClass('zoom-in');
        });
      
        // Datatables or tables
        function notification_table(){

            $('#notification_datatable').DataTable({
                destroy: true,
                lengthMenu: false,
                info: false,
                paging: false,
                searching: true,
                lengthChange: false,
                scrollX: false,
                autoWidth: false,
                order: false,
                data: notifications,
                columns : [
                    {"data" : null},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null}
                ],
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var index = row + 1; // Start indexing from 1
                            var text = '<span>&nbsp;&nbsp;' + index + '</span>';
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-left');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a href="javascript:void(0)" id="btn_viewnotifaction" subject="'+rowData.subject+'" additionalmessage="'+rowData.additionalmessage+'" acknowledge_status="'+rowData.acknowledge_status+'" notification-id="'+rowData.id+'" acknowledgeby="'+rowData.acknowledgeby+'" recipienttype="'+rowData.recipienttype+'" recipientid="'+rowData.recipientid+'" createddatetime="'+rowData.createddatetime+'"><span class="text-dark"><b>'+rowData.subject+'</b> - '+rowData.additionalmessage+'</span></a';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<span></span>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            // <a href="javascript:void(0)"  id="edit_notification" data-toggle="tooltip" title="Edit"><i class="far fa-edit text-primary" style="font-size: 18px!important;"></i></a>&nbsp;&nbsp;
                            var text = `<a href="javascript:void(0)"  id="delete_notification" data-toggle="tooltip" title="Remove" notification-id="${rowData.id}"><i class="fas fa-trash text-danger" style="font-size: 18px!important;"></i></a>`;
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    }
                ]
            })

            var label_text = $($('#notification_datatable_wrapper')[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = '<a href="javascript:void(0)" class="btn text-white btn-primary btn-sm" id="btn-addnotification"><i class="fas fa-plus"></i> Compose</a>'

        }

    })
</script>
@endsection


{{-- // variable declaration
      
// function call

// select2 decalaration

// modal close

// functions

// select2 function call

// Click Events

// Datatables or tables --}}

