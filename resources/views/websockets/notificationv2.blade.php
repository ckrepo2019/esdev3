@php
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
    } elseif (Session::get('currentPortal') == 6) {
        $extend = 'adminPortal.layouts.app2';
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
    } elseif (auth()->user()->type == 7) {
        $extend = 'studentPortal.layouts.app2';
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
            } elseif ($check_refid->refid == 31) {
                $extend = 'guidanceV2.layouts.app2';
            } elseif ($check_refid->refid == 33) {
                $extend = 'inventory.layouts.app2';
            } else {
                $extend = 'general.defaultportal.layouts.app';
            }
        } else {
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp
@extends($extend)

@section('content')

    <style>
        .timeline {
            padding: 0;
            margin: 0;
        }

        .timeline-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-left: 10px;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #d3d3d3;
        }

        .timeline-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
            font-size: 16px;
        }

        .timeline-date {
            font-size: 0.9em;
            color: #999;
            margin-right: 15px;
        }

        .timeline-content {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .timeline-content .heading {
            font-size: 1.1em;
            margin-bottom: 5px;
        }

        .timeline-content .message {
            font-size: 0.95em;
            color: #555;
        }
    </style>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Notifications</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled timeline">
                                @if ($notifications->isEmpty())
                                    <p class="text-center text-muted">No notifications found</p>
                                @else
                                    @foreach ($notifications as $notification)
                                        <li class="timeline-item">
                                            <span class="timeline-icon bg-primary text-white">
                                                <i class="fas fa-bell"></i>
                                            </span>
                                            <span class="timeline-date text-muted">
                                                {{ \Carbon\Carbon::parse($notification->created_at)->format('M j, Y') }}
                                            </span>
                                            <div class="timeline-content">
                                                <p class="heading font-weight-bold">{{ $notification->about }}</p>
                                                <p class="message text-muted">{{ $notification->message }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footerjavascript')
@endsection
