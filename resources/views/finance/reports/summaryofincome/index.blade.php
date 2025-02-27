@extends('finance.layouts.app')

@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<link href="{{asset('plugins/bootstrap-datepicker/1.2.0/css/datepicker.min.css')}}" rel="stylesheet">
<style>
    table{
        font-size: 13px;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Summary of Income</h1>
                <!-- <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                    <i class="fa fa-file-invoice nav-icon"></i> 
                    <b>STUDENT LEDGER</b></h4> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Summary of Income</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<div class="row m-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Month Range:</label>
        
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i> &nbsp;&nbsp;From</span>
                            </div>
                            <input type="text" class="form-control datepicker" name="datepicker" id="datefrom" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/yyyy" data-mask readonly style="background-color: white; cursor: pointer;"/>
                            <div class="input-group-append">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i> &nbsp;&nbsp;To</span>
                            </div>
                            <input type="text" class="form-control datepicker" name="datepicker" id="dateto" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/yyyy" data-mask readonly style="background-color: white; cursor: pointer;"/>
                          </div>
                          <!-- /.input group -->
                        </div>

                    </div>
                    {{-- <div class="col-md-3">
                        <label>Terminal</label><br/>
                        <select class="form-control" id="selectedterminal">
                            <option value="">Select Terminal</option>
                            @foreach($terminals as $terminal)
                                <option value="{{$terminal->id}}">{{$terminal->description}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3" style="visibility: hidden;">
                        <label>Display</label><br/>
                        <select class="form-control" id="selecteddisplay">
                            <option value="1" selected>Accounts</option>
                            <option value="2">Students</option>
                        </select>
                    </div> --}}
                    <div class="col-md-6 d-block text-right">
                        <label>&nbsp;</label><br/>
                        <button type="button" class="btn btn-primary" id="generatebutton" disabled>Generate</button>
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
@endsection
@section('footerscripts')

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
<script src="{{asset('plugins/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#export-tools').hide();
        $('body').addClass('sidebar-collapse')
        var loadingtext = 'Getting ready...';
        var selecteddaterange = null;
        var selectedterminal = null;
        $(".datepicker").datepicker( {
            format: "yyyy-mm",
            startView: "months", 
            minViewMode: "months"
        });
        // $('#selecteddaterange').daterangepicker({
        //     autoUpdateInput: false,
        //     locale: {
        //         cancelLabel: 'Clear',
        //         format: 'MM/DD/YYYY'
        //     }
        // })
        // $('#selecteddaterange').on('apply.daterangepicker', function(ev, picker) {
        //     loadingtext = 'Loading...';
        //     $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        //     selecteddaterange = $(this).val();
        //     $('#generatebutton').attr('disabled',false)
        // });
        // $('#selectedterminal').on('change', function() {
        //     selectedterminal = $(this).val();
        // });
        $('#datefrom').on('change', function(){
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                $('#generatebutton').prop('disabled',true)
            }else{
                if($('#dateto').val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $('#generatebutton').prop('disabled',true)
                }else{                    
                    $('#generatebutton').prop('disabled',false)
                }
            }
        })
        $('#dateto').on('change', function(){
            if($(this).val().replace(/^\s+|\s+$/g, "").length == 0)
            {
                $('#generatebutton').prop('disabled',true)
            }else{
                if($('#datefrom').val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $('#generatebutton').prop('disabled',true)
                }else{                    
                    $('#generatebutton').prop('disabled',false)
                }
            }
        })
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
                url: '{{ route('incomesummarygenerate')}}',
                type: 'GET',
                data: {
                    datefrom   : $('#datefrom').val(),
                    dateto     : $('#dateto').val()
                },
                success:function(data){
                    $('#resultscontainer').empty();
                    $('#resultscontainer').append(data)
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        })
        $(document).on('click', '#btn-exportpdf', function(){
                var exporttype = 'pdf';
                var paramet = {
                    datefrom   : $('#datefrom').val(),
                    dateto     : $('#dateto').val()
                }
				window.open("/finance/reports/incomesummary/generate?exporttype="+exporttype+"&"+$.param(paramet));
        })
    })
</script>
@endsection