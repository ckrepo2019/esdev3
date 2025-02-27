<style>
    table td, table th{
        padding: 0px !important;
    }
    .input-norecord input-header{
        height: unset;
        border-top: none !important;
        border-right: none !important;
        border-left: none !important;
    }
</style>
<div class="card collapsed-card">
    <div class="card-header">
        {{-- <div class="card-tools"> --}}
          <button type="button" class="btn btn-tool" data-card-widget="collapse">View Eligibility <i class="fas fa-minus"></i>
          </button>
        {{-- </div> --}}
    </div>
    <div class="card-body pt-0">
        <div class="row mb-2">
            <div class="col-md-4 text-left">
                <label>Date of SHS Admission</label>
                <input type="date" class="form-control" id="input-dateshsadmission" value="{{$eligibility->shsadmissiondate ?? null}}"/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 bg-gray text-center mb-2">
                <h6>ELIGIBILITY FOR SHS ENROLMENT</h6>
            </div>
        </div>
        <div class="row p-1" style="font-size: 12px; border: 1px solid black;">
            <div class="col-md-6">
                <div class="form-group clearfix">
                    <div class="icheck-primary d-inline">
                      <input type="checkbox"  id="checkbox-completerhs" value="{{$eligibility->completerhs}}" @if($eligibility->completerhs == 1) checked="" @endif>
                      <label for="checkbox-completerhs">
                          High School Completer* 
                      </label>
                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Gen. Ave.:</label> &nbsp; <input id="generalaveragehs" type="number" value="{{$eligibility->genavehs}}"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group clearfix">
                    <div class="icheck-primary d-inline">
                      <input type="checkbox"  id="checkbox-completerjh" value="{{$eligibility->completerjh}}" @if($eligibility->completerjh == 1) checked="" @endif>
                      <label for="checkbox-completerjh">
                          Junior High School Completer*
                      </label>
                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Gen. Ave.:</label> &nbsp; <input id="generalaveragejh" type="number" value="{{$eligibility->genavejh}}"/>
                </div>
            </div>
            {{-- <div class="col-md-4">
                <label>Citation: (If Any)</label> &nbsp; <textarea class="form-control" id="citation">{{$eligibility->citation}}</textarea>
            </div> --}}
            <div class="col-md-4">
                Date of Graduation/Completion (MM/DD/YYYY): <input type="date" class="form-control" id="graduationdate" value="{{$eligibility->graduationdate}}"/>
            </div>
            <div class="col-md-4">
                Name of School: <input type="text" class="form-control" id="schoolname" value="{{$eligibility->schoolname}}"/>
            </div>
            <div class="col-md-4">
                School Address: <input type="text" class="form-control" id="schooladdress" value="{{$eligibility->schooladdress}}"/>
            </div>
        </div>
        <div class="row" style="font-size: 12px;">
            <div class="col-md-12">
                Other Credential Presented
            </div>
            <div class="col-md-4">
                <div class="form-group clearfix">
                    <div class="icheck-primary d-inline">
                      <input type="checkbox" id="checkbox-peptpasser" value="{{$eligibility->peptpasser}}" @if($eligibility->peptpasser == 1) checked="" @endif>
                      <label for="checkbox-peptpasser">
                            PEPT Passer
                      </label>
                    </div>
                </div>
                Rating: <input type="text" id="peptrating" class="form-control form-control-sm" value="{{$eligibility->peptrating}}"/>
            </div>
            <div class="col-md-4">
                <div class="form-group clearfix">
                    <div class="icheck-primary d-inline">
                      <input type="checkbox" id="checkbox-alspasser" value="{{$eligibility->alspasser}}" @if($eligibility->alspasser == 1) checked="" @endif>
                      <label for="checkbox-alspasser">
                            ALS A & E Passer
                      </label>
                    </div>
                </div>
                Rating: <input type="text" id="alsrating" class="form-control form-control-sm" value="{{$eligibility->alsrating}}"/>
            </div>
            <div class="col-md-4">
                Other (Pls.Specify)
                <textarea class="form-control" id="specify">{{$eligibility->others}}</textarea>
            </div>
        </div>
        <div class="row mt-2" style="font-size: 12px;position: relative;">
            <div class="col-md-3">
                Date of Examination/Assessment (mm/dd/yyyy):
            </div>
            <div class="col-md-3"><input type="date" id="examdate" class="form-control form-control-sm" value="{{$eligibility->examdate}}"/>
            </div>
            <div class="col-md-3"><span style="position: absolute;bottom: 0;">Name and Address of Testing Center:</span></div>
            <div class="col-md-3"><input type="text" id="centername" class="form-control form-control-sm" value="{{$eligibility->centername}}"/></div>
        </div>
        <div class="row mt-1">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-sm btn-default" id="btn-eligibility-update"><i class="fa fa-edit"></i> Update</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-9 mb-2">
        <form 
              action="/" 
              id="upload_sf1" 
              method="POST" 
              enctype="multipart/form-data"
               class="m-0">
            @csrf
            <div class="form-group">
                <div class="input-group input-group-sm date">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Upload SF10</div>
                    </div>
                    <input type="file" class="form-control" data-target="#reservationdatetime"  accept=".xls,.xlsx" name="excelfile"/>
                    <div class="input-group-append">
                        <div class="input-group-text p-0"><button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-share"></i>&nbsp;&nbsp;&nbsp;&nbsp;Submit</button></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-3 mb-2 text-right">
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
            <form action="/reports_schoolform10v2/getrecords" target="_blank" method="get" class="m-0 p-0" style="display: inline;">
                <input type="hidden" value="1" name="export"/>
                <input type="hidden" value="{{$studentid}}" name="studentid"/>
                <input type="hidden" value="{{$acadprogid}}" name="acadprogid"/>
                <input type="hidden" value="pdf" name="exporttype"/>
                <input type="hidden" value="school" name="format"/>
                <button type="submit" class="btn btn-primary btn-sm text-white">
                    <i class="fa fa-file-pdf"></i>
                Export to PDF
                </button>
            </form>
            <form action="/reports_schoolform10v2/getrecords" target="_blank" method="get" class="m-0 p-0" style="display: inline;">
                <input type="hidden" value="1" name="export"/>
                <input type="hidden" value="{{$studentid}}" name="studentid"/>
                <input type="hidden" value="{{$acadprogid}}" name="acadprogid"/>
                <input type="hidden" value="pdf" name="exporttype"/>
                <input type="hidden" value="deped" name="format"/>
                <button type="submit" class="btn btn-primary btn-sm text-white">
                    <i class="fa fa-file-pdf"></i>
                    Export to PDF (DepEd Format)
                </button>
            </form>

        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hcb' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'spct')
            <form action="/reports_schoolform10v2/getrecords" target="_blank" method="get" class="m-0 p-0" style="display: inline;">
                <input type="hidden" value="1" name="export"/>
                <input type="hidden" value="{{$studentid}}" name="studentid"/>
                <input type="hidden" value="{{$acadprogid}}" name="acadprogid"/>
                <input type="hidden" value="pdf" name="exporttype"/>
                <button type="submit" class="btn btn-primary btn-sm text-white">
                    <i class="fa fa-file-pdf"></i>
                    Export to PDF
                </button>
            </form>
        @endif
    </div>
