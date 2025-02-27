


@extends($extends)
@section('content')
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
@php
    $leaves = DB::table('hr_leaves')
                ->where('isactive','1')
                ->where('deleted','0')
                ->get();
@endphp
<section class="content-header p-0">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
           <h1>Filed Leaves</h1> 
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Filed Leaves</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
<div class="card" style="border: none;" hidden>
    <div class="card-header">
        <div class="row">
            {{-- <div class="col-md-4 text-left">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#fileleave"><i class="fa fa-plus"></i> File</button>
            </div> --}}
            <div class="col-md-4">
                <select class="form-control" id="select-leavetype">
                    <option value="0">All</option>
                    @if(count($leaves)>0)
                        @foreach($leaves as $leave)
                            <option value="{{$leave->id}}">{{$leave->leave_type}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4 text-right">
                <button type="button" class="btn btn-primary btn-block" id="btn-generate"><i class="fa fa-sync"></i> Generate</button>
            </div>
        </div>
    </div>
</div>
{{-- <div class="row">
    <div class="col-3">
        <div class="info-box shadow">
            <span class="info-box-icon text-success"><i class="fa fa-share"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Leave Applications</span>
            <span class="info-box-number" id="spanbox-submitted"></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-3">
        <div class="info-box shadow">
            <span class="info-box-icon text-warning"><i class="fa fa-clock"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Pending</span>
            <span class="info-box-number" id="spanbox-pending"></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-3">
        <div class="info-box shadow">
            <span class="info-box-icon text-success"><i class="fa fa-check"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Approved</span>
            <span class="info-box-number" id="spanbox-approved"></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-3">
        <div class="info-box shadow">
            <span class="info-box-icon text-danger"><i class="fa fa-times"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Disapproved</span>
            <span class="info-box-number" id="spanbox-disapproved"></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
</div> --}}
<div class="card">
    <div class="card-body table-responsive"  id="results-container-table">
        <table width="100%" class="table table-sm table-bordered table-head-fixed" id="filedleave_datatables"  style="font-size: 15px; table-layout: fixed;">
            <thead>
                    <tr>
                        <td class="text-left" width="15%"><b>Date Applied</b></td>
                        <td class="text-left" width="25%"><b>Name</b></td>
                        <td class="text-center" width="13%"><b>Type</b></td>
                        <td class="text-center" width="15%"><b>Dates Covered</b></td>
                        <td class="text-center" width="25%"><b>Purpose/Reason</b></td>
                        {{-- <td class="text-center" width="10%"><b>Status</b></td> --}}
                        <td class="text-center" width="10%"><b>Action</b></td>
                    </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="card-body table-responsive"  id="results-container">

    </div>
</div>
{{-- <div id="fileleave" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document" style="color: black;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >Leave Application</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" style="text-align: none !important">
                <div class="row mb-3">
                    <div class="col-md-12 mb-2">
                        <label>Employees</label>
                        <select id="select-employees" class="form-control select2 m-0 text-uppercase" multiple="multiple" data-placeholder="Select employee/s:" name="leaveapplicants[]" required>
                            @foreach($employees as $employee)
                                <option value="{{$employee->id}}">
                                    {{strtoupper($employee->lastname)}}, {{strtoupper($employee->firstname)}} {{strtoupper($employee->suffix)}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label>Leave Type</label>
                        <select class="form-control" name="leavetype" id="leavetype" required>
                            @foreach ($leavetypes as $leavetype)
                                <option value="{{$leavetype->id}}">{{$leavetype->leave_type}}</option>                        
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label>Remarks</label>
                        <textarea class="form-control" id="textarea-remarks"></textarea>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-sm btn-info" id="btn-adddates"><i class="fa fa-plus"></i> Add dates</button>
                    </div>
                </div>
                <div id="div-adddates">
                    
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="btn-submit">Submit</button>
            </div>
        </div>
    </div>
</div> --}}
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

        resultsleaves()
        // $('body').addClass('sidebar-collapse');
        // $('.select2').select2();
        // $('[data-toggle="tooltip"]').tooltip();
        // $('#select-leavetype').on('change', function(){
        //     $('#results-container').empty();
        // })
        // $('#btn-generate').on('click', function(){
        //     Swal.fire({
        //         title: 'Fetching data...',
        //         allowOutsideClick: false,
        //         closeOnClickOutside: false,
        //         onBeforeOpen: () => {
        //             Swal.showLoading()
        //         }
        //     })  
        //     $.ajax({
        //         url: '/hr/leaves/index',
        //         type: 'GET',
        //         data: {
        //             leavetypeid   : $('#select-leavetype').val()
        //         },
        //         success:function(data){
        //             $(".swal2-container").remove();
        //             $('body').removeClass('swal2-shown')
        //             $('body').removeClass('swal2-height-auto')
        //             $('#results-container').empty()
        //             $('#results-container').append(data)
                    
        //         }
        //     })
        // })
        // $('#btn-generate').click();
        // $(document).on('click', '.btn-approve',function(){
        //     var id = $(this).attr('data-id');            
        //     Swal.fire({
        //         title: 'Approving request...',
        //         text: 'Would you like to continue?',
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Continue',
        //         allowOutsideClick: false
        //     }).then((result) => {
        //         if (result.value) {
        //             $.ajax({
        //                 url: '/hr/leaves/approve',
        //                 type:"GET",
        //                 dataType:"json",
        //                 data:{
        //                     id: id,
        //                 },
        //                 // headers: { 'X-CSRF-TOKEN': token },,
        //                 success: function(data){
        //                     if(data == 1)
        //                     {
        //                         toastr.success('Approved successfully!')
        //                         $('#btn-generate').click();
        //                     }else{
        //                         toastr.error('Something went wrong!')
        //                     }
        //                 }
        //             })
        //         }
        //     })
        // })

        // $(document).on('click','.btn-pending', function(){
        //     var id = $(this).attr('data-id');
            
        //     Swal.fire({
        //         title: 'Pending request...',
        //         text: 'Would you like to continue?',
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Continue',
        //         allowOutsideClick: false
        //     }).then((result) => {
        //         if (result.value) {
        //             $.ajax({
        //                 url: '/hr/leaves/pending',
        //                 type:"GET",
        //                 dataType:"json",
        //                 data:{
        //                     id: id,
        //                 },
        //                 // headers: { 'X-CSRF-TOKEN': token },,
        //                 success: function(data){
        //                     if(data == 1)
        //                     {
        //                         toastr.success('Pending successfully!')
        //                         $('#btn-generate').click();
        //                     }else{
        //                         toastr.error('Something went wrong!')
        //                     }
        //                 }
        //             })
        //         }
        //     })
        // })
        
        // $(document).on('click','.btn-disapprove', function(){
        //     var id = $(this).attr('data-id');
            
        //     Swal.fire({
        //         title: 'Disapproving request...',
        //         html: '<div class="row"><div class="col-md-12"><label>Remarks:</label><input type="text" class="form-control" id="input-disapproveremarks"/></div></div>',
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Continue',
        //         allowOutsideClick: false
        //     }).then((result) => {
        //         if (result.value) {
        //             $.ajax({
        //                 url: '/hr/leaves/disapprove',
        //                 type:"GET",
        //                 dataType:"json",
        //                 data:{
        //                     id: id,
        //                     remarks: $('#input-disapproveremarks').val(),
        //                 },
        //                 success: function(data){
        //                     if(data == 1)
        //                     {
        //                         toastr.success('Request has been disapproved!')
        //                         $('#btn-generate').click();
        //                     }else{
        //                         toastr.error('Something went wrong!')
        //                     }
        //                 }
        //             })
        //         }
        //     })
        // })
        
        // $(document).on("keyup",'.filter', function() {
        //     var input = $(this).val().toUpperCase();
        //     var visibleCards = 0;
        //     var hiddenCards = 0;

        //     $(".container").append($("<div class='card-group card-group-filter'></div>"));


        //     $(".eachfiledleave").each(function() {
        //         if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

        //         $(".card-group.card-group-filter:first-of-type").append($(this));
        //         $(this).hide();
        //         hiddenCards++;

        //         } else {

        //         $(".card-group.card-group-filter:last-of-type").prepend($(this));
        //         $(this).show();
        //         visibleCards++;

        //         if (((visibleCards % 4) == 0)) {
        //             $(".container").append($("<div class='card-group card-group-filter'></div>"));
        //         }
        //         }
        //     });

        // });
        $(document).on('click', '.btn-modalstatus', function() {
            var currentstatus = $(this).attr('data-status');
            var employeeleaveid = $(this).attr('data-id');
            var remarks = $(this).attr('data-remarks');
            $('#textarea-reason').val(remarks);

            $('#btn-submitstatus').attr('data-id', employeeleaveid);
          
            // Handling pending status
            if (currentstatus == 0) {
                $('#input-status-pending').prop('checked', true);
                Swal.fire({
                    title: 'Are you sure you want to pending?',
                    text: 'Changing Status...',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Continue'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/hr/leaves/changestatus',
                            type: "GET",
                            dataType: "json",
                            data: {
                                id: employeeleaveid,
                                selectedstatus: currentstatus,
                                reason: ''
                            },
                            success: function(data) {
                                if (data == 1) {
                                    $('#textarea-reason').val('');
                                    toastr.success('Pending Successfully!');
                                    resultsleaves()
                                } else {
                                    toastr.error('Something went wrong!');
                                }
                            }
                        });
                    }
                });
            } 
            
            // Handling approval
            else if (currentstatus == 1) {
                $('#input-status-approve').prop('checked', true);
                var closestRow = $(this).closest('tr');
                var leaveDatesArray = closestRow.find('.leavedates').map(function() {
                    return $(this).attr('leavedates');
                }).get();

                Swal.fire({
                    title: 'Are you sure you want to approve?',
                    text: 'Changing Status...',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Continue'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/hr/leaves/changestatus',
                            type: "GET",
                            dataType: "json",
                            data: {
                                id: employeeleaveid,
                                selectedstatus: currentstatus,
                                reason: '',
                                leaveDatesArray: leaveDatesArray
                            },
                            success: function(data) {
                                if (data == 1) {
                                    $('#textarea-reason').val('');
                                    toastr.success('Approved Successfully!');
                                    resultsleaves()
                                } else {
                                    toastr.error('Something went wrong!');
                                }
                            }
                        });
                    }
                });
            } 
            
            // Handling deletion (pending status)
            else if (currentstatus == 3) {
                $('#input-status-approve').prop('checked', true);
                var closestRow = $(this).closest('tr');
                var leaveDatesArray = closestRow.find('.leavedates').map(function() {
                    return $(this).attr('leavedates');
                }).get();

                Swal.fire({
                    title: 'Are you sure you want to delete?',
                    text: 'Delete Leave?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Continue'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/hr/leaves/changestatus',
                            type: "GET",
                            dataType: "json",
                            data: {
                                id: employeeleaveid,
                                selectedstatus: currentstatus,
                                reason: '',
                                leaveDatesArray: leaveDatesArray
                            },
                            success: function(data) {
                                if (data == 1) {
                                    $('#textarea-reason').val('');
                                    toastr.success('Deleted successfully!');
                                    resultsleaves()
                                } else {
                                    toastr.error('Something went wrong!');
                                }
                            }
                        });
                    }
                });
            } 
            
            // Handling disapproval
            else if (currentstatus == 2) {
                $('#input-status-approve').prop('checked', true);
                var closestRow = $(this).closest('tr');
                var leaveDatesArray = closestRow.find('.leavedates').map(function() {
                    return $(this).attr('leavedates');
                }).get();

                Swal.fire({
                    title: 'Are you sure you want to approve?',
                    text: 'Changing Status...',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Continue'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/hr/leaves/changestatus',
                            type: "GET",
                            dataType: "json",
                            data: {
                                id: employeeleaveid,
                                selectedstatus: currentstatus,
                                reason: '',
                                leaveDatesArray: leaveDatesArray
                            },
                            success: function(data) {
                                if (data == 1) {
                                    $('#textarea-reason').val('');
                                    toastr.success('Disapproved Successfully!');
                                    resultsleaves()
                                } else {
                                    toastr.error('Something went wrong!');
                                }
                            }
                        });
                    }
                });
            }
        });


        // function results() {
        //     $.ajax({
        //         url: '/hr/leaves/index',
        //         type: 'GET',
        //         data: {
        //             leavetypeid   : $('#select-leavetype').val()
        //         },
        //         success:function(data){

        //             console.log(data);
        //             // $(".swal2-container").remove();
        //             // $('body').removeClass('swal2-shown')
        //             // $('body').removeClass('swal2-height-auto')
        //             $('#results-container').empty()
        //             $('#results-container').append(data)
        //             filedleave_tables()
        //         }
        //     })
        // }

        // function resultsleaves() {
        //     $.ajax({
        //         url: '/hr/leaves/filedleaves',
        //         type: 'GET',
        //         data: {
        //             leavetypeid   : $('#select-leavetype').val()
        //         },
        //         success:function(data){
        //             var filedleaves_data = data.filedleaves
        //             filedleave_tables(filedleaves_data)
        //         }
        //     })
        // }


        // function filedleave_tables(filedleaves_data){
        //     console.log(filedleaves_data, '--');

        //     $('#filedleave_datatables').DataTable({
        //         destroy: true,
        //         lengthChange: false,
        //         scrollX: false,
        //         autoWidth: false,
        //         searching: true,
        //         order: false,
        //         bInfo: false,
        //         data: filedleaves_data,
        //         columns: [
        //             {"data": "createddatetime"},  // Date Applied
        //             {"data": null},  // Name (Composed of firstname and lastname)
        //             {"data": "firstname"},  // Type
        //             {"data": "lastname"},  // Dates Covered
        //             {"data": null},  // Purpose/Reason
        //             {"data": null},  // Action buttons
        //         ],
        //         columnDefs: [
        //             {
        //                 'targets': 0,
        //                 'orderable': false,
        //                 'createdCell': function (td, cellData, rowData, row, col) {
        //                     var createddate = moment(rowData.createddatetime).format('MM/DD/YYYY')
        //                     var text = '<span>'+createddate+'</span>';
        //                     $(td)[0].innerHTML =  text
        //                     $(td).addClass('align-middle  text-left')
        //                 }
        //             },
        //             {
        //                 'targets': 1,
        //                 'orderable': false,
        //                 'createdCell': function (td, cellData, rowData, row, col) {
        //                     var text = '<span class="font-weight-bold" style="color: #20343d;">' + rowData.lastname + ', ' + rowData.firstname + '</span>';

        //                     if (rowData.approvals && Array.isArray(rowData.approvals)) {
        //                         rowData.approvals.forEach(function(approval) {
        //                             text += '<br><span class="badge ';

        //                             // Determine the status badge
        //                             switch(approval.appstatus) {
        //                                 case 0:
        //                                     text += 'badge-warning">Pending</span>';
        //                                     break;
        //                                 case 1:
        //                                     text += 'badge-success">Approve</span>';
        //                                     break;
        //                                 case 2:
        //                                     text += 'badge-danger">Rejected</span>';
        //                                     if (approval.remarks) {
        //                                         text += '<span> (Reason: ' + approval.remarks + ')</span>';
        //                                     }
        //                                     break;
        //                                 default:
        //                                     text += 'badge-secondary">Unknown</span>';
        //                             }

        //                             text += '&nbsp;&nbsp;<span style="font-size: 12px!important; font-weight: 500;">' + approval.lastname + ', ' + approval.firstname + '</span>';
        //                             text += '&nbsp;';
        //                         });
        //                     }

        //                     $(td).html(text);
        //                     $(td).addClass('align-middle text-left');
        //                 }
        //             },
        //             // Leave Type
        //             {
        //                 'targets': 2,
        //                 'orderable': false,
        //                 'createdCell': function (td, cellData, rowData, row, col) {

        //                     var text = '<span>'+rowData.leave_type+'</span>';
        //                     $(td)[0].innerHTML =  text
        //                     $(td).addClass('align-middle  text-center')
        //                 }
        //             },
        //             // Dates Covered
        //             {
        //                 'targets': 3,
        //                 'orderable': false,
        //                 'createdCell': function (td, cellData, rowData, row, col) {
        //                     var text = '';
        //                     if (rowData.dates && rowData.dates.length > 0) {
        //                         rowData.dates.forEach(function(eachdate) {
        //                             text += '<span class="leavedates" leavedates="' + eachdate.id + '">' + moment(eachdate.ldate).format('MM/DD/YYYY') + ' - <span class="text-info">' + eachdate.daystatus + '</span></span><br>';
        //                         });
        //                     } else {
        //                         text = '<span>No dates provided</span>'; // Default if no dates
        //                     }
        //                     $(td).html(text);
        //                     $(td).addClass('align-middle text-center');
        //                 }
        //             },
        //             // Purpose/Reason
        //             {
        //                 'targets': 4,
        //                 'orderable': false,
        //                 'createdCell': function (td, cellData, rowData, row, col) {
        //                     var text = '';

        //                     // Check if remarks exist as a string
        //                     if (rowData.remarks) {
        //                         text = '<span>' + rowData.remarks + '</span>';
        //                     } else {
        //                         text = '<span>No remarks</span>'; // Default if no remarks
        //                     }

        //                     $(td).html(text);
        //                     $(td).addClass('align-middle text-center');
        //                 }
        //             },
        //             {
        //                 'targets': 5,
        //                 'orderable': false,
        //                 'createdCell': function (td, cellData, rowData, row, col) {
        //                     var text = '';

        //                     // Assuming rowData.approvals is populated with the necessary data
        //                     if (rowData.approvals && Array.isArray(rowData.approvals)) {
        //                         rowData.approvals.forEach(function(approval) {
        //                             if (approval.userid === {{ auth()->user()->id }}) {
        //                                 if (rowData.display == 1) {
        //                                     if (approval.appstatus === 0) {
        //                                         text += '<button type="button" class="btn btn-sm btn-success btn-modalstatus pr-1 pl-1 pt-0 pb-0" data-status="1" data-remarks="' + approval.remarks + '" data-id="' + rowData.id + '" data-toggle="tooltip" data-placement="left" title="Approve"><i class="fas fa-thumbs-up"></i></button>&nbsp;&nbsp;';
        //                                         // text += '<button type="button" class="btn btn-sm btn-dark btn-modalstatus pr-1 pl-1 pt-0 pb-0" data-status="2" data-remarks="' + approval.remarks + '" data-id="' + rowData.id + '" data-toggle="tooltip" data-placement="left" title="Reject"><i class="far fa-thumbs-down"></i></button>&nbsp;&nbsp;';
        //                                         text += '<button type="button" class="btn btn-sm btn-danger btn-modalstatus pr-1 pl-1 pt-0 pb-0" data-status="3" data-remarks="' + approval.remarks + '" data-id="' + rowData.id + '" data-toggle="tooltip" data-placement="left" title="Delete"><i class="fas fa-trash-alt"></i></button>';
        //                                     } else if (approval.appstatus === 1) {
        //                                         text += '<button type="button" class="btn btn-sm btn-warning btn-modalstatus pr-1 pl-1 pt-0 pb-0" data-status="0" data-remarks="' + approval.remarks + '" data-id="' + rowData.id + '" data-toggle="tooltip" data-placement="left" title="Pending"><i class="fa fa-undo"></i></button>';
        //                                     } else if (approval.appstatus === 2) {
        //                                         text += '<button type="button" class="btn btn-sm btn-warning btn-modalstatus pr-1 pl-1 pt-0 pb-0" data-status="0" data-remarks="' + approval.remarks + '" data-id="' + rowData.id + '" data-toggle="tooltip" data-placement="left" title="Pending"><i class="fa fa-undo"></i></button>';
        //                                     }
        //                                 }
        //                             }
        //                         });
        //                     }

        //                     $(td).html(text);
        //                     $(td).addClass('align-middle text-center');
        //                 }
        //             }

        //         ]
        //     });
        // }

        function resultsleaves() {
            Swal.fire({
                title: 'Fetching data...',
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });

            // Retrieve the search value from sessionStorage
            var previousSearchValue = sessionStorage.getItem('leaveSearchValue');

            var table = $('#filedleave_datatables').DataTable({
                destroy: true,            // Re-initialize DataTable on each request
                lengthChange: false,      // Disable length change dropdown
                scrollX: false,           // Disable horizontal scroll
                autoWidth: false,         // Disable auto-width
                searching: true,          // Enable searching
                order: [],                // Disable initial ordering
                bInfo: false,             // Hide table information
                processing: true,         // Show processing indicator
                serverSide: true,         // Enable server-side processing
                ajax: {
                    url: '/hr/leaves/filedleaves',  // Server-side endpoint
                    type: 'GET',
                    data: function(d) {
                        d.leavetypeid = $('#select-leavetype').val();
                    },
                    dataSrc: function(json) {
                        Swal.close();
                        return json.data;
                    }
                },
                columns: [
                    {"data": "createddatetime"},
                    {"data": null},
                    {"data": "firstname"},
                    {"data": "lastname"},
                    {"data": null},
                    {"data": null},
                ],
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false,
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var createddate = moment(rowData.createddatetime).format('MM/DD/YYYY')
                            var text = '<span>'+createddate+'</span>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false,
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var text = '<span class="font-weight-bold" style="color: #20343d;">' + rowData.lastname + ', ' + rowData.firstname + '</span>';

                            if (rowData.approvals && Array.isArray(rowData.approvals)) {
                                rowData.approvals.forEach(function(approval) {
                                    text += '<br><span class="badge ';

                                    // Determine the status badge
                                    switch(approval.appstatus) {
                                        case 0:
                                            text += 'badge-warning">Pending</span>';
                                            break;
                                        case 1:
                                            text += 'badge-success">Approve</span>';
                                            break;
                                        case 2:
                                            text += 'badge-danger">Rejected</span>';
                                            if (approval.remarks) {
                                                text += '<span> (Reason: ' + approval.remarks + ')</span>';
                                            }
                                            break;
                                        default:
                                            text += 'badge-secondary">Unknown</span>';
                                    }

                                    text += '&nbsp;&nbsp;<span style="font-size: 12px!important; font-weight: 500;">' + approval.lastname + ', ' + approval.firstname + '</span>';
                                    text += '&nbsp;';
                                });
                            }

                            $(td).html(text);
                            $(td).addClass('align-middle text-left');
                        }
                    },
                    // Leave Type
                    {
                        'targets': 2,
                        'orderable': false,
                        'createdCell': function (td, cellData, rowData, row, col) {

                            var text = '<span>'+rowData.leave_type+'</span>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    },
                    // Dates Covered
                    {
                        'targets': 3,
                        'orderable': false,
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var text = '';
                            if (rowData.dates && rowData.dates.length > 0) {
                                rowData.dates.forEach(function(eachdate) {
                                    text += '<span class="leavedates" leavedates="' + eachdate.id + '">' + moment(eachdate.ldate).format('MM/DD/YYYY') + ' - <span class="text-info">' + eachdate.daystatus + '</span></span><br>';
                                });
                            } else {
                                text = '<span>No dates provided</span>'; // Default if no dates
                            }
                            $(td).html(text);
                            $(td).addClass('align-middle text-center');
                        }
                    },
                    // Purpose/Reason
                    {
                        'targets': 4,
                        'orderable': false,
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var text = '';

                            // Check if remarks exist as a string
                            if (rowData.remarks) {
                                text = '<span>' + rowData.remarks + '</span>';
                            } else {
                                text = '<span>No remarks</span>'; // Default if no remarks
                            }

                            $(td).html(text);
                            $(td).addClass('align-middle text-center');
                        }
                    },
                    {
                        'targets': 5,
                        'orderable': false,
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var text = '';

                            // Assuming rowData.approvals is populated with the necessary data
                            if (rowData.approvals && Array.isArray(rowData.approvals)) {
                                rowData.approvals.forEach(function(approval) {
                                    if (approval.userid === {{ auth()->user()->id }}) {
                                        if (rowData.display == 1) {
                                            if (approval.appstatus === 0) {
                                                text += '<button type="button" class="btn btn-sm btn-success btn-modalstatus pr-1 pl-1 pt-0 pb-0" data-status="1" data-remarks="' + approval.remarks + '" data-id="' + rowData.id + '" data-toggle="tooltip" data-placement="left" title="Approve"><i class="fas fa-thumbs-up"></i></button>&nbsp;&nbsp;';
                                                text += '<button type="button" class="btn btn-sm btn-dark btn-modalstatus pr-1 pl-1 pt-0 pb-0" data-status="2" data-remarks="' + approval.remarks + '" data-id="' + rowData.id + '" data-toggle="tooltip" data-placement="left" title="Reject"><i class="far fa-thumbs-down"></i></button>&nbsp;&nbsp;';
                                                text += '<button type="button" class="btn btn-sm btn-danger btn-modalstatus pr-1 pl-1 pt-0 pb-0" data-status="3" data-remarks="' + approval.remarks + '" data-id="' + rowData.id + '" data-toggle="tooltip" data-placement="left" title="Delete"><i class="fas fa-trash-alt"></i></button>';
                                            } else if (approval.appstatus === 1) {
                                                text += '<button type="button" class="btn btn-sm btn-warning btn-modalstatus pr-1 pl-1 pt-0 pb-0" data-status="0" data-remarks="' + approval.remarks + '" data-id="' + rowData.id + '" data-toggle="tooltip" data-placement="left" title="Pending"><i class="fa fa-undo"></i></button>';
                                            } else if (approval.appstatus === 2) {
                                                text += '<button type="button" class="btn btn-sm btn-warning btn-modalstatus pr-1 pl-1 pt-0 pb-0" data-status="0" data-remarks="' + approval.remarks + '" data-id="' + rowData.id + '" data-toggle="tooltip" data-placement="left" title="Pending"><i class="fa fa-undo"></i></button>';
                                            }
                                        }
                                    }
                                });
                            }

                            $(td).html(text);
                            $(td).addClass('align-middle text-center');
                        }
                    }

                ]
            });

            // Apply previous search value if exists
            if (previousSearchValue) {
                table.search(previousSearchValue).draw();
            }

            // Listen for search input changes
            table.on('search.dt', function() {
                var searchValue = table.search();
                sessionStorage.setItem('leaveSearchValue', searchValue); // Save search value
            });
        }



    })
</script>
@endsection

