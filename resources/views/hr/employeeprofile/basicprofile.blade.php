
<div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
    <div id="emp_profile" class="pro-overview tab-pane fade active show">
        <div class="card" style="border: none;">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-10">
                        <button type="button" class="btn btn-sm btn-default btn-block"><strong>Personal Information</strong></button>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-sm btn-warning btn-block" data-toggle="modal" data-target="#edit_profile_info">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <table class="table" style="font-size: 13px;">
                            <tr>
                                <td class="p-1"><label>Phone</label></td>
                                <td  class="p-1" id="profilecontactnumcontainer">
                                    {{$profileinfo->contactnum}}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1"><label>Email</label></td>
                                <td class="p-1" id="profileemailnumcontainer">
                                    {{$profileinfo->email}}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1"><label>Birthday</label></td>
                                <td class="p-1 text-uppercase" id="profiledobcontainer">
                                    {{$profileinfo->dobstring}}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1"><label>Address</label></td>
                                <td class="p-1 text-uppercase" id="profileaddresscontainer">
                                    {{$profileinfo->address}}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1"><label>Gender</label></td>
                                <td class="p-1 text-uppercase" id="profilegendercontainer">
                                    {{$profileinfo->gender}}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1"><label>Nationality</label></td>
                                <td class="p-1 text-uppercase" id="profilenationalitycontainer">
                                    {{$profileinfo->nationality}}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1"><label>Religion</label></td>
                                <td class="p-1 text-uppercase" id="profilereligioncontainer">
                                    {{$profileinfo->religionname}}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1"><label>Marital status</label></td>
                                <td class="p-1 text-uppercase" id="profilecivilstatuscontainer">
                                    {{$profileinfo->civilstatus}}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1"><label>Employment of spouse</label></td>
                                <td class="p-1 text-uppercase" id="profilespouseemploymentcontainer">
                                    {{$profileinfo->spouseemployment}}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1"><label>No. of children</label></td>
                                <td class="p-1 text-uppercase" id="profilenumofchldrencontainer">
                                    {{$profileinfo->numberofchildren}}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-10">
                        <button type="button" class="btn btn-sm btn-default btn-block"><strong>Emergency Contact</strong></button>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-sm btn-warning btn-block" data-toggle="modal" data-target="#edit_emergency_contact">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                    </div>
                    <div class="col-md-12">
                        <table class="table" style="font-size: 13px;">
                            <tbody>
                                <tr>
                                    <td style="width: 10%; font-weight: bold;">Name</td>
                                    <td style="border-bottom: 1px solid #ddd; width: 30%;">{{$profileinfo->emercontactname}}</td>
                                    <td style="width: 10%; font-weight: bold;">Relationship</td>
                                    <td style="border-bottom: 1px solid #ddd;">{{$profileinfo->emercontactrelation}}</td>
                                    <td style="width: 10%; font-weight: bold;">Phone</td>
                                    <td style="border-bottom: 1px solid #ddd;">{{$profileinfo->emercontactnum}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-10">
                        <button type="button" class="btn btn-sm btn-default btn-block"><strong>Personal Accounts</strong></button>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-sm btn-warning btn-block" data-toggle="modal" data-target="#edit_accounts">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                    </div>
                    <div class="col-md-12">
                        <table class="table" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th style="width: 50%;" >Description</th>
                                    <th style="width: 50%;">Account #</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($profileinfo->accounts) > 0)
                                    @foreach($profileinfo->accounts as $account)
                                        <tr id="{{$account->id}}">
                                            <td>
                                                {{$account->accountdescription}}
                                            </td>
                                            <td>
                                                {{$account->accountnum}}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm deleteaccount" accountdescription="{{$account->accountdescription}}" accountnumber="{{$account->accountnum}}" ><i class="fa fa-trash text-secondary"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-10">
                        <button type="button" class="btn btn-sm btn-default btn-block"><strong>Family Information</strong></button>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-sm btn-warning btn-block" data-toggle="modal" data-target="#family_info_modal">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                    </div>
                    @if(session()->has('messageUpdated'))
                        <div class="col-md-12">
                                <div class="alert alert-success alert-dismissible col-12">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                    {{ session()->get('messageUpdated') }}
                                </div>
                        </div>
                    @endif
                    <div class="col-md-12">
                        <table class="table" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th style="width: 50%;" >Name</th>
                                    <th>Phone</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($profileinfo->familyinfo)==0)
                                @else
                                    @foreach($profileinfo->familyinfo as $familyinfo)
                                        <tr>
                                            <td class="text-uppercase">
                                                {{$familyinfo->famname}}
                                                <br>
                                                <span class="text-muted">{{$familyinfo->famrelation}}</span>
                                            </td>
                                            <td class="text-uppercase">{{$familyinfo->contactnum}}</td>
                                            <td class="float-right">
                                                <button type="button" class="btn btn-sm deletefamilymember" familyid="{{$familyinfo->id}}" familymembername="{{$familyinfo->famname}}" id="{{$profileinfo->id}}">
                                                    <i class="fas fa-trash text-secondary"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-10">
                        <button type="button" class="btn btn-sm btn-default btn-block"><strong>Educational Background</strong></button>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-sm btn-success btn-block" data-toggle="modal" data-target="#profile_addeducbg">
                            <i class="fa fa-plus"></i> Add
                        </button>
                        <div id="profile_addeducbg" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-info">
                                        <h5 class="modal-title"><strong>Educational Background</strong></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-2">
                                            <div class="col-md-12">
                                                <label>School Year</label>
                                                <input type="text" class="form-control form-control-sm" id="input-educbg-sy"/>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-12">
                                                <label>University</label>
                                                <input type="text" class="form-control form-control-sm" id="input-educbg-university"/>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-12">
                                                <label>Address</label>
                                                <input type="text" class="form-control form-control-sm" id="input-educbg-address"/>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-12">
                                                <label>Course</label>
                                                <input type="text" class="form-control form-control-sm" id="input-educbg-course"/>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-12">
                                                <label>Major</label>
                                                <input type="text" class="form-control form-control-sm" id="input-educbg-major"/>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-12">
                                                <label>Awards</label>
                                                <input type="text" class="form-control form-control-sm" id="input-educbg-awards"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                      <button type="button" class="btn btn-primary" id="btn-submit-addeducbg">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-12">
                        @if(count($profileinfo->educationalbackground)>0)
                            <table class="table" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th>School Year</th>
                                        <th>University</th>
                                        <th>Address</th>
                                        <th>Course</th>
                                        <th>Major</th>
                                        <th>Awards</th>
                                    </tr>
                                </thead>
                            </table>
                        @endif
                        {{-- <div class="experience-box text-uppercase">
                            <ul class="experience-list">
                                @if(count($profileinfo->educationalbackground)==0)
                                &nbsp;
                                @else
                                    @foreach($profileinfo->educationalbackground as $educinfo)
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
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
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
                                @if(count($profileinfo->experiences)==0)
                                &nbsp;
                                @else
                                    @foreach($profileinfo->experiences as $experience)
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
            {{-- </div>
        </div> --}}
    </div>
</div>
{{-- ================================= --}}
<div id="edit_profile_info" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">Personal Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- <form action="/hr/updatepersonalinfo" method="get"> --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" name="title" id="profiletitle" value="{{$profileinfo->title}}"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" name="fname" id="profilefname" value="{{$profileinfo->firstname}}" required/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Middle Name</label>
                                        <input type="text" class="form-control" name="mname" id="profilemname" value="{{$profileinfo->middlename}}" required/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" name="lname" id="profilelname" value="{{$profileinfo->lastname}}" required/>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>Suffix</label>
                                        <input type="text" class="form-control" name="suffix" id="profilesuffix" value="{{$profileinfo->suffix}}"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Civil Status</label>
                                        <select class="select form-control text-uppercase" name="civilstatusid"  id="profilecivilstatusid" >
                                            @if($profileinfo->maritalstatusid==0)
                                            @foreach($civilstatus as $cstatus)
                                                <option value="{{$cstatus->id}}">{{$cstatus->civilstatus}}</option>
                                            @endforeach
                                            @else
                                            @foreach($civilstatus as $cstatus)
                                                <option value="{{$cstatus->id}}" {{$cstatus->id == $profileinfo->maritalstatusid ? 'selected' : ''}}>{{$cstatus->civilstatus}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Birth Date</label>
                                        <div class="cal-icon">
                                            @if($profileinfo->dob==null)
                                                <input class="form-control datetimepicker" type="date" name="dob"  id="profiledob" required/>
                                            @else
                                                <input class="form-control datetimepicker" type="date" name="dob"  id="profiledob"value="{{$profileinfo->dob}}" required/>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Gender</label>
                                        @if($profileinfo->gender == null)
                                        <select class="select form-control text-uppercase" name="gender" id="profilegender">
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                        @else
                                            <select class="select form-control text-uppercase" name="gender" id="profilegender" >
                                                <option value="male" {{"male" == strtolower($profileinfo->gender) ? 'selected' : ''}}>Male</option>
                                                <option value="female" {{"female" == strtolower($profileinfo->gender) ? 'selected' : ''}}>Female</option>
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control text-uppercase" name="address"  id="profileaddress" value="{{$profileinfo->address}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="text" class="form-control" name="email" value="{{$profileinfo->email}}" id="profileemail">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="text" class="form-control" id="contactnum" name="contactnum" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" value="{{$profileinfo->contactnum}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Employment of Spouse</label>
                                <input type="text" class="form-control text-uppercase" name="spouseemployment"  id="profilespouseemployment"value="{{$profileinfo->spouseemployment}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Number of children</label>
                                <input type="text" class="form-control" name="numofchildren"id="profilenumofchildren" value="{{$profileinfo->numberofchildren}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nationality</label>
                                <select class="select form-control text-uppercase" name="nationalityid" id="profilenationalityid">
                                    @if($profileinfo->nationalityid == null || $profileinfo->nationalityid == '0')
                                        @foreach($nationality as $nationalityeach)
                                            <option value="{{$nationalityeach->id}}" {{strtolower($nationalityeach->nationality) == 'filipino' ? 'selected' : ''}}>{{$nationalityeach->nationality}}</option>
                                        @endforeach
                                    @else
                                        @foreach($nationality as $nationalityeach)
                                            <option value="{{$nationalityeach->id}}" {{$nationalityeach->id == $profileinfo->nationalityid ? 'selected' : ''}}>{{$nationalityeach->nationality}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Religion</label>
                                <select class="select form-control text-uppercase" name="religionid"  id="profilereligionid">
                                    @if($profileinfo->religionid==null || $profileinfo->religionid==0)
                                        @foreach($religions as $religioneach)
                                            <option value="{{$religioneach->id}}">{{$religioneach->religionname}}</option>
                                        @endforeach
                                    @else
                                    @foreach($religions as $religioneach)
                                        <option value="{{$religioneach->id}}" {{$religioneach->id == $profileinfo->religionid ? 'selected' : ''}}>{{$religioneach->religionname}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date Hired</label>
                                <input type="date" name="datehired" class="form-control form-control-sm" id="profiledatehired" value="{{$profileinfo->datehired}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button type="button" class="btn btn-primary submit-btn float-right" data-dismiss="modal"  id="updatepersonalinformation">Update</button>
                    </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>
</div>

<div id="edit_emergency_contact" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title"><strong>Emergency Contact</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form {{--action="/hr/updateemergencycontact" method="get"--}}>
                    {{-- <input type="hidden" class="form-control" name="linkid" value="custom-content-above-home" /> --}}
                    <label>Name</label>
                    @if($profileinfo->emercontactname == null)
                    <input type="text" class="form-control form-control-sm" name="emergencyname" id="emergencyname" required/>
                    @else
                    <input type="text" class="form-control form-control-sm" name="emergencyname" id="emergencyname" value="{{$profileinfo->emercontactname}}" required/>
                    @endif
                    <br>
                    <label>Relationship</label>
                    @if($profileinfo->emercontactname == null)
                    <input type="text" class="form-control form-control-sm" name="relationship" id="emergencyrelationship" required/>
                    @else
                    <input type="text" class="form-control form-control-sm" name="relationship" id="emergencyrelationship" value="{{$profileinfo->emercontactrelation}}" required/>
                    @endif
                    <br>
                    <label>Contact Number</label>
                    @if($profileinfo->emercontactnum == null)
                    <input type="text" class="form-control form-control-sm" id="emergencycontactnumber" name="contactnumber" minlength="11" maxlength="13" data-inputmask-clearmaskonlostfocus="true" required/>
                    @else
                    <input type="text" class="form-control form-control-sm" id="emergencycontactnumber" name="contactnumber" value="{{$profileinfo->emercontactnum}}" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" required/>
                    @endif
                    <br>
                    <div class="submit-section">
                        <button type="button" class="btn btn-primary submit-btn float-right" data-dismiss="modal" id="updateemergencycontact">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="edit_designation" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title"><strong>Department & Designation</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form {{--action="/hr/updatedesignation" method="get"--}}></form>
                    {{-- <input type="hidden" class="form-control" name="linkid" value="custom-content-above-home" />  --}}
                    <div class="form-group text-uppercase">
                        <label>Department <span class="text-danger">*</span></label>
                        <select class="form-control text-uppercase" name="departmentid" id="departmentid" required>
                            @if($profileinfo->departmentid==0|| $profileinfo->departmentid == null)
                                <option>Select department</option>
                                @foreach($departments as $dept)
                                    <option value="{{$dept->id}}" >{{strtoupper($dept->department)}}</option>
                                @endforeach
                            @else
                                <option>Select department</option>
                                @foreach($departments as $dept)
                                    <option value="{{$dept->id}}" {{$dept->id == $profileinfo->departmentid ? 'selected' : ''}}>{{strtoupper($dept->department)}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <label>Designation <span class="text-danger">*</span></label>
                        <select class="form-control text-uppercase" name="designationid"  id="designationid" required> 
                            @if($profileinfo->designationid==0|| $profileinfo->designationid == null)
                            @else
                                @foreach($designations as $designation)
                                    <option value="{{$designation->id}}" {{$designation->id == $profileinfo->designationid ? 'selected' : ''}}>{{strtoupper($designation->utype)}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <br>
                    <div class="submit-section">
                        <button type="button" class="btn btn-primary float-right" data-dismiss="modal"  id="updatedesignationid">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 
<div id="edit_accounts" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title"><strong>Update Personal Accounts</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form {{--action="/hr/updateaccounts" method="get"--}}>
                    <div class="addrowaccounts">
                        @if(count($profileinfo->accounts) == 0)
                            <div class="row">
                                <div class="col-md-5">
                                    <label>Description <span class="text-danger">*</span></label>
                                    <input type="text" name="newaccountdescription[]" class="form-control form-control-sm" required/>
                                </div>
                                <div class="col-md-5">
                                    <label>Account # <span class="text-danger">*</span></label>
                                    <input type="text" name="newaccountnumber[]" class="form-control form-control-sm" required/>
                                </div>
                                <div class="col-md-2 text-left">
                                    <label>&nbsp;</label>
                                    <br>
                                    <button type="button" class="btn btn-danger removeaddaccountrow"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                        @else
                            @foreach($profileinfo->accounts as $employee_account)
                                <div class="row">
                                    <div class="col-md-5">
                                        <label>Description <span class="text-danger">*</span></label>
                                        <input type="text" name="oldaccountdescription[]" class="form-control form-control-sm" value="{{$employee_account->accountdescription}}" required/>
                                    </div>
                                    <div class="col-md-5">
                                        <label>Account # <span class="text-danger">*</span></label>
                                        <input type="text" name="oldaccountnumber[]" class="form-control form-control-sm" value="{{$employee_account->accountnum}}" required/>
                                    </div>
                                    <div class="col-md-2 text-left">
                                        <label>&nbsp;</label>
                                        <br>
                                        <input type="hidden" name="oldaccountid[]" class="form-control form-control-sm" value="{{$employee_account->id}}" required/>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <br>
                    <div class="submit-section">
                        <button type="button" class="btn btn-primary btn-sm float-left addrowaccountsbutton">Add Account</button>
                        <button type="submit" class="btn btn-primary btn-sm float-right" data-dismiss="modal" id="updateaccounts">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 
<div id="family_info_modal" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-LG" role="document">
        <div class="modal-content">
            <form {{--action="/hr/updatefamilyinfo" method="get"--}}>
                <div class="modal-header bg-info">
                    <h5 class="modal-title"><strong>Family Information</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="overflow: scroll;">
                        {{-- <input type="hidden" class="form-control" name="id" value="{{$profileinfo->id}}" required/> --}}
                        @if(count($profileinfo->familyinfo)==0)
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Name</th>
                                        <th>Relationship</th>
                                        <th style="width: 20%;">Contact Number</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody id="familytbody">
                                    <tr>
                                        <td class="p-0 text-uppercase"><input class="form-control text-uppercase" type="text" name="familyname[]" required/></td>
                                        <td class="p-0 text-uppercase"><input class="form-control text-uppercase" type="text" name="familyrelation[]"/></td>
                                        <td class="p-0 text-uppercase"><input class="form-control text-uppercase familycontactnum" type="text" name="familynum[]" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true"/></td>
                                        <td class="p-0 bg-danger" style="vertical-align: middle;"><button type="button" class="btn btn-sm btn-danger btn-block deleterow"><i class="fa fa-times"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Name</th>
                                        <th>Relationship</th>
                                        <th style="width: 20%;">Contact Number</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody id="familytbody">
                                    @foreach($profileinfo->familyinfo as $family)
                                        <tr>
                                            <td class="p-0"><input class="form-control" type="hidden" name="oldfamilyid[]" value="{{$family->id}}"/><input class="form-control" type="text" name="oldfamilyname[]" value="{{$family->famname}}" required/></td>
                                            <td class="p-0"><input class="form-control" type="text" name="oldfamilyrelation[]" value="{{$family->famrelation}}" /></td>
                                            <td class="p-0"><input class="form-control familycontactnum" type="text" name="oldfamilynum[]" value="{{$family->contactnum}}" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true"/></td>
                                            <td class="p-0" style="vertical-align: middle;">&nbsp;</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                    <span class="float-left ml-3">
                    <button type="button" class="btn btn-sm btn-info pr-4 pl-4 addrow"><i class="fa fa-plus"></i>&nbsp; Add More</button>
                    </span>
                    </div>
                </div>
                <br>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updatefamilyinfo" data-dismiss="modal" >Update</button>
                </div>
            </form>
        </div>
    </div>
</div> 
<div id="education_info" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form {{--action="/hr/updateeducationinfo" method="get"--}}>
                <div class="modal-header bg-info">
                    <h5 class="modal-title"><strong>Educational Background</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="overflow: scroll;height: 400px;" >
                    
                    <input type="hidden" class="form-control" name="id" value="{{$profileinfo->id}}" required/>
                    
                    <div id="educationalbackgroundcontainer"></div>
                    @if(count($profileinfo->educationalbackground) > 0)
                        @foreach($profileinfo->educationalbackground as $educationinfo)
                            <div class="card p-4">
                                <div class="row">
                                    <input type="hidden" style="border:none" name="oldeducationid[]" value="{{$educationinfo->id}}" class="form-control form-control-sm pb-0 pt-0"/>
                                    <div class="col-lg-6 mb-2 pb-0">
                                        <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                            <label class="mb-0">Institution</label>
                                            <input type="text" style="border:none" name="oldschoolname[]" value="{{$educationinfo->schoolname}}" class="form-control form-control-sm pb-0 pt-0 text-uppercase" required/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2 pb-0">
                                        <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                            <label class="mb-0">Address</label>
                                            <input type="text" style="border:none" name="oldaddress[]" value="{{$educationinfo->schooladdress}}" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 mb-2 pb-0">
                                        <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                            <label class="mb-0">Course Taken</label>
                                            <input type="text" style="border:none" name="oldcoursetaken[]" value="{{$educationinfo->coursetaken}}" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2 pb-0">
                                        <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                            <label class="mb-0">Major</label>
                                            <input type="text" style="border:none" name="oldmajor[]" value="{{$educationinfo->major}}" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 mb-2 pb-0">
                                        <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                            <label class="mb-0">Date Completed</label>
                                            <input type="date" style="border:none" name="olddatecompleted[]" value="{{$educationinfo->completiondate}}" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mb-2 pb-0" >
                                        <div class="col-12"style="position:absolute;top:0;right:0;"><button type="button" class="btn btn-default btn-sm float-right deletecard">Delete &nbsp;<i class="fas fa-trash-alt text-danger"></i></button></div><br>&nbsp;
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <span class="float-left ml-3">
                        <button type="button" class="btn btn-sm btn-info pr-4 pl-4 addeducationcard"><i class="fa fa-plus"></i>&nbsp; Add More</button>
                        </span>
                    </div>
                </div>
                <br>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="updateeducationalbackground" data-dismiss="modal">Update</button>
                </div>
            </form>
        </div>
    </div>
</div> 
<div id="experience_info" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form {{--action="/hr/employeeexperience/updateexperience" method="get"--}}>
                <div class="modal-header bg-info">
                    <h5 class="modal-title"><strong>Experience</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="overflow: scroll;height: 400px;" >
                    
                    <input type="hidden" class="form-control" name="id" value="{{$profileinfo->id}}" required/>
                    <div id="experiencecontainer"></div>
                    @if(count($profileinfo->experiences) == 0)
                        <div class="card p-4">
                            <div class="row">
                                <div class="col-lg-12 mb-2 pb-0">
                                    <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                        <label class="mb-0">Company Name</label>
                                        <input type="text" style="border:none" name="companyname[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-2 pb-0">
                                    <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                        <label class="mb-0">Location</label>
                                        <input type="text" style="border:none" name="location[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase" />
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2 pb-0">
                                    <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                        <label class="mb-0">Job Position</label>
                                        <input type="text" style="border:none" name="jobposition[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-2 pb-0">
                                    <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                        <label class="mb-0">Period from</label>
                                        <input type="date" style="border:none" name="periodfrom[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2 pb-0">
                                    <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                        <label class="mb-0">Period to</label>
                                        <input type="date" style="border:none" name="periodto[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mb-2 pb-0" >
                                    <div class="col-12"style="position:absolute;top:0;right:0;"><button type="button" class="btn btn-default btn-sm float-right deletecard">Delete &nbsp;<i class="fas fa-trash-alt text-danger"></i></button></div><br>&nbsp;
                                </div>
                            </div>
                        </div>
                    @else
                        @foreach($profileinfo->experiences as $experience)
                            <div class="card p-4">
                                <input type="hidden" style="border:none" name="oldexperienceid[]"  value="{{$experience->id}}"class="form-control form-control-sm pb-0 pt-0 text-uppercase" required/>
                                <div class="row">
                                    <div class="col-lg-12 mb-2 pb-0">
                                        <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                            <label class="mb-0">Company Name</label>
                                            <input type="text" style="border:none" name="oldcompanyname[]" value="{{$experience->companyname}}" class="form-control form-control-sm pb-0 pt-0 text-uppercase" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 mb-2 pb-0">
                                        <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                            <label class="mb-0">Location</label>
                                            <input type="text" style="border:none" name="oldlocation[]"  value="{{$experience->companyaddress}}"class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2 pb-0">
                                        <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                            <label class="mb-0">Job Position</label>
                                            <input type="text" style="border:none" name="oldjobposition[]"  value="{{$experience->position}}"class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 mb-2 pb-0">
                                        <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                            <label class="mb-0">Period from</label>
                                            <input type="date" style="border:none" name="oldperiodfrom[]"  value="{{$experience->periodfrom}}"class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2 pb-0">
                                        <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                            <label class="mb-0">Period to</label>
                                            <input type="date" style="border:none" name="oldperiodto[]"  value="{{$experience->periodto}}"class="form-control form-control-sm pb-0 pt-0"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <span class="float-left ml-3">
                        <button type="button" class="btn btn-sm btn-info pr-4 pl-4 addexperiencecard"><i class="fa fa-plus"></i>&nbsp; Add More</button>
                        </span>
                    </div>
                </div>
                <br>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="updateexperience">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    
    $(document).on('click','#updatepersonalinformation', function(){
            var profiletitle = $('#profiletitle').val();
            var profilesuffix = $('#profilesuffix').val();
            var profilefname = $('#profilefname').val();
            var profilemname = $('#profilemname').val();
            var profilelname = $('#profilelname').val();
            var profilecivilstatusid = $('#profilecivilstatusid').val();
            var profiledob = $('#profiledob').val();
            var profilegender = $('#profilegender').val();
            var profileaddress = $('#profileaddress').val();
            var profileemail = $('#profileemail').val();
            var contactnum = $('#contactnum').val();
            var profilespouseemployment = $('#profilespouseemployment').val();
            var profilenumofchildren = $('#profilenumofchildren').val();
            var profilenationalityid = $('#profilenationalityid').val();
            var profilereligionid = $('#profilereligionid').val();
            var profiledatehired = $('#profiledatehired').val();
            $.ajax({
                url: "/hr/updatepersonalinfo",
                type: "get",
                data: {
                    employeeid: '{{$profileinfo->id}}',
                    profiletitle:profiletitle,
                    profilesuffix:profilesuffix,
                    profilefname:profilefname,
                    profilemname:profilemname,
                    profilelname:profilelname,
                    profilecivilstatusid:profilecivilstatusid,
                    profiledob:profiledob,
                    profilegender:profilegender,
                    profileaddress:profileaddress,
                    profileemail:profileemail,
                    contactnum:contactnum,
                    profilespouseemployment:profilespouseemployment,
                    profilenumofchildren:profilenumofchildren,
                    profilenationalityid:profilenationalityid,
                    profilereligionid:profilereligionid,
                    profiledatehired:profiledatehired
                },
                success: function (data) {
                    toastr.success('Personal Information updated successfully!')
                    $('#custom-content-above-tabContent').empty()
                    $('#custom-content-above-profile-tab').click()
                }
            });
      })
    $(document).on('click','#updateemergencycontact', function(){
        var emergencyname = $('#emergencyname').val()
        var emergencyrelationship = $('#emergencyrelationship').val()
        var emergencycontactnumber = $('#emergencycontactnumber').val()
        $.ajax({
            url: "/hr/updateemergencycontact",
            type: "get",
            data: {
                employeeid: '{{$profileinfo->id}}',
                emergencyname:emergencyname,
                emergencyrelationship:emergencyrelationship,
                emergencycontactnumber:emergencycontactnumber
            },
            success: function (data) {
                toastr.success('Emergency Contact updated successfully!')
                $('#custom-content-above-tabContent').empty()
                $('#custom-content-above-profile-tab').click()
            }
        });
      })
        $(document).on('click','#updatedesignationid', function(){
            var departmentid = $('#departmentid').val()
            var designationid = $('#designationid').val()
            $.ajax({
                url: "/hr/updatedesignation",
                type: "get",
                data: {
                    employeeid: '{{$profileinfo->id}}',
                    departmentid:departmentid,
                    designationid:designationid
                },
                success: function (data) {
                    toastr.success('Emergency Contact updated successfully!')
                    $('#custom-content-above-tabContent').empty()
                    $('#custom-content-above-profile-tab').click()
                }
            });
      })
    
    $(document).on('click','.deleteaccount',function() {
        var accountid       = $(this).closest('tr').attr('id');
        var accountdesc     = $(this).attr('accountdescription');
        var accountnum      = $(this).attr('accountnumber');
        var thistr          = $(this).closest('tr');
        Swal.fire({
            title: 'Are you sure you want to delete the selected account info?',
            // text: "You won't be able to revert this!",
            html:
                "Account Description: <strong>" + accountdesc + '</strong>'+
                '<br>'+ 
                "Account #: <strong>" + accountnum + '</strong>'+
                '<br>'+
                "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/hr/deleteaccount',
                    type:"GET",
                    dataType:"json",
                    data:{
                        accountid: accountid,
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    success: function(data){
                        if(data == 1)
                        {
                            thistr.remove();
                            toastr.success('Account deleted successfully!')
                            $('#custom-content-above-tabContent').empty()
                            $('#custom-content-above-profile-tab').click()
                        }else{
                            toastr.error('Soemthng went wrong!')
                        }
                    }
                })
            }
        })
    });
    $(document).on('click','#updateaccounts', function(){
        var newdescriptions = [];
        var oldaccountdescription = [];
        var newaccountnumber = [];
        var oldaccountnumber = [];
        var oldaccountid = [];
        var emptyelements = [];
        $('input[name="newaccountdescription[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                newdescriptions.push($(this).val())
            }
        })
        $('input[name="newaccountnumber[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                newaccountnumber.push($(this).val())
            }
        })
        if($(this).closest('form').find('input[name="oldaccountdescription[]"]').length > 0)
        {
            $('input[name="oldaccountdescription[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    oldaccountdescription.push($(this).val())
                }
            })
            $('input[name="oldaccountnumber[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    oldaccountnumber.push($(this).val())
                }
            })
            $('input[name="oldaccountid[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    oldaccountid.push($(this).val())
                }
            })
        }
        
        if(emptyelements.length == 0)
        {
            $.ajax({
                url: "/hr/updateaccounts",
                type: "GET",
                data: {
                    employeeid:   '{{$profileinfo->id}}',
                    newdescriptions:newdescriptions,
                    newaccountnumber:newaccountnumber,
                    oldaccountdescription:oldaccountdescription,
                    oldaccountnumber:oldaccountnumber,
                    oldaccountid:oldaccountid
                    },
                success: function (data) {
                    // $('#profilepic').attr('src',data)
                    toastr.success('Accounts updated successfully!')
                    $('#custom-content-above-tabContent').empty()
                    $('#custom-content-above-profile-tab').click()
                }
            });
        }else{
            $.each(emptyelements,function(){
                $(this).css('border','1px solid red')
            })
        }
          
      })
      
        
      $('.addrow').on('click', function(){
            $('#familytbody').append(
                '<tr>'+
                    '<td class="p-0"><input class="form-control text-uppercase" type="text" name="familyname[]" required/></td>'+
                    '<td class="p-0"><input class="form-control text-uppercase" type="text" name="familyrelation[]"/></td>'+
                    // '<td class="p-0"><input class="form-control text-uppercase" type="date" name="familydob[]"/></td>'+
                    '<td class="p-0"><input class="form-control text-uppercase familycontactnum" type="text" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" name="familynum[]"/></td>'+
                    '<td class="p-0 bg-danger" style="vertical-align: middle;"><button type="button" class="btn btn-sm btn-danger btn-block deleterow"><i class="fa fa-times"></i></button></td>'+
                '</tr>'
            );
            $(".familycontactnum").inputmask({mask: "9999-999-9999"});
        });
        $(document).on('click','.deleterow', function(){
            $(this).closest('tr').remove();
        })
        

        $('.deletefamilymember').click(function() {
            var familymemberid      = $(this).attr('familyid');
            var familymembername    = $(this).attr('familymembername');
            var employeeid          = $(this).attr('id');
            
            Swal.fire({
                title: 'Are you sure you want to delete this family member?',
                // text: "You won't be able to revert this!",
                html:
                    "Family member: <strong>" + familymembername + '</strong>'+
                    '<br>'+
                    "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/hr/deletefamilyinfo',
                        type:"GET",
                        dataType:"json",
                        data:{
                            familymemberid: familymemberid,
                            employeeid: employeeid
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(){
                            
                            toastr.success('Accounts updated successfully!')
                            $('#custom-content-above-tabContent').empty()
                            $('#custom-content-above-profile-tab').click()
                        }
                    })
                }
            })
        });
        $(document).on('click','#updatefamilyinfo', function(){
            var thiselement = $(this);
            var familyname = [];
            var familyrelation = [];
            var familynum = [];
            var oldid = [];
            var oldfamilyname = [];
            var oldfamilyrelation = [];
            var oldfamilynum = [];
            var emptyelements = [];
        
            $('input[name="familyname[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    familyname.push($(this).val())
                }
            })
            $('input[name="familyrelation[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    familyrelation.push($(this).val())
                }
            })
            $('input[name="familynum[]"]').each(function(){
                $(this).css('border','1px solid #ddd')
                if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    emptyelements.push($(this))
                }else{

                    familynum.push($(this).val())
                }
            })

            if($(this).closest('form').find('input[name="oldfamilyid[]"]').length > 0)
            {
                $('input[name="oldfamilyid[]"]').each(function(){
                    $(this).css('border','1px solid #ddd')
                    if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        emptyelements.push($(this))
                    }else{

                        oldid.push($(this).val())
                    }
                })
                $('input[name="oldfamilyname[]"]').each(function(){
                    $(this).css('border','1px solid #ddd')
                    if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        emptyelements.push($(this))
                    }else{

                        oldfamilyname.push($(this).val())
                    }
                })
                $('input[name="oldfamilyrelation[]"]').each(function(){
                    $(this).css('border','1px solid #ddd')
                    if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        emptyelements.push($(this))
                    }else{

                        oldfamilyrelation.push($(this).val())
                    }
                })
                $('input[name="oldfamilynum[]"]').each(function(){
                    $(this).css('border','1px solid #ddd')
                    if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        emptyelements.push($(this))
                    }else{

                        oldfamilynum.push($(this).val())
                    }
                })
            }
            if(emptyelements.length == 0)
            {
                
                $.ajax({
                    url: "/hr/updatefamilyinfo",
                    type: "GET",
                    data: {
                        employeeid:   '{{$profileinfo->id}}',
                        familyname:familyname,
                        familyrelation:familyrelation,
                        familynum:familynum,
                        oldid:oldid,
                        oldfamilyname:oldfamilyname,
                        oldfamilyrelation:oldfamilyrelation,
                        oldfamilynum:oldfamilynum
                        },
                    success: function (data) {
                        // $('#profilepic').attr('src',data)
                        toastr.success('Accounts updated successfully!')
                        $('#custom-content-above-tabContent').empty()
                        $('#custom-content-above-profile-tab').click()
                    }
                });
            }else{
                $.each(emptyelements,function(){
                    $(this).css('border','1px solid red')
                })
            }
      })
      $(document).on('click','#updateeducationalbackground', function(){
            var schoolname = [];
            var address = [];
            var coursetaken = [];
            var major = [];
            var datecompleted = [];
            var oldid = [];
            var oldschoolname = [];
            var oldaddress = [];
            var oldcoursetaken = [];
            var oldmajor = [];
            var olddatecompleted = [];
            var emptyelements = [];
        
        $('input[name="schoolname[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                schoolname.push($(this).val())
            }
        })
        $('input[name="address[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                address.push($(this).val())
            }
        })
        $('input[name="coursetaken[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                coursetaken.push($(this).val())
            }
        })
        $('input[name="major[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                major.push($(this).val())
            }
        })
        $('input[name="datecompleted[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                datecompleted.push($(this).val())
            }
        })
        $('input[name="oldeducationid[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                oldid.push($(this).val())
            }
        })
        $('input[name="oldschoolname[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                oldschoolname.push($(this).val())
            }
        })
        $('input[name="oldaddress[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                oldaddress.push($(this).val())
            }
        })
        $('input[name="oldcoursetaken[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                oldcoursetaken.push($(this).val())
            }
        })
        $('input[name="oldmajor[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                oldmajor.push($(this).val())
            }
        })
        $('input[name="olddatecompleted[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                olddatecompleted.push($(this).val())
            }
        })
        if(emptyelements.length == 0)
        {
            
            $.ajax({
                url: "/hr/updateeducationinfo",
                type: "GET",
                data: {
                    employeeid:   '{{$profileinfo->id}}',
                    schoolname  :   schoolname,
                    address :   address,
                    coursetaken :   coursetaken,
                    major   :   major,
                    datecompleted   :   datecompleted,
                    oldid   :   oldid,
                    oldschoolname   :   oldschoolname,
                    oldaddress  :   oldaddress,
                    oldcoursetaken  :   oldcoursetaken,
                    oldmajor    :   oldmajor,
                    olddatecompleted    :   olddatecompleted
                    },
                success: function (data) {
                    // $('#profilepic').attr('src',data)
                    toastr.success('Accounts updated successfully!')
                    $('#custom-content-above-tabContent').empty()
                    $('#custom-content-above-profile-tab').click()
                }
            });
        }else{
            $.each(emptyelements,function(){
                $(this).css('border','1px solid red')
            })
        }
      })
      $(document).on('click','#updateexperience',function(){
            var companyname = [];
            var location = [];
            var jobposition = [];
            var periodfrom = [];
            var periodto = [];
            var oldid = [];
            var oldcompanyname = [];
            var oldlocation = [];
            var oldjobposition = [];
            var oldperiodfrom = [];
            var oldperiodto = [];
            var emptyelements = [];
        $('input[name="companyname[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                companyname.push($(this).val())
            }
        })
        $('input[name="location[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                location.push($(this).val())
            }
        })
        $('input[name="jobposition[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                jobposition.push($(this).val())
            }
        })
        $('input[name="periodfrom[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                periodfrom.push($(this).val())
            }
        })
        $('input[name="periodto[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                periodto.push($(this).val())
            }
        })
        $('input[name="oldexperienceid[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                oldid.push($(this).val())
            }
        })
        $('input[name="oldcompanyname[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                oldcompanyname.push($(this).val())
            }
        })
        $('input[name="oldlocation[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                oldlocation.push($(this).val())
            }
        })
        $('input[name="oldjobposition[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                oldjobposition.push($(this).val())
            }
        })
        $('input[name="oldperiodfrom[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                oldperiodfrom.push($(this).val())
            }
        })
        $('input[name="oldperiodto[]"]').each(function(){
            $(this).css('border','1px solid #ddd')
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                emptyelements.push($(this))
            }else{

                oldperiodto.push($(this).val())
            }
        })
        if(emptyelements.length == 0)
        {
            
            $.ajax({
                url: "/hr/employeeexperience/updateexperience",
                type: "GET",
                data: {
                    employeeid:   '{{$profileinfo->id}}',
                    companyname  :   companyname,
                    location :   location,
                    jobposition :   jobposition,
                    periodfrom   :   periodfrom,
                    periodto   :   periodto,
                    oldid   :   oldid,
                    oldcompanyname   :   oldcompanyname,
                    oldlocation  :   oldlocation,
                    oldjobposition  :   oldjobposition,
                    oldperiodfrom    :   oldperiodfrom,
                    oldperiodto    :   oldperiodto
                    },
                success: function (data) {
                    // $('#profilepic').attr('src',data)
                    toastr.success('Work Experience updated successfully!')
                    $('#custom-content-above-tabContent').empty()
                    $('#custom-content-above-profile-tab').click()
                }
            });
        }else{
            $.each(emptyelements,function(){
                $(this).css('border','1px solid red')
            })
        }
      })
        $(document).on('click','.deleteexperience',function() {
            var experienceid        = $(this).attr('experienceid');
            var experiencecompany   = $(this).attr('experiencecompany');
            var experienceposition  = $(this).attr('experienceposition');
            var employeeid          = '{{$profileinfo->id}}';


            Swal.fire({
                title: 'Are you sure you want to delete this work experience?',
                // text: "You won't be able to revert this!",
                html:
                    "Company: <strong>" + experiencecompany + '</strong>'+
                    '<br>'+
                    "Position: <strong>" + experienceposition + '</strong>'+
                    '<br>'+
                    "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/hr/employeeexperience/delete',
                        type:"GET",
                        dataType:"json",
                        data:{
                            experienceid: experienceid,
                            employeeid: employeeid
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Work Experience Info deleted successfully!')
                            $('#custom-content-above-tabContent').empty()
                            $('#custom-content-above-profile-tab').click()
                        }
                    })
                }
            })
        });
        $('#btn-submit-addeducbg').on('click', function(){
            var sy              = $('#input-educbg-sy').val();
            var university      = $('#input-educbg-university').val();
            var address         = $('#input-educbg-address').val();
            var course          = $('#input-educbg-course').val();
            var major           = $('#input-educbg-major').val();
            var awards          = $('#input-educbg-awards').val();
            if(university.replace(/^\s+|\s+$/g, "").length == 0)
            {
                $('#input-educbg-university').css('border','1px solid red')
                toastr.warning('Please fill in required field!')
            }else{
                    $.ajax({
                        url: '/hr/employeeexperience/delete',
                        type:"GET",
                        dataType:"json",
                        data:{
                            experienceid: experienceid,
                            employeeid: employeeid
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){
                            toastr.success('Work Experience Info deleted successfully!')
                            $('#custom-content-above-tabContent').empty()
                            $('#custom-content-above-profile-tab').click()
                        }
                    })
            }
        })
</script>
