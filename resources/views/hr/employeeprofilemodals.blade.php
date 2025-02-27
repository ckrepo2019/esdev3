
{{-- ===================================================================================================================================================================
===================================================================================================================================================================
=================================================================================================================================================================== --}}
<div id="edit_profile_pic" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Profile Photo</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-md-12 text-center">
        
                        <div id="upload-demo"></div>
        
                        </div>
        
                    </div>
                    <input type="file" id="upload" class="form-control form-control-sm" style="overflow: hidden;">
                    <br>
                    <br>
                <button class="btn btn-success upload-result">Upload Image</button>
            </div>
        </div>
    </div>
</div>
{{-- ===================================================================================================================================================================
===================================================================================================================================================================
=================================================================================================================================================================== --}}
{{-- ===================================================================================================================================================================
===================================================================================================================================================================
=================================================================================================================================================================== --}}
{{-- <div id="edit_emergency_contact" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title"><strong>Emergency Contact</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/employeeinfo/updateemergencycontact" method="get">
                    <input type="hidden" class="form-control" name="id" value="{{$profile->id}}" required/>
                    <input type="hidden" class="form-control" name="linkid" value="custom-content-above-home" />
                    <label>Name</label>
                    @if(count($employee_info)==0)
                    <input type="text" class="form-control form-control-sm" name="emergencyname" required/>
                    @else
                    <input type="text" class="form-control form-control-sm" name="emergencyname" value="{{$employee_info[0]->emercontactname}}" required/>
                    @endif
                    <br>
                    <label>Relationship</label>
                    @if(count($employee_info)==0)
                    <input type="text" class="form-control form-control-sm" name="relationship" required/>
                    @else
                    <input type="text" class="form-control form-control-sm" name="relationship" value="{{$employee_info[0]->emercontactrelation}}" required/>
                    @endif
                    <br>
                    <label>Contact Number</label>
                    @if(count($employee_info)==0)
                    <input type="text" class="form-control form-control-sm" id="emergencycontactnumber" name="contactnumber" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" required/>
                    @else
                    <input type="text" class="form-control form-control-sm" id="emergencycontactnumber" name="contactnumber" value="{{$employee_info[0]->emercontactnum}}" minlength="13" maxlength="13" data-inputmask-clearmaskonlostfocus="true" required/>
                    @endif
                    <br>
                    <div class="submit-section">
                        <button type="submit" class="btn btn-primary submit-btn float-right">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}
{{-- ===================================================================================================================================================================
===================================================================================================================================================================
=================================================================================================================================================================== --}}
{{-- <div id="edit_designation" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title"><strong>Department & Designation</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/employeeinfo/updatedesignation" method="get">
                    <input type="hidden" class="form-control" name="id" value="{{$profile->id}}" required/>
                    <input type="hidden" class="form-control" name="linkid" value="custom-content-above-home" />
                    <div class="form-group text-uppercase">
                        <label>Department <span class="text-danger">*</span></label>
                        <select class="form-control text-uppercase" name="departmentid" required>
                            @if(count($employee_info)==0)
                                <option>Select department</option>
                                @foreach($department as $dept)
                                    <option value="{{$dept->id}}" >{{strtoupper($dept->department)}}</option>
                                @endforeach
                            @else
                                <option>Select department</option>
                                @foreach($department as $dept)
                                    <option value="{{$dept->id}}" {{$dept->id == $employee_info[0]->departmentid ? 'selected' : ''}}>{{strtoupper($dept->department)}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <label>Designation <span class="text-danger">*</span></label>
                        <select class="form-control text-uppercase" name="designationid" required> 
                            @if(count($employee_info)==0)
                            @else
                                @foreach($designations as $designation)
                                    <option value="{{$designation->id}}" {{$designation->id == $employee_info[0]->designationid ? 'selected' : ''}}>{{strtoupper($designation->designation)}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <br>
                    <div class="submit-section">
                        <button type="submit" class="btn btn-primary submit-btn float-right">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}
{{-- ===================================================================================================================================================================
===================================================================================================================================================================
=================================================================================================================================================================== --}}
{{-- <div id="edit_accounts" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title"><strong>Update Accounts</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/employeeinfo/updateaccounts" method="get">
                    <input type="hidden" class="form-control" name="id" value="{{$profile->id}}" required/>
                    <input type="hidden" class="form-control" name="linkid" value="custom-content-above-home" />
                    <div class="addrowaccounts">
                        @if(count($employee_accounts) == 0)
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
                            @foreach($employee_accounts as $employee_account)
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
                        <button type="submit" class="btn btn-primary btn-sm float-right">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}
{{-- ===================================================================================================================================================================
===================================================================================================================================================================
=================================================================================================================================================================== --}}
{{-- <div id="family_info_modal" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-LG" role="document">
        <div class="modal-content">
            <form action="/employeefamily/updatefamilyinfo" method="get">
                <div class="modal-header bg-info">
                    <h5 class="modal-title"><strong>Family Information</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="overflow: scroll;">
                        <input type="hidden" class="form-control" name="id" value="{{$profile->id}}" required/>
                        @if(count($employee_familyinfo)==0)
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
                                    @foreach($employee_familyinfo as $family)
                                        <tr>
                                            <td class="p-0"><input class="form-control" type="hidden" name="oldid[]" value="{{$family->id}}"/><input class="form-control" type="text" name="oldfamilyname[]" value="{{$family->famname}}" required/></td>
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
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
{{-- ===================================================================================================================================================================
===================================================================================================================================================================
=================================================================================================================================================================== --}}
{{-- <div id="education_info" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form action="/employeeeducation/updateeducationinfo" method="get">
                <div class="modal-header bg-info">
                    <h5 class="modal-title"><strong>Educational Background</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="overflow: scroll;height: 400px;" >
                    
                    <input type="hidden" class="form-control" name="id" value="{{$profile->id}}" required/>
                    
                    <div id="educationalbackgroundcontainer"></div>
                    @if(count($employee_educationinfo) == 0)
                        <div class="card p-4">
                            <div class="row">
                                <div class="col-lg-6 mb-2 pb-0">
                                    <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                        <label class="mb-0">Institution</label>
                                        <input type="text" style="border:none" name="schoolname[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase" required/>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2 pb-0">
                                    <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                        <label class="mb-0">Address</label>
                                        <input type="text" style="border:none" name="address[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-2 pb-0">
                                    <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                        <label class="mb-0">Course Taken</label>
                                        <input type="text" style="border:none" name="coursetaken[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2 pb-0">
                                    <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                        <label class="mb-0">Major</label>
                                        <input type="text" style="border:none" name="major[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-2 pb-0">
                                    <div class="col-12" style="border:1px solid #ddd;border-radius: 10px;">
                                        <label class="mb-0">Date Completed</label>
                                        <input type="date" style="border:none" name="datecompleted[]" class="form-control form-control-sm pb-0 pt-0 text-uppercase"/>
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
                        @foreach($employee_educationinfo as $educationinfo)
                            <div class="card p-4">
                                <div class="row">
                                    <input type="hidden" style="border:none" name="oldid[]" value="{{$educationinfo->id}}" class="form-control form-control-sm pb-0 pt-0"/>
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
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
{{-- ===================================================================================================================================================================
===================================================================================================================================================================
=================================================================================================================================================================== --}}
{{-- <div id="experience_info" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form action="/employeeexperience/updateexperience" method="get">
                <div class="modal-header bg-info">
                    <h5 class="modal-title"><strong>Experience</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="overflow: scroll;height: 400px;" >
                    
                    <input type="hidden" class="form-control" name="id" value="{{$profile->id}}" required/>
                    <div id="experiencecontainer"></div>
                    @if(count($employee_experience) == 0)
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
                        @foreach($employee_experience as $experience)
                            <div class="card p-4">
                                <input type="hidden" style="border:none" name="oldid[]"  value="{{$experience->id}}"class="form-control form-control-sm pb-0 pt-0 text-uppercase" required/>
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
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
{{-- ===================================================================================================================================================================
===================================================================================================================================================================
=================================================================================================================================================================== --}}
{{-- <div id="rateelevation" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title"><strong>Change rate</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/employeerateelevation" method="get">
                    <input type="hidden" class="form-control" name="id" value="{{$profile->id}}" required/>
                    <input type="hidden" class="form-control" name="linkid" value="custom-content-above-profile" />
                    @if(count($employee_basicsalaryinfo) == 0)
                    <input type="hidden" class="form-control" name="oldamount" value="0" />
                    @else
                    <input type="hidden" class="form-control" name="oldamount" value="{{$employee_basicsalaryinfo[0]->amount}}" />
                    @endif
                    <label>Salary Amount</label>
                    @if(count($employee_basicsalaryinfo) > 0)
                        <input type="number" class="form-control form-control-sm" name="rateelevationamount" value="{{$employee_basicsalaryinfo[0]->amount}}"/>
                        <br>
                    @endif
                    <div class="submit-section">
                        <button type="submit" class="btn btn-primary submit-btn float-right">Submit request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}
{{-- ===================================================================================================================================================================
===================================================================================================================================================================
=================================================================================================================================================================== --}}
{{-- <div id="addcredentials" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title"><strong>Upload Credential</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/employeeinfo/updatedesignation" method="get">
                    <input type="hidden" class="form-control" name="id" value="{{$profile->id}}" required/>
                    <input type="hidden" class="form-control" name="linkid" value="custom-content-above-home" />
                    <div class="form-group">
                        <label>Department <span class="text-danger">*</span></label>
                        <select class="form-control" name="departmentid" required>
                            @if(count($employee_info)==0)
                                <option>Select department</option>
                                @foreach($department as $dept)
                                    <option value="{{$dept->id}}" >{{$dept->department}}</option>
                                @endforeach
                            @else
                                <option>Select department</option>
                                @foreach($department as $dept)
                                    <option value="{{$dept->id}}" {{$dept->id == $employee_info[0]->departmentid ? 'selected' : ''}}>{{$dept->department}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <label>Designation <span class="text-danger">*</span></label>
                        <select class="form-control" name="designationid" required> 
                            @if(count($employee_info)==0)
                            @else
                                @foreach($designations as $designation)
                                    <option value="{{$designation->id}}" {{$designation->id == $employee_info[0]->designationid ? 'selected' : ''}}>{{$designation->designation}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <br>
                    <div class="submit-section">
                        <button type="submit" class="btn btn-primary submit-btn float-right">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}