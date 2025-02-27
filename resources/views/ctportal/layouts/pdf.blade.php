<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>College Instructor</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets\css\sideheaderfooter.css')}}">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <style>
    .shadow {
      box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
      border: 0 !important;
    }
  </style>
	@php
		$schoolinfo = DB::table('schoolinfo')->first();
	@endphp
	
	 <style>
     
  </style>

   
    @yield('pagespecificscripts')
  
</head>
<body >
@yield('content')
</body>
</html>
