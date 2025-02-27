<div class="row">
    @if(count($files)==0)
        <div class="col-md-12">
            No files shown
        </div>
    @else
        <div class="col-md-12 mb-2">

        </div>
        @foreach($files as $file)
            <div class="col-md-6 mb-2">
                <a href="{{asset($file->filepath)}}" style="cursor: pointer; color: inherit;" download  data-toggle="tooltip" data-placement="bottom" title="{{$file->filename}}">
                    <div class="info-box shadow p-1 m-0" >
                        <span class="info-box-icon"><i class="far fa-file"></i></span>
                        <div class="info-box-content p-0" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <span class="info-box-text">{{$file->filename}}</span>
                            <span class="info-box-number">{{$file->extension}}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    @endif
</div>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>