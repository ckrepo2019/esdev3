@extends('finance.layouts.app')

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <!-- <h1>Finance</h1> -->
          
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Tuition Entry</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
      	
        <div class="card col-md-12 p-0">
      		<div class="card-header text-lg bg-info">
      			<!-- <h3 class="card-title">Tuition Entry</h3> -->
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            <b>TUITION ENTRY</b></h4>
      		</div>
      		<div class="card-body">
            <div class="row">
              <div class="form-group row col-md-4">
                <label for="glevel" class="col-sm-5 col-form-label">Grade Level</label>
                <div class="col-sm-7">
                  <select id="glevel" name="studid" class="text-secondary form-control select2bs4 updq" value="">
                    @foreach(App\FinanceModel::loadGlevel() as $glevel)
                      <option value="{{$glevel->id}}">{{$glevel->levelname}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group row col-md-3">
                <label for="semester" class="col-sm-3 col-form-label">Sem</label>
                <div class="col-sm-9">
                  <select id="semester" class="text-secondary form-control select2bs4 updq" value="" disabled="">
                    
                    @foreach(App\FinanceModel::getSem() as $sem)
                        
                        @if(App\FinanceModel::getSemID() == $sem->id)
                          <option selected value="{{$sem->id}}">{{$sem->semester}}</option>
                        @else
                          <option value="{{$sem->id}}">{{$sem->semester}}</option>
                        @endif

                    @endforeach

                  </select>
                </div>
              </div>
              <div class="form-group row col-md-3">
                <label for="sy" class="col-sm-2 col-form-label">SY</label>
                <div class="col-sm-8">
                  <select id="sy" class="text-secondary form-control select2bs4 updq" value="">
                    @foreach(App\FinanceModel::getSY() as $sy)
                      @if(App\FinanceModel::getSYID() == $sy->id)
                        <option selected value="{{$sy->id}}">{{$sy->sydesc}}</option>
                      @else
                        <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-1">
                <button id="btn-search" class="btn btn-primary">SEARCH</button>
              </div>
            </div>  
          </div>
          <div class="card-body">
            <div id="card-lists" class="row">
              
            </div>
          </div>
        </div>
      </div>


      <div class="row">

        <div class="col-8">
          <div class="card card-info">
            <div class="card-header bg-primary">
              <h3 class="card-title">Student List</h3>
            </div>
            <div class="card-body table-responsive p-0" style="height: 330px">
              <table class="table table-striped">
                <thead class="bg-warning">
                  <tr>
                    <th class="p-1"></th>
                    <th>Name</th>
                    <th>Section</th>
                    <th class="" id="table-desc">Grantee</th>
                    {{-- <th class="text-center">Tuition</th> --}}
                  </tr>
                </thead>
                <tbody id="studlists">
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>

      <div class="col-4">
        <div class="card card-info">
          <div class="card-header bg-success">
            <h3 class="card-title">Payment Particulars</h3>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-striped">
              <thead class="bg-warning">
                <tr>
                  <th>Particulars</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody id="tuitiondetail">
                
              </tbody>
            </table>
              
            
          </div>
        </div>
      </div>
        
      </div>

      

    </div>

    

    </div>

  </section>
@endsection

@section('modal')

  <div class="modal fade show" id="modal-class-new" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Payment Classification - Add</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="modal-classification" class="col-sm-4 col-form-label">Classification</label>
                <div class="col-sm-8">
                  <select id="modal-classification" class="form-control">
                    
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="modal-classification" class="col-sm-4 col-form-label">Mode of payment</label>
                <div class="col-sm-8">
                  <select id="modal-mop" class="form-control">
                    
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
          <button id="savePayClass" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

 

  

@endsection


@section('js')
  <script type="text/javascript">
    
    $(document).ready(function(){

      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });


      $(document).on('change', '#glevel', function(){
        if($(this).val() == 14 || $(this).val() == 15)
        {
          $('#semester').prop('disabled', false);
        }
        else
        {
          $('#semester').prop('disabled', true); 
        }
      });

      $(document).on('click', '#btn-search', function(){
        var levelid = $('#glevel').val();
        var semid = $('#semester').val();
        var syid = $('#sy').val();

        // console.log('this');

        $.ajax({
          url:"{{route('loadTsetup')}}",
          method:'GET',
          data:{
            levelid:levelid,
            semid:semid,
            syid:syid    
          },
          dataType:'json',
          success:function(data)
          {
            $('#card-lists').html(data.lists);
          }
        });
      });

      $(document).on('click', '.tui-card', function(){
        $('.tui-card').removeClass('bg-info');
        $(this).addClass('bg-info');

        var tuitionid = $(this).attr('data-id');
        $.ajax({
          url:"{{route('loadTstudent')}}",
          method:'GET',
          data:{
            tuitionid:tuitionid
          },
          dataType:'json',
          success:function(data)
          {
            console.log(data.studlist);
            $('#studlists').html(data.studlist);
            $('#tuitiondetail').html(data.tparticulars);
          }
        });
      });

      $(document).on('click', '#tui-entry', function(){
        var tuitionid = $(this).attr('data-id');
        var studarray = [];



        $('#studlists tr').each(function(){
          studarray.push($(this).attr('data-id'));
        });


        Swal.fire({
          title: 'Tuition Payment Entry.',
          text: "Proceed?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes!'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"{{route('procTuition')}}",
              method:'GET',
              data:{
                tuitionid:tuitionid,
                studarray:studarray
              },
              dataType:'',
              success:function(data)
              {


                $('.tui-card').each(function(){
                  if($(this).attr('data-id') == tuitionid)
                  {
                    $(this).trigger('click');
                  }
                });

                Swal.fire(
                  'Done!',
                  '',
                  'success'
                );



              }
            }); 
          }
        });
      });

    $(document).on('change', '#glevel', function()
    {
      var levelid = $(this).val()
      $('#btn-search').trigger('click');
      if(levelid >= 17 && levelid <= 21)
      {
        $('#table-desc').text('Course');
      }
      else
      {
        $('#table-desc').text('Grantee'); 
      }
    });


    });




  </script>


@endsection