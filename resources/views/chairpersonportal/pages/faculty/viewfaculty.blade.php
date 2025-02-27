
@extends('deanportal.layouts.app2')

@section('pagespecificscripts')

@endsection

@section('content')
      <section class="content">
            <div class="card">
                  <div class="card-header">
                        COURSES
                  </div>
                  <div class="card-body p-0">
                        <table class="table table-striped">
                              <thead>
                                    <tr>
                                          <td>DESCRIPTION</>
                                          <td>COURSE</>
                                    </tr>
                              </thead>
                              <tbody>
                                    @foreach ($teachers as $item)
                                          <tr>
                                                <td><a href="#">{{$item->lastname}}, {{$item->firstname}}<a></td>
                                                <td>{{$item->courseabrv}}</td>
                                          </tr>
                                    @endforeach
                              </tbody>
                        </table>
                  </div>
            </div>
      </section>
@endsection

@section('footerscript')

@endsection

