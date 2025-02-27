@extends('finance.layouts.app')

@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<style>
    table{
        font-size: 13px;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Consolidated Report</h1>
                <!-- <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                    <i class="fa fa-file-invoice nav-icon"></i> 
                    <b>STUDENT LEDGER</b></h4> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Cash Receipt Summary</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<div class="row m-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" id="export-tools">
                {{-- <button type="button" class="btn btn-default btn-sm export" exporttype="excel"><i class="fa fa-download"></i> Excel</button> --}}
					{{-- <button type="button" class="btn btn-default btn-sm export" exporttype="pdf"><i class="fa fa-download"></i> PDF</button>--}}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label>Date Range</label><br/>
                        <input type="text" class="form-control" id="selecteddaterange" placeholder="Select date range">
                    </div>
                    <div class="col-md-4" style="visibility: hidden;">
                        <label>Display</label><br/>
                        <select class="form-control" id="selecteddisplay">
                            <option value="1" selected>Accounts</option>
                            <option value="2">Students</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-block text-right">
                        <label>&nbsp;</label><br/>
                        <button type="button" class="btn btn-primary btn-block" id="generatebutton" disabled>Generate</button>
                    </div>
                    <div class="col-md-2 d-block text-right">
                        <label>&nbsp;</label><br/>
                        <button type="button" class="btn btn-warning btn-block" id="checktransaction">Check Transactions</button>
                    </div>
                </div>
            </div>
            {{-- <div class="card-footer">
                <div class="row">
                    <div class="col-12" id="selectedoptionscontainer"></div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
<div class="row m-2">
    <div class="col-md-12" id="resultscontainer">
        
    </div>
</div>
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#export-tools').hide();
        $('body').addClass('sidebar-collapse')
        var loadingtext = 'Getting ready...';
        var selecteddaterange = null;
        var selectedterminal = null;
        $('#selecteddaterange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'MM/DD/YYYY'
            }
        })

        $('.select2').select2({
           theme: 'bootstrap4'
        });

        $('#selecteddaterange').on('apply.daterangepicker', function(ev, picker) {
            loadingtext = 'Loading...';
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            selecteddaterange = $(this).val();
            $('#generatebutton').attr('disabled',false)
        });
        $('#selectedterminal').on('change', function() {
            selectedterminal = $(this).val();
        });
        $('#generatebutton').on('click', function(){
            var selecteddisplay = $('#selecteddisplay').val();
            Swal.fire({
                title: loadingtext,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })          
            $.ajax({
                url: '{{ route('consolidatedgenerate')}}',
                type: 'GET',
                data: {
                    selecteddaterange   : selecteddaterange,
                    selectedterminal    : selectedterminal,
                    selecteddisplay    : selecteddisplay
                },
                success:function(data){
                    // $('#consolidated_list').DataTable();
                    if(data == 'no data')
                    {
                        Swal.fire(
                          'No Transactions',
                          '',
                          'error'
                        )
                    }
                    else{
                        $('#export-tools').show();
                        $('#resultscontainer').empty();
                        $('#resultscontainer').append(data)
                        $('.paginate_button').addClass('btn btn-sm btn-default')
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                }
            })
        })
        $(document).on('click', '#btn-exportpdf', function(){
                var exporttype = 'pdf';
                var paramet = {
                    selecteddaterange  : selecteddaterange,
                    selectedterminal   : selectedterminal
                }
				window.open("/finance/reports/consolidated/generate?exporttype="+exporttype+"&"+$.param(paramet));
        })

        $(document).on('click', '#checktransaction', function(){
            var daterange = $('#selecteddaterange').val();

            $.ajax({
                url: '{{route('consolidated_chktrans')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    daterange:daterange
                },
                success:function(data)
                {
                    $('#trans_list').html(data.list);
                    $('#modal-checktrans').modal('show');
                }
            });
            
        })
        $(document).on('click', '#trans_list tr', function(){
            if($(this).hasClass('trxheader'))
            {
                var transid = $(this).attr('data-id');

                $.ajax({
                    url: '{{route('consolidated_trans')}}',
                    type: 'GET',
                    data: {
                        transid:transid
                    },
                    success:function(data)
                    {
                        $('#trx_progid').val(data);
                        $('#trx_progid').trigger('change');
                        $('#trxhead_save').attr('data-id', transid);
                        $('#modal-checktrans_header').modal('show');
                    }
                });
            }
        });

        $(document).on('click', '#trxhead_save', function(){
            var transid = $(this).attr('data-id');
            var progid = $('#trx_progid').val();

            $.ajax({
                url: '{{route('consolidated_trans_update')}}',
                type: 'GET',
                data: {
                    transid:transid,
                    progid:progid
                },
                success:function(data)
                {
                    const Toast = Swal.mixin({
                      toast: true,
                      position: 'top-end',
                      showConfirmButton: false,
                      timer: 3000,
                      timerProgressBar: true,
                      didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                      }
                    })

                    Toast.fire({
                      type: 'success',
                      title: 'Saved'
                    })

                    $('#modal-checktrans_header').modal('hide');
                    $('#checktransaction').trigger('click');
                }
            });
            
        });

    })
</script>

<div class="modal fade show" id="modal-checktrans" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Transactions</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 table-responsive" style="height: 28em;">
                    <table class="table table-striped table-sm text-sm">
                        <thead>
                            <tr>
                                <th>OR Number</th>
                                <th>Particulars</th>
                                <th>Amount</th>
                                <th>Class Code</th>
                            </tr>
                        </thead>
                        <tbody id="trans_list" style="cursor: pointer;">
                            
                        </tbody>
                    </table>
                </div>
            </div> 
           </div>
            
        <div class="modal-footer justify-content-between"> 
            {{--  --}}

            <div class="float-left">
                
            </div>          
            <div class="float-right">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>     
                {{-- <button id="cmdSaveMT" type="button" class="btn btn-primary" style="width: 90px"><i class="fas fa-save"></i> Save</button> --}}
            </div>
        </div>
      </div>
    </div>
      <!-- /.modal-content -->
</div>

<div class="modal fade show" id="modal-checktrans_header" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md" style="margin-top: 11em;">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">Transactions</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <select id="trx_progid" class="select2" style="width: 100%;">
                            <option value="0">ACADEMIC PROGRAM</option>
                            @foreach(DB::table('academicprogram')->get() as $acadprog)
                                <option value="{{$acadprog->id}}">{{$acadprog->progname}}</option>
                            @endforeach
                        </select>
                    </div>
                </div> 
            </div>
            <div class="modal-footer justify-content-between"> 
                <div class="float-left"></div>          
                <div class="float-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>     
                    <button id="trxhead_save" type="button" data-id="0" class="btn btn-primary" style="width: 90px"><i class="fas fa-save"></i> Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show" id="modal-checktrans_detail" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">Items</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <select id="trx_classcode" class="select2" style="width: 100%;">
                            <option value="0">Class Code</option>
                            @foreach(DB::table('academicprogram')->get() as $acadprog)
                                <option value="{{$acadprog->id}}">{{$acadprog->progname}}</option>
                            @endforeach
                        </select>
                    </div>
                </div> 
            </div>
            <div class="modal-footer justify-content-between"> 
                <div class="float-left"></div>          
                <div class="float-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>     
                    <button id="trxdetail_save" type="button" class="btn btn-primary" style="width: 90px"><i class="fas fa-save"></i> Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection