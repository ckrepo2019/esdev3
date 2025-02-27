{{-- 
<form action="/printable/coranking" method="GET" target="_blank">
    @csrf
    <input type="hidden" class="form-control" id="input-export" name="export" value="pdf"/>
    <input type="hidden" class="form-control" name="action" value="export"/>
    <input type="hidden" class="form-control" id="input-studid" name="studid" value="{{$studinfo->id}}"/>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12 text-center">
                    <h4 class="m-0 text-bold">C  E  R  T  I  F  I  C  A  T  I  O  N</h4>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-default"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                </div>
                <div class="col-md-12">
                    <p>TO WHOM IT MAY CONCERN:</p>
                    <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <u><strong>{{ucwords(strtolower($studinfo->lastname))}} {{ucwords(strtolower($studinfo->firstname))}} {{ucwords(strtolower($studinfo->middlename))}} {{ucwords(strtolower($studinfo->suffix))}}</strong></u>, graduated from this institution with the degree of <u><strong>{{$studinfo->coursename}}</strong></u> as of <input type="date" name="input-date-asof" required/>.</p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify further that she graduated rank _____ out of ______ graduates with honor point average of _____.</p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This further certifies that the  <input type="text" name="input-program" required/> Program of {{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}} is an ACSCU-AAI accredited / FAAP- certified Level II status and is, therefore, exempted from Special Order Requirement (CHED Order No. 31, s. 1995 dated September 25, 1995). </p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued for employment purposes.</p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Issued this <input type="date" name="input-date-issued" value="{{date('Y-m-d')}}"/> at {{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}}, {{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}.</p>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4 text-center">
                    <input type="text" class="form-control" id="input-schoolregistrar" name="schoolregistrar" value="{{$schoolregistrar ?? null}}"/>
                    <label>Registrar</label>
                </div>
            </div>
        </div>
    </div>
</form> --}}

<div class="modal fade" id="corankingModal" tabindex="-1" role="dialog" aria-labelledby="corankingModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="corankingModalLabel">C E R T I F I C A T I O N</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/printable/coranking" method="GET" target="_blank">
                    @csrf
                    <input type="hidden" class="form-control" id="input-export" name="export" value="pdf" />
                    <input type="hidden" class="form-control" name="action" value="export" />
                    <input type="hidden" class="form-control" id="input-studid" name="studid"
                        value="{{ $studinfo->id }}" />
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-file-pdf"></i> Export
                                        to PDF</button>
                                </div>
                                <div class="col-md-12">
                                    <p>TO WHOM IT MAY CONCERN:</p>
                                    <p style="text-align: justify;">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This
                                        is to certify that <u><strong>{{ ucwords(strtolower($studinfo->lastname)) }}
                                                {{ ucwords(strtolower($studinfo->firstname)) }}
                                                {{ ucwords(strtolower($studinfo->middlename)) }}
                                                {{ ucwords(strtolower($studinfo->suffix)) }}</strong></u>, graduated
                                        from
                                        this institution with the degree of
                                        <u><strong>{{ $studinfo->coursename }}</strong></u>
                                        as of <input type="date" name="input-date-asof" required />.
                                    </p>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This
                                        is to certify further that she graduated rank _____ out of ______ graduates with
                                        honor point average of _____.</p>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This
                                        further certifies that the <input type="text" name="input-program"
                                            required />
                                        Program of
                                        {{ ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname)) }} is an
                                        ACSCU-AAI accredited / FAAP- certified Level II status and is, therefore,
                                        exempted
                                        from Special Order Requirement (CHED Order No. 31, s. 1995 dated September 25,
                                        1995). </p>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This
                                        certification is issued for employment purposes.</p>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Issued
                                        this <input type="date" name="input-date-issued"
                                            value="{{ date('Y-m-d') }}" /> at
                                        {{ ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname)) }},
                                        {{ ucwords(strtolower(DB::table('schoolinfo')->first()->address)) }}.</p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">&nbsp;</div>
                                <div class="col-md-4">&nbsp;</div>
                                <div class="col-md-4 text-center">
                                    <input type="text" class="form-control" id="input-schoolregistrar"
                                        name="schoolregistrar" value="{{ $schoolregistrar ?? null }}" />
                                    <label>Registrar</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
