
@extends($extends.'.layouts.app')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{asset('plugins/jquery-year-picker/css/yearpicker.css')}}" />
    <style>
        td                                          { border-bottom: hidden; }
        input[type=text], .input-group-text, .select{ background-color: white !important; border: hidden; border-bottom: 2px solid #ddd; font-size: 12px !important; }
        .input-group-text                           { border-bottom: hidden; }
        .fontSize                                   { font-size: 12px; }
        .container                                  { overflow-x: scroll !important; }
        table                                       { width: 100%; }
        .inputClass                                 { width: 100%; }
        .tdInputClass                               { padding: 0px !important; }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button            { -webkit-appearance: none; margin: 0; }
        
        .show-modal .modal {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        overflow: hidden;
        }

        .show-modal .modal-dialog {
        position: fixed;
        margin: 0;
        width: 100%;
        height: 100%;
        padding: 0;
        }
        @media (min-width: 576px)
        {
            .show-modal .modal-dialog {
                max-width:  unset !important;
                margin: unset !important;
            }
        }
        .show-modal .modal-content {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        border: 2px solid #3c7dcf;
        border-radius: 0;
        box-shadow: none;
        }

        .show-modal .modal-header {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 50px;
        padding: 10px;
        background: #6598d9;
        border: 0;
        }

        .show-modal .modal-title {
        font-weight: 300;
        font-size: 2em;
        color: #fff;
        line-height: 30px;
        }

        .show-modal .modal-body {
        position: absolute;
        top: 50px;
        bottom: 60px;
        width: 100%;
        font-weight: 300;
        overflow: auto;
            background-color: rgba(0,0,0,.0001) !important;
        }

        .show-modal .modal-footer {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        height: 60px;
        padding: 10px;
        background: #f1f3f5;
        }
        #input-addnewschoolyear{
            background-color: unset !important; border: 1px solid #ddd; border-bottom: 1px solid #ddd; font-size: unset !important; 
        }
        
        select[readonly]:-moz-read-only {
  /* For Firefox */
  pointer-events: none;
}

