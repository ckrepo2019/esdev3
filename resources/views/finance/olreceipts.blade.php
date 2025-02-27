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
            <li class="breadcrumb-item active">Online Receipts</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content pt-0">
  	<div class="main-card card">
  		<div class="card-header bg-info">
        <div class="row">
          <div class="text-lg col-md-4">
            <!-- Fees and Collection     -->
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            <b>ONLINE RECEIPTS</b></h4>
          </div>
          <div class="col-md-4"></div>
          <div class="col-md-4">
                  
          </div>  
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group mb-3">
              <input id="datefrom" class="form-control" type="date" data-toggle="tooltip" title="Date from" value="{{date_format(App\FinanceModel::getServerDateTime(), 'Y-m-d')}}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group mb-3">
              <input id="dateto" class="form-control" type="date" data-toggle="tooltip" title="Date to" value="{{date_format(App\FinanceModel::getServerDateTime(), 'Y-m-d')}}">
            </div>
          </div>

          <div class="col-md-2">
            <div class="form-group mb-3">
              <input id="code" type="text" class="form-control" placeholder="Search Code" data-toggle="tooltip" title="Code">
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group mb-3">
              <select id="cbostatus" class="form-control" data-toggle="tooltip" title="Status">
                <option value="0">All</option>
                <option value="1">Approved</option>
                <option value="2">Disapproved</option>
                <option value="3">Cancelled</option>
                <option value="5">Completed</option>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group mb-3">
              <button id="btnsearch" class="btn btn-warning"><i class="fas fa-search"></i> Search</button>
            </div>
          </div>
          
        </div>
  		</div>
      
  		<div class="card-body table-responsive p-0" style="height:380px">
        <table class="table table-striped">
          <thead class="bg-warning">
            <tr>
              <th>CODE</th>
              <th>NAME</th>
              <th>GRADE LEVEL</th>
              <th>DATE TRANS</th>
              <th>AMOUNT</th>
              <th>TYPE</th>
              <th>STATUS</th>
              <th></th>
            </tr>  
          </thead> 
          <tbody id="list" class="cursor-pointer">
            
          </tbody>             
        </table>
  		</div>
  	</div>
  </section>
@endsection

@section('modal')
  <div class="modal fade show" id="modal-img" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body bg-secondary text-center">
          <a id="dl-link" href=""download>
            <img id="img-receipt" class="w-100" src="" data-toggle="tooltip" title="Click to download">
          </a>
        </div>
        <div class="modal-footer justify-content-between">
          {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="saveItem" type="button" class="btn btn-primary" data-dismiss="modal">Save</button> --}}
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>


  
@endsection

@section('js')

  <style type="text/css">
    .cursor-pointer{
      cursor: pointer;

    }

    .Div-hide{
      display: none !important;
    }

    .Div-show{
      display: block;
    }
  </style>


  <script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      });

    $(document).ready(function(){

      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      function searchOL()
      {
        var dtfrom = $('#datefrom').val();
        var dtto = $('#dateto').val();
        var code = $('#code').val();
        var status = $('#cbostatus').val();

        $.ajax({
          url:"{{route('searchOLReceipt')}}",
          method:'GET',
          data:{
            dtfrom:dtfrom,
            dtto:dtto,
            code:code,
            status:status
          },
          dataType:'json',
          success:function(data)
          {
            $('#list').html(data.list);
          }
        });  
      }

      $(document).on('click', '#btnsearch', function(){
        searchOL();
      });

      $(document).on('mouseover', '#list tr', function(){
        $(this).addClass('bg-secondary');
      });

      $(document).on('mouseout', '#list tr', function(){
        $(this).removeClass('bg-secondary')
      });

      $(document).on('click', '#list .ol-item', function(){
        // alert('view');
        // console.log();
        $('.modal-title').text($(this).parent().find('#qcode').text());
        // $('#img-receipt').attr('src', $(this).parent().attr('data-src'));
		$('#img-receipt').attr('src', $(this).parent().attr('data-src'));
        $('#dl-link').attr('href', $(this).parent().attr('data-src'));
        $('#modal-img').modal('show');

      });

      $(document).on('click', '#list .dl', function(){
        
      });

      $(document).on('change', '.form-control', function(){
        searchOL();
      });

    });

  </script>
@endsection