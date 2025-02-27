@extends('finance.layouts.app')

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Finance</h1> -->
          
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/finance/setup">Finance Setup</a></li>
            <li class="breadcrumb-item active">Chart of Accounts</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content pt-0">
    <div class="row">
      <div class="col-md-12">
        <div class="main-card card">
      		<div class="card-header text-lg bg-info">
            <div class="row">
              <div class="col-md-5">
                <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
                  <b>Chart of Accounts</b>
                </h4>
              </div>
              <div class="col-md-3">
                <select id="cbofiltergroup" class="form-control filter">
                  <option value="0">Account Type</option>
                </select>
              </div>
              <div class="col-md-4 input-group">
                <input type="search" id="txtaccname" class="form-control filter" placeholder="Account Type">
                <div class="input-group-append">
                  <button id="btncreatetype" class="btn btn-warning"><i class="far fa-plus-square"></i> Create</button>  
                </div>
              </div>
            </div>
      		</div>
          <div class="card-body table-responsive p-0 mt-0" style="height: 425px; margin-top: -2em">
            <table class="table table-striped">
              <thead class="bg-warning">
                <th>Code</th>
                <th>Account Title</th>
                <th>Classification</th>
                <th>Mapping</th>
              </thead>
              <tbody id="coalist" style="cursor: pointer">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection
