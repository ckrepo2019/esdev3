
@extends('collegeportal.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')

      {{-- @include('collegeportal.pages.forms.generalform') --}}

      <div class="card">
            <div class="card-header">
                 
            </div>
            <div class="card-body">
                  <table class="table table-striped">
                        <thead>
                              <tr>
                                    <th width="10%">ID</th>
                                    <th with="70%">NAME</th>
                                   
                              </tr>
                        </thead>
                        <tbody>
                              @foreach($students as $student)
                                    <tr>
                                          <td>{{$student->id}}</a></td>
                                          <td><a href="/enrollement/college/show/{{ sprintf("%06d", $student->id)}}/{{Str::slug($student->firstname.' '.$student->lastname,'-')}}">{{$student->lastname}}, {{$student->firstname}}</a></td>
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

