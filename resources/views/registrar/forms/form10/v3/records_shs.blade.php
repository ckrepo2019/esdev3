<style>
    table td, table th{
        padding: 0px !important;
    }
    .input-norecord{
        height: unset;
    }
</style>
<div class="row mb-2">
    {{-- <div class="col-md-9 mb-2">
        <form 
              action="/" 
              id="upload_sf10" 
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
    <div class="col-md-3 mb-2 text-right"> --}}
        <div class="col-md-12 mb-2 text-right">
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
                <button class="btn-exportform-shs dropdown-item" data-exporttype="pdf" data-format="deped" data-layout="1"><i class="fa fa-file-pdf"></i> &nbsp;Deped (Template 1)</button>
                <button class="btn-exportform-shs dropdown-item" data-exporttype="pdf" data-format="deped-2" data-layout="1"><i class="fa fa-file-pdf"></i> &nbsp;Deped (Template 1 - with school logo)</button>
                @if($acadprogid == 5)
                <button class="btn-exportform-shs dropdown-item" data-exporttype="pdf" data-format="depedspr" data-layout="1"><i class="fa fa-file-pdf"></i> &nbsp;Deped - SPR (Template 2)</button>
                <button class="btn-exportform-shs dropdown-item" data-exporttype="pdf" data-format="depedspr" data-layout="2"><i class="fa fa-file-pdf"></i> &nbsp;Deped - SPR (Template 2 - with school logo)</button>
                @endif
                <button class="btn-exportform-shs dropdown-item" @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')data-exporttype="excel"@else data-exporttype="pdf"@endif data-format="school" data-layout="1"><i class="fa fa-file-pdf"></i> &nbsp;School Template @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')1 @endif<br/><small><em class="text-danger">Note: Please provide the school's own template</em></small></button>
            </div>
          </div>
        {{-- <button id="addrecord" type="button" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i> Add New Record</button> --}}
        {{-- <button id="addrecordbyquarter" type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-addnewperq"><i class="fa fa-plus"></i> Per Quarter</button> --}}
        
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
                    <li class="nav-item"><a class="nav-link {{--@if($key == 0) active @endif--}}" href="#tab_{{$key}}-1" data-toggle="tab">{{$gradelevel->levelname}} - 1st Sem</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab_{{$key}}-2" data-toggle="tab">{{$gradelevel->levelname}} - 2nd Sem</a></li>
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
                                <button type="button" class="btn btn-sm btn-primary" id="btn-eligibility-update-shs"><i class="fa fa-edit"></i> Update</button>
                            </div>
                        </div>
                    </div>
                    @foreach ($gradelevels as $key=>$gradelevel)
                        @if(collect($records)->where('levelid', $gradelevel->id)->where('semid','1')->count() == 1)
                            
                            @php
                                $studentid = $studinfo->id;
                                $recordsem1 = collect($records)->where('levelid', $gradelevel->id)->where('semid','1')->first();
                                $recordatt = array();
                                $syid =0;
                                $sectionid =0;
                                if($recordsem1)
                                {
                                    $syid = $recordsem1->syid;
                                    $sydesc = $recordsem1->sydesc;
                                    $sectionid =$recordsem1->sectionid;
                                    $recordatt = collect($records)->where('levelid', $gradelevel->id)->first()->attendance;
                                }else{
                                    $syid = 0;
                                    $sydesc = null;
                                    $sectionid = 0;
                                }
                                $subjects = array();
                            @endphp
                        @endif


                    @for($eachsem=0; $eachsem<2; $eachsem++)
                    <div class="tab-pane  {{--@if($key == 0) active @endif--}}" id="tab_{{$key}}-{{$eachsem+1}}"> 
                        @php
                        $eachrecord = collect($records)->where('levelid', $gradelevel->id)->where('semid',($eachsem+1))->first();
                        @endphp
                        @if(collect($records)->where('levelid', $gradelevel->id)->where('semid',($eachsem+1))->count() == 0)
                            <fieldset class="form-group border p-2 fieldset-grades">
                                <legend class="w-auto m-0">Grades</legend>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table m-0" style="font-size: 12px;">
                                                <tr>
                                                    <td style="width: 15%;">School</td>
                                                    <td colspan="7" style=""><input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolname"/></td>
                                                </tr>
                                                <tr>
                                                    <td>School ID</td>
                                                    <td colspan="7"><input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolid"/></td>
                                                </tr>
                                                <tr>
                                                    <td>Grade Level</td>
                                                    <td style="width: 30%; "><input type="text" class="form-control form-control-sm p-0 input-norecord input-levelid" data-id="{{$gradelevel->id}}" value="{{$gradelevel->levelname}}" readonly/></td>
                                                    <td class="text-right">SY&nbsp;&nbsp;</td>
                                                    <td style="width: 15%; "><input type="text" class="form-control form-control-sm p-0 input-norecord input-sydesc"/></td>
                                                    <td class="text-right">Sem&nbsp;&nbsp;</td>
                                                    <td style="width: 15%; "><input type="text" class="form-control form-control-sm p-0 input-norecord input-semid" data-id="{{$eachsem+1}}" value="{{($eachsem+1) == 1 ? '1st' : '2nd'}}" readonly/></td>
                                                </tr>
                                                <tr>
                                                    <td>Track</td>
                                                    <td style="width: 15%; " colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-trackname"/></td>
                                                    <td class="text-right">Strand&nbsp;&nbsp;</td>
                                                    <td style="width: 15%; " colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-strandname"/></td>
                                                </tr>
                                                <tr>
                                                    <td>Section</td>
                                                    <td style="width: 15%; " colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-sectionname"/></td>
                                                    <td class="text-right">Adviser&nbsp;&nbsp;</td>
                                                    <td style="width: 15%; " colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-adviser"/></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            @php
                                                
                                                $defaultsubjects = collect($gradelevel->subjects)->where('levelid', $gradelevel->id)->where('semid', ($eachsem+1))->where('strandid', $studinfo->strandid)->unique('subjcode');
                                            @endphp
                                            <table class="table table-striped" style="font-size: 11px; table-layout: fixed;">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th style="width: 8%;">Indication</th>
                                                        <th style="width: 40%;">Subjects</th>
                                                        <th>Q1</th>
                                                        <th>Q2</th>
                                                        <th style="width: 10%;">Final Grade</th>
                                                        <th style="width: 15%;">Action Taken</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($defaultsubjects)>0)
                                                        @foreach($defaultsubjects as $defaultsubject)
                                                            <tr class="eachsubject">
                                                                <td style="width: 15%;"><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="{{$defaultsubject->id}}"/><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjcode" placeholder="Ex: Core" value="{{$defaultsubject->subjcode}}"/></td>
                                                                <td style="width: 30%;"><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjdesc" placeholder="Subject" value="{{ucwords(strtolower($defaultsubject->subjtitle))}}"/></td>
                                                                <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q1" placeholder="Grade"/></td>
                                                                <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q2" placeholder="Grade"/></td>
                                                                <td style="width: 15%;"><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-finalgrade" placeholder="Final Grade"/></td>
                                                                <td style="width: 15%;"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-remarks" placeholder="Action Taken"/></td>
                                                                <td colspan="2"><button type="button" class="btn btn-sm btn-block btn-default p-0 btn-deleterow"><small><i class="fa fa-trash-alt"></i></small></button></td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    <tr>
                                                        <td colspan="8" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sjaes')
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table" style="width: 100%; font-size: 11px;">
                                                    <tr>
                                                        <th style="width: 15% !important;" class="text-right">Remarks:</th>
                                                        <td colspan="3" style="width: 85% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-semremarks"/></td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 15% !important;" class="text-right">Record's In-charge:</th>
                                                        <td style="width: 60% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-recordsincharge"/></td>
                                                        <th style="width: 15% !important;" class="text-right">Date Checked:</th>
                                                        <td style="width: 10% !important;"><input type="date" class="form-control form-control-sm p-0 input-norecord input-semdatechecked"/></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    @endif --}}
                                    <div class="row mb-2">
                                        <div class="col-md-12 text-right">
                                            <button type="button" class="btn btn-sm btn-outline-success btn-saverecord"><i class="fa fa-share"></i> Submit Grades Record</button>
                                        </div>
                                    </div>
                            </fieldset> 
                        @else
                            {{-- @if(collect($records)->where('levelid', $gradelevel->id)->where('semid',$eachsem+1)->count() > 0)  --}}
                            {{--this is when there is more than one record in the same grade level and semester, so all records can be displayed--}}
                            
                                <ul class="nav nav-tabs" id="custom-content-tab-{{$gradelevel->id}}-{{$eachsem}}" role="tablist">
                                    @foreach(collect($records)->where('levelid', $gradelevel->id)->where('semid',$eachsem+1)->values() as $keytab=>$eachrecord) 
                                    <li class="nav-item">
                                        <a class="nav-link  @if($keytab == 0)active @endif" id="custom-content-tab-link-{{$gradelevel->id}}-{{$eachsem}}-{{$eachrecord->sydesc}}" data-toggle="pill" href="#custom-content-link-{{$gradelevel->id}}-{{$eachsem}}-{{$eachrecord->sydesc}}" role="tab" aria-controls="custom-content-{{$gradelevel->id}}-{{$eachsem}}-{{$eachrecord->sydesc}}" aria-selected=" @if($keytab == 0)true @else false @endif">{{$eachrecord->sydesc}}</a>
                                    </li>
                                    @endforeach
                                </ul>
                                
                                <div class="tab-content" id="custom-content-link-tabContent">
                                    @foreach(collect($records)->where('levelid', $gradelevel->id)->where('semid',$eachsem+1)->values() as $keytab=>$eachrecord) 
                                        <div class="tab-pane fade show  @if($keytab == 0)active @endif" id="custom-content-link-{{$gradelevel->id}}-{{$eachsem}}-{{$eachrecord->sydesc}}" role="tabpanel" aria-labelledby="custom-content-tab-link-{{$gradelevel->id}}-{{$eachsem}}-{{$eachrecord->sydesc}}">
                                            @php
                                                $grades = $eachrecord->grades;
                                            @endphp
                                            <fieldset class="form-group border p-2 fieldset-grades">
                                                <legend class="w-auto m-0">Grades</legend>
                                                <div class="row">
                                                    @if($eachrecord->type == 1)
                                                        <div class="col-md-12"><span class="badge badge-warning">Auto Generated</span></div>
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
                                                                <td colspan="7" style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schoolname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolname" value="{{$eachrecord->schoolname}}"/>@endif </td>
                                                            </tr>
                                                            <tr>
                                                                <td>School ID</td>
                                                                <td colspan="7" style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schoolid}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolid" value="{{$eachrecord->schoolid}}"/>@endif</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Grade Level</td>
                                                                <td style="width: 30%; border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->levelname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-levelid" data-id="{{$gradelevel->id}}" value="{{$gradelevel->levelname}}" readonly/>@endif</td>
                                                                <td>SY</td>
                                                                <td style="width: 20%; border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->sydesc}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-sydesc" value="{{$eachrecord->sydesc}}"/>@endif</td>
                                                                <td>Sem</td>
                                                                <td style="width: 20%; border-bottom: 1px solid black;">@if($eachrecord->type == 1){{($eachsem+1) == 1 ? '1st' : '2nd'}} @else<input type="text" class="form-control form-control-sm p-0 input-norecord input-semid" data-id="{{$eachsem+1}}" value="{{($eachsem+1) == 1 ? '1st' : '2nd'}}" readonly/>@endif</td>
                                                            </tr>
                                                        </table>
                                                        <table class="table m-0" style="font-size: 11px;">
                                                            <tr>
                                                                <td style="width: 20%;">Track</td>
                                                                <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->trackname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-trackname" value="{{$eachrecord->trackname}}"/>@endif</td>
                                                                <td>Strand</td>
                                                                <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->strandname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-strandname" value="{{$eachrecord->strandname}}"/>@endif</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 20%;">Section</td>
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
                                                        @else
                                                            <table class="table table-striped" style="font-size: 12px; table-layout: fixed;">
                                                                <thead class="text-center">
                                                                    <tr>
                                                                        <th style="width: 15%;">Indication</th>
                                                                        <th style="width: 30%;">Subjects</th>
                                                                        <th>Q1</th>
                                                                        <th>Q2</th>
                                                                        <th style="width: 15%;">Final Grade</th>
                                                                        <th style="width: 10%;">Action Taken</th>
                                                                        @if($eachrecord->type == 1)
                                                                        <th style="width: 12%;">&nbsp;</th>                  
                                                                        @else
                                                                        <th colspan="2">Delete</th>
                                                                        @endif
                                                                    </tr>
                                                                </thead>
                                                                <tbody>     
                                                                    @foreach($grades as $grade)
                                                                        <tr @if($eachrecord->type != 1)s class="eachsubject" @endif>
                                                                            <td>
                                                                                @if($eachrecord->type == 1)@if(isset($grade->subjcode)) {{$grade->subjcode}} @endif @else<input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="{{$grade->id}}"/><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-subjcode" value="{{$grade->subjcode}}"/>@endif
                                                                            </td>
                                                                            <td>@if($eachrecord->type == 1){{ucwords(strtolower($grade->subjdesc))}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-subjdesc" value="{{$grade->subjdesc}}"/>@endif</td>

                                                                            @if($grade->q1stat != 0)
                                                                                    <td class="text-center p-0">
                                                                                        <div class="row text-center p-0 m-0">
                                                                                            <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline; font-size: 12px; height: 25px !important;" @if($grade->q1stat == 2) value="{{$grade->q1}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade->q1stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="1"  data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}">@if($grade->q1stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs m-0"></i>@endif</button>
                                                                                        </div>
                                                                                    </td>
                                                                            @else
                                                                            <td class="text-center">@if($eachrecord->type == 1){{$grade->q1 ?? $grade->q3}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1" value="{{$grade->q1}}"/>@endif</td>
                                                                            @endif
                                                                            @if($grade->q2stat != 0)
                                                                                    <td class="text-center p-0">
                                                                                        <div class="row text-center p-0 m-0">
                                                                                            <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline; font-size: 12px; height: 25px !important;;" @if($grade->q2stat == 2) value="{{$grade->q2}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade->q2stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="2"  data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}">@if($grade->q2stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs m-0"></i>@endif</button>
                                                                                        </div>
                                                                                    </td>
                                                                            @else
                                                                            <td class="text-center">@if($eachrecord->type == 1){{$grade->q2 ?? $grade->q4}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2" value="{{$grade->q2}}"/>@endif</td>
                                                                            @endif

                                                                            <td class="text-center">@if($eachrecord->type == 1){{$grade->finalrating}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-finalgrade" value="{{$grade->finalrating}}"/>@endif</td>
                                                                            
                                                                            <td class="text-center">@if($eachrecord->type == 1){{$grade->remarks ?? $grade->actiontaken}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{$grade->remarks}}"/>@endif</td>
                                                                            @if($eachrecord->type == 1)  
                                                                            <td>&nbsp;</td>            
                                                                            @else
                                                                            <th colspan="2"> <button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-default btn-deletesubject text-sm" data-id="{{$grade->id}}"><i class="fa fa-trash-alt"></i></button></th>
                                                                            @endif
                                                                        </tr>
                                                                    @endforeach      
                                                                    @if($eachrecord->type == 1)
                                                                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                                                            <tr>
                                                                                <td colspan="6" class="p-0 pl-2 pt-2"><em class="text-danger">Note: The added subjects are not included in General Average computation</em></td>
                                                                                <td class="text-center p-0"><button type="button" class="btn btn-default btn-sm m-0 btn-block btn-addsubjinauto" data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-plus"></i> Subject</button></td>
                                                                            </tr>
                                                                        @else         
                                                                        <tr>
                                                                            <td class="text-center p-0" ><button type="button" class="btn btn-default btn-sm m-0 btn-block btn-addsubjinauto" data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-plus"></i> Subject</button></td>
                                                                            <td colspan="6" class="p-0 pl-2 pt-2"><em class="text-danger"></em></td>
                                                                        </tr>
                                                                        @endif
                                                                        @if(count($eachrecord->subjaddedforauto)>0)
                                                                            @foreach($eachrecord->subjaddedforauto as $customsubjgrade)
                                                                                <tr>
                                                                                    <td class="p-0"><input type="text" class="form-control form-control-sm subjcode" value="{{$customsubjgrade->subjcode}}" disabled/></td>
                                                                                    <td class="p-0"><input type="text" class="form-control form-control-sm subjdesc" value="{{$customsubjgrade->subjdesc}}" disabled/></td>
                                                                                    <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjq1" value="{{$customsubjgrade->q1}}" disabled/></td>
                                                                                    <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjq2" value="{{$customsubjgrade->q2}}" disabled/></td>
                                                                                    <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjfinalrating" value="{{$customsubjgrade->finalrating}}" disabled/></td>
                                                                                    <td class="text-center p-0"><input type="text" class="form-control form-control-sm subjremarks" value="{{$customsubjgrade->actiontaken}}" disabled/></td>
                                                                                    <td class="text-right p-0">
                                                                                        <button type="button" class="btn btn-sm btn-default btn-subjauto-edit"><i class="fa fa-edit text-warning"></i></button><button type="button" class="btn btn-sm btn-default btn-subjauto-update" data-id="{{$customsubjgrade->id}}" disabled><i class="fa fa-share text-success"></i></button><button type="button" class="btn btn-sm btn-default btn-subjauto-delete" data-id="{{$customsubjgrade->id}}" disabled><i class="fa fa-trash text-danger"></i></button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @endif
                                                                    @else                         
                                                                    <tr>
                                                                        <td colspan="8" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                                                    </tr>
                                                                    @endif        
                                                                </tbody>
                                                            </table>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-12 text-right">
                                                        @if($eachrecord->type != 1)
                                                            <button type="button" class="btn btn-sm btn-outline-success btn-updaterecord" data-id="{{$eachrecord->id}}"><i class="fa fa-share"></i> Update Grades Record</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <form action="/schoolform10/updatesigneachsem" method="POST" class="form-eachsem" data-levelid="{{$gradelevel->id}}" data-semid="{{$eachsem+1}}" data-sydesc="{{$gradelevel->sydesc ?? ''}}">
                                                @csrf
                                                <div class="row eachsem-other-container">
                                                    <div class="col-md-12">
                                                        <label>Remarks</label>
                                                        <textarea class="form-control" name="remarks">@if(isset($eachrecord)){{(collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->count() > 0 ? collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->first()->remarks : '') ?? ''}}@endif</textarea>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small><label>Prepared by:</label></small>
                                                        <input class="form-control form-control-sm text-center" value="{{$eachrecord->teachername ?? ''}}"/>
                                                        <input class="form-control form-control-sm text-center" value="Signature of Adviser over Printed Name" readonly/>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small><label>Certified true & Correct:</label></small>
                                                        <input type="text" class="form-control form-control-sm input-certncorrectname text-center" name="certncorrectname" @if(isset($eachrecord))value="{{(collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->count() > 0 ? collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->first()->certncorrectname : '') ?? ''}}"@endif/>
                                                        <input type="text" class="form-control form-control-sm input-certncorrectdesc text-center" name="certncorrectdesc" @if(isset($eachrecord))value="{{(collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->count() > 0 ? collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->first()->certncorrectdesc : "SHS-School Record's In-Charge") ?? "SHS-School Record's In-Charge"}}"@else value="SHS-School Record's In-Charge" @endif/>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <small><label>Date Checked (MM/DD/YYYY)</label></small>
                                                        <input type="date" class="form-control form-control-sm input-datechecked" name="datechecked" @if(isset($eachrecord))value="{{(collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->count() > 0 ? collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->first()->datechecked : '') ?? ''}}"@endif/>
                                                        <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fa fa-share"></i> Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                            @if($eachrecord->sydesc != null)
                                                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'mcs')
                                                    <fieldset class="form-group border p-2">
                                                        <legend class="w-auto m-0">Attendance</legend>
                                                        <div class="row">
                                                            <div class="col-md-12 text-bold">
                                                            <em><small>Note: Fill in only if your school form template requires the attendance records</small></em>
                                                            </div>
                                                        </div>
                                                        <table class="table mb-1" style="font-size: 14px;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 15%;">Month</th>
                                                                    @if(count($eachrecord->attendance) == 0)
                                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                                    <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                                    @else
                                                                        @for($x = 0; $x < 6; $x++)
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
                                                                    @else
                                                                        @for($x = 0; $x < 6; $x++)
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
                                                                    @else
                                                                        @for($x = 0; $x < 6; $x++)
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
                                                                    @else
                                                                        @for($x = 0; $x < 6; $x++)
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
                                                                    @else
                                                                        @for($x = 0; $x < 6; $x++)
                                                                            @if(isset($eachrecord->attendance[$x]))
                                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum" value="{{$eachrecord->attendance[$x]->numtimestardy ?? ''}}"/></td>
                                                                            @else
                                                                            <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                                            @endif
                                                                        @endfor
                                                                    @endif
                                                                </tr>
                                                            </tbody>
                                                        </table>                                            
                                                        <div class="row">
                                                            <div class="col-md-6 text-left">
                                                                @if(count($eachrecord->attendance) > 0)
                                                                    @if(!collect($eachrecord->attendance[0])->has('type'))
                                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-updateatt" data-studid="{{$studinfo->id}}" data-sydesc="{{$eachrecord->sydesc}}" data-semid="{{$eachrecord->semid}}" data-action="reset"><i class="fa fa-sync"></i> Reset</button>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6 text-right">
                                                                <button type="button" class="btn btn-sm btn-outline-primary btn-updateatt" data-studid="{{$studinfo->id}}" data-sydesc="{{$eachrecord->sydesc}}" data-semid="{{$eachrecord->semid}}" data-action="update"><i class="fa fa-share"></i> Update</button>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                @endif
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
                                                                            <th colspan="4" class="text-left">
                                                                                <label>School Name</label>
                                                                                @if(collect($remedials)->where('type',2)->count()> 0)
                                                                                    @foreach(collect($remedials)->where('type',2)->values() as $remedial)
                                                                                        {{$remedial->schoolname}}
                                                                                    @endforeach
                                                                                @endif
                                                                            </th>
                                                                            <th></th>
                                                                            <th colspan="2" class="text-left">
                                                                                <label>School ID</label>
                                                                                @if(collect($remedials)->where('type',2)->count()> 0)
                                                                                    @foreach(collect($remedials)->where('type',2)->values() as $remedial)
                                                                                        {{$remedial->schoolid}}
                                                                                    @endforeach
                                                                                @endif
                                                                            </th>
                                                                        </tr>
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
                                                                        </tr>
                                                                        <tr class="text-uppercase">
                                                                            <th style="width: 10%;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th>
                                                                            <th>SUBJECTS</th>
                                                                            <th style="width: 10%;">SEM FINAL GRADE</th>
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
                                                                                        <td>{{$remedial->subjectcode}}</td>
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
                                                                            <th colspan="3" class="text-left">
                                                                                <label>School Name</label>
                                                                                @if(collect($remedials)->where('type',2)->count()> 0)
                                                                                    @foreach(collect($remedials)->where('type',2)->values() as $remedial)
                                                                                        <input type="text" class="form-control form-control-sm remedial-schoolname" placeholder="School Name" value="{{$remedial->schoolname}}"/>
                                                                                    @endforeach
                                                                                @else
                                                                                    <input type="text" class="form-control form-control-sm remedial-schoolname" placeholder="School Name"/>
                                                                                @endif
                                                                            </th>
                                                                            <th colspan="2" class="text-left">
                                                                                <label>School ID</label>
                                                                                @if(collect($remedials)->where('type',2)->count()> 0)
                                                                                    @foreach(collect($remedials)->where('type',2)->values() as $remedial)
                                                                                            <input type="number" class="form-control form-control-sm remedial-schoolid" placeholder="School ID" value="{{$remedial->schoolid}}"/>
                                                                                    @endforeach
                                                                                @else
                                                                                    <input type="number" class="form-control form-control-sm remedial-schoolid" placeholder="School ID"/>
                                                                                @endif
                                                                            </th>
                                                                            <th colspan="2" class="text-left">
                                                                                <label>Teacher</label>
                                                                                @if(collect($remedials)->where('type',2)->count()> 0)
                                                                                    @foreach(collect($remedials)->where('type',2)->values() as $remedial)
                                                                                            <input type="text" class="form-control form-control-sm remedial-teachername" placeholder="Name of Teacher/Adviser:" value="{{$remedial->teachername ?? ''}}"/>
                                                                                    @endforeach
                                                                                @else
                                                                                    <input type="text" class="form-control form-control-sm remedial-teachername" placeholder="Name of Teacher/Adviser: "/>
                                                                                @endif
                                                                            </th>
                                                                        </tr>
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
                                                                            <td colspan="2" class="text-left">
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
                                                                                <button type="button" class="btn btn-outline-primary btn-sm btn-edit-editremedialheader" data-studid="{{$studinfo->id}}" data-sydesc="{{$eachrecord->sydesc}}" data-semid="{{$eachrecord->semid}}" data-action="header" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-share"></i> Update</button>
                                                                            </th>
                                                                        </tr>
                                                                        <tr class="text-uppercase">
                                                                            <th style="width: 10%;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th>
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
                                                                                        <td><input type="text" id="subjectcode{{$remedial->id}}" value="{{$remedial->subjectcode}}" class="form-control form-control-sm"/></td>
                                                                                        <td><input type="text" id="subject{{$remedial->id}}" value="{{$remedial->subjectname}}" class="form-control form-control-sm"/></td>
                                                                                        <td><input type="text" id="finalrating{{$remedial->id}}" value="{{$remedial->finalrating}}" class="form-control form-control-sm"/></td>
                                                                                        <td><input type="text" id="remclassmark{{$remedial->id}}" value="{{$remedial->remclassmark}}" class="form-control form-control-sm"/></td>
                                                                                        <td><input type="text" id="recomputedfinal{{$remedial->id}}" value="{{$remedial->recomputedfinal}}" class="form-control form-control-sm"/></td>
                                                                                        <td><input type="text" id="remarks{{$remedial->id}}" value="{{$remedial->remarks}}" class="form-control form-control-sm"/></td>
                                                                                        <td class="text-right"><button type="button" class="btn btn-sm btn-outline-success p-1 btn-edit-editremedial" data-id="{{$remedial->id}}"><i class="fa fa-edit"></i> Update</button> <button type="button" class="btn btn-sm btn-outline-danger p-1 btn-edit-deleteremedial" data-id="{{$remedial->id}}" data-studid="{{$studinfo->id}}" data-sydesc="{{$eachrecord->sydesc}}" data-semid="{{$eachrecord->semid}}" data-action="delete" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-trash-alt text-danger"></i>&nbsp;</button></td>
                                                                                    </tr>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <td style="width: 15%;" class="text-right">
                                                                                <button type="button" class="btn btn-default btn-sm btn-block btn-remedial-addrow" data-studid="{{$studinfo->id}}" data-sydesc="{{$eachrecord->sydesc}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-plus"></i> Subject</button>
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
                                        </div>
                                    @endforeach
                                </div>
                            {{-- @else
                                @php
                                    $eachrecord = collect($records)->where('levelid', $gradelevel->id)->where('semid','1')->first();
                                    $grades = $eachrecord->grades;
                                @endphp
                                <fieldset class="form-group border p-2 fieldset-grades">
                                    <legend class="w-auto m-0">Grades</legend>
                                    <div class="row">
                                        @if($eachrecord->type == 1)
                                            <div class="col-md-12"><span class="badge badge-warning">Auto Generated</span></div>
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
                                                    <td colspan="7" style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schoolname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolname" value="{{$eachrecord->schoolname}}"/>@endif </td>
                                                </tr>
                                                <tr>
                                                    <td>School ID</td>
                                                    <td colspan="7" style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->schoolid}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-schoolid" value="{{$eachrecord->schoolid}}"/>@endif</td>
                                                </tr>
                                                <tr>
                                                    <td>Grade Level</td>
                                                    <td style="width: 30%; border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->levelname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-levelid" data-id="{{$gradelevel->id}}" value="{{$gradelevel->levelname}}" readonly/>@endif</td>
                                                    <td>SY</td>
                                                    <td style="width: 20%; border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->sydesc}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-sydesc" value="{{$eachrecord->sydesc}}"/>@endif</td>
                                                    <td>Sem</td>
                                                    <td style="width: 20%; border-bottom: 1px solid black;">@if($eachrecord->type == 1){{($eachsem+1) == 1 ? '1st' : '2nd'}} @else<input type="text" class="form-control form-control-sm p-0 input-norecord input-semid" data-id="{{$eachsem+1}}" value="{{($eachsem+1) == 1 ? '1st' : '2nd'}}" readonly/>@endif</td>
                                                </tr>
                                            </table>
                                            <table class="table m-0" style="font-size: 11px;">
                                                <tr>
                                                    <td style="width: 20%;">Track</td>
                                                    <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->trackname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-trackname" value="{{$eachrecord->trackname}}"/>@endif</td>
                                                    <td>Strand</td>
                                                    <td style="border-bottom: 1px solid black;">@if($eachrecord->type == 1){{$eachrecord->strandname}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-strandname" value="{{$eachrecord->strandname}}"/>@endif</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 20%;">Section</td>
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
                                            @else
                                                <table class="table table-striped" style="font-size: 12px; table-layout: fixed;">
                                                    <thead class="text-center">
                                                        <tr>
                                                            <th style="width: 15%;">Indication</th>
                                                            <th style="width: 30%;">Subjects</th>
                                                            <th>Q1</th>
                                                            <th>Q2</th>
                                                            <th style="width: 15%;">Final Grade</th>
                                                            <th style="width: 10%;">Action Taken</th>
                                                            @if($eachrecord->type == 1)
                                                            <th style="width: 12%;">&nbsp;</th>                  
                                                            @else
                                                            <th colspan="2">Delete</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>     
                                                        @foreach($grades as $grade)
                                                            <tr @if($eachrecord->type != 1)s class="eachsubject" @endif>
                                                                <td>
                                                                    @if($eachrecord->type == 1)@if(isset($grade->subjcode)) {{$grade->subjcode}} @endif @else<input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="{{$grade->id}}"/><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-subjcode" value="{{$grade->subjcode}}"/>@endif
                                                                </td>
                                                                <td>@if($eachrecord->type == 1){{ucwords(strtolower($grade->subjdesc))}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-subjdesc" value="{{$grade->subjdesc}}"/>@endif</td>

                                                                @if($grade->q1stat != 0)
                                                                        <td class="text-center p-0">
                                                                            <div class="row text-center p-0 m-0">
                                                                                <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline; font-size: 12px; height: 25px !important;" @if($grade->q1stat == 2) value="{{$grade->q1}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade->q1stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="1"  data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}">@if($grade->q1stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs m-0"></i>@endif</button>
                                                                            </div>
                                                                        </td>
                                                                @else
                                                                <td class="text-center">@if($eachrecord->type == 1){{$grade->q1 ?? $grade->q3}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1" value="{{$grade->q1}}"/>@endif</td>
                                                                @endif
                                                                @if($grade->q2stat != 0)
                                                                        <td class="text-center p-0">
                                                                            <div class="row text-center p-0 m-0">
                                                                                <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline; font-size: 12px; height: 25px !important;;" @if($grade->q2stat == 2) value="{{$grade->q2}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade->q2stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="2"  data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}">@if($grade->q2stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs m-0"></i>@endif</button>
                                                                            </div>
                                                                        </td>
                                                                @else
                                                                <td class="text-center">@if($eachrecord->type == 1){{$grade->q2 ?? $grade->q4}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2" value="{{$grade->q2}}"/>@endif</td>
                                                                @endif

                                                                <td class="text-center">@if($eachrecord->type == 1){{$grade->finalrating}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-finalgrade" value="{{$grade->finalrating}}"/>@endif</td>
                                                                
                                                                <td class="text-center">@if($eachrecord->type == 1){{$grade->remarks ?? $grade->actiontaken}}@else<input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{$grade->remarks}}"/>@endif</td>
                                                                @if($eachrecord->type == 1)  
                                                                <td>&nbsp;</td>            
                                                                @else
                                                                <th colspan="2"> <button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-default btn-deletesubject text-sm" data-id="{{$grade->id}}"><i class="fa fa-trash-alt"></i></button></th>
                                                                @endif
                                                            </tr>
                                                        @endforeach      
                                                        @if($eachrecord->type == 1)
                                                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                                                <tr>
                                                                    <td colspan="6" class="p-0 pl-2 pt-2"><em class="text-danger">Note: The added subjects are not included in General Average computation</em></td>
                                                                    <td class="text-center p-0"><button type="button" class="btn btn-default btn-sm m-0 btn-block btn-addsubjinauto" data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-plus"></i> Subject</button></td>
                                                                </tr>
                                                            @else         
                                                            <tr>
                                                                <td class="text-center p-0" ><button type="button" class="btn btn-default btn-sm m-0 btn-block btn-addsubjinauto" data-syid="{{$eachrecord->syid}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-plus"></i> Subject</button></td>
                                                                <td colspan="6" class="p-0 pl-2 pt-2"><em class="text-danger"></em></td>
                                                            </tr>
                                                            @endif
                                                            @if(count($eachrecord->subjaddedforauto)>0)
                                                                @foreach($eachrecord->subjaddedforauto as $customsubjgrade)
                                                                    <tr>
                                                                        <td class="p-0"><input type="text" class="form-control form-control-sm subjcode" value="{{$customsubjgrade->subjcode}}" disabled/></td>
                                                                        <td class="p-0"><input type="text" class="form-control form-control-sm subjdesc" value="{{$customsubjgrade->subjdesc}}" disabled/></td>
                                                                        <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjq1" value="{{$customsubjgrade->q1}}" disabled/></td>
                                                                        <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjq2" value="{{$customsubjgrade->q2}}" disabled/></td>
                                                                        <td class="text-center p-0"><input type="number" class="form-control form-control-sm subjfinalrating" value="{{$customsubjgrade->finalrating}}" disabled/></td>
                                                                        <td class="text-center p-0"><input type="text" class="form-control form-control-sm subjremarks" value="{{$customsubjgrade->actiontaken}}" disabled/></td>
                                                                        <td class="text-right p-0">
                                                                            <button type="button" class="btn btn-sm btn-default btn-subjauto-edit"><i class="fa fa-edit text-warning"></i></button><button type="button" class="btn btn-sm btn-default btn-subjauto-update" data-id="{{$customsubjgrade->id}}" disabled><i class="fa fa-share text-success"></i></button><button type="button" class="btn btn-sm btn-default btn-subjauto-delete" data-id="{{$customsubjgrade->id}}" disabled><i class="fa fa-trash text-danger"></i></button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        @else                         
                                                        <tr>
                                                            <td colspan="8" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                                        </tr>
                                                        @endif        
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-12 text-right">
                                            @if($eachrecord->type != 1)
                                            <button type="button" class="btn btn-sm btn-outline-success btn-updaterecord" data-id="{{$eachrecord->id}}"><i class="fa fa-share"></i> Update Grades Record</button>
                                            @endif
                                        </div>
                                    </div>
                                </fieldset>
                                <form action="/schoolform10/updatesigneachsem" method="POST" class="form-eachsem" data-levelid="{{$gradelevel->id}}" data-semid="{{$eachsem+1}}" data-sydesc="{{$gradelevel->sydesc ?? ''}}">
                                    @csrf
                                    <div class="row eachsem-other-container">
                                        <div class="col-md-12">
                                            <label>Remarks</label>
                                            <textarea class="form-control" name="remarks">@if(isset($eachrecord)){{(collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->count() > 0 ? collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->first()->remarks : '') ?? ''}}@endif</textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <small><label>Prepared by:</label></small>
                                            <input class="form-control form-control-sm text-center" value="{{$eachrecord->teachername ?? ''}}"/>
                                            <input class="form-control form-control-sm text-center" value="Signature of Adviser over Printed Name" readonly/>
                                        </div>
                                        <div class="col-md-4">
                                            <small><label>Certified true & Correct:</label></small>
                                            <input type="text" class="form-control form-control-sm input-certncorrectname text-center" name="certncorrectname" @if(isset($eachrecord))value="{{(collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->count() > 0 ? collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->first()->certncorrectname : '') ?? ''}}"@endif/>
                                            <input type="text" class="form-control form-control-sm input-certncorrectdesc text-center" name="certncorrectdesc" @if(isset($eachrecord))value="{{(collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->count() > 0 ? collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->first()->certncorrectdesc : "SHS-School Record's In-Charge") ?? "SHS-School Record's In-Charge"}}"@else value="SHS-School Record's In-Charge" @endif/>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <small><label>Date Checked (MM/DD/YYYY)</label></small>
                                            <input type="date" class="form-control form-control-sm input-datechecked" name="datechecked" @if(isset($eachrecord))value="{{(collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->count() > 0 ? collect($eachsemsignatories)->where('levelid', $eachrecord->levelid)->where('semid', $eachrecord->semid)->first()->datechecked : '') ?? ''}}"@endif/>
                                            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fa fa-share"></i> Update</button>
                                        </div>
                                    </div>
                                </form>
                                @if($eachrecord->sydesc != null)
                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'mcs')
                                        <fieldset class="form-group border p-2">
                                            <legend class="w-auto m-0">Attendance</legend>
                                            <div class="row">
                                                <div class="col-md-12 text-bold">
                                                <em><small>Note: Fill in only if your school form template requires the attendance records</small></em>
                                                </div>
                                            </div>
                                            <table class="table mb-1" style="font-size: 14px;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 15%;">Month</th>
                                                        @if(count($eachrecord->attendance) == 0)
                                                        <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                        <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                        <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                        <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                        <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                        <th><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-month"/></th>
                                                        @else
                                                            @for($x = 0; $x < 6; $x++)
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
                                                        @else
                                                            @for($x = 0; $x < 6; $x++)
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
                                                        @else
                                                            @for($x = 0; $x < 6; $x++)
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
                                                        @else
                                                            @for($x = 0; $x < 6; $x++)
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
                                                        @else
                                                            @for($x = 0; $x < 6; $x++)
                                                                @if(isset($eachrecord->attendance[$x]))
                                                                <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum" value="{{$eachrecord->attendance[$x]->numtimestardy ?? ''}}"/></td>
                                                                @else
                                                                <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord input-dtardy input-daynum"/></td>
                                                                @endif
                                                            @endfor
                                                        @endif
                                                    </tr>
                                                </tbody>
                                            </table>                                            
                                            <div class="row">
                                                <div class="col-md-6 text-left">
                                                    @if(count($eachrecord->attendance) > 0)
                                                        @if(!collect($eachrecord->attendance[0])->has('type'))
                                                            <button type="button" class="btn btn-sm btn-outline-danger btn-updateatt" data-studid="{{$studinfo->id}}" data-sydesc="{{$eachrecord->sydesc}}" data-semid="{{$eachrecord->semid}}" data-action="reset"><i class="fa fa-sync"></i> Reset</button>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button type="button" class="btn btn-sm btn-outline-primary btn-updateatt" data-studid="{{$studinfo->id}}" data-sydesc="{{$eachrecord->sydesc}}" data-semid="{{$eachrecord->semid}}" data-action="update"><i class="fa fa-share"></i> Update</button>
                                                </div>
                                            </div>
                                        </fieldset>
                                    @endif
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
                                                                <th colspan="4" class="text-left">
                                                                    <label>School Name</label>
                                                                    @if(collect($remedials)->where('type',2)->count()> 0)
                                                                        @foreach(collect($remedials)->where('type',2)->values() as $remedial)
                                                                            {{$remedial->schoolname}}
                                                                        @endforeach
                                                                    @endif
                                                                </th>
                                                                <th></th>
                                                                <th colspan="2" class="text-left">
                                                                    <label>School ID</label>
                                                                    @if(collect($remedials)->where('type',2)->count()> 0)
                                                                        @foreach(collect($remedials)->where('type',2)->values() as $remedial)
                                                                            {{$remedial->schoolid}}
                                                                        @endforeach
                                                                    @endif
                                                                </th>
                                                            </tr>
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
                                                            </tr>
                                                            <tr class="text-uppercase">
                                                                <th style="width: 10%;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th>
                                                                <th>SUBJECTS</th>
                                                                <th style="width: 10%;">SEM FINAL GRADE</th>
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
                                                                            <td>{{$remedial->subjectcode}}</td>
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
                                                                <th colspan="3" class="text-left">
                                                                    <label>School Name</label>
                                                                    @if(collect($remedials)->where('type',2)->count()> 0)
                                                                        @foreach(collect($remedials)->where('type',2)->values() as $remedial)
                                                                            <input type="text" class="form-control form-control-sm remedial-schoolname" placeholder="School Name" value="{{$remedial->schoolname}}"/>
                                                                        @endforeach
                                                                    @else
                                                                        <input type="text" class="form-control form-control-sm remedial-schoolname" placeholder="School Name"/>
                                                                    @endif
                                                                </th>
                                                                <th colspan="2" class="text-left">
                                                                    <label>School ID</label>
                                                                    @if(collect($remedials)->where('type',2)->count()> 0)
                                                                        @foreach(collect($remedials)->where('type',2)->values() as $remedial)
                                                                                <input type="number" class="form-control form-control-sm remedial-schoolid" placeholder="School ID" value="{{$remedial->schoolid}}"/>
                                                                        @endforeach
                                                                    @else
                                                                        <input type="number" class="form-control form-control-sm remedial-schoolid" placeholder="School ID"/>
                                                                    @endif
                                                                </th>
                                                                <th colspan="2" class="text-left">
                                                                    <label>Teacher</label>
                                                                    @if(collect($remedials)->where('type',2)->count()> 0)
                                                                        @foreach(collect($remedials)->where('type',2)->values() as $remedial)
                                                                                <input type="text" class="form-control form-control-sm remedial-teachername" placeholder="Name of Teacher/Adviser:" value="{{$remedial->teachername ?? ''}}"/>
                                                                        @endforeach
                                                                    @else
                                                                        <input type="text" class="form-control form-control-sm remedial-teachername" placeholder="Name of Teacher/Adviser: "/>
                                                                    @endif
                                                                </th>
                                                            </tr>
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
                                                                <td colspan="2" class="text-left">
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
                                                                    <button type="button" class="btn btn-outline-primary btn-sm btn-edit-editremedialheader" data-studid="{{$studinfo->id}}" data-sydesc="{{$eachrecord->sydesc}}" data-semid="{{$eachrecord->semid}}" data-action="header" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-share"></i> Update</button>
                                                                </th>
                                                            </tr>
                                                            <tr class="text-uppercase">
                                                                <th style="width: 10%;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th>
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
                                                                            <td><input type="text" id="subjectcode{{$remedial->id}}" value="{{$remedial->subjectcode}}" class="form-control form-control-sm"/></td>
                                                                            <td><input type="text" id="subject{{$remedial->id}}" value="{{$remedial->subjectname}}" class="form-control form-control-sm"/></td>
                                                                            <td><input type="text" id="finalrating{{$remedial->id}}" value="{{$remedial->finalrating}}" class="form-control form-control-sm"/></td>
                                                                            <td><input type="text" id="remclassmark{{$remedial->id}}" value="{{$remedial->remclassmark}}" class="form-control form-control-sm"/></td>
                                                                            <td><input type="text" id="recomputedfinal{{$remedial->id}}" value="{{$remedial->recomputedfinal}}" class="form-control form-control-sm"/></td>
                                                                            <td><input type="text" id="remarks{{$remedial->id}}" value="{{$remedial->remarks}}" class="form-control form-control-sm"/></td>
                                                                            <td class="text-right"><button type="button" class="btn btn-sm btn-outline-success p-1 btn-edit-editremedial" data-id="{{$remedial->id}}"><i class="fa fa-edit"></i> Update</button> <button type="button" class="btn btn-sm btn-outline-danger p-1 btn-edit-deleteremedial" data-id="{{$remedial->id}}" data-studid="{{$studinfo->id}}" data-sydesc="{{$eachrecord->sydesc}}" data-semid="{{$eachrecord->semid}}" data-action="delete" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-trash-alt text-danger"></i>&nbsp;</button></td>
                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td style="width: 15%;" class="text-right">
                                                                    <button type="button" class="btn btn-default btn-sm btn-block btn-remedial-addrow" data-studid="{{$studinfo->id}}" data-sydesc="{{$eachrecord->sydesc}}" data-semid="{{$eachrecord->semid}}" data-levelid="{{$eachrecord->levelid}}"><i class="fa fa-plus"></i> Subject</button>
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
                                @endif --}}
                                {{-- @if($eachrecord->type == 1)
                                @else
                                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sjaes')
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table" style="width: 100%; font-size: 11px;">
                                                    <tr>
                                                        <th style="width: 15% !important;" class="text-right">Remarks:</th>
                                                        <td colspan="3" style="width: 85% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-semremarks" value="{{$eachrecord->remarks}}"/></td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 15% !important;" class="text-right">Record's In-charge:</th>
                                                        <td style="width: 60% !important;"><input type="text" class="form-control form-control-sm p-1 input-norecord input-recordsincharge" value="{{$eachrecord->recordincharge}}"/></td>
                                                        <th style="width: 15% !important;" class="text-right">Date Checked:</th>
                                                        <td style="width: 10% !important;"><input type="date" class="form-control form-control-sm p-0 input-norecord input-semdatechecked" value="{{$eachrecord->datechecked}}"/></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="row mb-2">
                                            <div class="col-md-12 text-right">
                                                <button type="button" class="btn btn-sm btn-success btn-updaterecord" data-id="{{$eachrecord->id}}"><i class="fa fa-share"></i> Save changes</button>
                                            </div>
                                        </div>
                                @endif --}}
                            {{-- @endif --}}
                        @endif
                    </div>
                    @endfor
                    @endforeach
                    <div class="tab-pane" id="tab_certification">
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
                                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
                                <div class="col-md-2">Remarks: </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="footercopyforupper" placeholder="Enter text here" value="{{$footer->copyforupper}}"/>
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
                                        
                                    </table>
                                </div>
                            @endif
                            {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes') --}}
                            <div class="col-md-6">
                                <label>Certified by</label><br/>
                                <input type="text" class="form-control" id="footerregistrar" placeholder="Enter text here" value="{{$footer->registrar ?? null}}"/>
                            </div>
                            {{-- @endif --}}
                            <div class="col-md-12 text-right d-block">
                                <button type="button" class="btn btn-primary" id="btn-savefooter"><i class="fa fa-share"></i> Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>    
    </div>    
</div>

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
<script>
    $( '#upload_sf10' )
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
    $(document).ready(function(){

        var completerhs = $('#checkbox-completerhs').val()
        var completerjh = $('#checkbox-completerjh').val()
        var peptpasser = $('#checkbox-peptpasser').val()
        var alspasser = $('#checkbox-alspasser').val()

        var infoid ;
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
        $('#btn-eligibility-update-shs').on('click', function(){
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

            var courseschool = $('#input-courseschool').val()
            var courseyear   = $('#input-courseyear').val()
            var coursegenave = $('#input-coursegenave').val()
            
            $.ajax({
                url: '/schoolform10/updateeligibility',
                type: 'post',
                data:{
                        "_token": "{{ csrf_token() }}",
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
                    centername          :   centername,
                    courseschool          :   courseschool,
                    courseyear          :   courseyear,
                    coursegenave          :   coursegenave
                }, success:function(data)
                {

                    toastr.success('Updated successfully!','Eligibility')
                }
            });
        })
        $('.btn-saverecord').hide()
        $('.btn-addrow').on('click', function(){
            var thistbody = $(this).closest('tbody');
            thistbody.append(
                '<tr class="eachsubject">'+
                    '<td style="width: 15%;"><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="0"/><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjcode" placeholder="Ex: Core"/></td>'+
                    '<td style="width: 30%;"><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjdesc" placeholder="Subject"/></td>'+
                    '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q1" placeholder="Grade"/></td>'+
                    '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q2" placeholder="Grade"/></td>'+
                    '<td style="width: 15%;"><input type="number" class="form-control form-control-sm p-0 input-norecord new-inpuy input-finalgrade" placeholder="Final Grade"/></td>'+
                    '<td style="width: 15%;"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-remarks" placeholder="Action Taken"/></td>'+
                    '<td colspan="2"><button type="button" class="btn btn-sm btn-block btn-default p-0 btn-deleterow"><small><i class="fa fa-trash-alt"></i></small></button></td>'+
                '</tr>'
            )
        })
        $(document).on('click','.btn-deleterow', function(){
            $(this).closest('tr').remove()
        })
        $(document).on('input','.input-norecord', function(){
            // console.log('asdasd')
            $(this).closest('.fieldset-grades').find('.btn-saverecord').show()
        })
        $('.btn-saverecord').one('click', function(){
            var thiscardheader = $(this).closest('.tab-pane');
            if(thiscardheader.find('.eachsyrecord').length > 0)
            {
                thiscardheader = $(this).closest('.eachsyrecord');
            }
            var schoolname = thiscardheader.find('.input-schoolname').val();
            var schoolid = thiscardheader.find('.input-schoolid').val();
            var gradelevelid = thiscardheader.find('.input-levelid').attr('data-id');
            var sectionname = thiscardheader.find('.input-sectionname').val();
            var schoolyear = thiscardheader.find('.input-sydesc').val();
            var semester = thiscardheader.find('.input-semid').attr('data-id');
            var trackname = thiscardheader.find('.input-trackname').val();
            var strandname = thiscardheader.find('.input-strandname').val();
            var teachername = thiscardheader.find('.input-adviser').val();
            
            var thistbody = thiscardheader;
            var thistrs = thistbody.find('tr.eachsubject');
            var subjects = [];
            thistrs.each(function(){
                var subjcode    = $(this).find('.input-subjcode').val();
                var subjdesc    = $(this).find('.input-subjdesc').val();
                var q1          = $(this).find('.input-q1').val();
                var q2          = $(this).find('.input-q2').val();
                var finalgrade  = $(this).find('.input-finalgrade').val();
                var remarks     = $(this).find('.input-remarks').val();
                if (subjdesc != null){
                    if(subjdesc.replace(/ /g,'').length > 0)
                    {
                        if(subjcode.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            subjcode = " ";
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
                            subjdesc      : subjdesc,
                            q1              : q1,
                            q2          : q2,
                            final      : finalgrade,
                            remarks      : remarks,
                            fromsystem   : 0,
                            editablegrades   : 0,
                            inmapeh   : 0,
                            intle   : 0
                        };
                        subjects.push(obj);
                    }
                }
            })
            if(subjects.length == 0)
            {
                                toastr.warning('Empty Subjects!')
            }
            // else{
                var semesterremarks = thistbody.find('.input-semremarks').val();
                var recordsincharge = thistbody.find('.input-recordsincharge').val();
                var datechecked =thistbody.find('.input-semdatechecked').val();
                
                $.ajax({
                    url: '/schoolform10/submitnewform',
                    type: 'POST',
                    data:{
                        "_token": "{{ csrf_token() }}",
                        studentid           : '{{$studinfo->id}}',
                        acadprogid          : 5,
                        schoolname          :   schoolname,
                        schoolid            :   schoolid,
                        gradelevelid        :   gradelevelid,
                        trackname           :   trackname,
                        strandname          :   strandname,
                        sectionname         :   sectionname,
                        schoolyear          :   schoolyear,
                        semester            :   semester,
                        teachername         :   teachername,
                        recordsincharge     :   recordsincharge,
                        datechecked         :   datechecked,
                        // indications         :   indications,
                        subjects            :   JSON.stringify(subjects),
                        semesterremarks     :   semesterremarks

                    }, success:function(data)
                    {
                        toastr.success('Record added successfully!')
                        $('#btn-getrecords').click();
                        $('#addcontainer').empty()
                        $('#addrecord').prop('disabled',false)
                    }
                });
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
                            acadprogid      : 5
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
            var thiscardheader = $(this).closest('.tab-pane');
            var schoolname = thiscardheader.find('.input-schoolname').val();
            var schoolid = thiscardheader.find('.input-schoolid').val();
            var gradelevelid = thiscardheader.find('.input-levelid').attr('data-id');
            var sectionname = thiscardheader.find('.input-sectionname').val();
            var schoolyear = thiscardheader.find('.input-sydesc').val();
            var semester = thiscardheader.find('.input-semid').attr('data-id');
            var trackname = thiscardheader.find('.input-trackname').val();
            var strandname = thiscardheader.find('.input-strandname').val();
            var teachername = thiscardheader.find('.input-adviser').val();
            var thistbody = thiscardheader;
            var thistrs = thistbody.find('tr.eachsubject');
            var subjects = [];
            thistrs.each(function(){
                if($(this).find('input').length >0)
                {
                    var subjid    = $(this).find('.input-subjid').val();
                    var subjcode    = $(this).find('.input-subjcode').val();
                    var subjdesc    = $(this).find('.input-subjdesc').val();
                    var q1          = $(this).find('.input-q1').val();
                    var q2          = $(this).find('.input-q2').val();
                    var finalgrade  = $(this).find('.input-finalgrade').val();
                    var remarks     = $(this).find('.input-remarks').val();
                    // if (subjcode != null && subjdesc != null && q1 != null && q2 != null && finalgrade != null && remarks != null){
                        if(subjdesc.replace(/ /g,'').length > 0)
                        {
                            if(subjcode.replace(/^\s+|\s+$/g, "").length == 0)
                            {
                                subjcode = " ";
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
                                id      : subjid,
                                subjcode      : subjcode,
                                subjdesc      : subjdesc,
                                q1              : q1,
                                q2          : q2,
                                final      : finalgrade,
                                remarks      : remarks,
                                fromsystem   : 0,
                                editablegrades   : 0,
                                inmapeh   : 0,
                                intle   : 0
                            };
                            subjects.push(obj);
                        }
                    // }
                }
            })
            console.log(subjects)
            
            if(subjects.length == 0)
            {
                toastr.warning('No Subjects detected!')
            }
            // else{
                var semesterremarks = thistbody.find('.input-semremarks').val();
                var recordsincharge = thistbody.find('.input-recordsincharge').val();
                var datechecked = thistbody.find('.input-semdatechecked').val();
                
                var paramet = {
                        "_token": "{{ csrf_token() }}",
                        studentid           : '{{$studinfo->id}}',
                        acadprogid          : 5,
                        id          : id,
                        schoolname          :   schoolname,
                        schoolid            :   schoolid,
                        gradelevelid        :   gradelevelid,
                        trackname           :   trackname,
                        strandname          :   strandname,
                        sectionname         :   sectionname,
                        schoolyear          :   schoolyear,
                        semester            :   semester,
                        teachername         :   teachername,
                        recordsincharge     :   recordsincharge,
                        datechecked         :   datechecked,
                        // indications         :   indications,
                        subjects            :   JSON.stringify(subjects),
                        semesterremarks     :   semesterremarks
                    }
                $.ajax({
                    url: '/schoolform10/updateform',
                    type: 'POST',
                    data: $.param(paramet)
                    , success:function(data)
                    {
                        console.log(subjects)
                            toastr.success('Record updated successfully!')
                        $('#btn-getrecords').click();
                        $('#addcontainer').empty()
                        $('#addrecord').prop('disabled',false)
                    }
                });
            // }
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
                            acadprogid      : 5
                        },
                        success:function(data){
                            if(data == 1)
                            {
                                toastr.success('Deleted successfully!')
                                $('#btn-getrecords').click();

                            }else{
                                toastr.error('Something went wrong!')
                            }
                        }
                    })
                }
            })
        })
        $('.btn-updateatt').on('click', function(){ //mcs
            var studentid = $(this).attr('data-studid');
            var semid = $(this).attr('data-semid');
            var sydesc = $(this).attr('data-sydesc');
            var action = $(this).attr('data-action');
            var monthinputs =  $(this).closest('.tab-pane').find('.input-month');
            // var months = [];
            // var atttable = $(this).closest('.tab-pane').find('.table-attendance');
            // var atttbody = $(this).closest('.tab-pane').find('.tbody-attendance');
            // monthinputs.each(function(){
            //     var cellindex = $(this).parent().index();
            //     var tdpresent = atttbody[0].children[1].children[cellindex];
            // // console.log(tdpresent)
            //     var eachmonthvalue = $(this).val();
            //     var eachmonthpresent = $(tdpresent).find('input').val();
            //     if(eachmonthvalue.replace(/^\s+|\s+$/g, "").length == 0)
            //     {
            //         eachmonthvalue = 0
            //     }
            //     if(eachmonthpresent.replace(/^\s+|\s+$/g, "").length == 0)
            //     {
            //         eachmonthpresent = 0
            //     }
            //     obj = {
            //         monthdesc : $(this).attr('name'),
            //         numdays : eachmonthvalue,
            //         numdayspresent : eachmonthpresent
            //     }
            //     months.push(obj)
            // })
            var attendance = [];
            monthinputs.each(function(){
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
            if(action == 'reset')
            {
                Swal.fire({
                    title: 'Are you sure you want to reset the attendance?',
                    // text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Reset',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/schoolform10/updateattendance',
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                action          : action,
                                studentid      : studentid,
                                sydesc         : sydesc,
                                semid         : semid,
                                acadprogid     : 5,
                                attendance         : JSON.stringify(attendance)
                            },
                            success:function(data){
                                if(data == 1)
                                {
                                    toastr.success('Attendance reset successfully!')
                                    $('#btn-getrecords').click();

                                }else{
                                    toastr.error('Something went wrong!')
                                }
                            }
                        })
                    }
                })
            }else{                
                $.ajax({
                        url: '/schoolform10/updateattendance',
                    type: 'POST',
                    data: {
                    "_token": "{{ csrf_token() }}",
                        action          : action,
                        studentid      : studentid,
                        sydesc         : sydesc,
                        semid         : semid,
                        acadprogid     : 5,
                        attendance         : JSON.stringify(attendance)
                    },
                    success:function(data){
                        if(data == 1)
                        {
                            toastr.success('Attendance updated successfully!')

                        }else{
                            toastr.error('Something went wrong!')
                        }
                    }
                })
            }
        })
        $(document).on('click','#btn-savefooter', function(){
            var footerstrandaccomplished    = $('#footerstrandaccomplished').val(); 
            var footergenave                = $('#footergenave').val();
            var footerhonorsreceived        = $('#footerhonorsreceived').val(); 
            var footerdategrad              = $('#footerdategrad').val();
            var footerdatecertified         = $('#footerdatecertified').val();
            var footercopyforupper          = $('#footercopyforupper').val();
            var footercopyforlower          = $('#footercopyforlower').val();

            var footerregistrar          = $('#footerregistrar').val();
            

            $.ajax({
                url: '/schoolform10/certification',
                type:"POST",
                dataType:"json",
                data:{
                    "_token": "{{ csrf_token() }}",
                        action               :   'update',
                    studentid               :   '{{$studentid}}',
                    acadprogid              : '{{$acadprogid}}',
                    footerstrandaccomplished: footerstrandaccomplished,
                    footergenave            : footergenave,
                    footerhonorsreceived    : footerhonorsreceived,
                    footerdategrad          : footerdategrad,
                    footerdatecertified     : footerdatecertified,
                    footercopyforupper      : footercopyforupper,
                    footercopyforlower      : footercopyforlower,
                    footerregistrar         : footerregistrar
                },
                // headers: { 'X-CSRF-TOKEN': token },,
                complete: function(){

                    toastr.success('Updated successfully!','Certification')                            

                }
            })
        })
        var counter = 0;
        $('.btn-addsubjinauto').on('click', function(){
            var tbody = $(this).closest('table').find('tbody');
            var syid  = $(this).attr('data-syid');
            var semid  = $(this).attr('data-semid');
            var levelid  = $(this).attr('data-levelid');

            tbody.append(
                '<tr>'+
                    '<td class="p-0"><input type="text" class="form-control form-control-sm subjcode" placeholder="Code"/></td>'+
                    '<td class="p-0"><input type="text" class="form-control form-control-sm subjdesc" placeholder="Description"/></td>'+
                    '<td class="p-0"><input type="number" class="form-control form-control-sm subjq1" placeholder="Q1 Grade"/></td>'+
                    '<td class="p-0"><input type="number" class="form-control form-control-sm subjq2" placeholder="Q2 Grade"/></td>'+
                    '<td class="p-0"><input type="number" class="form-control form-control-sm subjfinalrating" placeholder="Final Grade"/></td>'+
                    '<td class="p-0"><input type="text" class="form-control form-control-sm subjremarks" placeholder="Action Taken"/></td>'+
                    '<td class="p-0 text-right"><button type="button" class="btn btn-default text-success btn-subjauto-save'+counter+' btn-sm" data-syid="'+syid+'" data-semid="'+semid+'" data-levelid="'+levelid+'"><i class="fa fa-share"></i></button><button type="button" class="btn btn-default text-danger removebutton btn-sm"><i class="fa fa-times"></i></button></td>'+
                '</tr>'
            )
            $('.btn-subjauto-save'+counter).on('click', function(){
                var subjcode = $(this).closest('tr').find('.subjcode').val();
                var subjdesc = $(this).closest('tr').find('.subjdesc').val();
                var subjq1 = $(this).closest('tr').find('.subjq1').val();
                var subjq2 = $(this).closest('tr').find('.subjq2').val();
                var subjq3 = 0;
                var subjq4 = 0;
                var subjfinalrating = $(this).closest('tr').find('.subjfinalrating').val();
                var subjremarks = $(this).closest('tr').find('.subjremarks').val();

                if(subjdesc.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('.subjdesc').css('border','1px solid red')
                }else{
                    $(this).closest('tr').find('.subjdesc').removeAttr('style');

                    var syid  = $(this).attr('data-syid');
                    var semid  = $(this).attr('data-semid');
                    var levelid  = $(this).attr('data-levelid');
                    var thistd  = $(this).closest('td')
                    var thistr  = $(this).closest('tr')
                    $.ajax({
                        url: '/schoolform10/addsubjgradesinauto',
                        type:"POST",
                        dataType:"json",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            studentid       :   '{{$studinfo->id}}',
                            acadprogid      : '{{$acadprogid}}',
                            syid            : syid,
                            semid           : semid,
                            levelid         : levelid,
                            subjcode        : subjcode,
                            subjdesc        : subjdesc,
                            subjq1          : subjq1,
                            subjq2          : subjq2,
                            subjq3          : subjq3,
                            subjq4          : subjq4,
                            subjfinalrating : subjfinalrating,
                            subjremarks     : subjremarks
                        },
                        success: function(data){
                            if(data == 0)
                            {
                                    toastr.error('Something went wrong!')
                            }else{
                                    toastr.success('Added successfully!')
                                thistr.find('input').prop('disabled',true)
                                thistd.empty()
                                thistd.append(
                                    '<button type="button" class="btn btn-default text-warning btn-subjauto-edit'+counter+' btn-sm"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-default text-success btn-subjauto-update'+counter+' btn-sm" data-id="'+data+'" disabled><i class="fa fa-share"></i></button><button type="button" class="btn btn-default text-danger btn-subjauto-delete btn-sm" data-id="'+data+'" disabled><i class="fa fa-trash"></i></button>'
                                )
                            }                            

                        }
                    })
                    $('.btn-subjauto-edit'+counter).on('click', function(){
                        $(this).closest('tr').find('input,button').prop('disabled',false)
                    })
                    $('.btn-subjauto-update'+counter).on('click', function(){
                        var subjcode = $(this).closest('tr').find('.subjcode').val();
                        var subjdesc = $(this).closest('tr').find('.subjdesc').val();
                        var subjq1 = $(this).closest('tr').find('.subjq1').val();
                        var subjq2 = $(this).closest('tr').find('.subjq2').val();
                        var subjq3 = 0;
                        var subjq4 = 0;
                        var subjfinalrating = $(this).closest('tr').find('.subjfinalrating').val();
                        var subjremarks = $(this).closest('tr').find('.subjremarks').val();

                        if(subjdesc.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            $(this).closest('tr').find('.subjdesc').css('border','1px solid red')
                        }else{
                            $(this).closest('tr').find('.subjdesc').removeAttr('style');

                            var id          = $(this).attr('data-id');
                            var thisbutton  = $(this);
                            var thistr      = $(this).closest('tr')
                            $.ajax({
                                url: '/schoolform10/updatesubjgradesinauto',
                                type:"POST",
                                dataType:"json",
                                data:{
                                    "_token": "{{ csrf_token() }}",
                                    studentid       :   '{{$studinfo->id}}',
                                    acadprogid      : '{{$acadprogid}}',
                                    id              : id,
                                    subjcode        : subjcode,
                                    subjdesc        : subjdesc,
                                    subjq1          : subjq1,
                                    subjq2          : subjq2,
                                    subjq3          : subjq3,
                                    subjq4          : subjq4,
                                    subjfinalrating : subjfinalrating,
                                    subjremarks     : subjremarks
                                },
                                success: function(data){
                                    if(data == 1)
                                    {
                                            thistr.find('input,button').prop('disabled',true)
                                            thistr.find('.btn-subjauto-edit'+counter).prop('disabled',false)
                                            toastr.success('Added successfully!')
                                    }else{
                                            toastr.error('Something went wrong!')
                                    }                            

                                }
                            })

                        }
                    })
                    $('.btn-subjauto-delete'+counter).on('click', function(){
                        var id          = $(this).attr('data-id');
                        var thistr      = $(this).closest('tr')
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
                                    url: '/schoolform10/deletesubjgradesinauto',
                                    type:"GET",
                                    dataType:"json",
                                    data:{
                                        id: id
                                    },
                                    // headers: { 'X-CSRF-TOKEN': token },,
                                    complete: function(){

                                        toastr.success('Deleted successfully!')

                                                thistr.remove();
                                                

                                    }
                                })
                            }
                        })
                    })
                    

                }
            })
            counter+=1;
        })
        $(document).on('click', '.removebutton', function () {
            $(this).closest('tr').remove();
            // return false;
        });
        $('.btn-subjauto-save').on('click', function(){
            var subjcode = $(this).closest('tr').find('.subjcode').val();
            var subjdesc = $(this).closest('tr').find('.subjdesc').val();
            var subjq1 = $(this).closest('tr').find('.subjq1').val();
            var subjq2 = $(this).closest('tr').find('.subjq2').val();
            var subjq3 = 0;
            var subjq4 = 0;
            var subjfinalrating = $(this).closest('tr').find('.subjfinalrating').val();
            var subjremarks = $(this).closest('tr').find('.subjremarks').val();

            if(subjdesc.replace(/^\s+|\s+$/g, "").length == 0)
            {
                $(this).closest('tr').find('.subjdesc').css('border','1px solid red')
            }else{
                $(this).closest('tr').find('.subjdesc').removeAttr('style');

                var syid  = $(this).attr('data-syid');
                var semid  = $(this).attr('data-semid');
                var levelid  = $(this).attr('data-levelid');
                var thistd  = $(this).closest('td')
                var thistr  = $(this).closest('tr')
                $.ajax({
                    url: '/schoolform10/addsubjgradesinauto',
                    type:"POST",
                    dataType:"json",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        studentid       :   '{{$studinfo->id}}',
                        acadprogid      : '{{$acadprogid}}',
                        syid            : syid,
                        semid           : semid,
                        levelid         : levelid,
                        subjcode        : subjcode,
                        subjdesc        : subjdesc,
                        subjq1          : subjq1,
                        subjq2          : subjq2,
                        subjq3          : subjq3,
                        subjq4          : subjq4,
                        subjfinalrating : subjfinalrating,
                        subjremarks     : subjremarks
                    },
                    success: function(data){
                        if(data == 0)
                        {
                                toastr.error('Something went wrong!')
                        }else{
                                toastr.success('Added successfully!')
                            thistr.find('input').prop('disabled',true)
                            thistd.empty()
                            thistd.append(
                                '<button type="button" class="btn btn-default text-warning btn-subjauto-edit btn-sm"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-default text-success btn-subjauto-update btn-sm" data-id="'+data+'" disabled><i class="fa fa-share"></i></button><button type="button" class="btn btn-default text-danger btn-subjauto-delete btn-sm" data-id="'+data+'" disabled><i class="fa fa-trash"></i></button>'
                            )
                        }                            

                    }
                })

            }
        })
        $('.btn-subjauto-edit').on('click', function(){
            $(this).closest('tr').find('input,button').prop('disabled',false)
        })
        $('.btn-subjauto-update').on('click', function(){
            var subjcode = $(this).closest('tr').find('.subjcode').val();
            var subjdesc = $(this).closest('tr').find('.subjdesc').val();
            var subjq1 = $(this).closest('tr').find('.subjq1').val();
            var subjq2 = $(this).closest('tr').find('.subjq2').val();
            var subjq3 = 0;
            var subjq4 = 0;
            var subjfinalrating = $(this).closest('tr').find('.subjfinalrating').val();
            var subjremarks = $(this).closest('tr').find('.subjremarks').val();

            if(subjdesc.replace(/^\s+|\s+$/g, "").length == 0)
            {
                $(this).closest('tr').find('.subjdesc').css('border','1px solid red')
            }else{
                $(this).closest('tr').find('.subjdesc').removeAttr('style');

                var id          = $(this).attr('data-id');
                var thisbutton  = $(this);
                var thistr      = $(this).closest('tr')
                $.ajax({
                    url: '/schoolform10/updatesubjgradesinauto',
                    type:"POST",
                    dataType:"json",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        studentid       :   '{{$studinfo->id}}',
                        acadprogid      : '{{$acadprogid}}',
                        id              : id,
                        subjcode        : subjcode,
                        subjdesc        : subjdesc,
                        subjq1          : subjq1,
                        subjq2          : subjq2,
                        subjq3          : subjq3,
                        subjq4          : subjq4,
                        subjfinalrating : subjfinalrating,
                        subjremarks     : subjremarks
                    },
                    success: function(data){
                        if(data == 1)
                        {
                                thistr.find('input,button').prop('disabled',true)
                                thistr.find('.btn-subjauto-edit').prop('disabled',false)
                                toastr.success('Added successfully!')
                        }else{
                                toastr.error('Something went wrong!')
                        }                            

                    }
                })

            }
        })
        $('.btn-subjauto-delete').on('click', function(){
            var id          = $(this).attr('data-id');
            var thistr      = $(this).closest('tr')
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
                        url: '/schoolform10/deletesubjgradesinauto',
                        type:"GET",
                        dataType:"json",
                        data:{
                            id: id
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(){

                            toastr.success('Deleted successfully!')

                                    thistr.remove();
                                    

                        }
                    })
                }
            })
        })
        
        
        $('.btn-addinauto').on('click',function(){
            var subjectid   = $(this).attr('data-subjid');
            var quarter     = $(this).attr('data-quarter');
            var semid        = $(this).attr('data-semid');
            var syid        = $(this).attr('data-syid');
            var levelid     = $(this).attr('data-levelid');
            var gradevalue  = $(this).closest('.row').find('input').val();
            var thisbutton  = $(this).closest('.row').find('button');
            if(gradevalue.replace(/^\s+|\s+$/g, "").length==0)
            {
                $(this).closest('.row').find('input').css('border','1px solid red');
                toastr.warning('This field is empty!')
            }else{
                $(this).closest('.row').find('input').removeAttr('style');
                $.ajax({
                    url: '/schoolform10/addinauto',
                    type:"POST",
                    dataType:"json",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        studentid               : '{{$studinfo->id}}',
                        acadprogid              : '{{$acadprogid}}',
                        subjectid               : subjectid,
                        quarter                 : quarter,
                        syid                    : syid,
                        semid                   : semid,
                        levelid                 : levelid,
                        gradevalue              : gradevalue
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    success: function(data){

                        if(data == 1)
                        {
                toastr.success('Added successfully!')
                            thisbutton.removeClass('btn-addinauto');
                            thisbutton.empty()
                            thisbutton.append('<i class="fa fa-edit fa-xs"></i>')
                        }else{
                toastr.error('Something went wrong!')
                        }                             

                    }
                })
            }
        })
        var counter = 0;
        $('.btn-remedial-addrow').on('click', function(){
            var studentid = $(this).attr('data-studid');
            var semid = $(this).attr('data-semid');
            var sydesc = $(this).attr('data-sydesc');
            var levelid = $(this).attr('data-levelid');
            
            $(this).closest('table').find('#remedial-tbody').append(
                `
                <tr>
                <td><input type="text" value="" class="form-control form-control-sm" name="add-new-subject"/></td>
                <td><input type="text" value="" class="form-control form-control-sm" name="add-new-subjectcode"/></td>
                <td><input type="text" value="" class="form-control form-control-sm" name="add-new-finalrating"/></td>
                <td><input type="text" value="" class="form-control form-control-sm" name="add-new-classmark"/></td>
                <td><input type="text" value="" class="form-control form-control-sm" name="add-new-recomputed"/></td>
                <td><input type="text" value="" class="form-control form-control-sm" name="add-new-remarks"/></td>
                <td class="text-right"><button type="button" class="btn btn-sm btn-outline-success p-1 btn-edit-addremedial" id="btn-edit-addremedial`+levelid+`-`+semid+`-`+counter+`" data-studid="`+studentid+`" data-sydesc="`+sydesc+`" data-semid="`+semid+`" data-action="add" data-levelid="`+levelid+`"><i class="fa fa-edit"></i> Save</button> <button type="button" class="btn btn-sm btn-outline-danger p-1  removebutton"><i class="fa fa-trash-alt text-danger"></i>&nbsp;</button></td>
                </tr>
                `
            );
            $('#btn-edit-addremedial'+levelid+'-'+semid+'-'+counter).on('click', function(){
                var thisbutton = $(this);
                var studentid = $(this).attr('data-studid');
                var semid = $(this).attr('data-semid');
                var sydesc = $(this).attr('data-sydesc');
                var levelid = $(this).attr('data-levelid');
                var action = $(this).attr('data-action');

                var addsubject     = $(this).closest('tr').find('input[name="add-new-subject"]').val()
                var addsubjectcode     = $(this).closest('tr').find('input[name="add-new-subjectcode"]').val()
                var addfinalrating = $(this).closest('tr').find('input[name="add-new-finalrating"]').val()
                var addclassmark   = $(this).closest('tr').find('input[name="add-new-classmark"]').val()
                var addrecomputed  = $(this).closest('tr').find('input[name="add-new-recomputed"]').val()
                var addremarks     = $(this).closest('tr').find('input[name="add-new-remarks"]').val()

                var validationcheck = 0;

                if(addsubjectcode.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-subjectcode"]').css('border','1px solid red');
                }else{
                    $(this).closest('tr').find('input[name="add-new-subjectcode"]').removeAttr('style')
                }

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
                    }else if(action == 'editremedial')
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
                            semid         : semid,
                            levelid         : levelid,
                            acadprogid : 5,
                            subjectcode: addsubjectcode,
                            subject: addsubject,
                            finalrating: addfinalrating,
                            remclassmark: addclassmark,
                            recomputedfinal: addrecomputed,
                            remarks: addremarks
                        },
                        dataType: 'json',
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){
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
        $('.btn-edit-editremedialheader').on('click', function(){
            var thistable = $(this).closest('table');
            var conductdatefrom     = thistable.find('.remedial-datefrom').val();
            var conductdateto       = thistable.find('.remedial-dateto').val();
            var schoolname       = thistable.find('.remedial-schoolname').val();
            var schoolid       = thistable.find('.remedial-schoolid').val();
            var teachername       = thistable.find('.remedial-teachername').val();
            var studentid = $(this).attr('data-studid');
            var semid = $(this).attr('data-semid');
            var sydesc = $(this).attr('data-sydesc');
            var levelid = $(this).attr('data-levelid');
            var action = $(this).attr('data-action');

            // var validationcheck = 0;
            // if(conductdatefrom.replace(/^\s+|\s+$/g, "").length == 0)
            // {
            //     $('#remedial-datefrom').css('border','1px solid red');
            // }else{
            //     validationcheck+=1;
            //     $('#remedial-datefrom').removeAttr('style');
            // }
            // if(conductdateto.replace(/^\s+|\s+$/g, "").length == 0)
            // {
            //     $('#remedial-dateto').css('border','1px solid red');
            // }else{
            //     conductdateto+=1;
            //     $('#remedial-dateto').removeAttr('style');
            // }
            
            // if(validationcheck == 2)
            // {
                $.ajax({
                    url: '/schoolform10/updateremedialheader',
                    type:"GET",
                    dataType:"json",
                    data:{
                        action          : action,
                        studentid      : studentid,
                        sydesc         : sydesc,
                        semid         : semid,
                        levelid         : levelid,
                        acadprogid     : 5,
                        conductdatefrom: conductdatefrom,
                        conductdateto: conductdateto,
                        schoolname: schoolname,
                        teachername: teachername,
                        schoolid: schoolid
                    },
                    success: function(){

                        toastr.success('Updated successfully!')
                        
                    }
                })
            // }

        })
        
        $('.btn-edit-editremedial').on('click', function(){
            var thisbutton = $(this);
            var studentid = $(this).attr('data-studid');
            var remedialid = $(this).attr('data-id');
            var sydesc = $(this).attr('data-sydesc');
            var levelid = $(this).attr('data-levelid');
            var action = $(this).attr('data-action');
                var semid = $(this).attr('data-semid');

                var editsubjectcode     = $(this).closest('tr').find('input[name="add-new-subjectcode"]').val()
            var editsubjectcode     = $(this).closest('tr').find('#subjectcode'+$(this).attr('data-id')).val()
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
                        semid         : semid,
                        levelid         : levelid,
                        acadprogid : 5,
                        remedialid:remedialid,
                            subjectcode: editsubjectcode,
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
            console.log(thistr)
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
        $('.btn-editinauto').on('click',function(){
            var subjectid   = $(this).attr('data-subjid');
            var quarter     = $(this).attr('data-quarter');
            var semid        = $(this).attr('data-semid');
            var syid        = $(this).attr('data-syid');
            var levelid     = $(this).attr('data-levelid');
            var gradevalue  = $(this).closest('.row').find('input').val();
            var thisbutton  = $(this).closest('.row').find('button');

            if(gradevalue.replace(/^\s+|\s+$/g, "").length==0)
            {
                $(this).closest('.row').find('input').css('border','1px solid red');
                toastr.warning('This field is empty!')
            }else{
                $(this).closest('.row').find('input').removeAttr('style');
                $.ajax({
                    url: '/schoolform10/editinauto',
                    type:"post",
                    dataType:"json",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        studentid               : '{{$studinfo->id}}',
                        acadprogid              : '{{$acadprogid}}',
                        subjectid               : subjectid,
                        quarter                 : quarter,
                        syid                    : syid,
                        semid                   : semid,
                        levelid                 : levelid,
                        gradevalue              : gradevalue
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    success: function(data){

                        if(data == 1)
                        {
                toastr.success('Updated successfully!')
                
                            thisbutton.removeClass('btn-addinauto');
                            thisbutton.empty()
                            thisbutton.append('<i class="fa fa-edit fa-xs"></i>')
                        }else{
                toastr.error('Something went wrong!')
                        }                             

                    }
                })
            }
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
        $('.btn-exportform-shs').on('click', function(){
            var papersize = $('#select-papersize').val()
            var formexport = 1;
            var layout = $(this).attr('data-layout')
            var format = $(this).attr('data-format')
            var exporttype = $(this).attr('data-exporttype')
            var studentid   = '{{$studentid}}';
            var acadprogid   = '{{$acadprogid}}';
            window.open('/schoolform10/getrecordssenior?export='+formexport+'&format='+format+'&exporttype='+exporttype+'&studentid='+studentid+'&acadprogid='+acadprogid+'&layout='+layout+'&papersize='+papersize,'_blank')
        })
    })

</script>