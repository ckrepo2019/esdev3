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
    <div class="col-md-3 mb-2">
        <label>Registrar</label>
        <input type="text" class="form-control form-control-sm" id="input-registrar" placeholder="Registrar" value="{{collect($signatories)->where('title','Registrar')->first()->name ?? ''}}"/>
    </div>
    <div class="col-md-3 mb-2">
        <label>Assistant Registrar</label>
        <input type="text" class="form-control form-control-sm" id="input-assistantreg" placeholder="Assistant Registrar"/>
    </div>
    <div class="col-md-3 mb-2">
        <label>O.R #</label>
        <input type="text" class="form-control form-control-sm" id="input-or" placeholder="O.R #:"/>
    </div>
    <div class="col-md-3 mb-2">
        <label>Date Issued:</label><input type="date" class="form-control form-control-sm p-1" id="input-date-issued" placeholder="Date Issued:" value="{{date('Y-m-d')}}"/>
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
          Export TOR
        </button>
        <div class="dropdown-menu dropdown-menu-right">
          <button class="btn-exporttor dropdown-item" data-format="pdf">to PDF</button>
          <button class="btn-exporttor dropdown-item" data-format="excel">to Excel</button>
        </div>
      </div>
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
        <div class="row p-0" style="font-size: 13px !important;">
            
            <div class="col-md-12">
                <table style="width: 100%; table-layout: fixed;">
                    <tr>
                        <td>Date of Admission <span class="float-right">:</span></td>
                        <td colspan="2" > <input type="date"  style="width: 100%;" value="{{$details->admissiondate}}" id="input-admissiondate" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                        <td>Admission  Credential <span class="float-right">:</span></td>
                        <td colspan="2"><input type="text" style="width: 100%;" value="{{$details->entrancedata ?? ''}}" id="input-entrancedata"/></td>
                    </tr>
                    <tr>
                        <td style="width: 12%;">Home Address <span class="float-right">:</span></td>
                        <td colspan="5">{{$studentinfo->street}}, {{$studentinfo->barangay}}, {{$studentinfo->city}}, {{$studentinfo->province}}</td>
                    </tr>
                    {{-- <tr>
                        <td style="width: 12%;">Birth Place</td>
                        <td>: {{$studentinfo->pob ?? ''}}</td>
                        <td style="width: 12%;">Citizenship</td>
                        <td>:  <input type="text" style="width: 90%;" value="{{$details->citizenship ?? ''}}" id="input-citizenship"/></td>
                        <td style="width: 12%;">Entrance Data</td>
                        <td>: <input type="text" style="width: 90%;" value="{{$details->entrancedata ?? ''}}" id="input-entrancedata"/></td>
                    </tr> --}}
                    <tr>
                        <td style="width: 12%;">Place of Birth <span class="float-right">:</span></td>
                        <td colspan="2">{{$studentinfo->pob ?? ''}}</td>
                        <td style="width: 12%;">Civil Status <span class="float-right">:</span></td>
                        <td colspan="2"><input type="text" style="width: 100%;" value="{{$details->civilstatus ?? ''}}" id="input-civilstatus"/></td>
                    </tr>
                    <tr>
                        <td style="width: 12%;">City Address <span class="float-right">:</span></td>
                        <td colspan="5"> <input type="text" style="width: 100%;" value="{{$details->cityaddress ?? ''}}" id="input-cityaddress"/></td>
                    </tr>
                    <tr>
                        <td style="width: 12%;">Citizenship <span class="float-right">:</span></td>
                        <td colspan="2"><input type="text" style="width: 100%;" value="{{$details->citizenship ?? ''}}" id="input-citizenship"/></td>
                        <td style="width: 12%;">Religion <span class="float-right">:</span></td>
                        <td colspan="2">{{$studentinfo->religionanme ?? ''}}</td>
                    </tr>
                    <tr>
                        <td style="width: 12%;">Degree <span class="float-right">:</span></td>
                        <td colspan="5"><input type="text" style="width: 100%;"  value="{{$details->degree != null ? $details->degree : ($coursename ?? '')}}" id="input-degree" style="border: none; border-bottom: 1px solid #ddd;"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 12%;">Father’s Name <span class="float-right">:</span></td>
                        <td colspan="2">{{$studentinfo->fathername ?? ''}}</td>
                        <td style="width: 12%;">Mother’s Name <span class="float-right">:</span></td>
                        <td colspan="2">{{$studentinfo->mothername ?? ''}}</td>
                    </tr>
                    <tr>
                        <td style="width: 12%;">Major <span class="float-right">:</span></td>
                        <td colspan="2">
                            <input type="text" style="width: 100%;"  value="{{$details->major != null ? $details->major : ($major ?? '')}}" id="input-major" style="border: none; border-bottom: 1px solid #ddd;"/>
                        </td>
                        <td style="width: 12%;">Date Conferred <span class="float-right">:</span></td>
                        <td colspan="2"><input type="date" style="width: 100%;" value="{{$details->graduationdate ?? ''}}" id="input-graduationdate" style="border: none; border-bottom: 1px solid #ddd;"/>
                        </td></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-12 text-center">
                <h6 class="text-bold">RECORDS OF PRELIMINARY EDUCATION</h6>
            </div>
            <div class="col-md-12">
                <table class="" style="table-layout: fixed; width: 100%;" border="1">
                    <thead>
                        <tr>
                            <th style="width: 15%;"></th>
                            <th>Name of School</th>
                            <th>Address</th>
                            <th style="width: 15%;">School Year</th>
                        </tr>
                    </thead>
                    <tr>
                        <td>Primary</td>
                        <td><input type="text"  style="width: 100%;" value="{{$details->primaryschoolname ?? ''}}" id="input-schoolname-primary"/></td>
                        <td><input type="text"  style="width: 100%;" value="{{$details->primaryschooladdress ?? ''}}" id="input-schooladdress-primary"/></td>
                        <td><input type="text"  style="width: 100%;" value="{{$details->primaryschoolyear ?? ''}}" id="input-schoolyear-primary"/></td>
                    </tr>
                    <tr>
                        <td>Intermediate</td>
                        <td><input type="text"  style="width: 100%;" value="{{$details->intermediateschoolname ?? ''}}" id="input-schoolname-intermediate"/></td>
                        <td><input type="text"  style="width: 100%;" value="{{$details->intermediateschooladdress ?? ''}}" id="input-schooladdress-intermediate"/></td>
                        <td><input type="text"  style="width: 100%;" value="{{$details->intermediatesy ?? ''}}" id="input-intermediateschoolyear"/></td>
                    </tr>
                    <tr>
                        <td>High School</td>
                        <td><input type="text"  style="width: 100%;" value="{{$details->juniorschoolname ?? ''}}" id="input-schoolname-junior"/></td>
                        <td><input type="text"  style="width: 100%;" value="{{$details->juniorschooladdress ?? ''}}" id="input-schooladdress-junior"/></td>
                        <td><input type="text"  style="width: 100%;" value="{{$details->juniorschoolyear ?? ''}}" id="input-schoolyear-junior"/></td>
                    </tr>
                </table>
            </div>
            {{-- <div class="col-md-12">
                <table class="" style="table-layout: fixed; width: 100%;">
                    <tr>
                        <td style="width: 15%;">Date of Graduation</td>
                        <td><input type="date" style="width:" value="{{$details->graduationdate}}" id="input-graduationdate" style="border: none; border-bottom: 1px solid #ddd;"/></td>
                        <td class="text-right">NSTP Serial No.: &nbsp;&nbsp;&nbsp;</td>
                        <td><input type="text"  style="width: 100%;" value="{{$details->nstpserialno ?? ''}}" id="input-nstpserialno"/></td>
                    </tr>
                </table>
                
            </div> --}}
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
        var registrar    = $('#input-registrar').val();
        var assistantreg = $('#input-assistantreg').val();
        var or           = $('#input-or').val();
        var dateissued   = $('#input-date-issued').val();
        window.open('/schoolform/tor/exporttopdf?studid={{$studentinfo->id}}&registrar='+registrar+'&assistantreg='+assistantreg+'&or='+or+'&dateissued='+dateissued+'&format='+format,'_blank')
    })
</script>