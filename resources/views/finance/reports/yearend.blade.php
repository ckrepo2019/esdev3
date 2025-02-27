@extends('finance.layouts.app')

@section('content')
  {{-- <style type="text/css">
    .table thead th  { 
                position: sticky !important; left: 0 !important; 
                width: 150px !important;
                background-color: #fff !important; 
                outline: 2px solid #fff !important;
                outline-offset: -1px !important;
            }
  </style> --}}
	{{-- <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Finance</h1> -->
          
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Payment Items</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section> --}}
  <section class="content">
  			<!-- Payment Items -->
        <div class="row mb-2 ml-2">
          <h1 class="m-0 text-dark">Year-end Summary Report</h1>
        </div>
        <div class="row form-group">
            <div class="col-md-8">
                
            </div>
            
            <div class="col-md-4 text-right">
                <button id="ye_generate" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Generate</button>
                <button id="ye_print" class="btn btn-danger"><i class="fas fa-print"></i> Print</button>
                {{--<button id="ye_export" class="btn btn-warning"><i class="fas fa-download"></i> Export</button>--}}
            </div>
        </div>
		<div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary">
                        
                    </div>
                    <div class="card-body p-0">
                        <div id="" class="main_table p-0 table-responsive">
                            <table cellspacing="0" cellpadding="0" class="table table-sm text-sm" style="">
                                <thead class="bg-gray-dark" id="ye_header">
                                    
                                </thead>
                                <tbody id="ye_list" style="height: 300px; overflow-y: auto;">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>          
            <div class="col-md-4">
                <div class="card p-0">
                    <div class="card-header bg-info">
                        
                    </div>
                    <div class="card-body p-0">
                        <div id="" class="main_table table-responsive p-0" style="">
                            <table cellspacing="0" cellpadding="0" class="table table-striped table-sm text-sm" style="table-layout: fixed;">
                                <thead class="" id="">
                                    <tr>
                                        <th>MONTH</th>
                                        <th class="text-center">YEAR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="jan">JANUARY</td>
                                        <td>
                                            <select id="" class="ye_year y1 form-control" size="1" style="width: 100%;">
                                                <option></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="feb">FEBRUARY</td>
                                        <td>
                                            <select id="" class="ye_year y2 form-control" size="1" style="width: 100%;">
                                                <option></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="mar">MARCH</td>
                                        <td>
                                            <select id="" class="ye_year y3 form-control" size="1" style="width: 100%;">
                                                <option></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="apr">APRIL</td>
                                        <td>
                                            <select id="" class="ye_year y4 form-control" size="1" style="width: 100%;">
                                                <option></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="may">MAY</td>
                                        <td>
                                            <select id="" class="ye_year y5 form-control" size="1" style="width: 100%;">
                                                <option></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="jun">JUNE</td>
                                        <td>
                                            <select id="" class="ye_year y6 form-control" size="1" style="width: 100%;">
                                                <option></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="jul">JULY</td>
                                        <td>
                                            <select id="" class="ye_year y7 form-control" size="1" style="width: 100%;">
                                                <option></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="aug">AUGUST</td>
                                        <td>
                                            <select id="" class="ye_year y8 form-control" size="1" style="width: 100%;">
                                                <option></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="sep">SEPTEMBER</td>
                                        <td>
                                            <select id="" class="ye_year y9 form-control" size="1" style="width: 100%;">
                                                <option></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="oct">OCTOBER</td>
                                        <td>
                                            <select id="" class="ye_year y10 form-control" size="1" style="width: 100%;">
                                                <option></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="nov">NOVEMBER</td>
                                        <td>
                                            <select id="" class="ye_year y11 form-control" size="1" style="width: 100%;">
                                                <option></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="dec">DECEMBER</td>
                                        <td>
                                            <select id="" class="ye_year y12 form-control" size="1" style="width: 100%;">
                                                <option></option>
                                            </select>
                                        </td>
                                    </tr>
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
    <div class="modal fade" id="modal-overlay" data-backdrop="static" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content bg-gray-dark" style="opacity: 78%; margin-top: 15em">
                <div class="modal-body" style="height: 250px">
                    <div class="row">
                        <div class="col-md-12 text-center text-lg text-bold b-close">
                            Please Wait
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="loader"></div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: -30px">
                        <div class="col-md-12 text-center text-lg text-bold">
                            Processing...
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

@endsection

