

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
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


<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

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

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-chart-line nav-icon"></i> OFFICES (<small>Former Departments</small>)</h4>
          <!-- <h1>Attendance</h1> -->
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">OFFICES</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
{{-- <div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-12">
            <!-- <h3 class="page-title">Departments</h3> -->
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            DEPARTMENTS</h4>
            <ul class="breadcrumb col-md-9 float-left">
                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                <li class="breadcrumb-item active">Departments</li>
            </ul>
            <div class="col-md-3 float-right ml-auto">
                <a href="#" class="btn btn-block" data-toggle="modal" data-target="#add_dept"><i class="fa fa-plus"></i> Add Department</a>
                <div class="modal fade" id="add_dept" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <form action="/departments/adddepartment" method="get">
                            <div class="modal-content text-uppercase">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title">Add Department</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Department</strong></p>
                                    <input type="text" class="form-control  text-uppercase" name="department" required/>
                                    <br>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </form>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            </div>
        </div>
    </div>
</div> --}}
@if(session()->has('messageAdded'))
    <div class="alert alert-success alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('messageAdded') }}
    </div>
@endif
@if(session()->has('messageEdited'))
    <div class="alert alert-success alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('messageEdited') }}
    </div>
@endif
@if(session()->has('messageDeleted'))
    <div class="alert alert-success alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-trash"></i> Alert!</h5>
        {{ session()->get('messageDeleted') }}
    </div>
@endif
@if(session()->has('messageExists'))
    <div class="alert alert-warning alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-edit"></i> Alert!</h5>
        {{ session()->get('messageExists') }}
    </div>
@endif

<div class="row mb-2">
    <div class="col-md-4">
        <input class="filter form-control" placeholder="Search office" />
    </div>
    <div class="col-md-6">
        &nbsp;
    </div>
    <div class="col-md-2">
        <a class="btn btn-block btn-primary text-white" data-toggle="modal" data-target="#add_dept"><i class="fa fa-plus" ></i> Office</a>
        <div class="modal fade" id="add_dept" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <form action="/hr/settings/offices/addoffice" method="get">
                    <div class="modal-content text-uppercase">
                        <div class="modal-header bg-info">
                            <h4 class="modal-title">Add Office</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Office</strong></p>
                            <input type="text" class="form-control  text-uppercase" name="department" required/>
                            <br>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </form>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
