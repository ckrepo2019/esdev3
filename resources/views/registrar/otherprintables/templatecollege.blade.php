@if($studentinfo)
<style>
    table td, table th {
        border: none !important;
    }
</style>
<form action="/printable/certification/generate" method="GET" target="_blank">
    @csrf
    <input type="hidden" class="form-control" id="input-export" name="export" value="pdf"/>
    <input type="hidden" class="form-control" id="input-template" name="template" value="college"/>
    <input type="hidden" class="form-control" id="input-syid" name="syid" value="{{$syinfo->id}}"/>
    <input type="hidden" class="form-control" id="input-semid" name="semid" value="{{$semesterinfo->id}}"/>
    <input type="hidden" class="form-control" id="input-studid" name="studid" value="{{$studentinfo->id}}"/>
    <input type="hidden" class="form-control" id="input-givendate" name="givendate" value="{{$givendate}}"/>

    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic')
        <input type="hidden" class="form-control" id="input-exporttype" name="exporttype" value="withratings"/>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-outline-info"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p>TO WHOM IT MAY CONCERN:</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that @if(strtolower($studentinfo->gender) == 'female') MS. @else MR. @endif {{$studentinfo->firstname}} @if($studentinfo->middlename != null) {{$studentinfo->middlename[0]}}.@endif {{$studentinfo->lastname}} {{$studentinfo->suffix}}, a {{$studentinfo->levelname}} student of the course {{$studentinfo->strandname}} under the {{$studentinfo->collegename}} of this Institution was officially enrolled during the {{$semesterinfo->semester}} of SY {{$syinfo->sydesc}} in the subjects listed below with their corresponding grade(s) and unit(s).</p>
                <br/>
                <table class="table">
                    <thead>
                        <tr>
                            <th>CODE</th>
                            <th>DESCRIPTIVE TITLE</th>
                            <th>GRADE</th>
                            <th>C.G.</th>
                            <th>UNITS</th>
                        </tr>
                        <tr>
                            <th colspan="5"><u>College {{$semesterinfo->semester}} A.Y. {{$syinfo->sydesc}}</u></th>
                        </tr>
                    </thead>
                    @if(count($subjects) == 0)
                    <tr>
                        <th>&nbsp;</th>
                        <td></td>
                        <td></td>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                    @else
                        @foreach($subjects as $subj)
                            <tr>
                                <td class="p-0">{{$subj->subjcode}}</td>
                                <td class="p-0">{{$subj->subjdesc}}</td>
                                <td class="p-0 text-center">{{$subj->subjgrade}}</td>
                                <td></td>
                                <td class="p-0 text-center">{{$subj->subjunit}}</td>
                                {{-- <td class="p-0"><input type="text" class="form-control form-control-sm input-subjdesc" placeholder="Description" value="{{$subj->subjdesc}}" disabled/></td>
                                <td class="p-0"><input type="number" class="form-control form-control-sm input-subjgrade" placeholder="Grade" value="{{$subj->subjgrade}}" disabled/></td> --}}
                            </tr>
                        @endforeach
                    @endif
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right">Units:</td>
                        <td class="text-center" style="border-bottom: 1px solid black !important;">{{collect($subjects)->sum('subjunit')}}</td>
                    </tr>
                </table>
                <br/>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of @if(strtolower($studentinfo->gender) == 'female') Ms. @else Mr. @endif {{ucwords(strtolower($studentinfo->lastname))}} this {{date('jS', strtotime($givendate))}} day of {{date('F Y', strtotime($givendate))}} for: <textarea name="purpose" placeholder="State purpose..." class="form-control">reference purposes.</textarea>
                    
                    {{-- <input type="text" namea="purpose" placeholder="State purpose..."/> --}}
                    
                    
                    {{-- Given this <u>&nbsp;&nbsp;{{date('jS', strtotime($givendate))}}&nbsp;&nbsp;</u> day of <u>&nbsp;&nbsp;{{date('M', strtotime($givendate))}}&nbsp;&nbsp;</u>, {{date('Y', strtotime($givendate))}} at {{DB::table('schoolinfo')->first()->schoolname}}, {{DB::table('schoolinfo')->first()->address}}. --}}
                </p>
                <div class="row mb-5">
                    <div class="col-md-4">&nbsp;</div>
                    <div class="col-md-4">&nbsp;</div>
                    <div class="col-md-4">
                        <label>Registrar</label>
                        <input type="text" class="form-control" id="input-schoolregistrar" name="schoolregistrar" value="{{$schoolregistrar}}"/>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <p>NOT VALID WITHOUT SCHOOL SEAL.</p>
                    </div>
                </div>
            </div>
        </div>
    @else
    <input type="hidden" class="form-control" id="input-exporttype" name="exporttype" value="withunits"/>
    
    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">With units</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">With Ratings</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-outline-info"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                                </div>
                                <div class="col-md-12">
                                    <p>To Whom It May Concern:</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <u>&nbsp;&nbsp;{{$studentinfo->firstname}} {{isset($studentinfo->middlename[0]) ? $studentinfo->middlename[0].'. ' : ''}} {{$studentinfo->lastname}}&nbsp;&nbsp;</u> is officially enrolled in <u>&nbsp;&nbsp;{{$studentinfo->strandname}} ({{$studentinfo->strandcode}})&nbsp;&nbsp;</u> at {{DB::table('schoolinfo')->first()->schoolname}} for the {{$semesterinfo->semester}}, School Year {{$syinfo->sydesc}}.</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify further that he/she enrolled the following courses to wit:</p>
                                </div>
                                <div class="col-md-12">
                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic')
                                    <table class="table">
                                    @else
                                    <table class="table mr-5 ml-5">
                                    @endif
                                        <thead>
                                            <tr>
                                                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic')
                                                <th>&nbsp;</th>
                                                <th class=""><i>Course Code</i></th>
                                                @else
                                                {{-- if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc') --}}
                                                <th class="p-0"><i>Course Code</i></th>
                                                <th class="p-0"><i>Course Description</i></th>
                                                {{-- @else
                                                <th>&nbsp;</th>
                                                <th class="text-center"><i>Course Code</i></th> --}}
                                                @endif
                                                <th class="text-center"><i>Units</i></th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        @if(count($subjects) == 0)
                                        <tr>
                                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                                            <td></td>
                                            <th>&nbsp;</th>
                                            @else
                                            <th>&nbsp;</th>
                                            <td></td>
                                            @endif
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        @else
                                            @foreach($subjects as $subj)
                                                {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc') --}}
                                                <tr>
                                                    <td class="p-0">{{$subj->subjcode}}</td>
                                                    <td class="p-0">{{$subj->subjdesc}}</td>
                                                    <td class="p-0 text-center">{{$subj->subjunit}}</td>
                                                    <td></td>
                                                </tr>
                                                {{-- @else
                                                <tr>
                                                    <td></td>
                                                    <td class="p-0">{{$subj->subjcode}}</td>
                                                    <td class="p-0 text-center">{{$subj->subjunit}}</td>
                                                    <td></td>
                                                </tr>
                                                @endif --}}
                                            @endforeach
                                        @endif
                                        <tr>
                                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                                            <th>&nbsp;</th>
                                            @else
                                            <th>&nbsp;</th>
                                            @endif
                                            <th class="text-right">Total</th>
                                            <th style="border-top: 1px solid black;" class="text-center">{{collect($subjects)->sum('subjunit')}}</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon his/her request for whatever legal purposes it may serve him/her best.</p>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-12">
                                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this <u>&nbsp;&nbsp;{{date('jS', strtotime($givendate))}}&nbsp;&nbsp;</u> day of <u>&nbsp;&nbsp;{{date('F', strtotime($givendate))}}&nbsp;&nbsp;</u>, {{date('Y', strtotime($givendate))}} at {{DB::table('schoolinfo')->first()->schoolname}}, {{DB::table('schoolinfo')->first()->address}}.</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-outline-info"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                                </div>
                                <div class="col-md-12">
                                    <p>To Whom It May Concern:</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <u>&nbsp;&nbsp;{{$studentinfo->firstname}} {{isset($studentinfo->middlename[0]) ? $studentinfo->middlename[0].'. ' : ''}} {{$studentinfo->lastname}}&nbsp;&nbsp;</u> is officially enrolled in <u>&nbsp;&nbsp;{{$studentinfo->strandname}} ({{$studentinfo->strandcode}})&nbsp;&nbsp;</u> at {{DB::table('schoolinfo')->first()->schoolname}} for the {{$semesterinfo->semester}}, School Year {{$syinfo->sydesc}}.</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify further that he/she enrolled the following courses and earned the corresponding ratings to wit:</p>
                                </div>
                                <div class="col-md-12">
                                    
                                    {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc') --}}
                                    <table class="table mr-5 ml-5" style="table-layout: fixed;">
                                        <thead>
                                            <tr>
                                                <th class="p-0"><i>Course Code</i></th>
                                                <th class="p-0" style="width: 40%;"><i>Course Description</i></th>
                                                <th class="p-0 text-center"><i>Grades</i></th>
                                                <th class="p-0 text-center"><i>Units</i></th>
                                            </tr>
                                        </thead>
                                        @if(count($subjects) == 0)
                                        <tr>
                                            <th>&nbsp;</th>
                                            <td></td>
                                            <td></td>
                                            <th>&nbsp;</th>
                                        </tr>
                                        @else
                                            @foreach($subjects as $subj)
                                                <tr>
                                                    <td class="p-0">{{$subj->subjcode}}</td>
                                                    <td class="p-0">{{$subj->subjdesc}}</td>
                                                    <td class="p-0 text-center">{{$subj->subjgrade}}</td>
                                                    <td class="p-0 text-center">{{$subj->subjunit}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                    {{-- @else
                                    <table class="table mr-5 ml-5">
                                        <thead>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th class="text-center"><i>Course Code</i></th>
                                                <th class="text-center"><i>Ratings</i></th>
                                                <th class="text-center"><i>Units</i></th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        @if(count($subjects) == 0)
                                        <tr>
                                            <th>&nbsp;</th>
                                            <td></td>
                                            <td></td>
                                            <th>&nbsp;</th>
                                        </tr>
                                        @else
                                            @foreach($subjects as $subj)
                                                <tr>
                                                    <td></td>
                                                    <td class="p-0">{{$subj->subjcode}}</td>
                                                    <td class="p-0 text-center">{{$subj->subjgrade}}</td>
                                                    <td class="p-0 text-center">{{$subj->subjunit}}</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                    @endif --}}
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-12">
                                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this <u>&nbsp;&nbsp;{{date('jS', strtotime($givendate))}}&nbsp;&nbsp;</u> day of <u>&nbsp;&nbsp;{{date('M', strtotime($givendate))}}&nbsp;&nbsp;</u>, {{date('Y', strtotime($givendate))}} at {{DB::table('schoolinfo')->first()->schoolname}}, {{DB::table('schoolinfo')->first()->address}}.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'nsdphs')
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <label>Signatories</label>
                        </div>
                        @if(count($signatoryinfo) == 0)
                            <div class="col-md-4 each-signatory">
                                <input type="text" class="form-control form-control-sm sig-name" name="sig-name[]" placeholder="Name" style="border: none; border-bottom: 1px solid black;"/>
                                <input type="text" class="form-control form-control-sm sig-label" name="sig-label[]" placeholder="E.g. Principal"/>
                                <input type="text" class="form-control form-control-sm sig-label" name="sig-id[]" hidden/>
                            </div>
                            <div class="col-md-4 each-signatory">
                                <input type="text" class="form-control form-control-sm sig-name" name="sig-name[]" placeholder="Name" style="border: none; border-bottom: 1px solid black;"/>
                                <input type="text" class="form-control form-control-sm sig-label" name="sig-label[]" placeholder="E.g. Guidance Coordinator"/>
                                <input type="text" class="form-control form-control-sm sig-label" name="sig-id[]" hidden/>
                            </div>
                            @else
                            <div class="col-md-4 each-signatory">
                                <input type="text" class="form-control form-control-sm sig-name" name="sig-name[]" placeholder="Name" style="border: none; border-bottom: 1px solid black;" value="{{$signatoryinfo[0]->name}}"/>
                                <input type="text" class="form-control form-control-sm sig-label" name="sig-label[]" placeholder="E.g. Principal" value="{{$signatoryinfo[0]->description}}"/>
                                <input type="text" class="form-control form-control-sm sig-label" name="sig-id[]" hidden  value="{{$signatoryinfo[0]->id}}"/>
                            </div>
                            <div class="col-md-4 each-signatory">
                                <input type="text" class="form-control form-control-sm sig-name" name="sig-name[]" placeholder="Name" style="border: none; border-bottom: 1px solid black;" value="{{$signatoryinfo[1]->name}}"/>
                                <input type="text" class="form-control form-control-sm sig-label" name="sig-label[]" placeholder="E.g. Guidance Coordinator" value="{{$signatoryinfo[1]->description}}"/>
                                <input type="text" class="form-control form-control-sm sig-label" name="sig-id[]" hidden  value="{{$signatoryinfo[1]->id}}"/>
                            </div>
                        @endif
                    </div>
                    @else
                    <div class="row mb-5">
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4">
                            <label>Registrar</label>
                            <input type="text" class="form-control" id="input-schoolregistrar" name="schoolregistrar" value="{{$schoolregistrar}}"/>
                        </div>
                    </div>
                    @endif
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <p>NOT VALID WITHOUT SCHOOL SEAL.</p>
                        </div>
                    </div>
                </div>
            
            </div>
        </div>
    </div>
    @endif
</form>
<script>
    $('#custom-tabs-one-home-tab').on('click', function(){
        $('#input-exporttype').val('withunits')
    })
    $('#custom-tabs-one-profile-tab').on('click', function(){
        $('#input-exporttype').val('withratings')
    })
</script>
@else
<div class="alert alert-warning" role="alert" id="alert-scholarship">
    <h6 class="alert-heading text-bold"><em>Template</em></h6>
    <p>Not a College Student</p>
</div>
@endif