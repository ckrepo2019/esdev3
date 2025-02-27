@extends('hr.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@php
$refid = DB::table('usertype')
    ->where('id', Session::get('currentPortal'))
    ->first()->refid;
@endphp
<section class="content-header p-0" style="padding-top: 15px!important;">
    <div class="container-fluid">
        <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Additional
                        @if ($refid == 26)
                            Earnings
                        @else
                            Deductions  
                        @endif
                    </h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Additional
                        @if ($refid == 26)
                            Earnings
                        @else
                            Deductions  
                        @endif
                    </li>
                </ol>
                </div>
        </div>
    </div>
</section>


<style>
    #select-payrollrange{
        width: 230px!important;
    }
    .select2-container .select2-selection--single {
        height: 34px!important;
        width: 230px;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #000;
        font-size: 15px;
        font-weight: 400;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #000;
        line-height: 24px;
        font-size: 15px;
        font-weight: 400;
    }
    .modal-header {
        padding: 16px;
        padding-bottom: 0;
    }
    .highlight {
        background-color: rgba(42, 166, 255, 0.313);
    }
    .picurl {

    }
</style>
{{-- MODALS --}}
{{-- ADD EARNINGS --}}
<div class="modal fade" tabindex="-1" role="dialog" id="modal_earnings">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header" style="padding: 10px; align-items: center!important;">
            <div class="table-avatar">
               {{-- @php
                    $number = rand(1,3);
                    if(strtoupper($employeeinfo->gender) == 'FEMALE'){
                        $avatar = 'avatar/T(F) '.$number.'.png';
                    }
                    else{
                        $avatar = 'avatar/T(M) '.$number.'.png';
                    }
                @endphp
                <a href="#" class="avatar">
                    <img src="{{ asset($employeeinfo->picurl) }}" alt="" onerror="this.onerror = null, this.src='{{asset($avatar)}}'" style="width: 100px;"/>
                </a> --}}
                <a class="picurl" id="pic"></a> 
            </div>
            <h5 class="modal-title" style="display: flex; padding-left: 15px;">
                {{-- Earnings --}}
                <input type="hidden" id="teacher_id">
                {{-- <div class="form-group" style="margin-left: 10px;" id="earningselect2">
                    <select class="form-control form-control-sm select2" id="select-earning-payrollrange"></select>
                </div> --}}
                {{-- <button type="button" class="btn btn-primary btn-sm" id="addearning" style="margin-left: 10px; height: 34px;">Add Earning</button> --}}
                <span style="font-size: 25px; font-weight: 400;" id="fullnamehere"></span>
            </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row"
            {{-- @if ($refid != 26)
                style="display: none;"
            @endif --}}
            >
            <div class="col-md-12">
                <table width="100%" class="table table-sm table-bordered table-head-fixed" id="loadearnings_datatables"  style="font-size: 15px; table-layout: fixed;">
                    <input type="hidden" id="additionalvalue" value="2">
                    <thead>
                            <tr>
                                <td class="text-left" width="60%">Particulars</td>
                                {{-- <td class="text-center" width="20%">Date</td> --}}
                                <td class="text-center" width="10%">Amount</td>
                                <td class="text-center" width="20%">Remarks</td>
                                <td class="text-center" width="10%">Action</td>
                            </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
          </div>
          <div class="row"
            {{-- @if ($refid == 26)
                style="display: none; margin-top: 20px;"
            @endif --}}
            >
            <div class="col-md-12">
                <table width="100%" class="table table-sm table-bordered table-head-fixed" id="loaddeductions_datatables"  style="font-size: 15px; table-layout: fixed;">
                    <input type="hidden" id="additionalvalue" value="1">
                    <thead>
                            <tr>
                                <td class="text-left" width="60%">Particulars</td>
                                {{-- <td class="text-center" width="20%">Date</td> --}}
                                <td class="text-center" width="10%">Amount</td>
                                <td class="text-center" width="20%">Remarks</td>
                                <td class="text-center" width="10%">Action</td>
                            </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
          </div>
        </div>
        {{-- <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="">Save changes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div> --}}
      </div>
    </div>
