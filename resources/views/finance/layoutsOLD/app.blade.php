<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="google" content="notranslate">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Essentiel | Finance</title>

    <!-- Scripts -->
    {{-- <script src="{{ asset('plugins/jquery/jquery.min.js') }}" defer></script> --}}


    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <link href="{{asset('dist/css/adminlte.css')}}" rel="stylesheet">
    {{-- <link type="text/css" href="{{asset('css/fontawesome.css')}}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
   <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}"> 
   <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">

   <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
   <link rel="stylesheet" href="{{asset('dist/css/fontfamily.css')}}">
   <link rel="stylesheet" href="{{asset('dist/css/ionicons.min.css')}}">
   <link rel="stylesheet" href="{{asset('dist/css/googleapis-font.css')}}">
   <link rel="stylesheet" href="{{asset('dist/css/select2.min.css')}}">
   <link rel="stylesheet" href="{{asset('dist/css/select2-bootstrap4.min.css')}}">
   <link rel="stylesheet" href="{{asset('dist/css/simplePagination.css')}}">
   @yield('jsUP') 

   {{-- remove default arrow on select --}}
    <style type="text/css"> 
      select {
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: '';
}
    </style>
</head>
<body class="sidebar-mini layout-fixed" style="height: auto;">

    @include('finance.layouts.navbar')
    {{-- @include('finance.layouts.header') --}}
        <div class="content-wrapper" style="min-height: 543px;">
            @yield('content')
        </div>
    @include('finance.layouts.footer')

</body>
</html>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>

<script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>

<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>

<script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<script src="{{asset('dist/js/adminlte.js')}}"></script>
{{-- <script src="{{asset('dist/js/pages/dashboard.js')}}"></script> --}}
<script src="{{asset('dist/js/demo.js')}}"></script>
<script src="{{asset('dist/js/select2.full.min.js')}}"></script>
<script src="{{asset('dist/js/jquery.simplePagination.js')}}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>

@yield('modal')
@yield('js')