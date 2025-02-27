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
    {
  font-family: Arial;
}
        .card{
            border: none;
            box-shadow: unset;
        }
  </style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-chart-line nav-icon"></i> Overtime Settings</h4>
                <!-- <h1>Attendance</h1> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Overtime Settings</li>
                </ol>
            </div>
      </div>
    </div><!-- /.container-fluid -->
</section>
<div class="row mb-2">
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
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-12">
      <button type="butotn" class="btn btn-primary" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add Leave</button>
      <div class="modal fade" id="add_leave" style="display: none;" aria-hidden="true">
          <div class="modal-dialog modal-md">
            <div class="modal-content text-uppercase">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Add Leave</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Leave Type</strong></p>
                    <input type="text" class="form-control text-uppercase" id="leave_type"/>
                    {{-- <hr/>
                    <button type="button" class="btn btn-default btn-sm mb-2" id="btn-adddates"><i class="fa fa-plus"></i> Dates covered</button>
                    <div id="div-adddates"></div> --}}
                    {{-- <hr/>
                    <p><strong>Employees</strong></p>
                    <select class="select2bs4" multiple="multiple" data-placeholder="Select a State" style="width: 100%;" id="select-employees">
                    @foreach ($employees as $employee)
                        <option value="{{$employee->id}}">{{ucwords(strtolower($employee->lastname))}}, {{ucwords(strtolower($employee->firstname))}}</option>
                    @endforeach
                    </select> --}}
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-close" id="btn-close" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-newleave">Save changes</button>
                </div>
            </div>
          </div>
      </div>
    </div>  
