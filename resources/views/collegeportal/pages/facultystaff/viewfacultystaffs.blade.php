
@extends('collegeportal.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')

      @include('collegeportal.pages.forms.generalform')
<section class="content">
      <div class="card">
            <div class="card-header">
                  <button class="btn btn-sm btn-primary" data-toggle="modal"  data-target="#{{$modalInfo->modalName}}" title="Contacts" data-widget="chat-pane-toggle"><b>CREATE TEACHER ACCOUNT</b></button>
            </div>
            <div class="card-body">
                  
                  <table class="table table-striped">
                        <thead>
                              <tr>
                                    <th>TEACHER NAME</th>
                                    <th>COLLEGE</th>
                              </tr>
                        </thead>
                        <tbody>
                              @foreach($teachers as $teacher)
                                    <tr>
                                          {{-- <td><a href="/facultystaff/college/show/{{Str::slug($teacher->lastname.' cl '.$teacher->firstname, '-')}}">{{$teacher->lastname}}, {{$teacher->firstname}}</a></td> --}}
                                          <td>{{$teacher->lastname}}, {{$teacher->firstname}}</td>
                                          {{-- <td>{{$teacher->courseabrv}}</td> --}}
                                          <td></td>
                                    </tr>
                              @endforeach

                              @if($errors->has('firstname'))
                                    {{ old('firstname') }} 
                              @endif
                        </tbody>
                  </table>
            </div>
      </div>
</section>
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

                  @if ($errors->any())
                        $('#facultystaffModal').modal('show');
                  @endif
            })
      </script>
@endsection

