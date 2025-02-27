<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

<div class="row d-flex align-items-stretch p-4">
    @if($data[0]->count > 0)
        @foreach ($data[0]->data as $block)
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                <div class="card bg-light w-100">
                    <div class="card-body pt-0 mt-2 ">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="lead"><b> {{$block->blockname}}
                                <span class="h6"><br><span></b>
                            </h2>
                        </div>
                        <div class="col-12">
                            @if($block->levelname != null)
                                <p class="text-muted text-sm"><b>{{$block->levelname}}</b></p>
                            @else
                                <p class="text-danger text-sm"><b>Unassigned</b></p>
                            @endif
                        </div>
                        <div class="col-12">
                            <p class="text-muted text-sm mb-0">{{$block->strandname}}</p>
                        </div>
                    </div>
                    </div>
                    <a href="prinicipalblockinfo/{{$block->id}}" class="card-footer bg-info text-center pt-2 pb-2"><span class="text-white">More info </span><i class=" text-white fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endforeach 
    @else
        <p class="w-100 text-center">No Results Found</p>
    @endif
</div>