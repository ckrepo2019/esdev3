
    <div id="detailscontainer{{$subid}}">
    @if(count($details) == 0)
    @else
        @foreach($details as $detail)
            <div class="row mt-2">
               <div class="col-md-3 text-muted text-center">&nbsp;</div>
                <div class="col-md-7">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text form-control form-control-sm">Detail</span>
                        </div>
                        <input type="text" value="{{$detail->description}}" class="form-control form-control-sm" detailid="{{$detail->id}}" disabled/>
                        <div class="input-group-append">
                            <span class="input-group-text form-control form-control-sm">Map</span>
                        </div>
                        <div class="input-group-append">
                            <input type="text" value="{{$detail->mapname}}" class="form-control form-control-sm" detailid="{{$detail->id}}" disabled/>
                        </div>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-sm btn-default m-0 editdetail"><i class="fa fa-edit text-warning"></i></button>
                            <button type="button" class="btn btn-sm btn-default m-0 deletedetail"><i class="fa fa-times text-danger"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
<div class="row mb-2">
   <div class="col-md-3">&nbsp;</div>
   <div class="col-md-2">
       <button type="button" class="btn btn-default btn-block btn-sm adddetail mt-2" subid="{{$subid}}"><i class="fa fa-plus"></i> Add detail</button>
   </div>
</div>