select[readonly]:read-only {
  pointer-events: none;
}
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12">
                            <h3><strong>Learner's Permanent Academic Record</strong></h3>
                            <small><em>(Formerly Form 137)</em></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card collapsed-card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">NAME:</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->lastname}}, {{$studentdata->firstname}} {{$studentdata->middlename}} {{$studentdata->suffix}}." aria-describedby="inputGroupPrepend" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">SEX:</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->gender}}" aria-describedby="inputGroupPrepend" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group ">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupPrepend">DATE OF BIRTH:</span>
                                    </div>
                                    <input type="text" class="form-control text-uppercase" id="validationCustomUsername"  value="{{$studentdata->dob}}" aria-describedby="inputGroupPrepend" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">View Eligibility <i class="fas fa-minus"></i>
                      </button>
                    </div>
                </div>
                <div class="card-body pt-0">
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
                            <button type="button" class="btn btn-sm btn-primary" id="btn-eligibility-update"><i class="fa fa-edit"></i> Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-9">
            
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
        <form action="/reports_schoolform10/getrecordsjunior" target="_blank" method="get" class="m-0 p-0" style="display: inline;">
            <input type="hidden" value="1" name="export"/>
            <input type="hidden" value="{{$studentid}}" name="studentid"/>
            <input type="hidden" value="{{$acadprogid}}" name="acadprogid"/>
            <input type="hidden" value="pdf" name="exporttype"/>
            <input type="hidden" value="school" name="format"/>
            <input type="hidden" value="1" name="layout"/>
            <button type="submit" class="btn btn-primary btn-sm text-white">
                <i class="fa fa-file-pdf"></i>
            PDF (Layout 1)
            </button>
        </form>
        <form action="/reports_schoolform10/getrecordsjunior" target="_blank" method="get" class="m-0 p-0" style="display: inline;">
            <input type="hidden" value="1" name="export"/>
            <input type="hidden" value="{{$studentid}}" name="studentid"/>
            <input type="hidden" value="{{$acadprogid}}" name="acadprogid"/>
            <input type="hidden" value="pdf" name="exporttype"/>
            <input type="hidden" value="school" name="format"/>
            <input type="hidden" value="2" name="layout"/>
            <button type="submit" class="btn btn-primary btn-sm text-white">
                <i class="fa fa-file-pdf"></i>
            PDF (Layout 2)
            </button>
        </form>
        <form action="/reports_schoolform10/getrecordsjunior" target="_blank" method="get" class="m-0 p-0" style="display: inline;">
            <input type="hidden" value="1" name="export"/>
            <input type="hidden" value="{{$studentid}}" name="studentid"/>
            <input type="hidden" value="{{$acadprogid}}" name="acadprogid"/>
            <input type="hidden" value="pdf" name="exporttype"/>
            <input type="hidden" value="deped" name="format"/>
            <button type="submit" class="btn btn-primary btn-sm text-white">
                <i class="fa fa-file-pdf"></i>
            PDF (DepEd Format)
            </button>
        </form>
        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
            <form action="/reports_schoolform10/getrecordsjunior" target="_blank" method="get" class="m-0 p-0" style="display: inline;">
                <input type="hidden" value="1" name="export"/>
                <input type="hidden" value="{{$studentid}}" name="studentid"/>
                <input type="hidden" value="{{$acadprogid}}" name="acadprogid"/>
                <input type="hidden" value="pdf" name="exporttype"/>
                <input type="hidden" value="school" name="format"/>
                <button type="submit" class="btn btn-primary btn-sm text-white">
                    <i class="fa fa-file-pdf"></i>
                PDF
                </button>
            </form>
            <form action="/reports_schoolform10/getrecordsjunior" target="_blank" method="get" class="m-0 p-0" style="display: inline;">
                <input type="hidden" value="1" name="export"/>
                <input type="hidden" value="{{$studentid}}" name="studentid"/>
                <input type="hidden" value="{{$acadprogid}}" name="acadprogid"/>
                <input type="hidden" value="pdf" name="exporttype"/>
                <input type="hidden" value="deped" name="format"/>
                <button type="submit" class="btn btn-primary btn-sm text-white">
                    <i class="fa fa-file-pdf"></i>
                PDF (DepEd Format)
                </button>
            </form>
        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hcb' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'spct')
            <form action="/reports_schoolform10/getrecordsjunior" target="_blank" method="get" class="m-0 p-0" style="display: inline;">
                <input type="hidden" value="1" name="export"/>
                <input type="hidden" value="{{$studentid}}" name="studentid"/>
                <input type="hidden" value="{{$acadprogid}}" name="acadprogid"/>
                <input type="hidden" value="pdf" name="exporttype"/>
                <button type="submit" class="btn btn-primary btn-sm text-white">
                    <i class="fa fa-file-pdf"></i>
                PDF 
                </button>
            </form>
        @endif
        </div> --}}
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-default btn-sm" id="btn-reload"><i class="fa fa-sync"></i> Reload</button>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Export Form
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" style="font-size: 14px;">
                      <button class="btn-exportform dropdown-item" data-exporttype="pdf" data-format="deped" data-layout="1"><i class="fa fa-file-pdf"></i> &nbsp;Deped (Template 1)</button>
                      <button class="btn-exportform dropdown-item" data-exporttype="pdf" data-format="deped-2" data-layout="1"><i class="fa fa-file-pdf"></i> &nbsp;Deped (Template 2 - with school logo)</button>
                      @if($acadprogid == 5)
                      <button class="btn-exportform dropdown-item" data-exporttype="pdf" data-format="depedspr" data-layout="1"><i class="fa fa-file-pdf"></i> &nbsp;Deped - SPR (Template 3)</button>
                      @endif
                      <button class="btn-exportform dropdown-item" @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')data-exporttype="excel"@else data-exporttype="pdf"@endif data-format="school" data-layout="1"><i class="fa fa-file-pdf"></i> &nbsp;School Template @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')1 @endif<br/><small><em class="text-danger">Note: Please provide the school's own template</em></small></button>
                      @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                      <button class="btn-exportform dropdown-item" @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')data-exporttype="excel"@else data-exporttype="pdf"@endif data-format="school" data-layout="2"><i class="fa fa-file-pdf"></i> &nbsp;School Template 2<br/><small><em class="text-danger">Note: Please provide the school's own template</em></small></button>
                      @endif
                    </div>
                  </div>
                
            </div>
        </div>
        &nbsp;
        <div class="col-md-12" id="addcontainer">
            
        </div>
        <div class="col-md-12"id="recordscontainer">
            
        </div>
    </div>
    <div class="modal fade" id="show-edit-info" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">School Info</h4>
            <button type="button" id="btn-modal-close-info" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-12 mb-2">
                      <label class="m-0">School Name</label>
                      <input type="text" id="edit-schoolname" class="form-control"/>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">School ID</label>
                      <input type="text" id="edit-schoolid" class="form-control"/>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">District</label>
                      <input type="text" id="edit-schooldistrict" class="form-control"/>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">Division</label>
                      <input type="text" id="edit-schooldivision" class="form-control"/>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">Region</label>
                      <input type="text" id="edit-schoolregion" class="form-control"/>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">Classified as Grade</label>
                      <select id="edit-levelid" class="form-control"></select>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">Section</label>
                      <input type="text" id="edit-sectionname" class="form-control"/>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">School Year</label>
                      <input type="text" id="edit-schoolyear" class="form-control"/>
                  </div>
                  <div class="col-12 mb-2">
                      <label class="m-0">Name of Adviser</label>
                      <input type="text" id="edit-teachername" class="form-control"/>
                  </div>
              </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default btn-edit-close" data-dismiss="modal">Close</button>
            <button type="button" id="btn-edit-save-info" class="btn btn-primary">Save Changes</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="show-edit-info" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">School Info</h4>
            <button type="button" id="closeremarks" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
              
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default btn-edit-close" data-dismiss="modal">Close</button>
            <button type="button" id="btn-edit-save-info" class="btn btn-primary">Save Changes</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="modal fade show-modal" id="show-edit-grades" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Report Card</h4>
            <button type="button" id="btn-modal-close-reportcard" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body" id="edit-gradescontainer">
              
          </div>
          {{-- <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default btn-edit-close" data-dismiss="modal">Close</button>
            <button type="button" id="btn-edit-save-info" class="btn btn-primary">Save Changes</button>
          </div> --}}
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="modal fade show-modal" id="show-edit-remedial" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Remedial Classes</h4>
            <button type="button" id="btn-modal-close-remedial" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body" id="edit-remedialclasscontainer">
              
          </div>
          {{-- <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default btn-edit-close" data-dismiss="modal">Close</button>
            <button type="button" id="btn-edit-save-info" class="btn btn-primary">Save Changes</button>
          </div> --}}
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
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
    {{-- <div class="modal fade" id="modal-addnewperq">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add new Record (Per Quarter)</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row mb-2">
                <div class="col-md-12">
                  <label>S.Y</label>
                  <input type="tel" class="form-control" id="input-addnewperqschoolyear"/>
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
                </div>
            </div>
              <div class="row mb-2">
                  <div class="col-md-6">
                    <label>Select Grade Level</label>
                    <select class="form-control" id="select-addnewperqlevelid">
                        @foreach($gradelevels as $gradelevel)
                            <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label>Select Quarter</label>
                    <select class="form-control" id="select-addnewperqquarter">
                        <option value="1">1st Quarter</option>
                        <option value="2">2nd Quarter</option>
                        <option value="3">3rd Quarter</option>
                        <option value="4">4th Quarter</option>
                    </select>
                  </div>
              </div>
              <div class="row mb-2">
                  <div class="col-md-12" id="container-perquarter">

                  </div>
              </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="button-closeaddperq">Close</button>
            <button type="button" class="btn btn-primary" id="button-getsubjectsperquarter">Get Subjects</button>
            <button type="button" class="btn btn-primary" id="button-submitaddperq">Add</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div> --}}
    {{-- </div> --}}
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- fullCalendar 2.2.5 -->
    <!-- InputMask -->
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('plugins/jquery-year-picker/js/yearpicker.js')}}"></script>
    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
    <script>
        $(document).ready(function(){

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            $('#btn-reload').on('click', function(){
                
                getrecords();
            })
            var completer = $('#checkbox-completer').val()
            var peptpasser = $('#checkbox-peptpasser').val()
            var alspasser = $('#checkbox-alspasser').val()
            var infoid ;
            $('#button-submitaddperq').hide()
            function getrecords()
            {
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                }) 

                $.ajax({
                    url: '/reports_schoolform10/getrecordsjunior',
                    type: 'GET',
                    data:{
                        studentid : '{{$studentid}}',
                        acadprogid : '{{$acadprogid}}'
                    }, success:function(data)
                    {
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        $('#recordscontainer').empty()
                        $('#recordscontainer').append(data)
                    }
                });
            }

            getrecords();

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

                var courseschool = $('#input-courseschool').val()
                var courseyear   = $('#input-courseyear').val()
                var coursegenave = $('#input-coursegenave').val()

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
                        totalnoofyears      :   totalnoofyears,
                        courseschool          :   courseschool,
                        courseyear          :   courseyear,
                        coursegenave          :   coursegenave
                    }, success:function(data)
                    {

                            Toast.fire({
                                type: 'success',
                                title: 'Eligibility',
                                html: 'Updated successfully!'
                            })
                    }
                });
            })
            $(document).on('click','.eachrecord', function(){
                infoid = $(this).attr('data-id')
                console.log(infoid)
            })

            $('#addrecord').on('click',function(){
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                $('#modal-addnew').modal('show')
                @else
                    $(this).prop('disabled', true)
                    $.ajax({
                        url: '/reports_schoolform10/getaddnew',
                        type: 'GET',
                        data:{
                            acadprogid : '{{$acadprogid}}'
                        }, success:function(data)
                        {
                            $('#addcontainer').append(data);
                            $('#card-body-elem-addsubjects').hide()
                        }
                    });
                @endif
                // $(this).prop('disabled', true)
                // $.ajax({
                //     url: '/reports_schoolform10/getaddnew',
                //     type: 'GET',
                //     data:{
                //         acadprogid : '{{$acadprogid}}'
                //     }, success:function(data)
                //     {
                //         $('#addcontainer').append(data);
                //         $('#card-body-elem-addsubjects').hide()
                //     }
                // });
            });
            // $('#input-addnewschoolyear').on('keypress', function(key) {
            //     if(key.charCode < 48 || key.charCode > 57) return false;
            // });
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
                        url: '/reports_schoolform10/getaddnew',
                        type: 'GET',
                        data:{
                            studentid           :   '{{$studentid}}',
                            acadprogid  : '{{$acadprogid}}',
                            levelid     : levelidvalue,
                            sectionid   : '{{$sectionid}}',
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
            // $(document).on('change','#gradelevelid', function(){
            //     if($(this).val().replace(/^\s+|\s+$/g, "").length == 0){
            //         $('#card-body-elem-addsubjects').hide()
            //     }else{
            //         $.ajax({
            //             url: '/reports_schoolform10/getsubjects',
            //             type: 'GET',
            //             data:{
            //                 acadprogid : '{{$acadprogid}}',
            //                 levelid    : $(this).val()
            //             }, success:function(data)
            //             {
            //                 $('#addcontainer').append(data);
            //                 $('#card-body-elem-addsubjects').hide()
            //             }
            //         });
            //     }
            // })
            $(document).on('click', '#addrow', function(){
                var closestTable = $(this).closest("table");
                closestTable.append(
                    '<tr class="tr-eachsubject">'+
                        '<td class="tdInputClass"><input type="text" class="form-control input0" value="1" hidden/><input type="text" class="form-control input00" value="0" hidden/><input type="text" class="form-control input1" name="add-subject[]" required/><input type="text" class="form-control input000mapeh" value="1" hidden=""><input type="text" class="form-control input000tle" value="0" hidden=""></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control input2 grades" name="add-q1[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control input3 grades" name="add-q2[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control input4 grades" name="add-q3[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control input5 grades" name="add-q4[]" required/></td>'+
                        '<td class="tdInputClass"><input type="number" class="form-control input6 grades" name="add-final[]" required/></td>'+
                        '<td class="tdInputClass"><input type="text" class="form-control input7" name="add-remarks[]" required/></td>'+
                        // '<td class="tdInputClass"><input type="number" class="form-control" name="entry[]" required/></td>'+
                        '<td class="removebutton"><center><i class="fa fa-trash text-gray"></i></center></td>'+
                    '</tr>'
                );
                
                // $('.grades').on('change', function () {
                //     var input = parseInt(this.value);
                //     if (input < 60 )
                //         $(this).val('60')
                //     else if (input > 100 )
                //         $(this).val('100')
                //     return;
                // });
            });
            $(document).on('click', '#btn-submitnewform', function(){
                var schoolname = $('input[name="add-schoolname"]').val();
                var schoolid = $('input[name="add-schoolid"]').val();
                var schooldistrict = $('input[name="add-schooldistrict"]').val();
                var schooldivision = $('input[name="add-schooldivision"]').val();
                var schoolregion = $('input[name="add-schoolregion"]').val();
                var gradelevelid = $('select[name="add-gradelevelid"]').val();
                var sectionname = $('input[name="add-sectionname"]').val();
                var schoolyear = $('input[name="add-schoolyear"]').val();
                var teachername = $('input[name="add-teachername"]').val();
                
                var subjects = [];
                $('.tr-eachsubject').each(function(){
                    var input0 = $(this).find('.input0').val();
                    var input00 = $(this).find('.input00').val();
                    var input000mapeh = $(this).find('.input000mapeh').val();
                    var input000tle = $(this).find('.input000tle').val();
                    var input1 = $(this).find('.input1').val();
                    var input2 = $(this).find('.input2').val();
                    var input3 = $(this).find('.input3').val();
                    var input4 = $(this).find('.input4').val();
                    var input5 = $(this).find('.input5').val();
                    var input6 = $(this).find('.input6').val();
                    var input7 = $(this).find('.input7').val();
                    if(input1.replace(/^\s+|\s+$/g, "").length > 0)
                    {
                        if(input2.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            input2 = 0;
                        }
                        if(input3.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            input3 = 0;
                        }
                        if(input4.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            input4 = 0;
                        }
                        if(input5.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            input5 = 0;
                        }
                        if(input6.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            input6 = 0;
                        }
                        if(input7.replace(/^\s+|\s+$/g, "").length == 0)
                        {
                            input7 = " " ;
                        }

                        obj = {
                            inmapeh      : input000mapeh,
                            intle      : input000tle,
                            editablegrades      : input0,
                            fromsystem      : input00,
                            subjdesc      : input1,
                            q1      : input2,
                            q2      : input3,
                            q3      : input4,
                            q4      : input5,
                            final      : input6,
                            remarks      : input7
                        };
                        subjects.push(obj);
                    }
                })

                var generalaverageval = $('input[name="add-generalaverageval"]').val();
                if(subjects.length>0)
                {
                    $.ajax({
                        url: '/reports_schoolform10/submitnewform',
                        type: 'GET',
                        data:{
                            studentid           :   '{{$studentid}}',
                            acadprogid          :   '{{$acadprogid}}',
                            schoolname          :   schoolname,
                            schoolid            :   schoolid,
                            schooldistrict      :   schooldistrict,
                            schooldivision      :   schooldivision,
                            schoolregion        :   schoolregion,
                            gradelevelid        :   gradelevelid,
                            sectionname         :   sectionname,
                            schoolyear          :   schoolyear,
                            teachername         :   teachername,
                            subjects            :   JSON.stringify(subjects),
                            // q1                  :   q1,
                            // q2                  :   q2,
                            // q3                  :   q3,
                            // q4                  :   q4,
                            // final               :   final,
                            // remarks             :   remarks,
                            generalaverageval   :   generalaverageval

                        }, success:function(data)
                        {
                            if(data == 1)
                            {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Record added successfully!'
                                })
                                $('#addrecord').removeAttr('disabled')
                                $('#addcontainer').empty()
                                getrecords();
                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: 'Empty Subjects!'
                                })
                            }
                        }
                    });
                }else{
                    Toast.fire({
                                    type: 'error',
                                    title: 'Empty Subjects!'
                                })
                }
                
            })
            $(document).on('click','.btn-delete-syinfo', function(){
                var id = $(this).attr('data-id');
                var thiscard = $(this).closest('.card');
                Swal.fire({
                    title: 'Are you sure you want to delete the selected record?',
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
                            url: '/reports_schoolform10/deleterecord',
                            type:"GET",
                            dataType:"json",
                            data:{
                                id: id
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            complete: function(){

                                        Toast.fire({
                                            type: 'success',
                                            title: 'Record successfully!'
                                        })

                                        thiscard.remove();
                                        

                            }
                        })
                    }
                })
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
            $(document).on('click', '.btn-edit-syinfo', function(){
                infoid = $(this).attr('data-id');

                $('#show-edit-info').modal('show')
                $.ajax({
                    url: '/reports_schoolform10/getinfo',
                    type: 'GET',
                    data:{
                        acadprogid : '{{$acadprogid}}',
                        infoid : infoid
                    }, success:function(data)
                    {
                        $('#edit-levelid').empty()
                        $('#edit-schoolname').val(data.schoolname)
                        $('#edit-schoolid').val(data.schoolid)
                        $('#edit-schooldistrict').val(data.schooldistrict)
                        $('#edit-schooldivision').val(data.schooldivision)
                        $('#edit-schoolregion').val(data.schoolregion)
                        $('#edit-levelid').append(data.selectlevel)
                        $('#edit-sectionname').val(data.sectionname)
                        $('#edit-schoolyear').val(data.sydesc)
                        $('#edit-teachername').val(data.teachername)
                    }
                });
            })
            $(document).on('click','#btn-edit-save-info', function(){
                var schoolname = $('#edit-schoolname').val()
                var schoolid = $('#edit-schoolid').val()
                var schooldistrict = $('#edit-schooldistrict').val()
                var schooldivision = $('#edit-schooldivision').val()
                var schoolregion = $('#edit-schoolregion').val()
                var levelid = $('#edit-levelid').val()
                var sectionname = $('#edit-sectionname').val()
                var schoolyear = $('#edit-schoolyear').val()
                var teachername = $('#edit-teachername').val()

                $.ajax({
                    url: '/reports_schoolform10/updateinfo',
                    type: 'GET',
                    data:{
                        acadprogid : '{{$acadprogid}}',
                        infoid : infoid,
                        schoolname : schoolname,
                        schoolid : schoolid,
                        schooldistrict : schooldistrict,
                        schooldivision : schooldivision,
                        schoolregion : schoolregion,
                        levelid : levelid,
                        sectionname : sectionname,
                        schoolyear : schoolyear,
                        teachername : teachername
                    }, success:function(data)
                    {
                        $('.btn-edit-close').click();
                        getrecords();
                    }
                });
            })
            $(document).on('click', '.btn-edit-reportcard', function(){
                infoid = $(this).attr('data-id');
                $('#show-edit-grades').modal('show')
                $.ajax({
                    url: '/reports_schoolform10/getgradesedit',
                    type: 'GET',
                    data:{
                        acadprogid : '{{$acadprogid}}',
                        infoid : infoid
                    }, success:function(data)
                    {
                        $('#edit-gradescontainer').empty()
                        $('#edit-gradescontainer').append(data)
                    }
                });
            })
            $(document).on('click','#btn-modal-close-reportcard', function(){
                getrecords();
            })
            var gradeid;
            $(document).on('click', '.btn-edit-deletesubject', function(){
                gradeid = $(this).attr('data-id');
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
                            url: '/reports_schoolform10/deletesubjectgrades',
                            type:"GET",
                            dataType:"json",
                            data:{
                                acadprogid : '{{$acadprogid}}',
                                gradeid: gradeid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            complete: function(){

                                        Toast.fire({
                                            type: 'success',
                                            title: 'Report Card updated successfully!'
                                        })

                                        thistr.remove();
                                        

                            }
                        })
                    }
                })
            })
            $(document).on('click','.btn-edit-editsubject', function(){
                gradeid         = $(this).attr('data-id');
                editsubject     = $('#subject'+gradeid).val();
                editq1          = $('#q1'+gradeid).val();
                editq2          = $('#q2'+gradeid).val();
                editq3          = $('#q3'+gradeid).val();
                editq4          = $('#q4'+gradeid).val();
                editfinalrating = $('#finalrating'+gradeid).val();
                editremarks     = $('#remarks'+gradeid).val();
                if($('#inMAPEH'+gradeid).is(':checked')){
                    var editinmapeh = 1;
                }else{
                    var editinmapeh = 0;
                }
                if($('#inTLE'+gradeid).is(':checked')){
                    var editintle = 1;
                }else{
                    var editintle = 0;
                }
                $.ajax({
                    url: '/reports_schoolform10/editsubjectgrades',
                    type:"GET",
                    dataType:"json",
                    data:{
                        acadprogid : '{{$acadprogid}}',
                        gradeid: gradeid,
                        editsubject: editsubject,
                        editq1: editq1,
                        editq2: editq2,
                        editq3: editq3,
                        editq4: editq4,
                        editfinalrating: editfinalrating,
                        editremarks: editremarks,
                        editinmapeh: editinmapeh,
                        editintle: editintle
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    complete: function(){

                        Toast.fire({
                            type: 'success',
                            title: 'Updated successfully!'
                        })
                        
                    }
                })
            })
            $(document).on('click', '#btn-edit-addrow', function(){
                $('#grades-tbody').append(
                    '<tr>'+
                        '<td><input type="text" class="form-control" name="add-new-subject" required/></td>'+
                        '<td><input type="number" class="form-control" name="add-new-q1" required/></td>'+
                        '<td><input type="number" class="form-control" name="add-new-q2" required/></td>'+
                        '<td><input type="number" class="form-control" name="add-new-q3" required/></td>'+
                        '<td><input type="number" class="form-control" name="add-new-q4" required/></td>'+
                        '<td><input type="number" class="form-control" name="add-new-final" required/></td>'+
                        '<td><input type="text" class="form-control" name="add-new-remarks" required/></td>'+
                        // '<td class="tdInputClass"><input type="number" class="form-control" name="entry[]" required/></td>'+
                        '<td><button type="button" class="btn btn-sm btn-success p-1 btn-edit-addsubject" data-id="'+gradeid+'"><i class="fa fa-edit"></i> Save &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> <button type="button" class="btn btn-sm btn-default p-1 removebutton"><i class="fa fa-trash text-danger"></i>&nbsp;</button></td>'+
                    '</tr>'
                );
            })
            $(document).on('click','.btn-edit-addsubject', function(){
                gradeid        = $(this).attr('data-id');
                addsubject     = $(this).closest('tr').find('input[name="add-new-subject"]').val()
                addq1          = $(this).closest('tr').find('input[name="add-new-q1"]').val()
                addq2          = $(this).closest('tr').find('input[name="add-new-q2"]').val()
                addq3          = $(this).closest('tr').find('input[name="add-new-q3"]').val()
                addq4          = $(this).closest('tr').find('input[name="add-new-q4"]').val()
                addfinalrating = $(this).closest('tr').find('input[name="add-new-final"]').val()
                addremarks     = $(this).closest('tr').find('input[name="add-new-remarks"]').val()

                var validationcheck = 0;

                if(addsubject.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-subject"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-subject"]').removeAttr('style')
                }

                if(addq1.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-q1"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-q1"]').removeAttr('style')
                }
                if(addq2.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-q2"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-q2"]').removeAttr('style')
                }
                if(addq3.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-q3"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-q3"]').removeAttr('style')
                }
                if(addq4.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-q4"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-q4"]').removeAttr('style')
                }
                if(addfinalrating.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('input[name="add-new-final"]').css('border','1px solid red');
                }else{
                    validationcheck+=1;
                    $(this).closest('tr').find('input[name="add-new-final"]').removeAttr('style')
                }

                if(validationcheck == 6)
                {
                    $.ajax({
                        url: '/reports_schoolform10/addsubjectgrades',
                        type:"GET",
                        dataType:"json",
                        data:{
                            acadprogid : '{{$acadprogid}}',
                            infoid: infoid,
                            gradeid: gradeid,
                            addsubject: addsubject,
                            addq1: addq1,
                            addq2: addq2,
                            addq3: addq3,
                            addq4: addq4,
                            addfinalrating: addfinalrating,
                            addremarks: addremarks
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){
                            if(data == 1)
                            {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Updated successfully!'
                                })
                                $.ajax({
                                    url: '/reports_schoolform10/getgradesedit',
                                    type: 'GET',
                                    data:{
                                        acadprogid : '{{$acadprogid}}',
                                        infoid : infoid
                                    }, success:function(data)
                                    {
                                        $('#edit-gradescontainer').empty()
                                        $('#edit-gradescontainer').append(data)
                                    }
                                });
                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: 'Already exists!'
                                })
                            }
                            
                        }
                    })
                }else{
                    Toast.fire({
                        type: 'warning',
                        title: 'Some fields are empty!'
                    })
                }

            })

            $(document).on('click','#btn-savefooter', function(){
                var purpose         = $('#purpose').val(); 
                var classadviser    = $('#classadviser').val();
                var recordsincharge = $('#recordsincharge').val(); 
                
                var certcopysentto = $('#certcopysentto').val(); 
                var certaddress = $('#certaddress').val(); 
                $.ajax({
                    url: '/reports_schoolform10/submitfooter',
                    type:"GET",
                    dataType:"json",
                    data:{
                        studentid               :   '{{$studentid}}',
                        acadprogid              : '{{$acadprogid}}',
                        purpose                 : purpose,
                        classadviser            : classadviser,
                        recordsincharge         : recordsincharge,
                        certcopysentto         : certcopysentto,
                        certaddress         : certaddress
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
            $(document).on('click','.btn-addinauto',function(){
                var subjectid   = $(this).attr('data-subjid');
                var quarter     = $(this).attr('data-quarter');
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
                        url: '/reports_schoolform10/addinauto',
                        type:"GET",
                        dataType:"json",
                        data:{
                            studentid               : '{{$studentid}}',
                            acadprogid              : '{{$acadprogid}}',
                            subjectid               : subjectid,
                            quarter                 : quarter,
                            syid                    : syid,
                            levelid                 : levelid,
                            gradevalue              : gradevalue
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){

                            if(data == 1)
                            {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Added successfully!'
                                }) 
                                thisbutton.removeClass('btn-addinauto');
                                thisbutton.empty()
                                thisbutton.append('<i class="fa fa-edit fa-xs"></i>')
                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong!'
                                }) 
                            }                             

                        }
                    })
                }
            })
            $(document).on('click','.btn-editinauto',function(){
                var subjectid   = $(this).attr('data-subjid');
                var quarter     = $(this).attr('data-quarter');
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
                        url: '/reports_schoolform10/editinauto',
                        type:"GET",
                        dataType:"json",
                        data:{
                            studentid               : '{{$studentid}}',
                            acadprogid              : '{{$acadprogid}}',
                            subjectid               : subjectid,
                            quarter                 : quarter,
                            syid                    : syid,
                            semid                    : 0,
                            levelid                 : levelid,
                            gradevalue              : gradevalue
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){

                            if(data == 1)
                            {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Updated successfully!'
                                }) 
                                thisbutton.removeClass('btn-addinauto');
                                thisbutton.empty()
                                thisbutton.append('<i class="fa fa-edit fa-xs"></i>')
                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong!'
                                }) 
                            }                             

                        }
                    })
                }
            })
            $(document).on('click','.btn-addsubjinauto', function(){
                var tbody = $(this).closest('table').find('tbody');
                var syid  = $(this).attr('data-syid');
                var levelid  = $(this).attr('data-levelid');

                tbody.append(
                    '<tr>'+
                        '<td class="p-0"><input type="text" class="form-control form-control-sm subjdesc" placeholder="Description"/></td>'+
                        '<td class="p-0"><input type="number" class="form-control form-control-sm subjq1" placeholder="Q1 Grade"/></td>'+
                        '<td class="p-0"><input type="number" class="form-control form-control-sm subjq2" placeholder="Q2 Grade"/></td>'+
                        '<td class="p-0"><input type="number" class="form-control form-control-sm subjq3" placeholder="Q3 Grade"/></td>'+
                        '<td class="p-0"><input type="number" class="form-control form-control-sm subjq4" placeholder="Q4 Grade"/></td>'+
                        '<td class="p-0"><input type="number" class="form-control form-control-sm subjfinalrating" placeholder="Final Grade"/></td>'+
                        '<td class="p-0"><input type="text" class="form-control form-control-sm subjremarks" placeholder="Remarks"/></td>'+
                        '<td class="p-0 text-right"><button type="button" class="btn btn-default text-success btn-subjauto-save btn-sm" data-syid="'+syid+'" data-levelid="'+levelid+'"><i class="fa fa-share"></i></button><button type="button" class="btn btn-default text-danger removebutton btn-sm"><i class="fa fa-times"></i></button></td>'+
                    '</tr>'
                )
            })
            $(document).on('click','.btn-subjauto-save', function(){
                var subjcode = $(this).closest('tr').find('.subjcode').val();
                var subjdesc = $(this).closest('tr').find('.subjdesc').val();
                var subjq1 = $(this).closest('tr').find('.subjq1').val();
                var subjq2 = $(this).closest('tr').find('.subjq2').val();
                var subjq3 = $(this).closest('tr').find('.subjq3').val();
                var subjq4 = $(this).closest('tr').find('.subjq4').val();
                var subjfinalrating = $(this).closest('tr').find('.subjfinalrating').val();
                var subjremarks = $(this).closest('tr').find('.subjremarks').val();

                if(subjdesc.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('tr').find('.subjdesc').css('border','1px solid red')
                }else{
                    $(this).closest('tr').find('.subjdesc').removeAttr('style');

                    var syid  = $(this).attr('data-syid');
                    var levelid  = $(this).attr('data-levelid');
                    var thistd  = $(this).closest('td')
                    var thistr  = $(this).closest('tr')
                    $.ajax({
                        url: '/reports_schoolform10/addsubjgradesinauto',
                        type:"GET",
                        dataType:"json",
                        data:{
                            studentid       :   '{{$studentid}}',
                            acadprogid      : '{{$acadprogid}}',
                            syid            : syid,
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
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Something went wrong!'
                                    })  
                            }else{
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Added successfully!'
                                    })  
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
            $(document).on('click','.btn-subjauto-edit', function(){
                $(this).closest('tr').find('input,button').prop('disabled',false)
            })
            $(document).on('click','.btn-subjauto-update', function(){
                var subjdesc = $(this).closest('tr').find('.subjdesc').val();
                var subjq1 = $(this).closest('tr').find('.subjq1').val();
                var subjq2 = $(this).closest('tr').find('.subjq2').val();
                var subjq3 = $(this).closest('tr').find('.subjq3').val();
                var subjq4 = $(this).closest('tr').find('.subjq4').val();
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
                        url: '/reports_schoolform10/updatesubjgradesinauto',
                        type:"GET",
                        dataType:"json",
                        data:{
                            studentid       :   '{{$studentid}}',
                            acadprogid      : '{{$acadprogid}}',
                            id              : id,
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
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Added successfully!'
                                    })   
                            }else{
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Something went wrong!'
                                    }) 
                            }                            

                        }
                    })

                }
            })
            $(document).on('click','.btn-subjauto-delete', function(){
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
                                url: '/reports_schoolform10/deletesubjgradesinauto',
                                type:"GET",
                                dataType:"json",
                                data:{
                                    id: id
                                },
                                // headers: { 'X-CSRF-TOKEN': token },,
                                complete: function(){

                                            Toast.fire({
                                                type: 'success',
                                                title: 'Deletedd successfully!'
                                            })

                                            thistr.remove();
                                            

                                }
                            })
                        }
                    })
            })
            $(document).on('click','.btn-exportform', function(){
                var formexport = 1;
                var format = $(this).attr('data-format')
                var layout = $(this).attr('data-layout')
                var exporttype = $(this).attr('data-exporttype')
                var studentid   = '{{$studentid}}';
                var acadprogid   = '{{$acadprogid}}';
                window.open('/reports_schoolform10/getrecordsjunior?export='+formexport+'&format='+format+'&exporttype='+exporttype+'&studentid='+studentid+'&acadprogid='+acadprogid+'&layout='+layout,'_blank')
            })
        })
    </script>
@endsection