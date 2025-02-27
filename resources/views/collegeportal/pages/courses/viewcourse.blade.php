
@extends('collegeportal.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <style>
           .dropdown-toggle::after {
                  content: none;
            }
      </style>
@endsection

@section('content')

      @foreach($inputArray  as $item)
            @php
                  $inputs = $item[0];
                  $modalInfo = $item[1];
            @endphp
            @include('collegeportal.pages.forms.generalform')
      @endforeach
    


<section class="content">
      <div class="row">
            
            <div class="col-md-9">
                  <div class="card">
                        <div class="card-header card-title bg-primary">
                              PROSPECTUS
                              {{-- <button class="btn btn-sm btn-light mb-2 float-right" data-toggle="modal"  data-target="#prospectusModal" data-widget="chat-pane-toggle"><b>ADD SUBJECT</b></button> --}}
                        </div>
                        <div class="card-body">
                              @foreach (DB::table('college_year')->get() as $year)

                                    @foreach (DB::table('semester')->where('deleted','0')->get() as $semester)

                                          @php
                                                $subjects = collect($prospectus)->where('yearDesc',$year->yearDesc)->where('semester',$semester->semester);
                                          @endphp

                                          @if(count($subjects) > 0 )

                                                <label class="bg-success d-block p-2 mt-2">{{$year->yearDesc}} - {{$semester->semester}}</label>
                                                <table class="table table-striped table-sm">
                                                      <thead class="bg-info">
                                                            <tr>
                                                                  <td width="20%">CODE</td>
                                                                  <td width="65%">DESCRIPTION</td>
                                                                  <td width="10%" class="text-center">UNITS</td>
                                                                  <td width="5%" class="text-center"></td>
                                                            </td>
                                                      </thead>
                                                      <tbody>
                                                            @foreach ($subjects as $subject)
                                                                  <tr>
                                                                        <td  class="align-middle">{{$subject->subjCode}}</td>
                                                                        <td  class="align-middle">{{$subject->subjDesc}}</td>
                                                                        <td class="text-center align-middle">{{$subject->subjectUnit}}</td>
                                                                        <td class="text-center align-middle p-0">
                                                                             

                                                                              {{-- <button type="button" class="btn " data-toggle="modal" data-target="#modal-primary"><i class="far fa-edit text-primary"></i></button> --}}
                                                                        </td>
                                                                  </td>
                                                            @endforeach
                                                            
                                                      </tbody>
                                                      <tfoot class="bg-info">
                                                            <tr>
                                                                  <td></td>
                                                                  <td class="text-right">TOTAL UNIT:</td>
                                                                  <td class="text-center"> {{collect($subjects)->sum('subjectUnit')}}</td>
                                                                  <td class="text-center"> {{collect($subjects)->sum('subjectUnit')}}</td>
                                                                  
                                                            </td>
                                                      </tfoot>
                                                </table>
                                          @endif
                                    @endforeach
                              @endforeach
                        </div>
                  </div>
                  {{-- <div class="card">
                        <div class="card-header card-title bg-success">
                              CLASS SCHEDULE
                              <button class="btn btn-sm btn-light mb-2 float-right" data-toggle="modal"  data-target="#sectionModal" data-widget="chat-pane-toggle"><b>CREATE SECTION</b></button>
                        </div>
                        <div class="card-body">
                              
                              @foreach ($sections as $key=>$section)
                                    <div class="card">
                                          <div class="card-header">
                                                {{$key}}
                                          </div>
                                          <div class="card-body p-0">
                                                <table class="table table-sm">
                                                      <tr>
                                                            <td width="10%" class="pl-1 pr-1">Code</td>
                                                            <td width="35%">Description</td>
                                                            <td width="10%">Units</td>
                                                            <td width="10%">Day</td>
                                                            <td width="10%">Time</td>
                                                            <td width="10%">Room</td>
                                                            <td width="10%">Teacher</td>
                                                            <td width="5%" class="p-0"></td>
                                                      </tr>
                                                      @foreach ($section as $item)
                                                            <tr>
                                                                  <td class="align-middle pl-1 pr-1">{{$item->subjCode}}</td>
                                                                  <td class="align-middle">{{$item->subjDesc}}</td>
                                                                  <td class="align-middle">{{$item->subjectUnit}}</td>
                                                                  <td class="align-middle"></td>
                                                                  <td class="align-middle"></td>
                                                                  <td class="align-middle"></td>
                                                                  <td class="align-middle"></td>
                                                                  <td class="p-0 text-center align-middle">
                                                                    
                                                                        <button type="button" class="btn " data-toggle="modal" data-target="#modal-primary"><i class="far fa-edit text-primary"></i></button>    
                                                                      
                                                                  </td>
                                                            </tr>
                                                      @endforeach
                                                      <tr>
                                                            <td></td>
                                                            <td class="text-right">Total Units: </td>
                                                            <td >{{collect($section)->sum('subjectUnit')}}</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>
                                                                
                                                            </td>
                                                      </tr>
                                                      
                                                </table>
                                          </div>
                                    </div>
                                    
                                  
                                 
                              @endforeach
                        </div>
                  </div> --}}
            </div>
            <div class="col-md-3">
                  <div class="card">
                        <div class="card-header card-title bg-primary">
                              About   
                              {{-- @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                              @endforeach --}}
                              @if ($errors->any())
                                    {{ Session::get('errors')}}
                              @endif
                        </div>
                        <div class="card-body">
                              <label><i class="fa fa-door-open mr-2"></i>COLLEGE</label>
                              <p class="text-success pl-2">{{$courseInfo->collegeDesc}}</p>
                              <hr>
                              <label><i class="fa fa-door-open mr-2"></i>COURSE</label>
                              <p class="text-success  pl-2">{{$courseInfo->courseDesc}}</p>
                              <hr>
                              <button class="btn btn-sm btn-success btn-block mb-2" data-toggle="modal"  data-target="#courseModal" data-widget="chat-pane-toggle"><b>UPDATE</b></button>

                            
                             
                              <a href="/course/delete/{{Str::slug($courseInfo->courseDesc)}}" class="btn btn-sm btn-danger btn-block mb-2"><b>DELETE</b></a>
                              
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

