@extends('principalsportal.layouts.app')

@section('content')
    <div class="row">
    @foreach ($topGradesbySubject as $item) 
        @if($item->message!="Empty")
            <div class="col-lg-6">
                <div class="main-card mb-3 card">
                    <div class="card-body"><h5 class="card-title"> {{$item->data[0]->subject}}</h5>
                        <table class="mb-0 table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Name</th>
                                    <th>Section</th>
                                    <th>Ave</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->data[1]->rankings as $val)
                                    <tr>
                                        <th>{{$val->ranking}}</th>
                                        <td>{{$val->studname}}</td>
                                        <td>{{$val->section}}</td>
                                        <td>{{$val->grade}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="col-lg-6">
                <div class="main-card mb-3 card" style=" height: 296px;">
                    <div class="card-body"><h5 class="card-title"> {{$item->data->subject}}</h5>
                        <p>Cannot generate ranking for this subject. Please visit your administrator.</p>
                    </div>
                </div>
            </div>
        @endif
        @endforeach
    </div>
@endsection
