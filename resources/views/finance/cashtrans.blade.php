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
            <li class="breadcrumb-item active">Cashier Transactions</li>
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
            <b>Cashier Transactions</b></h4>
          </div>
          <div class="col-md-4"></div>
          <div class="col-md-4">
                  
          </div>  
        </div>
        <div class="row">
          <div class="col-md-2">
            <div class="form-group mb-3">
              <select id="cboterminal" class="form-control filter-control" data-toggle="tooltip" title="Terminal No.">
                <option value="0">All</option>
                {{$terminals = DB::table('chrngterminals')->get()}}
                @foreach($terminals as $terminal)
                  <option value="{{$terminal->id}}">{{$terminal->description}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group mb-3">
              <input id="datefrom" class="form-control filter-control" type="date" data-toggle="tooltip" title="Date from" value="{{date_format(App\FinanceModel::getServerDateTime(), 'Y-m-d')}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group mb-3">
              <input id="dateto" class="form-control filter-control" type="date" data-toggle="tooltip" title="Date to" value="{{date_format(App\FinanceModel::getServerDateTime(), 'Y-m-d')}}">
            </div>
          </div>

          <div class="col-md-2">
            <div class="input-group mb-3">
              <input id="filter" type="text" class="form-control filter-control" placeholder="Search" data-toggle="tooltip" title="Search">
            </div>
          </div>

          <div class="col-md-4">
            <div class="input-group">
              <select id="ptype" class="select2bs4 form-control filter-control" multiple="" data-placeholder="Select Payment Type">
                @php
                  $paymenttype = db::table('paymenttype')
                    ->where('deleted', 0)
                    ->get();
                @endphp

                @foreach($paymenttype as $paytype)
                  <option value="{{$paytype->id}}">{{$paytype->description}}</option>
                @endforeach

              </select>
              <div class="input-group-append">
                <span id="btnsearch" class="input-group-text" data-toggle="tooltip" title="Search">
                  <i class="fas fa-search"></i>
                </span>
              </div>
              <div class="input-group-append">
                <span id="btnprint" class="input-group-text" data-toggle="tooltip" title="Print">
                  <i class="fas fa-print"></i>
                </span>
              </div>
            </div>
          </div>
          
        </div>
  		</div>
      
  		<div class="card-body table-responsive p-0" style="height:380px">
        <table class="table table-striped">
          <thead class="bg-warning">
            <tr>
              <th></th>
              <th>DATE</th>
              <th>OR NUMBER</th>
              <th>NAME</th>
              <th>AMOUNT</th>
              <th>POSTED</th>
              <th>CASHIER</th>
              <th>PAYMENT TYPE</th>
              <th></th>
            </tr>  
          </thead> 
          <tbody id="list" class="text-sm">
            
          </tbody>             
        </table>
  		</div>
  	</div>
  </section>
@endsection

@section('modal')
  <div class="modal fade show" id="modal-viewtrans" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div id="modalhead" class="modal-header">
          <h4 class="modal-title">OR: <span id="head-ornum" class="text-bold"></span> <span id="lblvoid" class="text-bold"> - VOID</span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-9">
              <div class="row">
                <div class="col-md-3">
                  ID No.:
                </div>
                <div class="col-md-2">
                  <span id="lblidno" class="text-bold"></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  NAME:
                </div>
                <div class="col-md-5">
                  <span id="lblstudname" class="text-bold"></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  GRADE|SECTION:
                </div>
                <div class="col-md-5">
                  <span id="lblgrade" class="text-bold"></span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="row">
                <div class="col-md-12">
                  Date Trans: <span class="text-bold" id="lbltransdate"></span>
                </div>
              </div>
            </div>
          </div>
          <hr>

          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th class="">PARTICULARS</th>
                      <th class="text-center">AMOUNT</th>
                    </tr>
                  </thead>
                  <tbody id="list-detail">
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>

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

      searchTrans();
      function searchTrans()
      {
        var dtfrom = $('#datefrom').val();
        var dtto = $('#dateto').val();
        var filter = $('#filter').val();
        var terminalno = $('#cboterminal').val();
        var paytype = $('#ptype').val();

        $.ajax({
          url:"{{route('cashtranssearch')}}",
          method:'GET',
          data:{
            dtfrom:dtfrom,
            dtto:dtto,
            filter:filter,
            terminalno:terminalno,
            paytype:paytype
          },
          dataType:'json',
          success:function(data)
          {
            $('#list').html(data.list);
          }
        });  
      }

      $(document).on('click', '#btnsearch', function(){
        searchTrans();
      })

      $(document).on('change', '.filter-control', function(){
        console.log($('#ptype').val())
        searchTrans();
      });

      $(document).on('click', '#btnprint', function(){
        var paytype = $('#ptype').val();

        if(paytype == '')
        {
          paytype = 0;
        }

        window.open('printcashtrans/' + $('#cboterminal').val() + '/' + $('#datefrom').val() + '/' + $('#dateto').val() + '/"' + $('#filter').val() + '"' + '/' + paytype + '/ cashiertransactions' , '_blank');
      });

      $(document).on('click', '.btn-view', function(){
        var transid = $(this).attr('data-id');

        $.ajax({
          url:"{{route('transviewdetail')}}",
          method:'GET',
          data:{
            transid:transid
          },
          dataType:'json',
          success:function(data)
          {
            $('#head-ornum').text(data.ornum);
            $('#lblstudname').text(data.studname);
            $('#lblgrade').text(data.gradelevel);
            $('#lblidno').text(data.idno);
            $('#list-detail').html(data.list);
            $('#lbltransdate').text(data.transdate);
            console.log('CANCELLED: ' + data.cancelled);
            if(data.cancelled == 0)
            {
              $('#modalhead').removeClass('bg-danger'); 
              $('#modalhead').addClass('bg-primary');
              $('#lblvoid').hide();
            }
            else
            {
              $('#modalhead').addClass('bg-danger'); 
              $('#modalhead').removeClass('bg-primary'); 
              $('#lblvoid').show();
            }

            $('#modal-viewtrans').modal('show');
          }
        });  

      });

      // $('#ptype')r

      

    });

  </script>
@endsection