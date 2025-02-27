<div class="row">
    <div class="col-12">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 20%;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th>
                    <th rowspan="2" style="width: 30%;">Subjects</th>
                    <th colspan="2" style="width: 15">Quarter</th>
                    <th rowspan="2" style="width: 10%;">SEM FINAL GRADE	</th>
                    <th rowspan="2" style="width: 10%;">ACTION TAKEN
                    </th>
                    <th rowspan="2" style="width: 15%;"></th>
                </tr>
            </thead>
            <tbody id="grades-tbody">
                @if(count($grades)> 0)
                    @foreach($grades as $grade)
                        @if(strtolower($grade->subjdesc) != 'general average')
                            <tr>
                                <td><input type="text" id="subjectcode{{$grade->id}}" value="{{$grade->subjcode}}" class="form-control" @if($grade->fromsystem == 1) readonly @endif/></td>
                                <td><input type="text" id="subject{{$grade->id}}" value="{{$grade->subjdesc}}" class="form-control" @if($grade->fromsystem == 1) readonly @endif/></td>
                                <td><input type="number" id="q1{{$grade->id}}" value="{{$grade->q1}}" class="form-control" @if($grade->editablegrades == 0) readonly @endif/></td>
                                <td><input type="number" id="q2{{$grade->id}}" value="{{$grade->q2}}" class="form-control" @if($grade->editablegrades == 0) readonly @endif/></td>
                                <td><input type="number" id="finalrating{{$grade->id}}" value="{{$grade->finalrating}}" class="form-control" @if($grade->editablegrades == 0) readonly @endif/></td>
                                <td><input type="text" id="remarks{{$grade->id}}" value="{{$grade->remarks}}" class="form-control"/></td>
                                <td><button type="button" class="btn btn-sm btn-warning p-1 btn-edit-editsubject" data-id="{{$grade->id}}"><i class="fa fa-edit"></i> Update</button> <button type="button" class="btn btn-sm btn-default p-1 btn-edit-deletesubject" data-id="{{$grade->id}}"><i class="fa fa-trash text-danger"></i>&nbsp;</button></td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <br/>
    <div class="col-12">
        <table class="table table-bordered table-hover">
            <tbody>
                <tr>
                    <td colspan="6">
                        &nbsp;
                    </td>
                    <td style="width: 15%;" class="text-right">
                        <button type="button" class="btn btn-primary btn-block" id="btn-edit-addrow">Add row</button>
                    </td>
                </tr>
                @if(count($grades)> 0)
                    @foreach($grades as $grade)
                        @if(strtolower($grade->subjdesc) == 'general average')
                            <tr>
                                <td style="width: 20%;"><input type="text" id="subjectcode{{$grade->id}}" value="" class="form-control" readonly/></td>
                                <td style="width: 30%;"><input type="text" id="subject{{$grade->id}}" value="{{$grade->subjdesc}}" class="form-control" readonly/></td>
                                <td><input type="number" id="q1{{$grade->id}}" value="{{$grade->q1}}" class="form-control" readonly/></td>
                                <td><input type="number" id="q2{{$grade->id}}" value="{{$grade->q2}}" class="form-control" readonly/></td>
                                <td style="width: 10%;"><input type="number" id="finalrating{{$grade->id}}" value="{{$grade->finalrating}}" class="form-control" /></td>
                                <td style="width: 10%;"><input type="text" id="remarks{{$grade->id}}" value="{{$grade->remarks}}" class="form-control" /></td>
                                <td style="width: 15%;"><button type="button" class="btn btn-sm btn-warning p-1 btn-edit-editsubject" data-id="{{$grade->id}}"><i class="fa fa-edit"></i> Update</button></td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>