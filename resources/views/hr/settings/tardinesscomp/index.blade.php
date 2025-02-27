

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
@extends('hr.layouts.app')
@section('content')
<style>
    .mobile{
        display: none;
    }
    @media only screen and (max-width: 600px) {
        .mobile {
            display: block;
        }
        .web {
            display: none;
        }
    }
    .container {padding:20px;}
.popover {width:170px;max-width:170px;}
.popover-content h4 {
  color: #00A1FF;
}
.popover-content h4 small {
  color: black;
}
.popover-content button.btn-primary {
  color: #00A1FF;
  border-color:#00A1FF;
  background:white;
}

.popover-content button.btn-default {
  color: gray;
  border-color:gray;
}

.dataTables_wrapper .dataTables_info {
    clear:none;
    margin-left:10px;
    padding-top:0;
}
.swal2-header {
    border: hidden;
}
</style>

@php
    $activedepts = collect($computations)->where('isactive','1')->unique('departmentid')->values();
@endphp
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Late Computation Setup</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Late Computation Setup</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
   </section>
   <div class="card shadow" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; border: none !important;">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <label>Select Department</label>
                    <select class="form-control select2" id="select-departmentid">    
                        @if(count($departments)>0)
                            @foreach($departments as $department)
                                <option value="{{$department->id}}">{{$department->department}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="hidden" id="depid">
                </div>
                <div class="col-md-3">
                    <div class="form-group clearfix">
                        <label>&nbsp;</label><br>
                        <div class="icheck-primary d-inline" style="position: absolute!important;">
                            <input type="checkbox" id="checkbox-activation-bos">
                            <label for="checkbox-activation-bos">
                                Tardiness Base on Salary
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>
   <div class="card shadow" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; border: none !important;" id="card-results">
        <div class="card-body" id="container-brackets">
        </div>
   </div>
   
   {{-- <div class="row">
       <div class="col-md-4">
            <div class="card" style="border: none; min-height: 600px;">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Departments</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-1">
                    <div class="row mt-2 mb-2">
                        <div class="col-md-2">
                            <div class="form-group m-0">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="department-0" data-id="0" @if(collect($activedepts)->where('departmentid','0')->count()>0) checked @endif/>
                                    <label class="custom-control-label" for="department-0">&nbsp;</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <button type="button" class="each-department btn btn-sm btn-outline-success btn-block p-0 text-left" data-deptid="0">&nbsp;&nbsp;&nbsp;&nbsp;ALL DEPARTMENTS</button>
                        </div>
                    </div>
                    <hr/>
                    @if(count($departments)>0)
                        @foreach($departments as $department)
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <div class="form-group m-0">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="department-{{$department->id}}" data-id="{{$department->id}}" @if(collect($activedepts)->where('departmentid',$department->id)->count()>0) checked @endif/>
                                        <label class="custom-control-label" for="department-{{$department->id}}">&nbsp;</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <button type="button" class="each-department btn btn-sm btn-outline-success btn-block p-0 text-left" data-deptid="{{$department->id}}">&nbsp;&nbsp;&nbsp;&nbsp;{{$department->department}}</button>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
       </div>
       <div class="col-md-8">
            <div class="card" style="border: none; min-height: 600px;">
                <div class="card-body p-1" id="container-brackets">
                    
                </div>
                <div class="card-footer text-right">
                    <button type="button" class="btn btn-sm btn-primary" id="btn-submit">Submit New Time Brackets</button>
                </div>
            </div>
        </div>
   </div> --}}
@endsection
@section('footerscripts')
    <script>
        $(document).ready(function(){
            $('#card-results').hide();
            function selectdepartment()
            {
                $('#card-results').hide();
                var deptid = $('#select-departmentid').val();
                $('#depid').val(deptid)
                loadtardinessbos(deptid)

                $.ajax({
                    url: '/hr/tardinesscomp/getbrackets',
                    type:"GET",
                    data:{
                        deptid: deptid
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    success: function(data){
                        $('#card-results').show();
                        $('#container-brackets').empty()
                        $('#container-brackets').append(data)
                    }
                })
            }
            @if(count($departments)>0)
            selectdepartment();
            @else
            toastr.success('No department(s) found!)
            @endif
            $('#select-departmentid').on('change', function(){
                selectdepartment();
            })
            $('.select2').select2({
            theme: 'bootstrap4'
            })

            $('.each-department').on('click', function(){
                var deptid = $(this).attr('data-deptid')
                $('.each-department').removeClass('btn-success')
                $('.each-department').addClass('btn-outline-success')
                $(this).removeClass('btn-outline-success');
                $(this).addClass('btn-success');
                $.ajax({
                    url: '/hr/tardinesscomp/getbrackets',
                    type:"GET",
                    data:{
                        deptid: deptid
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    success: function(data){
                        $('#container-brackets').empty()
                        $('#container-brackets').append(data)
                    }
                })
            })
            $('.custom-control-input').on('click', function(){
                var deptid = $(this).attr('data-id');
                var isactive = 0;
                if ( $(this).is(':checked') ) {
                    isactive = 1
                } 
                $.ajax({
                    url: '/hr/tardinesscomp/activation',
                    type:"GET",
                    data:{
                        deptid      : deptid,
                        isactive    : isactive
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    success: function(data){
                        if(data == 1)
                        {                            
                            toastr.success('Updated successfully!', 'Activation')
                        }else if(data == 3){
                            toastr.warning('No brackets found!', 'Activation')
                        }
                    }
                })

            });

            function loadtardinessbos(deptid){
                $.ajax({
                    type: "GET",
                    url: "/hr/tardinesscomp/loadtardinessbos",
                    data: {
                        deptid : deptid
                    },
                    success: function (data) {
                        if (data.isactive == 1) {
                            $('#checkbox-activation-bos').prop('checked', true);
                            $("#checkbox-activation-bos").attr("status", "deactivate");
                        } else {
                            $('#checkbox-activation-bos').prop('checked', false);
                            $("#checkbox-activation-bos").attr("status", "activate");
                        }
                    }
                });
            }


            $('#checkbox-activation-bos').on('click', function(){
                // $('#checkbox-activation').prop('checked', false);
                var deptid = $('#depid').val();
                var applyall = 0;
                var status = $(this).attr('status');

                if (status == 'deactivate') {
                    console.log(status);
                } else {
                    $('#checkbox-activation').prop('checked', false)
                }
                $.ajax({
                    type: "GET",
                    url: "/hr/tardinesscomp/baseonsalary",
                    data: {
                        deptid : deptid,
                        applyall : applyall,
                        status : status
                    },

                    success: function (data) {
                        if(data == 1){            
                            toastr.success('Updated successfully!', 'Base On Attendance')
                        }
                    }
                });
            })
        })
    </script>
@endsection

