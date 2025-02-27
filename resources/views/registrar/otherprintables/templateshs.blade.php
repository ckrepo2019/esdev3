@if($studentinfo)
@php
    // $pupil = 'pupil';
    // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs')
    // {
    $pupil = 'student';
    // }
@endphp
<form action="/printable/certification/generate" method="GET" target="_blank">
    @csrf
    <input type="hidden" class="form-control" id="input-export" name="export" value="pdf"/>
    <input type="hidden" class="form-control" id="input-template" name="template" value="shs"/>
    <input type="hidden" class="form-control" id="input-syid" name="syid" value="{{$syinfo->id}}"/>
    <input type="hidden" class="form-control" id="input-semid" name="semid" value="{{$semesterinfo->id}}"/>
    <input type="hidden" class="form-control" id="input-studid" name="studid" value="{{$studentinfo->id}}"/>
    <input type="hidden" class="form-control" id="input-studid" name="givendate" value="{{$givendate}}"/>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-default"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <h4>CERTIFICATION</h4>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
            <div class="row mb-3">
                <div class="col-md-12">
                    <p>To Whom It May Concern:</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <u>&nbsp;&nbsp;{{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. {{$studentinfo->lastname}}&nbsp;&nbsp;</u> is officially enrolled in the <u>&nbsp;&nbsp;{{$studentinfo->levelname}} - {{$studentinfo->sectionname}} - {{$studentinfo->strandname}}&nbsp;&nbsp;</u> Course in the {{strtoupper($schoolinfo->schoolname)}}@if(strtolower($schoolinfo->abbreviation) == 'apmc') (Cebuano Barracks Institute), {{ucfirst(strtolower($schoolinfo->division))}}, Zamboanga del Sur @endif during this <u>&nbsp;&nbsp;{{$semesterinfo->semester}}&nbsp;&nbsp;</u>, School Year <u>&nbsp;&nbsp;{{$syinfo->sydesc}}&nbsp;&nbsp;</u>.</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certifies further that the undersigned knows him/her to be Good Moral Character, and as far as knowledgeable information is concerned, he/she has never been charged nor convicted of any crime involving moral turpitude.</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification issued to <u>&nbsp;&nbsp;{{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. {{$studentinfo->lastname}}&nbsp;&nbsp;</u> for All legal purposes that may serve him/her best.</p>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-12">
                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this <u>&nbsp;&nbsp;{{date('jS', strtotime($givendate))}}&nbsp;&nbsp;</u> day of <u>&nbsp;&nbsp;{{date('M', strtotime($givendate))}}&nbsp;&nbsp;</u>, {{date('Y', strtotime($givendate))}} at {{ucwords(strtolower($schoolinfo->address))}}.</p>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4">
                    <label>School Registrar</label>
                    <input type="text" class="form-control" id="input-schoolregistrar" name="schoolregistrar" value="{{$schoolregistrar}}"/>
                </div>
            </div>
            @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
            <div style="width: 100%; font-weight: bold; font-size: 18px;;">
                To Whom It May Concern:
            </div>
            <br/>
            <br/>
            <br/>
            <div style="width: 100%; font-size: 14px; text-align: justify;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <strong>{{$studentinfo->firstname}} {{$studentinfo->middlename}} {{$studentinfo->lastname}}</strong> is a bonafide {{$studentinfo->levelname}} student of the Senior High School under the <strong>{{$studentinfo->strandname}} ({{$studentinfo->strandcode}}) Strand</strong> of this institution for the school year {{$syinfo->sydesc}}.
            </div>
            <br/>
            <div style="width: 100%; font-size: 14px; text-align: justify;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the verbal request of the above-named student (state purpose below) <textarea class="form-control" id="textarea-purpose" name="purpose">for entrance examination purposes </textarea>.
            </div>
            <br/>
            <div style="width: 100%; font-size: 14px; text-align: justify;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Issued this {{date('jS', strtotime($givendate))}} day of {{date('F', strtotime($givendate))}}, {{date('Y', strtotime($givendate))}} at {{$schoolinfo->schoolname}}, {{ucwords(strtolower($schoolinfo->address))}}.
            </div>
            <br/>
            <div class="row mb-5">
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4 text-center">
                    <input type="text" class="form-control" id="input-schoolregistrar" name="schoolregistrar" value="{{$schoolregistrar}}"/>
                    @if($signatoryinfo)
                    <input type="text" class="form-control text-center text-bold" id="input-signatorylabel" name="signatorylabel" 
                    value="{{$signatoryinfo->title}}"/>
                    @else
                     <input type="text" class="form-control text-center text-bold" id="input-signatorylabel" name="signatorylabel" @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ia' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'msl')
                    value="School Principal"
                    @else
                    value="School Registrar"
                    @endif/>
                    @endif
                </div>
            </div>
            @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma')
            <div class="row mb-3">
                <div class="col-md-12">
                    <p>To Whom It May Concern:</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <u>&nbsp;&nbsp;{{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. {{$studentinfo->lastname}}&nbsp;&nbsp;</u> is currently enrolled as <u>&nbsp;&nbsp;{{$studentinfo->levelname}} - {{$studentinfo->strandcode}}&nbsp;&nbsp;</u> student with LRN {{$studentinfo->lrn}} in this institution Stella Matutina Academy of Bukidnon, Inc. for this school year <u>&nbsp;&nbsp;{{$syinfo->sydesc}}&nbsp;&nbsp;</u>.</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is hereby granted upon request of the above named student for whatever legal purposes this may serve hihe/sher best.</p>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-12">
                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this <u>&nbsp;&nbsp;{{date('jS', strtotime($givendate))}}&nbsp;&nbsp;</u> day of <u>&nbsp;&nbsp;{{date('M', strtotime($givendate))}}&nbsp;&nbsp;</u>, {{date('Y', strtotime($givendate))}} at Stella Matutina Academy of Bukidnon, Inc., Kibawe, Bukidnon.</p>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4">
                    <label>Registrar's Incharge</label>
                    <input type="text" class="form-control" id="input-schoolregistrar" name="schoolregistrar" value="{{$schoolregistrar}}"/>
                </div>
            </div>
            @else
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that &nbsp;&nbsp;<u style="font-weight: bold;">{{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. {{$studentinfo->lastname}}</u>&nbsp;&nbsp; is a bonafide &nbsp;&nbsp;<u style="font-weight: bold;">{{$studentinfo->levelname}}</u>&nbsp;&nbsp; {{$pupil}} of  &nbsp;&nbsp;<strong>{{$schoolinfo->schoolname}}</strong>&nbsp;&nbsp; with LRN {{$studentinfo->lrn}} this aschool year&nbsp;&nbsp;{{$syinfo->sydesc}}.</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of the above mention for whatever legal purpose it may serve him/her best.</p>
            <p>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this &nbsp;&nbsp;&nbsp;&nbsp;{{date('jS', strtotime($givendate))}}&nbsp;&nbsp;&nbsp;&nbsp; day of &nbsp;&nbsp;&nbsp;&nbsp;{{date('F', strtotime($givendate))}}&nbsp;&nbsp; {{date('Y', strtotime($givendate))}} at {{ucwords(strtolower($schoolinfo->address))}}.
            </p>
            <br/>
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
                <div class="col-md-4 text-center">
                    <input type="text" class="form-control" id="input-schoolregistrar" name="schoolregistrar" value="{{$schoolregistrar}}"/>
                    @if($signatoryinfo)
                    <input type="text" class="form-control text-center text-bold" id="input-signatorylabel" name="signatorylabel" 
                    value="{{$signatoryinfo->title}}"/>
                    @else
                     <input type="text" class="form-control text-center text-bold" id="input-signatorylabel" name="signatorylabel" @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ia' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'msl')
                    value="School Principal"
                    @else
                    value="School Registrar"
                    @endif/>
                    @endif
                </div>
            </div>
            @endif
            @endif
            <div class="row mb-5">
                <div class="col-md-12">
                    <p>NOT VALID WITHOUT SCHOOL SEAL.
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>
@else

<div class="alert alert-warning" role="alert" id="alert-scholarship">
    <h6 class="alert-heading text-bold"><em>Template</em></h6>
    <p>Not a Senior High Student</p>
</div>
@endif