</div>
<div id="results-container"></div>
<div class="modal fade" id="modal-addmoredates" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content text-uppercase">
          <div class="modal-header bg-info">
              <h4 class="modal-title">Add Dates</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
              <button type="button" class="btn btn-default btn-sm mb-2" id="btn-addmoredates"><i class="fa fa-plus"></i> Dates covered</button>
              <div class="row mb-2">
                  <div class="col-md-5">
                      <input type="date" class="form-control input-adddatefrom"/>
                  </div>
                  <div class="col-md-5">
                      <input type="date" class="form-control input-adddateto"/>
                  </div>
                  {{-- <div class="col-md-2">
                      <button type="button" class="btn btn-default btn-removedate"><i class="fa fa-times"></i></button>
                  </div> --}}
              </div>
              <div id="div-addmoredates"></div>
          </div>
          <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="btn-submit-moredates">Save changes</button>
          </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modal-addmoreemployees" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content text-uppercase">
          <div class="modal-header bg-info">
              <h4 class="modal-title">Add Employees</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
            <select class="select2bs4" multiple="multiple" style="width: 100%;" id="select-moreemployees">
                @foreach ($employees as $employee)
                    <option value="{{$employee->id}}">{{ucwords(strtolower($employee->lastname))}}, {{ucwords(strtolower($employee->firstname))}}</option>
                @endforeach
            </select>    
            <hr>
            <label>Who can approve their request?</label>
            <select class="select2bs4" multiple="multiple" style="width: 100%;" id="select-approvals">
                @foreach ($employees as $employee)
                    <option value="{{$employee->userid}}">{{ucwords(strtolower($employee->lastname))}}, {{ucwords(strtolower($employee->firstname))}}</option>
                @endforeach
            </select>    
          </div>
          <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="btn-submit-moreemployees">Save changes</button>
          </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modal-view-approvals" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content text-uppercase">
          <div class="modal-header bg-info">
              <h4 class="modal-title">Approval Settings</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body" id="approval-container">
                
          </div>
          <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="btn-submit-moreapprovals">Save changes</button>
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
        
    var leavedates = [];
    var leaveemployees = [];
    
    var leaveid = 0;
    $(document).ready(function(){
        $('.select2').select2()
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
        // $('#btn-adddates').on('click', function(){
        //     $('#div-adddates').append(
        //         '<div class="row mb-2">'+
        //             '<div class="col-md-6">'+
        //                 '<input type="date" class="form-control input-adddates"/>'+
        //             '</div>'+
        //             '<div class="col-md-2">'+
        //                 '<button type="button" class="btn btn-default btn-removedate"><i class="fa fa-times"></i></button>'+
        //             '</div>'+
        //         '</div>'
        //     )
        // })
        $('#btn-addmoredates').on('click', function(){
            $('#div-addmoredates').append(
                '<div class="row mb-2">'+
                    '<div class="col-md-5">'+
                        '<input type="date" class="form-control input-adddatefrom"/>'+
                    '</div>'+
                    '<div class="col-md-5">'+
                        '<input type="date" class="form-control input-adddateto"/>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<button type="button" class="btn btn-default btn-removedate"><i class="fa fa-times"></i></button>'+
                    '</div>'+
                '</div>'
            )
        })
        $(document).on('click','.btn-removedate', function(){
            $(this).closest('.row').remove();
        })

        function loaddata()
        {
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                closeOnClickOutside: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                }
            })  
            $.ajax({
                url: '/hr/settings/leaves?action=load',
                type: 'GET',
                success:function(data){
                    $('#results-container').empty()
                    $('#results-container').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    leavedates = [];
                    leaveemployees = [];
                    
                }
            })
        }
        loaddata()
        
        $('#btn-submit-newleave').on('click', function(){
            var leave_type = $('#leave_type').val();
            if(leave_type.replace(/^\s+|\s+$/g, "").length == 0)
            {
                $('#leave_type').css('border','1px solid red');
                toastr.warning('Please fill in required field!', 'Leave Settings')
            }else{
                $('#leave_type').removeAttr('style');
                // $('.input-adddates').each(function(){
                //     if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
                //     {
                //         leavedates.push($(this).val())
                //     }
                // })
                // leaveemployees = $('#select-employees').val()
                $.ajax({
                    url: '/hr/settings/leaves?action=create',
                    type: 'GET',
                    data: {
                        // leaveemployees     :   JSON.stringify(leaveemployees),
                        leave_type         :   leave_type,
                        // leavedates         :   JSON.stringify(leavedates)
                    },
                    complete:function(){
                        leavedates = [];
                        leaveemployees = [];
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        toastr.success('Added successfully!', 'Leave Settings')
                        loaddata()
                        $('.btn-close').click();
                    }
                })                
            }
        })

        $(document).on('click','input.leavename', function(){
            leaveid = $(this).attr('data-id');
        })
        $(document).on('click','input.leavedays', function(){
            leaveid = $(this).attr('data-id');
        })
        
        $(document).on('keyup', 'input.leavename', function (e) {
            if (e.which == 13) {
                var leavename = $('input.leavename[data-id="'+leaveid+'"]').val();
                $.ajax({
                    url: '/hr/settings/leaves?action=updateleavename',
                    type: "get",
                    data: {
                        leavename: leavename,
                        id: leaveid,
                    },
                    success: function (data) {
                        toastr.success('Updated successfully!')
                        $('.leavename').attr('readonly', true);
                    }
                });
                return false;    //<---- Add this line
            }
        });
        $(document).on('click', 'input[name="withpay"]', function () {
            if($(this).is(":checked")){
                var withpay = 1;
            }
            else if($(this).is(":not(:checked)")){
                var withpay = 0;
            }
            leaveid = $(this).attr('data-id');
            $.ajax({
                url: '/hr/settings/leaves?action=updatewithpay',
                type: "get",
                data: {
                    id: leaveid,
                    withpay: withpay
                },
                success: function (data) {
                    toastr.success('Updated successfully!')
                }
            });

        });
        $(document).on('keyup', 'input.leavedays', function (e) {
            if (e.which == 13) {
                var leavedays = $('input.leavedays[data-id="'+leaveid+'"]').val();
                $.ajax({
                    url: '/hr/settings/leaves?action=updateleavedays',
                    type: "get",
                    data: {
                        leavedays: leavedays,
                        id: leaveid,
                    },
                    success: function (data) {
                        toastr.success('Updated successfully!')
                        $('.leavedays').attr('readonly', true);
                    }
                });
                return false;    //<---- Add this line
            }
        });
        $(document).on('click','.btn-deleteleave', function(){
            var leaveid = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure you want to delete this leave type?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/hr/settings/leaves?action=deleteleave',
                        type: 'GET',
                        data: {
                            id     :   leaveid
                        },
                        complete:function(){
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                            toastr.success('Deleted successfully!', 'Leave Settings')
                            loaddata()
                        }
                    })
                }
            })
        })
        $(document).on('click','.btn-addmoredates', function(){
            $('#modal-addmoredates').modal('show')
            leaveid = $(this).attr('data-leaveid');
        })
        $(document).on('click','#btn-submit-moredates', function(){
            leavedates = [];
            $('.input-adddatefrom').each(function(){
                if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
                {
                    obj = {
                        'datefrom'  : $(this).val(),
                        'dateto'  : $(this).closest('.row').find('.input-adddateto').val()
                    }
                    leavedates.push(obj)
                }
            })
            if(leavedates.length>0)
            {
                $.ajax({
                    url: '/hr/settings/leaves?action=addmoredays',
                    type: "get",
                    data: {
                        leavedates: JSON.stringify(leavedates),
                        id: leaveid,
                    },
                    success: function (data) {
                        $('.btn-close').click();
                        toastr.success('Updated successfully!')
                        loaddata()
                        leavedates = [];
                        $('#div-addmoredates').empty()
                    }
                });
            }
        })
        $(document).on('click','.btn-addmoreemployees', function(){
            $('#modal-addmoreemployees').modal('show')
            leaveid = $(this).attr('data-leaveid');
        })
        $('#btn-submit-moreemployees').on('click', function(){
            leaveemployees = $('#select-moreemployees').val();
            var eachapprovals = $('#select-approvals').val();
            
            if(leaveemployees.length>0)
            {
                if(eachapprovals.length == 0)
                {
                    toastr.warning('Please fill in required fields!')
                    $('#select-approvals').css('border','1px solid red')
                }else{
                    $.ajax({
                        url: '/hr/settings/leaves?action=addmoreemployees',
                        type: "get",
                        data: {
                            leaveemployees: JSON.stringify(leaveemployees),
                            eachapprovals: JSON.stringify(eachapprovals),
                            id: leaveid,
                        },
                        success: function (data) {
                            toastr.success('Updated successfully!')
                            loaddata()
                            $('.btn-close').click();
                            leaveemployees = [];
                            $('#select-moreemployees').val('')
                        }
                    });
                }
            }
        })
        $(document).on('click', '.btn-delete-date', function(){
            var id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure you want to delete this date?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/hr/settings/leaves?action=deletedate',
                        type: 'GET',
                        data: {
                            id     :   id
                        },
                        complete:function(){
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                            toastr.success('Deleted successfully!', 'Leave Settings')
                            loaddata()
                        }
                    })
                }
            })
        })
        $(document).on('click', '.btn-delete-employee', function(){
            var id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure you want to delete this employee?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/hr/settings/leaves?action=deleteemployee',
                        type: 'GET',
                        data: {
                            id     :   id
                        },
                        complete:function(){
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                            toastr.success('Deleted successfully!', 'Leave Settings')
                            loaddata()
                        }
                    })
                }
            })
        })
        $(document).on('click','.btn-view-approvals', function(){
            var leaveempid = $(this).attr('data-leaveempid');
            $('#modal-view-approvals').modal('show')
            $.ajax({
                url: '/hr/settings/leaves?action=getapprovals',
                type: 'GET',
                data: {
                    leaveempid     :   leaveempid
                },
                success:function(data){
                    $('#approval-container').empty();
                    $('#approval-container').append(data);
                    $('.select2bs4').select2({
                        theme: 'bootstrap4'
                    })
                    $('#btn-submit-moreapprovals').hide();
                    $('#btn-submit-moreapprovals').attr('data-leaveempid', leaveempid);
                }
            })
        })
        $(document).on('change','#select-moreapprovals', function(){
            if($(this).val() == "")
            {
                $('#btn-submit-moreapprovals').hide();
            }else{
                $('#btn-submit-moreapprovals').show();
            }
        })
        $(document).on('click','#btn-submit-moreapprovals', function()
        {
            var leaveempid = $(this).attr('data-leaveempid');
            var moreapprovals  = $('#select-moreapprovals').val();
            $.ajax({
                url: '/hr/settings/leaves?action=addmoreapprovals',
                type: "get",
                data: {
                    moreapprovals: JSON.stringify(moreapprovals),
                    leaveempid: leaveempid,
                },
                success: function (data) {
                    toastr.success('Updated successfully!')
                    loaddata()
                    $('.btn-close').click();
                    $('#select-moreapprovals').val('')
                    $('.btn-view-approvals[data-leaveempid="'+leaveempid+'"]').click()
                }
            });
        })
        $(document).on('click','.btn-delete-approval', function(){
            var approvalid = $(this).attr('data-id');
            var thisrow    = $(this).closest('tr');
            Swal.fire({
                title: 'Are you sure you want to delete this employee?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/hr/settings/leaves?action=deleteapproval',
                        type: 'GET',
                        data: {
                            approvalid     :   approvalid
                        },
                        complete:function(data){
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                            toastr.success('Deleted successfully!', 'Approval Settings')
                            thisrow.remove()
                        }
                    })
                }
            })
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

