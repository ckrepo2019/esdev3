@php
    $priveledge = DB::table('faspriv')
                    ->join('usertype','faspriv.usertype','=','usertype.id')
                    ->select('faspriv.*','usertype.utype')
                    ->where('userid', auth()->user()->id)
                    ->where('faspriv.deleted','0')
                    ->where('type_active',1)
                    ->where('faspriv.privelege','!=','0')
                    ->get();

    $usertype = DB::table('usertype')->where('deleted',0)->where('id',auth()->user()->type)->first();

@endphp
<li class="nav-header text-warning" {{count($priveledge) > 0 ? '':'hidden'}}>Other Portal</li>
@foreach ($priveledge as $item)
    @if($item->usertype != Session::get('currentPortal'))
        <li class="nav-item">
            <a class="nav-link portal" href="/gotoPortal/{{$item->usertype}}" id="{{$item->usertype}}">
                <i class=" nav-icon fas fa-cloud"></i>
                <p>
                    {{$item->utype}}
                </p>
            </a>
        </li>
    @endif
@endforeach

@if($usertype->id != Session::get('currentPortal'))
    <li class="nav-item">
        <a class="nav-link portal" href="/gotoPortal/{{$usertype->id}}">
            <i class=" nav-icon fas fa-cloud"></i>
            <p>
                {{$usertype->utype}}
            </p>
        </a>
    </li>
@endif