<div class="row">
    <div class="col-12">
        <table class="table table-bordered table-hover" style="font-size: 12px;">
            <thead class="text-center">
                @if(collect($remedials)->contains('type','2'))
                    @foreach($remedials as $remedial)
                        @if($remedial->type == 2)
                            <tr>
                                <th style="width:30%;">Remedial Classes</th>
                                <th>Conducted from</th>
                                <th style="width:15%;"><input type="date" class="form-control" id="remedial-datefrom" value="{{$remedial->datefrom}}"/></th>
                                <th>to</th>
                                <th style="width:15%;"><input type="date" class="form-control" id="remedial-dateto" value="{{$remedial->dateto}}"/></th>
                                <td style="width:20%;"><button type="button" class="btn btn-warning btn-sm btn-block" id="btn-edit-remedialheader"><i class="fa fa-edit"></i> Update</button></td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <th style="width:30%;">Remedial Classes</th>
                        <th>Conducted from</th>
                        <th style="width:15%;"><input type="date" class="form-control" id="remedial-datefrom"/></th>
                        <th>to</th>
                        <th style="width:15%;"><input type="date" class="form-control" id="remedial-dateto"/></th>
                        <td style="width:20%;"><button type="button" class="btn btn-warning btn-sm btn-block" id="btn-edit-remedialheader"><i class="fa fa-edit"></i> Update</button></td>
                    </tr>
                @endif
                <tr>
                    <th>Learning Areas</th>
                    <th>Final Rating</th>
                    <th>Remedial Class Mark</th>
                    <th>Recomputed Final Grade</th>
                    <th>Remarks</th>
                    <td></td>
                </tr>
            </thead>
            <tbody id="remedial-tbody">
                @if(count($remedials)> 0)
                    @foreach($remedials as $remedial)
                        @if($remedial->type == 1)
                            <tr>
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