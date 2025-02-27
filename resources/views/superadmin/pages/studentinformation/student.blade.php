
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <style>
            .select2-selection{
                height: calc(2.25rem + 2px) !important;
            }
      </style>
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
                                    <li class="breadcrumb-item active">Student Information</li>
                              </ol>
                        </div>
                  </div>
            </div>
      </section>
      <section class="content pt-0">
            <div class="row">
                  <div class="col-12">
                        <div class="card">
                              <div class="card-header bg-primary p-1">
                              </div>
                              <div class="card-body">
                                   <div class="row">
                                         <div class="col-md-4 form-group">
                                                <label for="">Student</label>
                                               <select name="" id="student_list" class="form-control select2">
                                                      <option value="">Select Student</option>
                                                      @foreach ($students as $student)
                                                          <option value="{{$student->id}}">{{$student->lastname.', '.$student->firstname.' - '.$student->sid}}</option>
                                                      @endforeach
                                                   
                                               </select>
                                         </div>
                                         <div class="col-md-4">
                                         </div>
                                         <div class="col-md-4 ">
                                                <button class="btn btn-primary mt-4 float-right" id="reload">RELOAD</button>
                                         </div>
                                    </div>
                                    <div class="row" id="studen_information">

                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </section>

@endsection

@section('footerjavascript')
      <script src="{{asset('js/pagination.js')}}"></script>
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

      <script src="{{asset('plugins/croppie/croppie.js')}}"></script>
      <link rel="stylesheet" href="{{asset('plugins/croppie/croppie.css')}}">

      <script>
            $(document).ready(function(){
                  $('.select2').select2()
            })

            var selected_student

            function view_info(){

                  $.ajax({
                        type:'GET',
                        url:'/student/information/profile',
                        data:{
                              studid: selected_student,
                        },
                        success:function(data){
                              $('#studen_information').empty()
                              $('#studen_information').append(data)
                        }
                  })
            }

            $(document).on('change','#student_list',function(){
                  selected_student = $(this).val()
                  view_info()
            })

            $('#reload').click(function(){
                  
                  view_info()
            })

      </script>

@endsection


