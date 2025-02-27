@extends('superadmin.layouts.app2')

@section('content')

<section class="content-header">
</section>
<section class="content">
    <div class="error-page">
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! No active school year.</h3>
            <p>
            <a href="/setup/schoolyear">Click here to manage school year.</a>
            </p>
        </div>
    </div>
</section>
@endsection