@section('js')
    
    <style>
        .loader{
            width: 100px;
            height: 100px;
            margin: 50px auto;
            position: relative;
        }
        .loader:before,
        .loader:after{
            content: "";
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: solid 8px transparent;
            position: absolute;
            -webkit-animation: loading-1 1.4s ease infinite;
            animation: loading-1 1.4s ease infinite;
        }
        .loader:before{
            border-top-color: #d72638;
            border-bottom-color: #07a7af;
        }
        .loader:after{
            border-left-color: #ffc914;
            border-right-color: #66dd71;
            -webkit-animation-delay: 0.7s;
            animation-delay: 0.7s;
        }
        @-webkit-keyframes loading-1{
            0%{
                -webkit-transform: rotate(0deg) scale(1);
                transform: rotate(0deg) scale(1);
            }
            50%{
                -webkit-transform: rotate(180deg) scale(0.5);
                transform: rotate(180deg) scale(0.5);
            }
            100%{
                -webkit-transform: rotate(360deg) scale(1);
                transform: rotate(360deg) scale(1);
            }
        }
        @keyframes loading-1{
            0%{
                -webkit-transform: rotate(0deg) scale(1);
                transform: rotate(0deg) scale(1);
            }
            50%{
                -webkit-transform: rotate(180deg) scale(0.5);
                transform: rotate(180deg) scale(0.5);
            }
            100%{
                -webkit-transform: rotate(360deg) scale(1);
                transform: rotate(360deg) scale(1);
            }
        }
    </style>
  
  <script type="text/javascript">

    $(function () {
        //Reference the DropDownList.
        var ddlYears = $(".ye_year");
 
        //Determine the Current Year.
        var currentYear = (new Date()).getFullYear();
 
        //Loop and add the Year values to DropDownList.
        for (var i = 2020; i <= currentYear; i++) {
            var option = $("<option />");
            option.html(i);
            option.val(i);
            ddlYears.append(option);
        }
    });


    
    $(document).ready(function(){
        var searchVal = $('#txtsearchitem').val();
        // searchitems();

        $('.select2').select2({
            theme: 'bootstrap4'
        });

        $('.select2-sm').select2();

        screenadjust();

        function screenadjust()
        {
            var screen_height = $(window).height();

            $('.main_table').css('height', screen_height - 300);
            // $('.screen-adj').css('height', screen_height - 223);
        }

        $(document).on('click', '#ye_generate', function(){
            var y1 = '';
            var y2 = '';
            var y3 = '';
            var y4 = '';
            var y5 = '';
            var y6 = '';
            var y7 = '';
            var y8 = '';
            var y9 = '';
            var y10 = '';
            var y11 = '';
            var y12 = '';

            $('.ye_year').each(function(){
                if($(this).hasClass('y1'))
                {
                    console.log('value: ' + $(this).val());
                    y1 = $(this).val();
                }
                else if($(this).hasClass('y2'))
                {
                    y2 = $(this).val();
                }
                else if($(this).hasClass('y3'))
                {
                    y3 = $(this).val();
                }
                else if($(this).hasClass('y4'))
                {
                    y4 = $(this).val();
                }
                else if($(this).hasClass('y5'))
                {
                    y5 = $(this).val();
                }
                else if($(this).hasClass('y6'))
                {
                    y6 = $(this).val();
                }
                else if($(this).hasClass('y7'))
                {
                    y7 = $(this).val();
                }
                else if($(this).hasClass('y8'))
                {
                    y8 = $(this).val();
                }
                else if($(this).hasClass('y9'))
                {
                    y9 = $(this).val();
                }
                else if($(this).hasClass('y10'))
                {
                    y10 = $(this).val();
                }
                else if($(this).hasClass('y11'))
                {
                    y11 = $(this).val();
                }
                else if($(this).hasClass('y12'))
                {
                    y12 = $(this).val();
                }
            });

            
            $('#modal-overlay').modal('show');

            $.ajax({
                url: '{{route('ye_generate')}}',
                type: 'GET',
                // dataType: 'json',
                data: {
                    y1:y1,
                    y2:y2,
                    y3:y3,
                    y4:y4,
                    y5:y5,
                    y6:y6,
                    y7:y7,
                    y8:y8,
                    y9:y9,
                    y10:y10,
                    y11:y11,
                    y12:y12,
                    action:'generate'
                },
                success:function(data)
                {
                    $('#ye_header').html(data.headerlist);
                    $('#ye_list').html(data.bodylist);

                    
                },
                complete:function()
                {
                    setTimeout(function(){
                        $('#modal-overlay').modal('hide');
                    }, 1000)
                }
            });    
        });

        $(document).on('click', '#ye_print', function(){
            var y1 = '';
            var y2 = '';
            var y3 = '';
            var y4 = '';
            var y5 = '';
            var y6 = '';
            var y7 = '';
            var y8 = '';
            var y9 = '';
            var y10 = '';
            var y11 = '';
            var y12 = '';

            $('.ye_year').each(function(){
                if($(this).hasClass('y1'))
                {
                    console.log('value: ' + $(this).val());
                    y1 = $(this).val();
                }
                else if($(this).hasClass('y2'))
                {
                    y2 = $(this).val();
                }
                else if($(this).hasClass('y3'))
                {
                    y3 = $(this).val();
                }
                else if($(this).hasClass('y4'))
                {
                    y4 = $(this).val();
                }
                else if($(this).hasClass('y5'))
                {
                    y5 = $(this).val();
                }
                else if($(this).hasClass('y6'))
                {
                    y6 = $(this).val();
                }
                else if($(this).hasClass('y7'))
                {
                    y7 = $(this).val();
                }
                else if($(this).hasClass('y8'))
                {
                    y8 = $(this).val();
                }
                else if($(this).hasClass('y9'))
                {
                    y9 = $(this).val();
                }
                else if($(this).hasClass('y10'))
                {
                    y10 = $(this).val();
                }
                else if($(this).hasClass('y11'))
                {
                    y11 = $(this).val();
                }
                else if($(this).hasClass('y12'))
                {
                    y12 = $(this).val();
                }
            });

            var withval = 0;

            $('.ye_year').each(function (index, element) {
                if($(this).val() != '')
                {
                    withval = 1;
                }
            });

            if(withval == 1)
            {
                window.open("/finance/reports/ye_print?y1="+y1+"&y2="+y2+"&y3="+y3+"&y4="+y4+"&y5="+y5+"&y6="+y6+"&y7="+y7+"&y8="+y8+"&y9="+y9+"&y10="+y10+"&y11="+y11+"&y12="+y12+"&action=print", '_blank');
            }
            else{
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    type: "error",
                    title: "Please select month"
                });
            }
     
        });

        $(document).on('click', '#dcpr_filterdate', function(){
            $('.dcpr_divdate').show();
            $('.dcpr_divor').hide();
        });

        $(document).on('click', '#dcpr_filteror', function(){
            $('.dcpr_divdate').hide();
            $('.dcpr_divor').show();
        });

    });

  </script>
  
@endsection