</div>
    <div class="row d-flex align-items-stretch text-uppercase">
        @if(count($departments)!=0)
            @foreach ($departments as $department)
                <div class="card col-md-4 " style="border: none !important;box-shadow: none !important;" data-string="{{$department->department}}<">
                    <div class="card-body p-2 @if($department->constant == '1') bg-warning @endif"  @if($department->constant == '0')style="border: 1px solid#ddd;background: #e8e8e8;"@endif>
                        {{-- <h6 class="text-center">{{$department->department}}</h6> --}}
                        <div class="row">
                            <div class="col-7"><h6>{{$department->department}}</h6></div>
                            <div class="col-5 text-right">
                                @if($department->constant != '1')
                                <button type="button" class="btn btn-sm btn-warning updatebutton" data-toggle="modal" data-target="#edit{{$department->id}}"><small>Edit</small></button>
                                <button type="button" class="btn btn-sm btn-danger deletebutton"  data-toggle="modal" data-target="#delete{{$department->id}}"><small>Delete</small></button>
                                @endif
                            </div>
                        </div>
                        <small class="text-muted">
                            Created by: {{$department->lastname}}, {{$department->firstname}} {{$department->middlename}} {{$department->suffix}}
                        </small>
                        <br/>
                        @if($department->constant == '1')
                            <small>
                                <sub>
                                    Note: You cannot make changes to this office
                                </sub>
                            </small>
                        @else
                            
                            <div class="modal fade" id="delete{{$department->id}}" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-md">
                                    <form action="/hr/settings/offices/deleteoffice" method="get" id="" name="changestatus">
                                        <div class="modal-content text-uppercase">
                                            <div class="modal-header">
                                                <h4>Delete Office</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <strong>Are you sure you want to delete this office?</strong>
                                                <input type="hidden" class="form-control" name="departmentid" value="{{$department->id}}"/>
                                                <input type="hidden" class="form-control text-uppercase" name="department" value="{{$department->department}}"/>
                                                <br>
                                                <br>
                                                <h3 class="text-danger"><strong>{{$department->department}}</strong></h3>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default disapproved" >Cancel</button>
                                                <button type="submit" class="btn btn-danger approves">Delete</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal fade" id="edit{{$department->id}}" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-md">
                                    <form action="/hr/settings/offices/editoffice" method="get" id="" name="changestatus">
                                        <div class="modal-content text-uppercase">
                                            <div class="modal-header bg-info">
                                                <h4>Edit Office</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <label>Office</label>
                                                <input type="text" class="form-control text-uppercase" name="department" value="{{$department->department}}"/>
                                                <input type="hidden" class="form-control" name="departmentid" value="{{$department->id}}"/>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default disapproved" >Cancel</button>
                                                <button type="submit" class="btn btn-warning approves">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
{{-- <div class="card text-uppercase">
    <div class="card-body">
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow: scroll">
            <div class="row">
                <div class="col-sm-12">
                    <table id="example1"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                        <thead class="bg-warning">
                            <tr>
                                <th style="width:5%">#</th>
                                <th><center>Department</center></th>
                                <th style="width:20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($departments)!=0)
                                @foreach ($departments as $department)
                                    <tr>
                                        <td></td>
                                        <td>{{$department->department}}</td>
                                        <td>
                                            @if($department->constant == '1')
                                                <button class="btn btn-sm btn-secondary">You cannot apply some actions in this designation.</button>
                                            @else
                                                <button class="btn btn-sm btn-warning updatebutton" data-toggle="modal" data-target="#edit{{$department->id}}"><i class="fa fa-edit"></i>&nbsp;Edit</button>
                                                <div class="modal fade" id="edit{{$department->id}}" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog modal-md">
                                                        <form action="/departments/editdepartment" method="get" id="" name="changestatus">
                                                            <div class="modal-content text-uppercase">
                                                                <div class="modal-header bg-info">
                                                                    <h4>Edit Department</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <label>Department</label>
                                                                    <input type="text" class="form-control text-uppercase" name="department" value="{{$department->department}}"/>
                                                                    <input type="hidden" class="form-control" name="departmentid" value="{{$department->id}}"/>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-default disapproved" >Cancel</button>
                                                                    <button type="submit" class="btn btn-warning approves">Save Changes</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                &nbsp;&nbsp;&nbsp;
                                                <button class="btn btn-sm btn-danger deletebutton" data-toggle="modal" data-target="#delete{{$department->id}}"><i class="fa fa-trash"></i>&nbsp;Delete</button>
                                                <div class="modal fade" id="delete{{$department->id}}" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog modal-md">
                                                        <form action="/departments/deletedepartment" method="get" id="" name="changestatus">
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
                                                                    <button type="button" class="btn btn-default disapproved" >Cancel</button>
                                                                    <button type="submit" class="btn btn-danger approves">Delete</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>  --}}
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script>
    $(function () {
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });
        
        $('.compose-textarea').summernote({
            
            toolbar: []
        });
    })
   
    $(document).ready(function(){
        $('.note-editable').attr('contenteditable','false');
                $('.note-editable').css('backgroundColor','white');
                $('.note-editable').css('backgroundColor','white');
                $('.note-editor').removeClass('card');
        window.setTimeout(function () {
            $(".alert-success").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
        window.setTimeout(function () {
            $(".alert-danger").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
   })
   $(document).on('click', '.disapproved', function(){
       window.location.reload();

   })
  </script>
@endsection

