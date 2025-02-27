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
            <li class="breadcrumb-item active">Mode of Payments</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
  	<div class="main-card card">
  		<div class="card-header text-lg bg-info">
  			<!-- Mode of Payments -->
        <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            <b>MODE OF PAYMENT</b></h4>
  		</div>
  		<div class="card-body">
        <div class="row">
          <div class="col-8">

          </div>
          <div class="col-4">
            <div class="input-group mb-3">
              <input id="txtsearchitem" type="text" class="form-control" placeholder="Search Mode of Payment" onkeyup="this.value = this.value.toUpperCase();">
              <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <div class="input-group-append">
                  <button class="btn btn-success" id="btnitem-new" onclick="window.location= '{!! route('mopnew')!!}'">New</button>
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
                    <th>PAYMENT DESCRIPTION</th>
                    <th class="text-center">NUMBER OF PAYMENT</th>
                    <th class="text-center">ONE TIME PAYMENT</th>
                    <th class="text-center">PERCENTAGE</th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="paymethod-list">

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
  <div class="modal fade show" id="modal-item-new" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Payment Items - New</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="class-desc" class="col-sm-2 col-form-label">Item Code</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control validation" id="item-code" placeholder="Item Code" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-desc" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control validation" id="item-desc" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-glid" class="col-sm-2 col-form-label">Classification</label>
                <div class="col-sm-10">
                  <select class="form-control" id=item-class>
                    <option></option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="class-glid" class="col-sm-2 col-form-label">SL Account</label>
                <div class="col-sm-10">
                  <select class="form-control" id=item-SL>
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
          <button id="saveItem" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>







@endsection

@section('js')
  <script type="text/javascript">

    $(document).ready(function(){

      searchMOP();
      function searchMOP(query)
      {
        $.ajax({
          url:"{{route('searchMOP')}}",
          method:'GET',
          data:{
            query:query
          },
          dataType:'json',
          success:function(data)
          {
            $('#paymethod-list').html(data.output);
          }
        });
      }

      $(document).on('click', '#btnmop-delete', function(){
        var mopid = $(this).attr('data-id');

        Swal.fire({
            title: 'Delete selected Mode of payment?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.value) {

              $.ajax({
                url:"{{route('mopdel')}}",
                method:'GET',
                data:{
                  mopid:mopid
                },
                dataType:'',
                success:function(data)
                {

                  searchMOP();


                  Swal.fire(
                    'Deleted!',
                    'Item has been deleted.',
                    'success'
                  );

                }
              });
            }
          });


      });

      $(document).on('keyup', '#txtsearchitem', function(){
        var query = $(this).val();
        searchMOP(query);
      })

    });

  </script>
@endsection
