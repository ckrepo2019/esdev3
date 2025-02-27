@extends('teacher.layouts.app')
@section('pagespecificscripts')

    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Summary</h1>
                <h6>Loads</h6>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item"><a href="/summary">Summaries</a></li>
                    <li class="breadcrumb-item active">Loads</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-filter"></i> Filter Loads
            </div>
            <div class="card-body">
                <label>Day</label>
                <form name="changeday" action="/summaryofloads/changeday" method="get">
                    <select class="form-control form-control-sm" name="selecteddayoftheweek">
                        <option value="all" {{"all" == $selection ? 'selected':''}}>All</option>
                        @foreach($days as $day)
                            <option value="{{$day->id}}" {{$day->id == $selection ? 'selected':''}}>{{$day->description}}</option>
                        @endforeach
                    </select>
                </form>
                <br/>
                <form name="printloads" action="/summaryofloads/print" method="get" target="_blank">
                    <input name="selection" value="{{$selection}}" type="hidden"/>
                    <button type="submit" class="btn btn-primary btn-sm btn-block">Print</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped" style="font-size: 11px;">
                    <thead>
                        <tr>
                            <th></th> 
                            <th>Grade & Section</th> 
                            <th>Subject</th> 
                            <th>Time</th> 
                            <th>Room</th> 
                        </tr>
                    </thead>
                    <tbody class="studentscontainer text-uppercase">
                        @if(isset($monday))
                            @foreach($monday as $monsched)
                                <tr>
                                    <td>
                                        <span hidden>{{$monsched->day}}</span>{{$monsched->description}}
                                    </td>
                                    <td>{{$monsched->levelname}} -
                                        @if(isset($monsched->sectionname))
                                        {{$monsched->sectionname}}
                                        @endif
                                    </td>
                                    <td>{{$monsched->subjdesc}}</td>
                                    <td>{{$monsched->stime}} - {{$monsched->etime}}</td>
                                    <td>{{$monsched->roomname}}</td>
                                </tr>
                            @endforeach
                        @endif
                        @if(isset($tuesday))
                            @foreach($tuesday as $tuesched)
                                <tr>
                                    <td>
                                        <span hidden>{{$tuesched->day}}</span>{{$tuesched->description}}
                                    </td>
                                    <td>{{$tuesched->levelname}} -
                                        @if(isset($tuesched->sectionname))
                                        {{$tuesched->sectionname}}
                                        @endif
                                    </td>
                                    <td>{{$tuesched->subjdesc}}</td>
                                    <td>{{$tuesched->stime}} - {{$tuesched->etime}}</td>
                                    <td>{{$tuesched->roomname}}</td>
                                </tr>
                            @endforeach
                        @endif
                        @if(isset($wednesday))
                            @foreach($wednesday as $wedsched)
                                <tr>
                                    <td>
                                        <span hidden>{{$wedsched->day}}</span>{{$wedsched->description}}
                                    </td>
                                    <td>{{$wedsched->levelname}} -
                                        @if(isset($wedsched->sectionname))
                                        {{$wedsched->sectionname}}
                                        @endif
                                    </td>
                                    <td>{{$wedsched->subjdesc}}</td>
                                    <td>{{$wedsched->stime}} - {{$wedsched->etime}}</td>
                                    <td>{{$wedsched->roomname}}</td>
                                </tr>
                            @endforeach
                        @endif
                        @if(isset($thursday))
                            @foreach($thursday as $thusched)
                                <tr>
                                    <td>
                                        <span hidden>{{$thusched->day}}</span>{{$thusched->description}}
                                    </td>
                                    <td>{{$thusched->levelname}} -
                                        @if(isset($thusched->sectionname))
                                        {{$thusched->sectionname}}
                                        @endif
                                    </td>
                                    <td>{{$thusched->subjdesc}}</td>
                                    <td>{{$thusched->stime}} - {{$thusched->etime}}</td>
                                    <td>{{$thusched->roomname}}</td>
                                </tr>
                            @endforeach
                        @endif
                        @if(isset($friday))
                            @foreach($friday as $frisched)
                                <tr>
                                    <td>
                                        <span hidden>{{$frisched->day}}</span>{{$frisched->description}}
                                    </td>
                                    <td>{{$frisched->levelname}} -
                                        @if(isset($frisched->sectionname))
                                        {{$frisched->sectionname}}
                                        @endif
                                    </td>
                                    <td>{{$frisched->subjdesc}}</td>
                                    <td>{{$frisched->stime}} - {{$frisched->etime}}</td>
                                    <td>{{$frisched->roomname}}</td>
                                </tr>
                            @endforeach
                        @endif
                        @if(isset($saturday))
                            @foreach($saturday as $satsched)
                                <tr>
                                    <td>
                                        <span hidden>{{$satsched->day}}</span>{{$satsched->description}}
                                    </td>
                                    <td>{{$satsched->levelname}} -
                                        @if(isset($satsched->sectionname))
                                        {{$satsched->sectionname}}
                                        @endif
                                    </td>
                                    <td>{{$satsched->subjdesc}}</td>
                                    <td>{{$satsched->stime}} - {{$satsched->etime}}</td>
                                    <td>{{$satsched->roomname}}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footerscripts')
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script>

    $(function () {
        $("#example1").DataTable();
    });

    $(document).on('change','select[ name="selecteddayoftheweek"]', function(){
        $(this).closest('form').submit();

    })
</script>
@endsection