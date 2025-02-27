@extends('finance.layouts.app')

@section('content')
	
  <section class="content pt-0">
    <h4><b>ALLOW NO DOWNPAYMENT</b></h4>
  		
    <div class="row">
      <div class="col-md-6"></div>
      <div class="col-md-6">
        <div class="input-group">
          <input id="filter" type="" class="form-control" placeholder="Search">
          <div class="input-group-append">
            <div class="input-group-text">
              <span><i class="fas fa-search"></i></span>
            </div>
              
          </div>
          <div class="input-group-append">
            <button class="btn btn-primary" id="btn-selstud" data-toggle="tooltip" title="Select a student to allow no DP">
              <i class="far fa-plus-square"></i> SELECT STUDENT
            </button>
          </div>
        </div>
      </div>
    </div>
    <hr>
		<div class="card-body table-responsive p-0 DataTables" style="height:380px">
      <table class="table table-striped table-sm text-sm">
        <thead>
          <tr>
            <th>LRN</th>
            <th>ID NO.</th>
            <th>STUDENT NAME</th>
            <th>GRADE LEVEL</th>
            <th>GRANTEE</th>
            <th>STATUS</th>
            <th></th>
          </tr>  
        </thead> 
        <tbody id="nodp-list" style="cursor: pointer">
          
        </tbody>             
      </table>
		</div>
  	
  </section>
@endsection

@section('modal')
  <div class="modal fade show" id="modal-student" data-backdrop="static" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-lg mt-5">
      <div class="modal-content">
        <div id="modal-adj-header" class="modal-header bg-primary">
          <h4 class="modal-title">SELECT STUDENT</h4> 
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <label> STUDENT LIST</label>
              <select id="studlist" class="form-control select2bs4"> 
                
              </select>
            </div>
          </div>
          <div class="row mt-2 bg-light">
            <div class="col-md-12 mt-1">
              <button id="btnallow" class="btn btn-outline-primary btn-block">ALLOW NO DP</button>
              <button id="btnremove" class="btn btn-outline-danger btn-block" hidden="">REMOVE</button>
            </div>
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>


  
@endsection

@section('js')
  <script>
    
    $(document).ready(function(){

      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      loadstud();
      searchnodp();

      function loadstud()
      {
        var filter = $('#filter').val();

        $.ajax({
          url:"{{route('loadstudnodp')}}",
          method:'GET',
          data:{
          },
          dataType:'',
          success:function(data)
          {
            $('#studlist').html(data);
          }
        });          
      }

      function searchnodp()
      {
        var filter = $('#filter').val();
        
        $.ajax({
          url:"{{route('searchnodp')}}",
          method:'GET',
          data:{
            filter:filter
          },
          dataType:'',
          success:function(data)
          {
            $('#nodp-list').html(data);
          }
        }); 
      }


      $(document).on('click', '#btn-selstud', function(){
        $('#modal-student').modal('show');
      });

      $(document).on('click', '#btnallow', function(){
        var studid = $('#studlist').val();

        if(studid != 0)
        {
          $.ajax({
            url:"{{route('appendnodp')}}",
            method:'GET',
            beforeSend: function(){
              $(this).prop('disabled', true);
            },
            data:{
              studid:studid
            },
            dataType:'',
            success:function(data)
            {

              const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: false,
                onOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
              })

              Toast.fire({
                type: 'success',
                title: 'Student successfully added.'
              })

              $('#modal-student').modal('hide');   
              loadstud();
              searchnodp();
            }
          });  
        }
        else
        {
          const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: false,
            onOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })

          Toast.fire({
            type: 'warning',
            title: 'Please select a student.'
          })
        }
      });

      $(document).on('click', '.removestud', function(){
        var studid = $(this).attr('data-id');

        $.ajax({
          url:"{{route('removenodp')}}",
          method:'GET',
          beforeSend: function(){
            $(this).prop('disabled', true);
          },
          data:{
            studid:studid
          },
          dataType:'',
          success:function(data)
          {
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: false,
              onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })

            Toast.fire({
              type: 'success',
              title: 'Student successfully removed.'
            })

            // $('#modal-student').modal('hide');   
            loadstud();
            searchnodp();
          }
        });  
      });

      $(document).on('keyup', '#filter', function(){
        searchnodp();
      })

    });

  </script>
@endsection