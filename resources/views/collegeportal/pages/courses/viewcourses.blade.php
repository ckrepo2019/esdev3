
@extends('collegeportal.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')

      @include('collegeportal.pages.forms.generalform')

      <div class="card">
            <div class="card-header">
                  <button class="btn btn-sm btn-primary" data-toggle="modal"  data-target="#{{$modalInfo->modalName}}" title="Contacts" data-widget="chat-pane-toggle"><b>CREATE COURSE</b></button>
            </div>
            <div class="card-body">
                  <table class="table table-striped">
                        <thead>
                              <tr>
                                    <th>COURSE DESCRIPTION </th>
                                    <th>COLLEGE</th>
                              </tr>
                        </thead>
                        <tbody>
                              @foreach($courses as $course)
                                    <tr>
                                          <td><a href="/courses/show/{{Str::slug($course->courseDesc, '-')}}">{{$course->courseDesc}}</a></td>
                                          <td>{{$course->collegeDesc}}</td>
                                    </tr>
                              @endforeach
                        </tbody>
                  </table>
            </div>
      </div>
      
@endsection

@section('footerscript')
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script>
            $(document).ready(function(){
                  $(function () {
                        $('.select2').select2({
                              theme: 'bootstrap4'
                        })
                  })
            })
      </script>
@endsection

