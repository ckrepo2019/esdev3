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
    {{-- <link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}"> --}}
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="{{asset('plugins/ekko-lightbox/ekko-lightbox.css')}}">
    {{-- <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}"> --}}
@endsection

@section('content')
<style>
    .thumb{
        /* margin: 10px 5px 0 0; */
        width: 100%;
    } 
    .tooltip {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        padding: 5px;
        z-index: 1;
    }
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3>
                    Leave Application
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
    <div class="row">
        <div class="col-md-3">
            <div class="info-box shadow">
                <span class="info-box-icon text-success"><i class="fa fa-share"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">Leaves Applied</span>
                <span class="info-box-number">{{collect($leavesapplied)->sum('countapplied')}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box shadow">
                <span class="info-box-icon text-warning"><i class="fa fa-clock"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">Pending</span>
                <span class="info-box-number">{{collect($leavesapplied)->where('leavestatus','0')->count()}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box shadow">
                <span class="info-box-icon text-success"><i class="fa fa-check"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">Approved</span>
                <span class="info-box-number">{{collect($leavesapplied)->where('leavestatus','1')->count()}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box shadow">
                <span class="info-box-icon text-danger"><i class="fa fa-times"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">Disapproved</span>
                <span class="info-box-number">{{collect($leavesapplied)->where('leavestatus','2')->count()}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
    </div>
    @if(count($leavetypes)>0)
        <div class="row mb-2">
            <div class="col-md-3">
                <button type="button" class="btn btn-primary" id="btn-modal-fileleave" data-toggle="modal" data-target="#modal-showapplyleave"><i class="fa fa-plus"></i> Apply leave</button>
            </div>
            <div class="col-md-9 text-right">
            </div>
        </div>
        @php
            $ltypes = collect($leavetypes)->toArray();
            $ltypes = array_chunk($ltypes,3);
            $countleavetypes = count($ltypes);
        @endphp
        <div class="row">
            <div class="col-md-12">
                <label for="">Years of Service : <span>{{$yearofservice}}</span></label>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12">
                <table class="table" style="font-size: 13px; table-layout: fixed;">
                    @foreach($ltypes as $ltype)
                        <tr>
                            @foreach ($ltype as $type)
                                @if ($type->applicable == 1)
                                    <td class="p-0">{{ucwords(strtolower($type->leave_type))}}:</td>
                                    <td class="p-0" style="width: 10%;"><span class="badge badge-warning right" style="font-size: 12px;">{{$type->countapplied}}/{{$type->days}}</span></td>
                                @else
                                    {{-- <td class="p-0"><a href="javascript:void(0)"><s class="text-danger leavedetails" id="leavedetails{{$type->id}}" leaveid="{{$type->id}}">{{ucwords(strtolower($type->leave_type))}}:</s></a></td>
                                    <td class="p-0" style="width: 10%;"><span class="badge badge-warning right" style="font-size: 12px;">{{$type->countapplied}}/{{$type->days}}</span></td> --}}
                                    <td class="p-0">
                                        <a href="javascript:void(0)" class="leavedetails" id="leavedetails{{$type->id}}" leaveid="{{$type->id}}">
                                            <s class="text-danger">
                                                {{ucwords(strtolower($type->leave_type))}}:
                                            </s>
                                        </a>
                                    </td>
                                    
                                    <td class="p-0" style="width: 10%;">
                                        <span class="badge badge-warning right" style="font-size: 12px;">{{$type->countapplied}}/{{$type->days}}</span>
                                    </td>
                                @endif
                            @endforeach
                            @if(count($ltype)<3)
                                @for($x = $countleavetypes; $x < 3; $x++)
                                    <td class="p-0">&nbsp;</td>
                                    <td class="p-0" style="width: 10%;">&nbsp;</td>
                                @endfor
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif
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
    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
    <div class="row mb-2">
        <div class="col-md-12">
            <div style="width: 30px; background-color: #d5f5de; display: inline; padding: 3px  10px; border: 1px solid black;">&nbsp;</div>&nbsp; - &nbsp;Allowed application dates
        </div>
    </div>   
    @endif         
    <div class="row">
        <div class="col-md-12">
            <!-- The time line -->
            <div class="timeline">
                @if(count($leavesapplied) > 0)    
                    @foreach($leavesapplied as $leaveapp)
                        @if(count($leaveapp->dates) > 0)
                            <!-- timeline time label -->
                            <div class="time-label">
                                <span class="border">{{date('M d, Y', strtotime($leaveapp->createddatetime))}}</span>
                                <span class="border">{{date('D', strtotime($leaveapp->createddatetime))}}</span>
                                @if($leaveapp->canbedeleted == 0)
                                <span class="border text-danger btn-deleteapp"  id="{{$leaveapp->id}}" style="cursor: pointer;"><i class="fa fa-trash-alt"></i> Delete</span>
                                @endif
                            </div>
                            <!-- /.timeline-label -->
                            <!-- timeline item -->
                            <div>
                                <i class="fas fa-file @if($leaveapp->leavestatus == 0) bg-warning @elseif($leaveapp->leavestatus == 1) bg-success @elseif($leaveapp->leavestatus == 2) bg-danger @endif"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> {{date('h:i A', strtotime($leaveapp->createddatetime))}}</span>
                                    <h3 class="timeline-header">Reasons/Purpose: {{$leaveapp->leavetype}}</h3>
                
                                    <div class="timeline-body">
                                        <textarea class="form-control form-control-sm editremarks" readonly="true" @if($leaveapp->canbedeleted == 0) ondblclick="this.readOnly='';" @endif data-id="{{$leaveapp->id}}">{{$leaveapp->remarks}}</textarea>
                                        {{-- <input class="form-control form-control-sm editremarks" value="{{$leaveapp->remarks}}" readonly="true" @if(collect($leaveapp->dates)->where('canbedeleted','0')->count() == 0) ondblclick="this.readOnly='';" @endif data-id="{{$leaveapp->id}}"/> --}}
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class="fa fa-paperclip  @if($leaveapp->leavestatus == 0) bg-warning @elseif($leaveapp->leavestatus == 1) bg-success @elseif($leaveapp->leavestatus == 2) bg-danger @endif"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">Attachments</h3>
                                    <div class="timeline-body">
                                        @if($leaveapp->leavestatus == 0)
                                            <form method="POST" action="/leaves/apply/uploadfiles"  enctype="multipart/form-data">
                                                @csrf
                                                <div class="row mb-2" id="preview-files{{$leaveapp->id}}"></div>
                                                <div class="row mb-2">
                                                    <div class="col-md-10 m-0">  
                                                        <input id="file-input{{$leaveapp->id}}" type="file" multiple accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,text/plain, application/pdf, image/*" name="attachments[]" class="form-control add-files" data-employeeleaveid="{{$leaveapp->id}}"/>
                                                    </div>
                                                    <div class="col-md-2 m-0">
                                                        <button type="submit" class="btn btn-success btn-block" id="btn-upload-files{{$leaveapp->id}}">
                                                            <i class="fa fa-share"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <script>
                                                    $('#btn-upload-files{{$leaveapp->id}}').hide()
                                                    var input_file = document.getElementById('file-input{{$leaveapp->id}}');
                                                    var remove_products_ids = [];
                                                    var product_dynamic_id = 0;
                                        
                                                    $("#file-input{{$leaveapp->id}}").change(function (event) {
                                                        var len = input_file.files.length;
                                                        if(len == 0)
                                                        {
                                                            $('#btn-upload-files{{$leaveapp->id}}').hide()
                                                        }else{
                                                            $('#btn-upload-files{{$leaveapp->id}}').show()
                                                        }
                                                        $('#preview-files{{$leaveapp->id}}').empty()
                                                        
                                                        for(var j=0; j<len; j++) {
                                                            var src = "";
                                                            var name = event.target.files[j].name;
                                                            var mime_type = event.target.files[j].type.split("/");
                                                            if(mime_type[0] == "image") {
                                                            src = URL.createObjectURL(event.target.files[j]);
                                                            } else if(mime_type[0] == "video") {
                                                            src = 'icons/video.png';
                                                            } else {
                                                            src = 'icons/file.png';
                                                            }
                                                            $('#preview-files{{$leaveapp->id}}').append("<div class='col-md-2'><img id='" + product_dynamic_id + "' src='"+src+"' title='"+name+"' width='100%'></div>");
                                                            product_dynamic_id++;
                                                        }
                                                    });        
                                                </script>
                                                <input type="hidden" value="{{$id}}" name="employeeid"/>
                                                <input type="hidden" value="{{$leaveapp->id}}" name="employeeleaveid"/>
                                            </form>
                                        @endif
                                        @if(count($leaveapp->attachments)>0)
                                            <div class="row">
                                                @foreach($leaveapp->attachments as $attachment)
                                                    {{-- @if(strtolower($attachment->extension) == 'png' || strtolower($attachment->extension) == 'jpg' || strtolower($attachment->extension) == 'jpeg') --}}
                                                        <div class="col-sm-2">
                                                            <script>
                                                                function UrlExists(url) 
                                                                {
                                                                var http = new XMLHttpRequest();
                                                                http.open('HEAD', url, false);
                                                                http.send();
                                                                return http.status!=404;
                                                                }
                                                                
                                                                document.onreadystatechange = function() 
                                                                {
                                                                    if (UrlExists('{{asset($attachment->picurl)}}')) {
                                                                        @php
                                                                        $anchorhref=asset($attachment->picurl); 
                                                                        @endphp
                                                                    }
                                                                    else {
                                                                        @php
                                                                        $anchorhref=asset('assets/images/error-404-page-file-found.jpg'); 
                                                                        @endphp
                                                                    }

                                                                    @php
                                                                        $attachment->althref = $anchorhref;
                                                                    @endphp
                                                                    
                                                                    return false;
                                                                }
                                                            </script>
                                                            <a href="{{asset($attachment->picurl)}}" target="_blank" >
                                                                <img src="{{asset($attachment->picurl)}}" class="img-fluid mb-2" alt="{{$attachment->filename}}" onerror="this.onerror = null, this.src='{{asset('assets/images/error-404-page-file-found.jpg')}}'"/>
                                                            </a>
                                                            @if($leaveapp->leavestatus == 0)
                                                            <button type="button" class="btn btn-sm btn-danger p-0 btn-deleteattch" data-id="{{$attachment->id}}" style="font-size: 11px; width: 28%; float: left;" data-toggle="tooltip" data-placement="bottom" title="Delete">
                                                                <i class="fa fa-trash"></i> 
                                                            </button>
                                                            @endif
                                                            <a href="{{$attachment->althref}}" class="btn btn-sm btn-success p-0" download style="font-size: 11px; width: 68%; float: right;" data-toggle="tooltip" data-placement="bottom" title="Download"><i class="fa fa-download"></i></a>
                                                        </div>
                                                @endforeach
                                            </div>
                                        @else                                        
                                            <div class="row">
                                                <div class="col-md-12"><label>No files attached!</label></div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class="fas fa-calendar  @if($leaveapp->leavestatus == 0) bg-warning @elseif($leaveapp->leavestatus == 1) bg-success @elseif($leaveapp->leavestatus == 2) bg-danger @endif"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> {{date('h:i A', strtotime($leaveapp->createddatetime))}}</span>
                                    <h3 class="timeline-header">Dates Covered:</h3>
                
                                    <div class="timeline-body pt-0">
                                        <table class="table">
                                            @foreach($leaveapp->dates as $ldate)
                                                <tr>
                                                    <td class="p-1" style="width: 10%;">@if($ldate->dayshift == 0)<span class="badge badge-info">Whole day</span>@elseif($ldate->dayshift == 1) <span class="badge badge-info">AM</span> @elseif($ldate->dayshift == 2) <span class="badge badge-info">PM</span> @endif</td>
                                                    <td class="p-1" style="width: 20%;">{{date('D M d, Y',strtotime($ldate->ldate))}} </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class="fas fa-users  @if($leaveapp->leavestatus == 0) bg-warning @elseif($leaveapp->leavestatus == 1) bg-success @elseif($leaveapp->leavestatus == 2) bg-danger @endif"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-users"></i></span>
                                    <h3 class="timeline-header">Approvals:</h3>
                
                                    <div class="timeline-body p-0">
                                        <table class="table table-bordered" style="table-layout: fixed;">
                                            @foreach($leaveapp->approvals as $approval)
                                                    <tr>
                                                        <td class="p-1 text-left">
                                                            <small>{{ucwords(strtoupper($approval->lastname))}}, {{ucwords(strtoupper($approval->firstname))}}</small>
                                                        </td>
                                                        <td class="p-1">:<small>{{$approval->remarks}}</small></td>
                                                        <td class="p-1 text-right"><span class="badge @if($approval->appstatus == 0) badge-warning @elseif($approval->appstatus == 1) badge-success @elseif($approval->appstatus == 2) badge-danger @endif" data-toggle="tooltip" data-placement="bottom" title="@if($approval->appstatus == 0) Pending @elseif($approval->appstatus == 1) Approved @elseif($approval->appstatus == 2) Disapproved  @endif" >@if($approval->appstatus == 0) Pending @elseif($approval->appstatus == 1) Approved @elseif($approval->appstatus == 2) Disapproved @endif</span></td>
                                                    </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- END timeline item -->
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
        <!-- /.col -->
    </div>
        </div>
    </div>
    
    <div class="modal fade" id="modal-showapplyleave">
        <div class="modal-dialog @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')modal-lg @endif">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Apply Leave</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="/leaves/apply/submit" id="multiple-files-upload" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label>Leave Type</label>
                            <select class="form-control" name="leaveid" id="leavetype" required>
                                <option value="0">Select</option>
                                @foreach ($leavetypes as $leavetype)
                                    @if($leavetype->countapplied < $leavetype->days )
                                        <option value="{{$leavetype->id}}">({{$leavetype->countapplied}}/{{$leavetype->days}}) - {{$leavetype->leave_type}}</option>     
                                    @endif                   
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="container-visibility">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <label>Reasons/Purpose</label>
                                <textarea class="form-control" id="textarea-remarks" name="remarks"></textarea>
                            </div>
                        </div>
                        {{-- <div id="container-alloweddates"></div> --}}
                        <div id="container-dates"></div>
                        {{-- <div class="row mb-2">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-sm btn-info" id="btn-adddates"><i class="fa fa-plus"></i> Add dates</button>
                            </div>
                        </div>
                        <div id="div-adddates">
                            
                        </div> --}}
                        <div class="container p-0 m-0">  
                            <label>Attachments</label>
                            <input type="file" id="file-input" multiple accept="application/pdf, image/*" name="files[]" class="form-control"/>
                            <span class="text-danger">{{ $errors->first('image') }}</span>
                            <div id="thumb-output" class="row mt-2"></div>
                        </div>
                        <input type="hidden" value="{{$id}}" name="employeeids" id="empid"/>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-applyleave-submit">Submit</button>
                </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-show-sharedfolder">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title" id="modal-show-sharedfolder-title"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body" id="modal-show-sharedfolder-container">
                
            </div>
        </div>
        <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</section>
@endsection
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
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
    <script src="{{asset('plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>
    <!-- Filterizr-->
    {{-- <script src="{{asset('plugins/filterizr/jquery.filterizr.min.js')}}"></script> --}}
    <script type="text/javascript">
    
        $(document).ready(function(){
            $('#container-visibility').hide();
            $('#btn-applyleave-submit').hide();

            $('[data-toggle="tooltip"]').tooltip();

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

            @foreach($leavesapplied as $leaveapp)
                @if(count($leaveapp->dates) > 0)
                    @foreach($leaveapp->dates as $ldate)
                        @if($ldate->leavestatus == 0)
                            var backgroundcolor = '#ebc034';
                        @elseif($ldate->leavestatus == 1)
                            var backgroundcolor = '#7ad461';
                        @else
                            var backgroundcolor = 'red';
                        @endif
                        schedule.push({
                            id       : '{{$ldate->id}}',
                            title          : '{{$leaveapp->remarks}}',
                            start          : '{{$ldate->ldate}}',
                            end            : '{{$ldate->ldate}}',
                            backgroundColor: backgroundcolor,
                            borderColor    : backgroundcolor,
                            allDay         : true
                        })
                    @endforeach
                @endif
            @endforeach

            var Calendar = FullCalendar.Calendar;

            var calendarEl = document.getElementById('newcal');

            var calendar = new Calendar(calendarEl, {
                plugins: [ 'bootstrap', 'interaction', 'dayGrid'],
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

            function markers()
            {                
                @foreach($alloweddates as $alloweddate)
                    $('td[data-date={{$alloweddate}}]').css('background-color','#d5f5de')
                @endforeach
            }
            markers()
            $('.fc-prev-button').on('click', function(){
                markers()
            })
            $('.fc-next-button').on('click', function(){
                markers()
            })
            $('.fc-today-button').on('click', function(){
                markers()
            })
            $('.fc-dayGridMonth-button').on('click', function(){
                markers()
            })
            $('.fc-timeGridWeek-button').on('click', function(){
                markers()
            })
            $('.fc-timeGridDay-button').on('click', function(){
                markers()
            })
            
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox({
                alwaysShowClose: true
                });
            });
            
            $('#leavetype').on('change', function(){
                var selecttext = $(this).children("option").filter(":selected").text();
                $('#container-dates').empty()
                $('#container-alloweddates').empty()
                $('#div-adddates').empty()
                
                if($(this).val() == '0')
                {
                    $('#container-visibility').hide();
                    $('#btn-applyleave-submit').hide();
                }else{
                    
                    $('#container-visibility').show();
                    $('#btn-applyleave-submit').show();
                    $.ajax({
                        url: '/leaves/datesallowed/getinfo',
                        type: 'GET',
                        data: {
                            selecttext     :   selecttext,
                            employeeid     :   '{{$id}}',
                            leaveid         :   $(this).val()
                        },
                        success:function(data){
                            $('#container-dates').append(data)
                            // if(data.length == 0)
                            // {
                            //     $('#container-alloweddates').append(
                            //         '<div class="row mb-2">'+
                            //             '<div class="col-md-12">'+
                            //                 'No allowed dates to apply!'+
                            //             '</div>'+
                            //         '</div>'
                            //     )
                            // }else{
                            // }
                        }
                    })
                }
            })

            $("#input-filter").on("keyup", function() {
                var input = $(this).val().toUpperCase();
                var visibleCards = 0;
                var hiddenCards = 0;

                $(".container").append($("<div class='card-group card-group-filter'></div>"));


                $(".each-folder").each(function() {
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
            //Upload Files
            
            var input_file = document.getElementById('file-input');
            var remove_products_ids = [];
            var product_dynamic_id = 0;

            $("#file-input").change(function (event) {
                var file = this.files[0];
                var  fileType = file['type'];
                var validImageTypes = ['image/gif', 'image/jpeg', 'image/png','application/pdf'];
                if (!validImageTypes.includes(fileType)) {
                    toastr.warning('Invalid File Type! JPEG/PNG/PDF files only!', 'Leave Application')
                    $(this).val('')
                    $('#thumb-output').empty()
                }else{
                    var len = input_file.files.length;
                    $('#thumb-output').empty()
                    
                    for(var j=0; j<len; j++) {
                        var src = "";
                        var name = event.target.files[j].name;
                        var mime_type = event.target.files[j].type.split("/");
                        if(mime_type[0] == "image") {
                        src = URL.createObjectURL(event.target.files[j]);
                        } else if(mime_type[0] == "video") {
                        src = 'icons/video.png';
                        } else {
                        src = 'icons/file.png';
                        }
                        $('#thumb-output').append("<div class='col-md-3'><img id='" + product_dynamic_id + "' src='"+src+"' title='"+name+"' width='100%'></div>");
                        product_dynamic_id++;
                    }
                }
            });        

            $('#btn-applyleave-submit').on('click',function(){
                var leaveid = $('#leavetype').val();
                var remarks = $('#textarea-remarks').val();
                var selecteddates = [];

                $('.input-adddates').each(function(){
                    
                    if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
                    {
                        selecteddates.push($(this).val())
                    }
                })

                var checkvalidation = 0;
                if(remarks.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    checkvalidation = 1;
                    
                    $('#textarea-remarks').css('border','1px solid red');
                    toastr.warning('Please write a purpose/reason!', 'Leave Application')
                }
                if(selecteddates.length == 0)
                {
                    checkvalidation = 1;
                    toastr.warning('Please select dates!', 'Leave Application')
                }

                if(checkvalidation == 0)
                {
                    $(this).closest('form').submit()
                }
            })
            // <--- Application --->

            $('.editremarks').keypress(function (e) {
                if (e.which == 13) {
                    var empleaveid = $(this).attr('data-id');
                    var remarks = $(this).val();
                    
                    if(remarks.replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        checkvalidation = 1;                        
                        $(this).css('border','1px solid red');
                        toastr.warning('Please write a purpose/reason!', 'Leave Application')
                    }else{
                        $.ajax({
                            url: "/leaves/update/remarks",
                            type: "get",
                            data: {
                                empleaveid: empleaveid,
                                remarks   : remarks
                            },
                            complete: function (data) {
                                toastr.success('Updated successfully!')
                                $('.editremarks').attr('readonly', true);
                            }
                        });
                        return false;    //<---- Add this line
                    }
                }
            });
            //Deletion
            $('.each-date').on('click', function(){
                var ldateid = $(this).attr('id');
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
                            url: '/leaves/delete/ldate',
                            type:"GET",
                            dataType:"json",
                            data:{
                                ldateid   :  ldateid,
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
            $('.btn-deleteattch').on('click', function(){
                var attachmentid = $(this).attr('data-id');
                var thiscol = $(this).closest('div')
                Swal.fire({
                    title: 'Are you sure you want to delete this file?',
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
                            url: '/leaves/delete/file',
                            type:"GET",
                            dataType:"json",
                            data:{
                                attachmentid   :  attachmentid,
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                if(data == 1)
                                {
                                    thiscol.remove()
                                    toastr.success('Deleted successfully!')
                                }else{
                                    toastr.error('Something went wrong!')
                                }
                            }
                        })
                    }
                })
            })
            $('.btn-deleteapp').on('click', function(){
                var id = $(this).attr('id');
                Swal.fire({
                    title: 'Are you sure you want to delete this application?',
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
                            url: '/leaves/delete/application',
                            type:"GET",
                            dataType:"json",
                            data:{
                                id   :  id,
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
                    }
                })
            })
            // <--- Deletion --->

            $(document).on('mouseover', '.leavedetails', function(){
                var leaveid = $(this).attr('leaveid')
                var empid = $('#empid').val()
                
                $.ajax({
                    type: "GET",
                    url: "/leaves/apply/loadleavedetails",
                    data: {
                        leaveid : leaveid,
                        empid : empid
                    },
                    success: function (data) {

                    }
                });
            })

            // Hide tooltip on mouseleave
            $(document).on('mouseleave', '.leavedetails', function () {
                $(this).tooltip('hide');
            });

            
        })
    </script>
@endsection