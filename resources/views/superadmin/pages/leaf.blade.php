@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(auth()->user()->type == 3 || Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }
@endphp

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <style>
             .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }
            .no-border-col{
                  border-left: 0 !important;
                  border-right: 0 !important;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }
      </style>
@endsection

@extends($extend)

@section('content')

@php
   $sy = DB::table('sy')->get(); 
   $activesy = DB::table('sy')->where('isactive',1)->first()->id; 
@endphp

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>LEASF</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">LEASF</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-8">
                        <div class="info-box shadow-lg">
                              <div class="info-box-content">
                                    <div class="row">
                                          <div class="col-md-3">
                                                <div class="form-group">
                                                      <label for="">School Year</label>
                                                      <select class="form-control" id="filter_sy">
                                                            @foreach ($sy as $item)
                                                                  @if($item->isactive == 1)
                                                                        <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                                  @else
                                                                        <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                                  @endif
                                                            @endforeach
                                                      </select>
                                                </div>
                                          </div>
                                          <div class="col-md-9">
                                                <div class="form-group">
                                                      <label for="">Student</label>
                                                      <select class="form-control" id="filter_students">
                                                         
                                                      </select>
                                                </div>
                                          </div>
                                    </div>
                                    {{-- <div class="row">
                                          <div class="col-md-4">
                                                <button class="btn btn-primary btn-sm" id="button_to_filter">Filter</button>
                                          </div>
                                    </div> --}}
                              </div>
                        </div>
                  </div>
                  <div class="col-md-5">
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12" id="leaf_holder">

                  </div>
            </div>
           
          
      </div>
</section>

@endsection

@section('footerjavascript')
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
      <script>
            $(document).ready(function(){

                  var all_student = []
                  var temp_studid = @json(\Request::get('studid'));
                  var syid = @json(\Request::get('syid'));
                  get_student()

                  $("#filter_sy").select2()
                  $("#filter_students").select2()

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  if(syid != null){
                        $('#filter_sy').val(syid).change()
                  }

               

                  function get_student(){
                        $.ajax({
                              type:'GET',
                              url: '/superadmin/leaf/students',
                            
                              success:function(data) {
                                    $('#filter_students').empty()
                                    $('#filter_students').append('<option value="">Select student</option>')
                                    $("#filter_students").select2({
                                          data: data,
                                    })
                                    $("#filter_students").val(temp_studid).change()
                              }
                        })
                  }

                  $(document).on('change','#filter_students , #filter_sy',function(){
                        $('#syid').val($('#filter_sy').val())
                        load_leaf()
                  })

                  

                 
                  function load_leaf(){

                        $.ajax({
                              type:'GET',
                              url: '/superadmin/leaf/get_details',
                              data:{
                                   syid:$('#filter_sy').val(),
                                   studid:$('#filter_students').val(),
                              },
                              success:function(data) {
                                    $('#leaf_holder').empty()
                                    $('#leaf_holder').append(data)

                              }
                        })
                  }

                  
                  $(document).on('click', '#btn-exporttopdf', function(){
				window.open('/superadmin/leaf/get_details?export=pdf&studid='+$('#filter_students').val()+'&syid='+$('#filter_sy').val()+'','_blank')
			})


            })
      </script>


@endsection


