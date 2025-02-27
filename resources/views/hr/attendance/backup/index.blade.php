

@extends('hr.layouts.app')
@section('content')
<!-- DataTables -->
{{-- <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}"> --}}

<style>
    table.table td h2.table-avatar {
    align-items: center;
    display: inline-flex;
    font-size: inherit;
    font-weight: 400;
    margin: 0;
    padding: 0;
    vertical-align: middle;
    white-space: nowrap;
}
.avatar {
    background-color: #aaa;
    border-radius: 50%;
    color: #fff;
    display: inline-block;
    font-weight: 500;
    height: 38px;
    line-height: 38px;
    margin: 0 10px 0 0;
    text-align: center;
    text-decoration: none;
    text-transform: uppercase;
    vertical-align: middle;
    width: 38px;
    position: relative;
    white-space: nowrap;
}
table.table td h2 span {
    color: #888;
    display: block;
    font-size: 12px;
    margin-top: 3px;
}
.avatar > img {
    border-radius: 50%;
    display: block;
    overflow: hidden;
    width: 100%;
}
img {
    vertical-align: middle;
    border-style: none;
}
.dataTables_filter, .dataTables_info { display: none; }
@media screen and (max-width : 1920px){
  .div-only-mobile{
  visibility:hidden;
  display: none;
  }
}
@media screen and (max-width : 906px){
 .desk{
  visibility:hidden;
  }
 .div-only-mobile{
  visibility:visible;
  display: block;
  }
  .viewtime{
      width: 200px !important;
  }
}
.timepickerinputs{cursor: pointer;}
</style>

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-chart-line nav-icon"></i> ATTENDANCE</h4>
          <!-- <h1>Attendance</h1> -->
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Attendance</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  @if(isset(DB::table('schoolinfo')->first()->servertype))
    @if(DB::table('schoolinfo')->first()->servertype == 1)
        <div class="card" style="border: none !important;">
            {{-- <div class="card-header">
                <div class="row">
                    <div class="col-md-6">Data syncing from Tapping Station to Server</div>
                    <div class="col-md-6"></div>
                </div>
            </div> --}}
            <div class="card-body">

                <div class="row">
                    <div class="col-3 text-bold">Syncing</div>
                    <div class="col-4">
                        <label>Tapping Station:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span id="text-tap-count"></span>
                    </div>
                    <div class="col-3">
                        <label>Server:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span id="text-server-count"></span>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-primary btn-sm btn-block" id="display-sync-info">Sync</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
  @endif
{{-- <form name="changeattendance" action="/changeattendance" method="get"> --}}
    {{-- <div class="row">
        <div class="col-md-4 col-12" id="dateDiv"> --}}
            {{-- <label><small>Date:</small></label> --}}
            {{-- <form name="changedate" action="/attendance/{{Crypt::encrypt('dashboard')}}" method="get"> --}}
                {{-- <input type="date" id="currentDate" class="form-control" value="{{$currentdate}}"/> --}}
                {{-- <input type="text" id="currentDate" name="currentDate" width="176" /> --}}
            {{-- </form> --}}
        {{-- </div>
    </div> --}}
{{-- </form> --}}
{{-- <div class="card">
    <div class="card-body"> --}}
        <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Date:</span>
                    </div>
                    <input type="date" id="currentDate" class="form-control" value="{{$currentdate}}" onkeydown="return false"/>
                </div>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                    <input class="filter form-control" placeholder="Search employee" />
                </div>
            </div>
        </div>
        <div class="row pt-3  mb-2" style=" position: -webkit-sticky;
        position: sticky;
        top: 5%;
        background-color: #63ade8;
        font-size: 20px;z-index: 999; width: 100%;">
            <div class="col-md-3 ">&nbsp;</div>
            <div class="col-md-2 text-center">
                <label>AM IN</label>
            </div>
            <div class="col-md-2 text-center">
                <label>AM OUT</label>
            </div>
            <div class="col-md-2 text-center">
                <label>PM IN</label>
            </div>
            <div class="col-md-2 text-center">
                <label>PM OUT</label>
            </div>
            <div class="col-md-1">
                &nbsp;
            </div>
            {{-- <div class="col-md-12 bg-warning p-2" style="font-size: 11px;">
                <em><strong>New Update!</strong></em>
            </div> --}}
        </div>
        <div class="" id="attendancecontainer">
        </div>
        <div class="modal fade" id="modal-timelogs" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-md">
              <div class="modal-content" id="timelogsdetails">

              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
<!-- Bootstrap 4 -->
{{-- <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- Toastr -->
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<!-- Bootstrap Switch -->
<script src="{{asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script> --}}
@endsection
@section('footerscripts')
<script>
    var $ = jQuery;
    $(document).ready(function(){
        $(".filter").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".card-each-emp").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                $(".card-group.card-group-filter:first-of-type").append($(this));
                $(this).hide();
                hiddenCards++;

                } else {

                $(".card-group.card-group-filter:last-of-type").prepend($(this));
                $(this).show();
                visibleCards++;

                if (((visibleCards % 4) == 0)) {
                    $(".container").append($("<div class='card-group card-group-filter'></div>"));
                }
                }
            });

        });
    })
