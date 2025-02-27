
@extends('collegeportal.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')

      {{-- @include('collegeportal.pages.forms.generalform') --}}

      <div class="row">
            <div class="col-md-9">
                  <div class="card">
                        <div class="card-header card-title bg-primary">
                              SUBJECTS
                        </div>
                        <div class="card-body">
                              
                        </div>
                  </div>
            </div>
            <div class="col-md-3">
                  <div class="card">
                        <div class="card-header card-title bg-primary">
                              About   
                        </div>
                        <div class="card-body">
                              <label><i class="fa fa-door-open mr-2"></i>DESCRIPTION</label>
                              <p class="text-success">{{$courseInfo->courseDesc}}</p>
                        </div>
                       
                  </div>
            </div>  
      </div>
@endsection

@section('footerscript')
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script>
            $(document).ready(function(){
                  $(function () {
                        $('#college').select2({
                              theme: 'bootstrap4'
                        })
                  })
            })
      </script>
@endsection

