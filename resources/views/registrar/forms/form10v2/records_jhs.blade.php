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
            <div class="col-4">
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
<div class="row mb-2">
    <div class="col-md-12">
        <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#modal-addnew"><i class="fa fa-plus"></i> Add new record</button>
    <div class="modal fade" id="modal-addnew">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add new Record</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                      <label>Select Grade Level</label>
                      <select class="form-control" id="select-addnewlevelid">
                          @foreach($gradelevels as $gradelevel)
                              <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                          @endforeach
                      </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                      <label>S.Y</label>
                      <input type="text" class="form-control" id="input-addnewschoolyear"/>
                      <small>
                          <em>Note:</em>
                          <ol>
                              <li>Example: <strong>2019-2020</strong></li>
                              <li>Should be 9 characters only</li>
                              <li>Avoid white spaces.</li>
                              <li></li>
                              <li></li>
                          </ol>
                      </small>
                      {{-- <select class="form-control" id="select-addnewlevelid">
                          @foreach($gradelevels as $gradelevel)
                              <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                          @endforeach
                      </select> --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal" id="button-closeadd">Close</button>
              <button type="button" class="btn btn-primary" id="button-submitadd">Add</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    </div>
</div>
<div id="addcontainer"></div>
@if(count($gradelevels)>0)
    @foreach($gradelevels as $gradelevel)
        <div class="card">
            <div class="card-header">
                <div class="row">
                    @if($gradelevel->recordinputype == 0)
                        <div class="col-md-12 mb-2">@if($gradelevel->recordinputype != 0 && $gradelevel->recordinputype != 3)<span class="badge badge-success"> Uploaded</span> @if($gradelevel->autoexists == 1)<span class="badge badge-default" style="border: 1px solid #ddd;"> Select Auto-generated record</span>@endif @elseif($gradelevel->recordinputype == 0)<span class="badge badge-success" > Auto-generated</span> @if($gradelevel->manualexists == 1)<span class="badge badge-default" style="border: 1px solid #ddd;"> Select Manual Record</span>@endif @endif @if($gradelevel->syid == DB::table('sy')->where('isactive','1')->first()->id)<span class="badge badge-success">Current School Year</span>@endif</div>
                        <div class="col-md-12">
                            <table class="m-0" style="font-size: 13.5px; width: 100%; table-layout: fixed;">
                                <tr>
                                    <td style="width: 15%;">School</td>
                                    <td colspan="5" style="border-bottom: 1px solid #ddd;">{{$gradelevel->headerinfo[0]->schoolname}}</td>
                                </tr>
                                <tr>
                                    <td>School ID</td>
                                    <td colspan="5" style="border-bottom: 1px solid #ddd;">{{$gradelevel->headerinfo[0]->schoolid}}</td>
                                </tr>
                                <tr>
                                    <td>District</td>
                                    <td style="width: 20%; border-bottom: 1px solid #ddd;">{{$gradelevel->headerinfo[0]->schooldistrict}}</td>
                                    <td class="text-right">Division&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;">{{$gradelevel->headerinfo[0]->schooldivision}}</td>
                                    <td class="text-right">Region&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;">{{$gradelevel->headerinfo[0]->schoolregion}}</td>
                                </tr>
                                <tr>
                                    <td>Grade Level</td>
                                    <td style="border-bottom: 1px solid #ddd;" colspan="3">{{$gradelevel->levelname}}</td>
                                    <td class="text-right">School Year&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;">{{$gradelevel->sydesc}}</td>
                                </tr>
                            </table>
                            <table class="m-0" style="font-size: 13.5px; width: 100%; table-layout: fixed;">
                                <tr>
                                    <td style="width: 15%;">Section</td>
                                    <td style="border-bottom: 1px solid #ddd;">{{$gradelevel->headerinfo[0]->sectionname}}</td>
                                    <td style="width: 15%;" class="text-right">Adviser&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;">{{$gradelevel->headerinfo[0]->teachername}}</td>
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
                                    <td colspan="5" style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-schoolname"/> </td>
                                </tr>
                                <tr>
                                    <td>School ID</td>
                                    <td colspan="5" style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-schoolid"/></td>
                                </tr>
                                <tr>
                                    <td>District</td>
                                    <td style="width: 20%; border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-district"/></td>
                                    <td class="text-right">Division&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-division"/></td>
                                    <td class="text-right">Region&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header  input-region"/></td>
                                </tr>
                                <tr>
                                    <td>Grade Level</td>
                                    <td style="border-bottom: 1px solid #ddd;" colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-levelid" data-id="{{$gradelevel->id}}" value="{{$gradelevel->levelname}}" readonly/></td>
                                    <td class="text-right">School Year&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;">@if($gradelevel->recordinputype == 1){{$gradelevel->sydesc}}@else<input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-sydesc text-center" value="{{$gradelevel->sydesc}}" placeholder="Ex: 2019-2020"/>@endif</td>
                                </tr>
                            </table>
                            <table class="m-0" style="font-size: 13.5px; width: 100%;">
                                <tr>
                                    <td style="width: 15%;">Section</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-sectionname"/></td>
                                    <td class="text-right">Adviser&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-adviser"/></td>
                                </tr>
                            </table>
                            @else
                            <table class="m-0" style="font-size: 13.5px; width: 100%;">
                                <tr>
                                    <td style="width: 15%;">School</td>
                                    <td colspan="5" style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-schoolname" value="{{$gradelevel->headerinfo[0]->schoolname}}"/></td>
                                </tr>
                                <tr>
                                    <td>School ID</td>
                                    <td colspan="5" style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-schoolid" value="{{$gradelevel->headerinfo[0]->schoolid}}"/></td>
                                </tr>
                                <tr>
                                    <td>District</td>
                                    <td style="width: 20%; border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-district" value="{{$gradelevel->headerinfo[0]->schooldistrict}}"/></td>
                                    <td class="text-right">Division&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-division" value="{{$gradelevel->headerinfo[0]->schooldivision}}"/></td>
                                    <td class="text-right">Region&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-region" value="{{$gradelevel->headerinfo[0]->schoolregion}}"/></td>
                                </tr>
                                <tr>
                                    <td>Grade Level</td>
                                    <td style="border-bottom: 1px solid #ddd;" colspan="3"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-levelid" data-id="{{$gradelevel->id}}" value="{{$gradelevel->levelname}}" readonly/></td>
                                    <td class="text-right">School Year&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-sydesc text-center" value="{{$gradelevel->sydesc}}" placeholder="Ex: 2019-2020"/></td>
                                </tr>
                            </table>
                            <table class="m-0" style="font-size: 13.5px; width: 100%;">
                                <tr>
                                    <td style="width: 15%;">Section</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-sectionname" value="{{$gradelevel->headerinfo[0]->sectionname}}"/></td>
                                    <td class="text-right">Adviser&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="border-bottom: 1px solid #ddd;"><input type="text" class="form-control form-control-sm p-0 input-norecord input-header input-adviser" value="{{$gradelevel->headerinfo[0]->teachername}}"/></td>
                                </tr>
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
                                        <th style="width: 30%;">Subjects</th>
                                        <th>1st</th>
                                        <th>2nd</th>
                                        <th>3rd</th>
                                        <th>4th</th>
                                        <th style="width: 8%;">Final</th>
                                        <th style="width: 15%;">Remarks</th>
                                        <th style="width: 8%;">Credit Earned</th>
                                    </tr>
                                </thead>
                                <tbody class="gradescontainer">    
                                </tbody>
                            </table>

                        @else
                            <table class="table" style="font-size: 13.5px;">
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
                                {{-- @if($gradelevel->type == 1)
                                @else                         
                                <tr>
                                    <td colspan="10" class="text-right"><button type="button" class="btn btn-sm p-0 pr-1 pl-1 btn-outline-success btn-addrow"><small><i class="fa fa-plus"></i> &nbsp;&nbsp;Add subject</small></button></td>
                                </tr>
                                @endif      --}}
                                <tbody class="gradescontainer">     
                                    @foreach($gradelevel->grades as $grade)
                                    
                                                <tr>
                                                    <td>@if($grade->inMAPEH == 1) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@endif{{ucwords(strtolower($grade->subjdesc))}}</td>
                                                    @if($gradelevel->syid == DB::table('sy')->where('isactive','1')->first()->id)
                                                    <td class="text-center">{{$grade->q1}}</td>
                                                    <td class="text-center">{{$grade->q2}}</td>
                                                    <td class="text-center">{{$grade->q3}}</td>
                                                    <td class="text-center">{{$grade->q4}}</td>
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
                                                        @if($grade->q3stat == 0)
                                                        <td class="text-center">{{$grade->q3}}</td>
                                                        @else
                                                            <td class="text-center p-0">
                                                                <div class="row text-center p-0 m-0">
                                                                    <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline;" @if($grade->q3stat == 2) value="{{$grade->q3}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade->q3stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="3"  data-syid="{{$gradelevel->syid}}"  data-levelid="{{$gradelevel->id}}">@if($grade->q3stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs"></i>@endif</button>
                                                                </div>
                                                            </td>
                                                        @endif
                                                        @if($grade->q4stat == 0)
                                                        <td class="text-center">{{$grade->q4}}</td>
                                                        @else
                                                            <td class="text-center p-0">
                                                                <div class="row text-center p-0 m-0">
                                                                    <input type="number" class="form-control form-control-sm p-0 col-8 text-center" style="display: inline;" @if($grade->q4stat == 2) value="{{$grade->q4}}" @endif/><button type="button" class="btn btn-default col-4 p-0 @if($grade->q4stat == 1) btn-addinauto @else btn-editinauto @endif"  data-subjid="{{$grade->subjid}}" data-quarter="4"  data-syid="{{$gradelevel->syid}}"  data-levelid="{{$gradelevel->id}}">@if($grade->q4stat == 2)<i style="display: inline;" class="fa fa-edit fa-xs"></i>@else <i style="display: inline;" class="fa fa-plus fa-xs"></i>@endif</button>
                                                                </div>
                                                            </td>
                                                        @endif
                                                    @endif
                                                    <td class="text-center">@if($grade->inMAPEH == 0){{$grade->finalrating}}@endif</td>
                                                    <td class="text-center">@if($grade->inMAPEH == 0){{isset($grade->actiontaken) ? $grade->actiontaken : $grade->remarks}}@endif</td>
                                                </tr>
                                                
                                    @endforeach  
                                    @if(count($gradelevel->generalaverage)>0)
                                        <tr>
                                            <td>{{ucwords(strtolower(collect($gradelevel->generalaverage)->first()->subjdesc))}}</td>
                                            <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q1}}</td>
                                            <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q2}}</td>
                                            <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q3}}</td>
                                            <td class="text-center">{{collect($gradelevel->generalaverage)->first()->q4}}</td>
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
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
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
                                        <th>Indent</th>
                                        <th style="width: 30%;">Subjects</th>
                                        <th>1st</th>
                                        <th>2nd</th>
                                        <th>3rd</th>
                                        <th>4th</th>
                                        <th style="width: 8%;">Final</th>
                                        <th style="width: 13%;">Remarks</th>
                                        <th style="width: 8%;">Credit Earned</th>
                                        <th style="width: 15%;">Actions</th>
                                    </tr>
                                </thead>
                                @if(count($gradelevel->grades)>0)
                                    @foreach($gradelevel->grades as $grade)
                                        <tr>
                                            <td><input type="checkbox" class="form-control" id="{{$grade->id}}" style="width: 20px;height: 20px;" @if($grade->inMAPEH == 1) checked @endif></td>
                                            <td><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="{{$grade->id}}"/><input type="text" class="form-control form-control-sm p-0 input-norecord input-subjdesc" value="{{$grade->subjdesc}}"/></td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q1" value="{{$grade->q1}}"/></td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q2" value="{{$grade->q2}}"/></td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q3" value="{{$grade->q3}}"/></td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-q4" value="{{$grade->q4}}"/></td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-finalgrade" value="{{$grade->finalrating}}"/></td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-remarks" value="{{isset($grade->actiontaken) ? $grade->actiontaken : $grade->remarks}}"/></td>
                                            <td class="text-center"><input type="text" class="form-control form-control-sm text-center p-0 input-norecord input-credits" value="{{isset($grade->credits) ? $grade->credits : 0}}"/></td>
                                            <th><button type="button" class="btn btn-sm btn-outline-warning btn-updatesubject text-sm" data-id="{{$grade->id}}">Update</button> <button type="button" class="btn btn-sm btn-outline-danger btn-deletesubject text-sm" data-id="{{$grade->id}}"><i class="fa fa-trash-alt"></i></button></th>
                                        </tr>
                                    @endforeach 
                                @endif
                                <tr>
                                    <th colspan="10" class="text-right"><button type="button" class="btn btn-sm btn-outline-success btn-addrow mt-2"><i class="fa fa-plus"></i> Add Subject</button></th>
                                </tr>
                                <tbody class="gradescontainer">  
                                    @if(count($gradelevel->grades) == 0)
                                        @if(count($subjects)>0)
                                            @foreach($subjects as $subject)
                                                <tr class="eachsubject">
                                                    <td style="vertical-align: middle !important;"><input type="checkbox" class="form-control" style="width: 20px;height: 20px;"></td>
                                                    <td><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="0"/><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjdesc" placeholder="Subject" value="{{$subject->subjdesc}}"/></td>
                                                    <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q1 text-center"/></td>
                                                    <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q2 text-center"/></td>
                                                    <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q3 text-center"/></td>
                                                    <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q4 text-center"/></td>
                                                    <td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-finalgrade text-center" placeholder="Final"/></td>
                                                    <td><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-remarks" placeholder="Remarks"/></td>
                                                    <td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord new-input input-credits text-center" placeholder="Credits"/></td>
                                                    <td><button type="button" class="btn btn-sm btn-block btn-default btn-deleterow" style="height: unset;">&nbsp;<i class="fa fa-trash-alt"></i>&nbsp;</button></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                </tbody>
                                <tfoot hidden>
                                    <tr>
                                        <th colspan="10" class="text-right">
                                            <button type="button" class="btn btn-sm btn-success btn-updaterecord mt-2" data-id="{{$gradelevel->headerinfo[0]->id}}"><i class="fa fa-share"></i> Save changes</button>
                                        </th>
                                    </tr>    
                                </tfoot>
                                <script>
                                    if($('.gradescontainer').find('.eachsubject').length >0)
                                    {
                                        $('.gradescontainer').closest('.table').find('tfoot').removeAttr('hidden')
                                    }
                                </script>
                            </table>
                        </div>
                    </div>
                @endif
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
<script>    
    var completer = $('#checkbox-completer').val()
    var peptpasser = $('#checkbox-peptpasser').val()
    var alspasser = $('#checkbox-alspasser').val()
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
    $('#btn-eligibility-update').on('click', function(){
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

        $.ajax({
            url: '/reports_schoolform10/updateeligibility',
            type: 'GET',
            data:{
                studentid           : '{{$studentid}}',
                acadprogid          : '{{$acadprogid}}',
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
                totalnoofyears      :   totalnoofyears
            }, success:function(data)
            {
                toastr.success('Updated successfully!','Eligibility')
            }
        });
    })
    $('#button-submitadd').on('click', function(){
        var schoolyearvalue = $('#input-addnewschoolyear').val();
        // console.log($('#input-addnewschoolyear'))
        if(schoolyearvalue.replace(/^\s+|\s+$/g, "").length < 9 || schoolyearvalue.replace(/^\s+|\s+$/g, "").length > 9)
        {
            $('#input-addnewschoolyear').css('border','1px solid red')
        }else{
            $('#input-addnewschoolyear').removeAttr('style')
            var levelidvalue = $('#select-addnewlevelid').val();
            $.ajax({
            url: '/reports_schoolform10v2/addnewrecord',
                type: 'GET',
                data:{
                    studentid           :   '{{$studentid}}',
                    acadprogid  : '{{$acadprogid}}',
                    levelid     : levelidvalue,
                    schoolyear  : schoolyearvalue
                }, success:function(data)
                {
                    $('#addcontainer').append(data);
                    $('#button-closeadd').click()
                    $('#addrecord').prop('disabled', true)
                    // $('#card-body-elem-addsubjects').hide()
                }
            });

        }
        // select-addnewlevelid

    })
            $(document).on('click', '.removebutton', function () {
                $(this).closest('tr').remove();
                // return false;
            });
            $(document).on('click','.removeCard', function () {
                $(this).closest('.card').remove();
                $('#addrecord').removeAttr('disabled')
                return false;
            });
    // var rowcount = 0;
    $('.btn-addrow').on('click', function(){
        var thistbody = $(this).closest('table').find('.gradescontainer');
        thistbody.append(
            '<tr class="eachsubject">'+
                '<td style="vertical-align: middle;"><input type="checkbox" class="form-control" style="width: 20px;height: 20px;"></td>'+
                '<td><input type="hidden" class="form-control form-control-sm text-center p-0 input-subjid" value="0"/><input type="text" class="form-control form-control-sm p-0 input-norecord new-input input-subjdesc" placeholder="Subject"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q1 text-center"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q2 text-center"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q3 text-center"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-q4 text-center"/></td>'+
                '<td><input type="number" class="form-control form-control-sm p-0 input-norecord new-input input-finalgrade text-center" placeholder="Final"/></td>'+
                '<td><input type="text" class="form-control form-control-sm text-center p-0 input-norecord new-input input-remarks" placeholder="Remarks"/></td>'+
                '<td><input type="number" class="form-control form-control-sm text-center p-0 input-norecord new-input input-credits text-center" placeholder="Credits"/></td>'+
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
        var subjdescinputs = $(this).closest('.gradescontainer').find('.input-subjdesc');
        var q1inputs = $(this).closest('.gradescontainer').find('.input-q1');
        var q2inputs = $(this).closest('.gradescontainer').find('.input-q2');
        var q3inputs = $(this).closest('.gradescontainer').find('.input-q3');
        var q4inputs = $(this).closest('.gradescontainer').find('.input-q4');
        var inputwithval = 0;
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
            $(this).closest('.card-body').find('tfoot').prop('hidden',true);
        }else{
            $(this).closest('.card-body').find('tfoot').removeAttr('hidden');
        }
    })
    $('.btn-updaterecord').on('click', function(){
        var studentid = $('#select-studentid').val();
        var acadprogid = $('#select-acadprogid').val();
        var recordid = $(this).attr('data-id')
        var thistrs = $(this).closest('.card-body').find('tr.eachsubject');
        var eachsubjects = [];
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
        var indentcheck = $(this).closest('tr').find('input[type="checkbox"]:checked');
        var subjid    = $(this).closest('tr').find('.input-subjid').val();
        var subjdesc    = $(this).closest('tr').find('.input-subjdesc').val();
        var q1          = $(this).closest('tr').find('.input-q1').val();
        var q2          = $(this).closest('tr').find('.input-q2').val();
        var q3          = $(this).closest('tr').find('.input-q3').val();
        var q4          = $(this).closest('tr').find('.input-q4').val();
        var finalgrade  = $(this).closest('tr').find('.input-finalgrade').val();
        var remarks     = $(this).closest('tr').find('.input-remarks').val();
        var credits     = $(this).closest('tr').find('.input-credits').val();
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
        }
        $.ajax({
            url: '/reports_schoolform10v2/updatesubject',
            type: 'GET',
            data: {
                id              : id,
                acadprogid      : acadprogid,
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
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                }) 

        
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

        // // inputs.append('lrn',$('#lrn').val())
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
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    $('#modal-lg').modal('show')
                }
            
                ,error:function(){
                    toastr.error('Something went wrong! PLease make sure it is the correct file!')
                }
        })
        e.preventDefault();
    })
</script>