@extends('teacher.layouts.app')

@section('content')
<style>
    td  { text-transform: uppercase; }
</style>
    <div>
        <nav class="" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">School Form 9</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="main-card mb-3 card ">
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Guardian</th>
                                <th>Contact Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                            <tr>
                                <td><a href="{{ url('form_138/preview/'.$student[0]->id) }}" class="btn btn-light btn-block">{{$student[0]->lastname}}, {{$student[0]->firstname}}</a></td>
                                <td>{{$student[0]->contactno}}</td>
                                <td>{{$student[0]->guardianname}}</td>
                                <td>{{$student[0]->gcontactno}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
@endsection