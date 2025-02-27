@if(count($headers) == 0)
    <div class="row">
        <div class="col-md-12 text-center">
            <h3>No setup shown</h3>
        </div>
    </div>
@else
    @foreach($headers as $header)
        <div class="row mb-2 headerrow" id="header{{$header->id}}">
            <div class="col-md-9">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                          <strong>Header</strong>
                        </span>
                    </div>
                    <input type="text" class="form-control form-control-sm" disabled="disabled" value="{{$header->headername}}"/>
                </div>
            </div>
            <div class="col-md-3 text-right">
                <button type="button" class="btn btn-default btn-addsubheader btn-sm" data-id="{{$header->id}}">
                    <small>
                        <i class="fa fa-plus"></i> Subheader
                    </small>
                </button>
                <button type="button" class="btn btn-danger btn-deleteheader btn-sm" data-id="{{$header->id}}">
                    <small>
                        <i class="fa fa-trash"></i>
                    </small>
                </button>
            </div>
        </div>
        <div id="container-subheader{{$header->id}}">
        
        </div>
        @if(count($header->subheaders)>0)
            @foreach($header->subheaders as $subheader)
                <div class="row mb-2 subheaderrow" id="subheader{{$subheader->id}}">
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-8">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                  <strong>Sub header</strong>
                                </span>
                            </div>
                            <input type="text" class="form-control form-control-sm" disabled="disabled" value="{{$subheader->subname}}"/>
                        </div>
                    </div>
                    <div class="col-md-3 text-right">
                        <button type="button" class="btn btn-default btn-adddetail btn-sm" data-id="{{$subheader->id}}">
                            <small>
                                <i class="fa fa-plus"></i> Detail
                            </small>
                        </button>
                        <button type="button" class="btn btn-danger btn-deletesubheader btn-sm" data-id="{{$subheader->id}}">
                            <small>
                                <i class="fa fa-trash"></i>
                            </small>
                        </button>
                    </div>
                </div>
                <div id="container-detail{{$subheader->id}}">
                
                </div>
                @if(count($subheader->details)>0)
                    @foreach($subheader->details as $detail)
                        <div class="row detailrow" id="detail{{$detail->id}}">
                            <div class="col-md-2">&nbsp;</div>
                            <div class="col-md-7">
                                <div class="input-group input-group-sm mb-2">
                                    <input type="text" class="form-control form-control-sm input-description" value="{{$detail->description}}">
                                    <div class="input-group-append">
                                      <span class="input-group-text">Map</span>
                                    </div>
                                    <div class="input-group-append">
                                        <select class="form-control form-control-sm select-maps">
                                            @if(count($maps)>0)
                                                @foreach($maps as $map)
                                                    <option value="{{$map->id}}" @if($map->id == $detail->mapid) selected @endif>{{$map->mapname}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        {{-- <input type="text" class="form-control form-control-sm" value="{{$detail->mapname}}"> --}}
                                    </div>
                                  </div>
                                {{-- <input type="text" class="form-control form-control-sm" disabled="disabled" value="{{$detail->description}}"/> --}}
                            </div>
                            <div class="col-md-3 text-right">
                                <button type="button" class="btn btn-warning btn-editdetail btn-sm" data-id="{{$detail->id}}">
                                    <small>
                                        <i class="fa fa-edit"></i> Detail
                                    </small>
                                </button>
                                <button type="button" class="btn btn-danger btn-deletedetail btn-sm" data-id="{{$detail->id}}">
                                    <small>
                                        <i class="fa fa-trash"></i>
                                    </small>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach
        @endif
    @endforeach
@endif