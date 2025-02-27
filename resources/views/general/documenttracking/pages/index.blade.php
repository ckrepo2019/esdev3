<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar-daygrid/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar-timegrid/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar-bootstrap/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar-interaction/main.min.css') }}">


{{-- @php
    $check_refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->select('refid', 'resourcepath')
        ->first();

    if (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (Session::get('currentPortal') == 8) {
        $extend = 'admission.layouts.app2';
    } elseif (Session::get('currentPortal') == 1) {
        $extend = 'teacher.layouts.app';
    } elseif (Session::get('currentPortal') == 2) {
        $extend = 'principalsportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 4) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 15) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 18) {
        $extend = 'ctportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 10) {
        $extend = 'hr.layouts.app';
    } elseif (Session::get('currentPortal') == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } elseif (auth()->user()->type == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } else {
        if (isset($check_refid->refid)) {
            if ($check_refid->resourcepath == null) {
                $extend = 'general.defaultportal.layouts.app';
            } elseif ($check_refid->refid == 27) {
                $extend = 'academiccoor.layouts.app2';
            } elseif ($check_refid->refid == 22) {
                $extend = 'principalcoor.layouts.app2';
            } elseif ($check_refid->refid == 29) {
                $extend = 'idmanagement.layouts.app2';
            } elseif ($check_refid->refid == 23) {
                $extend = 'clinic.index';
            } elseif ($check_refid->refid == 24) {
                $extend = 'clinic_nurse.index';
            } elseif ($check_refid->refid == 25) {
                $extend = 'clinic_doctor.index';
            } else {
                $extend = 'general.defaultportal.layouts.app';
            }
        } else {
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp --}}

{{-- @extends($extend) --}}

@extends('general.documenttracking.layouts.app')

<style>
    .maindiv {
        background-image: url('https://ds6br8f5qp1u2.cloudfront.net/blog/wp-content/uploads/2015/12/funny-cat-year2015-web-dev.gif') !important;
        background-size: cover;
        /* Optional: Adjusts the size of the background image to cover the entire body */
        background-position: center;
        /* Optional: Centers the background image horizontally and vertically */
        height: 100%;
    }
</style>
@section('content')
    <div class="row justify-content-center maindiv">
        <div class="col-12">
        </div>
    </div>




    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar/main.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-daygrid/main.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-timegrid/main.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-interaction/main.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-bootstrap/main.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
