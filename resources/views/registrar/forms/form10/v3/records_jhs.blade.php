<style>
    table td, table th{
        padding: 0px !important;
    }
    .input-norecord{
        height: unset;
    }
</style>

<div class="row mb-2">
    <div class="col-md-12 text-right">
        <div class="form-inline" style="display: unset !important;">
            <div class="btn-group">
                <select class="form-control form-control-sm" id="select-papersize">
                    <option value="8.3in 11.7in">A4</option>
                    <option value="8.5in 14in">Legal</option>
                    <option value="8.5in 13in">Long</option>
                    <option value="8.5in 11in">Short</option>
                </select>
            </div>

        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Export Form
            </button>
            <div class="dropdown-menu dropdown-menu-right" style="font-size: 14px;">
            <button class="btn-exportform-jhs dropdown-item" data-exporttype="pdf" data-format="deped" data-layout="1"><i class="fa fa-file-pdf"></i> &nbsp;Deped (Template 1)</button>
            <button class="btn-exportform-jhs dropdown-item" data-exporttype="pdf" data-format="deped" data-layout="2"><i class="fa fa-file-pdf"></i> &nbsp;Deped (Template 1 - with school logo)</button>
            <button class="btn-exportform-jhs dropdown-item" data-exporttype="pdf" data-format="deped-2" data-layout="1"><i class="fa fa-file-pdf"></i> &nbsp;Deped (Template 2 - with school logo)</button>
            @if($acadprogid == 5)
            <button class="btn-exportform-jhs dropdown-item" data-exporttype="pdf" data-format="depedspr" data-layout="1"><i class="fa fa-file-pdf"></i> &nbsp;Deped - SPR (Template 3)</button>
            @endif
            <button class="btn-exportform-jhs dropdown-item" @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')data-exporttype="excel"@else data-exporttype="pdf"@endif data-format="school" data-layout="1"><i class="fa fa-file-pdf"></i> &nbsp;School Template @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')1 @endif<br/><small><em class="text-danger">Note: Please provide the school's own template</em></small></button>
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
            <button class="btn-exportform-jhs dropdown-item" @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')data-exporttype="excel"@else data-exporttype="pdf"@endif data-format="school" data-layout="2"><i class="fa fa-file-pdf"></i> &nbsp;School Template 2<br/><small><em class="text-danger">Note: Please provide the school's own template</em></small></button>
            @endif
            </div>
        </div>
        </div>
        
    </div>
