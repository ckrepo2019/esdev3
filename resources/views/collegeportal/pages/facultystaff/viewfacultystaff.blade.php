
@extends('collegeportal.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')

      @foreach ($inputArray as $item)

            @php
                  $inputs = $item[0];
                  $modalInfo = $item[1];
            @endphp
            
            @include('collegeportal.pages.forms.generalform')  

      @endforeach
      <section class="content-header">
            <div class="container-fluid">
                  <div class="row">
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">
                              <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                    <li class="breadcrumb-item active"><a href="/facultystaff/college">FACULTY</a></li>
                              </ol>
                        </div>
                  </div>
            </div>
      </section>
 
      <section class="content pt-0">
            <div class="row">
                  <div class="col-md-9">
                        <div class="card">
                              <div class="card-title bg-primary card-header">
                                    Teacher Subject Specialization
                                    <button class="btn btn-light btn-sm float-right" data-toggle="modal"  data-target="#subjectModal" title="Contacts" data-widget="chat-pane-toggle"><b>ADD SUBJECTS</b></button>
                              </h4>
                              </div>
                              <div class="card-body">
                                    <table class="table">
                                          @foreach ($TSP as $item)
                                                <tr>
                                                      <td>{{$item->subjDesc}}</td>
                                                      <td><a href="/admin/college/remove/teachersubject/{{Str::slug($item->subjDesc)}}"><i class="far fa-trash-alt text-danger"></i></a></td>
                                                      
                                                </tr>
                                          @endforeach
                                    </table>
                              </div>
                        </div>
                        {{-- <div class="card">
                            
                        </div> --}}
                  </div>
                  <div class="col-md-3">
                        <div class="card">
                              <div class="card-header card-title bg-primary">
                                    ABOUT FACULTY
                              </div>
                              <div class="card-body">
                                    <label><i class="fa fa-door-open mr-2"></i>NAME</label>
                                    <p class="text-success pl-2">{{$teacherInfo->firstname}}, {{$teacherInfo->lastname}}</p>
                                    <hr>
                                    <label><i class="fa fa-door-open mr-2"></i>COURSE</label>
                                    <p class="text-success pl-2">{{$teacherInfo->courseabrv}}</p>

                                    <hr>
                                    <label><i class="fa fa-door-open mr-2"></i>CHAIRPERSON</label>

                                    @if($teacherInfo->id == $teacherInfo->courseChairPerson)
                                          <a class="btn btn-danger btn-sm btn-block" href="/admin/college/remove/chairperson"><b>REMOVE AS {{$teacherInfo->courseabrv}} CHAIRPERSON</b></a>
                                    @elseif($teacherInfo->courseChairPerson != null && $teacherInfo->id != $teacherInfo->courseChairPerson)

                                          <p class="text-success pl-2">{{$teacherInfo->chairpersonlastname}}, {{$teacherInfo->chairpersonfirstname}}</p>

                                    @else
                                          <a class="btn btn-primary btn-sm btn-block" href="/admin/college/assign/chairperson"><b>ASSIGN AS {{$teacherInfo->courseabrv}} CHAIRPERSON</b></a>
                                    @endif

                                    <hr>
                                    <label><i class="fa fa-door-open"></i>DEAN</label>
                                    @if($teacherInfo->id == $teacherInfo->collegeDean)

                                           <a class="btn btn-danger btn-sm btn-block" href="/admin/college/remove/dean"><b>REMOVE AS {{$teacherInfo->collegeabrv}} DEAN</b></a>
                                    
                                           @elseif($teacherInfo->collegeDean != null && $teacherInfo->id != $teacherInfo->collegeDean)
                                         
                                          <p class="text-success pl-2">{{$teacherInfo->deanlastname}}, {{$teacherInfo->deanfirstname}}</p>

                                    @else
                                          <a class="btn btn-success btn-sm btn-block" href="/admin/college/assign/dean"><b>ASSIGN AS {{$teacherInfo->collegeabrv}} DEAN</b></a>
                                          
                                    @endif
                              </div>
                        </div>
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
            })
      </script>
     
@endsection

