@extends('studentPortal.layouts.app2')


@section('content')
<style>
    .alert-danger {
        color: #842029;
        background-color: #f8d7da;
        border-color: #f5c2c7;
    }
    .alert {
        position: relative;
        padding: 1rem 1rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: .25rem;
}

</style>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4 class="m-0">Tertiary Education Subsidy</h4>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Tes Application</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="container-fluid">      
            <div class="card shadow @if(collect($applications)->where('submitted','1')->count()>0) collapsed-card @endif" style="border: none;">
                <div class="card-header">
                  <h3 class="card-title">Application</h3>
  
                  @if(collect($applications)->where('submitted','1')->count()>0)
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                  </div>
                  @endif
                  <!-- /.card-tools -->
                </div>
                <div class="card-body">
                    <form action="/student/apptes/update" method="GET">
                        <input type="text" class="form-control" name="infoid" value="{{$info->id}}" hidden>
                        <input type="text" class="form-control" name="levelid" value="{{$info->levelid}}" hidden>
                        <input type="text" class="form-control" name="courseid" value="{{$info->courseid}}" hidden>
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label>LRN</label>
                                <div class="input-group mb-3">
                                    {{-- <div class="input-group-prepend">
                                        <span class="input-group-text">LRN</span>
                                    </div> --}}
                                    <input type="text" class="form-control form-control-sm" placeholder="LRN" value="{{DB::table('studinfo')->where('userid', auth()->user()->id)->first()->lrn}}" disabled>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>SID</label>
                                <div class="input-group mb-3">
                                    {{-- <div class="input-group-prepend">
                                        <span class="input-group-text">SID</span>
                                    </div> --}}
                                    <input type="text" class="form-control form-control-sm" placeholder="Student ID" value="{{DB::table('studinfo')->where('userid', auth()->user()->id)->first()->sid}}" disabled>
                                </div>
                            </div>
                        {{-- </div>
                        <div class="row mb-2"> --}}
                            <div class="col-md-3">
                                <label>DSWD Household No.</label>
                                <div class="input-group mb-3">
                                    {{-- <div class="input-group-prepend">
                                        <span class="input-group-text">DSWD Household No.</span>
                                    </div> --}}
                                    <input type="text" class="form-control form-input form-control-sm" name="dswdhn" placeholder="DSWD Household No." value="{{$info->dswdhno}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Household Per Capita Income</label>
                                <div class="input-group mb-3">
                                    {{-- <div class="input-group-prepend">
                                        <span class="input-group-text">Household Per Capita Income</span>
                                    </div> --}}
                                    <input type="text" class="form-control form-input form-control-sm" id="householdincome" name="household-income" placeholder="Household Per Capita Income" value="{{$info->hpcincome}}" required>
                                </div>
                            </div>
                        </div>
                        {{-- <fieldset class="form-group border p-2">
                            <legend class="w-auto m-0">Student's Name</legend>
                            <div class="form-group">
                                <label class="col-form-label pt-0">Last Name</label>
                                <input type="text" class="form-control" placeholder="Enter " disabled>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label pt-0">First Name</label>
                                <input type="text" class="form-control" placeholder="Enter " disabled>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label pt-0">Middle Name</label>
                                <input type="text" class="form-control" placeholder="Enter " disabled>
                            </div>
                        </fieldset>
                        <fieldset class="form-group border p-2">
                            <legend class="w-auto m-0">Student's Data</legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label pt-0">Sex</label>
                                        <select class="form-control" disabled>
                                            <option value="male">MALE</option>
                                            <option value="female">FEMALE</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label pt-0">Birthdate</label>
                                        <input type="date" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label pt-0">Complete Program Name / Course</label>
                                        <input type="text" class="form-control" placeholder="Enter " disabled>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label pt-0">Year Level</label>
                                        <select class="form-control" disabled>
                                            <option value="0"></option>
                                            <option value="male">MALE</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </fieldset> --}}
                        <fieldset class="form-group border p-2">
                            <legend class="w-auto m-0" style="font-size: 12px; font-weight: bold;">Father's Complete Maiden Name</legend>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label pt-0">Last Name</label>
                                        <input type="text" class="form-control form-input" name="flastname" placeholder="Enter last name" value="{{$info->flastname}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label pt-0">First Name</label>
                                        <input type="text" class="form-control form-input" name="ffirstname" placeholder="Enter first name" value="{{$info->ffirstname}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label pt-0">Middle Name</label>
                                        <input type="text" class="form-control form-input" name="fmiddlename" placeholder="Enter middle name" value="{{$info->fmiddlename}}" >
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="form-group border p-2">
                            <legend class="w-auto m-0" style="font-size: 12px; font-weight: bold;">Mother's Complete Maiden Name</legend>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label pt-0">Last Name</label>
                                        <input type="text" class="form-control form-input" name="mmlastname" placeholder="Enter last name" value="{{$info->mmlastname}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label pt-0">First Name</label>
                                        <input type="text" class="form-control form-input" name="mmfirstname" placeholder="Enter first name" value="{{$info->mmfirstname}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label pt-0">Middle Name</label>
                                        <input type="text" class="form-control form-input" name="mmmiddlename" placeholder="Enter middle name" value="{{$info->mmmiddlename}}" >
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="form-group border p-2">
                            <legend class="w-auto m-0" style="font-size: 12px; font-weight: bold;">Permanent Address</legend>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label class="col-form-label pt-0">Street</label>
                                    <input type="text" class="form-control form-input" name="street" placeholder="Enter street" value="{{$info->street}}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label pt-0">Barangay</label>
                                    <input type="text" class="form-control form-input" name="barangay" placeholder="Enter barangay" value="{{$info->barangay}}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label pt-0">Town/City/Municipality</label>
                                    <input type="text" class="form-control form-input" name="city"  placeholder="Enter city" value="{{$info->city}}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label pt-0">Province</label>
                                    <input type="text" class="form-control form-input" name="province" placeholder="Enter province" value="{{$info->province}}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label pt-0">Zip Code</label>
                                    <input type="text" class="form-control form-input" name="zipcode" placeholder="Enter zip code" value="{{$info->zipcode}}" required>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="form-group border p-2">
                            <legend class="w-auto m-0" style="font-size: 12px; font-weight: bold;">Contact Details</legend>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="col-form-label pt-0">Contact No.</label>
                                    <input type="text" class="form-control form-input" name="contactno" placeholder="Enter contact no" value="{{$info->contactno}}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label pt-0">Email Address</label>
                                    <input type="email" class="form-control form-input" name="emailaddress" placeholder="Enter email address" value="{{$info->emailaddress}}" required>
                                </div>
                            </div>
                        </fieldset>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
                        <fieldset class="form-group border p-2">
                            <legend class="w-auto m-0" style="font-size: 12px; font-weight: bold;">Guardian Information</legend>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label pt-0">Guardian Name</label>
                                        <input type="text" class="form-control form-input" name="guardianname" placeholder="Enter Guardian Name" value="{{$info->guardianname}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label pt-0">Guardian Address</label>
                                        <input type="text" class="form-control form-input" name="guardianaddress" placeholder="Enter Guardian Address" value="{{$info->guardianaddress}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label pt-0">Guardian Contact No.</label>
                                        <input type="text" class="form-control form-input" name="gcontactno" placeholder="Enter Contact No." value="{{$info->gcontactno}}" >
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <label class="col-form-label pt-0">Number of Siblings</label>
                                <input type="text" class="form-control form-input" name="numofsiblings" placeholder="Enter # of siblings" value="{{$info->numofsiblings}}">
                            </div>
                        </div>
                        @endif
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <label class="col-form-label pt-0">Disability</label>
                                <input type="text" class="form-control form-input" name="disability" placeholder="Enter disability" value="{{$info->disability}}">
                            </div>
                        </div>
                        @if($info->submitted == 0)
                            <div class="row">
                                @if($recentstatus == 2)
                                    <div class="col-md-8 text-left">
                                        <div class="alert alert-danger p-2" role="alert">
                                            <strong>Your application was disapproved.<br/>Submit new application by filling in the fields above.</strong>
                                        </div>     
                                    </div>
                                @else
                                    <div class="col-md-8 text-left">
                                        <br/>
                                        @if($info->id != 0)
                                        <button type="button" id="btn-submitapptes" class="btn btn-outline-success float-left"><i class="fa fa-share"></i> Submit Application</button>
                                        @endif
                                    </div>
                                @endif
                                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'dcc')
                                <div class="col-md-4 text-right">
                                    <br/>
                                    <button type="submit" id="btn-updateapptes" class="btn btn-outline-primary float-right"><i class="fa fa-share"></i> Update Changes</button>
                                </div>
                                @endif
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-6">
                                    @if($info->appstatus == 0)
                                        <label>Status: Pending</label> 
                                    @elseif($info->appstatus == 1)
                                        <label>Status: Approved</label> 
                                    @endif
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
            @if(collect($applications)->where('submitted','1')->count()>0)
                <div class="row" id="accordion">
                    <div class="col-md-12 mb-2">                        
                        <h4 class="card-title w-100">
                            <a class="btn btn-default" data-toggle="collapse" href="#collapseTwo" style="color: inherit;">
                            Application History
                            </a>
                        </h4>
                    </div>
                    <div class="col-md-12">
                        <div id="collapseTwo" class="collapse show" data-parent="#accordion">                    
                            <!-- Timelime example  -->
                            <div class="row">
                                <div class="col-md-12">
                                <!-- The time line -->
                                    <div class="timeline">
                                        @foreach($applications as $application)
                                            <!-- timeline time label -->
                                            <div class="time-label">
                                                @if($application->appstatus == 0)
                                                    <span class="bg-warning">{{date('M d, Y', strtotime($application->createddatetime))}}</span>
                                                    <span class="bg-warning">Pending</span>
                                                @elseif($application->appstatus == 1)
                                                    <span class="bg-success">{{date('M d, Y', strtotime($application->createddatetime))}}</span>
                                                    <span class="bg-success">Approved</span>
                                                    <button type="button" class="btn btn-default btn-export-application" data-id="{{$application->id}}"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                                                @elseif($application->appstatus == 2)
                                                    <span class="bg-danger">{{date('M d, Y', strtotime($application->createddatetime))}}</span>
                                                    <span class="bg-danger">Disapproved</span>
                                                @endif
                                            </div>
                                            <!-- /.timeline-label -->
                                            <!-- timeline item -->
                                            <div>
                                                <i class="fas fa-envelope bg-blue"></i>
                                                <div class="timeline-item">
                                                    <span class="time"><i class="fas fa-clock"></i> {{date('h:i A', strtotime($application->createddatetime))}}</span>
                                                    <h3 class="timeline-header"><a href="#">Application submitted</a></h3>
                                    
                                                    <div class="timeline-body pb-0" style="font-size: 13px;">
                                                        <div class="row mb-2">
                                                            <div class="col-md-6">
                                                                <label>DSWD Household No. :</label> {{$application->dswdhno}}
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Household Per Capita Income :</label> {{$application->hpcincome}}
                                                            </div>
                                                        </div>
                                                        <fieldset class="form-group border p-2 mb-2">
                                                            <legend class="w-auto m-0" style="font-size: 12px; font-weight: bold;">Father's Complete Name</legend>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label>Last Name :</label><br/> {{$application->flastname}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>First Name :</label><br/> {{$application->ffirstname}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Middle Name :</label><br/> {{$application->fmiddlename}}
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset class="form-group border p-2 mb-2">
                                                            <legend class="w-auto m-0" style="font-size: 12px; font-weight: bold;">Mother's Complete Maiden Name</legend>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label>Last Name :</label><br/> {{$application->mmlastname}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>First Name :</label><br/> {{$application->mmfirstname}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Middle Name :</label><br/> {{$application->mmmiddlename}}
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset class="form-group border p-2 mb-2">
                                                            <legend class="w-auto m-0" style="font-size: 12px; font-weight: bold;">Permanent Address</legend>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label>Street :</label> {{$application->street}}
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <label>Barangay :</label> {{$application->barangay}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Town/City/Municipality :</label> {{$application->city}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Province :</label> {{$application->province}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Zip Code :</label> {{$application->zipcode}}
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset class="form-group border p-2 mb-2">
                                                            <legend class="w-auto m-0" style="font-size: 12px; font-weight: bold;">Contact Details</legend>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label>Contact No. :</label> {{$application->contactno}}
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Email Address :</label> {{$application->emailaddress}}
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
                                                        <fieldset class="form-group border p-2 mb-2">
                                                            <legend class="w-auto m-0" style="font-size: 12px; font-weight: bold;">Guardian Information</legend>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label>Guardian Name :</label><br/> {{$application->guardianname}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Contact No. :</label><br/> {{$application->gcontactno}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Guardian Address :</label><br/> {{$application->guardianaddress}}
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label>Number of siblings :</label> {{$application->numofsiblings}}
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label>Disability :</label> {{$application->disability}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($application->appstatus > 0)
                                                    <hr/>
                                                        <div class="timeline-footer pt-0">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label>Reason for disapproval : </label> {{$application->disapprovalreason}}
                                                                </div>
                                                                <div class="col-md-6 text-right">
                                                                    <small>Status updated last {{date('F d, Y', strtotime($application->appstatusdatetime))}}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- END timeline item -->
                                        @endforeach
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('footerscript')
<script>
        $("#household-income").on("keypress keyup blur",function (event) {
            var val = $(this).val();
            if(isNaN(val)){
                val = val.replace(/[^0-9\.]/g,'');
                if(val.split('.').length>2) 
                    val =val.replace(/\.+$/,"");
            }
            $(this).val(val); 
        });
      @if($info->submitted == 0)
        $('.form-input').prop('disabled',false)
      @else
        $('.form-input').prop('disabled',true)
      @endif
    $(document).on('click','.delete-subreq', function(){
        var id = $(this).attr('data-id');
        var thisbtn = $(this);
        Swal.fire({
            title: 'Are you sure you want to delete this submitted requirement?',
            html: 'You won\'t be able to revert this!<br/>Would you like to continue?',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Continue'
        })
        .then((result) => {
            if (result.value) {
                thisbtn.prop('disabled', true)
                $.ajax({
                    url: '/student/studentrequirementsdeletephoto',
                    type:'GET',
                    dataType: 'json',
                    data: {
                        id      :  id
                    },
                    success:function(data) {
                        if(data == 1)
                        {
                            window.location.reload()
                        }
                    }
                })
            }
        })
    })
    $('#btn-submitapptes').on('click', function(){
        Swal.fire({
            title: 'Are you sure you want to submit this application?',
            html: 'You won\'t be able to revert this!<br/>Would you like to continue?',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Continue'
        })
        .then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/student/apptes/submit',
                    type:'GET',
                    dataType: 'json',
                    data: {
                        id      :  '{{$info->id}}'
                    },
                    success:function(data) {
                        if(data == 1)
                        {
                            $('#btn-submitapptes').prop('hidden',true)
                            $('#btn-updateapptes').prop('hidden',true)
                            window.location.reload()
                        }
                    }
                })
            }
        })
    })
    $('.btn-export-application').click(function(){
        var tesid = $(this).attr('data-id');
        window.open('/student/apptes/export?tesid='+tesid,'_blank')
    })
</script>
@endsection
