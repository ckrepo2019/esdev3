<div class="modal fade" id="modal-record" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Generate Record of Candidate for Graduation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-12 text-right">
                        {{-- <button type="button" class="btn btn-sm btn-outline-info" id="btn-exportpdf"><i class="fa fa-file-pdf"></i> Export to PDF</button> --}}
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Export Record
                            </button>

                            <div class="dropdown-menu dropdown-menu-right" style="font-size: 14px;">
                                <button class="btn-export-record dropdown-item" data-exporttype="pdf"
                                    data-format="school" data-layout="1" id="btn-exportpdf"><i
                                        class="fa fa-file-pdf"></i> &nbsp;PDF -
                                    @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                                        Application for Graduation from Collegiate Course
                                    @else
                                        Record of Candidate For Graduation
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header p-1">
                        <div class="row">
                            @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                                <div class="col-md-6">
                                    <label>Date of Graduation</label>
                                    <input type="date" class="form-control form-control-sm"
                                        id="input-graduationdate" />
                                </div>
                            @else
                                <div class="col-md-6"> {{-- sbc --}}
                                    <label>Title/Degree</label>
                                    <input type="text" class="form-control form-control-sm"
                                        placeholder="Ex. Diploma In Midwifery" id="input-degree"
                                        value="{{ $degree != null ? $degree : $coursename }}" />
                                </div>
                            @endif
                            <div class="col-md-6">
                                <label>Entrance Data</label>
                                <input type="text" class="form-control form-control-sm"
                                    placeholder="Ex. Certificate of Transfer Credential/Transcript of Records for Evaluation"
                                    id="input-entrancedata" value="{{ $details->entrancedata ?? '' }}" />
                            </div>
                            @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                                <div class="col-md-4">
                                    <label>Intermediate Course</label>
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        id="input-intermediatecourse" value="{{ $details->secondcourse ?? '' }}" />
                                </div>
                                <div class="col-md-2">
                                    <label>Year</label>
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        id="input-intermediateyear" value="{{ $details->intermediatesy ?? '' }}" />
                                </div>
                                <div class="col-md-4">
                                    <label>Secondary Course</label>
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        id="input-secondarycourse" value="{{ $details->intermediatecourse ?? '' }}" />
                                </div>
                                <div class="col-md-2">
                                    <label>Year</label>
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        id="input-secondaryyear" value="{{ $details->secondsy ?? '' }}" />
                                </div>
                            @endif
                            <div class="col-md-3">
                                <label>Checked by</label>
                                <input type="text" class="form-control form-control-sm" placeholder=""
                                    id="input-checkedby" />
                            </div>
                            <div class="col-md-3">
                                <label>College Registrar</label>
                                <input type="text" class="form-control form-control-sm" placeholder=""
                                    id="input-collegereg" />
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-1">
                        <input type="text" id="pdf_studid" hidden>
                        <table class="table m-1 table-bordered" style="font-size: 12px; table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">COURSE NO.</th>
                                    <th style="width: 30%;" class="text-center">DESCRIPTIVE TITLE</th>
                                    <th class="text-center">FINAL</th>
                                    <th class="text-center">Re-exam</th>
                                    <th class="text-center">UNIT</th>
                                    @if (count($subjgroups) > 0)
                                        @foreach ($subjgroups as $subjgroup)
                                            <th class="text-center">{{ $subjgroup->sortnum }}</th>
                                        @endforeach
                                    @endif
                                    <th class="text-center" style="width: 7%;">Actions</th>
                                </tr>
                            </thead>
                            @if (collect($collectgradelevels)->where('syid', '>', '0')->count() > 0)
                                @foreach (collect($collectgradelevels)->where('syid', '>', '0')->values() as $collectgradelevel)
                                    <tr>
                                        <td colspan="2">
                                            @if ($collectgradelevel->syid > 0)
                                                <u>{{ DB::table('schoolinfo')->first()->schoolname }} -
                                                    {{ ucwords(strtolower(DB::table('schoolinfo')->first()->address)) }}</u>
                                            @endif
                                        </td>
                                        <td></td>
                                        <td></td>
                                        @if (count($subjgroups) > 0)
                                            @foreach ($subjgroups as $subjgroup)
                                                <td></td>
                                            @endforeach
                                        @endif
                                    </tr>
                                    <tr>
                                        <td colspan="2"><u>
                                                @if ($collectgradelevel->semid == 1)
                                                    FIRST SEMESTER {{ $collectgradelevel->sydesc }}
                                                @elseif($collectgradelevel->semid == 2)
                                                    SECOND SEMESTER {{ $collectgradelevel->sydesc }}
                                                @else
                                                    SUMMER
                                                @endif
                                            </u></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        @if (count($subjgroups) > 0)
                                            @foreach ($subjgroups as $subjgroup)
                                                <td></td>
                                            @endforeach
                                        @endif
                                        <td></td>
                                    </tr>
                                    @if (count($collectgradelevel->subjects) > 0)
                                        @foreach ($collectgradelevel->subjects as $eachsubject)
                                            <tr>
                                                <td>
                                                    {{ $eachsubject['subjCode'] }}
                                                </td>
                                                <td>{{ $eachsubject['subjDesc'] }}
                                                </td>
                                                <td class="text-center">{{ $eachsubject['subjgrade'] }}</td>
                                                <td></td>
                                                <th class="text-center">{{ $eachsubject['units'] }}
                                                </th>
                                                @if (count($subjgroups) > 0)
                                                    @foreach ($subjgroups as $key => $subjgroup)
                                                        <td class="text-center">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio"
                                                                    id="{{ $eachsubject['subjCode'] }}-{{ $key }}"
                                                                    name="{{ $eachsubject['subjCode'] }}"
                                                                    value="{{ $subjgroup->id }}"
                                                                    @if ($subjgroup->id == $eachsubject['subjgroupid']) checked @endif><label
                                                                    for="{{ $eachsubject['subjCode'] }}-{{ $key }}"></label>
                                                            </div>
                                                        </td>
                                                    @endforeach
                                                @endif
                                                <td><button type="button"
                                                        class="btn btn-sm btn-default p-0 btn-block btn-updateunitplot"
                                                        data-syid="{{ $collectgradelevel->syid }}"
                                                        data-semid="{{ $collectgradelevel->semid }}"
                                                        data-subjectid="{{ $eachsubject['subjectID'] }}">Update</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>
                                            </td>
                                            <td>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <th class="text-center" style="border-top: 2px solid black;">
                                                {{ collect($collectgradelevel->subjects)->sum('units') }}
                                            </th>
                                            @if (count($subjgroups) > 0)
                                                @foreach ($subjgroups as $subjgroup)
                                                    <td></td>
                                                @endforeach
                                            @endif
                                            <td></td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
