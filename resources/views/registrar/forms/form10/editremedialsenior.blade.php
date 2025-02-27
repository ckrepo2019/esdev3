
<div class="row mb-2">
    <div class="col-6">
        <label>Conducted from</label>
        @if(count($remedials)> 0)
            @foreach($remedials as $remedial)
                @if($remedial->type == 2)
                <input type="date" class="form-control" id="remedial-datefrom" value="{{$remedial->datefrom}}"/>
                @endif
            @endforeach
        @else
            <input type="date" class="form-control" id="remedial-datefrom"/>
        @endif
    </div>
    <div class="col-6">
        <label>Conducted to</label>
        @if(count($remedials)> 0)
            @foreach($remedials as $remedial)
                @if($remedial->type == 2)
                <input type="date" class="form-control" id="remedial-dateto" value="{{$remedial->dateto}}"/>
                @endif
            @endforeach
        @else
            <input type="date" class="form-control" id="remedial-dateto"/>
        @endif
    </div>
</div>
<div class="row mb-4">
    <div class="col-6">
        <label>School Name</label>
        @if(count($remedials)> 0)
            @foreach($remedials as $remedial)
                @if($remedial->type == 2)
                    <input type="text" class="form-control" id="remedial-schoolname" placeholder="School Name" value="{{$remedial->schoolname}}"/>
                @endif
            @endforeach
        @else
            <input type="text" class="form-control" id="remedial-schoolname" placeholder="School Name"/>
        @endif
    </div>
    <div class="col-6">
        <label>School ID</label>
        @if(count($remedials)> 0)
            @foreach($remedials as $remedial)
                @if($remedial->type == 2)
                    <input type="number" class="form-control" id="remedial-schoolid" placeholder="School ID" value="{{$remedial->schoolid}}"/>
                @endif
            @endforeach
        @else
            <input type="number" class="form-control" id="remedial-schoolid" placeholder="School ID"/>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-12 text-right">
        <button type="button" class="btn btn-primary" id="btn-edit-editremedialheader"><i class="fa fa-share"></i> Update</button>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-12">
        <table class="table table-bordered table-hover" style="font-size: 12px;">
            <thead class="text-center">
                <tr class="text-uppercase">
                    <th style="width: 10%;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th>
                    <th>SUBJECTS</th>
                    <th style="width: 10%;">SEM FINAL GRADE</th>
                    <th>Remedial Class Mark</th>
                    <th style="width: 10%;">Recomputed Final Grade</th>
                    <th>Remarks</th>
                    <td style="width: 15%;"></td>
                </tr>
            </thead>
            <tbody id="remedial-tbody">
                @if(count($remedials)> 0)
                    @foreach($remedials as $remedial)
                        @if($remedial->type == 1)
                            <tr>
                                <td><input type="text" id="subjectcode{{$remedial->id}}" value="{{$remedial->subjectcode}}" class="form-control"/></td>
                                <td><input type="text" id="subject{{$remedial->id}}" value="{{$remedial->subjectname}}" class="form-control"/></td>
                                <td><input type="text" id="finalrating{{$remedial->id}}" value="{{$remedial->finalrating}}" class="form-control"/></td>
                                <td><input type="text" id="remclassmark{{$remedial->id}}" value="{{$remedial->remclassmark}}" class="form-control"/></td>
                                <td><input type="text" id="recomputedfinal{{$remedial->id}}" value="{{$remedial->recomputedfinal}}" class="form-control"/></td>
                                <td><input type="text" id="remarks{{$remedial->id}}" value="{{$remedial->remarks}}" class="form-control"/></td>
                                <td><button type="button" class="btn btn-sm btn-warning p-1 btn-edit-editremedial" data-id="{{$remedial->id}}"><i class="fa fa-edit"></i> Update</button> <button type="button" class="btn btn-sm btn-default p-1 btn-edit-deleteremedial" data-id="{{$remedial->id}}"><i class="fa fa-trash text-danger"></i>&nbsp;</button></td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <br/>
    <div class="col-12">
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="5">
                        &nbsp;
                    </td>
                    <td style="width: 15%;" class="text-right">
                        <button type="button" class="btn btn-primary btn-block" id="btn-edit-addremedial">Add row</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>