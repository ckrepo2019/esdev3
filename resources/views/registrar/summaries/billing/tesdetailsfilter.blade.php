
        @if(count($students)==0)
        <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                No applications approved by the finance!
            </div>
        </div>
        </div>
        
        @else
        <div class="card">
    <div class="card-body p-2" style="overflow: hidden;">
        <div class="row p-0">
            <div class="col-md-12 text-right mb-2">
                <button type="button" class="btn btn-default btn-export-pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                <button type="button" class="btn btn-default btn-export-excel"><i class="fa fa-file-excel"></i> Export to Excel</button>
                {{-- <button type="button" class="btn btn-default btn-export-excel"><i class="fa fa-file-excel"></i> Export to EXCEL</button> --}}
            </div>
            <div class="col-md-12 p-0">
                <table class="table table-hover m-0" style="font-size: 13px;" cellspacing="0" width="100%" id="table-results">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>SID</th>
                            <th>Student Name</th>
                            <th>Sex</th>
                            <th>Birthdate</th>
                            <th>Degree Program</th>
                            <th>Units Enrolled</th>
                            <th>E-mail address</th>
                            <th>Phone Number</th>
                            <th>Actual Tuition and<br/>Other School Fees</th>
                            <th>Billed Amount</th>
                            <th>Stipend</th>
                            <th>Person w/<br/>disability</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                {{-- <td></td> --}}
                                <td>
                                    <div class="form-group clearfix">
                                      <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="checkboxPrimary{{$student->id}}" @if($student->appstatus == 1) checked @endif class="check-approve" value="{{$student->appid}}">
                                        <label for="checkboxPrimary{{$student->id}}">
                                        </label>
                                      </div>
                                    </div>
                                </td>
                                <td>{{$student->sid}}</td>
                                <td>
                                    
                                    <a href="#modal-viewapp{{$student->appid}}" data-toggle="modal" data-target="#modal-viewapp{{$student->appid}}" class="eachapplication" id="{{$student->appid}}">{{ucwords(strtolower($student->lastname))}}, {{ucwords(strtolower($student->firstname))}} {{ucwords(strtolower($student->middlename))}} {{$student->suffix}}</a>
                                    <div class="modal fade" id="modal-viewapp{{$student->appid}}">
                                      <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h4 class="modal-title">Application Details</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body">
                                            <div class="timeline">
                                            <!-- timeline time label -->
                                            <div class="time-label">
                                                @if($student->appstatus == 0)
                                                    <span class="bg-warning">{{date('M d, Y', strtotime($student->createddatetime))}}</span>
                                                    <span class="bg-warning">Pending</span>
                                                @elseif($student->appstatus == 1)
                                                    <span class="bg-success">{{date('M d, Y', strtotime($student->createddatetime))}}</span>
                                                    <span class="bg-success">Approved</span>
                                                    {{-- <button type="button" class="btn btn-default btn-export-application" data-id="{{$student->appid}}"><i class="fa fa-file-pdf"></i> Export to PDF</button> --}}
                                                @elseif($student->appstatus == 2)
                                                    <span class="bg-danger">{{date('M d, Y', strtotime($student->createddatetime))}}</span>
                                                    <span class="bg-danger">Disapproved</span>
                                                @endif
                                            </div>
                                            <!-- /.timeline-label -->
                                            <!-- timeline item -->
                                            <div>
                                                <i class="fas fa-envelope bg-blue"></i>
                                                <div class="timeline-item">
                                                    <span class="time"><i class="fas fa-clock"></i> {{date('h:i A', strtotime($student->createddatetime))}}</span>
                                                    <h3 class="timeline-header"><a href="#">Application submitted</a></h3>
                                    
                                                    <div class="timeline-body pb-0" style="font-size: 13px;">
                                                        <div class="row mb-2">
                                                            <div class="col-md-6">
                                                                <label>DSWD Household No. :</label> {{$student->dswdhno}}
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Household Per Capita Income :</label> {{$student->hpcincome}}
                                                            </div>
                                                        </div>
                                                        <fieldset class="form-group border p-2 mb-2">
                                                            <legend class="w-auto m-0" style="font-size: 12px; font-weight: bold;">Father's Complete Name</legend>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label>Last Name :</label><br/> {{$student->flastname}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>First Name :</label><br/> {{$student->ffirstname}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Middle Name :</label><br/> {{$student->fmiddlename}}
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset class="form-group border p-2 mb-2">
                                                            <legend class="w-auto m-0" style="font-size: 12px; font-weight: bold;">Mother's Complete Maiden Name</legend>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label>Last Name :</label><br/> {{$student->mmlastname}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>First Name :</label><br/> {{$student->mmfirstname}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Middle Name :</label><br/> {{$student->mmmiddlename}}
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset class="form-group border p-2 mb-2">
                                                            <legend class="w-auto m-0" style="font-size: 12px; font-weight: bold;">Permanent Address</legend>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label>Street :</label> {{$student->street}}
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <label>Barangay :</label> {{$student->barangay}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Town/City/Municipality :</label> {{$student->city}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Province :</label> {{$student->province}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Zip Code :</label> {{$student->zipcode}}
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset class="form-group border p-2 mb-2">
                                                            <legend class="w-auto m-0" style="font-size: 12px; font-weight: bold;">Contact Details</legend>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label>Contact No. :</label> {{$student->contactno}}
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Email Address :</label> {{$student->emailaddress}}
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
                                                        <fieldset class="form-group border p-2 mb-2">
                                                            <legend class="w-auto m-0" style="font-size: 12px; font-weight: bold;">Guardian Information</legend>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label>Guardian Name :</label><br/> {{$student->guardianname}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Contact No. :</label><br/> {{$student->gcontactno}}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Guardian Address :</label><br/> {{$student->guardianaddress}}
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label>Number of siblings :</label> {{$student->numofsiblings}}
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label>Disability :</label> {{$student->disability}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($student->appstatus > 0)
                                                        <div class="timeline-footer text-right pt-0">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    Status updated last {{date('F d, Y', strtotime($student->appstatusdatetime))}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                          </div>
                                        </div>
                                          {{-- <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                          </div> --}}
                                        </div>
                                        <!-- /.modal-content -->
                                      </div>
                                      <!-- /.modal-dialog -->
                                    </div>
                                </td>
                                <td>{{$student->gender}}</td>
                                <td>@if($student->dob != null){{date('M d, Y', strtotime($student->dob))}}@endif</td>
                                <td>{{$student->courseabrv}}</td>
                                <td>{{$student->units}}</td>
                                <td>{{$student->emailaddress}}</td>
                                <td>{{$student->contactno}}</td>
                                <td>{{number_format($student->overallfees,2,'.',',')}}</td>
                                <td>{{number_format($student->billedamount,2,'.',',')}}</td>
                                <td>{{number_format($student->stipend,2,'.',',')}}</td>
                                <td>{{number_format($student->disabilityamount,2,'.',',')}}</td>
                                <td>{{number_format(($student->billedamount+$student->stipend+$student->disabilityamount),2,'.',',')}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif