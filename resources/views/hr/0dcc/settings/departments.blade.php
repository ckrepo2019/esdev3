

{{-- <!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script> --}}

@extends('hr.layouts.app')
@section('content')
<style>
    
@media screen and (max-width : 1920px){
  .div-only-mobile{
  visibility:hidden;
  }
}
@media screen and (max-width : 906px){
 .desk{
  visibility:hidden;
  }
 .div-only-mobile{
  visibility:visible;
  }
  /* .updatebutton, .deletebutton{
      width: 100% ;
      display: block;
  } */
  
}

</style>



<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-chart-line nav-icon"></i> DEPARTMENTS</h4>
          <!-- <h1>Attendance</h1> -->
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">DEPARTMENTS</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
      {{-- <div class="col-md-12">
          <div class="card">
              <div class="card-header"> --}}
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Search:</label>
    
                      <div class="input-group">
                        <input class="filter form-control" placeholder="Search department" />
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                      </div>
                      <!-- /.input group -->
                    </div>
                  </div>
                  <div class="col-md-8">
                    <button type="button" class="btn btn-sm btn-primary float-right" id="adddepartment"><i class="fa fa-plus"></i> Department</button>
                  </div>
                </div>
                <div class="row d-flex align-items-stretch text-uppercase">
                  @if(count($departments) == 0)
                  <div class="col-md-12">
                    <div class="alert alert-info alert-dismissible">
                        {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                        <h5><i class="icon fas fa-info"></i> Alert!</h5>
                        No departments shown.
                    </div>
                  </div>
                  @else
                    @foreach($departments as $department)
                    <div class="card col-md-4 " style="border: none !important;box-shadow: none !important;" data-string="{{$department->department}}<">
                        <div class="card-body p-2"  style="border: 1px solid#ddd;background: #e8e8e8;">
                            <div class="row">
                              <div class="col-md-8">{{$department->department}}</div>
                              <div class="col-md-4 p-0 pr-2 text-right">
                                <button type="button" class="btn btn-sm btn-warning p-1 m-0 updatebutton" data-toggle="modal" data-target="#edit{{$department->id}}"><small>Edit</small></button>
                                <button type="button" class="btn btn-sm btn-danger p-1 m-0 deletebutton"  data-toggle="modal" data-target="#delete{{$department->id}}"><small>Delete</small></button>
                              </div>
                            </div>
                            <small class="text-muted">
                                DEPARTMENT HEAD:
                                @if(count($department->deptheads)>0)
                                  @foreach($department->deptheads as $depthead)
                                    {{$depthead->lastname}}, {{$depthead->firstname}} {{$depthead->middlename}} {{$depthead->suffix}}<br/>
                                  @endforeach
                                @endif 
                            </small>
                            <small class="text-muted">
                                Created by: {{$department->lastname}}, {{$department->firstname}} {{$department->middlename}} {{$department->suffix}}
                            </small>
                            <div class="modal fade" id="edit{{$department->id}}" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-md">
                                    <form action="/hr/settings/departments/editdepartment" method="get" id="" name="changestatus">
                                        <div class="modal-content text-uppercase">
                                            <div class="modal-header bg-info">
                                                <h4>Edit Department</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <label>Office</label>
                                                <input type="text" class="form-control text-uppercase" name="department" value="{{$department->department}}"/>
                                                <input type="hidden" class="form-control" name="departmentid" value="{{$department->id}}"/>
                                                <br/>
                                                <label>Department Head</label>
                                                <select class="form-control select2" style="width: 100%;" name="employeeid">
                                                  <option value="" selected="selected">NONE</option>
                                                  @if(count($employees)>0)
                                                    @foreach($employees as $employee)
                                                      <option value="{{$employee->id}}"@if(count($department->deptheads)>0) @foreach($department->deptheads as $depthead) {{$employee->id == $depthead->id ? 'selected' : ''}} @endforeach @endif>{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</option>
                                                    @endforeach
                                                  @endif
                                                </select>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-default" >Cancel</button>
                                                <button type="submit" class="btn btn-warning">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal fade" id="delete{{$department->id}}" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-md">
                                    <form action="/hr/settings/departments/deletedepartment" method="get" id="" name="changestatus">
                                        <div class="modal-content text-uppercase">
                                            <div class="modal-header">
                                                <h4>Delete Department</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <strong>Are you sure you want to delete this department?</strong>
                                                <input type="hidden" class="form-control" name="departmentid" value="{{$department->id}}"/>
                                                <input type="hidden" class="form-control text-uppercase" name="department" value="{{$department->department}}"/>
                                                <br>
                                                <br>
                                                <h3 class="text-danger"><strong>{{$department->department}}</strong></h3>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                  @endif
                </div>
              {{-- </div>
              <div class="card-body"> --}}
  <div class="modal fade" id="modal-add-dept" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="/hr/settings/departments/adddepartment" method="GET">
          @csrf
          <div class="modal-header">
            <h4 class="modal-title">Add department</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <label>Department</label>
            <input type="text" name="departmentname" class="form-control" required/>
            <br/>
            <label>Department Head</label>
            <select class="form-control select2" style="width: 100%;" name="employeeid">
              <option value="" selected="selected">NONE</option>
              @if(count($employees)>0)
                @foreach($employees as $employee)
                  <option value="{{$employee->id}}">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</option>
                @endforeach
              @endif
            </select>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  @endsection
  @section('footerscripts')
{{-- <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script> --}}
<script>
  var $ = jQuery;
  $(document).ready(function(){
      $(".filter").on("keyup", function() {
          var input = $(this).val().toUpperCase();
          var visibleCards = 0;
          var hiddenCards = 0;

          $(".container").append($("<div class='card-group card-group-filter'></div>"));


          $(".card").each(function() {
              if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

              $(".card-group.card-group-filter:first-of-type").append($(this));
              $(this).hide();
              hiddenCards++;

              } else {

              $(".card-group.card-group-filter:last-of-type").prepend($(this));
              $(this).show();
              visibleCards++;

              if (((visibleCards % 4) == 0)) {
                  $(".container").append($("<div class='card-group card-group-filter'></div>"));
              }
              }
          });

      });
  })
</script>
<script>
    $(document).ready(function(){
    $('.select2').select2()
        const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
        });
      
      
    @if(session()->has('response'))
        @if(session('response') == 1)
            toastr.success('Added successfully!', 'Departments')
        @elseif(session('response') == 2)
            toastr.warning('Department already exists!', 'Departments')
        @elseif(session('response') == 11)
            toastr.success('Updated successfully!', 'Departments')
        @elseif(session('response') == 111)
            toastr.success('Deleted successfully!', 'Departments')
        @else
            toastr.error('Something went wrong!', 'Departments')
        @endif
    @endif
      $('#adddepartment').on('click',function(){
        $('#modal-add-dept').modal('show')
      })
    })
  </script>
@endsection

