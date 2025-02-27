<div class="row">
    <div class="col-12">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 30%;">Learning Areas</th>
                    <th colspan="4">Quarterly Rating</th>
                    <th rowspan="2" style="width: 10%;">Final Rating</th>
                    <th rowspan="2" style="width: 15%;">Remarks</th>
                    <th rowspan="2" style="width: 15%;"></th>
                </tr>
            </thead>
            <tbody id="grades-tbody">
                @if(count($grades)> 0)
                    @foreach($grades as $grade)
                        @if(strtolower($grade->subjectname) != 'general average')
                            <tr>
                                <td>
                                    <input type="text" id="subject{{$grade->id}}" value="{{$grade->subjectname}}" class="form-control" @if($grade->fromsystem == 1) readonly @endif/>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input inMAPEH" type="checkbox" id="inMAPEH{{$grade->id}}" value="{{$grade->id}}" @if($grade->inMAPEH == 1) checked @endif>
                                                <label for="inMAPEH{{$grade->id}}" class="custom-control-label"><small>in MAPEH</small></label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input inTLE" type="checkbox" id="inTLE{{$grade->id}}" value="{{$grade->id}}" @if($grade->inTLE == 1) checked @endif>
                                                <label for="inTLE{{$grade->id}}" class="custom-control-label"><small>in TLE</small></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><input type="text" id="q1{{$grade->id}}" value="{{$grade->q1}}" class="form-control" @if($grade->editablegrades == 0) readonly @endif/></td>
                                <td><input type="text" id="q2{{$grade->id}}" value="{{$grade->q2}}" class="form-control" @if($grade->editablegrades == 0) readonly @endif/></td>
                                <td><input type="text" id="q3{{$grade->id}}" value="{{$grade->q3}}" class="form-control" @if($grade->editablegrades == 0) readonly @endif/></td>
                                <td><input type="text" id="q4{{$grade->id}}" value="{{$grade->q4}}" class="form-control" @if($grade->editablegrades == 0) readonly @endif/></td>
                                <td><input type="text" id="finalrating{{$grade->id}}" value="{{$grade->finalrating}}" class="form-control" @if($grade->editablegrades == 0) readonly @endif/></td>
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
                    <td colspan="7">
                        &nbsp;
                    </td>
                    <td style="width: 15%;" class="text-right">
                        <button type="button" class="btn btn-primary btn-block" id="btn-edit-addrow">Add row</button>
                    </td>
                </tr>
                @if(count($grades)> 0)
                    @foreach($grades as $grade)
                        @if(strtolower($grade->subjectname) == 'general average')
                            <tr>
                                <td style="width: 30%;"><input type="text" id="subject{{$grade->id}}" value="{{$grade->subjectname}}" class="form-control" readonly/></td>
                                <td><input type="text" id="q1{{$grade->id}}" value="{{$grade->q1}}" class="form-control" readonly/></td>
                                <td><input type="text" id="q2{{$grade->id}}" value="{{$grade->q2}}" class="form-control" readonly/></td>
                                <td><input type="text" id="q3{{$grade->id}}" value="{{$grade->q3}}" class="form-control" readonly/></td>
                                <td><input type="text" id="q4{{$grade->id}}" value="{{$grade->q4}}" class="form-control" readonly/></td>
                                <td style="width: 10%;"><input type="text" id="finalrating{{$grade->id}}" value="{{$grade->finalrating}}" class="form-control"/></td>
                                <td style="width: 15%;"><input type="text" id="remarks{{$grade->id}}" value="{{$grade->remarks}}" class="form-control" readonly/></td>
                                <td style="width: 15%;"><button type="button" class="btn btn-sm btn-warning p-1 btn-edit-editsubject" data-id="{{$grade->id}}"><i class="fa fa-edit"></i> Update</button></td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>