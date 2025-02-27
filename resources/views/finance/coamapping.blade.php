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
            <li class="breadcrumb-item active">Mapping</li>
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
              <div class="col-md-6">
                <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
                  <b>Chart of Accounts Mapping</b>
                </h4>
              </div>
              <div class="col-md-2">
              </div>
              <div class="col-md-4 input-group">
                <input type="search" id="txtfilter" class="form-control filter" placeholder="Search">
                <div class="input-group-append">
                  <button id="btncreatemap" class="btn btn-warning"><i class="far fa-plus-square"></i> Create</button>  
                </div>
              </div>
            </div>
      		</div>
          <div class="card-body table-responsive p-0 mt-0" style="height: 425px; margin-top: -2em">
            <table class="table table-striped">
              <thead class="bg-warning">
                <th>Description</th>
              </thead>
              <tbody id="maplist" style="cursor: pointer">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection
@section('modal')
  <div class="modal fade show" id="modal-mapping" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title"><span id="action"></span> Chart of Account Mapping - <span id="mapAction"></span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Description</label> 
            <input type="search" name="" id="txtdesc" class="form-control">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
          <div>
            <button id="cmdmapdelete" type="button" class="btn btn-danger" data-dismiss="modal" data-id="" action-id=""><i class="far fa-trash-alt"></i> Delete</button>
            <button id="cmdmapsave" type="button" class="btn btn-primary" data-dismiss="modal" data-id="" action-id=""><i class="fas fa-share-square"></i> Save</button>
          </div>

        </div>
      </div>
    </div> 
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

      loadmap();


      function loadmap()
      {
        var filter = $('#txtfilter').val();

        $.ajax({
          url:"{{route('loadmapping')}}",
          method:'GET',
          data:{
            filter:filter,
          },
          dataType:'json',
          success:function(data)
          {
            $('#maplist').html(data.list);
          }
        });
      }
      

      $(document).on('click', '#btncreatemap', function(){
        $('#txtdesc').val('');
        $('#mapAction').text('Create');
        $('#cmdmapdelete').hide();
        $('#modal-mapping').modal('show');
      });

      $(document).on('click', '#cmdmapsave', function(){
        var desc = $('#txtdesc').val();

        console.log($('#mapAction').text());

        if($('#mapAction').text() == 'Create') 
        {
          $.ajax({
            url:"{{route('savemapping')}}",
            method:'GET',
            data:{
              desc:desc
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
                  title: 'Map name already exist.'
                })
              }
              else
              {

                loadmap() 
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
                  title: 'Map name successfully created.'
                })
              }

              
            }
          });
        }
        else
        {
          var mapid = $('#cmdmapsave').attr('data-id');
          $.ajax({
            url:"{{route('updatemapping')}}",
            method:'GET',
            data:{
              mapid:mapid,
              mapname:desc
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
                  title: 'Map name already exist.'
                })
              }
              else
              {

                loadmap();  
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
                  title: 'Map name updated.'
                })

                
              }

              
            }
          }); 
        }
      });

      $(document).on('mouseenter', '#maplist tr', function(){
        $(this).addClass('bg-secondary');
      });

      $(document).on('mouseout', '#maplist tr', function(){
        $(this).removeClass('bg-secondary');
      });

      $(document).on('click', '#maplist tr', function(){
        var mapid = $(this).attr('data-id');

        $('#cmdmapsave').attr('data-id', mapid);

        $.ajax({
          url:"{{route('editmapping')}}",
          method:'GET',
          data:{
            mapid:mapid
          },
          dataType:'json',
          success:function(data)
          {
            $('#txtdesc').val(data.mapname);
            $('#cmdmapdelete').show();
            $('#mapAction').text('Edit')
            $('#modal-mapping').modal('show');
          }
        });
      })

      $(document).on('click', '#cmdmapdelete', function(){
        var mapid = $('#cmdmapsave').attr('data-id');

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
              url: '{{route('deletemapping')}}',
              type: 'GET',
              dataType: '',
              data: {
                mapid:mapid
              },
              success:function(data)
              {
                if(data == 0)
                {
                  loadmap();  
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
                    type: 'warning',
                    title: 'Map cannot be deleted.'
                  })
                }
                else
                {
                  loadmap();  
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
                    title: 'Map has beed deleted.'
                  })
                }
              }
            })
          }
        })
      });

    });
  </script>
@endsection
