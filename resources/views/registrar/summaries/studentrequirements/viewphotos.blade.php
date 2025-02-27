@if(count($requirements) == 0)
@else
    @foreach($requirements as $requirement)
        <div class="card card-primary collapsed-card">
        <div class="card-header">
            <h3 class="card-title" data-card-widget="collapse" style="cursor: pointer;">{{$requirement->description}}</h3>

            {{-- <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
            </button>
            </div> --}}
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @if($requirement->picurl == 'none')
            <h3 class="text-center">No photo attached</h3>
            @else
            <img src="{{asset($requirement->picurl)}}" onerror="this.onerror = null, this.src='{{asset($requirement->picurl)}}'" style="border-radius: unset !important; width:100%;" alt="User Image"/>
            @endif
        </div>
        <!-- /.card-body -->
        </div>
    @endforeach
@endif