@extends('principalsportal.layouts.app2')

@section('content')

<section class="content-header">
</section>
<section class="content">
    <div class="error-page">
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i>{{$message}}</h3>
            <p>
                {{$messagenote}}
            </p>
        </div>
    </div>
</section>
@endsection
