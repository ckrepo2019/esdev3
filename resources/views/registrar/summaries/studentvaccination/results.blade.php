<div class="card">
    <div class="card-header">
        {{-- <div class="row">
            <div class="col-md-6">
                <button type="button" class="btn btn-sm btn-warning">{{count($students)}} Student(s)</button>
            </div>
            <div class="col-md-6 text-right">
                @if($levelid > 0)
                <button type="button" class="btn btn-sm btn-default" id="btn-export-pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                @endif
            </div>
        </div> --}}
        <div class="row">
            
            {{-- <div class="col-md-3">
                <label>Registrar</label>
                <input type="text" class="form-control" id="input-registrar" value="{{collect($signatories)->where('title','Registrar')->first()->name ?? null}}"/>
            </div>
            <div class="col-md-3">
                <label>President</label>
                <input type="text" class="form-control" id="input-president" value="{{collect($signatories)->where('title','President')->first()->name ?? null}}"/>
            </div> --}}
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-sm btn-warning">{{count($students)}} Student(s)</button>
                <button type="button" class="btn btn-sm btn-default" id="btn-export-pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
            </div>

        </div>
    </div>
    <div class="card-body" >
        <table class="table table-bordered table-hover " style="font-size: 13px;">
        {{-- <table class="table table-bordered" style="font-size: 12px;"> --}}
            <thead>
                <tr>
                    <th style="width: 5%;">Student ID</th>
                    <th style="width: 5%;">LRN</th>
                    <th style="">Student Name</th>
                    <th style="width: 10%;">Vaccination Status</th>
                    <th style="width: 10%;">Vaccination Type</th>
                    <th style="width: 13%;">Vacc Card ID</th>
                    <th style="width: 12%;">First dose</th>
                    <th style="width: 12%;">Second dose</th>
                </tr>
            </thead>
            @foreach($students as $student)
                <tr>
                    <td>{{$student->sid}}</td>
                    <td>{{$student->lrn}}</td>
                    <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                    <td class="text-center">@if(isset($student->vaccstatus))@if($student->vaccstatus == 1)<i class="fa fa-check text-success"></i>@else <i class="fa fa-times text-danger"></i>@endif @endif</td>
                    @if(isset($student->medinfo))
                    <td>{{$student->medinfo->vacc_type}}</td>
                    <td>{{$student->medinfo->vacc_card_id}}</td>
                    <td>{{$student->medinfo->dose_date_1st != null ?  date('M d, Y', strtotime($student->medinfo->dose_date_1st)) : null}}</td>
                    <td>{{$student->medinfo->dose_date_2nd != null ?  date('M d, Y', strtotime($student->medinfo->dose_date_2nd)) : null}}</td>
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>
</div>
<script>    
</script>