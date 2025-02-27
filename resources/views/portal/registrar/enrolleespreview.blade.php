@extends('registrar.layouts.app')
@section('content')
    <style>
        .centeredLabel{ text-align:center; }
    </style>
    <div class="row">
        <div class="card col-12">
            <div class="card-body">
                <h3>Number of Enrollees</h3>
                <form name="formSubmit" action="" method="GET" target="_blank">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group float-left">
                                <label>Date range:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    
                                    <input type="text" class="form-control float-right" id="reservation" value="{{$datestarted}} - {{$currentdate}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group float-right">
                                <label>&nbsp;</label>
                                <button id="print" type="button" class="btn btn-block btn-info btn-sm"><i class="fa fa-print"></i> Print</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card h-100">
            <div class="card-body">
                <small>As of:</small>&nbsp;&nbsp;<span id="from" class="badge badge-warning"></span>&nbsp;&nbsp;<small>to</small>&nbsp;&nbsp;<span id="to" class="badge badge-warning"></span>
                <div id="numOfEnrollees">

                </div>
                <div class="col-sm-4">
                <span>Total:</span>&nbsp;<button style="width:50%" class="btn btn-default btn-sm m-1 " id="totalNumOfEnrollees"></button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- BAR CHART -->
        <div class="card h-100">
            <div class="card-body">
                <div class="chart">
                    <canvas id="bar-chart-horizontal" width="1000" height="500"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- ChartJS -->
    <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
    <!-- InputMask -->
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script>
        $(function () {
            $('#reservation').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                }
            })
        })
    
        $('#reservation').on('change',function(){
            var dateRange = $('#reservation').val();
            $.ajax({
                url: '/show_enrollees/preview/'+dateRange+'',
                type:"GET",
                dataType:"json",
                success:function(data) {
                    $('#daterange').empty();
                    $('#numOfEnrollees').empty();
                    $('#from').text(data[1][0]);
                    $('#totalNumOfEnrollees').empty();
                    $('#to').text(data[1][1]);
                    var total = 0;
                    $.each(data[0], function(key, value){
                        if(value[1] == 0){
                            var pillClass = 'badge-danger';
                        }
                        else if(value[1] > 0){
                            var pillClass = 'badge-warning';
                        }
                        $('#numOfEnrollees').append(
                            '<button class="btn btn-default btn-sm m-1">'+value[0]+' <span class="right badge '+pillClass+'">'+value[1]+'</span></button>'
                        );
                        total+=value[1];
                    })
                    $('#totalNumOfEnrollees').text(total);
                    $(function () {
                        /*
                        * BAR CHART
                        * ---------
                        */
                        var ctxLine = document.getElementById("bar-chart-horizontal").getContext("2d");
                        if(window.bar != undefined) 
                        window.bar.destroy(); 
                        window.bar = new Chart(ctxLine, {
                            type: 'horizontalBar',
                            data: {
                                labels: ["NURSERY", "KINDER 1", "KINDER 2", "GRADE 1", "GRADE 2", "GRADE 3", "GRADE 4", "GRADE 5", "GRADE 6", "GRADE 7", "GRADE 8", "GRADE 9", "GRADE 10", "GRADE 11", "GRADE 12"],
                                datasets: [
                                    {
                                    // label: "Population (millions)",
                                    backgroundColor: ["#fdb414", "#b083d1","#b083d1","#3cd7dd","#3cd7dd","#3cd7dd","#3cd7dd","#3cd7dd","#3cd7dd","#e8c3b9","#e8c3b9","#e8c3b9","#e8c3b9","#00bfff","#00bfff"],
                                    borderColor: [
                                        '#fd7e14',
                                        '#6f42c1',
                                        '#6f42c1',
                                        '#3cab93',
                                        '#3cab93',
                                        '#3cab93',
                                        '#3cab93',
                                        '#3cab93',
                                        '#3cab93',
                                        '#d89c8c',
                                        '#d89c8c',
                                        '#d89c8c',
                                        '#d89c8c',
                                        '#0080ff',
                                        '#0080ff'
                                    ],
                                    borderWidth: 1,
                                    data: [data[0][0][1],data[0][1][1],data[0][2][1],data[0][3][1],data[0][4][1],data[0][5][1],data[0][6][1],data[0][7][1],data[0][8][1],data[0][9][1],data[0][10][1],data[0][11][1],data[0][12][1],data[0][13][1],data[0][14][1]]
                                    }
                                ]
                            },
                            options: {
                                legend: { display: false },
                                title: {
                                    display: true,
                                    text: 'Bar Chart for the selected dates'+"'"+' Number of Enrollees'
                                },
                                scales: {
                                    xAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    })
                }
            });
        })
        $("#print").on('click',function () {
            var daterange = $('#reservation').val();
            console.log(daterange)
            $('form[name=formSubmit]').attr('action', '/show_enrollees/print/'+daterange+'').submit();
        });
    </script>
@endsection