</div>

{{-- ADD DEDUCTIONS --}}
<div class="modal fade" tabindex="-1" role="dialog" id="">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="display: flex">
            Deductions
            <input type="hidden" id="dteacher_id">
            <div class="form-group" style="margin-left: 10px;">
                <select class="form-control form-control-sm select2" id="select-deduction-payrollrange"></select>
            </div>
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
                <table width="100%" class="table table-sm table-bordered table-head-fixed" id="loaddeductions_datatables"  style="font-size: 15px; table-layout: fixed;">
                    <input type="hidden" id="additionalvalue" value="1">
                    <thead>
                            <tr>
                                <td class="text-left" width="40%">Particulars</td>
                                <td class="text-center" width="20%">Date</td>
                                <td class="text-center" width="10%">Amount</td>
                                <td class="text-center" width="20%">Remarks</td>
                                <td class="text-center" width="10%">Action</td>
                            </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
          </div>
        </div>
        {{-- <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="">Save changes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div> --}}
      </div>
    </div>
</div>


{{-- ADD EARNINGS --}}
<div class="modal fade modal_addearnings modal_adddeductions" tabindex="-1" role="dialog" id="">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" style="display: flex;padding-bottom: 12px;">
                <span id="headearning">Add Earnings</span>
                <input type="hidden" id="earning-payrollrange">
                <input type="hidden" id="particular_id">
                
            </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" id="input-particular-description"/>
                    </div>
                </div>
                <div class="col-md-12 mt-1">
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" class="form-control" id="input-particular-amount" placeholder="0" min="0"/>
                    </div>
                </div>
                <div class="col-md-12 mt-1">
                    <div class="form-group">
                        <label for="exampleTextarea">Remarks</label>
                        <textarea class="form-control" id="input-particular-remarks" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-primary saveearning">Save changes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
{{-- END MODAL --}}

