
@extends('registrar.layouts.app')

@section('content')
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="active breadcrumb-item">School Forms</li>
            {{-- @if($formtype == 'form1')
            <li class="active breadcrumb-item" aria-current="page">School Form 1</li>
            @elseif($formtype == 'form2')
            <li class="active breadcrumb-item" aria-current="page">School Form 2</li>
            @elseif($formtype == 'form5')
            <li class="active breadcrumb-item" aria-current="page">School Form 5</li>
            @elseif($formtype == 'form5a')
            <li class="active breadcrumb-item" aria-current="page">School Form 5A</li>
            @elseif($formtype == 'form5b')
            <li class="active breadcrumb-item" aria-current="page">School Form 5B</li>
            @elseif($formtype == 'form9')
            <li class="active breadcrumb-item" aria-current="page">School Form 9</li>
            @endif --}}
            <li class="active breadcrumb-item">School Form 5a</li>
            <li class="active breadcrumb-item" aria-current="page">{{$levelname}}</li>
        </ol>
    </nav>
    <div class="row">
        @if(count($strands) > 0)
            @foreach($strands as $strand)
            <div class="col-md-4">
                <div class="small-box bg-warning">
                    <div class="inner">

                        <p>{{$strand->trackname}} - {{$strand->strandcode}}</p>
                        <span style="font-size: 15px"><strong>{{$strand->numofstudents}}</strong></span>  Students - @if($strand->sem == 1) 1st @elseif($strand->sem == 2) 2nd @endif Sem
                        <div class="row" style="font-size: 14px;">
                            <div class="col-6">
                                <small>Enrolled: {{$strand->numofenrolledstudents}}</small><br/>
                                <small>Late Enrolled: {{$strand->numoflateenrolledstudents}}</small><br/>
                                <small>Transferred In: {{$strand->numoftransferredinstudents}}</small><br/>
                            </div>
                            <div class="col-6">
                                <small>Transferred Out: {{$strand->numoftransferredoutstudents}}</small><br/>
                                <small>Dropped Out: {{$strand->numofdroppedoutstudents}}</small><br/>
                                <small>Withdrawn: {{$strand->numofwithdrawnstudents}}</small>
                            </div>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <form action="/export/form5a" method="get" class="small-box-footer">
                        <input type="hidden" name="action" value="show"/>
                        <input type="hidden" name="sectionid" value="{{$sectionid}}"/>
                        <input type="hidden" name="syid" value="{{$syid}}"/>
                        <input type="hidden" name="levelid" value="{{$levelid}}"/>
                        <input type="hidden" name="strandid" value="{{$strand->strandid}}"/>
                        <input type="hidden" name="semid" value="{{$strand->sem}}"/>
                        <input type="hidden" name="selectedmonth" value="{{date('m')}}"/>
                        <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                    </form>
                </div>
                
            </div>
            @endforeach
        @endif
    </div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script>
    
        $('[data-id="exportpdf"]').on('click', function(){
            $(this).closest('form').find('input[name="exporttype"]').val('pdf')
            $(this).closest('form').submit();
        })
        $('[data-id="exportexcel"]').on('click', function(){
            $(this).closest('form').find('input[name="exporttype"]').val('excel')
            $(this).closest('form').submit();
        })
</script>
@endsection