</div>
<div class="row">
    <div class="col-12">
    
        <div class="card">
            <div class="card-header d-flex p-0">
                <ul class="nav nav-pills ml-auto p-2" style="font-size: 14px;">
                    <li class="nav-item"><a class="nav-link active" href="#tab_eligibility" data-toggle="tab">Eligibility</a></li>
                    @foreach ($gradelevels as $key=>$gradelevel)
                    <li class="nav-item"><a class="nav-link {{--@if($key == 0) active @endif--}}" href="#tab_{{$key}}" data-toggle="tab">{{$gradelevel->levelname}}</a></li>
                    @endforeach
                    <li class="nav-item"><a class="nav-link" href="#tab_certification" data-toggle="tab">Certification</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_eligibility"> 
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                            <div class="row mb-2">
                                <div class="col-md-7">
                                    <label>Intermediate course Completed (School)</label>
                                    <input type="text" class="form-control form-control-sm" name="input-courseschool" id="input-courseschool" value="{{$eligibility->courseschool ?? ''}}"/>
                                </div>
                                <div class="col-md-2">
                                    <label>Year</label>
                                    <input type="text" class="form-control form-control-sm" name="input-courseyear" id="input-courseyear" value="{{$eligibility->courseyear ?? ''}}"/>
                                </div>
                                <div class="col-md-3">
                                    <label>General Average</label>
                                    <input type="number" class="form-control form-control-sm" name="input-coursegenave" id="input-coursegenave" value="{{$eligibility->coursegenave ?? ''}}"/>
                                </div>
                            </div>
                        @endif
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xai')
                            <div class="row m-0 p-0">
                                <div class="col-6">
                                    <small class="m-0">Name of Elementary School:</small>
                                     <input type="text" class="form-control form-control-sm m-0" id="schoolname" value="{{$eligibility->schoolname}}"/>
                                </div>
                                <div class="col-md-6">
                                    <small class="m-0">Address of Parent/Guardian:</small>
                                    <input type="text" class="form-control form-control-sm m-0" value="{{$eligibility->guardianaddress}}" id="input-guardianaddress"/>
                                </div>
                                <div class="col-md-3">
                                    <small class="m-0">School Year Graduated:</small>
                                    <input type="text" class="form-control form-control-sm m-0" value="{{$eligibility->sygraduated}}" id="input-sygraduated" placeholder="Ex. 2019-2020"/>
                                </div>
                                <div class="col-md-7">
                                    <small class="m-0">Total No. of Years in school to complete Elementary Education:</small>
                                    <input type="text" class="form-control form-control-sm m-0" value="{{$eligibility->totalnoofyears}}" id="input-totalnoofyears"/>
                                </div>
                                <div class="col-2">
                                    <small class="m-0">General Average:</small><input id="generalaverage" type="number" class="form-control form-control-sm" value="{{$eligibility->genave}}"/>
                                </div>
                            </div>
                        @else
                        <div class="row">
                            <div class="col-12 bg-gray text-center mb-2">
                                <h6>ELIGIBILITY FOR JHS ENROLMENT</h6>
                            </div>
                        </div>
                        <div class="row p-1" style="font-size: 12px; border: 1px solid black;">
                            <div class="col-4">
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                      <input type="checkbox"  id="checkbox-completer" value="{{$eligibility->completer}}" @if($eligibility->completer == 1) checked="" @endif>
                                      <label for="checkbox-completer">
                                          Elementary School Completer
                                      </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <label>General Average:</label> &nbsp; <input id="generalaverage" type="number" value="{{$eligibility->genave}}"/>
                            </div>
                            <div class="col-12">
                                <label>Citation: (If Any)</label> &nbsp; <textarea class="form-control" id="citation">{{$eligibility->citation}}</textarea>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-4">
                                Name of Elementary School: <input type="text" class="form-control" id="schoolname" value="{{$eligibility->schoolname}}"/>
                            </div>
                            <div class="col-4">
                                School ID: <input type="text" class="form-control" id="schoolid" value="{{$eligibility->schoolid}}"/>
                            </div>
                            <div class="col-4">
                                Address of School: <input type="text" class="form-control" id="schooladdress" value="{{$eligibility->schooladdress}}"/>
                            </div>
                            <div class="col-4">
                                <small class="m-0">School Year Graduated:</small>
                                <input type="text" class="form-control form-control-sm m-0" value="{{$eligibility->sygraduated}}" id="input-sygraduated" placeholder="Ex. 2019-2020"/>
                            </div>
                            <div class="col-4">
                                <small class="m-0">Total No. of Years in school to complete Elementary Education:</small>
                                <input type="text" class="form-control form-control-sm m-0" value="{{$eligibility->totalnoofyears}}" id="input-totalnoofyears"/>
                            </div>
                        </div>
                        <div class="row" style="font-size: 12px;">
                            <div class="col-12">
                                Other Credential Presented
                            </div>
                            <div class="col-4">
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
                            <div class="col-4">
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
                            <div class="col-4">
                                Other (Pls.Specify)
                                <textarea class="form-control" id="specify">{{$eligibility->specifyothers}}</textarea>
                            </div>
                        </div>
                        <div class="row mt-2" style="font-size: 12px;position: relative;">
                            <div class="col-3">
                                Date of Examination/Assessment (mm/dd/yyyy):
                            </div>
                            <div class="col-3"><input type="date" id="examdate" class="form-control form-control-sm" value="{{$eligibility->examdate}}"/>
                            </div>
                            <div class="col-3"><span style="position: absolute;bottom: 0;">Name and Address of Testing Center:</span></div>
                            <div class="col-3"><input type="text" id="centername" class="form-control form-control-sm" value="{{$eligibility->centername}}"/></div>
                        </div>
                        @endif
                        <div class="row mt-1">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-sm btn-primary" id="btn-eligibility-update-jhs"><i class="fa fa-edit"></i> Update</button>
                            </div>
                        </div>
                    </div>
                    @foreach ($gradelevels as $key=>$gradelevel)
                        <div class="tab-pane  {{--@if($key == 0) active @endif--}}" id="tab_{{$key}}">
                            
                            @if(collect($records)->where('levelid', $gradelevel->id)->count() == 0)
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table m-0" style="font-size: 12px; table-layout: fixed;">
                                            <tr>
                                                <td style="width: 15%;">School</td>
                                                <td colspan="5" style=""><input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolname"/></td>
                                            </tr>
                                            <tr>
                                                <td>School ID</td>
                                                <td colspan="5"><input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolid"/></td>
                                            </tr>
                                            <tr>
                                                <td>District</td>
                                                <td><input type="text" class="form-control form-control-sm p-0 input-norecord input-district"/></td>
                                                <td>Division</td>
                                                <td><input type="text" class="form-control form-control-sm p-0 input-norecord input-division"/></td>
                                                <td>Region</td>
                                                <td ><input type="text" class="form-control form-control-sm p-0 input-norecord input-region"/></td>
                                            </tr>
                                            <tr>
                                                <td>Grade Level</td>
                                                <td colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-levelid" data-id="{{$gradelevel->id}}" value="{{$gradelevel->levelname}}" readonly/></td>
                                                <td>School Year</td>
                                                <td style="width: 15%; "><input type="text" class="form-control form-control-sm p-0 input-norecord input-sydesc"/></td>
                                            </tr>
                                            <tr>
                                                <td>Section</td>
                                                <td colspan="2"><input type="text" class="form-control form-control-sm p-0 input-norecord input-sectionname"/></td>
                                                <td>Adviser</td>
                                                <td style="" colspan="2"><input type="text" class="form-control form-control-sm p-0 input-norecord input-adviser"/></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        @php
                                            $defaultsubjects = collect($gradelevel->subjects)->values();
                                        @endphp
                                        <table class="table table-striped" style="font-size: 11px; table-layout: fixed;">
                                            <thead class="text-center">
                                                <tr>
                                                    <th>Indent</th>
                                                    <th style="width: 30%;">Subjects</th>
                                                    <th>1st</th>
                                                    <th>2nd</th>
                                                    <th>3rd</th>
                                                    <th>4th</th>
                                                    <th style="width: 8%;">Final</th>
                                                    <th style="width: 15%;">Remarks</th>
                                                    <th style="width: 8%;">Credit Earned</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <td colspan="11" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                                </tr>
                                            </thead>
                                            <tbody class="gradescontainer">
                                                @if(count($defaultsubjects)>0)
                                                    @foreach($defaultsubjects as $defaultsubject)
                                                        <tr class="eachsubject">
                                                            <td><input type="checkbox" class="form-control" style="width: 20px;height: 20px;"></td>
                                                            <td><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="0"/><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjdesc" placeholder="Subject" value="{{$defaultsubject->subjdesc}}"/></td>
                                                            <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q1"/></td>
                                                            <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q2"/></td>
                                                            <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q3"/></td>
                                                            <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q4"/></td>
                                                            <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-finalgrade" placeholder="Final"/></td>
                                                            <td><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-remarks" placeholder="Remarks"/></td>
                                                            <td><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-credits" placeholder="Credits"/></td>
                                                            <td colspan="2"><button type="button" class="btn btn-sm btn-block btn-default p-0 btn-deleterow"><small><i class="fa fa-trash-alt"></i></small></button></td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
                                    <div class="col-md-12">
                                        
                                    </div>
                                    @else
                                    <div class="col-md-12">
                                        <table class="table" style="width: 100%; font-size: 11px;">
                                            <tr>
                                                <th style="width: 20% !important;" class="text-right">Remarks:</th>
                                                <td colspan="3" style="width: 85% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-semremarks"/></td>
                                            </tr>
                                            <tr>
                                                <th style="width: 20% !important;" class="text-right">Record's In-charge:</th>
                                                <td style="width: 40% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-incharge"/></td>
                                                <th style="width: 20% !important;" class="text-right">Date Checked:</th>
                                                <td style="width: 20% !important;"><input type="date" class="form-control form-control-sm p-0 input-norecord input-datechecked"/></td>
                                            </tr>
                                        </table>
                                    </div>
                                    @endif --}}
                                    <div class="col-md-12 text-right">
                                        <button type="button" class="btn btn-sm btn-success btn-saverecord"><i class="fa fa-share"></i> Save this record</button>
                                    </div>
                                </div>
                                
                            @else
                            @php
                                $eachrecord = collect($records)->where('levelid', $gradelevel->id)->first();
                                $grades = collect($records)->where('levelid', $gradelevel->id)->first()->grades;
                                $subjects = collect($gradelevel->subjects)->where('sydesc',$eachrecord->sydesc)->all();
                            @endphp
                                <div class="row">
                                    @if($eachrecord->type == 1)
                                        <div class="col-md-6"><span class="badge badge-warning">Auto Generated: You cannot revise this record</span> @if($eachrecord->sydesc == DB::table('sy')->where('isactive','1')->first()->sydesc)<span class="badge badge-success">Current School Year</span>@endif</div>
                                    @else
                                        <div class="col-md-6"><span class="badge badge-success">Manual</span> @if($eachrecord->syid == DB::table('sy')->where('isactive','1')->first()->id)<span class="badge badge-success">Current School Year</span>@endif</div>
                                        @if($eachrecord->type==2)
                                            <div class="col-md-6 text-right">
                                                <span class="badge badge-warning badge-clear-record" style="cursor: pointer;" data-id="{{$eachrecord->id}}">Clear This Record</span>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="col-md-12">
                                        <table class="table m-0" style="font-size: 12px;">
                                            <tr>
                                                <td style="width: 15%;">School</td>
                                                <td colspan="5" style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schoolname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolname" value="{{$eachrecord->schoolname}}"/>@endif </td>
                                            </tr>
                                            <tr>
                                                <td>School ID</td>
                                                <td colspan="5" style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schoolid}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolid" value="{{$eachrecord->schoolid}}"/>@endif</td>
                                            </tr>
                                            <tr>
                                                <td>District</td>
                                                <td style="width: 20%; border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schooldistrict}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-district" value="{{$eachrecord->schooldistrict}}"/>@endif</td>
                                                <td>Division</td>
                                                <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schooldivision}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-division" value="{{$eachrecord->schooldivision}}"/>@endif</td>
                                                <td>Region</td>
                                                <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schoolregion}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-region" value="{{$eachrecord->schoolregion}}"/>@endif</td>
                                            </tr>
                                            <tr>
                                                <td>Grade Level</td>
                                                <td style="border-bottom: 1px solid black;" colspan="3">@if($eachrecord->type == 1){{$eachrecord->levelname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-levelid" data-id="{{$gradelevel->id}}" value="{{$gradelevel->levelname}}" readonly/>@endif</td>
                                                <td>School Year</td>
                                                <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->sydesc}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-sydesc" value="{{$eachrecord->sydesc}}"/>@endif</td>
                                            </tr>
                                        </table>
                                        <table class="table m-0" style="font-size: 11px;">
                                            <tr>
                                                <td style="width: 15%;">Section</td>
                                                <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->sectionname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-sectionname" value="{{$eachrecord->sectionname}}"/>@endif</td>
                                                <td>Adviser</td>
                                                <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->teachername}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-adviser" value="{{$eachrecord->teachername}}"/>@endif</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        @if(count($grades) == 0)
                                            @if($eachrecord->type == 1)
                                            <table class="table table-striped" style="font-size: 12px;">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th style="width: 30%;">Subjects</th>
                                                        <th>1st</th>
                                                        <th>2nd</th>
                                                        <th>3rd</th>
                                                        <th>4th</th>
                                                        <th style="width: 8%;">Final</th>
                                                        <th style="width: 15%;">Remarks</th>
                                                        <th style="width: 8%;">Credit Earned</th>
                                                        @if($eachrecord->type == 1)
                                                        @else
                                                        <th colspan="2">Delete</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                @if($eachrecord->type == 1)
                                                @else                         
                                                <tr>
                                                    <td colspan="11" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                                </tr>
                                                @endif 
                                                <tbody class="gradescontainer">    
                                                </tbody>
                                            </table>
                                            @else
                                            <table class="table table-striped" style="font-size: 12px;">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th>Indent</th>
                                                        <th style="width: 30%;">Subjects</th>
                                                        <th>1st</th>
                                                        <th>2nd</th>
                                                        <th>3rd</th>
                                                        <th>4th</th>
                                                        <th style="width: 8%;">Final</th>
                                                        <th style="width: 15%;">Remarks</th>
                                                        <th style="width: 8%;">Credit Earned</th>
                                                        <th colspan="2">Delete</th>
                                                    </tr>
                                                </thead>                 
                                                <tr>
                                                    <td colspan="11" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                                </tr>
                                                <tbody class="gradescontainer">    
                                                </tbody>
                                            </table>
                                            @endif

                                        @else
                                            @if($eachrecord->type == 1)
                                            <table class="table table-striped" style="font-size: 12px;">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th style="width: 30%;">Subjects</th>
                                                        <th>1st</th>
                                                        <th>2nd</th>
                                                        <th>3rd</th>
                                                        <th>4th</th>
                                                        <th style="width: 15%;">Final</th>
                                                        <th style="width: 15%;">Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="gradescontainer">     
                                                    @foreach($grades as $grade)
                                                        <tr>
                                                            <td>@if($eachrecord->type == 1){{ucwords(strtolower($grade->subjdesc))}}@else<input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="{{$grade->id}}"/><input type="text" class="form-control form-control-sm p-0 input-norecord input-subjdesc" value="{{$grade->subjdesc}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{$grade->q1}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1" value="{{$grade->q1 > 0 ? $grade->q1 : ''}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{$grade->q2}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2" value="{{$grade->q2 > 0 ? $grade->q2 : ''}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{$grade->q3}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q3" value="{{$grade->q3 > 0 ? $grade->q3 : ''}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{$grade->q4}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q4" value="{{$grade->q4 > 0 ? $grade->q4 : ''}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{$grade->finalrating}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-finalgrade" value="{{$grade->finalrating > 0 ? $grade->finalrating : ''}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{isset($grade->actiontaken) ? $grade->actiontaken : $grade->remarks}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{isset($grade->actiontaken) ? $grade->actiontaken : $grade->remarks}}"/>@endif</td>
                                                        </tr>
                                                    @endforeach  
                                                    @if(count($eachrecord->generalaverage)>0)
                                                        <tr>
                                                            <td>@if($eachrecord->type == 1){{ucwords(strtolower(collect($eachrecord->generalaverage)->first()->subjdesc)) ?? 'General Average'}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-subjdesc" value="{{collect($eachrecord->generalaverage)->first()->subjdesc ?? 'General Average'}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{collect($eachrecord->generalaverage)->first()->q1 ?? ''}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1" value="{{collect($eachrecord->generalaverage)->first()->q1 ?? ''}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{collect($eachrecord->generalaverage)->first()->q2 ?? ''}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2" value="{{collect($eachrecord->generalaverage)->first()->q2 ?? ''}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{collect($eachrecord->generalaverage)->first()->q3 ?? ''}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1" value="{{collect($eachrecord->generalaverage)->first()->q3 ?? ''}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{collect($eachrecord->generalaverage)->first()->q4 ?? ''}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2" value="{{collect($eachrecord->generalaverage)->first()->q4 ?? ''}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{collect($eachrecord->generalaverage)->first()->finalrating ?? ''}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-finalgrade" value="{{collect($eachrecord->generalaverage)->first()->finalrating ?? ''}}"/>@endif</td>
                                                            <td class="text-center">
                                                                @if($eachrecord->type == 1)
                                                                    @if(isset(collect($eachrecord->generalaverage)->first()->actiontaken) || isset(collect($eachrecord->generalaverage)->first()->remarks))
                                                                    {{ collect($eachrecord->generalaverage)->first()->actiontaken ?? collect($eachrecord->generalaverage)->first()->remarks}}
                                                                    @endif
                                                                    @if(collect($eachrecord->generalaverage)->contains('actiontaken'))
                                                                        <input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{collect($eachrecord->generalaverage)->first()->actiontaken}}"/>
                                                                    @else
                                                                        @if(collect($eachrecord->generalaverage)->contains('remarks'))
                                                                            <input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{collect($eachrecord->generalaverage)->first()->remarks}}"/>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @if(isset(collect($eachrecord->generalaverage)->first()->actiontaken) || isset(collect($eachrecord->generalaverage)->first()->remarks))                                                        
                                                                        <input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{ collect($eachrecord->generalaverage)->first()->actiontaken ?? collect($eachrecord->generalaverage)->first()->remarks}}"/>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            @if($eachrecord->type == 1)
                                                            @else
                                                            <th colspan="2"> <button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-default btn-deletesubject text-sm" data-id="{{$grade->id}}"><i class="fa fa-trash-alt"></i></button></th>
                                                            @endif
                                                        </tr>
                                                    @endif  
                                                </tbody>
                                            </table>
                                            @else
                                            <table class="table table-striped" style="font-size: 12px;">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th>Indent</th>
                                                        <th style="width: 30%;">Subjects</th>
                                                        <th>1st</th>
                                                        <th>2nd</th>
                                                        <th>3rd</th>
                                                        <th>4th</th>
                                                        <th style="width: 8%;">Final</th>
                                                        <th style="width: 15%;">Remarks</th>
                                                        <th style="width: 8%;">Credit Earned</th>
                                                        @if($eachrecord->type == 1)
                                                        @else
                                                        <th colspan="2">Delete</th>
                                                        @endif
                                                    </tr>
                                                </thead>       
                                                <tr>
                                                    <td colspan="11" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                                </tr> 
                                                <tbody class="gradescontainer">     
                                                    @foreach($grades as $grade)
                                                        <tr class="eachsubject">
                                                            <td><input type="checkbox" class="form-control" id="{{$grade->id}}" style="width: 20px;height: 20px;" @if($grade->inMAPEH == 1) checked @endif></td>
                                                            <td>@if($eachrecord->type == 1){{ucwords(strtolower($grade->subjdesc))}}@else<input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="{{$grade->id}}"/><input type="text" class="form-control form-control-sm p-0 input-norecord input-subjdesc" value="{{$grade->subjdesc}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{$grade->q1}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1" value="{{$grade->q1 > 0 ? $grade->q1 : ''}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{$grade->q2}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2" value="{{$grade->q2 > 0 ? $grade->q2 : ''}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{$grade->q3}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q3" value="{{$grade->q3 > 0 ? $grade->q3 : ''}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{$grade->q4}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q4" value="{{$grade->q4 > 0 ? $grade->q4 : ''}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{$grade->finalrating}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-finalgrade" value="{{$grade->finalrating > 0 ? $grade->finalrating : ''}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1){{isset($grade->actiontaken) ? $grade->actiontaken : $grade->remarks}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{isset($grade->actiontaken) ? $grade->actiontaken : $grade->remarks}}"/>@endif</td>
                                                            <td class="text-center">@if($eachrecord->type == 1) @else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-credits" value="{{isset($grade->credits) ? $grade->credits : 0}}"/>@endif</td>
                                                            @if($eachrecord->type == 1)
                                                            @else
                                                            <th colspan="2"> <button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-default btn-deletesubject text-sm" data-id="{{$grade->id}}"><i class="fa fa-trash-alt"></i></button></th>
                                                            @endif
                                                        </tr>
                                                    @endforeach  
                                                </tbody>
                                            </table>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @if($eachrecord->type == 1)
                                    <div class="row">
                                        {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
                                        <div class="col-md-12">
                                            
                                        </div>
                                        @else
                                        <div class="col-md-12">
                                            <table class="table" style="width: 100%; font-size: 11px;">
                                                <tr>
                                                    <th style="width: 20% !important;" class="text-right">Remarks:</th>
                                                    <td colspan="3" style="width: 80% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-semremarks" value=""/></td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 20% !important;" class="text-right">Record's In-charge:</th>
                                                    <td style="width: 40% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-incharge" value=""/></td>
                                                    <th style="width: 20% !important;" class="text-right">Date Checked:</th>
                                                    <td style="width: 20% !important;"><input type="date" class="form-control form-control-sm p-0 input-norecord input-datechecked" value=""/></td>
                                                </tr>
                                            </table>
                                        </div>
                                        @endif --}}
                                        <div class="col-md-12">
                                            <label>Attendance</label><br/>
                                            {{-- <sup><em>Note: Check the template if there's an Attendance section</em></sup> --}}
                                            <table class="table" style="font-size: 10.5px;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 20%;">Month</th>
                                                        @if(count($eachrecord->attendance) == 0)
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        @else
                                                            @foreach($eachrecord->attendance as $att)
                                                                <th>{{substr($att->monthdesc, 0, 3)}}</th>
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>School days</td>
                                                        @if(count($eachrecord->attendance) == 0)
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        @else
                                                            @foreach($eachrecord->attendance as $att)
                                                                <td>{{$att->days}}</td>
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td>Days present</td>
                                                        @if(count($eachrecord->attendance) == 0)
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        @else
                                                            @foreach($eachrecord->attendance as $att)
                                                                <td>{{$att->present}}</td>
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td>Days absent</td>
                                                        @if(count($eachrecord->attendance) == 0)
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        @else
                                                            @foreach($eachrecord->attendance as $att)
                                                                <td>{{$att->absent}}</td>
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td>Times tardy</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            
                            @else
                                <div class="row">
                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
                                    <div class="col-md-12">
                                        <table class="table" style="width: 100%; font-size: 11px;">
                                            <tr>
                                                <th style="width: 30% !important; vertical-align: bottom;" class="text-right">Has advance credit in&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                <td style="vertical-align: bottom;"><input type="text" class="form-control form-control-sm pb-0 input-norecord input-credit-advance" value="{{$eachrecord->credit_advance}}"/></td>
                                            </tr>
                                            <tr>
                                                <th style="width: 30% !important; vertical-align: bottom;" class="text-right">Lacks credits in&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                <td style="vertical-align: bottom;"><input type="text" class="form-control form-control-sm pb-0  input-norecord input-credit-lacks" value="{{$eachrecord->credit_lack}}"/></td>
                                            </tr>
                                            <tr>
                                                <th style="width: 30% !important; vertical-align: bottom;" class="text-right">Total number of years in school to date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                <td style="vertical-align: bottom;"><input type="number" class="form-control form-control-sm pb-0  input-norecord input-noofyears" value="{{$eachrecord->noofyears}}"/></td>
                                            </tr>
                                        </table>
                                    </div>
                                    {{-- @else
                                    <div class="col-md-12">
                                        <table class="table" style="width: 100%; font-size: 11px;">
                                            <tr>
                                                <th style="width: 20% !important;" class="text-right">Remarks:</th>
                                                <td colspan="3" style="width: 80% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-semremarks" value="{{$eachrecord->remarks}}"/></td>
                                            </tr>
                                            <tr>
                                                <th style="width: 20% !important;" class="text-right">Record's In-charge:</th>
                                                <td style="width: 40% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-incharge" value="{{$eachrecord->recordincharge}}"/></td>
                                                <th style="width: 20% !important;" class="text-right">Date Checked:</th>
                                                <td style="width: 20% !important;"><input type="date" class="form-control form-control-sm p-0 input-norecord input-datechecked" value="{{$eachrecord->datechecked}}"/></td>
                                            </tr>
                                        </table>
                                    </div> --}}
                                    @endif
                                    <div class="col-md-12 text-right">
                                        <button type="button" class="btn btn-sm btn-success btn-updaterecord" data-id="{{$eachrecord->id}}"><i class="fa fa-share"></i> Save changes</button>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Attendance</label><br/>
                                        <sup><em>Note: Check the template if there's an Attendance section</em></sup>
                                        <table class="table mb-1" style="font-size: 10.5px;">
                                            <thead>
                                                <tr>
                                                    <th style="width: 20%;">Month</th>
                                                    @if(count($eachrecord->attendance) == 0)
                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                    @else
                                                        @for($x = 0; $x < 12; $x++)
                                                            @if(isset($eachrecord->attendance[$x]))
                                                            <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month" value="{{$eachrecord->attendance[$x]->monthdesc}}"/></th>
                                                            @else
                                                            <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                            @endif
                                                        @endfor
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td># of school days</td>
                                                    @if(count($eachrecord->attendance) == 0)
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                    @else
                                                        @for($x = 0; $x < 12; $x++)
                                                            @if(isset($eachrecord->attendance[$x]))
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum" value="{{$eachrecord->attendance[$x]->numdays}}"/></td>
                                                            @else
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dschool input-daynum"/></td>
                                                            @endif
                                                        @endfor
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td># of days present</td>
                                                    @if(count($eachrecord->attendance) == 0)
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                    @else
                                                        @for($x = 0; $x < 12; $x++)
                                                            @if(isset($eachrecord->attendance[$x]))
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum" value="{{$eachrecord->attendance[$x]->numdayspresent}}"/></td>
                                                            @else
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dpresent input-daynum"/></td>
                                                            @endif
                                                        @endfor
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td># of days absent</td>
                                                    @if(count($eachrecord->attendance) == 0)
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                    @else
                                                        @for($x = 0; $x < 12; $x++)
                                                            @if(isset($eachrecord->attendance[$x]))
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum" value="{{$eachrecord->attendance[$x]->numdaysabsent}}"/></td>
                                                            @else
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dabsent input-daynum"/></td>
                                                            @endif
                                                        @endfor
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td># of times tardy</td>
                                                    @if(count($eachrecord->attendance) == 0)
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                    @else
                                                        @for($x = 0; $x < 12; $x++)
                                                            @if(isset($eachrecord->attendance[$x]))
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum" value="{{$eachrecord->attendance[$x]->numtimestardy}}"/></td>
                                                            @else
                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                            @endif
                                                        @endfor
                                                    @endif
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 text-right mb-2">
                                        <button type="button" class="btn btn-sm btn-success btn-updateattendance" data-id="{{$eachrecord->id}}"><i class="fa fa-share"></i> Save Attendance</button>
                                    </div>
                                </div>
                            @endif
                            
                            @if($eachrecord)
                            @php
                                $remedials = $eachrecord->remedials;
                                @endphp
                                <fieldset class="form-group border p-2">
                                    <legend class="w-auto m-0">Remedial</legend>
                                    <div class="row">
                                        <div class="col-12">
                                            @if($eachrecord->type == 1)
                                                <span class="badge badge-warning">Auto-Generated</span>
                                                <table class="table" style="font-size: 12px; border: none !important;">
                                                    <thead class="text-center">
                                                        <tr>
                                                            <td colspan="2" class="text-left">
                                                                <label>Conducted from</label>
                                                                @if(collect($remedials)->where('type',2)->count()> 0)
                                                                    @foreach(collect($remedials)->where('type',2)->values() as $remedial)
                                                                    {{$remedial->datefrom}}
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td colspan="2" class="text-left">
                                                                <label>Conducted to</label>
                                                                @if(collect($remedials)->where('type',2)->count()> 0)
                                                                    @foreach(collect($remedials)->where('type',2)->values() as $remedial)
                                                                        {{$remedial->dateto}}
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                        <tr class="text-uppercase">
                                                            <th>Learning Areas</th>
                                                            <th style="width: 10%;">Final Grade</th>
                                                            <th>Remedial Class Mark</th>
                                                            <th style="width: 10%;">Recomputed Final Grade</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(count($remedials)> 0)
                                                            @foreach($remedials as $remedial)
                                                                @if($remedial->type == 1)
                                                                    <tr>
                                                                        <td>{{$remedial->subjectname}}</td>
                                                                        <td>{{$remedial->finalrating}}</td>
                                                                        <td>{{$remedial->remclassmark}}</td>
                                                                        <td>{{$remedial->recomputedfinal}}</td>
                                                                        <td>{{$remedial->remarks}}</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            @for($x = 0; $x <5; $x++)
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                            @endfor
                                                        @endif
                                                    </tbody>
                                                </table>
                                            @else
                                                <span class="badge badge-success">Manual</span>
                                                <table class="table" style="font-size: 12px; border: none !important;">
                                                    <thead class="text-center">
                                                        <tr>
                                                            <td colspan="2" class="text-left">
                                                                <label>Conducted from</label>
                                                                @if(collect($remedials)->where('type',2)->count()> 0)
                                                                    @foreach(collect($remedials)->where('type',2)->values() as $remedial)
                                                                        <input type="date" class="form-control form-control-sm remedial-datefrom" value="{{$remedial->datefrom}}"/>
                                                                    @endforeach
                                                                @else
                                                                    <input type="date" class="form-control form-control-sm remedial-datefrom"/>
                                                                @endif
                                                            </td>
                                                            <td class="text-left">
                                                                <label>Conducted to</label>
                                                                @if(collect($remedials)->where('type',2)->count()> 0)
                                                                    @foreach(collect($remedials)->where('type',2)->values() as $remedial)
                                                                        <input type="date" class="form-control form-control-sm remedial-dateto" value="{{$remedial->dateto}}"/>
                                                                    @endforeach
                                                                @else
                                                                    <input type="date" class="form-control form-control-sm remedial-dateto"/>
                                                                @endif
                                                            </td>
                                                            <th colspan="3" class="text-right">
                                                                <button type="button" class="btn btn-outline-primary btn-sm btn-edit-editremedialheader" data-studid="{{$studinfo->id}}" data-sydesc="{{$eachrecord->sydesc}}"  data-action="header" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-share"></i> Update</button>
                                                            </th>
                                                        </tr>
                                                        <tr class="text-uppercase">
                                                            <th>SUBJECTS</th>
                                                            <th style="width: 10%;">SEM FINAL GRADE</th>
                                                            <th>Remedial Class Mark</th>
                                                            <th style="width: 10%;">Recomputed Final Grade</th>
                                                            <th>Remarks</th>
                                                            <td style="width: 15%;"></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="remedial-tbody">
                                                        @if(collect($remedials)->where('type',1)->count()> 0)
                                                            @foreach(collect($remedials)->where('type',1)->values() as $remedial)
                                                                @if($remedial->type == 1)
                                                                    <tr>
                                                                        <td><input type="text" id="subject{{$remedial->id}}" value="{{$remedial->subjectname}}" class="form-control form-control-sm"/></td>
                                                                        <td><input type="text" id="finalrating{{$remedial->id}}" value="{{$remedial->finalrating}}" class="form-control form-control-sm"/></td>
                                                                        <td><input type="text" id="remclassmark{{$remedial->id}}" value="{{$remedial->remclassmark}}" class="form-control form-control-sm"/></td>
                                                                        <td><input type="text" id="recomputedfinal{{$remedial->id}}" value="{{$remedial->recomputedfinal}}" class="form-control form-control-sm"/></td>
                                                                        <td><input type="text" id="remarks{{$remedial->id}}" value="{{$remedial->remarks}}" class="form-control form-control-sm"/></td>
                                                                        <td class="text-right"><button type="button" class="btn btn-sm btn-outline-success p-1 btn-edit-editremedial" data-id="{{$remedial->id}}"><i class="fa fa-edit"></i> Update</button> <button type="button" class="btn btn-sm btn-outline-danger p-1 btn-edit-deleteremedial" data-id="{{$remedial->id}}" data-studid="{{$studinfo->id}}" data-sydesc="{{$eachrecord->sydesc}}" data-action="delete" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-trash-alt text-danger"></i>&nbsp;</button></td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td style="width: 15%;" class="text-right">
                                                                <button type="button" class="btn btn-default btn-sm btn-block btn-remedial-addrow" data-studid="{{$studinfo->id}}" data-sydesc="{{$eachrecord->sydesc}}" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-plus"></i> Subject</button>
                                                            </td>
                                                            <td colspan="6">
                                                                &nbsp;
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            @endif
                                        </div>
                                    </div>
                                </fieldset>
                            @endif
                            {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sjaes')
                                @php
                                    $thislevelsign = collect($eachlevelsignatories)->where('levelid', $gradelevel->id)->where('sydesc', $eachrecord->sydesc)->first();
                                @endphp

                                <form action="/reports_schoolform10v3/updatesigneachsem" method="POST" class="form-eachlevel" data-levelid="{{$gradelevel->id}}" data-sydesc="{{$eachrecord->sydesc}}">
                                    @csrf
                                    <div class="row eachsem-other-container">
                                        <div class="col-md-12">
                                            <label>Remarks</label>
                                            <textarea class="form-control" name="remarks">@if($thislevelsign){{$thislevelsign->remarks ?? ''}}@endif</textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <small><label>Prepared by:</label></small>
                                            <input class="form-control form-control-sm text-center" value="{{$eachrecord->teachername ?? ''}}"/>
                                            <input class="form-control form-control-sm text-center" value="Signature of Adviser over Printed Name" readonly/>
                                        </div>
                                        <div class="col-md-4">
                                            <small><label>Certified true & Correct:</label></small>
                                            <input type="text" class="form-control form-control-sm input-certncorrectname text-center" name="certncorrectname" @if(isset($eachrecord))value="{{(collect($eachlevelsignatories)->where('levelid', $gradelevel->id)->count() > 0 ? collect($eachlevelsignatories)->where('levelid', $gradelevel->id)->first()->certncorrectname : '') ?? ''}}"@endif/>
                                            <input type="text" class="form-control form-control-sm input-certncorrectdesc text-center" name="certncorrectdesc" @if(isset($eachrecord))value="{{(collect($eachlevelsignatories)->where('levelid', $gradelevel->id)->count() > 0 ? collect($eachlevelsignatories)->where('levelid', $gradelevel->id)->first()->certncorrectdesc : "SHS-School Record's In-Charge") ?? "SHS-School Record's In-Charge"}}"@else value="SHS-School Record's In-Charge" @endif/>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <small><label>Date Checked (MM/DD/YYYY)</label></small>
                                            <input type="date" class="form-control form-control-sm input-datechecked" name="datechecked" @if(isset($eachrecord))value="{{(collect($eachlevelsignatories)->where('levelid', $gradelevel->id)->count() > 0 ? collect($eachlevelsignatories)->where('levelid', $gradelevel->id)->first()->datechecked : '') ?? ''}}"@endif/>
                                            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fa fa-share"></i> Update</button>
                                        </div>
                                    </div>
                                </form>
                            @endif --}}
                        @endif
                    </div>
                    @endforeach  
                    <div class="tab-pane" id="tab_certification">
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hcb' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc' )
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label>Copy sent to / Copy for:</label>
                                <input type="text" class="form-control" id="certcopysentto" value="{{$footer->copysentto}}" placeholder=""/>
                            </div>
                            <div class="col-md-4">
                                <label>Address:</label>
                                <input type="text" class="form-control" id="certaddress" value="{{$footer->address}}" placeholder=""/>
                            </div>
                            <div class="col-md-4 text-right">
                                <label>&nbsp;</label><br/>
                                <button type="button" class="btn btn-primary" id="btn-savefooter"><i class="fa fa-share"></i> Save Changes</button>
                            </div>
                        </div>
                        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes' )
                        
                        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label>Purpose</label>
                                    <input type="text" class="form-control" id="purpose" value="{{$footer->purpose}}" placeholder="Type purposes of the copy here"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label>Records In-charge</label>
                                    <input type="text" class="form-control" id="recordsincharge" value="{{$footer->recordsincharge}}" placeholder="Records In-charge"/>
                                </div>
                                <div class="col-md-6 text-right">
                                    <label>&nbsp;</label><br/>
                                    <button type="button" class="btn btn-primary" id="btn-savefooter"><i class="fa fa-share"></i> Save Changes</button>
                                </div>
                            </div>
                        @else
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label>Purpose/Remarks</label>
                                    <input type="text" class="form-control" id="purpose" value="{{$footer->purpose}}" placeholder="Type purposes of the copy here"/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label>Class Adviser</label>
                                    <input type="text" class="form-control" id="classadviser" value="{{$footer->classadviser}}" placeholder="Class Adviser"/>
                                </div>
                                <div class="col-md-6">
                                    <label>Records In-charge/Records Assistant </label>
                                    <input type="text" class="form-control" id="recordsincharge" value="{{$footer->recordsincharge}}" placeholder="Records In-charge"/>
                                </div>
                                <div class="col-md-6">
                                    <label><small class="text-bold">Eligible for admission to Grade:</small></label>
                                    <input type="text" class="form-control" id="admissiontograde" value="{{$footer->admissiontograde ?? ''}}" placeholder=""/>
                                </div>
                                <div class="col-md-6">
                                    <label>Last School Year Attended:</label>
                                    <input type="text" class="form-control" id="lastsy" value="{{$footer->lastsy ?? ''}}" placeholder="Records In-charge"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <label>&nbsp;</label><br/>
                                    <button type="button" class="btn btn-primary" id="btn-savefooter"><i class="fa fa-share"></i> Save Changes</button>
                                </div>
                            </div>
                        @endif
                    </div>              
                </div>            
            </div>
        </div>
    
    </div>
    
</div>
    

{{-- '<tr>'+
    '<td style="width: 15%;"><input type="text" class="form-control form-control-sm p-0 input-norecord" placeholder="Ex: Core"/></td>'+
    '<td style="width: 30%;"><input type="text" class="form-control form-control-sm p-0 input-norecord" placeholder="Subject"/></td>'+
    '<td><input type="text" class="form-control form-control-sm p-0 input-norecord" placeholder="Grade"/></td>'+
    '<td><input type="text" class="form-control form-control-sm p-0 input-norecord" placeholder="Grade"/></td>'+
    '<td style="width: 15%;"><input type="text" class="form-control form-control-sm p-0 input-norecord" placeholder="Final"/></td>'+
    '<td style="width: 15%;"><input type="text" class="form-control form-control-sm p-0 input-norecord" placeholder="Action Taken"/></td>'+
    '<td><button type="button" class="btn btn-sm btn-block btn-default p-0 btn-saverow"><small><i class="fa fa-share"></i> Save</small></button></td>'+
    '<td><button type="button" class="btn btn-sm btn-block btn-default p-0 btn-deleterow"><small><i class="fa fa-trash-alt"></i></small></button></td>'+
'</tr>' --}}
<script>
            var completer = $('#checkbox-completer').val()
            var peptpasser = $('#checkbox-peptpasser').val()
            var alspasser = $('#checkbox-alspasser').val()
            var infoid ;
    $('#checkbox-completer').change(function(){
        if($(this).prop('checked'))
        {
            $(this).val('1')
            completer = 1;
        }else{
            $(this).val()
            completer = 0;
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
    $('#btn-eligibility-update-jhs').on('click', function(){
        var generalaverage = $('#generalaverage').val()
        var citation = $('#citation').val()
        
        var schoolname = $('#schoolname').val();
        var schoolid = $('#schoolid').val();
        var schooladdress = $('#schooladdress').val();
        var peptrating = $('#peptrating').val();
        var alsrating = $('#alsrating').val();
        var examdate = $('#examdate').val();
        var specify = $('#specify').val();
        var centername = $('#centername').val();
        
        var guardianaddress = $('#input-guardianaddress').val();
        var sygraduated = $('#input-sygraduated').val();
        var totalnoofyears = $('#input-totalnoofyears').val();

        var courseschool = $('#input-courseschool').val()
        var courseyear   = $('#input-courseyear').val()
        var coursegenave = $('#input-coursegenave').val()

        $.ajax({
            url: '/schoolform10/updateeligibility',
            type: 'POST',
            data:{
                        "_token": "{{ csrf_token() }}",
                studentid           : '{{$studinfo->id}}',
                acadprogid          : 4,
                completer           :   completer,
                generalaverage      :   generalaverage,
                citation            :   citation,
                peptpasser          :   peptpasser,
                alspasser           :   alspasser,
                peptrating          :   peptrating,
                alsrating           :   alsrating,
                schoolname          :   schoolname,
                schoolid            :   schoolid,
                schooladdress       :   schooladdress,
                examdate            :   examdate,
                specify             :   specify,
                centername          :   centername,
                guardianaddress     :   guardianaddress,
                sygraduated         :   sygraduated,
                totalnoofyears      :   totalnoofyears,
                courseschool        :   courseschool,
                courseyear          :   courseyear,
                coursegenave        :   coursegenave
            }, success:function(data)
            {

                toastr.success('Updated successfully!','Eligibility')
            }
        });
    })
    $('.btn-saverecord').hide()
    $('.btn-addrow').on('click', function(){
        var thistbody = $(this).closest('table').find('.gradescontainer');
        thistbody.append(
            '<tr class="eachsubject">'+
                '<td><input type="checkbox" class="form-control" style="width: 20px;height: 20px;"></td>'+
                '<td><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="0"/><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjdesc" placeholder="Subject"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q1"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q2"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q3"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q4"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-finalgrade" placeholder="Final"/></td>'+
                '<td><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-remarks" placeholder="Remarks"/></td>'+
                '<td><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-credits" placeholder="Credits"/></td>'+
                '<td colspan="2"><button type="button" class="btn btn-sm btn-block btn-default p-0 btn-deleterow"><small><i class="fa fa-trash-alt"></i></small></button></td>'+
            '</tr>'
        )
    })
    $(document).on('click','.btn-deleterow', function(){
        $(this).closest('tr').remove()
    })
    $(document).on('input','.input-norecord', function(){
        $(this).closest('.card').find('.btn-saverecord').show()
    })
    $('.btn-saverecord').on('click', function(){
        // var thiscardheader = $(this).closest('.card').find('.card-header');
        var thiscardheader = $(this).closest('.tab-pane');
        var schoolname = thiscardheader.find('.input-schoolname').val();
        var schoolid = thiscardheader.find('.input-schoolid').val();
        var district = thiscardheader.find('.input-district').val();
        var division = thiscardheader.find('.input-division').val();
        var region = thiscardheader.find('.input-region').val();
        var gradelevelid = thiscardheader.find('.input-levelid').attr('data-id');
        var sectionname = thiscardheader.find('.input-sectionname').val();
        var schoolyear = thiscardheader.find('.input-sydesc').val();
        var teachername = thiscardheader.find('.input-adviser').val();
        
        var thiscardbody = $(this).closest('.tab-pane');
        var thistbody = thiscardbody.find('.gradescontainer');
        var thistrs = thistbody.find('tr.eachsubject');
        var subjects = [];
        thistrs.each(function(){
            var indentcheck = $(this).find('input[type="checkbox"]:checked');
            var subjdesc    = $(this).find('.input-subjdesc').val();
            var q1          = $(this).find('.input-q1').val();
            var q2          = $(this).find('.input-q2').val();
            var q3          = $(this).find('.input-q3').val();
            var q4          = $(this).find('.input-q4').val();
            var finalgrade  = $(this).find('.input-finalgrade').val();
            var remarks     = $(this).find('.input-remarks').val();
            var credits     = $(this).find('.input-credits').val();
            var indentsubj = 0;
            if(indentcheck.length > 0)
            {
                var indentsubj = 1;
            }
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
                if(q3.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    q3 = 0;
                }
                if(q4.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    q4 = 0;
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
                    indentsubj      : indentsubj,
                    subjdesc      : subjdesc,
                    q1              : q1,
                    q2          : q2,
                    q3          : q3,
                    q4          : q4,
                    final      : finalgrade,
                    remarks      : remarks,
                    credits      : credits,
                    fromsystem   : 0,
                    editablegrades   : 0,
                    inmapeh   : 0,
                    intle   : 0
                };
                subjects.push(obj);
            }
        })
        if(subjects.length == 0)
        {
            toastr.warning('No Subjects detected!')
        }
        // else{
            var remarks = thiscardbody.find('.input-semremarks').val();
            var recordsincharge = thiscardbody.find('.input-incharge').val();
            var datechecked = thiscardbody.find('.input-datechecked').val();
            if(schoolyear.replace(/^\s+|\s+$/g, "").length == 0)
            {
                toastr.warning('Please fill in the School Year!')
                thiscardheader.find('.input-sydesc').css('border','1px solid red')
            }else{
                thiscardheader.find('.input-sydesc').removeAttr('style')
                    var paramet = {
                        "_token": "{{ csrf_token() }}",
                        studentid           : '{{$studinfo->id}}',
                        acadprogid          : 4,
                        schoolname          :   schoolname,
                        schoolid            :   schoolid,
                        district            :   district,
                        division            :   division,
                        region              :   region,
                        gradelevelid        :   gradelevelid,
                        sectionname         :   sectionname,
                        schoolyear          :   schoolyear,
                        teachername         :   teachername,
                        recordsincharge     :   recordsincharge,
                        datechecked         :   datechecked,
                        // indications         :   indications,
                        subjects            :   JSON.stringify(subjects),
                        remarks             :   remarks
                    }
                $.ajax({
                    url: '/schoolform10/submitnewform',
                    type: 'POST',
                        data: $.param(paramet)
                        , success:function(data)
                    {
                        toastr.success('Record added successfully!')
                        $('#addcontainer').empty()
                        $('#addrecord').prop('disabled',false)
                                $('#btn-getrecords').click();
                        // getrecords();
                    }
                });
            }
        // }
    })
    $('.btn-deletesubject').on('click', function(){
        var id = $(this).attr('data-id')
        var thisrow = $(this).closest('tr');
        Swal.fire({
            title: 'Are you sure you want to delete this?',
            html: 'You won\'t be able to revert this!',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading()
        }).then((reasoninput) => {
            if (reasoninput.value) {
                billedamount = 0.00;
                stipend = 0.00;
                disabilityamount = 0.00;
                $.ajax({
                    url: '/schoolform10/deleterecord',
                    type: 'GET',
                    data: {
                        action          : 'subject',
                        id              : id,
                        acadprogid      : 4
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
    $('.btn-updaterecord').on('click', function(){
        var id = $(this).attr('data-id')
        // var thiscardheader = $(this).closest('.card').find('.card-header');
        var thiscardheader = $(this).closest('.tab-pane');
        
        var schoolname = thiscardheader.find('.input-schoolname').val();
        var schoolid = thiscardheader.find('.input-schoolid').val();
        var district = thiscardheader.find('.input-district').val();
        var division = thiscardheader.find('.input-division').val();
        var region = thiscardheader.find('.input-region').val();
        var gradelevelid = thiscardheader.find('.input-levelid').attr('data-id');
        var sectionname = thiscardheader.find('.input-sectionname').val();
        var schoolyear = thiscardheader.find('.input-sydesc').val();
        var teachername = thiscardheader.find('.input-adviser').val();
        
        var thiscardbody = thiscardheader;
        var thistbody =thiscardbody.find('.gradescontainer');
        var thistrs = thistbody.find('tr.eachsubject');
        var subjects = [];
        thistrs.each(function(){
            var indentcheck = $(this).find('input[type="checkbox"]:checked');
            var subjid    = $(this).find('.input-subjid').val();
            var subjdesc    = $(this).find('.input-subjdesc').val();
            var q1          = $(this).find('.input-q1').val();
            var q2          = $(this).find('.input-q2').val();
            var q3          = $(this).find('.input-q3').val();
            var q4          = $(this).find('.input-q4').val();
            var finalgrade  = $(this).find('.input-finalgrade').val();
            var remarks     = $(this).find('.input-remarks').val();
            var credits     = $(this).find('.input-credits').val();
            var indentsubj = 0;
            if(indentcheck.length > 0)
            {
                var indentsubj = 1;
            }
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
                if(q3.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    q3 = 0;
                }
                if(q4.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    q4 = 0;
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
                    indentsubj      : indentsubj,
                            id      : subjid,
                    subjdesc      : subjdesc,
                    q1              : q1,
                    q2          : q2,
                    q3          : q3,
                    q4          : q4,
                    final      : finalgrade,
                    remarks      : remarks,
                    credits      : credits,
                    fromsystem   : 0,
                    editablegrades   : 0,
                    inmapeh   : 0,
                    intle   : 0
                };
                subjects.push(obj);
            }
        })
        
        if(subjects.length == 0)
        {
            toastr.warning('No Subjects detected!')
        }
        // else{
            var remarks = thiscardbody.find('.input-semremarks').val();
            var recordsincharge = thiscardbody.find('.input-incharge').val();
            var datechecked =thiscardbody.find('.input-datechecked').val();
            var credit_advance = thiscardbody.find('.input-credit-advance').val();
            var credit_lacks = thiscardbody.find('.input-credit-lacks').val();
            var noofyears = thiscardbody.find('.input-noofyears').val();
            
            var paramet = {
                    "_token": "{{ csrf_token() }}",
                    studentid           : '{{$studinfo->id}}',
                    acadprogid          : 4,
                    id          :   id,
                    schoolname          :   schoolname,
                    schoolid            :   schoolid,
                    district            :   district,
                    division            :   division,
                    region              :   region,
                    gradelevelid        :   gradelevelid,
                    sectionname         :   sectionname,
                    schoolyear          :   schoolyear,
                    teachername         :   teachername,
                    recordsincharge     :   recordsincharge,
                    datechecked         :   datechecked,
                    credit_advance      :   credit_advance,
                    credit_lacks        :   credit_lacks,
                    noofyears           :   noofyears,
                    // indications         :   indications,
                    subjects            :   JSON.stringify(subjects),
                    remarks             :   remarks
                }
            $.ajax({
                url: '/schoolform10/updateform',
                type: 'POST',
                data: $.param(paramet)
                , success:function(data)
                {
                        toastr.success('Record updated successfully!')
                        $('#addcontainer').empty()
                        $('#addrecord').prop('disabled',false)
                                $('#btn-getrecords').click();
                }
            });
        // }
    })
    $('.input-month').on('input', function(){
        if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
        {
            var thindex = $(this).parent().index();
            var trs = $(this).closest('table').find('tbody').find('tr')
            trs.each(function(){
                var thistd = $(this).find('td').eq(thindex)
                thistd.find('input').val('')
            })
        }
    })
    $('.input-daynum').on('input', function(){
        var tdindex = $(this).parent().index();
        var thparent = $(this).closest('table').find('thead').find('th').eq(tdindex)
        var monthvalue = thparent.find('input').val();
        // console.log(thparent)
        // console.log(monthvalue)
        if(monthvalue.replace(/^\s+|\s+$/g, "").length == 0)
        {
            thparent.css('border','1px solid red')
            toastr.error('Please fill in the months\' row')
            $(this).val('')
        }else{
            thparent.removeAttr('style')
        }
    })
    $('.btn-updateattendance').on('click', function(){
        var recordid = $(this).attr('data-id');
        var attendance = [];
        $('.input-month').each(function(){
            var monthdesc = $(this).val();
            var schooldays = 0;
            var dayspresent = 0;
            var daysabsent = 0;
            var timestardy = 0;
            if($(this).val().replace(/^\s+|\s+$/g, "").length > 0)
            {
                var thindex = $(this).parent().index();
                var trs = $(this).closest('table').find('tbody').find('tr')
                trs.each(function(){
                    
                    var thistd = $(this).find('td').eq(thindex)
                    var thisinput = thistd.find('input');
                    var thisvalue = thistd.find('input').val();

                    if(thisinput.hasClass('input-dschool'))
                    {
                        schooldays = thisvalue;
                    }else if(thisinput.hasClass('input-dpresent'))
                    {
                        dayspresent = thisvalue;
                    }else if(thisinput.hasClass('input-dabsent'))
                    {
                        daysabsent = thisvalue;
                    }else if(thisinput.hasClass('input-dtardy'))
                    {
                        timestardy = thisvalue;
                    }
                })
                obj = {
                    monthdesc : monthdesc,
                    schooldays : schooldays,
                    dayspresent : dayspresent,
                    daysabsent : daysabsent,
                    timestardy : timestardy
                }
                attendance.push(obj)
            }
        })
        // if(attendance.length == 0)
        // {
        //     toastr.warning('Please fill in the Attendance first!')
        // }else{
            $.ajax({
                url: '/schoolform10/updateattendance',
                type: 'POST',
                data:{
                        "_token": "{{ csrf_token() }}",
                    studentid           : '{{$studinfo->id}}',
                    acadprogid          : 4,
                    id                  :   recordid,
                    attendance          :   JSON.stringify(attendance)

                }, success:function(data)
                {
                    toastr.success('Attendance updated successfully!')
                }
            });
        // }
    })
    
    $('.btn-edit-editremedialheader').on('click', function(){
            var thistable = $(this).closest('table');
            var conductdatefrom     = thistable.find('.remedial-datefrom').val();
            var conductdateto       = thistable.find('.remedial-dateto').val();
            var studentid = $(this).attr('data-studid');
            var sydesc = $(this).attr('data-sydesc');
            var levelid = $(this).attr('data-levelid');
            var action = $(this).attr('data-action');
            $.ajax({
                url: '/schoolform10/updateremedialheader',
                type:"GET",
                dataType:"json",
                data:{
                    action          : action,
                    studentid      : studentid,
                    sydesc         : sydesc,
                    levelid         : levelid,
                    acadprogid     : 4,
                    conductdatefrom: conductdatefrom,
                    conductdateto: conductdateto
                },
                success: function(){

                    toastr.success('Updated successfully!')
                    
                }
            })

        })
        
        var counter = 0;
        $('.btn-remedial-addrow').on('click', function(){
            var studentid = $(this).attr('data-studid');
            var sydesc = $(this).attr('data-sydesc');
            var levelid = $(this).attr('data-levelid');
            
            $(this).closest('table').find('#remedial-tbody').append(
                `
                <tr>
                <td><input type="text" value="" class="form-control form-control-sm" name="add-new-subject"/></td>
                <td><input type="text" value="" class="form-control form-control-sm" name="add-new-finalrating"/></td>
                <td><input type="text" value="" class="form-control form-control-sm" name="add-new-classmark"/></td>
                <td><input type="text" value="" class="form-control form-control-sm" name="add-new-recomputed"/></td>
                <td><input type="text" value="" class="form-control form-control-sm" name="add-new-remarks"/></td>
                <td class="text-right"><button type="button" class="btn btn-sm btn-outline-success p-1 btn-edit-addremedial" id="btn-edit-addremedial`+levelid+`-`+counter+`" data-studid="`+studentid+`" data-sydesc="`+sydesc+`" data-action="add" data-levelid="`+levelid+`"><i class="fa fa-edit"></i> Save</button> <button type="button" class="btn btn-sm btn-outline-danger p-1  removebutton"><i class="fa fa-trash-alt text-danger"></i>&nbsp;</button></td>
                </tr>
                `
            );
            $('#btn-edit-addremedial'+levelid+'-'+counter).on('click', function(){
                var thisbutton = $(this);
                var studentid = $(this).attr('data-studid');
                var sydesc = $(this).attr('data-sydesc');
                var levelid = $(this).attr('data-levelid');
                var action = $(this).attr('data-action');

                var addsubject     = $(this).closest('tr').find('input[name="add-new-subject"]').val()
                var addfinalrating = $(this).closest('tr').find('input[name="add-new-finalrating"]').val()
                var addclassmark   = $(this).closest('tr').find('input[name="add-new-classmark"]').val()
                var addrecomputed  = $(this).closest('tr').find('input[name="add-new-recomputed"]').val()
                var addremarks     = $(this).closest('tr').find('input[name="add-new-remarks"]').val()

                var validationcheck = 0;
                if(addsubject.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-subject"]').css('border','1px solid red');
                }else{
                    $(this).closest('tr').find('input[name="add-new-subject"]').removeAttr('style')
                }

                if(addfinalrating.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-finalrating"]').css('border','1px solid red');
                }else{
                    $(this).closest('tr').find('input[name="add-new-finalrating"]').removeAttr('style')
                }
                if(addclassmark.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-classmark"]').css('border','1px solid red');
                }else{
                    $(this).closest('tr').find('input[name="add-new-classmark"]').removeAttr('style')
                }
                if(addrecomputed.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-recomputed"]').css('border','1px solid red');
                }else{
                    $(this).closest('tr').find('input[name="add-new-recomputed"]').removeAttr('style')
                }
                if(addremarks.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-remarks"]').css('border','1px solid red');
                }else{
                    $(this).closest('tr').find('input[name="add-new-remarks"]').removeAttr('style')
                }

                if(validationcheck == 0)
                {
                    if(action == 'add')
                    {
                        var _thisurl = '/schoolform10/addremedial';
                    }else if(action == 'edit')
                    {
                        var _thisurl = '/schoolform10/editremedial';
                    }
                    $.ajax({
                        url: _thisurl,
                        type:"GET",
                        dataType:"json",
                        data:{
                            action          : action,
                            studentid      : studentid,
                            sydesc         : sydesc,
                            levelid         : levelid,
                            acadprogid : 4,
                            subject: addsubject,
                            finalrating: addfinalrating,
                            remclassmark: addclassmark,
                            recomputedfinal: addrecomputed,
                            remarks: addremarks
                        },
                        dataType: 'json',
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){
                            thisbutton.removeAttr('id')
                            thisbutton.attr('data-id', data)
                            thisbutton.attr('data-action', 'edit')
                            thisbutton.html('<i class="fa fa-edit"></i> Update')
                            var thisdeletebutton = thisbutton.closest('td').find('.removebutton')
                            thisdeletebutton.attr('data-id',data)
                            thisdeletebutton.addClass('btn-edit-deleteremedial')
                            thisdeletebutton.removeClass('removebutton')
                            toastr.success('Added successfully!')
                            
                        }
                    })
                }else{
                    toastr.error('Some fields are empty!')
                }

            })
            counter+=1;
        })
        
        $('.btn-edit-editremedial').on('click', function(){
            var thisbutton = $(this);
            var studentid = $(this).attr('data-studid');
            var remedialid = $(this).attr('data-id');
            var sydesc = $(this).attr('data-sydesc');
            var levelid = $(this).attr('data-levelid');
            var action = $(this).attr('data-action');

            var editsubject     = $(this).closest('tr').find('#subject'+$(this).attr('data-id')).val()
            var editfinalrating = $(this).closest('tr').find('#finalrating'+$(this).attr('data-id')).val()
            var editclassmark   = $(this).closest('tr').find('#remclassmark'+$(this).attr('data-id')).val()
            var editrecomputed  = $(this).closest('tr').find('#recomputedfinal'+$(this).attr('data-id')).val()
            var editremarks     = $(this).closest('tr').find('#remarks'+$(this).attr('data-id')).val()

            var validationcheck = 0;
            if(editsubject.replace(/^\s+|\s+$/g, "").length == 0)
            {
                validationcheck+=1;
                $(this).closest('tr').find('#subject'+$(this).attr('data-id')).css('border','1px solid red');
            }else{
                $(this).closest('tr').find('#subject'+$(this).attr('data-id')).removeAttr('style')
            }

            if(editfinalrating.replace(/^\s+|\s+$/g, "").length == 0)
            {
                validationcheck+=1;
                $(this).closest('tr').find('#finalrating'+$(this).attr('data-id')).css('border','1px solid red');
            }else{
                $(this).closest('tr').find('#finalrating'+$(this).attr('data-id')).removeAttr('style')
            }
            if(editclassmark.replace(/^\s+|\s+$/g, "").length == 0)
            {
                validationcheck+=1;
                $(this).closest('tr').find('#remclassmark'+$(this).attr('data-id')).css('border','1px solid red');
            }else{
                $(this).closest('tr').find('#remclassmark'+$(this).attr('data-id')).removeAttr('style')
            }
            if(editrecomputed.replace(/^\s+|\s+$/g, "").length == 0)
            {
                validationcheck+=1;
                $(this).closest('tr').find('#recomputedfinal'+$(this).attr('data-id')).css('border','1px solid red');
            }else{
                $(this).closest('tr').find('#recomputedfinal'+$(this).attr('data-id')).removeAttr('style')
            }
            if(editremarks.replace(/^\s+|\s+$/g, "").length == 0)
            {
                validationcheck+=1;
                $(this).closest('tr').find('#remarks'+$(this).attr('data-id')).css('border','1px solid red');
            }else{
                $(this).closest('tr').find('#remarks'+$(this).attr('data-id')).removeAttr('style')
            }

            if(validationcheck == 0)
            {
                $.ajax({
                    url: '/schoolform10/editremedial',
                    type:"GET",
                    dataType:"json",
                    data:{
                        action          : action,
                        studentid      : studentid,
                        sydesc         : sydesc,
                        levelid         : levelid,
                        acadprogid : 4,
                        remedialid:remedialid,
                        subject: editsubject,
                        finalrating: editfinalrating,
                        remclassmark: editclassmark,
                        recomputedfinal: editrecomputed,
                        remarks: editremarks
                    },
                    dataType: 'json',
                    // headers: { 'X-CSRF-TOKEN': token },,
                    success: function(data){
                        toastr.success('Updated successfully!')
                        
                    }
                })
            }else{
                toastr.error('Some fields are empty!')
            }

        })
        $(document).on('click','.btn-edit-deleteremedial', function(){
            var remedialid = $(this).attr('data-id');
            var thistr = $(this).closest('tr');
            Swal.fire({
                title: 'Are you sure you want to delete this row?',
                // text: "You won't be able to revert this!",
                html:
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
                        url: '/schoolform10/deleteremedial',
                        type:"GET",
                        dataType:"json",
                        data:{
                            acadprogid : '{{$acadprogid}}',
                            remedialid: remedialid
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(){
                            // console.log(thistr)
                            toastr.success('Deleted successfully!')
                                    thistr.remove();
                                    

                        }
                    })
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
                    billedamount = 0.00;
                    stipend = 0.00;
                    disabilityamount = 0.00;
                    $.ajax({
                        url: '/schoolform10/deleterecord',
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
                                thisrow.remove()

                            }else{
                                toastr.error('Something went wrong!')
                            }
                        }
                    })
                }
            })
        })
        $('#btn-savefooter').on('click', function(){
            var purpose         = $('#purpose').val(); 
            var classadviser    = $('#classadviser').val();
            var recordsincharge = $('#recordsincharge').val(); 
            
            var lastsy = $('#lastsy').val(); 
            var admissiontograde = $('#admissiontograde').val(); 

            var certcopysentto = $('#certcopysentto').val(); 
            var certaddress = $('#certaddress').val(); 
            $.ajax({
                url: '/schoolform10/certification',
                type:"POST",
                dataType:"json",
                data:{
                    "_token": "{{ csrf_token() }}",
                    action               :   'update',
                    studentid               :   '{{$studinfo->id}}',
                    acadprogid              : '{{$acadprogid}}',
                    purpose                 : purpose,
                    classadviser            : classadviser,
                    recordsincharge         : recordsincharge,
                    lastsy                  : lastsy,
                    admissiontograde        : admissiontograde,
                    certcopysentto         : certcopysentto,
                    certaddress         : certaddress
                },
                // headers: { 'X-CSRF-TOKEN': token },,
                complete: function(){

                    toastr.success('Updated successfully!','Certification')                         

                }
            })
        })
        $('.btn-exportform-jhs').on('click', function(){
            var papersize = $('#select-papersize').val()
            var formexport = 1;
            var format = $(this).attr('data-format')
            var layout = $(this).attr('data-layout')
            var exporttype = $(this).attr('data-exporttype')
            var studentid   = '{{$studinfo->id}}';
            var acadprogid   = '{{$acadprogid}}';
            window.open('/schoolform10/getrecordsjunior?export='+formexport+'&format='+format+'&exporttype='+exporttype+'&studentid='+studentid+'&acadprogid='+acadprogid+'&layout='+layout+'&papersize='+papersize,'_blank')
        })

</script>