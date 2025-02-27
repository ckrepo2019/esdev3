<style>

    img {
        border-radius: unset !important;
    }
                        .table-ccsa td {
                            padding: 0px;
                        }
                        hr {
                            margin-top: 5px;
                            margin-bottom: 5px;
                        }
</style>
<div class="row mb-2">
    <div class="col-md-3">
        <label>College Dean</label>
        <input type="text" class="form-control form-control-sm" id="input-collegedean" placeholder="College Dean" value=""/>
    </div>
    <div class="col-md-3">
        <label>Cleared by</label>
        <input type="text" class="form-control form-control-sm" id="input-clearedby" placeholder="Cleared by" value=""/>
    </div>
    <div class="col-md-3">
        <label>Prepared & Checked by</label>
        <input type="text" class="form-control form-control-sm" id="input-preparedncheckedby" placeholder="Prepared & Checked by" value=""/>
    </div>
    <div class="col-md-3">
        <label>Verified & Released by</label>
        <input type="text" class="form-control form-control-sm" id="input-verifiednreleasedby" placeholder="Verified & Released by" value=""/>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="collapse" href="#collapseTwo">
        View Information
        </button>
    </div>
    <div class="col-md-6 text-right mb-2"><div class="btn-group">
        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Export TOR to PDF
        </button>
        <div class="dropdown-menu dropdown-menu-right">
          <button class="btn-exporttor dropdown-item" data-format="for_so_application">for S.O Application</button>
          <button class="btn-exporttor dropdown-item" data-format="for_inactive_stud">for Inactive Student</button>
          <button class="btn-exporttor dropdown-item" data-format="for_graduate_stud">for Graduate Student</button>
        </div>
      </div>
        {{-- <button type="button" class="btn btn-secondary btn-sm" id="btn-exporttopdf"><i class="fa fa-file-pdf"></i> Export TOR to PDF</button>
        <button type="button" class="btn btn-secondary btn-sm" id="btn-exporttopdf"><i class="fa fa-file-pdf"></i> Export TOR to PDF</button>
        <button type="button" class="btn btn-secondary btn-sm" id="btn-exporttopdf"><i class="fa fa-file-pdf"></i> Export TOR to PDF</button> --}}
    </div>
</div>
<hr/>
@php

    if(strtoupper($studentinfo->gender) == 'FEMALE'){
        $avatar = 'avatar/S(F) 1.png';
    }
    else{
        $avatar = 'avatar/S(M) 1.png';
    }
