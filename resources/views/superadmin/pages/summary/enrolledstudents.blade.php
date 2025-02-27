
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@endsection

@section('modalSection')
  
@endsection

@section('content')
<section class="content-header">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-sm-6">
                  
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Enrolled Student</li>
                  </ol>
                  </div>
            </div>
      </div>
      </section>
      
      <section class="content pt-0">
            <div class="row">
                  <div class="card col-md-12">
                        <div class="card-body">
                              <table class="table">
                                    <thead>
                                          <tr>
                                                <th>School</th>
                                                <th>Total</th>
                                                <th></th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          @foreach ($enrollmentsum as $item)
                                                <tr> 
                                                      <td>{{$item->schoolname}}</td>
                                                      <td>{{$item->enrollmentreport->totalenrolledstudents}}</td>
                                                      <td></td>
                                                </tr>
                                          @endforeach
                                        
                                    </tbody>
                              </table>
                        </div>
                  </div>
            </div>
      </section>
@endsection

@section('footerjavascript')

    
@endsection

