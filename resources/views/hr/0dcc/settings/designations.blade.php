

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
@extends('hr.layouts.app')
@section('content')

<script src="{{asset('plugins/jquery/jquery-3-3-1.min.js')}}"></script>

<script>
    var $ = jQuery;
    $(document).ready(function(){
        $(".filter").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".each-designation").each(function() {
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
        <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-chart-line nav-icon"></i> DESIGNATIONS</h4>
          <!-- <h1>Attendance</h1> -->
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">DESIGNATIONS</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  
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
      <div class="col-md-6">
          <input class="filter form-control" placeholder="Search designation" />
      </div>
      <div class="col-md-6 text-right">
          <a class="btn btn-primary text-white" data-toggle="modal" data-target="#add_dept"><i class="fa fa-plus" ></i> Designation</a>
      </div>
  </div>
  <div class="modal fade" id="add_dept" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <form action="/hr/settings/designations/adddesignation" method="get">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Add Designation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p hidden><strong>Office</strong></p>
                    <select class="form-control" name="departmentid" required hidden>
                        <option value="0"></option>
                        {{-- @if(count($departments)!=0)
                            @foreach ($departments as $department)
                                <option value="{{$department->id}}">{{strtoupper($department->department)}}</option>
                            @endforeach
                        @endif --}}
                    </select>
                    <br>
                    <p><strong>Designation</strong></p>
                    <input type="text" class="form-control text-uppercase" name="designation" required/>
                    <br>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default cancelbtn" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
  </div>
  <div class="row text-uppercase">
      @if(count($designations)!=0)
          @foreach ($designations as $designation)
            <div class="col-md-4 each-designation" data-string="{{$designation->designation}} {{$designation->departmentname}}<">
                <div class="card shadow" style="border: none !important;box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; border-radius: 10px;">
                    <div class="card-body p-2" >
                      <div class="row">
                          <div class="col-md-12">
                              <h6>{{$designation->designation}}</h6>
                              <small class="text-muted">
                                  Created by: {{$designation->lastname}}, {{$designation->firstname}} {{$designation->middlename}} {{$designation->suffix}}</small>
                          </div>
                          {{-- <div class="col-md-4">
                              <h6>OFFICE:  </h6>
                              <form action="/hr/settings/designations/editdepartment" method="get" name="changedepartment">
                                  <input type="hidden" name="designationid" value="{{$designation->id}}"/>
                                  <input type="hidden" name="designation" value="{{$designation->designation}}"/>
                                  <select class="form-control form-control-sm departmentid  text-uppercase" style="display: inline;" designationid="{{$designation->id}}" name="departmentid" required>
                                      <option value="">SELECT OFFICE</option>
                                      @if($designation->departmentid == 0)                                                
                                      @endif
                                          @if(count($departments)!=0)
                                              @foreach ($departments as $department)
                                                  <option value="{{$department->id}}" {{$department->id == $designation->departmentid ? 'selected' : ''}}>{{strtoupper($department->department)}}</option>
                                              @endforeach
                                          @endif
                                  </select>
                              </form>
                          </div> --}}
                          <div class="col-md-12">
                              @if($designation->constant == '1')
                                  <small style="font-size: 10px;">
                                        Note: You cannot make changes to this designation
                                  </small>
                              @else
                                  
                                  <button type="button" class="btn btn-default p-1 mr-2 mb-2 mt-2 updatebutton" data-toggle="modal" data-target="#edit{{$designation->id}}"><small>Edit</small></button>
                                  <div class="modal fade" id="edit{{$designation->id}}" style="display: none;" aria-hidden="true">
                                      <div class="modal-dialog modal-md">
                                          <form action="/hr/settings/designations/editdesignation" method="get" id="" name="changestatus">
                                              <div class="modal-content">
                                                  <div class="modal-header">
                                                      <h4>Edit Designation</h4>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">×</span>
                                                      </button>
                                                  </div>
                                                  <div class="modal-body">
                                                      <label>Designation</label>
                                                      <input type="text" class="form-control" name="designation" value="{{$designation->designation}}"/>
                                                      <input type="hidden" class="form-control" name="designationid" value="{{$designation->id}}"/>
                                                  </div>
                                                  <div class="modal-footer justify-content-between">
                                                      <button type="button" class="btn btn-default cancelbtn" >Cancel</button>
                                                      <button type="submit" class="btn btn-warning approves">Save Changes</button>
                                                  </div>
                                              </div>
                                          </form>
                                      </div>
                                  </div>
                                  <button type="button" class="btn btn-outline-danger p-1 mb-2 mt-2 deletebutton"  data-toggle="modal" data-target="#delete{{$designation->id}}"><small><i class="fa fa-trash"></i></small></button>
                                  <div class="modal fade" id="delete{{$designation->id}}" style="display: none;" aria-hidden="true">
                                      <div class="modal-dialog modal-md">
                                          <form action="/hr/settings/designations/deletedesignation" method="get" id="" name="changestatus">
                                              <div class="modal-content">
                                                  <div class="modal-header">
                                                      <h4>Delete Department</h4>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">×</span>
                                                      </button>
                                                  </div>
                                                  <div class="modal-body">
                                                      <strong>Are you sure you want to delete this designation?</strong>
                                                      <input type="hidden" class="form-control" name="designationid" value="{{$designation->id}}"/>
                                                      <input type="hidden" class="form-control" name="designation" value="{{$designation->designation}}"/>
                                                      <br>
                                                      <br>
                                                      <h3 class="text-danger"><strong>{{$designation->designation}}</strong></h3>
                                                  </div>
                                                  <div class="modal-footer justify-content-between">
                                                      <button type="button" class="btn btn-default cancelbtn" >Cancel</button>
                                                      <button type="submit" class="btn btn-danger approves">Delete</button>
                                                  </div>
                                              </div>
                                          </form>
                                      </div>
                                  </div>
                              @endif 
                          </div>
                      </div>
                    </div>
                </div>
            </div>
          @endforeach
      @endif
  </div>
{{-- <div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-12">
            <!-- <h3 class="page-title">Designations</h3> -->
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            DESIGNATIONS</h4>
            <ul class="breadcrumb col-md-9 float-left">
                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                <li class="breadcrumb-item active">Designations</li>
            </ul>
            <div class="col-md-3 float-right ml-auto">
                <a href="#" class="btn btn-block" data-toggle="modal" data-target="#add_dept"><i class="fa fa-plus"></i> Add Designations</a>
                <div class="modal fade" id="add_dept" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <form action="/designations/adddesignation" method="get">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title">Add Designation</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Department</strong></p>
                                    <select class="form-control" name="departmentid" required>
                                        @if(count($departments)!=0)
                                            @foreach ($departments as $department)
                                                <option value="{{$department->id}}">{{strtoupper($department->department)}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <br>
                                    <p><strong>Designation</strong></p>
                                    <input type="text" class="form-control text-uppercase" name="designation" required/>
                                    <br>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default cancelbtn" data-dismiss="modal">Close</button>
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
</div>
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
<div class="card  text-uppercase">
    <div class="card-header bg-info">
        <span >Note: To change selected designation's department, select your option by clicking the department dropdown.</span>
    </div>
    <div class="card-body">
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow: scroll">
            <div class="row">
                <div class="col-sm-12">
                    <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                        <thead class="bg-warning">
                            <tr>
                                <th><center>Designation</center></th>
                                <th><center>Department</center></th>
                                <th style="width:20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($designations)!=0)
                            @foreach ($designations as $designation)
                                <tr>
                                    <td>{{$designation->designation}}</td>
                                    <td>
                                        <form action="/designations/editdepartment" method="get" name="changedepartment">
                                            <input type="hidden" name="designationid" value="{{$designation->id}}"/>
                                            <input type="hidden" name="designation" value="{{$designation->designation}}"/>
                                            <select class="form-control departmentid  text-uppercase" designationid="{{$designation->id}}" name="departmentid" required>
                                                <option value="">SELECT DEPARTMENT</option>
                                                @if($designation->departmentid == 0)                                                
                                                @endif
                                                    @if(count($departments)!=0)
                                                        @foreach ($departments as $department)
                                                            <option value="{{$department->id}}" {{$department->id == $designation->departmentid ? 'selected' : ''}}>{{strtoupper($department->department)}}</option>
                                                        @endforeach
                                                    @endif
                                            </select>
                                        </form>
                                        <br>
                                        
                                    </td>
                                    <td>
                                        @if($designation->constant == 1)
                                            <button class="btn btn-sm btn-secondary">You cannot apply some actions in this designation.</button>
                                        @elseif($designation->constant == 0)
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#edit{{$designation->id}}"><i class="fa fa-edit"></i>&nbsp;Edit</button>
                                            <div class="modal fade" id="edit{{$designation->id}}" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-md">
                                                    <form action="/designations/editdesignation" method="get" id="" name="changestatus">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4>Edit Designation</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <label>Designation</label>
                                                                <input type="text" class="form-control" name="designation" value="{{$designation->designation}}"/>
                                                                <input type="hidden" class="form-control" name="designationid" value="{{$designation->id}}"/>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default cancelbtn" >Cancel</button>
                                                                <button type="submit" class="btn btn-warning approves">Save Changes</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            &nbsp;&nbsp;&nbsp;
                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete{{$designation->id}}"><i class="fa fa-trash"></i>&nbsp;Delete</button>
                                            <div class="modal fade" id="delete{{$designation->id}}" style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-md">
                                                    <form action="/designations/deletedesignation" method="get" id="" name="changestatus">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4>Delete Department</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <strong>Are you sure you want to delete this designation?</strong>
                                                                <input type="hidden" class="form-control" name="designationid" value="{{$designation->id}}"/>
                                                                <input type="hidden" class="form-control" name="designation" value="{{$designation->designation}}"/>
                                                                <br>
                                                                <br>
                                                                <h3 class="text-danger"><strong>{{$designation->designation}}</strong></h3>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default cancelbtn" >Cancel</button>
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
</div> --}}
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
    // $(function () {
    //     $("#example1").DataTable({
    //         // pageLength : 10,
    //         // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
    //         paging: false
    //     });
        
    //     $('.compose-textarea').summernote({
            
    //         toolbar: []
    //     });
    // })
   
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
   $(document).on('click', '.cancelbtn', function(){
       window.location.reload();

   })
   $(document).on('change', '.departmentid', function(){
       $(this).closest('form[name=changedepartment]').submit();
   })
  </script>
@endsection

