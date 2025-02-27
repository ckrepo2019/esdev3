
                    
@if(session()->has('linkid'))
    @if( session()->get('linkid') == 'custom-content-above-home')
        <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
    @else
        <div class="tab-pane fade" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
    @endif
@else
    <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
@endif
        <div id="emp_profile" class="pro-overview tab-pane fade active show">
            <div class="row">
                <div class="col-md-12 d-flex">
                    <div class="card profile-box flex-fill">
                        <div class="row">
                            <div class="col-md-6 p-4">
                                <a href="#" class="edit-icon" data-toggle="modal" data-target="#edit_profile_info">
                                    <i class="fas fa-edit" style="color: black !important"></i>
                                </a>
                                <h3 class="card-title">
                                    <strong>Personal Information</strong>
                                </h3>
                                <br>
                                <table class="table">
                                    <tr>
                                        <td class="p-1">Phone</td>
                                        <td  class="p-1">
                                            @if(count($employee_info)==0)
                                            &nbsp;
                                            @else
                                                {{$employee_info[0]->contactnum}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">Email</td>
                                        <td class="p-1">
                                            @if(count($employee_info)==0)
                                            &nbsp;
                                            @else
                                                {{$employee_info[0]->email}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">Birthday</td>
                                        <td class="p-1 text-uppercase">
                                            @if(count($employee_info)==0)
                                            &nbsp;
                                            @else
                                                {{$employee_info[0]->dobstring}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">Address</td>
                                        <td class="p-1 text-uppercase">
                                            @if(count($employee_info)==0)
                                            &nbsp;
                                            @else
                                                {{$employee_info[0]->address}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">Gender</td>
                                        <td class="p-1 text-uppercase">
                                            @if(count($employee_info)==0)
                                            &nbsp;
                                            @else
                                                {{$employee_info[0]->gender}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">Nationality</td>
                                        <td class="p-1 text-uppercase">
                                            @if(count($employee_info)==0)
                                            &nbsp;
                                            @else
                                                {{$employee_info[0]->nationality}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">Religion</td>
                                        <td class="p-1 text-uppercase">
                                            @if(count($employee_info)==0)
                                            &nbsp;
                                            @else
                                                {{$employee_info[0]->religionname}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">Marital status</td>
                                        <td class="p-1 text-uppercase">
                                            @if(count($employee_info)==0)
                                            &nbsp;
                                            @else
                                                {{$employee_info[0]->civilstatus}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">Employment of spouse</td>
                                        <td class="p-1 text-uppercase">
                                            @if(count($employee_info)==0)
                                            &nbsp;
                                            @else
                                                {{$employee_info[0]->spouseemployment}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">No. of children</td>
                                        <td class="p-1 text-uppercase">
                                            @if(count($employee_info)==0)
                                            &nbsp;
                                            @else
                                                {{$employee_info[0]->numberofchildren}}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6 p-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="#" class="edit-icon" data-toggle="modal" data-target="#edit_emergency_contact">
                                            <i class="fas fa-edit" style="color: black !important"></i>
                                        </a>
                                        <h3 class="card-title">
                                            <strong>Emergency Contact</strong>
                                        </h3>
                                        <br>
                                        <ul class="personal-info">
                                            <li>
                                                <div class="title">Name</div>
                                                <div class="text">
                                                    @if(count($employee_info)==0)
                                                    &nbsp;
                                                    @else
                                                        {{$employee_info[0]->emercontactname}}
                                                    @endif
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">Relationship</div>
                                                <div class="text">
                                                    @if(count($employee_info)==0)
                                                    &nbsp;
                                                    @else
                                                        {{$employee_info[0]->emercontactrelation}}
                                                    @endif
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">Phone </div>
                                                <div class="text">
                                                    @if(count($employee_info)==0)
                                                    &nbsp;
                                                    @else
                                                        {{$employee_info[0]->emercontactnum}}
                                                    @endif
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-12">
                                        <br>
                                        <a href="#" class="edit-icon" data-toggle="modal" data-target="#edit_designation"><i class="fas fa-edit" style="color: black !important"></i></a>
                                        <h3 class="card-title"><strong>Department & Designation</strong> </h3>
                                        <br>
                                        <ul class="personal-info">
                                            <li>
                                                <div class="title">Department</div>
                                                <div class="text">
                                                    @if(count($employee_info)==0)
                                                    &nbsp;
                                                    @else
                                                        @foreach($department as $dept)
                                                            @if($dept->id == $employee_info[0]->departmentid)
                                                                {{strtoupper($dept->department)}}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">Designation</div>
                                                <div class="text">
                                                <!-- {{$designations}} -->
                                                    @if(count($employee_info)==0)
                                                    &nbsp;
                                                    @else
                                                        @foreach($designations as $designation)
                                                            @if($designation->id == $employee_info[0]->designationid)
                                                                {{$designation->designation}}
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                {{-- <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="#" class="edit-icon" data-toggle="modal" data-target="#edit_accounts">
                                            <i class="fas fa-edit" style="color: black !important"></i>
                                        </a>
                                        <h3 class="card-title">
                                            <strong>Accounts</strong>
                                        </h3>
                                        <br>
                                        <ul class="personal-info">
                                            <li>
                                                <div class="title"></div>
                                                <div class="text">
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title"></div>
                                                <div class="text">
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 d-flex">
                    <div class="card profile-box flex-fill">
                        <div class="card-header bg-success">
                            <h3 class="card-title col-12">
                                <strong>Accounts</strong>
                                <a href="#" class="edit-icon" data-toggle="modal" data-target="#edit_accounts">
                                    <i class="fas fa-edit" style="color: black !important"></i>
                                </a>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-nowrap">
                                    <thead class="text-secondary bg-warning">
                                        <tr>
                                            <th style="width: 50%;" >Description</th>
                                            <th style="width: 50%;">Account #</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($employee_accounts) > 0)
                                            @foreach($employee_accounts as $employee_account)
                                                <tr id="{{$employee_account->id}}">
                                                    <td>
                                                        {{$employee_account->accountdescription}}
                                                    </td>
                                                    <td>
                                                        {{$employee_account->accountnum}}
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm deleteaccount" accountdescription="{{$employee_account->accountdescription}}" accountnumber="{{$employee_account->accountnum}}" ><i class="fa fa-trash text-secondary"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 d-flex">
                    @if(session()->has('messageUpdated'))
                        <div class="col-md-12">
                                <div class="alert alert-success alert-dismissible col-12">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                    {{ session()->get('messageUpdated') }}
                                </div>
                        </div>
                    @endif
                    <div class="card profile-box flex-fill">
                        <div class="card-header bg-success">
                            <h3 class="card-title col-12">
                                <strong>Family Information</strong>
                                <a href="#" class="edit-icon" data-toggle="modal" data-target="#family_info_modal">
                                    <i class="fas fa-edit" style="color: black !important"></i>
                                </a>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-nowrap">
                                    <thead class="text-secondary bg-warning">
                                        <tr>
                                            <th style="width: 50%;" >Name</th>
                                            <th>Phone</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($employee_familyinfo)==0)
                                        @else
                                            @foreach($employee_familyinfo as $family)
                                                <tr>
                                                    <td class="text-uppercase">
                                                        {{$family->famname}}
                                                        <br>
                                                        <span class="text-muted">{{$family->famrelation}}</span>
                                                    </td>
                                                    <td class="text-uppercase">{{$family->contactnum}}</td>
                                                    <td class="float-right">
                                                        <button type="button" class="btn btn-sm deletefamilymember" familyid="{{$family->id}}" familymembername="{{$family->famname}}" id="{{$profile->id}}">
                                                            <i class="fas fa-trash text-secondary"></i>
                                                        </button>
                                                        {{-- <div id="deletefamily{{$family->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"><strong>Family Info</strong></h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <center>
                                                                            Are you sure you want to <span class="text-danger"><strong>delete {{$profile->lastname}}'s {{$family->famrelation}}, {{$family->famname}}</strong></span>?
                                                                        </center>
                                                                        <br>
                                                                        <form action="/employeefamily/delete" method="get">
                                                                            <div class="submit-section">
                                                                                <input type="hidden" class="form-control" name="employeeid" value="{{$profile->id}}" required/>
                                                                                <input type="hidden" class="form-control" name="familyid" value="{{$family->id}}" required/>
                                                                                <input type="hidden" class="form-control" name="familyname" value="{{$family->famname}}" required/>
                                                                                <button type="submit" class="btn btn-danger submit-btn float-right">Delete</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 d-flex">
                    <div class="card profile-box flex-fill">
                        <div class="card-body">
                            <h3 class="card-title col-12">
                                <strong>Educational Background</strong>
                                <a href="#" class="edit-icon" data-toggle="modal" data-target="#education_info">
                                    <i class="fas fa-edit" style="color: black !important"></i>
                                </a>
                            </h3>
                            <br>
                            <br>
                            <div class="experience-box text-uppercase">
                                <ul class="experience-list">
                                    @if(count($employee_educationinfo)==0)
                                    &nbsp;
                                    @else
                                        @foreach($employee_educationinfo as $educinfo)
                                            <li>
                                                <div class="experience-user">
                                                    <div class="before-circle"></div>
                                                </div>
                                                <div class="experience-content">
                                                    <div class="timeline-content">
                                                        <a href="#/" class="name">{{$educinfo->schoolname}}</a>
                                                        <div>{{$educinfo->coursetaken}}</div>
                                                        <span class="time">{{$educinfo->schoolyear}}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex">
                    <div class="card profile-box flex-fill">
                        <div class="card-body">
                            <h3 class="card-title col-12">
                                <strong>Experience</strong>
                                <a href="#" class="edit-icon" data-toggle="modal" data-target="#experience_info">
                                    <i class="fas fa-edit" style="color: black !important"></i>
                                </a>
                            </h3>
                            <br>
                            <br>
                            <div class="experience-box  text-uppercase">
                                <ul class="experience-list">
                                    @if(count($employee_experience)==0)
                                    &nbsp;
                                    @else
                                        @foreach($employee_experience as $experience)
                                            <li>
                                                <div class="experience-user">
                                                    <div class="before-circle"></div>
                                                </div>
                                                <div class="experience-content">
                                                    <div class="timeline-content">
                                                        <a href="#" class="name">{{$experience->companyname}}</a>
                                                        <span class="float-right"><i class="fa fa-trash text-muted deleteexperience" experienceid="{{$experience->id}}" experiencecompany="{{$experience->companyname}}" experienceposition="{{$experience->position}}"></i></span>
                                                        <div>{{$experience->position}}</div>
                                                        <span class="time">{{$experience->periodfrom}} - {{$experience->periodto}}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@if(session()->has('linkid'))
    @if( session()->get('linkid') == 'custom-content-above-home')
        </div>
    @else
        </div>
    @endif
@else
    </div>
@endif