</script>
<script>
   $(function () {
    var table =  $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']],
        // scrollY:        "500px",
        // scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   true
        });
        // / #myInput is a <input type="text"> element
        $('#myInput').on( 'keyup', function () {
            table.search( this.value ).draw();
        } );
    });
    
    $(document).ready(function(){

        const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1000
        });

        // $('#currentDate').datepicker({
        //     format: 'mm-dd-yyyy',
        //     value: '{{$currentdate}}'
        // });
        $('select[name=monthselection]').on('change', function(){
            // var monthid = $(this).val();
            // var yearid = $('select[name=yearselection]').val();
            $('form[name=changeattendance]').submit();
        })
        $('[data-dismiss=modal]').on('click', function (e) {
            var $t = $(this),
                target = $t[0].href || $t.data("target") || $t.parents('.modal') || [];

            $(target)
                .find("input,textarea,select")
                .val('')
                .end()
                .find("input[type=checkbox], input[type=radio]")
                .prop("checked", "")
                .end();
        });
        $('#currentDate').on('change', function(){
                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    closeOnClickOutside: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                })  
                $.ajax({
                    url: '/hr/attendance/index',
                    type:"GET",
                    data:{
                        changedate:$(this).val()
                    },
                    success:function(data) {
                    $('#attendancecontainer').empty();
                    $('#attendancecontainer').append(data);
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                }
            });
        })
        $('#currentDate').trigger('change')
        $(document).on('click','.timelogs', function(){
            $('#modal-timelogs').modal('show')
            var thisrow = $(this).closest('.row')
            var employeeid = thisrow.attr('id');
            var usertypeid = thisrow.attr('usertypeid');
            var selecteddate =  $('#currentDate').val()
            $.ajax({
                url: '/hr/attendance/gettimelogs',
                type:"GET",
                data:{
                    employeeid: employeeid,
                    usertypeid: usertypeid,
                    selecteddate: selecteddate
                },
                success:function(data) {
                    $('#timelogsdetails').empty()
                    $('#timelogsdetails').append(data)
                }
            });
        })
            $(document).on('keypress', '.employee-remarks',function (e) {
                if (e.which == 13) {
            var selecteddate =  $('#currentDate').val()
                    
                    $.ajax({
                        url: "/hr/attendance/updateremarks",
                        type: "get",
                        data: {
                            id: $(this).attr('data-id'),
                            selecteddate: selecteddate,
                            remarks  : $(this).val()
                        },
                        success: function (data) {
                            if(data == 1)
                            {
                                toastr.success('Updated successfully!')
                            }
                        }
                    });
                    return false;    //<---- Add this line
                }
            });
        var newlogscounter = 1;
        $(document).on('click','#buttonaddnewlog', function(){
            $('#newlogscontainer').append(
                '<div class="row mb-2">'+
                    '<div class="col-1">'+
                        '<button type="button" class="btn btn-sm btn-default p-0 mt-1 btn-block savenewtimelog'+newlogscounter+'">&nbsp;<i class="fa fa-check"></i>&nbsp;</button>'+
                    '</div>'+
                    '<div class="col-6">'+
                        '<input type="time" class="form-control form-control-sm" name="newtimelog"/>'+
                        // '<input id="newtimepick'+newlogscounter+'" class="timepick timepickerinputs form-control form-control-sm" name="newtimelog" readonly/>'+
                    '</div>'+
                    '<div class="col-4  p-0 text-center">'+
                    
                        '<input type="checkbox" id="newlogstate'+newlogscounter+'" name="logstate" checked data-bootstrap-switch data-off-color="warning" data-on-text="IN"  data-off-text="OUT" data-on-color="success" data-size="sm">'+
                    '</div>'+
                    // '<div class="col-2  p-0 text-center">'+
                    
                    //     '<input type="checkbox" id="newlog'+newlogscounter+'" name="dayshift" checked data-bootstrap-switch data-off-color="warning" data-on-text="AM"  data-off-text="PM" data-on-color="success" data-size="sm">'+
                    // '</div>'+
                    '<div class="col-1">'+
                        '<button type="button" class="btn btn-sm btn-default p-0 mt-1 btn-block removenewtimelog">&nbsp;<i class="fa fa-times"></i>&nbsp;</button>'+
                    '</div>'+
                '</div>'
            )
            // $('#newtimepick'+newlogscounter).timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'hh:MM'});
            // $('#newtimepick'+newlogscounter).on('change', function(){
            //     var newlogscounter = $(this).val().split(':');
            //     if(newlogscounter[0] == '00'){
            //         $(this).val('12:'+newlogscounter[1])
            //     }
            // })
            // var timeshift = 'am';
            // $('#newlog'+newlogscounter).bootstrapSwitch('state', true);
            // $('#newlog'+newlogscounter).on('switchChange.bootstrapSwitch',function () {
            //     var check = $(this).closest('.bootstrap-switch-on')
            //     if (check.length > 0) {
            //         timeshift = 'pm';
            //     } else {
            //         timeshift = 'am';
            //     }
            // });
            var logstate = 'in';
            $('#newlogstate'+newlogscounter).bootstrapSwitch('state', true);
            $('#newlogstate'+newlogscounter).on('switchChange.bootstrapSwitch',function () {
                var check = $(this).closest('.bootstrap-switch-on')
                if (check.length > 0) {
                    logstate = 'out';
                } else {
                    logstate = 'in';
                }
            });
            $(document).on('click','.savenewtimelog'+newlogscounter, function(){
                var thisrow = $(this).closest('.row');
                var thissavebutton = $(this);
                var timelog = thisrow.find('input[name="newtimelog"]').val();
                var employeeid = $('#newlogscontainer').attr('employeeid');
                var usertypeid = $('#newlogscontainer').attr('usertypeid');
                var selecteddate =  $('#currentDate').val()
                if(timelog.replace(/^\s+|\s+$/g, "").length == 0){

                    toastr.warning('Please set a time first!','Time Logs')

                }else{
                    $.ajax({
                        url: '/hr/attendance/addtimelog',
                        type:"GET",
                        data:{
                            employeeid  : employeeid,
                            usertypeid  : usertypeid,
                            timelog     : timelog,
                            // timeshift   : timeshift,
                            tapstate    : logstate,
                            selecteddate: selecteddate
                        },
                        success:function(data) {
                            if(data == '1')
                            {
                                toastr.success('Added successfully!','Time Logs')
                                thissavebutton.attr('disabled',true)
                                thisrow.find('.clock').remove()
                                thisrow.find('.removenewtimelog').remove()
                                thisrow.find("[name='dayshift']").bootstrapSwitch('disabled',true);
                            }else{
                                toastr.danger('Something went wrong!','Time Logs')
                            }
                        }
                    });
                }
            })
            newlogscounter+=1;

            $('.removenewtimelog').on('click', function(){
                $(this).closest('.row').remove()
            })
        })
        $(document).on('click', '.deletelog', function(){
            var thisrow = $(this).closest('.row');
            var logid   = $(this).attr('id');
            Swal.fire({
                title: 'Are you sure you want to delete this log?',
                type: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Delete',
                showCancelButton: true,
                allowOutsideClick: false
            }).then((confirm) => {
                if (confirm.value) {

                    $.ajax({
                        url: '/hr/attendance/deletetimelog',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            id          :   logid
                        },
                        complete: function(data){
                            thisrow.remove()
                            toastr.success('Time log deleted successfully!','Time Logs')
                        }
                    })
                }
            })
        })
        // $(document).on('click','.updatetimeatt', function(){
        //     var thisrow = $(this).closest('.row')
        //     var employeeid = $(this).closest('.row').attr('id');
        //     var amin = thisrow.find('#timepickeramin'+employeeid).val();
        //     var amout = thisrow.find('#timepickeramout'+employeeid).val();
        //     var pmin = thisrow.find('#timepickerpmin'+employeeid).val();
        //     var pmout = thisrow.find('#timepickerpmout'+employeeid).val();
        //     var selecteddate =  $('#currentDate').val()
        //         $.ajax({
        //             url: '/hr/attendance/updatetime',
        //             type:"GET",
        //             data:{
        //                 employeeid: employeeid,
        //                 amin        :   amin,
        //                 amout       :   amout,
        //                 pmin        :   pmin,
        //                 pmout       :   pmout,
        //                 selecteddate: selecteddate
        //             },
        //             dataType: 'json',
        //             success:function(data) {
        //                 if(data == 1)
        //                 {
                            
        //                 toastr.success('Updated successfully!')
        //                 thisrow.find('button').attr('disabled', true)
        //                 }   
        //             }
        //         });
        // })
        
    })
    

  </script>
@endsection

