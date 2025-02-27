<li class="nav-item">
    <a class="{{ Request::url() == url('/idprinting/home') ? 'active' : '' }} nav-link" href="/idprinting/home">
        <i class="nav-icon fas fa-layer-group"></i>
        <p>
            Students ID Printing
        </p>
    </a>
</li>
@php
    $check_refid = DB::table('usertype')
        ->where('id', \Session::get('currentPortal'))
        ->select('refid', 'resourcepath')
        ->first();
@endphp


@if ((isset($check_refid->refid) && $check_refid->refid == 29) || auth()->user()->type === 17)
    <li class="nav-item">
        <a href="/idprinting/view/teachers"
            class="nav-link {{ Request::url() == url('/idprinting/view/teachers') ? 'active' : '' }}">
            <i class="nav-icon fas fa-layer-group"></i>
            <p>
                Staff ID Printing
            </p>
        </a>
    </li>
    <li class="nav-item">
        <a href="/idprinting/view/templates"
            class="nav-link {{ Request::url() == url('/idprinting/view/templates') ? 'active' : '' }}">
            <i class="nav-icon fas fa-id-card"></i>
            <p>
                ID Templates
            </p>
        </a>
    </li>
@endif
