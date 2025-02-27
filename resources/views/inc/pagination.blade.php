<div id="pageholder">
    @if($data[0]->count!=0)
        <ul class="pagination pagination-sm m-0 pt-3">
            <li class="page-item disabled"><a class="page-link prev" href="#" >«</a></li>
            @for ($x = 1; $x<=$data[0]->count;$x++)
                @if($x==1)
                    <li class="page-item"><a class="page-link page-link-active" id="P{{$x}}" href="#">{{$x}}</a></li>
                @else
                    <li class="page-item"><a class="page-link" id="P{{$x}}" href="#">{{$x}}</a></li>
                @endif
            @endfor
            <li class="page-item"><a class="page-link next" href="#">»</a></li>
        </ul>
    @endif
</div>