</div>
@if(count($gradelevels)>0)
    @foreach($gradelevels as $gradelevel)
        <div class="card">
            <div class="card-header">
                <div class="row">
                    @if($gradelevel->recordinputype == 0)
                    <div class="col-md-12 mb-2">@if($gradelevel->recordinputype != 0 && $gradelevel->recordinputype != 3)<span class="badge badge-success"> Uploaded</span> @if($gradelevel->autoexists == 1)<span class="badge badge-default" style="border: 1px solid #ddd;"> {{-- Select Auto-generated record --}} </span>@endif @elseif($gradelevel->recordinputype == 0)<span class="badge badge-success" > Auto-generated</span> @if($gradelevel->manualexists == 1) {{-- <span class="badge badge-default" style="border: 1px solid #ddd;"> Select Manual Record</span> --}} @endif @endif @if($gradelevel->syid == DB::table('sy')->where('isactive','1')->first()->id)<span class="badge badge-success">Current School Year</span>@endif</div>
                    <div class="col-md-12">
                            <table class="m-0" style="font-size: 13.5px; width: 100%; table-layout: fixed;">
                                <tr>
                                    <td style="width: 15%;">School</td>
                                    <td colspan="3" style="border-bottom: 1px solid #ddd;">{{$gradelevel->headerinfo[0]->schoolname}}</td>
                                </tr>
                                <tr>
                                    <td>School ID</td>
                                    <td colspan="3" style="border-bottom: 1px solid #ddd;">{{$gradelevel->headerinfo[0]->schoolid}}</td>
                                </tr>
                                <tr>
                                    <td class="text-left">School Year&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;" class="text-left">{{$gradelevel->sydesc}}</td>
                                    <td style="width: 15%;" class="text-right">Semester&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;" class="text-center">@if($gradelevel->semid == 1)1st @elseif($gradelevel->semid == 2)2nd @endif</td>
                                </tr>
                            </table>
                            <table class="m-0" style="font-size: 13.5px; width: 100%; table-layout: fixed;">
                                <tr>
                                    <td style="width: 15%;">Grade Level</td>
                                    <td style="border-bottom: 1px solid #ddd;">{{$gradelevel->levelname}}</td>
                                    <td style="width: 15%;" class="text-right">Section&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd; width: 30%;">{{$gradelevel->headerinfo[0]->sectionname}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 15%;">Track</td>
                                    <td style="border-bottom: 1px solid #ddd;">{{$gradelevel->headerinfo[0]->trackname}}</td>
                                    <td class="text-right">Strand&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;">{{$gradelevel->headerinfo[0]->strandname}}</td>
                                </tr>
                            </table>
                        </div>
                    @else
                        <div class="col-md-6">@if($gradelevel->recordinputype == 2)<span class="badge badge-success"> Uploaded</span> @else <span class="badge badge-success" > Manual</span> @endif @if($gradelevel->autoexists == 1)<span class="badge badge-default" style="border: 1px solid #ddd;"> Select Auto-generated record</span>@endif @if($gradelevel->syid == DB::table('sy')->where('isactive','1')->first()->id)<span class="badge badge-success">Current School Year</span>@endif</div>
                            <div class="col-md-6 text-right">
                                    @if(count($gradelevel->headerinfo)>0)
                                    <span class="badge badge-warning badge-clear-record" style="cursor: pointer;" data-id="{{$gradelevel->headerinfo[0]->recordid}}">Clear This Record</span>
                                    @endif
                            </div>
                        <div class="col-md-12 mt-2">
                            @if(count($gradelevel->headerinfo) == 0)
                            <table class="m-0" style="font-size: 13.5px; width: 100%;">
                                <tr>
                                    <td style="width: 15%;">School</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-schoolname"/> </td>
                                    <td class="text-right">School ID&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-schoolid"/></td>
                                </tr>
                                {{-- <tr>
                                    <td>District</td>
                                    <td style="width: 20%; border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-district"/></td>
                                    <td class="text-right">Division&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-division"/></td>
                                    <td class="text-right">Region&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header  input-region"/></td>
                                </tr> --}}
                                <tr>
                                    <td class="text-left">School Year&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;">@if($gradelevel->recordinputype == 1){{$gradelevel->sydesc}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-sydesc text-center" value="{{$gradelevel->sydesc}}" placeholder="Ex: 2019-2020"/>@endif</td>
                                    <td class="text-right">Semester&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;">@if($gradelevel->recordinputype == 1){{$gradelevel->semid}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-semid text-center" value="{{$gradelevel->semid}}" readonly/>@endif</td>
                                </tr>
                            </table>
                            <table class="m-0" style="font-size: 13.5px; width: 100%;">
                                <tr>
                                    <td>Grade Level</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-levelid" data-id="{{$gradelevel->id}}" value="{{$gradelevel->levelname}}" readonly/></td>
                                    <td style="width: 15%;" class="text-right">Section&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-sectionname"/></td>
                                </tr>
                                <tr>
                                    <td style="width: 15%;">Track & Strand</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-trackstrand text-center"/></td>
                                    <td style="width: 15%;" class="text-right">Strand&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-strand text-center"/></td>
                                </tr>
                                {{-- <tr>
                                    <td>Remarks</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-semremarks"/></td>
                                    <td style="width: 15%;" class="text-right">Date Checked&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-datechecked"/></td>
                                </tr>
                                <tr>
                                    <td style="width: 15%;">Prepared by</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-preparedby text-center"/></td>
                                    <td style="width: 15%;" class="text-right">Certified By&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-certifiedby text-center"/></td>
                                </tr> --}}
                            </table>
                            @else
                            <table class="m-0" style="font-size: 13.5px; width: 100%;">
                                <tr>
                                    <td style="width: 15%;">School</td>
                                    <td colspan="3" style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-schoolname" value="{{$gradelevel->headerinfo[0]->schoolname}}"/></td>
                                </tr>
                                <tr>
                                    <td>School ID</td>
                                    <td colspan="3" style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-schoolid" value="{{$gradelevel->headerinfo[0]->schoolid}}"/></td>
                                </tr>
                                {{-- <tr>
                                    <td>District</td>
                                    <td style="width: 20%; border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-istrict" value="{{$gradelevel->headerinfo[0]->schooldistrict}}"/></td>
                                    <td class="text-right">Division&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-division" value="{{$gradelevel->headerinfo[0]->schooldivision}}"/></td>
                                    <td class="text-right">Region&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-region" value="{{$gradelevel->headerinfo[0]->schoolregion}}"/></td>
                                </tr> --}}
                                <tr>
                                    <td class="text-left">School Year&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-sydesc text-center" value="{{$gradelevel->sydesc}}" placeholder="Ex: 2019-2020"/></td>
                                    <td class="text-right">Semester&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-semid text-center" value="{{$gradelevel->semid}}" readonly/></td>
                                </tr>
                            </table>
                            <table class="m-0" style="font-size: 13.5px; width: 100%;">
                                <tr>
                                    <td>Grade Level</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-levelid" data-id="{{$gradelevel->id}}" value="{{$gradelevel->levelname}}" readonly/></td>
                                    <td style="width: 15%;" class="text-right">Section&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-sectionname" value="{{$gradelevel->headerinfo[0]->sectionname}}"/></td>
                                </tr>
                                <tr>
                                    <td style="width: 15%;">Track</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-track text-center" value="{{$gradelevel->headerinfo[0]->trackname}}"/></td>
                                    <td style="width: 15%;" class="text-right">Strand&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-strand text-center" value="{{$gradelevel->headerinfo[0]->strandname}}"/></td>
                                </tr>
                                {{-- <tr>
                                    <td>Remarks</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-semremarks" value="{{$gradelevel->headerinfo[0]->remarks}}"/></td>
                                    <td style="width: 15%;" class="text-right">Date Checked&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="date" class="form-control form-control-sm p-0 input-norecord input-header input-datechecked" value="{{$gradelevel->headerinfo[0]->datechecked}}"/></td>
                                </tr>
                                <tr>
                                    <td style="width: 15%;">Prepared by</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-preparedby text-center" value="{{$gradelevel->headerinfo[0]->teachername}}"/></td>
                                    <td style="width: 15%;" class="text-right">Certified By&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-certifiedby text-center" value="{{$gradelevel->headerinfo[0]->recordincharge}}"/></td>
                                </tr> --}}
                            </table>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            @if($gradelevel->recordinputype == 0)
            <div class="card-body pt-0">
                <div class="row">                        
                    <div class="col-md-12">
                        @if(count($gradelevel->grades) == 0)
                        {{-- <table class="table"></table> --}}
                            <table class="table" style="font-size: 12px;">
                                <thead class="text-center">
                                    <tr>
                                        <th style="width: 12%;">Indicate</th>
                                        <th style="width: 30%;">Subjects</th>
                                        <th>1st</th>
                                        <th>2nd</th>
                                        {{-- <th>3rd</th>
                                        <th>4th</th> --}}
                                        <th style="width: 8%;">Final</th>
                                        <th style="width: 15%;">Remarks</th>
                                        {{-- <th style="width: 8%;">Credit Earned</th> --}}
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px;" colspan="6" class="text-left"> IF SUBJECT IS <strong>CORE</strong>, <strong>APPLIED</strong>, OR <strong>SPECIALIZED</strong></th>
                                    </tr>
                                </thead>
                                <tbody class="gradescontainer">    
                                </tbody>
                            </table>

                        @else
                            <table class="table" style="font-size: 13.5px;">
                                <thead class="text-center">
                                    <tr>
                                        <th style="width: 12%;">Indicate</th>
                                        <th style="width: 30%;">Subjects</th>
                                        <th>1st</th>
                                        <th>2nd</th>
                                        {{-- <th>3rd</th>
                                        <th>4th</th> --}}
                                        <th style="width: 15%;">Final</th>
                                        <th style="width: 15%;">Remarks</th>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px;" colspan="6" class="text-left"> IF SUBJECT IS <strong>CORE</strong>, <strong>APPLIED</strong>, OR <strong>SPECIALIZED</strong></th>
                                    </tr>
                                </thead>
                                {{-- @if($gradelevel->type == 1)
                                @else                         
                                <tr>
                                    <td colspan="10" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                </tr>
                                @endif      --}}
                                <tbody class="gradescontainer">     
                                    @foreach($gradelevel->grades as $grade)
                                    
                                        <?php try { ?>
                                                    
                                            <tr>
                                                <td>{{$grade->subjcode}}</td>
                                                <td>{{ucwords(strtolower($grade->subjdesc))}}</td>
                                                @if($gradelevel->syid == DB::table('sy')->where('isactive','1')->first()->id)
                                                <td class="text-center">{{$grade->q1}}</td>
                                                <td class="text-center">{{$grade->q2}}</td>
                                                @else
                                                    @if($grade->q1stat == 0)
                                                    <td class="text-center">{{$grade->q1}}</td>
                                                    @else
                                                        <td class="text-center p-0">
                                                            <div class="row text-center p-0 m-0">
                                                                <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline;" @if($grade->q1stat == 2) value="{{$grade->q1}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade->q1stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="1"  data-syid="{{$gradelevel->syid}}"  data-levelid="{{$gradelevel->id}}">@if($grade->q1stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs"></i>@endif</button>
                                                            </div>
                                                        </td>
                                                    @endif
                                                    @if($grade->q2stat == 0)
                                                    <td class="text-center">{{$grade->q2}}</td>
                                                    @else
                                                        <td class="text-center p-0">
                                                            <div class="row text-center p-0 m-0">
                                                                <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline;" @if($grade->q2stat == 2) value="{{$grade->q2}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade->q2stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="2"  data-syid="{{$gradelevel->syid}}"  data-levelid="{{$gradelevel->id}}">@if($grade->q2stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs"></i>@endif</button>
                                                            </div>
                                                        </td>
                                                    @endif
                                                @endif
                                                <td class="text-center">{{$grade->finalrating}}</td>
                                                <td class="text-center">{{isset($grade->actiontaken) ? $grade->actiontaken : $grade->remarks}}</td>
                                            </tr>
                                            
                                        <?php }catch(\Exception $error) { ?>
                                    
                                            <tr>
                                                if($studentid == 2735)
                                                {
                                                    if($gradelevel->id == 12)
                                                    {
                                                        return $studgrades;
                                                    }
                                                }
                                                <td>{{$grade['subjcode']}}</td>
                                                <td>{{ucwords(strtolower($grade['subjdesc']))}}</td>
                                                @if($gradelevel->syid == DB::table('sy')->where('isactive','1')->first()->id)
                                                <td class="text-center">{{$grade['q1']}}</td>
                                                <td class="text-center">{{$grade['q2']}}</td>
                                                @else
                                                    @if($grade['q1stat'] == 0)
                                                    <td class="text-center">{{$grade['q1']}}</td>
                                                    @else
                                                        <td class="text-center p-0">
                                                            <div class="row text-center p-0 m-0">
                                                                <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline;" @if($grade['q1stat'] == 2) value="{{$grade['q1']}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade['q1stat'] == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade['subjid']}}" data-quarter="1"  data-syid="{{$gradelevel->syid}}"  data-levelid="{{$gradelevel->id}}">@if($grade['q1stat'] == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs"></i>@endif</button>
                                                            </div>
                                                        </td>
                                                    @endif
                                                    @if($grade['q2stat'] == 0)
                                                    <td class="text-center">{{$grade['q2']}}</td>
                                                    @else
                                                        <td class="text-center p-0">
                                                            <div class="row text-center p-0 m-0">
                                                                <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline;" @if($grade['q2stat'] == 2) value="{{$grade['q2']}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade['q2stat'] == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade['subjid']}}" data-quarter="2"  data-syid="{{$gradelevel->syid}}"  data-levelid="{{$gradelevel->id}}">@if($grade['q2stat'] == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs"></i>@endif</button>
                                                            </div>
                                                        </td>
                                                    @endif
                                                @endif
                                                <td class="text-center">{{$grade['finalrating']}}</td>
                                                <td class="text-center">{{isset($grade['actiontaken']) ? $grade['actiontaken'] : $grade['remarks']}}</td>
                                            </tr>
                                            
                                        <?php }?>
                                                
                                    @endforeach  
                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                        @if($studentid == 2161 && $gradelevel->id == 15 && $gradelevel->semid == 2)
                                        
                                            <tr>
                                                <td>SPECIALIZED</td>
                                                <td>CREATIVE NONFINCTION: THE LITERARY ESSAY</td>
                                                <td class="text-center">89</td>
                                                <td class="text-center">90</td>
                                                <td class="text-center">90</td>
                                                <td class="text-center">PASSED</td>
                                            </tr>
                                            <tr>
                                                <td>SPECIALIZED</td>
                                                <td>DISCIPLINE AND IDEAS IN THE APPLIED SCIENCES</td>
                                                <td class="text-center">88</td>
                                                <td class="text-center">92</td>
                                                <td class="text-center">90</td>
                                                <td class="text-center">PASSED</td>
                                            </tr>
                                        @endif
                                    @endif
                                    @if(count($gradelevel->generalaverage)>0)
                                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                                            @if($studentid == 2161 && $gradelevel->id == 15 && $gradelevel->semid == 2) 
                                                <tr>
                                                    <td></td>
                                                    <td>{{ucwords(strtolower(collect($gradelevel->generalaverage)->first()->subjdesc))}}</td>
                                                    <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q1 ?? collect($gradelevel->generalaverage)->first()->q3}}</td>
                                                    <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q2 ?? collect($gradelevel->generalaverage)->first()->q4}}</td>
                                                    {{-- <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q3}}</td>
                                                    <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q4}}</td> --}}
                                                    <td class="text-center">89</td>
                                                    <td class="text-center">
                                                        PASSED
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td></td>
                                                    <td>{{ucwords(strtolower(collect($gradelevel->generalaverage)->first()->subjdesc))}}</td>
                                                    <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q1 ?? collect($gradelevel->generalaverage)->first()->q3}}</td>
                                                    <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q2 ?? collect($gradelevel->generalaverage)->first()->q4}}</td>
                                                    {{-- <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q3}}</td>
                                                    <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q4}}</td> --}}
                                                    <td class="text-center">{{collect($gradelevel->generalaverage)->first()->finalrating}}</td>
                                                    <td class="text-center">
                                                        
                                                        @if(isset(collect($gradelevel->generalaverage)->first()->actiontaken) || isset(collect($gradelevel->generalaverage)->first()->remarks))
                                                        {{ collect($gradelevel->generalaverage)->first()->actiontaken ?? collect($gradelevel->generalaverage)->first()->remarks}}
                                                        @endif
                                                        @if(collect($gradelevel->generalaverage)->contains('actiontaken'))
                                                            <input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-header input-remarks" value="{{collect($gradelevel->generalaverage)->first()->actiontaken}}"/>
                                                        @else
                                                            @if(collect($gradelevel->generalaverage)->contains('remarks'))
                                                                <input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-header input-remarks" value="{{collect($gradelevel->generalaverage)->first()->remarks}}"/>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @else
                                            <tr>
                                                <td></td>
                                                <td>{{ucwords(strtolower(collect($gradelevel->generalaverage)->first()->subjdesc))}}</td>
                                                <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q1 ?? collect($gradelevel->generalaverage)->first()->q3}}</td>
                                                <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q2 ?? collect($gradelevel->generalaverage)->first()->q4}}</td>
                                                {{-- <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q3}}</td>
                                                <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q4}}</td> --}}
                                                <td class="text-center">{{collect($gradelevel->generalaverage)->first()->finalrating}}</td>
                                                <td class="text-center">
                                                    
                                                    @if(isset(collect($gradelevel->generalaverage)->first()->actiontaken) || isset(collect($gradelevel->generalaverage)->first()->remarks))
                                                    {{ collect($gradelevel->generalaverage)->first()->actiontaken ?? collect($gradelevel->generalaverage)->first()->remarks}}
                                                    @endif
                                                    @if(collect($gradelevel->generalaverage)->contains('actiontaken'))
                                                        <input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-header input-remarks" value="{{collect($gradelevel->generalaverage)->first()->actiontaken}}"/>
                                                    @else
                                                        @if(collect($gradelevel->generalaverage)->contains('remarks'))
                                                            <input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-header input-remarks" value="{{collect($gradelevel->generalaverage)->first()->remarks}}"/>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endif  
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                <form action="/schoolform10/updatesigneachsem" method="POST" class="form-eachsem" data-levelid="{{$gradelevel->id}}" data-semid="{{$gradelevel->semid}}" data-sydesc="{{$gradelevel->sydesc}}">
                    @csrf
                    @php
                        $levelsemsyfilter = collect($eachsemsignatories)->where('levelid', $gradelevel->id)->where('semid', $gradelevel->semid)->where('sydesc', $gradelevel->sydesc)->first();
                    @endphp
                    <div class="row eachsem-other-container">
                        <div class="col-md-12">
                            <label>Remarks</label>
                            <textarea class="form-control" name="remarks">{{($levelsemsyfilter != false ? $levelsemsyfilter->remarks : '') ?? ''}}</textarea>
                        </div>
                        <div class="col-md-4">
                            <small><label>Prepared by:</label></small>
                            <input class="form-control form-control-sm text-center" value="{{$gradelevel->headerinfo[0]->teachername ?? ''}}" readonly/>
                            <input class="form-control form-control-sm text-center" value="Signature of Adviser over Printed Name" readonly/>
                        </div>
                        <div class="col-md-4">
                            <small><label>Certified true & Correct:</label></small>
                            <input type="text" class="form-control form-control-sm input-certncorrectname text-center" name="certncorrectname" value="{{($levelsemsyfilter != false ? $levelsemsyfilter->certncorrectname : '') ?? ''}}"/>
                            <input type="text" class="form-control form-control-sm input-certncorrectdesc text-center" name="certncorrectdesc" value="{{($levelsemsyfilter != false ? $levelsemsyfilter->certncorrectdesc : "SHS-School Record's In-Charge") ?? "SHS-School Record's In-Charge"}}"/>
                        </div>
                        <div class="col-md-4 text-right">
                            <small><label>Date Checked (MM/DD/YYYY)</label></small>
                            <input type="date" class="form-control form-control-sm input-datechecked" name="datechecked" value="{{($levelsemsyfilter != false ? $levelsemsyfilter->datechecked : '') ?? ''}}"/>
                            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fa fa-share"></i> Update</button>
                        </div>
                    </div>
                </form>
            </div>
            @else
            <div class="card-body card-manual pt-1" @if(count($gradelevel->headerinfo) == 0)hidden @endif>
                <div class="row mt-0">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-sm btn-outline-success btn-submit-header" @if(count($gradelevel->headerinfo) == 0) data-recordid="0" @else data-recordid="{{$gradelevel->headerinfo[0]->id}}" @endif><i class="fa fa-share"></i> Save changes</button>
                    </div>
                </div>
                @if(count($gradelevel->headerinfo) > 0)
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <table class="table" style="font-size: 12px; table-layout: fixed;">
                                <thead class="text-center">
                                    <tr>
                                        <th style="width: 12%;">Indicate</th>
                                        <th style="width: 40%;">Subjects</th>
                                        <th>1st</th>
                                        <th>2nd</th>
                                        <th style="width: 8%;">Final</th>
                                        <th style="width: 13%;">Remarks</th>
                                        {{-- <th style="width: 8%;">Credit Earned</th> --}}
                                        <th style="width: 15%;">Actions</th>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px;" colspan="7" class="text-left"> IF SUBJECT IS <strong>CORE</strong>, <strong>APPLIED</strong>, OR <strong>SPECIALIZED</strong></th>
                                    </tr>
                                </thead>
                                @if(count($gradelevel->grades)>0)
                                    @foreach($gradelevel->grades as $grade)
                                        <tr>
                                            <td><input class="form-control form-control-sm text-center p-0 input-subjcode" value="{{$grade->subjcode}}"/></td>
                                            <td><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="{{$grade->id}}"/><input type="text" class="form-control form-control-sm p-0 input-norecord input-subjdesc" value="{{$grade->subjdesc}}"/></td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1" value="{{$grade->q1}}"/></td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2" value="{{$grade->q2}}"/></td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-finalgrade" value="{{$grade->finalrating}}"/></td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{isset($grade->actiontaken) ? $grade->actiontaken : $grade->remarks}}"/></td>
                                            {{-- <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-credits" value="{{isset($grade->credits) ? $grade->credits : 0}}"/></td> --}}
                                            <th><button type="button" class="btn btn-sm btn-outline-warning btn-updatesubject text-sm" data-id="{{$grade->id}}">Update</button> <button type="button" class="btn btn-sm btn-outline-danger btn-deletesubject text-sm" data-id="{{$grade->id}}"><i class="fa fa-trash-alt"></i></button></th>
                                        </tr>
                                    @endforeach 
                                @endif
                                <tbody class="gradescontainer">  
                                    @if(count($gradelevel->grades) == 0)
                                        @if(collect($subjects)->where('semid',$gradelevel->semid)->where('levelid',$gradelevel->id)->count()>0)
                                            @php
                                                $checksy = DB::table('sy')
                                                    ->where('sydesc', $gradelevel->sydesc)
                                                    ->first();

                                                if($checksy)
                                                {
                                                    $collectsubjects = collect($subjects)->where('semid',$gradelevel->semid)->where('syid',$checksy->syid)->where('levelid',$gradelevel->id)->unique('subjdesc');   
                                                }else{
                                                    $collectsubjects = collect($subjects)->where('semid',$gradelevel->semid)->where('levelid',$gradelevel->id)->unique('subjdesc');   
                                                }
                                            @endphp
                                            {{-- @foreach($collectsubjects as $subject)
                                                <tr class="eachsubject">
                                                    <td style="vertical-align: middle !important;"><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjcode" placeholder="Subject" value="{{$subject->subjcode}}"/></td>
                                                    <td><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="0"/><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjdesc" placeholder="Subject" value="{{$subject->subjdesc}}"/></td>
                                                    <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q1 text-center"/></td>
                                                    <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q2 text-center"/></td>
                                                    <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-finalgrade text-center" placeholder="Final"/></td>
                                                    <td><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-remarks" placeholder="Remarks"/></td>
                                                    <td><button type="button" class="btn btn-sm btn-block btn-default btn-deleterow" style="height: unset;">&nbsp;<i class="fa fa-trash-alt"></i>&nbsp;</button></td>
                                                </tr>
                                            @endforeach --}}
                                        @endif
                                    @endif
                                    {{-- @if(count($gradelevel->generalaverage) == 0)
                                        <tr class="eachsubject">
                                            <td style="vertical-align: middle !important;"><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjcode" hidden/></td>
                                            <td><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="0"/><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjdesc" placeholder="Subject" value="General Average" readonly/></td>
                                            <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q1 text-center" hidden/></td>
                                            <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q2 text-center"hidden/></td>
                                            <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-finalgrade text-center" placeholder="Final"/></td>
                                            <td><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-remarks" placeholder="Remarks"/></td>
                                            <td><button type="button" class="btn btn-sm btn-block btn-default btn-deleterow" style="height: unset;" hidden>&nbsp;<i class="fa fa-trash-alt"></i>&nbsp;</button></td>
                                        </tr>
                                    @endif --}}
                                </tbody>
                                <tr>
                                    <th colspan="7" class="text-right"><button type="button" class="btn btn-sm btn-outline-success btn-addrow mt-2"><i class="fa fa-plus"></i> Add Subject</button></th>
                                </tr>
                                <tfoot>
                                    <tr>
                                        <th colspan="7" style="border-bottom: none !important; border-top: none !important">&nbsp;</th>
                                    </tr> 
                                    @if(count($gradelevel->generalaverage) == 0)
                                        <tr class="eachsubject">
                                            <td style="vertical-align: middle !important;"><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjcode" hidden/></td>
                                            <td><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="0"/><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjdesc" placeholder="Subject" value="General Average" readonly/></td>
                                            <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q1 text-center" hidden/></td>
                                            <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q2 text-center"hidden/></td>
                                            <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-finalgrade text-center" placeholder="Final"/></td>
                                            <td><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-remarks" placeholder="Remarks"/></td>
                                            {{-- <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord new-input input-credits text-center" placeholder="Credits"/></td> --}}
                                            <td><button type="button" class="btn btn-sm btn-block btn-default btn-deleterow" style="height: unset;" hidden>&nbsp;<i class="fa fa-trash-alt"></i>&nbsp;</button></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td><input class="form-control form-control-sm text-center p-0 input-subjcode" value="" hidden/></td>
                                            <td><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="{{$gradelevel->generalaverage[0]->id}}"/><input type="text" class="form-control form-control-sm p-0 input-norecord input-subjdesc" value="{{$gradelevel->generalaverage[0]->subjdesc}}" readonly/></td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1" value="" hidden/></td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2" value=""hidden/></td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-finalgrade" value="{{$gradelevel->generalaverage[0]->finalrating}}"/></td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{isset($gradelevel->generalaverage[0]->actiontaken) ? $gradelevel->generalaverage[0]->actiontaken : $gradelevel->generalaverage[0]->remarks}}"/></td>
                                            {{-- <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-credits" value="{{isset($grade->credits) ? $grade->credits : 0}}"/></td> --}}
                                            <th><button type="button" class="btn btn-sm btn-outline-warning btn-updatesubject text-sm btn-block" data-id="{{$gradelevel->generalaverage[0]->id}}">Update</button></th>
                                        </tr>
                                    @endif 
                                    <tr>
                                        <th colspan="7" style="border-bottom: none !important; border-top: none !important">&nbsp;</th>
                                    </tr> 
                                    <tr hidden class="tfoot">
                                        <th colspan="7" class="text-right" style="border-bottom: none !important; border-top: none !important">
                                            <button type="button" class="btn btn-sm btn-success btn-updaterecord mt-2" data-id="{{$gradelevel->headerinfo[0]->id}}"><i class="fa fa-share"></i> Save changes</button>
                                        </th>
                                    </tr>  
                                </tfoot>
                                <script>
                                    if($('.gradescontainer').find('.eachsubject').length >0)
                                    {
                                        $('.gradescontainer').closest('.table').find('.tfoot').removeAttr('hidden')
                                    }
                                </script>
                            </table>
                        </div>
                    </div>
                @endif
                <form action="/schoolform10/updatesigneachsem" method="POST" class="form-eachsem" data-levelid="{{$gradelevel->id}}" data-semid="{{$gradelevel->semid}}" data-sydesc="{{$gradelevel->sydesc}}">
                    @csrf
                    @php
                        $levelsemsyfilter = collect($eachsemsignatories)->where('levelid', $gradelevel->id)->where('semid', $gradelevel->semid)->where('sydesc', $gradelevel->sydesc)->first();
                    @endphp
                    <div class="row eachsem-other-container">
                        <div class="col-md-12">
                            <label>Remarks</label>
                            <textarea class="form-control" name="remarks">{{($levelsemsyfilter != false ? $levelsemsyfilter->remarks : '') ?? ''}}</textarea>
                        </div>
                        <div class="col-md-4">
                            <small><label>Prepared by:</label></small>
                            <input class="form-control form-control-sm text-center" value="{{$gradelevel->headerinfo[0]->teachername ?? ''}}" readonly/>
                            <input class="form-control form-control-sm text-center" value="Signature of Adviser over Printed Name" readonly/>
                        </div>
                        <div class="col-md-4">
                            <small><label>Certified true & Correct:</label></small>
                            <input type="text" class="form-control form-control-sm input-certncorrectname text-center" name="certncorrectname" value="{{($levelsemsyfilter != false ? $levelsemsyfilter->certncorrectname : '') ?? ''}}"/>
                            <input type="text" class="form-control form-control-sm input-certncorrectdesc text-center" name="certncorrectdesc" value="{{($levelsemsyfilter != false ? $levelsemsyfilter->certncorrectdesc : "SHS-School Record's In-Charge") ?? "SHS-School Record's In-Charge"}}"/>
                        </div>
                        <div class="col-md-4 text-right">
                            <small><label>Date Checked (MM/DD/YYYY)</label></small>
                            <input type="date" class="form-control form-control-sm input-datechecked" name="datechecked" value="{{($levelsemsyfilter != false ? $levelsemsyfilter->datechecked : '') ?? ''}}"/>
                            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fa fa-share"></i> Update</button>
                        </div>
                    </div>
                </form>
            </div>
            @endif
        </div>
    @endforeach
@endif

<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
    <h4 class="modal-title">Ready to Upload Records</h4>
    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body" id="container-uploaded-records">
        
    </div>
    <div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
    </div>
    </div>
    
    </div>
    
</div>
<div class="card">
    <div class="card-header bg-info">
        &nbsp;
    </div>
    <div class="card-body" style="font-size: 13px;">
        <div class="row mb-4">
            <div class="col-md-3">Track/Strand Accomplished: </div>
            <div class="col-md-4"><input type="text" class="form-control" id="footerstrandaccomplished" placeholder="Enter text here" value="{{$footer->strandaccomplished}}"/></div>
            <div class="col-md-3">SHS General Average: </div>
            <div class="col-md-2"><input type="number" class="form-control" id="footergenave" value="{{$footer->shsgenave}}"/></div>
        </div>
        <div class="row mb-4">
            <div class="col-md-8">
                <label>Awards/Honors Received:</label><br/>
                <textarea id="footerhonorsreceived" class="form-control">{{$footer->honorsreceived}}</textarea>
            </div>
            <div class="col-md-4">
                <label>Date of SHS Garduation:</label><br/>
                <input type="date" class="form-control" id="footerdategrad" value="{{$footer->shsgraduationdateshow}}"/>
            </div>
        </div>
        
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hcb')
            <div class="row mb-5">
                <div class="col-md-2">Certified by: </div>
                <div class="col-md-4"><input type="text" class="form-control" id="footercertifiedby" value="{{$footer->certifiedby}}"/></div>
                <div class="col-md-2">Date Certified: </div>
                <div class="col-md-4"><input type="date" class="form-control" id="footerdatecertified" value="{{$footer->datecertifiedshow}}"/></div>
            </div>
        @endif
        <div class="row mb-5">
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb')
                <div class="col-md-2">REMARKS: </div>
                <div class="col-md-4">
                    <table>
                        <tr>
                            <td style="border-bottom: 1px solid black;">
                                <input type="text" class="form-control" id="footercopyforupper" placeholder="Enter text here" value="{{$footer->copyforupper}}"/>
                            </td>
                        </tr>
                        
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hcb')
                            <tr>
                                <td>
                                    <input type="text" class="form-control" id="footercopyforlower" placeholder="Enter text here" value="{{$footer->copyforlower}}"/>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            @else
                <div class="col-md-2">COPY FOR: </div>
                <div class="col-md-4">
                    <table>
                        <tr>
                            <td style="border-bottom: 1px solid black;">
                                <input type="text" class="form-control" id="footercopyforupper" placeholder="Enter text here" value="{{$footer->copyforupper}}"/>
                            </td>
                        </tr>
                        
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hcb')
                            <tr>
                                <td>
                                    <input type="text" class="form-control" id="footercopyforlower" placeholder="Enter text here" value="{{$footer->copyforlower}}"/>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            @endif
            <div class="col-md-6 text-right d-block">
                <label>&nbsp;</label><br/>
                <button type="button" class="btn btn-primary" id="btn-savefooter"><i class="fa fa-share"></i> Save Changes</button>
            </div>
        </div>
        {{-- <div class="row mb-5">
        </div> --}}
    </div>
</div>
<script>    

    var completerhs = $('#checkbox-completerhs').val()
    var completerjh = $('#checkbox-completerjh').val()
    var peptpasser = $('#checkbox-peptpasser').val()
    var alspasser = $('#checkbox-alspasser').val()

    $('#checkbox-completerhs').change(function(){
                if($(this).prop('checked'))
                {
                    $(this).val('1')
                    completerhs = 1;
                }else{
                    $(this).val()
                    completerhs = 0;
                }
            })

            $('#checkbox-completerjh').change(function(){
                if($(this).prop('checked'))
                {
                    $(this).val('1')
                    completerjh = 1;
                }else{
                    $(this).val()
                    completerjh = 0;
                }
            })
            $('#checkbox-peptpasser').change(function(){
                if($(this).prop('checked'))
                {
                    $(this).val('1')
                    peptpasser = 1;
                }else{
                    $(this).val()
                    peptpasser = 0;
                }
            })
            $('#checkbox-alspasser').change(function(){
                if($(this).prop('checked'))
                {
                    $(this).val('1')
                    alspasser = 1;
                }else{
                    $(this).val()
                    alspasser = 0;
                }
            })
    $('#btn-eligibility-update').on('click', function(){
        var generalaveragehs = $('#generalaveragehs').val()
        var generalaveragejh = $('#generalaveragejh').val()
        var graduationdate = $('#graduationdate').val()

        var schoolname = $('#schoolname').val();
        var schooladdress = $('#schooladdress').val();
        var peptrating = $('#peptrating').val();
        var alsrating = $('#alsrating').val();
        var specify = $('#specify').val();
        var examdate = $('#examdate').val();
        var centername = $('#centername').val();

        var dateshsadmission = $('#input-dateshsadmission').val();
        
        $.ajax({
            url: '/reports_schoolform10/updateeligibility',
            type: 'GET',
            data:{
                studentid           : '{{$studentid}}',
                acadprogid          : '{{$acadprogid}}',
                completerhs         :   completerhs,
                completerjh         :   completerjh,
                generalaveragehs    :   generalaveragehs,
                generalaveragejh    :   generalaveragejh,
                graduationdate      :   graduationdate,
                peptpasser          :   peptpasser,
                alspasser           :   alspasser,
                peptrating          :   peptrating,
                alsrating           :   alsrating,
                schoolname          :   schoolname,
                schooladdress       :   schooladdress,
                examdate            :   examdate,
                others              :   specify,
                dateshsadmission    :   dateshsadmission,
                centername          :   centername
            }, success:function(data)
            {

                            toastr.success('Updated successfully!','Eligibility')
            }
        });
    })
    // var rowcount = 0;
    $('.btn-addrow').on('click', function(){
        var thistbody = $(this).closest('table').find('.gradescontainer');
        thistbody.append(
            '<tr class="eachsubject">'+
                '<td style="vertical-align: middle;"><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjcode" Indicate/></td>'+
                '<td><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="0"/><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjdesc" placeholder="Subject"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q1 text-center"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q2 text-center"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-finalgrade text-center" placeholder="Final"/></td>'+
                '<td><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-remarks" placeholder="Remarks"/></td>'+
                // '<td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord new-input input-credits text-center" placeholder="Credits"/></td>'+
                '<td><button type="button" class="btn btn-sm btn-block btn-default btn-deleterow" style="height: unset;">&nbsp;<i class="fa fa-trash-alt"></i>&nbsp;</button></td>'+
            '</tr>'
        )
        // if(rowcount == 0)
        // {
        //     $(this).closest('.card-body').find('tfoot').prop('hidden',true);
        // }else{
        //     $(this).closest('.card-body').find('tfoot').removeAttr('hidden');
        // }
    })
    $(document).on('click','.btn-deleterow', function(){
        $(this).closest('tr').remove()
    })
    $(document).on('input','.new-input', function(){
        // gradescontainer
        var subjcodeinputs = $(this).closest('.eachsubject').find('.input-subjcode');
        var subjdescinputs = $(this).closest('.eachsubject').find('.input-subjdesc');
        var q1inputs = $(this).closest('.eachsubject').find('.input-q1');
        var q2inputs = $(this).closest('.eachsubject').find('.input-q2');
        var q3inputs = $(this).closest('.eachsubject').find('.input-q3');
        var q4inputs = $(this).closest('.eachsubject').find('.input-q4');
        var inputwithval = 0;
        subjcodeinputs.each(function(){
            if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
            {
                inputwithval+=1;
            }
        })
        subjdescinputs.each(function(){
            if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
            {
                inputwithval+=1;
            }
        })
        q1inputs.each(function(){
            if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
            {
                inputwithval+=1;
            }
        })
        q2inputs.each(function(){
            if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
            {
                inputwithval+=1;
            }
        })
        q3inputs.each(function(){
            if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
            {
                inputwithval+=1;
            }
        })
        q4inputs.each(function(){
            if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
            {
                inputwithval+=1;
            }
        })
        if(inputwithval == 0)
        {
            $(this).closest('.card-body').find('.tfoot').prop('hidden',true);
        }else{
            $(this).closest('.card-body').find('.tfoot').removeAttr('hidden');
        }
    })
    $('.btn-updaterecord').on('click', function(){
        var studentid = $('#select-studentid').val();
        var acadprogid = $('#select-acadprogid').val();
        var recordid = $(this).attr('data-id')
        var thistrs = $(this).closest('.card-body').find('tr.eachsubject');
        var eachsubjects = [];
        thistrs.each(function(){
            var subjid    = $(this).find('.input-subjid').val();
            var subjcode    = $(this).find('.input-subjcode').val();
            var subjdesc    = $(this).find('.input-subjdesc').val();
            var q1          = $(this).find('.input-q1').val();
            var q2          = $(this).find('.input-q2').val();
            var finalgrade  = $(this).find('.input-finalgrade').val();
            var remarks     = $(this).find('.input-remarks').val();
            var credits     = $(this).find('.input-credits').val();
            var indentsubj = 0;
            if(subjdesc.replace(/^\s+|\s+$/g, "").length > 0)
            {
                if(subjdesc.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    subjdesc = " ";
                }
                if(q1.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    q1 = 0;
                }
                if(q2.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    q2 = 0;
                }
                if(finalgrade.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    finalgrade = 0;
                }
                if(remarks.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    remarks = "";
                }

                obj = {
                    subjcode      : subjcode,
                    id      : subjid,
                    subjdesc      : subjdesc,
                    q1              : q1,
                    q2          : q2,
                    final      : finalgrade,
                    remarks      : remarks,
                    credits      : credits,
                    fromsystem   : 0,
                    editablegrades   : 0,
                    inmapeh   : 0,
                    intle   : 0
                };
                eachsubjects.push(obj);
            }
        })
        
        if(eachsubjects.length == 0)
        {
            toastr.warning('No Subjects detected!')
        }else{
            
        $.ajax({
            url: '/reports_schoolform10v2/updategrades',
            type: 'GET',
            data:{
                studentid           : studentid,
                acadprogid          : acadprogid,
                id          :   recordid,
                subjects            :   JSON.stringify(eachsubjects)

            }, success:function(data)
            {
                
                $('#btn-getrecords').click()
            }
        });
        }
    })
    $('.btn-deletesubject').on('click', function(){
        var id = $(this).attr('data-id')
        var thisrow = $(this).closest('tr');
        var studentid = $('#select-studentid').val();
        var acadprogid = $('#select-acadprogid').val();
        Swal.fire({
            title: 'Are you sure you want to delete this?',
            html: 'You won\'t be able to revert this!',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading()
        }).then((reasoninput) => {
            if (reasoninput.value) {
                $.ajax({
                    url: '/reports_schoolform10v2/deletesubject',
                    type: 'GET',
                    data: {
                        action          : 'subject',
                        id              : id,
                        acadprogid      : acadprogid
                    },
                    success:function(data){
                        if(data == 1)
                        {
                            toastr.success('Deleted successfully!')
                            thisrow.remove()

                        }else{
                            toastr.error('Something went wrong!')
                        }
                    }
                })
            }
        })
    })
    $('.btn-updatesubject').on('click', function(){
        var id = $(this).attr('data-id')
        var studentid = $('#select-studentid').val();
        var acadprogid = $('#select-acadprogid').val();
        var subjid    = $(this).closest('tr').find('.input-subjid').val();
        var subjcode    = $(this).closest('tr').find('.input-subjcode').val();
        var subjdesc    = $(this).closest('tr').find('.input-subjdesc').val();
        var q1          = $(this).closest('tr').find('.input-q1').val();
        var q2          = $(this).closest('tr').find('.input-q2').val();
        var finalgrade  = $(this).closest('tr').find('.input-finalgrade').val();
        var remarks     = $(this).closest('tr').find('.input-remarks').val();
        // var credits     = $(this).closest('tr').find('.input-credits').val();
        var indentsubj = 0;
        if(subjdesc.replace(/^\s+|\s+$/g, "").length > 0)
        {
            if(subjdesc.replace(/^\s+|\s+$/g, "").length == 0)
            {
                subjdesc = " ";
            }
            if(q1.replace(/^\s+|\s+$/g, "").length == 0)
            {
                q1 = 0;
            }
            if(q2.replace(/^\s+|\s+$/g, "").length == 0)
            {
                q2 = 0;
            }
            if(finalgrade.replace(/^\s+|\s+$/g, "").length == 0)
            {
                finalgrade = 0;
            }
            if(remarks.replace(/^\s+|\s+$/g, "").length == 0)
            {
                remarks = "";
            }
        }
        $.ajax({
            url: '/reports_schoolform10v2/updatesubject',
            type: 'GET',
            data: {
                id              : id,
                acadprogid      : acadprogid,
                indentsubj      : indentsubj,
                id      : subjid,
                subjcode      : subjcode,
                subjdesc      : subjdesc,
                q1              : q1,
                q2          : q2,
                final      : finalgrade,
                remarks      : remarks,
                // credits      : credits,
                fromsystem   : 0,
                editablegrades   : 0,
                inmapeh   : 0,
                intle   : 0
            },
            success:function(data){
                if(data == 1)
                {
                    toastr.success('Updated successfully!')

                }else{
                    toastr.error('Something went wrong!')
                }
            }
        })
    })
    $('.badge-clear-record').on('click', function(){
        var id = $(this).attr('data-id')
        Swal.fire({
            title: 'Are you sure you want to clear this record?',
            html: 'You won\'t be able to revert this!',
            showCancelButton: true,
            confirmButtonText: 'Clear',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading()
        }).then((reasoninput) => {
            if (reasoninput.value) {
                $.ajax({
                    url: '/reports_schoolform10/deleterecord',
                    type: 'GET',
                    data: {
                        // action          : 'record',
                        id              : id,
                        acadprogid      : 4
                    },
                    success:function(data){
                        if(data == 1)
                        {
                            toastr.success('Deleted successfully!')
                            $('#btn-getrecords').click();
                            $('#btn-getrecords').click();

                        }else{
                            toastr.error('Something went wrong!')
                        }
                    }
                })
            }
        })
    })
    $( '#upload_sf1' )
    .submit( function( e ) {

        
        // if($('.column').val() == ""){
        //         Toast.fire({
        //             type: 'warning',
        //             title: 'Empty column'
        //         })
        //         return false;
        // }

        // if($('#input_sf1').val() == ""){
        //         Toast.fire({
        //             type: 'warning',
        //             title: 'No File'
        //         })
        //         return false;
        // }

        var inputs = new FormData(this)

        inputs.append('acadprogid','{{$acadprogid}}')
        // // inputs.append('name',$('#name').val())
        // // inputs.append('gender',$('#gender').val())
        // // inputs.append('dob',$('#dob').val())
        // // inputs.append('street',$('#street').val())
        // // inputs.append('barangay',$('#barangay').val())
        // // inputs.append('city',$('#city').val())
        // // inputs.append('province',$('#province').val())
        // // inputs.append('fname',$('#fname').val())
        // // inputs.append('mname',$('#mname').val())
        // // inputs.append('gname',$('#gname').val())
        // // inputs.append('grelation',$('#grelation').val())
        // // inputs.append('contact',$('#contact').val())

        $.ajax({
                // xhr: function() {
                //     var xhr = new window.XMLHttpRequest();

                //     xhr.upload.addEventListener("progress", function(evt) {
                //     if (evt.lengthComputable) {
                //             var percentComplete = evt.loaded / evt.total;
                //             percentComplete = parseInt(percentComplete * 100);

                //             $('.progress-bar').width(percentComplete+'%');
                //             $('.progress-bar').html(percentComplete+'%');
                //             console.log(percentComplete)
                //             }
                //     }, false);

                    

                //     return xhr;
                // },
                url: '/reports_schoolform10v2/fileupload',
                type: 'POST',
                data: inputs,
                processData: false,
                contentType: false,
                success:function(data) {
                    $('#container-uploaded-records').empty()
                    $('#container-uploaded-records').append(data)
                    $('#modal-lg').modal('show')
                }
            
                ,error:function(){
                    toastr.error('Something went wrong! PLease make sure it is the correct file!')
                }
        })
        e.preventDefault();
    })
            $('#btn-savefooter').on('click', function(){
                var footerstrandaccomplished    = $('#footerstrandaccomplished').val(); 
                var footergenave                = $('#footergenave').val();
                var footerhonorsreceived        = $('#footerhonorsreceived').val(); 
                var footerdategrad              = $('#footerdategrad').val();
                var footercertifiedby         = $('#footercertifiedby').val();
                var footerdatecertified         = $('#footerdatecertified').val();
                var footercopyforupper          = $('#footercopyforupper').val();
                var footercopyforlower          = $('#footercopyforlower').val();
                

                $.ajax({
                    url: '/reports_schoolform10/submitfooter',
                    type:"GET",
                    dataType:"json",
                    data:{
                        studentid               :   '{{$studentid}}',
                        acadprogid              : '{{$acadprogid}}',
                        footerstrandaccomplished: footerstrandaccomplished,
                        footergenave            : footergenave,
                        footerhonorsreceived    : footerhonorsreceived,
                        footerdategrad          : footerdategrad,
                        footerdatecertified     : footerdatecertified,
                        footercertifiedby       : footercertifiedby,
                        footercopyforupper      : footercopyforupper,
                        footercopyforlower      : footercopyforlower
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    complete: function(){

                                Toast.fire({
                                    type: 'success',
                                    title: 'Form Footer updated successfully!'
                                })                              

                    }
                })
            })
        $('.form-eachsem').submit( function( e ) {

            var formdata = new FormData(this);
            formdata.append('studentid', '{{$studentid}}');
            formdata.append('levelid', $(this).attr('data-levelid'));
            formdata.append('semid', $(this).attr('data-semid'));
            formdata.append('sydesc', $(this).attr('data-sydesc'));
            $.ajax({
                url: '/schoolform10/updatesigneachsem',
                type:'POST',
                data: formdata,
                dataType: 'json',
                processData: false,
                contentType: false,
                success:function(data) {
                    toastr.success('Updated successfully!')
                }
            })
            e.preventDefault();

        })
</script>