@if($studentinfo)
<form action="/printable/certification/goodmoral" method="GET" target="_blank">
    @csrf
    <input type="hidden" class="form-control" id="input-export" name="export" value="pdf"/>
    <input type="hidden" class="form-control" id="input-templatetype" name="templatetype" value="goodmoral"/>
    <input type="hidden" class="form-control" id="input-template" name="template" value="{{$template}}"/>
    <input type="hidden" class="form-control" id="input-syid" name="syid" value="{{$syinfo->id}}"/>
    <input type="hidden" class="form-control" id="input-syid" name="semid" value="{{$semesterinfo->id}}"/>
    <input type="hidden" class="form-control" id="input-studid" name="studid" value="{{$studentinfo->id}}"/>
    <input type="hidden" class="form-control" id="input-studid" name="givendate" value="{{$givendate}}"/>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-default"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-12 text-bold">
                    <p>TO WHOM IT MAY CONCERN:</p>
                </div>
            </div>
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xkhs')
                <div class="row">
                    <div class="col-md-12">
                        <p>TO WHOM IT MAY CONCERN:</p>
                        <br/>
                        <p>This is to certify that <strong>{{$studentinfo->lastname}}, {{$studentinfo->firstname}} @if($studentinfo->middlename != null){{$studentinfo->middlename[0]}}.@endif</strong> is a  @if($template == 'jhs'){{strtolower($studentinfo->levelname)}} @if($studentinfo->levelid < 13) learner @else completer @endif @else Senior High School graduate @endif of {{DB::table('schoolinfo')->first()->schoolname}} for school year {{$syinfo->sydesc}}.</p>
                        <p>This further certifies that she is of good moral character and is cleared from all responsibilities and liabilities of this institution.</p>
                        <textarea class="form-control" id="textarea-purpose" name="purpose">This certification issued upon the request of the above-named student.</textarea> <p>Issued this {{date('jS', strtotime($givendate))}} day of {{date('F', strtotime($givendate))}} {{date('Y', strtotime($givendate))}} at {{DB::table('schoolinfo')->first()->schoolname}}, {{ucwords(strtolower($schoolinfo->address))}}.</p>
                    </div>
                </div>
            @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                <div class="row">
                    <div class="col-md-12">
                        <div>&nbsp;</div>
                        @if($template == 'jhs' || $template == 'shs')
                            <p style="font-size: 17.7px !important; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <strong>{{$studentinfo->lastname}}, {{$studentinfo->firstname}} @if($studentinfo->middlename != null){{$studentinfo->middlename[0]}}.@endif</strong> is a @if($template == 'jhs'){{$studentinfo->levelname}}  @else {{$studentinfo->strandname}} ({{$studentinfo->strandcode}}) @endif student with Learner's Reference Number <strong>{{$studentinfo->lrn}}</strong> of {{DB::table('schoolinfo')->first()->schoolname}}, @if($template == 'jhs') Junior High School Department @else Senior High School Department @endif, for  @if($template != 'jhs'){{$semesterinfo->semester}} and @endif School Year {{$syinfo->sydesc}}.</p>
                        @else
                        <p style="font-size: 17.7px !important; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <strong>{{$studentinfo->lastname}}, {{$studentinfo->firstname}} @if($studentinfo->middlename != null){{$studentinfo->middlename[0]}}.@endif</strong> is a student of {{$studentinfo->strandname}} ({{$studentinfo->strandcode}}) at {{DB::table('schoolinfo')->first()->schoolname}}, for {{$semesterinfo->semester}}, School Year {{$syinfo->sydesc}}.</p>
                        @endif
                        <p style="font-size: 17.7px !important; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify further that he/she has a <strong>GOOD MORAL CHARACTER</strong> standing in our academic community.</p>
                        <p style="font-size: 17.7px !important; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon hihe/sher request for whatever legal purpose(s) it may serve hihe/sher best.</p>
                        <p style="font-size: 17.7px !important; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this {{date('jS', strtotime($givendate))}} day of {{date('F', strtotime($givendate))}}, {{date('Y', strtotime($givendate))}} at {{ucwords(strtolower($schoolinfo->schoolname))}}, {{ucwords(strtolower($schoolinfo->address))}} Philippines.</p>
                    </div>
                </div>
            @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
                <div style="width: 100%; font-size: 14px; text-align: justify;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that based on the records of this office, <strong>{{$studentinfo->firstname}} {{$studentinfo->middlename}} {{$studentinfo->lastname}} was</strong> a {{$studentinfo->levelname}} - {{$studentinfo->sectionname}}  @if($template == 'shs')Senior High School @elseif($template == 'jhs')Junior High School @else Grade School @endif Student of this institution for the school year {{$syinfo->sydesc}}.
                </div>
                <br/>
                <div style="width: 100%; font-size: 14px; text-align: justify;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;That as our learner within the period of @if(strtolower($studentinfo->gender) == 'male')his @else her @endif stay, @if(strtolower($studentinfo->gender) == 'male')he @else she @endif had never infracted nor violated any of the standing policies, rules and regulations of the school.
                </div>
                <br/>
                <div style="width: 100%; font-size: 14px; text-align: justify;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of the above named student for whatever legal purpose this may serve @if(strtolower($studentinfo->gender) == 'male')him @else her @endif best.
                </div>
                <br/>
                <div style="width: 100%; font-size: 14px; text-align: justify;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this {{date('jS', strtotime($givendate))}} day of {{date('F', strtotime($givendate))}}, {{date('Y', strtotime($givendate))}} at {{$schoolinfo->schoolname}}, {{ucwords(strtolower($schoolinfo->address))}}.
                </div>
                <br/>
                
            @else
                <div class="row">
                    <div class="col-md-12">
                        <p><span class="text-bold">THIS IS TO CERTIFY</span> that <u>{{$studentinfo->firstname}} @if($studentinfo->middlename != null){{$studentinfo->middlename[0]}}.@endif {{$studentinfo->lastname}}</u> @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sma') has completed or graduated in @if($template != 'college')Grade @endif @else is a @endif <u>{{$studentinfo->levelname}}</u> @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma') completer @else @if($template == 'elem' || $template == 'jhs') Section <u>{{$studentinfo->sectionname}}</u> @elseif($template == 'shs') Strand  <u>{{$studentinfo->strandname}}</u> @elseif($template == 'college') in  <u>{{$studentinfo->strandname}}</u> @endif  @endif at <span class="text-bold">{{DB::table('schoolinfo')->first()->schoolname}}</span> School Year  <u>{{$syinfo->sydesc}}</u> with Learner Reference Number <u>{{$studentinfo->lrn}}</u> and ESC ID <input type="text" style="border: none; border-bottom: 1px solid black;" name="escid"/>.</p>
                        <p>This is to certify that he/she has good character and has no derogatory record filed in the office. This certification is issued to the above-mentioned student for whatever legal purpose it may serve him/her best.</p>
                        <p>Given this <u>{{date('jS', strtotime($givendate))}}</u> day of <u>{{date('F', strtotime($givendate))}}</u>, {{date('Y', strtotime($givendate))}} at the High School's Principal's Office, {{ucwords(strtolower($schoolinfo->schoolname))}}, {{ucwords(strtolower($schoolinfo->address))}} Philippines.</p>
                    </div>
                </div>
            @endif
            <div class="row mt-5">
                <div class="col-md-12">
                    <label>Signatories</label>
                </div>
                @if(count($signatories) == 0)
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
                    <input type="text" class="form-control form-control-sm sig-name" name="sig-name[]" placeholder="Name" style="border: none; border-bottom: 1px solid black;" value="{{$signatories[0]->name ?? ''}}"/>
                    <input type="text" class="form-control form-control-sm sig-label" name="sig-label[]" placeholder="E.g. Principal" value="{{$signatories[0]->description ?? ''}}"/>
                    <input type="text" class="form-control form-control-sm sig-label" name="sig-id[]" hidden  value="{{$signatories[0]->id ?? ''}}"/>
                </div>
                <div class="col-md-4 each-signatory">
                    <input type="text" class="form-control form-control-sm sig-name" name="sig-name[]" placeholder="Name" style="border: none; border-bottom: 1px solid black;" value="{{$signatories[1]->name ?? ''}}"/>
                    <input type="text" class="form-control form-control-sm sig-label" name="sig-label[]" placeholder="E.g. Guidance Coordinator" value="{{$signatories[1]->description ?? ''}}"/>
                    <input type="text" class="form-control form-control-sm sig-label" name="sig-id[]" hidden  value="{{$signatories[1]->id ?? ''}}"/>
                </div>
                @endif
            </div>
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'xkhs' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sait')
                <div class="row mt-4">
                    <div class="col-md-12">
                        <label>Purpose</label>
                        <textarea name="purpose" class="form-control"></textarea>
                    </div>
                </div>
            @endif
            {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
            <div class="row mb-3">
                <div class="col-md-12">
                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <u>{{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. {{$studentinfo->lastname}}</u> is officially enrolled as <u>{{$studentinfo->levelname}} - {{$studentinfo->sectionname}}</u> student in {{strtoupper($schoolinfo->schoolname)}}@if(strtolower($schoolinfo->abbreviation) == 'apmc') (Cebuano Barracks Institute), {{ucfirst(strtolower($schoolinfo->division))}}, Zamboanga del Sur @endif during the School Year <u>{{$syinfo->sydesc}}</u>.</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certifies further that the undersigned knows him/her to be Good Moral Character, and as far as knowledgeable information is concerned, he/she has never been charged nor convicted of any crime involving moral turpitude.</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification issued to <u>{{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. {{$studentinfo->lastname}}</u> for All legal purposes that may serve him/her best.</p>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-12">
                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this <u>{{date('jS', strtotime($givendate))}}</u> day of <u>{{date('M', strtotime($givendate))}}</u>, {{date('Y', strtotime($givendate))}} at {{ucwords(strtolower($schoolinfo->address))}}.</p>
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
            @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma')
            <div class="row mb-3">
                <div class="col-md-12">
                    <p>To Whom It May Concern:</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <u>&nbsp;&nbsp;{{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. {{$studentinfo->lastname}}&nbsp;&nbsp;</u> is currently enrolled as <u>&nbsp;&nbsp;{{$studentinfo->levelname}}&nbsp;&nbsp;</u> student with LRN {{$studentinfo->lrn}} in this institution Stella Matutina Academy of Bukidnon, Inc. for this school year <u>&nbsp;&nbsp;{{$syinfo->sydesc}}&nbsp;&nbsp;</u>.</p>
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
                    <label>Principal</label>
                    <input type="text" class="form-control" id="input-schoolregistrar" name="schoolregistrar" value="{{$schoolregistrar}}"/>
                </div>
            </div>
            @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
            <div class="row mb-3">
                <div class="col-md-12">
                    <p>To Whom It May Concern:</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <u>&nbsp;&nbsp;{{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. {{$studentinfo->lastname}}&nbsp;&nbsp;</u> with Learner’s   Reference Number (LRN) {{$studentinfo->lrn}}  is currently enrolled as <u>&nbsp;&nbsp;{{$studentinfo->levelname}}&nbsp;&nbsp;</u> student of this institution in the school year <u>&nbsp;&nbsp;{{$syinfo->sydesc}}&nbsp;&nbsp;</u>.</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is hereby granted upon request of the above-named person for whatever legal purpose this may serve hihe/sher best.</p>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-12">
                    <p class="text-justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Done this <u>&nbsp;&nbsp;{{date('jS', strtotime($givendate))}}&nbsp;&nbsp;</u> day of <u>&nbsp;&nbsp;{{date('M', strtotime($givendate))}}&nbsp;&nbsp;</u>, {{date('Y', strtotime($givendate))}} at Loyola High School, Don Carlos, Bukidnon.</p>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4">
                    <label>Principal</label>
                    <input type="text" class="form-control" id="input-schoolregistrar" name="schoolregistrar" value="{{$schoolregistrar}}"/>
                </div>
            </div>
            @else
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that &nbsp;&nbsp;<u style="font-weight: bold;">{{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. {{$studentinfo->lastname}}</u>&nbsp;&nbsp; is a bonafide &nbsp;&nbsp;<u style="font-weight: bold;">{{$studentinfo->levelname}}</u>&nbsp;&nbsp; pupil of  &nbsp;&nbsp;{{$schoolinfo->schoolname}}&nbsp;&nbsp; this &nbsp;&nbsp;{{$syinfo->sydesc}}.</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of the above mention for whatever legal purpose it may serve him best.</p>
            <p>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this &nbsp;&nbsp;&nbsp;&nbsp;{{date('jS', strtotime($givendate))}}&nbsp;&nbsp;&nbsp;&nbsp; day of &nbsp;&nbsp;&nbsp;&nbsp;{{date('F', strtotime($givendate))}}&nbsp;&nbsp; {{date('Y', strtotime($givendate))}} at {{ucwords(strtolower($schoolinfo->address))}}.
            </p>
            <br/>
            <div class="row mb-5">
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4 text-center">
                    <input type="text" class="form-control" id="input-schoolregistrar" name="schoolregistrar" value="{{$schoolregistrar}}"/>
                    <label>Principal</label>
                </div>
            </div>
            @endif
            <div class="row mb-5">
                <div class="col-md-12">
                    <p>NOT VALID WITHOUT SCHOOL SEAL.
                    </p>
                </div>
            </div> --}}
        </div>
    </div>
</form>
@else

<div class="alert alert-warning" role="alert" id="alert-scholarship">
    <h6 class="alert-heading text-bold"><em>Template</em></h6>
    <p>Not a {{strtoupper($template)}} Student</p>
</div>
@endif