

@extends('hr.layouts.app')
@section('content')
<style>
    .onoffswitch-inner:before {
    background-color: #55ce63;
    color: #fff;
    content: "ON";
    padding-left: 14px;
}
</style>
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-12">
            <!-- <h3 class="page-title">Leave Settings</h3> -->
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            LEAVE SETTINGS</h4>
            <ul class="breadcrumb col-md-10 float-left">
                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                <li class="breadcrumb-item active">Leave Settings</li>
            </ul>
            <div class="col-md-2 float-right ml-auto">
                <a href="#" class="btn btn-block" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add Leave</a>
                <div class="modal fade" id="add_leave" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <form action="/leavesettings/addleave" method="get">
                            <div class="modal-content text-uppercase">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title">Add Leave</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Leave Type</strong></p>
                                    <input type="text" class="form-control text-uppercase" name="leave_type" required/>
                                    <br>
                                    <p><strong>Days</strong></p>
                                    <input type="number" class="form-control" name="days" required/>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </form>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        @if(session()->has('messageExists'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                {{ session()->get('messageExists') }}
            </div>
        @endif
        @if(session()->has('messageAdd'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                {{ session()->get('messageAdd') }}
            </div>
        @endif
        @if(session()->has('messageDelete'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                {{ session()->get('messageDelete') }}
            </div>
        @endif
        
        <!-- Sick Leave -->
        @foreach($leaves as $leave)
        <div class="card " id="leave_annual">
            <div class="card-body">
                <form name="submitstatus" method="get">
                    <input value="{{$leave->isactive}}" name="newisactive" id="newisactive{{$leave->id}}" hidden>
                    <input value="{{$leave->id}}" name="leaveid" hidden>
                </form>
                <strong><h3>{{$leave->leave_type}}</h3></strong> 	
                <label>Days</label>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <form name="submitdays" method="get">
                            <div class="input-group" id="{{$leave->id}}">
                                <input type="text" class="form-control " name="days" value="{{$leave->days}}" readonly>
                                <input type="text" class="form-control " name="leaveid" value="{{$leave->id}}" hidden>
                                <div class="input-group-append">
                                @if($leave->isactive == '0')
                                    <span class="input-group-text" id="{{$leave->id}}"><i class="fas fa-edit"></i></span>
                                @elseif($leave->isactive == '1')
                                    <span class="input-group-text btnedit" id="{{$leave->id}}"><i class="fas fa-edit"></i></span>
                                @endif
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <form action="/leavesettings/updatewithorwithoutpay" name="submitwithorwithoutpay" method="get">
                            <input type="hidden"  value="{{$leave->id}}" name="leaveid">
                            <div class="form-group clearfix">
                            @if($leave->withpay == 1)
                                <div class="icheck-primary d-inline mr-3">
                                    <input type="radio" id="{{$leave->id}}radioPrimary1" name="withpay" value="1" checked>
                                    <label for="{{$leave->id}}radioPrimary1">
                                        With Pay
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="{{$leave->id}}radioPrimary2" name="withpay" value="0">
                                    <label for="{{$leave->id}}radioPrimary2">
                                        Without Pay
                                    </label>    
                                </div>
                            @else
                                <div class="icheck-primary d-inline mr-3">
                                    <input type="radio" id="{{$leave->id}}radioPrimary1" name="withpay" value="1" >
                                    <label for="{{$leave->id}}radioPrimary1">
                                        With Pay
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="{{$leave->id}}radioPrimary2" name="withpay" value="0" checked>
                                    <label for="{{$leave->id}}radioPrimary2">
                                        Without Pay
                                    </label>    
                                </div>
                            @endif
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="custom-control custom-switch custom-switch-on-success" >
                        <!-- <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-3"></div>
                            <div class="col-md-3"></div>
                        </div> -->
                            @if($leave->isactive == '0')
                            <input type="checkbox"  value="{{$leave->id}}" style="border:1px solid black;" name="isactive" id="isactive{{$leave->id}}" class="custom-control-input">
                            <label class="custom-control-label" for="isactive{{$leave->id}}">Inactive</label>
                            @elseif($leave->isactive == '1')
                            <input type="checkbox"  value="{{$leave->id}}" name="isactive"  id="isactive{{$leave->id}}" class="custom-control-input" checked>
                            <label class="custom-control-label" for="isactive{{$leave->id}}">Active</label>
                            @endif
                            <a href="#" class="btn" data-toggle="modal" data-target="#delete_leave{{$leave->id}}"><i class="fa fa-trash"></i></a>
                            <div class="modal fade" id="delete_leave{{$leave->id}}" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-md">
                                    <form action="/leavesettings/deleteleave" method="get">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Delete Leave type</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Leave Type</strong></p>
                                                <input type="text" class="form-control" value="{{$leave->leave_type}}" name="leave_type" readonly/>
                                                <br>
                                                <p><strong>Days</strong></p>
                                                <input type="number" class="form-control" value="{{$leave->days}}" name="days" readonly/>
                                                <input type="hidden" class="form-control" value="{{$leave->id}}" name="leaveid"/>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </form>
                                <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <!-- /Sick Leave -->
        
        <!-- Hospitalisation Leave -->
        <!-- /Hospitalisation Leave -->
        
        <!-- Maternity Leave -->
        <!-- /Maternity Leave -->
        
        <!-- Paternity Leave -->
        <!-- /Paternity Leave -->
        
    </div>
</div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('input[type=checkbox]').on('change', function(){
            // console.log($(this).closest("div.custom-control").find("input[name=isactive]").prop());
            if ($(this).closest("div.custom-control").find("input[name=isactive]").prop('checked') == true) {
                var isactive = 1;
            }
            else{
                var isactive = 0;
            }
            var leaveid  = $(this).val();
            
            $(this).closest("div.card-body").find('input[name=newisactive]').val(isactive)
            $(this).closest("div.card-body").find('form[name=submitstatus]').attr('action', '/leavesettings/updatestatus').submit();
            
        })
        $('.btnedit').on('click', function(){
            var leaveid = $(this).attr("id");
            $(this).closest("div.input-group").find('input[name=days]').prop('readonly', false);
            $(this).closest("div.input-group").append(
                '<button class="btn btn-default ml-2 mr-2 btncancel" name="btncancel" id="btncancel'+leaveid+'">Cancel</button> <button class="btn btn-success btnsave" id="'+leaveid+'">Save</button>'
            );
            $(this).remove();
        });
        $(document).on('click','.btncancel', function(){
            window.location.reload();
        })
        $(document).on('click','.btnsave', function(){
            $(this).closest("div.card-body").find('form[name=submitdays]').attr('action', '/leavesettings/updatedays').submit();
        })
        window.setTimeout(function () {
            $(".alert-success").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
        window.setTimeout(function () {
            $(".alert-danger").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
    });
    $(document).on('click','input[name=withpay]', function(){
        $(this).closest('form').submit();
    })
  </script>
@endsection

