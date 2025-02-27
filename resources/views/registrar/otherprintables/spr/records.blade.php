<style>
    .alert {
        position: relative;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.25rem;
    }
    .alert-primary {
        color: #004085;
        background-color: #cce5ff;
        border-color: #b8daff;
    }
    .alert-secondary {
        color: #383d41;
        background-color: #e2e3e5;
        border-color: #d6d8db;
    }
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }
    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }
    td, th{
        padding: 2px !important;
    }
</style>
{{-- <div class="alert alert-primary" role="alert">
    This is a primary alert—check it out!
  </div>
  <div class="alert alert-secondary" role="alert">
    This is a secondary alert—check it out!
  </div>
  <div class="alert alert-success" role="alert">
    This is a success alert—check it out!
  </div>
  <div class="alert alert-danger" role="alert">
    This is a danger alert—check it out!
  </div>
  <div class="alert alert-warning" role="alert">
    This is a warning alert—check it out!
  </div>
  <div class="alert alert-info" role="alert">
    This is a info alert—check it out!
  </div>
  <div class="alert alert-light" role="alert">
    This is a light alert—check it out!
  </div>
  <div class="alert alert-dark" role="alert">
    This is a dark alert—check it out!
  </div> --}}
@if(count($records) == 0 )
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning" role="alert">
              No records shown!
            </div>
        </div>
    </div>
@else
<div class="card">
    <div class="card-header p-1 text-right">
        <button type="button" class="btn btn-outline-success btn-sm btn-export-pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
    </div>
    <div class="card-body">
        @foreach($records as $record)
        @if(count($record->subjects)>0)
            <div class="row">
                <div class="col-md-6">
                    <button type="button" class="btn btn-default text-bold btn-sm" style="cursor: default;">S.Y. {{$record->sydesc}}, {{$record->semester}}</button>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-default text-bold btn-sm"  style="cursor: default;">{{$record->levelname}}</button>
                    {{-- <button type="button" class="btn btn-outline-success btn-sm btn-export-pdf" data-syid="{{$record->syid}}" data-semid="{{$record->semid}}" data-levelid="{{$record->levelid}}"><i class="fa fa-file-pdf"></i> Export to PDF</button> --}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @if(count($record->subjects) == 0)
                        <div class="alert alert-warning" role="alert">
                            No subjects shown!
                        </div>
                    @else
                    <table class="table table-bordered" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Description</th>
                                <th>Grade</th>
                                <th>Units</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        @foreach($record->subjects as $eachsubject)
                            <tr>
                                <td>{{$eachsubject->subjectcode}}</td>
                                <td>{{$eachsubject->subjectname}}</td>
                                <td class="text-center">{{$eachsubject->eqgrade}}</td>
                                <td class="text-center">{{$eachsubject->units}}</td>
                                <td style="text-align: center; vertical-align: top;">
                                    {{$eachsubject->remarks}}
                                    {{-- @if($eachsubject->eqgrade != null) {{$eachsubject->eqgrade >= 5.0 ? 'FAILED' : 'PASSED'}} @else @endif --}}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    @endif
                </div>
            </div>
            <hr/>
            @endif
        @endforeach
        <div class="row mb-2 pr-2">
            <div class="col-md-7">&nbsp;</div>
            <div class="col-md-5">
                <label>Registrar (Consultant)</label>
                <input type="text" class="form-control form-control-sm registrar-name"/>
            </div>
        </div>
    </div>
</div>
@endif