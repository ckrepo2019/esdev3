<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-2">
                <label>Registrar</label>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control form-control-sm registrar-name" value="{{$signatory[0]->name ?? null}}"/>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-default btn-sm" id="btn-export"><i class="fa fa-file-pdf"></i> Export to PDF</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label>No.</label>
                <input type="number" class="form-control form-control-sm" id="input-transferno" value="{{$studtransferelig->transferno ?? null}}"/>
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
                <label>Date</label>
                <input type="date" class="form-control form-control-sm" value="{{$studtransferelig->transferdate ?? date('Y-m-d')}}" id="input-transferdate"/>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12">
                <p><u>{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename[0]}}.</u> is hereby granted Transfer Credential/Honorable Dismissal from the <u>{{$studinfo->collegename}} ({{$studinfo->coursename}})</u> effective today.</p>
            </div>
        </div>
        {{-- <hr/> --}}
        {{-- <div class="row mt-2">
            <div class="col-md-12 text-center">
                <h5>REQUEST CARD</h5>
            </div>
            <div class="col-md-3"><label>Name of School</label></div>
            <div class="col-md-9"><input type="text" class="form-control form-control-sm"/></div>
            <div class="col-md-3"><label>Address</label></div>
            <div class="col-md-9"><input type="text" class="form-control form-control-sm"/></div>
            <div class="col-md-3"><label>Date</label></div>
            <div class="col-md-3"><input type="date" class="form-control form-control-sm"/></div>
        </div>
        <div class="row mt-2 mb-3">-
            <div class="col-md-12">The Registrar</div>
            <div class="col-md-12">{{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}}</div>
            <div class="col-md-12">{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</div>
        </div>
        <div class="row">
            <div class="col-md-12">Sir/Madam:</div>
            <div class="col-md-12">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; I have the honor to request you to send us the Transcript of Record of <u>{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename[0]}}.</u> who has been temporarily enrolled in this school for the <select>@foreach(DB::table('semester')->get() as $eachsem)<option value="{{$eachsem->id}}">{{$eachsem->semester}}</option>@endforeach</select> semester/ semester, <input type="text" style="border: hidden; border-bottom: 1px solid black;"/> upon presentation of his/her Certificate of Eligibility to Transfer.</div>
        </div> --}}
    </div>
</div>