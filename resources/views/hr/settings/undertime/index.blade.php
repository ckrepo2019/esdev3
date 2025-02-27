@extends('hr.layouts.app')
@section('content')
<style>
    .onoffswitch-inner:before {
    background-color: #55ce63;
    color: #fff;
    content: "ON";
    padding-left: 14px;
}
*{
  font-family: Arial;
}
        .card{
            border: none;
            box-shadow: unset;
        }
        
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #006fe6;
        color: #fff;
        padding: 0 10px;
        margin-top: .31rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
    }
  </style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4> Undertime Settings</h4>
                <!-- <h1>Attendance</h1> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Undertime Settings</li>
                </ol>
            </div>
      </div>
    </div><!-- /.container-fluid -->
</section>

<div class="card shadow" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
    {{-- <div class="card-header">

    </div> --}}
    <div class="card-body">
        <table class="table" id="example2" style="font-size: 12px;">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 25%;">Employee</th>
                    <th style="width: 40%;">Portals</th>
                    <th>Approvals</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $key=>$employee)
                    <tr>
                        <td style="vertical-align: top;">{{$key+1}}</td>
                        <td style="vertical-align: top;">{{strtoupper($employee->lastname)}}, {{strtoupper($employee->firstname)}}</td>
                        <td style="vertical-align: top;">{{ucwords(strtolower($employee->utype))}} @if(count($employee->otherportals)>0)@foreach($employee->otherportals as $eachportal), {{ucwords(strtolower($eachportal->utype))}}@endforeach @endif</td>
                        <td >
                            <div class="row" id="container-{{$employee->id}}">
                                @if(count($employee->approvals)>0)
                                    @foreach($employee->approvals as $approval)
                                        <div class="col-md-10">
                                            <a class="each-approval" data-id="{{$approval->id}}">{{ucwords(strtolower($approval->lastname))}}, {{ucwords(strtolower($approval->firstname))}}</a>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <button type="button" class="btn btn-sm btn-outline-danger pt-0 pr-1 pb-0 pl-1 btn-eachapproval-delete"  data-id="{{$approval->id}}" style="font-size: 10px;"><i class="fa fa-trash-alt"></i></button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-sm btn-default pt-0 pr-1 pb-0 pl-1 btn-addapproval" data-id="{{$employee->id}}"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>



<div class="modal fade" id="modal-addapproval">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Who can approve the applications?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        
                        <select class="select2 form-control mt-1" multiple="multiple" name="approvals" id="select-approvals" style="wdth: 100%;border-left: hidden;border-right: hidden;border-top: hidden;">
                            @foreach ($employees as $employee)
                                <option value="{{$employee->userid}}">{{$employee->sortname}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-modal-close">Close</button>
                <button type="button" class="btn btn-primary" id="btn-saveapproval">Save changes</button>
            </div>
        </div>
        
    </div>
    
</div>
    
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>
        
        $(document).ready(function(){
             var empid = 0;
            $('#example2').DataTable({
                // "paging": false,
                // "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
            $('#btn-apply-modal').on('click', function(){
                $('#modal-apply').modal('show')
            })
            $('.btn-addapproval').on('click', function(){
                var employeeid = $(this).attr('data-id');
                $('#modal-addapproval').modal('show')
                $('#btn-saveapproval').attr('data-id',employeeid)
                empid = employeeid;
            })
            $('.select2').select2()
            function getapprovals()
            {
                    $.ajax({
                        url: '/hr/settings/undertime',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            action      : 'getapprovals',
                            employeeid  :  empid
                        },
                        success:function(data)
                        {
                            var display_approvals = '';
                            $('#container-'+empid).empty()
                            if(data.length > 0)
                            {
                                $.each(data, function(key, value){
                                        display_approvals+='<div class="col-md-10"><a class="each-approval" data-id="'+value.id+'">'+value.lastname+', '+value.firstname+'</a></div>'+
                                        '<div class="col-md-2 text-right">'+
                                            '<button type="button" class="btn btn-sm btn-outline-danger pt-0 pr-1 pb-0 pl-1 btn-eachapproval"  data-id="'+value.id+'" style="font-size: 10px;"><i class="fa fa-trash-alt"></i></button>'+
                                        '</div>';
                                })
                            }
                            $('#container-'+empid).append(display_approvals)
                        }
                    }); 
            }
            $('#btn-saveapproval').on('click', function(){
                var approvals = $('#select-approvals').val()
                if(approvals.length == 0)
                {
                    $('#select-approvals').css('border','1px solid red')
                    toastr.warning('Please select who can approve the applications!', 'Add Approval')
                }else{
                    
                var employeeid = $(this).attr('data-id');
                    $.ajax({
                        url: '/hr/settings/undertime',
                        type: 'GET',
                        data: {
                            action      : 'addapproval',
                            employeeid  :  employeeid,
                            userids     :   JSON.stringify(approvals)
                        },
                        success:function(data)
                        {
                            if(data == 1)
                            {
                                toastr.success('Updated successfully!', 'Add Approval')

                            }else{
                                toastr.warning('The same form already exists!', 'Add new record')
                            }
                                $('#select-approvals').val(null).trigger('change');
                            $('#btn-modal-close').click();
                            getapprovals()
                        }
                    }); 
                }
            })
            $('.btn-eachapproval-delete').on('click', function(){
                
                var appid = $(this).attr('data-id');
                Swal.fire({
                    title: 'Deleting approval...',
                    html: "Would you like to continue? <br/> You won't be able to revert this.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                            
                            $.ajax({
                        url: '/hr/settings/undertime',
                                type:"GET",
                                dataType:"json",
                                data:{
                                    action   :  'deleteapproval',
                                    appid   :  appid
                                },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                if(data == 1)
                                {
                                    toastr.success('Deleted successfully!')
                                    window.location.reload()
                                }else{
                                    toastr.error('Something went wrong!')
                                }
                            }
                        })
                    }else if(result.dismiss == 'cancel'){
                            thisselect.val(statorigval)
                            
                    }
                })
            })
        })
  </script>
@endsection

