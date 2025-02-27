<ul class="nav-main">
    <div class="content content-full text-center">
        <div class="my-3">
            <img class="img-avatar img-avatar-thumb" src="{{ $picurl }}" alt="profile"
                style="object-fit: cover; height:100px; width: 100px;">

        </div>
        <h1 class="h5 text-white mb-0">{{ strtoupper(auth()->user()->name) }}</h1>
        <span class="text-white-75">{{ DB::table('usertype')->where('id', auth()->user()->type)->value('utype') }}</span>
    </div>

    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('/home') ? ' active' : '' }} || {{ request()->is('/') ? ' active' : '' }} || {{ request()->is('home') ? ' active' : '' }}"
            href="/home">
            <i class="nav-main-link-icon si si-home"></i>
            <span class="nav-main-link-name">Home</span>
        </a>
    </li>
    {{-- <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('/library/view/profile') ? ' active' : '' }}"
            href="/library/view/profile">
            <i class="nav-main-link-icon far fa-user"></i>
            <span class="nav-main-link-name">Profile</span>
        </a>
    </li> --}}
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/view/profile') ? ' active' : '' }}"
            href="/library/view/profile">
            <i class="nav-main-link-icon far fa-user"></i>
            <span class="nav-main-link-name">Profile</span>
        </a>
    </li>

    <li class="nav-main-heading">Books</li>
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/admin/masterlist') ? ' active' : '' }}"
            href="/library/admin/masterlist">
            <i class="nav-main-link-icon si si-book-open"></i>
            <span class="nav-main-link-name">Masterlist</span>
        </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/cataloging') ? ' active' : '' }}" href="/library/cataloging">
            <i class="nav-main-link-icon si si-puzzle"></i>
            <span class="nav-main-link-name">Cataloging</span>
        </a>
    </li>

    <li class="nav-main-heading">General Setup</li>
    @php
        $check_refid = DB::table('usertype')
            ->where('id', Session::get('currentPortal'))
            ->select('refid', 'resourcepath')
            ->first();
    @endphp
    @if ($check_refid && $check_refid->refid == 34)
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('library/admin/setup/libraries') ? ' active' : '' }}"
                href="/library/admin/setup/libraries?action=lib">
                <i class="nav-main-link-icon si si-wrench"></i>
                <span class="nav-main-link-name">Library Setup</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('library/admin/setup/category') ? ' active' : '' }}"
                href="/library/admin/setup/category?action=cat">
                <i class="nav-main-link-icon si si-settings"></i>
                <span class="nav-main-link-name">Category Setup</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('library/admin/setup/genre') ? ' active' : '' }}"
                href="/library/admin/setup/genre?action=genre">
                <i class="nav-main-link-icon si si-settings"></i>
                <span class="nav-main-link-name">Genre Setup</span>
            </a>
        </li>
    @endif
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/admin/setup/borrower') ? ' active' : '' }}"
            href="/library/admin/setup/borrower?action=borrower">
            <i class="nav-main-link-icon si si-wrench"></i>
            <span class="nav-main-link-name">Borrower Setup</span>
        </a>
    </li>

    <li class="nav-main-heading">Circulations</li>
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/admin/circulation/issued') ? ' active' : '' }}"
            href="/library/admin/circulation/issued?action=1">
            <i class="nav-main-link-icon fa fa-book-reader"></i>
            <span class="nav-main-link-name">Issued Book(s)</span>
        </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/admin/circulation/borrowed') ? ' active' : '' }}"
            href="/library/admin/circulation/borrowed?action=2">
            <i class="nav-main-link-icon fa fa-book-reader"></i>
            <span class="nav-main-link-name">Borrowed Book(s)</span>
        </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/admin/circulation/returned') ? ' active' : '' }}"
            href="/library/admin/circulation/returned?action=3">
            <i class="nav-main-link-icon far fa-calendar-check"></i>
            <span class="nav-main-link-name">Returned Books(s)</span>
        </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/admin/circulation/lost') ? ' active' : '' }}"
            href="/library/admin/circulation/lost?action=4">
            <i class="nav-main-link-icon fa fa-book-dead"></i>
            <span class="nav-main-link-name">Lost Books(s)</span>
        </a>
    </li>

    <li class="nav-main-heading">Procurements</li>
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/admin/procurements/store') ? ' active' : '' }}"
            href="/library/admin/procurements/store?action=store">
            <i class="nav-main-link-icon fa fa-store-alt"></i>
            <span class="nav-main-link-name">Supplier</span>
        </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/admin/procurements/purchase') ? ' active' : '' }}"
            href="/library/admin/procurements/purchase?action=purchase">
            <i class="nav-main-link-icon fab fa-shopify"></i>
            <span class="nav-main-link-name">Purchases</span>
        </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/admin/procurements/donation') ? ' active' : '' }}"
            href="/library/admin/procurements/donation?action=donation">
            <i class="nav-main-link-icon fa fa-gift"></i>
            <span class="nav-main-link-name">Donation</span>
        </a>
    </li>

    <li class="nav-main-heading">Reports</li>
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/admin/report/circulation') ? ' active' : '' }}"
            href="/library/admin/report/circulation?action=circulation">
            <i class="nav-main-link-icon si si-bar-chart"></i>
            <span class="nav-main-link-name">Circulation Report</span>
        </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/admin/report/borrower') ? ' active' : '' }}"
            href="/library/admin/report/borrower?action=borrower">
            <i class="nav-main-link-icon si si-bar-chart"></i>
            <span class="nav-main-link-name">Borrower Report</span>
        </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/admin/report/hardreference') ? ' active' : '' }}"
            href="/library/admin/report/hardreference?action=hardref">
            <i class="nav-main-link-icon si si-bar-chart"></i>
            <span class="nav-main-link-name">Hard Reference Report</span>
        </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/admin/report/e-reference') ? ' active' : '' }}"
            href="/library/admin/report/e-reference?action=eref">
            <i class="nav-main-link-icon si si-bar-chart"></i>
            <span class="nav-main-link-name">E-Reference Report</span>
        </a>
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/admin/report/overdue') ? ' active' : '' }}"
            href="/library/admin/report/overdue?action=overdue">
            <i class="nav-main-link-icon si si-bar-chart"></i>
            <span class="nav-main-link-name">Overdues Report</span>
        </a>
    </li>
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('library/admin/report/miscellaneous') ? ' active' : '' }}"
            href="/library/admin/report/miscellaneous?action=miscellaneous">
            <i class="nav-main-link-icon si si-bar-chart"></i>
            <span class="nav-main-link-name">Miscellaneous Report</span>
        </a>
    </li>

</ul>
