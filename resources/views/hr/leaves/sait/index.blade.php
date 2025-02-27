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
    }.alert-warning {
    color: #856404;
    background-color: #fff3cd;
    border-color: #ffeeba;
}
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3>
                    Leave Applications
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Leave Applications</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<section class="content-body">
    
        <div class="row">
            <div class="col-12">

                <div class="card" style="box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;">
                    <div class="card-header d-flex p-0">
                        <ul class="nav nav-pills ml-auto p-2">
                            <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Pending</a></li>
                            <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Approved</a></li>
                            <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Rejected</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                              <div class="row mb-2">
                                <div class="col-md-12">
                                  <input type="text" class="form-control" placeholder="Search name"/>
                                </div>
                              </div>
                              @if(collect($leaveapplications)->where('appstatus','0')->count()>0)
                                  @foreach(collect($leaveapplications)->where('appstatus','0')->values() as $leaveapplication)
                                      <div class="card" style="box-shadow: none !important; border: 1px solid #ddd; border-radius: 10px;">
                                          <div class="card-header">
                                            <div class="row">
                                              <div class="col-sm-6">{{$leaveapplication->lastname}}, {{$leaveapplication->firstname}}</div>
                                              <div class="col-sm-6 text-right">
                                                {{-- <h3 class="card-title text-right"> --}}
                                                    Application for <strong><u>{{$leaveapplication->leave_type}}</u></strong>
                                                {{-- </h3> --}}
                                              </div>
                                            </div>
                                          </div>                
                                          <div class="card-body pb-0" style="font-size: 13px;">
                                              <dl class="row mb-0">
                                                  <dt class="col-sm-4"> Date Applied</dt>
                                                  <dd class="col-sm-8">{{date('m/d/Y', strtotime($leaveapplication->createddatetime))}}</dd>
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
                                              <hr/>
                                              <form method="GET" action="/hr/leaves/changestatus"  style="width: 100%;">
                                                @csrf
                                              <div class="row mb-2">
                                                <div class="col-md-12">
                                                  <h5>Approval Details:</h5>                                                  
                                                </div>
                                                <div class="col-sm-4 align-self-center">
                                                  <div class="form-group clearfix m-0">
                                                    <div class="icheck-primary d-inline">
                                                    <input type="radio" id="leaveapp{{$leaveapplication->id}}1" name="leaveapp{{$leaveapplication->id}}" {{$leaveapplication->appstatus == 0 ? 'checked' : ''}} value="0">
                                                    <label for="leaveapp{{$leaveapplication->id}}1">
                                                      Pending
                                                    </label>
                                                    </div>
                                                  <div class="icheck-primary d-inline">
                                                  <input type="radio" id="leaveapp{{$leaveapplication->id}}2" name="leaveapp{{$leaveapplication->id}}"  {{$leaveapplication->appstatus == 1 ? 'checked' : ''}} value="1">
                                                  <label for="leaveapp{{$leaveapplication->id}}2">
                                                    Approve
                                                  </label>
                                                  </div>
                                                  <div class="icheck-primary d-inline">
                                                  <input type="radio" id="leaveapp{{$leaveapplication->id}}3" name="leaveapp{{$leaveapplication->id}}" {{$leaveapplication->appstatus == 2 ? 'checked' : ''}} value="2">
                                                  <label for="leaveapp{{$leaveapplication->id}}3">
                                                    Reject
                                                  </label>
                                                  </div>
                                                  </div>
                                                  
                                                </div>
                                                <div class="col-sm-6 align-self-center">
                                                    <input type="text" class="form-control form-control-sm" placeholder="Reason for disapproval" name="reasonfordisapproval"/>
                                                </div>
                                                <div class="col-sm-2 align-self-center">
                                                  <input type="hidden" name="leaveapplicationid" value="{{$leaveapplication->id}}"/>
                                                    <button type="submit" class="btn btn-sm btn-outline-success btn-block each-">Apply Changes</button>
                                                </div>
                                              </div>
                                            </form>
                                            @if(count($leaveapplication->approvals)>0)
                                            <div class="row">
                                              
                                              @foreach(collect($leaveapplication->approvals)->where('userid','!=', auth()->user()->id)->values() as $eachapproval)
                                              @if($eachapproval->appstatus == 2)
                                                <dt class="col-sm-3 align-self-center">
                                                  {{$eachapproval->signatorylabel}}
                                                </dt>
                                                <dd class="col-sm-3 align-self-center">
                                                  {{$eachapproval->signatoryname}}
                                                </dd>
                                                <dd class="col-sm-1 align-self-center">
                                                  {{$eachapproval->appstatusdesc}}
                                                </dd>
                                                <dd class="col-sm-3 align-self-center">
                                                  Reason for disapproval : {{$eachapproval->remarks}}
                                                </dd>
                                                <dd class="col-sm-2 align-self-center text-right">
                                                  {{$eachapproval->appstatusdate}}
                                                </dd>
                                                @else
                                                <dt class="col-sm-4 align-self-center">
                                                  {{$eachapproval->signatorylabel}}
                                                </dt>
                                                <dd class="col-sm-3 align-self-center">
                                                  {{$eachapproval->signatoryname}}
                                                </dd>
                                                <dd class="col-sm-2 align-self-center">
                                                  {{$eachapproval->appstatusdesc}}
                                                </dd>
                                                <dd class="col-sm-3 align-self-center text-right">
                                                  {{$eachapproval->appstatusdate}}
                                                </dd>
                                                @endif
                                              @endforeach
                                            </div>
                                            @endif
                                          </div>     
                                      </div>
                                  @endforeach
                                  @else                              
                                    <div class="alert alert-warning" role="alert">
                                      No <strong>Pending</strong> applications.
                                    </div>
                              @endif
                            </div>

                            <div class="tab-pane" id="tab_2">
                              <div class="row mb-2">
                                <div class="col-md-12">
                                  <input type="text" class="form-control" placeholder="Search name"/>
                                </div>
                              </div>
                              @if(collect($leaveapplications)->where('appstatus','1')->count()>0)
                                  @foreach(collect($leaveapplications)->where('appstatus','1')->values() as $leaveapplication)
                                  <div class="card collapsed-card" style="box-shadow: none !important; border: 1px solid #ddd !important; border-radius: 10px;">
                                    <div class="card-header">
                                      <h3 class="card-title">{{$leaveapplication->lastname}}, {{$leaveapplication->firstname}}<br/><small class="text-muted">Application for <strong><u>{{$leaveapplication->leave_type}}</u></strong></small></h3>
                                      <div class="card-tools text-secondary mt-2">
                                        <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-plus"></i> Show Details
                                        </button>
                                      </div>
                                    </div>
                                    
                                    
                                    <div class="card-body" style="font-size: 13px;">
                                      <dl class="row mb-0">
                                          <dt class="col-sm-4"> Date Applied</dt>
                                          <dd class="col-sm-8">{{date('m/d/Y', strtotime($leaveapplication->createddatetime))}}</dd>
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
                                      <hr/>
                                      <div class="row mb-2">
                                        <div class="col-md-12">
                                          <h5>Approval Details:</h5>                                                  
                                        </div>
                                        @if(count($leaveapplication->approvals)>0)
                                          @foreach($leaveapplication->approvals as $eachapproval)
                                          @if($eachapproval->appstatus == 2)
                                            <dt class="col-sm-3 align-self-center">
                                              {{$eachapproval->signatorylabel}}
                                            </dt>
                                            <dd class="col-sm-3 align-self-center">
                                              {{$eachapproval->signatoryname}}
                                            </dd>
                                            <dd class="col-sm-1 align-self-center">
                                              {{$eachapproval->appstatusdesc}}
                                            </dd>
                                            <dd class="col-sm-3 align-self-center">
                                              Reason for disapproval : {{$eachapproval->remarks}}
                                            </dd>
                                            <dd class="col-sm-2 align-self-center">
                                              {{$eachapproval->appstatusdate}}
                                            </dd>
                                            @else
                                            <dt class="col-sm-4 align-self-center">
                                              {{$eachapproval->signatorylabel}}
                                            </dt>
                                            <dd class="col-sm-3 align-self-center">
                                              {{$eachapproval->signatoryname}}
                                            </dd>
                                            <dd class="col-sm-2 align-self-center">
                                              {{$eachapproval->appstatusdesc}}
                                            </dd>
                                            <dd class="col-sm-3 align-self-center text-right">
                                              {{$eachapproval->appstatusdate}}
                                            </dd>
                                            @endif
                                          @endforeach
                                        @endif
                                        {{-- <div class="col-sm-4 align-self-center">
                                          {{$leaveapplication->appstatusdesc}}
                                        </div>
                                        <div class="col-sm-6 align-self-center">
                                          Reason for disapproval : {{$leaveapplication->remarks}}
                                        </div>
                                        <div class="col-sm-2 align-self-center">
                                          {{date('m/d/Y', strtotime($leaveapplication->createddatetime))}}
                                        </div> --}}
                                      </div>
                                    </div>
                                    
                                  </div>
                                  @endforeach
                              @else                     
                              <div class="alert alert-warning" role="alert">
                                No <strong>Approved</strong> applications.
                              </div>
                              @endif
                            </div>

                            <div class="tab-pane" id="tab_3">
                              <div class="row mb-2">
                                <div class="col-md-12">
                                  <input type="text" class="form-control" placeholder="Search name"/>
                                </div>
                              </div>
                              @if(collect($leaveapplications)->where('appstatus','2')->count()>0)
                                  @foreach(collect($leaveapplications)->where('appstatus','2')->values() as $leaveapplication)
                                  <div class="card collapsed-card" style="box-shadow: none !important; border: 1px solid #ddd !important; border-radius: 10px;">
                                    <div class="card-header">
                                      <h3 class="card-title">{{$leaveapplication->lastname}}, {{$leaveapplication->firstname}}<br/><small class="text-muted">Application for <strong><u>{{$leaveapplication->leave_type}}</u></strong></small></h3>
                                      <div class="card-tools text-secondary mt-2">
                                        <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-plus"></i> Show Details
                                        </button>
                                      </div>
                                    </div>
                                    
                                    
                                    <div class="card-body" style="font-size: 13px;">
                                      <dl class="row mb-0">
                                          <dt class="col-sm-4"> Date Applied</dt>
                                          <dd class="col-sm-8">{{date('m/d/Y', strtotime($leaveapplication->createddatetime))}}</dd>
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
                                      <hr/>
                                      <div class="row mb-2">
                                        <div class="col-md-12">
                                          <h5>Approval Details:</h5>                                                  
                                        </div>
                                        @if(count($leaveapplication->approvals)>0)
                                          @foreach($leaveapplication->approvals as $eachapproval)
                                          @if($eachapproval->appstatus == 2)
                                            <dt class="col-sm-3 align-self-center">
                                              {{$eachapproval->signatorylabel}}
                                            </dt>
                                            <dd class="col-sm-3 align-self-center">
                                              {{$eachapproval->signatoryname}}
                                            </dd>
                                            <dd class="col-sm-1 align-self-center">
                                              {{$eachapproval->appstatusdesc}}
                                            </dd>
                                            <dd class="col-sm-3 align-self-center">
                                              Reason for disapproval : {{$eachapproval->remarks}}
                                            </dd>
                                            <dd class="col-sm-2 align-self-center">
                                              {{$eachapproval->appstatusdate}}
                                            </dd>
                                            @else
                                            <dt class="col-sm-4 align-self-center">
                                              {{$eachapproval->signatorylabel}}
                                            </dt>
                                            <dd class="col-sm-3 align-self-center">
                                              {{$eachapproval->signatoryname}}
                                            </dd>
                                            <dd class="col-sm-2 align-self-center">
                                              {{$eachapproval->appstatusdesc}}
                                            </dd>
                                            <dd class="col-sm-3 align-self-center text-right">
                                              {{$eachapproval->appstatusdate}}
                                            </dd>
                                            @endif
                                          @endforeach
                                        @endif
                                        {{-- <div class="col-sm-4 align-self-center">
                                          {{$leaveapplication->appstatusdesc}}
                                        </div>
                                        <div class="col-sm-6 align-self-center">
                                          Reason for disapproval : {{$leaveapplication->remarks}}
                                        </div>
                                        <div class="col-sm-2 align-self-center">
                                          {{date('m/d/Y', strtotime($leaveapplication->createddatetime))}}
                                        </div> --}}
                                      </div>
                                    </div>
                                    
                                  </div>
                                  
                                  @endforeach
                              @else                     
                              <div class="alert alert-warning" role="alert">
                                No <strong>Rejected</strong> applications.
                              </div>
                              @endif
                            </div>

                        </div>

                    </div>
                </div>

            </div>

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