<div class="row mb-2">
    <div class="col-md-2">&nbsp;</div>
    <div class="col-md-7">
        <div class="input-group mb-3">
            <input type="text" class="form-control input-detail">
            <div class="input-group-append">
              <span class="input-group-text">Map</span>
            </div>
            <div class="input-group-append">
                <select class="form-control form-control select-map">
                    @if(count($maps)>0)
                        @foreach($maps as $map)
                            <option value="{{$map->id}}">{{$map->mapname}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
          </div>
    </div>
    <div class="col-md-3">
        <button type="button" class="btn btn-success save-button btn-submitadddetail btn-sm" data-id="{{$subheaderid}}"><i class="fa fa-share"></i></button>
        <button type="button" class="btn btn-danger btn-removeadd btn-sm"><i class="fa fa-times"></i></button>
    </div>
</div>