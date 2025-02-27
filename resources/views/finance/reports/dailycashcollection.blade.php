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
            <li class="breadcrumb-item active">Daily Cash Collection</li>
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
            <h4 class="text-warning mb-3" style="text-shadow: 1px 1px 1px #000000">
              <b>Daily Cash Collection</b>
            </h4>
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
            <div class="form-group mb-3">
              <select id="cboterminal" class="form-control filter-control" data-toggle="tooltip" title="Terminal No." disabled="">
                <option value="0">All</option>
                {{$terminals = DB::table('chrngterminals')->get()}}
                @foreach($terminals as $terminal)
                  <option value="{{$terminal->id}}">{{$terminal->description}}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-5">
            <div class="input-group">
              <input id="datenow" class="form-control filter-control" type="date" data-toggle="tooltip" title="Date" value="{{date_format(App\FinanceModel::getServerDateTime(), 'Y-m-d')}}">

              <div class="input-group-append">
                <span id="btngenreport" class="btn input-group-text text-bold" data-toggle="tooltip" title="Generate Report" style="cursor: pointer;">
                  Generate
                </span>
              </div>

              <div class="input-group-append">
                <span id="btnprint" class="input-group-text btn-print" data-action="List" data-toggle="tooltip" title="Print" style="cursor: pointer;">
                  <i class="fas fa-print"></i>
                </span>
              </div>
              {{-- <div class="input-group-append">
                <span id="btnsummary" class="input-group-text btn-print" data-action="Summary" data-toggle="tooltip" title="Print" style="cursor: pointer;">
                  Summary
                </span>
              </div> --}}
              {{-- @if(auth()->user()->id == 3)
                <div class="input-group-append">
                  <span id="distitemamount" class="input-group-text" data-toggle="tooltip" title="GENERATE#" >
                    <i class="fas fa-print"></i>
                  </span>
                </div>
              @endif --}}
            </div>
          </div>
          
        </div>
  		</div>
      
  		<div class="card-body table-responsive p-0" style="height:380px">
        <table class="table table-striped">
          <thead class="bg-warning">
            <tr>
              <th class="text-center">OR NO.</th>
              <th>Name</th>
              <th class="text-center" data-id="59">Registration</th>
              <th class="text-center" data-id="61">Medical</th>
              <th class="text-center" data-id="62">Insurance</th>
              <th class="text-center" data-id="63">ID</th>
              <th class="text-center" data-id="86">Developmental Fee</th>
              <th class="text-center" data-id="65">Annual Dues</th>
              <th class="text-center" data-id="67">Security Services</th>
              <th class="text-center" data-id="" class-id="43">PTA Maintenance</th>
              <th class="text-center" data-id="70">ID System</th>
              <th class="text-center" data-id="68">Internet Fee</th>
              <th class="text-center" data-id="87">Graduation Fee</th>
              <th>Tuition</th>
              <th class="text-center">Text Book</th>
              <th class="text-center">OLD Account</th>
              <th>Certificate</th>
              <th>Others</th>
              <th>TOTAL</th>
            </tr>
          </thead> 
          <tbody id="list" class="text-sm">
            
          </tbody>   
          <tfoot>
            <tr>
              <td colspan="2" class="text-right">TOTAL: </td>
              <td class="text-right text-bold" id="totalregistration"></td>
              <td class="text-right text-bold" id="totalmedical"></td>
              <td class="text-right text-bold" id="totalinsurance"></td>
              <td class="text-right text-bold" id="totalid"></td>
              <td class="text-right text-bold" id="totaldevelopmentfee"></td>
              <td class="text-right text-bold" id="totalannualdues"></td>
              <td class="text-right text-bold" id="totalsecurityservices"></td>
              <td class="text-right text-bold" id="totalpta"></td>
              <td class="text-right text-bold" id="totalidsystem"></td>
              <td class="text-right text-bold" id="totalinternetfee"></td>
              <td class="text-right text-bold" id="totalgraduationfee"></td>
              <td class="text-right text-bold" id="totaltuition"></td>
              <td class="text-right text-bold" id="totaltextbook"></td>
              <td class="text-right text-bold" id="totalbalforward"></td>
              <td class="text-right text-bold" id="totalcert"></td>
              <td class="text-right text-bold" id="totalothers"></td>
              <td class="text-right text-bold" id="grandtotal"></td>
            </tr>
          </tfoot>          
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

  <div class="modal fade" id="modal-overlay" data-backdrop="static" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-md" style="margin-top: 216px; background-color: #daa520">
      <div class="modal-content" style="opacity: 78%">
        <div class="overlay d-flex justify-content-center align-items-center" style="background-color: white">
          <i class="fas fa-7x fa-circle-notch fa-spin"></i>
        </div>
        {{-- <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div> --}}
        <div class="modal-body" style="height: 200px">
          <div class="row">
            <div class="col-md-12 text-right">
              <button id="btncloseoverlay" class="btn btn-secondary" hidden="">Close</button>
            </div>
          </div>
          <h3>Loading...</h3>
          
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
      genreport();
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

      function generateTH()
      {
        $.ajax({
          method:'GET',
          url:"{{route('cashtranssearch')}}",
          data:{
            
          },
          dataType:'json',
          success:function(data)
          {
            $('#list').html(data.list);
          }
        });
      }

      function genreport()
      {
        var terminal = $('#cboterminal').val();
        var datenow = $('#datenow').val();

        $.ajax({
          method:'GET',
          url:"{{route('generatereport')}}",
          data:{
            terminal:terminal,
            datenow:datenow
          },
          dataType:'json',
          beforeSend:function()
          {
            $('#modal-overlay').modal('show');
          },
          success:function(data)
          {
            $('#list').html(data.list);
            $('#totalregistration').html(data.totalregistration);
            $('#totalmedical').html(data.totalmedical);
            $('#totalinsurance').html(data.totalinsurance);
            $('#totalid').html(data.totalid);
            $('#totalsecurityservices').html(data.totalsecurityservices);
            $('#totalidsystem').html(data.totalidsystem);
            $('#totaldevelopmentfee').html(data.totaldevelopmentfee);
            $('#totalannualdues').html(data.totalannualdues);
            $('#totaltuition').html(data.totaltuition);
            $('#totalpta').html(data.totalpta);
            $('#totalbalforward').html(data.totalbalforward);
            $('#totalcert').html(data.totalcert);
            $('#totalinternetfee').html(data.totalinternetfee);
            $('#totalgraduationfee').html(data.totalgraduationfee);
            $('#totaltextbook').html(data.totaltextbook);
            $('#totalothers').html(data.totalothers);
            $('#grandtotal').html(data.grandtotal);
          },
          complete:function()
          {
            // $('#modal-overlay').modal('hide');
            $('.overlay').attr('style', 'background-color: white;display: none !important;')
            $('#btncloseoverlay').prop('hidden', false);
            $('h3').text('Done!')
          }
        }); 
      }

      $(document).on('click', '#btncloseoverlay', function(){
        $('.overlay').attr('style', 'background-color: white;')
        $('#modal-overlay').modal('hide');
        $('#btncloseoverlay').prop('hidden', true);
        $('h3').text('Loading...');
      });


      $(document).on('click', '#btngenreport', function(){
        genreport();
      });

      $(document).on('click', '#distitemamount', function(){
        $.ajax({
          url: '{{route('distitemamount')}}',
          type: 'GET',
          dataType: 'json',
          data: 
          {
            
          },
          success:function(data)
          {
            alert('DONE');
          }
        })
      });

      $(document).on('click', '.btn-print', function(){
        var terminal = $('#cboterminal').val();
        var datenow = $('#datenow').val();
        var action = $(this).attr('data-action');

        console.log('Test');
        window.open('/finance/reports/dailycashcollection/dailycashcollectionpdf/'+datenow+'/'+terminal+'/'+action, '_blank');
      });

      // $(document).on('click', '#btnsummary', function(){
      //   var terminal = $('#cboterminal').val();
      //   var datenow = $('#datenow').val();
      //   var action = $(this).attr('data-action');

      //   console.log('Test');
      //   window.open('/finance/reports/dailycashcollection/dailycashcollectionpdf/'+datenow+'/'+terminal, '_blank');
      // });


    });

  </script>
@endsection