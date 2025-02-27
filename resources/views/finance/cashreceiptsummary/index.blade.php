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
<br>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Cash Receipt Summary</h1>
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
            <div class="card-header">
                <button type="button" class="btn btn-default btn-sm export" exporttype="excel"><i class="fa fa-download"></i> Excel</button>
                <button type="button" class="btn btn-default btn-sm export" exporttype="pdf"><i class="fa fa-download"></i> PDF</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group ">
                            <div class="input-group-prepend" >
                                <span class="input-group-text form-control">
                                    Date Range
                                </span>
                            </div>
                            <input type="text" class="form-control" id="selecteddaterange" placeholder="Select date range">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group ">
                            <div class="input-group-prepend" >
                                <span class="input-group-text form-control">
                                    Terminal
                                </span>
                            </div>
                            <select class="form-control" id="selectedterminal">
                                <option value="">Select Terminal</option>
                                @foreach($terminals as $terminal)
                                    <option value="{{$terminal->id}}">{{$terminal->description}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary float-right" id="generatebutton" disabled>Generate</button>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12" id="selectedoptionscontainer"></div>
                </div>
                <div class="row">
                    <div class="col-12" id="">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    {{-- <th>Department</th> --}}
                                    <th>Debit</th>
                                    <th>Credit</th>
                                </tr>
                            </thead>
                            <tbody id="resultscontainer">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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
            Swal.fire({
                title: loadingtext,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })          
            $.ajax({
                url: '{{ route('cashreceiptfilter')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    selecteddaterange   : selecteddaterange,
                    selectedterminal    : selectedterminal
                },
                success:function(data){
                    $('#resultscontainer').empty();
                    $('#resultscontainer').append(data.output)
                    $('#resultscontainer').append(data.gtotal)
                    $('.paginate_button').addClass('btn btn-sm btn-default')
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        })
        $('.export').on('click', function(){
                var exporttype = $(this).attr('exporttype')
                var paramet = {
                    selecteddaterange  : selecteddaterange,
                    selectedterminal   : selectedterminal
                }
				window.open("/cashreceiptsummary/export?exporttype="+exporttype+"&"+$.param(paramet));
        })
    })
</script>
@endsection