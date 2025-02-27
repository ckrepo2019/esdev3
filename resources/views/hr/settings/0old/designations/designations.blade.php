

@extends('hr.layouts.app')
@section('content')
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"> --}}
{{-- <link rel="stylesheet" href="{{asset('plugins/datatables/DataTables/css/jquery.dataTables.min.css')}}"> --}}
<link rel="stylesheet" href="{{asset('plugins/datatables-rowreorder/css/rowReorder.bootstrap4.min.css')}}">
<style>
    /* td {
        padding: 0px !important;
    } */
    .dataTables_wrapper .dataTables_filter {
    float: unset !important;
    text-align: left !important;
}
</style>
{{-- <script src="{{asset('plugins/jquery/jquery-3-3-1.min.js')}}"></script>

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
</script> --}}

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        <h4><i class="fa fa-users"></i> DESIGNATIONS</h4>
          <!-- <h1>Attendance</h1> -->
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Designations</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  
{{-- @if(session()->has('messageAdded'))
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
@endif --}}
  {{-- <div class="row mb-2">
      <div class="col-md-6">
          <input class="filter form-control" placeholder="Search designation" />
      </div>
      <div class="col-md-6 text-right">
          <button class="btn btn-primary" id="addRow"><i class="fa fa-plus" ></i> Designation</button>
          <a class="btn btn-primary text-white" data-toggle="modal" data-target="#add_dept"><i class="fa fa-plus" ></i> Designation</a>
          
      </div>
  </div> --}}
  <div class="modal fade" id="add_dept" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <form action="/hr/settings/designations/adddesignation" method="get" id="form-add">
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
  
<div class="row">
    <div class="col-12">
        <div class="card shadow" style="border: none !important;box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; border-radius: 10px;">
            <div class="card-header d-flex p-0">
                {{-- <h3 class="card-title p-3">Tabs</h3> --}}
                <ul class="nav nav-pills ml-auto p-2">
                {{-- <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Tab 1</a></li> --}}
                <li class="nav-item">
                    {{-- <a class="nav-link" href="#tab_2" data-toggle="tab">                    </a> --}}
                    <a class="nav-link btn-info text-white ml-2" data-toggle="modal" data-target="#add_dept"><i class="fa fa-plus" ></i> Designation</a>
                </li>
                {{-- <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Tab 3</a></li> --}}
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <small><em>Note: Reorder designations by dragging the <strong>sorting id</strong>.</em></small>
                        <br/>&nbsp;
                        <table id="example" class="display mt-3 table table-hover" style="width:100%; font-size: 14px;">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Sort ID</th>
                                    <th style="width: 50%;">Title</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            
                            <tbody id="tbody-reorder">
                                @if(count($designations)>0)
                                {{-- @php
                                    $des = $designations;
                                @endphp --}}
                                    @foreach ($designations as $designation)
                                        <tr >
                                            <td typeid="{{$designation->id}}" class="text-center">{{$designation->sortid}}</td>
                                            @if($designation->constant == '1')
                                            <td class="text-center">{{$designation->designation}}</td>
                                            <td class="text-right">
                                                <small><i>You can't make changes to this designation</i></small>
                                            </td>
                                            @else
                                            <td class="text-center p-0" style="vertical-align: middle;"><input type="text" class="form-control text-center m-0 form-control-sm input-title" name="title" value="{{$designation->designation}}" readonly/></td>
                                            <td class="text-right p-0" style="vertical-align: middle;">
                                               <button type="button" class="btn btn-sm btn-delete"><i class="fa fa-trash"></i> Delete</button>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="tab_2">
                        <fieldset class="form-group border p-2">
                            <legend class="w-auto m-0">
                                <button class="btn btn-primary" id="addRow"><i class="fa fa-plus" ></i> Designations</button></legend>
                        <table class="mb-3" style="width:100%; font-size: 14px;">
                            <thead>
                                <tr>
                                    {{-- <th style="width: 10%;">Sort ID</th> --}}
                                    <th style="width: 32%;">Insert Before</th>
                                    <th style="width: 45%;">Title</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="table-add">
        
                            </tbody>
                        </table>
                        </fieldset>
                    </div>

                    {{-- <div class="tab-pane" id="tab_3">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                    when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    It has survived not only five centuries, but also the leap into electronic typesetting,
                    remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                    sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                    like Aldus PageMaker including versions of Lorem Ipsum.
                    </div> --}}

                </div>

            </div>
        </div>

    </div>

