
@extends('deanportal.layouts.app2')

@section('pagespecificscripts')

      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    

@endsection

@section('content')

      @include('collegeportal.pages.forms.generalform')
      <section class="content">
            <div class="row">
                  <div class="col-md-9">
                        <div class="card">
                              <div class="card-header">
                                    Courses
                              </div>
                              <div class="card-body">

                              </div>
                        </div>
                  </div>
                  <div class="col-md-3">
                        <div class="card">
                              <div class="card-header">
                                    About   
                              </div>
                              <div class="card-body">
                                    <label><i class="fa fa-door-open mr-2"></i>DESCRIPTION</label>
                                    <p class="text-success">{{$subjectInfo->subjDesc}}</p>
                                    <hr>
                                    <label><i class="fa fa-door-open mr-2"></i>SUBJECT CODE</label>
                                    <p class="text-success">{{$subjectInfo->subjCode}}</p>
                                    <hr>
                                    <button class="btn btn-sm btn-success btn-block mb-2" data-toggle="modal"  data-target="#subjectModal" data-widget="chat-pane-toggle"><b>UPDATE</b></button>
                                    <a href="/subjects/college/delete/{{Str::slug($subjectInfo->subjDesc)}}" class="btn btn-sm btn-danger btn-block mb-2"><b>DELETE</b></a>
                              </div>
                        </div>
                  </div>  
            </div>
      </section
@endsection

@section('footerjavascript')

      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
 
      <script>

            $(function () {
                  $('.select2').select2()

                  $('.select2').select2({
                        theme: 'bootstrap4'
                  })

                  
            })
            $(document).ready(function(){

                 


                
                

                  @if ($errors->any())
                        $('#'+'{{ $modalInfo->modalName }}').modal('show');
                  @endif

                  var prereq = [];

                  @foreach($prereq as $item)

                        prereq.push('{{Str::slug($item->subjDesc)}}')

                  @endforeach

                  $('select[name="prereq[]"]').val(prereq).trigger('change')

                  console.log(prereq);
                  
            })
      </script>

@endsection

