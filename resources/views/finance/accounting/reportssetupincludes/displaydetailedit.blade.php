
                        <div class="input-group-prepend">
                            <span class="input-group-text form-control form-control-sm">Detail</span>
                        </div>
                        <input type="text" value="{{$detailinfo->description}}" class="form-control form-control-sm" detailid="{{$detailinfo->id}}" >
                        <div class="input-group-append">
                            <span class="input-group-text form-control form-control-sm">Map</span>
                        </div>
                        <div class="input-group-append">
                            
                            <select class="form-control form-control-sm" >
                                    @foreach ($maps as $map)
                                        <option value="{{$map->id}}" {{$map->id == $detailinfo->mapid ? 'selected' : ''}}>{{$map->mapname}}</option>
                                    @endforeach
                            </select> 
                        </div>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-sm btn-default m-0 updatedetail"><i class="fa fa-upload text-success"></i></button>
                            {{-- <button type="button" class="btn btn-sm btn-default m-0 deletedetail"><i class="fa fa-times text-danger"></i></button> --}}
                        </div>