@section('modal')
  <div class="modal fade show" id="modal-subitem" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title"><span id="itemAction"></span> Sub Item</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <h4 id="edit-sub" style="cursor: pointer;"></h4>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="">
                      <input id="txtsubitemcode" class="form-control"  type="search" name="" placeholder="Account Code" data-toggle="tooltip" title="Sub Account Code">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="">
                      <input type="text" class="form-control validation" id="txtsubitemaccounttitle" placeholder="Account Title" data-toggle="tooltip" title="Sub Account Title">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-10">
                        <select id="cbosubitemmapping" class="select2bs4 form-control">
                          <option value="0">Select Mapping</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <button class="btn btn-primary" data-toggle="tooltip" title="Create Mapping"><i class="fas fa-external-link-alt"></i></button>
                      </div>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
          <div>
            <button id="cmddeleteSubItem" type="button" class="btn btn-danger" data-dismiss="modal" data-id="" action-id=""><i class="far fa-trash-alt"></i> Delete</button>
            <button id="cmdsaveSubItem" type="button" class="btn btn-primary" data-dismiss="modal" data-id="" action-id=""><i class="fas fa-share-square"></i> Save</button>
          </div>

        </div>
      </div>
    </div> 
  </div>

  <div class="modal fade show" id="modal-subledger" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title"><span id="subAction"></span> Sub Account</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <h4 id="edit-acc" style="cursor: pointer;"></h4>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="">
                      <input id="txtsubcode" class="form-control"  type="search" name="" placeholder="Account Code" data-toggle="tooltip" title="Sub Account Code">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="">
                      <input type="text" class="form-control validation" id="txtsubaccounttitle" placeholder="Account Title" data-toggle="tooltip" title="Sub Account Title">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-10">
                        <select id="cbosubmapping" class="select2bs4 form-control">
                          <option value="0">Select Mapping</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <button class="btn btn-primary" data-toggle="tooltip" title="Create Mapping"><i class="fas fa-external-link-alt"></i></button>
                      </div>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
          <div>
            <button id="cmddeleteSubName" type="button" class="btn btn-danger" data-dismiss="modal" data-id="" action-id=""><i class="far fa-trash-alt"></i> Delete</button>
            <button id="cmdsaveSubName" type="button" class="btn btn-primary" data-dismiss="modal" data-id="" action-id=""><i class="fas fa-share-square"></i> Save</button>
          </div>

        </div>
      </div>
    </div> 
  </div>

  <div class="modal fade show" id="modal-glaccount" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-secondary">
          <h4 class="modal-title"><span id="accAction"></span> Account Title</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <h4 id="edit-group" style="cursor: pointer;"></h4>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="">
                    <input id="txtcode" class="form-control"  type="search" name="" placeholder="Account Code" data-toggle="tooltip" title="Account Code">
                  </div>
                </div>
                <div class="form-group">
                  <div class="">
                    <input type="text" class="form-control validation" id="txtaccounttitle" placeholder="Account Title" data-toggle="tooltip" title="Account Title">
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-10">
                      <select id="cbomapping" class="select2bs4 form-control">
                        {{-- <option value="0">Select Mapping</option> --}}
                      </select>
                    </div>
                    <div class="col-md-2">
                      <button class="btn btn-primary" data-toggle="tooltip" title="Create Mapping"><i class="fas fa-external-link-alt"></i></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="divAdvOpt" class="row">
              <div class="col-md-12">
                <div id="accordion">
                                    
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="collapsed text-info" aria-expanded="false">
                    Advance  option &nbsp;<i class="fas fa-ellipsis-v"></i>
                  </a>
                    
                  <div id="collapseOne" class="panel-collapse in collapse" style="">
                    <div class="row mt-3">
                      <div class="col-md-5">
                        <div class="form-group">
                          <div class="icheck-primary d-inline mb-2">
                            <input type="radio" id="radAccTitle" name="r1">
                            <label for="radAccTitle">
                              Account Title
                            </label>
                          </div>
                          
                        </div>
                      </div>
                      <div class="col-md-7">
                        <select id="optAccTitle" class="select2bs4" disabled="">
                              <option>Select Account Type</option>
                            </select>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-md-5">
                        <div class="icheck-warning d-inline">
                          <input type="radio" id="radSubAcc" name="r1">
                          <label for="radSubAcc">
                            Sub Account
                          </label>
                        </div>
                      </div>
                      <div class="col-md-7">
                        <select id="optSubAcc" class="select2bs4" disabled="">
                            <option>Select Account Title </option>
                        </select>
                      </div>
                    </div>    
                    <div class="row mt-2">
                      <div class="col-md-12 text-right">
                        <button class="btn btn-outline-secondary btn-sm" id="btnapply">Apply</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
                
            </div>  
          </div>
          

          

          <div class="row">
            <div class="col-md-6">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <div class="col-md-6 text-right">
              <button id="cmddeleteAccName" type="button" class="btn btn-danger" data-dismiss="modal" data-id="" action-id="">
                <i class="far fa-trash-alt"></i> Delete
              </button>
              <button id="cmdsaveAccName" type="button" class="btn btn-primary" data-dismiss="modal" data-id="" action-id="">
                <i class="fas fa-share-square"></i> Save
              </button>
            </div>
          </div>
        </div>
          
      </div>
    </div> 
  </div>


  <div class="modal fade show" id="modal-createType" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title"><span id="typeAction">Create </span>Account Type</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body">
            <div class="form-group">
              <div class="">
                <input id="txttype" class="form-control" autocomplete="false" type="text" name="" placeholder="Account Type" data-toggle="tooltip" title="Account Type">
              </div>
            </div>
            <div class="form-group">
              <div class="">
                <select id="cboclass" class="select2bs4" name="" data-toggle="tooltip" title="Account Type">
                  <option value="0">Select Classification</option>
                  @php
                    $acc_class = DB::table('acc_coaclass')->get()
                  @endphp
                  @foreach($acc_class as $class)
                    <option value="{{$class->id}}">{{$class->classification}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-md-8">
                  <input type="number" class="form-control validation" id="txtsort" placeholder="Sequence" data-toggle="tooltip" title="Sequence">
                </div>
                <div class="col-md-4">
                  <button class="btn btn-primary btn-block">View Sequence</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
          <div>
            <button id="cmddelAccType" type="button" class="btn btn-danger" data-dismiss="modal" data-id="" action-id="">
                <i class="far fa-trash-alt"></i> Delete
              </button>
            <button id="cmdsaveAccType" type="button" class="btn btn-primary" data-dismiss="modal" data-id="" action-id="">Save</button>
          </div>

        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  
@endsection
{{-- @section('jsUP')
  <style type="text/css">

    table td {
      position: relative;
    }

    table td input {
      position: absolute;
      display: block;
      top:0;
      left:0;
      margin: 0;
      height: 100%;
      width: 100%;
      border: none;
      padding: 10px;
      box-sizing: border-box;
    }
  </style>
@endsection --}}
@section('js')
  <script type="text/javascript">
    $(document).ready(function(){

      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      // $('.select2bs4').select2();

      loadcoa();
      loadcoagroup();


      function loadcoa()
      {
        var accname = $('#txtaccname').val();
        var groupid = $('#cbofiltergroup').val();

        $.ajax({
          url:"{{route('loadchart')}}",
          method:'GET',
          data:{
            groupid:groupid,
            accname:accname
          },
          dataType:'json',
          success:function(data)
          {
            $('#coalist').html(data.coalist);
          }
        });
      }

      function loadcoagroup()
      {
        $.ajax({
          url:"{{route('loadgroups')}}",
          method:'GET',
          data:{

          },
          dataType:'json',
          success:function(data)
          {
            $('#cbofiltergroup').html(data.grouplist);
          }
        }); 
      }

      $(document).on('change', '#cbofiltergroup', function(){
        loadcoa();
      });

      $(document).on('keyup', '#txtaccname', function(){
        loadcoa();
      });

      $(document).on('click', '#btncreatetype', function(){
        $('#typeAction').text('Create ');
        $('#cmddelAccType').hide();
        $('#txttype').val('');
        $('#cboclass').val(0);
        $('#cboclass').trigger('change');
        $('#txtsort').val('');
        $('#modal-createType').modal('show');
      });

      $(document).on('mouseenter', '#coalist tr', function(){
        $(this).addClass('bg-primary');
      });

      $(document).on('mouseout', '#coalist tr', function(){
        $(this).removeClass('bg-primary');
      });


      $(document).on('click', '#coalist tr', function(){

        if($(this).attr('data-value') == 'group')
        {

          var gid = $(this).attr('data-id');


          $.ajax({
            url:"{{route('loadgroup')}}",
            method:'GET',
            data:{
              gid:gid
            },
            dataType:'json',
            success:function(data)
            {
              $('#accAction').text('Create ');
              $('#txtcode').val(data.code);
              $('#edit-group').html(data.groupid);
              $('#edit-group').attr('data-id', data.gid)
              $('#cbomapping').html(data.maplist);
              $('#acctitle').text(data.acctitle);
              $('#cmddeleteAccName').hide();
              $('#divAdvOpt').hide();
              $('#edit-group').show();
              $('#cmddelAccType').show();
              $('#modal-glaccount').modal('show');
            }
          });
        }
        else if($(this).attr('data-value') == 'acc')
        {
          var accid = $(this).attr('data-id');
          var accname = $(this).text();

          $.ajax({
            url:"{{route('loadaccname')}}",
            method:'GET',
            data:{
              accid:accid
            },
            dataType:'json',
            success:function(data)
            {
              $('#subAction').text('Create ');
              $('#edit-acc').show();
              $('#edit-acc').attr('data-id', data.accid);
              $('#edit-acc').html(data.accname);
              $('#txtsubcode').val(data.headcode);
              $('#cbosubmapping').html(data.maplist);
              $('#cmddeleteAccName').show();
              $('#cmddeleteSubName').hide();
              $('#txtsubaccounttitle').val('');
              $('#cbosubmapping').val(0);
              $('#cbosubmapping').trigger('change');
              $('#divAdvOpt').show();
              $('#radSubAcc').prop('checked', false);
              $('#radAccTitle').prop('checked', false);
              $('#optAccTitle').prop('disabled', true);
              $('#optSubAcc').prop('disabled', true);
              $('#modal-subledger').modal('show');
            }
          });
        }
        else if($(this).attr('data-value') == 'sub')
        {
          var subid = $(this).attr('data-id');

          $.ajax({
            url:"{{route('loadsubname')}}",
            method:'GET',
            data:{
              subid:subid
            },
            dataType:'json',
            success:function(data)
            {
              $('#edit-sub').show();
              $('#edit-sub').html(data.subname);
              $('#edit-sub').attr('data-id', data.subid);
              $('#cmddeleteSubName').show();
              $('#txtsubitemcode').val(data.code);
              $('#itemAction').text('Create ');
              $('#cmddeleteSubItem').hide();
              $('#txtsubitemaccounttitle').val('');
              $('#cbosubitemmapping').val(0);
              $('#cbosubitemmapping').trigger('change');    
              $('#cbosubitemmapping').html(data.maplist);
              $('#modal-subitem').modal('show');
            }
          });
        }
        else
        {
          var subitemid = $(this).attr('data-id');
          $('#cmdsaveSubItem').attr('data-id', subitemid);

          $.ajax({
            url:"{{route('editsubitem')}}",
            method:'GET',
            data:{
              subitemid:subitemid
            },
            dataType:'json',
            success:function(data)
            {
              $('#itemAction').text('Edit ')
              $('#edit-sub').hide();
              $('#txtsubitemcode').val(data.code);
              $('#txtsubitemaccounttitle').val(data.accname);
              $('#cbosubitemmapping').html(data.maplist);
              $('#cmddeleteSubItem').show();
              $('#modal-subitem').modal('show');
            }
          });
        }
      });

      $(document).on('click', '#edit-group', function(){
        var groupid = $(this).attr('data-id');

        $.ajax({
          url:"{{route('editacctype')}}",
          method:'GET',
          data:{
            groupid:groupid
          },
          dataType:'json',
          success:function(data)
          {
            $('#txttype').val(data.group);
            $('#cboclass').val(data.classid);
            $('#cboclass').trigger('change');
            $('#txtsort').val(data.sortid);


            $('#typeAction').text('Edit ');
            $('#modal-createType').modal('show');
          }
        });


        
      });

      $(document).on('click', '#cmdsaveAccType', function(){
        var acctype = $('#txttype').val();
        var classid = $('#cboclass').val();
        var sortid = $('#txtsort').val();

        console.log($('#typeAction').text());

        if($('#typeAction').text() == 'Create ') //CREATE
        {
          $.ajax({
            url:"{{route('saveacctype')}}",
            method:'GET',
            data:{
              acctype:acctype,
              classid:classid,
              sortid:sortid
            },
            dataType:'json',
            success:function(data)
            {
              if(data == 0)
              {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'error',
                  title: 'Account Type already exist.'
                })
              }
              else
              {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'success',
                  title: 'Account Type successufully created.'
                })

                loadcoa();
              }
            }
          });
        }
        else //UPDATE
        {
          var groupid = $('#edit-group').attr('data-id');
          
          $.ajax({
            url:"{{route('updateacctype')}}",
            method:'GET',
            data:{
              groupid:groupid,
              acctype:acctype,
              classid:classid,
              sortid:sortid
            },
            dataType:'json',
            success:function(data)
            {
              if(data.return == 0)
              {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'error',
                  title: 'Account Type already exist.'
                })
              }
              else
              {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'success',
                  title: 'Account Type successufully updated.'
                })

                loadcoa();
                $('#edit-group').html(data.acctype);
              }
            }
          });
        }
      });

      $(document).on('click', '#cmdsaveAccName', function(){
        var code = $('#txtcode').val();
        var accname = $('#txtaccounttitle').val();
        var mapid = $('#cbomapping').val();
        var group = $('#edit-group').text();
        var gid = $('#edit-group').attr('data-id');

        group = group.trim();
        console.log(mapid);

        if($('#accAction').text() == 'Create ')
        {
          $.ajax({
            url:"{{route('saveaccname')}}",
            method:'GET',
            data:{
              code:code,
              accname:accname,
              mapid:mapid,
              group:group,
              gid:gid
            },
            dataType:'json',
            success:function(data)
            {
              if(data == 0)
              {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'error',
                  title: 'Account Title already exist.'
                })
              }
              else
              {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'success',
                  title: 'Account Title successufully created.'
                })

                loadcoa();
              }
            }
          }); 
        }
        else
        {
          var accid = $('#edit-acc').attr('data-id');

          $.ajax({
            url:"{{route('updateaccname')}}",
            method:'GET',
            data:{
              accid:accid,
              code:code,
              accname:accname,
              mapid:mapid
            },
            dataType:'json',
            success:function(data)
            {
              if(data.return == 0)
              {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'error',
                  title: 'Account Title already exist.'
                })
              }
              else
              {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'success',
                  title: 'Account Title successufully updated.'
                })

                loadcoa();
                $('#edit-acc').html(data.accname);
              }
            }
          }); 
        }
      });

      $(document).on('click', '#edit-acc', function(){
        var accid = $(this).attr('data-id')

        $.ajax({
          url:"{{route('editaccname')}}",
          method:'GET',
          data:{
            accid:accid
          },
          dataType:'json',
          success:function(data)
          {
            $('#accAction').text('Edit ');
            $('#edit-group').hide();
            $('#txtcode').val(data.code)
            $('#txtaccounttitle').val(data.accname);
            $('#cbomapping').html(data.maplist);
            $('#cbomapping').val(data.mapid);
            $('#cbomapping').trigger('change');
            $('#edit-acc').attr('data-id', data.accid);
            $('#optAccTitle').html(data.typelist);
            $('#optSubAcc').html(data.acclist);

            $('#modal-glaccount').modal('show');
          }
        });

      })

      $(document).on('click', '#cmdsaveSubName', function(){
        var code = $('#txtsubcode').val();
        var accname = $('#txtsubaccounttitle').val();
        var mapid = $('#cbosubmapping').val();
        var headid = $('#edit-acc').attr('data-id');

        console.log(headid);

        if($('#subAction').text() == 'Create ')
        {
          $.ajax({
            url:"{{route('savesubname')}}",
            method:'GET',
            data:{
              code:code,
              accname:accname,
              mapid:mapid,
              headid:headid
            },
            dataType:'json',
            success:function(data)
            {
              if(data == 0)
              {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'error',
                  title: 'Sub Account already exist.'
                })
              }
              else
              {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'success',
                  title: 'Sub Account successufully created.'
                })

                loadcoa();
              }
            }
          }); 
        }
        else
        {
          var subid = $('#edit-sub').attr('data-id');

          $.ajax({
            url: '{{route('updatesubname')}}',
            type: 'GET',
            dataType: 'json',
            data: 
            {
              subid:subid,
              code:code,
              accname:accname,
              mapid:mapid,
            },
            success:function(data)
            {
              if(data.return == 0)
              {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'error',
                  title: 'Sub Account already exist.'
                })
              }
              else
              {
                $('#edit-sub').html(data.subname);
                loadcoa();

                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'success',
                  title: 'Sub Account successufully updated.'
                })
              }
            }
          })
        }

      });

      $(document).on('click', '#cmddelAccType', function(){
        var dataid = $('#edit-group').attr('data-id');



        Swal.fire({
          title: 'Are you sure?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url:"{{route('deleteacctype')}}",
              method:'GET',
              data:{
                dataid:dataid
              },
              dataType:'',
              success:function(data)
              {
                if(data == 0)
                {
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                  })

                  Toast.fire({
                    type: 'error',
                    title: 'You cannot delete the selected Account Type.'
                  });
                }
                else
                {
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                  })

                  Toast.fire({
                    type: 'success',
                    title: 'Account Type successufully deleted.'
                  })


                  $('#modal-createType').modal('hide');
                  $('#modal-glaccount').modal('hide');
                  
                  loadcoa();
                }
              }
            });
          }
        });
      });

      $(document).on('click', '#radAccTitle', function(){
        if($(this).prop('checked') == true)
        {
          console.log('check');
          $('#optAccTitle').prop('disabled', false);
          $('#optSubAcc').prop('disabled', true);
        }
      });

      $(document).on('click', '#radSubAcc', function(){
        if($(this).prop('checked') == true)
        {
          $('#optSubAcc').prop('disabled', false);
          $('#optAccTitle').prop('disabled', true);
        }
      });

      $(document).on('click', '#btnapply', function(){
        var accid = $('#edit-acc').attr('data-id');
        var acctypeid = $('#optAccTitle').val();
        var acctitle = $('#optSubAcc').val();

        var optswitch = 0;

        if($('#radAccTitle').prop('checked') == true)
        {
          optswitch = 1;
        }
        else if($('#radSubAcc').prop('checked') == true)
        {
          optswitch = 2;
        }

        $.ajax({
          url:"{{route('switchacc')}}",
          method:'GET',
          data:{
            accid:accid,
            acctypeid:acctypeid,
            acctitle:acctitle,
            optswitch:optswitch
          },
          dataType:'json',
          success:function(data)
          {

            if(data == 1)
            {
              const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                onOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
              })

              Toast.fire({
                type: 'success',
                title: 'Chart of account successufully updated.'
              });

              loadcoa();
              $('#modal-glaccount').modal('hide');
              $('#modal-subledger').modal('hide');
            }
            else
            {
              const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                onOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
              })

              Toast.fire({
                type: 'error',
                title: 'Please fillup all the required fields.'
              });              
            }


            
          }
        });
      });

      $(document).on('click', '#edit-sub', function(){
        var subid = $(this).attr('data-id');

        $.ajax({
          url: '{{route('editsubname')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            subid:subid
          },
          success:function(data)
          {
            $('#edit-acc').hide();
            $('#subAction').text('Edit ');
            $('#txtsubcode').val(data.code);
            $('#txtsubaccounttitle').val(data.accname);
            $('#cbosubmapping').html(data.maplist);
            $('#modal-subledger').modal('show');
          }
        })
      });

      $(document).on('click', '#cmddeleteAccName', function(){
        accid = $('#edit-acc').attr('data-id');

        Swal.fire({
          title: 'Delete Account name?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: '{{route('deleteaccname')}}',
              type: 'GET',
              dataType: '',
              data: {
                accid:accid
              },
              success:function(data)
              {
                if(data == 0) 
                {
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                  })

                  Toast.fire({
                    type: 'error',
                    title: 'There are Sub Items in this Account Title or It is already been used in Item Classification.'
                  });
                }
                else
                {
                  $('#modal-glaccount').modal('hide');
                  $('#modal-subledger').modal('hide');
                  loadcoa();

                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                  })

                  Toast.fire({
                    type: 'success',
                    title: 'Account title has been deleted.'
                  });
                }
              }
            })    
          }
        })        
      })

      $(document).on('click', '#cmddeleteSubName', function(){
        var subid = $('#edit-sub').attr('data-id');

        Swal.fire({
          title: 'Delete Sub Account?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: '{{route('deletesubname')}}',
              type: 'GET',
              dataType: '',
              data: {
                subid:subid
              },
              success:function(data)
              {
                if(data == 0) 
                {
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                  })

                  Toast.fire({
                    type: 'error',
                    title: 'There are Sub Items in this Sub Account or It is already been used in Item Classification.'
                  });
                }
                else
                {
                  $('#modal-glaccount').modal('hide');
                  $('#modal-subitem').modal('hide');
                  loadcoa();

                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                  })

                  Toast.fire({
                    type: 'success',
                    title: 'Account title has been deleted.'
                  });
                }
              }
            })    
          }
        }) 
      })

      $(document).on('click', '#cmdsaveSubItem', function(){
        var subid = $('#edit-sub').attr('data-id');
        var code = $('#txtsubitemcode').val();
        var accname = $('#txtsubitemaccounttitle').val();
        var mapid = $('#cbosubitemmapping').val();

        if($('#itemAction').text() == 'Create ')
        {
          $.ajax({
            url: '{{route('savesubitem')}}',
            type: 'GET',
            dataType: '',
            data:{
              code:code,
              accname:accname,
              mapid:mapid,
              subid:subid
            },
            success:function(data)
            {
              if(data == 0)
              {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'error',
                  title: 'Sub Item already exist.'
                });
              }
              else
              {
                loadcoa();

                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'success',
                  title: 'Sub Item successfully saved.'
                });
              }
            }
          });
        }
        else
        {
          var itemid = $('#cmdsaveSubItem').attr('data-id');
          console.log(itemid);
          $.ajax({
            url: '{{route('updatesubitem')}}',
            type: 'GET',
            dataType: '',
            data:{
              itemid:itemid,
              code:code,
              accname:accname,
              mapid:mapid
            },
            success:function(data)
            {
              if(data == 1)
              {
                loadcoa();
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'success',
                  title: 'Sub Item successfully saved.'
                });
              }
              else
              {
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
                })

                Toast.fire({
                  type: 'error',
                  title: 'Sub Item already exist.'
                });
              }
            }
          });
        }
      });

      $(document).on('click', '#cmddeleteSubItem', function(){
        var subitemid = $('#cmdsaveSubItem').attr('data-id');


        Swal.fire({
          title: 'Delete Sub Item?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: '{{route('deletesubitem')}}',
              type: 'GET',
              dataType: 'json',
              data:{
                subitemid:subitemid
              },
              success:function(data)
              {
                if(data == 0)
                {
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                  })

                  Toast.fire({
                    type: 'error',
                    title: 'Sub Item is already been used in Item Classification.'
                  });
                }
                else
                {
                  loadcoa();
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                  })

                  Toast.fire({
                    type: 'success',
                    title: 'Sub Item has been deleted.'
                  }); 
                }
              }
            });
          }
        })









        
        
      });

    });
  </script>
@endsection
