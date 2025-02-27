{{-- <div class="card" style="border: none; box-shadow: unset !important;">
    <div class="card-body"> --}}
        {{-- <div class="row">
            <div class="col-md-12">
                <table class="table table-hover">
                    @foreach($employees as $employee)
                        <tr>
                            <td></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div> --}}
        <div class="row mb-2">
            <div class="col-md-12 mb-2 text-right">                
                <button type="button" class="btn btn-sm btn-default" id="btn-exporttopdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
            </div>
            <div class="col-md-12">                
              <input class="form-control" id="input-search" placeholder="Search employee" />
            </div>
        </div>
        <div style="height: 500px; overflow: scroll;" class="row">
            @foreach($employees as $employee)
                <div class="col-md-12 card-each-employee" data-string="{{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}}<">
                    <div class="card collapsed-card" style="border: none; box-shadow: unset !important;">
                    <div class="card-header p-1">
                        <div class="row">
                            <div class="col-md-9">
                                <h5 class="mb-0"><span class="text-bold">{{$employee->lastname}}</span>, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</h5>
                                <small class="mt-0">Date Hired: @if($employee->datehired != null) {{date('M d, Y', strtotime($employee->datehired))}} @endif</small>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control form-control-sm select-change-empstatus" data-empid="{{$employee->id}}">
                                    <option value="0">Unset</option>
                                    @foreach($statustypes as $statustype)
                                        <option value="{{$statustype->id}}" @if($employee->employmentstatus == $statustype->id) selected @endif>{{$statustype->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    {{-- </div>
</div> --}}
<script>
</script>