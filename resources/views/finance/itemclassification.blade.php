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
            <li class="breadcrumb-item active">Item Classification</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
  	<div class="main-card card">
  		<div class="card-header text-lg bg-info">
  			<!-- Item Classification -->
        <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            <b>ITEM CLASSIFICATION</b></h4>
  		</div>
  		<div class="card-body">
        <div class="row">
          <div class="col-8">
            
          </div>
          <div class="col-4">
            <div class="input-group mb-3">
              <input id="txtsearchclassification" type="text" class="form-control" placeholder="Search Classification" onkeyup="this.value = this.value.toUpperCase();">
              <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <div class="input-group-append">
                  <button class="btn btn-success modal-class" id="btnclassfication-new" data-toggle="modal" data-target="#modal-classification">New</button>
                </div>
              </div>
          </div>

        </div>
  			<div class="row">
          <div class="col-12">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead class="bg-warning">
                  <tr>
                    <th>DESCRIPTION</th>
                    <th>GL ACCOUNT</th>
                    <th></th>
                  </tr>  
                </thead> 
                <tbody id="class-list">
                  
                </tbody>             
              </table>
            </div>
          </div>          
        </div>
  		</div>
  	</div>
  </section>
@endsection

@section('modal')
  <div class="modal fade show" id="modal-classification" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Item Classification - <span id="action"></span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="class-desc" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control validation" id="class-desc" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-glid" class="col-sm-2 col-form-label">GL Account</label>
                <div class="col-sm-10">
                  <select class="form-control select2bs4" id=class-glid>
                    <option></option>
                  </select>
                </div>
              </div>
              
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="saveClass" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>


  <div class="modal fade show" id="modal-classification-edit" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Item Classification - Edit</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="class-desc-edit" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="class-desc-edit" data-id="" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-glid-edit" class="col-sm-2 col-form-label">GL Account</label>
                <div class="col-sm-10">
                  <select class="form-control select2bs4" id=class-glid-edit>
                    <option></option>
                  </select>
                </div>
              </div>
              
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="updateClass" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  
@endsection

@section('js')
  <script type="text/javascript">
    
    $(document).ready(function(){
      var validateCount = 0;

      searchclassfication();

      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      function searchclassfication(sclass='')
      {
        $.ajax({
          url:"{{route('search_classification')}}",
          method:'GET',
          data:{
            sclass:sclass
          },
          dataType:'json',
          success:function(data)
          {
            $('#class-list').html(data.output);
          }
        });
      }

      function loadgl(glid = '')
      {

        if(glid == '')
        {
          $.ajax({
            url:"{{route('loadGL')}}",
            method:'GET',
            data:{
              glid:glid
            },
            dataType:'json',
            success:function(data)
            {
              $('#class-glid').html(data.output);
            }
          });        
        }
        else
        {
          $.ajax({
            url:"{{route('loadGL')}}",
            method:'GET',
            data:{
              glid:glid
            },
            dataType:'json',
            success:function(data)
            {
              $('#class-glid-edit').html(data.output);
            }
          }); 
        }
      }

      function validateInput()
      {
        console.log($('.validation').val());
        if($('.validation').val() == '')
        {
          $('.validation').removeClass('is-valid')
          $('.validation').addClass('is-invalid');
          validateCount += 1;
        }
        else
        {
          $('.validation').removeClass('is-invalid');
          $('.validation').addClass('is-valid');
          validateCount -= 1;
        }

        console.log(validateCount);
        if(validateCount > 0)
        {
          $('#saveClass').prop('disabled', true);
        }
        else
         {
          $('#saveClass').prop('disabled', false);
         } 


      }

      function validateInputEdit()
      {
        validateCount = 0;
        console.log($('#class-desc-edit').val());
        if($('#class-desc-edit').val() == '')
        {
          $('#class-desc-edit').removeClass('is-valid')
          $('#class-desc-edit').addClass('is-invalid');
          validateCount += 1;
        }
        else
        {
          $('#class-desc-edit').removeClass('is-invalid');
          $('#class-desc-edit').addClass('is-valid');
          validateCount -= 1;
        }

        console.log(validateCount);
        if(validateCount > 0)
        {
          $('#updateClass').prop('disabled', true);
        }
        else
         {
          $('#updateClass').prop('disabled', false);
         } 


      }

      $(document).on('keyup', '#txtsearchclassification', function(){
        var sclass = $(this).val();
        searchclassfication(sclass);
      });

      $(document).on('click', '.modal-class', function(){
        validateInput();
        loadgl();
      });

     $(document).on('keyup', '#class-desc', function(){
      validateInput();
     });

     $(document).on('keyup', '#class-desc-edit', function(){
      validateInputEdit();
     });

     $(document).on('click', '#btnclassfication-new', function(){
      $('#action').text('New');
     });

     $(document).on('click', '#saveClass', function(){
      var classdesc = $('#class-desc').val();
      var glid = $('#class-glid').val();

        $.ajax({
          url:"{{route('saveClass')}}",
          method:'GET',
          data:{
            classdesc:classdesc,
            glid:glid
          },
          dataType:'',
          success:function(data)
          {
            searchclassfication();
          }
        });     

      });

      $(document).on('click', '#btnclass-edit', function(){
        var glid = $(this).attr('data-gl');
        var classid = $(this).attr('data-id');
        // loadgl(glid);

        $.ajax({
          url:"{{route('viewClass')}}",
          method:'GET',
          data:{
            classid:classid,
            glid:glid
          },
          dataType:'json',
          success:function(data)
          {
            console.log(data);
            $('#class-desc-edit').val(data.desc);
            $('#class-glid-edit').html(data.gl);
            $('#class-desc-edit').attr('data-id', data.classid);
          }
        });     

     });

      $(document).on('click', '#updateClass', function(){
        var classid = $('#class-desc-edit').attr('data-id');
        var desc = $('#class-desc-edit').val();
        var glid = $('#class-glid-edit').val();

        $.ajax({
          url:"{{route('updateClass')}}",
          method:'GET',
          data:{
            classid:classid,
            desc:desc,
            glid:glid
          },
          dataType:'',
          success:function(data)
          { 
            // alert('Classification successfully updated.');
            // Swal.fire('Classification successfully updated.');


            const Toast = Swal.mixin({
            toast: true,
            position: 'top',
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
              title: 'Classification successfully updated.'
            })

            searchclassfication();
          }
        });           
      });

      $(document).on('click', '#btndelete', function(){
        var classid = $(this).attr('data-id')
        
        Swal.fire({
          title: 'Delete selected Classification?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"{{route('delClass')}}",
              method:'GET',
              data:{
                classid:classid,
              },
              dataType:'',
              success:function(data)
              {
                Swal.fire(
                  'Deleted!',
                  'Classification has been deleted.',
                  'success'
                );

                searchclassfication();
              }
            }); 
          }
        });

      });




    });

  </script>
@endsection