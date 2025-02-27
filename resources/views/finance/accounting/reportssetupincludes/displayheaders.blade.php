@php
    $particular = 1;   
@endphp
@if(count($headers) == 0)
@else
    @foreach($headers as $header)
        <div id="rowparticular{{$particular}}" class="rowparticular mt-2" style="border-bottom: 1px solid #ddd;" class="p-2">
            <div class="row">
                <div class="col-md-12">
                
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Header</span>
                        </div>
                        <input type="text" value="{{$header->classification}}" class="form-control" headerid="{{$header->id}}" style="background-color: #a9c8e4" disabled/>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-sm btn-default m-0 viewheader"><i class="fa fa-eye text-success"></i></button>
                            <button type="button" class="btn btn-sm btn-default m-0 deleteheader"><i class="fa fa-times text-danger"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="subcontainerhead{{$header->id}}">
                
            </div>
        </div>
        @php
            $particular += 1;   
        @endphp
    @endforeach
@endif