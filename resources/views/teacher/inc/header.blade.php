  <style>
    .status-dot {
        height: 7px;
        width: 7px;
        background-color: #ffffff;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
        animation: pulse 3s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.5);
        }
        100% {
            transform: scale(1);
        }
    }
  </style>
  <nav class="main-header navbar navbar-expand navbar-dark pace-primary nav-bg">
      <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars text-white" ></i></a>
          </li>
          <span class="ml-2 text-white" style="font-size: 12px">
            <div class="d-flex align-items-center">
                <span class="status-dot mb-0"></span>
                <strong class="text-white">Active</strong>
            </div>
            <b>SY: @php
                $sydesc = DB::table('sy')->where('isactive', 1)->first();
            @endphp {{ $sydesc->sydesc }} |
            @php
                $semester = DB::table('semester')->where('isactive', 1)->first();
            @endphp {{ $semester->semester }}</b>
        </span>
        </ul>
      <ul class="navbar-nav ml-auto">
        @include('components.headerprofile')
      </ul>
    </nav>
