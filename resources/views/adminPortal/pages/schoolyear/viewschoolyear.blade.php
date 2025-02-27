
@extends('adminPortal.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@endsection

@section('modalSection')

<div class="modal fade" id="schoolyearform" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">School Year Form</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="/adminupdatesy" method="GET" id="schoolyearform">
            <div class="modal-body">
                <div class="row">
                    <input  class="form-control" name="si" hidden value="{{Crypt::encrypt($syinfo[0]->id)}}">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input  class="form-control" name="sdate"  id="sdate"   type="text"  value="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>End Date</label>
                            <input  class="form-control" id="edate" name="edate" type="text" value="{{\Carbon\Carbon::now()->isoFormat('MM/DD/YYYY')}}" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="submit" class="btn btn-primary savebutton">Save</button>
            </div>
        </form>
      </div>
    </div>
</div>


@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
       
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item"><a href="/manageschoolyear">School Year</a></li>
            <li class="breadcrumb-item active">Room</li>
        </ol>
        </div>
    </div>
    </div>
</section>
<section class="content pt-0">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-info">
                        <h6 class="card-title">School Year Activation</h6>
                    </div>
                    <div class="card-body">
                      
                        @if(!$isNextSchoolYear)

                            @if($isFirstYear)

                                <p class="text-danger">Request is not required to activate this school year.</p>
                            
                            @elseif($no_sy_request)

                                <p class="text-danger">Permission request is required to activate this school year.</p>
                                <p><a href="/requestpermission/{{Crypt::encrypt($syinfo[0]->id)}}"> Click here</a> to send request</p>
                            
                            @else

                                @if($syinfo[0]->isactive != 1)
                                
                                    @if($latestRequest->reqid != $syinfo[0]->id)
                        
                                        @if( ( $latestRequest->status == 0 ||  $latestRequest->reqid != App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->id ) && ( $latestRequest->status =! 3 ||  $latestRequest->status =! 1))

                                            <p>You have pending request <a href="/viewschoolyearinformation/{{Crypt::encrypt($latestRequest->reqid)}}">click here</a> to view request</p>

                                        @else

                                            <p class="text-danger">Permission request is required to activate this school year.</p>
                                            <p><a href="/requestpermission/{{Crypt::encrypt($syinfo[0]->id)}}">Click here</a> to send request</p>

                                        @endif
                                    @else

                                        @if($latestRequest->reqid == $syinfo[0]->id && $latestRequest->status != 3)
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="timeline">
                                                            <div>
                                                                <i class="fas fa-paper-plane bg-blue"></i>
                                                                <div class="timeline-item">
                                                                    <h3 class="timeline-header text-primary">Request Submitted</h3>
                                                                    <div class="timeline-body">
                                                                        Your request to change school {{App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->sydesc}} to {{$syinfo[0]->sydesc}} has been submitted to:
                                                                        <table class="table table-sm mt-3 mb-0">
                                                                            <tr>
                                                                                <th width="70%">Name</th>
                                                                                <th width="30%">Status</th>
                                                                            </tr>
                                                                            @foreach($latestRequestDetails as $item)
                                                                                <tr>
                                                                                    <td>{{$item->name}}</td>
                                                                                    <td class="align-middle">
                                                                                        @if($item->response == 0)
                                                                                        <span class="badge badge-warning d-block">Waiting for response</span>
                                                                                        @elseif($item->response == 1)
                                                                                            <span class="badge badge-success d-block">Approved</span>
                                                                                        @elseif($item->response == 2)
                                                                                            <span class="badge badge-danger d-block">Disapproved</span>
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </table>
                                                                    </div>
                                                                    @if($latestRequest->status != 1)
                                                                        <div class="timeline-footer">
                                                                            <a href="/cancelrequest/{{Crypt::encrypt($latestRequest->id)}}" class="btn btn-danger btn-sm">Cancel request</a>
                                                                        </div>
                                                                    @endif
                                                                    </div>
                                                                </div>
                                                                @if($latestRequest->status == 1)
                                                                    <div>
                                                                        <i class="fas fa-thumbs-up bg-green"></i>
                                                                        <div class="timeline-item">
                                                                        <h3 class="timeline-header no-border text-success">Request Approved</h3>
                                                                        <div class="timeline-body">
                                                                            Your request to activate/change school year {{App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->sydesc}} to {{$syinfo[0]->sydesc}} has been approved.
                                                                        </div>
                                                                        <div class="timeline-footer">
                                                                            <a href="/setschoolyearactive/{{Crypt::encrypt($syinfo[0]->id)}}" class="btn btn-primary btn-sm">Activate School year</a>
                                                                            <a href="/cancelrequest/{{Crypt::encrypt($latestRequest->id)}}" class="btn btn-danger btn-sm float-right">Cancel request</a>
                                                                        </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-danger">Permission request is required to activate this school year.</p>
                                            <p><a href="/requestpermission/{{Crypt::encrypt($syinfo[0]->id)}}">Click here</a> to send request</p>
                                        @endif
                                    @endif
                                


                                @else
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="timeline">
                                                    <div>
                                                        <i class="fas fa-paper-plane bg-blue"></i>
                                                        <div class="timeline-item">
                                                            <h3 class="timeline-header text-primary">Permission Submitted</h3>
                                                                <div class="timeline-body">
                                                                    Your request to change school {{App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->sydesc}} to {{$syinfo[0]->sydesc}} has been submitted to:
                                                                    <table class="table table-sm mt-3 mb-0">
                                                                        <tr>
                                                                            <th width="70%">Name</th>
                                                                            <th width="30%">Status</th>
                                                                        </tr>
                                                                        @foreach($requestDetails as $item)
                                                                            <tr>
                                                                                <td>{{$item->name}}</td>
                                                                                <td class="align-middle">
                                                                                    @if($item->response == 0)
                                                                                        <span class="badge badge-warning d-block">Waiting for response</span>
                                                                                    @elseif($item->response == 1)
                                                                                        <span class="badge badge-success d-block">Approved</span>
                                                                                    @elseif($item->response == 2)
                                                                                        <span class="badge badge-danger d-block">Disapproved</span>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <i class="fas fa-thumbs-up bg-green"></i>
                                                            <div class="timeline-item">
                                                            <h3 class="timeline-header no-border text-success">Request Approved</h3>
                                                            <div class="timeline-body">
                                                                Your request to activate/change school year {{App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->sydesc}} to {{$syinfo[0]->sydesc}} has been approved.
                                                            </div>
                                                            <div class="timeline-footer">
                                                                <a  class="btn btn-primary btn-sm">School Year Activated</a>
                                                            </div>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @else
                            @if($gradesschoolispromoted && $juniorsispromoted && $seniorsispromoted)

                                @if($isFirstYear)

                                <p class="text-danger">Request is not required to activate this school year.</p>
                            
                            @elseif($no_sy_request)

                                <p class="text-danger">Permission request is required to activate this school year.</p>
                                <p><a href="/requestpermission/{{Crypt::encrypt($syinfo[0]->id)}}"> Click here</a> to send request</p>
                            
                            @else

                                @if($syinfo[0]->isactive != 1)
                                
                                    @if($latestRequest->reqid != $syinfo[0]->id)
                        
                                        @if( ( $latestRequest->status == 0 ||  $latestRequest->reqid != App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->id ) && ( $latestRequest->status =! 3 ||  $latestRequest->status =! 1))

                                            <p>You have pending request <a href="/viewschoolyearinformation/{{Crypt::encrypt($latestRequest->reqid)}}">click here</a> to view request</p>

                                        @else

                                            <p class="text-danger">Permission request is required to activate this school year.</p>
                                            <p><a href="/requestpermission/{{Crypt::encrypt($syinfo[0]->id)}}">Click here</a> to send request</p>

                                        @endif
                                    @else

                                        @if($latestRequest->reqid == $syinfo[0]->id && $latestRequest->status != 3)
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="timeline">
                                                            <div>
                                                                <i class="fas fa-paper-plane bg-blue"></i>
                                                                <div class="timeline-item">
                                                                    <h3 class="timeline-header text-primary">Request Submitted</h3>
                                                                    <div class="timeline-body">
                                                                        Your request to change school {{App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->sydesc}} to {{$syinfo[0]->sydesc}} has been submitted to:
                                                                        <table class="table table-sm mt-3 mb-0">
                                                                            <tr>
                                                                                <th width="70%">Name</th>
                                                                                <th width="30%">Status</th>
                                                                            </tr>
                                                                            @foreach($latestRequestDetails as $item)
                                                                                <tr>
                                                                                    <td>{{$item->name}}</td>
                                                                                    <td class="align-middle">
                                                                                        @if($item->response == 0)
                                                                                        <span class="badge badge-warning d-block">Waiting for response</span>
                                                                                        @elseif($item->response == 1)
                                                                                            <span class="badge badge-success d-block">Approved</span>
                                                                                        @elseif($item->response == 2)
                                                                                            <span class="badge badge-danger d-block">Disapproved</span>
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </table>
                                                                    </div>
                                                                    @if($latestRequest->status != 1)
                                                                        <div class="timeline-footer">
                                                                            <a href="/cancelrequest/{{Crypt::encrypt($latestRequest->id)}}" class="btn btn-danger btn-sm">Cancel request</a>
                                                                        </div>
                                                                    @endif
                                                                    </div>
                                                                </div>
                                                                @if($latestRequest->status == 1)
                                                                    <div>
                                                                        <i class="fas fa-thumbs-up bg-green"></i>
                                                                        <div class="timeline-item">
                                                                        <h3 class="timeline-header no-border text-success">Request Approved</h3>
                                                                        <div class="timeline-body">
                                                                            Your request to activate/change school year {{App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->sydesc}} to {{$syinfo[0]->sydesc}} has been approved.
                                                                        </div>
                                                                        <div class="timeline-footer">
                                                                            <a href="/setschoolyearactive/{{Crypt::encrypt($syinfo[0]->id)}}" class="btn btn-primary btn-sm">Activate School year</a>
                                                                            <a href="/cancelrequest/{{Crypt::encrypt($latestRequest->id)}}" class="btn btn-danger btn-sm float-right">Cancel request</a>
                                                                        </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-danger">Permission request is required to activate this school year.</p>
                                            <p><a href="/requestpermission/{{Crypt::encrypt($syinfo[0]->id)}}">Click here</a> to send request</p>
                                        @endif
                                    @endif
                                


                                @else
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="timeline">
                                                    <div>
                                                        <i class="fas fa-paper-plane bg-blue"></i>
                                                        <div class="timeline-item">
                                                            <h3 class="timeline-header text-primary">Permission Submitted</h3>
                                                                <div class="timeline-body">
                                                                    Your request to change school {{App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->sydesc}} to {{$syinfo[0]->sydesc}} has been submitted to:
                                                                    <table class="table table-sm mt-3 mb-0">
                                                                        <tr>
                                                                            <th width="70%">Name</th>
                                                                            <th width="30%">Status</th>
                                                                        </tr>
                                                                        @foreach($requestDetails as $item)
                                                                            <tr>
                                                                                <td>{{$item->name}}</td>
                                                                                <td class="align-middle">
                                                                                    @if($item->response == 0)
                                                                                        <span class="badge badge-warning d-block">Waiting for response</span>
                                                                                    @elseif($item->response == 1)
                                                                                        <span class="badge badge-success d-block">Approved</span>
                                                                                    @elseif($item->response == 2)
                                                                                        <span class="badge badge-danger d-block">Disapproved</span>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <i class="fas fa-thumbs-up bg-green"></i>
                                                            <div class="timeline-item">
                                                            <h3 class="timeline-header no-border text-success">Request Approved</h3>
                                                            <div class="timeline-body">
                                                                Your request to activate/change school year {{App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->sydesc}} to {{$syinfo[0]->sydesc}} has been approved.
                                                            </div>
                                                            <div class="timeline-footer">
                                                                <a  class="btn btn-primary btn-sm">School Year Activated</a>
                                                            </div>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                                

                            @else
                                <p>Before activating this school year, please be reminded of the following requirement:</p>
                                <li>
                                    <ul>1. Grade school students should be promoted. 
                                        @if($gradesschoolispromoted)
                                            <span class="badge badge-success">Complete</span>
                                        @else
                                            <span class="badge badge-danger">Incomplete</span>
                                        @endif
                                    </ul>
                                    <ul>2. High school students should be promoted.
                                        @if($juniorsispromoted)
                                            <span class="badge badge-success">Complete</span>
                                        @else
                                            <span class="badge badge-danger">Incomplete</span>
                                        @endif

                                    </ul>
                                    <ul>3. Senior high school should be promoted.
                                        @if($seniorsispromoted)
                                            <span class="badge badge-success">Complete</span>
                                        @else
                                            <span class="badge badge-danger">Incomplete</span>
                                        @endif
                                    </ul>
                                    <ul>
                                        4. First semester should be activiated.
                                        @if($isFirstSem)
                                            <span class="badge badge-success">Complete</span>
                                        @else
                                            <span class="badge badge-danger">Incomplete</span>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                        @endif
                    </div>
                </div>
                <div>
                    <div class="card">
                        <div class="card-header bg-info">
                            <h6 class="card-title">Semester Activation</h6>
                        </div>
                        <div class="card-body">

                            @if($isActiveShoolYear)

                                @if($seniorsispromoted)
                         
                                    @if($no_sem_request)
                                        <p class="text-danger">Permission request is required to activate
                                            @if($active_semester->id == 1)
                                                2nd semester. 
                                            @else
                                                1st semester. 
                                            @endif
                                        </p>
                                        <p>
                                            @if($active_semester->id == 1)
                                                <a href="/requestsempermission/{{Crypt::encrypt(2)}}"> 
                                            @else
                                                <a href="/requestsempermission/{{Crypt::encrypt(1)}}"> 
                                            @endif
                                                Click here</a> to send request
                                        </p>
                                    @elseif( ( $active_semester->id == $lastestSemRequest->reqid && $lastestSemRequest->status == 1 ) || $lastestSemRequest->status == 3)
                                        <p class="text-danger">Permission request is required to activate
                                            @if($active_semester->id == 1)
                                                2nd semester. 
                                            @else
                                                1st semester. 
                                            @endif
                                        </p>
                                        <p>
                                            @if($active_semester->id == 1)
                                                <a href="/requestsempermission/{{Crypt::encrypt(2)}}"> 
                                            @else
                                                <a href="/requestsempermission/{{Crypt::encrypt(1)}}"> 
                                            @endif
                                                Click here</a> to send request
                                        </p>
                                    @else
                                        <div class="col-md-12">
                                                <div class="timeline">
                                                    <div>
                                                        <i class="fas fa-paper-plane bg-blue"></i>
                                                        <div class="timeline-item">
                                                            <h3 class="timeline-header text-primary">Permission Submitted</h3>
                                                                <div class="timeline-body">
                                                                    @if($active_semester->id == 1)
                                                                        Your request to change 1st Semseter to 2nd Semester has been submitted to:
                                                                    @else
                                                                        Your request to change 2nd Semseter to 1st Semester has been submitted to:
                                                                    @endif
                                                                
                                                                    <table class="table table-sm mt-3 mb-0">
                                                                        <tr>
                                                                            <th width="70%">Name</th>
                                                                            <th width="30%">Status</th>
                                                                        </tr>
                                                                        @foreach($semrequestdetails as $item)
                                                                            <tr>
                                                                                <td width="70%">{{$item->name}}</td>
                                                                                <td>
                                                                                    @if($item->response == 0)
                                                                                        <span class="badge badge-warning d-block">Waiting for response</span>
                                                                                    @elseif($item->response == 1)
                                                                                        <span class="badge badge-success d-block">Approved</span>
                                                                                    @elseif($item->response == 2)
                                                                                        <span class="badge badge-danger d-block">Disapproved</span>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </table>
                                                                </div>
                                                                <div class="timeline-footer">
                                                                    <a href="/cancelrequest/{{Crypt::encrypt($lastestSemRequest->id)}}" class="btn btn-danger btn-sm">Cancel request</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if($lastestSemRequest->status == 1)
                                                            <div>
                                                            
                                                                <i class="fas fa-thumbs-up bg-green"></i>
                                                                <div class="timeline-item">
                                                                <h3 class="timeline-header no-border text-success">Request Approved</h3>
                                                                <div class="timeline-body">
                                                                    @if($active_semester->id == 1)
                                                                        Your request to change 1st semester to 2nd semester has been approved.
                                                                    @else
                                                                        Your request to change 2nd semester to 1st semester has been approved.
                                                                    @endif
                                                                
                                                                </div>
                                                                <div class="timeline-footer">
                                                                    @if($active_semester->id == 1)
                                                                        <a href="/setsemaractive/{{Crypt::encrypt(2)}}" class="btn btn-primary btn-sm">Activate 2nd Semester</a>
                                                                    @else
                                                                        <a href="/setsemaractive/{{Crypt::encrypt(1)}}" class="btn btn-primary btn-sm">Activate 1st Semester</a>
                                                                    @endif
                                                                
                                                                </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                </div>
                                            </div>
                                    @endif
                                    
                                @else

                                    <p>Before activating next semester, please be reminded of the following requirement:</p>
                                    <li>
                                        <ul>1. Senior high school should be promoted.
                                            @if($seniorsispromoted)
                                                <span class="badge badge-success">Complete</span>
                                            @else
                                                <span class="badge badge-danger">Incomplete</span>
                                            @endif
                                        </ul>
                                    </li>

                                @endif

                            @else
                                <p>Activation of semester is only available to current school year</p>

                            @endif
                            <div class="callout callout-success mb-0 text-success">
                                Semester is only applicable to schools with senior high.
                            </div>
                               
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-primary">
                    <div class="card-header bg-info">
                      <h3 class="card-title">About School Year</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-book mr-1"></i>School Year</strong>
                        <p class="text-muted">
                            {{$syinfo[0]->sydesc}}
                        </p>
                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i>Start Date</strong>
                        <p class="text-muted">
                            {{\Carbon\Carbon::create($syinfo[0]->sdate)->isoFormat('MMM DD YYYY')}}
                        </p>
                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i>End Date</strong>
                            <p class="text-muted">
                                {{\Carbon\Carbon::create($syinfo[0]->edate)->isoFormat('MMM DD YYYY')}}
                            </p>
                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i>Status</strong>
                            <p class="text-muted">
                                @if($syinfo[0]->isactive == 1)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-info">Inactive</span>
                                @endif
                            </p>
                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i>Active Semester</strong>
                            <p class="text-muted">
                                {{$active_semester->semester}}
                            </p>
                            
                        <hr>
                        
                   
                        <span>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-block" data-toggle="modal"  data-target="#schoolyearform" ><i class="far fa-edit mr-1"></i>Edit Information</button></span>
                    </div>
                    <!-- /.card-body -->
                  </div>
            </div>

        </div>
    </div>
</section>


@endsection


@section('footerjavascript')
    <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(function() {
            $('input[name="sdate"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: moment().year(),
                startDate: moment().add(5, 'day'),
                locale: {
                    format: 'MMM DD, YYYY'
                },
                maxYear: moment().add(1, 'years'),
            }, function(start, end, label) {
                var years = moment().diff(start, 'years');
            });
            
            $('input[name="edate"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: moment().year(),
                locale: {
                    format: 'MMM DD, YYYY'
                },
                maxYear: moment().add(1, 'years'),
            }, function(start, end, label) {
                var years = moment().diff(start, 'years');
            });
        });

        $(document).ready(function(){

            $('input[name="edate"]').data('daterangepicker').setStartDate('{{\Carbon\Carbon::create($syinfo[0]->edate)->isoFormat('MMM DD YYYY')}}')
            $('input[name="edate"]').data('daterangepicker').setEndDate('{{\Carbon\Carbon::create($syinfo[0]->edate)->isoFormat('MMM DD YYYY')}}')


            $('input[name="sdate"]').data('daterangepicker').setStartDate('{{\Carbon\Carbon::create($syinfo[0]->sdate)->isoFormat('MMM DD YYYY')}}')
            $('input[name="sdate"]').data('daterangepicker').setEndDate('{{\Carbon\Carbon::create($syinfo[0]->sdate)->isoFormat('MMM DD YYYY')}}')
        })
    </script>
@endsection


