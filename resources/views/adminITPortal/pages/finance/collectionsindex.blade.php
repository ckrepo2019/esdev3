@extends('adminITPortal.layouts.app')


@section('pagespecificscripts')
@endsection

<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@section('content')
<style>
    .shadow{
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .shadow-lg{
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }
    .card{
        border: none !important;
    }
</style>
@php
 
 $url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();   
@endphp
  <section class="content-header">
    <div class="container-fluid mb-2">
          <div class="row">
                <div class="col-sm-6">
                      <h1 class="m-0 text-dark">{{Session::get('schoolinfo')->schoolname}}</h1>
                </div>
                <div class="col-sm-6">
                </div>
          </div>
    </div>
    <div class="card shadow">
      <div class="card-header">
        <div class="row">
          <div class="col-md-4">
            <label>Select School Year</label>
            <select class="form-control" id="select-syid">
              @foreach($schoolyears as $sy)
                <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label>Select Semester</label>
            <select class="form-control" id="select-semid">
              @foreach($semesters as $semester)
                <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="card-deck mb-3" hidden>
      <div class="card card-success">
      <div class="card-header">
      <h3 class="card-title">Collapsable</h3>
      <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
      </button>
      </div>
      
      </div>
      
      <div class="card-body">
        <div class="chart card-img-top">
          <canvas id="barChart-payroll" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
        </div>
      </div>
      
      </div>
      
        <div class="card shadow" hidden>
            <div class="chart card-img-top">
              <canvas id="barChart-expenses" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          <div class="card-body">
            <div class="row">
                <div class="col-6">
                  <h5 class="card-title text-bold">Expenses</h5>
                </div>
                <div class="col-6 text-right">
                    <button type="button" class="btn btn-default btn-sm text-bold">View details</button>
                </div>
            </div>
          </div>
        </div>
    </div>
    <div id="container-results">

    </div>
    <div class="card-deck">
        <div class="card shadow" hidden>
            <div class="chart card-img-top">
                <canvas id="barChart-payables" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                    <h5 class="card-title text-bold">Payables</h5>
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class="btn btn-default btn-sm text-bold">View details</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
      <div class="card shadow">
          <div class="card-header">
              <h2 class="card-title">Collection</h2>
          </div>
          <div class="card-body">
            
            <div class="row mb-2">
              <div class="col-md-3">
                <label>Date From</label>
                <input type="date" class="form-control" id="input-datefrom" value="{{date('Y-m-d')}}"/>
              </div>
              <div class="col-md-3">
                <label>Date To</label>
                <input type="date" class="form-control" id="input-dateto" value="{{date('Y-m-d')}}"/>
              </div>
            {{-- </div>
            
            <div class="row mb-2"> --}}
                <div class="col-md-6 text-right align-self-end"><button type="button" class="btn btn-primary" id="btn-gettransactions"><i class="fa fa-sync"></i> Generate</button></div>
            </div>
            <div id="container-transactions-results">
              
            </div>
          </div>
      </div>
  </section>
@endsection


@section('footerjavascript')
<script src="{{asset('plugins/flot/jquery.flot.js')}}"></script>

<script src="{{asset('plugins/flot/jquery.flot.resize.js')}}"></script>

<script src="{{asset('plugins/flot/jquery.flot.pie.js')}}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
{{-- <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script> --}}
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
  <script>
    $(document).ready(function(){

      function filterrecords(){
        Swal.fire({
            title: 'Fetching data...',
            onBeforeOpen: () => {
                Swal.showLoading()
            },
            allowOutsideClick: false
        })
        $.ajax({
            url: '/finance/index?action=filter',
            type:'GET',
            data: {
                syid        :  $('#select-syid').val(),
                semid        :  $('#select-semid').val()
            },
            success:function(data) {
                $('#container-results').empty()
                $('#container-results').append(data)

                $(".swal2-container").remove();
                $('body').removeClass('swal2-shown')
                $('body').removeClass('swal2-height-auto')
                
            }
        })
      }
      // filterrecords();
      // $('#select-syid').on('change',function(){
      // $('#btn-gettransactions').click()
      // })
      // $('#select-semid').on('change',function(){
      // $('#btn-gettransactions').click()
      // })
      $(document).on('click','#btn-export-receivables', function(){        
        var syid        =  $('#select-syid').val();
        var semid        =  $('#select-semid').val();
        window.open("/finance/index?syid="+syid+"&semid="+semid+"&action=filter&export=1&report=receivables",'_blank');
      })
      $(document).on('click','#btn-export-income', function(){        
        var syid        =  $('#select-syid').val();
        var semid        =  $('#select-semid').val();
        window.open("/finance/index?syid="+syid+"&semid="+semid+"&action=filter&export=1&report=income",'_blank');
      })
      $(document).on('click', '#btn-gettransactions', function(){
          var terminal = $('#select-terminal').val()
          var datefrom = $('#input-datefrom').val()
          var dateto = $('#input-dateto').val()
          var paymenttype = $('#select-paymenttype').val()
          Swal.fire({
                  title: 'Fetching data...',
                  allowOutsideClick: false,
                  closeOnClickOutside: false,
                  onBeforeOpen: () => {
                      Swal.showLoading()
                  }
          })
          $.ajax({
              url: '{{$url->eslink}}/passData?action=getcashiertransactions',
              type:'GET',
              data: {
                  datatype          :  'json',
                  eslink          :  '{{$url->eslink}}',
                  syid          :  $('#select-syid').val(),
                  semid         :  $('#select-semid').val(),
                  terminalno    :  terminal,
                  datefrom      :  datefrom,
                  dateto        :  dateto,
                  paymenttype   :  paymenttype
              },
              success:function(data) {
                var totaltransactions = 0;
                var displayCTransactions = `                
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered" id="table-transactions" style="font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>OR No.</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Cashier</th>
                                        <th>Payment Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="font-size: 15px;">
                                        <th></th>
                                        <th></th>
                                        <th class="text-right">TOTAL</th>
                                        <th class="text-right"><span class="text-bold transactions-sum"></span></th>
                                        <th></th>
                                        <th></th>
                                    </tr>`
                                    if(data.length > 0)
                                    {
                                      $.each(data, function(key, value){
                                      displayCTransactions+=`
                                        <tr>
                                            <td class="text-bold">`+value.transdate+`</td>
                                            <td class="text-bold">`+value.ornum+`</td>
                                            <td>`+value.studname+`</td>
                                            <td class="text-right text-bold">`+value.amountpaid+`</td>
                                            <td>`+value.transby+`</td>
                                            <td class="text-center">`+value.paymenttype+`</td>
                                        </tr>`;
                                        totaltransactions+=parseFloat(value.amountpaid)

                                      })
                                    }
                                    displayCTransactions+=`<tr style="font-size: 15px;">
                                        <th></th>
                                        <th></th>
                                        <th class="text-right">TOTAL</th>
                                        <th class="text-right"><span class="text-bold transactions-sum"></span></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>`
                    
                  $('#container-transactions-results').empty()
                  $('#container-transactions-results').append(displayCTransactions)
                    $('.transactions-sum').text(totaltransactions.toFixed(2))

                  $('#table-transactions').DataTable({
                      "paging": false,
                      // "lengthChange": false,
                      "searching": true,
                      "ordering": false,
                      "info": true,
                      "autoWidth": false,
                      "responsive": true
                  });

                  $(".swal2-container").remove();
                  $('body').removeClass('swal2-shown')
                  $('body').removeClass('swal2-height-auto')                  
              }
          })
      })
    })

  </script>

@endsection