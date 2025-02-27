<style>
    img {
        border-radius: unset !important;
    }
</style>
@php
  $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
  $semester = DB::table('semester')->orderBy('id', 'asc')->get();
@endphp
<div class="row">
    @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
        <div class="col-md-3 mb-2">
            <label>Registrar</label>
            <input type="text" class="form-control form-control-sm" id="input-registrar" placeholder="Registrar"
                value="{{ collect($signatories)->where('title', 'Registrar')->first()->name ?? '' }}" />
        </div>
        <div class="col-md-3 mb-2">
            <label>Prepared by</label>
            <input type="text" class="form-control form-control-sm" id="input-preparedby" placeholder="Prepared by"
                value="{{ collect($signatories)->where('description', 'Prepared by')->first()->name ?? '' }}" />
        </div>
        <div class="col-md-3 mb-2">
            <label>Checked by</label>
            <input type="text" class="form-control form-control-sm" id="input-checkedby" placeholder="Checked by"
                value="{{ collect($signatories)->where('description', 'Checked by')->first()->name ?? '' }}" />
        </div>
        <div class="col-md-3 mb-2">
            <label>Date Issued:</label><input type="date" class="form-control form-control-sm p-1"
                id="input-date-issued" placeholder="Date Issued:" value="{{ date('Y-m-d') }}" />
        </div>
    @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
        <div class="col-md-3 mb-2">
            <label>O.R #</label>
            <input type="text" class="form-control form-control-sm" id="input-or" placeholder="O.R #:" />
        </div>
        <div class="col-md-3 mb-2">
            <label>Date Issued:</label><input type="date" class="form-control form-control-sm p-1"
                id="input-date-issued" placeholder="Date Issued:" value="{{ date('Y-m-d') }}" />
        </div>
        <div class="col-md-6 mb-2">
            <em><small>Note: Go to <strong><a href="/setup/signatories">SETUP > School Configuration > School Form
                            Signatories</a></strong> to add signatories.</small></em>
        </div>
    @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
        <div class="col-md-3 mb-2">
            <label>Assistant Registrar</label>
            <input type="text" class="form-control form-control-sm" id="input-assistantreg"
                placeholder="Assistant Registrar" />
        </div>
        <div class="col-md-3 mb-2">
            <label>Registrar</label>
            <input type="text" class="form-control form-control-sm" id="input-registrar" placeholder="Registrar"
                value="{{ collect($signatories)->where('title', 'Registrar')->first()->name ?? '' }}" />
        </div>
    @else
        <div class="col-md-3 mb-2">
            <label>Registrar</label>
            <input type="text" class="form-control form-control-sm" id="input-registrar" placeholder="Registrar"
                value="{{ collect($signatories)->where('title', 'Registrar')->first()->name ?? '' }}" />
        </div>
        <div class="col-md-3 mb-2">
            <label>Assistant Registrar</label>
            <input type="text" class="form-control form-control-sm" id="input-assistantreg"
                placeholder="Assistant Registrar" />
        </div>
        <div class="col-md-3 mb-2">
            <label>O.R #</label>
            <input type="text" class="form-control form-control-sm" id="input-or" placeholder="O.R #:" />
        </div>
        <div class="col-md-3 mb-2">
            <label>Date Issued:</label><input type="date" class="form-control form-control-sm p-1"
                id="input-date-issued" placeholder="Date Issued:" value="{{ date('Y-m-d') }}" />
        </div>
    @endif
    <div class="col-md-12 text-right mb-2">
        @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Export Transcript of Records (TOR)
                </button>
                <div class="dropdown-menu dropdown-menu-right" style="font-size: 14px;">
                    <button class="btn-exportform dropdown-item" data-exporttype="pdf" data-template="1"><i
                            class="fa fa-file-pdf"></i> &nbsp;Template 1 - Board Exam</button>
                    <button class="btn-exportform dropdown-item" data-exporttype="pdf" data-template="2"><i
                            class="fa fa-file-pdf"></i> &nbsp;Template 2 - Employment</button>
                </div>
            </div>
        @else
            {{-- <button type="button" class="btn btn-secondary btn-sm" id="btn-exporttopdf"><i class="fa fa-file-pdf"></i>
                Export TOR to PDF</button> --}}
        @endif

    </div>