@endphp
<div id="accordion">
    <div id="collapseTwo" class="collapse" data-parent="#accordion">
        <div class="row">
            <div class="col-md-2 text-center">
                @if($getphoto)
                    @if (file_exists(base_path().'/public/'.$getphoto->picurl))
                    <img src="{{URL::asset($getphoto->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}"  style="width: 140px; height: 140px; margin: 0px;" />
                    @else
                        @if (file_exists(base_path().'/public/'.$studentinfo->picurl))
                            <img src="/{{$studentinfo->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')}}" style="width: 140px; height: 140px; margin: 0px;" /> 
                        @else
                        <img src="{{asset($avatar)}}" alt="student" style="width: 140px; height: 140px; margin: 0px;" >
                        @endif
                    @endif
                @else
                    
                    @if (file_exists(base_path().'/public/'.$studentinfo->picurl))
                        <img src="/{{$studentinfo->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')}}" style="width: 140px; height: 140px; margin: 0px;" /> 
                    @else
                        
                    <img src="{{asset($avatar)}}" alt="student" style="width: 140px; height: 140px; margin: 0px;" >
                    @endif
                @endif
            </div>
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-12">
                        <table style="width: 100%; table-layout: fixed; font-size: 13px;" class="table-ccsa">
                            <tr>
                                <td style="width: 10%;">Date of Birth:</td>
                                <td style="width: 25%; ">
                                    {{$details->dob}}
                                </td>
                                <td style="width: 12%; text-align: right;">Place of Birth:&nbsp;&nbsp;</td>
                                <td style="width: 32%; border-bottom: 1px solid black;">
                                    <input type="text" class="form-control form-control-sm" value="{{$details->pob}}" id="input-placeofbirth" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                                <td style="width: 10%; text-align: right;">Sex:</td>
                                <td style="width: 10%;">
                                    {{$details->gender}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center"><sup>Municipality/City/Province</sup></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Citizenship:</td>
                                <td style="border-bottom: 1px solid black;">
                                    <input type="text" class="form-control form-control-sm" value="{{$details->citizenship}}" id="input-citizenship" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                                <td style="text-align: right;">Religion:&nbsp;&nbsp;</td>
                                <td > {{$studentinfo->religionanme ?? ''}}</td>
                                <td style="text-align: right;">ACR No.:</td>
                                <td style="border-bottom: 1px solid black;">
                                    <input type="text" class="form-control form-control-sm" value="{{$details->acrno}}" id="input-acrno" style="border: none; border-bottom: 1px solid #ddd;"/>
                                </td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                                <td style="border-bottom: 1px solid black;">{{$studentinfo->city}}</td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;">{{$studentinfo->province}}</td>
                                <td style="text-align: right;">Civil Status:</td>
                                <td style="border-bottom: 1px solid black;">
                                    <input type="text" class="form-control form-control-sm" value="{{$details->civilstatus}}" id="input-civilstatus" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-center"><sup>City</sup></td>
                                <td></td>
                                <td class="text-center"><sup>Provincial</sup></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Father:</td>
                                <td style="border-bottom: 1px solid black;">{{$studentinfo->fathername}}</td>
                                <td class="text-right">Mother:&nbsp;&nbsp;</td>
                                <td style="border-bottom: 1px solid black;">{{$studentinfo->mothername}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-12">
                <table style="width: 100%; table-layout: fixed; font-size: 13px;" class="table-ccsa">
                    <tr>
                        <td style="width: 25%;">Primary Grades completed at:</td>
                        <td colspan="2" style="border-bottom: 1px solid black;"><input type="text" class="form-control form-control-sm" value="{{$details->elemcourse}}" id="input-elemcourse" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                        <td class="text-right" style="width: 10%;">Year:</td>
                        <td colspan="2" style="width: 15%; border-bottom: 1px solid black;"><input type="text" class="form-control form-control-sm" value="{{$details->elemsy}}" id="input-elemschoolyear" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                    </tr>
                    <tr>
                        <td>Intermediate Grades completed at:</td>
                        <td colspan="2" style="border-bottom: 1px solid black;"><input type="text" class="form-control form-control-sm" value="{{$details->intermediatecourse}}" id="input-intermediatecourse" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                        <td class="text-right">Year:</td>
                        <td colspan="2" style="border-bottom: 1px solid black;"> <input type="text" class="form-control form-control-sm" value="{{$details->intermediatesy}}" id="input-intermediateschoolyear" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                    </tr>
                    <tr>
                        <td>High School Course completed at:</td>
                        <td colspan="2" style="border-bottom: 1px solid black;"><input type="text" class="form-control form-control-sm" value="{{$details->secondcourse}}" id="input-secondcourse" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                        <td class="text-right">Year:</td>
                        <td colspan="2" style="border-bottom: 1px solid black;"><input type="text" class="form-control form-control-sm" value="{{$details->secondsy}}" id="input-secondschoolyear" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                    </tr>
                </table>
            </div>
        </div>
        <hr/>
        <div class="row" style="font-size: 13px;">
            <div class="col-md-12">
                <table style="width: 100%; table-layout: fixed; font-size: 13px;" class="table-ccsa">
                    <tr>
                        <td>Admission</td>
                        <td style="border-bottom: 1px solid black;">
                            <input type="text" class="form-control form-control-sm" value="{{$details->basisofadmission}}" id="input-basisofadmission" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                        <td></td>
                        <td style="border-bottom: 1px solid black;">
                            <input type="date" class="form-control form-control-sm" value="{{$details->admissiondatestr}}" id="input-dateadmitted" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                        <td></td>
                        <td style="border-bottom: 1px solid black;">
                            <input type="text" class="form-control form-control-sm" value="{{$details->admissionsem ?? ''}}" id="input-admissionsem" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                        <td></td>
                        <td style="border-bottom: 1px solid black;">
                            <input type="text" class="form-control form-control-sm" value="{{$details->admissionsy ?? ''}}" id="input-admissionsy" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-center"><sup>Basis</sup></td>
                        <td></td>
                        <td class="text-center"><sup>Date</sup></td>
                        <td></td>
                        <td class="text-center"><sup>Semester</sup></td>
                        <td></td>
                        <td class="text-center"><sup>School Year</sup></td>
                    </tr>
                    <tr>
                        <td>Admitted to</td>
                        <td colspan="2" style="border-bottom: 1px solid black;">
                            <input type="text" class="form-control form-control-sm" value="{{$details->collegeof ?? ''}}" id="input-collegeof" style="border: none; border-bottom: 1px solid #ddd;"/>
                        </td>
                        <td></td>
                        <td colspan="4" style="border-bottom: 1px solid black;">
                            <input type="text" class="form-control form-control-sm" value="{{$details->degree ?? ''}}" id="input-degree" style="border: none; border-bottom: 1px solid #ddd;"/>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2"class="text-center"><sup>College/Department</sup></td>
                        <td></td>
                        <td colspan="4"class="text-center"><sup>Course/Major</sup></td>
                    </tr>
                </table>
            </div>
        </div>
        <hr/>
        <div class="row" style="font-size: 13px;">
            <div class="col-md-12">
                <table style="width: 100%; table-layout: fixed; font-size: 13px; border: 1px solid black;" class="table-ccsa">
                    <tr>
                        <td style="width: 7%;">&nbsp;&nbsp;Graduated</td>
                        <td colspan="2" style="border-bottom: 1px solid black;">
                            <input type="text" class="form-control form-control-sm" value="{{$details->graduationdegree ?? ''}}" id="input-graduationdegree" style="border: none; border-bottom: 1px solid #ddd;"/>
                        </td>
                        <td colspan="2" style="border-bottom: 1px solid black;">
                            <input type="text" class="form-control form-control-sm" value="{{$details->graduationmajor ?? ''}}" id="input-graduationmajor" style="border: none; border-bottom: 1px solid #ddd;"/>
                        </td>
                        <td style="border-bottom: 1px solid black;">
                            <input type="text" class="form-control form-control-sm" value="{{$details->graduationhonors ?? ''}}" id="input-graduationhonors" style="border: none; border-bottom: 1px solid #ddd;"/>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2"><sup>Degree</sup></td>
                        <td colspan="2"><sup>Major (Concentration)/Minor</sup></td>
                        <td><sup>Title/Honors</sup></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;&nbsp;Date of Graduation:</td>
                        <td style="border-bottom: 1px solid black;">
                            <input type="date" class="form-control form-control-sm" value="{{$details->graduationdate}}" id="input-graduationdate" style="border: none; border-bottom: 1px solid #ddd;"/>
                        </td>
                        <td>&nbsp;&nbsp;Special Order (B) Number:</td>
                        <td colspan="2" style="border-bottom: 1px solid black;">
                            <input type="text" class="form-control form-control-sm" value="{{$details->specialorder}}" id="input-specialorder" style="border: none; border-bottom: 1px solid #ddd;"/>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12">
                <label>Remarks:</label>
                <input type="text" class="form-control form-control-sm" value="{{$details->remarks}}" id="input-remarks" style="border: none; border-bottom: 1px solid black;"/>
            </div>
            <div class="col-md-12 text-right mt-2">
                <button type="button" class="btn btn-outline-primary" id="btn-details-save"><i class="fa fa-share"></i>&nbsp; Save Changes</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mb-2">
        <button type="button" class="btn btn-info btn-sm" id="btn-addnewrecord" data-toggle="modal" data-target="#modal-newrecord"><i class="fa fa-plus"></i> Add new record</button>
    </div>
    @if(count($records)>0)
        @foreach($records as $record)
            <div class="col-md-12 @if($record->type == 'auto') auto-disabled @endif" >
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <h6><strong>{{$record->sydesc}} / @if($record->semid == 1)1st Semester @elseif($record->semid == 2) 2nd Semester @elseif($record->semid == 3) Summer @endif </strong></h6>
                            </div>
                            <div class="col-md-8">
                                <h6><strong>{{$record->coursename}}</strong></h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="row">
                                    <div class="col-2">
                                        <label>School ID</label>
                                        <input type="text" class="form-control form-control-sm" value="{{$record->schoolid}}" style="border: none;" readonly/>
                                    </div>
                                    <div class="col-4">
                                        <label>School Name</label>
                                        <input type="text" class="form-control form-control-sm" value="{{$record->schoolname}}" style="border: none;" readonly/>
                                    </div>
                                    <div class="col-6">
                                        <label>School Address</label>
                                        <input type="text" class="form-control form-control-sm" value="{{$record->schooladdress}}" style="border: none;" readonly/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-right mb-2">
                                {{-- @if(count($record->subjdata)==0) --}}
                                {{-- <button class="btn btn-sm btn-default btn-adddata text-success" data-torid="{{$record->id}}" data-semid="{{$record->semid}}" data-sydesc="{{$record->sydesc}}" data-courseid="{{$record->courseid}}"><i class="fa fa-plus"></i> Add Data</button> --}}
                                {{-- @else --}}
                                    <button type="button" {{$record->type == 'auto' ? 'hidden' : ''}} class="btn btn-sm btn-default btn-adddata text-success" data-torid="{{$record->id}}" data-semid="{{$record->semid}}" data-sydesc="{{$record->sydesc}}" data-courseid="{{$record->courseid}}"><i class="fa fa-plus"></i> Add Data</button>
                                    <button type="button" {{$record->type == 'auto' ? 'hidden' : ''}} class="btn btn-sm btn-default btn-editrecord" data-torid="{{$record->id}}"><i class="fa fa-edit text-warning"></i> Edit Record Info</button>
                                    <button type="button" {{$record->type == 'auto' ? 'hidden' : ''}} class="btn btn-default btn-sm text-danger btn-deleterecord" data-torid="{{$record->id}}"><i class="fa fa-trash"></i> Delete this record</button>
                                   
                                {{-- @endif --}}
                            </div>
                        </div>
                    </div>
                    @if(count($record->subjdata)>0)
                        <div class="card-body p-0">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <table class="table" style="font-size: 14px;">
                                        <thead class="text-center">
                                            <tr>
                                                <th style="width: 15%;">Subject Code</th>
                                                <th style="width: 10%;">Units</th>
                                                <th>Description</th>
                                                <th style="width: 11%;">Grade</th>
                                                <th style="width: 11%;">Re-Ex</th>
                                                <th style="width: 11%;">Credits</th>
                                                @if($record->type != 'auto')
                                                <th style="width: 15%;"></th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody id="">
                                            @foreach(collect($record->subjdata)->unique('subjcode') as $subj)
                                                <tr>
                                                    <td class="p-0"><input type="text" class="form-control form-control-sm input-subjcode" placeholder="Code" value="{{$subj->subjcode}}" disabled/></td>
                                                    <td class="p-0"><input type="number" class="form-control form-control-sm input-subjunit" placeholder="Units" value="{{$subj->subjunit}}" disabled/></td>
                                                    <td class="p-0"><input type="text" class="form-control form-control-sm input-subjdesc" placeholder="Description" value="{{$subj->subjdesc}}" disabled/></td>
                                                    <td class="p-0"><input type="number" class="form-control form-control-sm input-subjgrade" placeholder="Grade" value="{{$subj->subjgrade}}" disabled/></td>
                                                    <td class="p-0"><input type="number" class="form-control form-control-sm input-subjreex" placeholder="Re-Ex" value="{{$subj->subjreex ?? null}}" disabled/></td>
                                                    <td class="p-0"><input type="number" class="form-control form-control-sm input-subjcredit" placeholder="Credit" value="{{$subj->subjcredit}}" disabled/></td>
                                                    @if($record->type != 'auto')
                                                    <td class="p-0 text-right"><button type="button" class="btn btn-default btn-sm btn-editdata" data-subjgradeid="{{$subj->id}}"><i class="fa fa-edit text-warning"></i></button><button type="button" class="btn btn-default btn-sm btn-editdata-save" data-subjgradeid="{{$subj->id}}" disabled><i class="fa fa-share text-success"></i></button><button type="button" class="btn btn-default btn-sm btn-delete-subjdata" data-subjgradeid="{{$subj->id}}" disabled><i class="fa fa-trash text-danger"></i></button></td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
    
    
    <div class="modal fade"  id="modal-newrecord" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Record</h4>
                    <button type="button" id="closeremarks" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>School ID</label>
                            <input type="text" class="form-control" id="input-schoolid"/>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>School Name</label>
                            <input type="text" class="form-control" id="input-schoolname"/>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>School Address</label>
                            <input type="text" class="form-control" id="input-schooladdress"/>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label>Select School Year</label>
                            <select class="form-control select2" id="select-sy">
                                <option value="0">Not on this selection</option>
                                @foreach($schoolyears as $schoolyear)
                                    <option value="{{$schoolyear->syid}}">{{$schoolyear->sydesc}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6" id="div-customsy">
                            <label>Custom School Year</label>
                            <input type="text" class="form-control" id="input-sy"/>
                            <small id="small-inputsy" class="text-danger">*Please fill in custom school year</small>
                        </div>
                        <small id="small-selectsy" class="text-danger col-md-12">*Please select school year. If not on the selection, please specify in the next highlighted field</small>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label>Select Semester</label>
                            <select class="form-control select2" id="select-sem">
                                @foreach(DB::table('semester')->where('deleted','0')->get() as $semester)
                                    <option value="{{$semester->id}}">{{$semester->semester}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>Select Course</label>
                            <select class="form-control select2" id="select-course">
                                <option value="0">Not on this selection</option>
                                @foreach($courses as $course)
                                    <option value="{{$course->id}}">{{$course->courseDesc}}</option>
                                @endforeach
                            </select>
                            <small id="small-selectcourse" class="text-danger">*Please select course. If not on the selection, please specify in the next highlighted field </small>
                        </div>
                    </div>
                    <div class="row mb-2" id="div-customcourse">
                        <div class="col-md-12">
                            <label>Custom Course</label>
                            <input type="text" class="form-control" id="input-coursename"/>
                            <small id="small-inputcoursename">*Please fill in custom course</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" id="btn-close-addnewrecord" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-addnewrecord">Submit</button>
                </div>
            </div>            
        </div>
    <!-- /.modal-content -->
    </div>
    
    
    
    <div class="modal fade"  id="modal-updaterecord" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Record</h4>
                    <button type="button" id="closeremarks" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="container-editrecord">
                    
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" id="btn-close-updaterecord" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-updaterecord">Save Changes</button>
                </div>
            </div>            
        </div>
    <!-- /.modal-content -->
    </div>
</div>
<!-- /.modal-dialog -->
<script>
    $('.btn-addtexts').on('click', function(){
        var thiscard = $(this).closest('.card');
        var thiscontainer = thiscard.find('.container-texts');
        var sydesc = $(this).attr('data-sydesc');
        var semid = $(this).attr('data-semid');
        thiscontainer.append(
            '<div class="row mb-2">'+
                '<div class="col-9">'+
                    '<input type="text" class="form-control form-control-sm" class="texts"/>'  +  
                '</div>'+
                '<div class="col-3 text-right">'+
                    '<button type="button" class="btn btn-sm btn-default text-danger btn-remove"><i class="fa fa-times"></i> Remove</button>&nbsp;&nbsp;'+
                    '<button type="button" class="btn btn-sm btn-default text-success btn-save-text" data-id="0" data-sydesc="'+sydesc+'" data-semid="'+semid+'"><i class="fa fa-share"></i> Save</button>'+
                '</div>'+
            '</div>'
        )
    })
    $(document).on('click','.btn-remove', function(){
        $(this).closest('.row').remove()
    })
    $(document).on('click','.btn-exporttor', function(){
        var format = $(this).attr('data-format')
        console.log(format)
        var collegedean   = $('#input-collegedean').val();
        var preparedncheckedby    = $('#input-preparedncheckedby').val();
        var verifiednreleasedby    = $('#input-verifiednreleasedby').val();
        var clearedby = $('#input-clearedby').val()
        window.open('/schoolform/tor/exporttopdf?studid={{$studentinfo->id}}&collegedean='+collegedean+'&preparedncheckedby='+preparedncheckedby+'&verifiednreleasedby='+verifiednreleasedby+'&clearedby='+clearedby+'&format='+format,'_blank')
    })
</script>