</div>

  {{-- <div class="row text-uppercase">
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
  </div> --}}
@endsection
@section('footerscripts')
{{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}
<script src="{{asset('plugins/datatables-rowreorder/js/dataTables.rowReorder.min.js')}}"></script>

<script type="text/javascript">
 $(document).ready(function() {
    $( document )
    .submit('#form-add', function( e ) {

        

        var inputs = new FormData(this)

        Swal.fire({
                title: 'Saving changes...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })

            console.log(inputs)
        $.ajax({
                url: '/hr/settings/designations/adddesignation',
                type: 'get',
                data: inputs,
                processData: false,
                contentType: false,
                success:function(data) {
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    window.location.reload()
                }
            
                ,error:function(){
                    toastr.error('Something went wrong! PLease make sure it is the correct file!')
                }
        })
        e.preventDefault();
    })
    var thisinput_done;
    var thisinput;
    $( ".input-title" ).dblclick(function() {
        if(thisinput_done == 0)
        {
            thisinput.css('border','1px solid red')
            toastr.warning('Please press enter when done editing', 'Designations')
        }else{
            thisinput_done = 0;
            thisinput = $(this);
            $('.input-title').prop('readonly',true);
            $(this).removeAttr('readonly')
        }

    });
    $(".input-title").on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            if(!thisinput.attr('readonly'))
            {
            $.ajax({
                url: '/hr/setup/designations/updatedesignation',
                type: 'get',
                data:{
                    action: 'edit',
                    id: thisinput.closest('tr').find('td').first().attr('typeid'),
                    value: thisinput.val()
                },
                success:function(data){
                $('.input-title').removeAttr('style');
                    thisinput_done=1;
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        $('.input-title').prop('readonly',true);
                        toastr.success('Updated successfully!', 'Designations')
                    
                }
            })
            }
        }
    });
    $('.btn-delete').on('click', function(){
        var thisbutton = $(this);
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
            $.ajax({
                    url:"/hr/setup/designations/updatedesignation",
                    method:'GET',
                    data:{
                        action: 'delete',
                        id: thisbutton.closest('tr').find('td').first().attr('typeid'),
                    },
                    success:function(data)
                    {
                        toastr.success('Deleted successfully!', 'Designations')
                        thisbutton.closest('tr').remove()
                    }
                });
            }
        })
    })
    function savechanges(_dataarray)
    {
            Swal.fire({
                title: 'Saving changes...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
        $.ajax({
            url: '/hr/setup/designations/updatesortid',
            type: 'POST',
            data:{
                "_token": "{{ csrf_token() }}",
                sortvalues: JSON.stringify(_dataarray)
            },
            success:function(data){
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    toastr.success('Reordered successfully!', 'Designations')
                
            }
        })
    }
    var table = $('#example').DataTable( {
    dom: 'Bfrtip',
    buttons: [
        'colvis',
        'excel',
        'print'
    ],
        responsive: true,
        rowReorder: true,
        // rowReorder: {
        //     selector: 'td:nth-child(2)'
        // },
        // rowReorder: {
        //     selector: 'tr'
        // },
        // columnDefs: [
        //     { targets: 0, visible: false }
        // ]
            "paging": false,
        scrollY: 500,
            'columnDefs': [ {
                'targets': [1,2], /* column index */
                'orderable': false, /* true or false */
            }]
    } );
    table.on( 'row-reorder', function ( e, diff, edit ) {
        var sortingarray = [];
        var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';
        // var designationid = edit.triggerRow.selector.rows[0].children[0].attributes[0].value;
        // var sortid = edit.triggerRow.data()[0];
        // console.log(sortid+'-'+designationid)
        // var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';
        // var newposition;
        for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
            var rowData = table.row( diff[i].node ).data();
            result += rowData[1]+' updated to be in position '+
                diff[i].newData+' (was '+diff[i].oldData+')<br>';
            var obj = {
                sortid : diff[i].newData,
                typeid  : $(diff[i].node).first().find('td').first().attr('typeid')
            }
            sortingarray.push(obj)
        }
        savechanges(sortingarray)

 
        
    } );
} );
  </script>
@endsection

