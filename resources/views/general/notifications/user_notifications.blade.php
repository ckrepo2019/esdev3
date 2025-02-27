@php
    if(Session::get('currentPortal') == 17){
        $extend = 'superadmin.layouts.app2';
    }else if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
    }else if(Session::get('currentPortal') == 6){
        $extend = 'adminPortal.layouts.app2';
    }else if(Session::get('currentPortal') == 2){
        $extend = 'principalsportal.layouts.app2';
    }else if(Session::get('currentPortal') == 1){
        $extend = 'teacher.layouts.app';
    }else if(Session::get('currentPortal') == 18){
        $extend = 'ctportal.layouts.app2';
    }else if(Session::get('currentPortal') == 10){
        $extend = 'hr.layouts.app';
    }else if(Session::get('currentPortal') == 14){
        $extend = 'deanportal.layouts.app2';
    }
@endphp

@extends($extend)

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

    .badge-container {
        position: relative;
    }

    .badge-secondary {
        position: absolute;
        top: 0;
        right: 0;
        margin: 5px; /* Adjust spacing from the edges as needed */
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

<div class="modal fade lower-right" id="composemodal" tabindex="-1" role="dialog" aria-labelledby="composemodalLabel" aria-hidden="true" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row" id="composemodalbody" style="font-family: calibri !important;">
                
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-start">
            <input type="hidden" id="notification_id">
            <button type="button" class="btn btn-secondary acknowledge_status" id="btn_acknowledge" style="border-radius: 10px">Acknowledge</button>
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
                    <table width="100%" class="table table-sm table-bordered table-head-fixed " id="notification_datatable"  style="table-layout: fixed; font-size: 16px">
                        <thead>
                              <tr>
                                    <th class="text-center" width="5%">&nbsp;&nbsp;No.</th>
                                    <th width="95%">Description</th>
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
        var valid_data = true
        var notifications = []

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
        load_user_notifications()
        notification_table()

        // select2 decalaration
        
        // modal close
       

        // functions
        function load_user_notifications(){
            $.ajax({
                type: "GET",
                url: "/user/notification/load_user_notifications",
                success: function (data) {
                    notifications = data

                    notification_table()
                }
            });
        }
        
        
        // select2 function call


        // Click Events
        $(document).on('click', '#btn_acknowledge', function(){
            var notificationid = $('#notification_id').val()

            $.ajax({
                type: "GET",
                url: "/user/notification/acknowledge_notification",
                data: {
                    notificationid: notificationid
                },
                success: function (data) {
                    if(data[0].status == 0){
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }else{
                        load_user_notifications()
                        $('.acknowledge_status').addClass('btn-success')
                        $('.acknowledge_status').removeClass('btn-secondary')
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    }
                }
            });
        })


        $(document).on('click', '#btn_viewnotifaction', function(){
            var subject = $(this).attr('subject')
            var additionalmessage = $(this).attr('additionalmessage')
            var acknowledge_status = $(this).attr('acknowledge_status')
            var createddatetime = $(this).attr('createddatetime')

            if(acknowledge_status == 1){
                $('.acknowledge_status').addClass('btn-success')
                $('.acknowledge_status').removeClass('btn-secondary')
            } else {
                $('.acknowledge_status').addClass('btn-secondary')
                $('.acknowledge_status').removeClass('btn-success')
            }

            $('#notification_id').val($(this).attr('notification-id'))

            $('#composemodalbody').html(`
                    <div class="col-md-12">
                        <p class="text-right">${createddatetime}</p>
                    </div>
                    <div class="col-md-12">
                        <h3 class="text-left">${subject}</h3>    
                    </div>
                    <div class="col-md-12">
                        <p class="text-left" style="font-size: 17px !important">${additionalmessage}</p>
                    </div>`
                )
            $('#composemodal').modal('show');
        })
       
        $(document).on('mouseenter', '.btn_viewnotifaction', function() {
            $(this).addClass('zoom-in').removeClass('zoom-out');
        });

        $(document).on('mouseleave', '.btn_viewnotifaction', function() {
            $(this).addClass('zoom-out').removeClass('zoom-in');
        });
      
        // Datatables or tables
        function notification_table(){

            console.log(notifications);
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
                    {"data" : 'subject'},
                    {"data" : 'additionalmessage'}
                ],
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var index = row + 1; // Start indexing from 1
                            var text = '<span>&nbsp;&nbsp;' + index + '</span>';
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-center');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            if (rowData.acknowledge_status == 1) {
                                var cl = 'bg-success'
                            } else {
                                var cl = ''
                            }

                            var messageExcerpt = rowData.additionalmessage.length > 100 ? rowData.additionalmessage.slice(0, 100) + '...' : rowData.additionalmessage;

                            var text = `
                                    <div class="badge-container">
                                        <a href="javascript:void(0)" class="text-dark" id="btn_viewnotifaction" notification-id="${rowData.id}" subject="${rowData.subject}" additionalmessage="${rowData.additionalmessage}" acknowledge_status="${rowData.acknowledge_status}" createddatetime="${rowData.createddatetime}"  style="font-family: Calibri">
                                            <b>${rowData.subject}</b> - ${messageExcerpt}
                                        </a>
                                        <span class="badge badge-secondary ${cl}">ACKNOWLEDGE</span>
                                    </div>
                            `;
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle text-left')
                        }
                    }
                    // ,
                    // {
                    //     'targets': 2,
                    //     'orderable': false, 
                    //     'createdCell':  function (td, cellData, rowData, row, col) {
                    //         if (rowData.acknowledge_status == 1) {
                    //             var cl = 'text-success'
                    //         } else {
                    //             var cl = ''
                    //         }

                    //         var text = '<a href="javascript:void(0)" class=" '+cl+' btn-sm btn_viewnotifaction" id="btn_acknowledge" notification-id="'+rowData.id+'"><i class="fas fa-check"></i></a>';
                    //         $(td)[0].innerHTML =  text
                    //         $(td).addClass('align-middle  text-center')
                    //     }
                    // }
                ]
            })

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

