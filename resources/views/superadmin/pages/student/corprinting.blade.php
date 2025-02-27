@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(auth()->user()->type == 3 || Session::get('currentPortal') == 3 ){
            $extend = 'registrar.layouts.app';
      }
@endphp

@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }
      </style>
@endsection


@section('content')

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>COR Printing</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">COR Printing</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>

@php
      $sy = DB::table('sy')->get(); 
      $semester = DB::table('semester')->get(); 
      $gradelevel = DB::table('gradelevel')->where('deleted',0)->orderBy('sortid')->get(); 
@endphp

    
<section class="content pt-0">
    
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="info-box shadow-lg">
                          <div class="info-box-content">
                              <div class="row">
                                    <div class="col-md-4">
                                         <h5><i class="fa fa-filter"></i> Filter</h5> 
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-2  form-group mb-0">
                                          <label for="">School Year</label>
                                          <select class="form-control select2 form-control-sm" id="filter_sy">
                                                @foreach ($sy as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-2  form-group mb-0">
                                          <label for="">Semester</label>
                                          <select class="form-control  form-control-sm select2 " id="filter_sem">
                                                @foreach ($semester as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->semester}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->semester}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                              </div>
                          </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row mt-2">
                                          <div class="col-md-12">
                                                <table class="table-hover table table-striped table-sm table-bordered " id="student_list" width="100%" style="font-size:.9rem !important">
                                                      <thead>
                                                            <tr>
                                                                  <th width="50%">Student</th>
                                                                  <th width="15%">Year Level</th>
                                                                  <th width="25%">Course</th>
                                                                  {{-- <th width="20%">Section</th> --}}
                                                                  <th width="10%"></th>
                                                            </tr>
                                                      </thead>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</section>

@endsection

@section('footerjavascript')
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>


      <script>
            $(document).ready(function(){

                  $('.select2').select2()

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  var all_students = []
                  get_students()

                  $(document).on('change','#filter_sem , #filter_sy',function(){
                        all_students = []
                        student_datatable()
                        get_students()
                  })
             
                  function get_students(){
                        $.ajax({
                              type:'GET',
                              url:'/student/cor/printing/enrolled',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val(),
                              },
                              success:function(data) {

                                    if(data.length == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'No Student found.'
                                          })
                                          all_students = data
                                          student_datatable()
                                    }else{
                                          Toast.fire({
                                                type: 'success',
                                                title: data.length+' students found.'
                                          })
                                          all_students = data
                                          student_datatable()
                                    }
                                    

                                  
                              }
                        })
                  }

                  $(document).on('click','.view_cor',function(){
                        var semid  = $('#filter_sem').val(); 
                        var syid  = $('#filter_sy').val(); 
                        var studid = $(this).attr('data-id')
                        window.open("/printcor/"+studid+"?syid="+syid+"&semid="+semid+"&studid="+studid+'&format=1');
                  })


                  function student_datatable(){

                        $("#student_list").DataTable({
                              destroy: true,
                              data:all_students,
                              lengthChange : false,
                              columns: [
                                          { "data": "studentname" },
                                          { "data": "levelname" },
                                          { "data": "courseabrv" },
                                          // { "data": "sectionDesc" },
                                          { "data": null },
                                    ],
                              columnDefs: [
                                    {
                                          'targets': 3,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                                $(td)[0].innerHTML = '<button style="font-size:.7rem !important" class="btn btn-primary btn-sm view_cor" data-id="'+rowData.studid+'">View COR</button>'
                                          }
                                    },
                                    
                              ]
                              
                        });

                        }
              

            })
      </script>


@endsection


