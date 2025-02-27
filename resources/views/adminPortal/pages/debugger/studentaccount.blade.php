
@extends('adminPortal.layouts.app2')

@section('pagespecificscripts')

<link rel="stylesheet" href="{{asset('css/pagination.css')}}">
    
@endsection

@section('modalSection')
  
@endsection

@section('content')

<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-8">
            <div class="card adminstudents">
                <div class="card-header">
                    <h3 class="card-title">Student with conflict accounts</h3>
                </div>
                <div class="card-body table-responsive p-0 " style="height: 600px;">
                <table class="table table-head-fixed">
                    <thead>
                        <th>ID</th>
                        <th>Name</th>
                        <th>NSA</th>
                        <th>DSA</th>
                        <th>NPA</th>
                        <th>DPA</th>
                    </thead>
                    <tbody>
                        @foreach ($studentsWithConflict as $item)
                            <tr>
                                <td>{{$item->studentid}}</td>
                                <td>{{$item->studentname}}</td>
                                @if($item->NSA)
                                    <td><i class="fa fa-check text-success"></i></td>
                                @else
                                    <td><i class="fa fa-times text-danger"></i></td>
                                @endif
                                @if($item->DSA)
                                    <td><i class="fa fa-check text-success"></i></td>
                                @else
                                    <td><i class="fa fa-times text-danger"></i></td>
                                @endif
                                @if($item->NPA)
                                   <td><i class="fa fa-check text-success"></i></td>
                                @else
                                    <td><i class="fa fa-times text-danger"></i></td>
                                @endif
                                @if($item->DPA)
                                    <td><i class="fa fa-check text-success"></i></td>
                                @else
                                    <td><i class="fa fa-times text-danger"></i></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-primary card-outline  adminstudents">
                <div class="card-body box-profile">
                  <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                      <b>With Conflict</b> <a class="float-right">{{count($studentsWithConflict)}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Student Count </b> <a class="float-right">{{$numberofStudents}}</a>
                    </li>
                  </ul>
                  <a href="/fixAccountConflict" class="btn btn-primary btn-block"><b>Fix Conflict Accounts</b></a>
                </div>
              </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('footerjavascript')

    
@endsection

