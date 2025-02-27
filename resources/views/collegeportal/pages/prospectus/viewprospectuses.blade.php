
@extends('collegeportal.layouts.app2')

@section('pagespecificscripts')

@endsection

@section('content')

      <div class="card">
            <div class="card-header card-title bg-primary">
                 COURSES
            </div>
            <div class="card-body">
                  <table class="table table-striped">
                        <thead>
                              <tr>
                                    <th>COURSE DESCRIPTION </th>
                              </tr>
                        </thead>
                        <tbody>
                              @foreach($courses as $course)
                                    <tr>
                                          <td><a href="/prospectus/college/show/{{Str::slug($course->courseDesc, '-')}}">{{$course->courseDesc}}</a></td>
                                    </tr>
                              @endforeach
                        </tbody>
                  </table>
            </div>
      </div>
      
@endsection

@section('footerscript')
   
@endsection

