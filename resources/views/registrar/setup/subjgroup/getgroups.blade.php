
        @if(count($subjgroups)>0)
            @foreach($subjgroups as $subjgroup)
                <div class="info-box">
                <div class="info-box-content p-1">
                    <div class="row">
                        <div class="col-md-2">
                            <label>Num. Order</label>
                            <input type="text" class="form-control form-control-sm input-num" name="input-num" value="{{$subjgroup->sortnum}}" placeholder="I / II / III / IV / V" readonly/>
                        </div>
                        <div class="col-md-5">
                            <label>Subject Group</label>
                            <input type="text" class="form-control form-control-sm input-group" name="input-group" value="{{$subjgroup->description}}" readonly/>
                        </div>
                        <div class="col-md-3">
                            <label>Units Required</label>
                            <input type="text" class="form-control form-control-sm input-units" name="input-units" value="{{$subjgroup->unitsreq}}" readonly/>
                        </div>
                        <div class="col-md-2">
                            <label>Actions</label><br/>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-deletegroup" data-id="{{$subjgroup->id}}"><i class="fa fa-trash-alt"></i></button>
                            {{-- <button type="button" class="btn btn-sm btn-outline-success btn-updategroup" data-id="{{$subjgroup->id}}"><i class="fa fa-share"></i></button> --}}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
       @endif