</div>
<hr />
@php

    if (strtoupper($studentinfo->gender) == 'FEMALE') {
        $avatar = 'avatar/S(F) 1.png';
    } else {
        $avatar = 'avatar/S(M) 1.png';
    }
@endphp

<div class="row">

    <div class="col-md-12">
        <div class="callout callout-info pt-0">
                <!-- SAIT -->

                <div class="row p-0" style="font-size: 13px !important;">
                    <div class="col-md-12 text-center">
                        <h6 class="text-bold">PERSONAL DATASHEET</h6>
                    </div>
                    <div class="col-md-12">
                        <table style="width: 100%; table-layout: fixed;">
                            <tr>
                                <td style="width: 12%;">Name</td>
                                <td width="21%">: <input type="text" style="width: 90%;" value="{{ $studentinfo->lastname }}, {{ $studentinfo->firstname }} {{ $studentinfo->suffix }} {{ $studentinfo->middlename }}" disabled>
                                <td style="width: 12%;">Sex</td>
                                <td width="21%">: <input type="text" style="width: 90%;" value="{{ $studentinfo->gender }}" disabled></td>
                                <td style="width: 12%;">Date of Entrance</td>
                                <td width="21%">: <input type="date" style="width: 90%;"
                                        value="{{ $details->entrancedate ?? '' }}" id="input-entrancedate" /></td>
                            </tr>
                            <tr>
                                <td style="width: 12%;">Date of Birth</td>
                                <td>: <input type="text" style="width: 90%;" value="{{ $studentinfo->dob ?? '' }}" disabled></td>
                                <td style="width: 12%;">Citizenship</td>
                                <td>: <input type="text" style="width: 90%;"
                                        value="{{ $details->citizenship ?? '' }}" id="input-citizenship" /></td>
                                <td style="width: 12%;">Entrance Data</td>
                                <td>: <input type="text" style="width: 90%;"
                                        value="{{ $details->entrancedata ?? '' }}" id="input-entrancedata" /></td>
                            </tr>
                            <tr>
                                <td style="width: 12%;">Father’s Name </td>
                                <td>: <input type="text" style="width: 90%;" value="{{ $studentinfo->fathername ?? '' }}" disabled></td>
                                <td style="width: 12%;">Civil Status</td>
                                <td>: <input type="text" style="width: 90%;"
                                        value="{{ $details->civilstatus ?? '' }}" id="input-civilstatus" /></td>
                                <td style="width: 12%;">Religion</td>
                                <td>: <input type="text" style="width: 90%;" value="{{ $studentinfo->religionanme ?? '' }}" disabled></td>
                            </tr>
                            <tr>
                                <td style="width: 12%;">Mother’s Name </td>
                                <td>: <input type="text" style="width: 90%;" value="{{ $studentinfo->religionanme ?? '' }}" disabled></td>
                                <td style="width: 12%;">Home Address</td>
                                <td>: <input type="text" style="width: 90%;" value="{{ $studentinfo->street }}, {{ $studentinfo->barangay }},
                                    {{ $studentinfo->city }}, {{ $studentinfo->province }}" disabled></td>
                                <td style="width: 12%;">Birth Place</td>
                                <td>: <input type="text" style="width: 90%;" value="{{ $studentinfo->pob ?? '' }}" disabled></td>
                            </tr>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="callout callout-info pt-0">
                <!-- SAIT -->
                <div class="row p-0" style="font-size: 13px !important;">
                    <div class="col-md-12 text-center">
                        <h6 class="text-bold">RECORDS OF PRELIMINARY EDUCATION</h6>
                    </div>
                    <div class="col-md-12">
                        <table class="" style="table-layout: fixed; width: 100%;" border="1">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Completed Courses</th>
                                    <th>Name of School</th>
                                    <th>Address</th>
                                    <th style="width: 15%;">School Year</th>
                                </tr>
                            </thead>
                            <tr>
                                <td>Primary</td>
                                <td><input type="text" style="width: 100%;"
                                        value="{{ isset($details->primaryschoolname) ? $details->primaryschoolname : $details->gsschoolname ?? '' }}"
                                        id="input-schoolname-primary" /></td>
                                <td><input type="text" style="width: 100%;"
                                        value="{{ $details->primaryschooladdress ?? '' }}"
                                        id="input-schooladdress-primary" /></td>
                                <td><input type="text" style="width: 100%;"
                                        value="{{ isset($details->primaryschoolyear) ? $details->primaryschoolyear : $details->gssy ?? '' }}"
                                        id="input-schoolyear-primary" /></td>
                            </tr>

                            <tr>
                                <td>Junior High School</td>
                                <td><input type="text" style="width: 100%;"
                                        value="{{ $details->juniorschoolname ?? ($details->jhsschoolname ?? '') }}"
                                        id="input-schoolname-junior" /></td>
                                <td><input type="text" style="width: 100%;"
                                        value="{{ $details->juniorschooladdress ?? '' }}"
                                        id="input-schooladdress-junior" /></td>
                                <td><input type="text" style="width: 100%;"
                                        value="{{ $details->juniorschoolyear ?? ($details->jhssy ?? '') }}"
                                        id="input-schoolyear-junior" /></td>
                            </tr>

                            <tr>
                                <td>Senior High School</td>
                                <td><input type="text" style="width: 100%;"
                                        value="{{ $details->seniorschoolname ?? ($details->shsschoolname ?? '') }}"
                                        id="input-schoolname-senior" /></td>
                                <td><input type="text" style="width: 100%;"
                                        value="{{ $details->seniorschooladdress ?? '' }}"
                                        id="input-schooladdress-senior" /></td>
                                <td><input type="text" style="width: 100%;"
                                        value="{{ $details->seniorschoolyear ?? ($details->shssy ?? '') }}"
                                        id="input-schoolyear-senior" /></td>
                            </tr>

                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="" style="table-layout: fixed; width: 100%;">
                            <tr>
                                <td style="width: 15%;">Date of Graduation</td>
                                <td><input type="date" style="width:" value="{{ $details->graduationdate }}"
                                        id="input-graduationdate"
                                        style="border: none; border-bottom: 1px solid #ddd;" /></td>
                                <td class="text-right">NSTP Serial No.: &nbsp;&nbsp;&nbsp;</td>
                                <td><input type="text" style="width: 100%;"
                                        value="{{ $details->nstpserialno ?? '' }}" id="input-nstpserialno" /></td>
                            </tr>
                        </table>

                    </div>
                </div>
            <div class="col-md-12 text-right mt-2">
                <button type="button" class="btn btn-outline-primary" id="btn-details-save"><i
                        class="fa fa-share"></i>&nbsp; Save Changes</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mb-2">
        <button type="button" class="btn btn-info btn-sm" id="btn-addnewrecord" data-toggle="modal"
            data-target="#modal-newrecord"><i class="fa fa-plus"></i> Add new record</button>
    </div>
    @if (count($records) > 0)
        @foreach ($records as $record)
            <div class="col-md-12 @if ($record->type == 'auto') auto-disabled @endif">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <h6><strong>{{ $record->sydesc }} / @if ($record->semid == 1)
                                            1st Semester
                                        @elseif($record->semid == 2)
                                            2nd Semester
                                        @elseif($record->semid == 3)
                                            Summer
                                        @endif </strong></h6>
                            </div>
                            <div class="col-md-8">
                                <h6><strong>{{ $record->coursename }}</strong></h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="row">
                                    <div class="col-2">
                                        <label>School ID</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="{{ $record->schoolid }}" style="border: none;" readonly />
                                    </div>
                                    <div class="col-4">
                                        <label>School Name</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="{{ $record->schoolname }}" style="border: none;" readonly />
                                    </div>
                                    <div class="col-6">
                                        <label>School Address</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="{{ $record->schooladdress }}" style="border: none;" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-right mb-2">
                                {{-- @if (count($record->subjdata) == 0) --}}
                                {{-- <button class="btn btn-sm btn-default btn-adddata text-success" data-torid="{{$record->id}}" data-semid="{{$record->semid}}" data-sydesc="{{$record->sydesc}}" data-courseid="{{$record->courseid}}"><i class="fa fa-plus"></i> Add Data</button> --}}
                                {{-- @else --}}
                                <button type="button" {{ $record->type == 'auto' ? 'hidden' : '' }}
                                    class="btn btn-sm btn-default btn-adddata text-success"
                                    data-torid="{{ $record->id }}" data-semid="{{ $record->semid }}"
                                    data-sydesc="{{ $record->sydesc }}"
                                    data-courseid="{{ $record->courseid }}"><i class="fa fa-plus"></i> Add
                                    Data</button>
                                <button type="button" {{ $record->type == 'auto' ? 'hidden' : '' }}
                                    class="btn btn-sm btn-default btn-editrecord"
                                    data-torid="{{ $record->id }}"><i class="fa fa-edit text-warning"></i> Edit
                                    Record Info</button>
                                <button type="button" {{ $record->type == 'auto' ? 'hidden' : '' }}
                                    class="btn btn-default btn-sm text-danger btn-deleterecord"
                                    data-torid="{{ $record->id }}"><i class="fa fa-trash"></i> Delete this
                                    record</button>

                                {{-- @endif --}}
                            </div>
                        </div>
                    </div>
                    @if (count($record->subjdata) > 0)
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
                                                @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc' ||
                                                        strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ccsa')
                                                    <th style="width: 11%;">Re-Ex</th>
                                                @endif
                                                <th style="width: 11%;">Credits</th>
                                                @if ($record->type != 'auto')
                                                    <th style="width: 15%;"></th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody id="">
                                            {{-- @foreach ($record->subjdata as $subj) --}}
                                            @foreach (collect($record->subjdata)->unique('subjcode') as $subj)
                                                <tr>
                                                    <td class="p-0"><input type="text"
                                                            class="form-control form-control-sm input-subjcode"
                                                            placeholder="Code" value="{{ $subj->subjcode }}"
                                                            disabled /></td>
                                                    <td class="p-0"><input type="number"
                                                            class="form-control form-control-sm input-subjunit"
                                                            placeholder="Units" value="{{ $subj->subjunit }}"
                                                            disabled /></td>
                                                    <td class="p-0"><input type="text"
                                                            class="form-control form-control-sm input-subjdesc"
                                                            placeholder="Description"
                                                            value="{{ $subj->subjdesc }}" disabled /></td>
                                                    <td class="p-0"><input type="text"
                                                            class="form-control form-control-sm input-subjgrade"
                                                            placeholder="Grade" value="{{ $subj->subjgrade }}"
                                                            disabled /></td>
                                                    @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc' ||
                                                            strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ccsa')
                                                        <td class="p-0"><input type="number"
                                                                class="form-control form-control-sm input-subjreex"
                                                                placeholder="Re-Ex"
                                                                value="{{ $subj->subjreex ?? null }}" disabled />
                                                        </td>
                                                    @endif
                                                    <td class="p-0"><input type="number"
                                                            class="form-control form-control-sm input-subjcredit"
                                                            placeholder="Credit" value="{{ $subj->subjcredit }}"
                                                            disabled /></td>
                                                    @if ($record->type != 'auto')
                                                        <td class="p-0 text-right"><button type="button"
                                                                class="btn btn-default btn-sm btn-editdata"
                                                                data-subjgradeid="{{ $subj->id }}"><i
                                                                    class="fa fa-edit text-warning"></i></button><button
                                                                type="button"
                                                                class="btn btn-default btn-sm btn-editdata-save"
                                                                data-subjgradeid="{{ $subj->id }}" disabled><i
                                                                    class="fa fa-share text-success"></i></button><button
                                                                type="button"
                                                                class="btn btn-default btn-sm btn-delete-subjdata"
                                                                data-subjgradeid="{{ $subj->id }}" disabled><i
                                                                    class="fa fa-trash text-danger"></i></button></td>
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
            @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                <div class="col-md-12">
                    <div class="card p-0">
                        <div class="card-body p-0">
                            <div class="col-md-12 p-0">
                                <div class="alert alert-info m-0" role="alert">
                                    Note: Insert texts here. Please separate each line.
                                </div>
                            </div>
                            @if (count($record->texts) > 0)
                                @foreach ($record->texts as $eachtext)
                                    <div class="row mb-2">
                                        <div class="col-9">
                                            <input type="text" class="form-control form-control-sm"
                                                class="texts" value="{{ $eachtext->description }}" />
                                        </div>
                                        <div class="col-3 text-right">
                                            <button type="button"
                                                class="btn btn-sm btn-default text-success btn-save-text"
                                                data-id="{{ $eachtext->id }}"
                                                data-sydesc="{{ $eachtext->sydesc }}"
                                                data-semid="{{ $eachtext->semid }}"><i class="fa fa-share"></i>
                                                Save changes</button>
                                            <button type="button"
                                                class="btn btn-sm btn-default text-danger btn-delete-text"
                                                data-id="{{ $eachtext->id }}"><i class="fa fa-trash-alt"></i>
                                                Remove</button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="col-md-12 text-right mt-2 mb-2">
                                <button type="button" class="btn btn-outline-success btn-addtexts"
                                    data-sydesc="{{ $record->sydesc }}" data-semid="{{ $record->semid }}"><i
                                        class="fa fa-plus"></i> Add Text</button>
                            </div>
                            <div class="col-md-12 container-texts"></div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif


    <div class="modal fade" id="modal-newrecord" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Record</h4>
                    <button type="button" id="closeremarks" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>School ID</label>
                            <input type="text" class="form-control" id="input-schoolid" />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>School Name</label>
                            <input type="text" class="form-control" id="input-schoolname" />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>School Address</label>
                            <input type="text" class="form-control" id="input-schooladdress" />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label>Select School Year</label>
                            <select class="form-control select2" id="select-sy">
                                <option value="0">Not on this selection</option>
                                @foreach ($schoolyears as $schoolyear)
                                    <option value="{{ $schoolyear->syid }}">{{ $schoolyear->sydesc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6" id="div-customsy">
                            <label>Custom School Year</label>
                            <input type="text" class="form-control" id="input-sy" />
                            <small id="small-inputsy" class="text-danger">*Please fill in custom school year</small>
                        </div>
                        <small id="small-selectsy" class="text-danger col-md-12">*Please select school year. If not
                            on the selection, please specify in the next highlighted field</small>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label>Select Semester</label>
                            <select class="form-control select2" id="select-sem">
                                @foreach (DB::table('semester')->where('deleted', '0')->get() as $semester)
                                    <option value="{{ $semester->id }}">{{ $semester->semester }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>Select Course</label>
                            <select class="form-control select2" id="select-course">
                                <option value="0">Not on this selection</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->courseDesc }}</option>
                                @endforeach
                            </select>
                            <small id="small-selectcourse" class="text-danger">*Please select course. If not on the
                                selection, please specify in the next highlighted field </small>
                        </div>
                    </div>
                    <div class="row mb-2" id="div-customcourse">
                        <div class="col-md-12">
                            <label>Custom Course</label>
                            <input type="text" class="form-control" id="input-coursename" />
                            <small id="small-inputcoursename">*Please fill in custom course</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" id="btn-close-addnewrecord"
                        data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-addnewrecord">Submit</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>



    <div class="modal fade" id="modal-updaterecord" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Record</h4>
                    <button type="button" id="closeremarks" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="container-editrecord">

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" id="btn-close-updaterecord"
                        data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-updaterecord">Save
                        Changes</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<!-- /.modal-dialog -->
<script>
    $('.btn-addtexts').on('click', function() {
        var thiscard = $(this).closest('.card');
        var thiscontainer = thiscard.find('.container-texts');
        var sydesc = $(this).attr('data-sydesc');
        var semid = $(this).attr('data-semid');
        thiscontainer.append(
            '<div class="row mb-2">' +
            '<div class="col-9">' +
            '<input type="text" class="form-control form-control-sm" class="texts"/>' +
            '</div>' +
            '<div class="col-3 text-right">' +
            '<button type="button" class="btn btn-sm btn-default text-danger btn-remove"><i class="fa fa-times"></i> Remove</button>&nbsp;&nbsp;' +
            '<button type="button" class="btn btn-sm btn-default text-success btn-save-text" data-id="0" data-sydesc="' +
            sydesc + '" data-semid="' + semid + '"><i class="fa fa-share"></i> Save</button>' +
            '</div>' +
            '</div>'
        )
    })
    $(document).on('click', '.btn-remove', function() {
        $(this).closest('.row').remove()
    })
</script>

<script>
    $(document).ready(function() {
        $('#img-preview').hide()
        $('.upload-result').hide()
        $uploadCrop = $('#img-preview').croppie({
            enableExif: true,
            viewport: {
                width: 304,
                height: 289,
                // type: 'circle'        
            },
            boundary: {
                width: 304,
                height: 289
            }
        });
        var studid = @json($studentid)
        
        $(document).on('hide', '.swal2-loading', function() {
            setTimeout(function() {
                    get_data()
            }, 500);
        })
        get_data()
        function get_data() {
              $.ajax({
                url: "/schoolform/tor/getinfo",
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                    studid: studid
                },
                success: function(data) {
                    $('#studentIdNumber').html(data.sid)
                    $('#studentLevel').html(data.levelname)
                    $('#studentCourse').html(data.courseDesc)
                }
            });
        }
        $(document).on('click', '#credSubjects', function() {
            $('#creditedSubjects').modal('show')
            get_schoolcred_subjcred()
        })

        function get_schoolcred_subjcred(){
            $.ajax({
                type: 'GET',
                url: '/superadmin/student/grade/evaluation/get/credit',
                data: {
                    studid: studid
                }, success: function(data) {
                    $('.school_credit_modal_table').remove()
                    append_credited_schools(data);
                    insert_data_to_credited_table(data)
                }
                
            })
        }

        var semesters = @json($semester);
        function append_credited_schools(data) {
            if (data.length == 0) {
                $('.no_credit').empty();
                $('.no_credit').append(`
                    <p class="mb-0 text-sm font-weight-bold">NO CREDITED UNITS RECORDED</p>
                    <span class="text-sm">(<i class="text-info">Please add subject/s to credit at the Grade Evaluation Module</i>)</span>
                `);
            } else {
                $('.no_credit').empty();
                $.each(data, function (key, school) {
                    $('#credited_table').append(`
                        <div style="font-size: 15px;" class="ml-1 font-weight-bold school_credit_modal_table">${school.schoolname}
                            <button class="btn btn-sm edit_school_credit" data-id="${school.headerid}">
                                <i class="fas fa-edit text-sm text-info ml-2"></i>
                            </button>
                            <button class="btn btn-sm delete_school_credit" data-id="${school.headerid}">
                                <i class="fas fa-trash text-sm text-danger ml-2"></i>
                            </button>
                        </div>
                    `);

                    $.each(semesters, function (index, sem) {
                        $('#credited_table').append(`
                            <div class="card mt-2 d-none school_div_credit-${school.headerid}-${sem.id} school_credit_modal_table">
                                <div class="card-body">
                                    <div>
                                        <div class="d-flex flex-row justify-content-between mb-1">
                                            <div style="font-size: 12px; font-weight: bold">${school.sydesc} - ${sem.semester}</div>
                                        </div>
                                        <table width="100%" class="table table-sm table-bordered table-striped" id="school_credit-${school.headerid}-${sem.id}">
                                            <thead>
                                                <tr style="font-size: 11px">
                                                    <th width="7%">Code</th>
                                                    <th width="25%">Subject Description</th>
                                                    <th width="25%">Pre-Requisite</th>
                                                    <th width="7%" class="text-center">Lecture</th>
                                                    <th width="7%" class="text-center">Laboratory</th>
                                                    <th width="7%" class="text-center">Credited Units</th>
                                                    <th width="7%" class="text-center">GPA</th>
                                                    <th width="7%" class="text-center">Credited</th>
                                                    <th width="8%" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <td contenteditable="true" class="credit_subjCode bg-info" data-school="${school.headerid}" data-sem="${sem.id}"></td>
                                                    <td contenteditable="true" class="credit_subjDesc bg-info" data-school="${school.headerid}" data-sem="${sem.id}"></td>
                                                    <td class="credit_subjPrereq"></td>
                                                    <td contenteditable="true" class="credit_subjLec bg-info text-center" data-school="${school.headerid}" data-sem="${sem.id}"></td>
                                                    <td contenteditable="true" class="credit_subjLab bg-info text-center" data-school="${school.headerid}" data-sem="${sem.id}"></td>
                                                    <td contenteditable="true" class="credit_subjCred bg-info text-center" data-school="${school.headerid}" data-sem="${sem.id}"></td>
                                                    <td contenteditable="true" class="credit_subjGPA text-center bg-info" data-school="${school.headerid}" data-sem="${sem.id}"></td>
                                                    <td></td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-success credit_to_prospectus_subject" data-school="${school.headerid}" data-sem="${sem.id}" data-id="${school.credsubj?.[0]?.levelID || ''}">
                                                            <i class="fa fa-save"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <div class="d-flex flex-row mb-1">
                                            <div style="font-size: 12px;color: red"><i>Please Input Non-Credited Subjects To be Displayed on TOR</i></div>
                                            <div class="d-flex flex-row text-success ml-auto align-middle">
                                                <i class="fa fa-check text-lg  ml-auto"></i> - Credited
                                            </div>
                                            <div class="d-flex flex-row text-danger ml-2 align-middle">
                                                <i class="fa fa-times text-lg  ml-auto"></i> - Not-Credited
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                });
            }
        }
        function insert_data_to_credited_table(data){
            $.each(data, function(key, school) {
                $.each(semester, function(key, sem) {
                    $.each(school.credsubj, function(key, cred) {
                        $('.school_div_credit-' + school.headerid + '-' + cred.semID).removeClass('d-none')
                    })
                    let credited_subj = school.credsubj.filter(cred => cred.schoolID == school.headerid && cred.semID == sem.id)
                    $('#school_credit-' + school.headerid + '-' + sem.id).DataTable({
                        destroy: true,
                        order: false,
                        data: credited_subj,
                        lengthChange: false,
                        info: false,
                        paging: false,
                        searching: false,
                        columns: [
                            {data: 'subjCode'},
                            {data: 'subjDesc'},
                            {data: null},
                            {data: null},
                            {data: null},
                            {data: null},
                            {data: null},
                            {data: null},
                            {data: null},
                        ],
                        columnDefs: [
                            {
                                targets: 0,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    let credpreq = rowData.credpreq.length > 0 ? rowData.credpreq[0].subjDesc : ''
                                    $(td).html(`${rowData.subjCode}`)
                                    .addClass('mb-0 align-middle credited_subjCode')
                                    .attr('data-school', rowData.schoolID)
                                    .attr('data-sem', rowData.semID)
                                    .attr('data-id', rowData.id)
                                }
                            },
                            {
                                targets: 1,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    let credpreq = rowData.credpreq.length > 0 ? rowData.credpreq[0].subjDesc : ''
                                    $(td).html(`${rowData.subjDesc}`)
                                    .addClass('mb-0 align-middle credited_subjDesc')
                                    .attr('data-school', rowData.schoolID)
                                    .attr('data-sem', rowData.semID)
                                    .attr('data-id', rowData.id)
                                }
                            },
                            {
                                targets: 2,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    let credpreq = rowData.credpreq.length > 0 ? rowData.credpreq[0].subjDesc : ''
                                    $(td).html(`<p class="mb-0">${credpreq}</p>`)
                                    .addClass('mb-0 ')
                                }
                            },
                            {
                                targets: 3,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p class="mb-0">${rowData.lecunits ? rowData.lecunits : ''}</p>`)
                                    .addClass('text-center  align-middle credited_lecunits')
                                    .attr('data-school', rowData.schoolID)
                                    .attr('data-sem', rowData.semID)
                                    .attr('data-id', rowData.id)
                                }
                            },
                            {
                                targets: 4,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="mb-0">${rowData.labunits ? rowData.labunits : ''}</p>`)
                                    .addClass('text-center m-0 mb-0  align-middle credited_labunits')
                                    .attr('data-school', rowData.schoolID)
                                    .attr('data-sem', rowData.semID)
                                    .attr('data-id', rowData.id)
                                }
                            },
                            {
                                targets: 5,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="mb-0">${rowData.credunits ? rowData.credunits : ''}</p>`)
                                    .addClass('text-center p-0 mb-0  align-middle credited_credunits')
                                    .attr('data-school', rowData.schoolID)
                                    .attr('data-sem', rowData.semID)
                                    .attr('data-id', rowData.id)
                                }
                            },
                            {
                                targets: 6,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    $(td).html(`<p  class="mb-0">${rowData.gpa != null ? rowData.gpa : ''}</p>`)
                                    .addClass('text-center p-0 mb-0  align-middle credited_gpa')
                                    .attr('data-school', rowData.schoolID)
                                    .attr('data-sem', rowData.semID)
                                    .attr('data-id', rowData.id)
                                }
                            },
                            {
                                targets: 7,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    if(rowData.status == 0){
                                        $(td).html(`<p  class="mb-0"><i class="fa fa-check text-success"></i></p>`)
                                    }else{
                                        $(td).html(`<p  class="mb-0"><i class="fa fa-times text-danger"></i></p>`)
                                    }
                                    $(td).addClass('text-center p-0 mb-0  align-middle')
                                }
                            },
                            {
                                targets: 8,
                                orderable: false,
                                createdCell: function(td, cellData, rowData) {
                                    if(rowData.status == 1){
                                        $(td).html(`<p  class="mb-0">
                                            <button class="btn btn-sm save_edit_additional_credit d-none" data-id="${rowData.id}" data-school="${rowData.schoolID}" data-sem="${rowData.semID}"><i class="fa fa-save text-success"></i></button>
                                            <button class="btn btn-sm close_edit_additional_credit d-none" data-id="${rowData.id}" data-school="${rowData.schoolID}" data-sem="${rowData.semID}"><i class="fa fa-window-close text-danger"></i></button>
                                            <button class="btn btn-sm edit_additional_credit" data-id="${rowData.id}" data-school="${rowData.schoolID}" data-sem="${rowData.semID}"><i class="fa fa-edit text-primary"></i></button>
                                            <button class="btn btn-sm delete_additional_credit" data-id="${rowData.id}"><i class="fa fa-trash text-danger"></i></button>
                                            </p>`)
                                        .addClass('text-center mb-0  align-middle ')

                                    }else{
                                        $(td).html(`<p  class="mb-0"></p>`)
                                        .addClass('text-center mb-0  align-middle ')

                                    }
                                }
                            },
                        ]
                    })
                })
            })
        }


        

        $(document).on('change', '#input-upload-photo', function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                $uploadCrop.croppie('bind', {
                    url: e.target.result
                }).then(function() {
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
            $('#img-preview').show()
            $('.upload-result').show()
        });
        $('.upload-result').on('click', function(ev) {
            var studid = $('#select-studentid').val();
            $uploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(resp) {
                $.ajax({
                    url: "/setup/studdisplayphoto/uploadphoto",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "image": resp,
                        "studid": '{{ $studentinfo->id }}'
                    },
                    success: function(data) {
                        $('#image-view').attr('src', data + '?random=' + new Date($
                            .now()))
                        $('.upload-result').hide()
                        $('#img-preview').hide()
                        $('#input-upload-photo').val('')
                    }
                });
            });
        });
    })
</script>
