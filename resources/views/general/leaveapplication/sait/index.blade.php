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
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="{{asset('plugins/ekko-lightbox/ekko-lightbox.css')}}">
    
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<link href="{{asset('plugins/bootstrap-datepicker/1.2.0/css/datepicker.min.css')}}" rel="stylesheet">
@endsection

@section('content')
<style>
    .thumb{
        width: 100%;
    } 
    img {
        border-radius: unset;
    }
    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
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
    
    @if($departmentid == 0)
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger" role="alert">
                You are not yet assigned to a department. Please contact your HR.
            </div>
        </div>
    </div>
    @else
        <div class="row">
            <div class="col-12">

                <div class="card" style="box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;">
                    <div class="card-header d-flex p-0">
                        <ul class="nav nav-pills ml-auto p-2">
                            <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Apply Leave</a></li>
                            <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Pending</a></li>
                            <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Approved</a></li>
                            <li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab">Rejected</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <form method="POST" action="/leaves/apply/submit" id="multiple-files-upload" enctype="multipart/form-data" style="width: 100%;">
                                    
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>I hereby apply for <input type="number" required name="noofdays"/> workdays</p>
                                            @if(count($leavetypes)>0)                
                                                <div class="form-group clearfix mb-0">
                                                    <div class="row">
                                                        @foreach($leavetypes as $leavetype)
                                                            <div class="col-md-6 mb-2">
                                                                <div class="icheck-primary">
                                                                    <input type="radio" id="leavetype{{$leavetype->id}}" name="leavetype" value="{{$leavetype->id}}">
                                                                    <label for="leavetype{{$leavetype->id}}">
                                                                        {{$leavetype->leave_type}}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        <div class="col-md-6 mb-2">
                                                            <div class="icheck-primary">
                                                                <input type="radio" id="leavetype0" name="leavetype" required value="0">
                                                                <label for="leavetype0" style="width: 100%;">
                                                                    Others: <textarea class="form-control" name="otherleavedesc"></textarea>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="mb-0">for the period from <input type="date" name="datefrom" required/> to <input type="date" name="dateto" required/> (inclusive dates).</p>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-md-12">                   
                                            <label>Reason for Leave:</label>
                                            <textarea class="form-control" required name="reason"></textarea>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-md-12">    
                                            <label>Advance pay for leave period requested:</label><br/>
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" id="advancepay1" name="advancepay" value="1">
                                            <label for="advancepay1">
                                                Yes
                                            </label>
                                            </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="icheck-primary d-inline">
                                            <input type="radio" id="advancepay0" name="advancepay" value="0" checked>
                                            <label for="advancepay0">
                                                No
                                            </label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row mb-2">
                                        <div class="col-md-12">    
                                            <label>Reminder:</label>
                                            <p>Attach medical certificate in case of sick leaves of three or more days. For maternity, attach medical certificate indicating expected date of delivery.</p>
                                            <input type="file" id="file-input" accept="application/pdf, image/*" name="file" class="form-control"/>
                                            {{-- <span class="text-danger">{{ $errors->first('image') }}</span> --}}
                                            <div id="thumb-output" class="row mt-2"></div>
                                        </div>
                                    </div>
                                    <div style="row">
                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-primary btn-lg">Submit Leave Application</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="tab_2">
                                @if(count($leaveapplications)>0)
                                    @foreach($leaveapplications as $leaveapplication)
                                        <div class="card" style="box-shadow: none !important; border: 1px solid #ddd; border-radius: 10px;">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    {{$leaveapplication->leave_type}}
                                                </h3>
                                            </div>                
                                            <div class="card-body" style="font-size: 13px;">
                                                <dl class="row mb-0">
                                                    <dt class="col-sm-4"> No. of days</dt>
                                                    <dd class="col-sm-8">{{$leaveapplication->noofdays}}</dd>
                                                    {{-- <dd class="col-sm-8 offset-sm-4">Donec id elit non mi porta gravida at eget metus.</dd> --}}
                                                    <dt class="col-sm-4">Period From</dt>
                                                    <dd class="col-sm-8">{{date('m/d/Y', strtotime($leaveapplication->datefrom))}}</dd>
                                                    <dt class="col-sm-4">Period to</dt>
                                                    <dd class="col-sm-8">{{date('m/d/Y', strtotime($leaveapplication->dateto))}}
                                                    </dd>
                                                    <dt class="col-sm-4">Reason</dt>
                                                    <dd class="col-sm-8">{{$leaveapplication->reason}}
                                                    </dd>
                                                    <dt class="col-sm-4">Advance pay for leave period requested</dt>
                                                    <dd class="col-sm-8">{{$leaveapplication->advancepay == 1 ? 'Yes' : 'No'}}
                                                    </dd>
                                                    <dt class="col-sm-4">Attachment</dt>
                                                    <dd class="col-sm-8">
                                                        <a href="{{asset($leaveapplication->picurl)}}" class="btn btn-default btn-sm" download >
                                                            Download
                                                        </a>
                                                    </dd>
                                                </dl>
                                            </div> 
                                            @if(count($leaveapplication->approvals)>0)
                                                <div class="card-footer" style="font-size: 13px;">
                                                    <div class="row mb-2">
                                                        <div class="col-md-12">
                                                            <h3 class="card-title">
                                                                Approvals
                                                            </h3>
                                                        </div>
                                                    </div>
                                                    <dl class="row mb-0">
                                                    @foreach($leaveapplication->approvals as $eachapproval)
                                                    <dt class="col-sm-4"> {{$eachapproval->signatorylabel}}</dt>
                                                    <dd class="col-sm-3">{{$eachapproval->lastname}}, {{$eachapproval->firstname}}</dd>
                                                    <dd class="col-sm-2">{{$eachapproval->appstatusdesc}}</dd>
                                                    <dd class="col-sm-3">{{$eachapproval->appstatusdate}}</dd>
                                                    @endforeach
                                                    </dl>
                                                </div>    
                                            @endif       
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="tab-pane" id="tab_3">
                            {{-- Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                            when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                            It has survived not only five centuries, but also the leap into electronic typesetting,
                            remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                            sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                            like Aldus PageMaker including versions of Lorem Ipsum. --}}
                            </div>
                            <div class="tab-pane" id="tab_4">
                            {{-- Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                            when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                            It has survived not only five centuries, but also the leap into electronic typesetting,
                            remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                            sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                            like Aldus PageMaker including versions of Lorem Ipsum. --}}
                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    @endif

    
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
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js')}}"></script>
    <!-- Filterizr-->
    {{-- <script src="{{asset('plugins/filterizr/jquery.filterizr.min.js')}}"></script> --}}
    <script type="text/javascript">
    
        $(document).ready(function(){
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
                        $('#thumb-output').append("<div class='col-md-4'><img id='" + product_dynamic_id + "' src='"+src+"' title='"+name+"' width='100%'></div>");
                        product_dynamic_id++;
                    }
                }
            });        

        })
    </script>
@endsection