<section class="content">
    <div class="card border-0">
        <div class="card-body">
            <div class="row">
                {{-- <div class="col-md-3">
                    <div class="form-group" style="">
                        <select class="form-control form-control-sm select2" id="select-payrollrange"></select>
                    </div>
                </div> --}}
                <div class="col-md-12">
                    <table width="100%" class="table table-sm table-bordered table-head-fixed " id="employee_datatables"  style="font-size: 16px">
                        <thead>
                              <tr>
                                    <th width="2%">&nbsp;&nbsp;No.</th>
                                    <th width="78%">Employee</th>
                                    {{-- <th class="text-center" width="20%">
                                        @if ($refid == 26)
                                            Earnings
                                        @else
                                            Deductions  
                                        @endif
                                    </th> --}}
                                    <th class="text-center" width="20%">Action</th>
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
        // variable calls
        var syid = @json($sy->id);
        var semid = @json($semester->id);

        var employee_list = [];
        var allemployeeearnings = [];
        var employeespecificearnings = [];
        var employeespecificdeductions = [];
        var payrollperiodss = [];
        // ==============================================================================================================================================

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        });
        
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // ============================================================= function calls =================================================================
        payrollperiods()
        earningtable()
        deductiontable()
        load_employees()

        
        // ==============================================================================================================================================

        // ============================================================= Modal Close ====================================================================
        $('.modal_addearnings').on('hide.bs.modal', function (e) {
            // Select all input elements inside the modal and empty their values
            $(this).find('input').val('');
            $(this).find('textarea').val('');
        });
        $('#modal_earnings').on('hide.bs.modal', function (e) {
            teacherid = 0;
            // Select all input elements inside the modal and empty their values
            $(this).find('input').val('');
            $('.highlightr').removeClass('highlight')
            getearnings(teacherid)
            getdeductions(teacherid)
        });
        
        // ========================================================= click event sections ===============================================================
        $(document).on('click', '.additionalearnings', function(){
            var teacherid = $(this).attr('teacherid');
            var salid = $(this).attr('saltypeid');
            var fullname = $(this).attr('dataname');
            var picurl = $(this).attr('picurlsrc');
            var payrollid = 0;

            if (picurl == 'noimage') {
                picurl = 'assets/images/avatars/unknown.png';
                $('#pic').html('<img src="{{ asset("/") }}' + picurl + '" alt="Teacher Image" style="width: 50px; height: 50px; border-radius: 100px; border: 1px solid #d5d5d5;" />');
            } else {
                //$('#pic').html('<img src="{{ asset("/") }}' + '/' + picurl + '" alt="Teacher Image" style="width: 50px; height: 50px; border-radius: 100px; border: 1px solid #d5d5d5;" />');
                $('#pic').html('<img src="{{ asset("/") }}' + '/' + picurl + '" alt="Teacher Image" style="width: 50px; height: 50px; border-radius: 100px; border: 1px solid #d5d5d5;" onerror="this.onerror=null; this.src=' + "'{{ asset('/') }}assets/images/avatars/unknown.png'" + '" />')
            }
            // Rest of your code...
            $('#teacher_id').val(teacherid);
            $('#fullnamehere').text(fullname);

            // Add or remove highlight classes as needed
            $('.highlight' + teacherid).addClass('highlight');
            $('.highlight' + teacherid).addClass('highlightr');

            // Additional actions...
            getearnings(teacherid);
            getdeductions(teacherid);
            $('#modal_earnings').modal('show');
        });

        // $(document).on('click', '.additionaldeductions', function(){
        //     console.log('deductionsssss');
        //     var teacherid = $(this).attr('teacherid')
        //     var payrollid = 0;

        //     $('#dteacher_id').val(teacherid)
        //     getdeductions(payrollid, teacherid)
        //     $('#modal_deductions').modal('show')
        // })
        
        $(document).on('change', '#select-earning-payrollrange', function(){
            var payrollid = $(this).val()
            var teacherid = $('#teacher_id').val()

            getearnings(payrollid, teacherid)
        })

        $(document).on('click', '#addearning', function(){
            var payrollid = $('#select-earning-payrollrange').val();
            var teacherid = $('#teacher_id').val();

            // if (payrollid == null || payrollid == '') {
            //     $('#earningselect2').css({
            //         'border-radius': '5px',
            //         'border': '1px solid red',
            //         'margin-left': '10px'
            //     });

            //     Toast.fire({
            //         type: 'warning',
            //         title: 'Select Active Payroll Date'
            //     });
            // } else {
            //     $('#earningselect2').css({
            //         'border-radius': 'none',
            //         'border': 'none',
            //         'margin-left': 'none'
            //     });
                
            //     $('.savededuction').addClass('saveearning')
            //     $('.saveearning').removeClass('savededuction')
            //     $('.updateearning').addClass('saveearning')
            //     $('.saveearning').removeClass('updateearning')
            //     $('.saveearning').removeClass('btn-success')
            //     $('.saveearning').addClass('btn-primary')
            //     $('#headearning').text('Add Earning')
            //     $('.modal_addearnings').modal('show')
            // }
            $('.savededuction').addClass('saveearning')
            $('.saveearning').removeClass('savededuction')
            $('.updateearning').addClass('saveearning')
            $('.saveearning').removeClass('updateearning')
            $('.saveearning').removeClass('btn-success')
            $('.saveearning').addClass('btn-primary')
            $('#headearning').text('Add Earning')
            $('.modal_addearnings').modal('show')
            
        })

        $(document).on('click', '.saveearning', function(){
            var earning_description = $('#input-particular-description').val();
            var earning_amount = $('#input-particular-amount').val();
            var earning_remarks = $('#input-particular-remarks').val();
            var payrollid = $('#select-earning-payrollrange').val();
            var teacherid = $('#teacher_id').val();

            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/additionalearningdeductions/saveearnings",
                data: {
                    earning_description : earning_description,
                    earning_amount : earning_amount,
                    earning_remarks : earning_remarks,
                    payrollid : payrollid,
                    teacherid : teacherid
                },
                success: function (data) {
                    // getearnings(payrollid, teacherid)
                    getearnings(teacherid)
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
        })

        $(document).on('click', '.updateearning ', function(){
            var earning_description = $('#input-particular-description').val();
            var earning_amount = $('#input-particular-amount').val();
            var earning_remarks = $('#input-particular-remarks').val();
            var payrollid = $('#earning-payrollrange').val();
            var teacherid = $('#teacher_id').val();
            var particularid = $('#particular_id').val();

            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/additionalearningdeductions/updateearning",
                data: {
                    earning_description : earning_description,
                    earning_amount : earning_amount,
                    earning_remarks : earning_remarks,
                    payrollid : payrollid,
                    teacherid : teacherid,
                    particularid : particularid
                },
                success: function (data) {
                    if(data[0].status == 0){
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                        }else{
                        // getearnings(payrollid, teacherid)
                        getearnings(teacherid)
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    }
                }
            });
        })

        $(document).on('click', '.editearnings', function(){
            var particularid = $(this).attr('data-particular')
            var teacherid = $('#teacher_id').val();

            $('.updatededuction').addClass('updateearning')
            $('.updateearning').removeClass('updatededuction')
            getemployeeearnings(particularid, teacherid)
        })

        $(document).on('click', '.deleteearnings', function(){
            var particularid = $(this).attr('data-particular')
            var teacherid = $('#teacher_id').val();

            deleteemployeeearnings(particularid, teacherid)

        })

        // Deductions
        $(document).on('click', '#adddeduction', function(){
            var payrollid = $('#select-earning-payrollrange').val();
            var teacherid = $('#teacher_id').val();

            // if (payrollid == null || payrollid == '') {
            //     $('#earningselect2').css({
            //         'border-radius': '5px',
            //         'border': '1px solid red',
            //         'margin-left': '10px'
            //     });

            //     Toast.fire({
            //         type: 'warning',
            //         title: 'Select Active Payroll Date'
            //     });
            // } else {
            //     $('#earningselect2').css({
            //         'border-radius': 'none',
            //         'border': 'none',
            //         'margin-left': 'none'
            //     });

                
            //     $('.updateearning').addClass('savededuction')
            //     $('.updatededuction').addClass('savededuction')
            //     $('.savededuction').addClass('updatededuction')
            //     $('.saveearning').addClass('savededuction')
            //     $('.savededuction').removeClass('saveearning')
            //     $('.savededuction').removeClass('updatededuction')
            //     $('.savededuction').removeClass('updateearning')
            //     $('.savededuction').addClass('btn-primary')
            //     $('.updateearning').addClass('btn-primary')
                
            //     $('.savededuction').removeClass('btn-success')
            //     $('#headearning').text('Add Deduction')
            //     $('.modal_adddeductions').modal();
            // }

            $('.updateearning').addClass('savededuction')
            $('.updatededuction').addClass('savededuction')
            $('.savededuction').addClass('updatededuction')
            $('.saveearning').addClass('savededuction')
            $('.savededuction').removeClass('saveearning')
            $('.savededuction').removeClass('updatededuction')
            $('.savededuction').removeClass('updateearning')
            $('.savededuction').addClass('btn-primary')
            $('.updateearning').addClass('btn-primary')
            
            $('.savededuction').removeClass('btn-success')
            $('#headearning').text('Add Deduction')
            $('.modal_adddeductions').modal();

            
        })

        $(document).on('click', '.savededuction', function(){
            var description = $('#input-particular-description').val();
            var amount = $('#input-particular-amount').val();
            var remarks = $('#input-particular-remarks').val();
            var payrollid = $('#select-earning-payrollrange').val();
            var teacherid = $('#teacher_id').val();

          
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/additionalearningdeductions/savededuction",
                data: {
                    description : description,
                    amount : amount,
                    remarks : remarks,
                    payrollid : payrollid,
                    teacherid : teacherid
                },
                success: function (data) {
                    // getdeductions(payrollid, teacherid)
                    getdeductions(teacherid)
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
        })
        
        $(document).on('click', '.editdeductions', function(){
            var particularid = $(this).attr('data-particular')
            var teacherid = $('#teacher_id').val();

            $('.saveearning').addClass('savededuction')
            $('.savededuction').removeClass('saveearning')
            $('#headearning').text('Add Deduction')
            getemployeedeductions(particularid, teacherid)
        })

        $(document).on('click', '.updatededuction', function(){
            var description = $('#input-particular-description').val();
            var amount = $('#input-particular-amount').val();
            var remarks = $('#input-particular-remarks').val();
            var payrollid = $('#earning-payrollrange').val();
            var teacherid = $('#teacher_id').val();
            var particularid = $('#particular_id').val();

            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/additionalearningdeductions/updatededuction",
                data: {
                    description : description,
                    amount : amount,
                    remarks : remarks,
                    payrollid : payrollid,
                    teacherid : teacherid,
                    particularid : particularid
                },
                success: function (data) {
                    if(data[0].status == 0){
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                        }else{
                        // getdeductions(payrollid, teacherid)
                        getdeductions(teacherid)
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    }
                }
            });
        })

        $(document).on('click', '.deleteedeductions', function(){
            var particularid = $(this).attr('data-particular')
            var teacherid = $('#teacher_id').val();

            deleteemployeedeductions(particularid, teacherid)

        })
        // ==============================================================================================================================================


        function load_employees(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/additionalearningdeductions/loademployees",
                success: function (data) {
                    employee_list = data
                    console.log('employee_list');
                    console.log(employee_list);
                    console.log('employee_list');
                    load_employeedatatable()
                }
            });
        }

        
        // this function is for the employee list datatable
        function load_employeedatatable(){
            $('#employee_datatables').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: true,
                autoWidth: true,
                order: false,
                data: employee_list,
                columns : [
                    {"data" : 'full_name'},
                    {"data" : null},
                    // {"data" : null},
                    {"data" : null}
                ], 
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var index = row + 1; // Start indexing from 1
                            var text = '<span>&nbsp;&nbsp;' + index +'</span>';
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-left');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0">'+rowData.full_name+'</a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            if (rowData.picurl == null) {
                                var picurl = 'noimage'
                            } else {
                                var picurl = rowData.picurl
                            }
                            if (rowData.salarybasistype == null) {
                                var text = '<span class="badge badge-warning additionalearnings" id="" teacherid="'+rowData.id+'" saltypeid="'+rowData.salarybasistype+'" dataname="'+rowData.full_name+'" picurlsrc="'+picurl+'" style="font-size: 13px;">No Basic Salary Info</span>';
                            } else {
                                // var text = '<a href="javascript:void(0)" class="text-primary additionalearnings additionaldeductions" teacherid="'+rowData.id+'" saltypeid="'+rowData.salarybasistype+'">View</a>';
                                var text = '<a href="javascript:void(0)" class="text-primary additionalearnings" teacherid="'+rowData.id+'" saltypeid="'+rowData.salarybasistype+'" dataname="'+rowData.full_name+'" picurlsrc="'+picurl+'">View</a>';
                            }
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    }
                    // ,
                    // {
                    //     'targets': 3,
                    //     'orderable': false, 
                    //     'createdCell':  function (td, cellData, rowData, row, col) {
                    //         var text = '<a href="javascript:void(0)" class="text-primary" id="additionaldeductions" teacherid="'+rowData.id+'">0</a>';
                    //         $(td)[0].innerHTML =  text
                    //         $(td).addClass('align-middle  text-center')
                    //     }
                    // }
                ],
                rowCallback: function (row, data) {
                    // Add your highlight class to the row based on a condition
                    $(row).addClass('highlight' + data.id);
                }
            })
            // var label_text = $($('#employee_datatables_wrapper')[0].children[0])[0].children[0]
            // $(label_text)[0].innerHTML = `<div class="form-group" style="margin-left: 10px;">
            //     <select class="form-control form-control-sm select2" id="select-payrollrange"></select>
            // </div>`
            
        }


        // function getearnings(payrollid, teacherid) {
        function getearnings(teacherid) {
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/additionalearningdeductions/getearnings",
                data: {
                    // payrollid : payrollid,
                    teacherid : teacherid
                },
                success: function (data) {
                    allemployeeearnings = data
                    earningtable()
                }
            });
        }

        function getemployeeearnings(particularid, teacherid) {
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/additionalearningdeductions/getemployeeearnings",
                data: {
                    particularid : particularid,
                    teacherid : teacherid
                },
                success: function (data) {
                    employeespecificearnings = data
                    $('#headearning').text('Update Earning')
                    $('.saveearning').addClass('updateearning')
                    $('.updateearning').removeClass('saveearning')
                    $('.updateearning').addClass('btn-success')
                    $('.updateearning').removeClass('btn-primary')
                    
                    $('#input-particular-description').val(employeespecificearnings.description)
                    $('#input-particular-amount').val(employeespecificearnings.amount)
                    $('#input-particular-remarks').val(employeespecificearnings.remarks)
                    $('#earning-payrollrange').val(employeespecificearnings.payrollid)
                    $('#particular_id').val(employeespecificearnings.id)
                    
                    $('.modal_addearnings').modal('show')
                }
            });
        }


        function earningtable(){
            console.log(allemployeeearnings);
            $('#loadearnings_datatables').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: false,
                autoWidth: false,
                searching: false,
                order: false,
                "bInfo": false,
                data: allemployeeearnings,
                columns : [
                    {"data" : null},
                    // {"data" : null},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null}
                ], 
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0">'+rowData.description+'</a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    // {
                    //     'targets': 1,
                    //     'orderable': false, 
                    //     'createdCell':  function (td, cellData, rowData, row, col) {
                    //         var text = '<spanaclass="text-primary" id="" particular="'+rowData.id+'" style="font-size: 14px;">'+rowData.daterange+'</spanaclass=>';
                    //         $(td)[0].innerHTML =  text
                    //         $(td).addClass('align-middle  text-center')
                    //     }
                    // }
                    // ,
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<span href="javascript:void(0)" class="" id="" particular="'+rowData.id+'">'+rowData.amount+'</span>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            if (rowData.remarks == null) {
                                remarks = '';
                            } else {
                                remarks = rowData.remarks;
                            }
                            var text = '<span href="javascript:void(0)" class="" id="" particular="'+rowData.id+'">'+remarks+'</span>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a href="javascript:void(0)" class="text-primary deleteearnings" id="deleteearnings'+rowData.id+'" data-particular="'+rowData.id+'"><i class="fas fa-trash text-danger"></i></a> &nbsp;&nbsp;<a href="javascript:void(0)" class="text-primary editearnings" id="editearnings'+rowData.id+'" data-particular="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    }
                ]

            })

            var label_text = $($('#loadearnings_datatables_wrapper')[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = `<a href="javascript:void(0)" class="text-primary" id="addearning" style="margin-left: 10px; height: 34px;"><i class="fas fa-plus"></i>&nbsp;Add Earning</a>`
        }

        // Delete Earnings
        function deleteemployeeearnings(particularid, teacherid){
            var payrollid = 0;
            Swal.fire({
            text: 'Are you sure you want to Remove Earning?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "GET",
                        url: "/payrollclerk/setup/additionalearningdeductions/deleteearning",
                        data: {
                            particularid : particularid,
                            teacherid : teacherid
                        },
                        success: function (data) {
                            if(data[0].status == 0){
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].message
                                })
                                }else{
                                // getearnings(payrollid, teacherid)
                                getearnings(teacherid)
                                Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                                })
                            }
                        }
                    });
                }
            })
        }

        // DEDUCTIONS
        function deductiontable(){
            $('#loaddeductions_datatables').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: false,
                autoWidth: false,
                searching: false,
                order: false,
                "bInfo": false,
                data: allemployeeearnings,
                columns : [
                    {"data" : null},
                    // {"data" : null},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null}
                ], 
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0">'+rowData.description+'</a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    // {
                    //     'targets': 1,
                    //     'orderable': false, 
                    //     'createdCell':  function (td, cellData, rowData, row, col) {
                    //         var text = '<spanaclass="text-primary" id="" particular="'+rowData.id+'" style="font-size: 14px;">'+rowData.daterange+'</spanaclass=>';
                    //         $(td)[0].innerHTML =  text
                    //         $(td).addClass('align-middle  text-center')
                    //     }
                    // },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<span href="javascript:void(0)" class="" id="" particular="'+rowData.id+'">'+rowData.amount+'</span>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            if (rowData.remarks == null) {
                                remarks = '';
                            } else {
                                remarks = rowData.remarks;
                            }
                            var text = '<span href="javascript:void(0)" class="" id="" particular="'+rowData.id+'">'+remarks+'</span>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a href="javascript:void(0)" class="text-primary deleteedeductions" id="deleteedeductions'+rowData.id+'" data-particular="'+rowData.id+'"><i class="fas fa-trash text-danger"></i></a> &nbsp;&nbsp;<a href="javascript:void(0)" class="text-primary editdeductions" id="editdeductions'+rowData.id+'" data-particular="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    }
                ]
            })
            var label_text = $($('#loaddeductions_datatables_wrapper')[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = `<a href="javascript:void(0)" class="text-primary" id="adddeduction" style="margin-left: 10px; height: 34px;"><i class="fas fa-plus"></i>&nbsp;Add Deduction</a>`
        }

        function payrollperiods(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/additionalearningdeductions/loadpayrollperiods",
                success: function (data) {
                    payrollperiodss = data;
                    $('#select-earning-payrollrange, #select-deduction-payrollrange, #select-payrollrange').empty();
                    $('#select-earning-payrollrange, #select-deduction-payrollrange, #select-payrollrange').append('<option value="">Select Payroll Date</option>')
                    $('#select-earning-payrollrange, #select-deduction-payrollrange, #select-payrollrange').select2({
                        data: data,
                        allowClear : true,
                        placeholder: {
                            id: '',
                            text: 'Select Payroll Date',
                            template: function (data) {
                                // Customize the placeholder style here
                                return '<span style="font-size: 9px; font-weight: normal;">' + data.text + '</span>';
                            }
                        }
                    });
                    
                }
            });
        }

        // function getdeductions(payrollid, teacherid) {
        function getdeductions(teacherid) {
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/additionalearningdeductions/getdeductions",
                data: {
                    // payrollid : payrollid,
                    teacherid : teacherid
                },
                success: function (data) {
                    allemployeeearnings = data
                    deductiontable()
                }
            });
        }
        
        function getemployeedeductions(particularid, teacherid) {
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/additionalearningdeductions/getemployeedeductions",
                data: {
                    particularid : particularid,
                    teacherid : teacherid
                },
                success: function (data) {
                    employeespecificdeductions = data

                    $('#headearning').text('Update Deduction')
                    $('.savededuction').addClass('updatededuction')
                    $('.updatededuction').removeClass('savededuction')
                    $('.updatededuction').removeClass('btn-primary')
                    $('.updatededuction').addClass('btn-success')
                    
                    $('#input-particular-description').val(employeespecificdeductions.description)
                    $('#input-particular-amount').val(employeespecificdeductions.amount)
                    $('#input-particular-remarks').val(employeespecificdeductions.remarks)
                    $('#earning-payrollrange').val(employeespecificdeductions.payrollid)
                    $('#particular_id').val(employeespecificdeductions.id)
                    
                    $('.modal_addearnings').modal('show')
                }
            });
        }

        function deleteemployeedeductions(particularid, teacherid){
            var payrollid = 0;
            Swal.fire({
            text: 'Are you sure you want to Remove Deduction?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "GET",
                        url: "/payrollclerk/setup/additionalearningdeductions/deletededuction",
                        data: {
                            particularid : particularid,
                            teacherid : teacherid
                        },
                        success: function (data) {
                            if(data[0].status == 0){
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].message
                                })
                                }else{
                                // getdeductions(payrollid, teacherid)
                                getdeductions(teacherid)
                                Toast.fire({
                                    type: 'success',
                                    title: data[0].message
                                })
                            }
                        }
                    });
                }
            })
        }
        
    })
</script>
@endsection


