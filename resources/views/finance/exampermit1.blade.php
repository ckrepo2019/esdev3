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
            <li class="breadcrumb-item active">Examination Permit</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content pt-0">
    <div class="container-fluid">
      <div class="row">
        <div class="card col-md-12 p-0">
      		<div class="card-header text-lg bg-info">
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
            <b>Examination Permit</b></h4>
      		</div>
      		<div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <select id="glevel" name="studid" class="text-secondary form-control select2bs4 updq w-100" value="">
                  <option value="0">Select Grade Level</option>
                  @foreach(App\FinanceModel::loadGlevel() as $glevel)
                    <option value="{{$glevel->id}}">{{$glevel->levelname}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-2 mt-2" hidden="">
                Amount Due less than
              </div>
              <div class="col-md-1" hidden="">
                <input id="lessthanamount" class="form-control" placeholder="0.00">
              </div>
              <div class="col-md-2 mt-2" hidden="">
                <div class="form-group clearfix">
                  <div class="icheck-info d-inline">
                    <input type="checkbox" id="ispercent">
                    <label for="ispercent">
                      Percent
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-md-1" hidden="">
                <button id="btn-search" data-allow="0" class="btn btn-primary">SEARCH</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-6">
          <div class="row mb-3">
            <div class="col-sm-3">
              <button id="btn-notallow" class="btn btn-danger btn-block btn-condition">Not Allowed</button>
            </div>
            <div class="col-sm-3">
              <button id="btn-allow" class="btn btn-outline-success btn-block btn-condition">Allowed</button>
            </div>
          </div>
        </div>
        <div class="col-md-6">

          <div class="row mb-3">
            <div class="col-sm-4">
              <button id="btn-ledger" class="btn btn-outline-primary btn-block">Student Ledger</button>
            </div>
            <div class="col-sm-4">
              <button id="btn-assessment" class="btn btn-outline-danger btn-block">Student Assessment</button>
            </div>
            <div class="col-sm-4" hidden="">
              <button id="btn-payment" class="btn btn-outline-success btn-block">Payment</button>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card card-info">
            <div class="card-header bg-primary">
              <div class="row">
                <div class="col-sm-6">
                  <h3 class="card-title">Student List</h3>    
                </div>
                <div class="col-sm-6 text-right">
                  Students: <span id="studcount" class="text-bold">0</span>
                </div>
              </div>
            </div>
            <div class="card-body table-responsive p-0" style="height: 330px">
              <table class="table table-striped">
                <thead class="bg-warning">
                  <tr>
                    <th>Name</th>
                    <th>Level</th>
                    <th class="text-left" id="table-desc">Balance</th>
                    <th style=""><button id="permit-all" class="btn btn-success btn-sm btn-block">Permit All</button></th>
                  </tr>
                </thead>
                <tbody id="studlist" class="text-sm" style="cursor: pointer;">
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>

      <div class="col-6">
        <div id="divledger" class="card card-info payinfo">
          <div class="card-header bg-info">
            <h3 class="card-title">Student Ledger</h3>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-striped">
              <thead class="bg-warning">
                <tr>
                  <th>Date</th>
                  <th>Particulars</th>
                  <th>Amount</th>
                  <th>Payment</th>
                  <th>Balance</th>
                </tr>
              </thead>
              <tbody id="ledgerlist" class="text-sm">
                
              </tbody>
            </table>
          </div>
        </div>

        <div id="divassessment" class="card card-info payinfo" hidden="">
          <div class="card-header bg-info">
            <h3 class="card-title">Student Assessment</h3>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-striped">
              <thead class="bg-warning">
                <tr>
                  <th>Particulars</th>
                  <th>Amount</th>
                  <th>Payment</th>
                  <th>Balance</th>
                </tr>
              </thead>
              <tbody id="assessmentlist">
                
              </tbody>
            </table>
          </div>
        </div>

        <div id="divpayment" class="card card-info payinfo" hidden="">
          <div class="card-header bg-info">
            <h3 class="card-title">Payment</h3>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-striped">
              <thead class="bg-warning">
                <tr>
                  <th>Date</th>
                  <th>OR number</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody id="paymentlist">
                
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

      $('body').addClass('sidebar-collapse');


        
      $(document).on('change', '#glevel', function()
      {
        // var levelid = $(this).val()
        // $('#btn-search').trigger('click');
        // if(levelid >= 17 && levelid <= 21)
        // {
        //   $('#table-desc').text('Course');
        // }
        // else
        // {
        //   $('#table-desc').text('Grantee'); 
        // }
      });


      $(document).on('click', '#btn-ledger', function(){
        $('.payinfo').prop('hidden', true);
        $('#divledger').prop('hidden', false);
      });

      $(document).on('click', '#btn-assessment', function(){
        $('.payinfo').prop('hidden', true);
        $('#divassessment').prop('hidden', false);
      });

      $(document).on('click', '#btn-payment', function(){
        $('.payinfo').prop('hidden', true);
        $('#divpayment').prop('hidden', false);
      });

      function studfilter()
      {
        var levelid = $('#glevel').val();
        var ispercent = $('ispercent').val();
        var lessthanamount = $('#lessthanamount').val();
        var allow = $('#btn-search').attr('data-allow');

        $.ajax({
          url:"{{route('permit_studfilter')}}",
          method:'GET',
          data:{
            levelid:levelid,
            ispercent:ispercent,
            lessthanamount:lessthanamount,
            allow:allow
          },
          dataType:'json',
          success:function(data)
          {
            $('#studlist').html(data.studlist);
            $('#studcount').text($('#studlist tr').length);
          }
        }); 
      }

      $(document).on('click', '.btn-condition', function(){
        $('#btn-allow').addClass('btn-outline-success');
        $('#btn-allow').removeClass('btn-success');

        $('#btn-notallow').addClass('btn-outline-danger');
        $('#btn-notallow').removeClass('btn-danger');

        if($(this).attr('id') == 'btn-allow')
        {
          $(this).removeClass('btn-outline-success');
          $(this).addClass('btn-success');
          $('#btn-search').attr('data-allow', 1);
          $('#permit-all').prop('hidden', true)
        }
        else
        {
          $(this).removeClass('btn-outline-danger');
          $(this).addClass('btn-danger'); 
          $('#btn-search').attr('data-allow', 0);
          $('#permit-all').prop('hidden', false)
        }
      });

      $(document).on('select2:close', '#glevel', function(){
        studfilter();
      });

      $(document).on('click', '.btn-condition', function(){
        studfilter();
      });

      $(document).on('mouseenter', '#studlist tr', function(){
        $(this).addClass('bg-secondary')
      });

      $(document).on('mouseout', '#studlist tr', function(){
        $(this).removeClass('bg-secondary')
      });

      $(document).on('click', '#studlist tr', function(){
        var studid = $(this).attr('data-id');

        $('#studlist tr').removeClass('bg-info');
        $(this).addClass('bg-info');

        $.ajax({
          url:"{{route('permit_loadinfo')}}",
          method:'GET',
          data:{
            studid:studid
          },
          dataType:'json',
          success:function(data)
          {
            $('#ledgerlist').html(data.ledgerlist);
            $('#assessmentlist').html(data.assessmentlist)
          }
        }); 
      });

      $(document).on('click', '.btn-permit', function(){
        var studid = $(this).attr('data-id')
        
        Swal.fire({
          title: 'Permit this student?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Permit!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url:"{{route('permit_allowtoexam')}}",
              method:'GET',
              data:{
                studid:studid
              },
              dataType:'',
              complete:function()
              {
                studfilter();

                Swal.fire(
                  'Permited!',
                  '',
                  'success'
                );
              }
            }); 
          }
        });
      });
    });

  </script>


@endsection