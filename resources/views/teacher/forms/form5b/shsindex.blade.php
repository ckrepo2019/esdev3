
@extends('teacher.layouts.app')

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
            <li class="active breadcrumb-item">School Form 5b</li>
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
                        <span style="font-size: 15px"><strong>{{$strand->numofstudents}}</strong></span>  Students
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
                    <form action="/forms/form5b" method="get" class="small-box-footer">
                        <input type="hidden" name="action" value="show"/>
                        <input type="hidden" name="sectionid" value="{{$sectionid}}"/>
                        <input type="hidden" name="levelid" value="{{$levelid}}"/>
                        <input type="hidden" name="strandid" value="{{$strand->strandid}}"/>
                        <input type="hidden" name="selectedmonth" value="{{date('m')}}"/>
                        <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                    </form>
                </div>
                
            </div>
            @endforeach
        @endif
    </div>
{{-- <div class="row">
    @if(count($sections) == 0)
    @else
        @foreach($sections as $section)
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <span style="font-size: 15px"><strong>{{$section->numberofstudents}}</strong></span>  Students

                        <p>{{$section->levelname}} - {{$section->sectionname}}</p>
                        <div class="row">
                            <div class="col-6">
                                <small>Enrolled: {{$section->numberofenrolled}}</small><br/>
                                <small>Late Enrolled: {{$section->numberoflateenrolled}}</small><br/>
                                <small>Transferred In: {{$section->numberoftransferredin}}</small><br/>
                            </div>
                            <div class="col-6">
                                <small>Transferred Out: {{$section->numberoftransferredout}}</small><br/>
                                <small>Dropped Out: {{$section->numberofdroppedout}}</small><br/>
                                <small>Withdrawn: {{$section->numberofwithdraw}}</small>
                            </div>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    @if($formtype == 'form1')
                        <form action="/forms/{{$formtype}}" method="get" class="small-box-footer" target="_blank">
                            <input type="hidden" name="action" value="show"/>
                            <input type="hidden" name="exporttype"/>
                            @csrf
                            <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                            <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                            <input type="hidden" name="currentmonth" value="{{\Carbon\Carbon::now()->month}}"/>
                            <button type="button" class=" btn btn-sm btn-block dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">Export As <span class="sr-only">Toggle Dropdown</span>
                                <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                  <a class="dropdown-item" href="#" data-id="exportpdf">PDF</a>
                                  <a class="dropdown-item" href="#" data-id="exportexcel">EXCEL</a>
                                </div>
                            </button>
                        </form>
                    @elseif($formtype == 'form2')
                        @if(strtolower($section->acadprogcode) == 'shs')
                            <form action="/forms/{{$formtype}}shsindex" method="get" class="small-box-footer">
                                <input type="hidden" name="action" value="index"/>
                                <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                                <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                                <input type="hidden" name="selectedmonth" value="{{date('m')}}"/>
                                <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                            </form>
                        @else
                            <form action="/forms/{{$formtype}}" method="get" class="small-box-footer">
                                <input type="hidden" name="action" value="index"/>
                                <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                                <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                                <input type="hidden" name="selectedmonth" value="{{date('m')}}"/>
                                <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                            </form>
                        @endif
                    @elseif($formtype == 'form5')
                        <form action="/forms/{{$formtype}}" method="get" class="small-box-footer" target="_blank">
                            <input type="hidden" name="action" value="export"/>
                            @csrf
                            <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                            <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                            <input type="hidden" name="exporttype"/>
                            <input type="hidden" name="currentmonth" value="{{\Carbon\Carbon::now()->month}}"/>
                            <button type="button" class=" btn btn-sm btn-block dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">Export As <span class="sr-only">Toggle Dropdown</span>
                                <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                  <a class="dropdown-item" href="#" data-id="exportpdf">PDF</a>
                                  <a class="dropdown-item" href="#" data-id="exportexcel">EXCEL</a>
                                </div>
                            </button>
                        </form>
                    @elseif($formtype == 'form5a')
                        <form action="/forms/{{$formtype}}" method="get" class="small-box-footer">
                            <input type="hidden" name="action" value="show"/>
                            @csrf
                            <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                            <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                            <input type="hidden" name="currentmonth" value="{{\Carbon\Carbon::now()->month}}"/>
                            <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                        </form>
                    @elseif($formtype == 'form5b')
                        <form action="/forms/{{$formtype}}" method="get" class="small-box-footer">
                            <input type="hidden" name="action" value="show"/>
                            @csrf
                            <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                            <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                            <input type="hidden" name="currentmonth" value="{{\Carbon\Carbon::now()->month}}"/>
                            <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                        </form>
                    @elseif($formtype == 'form9')
                        <form action="/forms/{{$formtype}}" method="get" class="small-box-footer">
                            <input type="hidden" name="action" value="show"/>
                            @csrf
                            <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                            <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                            <input type="hidden" name="currentmonth" value="{{\Carbon\Carbon::now()->month}}"/>
                            <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                        </form>
                    @elseif($formtype == 'form10')
                        <form action="/forms/{{$formtype}}" method="get" class="small-box-footer">
                            <input type="hidden" name="action" value="show"/>
                            @csrf
                            <input type="hidden" name="sectionid" value="{{$section->sectionid}}"/>
                            <input type="hidden" name="levelid" value="{{$section->levelid}}"/>
                            <button type="submit" class=" btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                        </form>
                    @endif
                </div>
                
            </div>
        @endforeach
    @endif

</div> --}}
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
