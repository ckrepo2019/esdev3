@extends($extends)

@section('headerjavascript')
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}">
@endsection

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3>
                    Overtime Application
                </h3>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">My Leaves</li>
            </ol>
            </div>
        </div>
    </div>
</section>
<section class="content-body">
    
    <div class="row mb-2">
        <div class="col-md-3">
            <div class="info-box shadow">
                <span class="info-box-icon text-success"><i class="fa fa-share"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">Applied</span>
                <span class="info-box-number">{{count($overtimes)}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box shadow">
                <span class="info-box-icon text-warning"><i class="fa fa-clock"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">Pending</span>
                <span class="info-box-number">{{collect($overtimes)->where('overtimestatus','0')->count()}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box shadow">
                <span class="info-box-icon text-success"><i class="fa fa-check"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">Approved</span>
                <span class="info-box-number">{{collect($overtimes)->where('overtimestatus','1')->count()}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box shadow">
                <span class="info-box-icon text-danger"><i class="fa fa-times"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">Disapproved</span>
                <span class="info-box-number">{{collect($overtimes)->where('overtimestatus','2')->count()}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-3">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-showapplyovertime"><i class="fa fa-plus"></i> Apply Overtime</button>
        </div>
        <div class="col-md-9 text-right">
            {{-- @foreach ($leavetypes as $leavetype)
                <button type="button" class="btn btn-sm btn-default">{{$leavetype->leave_type}}: 0/0</button>
            @endforeach --}}
        </div>
    </div>
    <div class="row mb-2" id="folder-container">
        <div class="col-md-12 tschoolcalendar">
            <div class="card card-primary tschoolcalendar shadow" style="border: unset; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                <div class="card-body p-1" style="overflow: scroll;">
                    <div class="calendarHolder">
                        <div id='newcal'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            
    <div class="row">
        <div class="col-md-12">
            <!-- The time line -->
            <div class="timeline">
                
            @if(count($overtimes) > 0)    
                @foreach($overtimes as $overtime)
                    <div class="time-label">
                        <span class="bg-info">{{date('D, M d, Y', strtotime($overtime->createddatetime))}}</span>
                    </div>
                    <!-- /.timeline-label -->
                    <!-- timeline item -->
                    <div>
                        <i class="fas fa-file bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{date('h:i A', strtotime($overtime->createddatetime))}}</span>
                            <h3 class="timeline-header">Remarks: <input class="form-control form-control-sm editremarks" value="{{$overtime->remarks}}" readonly="true" @if($overtime->overtimestatus == 0) ondblclick="this.readOnly='';" @endif data-id="{{$overtime->id}}"/></h3>
        
                            <div class="timeline-body">
                                <table class="table">
                                    <tr>
                                        <td class="p-1">{{date('h:i A',strtotime($overtime->timefrom))}} - {{date('h:i A',strtotime($overtime->timeto))}}</td>
                                        @if($overtime->overtimestatus == 0)
                                            <td class="p-1 text-danger text-right each-date" style="cursor: pointer;" id="{{$overtime->id}}">Delete</td>
                                        @elseif($overtime->overtimestatus == 1)
                                            <td class="p-1 text-success text-right">Approved</td>
                                        @elseif($overtime->overtimestatus == 2)
                                            <td class="p-1 text-muted text-right">Disapproved</td>
                                        @endif
                                    </tr>
                                </table>
                            </div>
                            {{-- <div class="timeline-footer">
                                <a class="btn btn-primary btn-sm">Read more</a>
                                <a class="btn btn-danger btn-sm">Delete</a>
                            </div> --}}
                        </div>
                    </div>
                @endforeach
            @endif
            </div>
        </div>
        <!-- /.col -->
    </div>
    
    <div id="modal-showapplyovertime" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="color: black;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Overtime Application</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="text-align: none !important">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>Remarks</label>
                            <textarea class="form-control" id="textarea-remarks"></textarea>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label>Date Range</label>
                        </div>
                        <div class="col-md-3">
                            <label>Time From</label>
                        </div>
                        <div class="col-md-3">
                            <label>Time To</label>
                        </div>
                    </div>
                    <div class="row mb-2" id="div-adddates">
                        <div class="col-md-6">
                            <input type="date" class="form-control input-adddaterange p-1"/>
                        </div>
                        <div class="col-md-3">
                            <input type="time" class="form-control input-addtimefrom p-1"/>
                        </div>
                        <div class="col-md-3">
                            <input type="time" class="form-control input-addtimeto p-1"/>
                        </div>
                    </div>
                    {{-- <div class="row mb-2">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-sm btn-info" id="btn-adddates"><i class="fa fa-plus"></i> Add dates</button>
                        </div>
                        <div class="col-md-5">
                            <label>Date Range</label>
                        </div>
                        <div class="col-md-3">
                            <label>Time From</label>
                        </div>
                        <div class="col-md-3">
                            <label>Time To</label>
                        </div>
                    </div>
                    <div id="div-adddates">
                        
                    </div> --}}
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default btn-apply-close" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('footerjavascript')
    <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
    <!-- bootstrap color picker -->
    <script src="{{asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="{{asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-timegrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-interaction/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
    <script type="text/javascript">
        
        $(document).ready(function(){

            if($(window).width()<500){

                $('.fc-prev-button').addClass('btn-sm')
                $('.fc-next-button').addClass('btn-sm')
                $('.fc-today-button').addClass('btn-sm')
                $('.fc-left').css('font-size','13px')
                $('.fc-toolbar').css('margin','0')
                $('.fc-toolbar').css('padding-top','0')

                var header = {
                        left:   'title',
                        center: '',
                        right:  'today prev,next'
                }
            }
            else{
                var header = {
                    left  : 'prev,next today',
                    center: 'title',
                    right : 'dayGridMonth,timeGridWeek,timeGridDay'
                }
            }

            var date = new Date()
            var d    = date.getDate(),
            m    = date.getMonth(),
            y    = date.getFullYear()

            var schedule = [];

            @foreach($overtimes as $overtime)
                @if($overtime->overtimestatus == 0)
                    var backgroundcolor = '#ebc034';
                @elseif($overtime->overtimestatus == 1)
                    var backgroundcolor = '#7ad461';
                @else
                    var backgroundcolor = 'red';
                @endif
                schedule.push({
                    id       : '{{$overtime->id}}',
                    title          : '{{date("h:i A",strtotime($overtime->timefrom))}} - {{date("h:i A",strtotime($overtime->timeto))}}',
                    start          : '{{$overtime->datefrom}}',
                    end            : '{{$overtime->datefrom}}',
                    backgroundColor: backgroundcolor,
                    borderColor    : backgroundcolor,
                    allDay         : true
                })
            @endforeach

            var Calendar = FullCalendar.Calendar;

            var calendarEl = document.getElementById('newcal');

            var calendar = new Calendar(calendarEl, {
                plugins: [ 'bootstrap', 'interaction', 'dayGrid' ],//, 'timeGrid'
                header    : header,
                events    : schedule,
                height : 'auto',
                themeSystem: 'bootstrap',
            });

            calendar.render();
            $('.fc-right').empty()
            $('.fc-today-button').text('Today')
            $('.fc-header-toolbar').css('padding','2px')
            $('.fc-header-toolbar').find('.btn-group').addClass('btn-group-sm')
            $('.fc-header-toolbar').find('.fc-today-button').removeClass('btn')
            $('.fc-header-toolbar').find('.fc-today-button').addClass('btn-sm')
            $('.fc-header-toolbar').find('.fc-center').css('font-size','11px')

                //Application
            $('#btn-adddates').on('click', function(){
                $('#div-adddates').append(
                    '<div class="row mb-2">'+
                        '<div class="col-md-5">'+
                            '<input type="date" class="form-control input-adddaterange p-1"/>'+
                        '</div>'+
                        '<div class="col-md-3">'+
                            '<input type="time" class="form-control input-addtimefrom p-1"/>'+
                        '</div>'+
                        '<div class="col-md-3">'+
                            '<input type="time" class="form-control input-addtimeto p-1"/>'+
                        '</div>'+
                        '<div class="col-md-1">'+
                            '<button type="button" class="btn btn-default btn-removedate"><i class="fa fa-times"></i></button>'+
                        '</div>'+
                    '</div>'
                )
                // $('.input-adddaterange').daterangepicker()
            })
            $(document).on('click','.btn-removedate', function(){
                $(this).closest('.row').remove();
            })
            $('#btn-submit').on('click', function(){
                var remarks = $('#textarea-remarks').val();
                var selecteddates = [];

                var checkvalidation = 0;

                $('.input-adddaterange').each(function(){
                    
                    if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
                    {
                        obj = {
                            daterange : $(this).val(),
                            timefrom  : $(this).closest('.row').find('.input-addtimefrom').val(),
                            timeto    : $(this).closest('.row').find('.input-addtimeto').val()
                        };

                        if($(this).closest('.row').find('.input-addtimefrom').val().replace(/^\s+|\s+$/g, "").length > 0 && $(this).closest('.row').find('.input-addtimeto').val().replace(/^\s+|\s+$/g, "").length > 0)
                        {
                            selecteddates.push(obj)
                        }

                    }
                })

                if(selecteddates.length == 0)
                {
                    checkvalidation = 1;
                    toastr.warning('Please select dates!', 'Overtime Application')
                }

                if(checkvalidation == 0)
                {
                    $.ajax({
                        url: '/overtime/apply/submit',
                        type: 'GET',
                        data: {
                            employeeid          :   '{{$id}}',
                            remarks         :   remarks,
                            selecteddates   :   JSON.stringify(selecteddates)
                        },
                        complete:function(){
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                            toastr.success('Filed successfully!', 'Overtime Application')
                            $('.btn-apply-close').click();
                            window.location.reload();
                        }
                    })
                }
            })
            // <--- Application --->

            $('.editremarks').keypress(function (e) {
                if (e.which == 13) {
                    var overtimeid = $(this).attr('data-id');
                    var remarks = $(this).val();
                    $.ajax({
                        url: "/overtime/update/remarks",
                        type: "get",
                        data: {
                            overtimeid: overtimeid,
                            remarks   : remarks
                        },
                        complete: function (data) {
                            toastr.success('Updated successfully!')
                            $('.editremarks').attr('readonly', true);
                        }
                    });
                    return false;    //<---- Add this line
                }
            });
            //Deletion
            $('.each-date').on('click', function(){
                var overtimeid = $(this).attr('id');
                Swal.fire({
                    title: 'Are you sure you want to delete this?',
                    // text: "You won't be able to revert this!",
                    html: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/overtime/delete/overtime',
                            type:"GET",
                            dataType:"json",
                            data:{
                                overtimeid   :  overtimeid,
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            complete: function(){
                                toastr.success('Deleted successfully!')
                                window.location.reload();
                            }
                        })
                    }
                })
            })
            // <--- Deletion --->

        })
    </script>
@endsection