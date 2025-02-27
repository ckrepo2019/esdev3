@extends('registrar.layouts.app')
@section('content')
    <style>
        .centeredLabel{ text-align:center; }
    </style>
    @php
        $total =0;
    @endphp
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info"><h3 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                    <span><i class="fas fa-graduation-cap"></i> <b>NUMBER OF ENROLLEES</b></span>
                </h3></div>
                <div class="card-body">
                    
                    <form name="formSubmit" action="" method="GET" target="_blank">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group float-left">
                                    <label>Date range:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        
                                        <input type="text" class="form-control float-right " name="changedate" id="reservation" value="{{$datestarted}} - {{$currentdate}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Grade Level</label>
                                <select  class="form-control" name="selectedgradelevel">
                                    <option value="all">All</option>
                                    @foreach($gradelevels as $gradelevel)
                                        <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                {{-- class="select2bs4"  multiple="multiple" --}}
                                <label>Section</label>
                                <select class="form-control"  style="width: 100%;" name="selectedsection">
                                    <option value="all">All</option>
                                </select>
                            </div>
                            <div class="col-md-3">
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
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card h-100">
                <div class="card-body">
                    <small>As of:</small>&nbsp;&nbsp;<span id="from" class="badge badge-warning">{{$datestarted}}</span>&nbsp;&nbsp;<small>to</small>&nbsp;&nbsp;<span id="to" class="badge badge-warning">{{$currentdate}}</span>
                    <div id="numOfEnrolleesdefault">
                        @foreach ($gradelevels as $value)
                        <button class="btn btn-default btn-sm m-1">{{$value->levelname}} 
                            @if($value->studentcount== 0)
                            <span class="right badge badge-danger">{{$value->studentcount}}</span>
                            @else
                            <span class="right badge badge-warning">{{$value->studentcount}}</span>
                            @php
                                $total+=$value->studentcount;
                            @endphp
                            @endif
                        </button>
                        @endforeach
                    </div>
                    {{-- <div id="numOfEnrollees">
    
                    </div> --}}
                    <div class="col-sm-4">
                    <span>Total:</span>&nbsp;<button style="width:50%" class="btn btn-default btn-sm m-1 " id="totalNumOfEnrollees">{{$countenrollees}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- BAR CHART -->
        <div class="col-md-12" id="bar-chart-horizontal-gradelevel-container">
            <div class="card h-100">
                <div class="card-body">
                    <div class="chart">
                        <canvas id="bar-chart-horizontal-gradelevel" width="1000" ></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="bar-chart-horizontal-sections-container">
            <div class="card h-100">
                <div class="card-body">
                    <div class="chart">
                        <canvas id="bar-chart-horizontal-sections" width="1000" ></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="studentscontainer">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6" id="malecontainer">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Male</th>
                                    </tr>
                                </thead>
                                <tbody id="studentscontainerbodymale">
                                </tbody>
                            </table>
                        </div>
                        <div class="col-6" id="femalecontainer">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Female</th>
                                    </tr>
                                </thead>
                                <tbody id="studentscontainerbodyfemale">
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
    <!-- ChartJS -->
    <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
    <!-- InputMask -->
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#bar-chart-horizontal-sections-container').hide();
            $('#studentscontainer').hide();
            /*
            * BAR CHART
            * ---------
            */
            var gradelevelbarchart = document.getElementById("bar-chart-horizontal-gradelevel").getContext("2d");
            if(window.bar != undefined) 
                window.bar.destroy(); 
                window.bar = new Chart(gradelevelbarchart, {
                    type: 'horizontalBar',
                    data: {
                        labels: [
                                @foreach($gradelevels as $value)
                                    '{{$value->levelname}}',
                                @endforeach
                        ],
                        datasets: [
                            {
                            // label: "Population (millions)",
                            backgroundColor: ["#fdb414", "#b083d1","#b083d1","#3cd7dd","#3cd7dd","#3cd7dd","#3cd7dd","#3cd7dd","#3cd7dd","#e8c3b9","#e8c3b9","#e8c3b9","#e8c3b9","#00bfff","#00bfff",'#70ff8d','#70ff8d','#70ff8d','#70ff8d'],
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
                                '#0080ff',
                                '#70ff8d',
                                '#70ff8d',
                                '#70ff8d',
                                '#70ff8d'
                            ],
                            borderWidth: 1,
                            data: [
                                @foreach($gradelevels as $value)
                                    '{{$value->studentcount}}',
                                @endforeach
                            ]
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
                        },
                        maintainAspectRatio: false,
                        responsive: true
                    }
                });
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })
                $('#reservation').daterangepicker({
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                },
                $('#reservation').on('change',function(){
                    $('#studentscontainer').hide();
                    var daterange = $(this).val();
                    $.ajax({
                        url: '/show_enrollees/{{Crypt::encrypt("preview")}}',
                        type:"GET",
                        dataType:"json",
                        data: {
                            daterange : daterange
                        },
                        success:function(data) {
                            console.log(data)
                            $('#daterange').empty();
                            $('#numOfEnrolleesdefault').empty();
                            $('#from').text(data[0].datefrom);
                            $('#to').text(data[0].dateto)
                            $('#totalNumOfEnrollees').empty();
                            var levelnames = [];
                            var studentcounts = [];
                            $.each(data[0].gradelevels, function(key, value){
                                if(value.studentcount == 0){
                                    var pillClass = 'badge-danger';
                                }
                                else if(value.studentcount > 0){
                                    var pillClass = 'badge-warning';
                                }
                                $('#numOfEnrolleesdefault').append(
                                    '<button class="btn btn-default btn-sm m-1">'+value.levelname+' <span class="right badge '+pillClass+'">'+value.studentcount+'</span></button>'
                                );
                                levelnames.push(value.levelname)
                                studentcounts.push(value.studentcount)
                            })
                            $('#totalNumOfEnrollees').text(data[0].total);
                            $('#bar-chart-horizontal-gradelevel-container').show()
                            $(function () {
                                /*
                                * BAR CHART
                                * ---------
                                */
                                var ctxLine = document.getElementById("bar-chart-horizontal-gradelevel").getContext("2d");
                                if(window.bar != undefined) 
                                window.bar.destroy(); 
                                window.bar = new Chart(ctxLine, {
                                    type: 'horizontalBar',
                                    data: {
                                        labels:levelnames,
                                        datasets: [
                                            {
                                            // label: "Population (millions)",
                                            backgroundColor: ["#fdb414", "#b083d1","#b083d1","#3cd7dd","#3cd7dd","#3cd7dd","#3cd7dd","#3cd7dd","#3cd7dd","#e8c3b9","#e8c3b9","#e8c3b9","#e8c3b9","#00bfff","#00bfff",'#70ff8d','#70ff8d','#70ff8d','#70ff8d'],
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
                                                '#0080ff',
                                                '#70ff8d',
                                                '#70ff8d',
                                                '#70ff8d',
                                                '#70ff8d'
                                            ],
                                            borderWidth: 1,
                                            data: studentcounts
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
            )
        })
        function showbysections(selectedgradelevel )
        {
            $.ajax({
                url: '/show_enrollees/{{Crypt::encrypt("getsections")}}',
                type:"GET",
                dataType:"json",
                data    : {
                    gradelevelid: selectedgradelevel,
                    daterange: $('input[name="changedate"]').val()
                },
                success:function(data) {
                    $('#studentscontainer').hide()
                    $('#numOfEnrolleesdefault').empty();
                    $('select[name="selectedsection"]').empty();
                    $('#bar-chart-horizontal-gradelevel-container').hide()
                    $('#bar-chart-horizontal-sections-container').show();
                    
                    if(selectedgradelevel == 'all'){
                        // $('#numOfEnrollees').empty();
                        // $('#numOfEnrolleesdefault').show();
                        $('#bar-chart-horizontal-gradelevel-container').show()
                        $('#bar-chart-horizontal-sections-container').hide();
                        var total = 0;
                        var levelnames = [];
                        var studentcounts = [];
                        $.each(data, function(key, value){
                            if(value.studentcount == 0){
                                var pillClass = 'badge-danger';
                            }
                            else if(value.studentcount > 0){
                                var pillClass = 'badge-warning';
                            }
                            $('#numOfEnrolleesdefault').append(
                                '<button class="btn btn-default btn-sm m-1">'+value.levelname+' <span class="right badge '+pillClass+'">'+value.studentcount+'</span></button>'
                            );
                            total+=value.studentcount;
                            levelnames.push(value.levelname)
                            studentcounts.push(value.studentcount)
                        })
                        $('#totalNumOfEnrollees').text(total);
                        
                        var gradelevelbarchart = document.getElementById("bar-chart-horizontal-gradelevel").getContext("2d");
                        if(window.bar != undefined) 
                        window.bar.destroy(); 
                        window.bar = new Chart(gradelevelbarchart, {
                            type: 'horizontalBar',
                            data: {
                                labels: levelnames,
                                datasets: [
                                    {
                                    // label: "Population (millions)",
                                    backgroundColor: ["#fdb414", "#b083d1","#b083d1","#3cd7dd","#3cd7dd","#3cd7dd","#3cd7dd","#3cd7dd","#3cd7dd","#e8c3b9","#e8c3b9","#e8c3b9","#e8c3b9","#00bfff","#00bfff",'#70ff8d','#70ff8d','#70ff8d','#70ff8d'],
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
                                        '#0080ff',
                                        '#70ff8d',
                                        '#70ff8d',
                                        '#70ff8d',
                                        '#70ff8d'
                                    ],
                                    borderWidth: 1,
                                    data: studentcounts
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
                                },
                                maintainAspectRatio: false,
                                responsive: true
                            }
                        });
                    }
                    else{
                        var datasections = [];
                        var datacountstudents = [];
                        var databgcolor = [];
                        var totalstudents = 0;
                        $('select[name="selectedsection"]').append(
                            '<option value="all">All</option>'
                        )
                        $.each(data.sections, function(key, value){

                            $('select[name="selectedsection"]').append(
                                '<option value="'+value.id+'">'+value.sectionname+'</option>'
                            )
                            datasections.push(value.sectionname);
                            datacountstudents.push(value.studentcount);
                            databgcolor.push(value.bgcolor);
                            totalstudents+=value.studentcount;
                        })
                        $('#totalNumOfEnrollees').text(totalstudents)
                        /*
                        * BAR CHART
                        * ---------
                        */
                        var sectionchart = document.getElementById("bar-chart-horizontal-sections").getContext("2d");
                        if(window.bar != undefined) 
                        window.bar.destroy(); 
                        window.bar = new Chart(sectionchart, {
                            type: 'horizontalBar',
                            data: {
                                labels: datasections,
                                datasets: [
                                    {
                                    // label: "Population (millions)",
                                    backgroundColor: databgcolor,
                                    borderColor: databgcolor,
                                    borderWidth: 1,
                                    data: datacountstudents
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
                                },
                                maintainAspectRatio: false,
                                responsive: true
                            }
                        });
                    }
                }
            })
        }
        $('select[name="selectedgradelevel"]').on('change', function(){
            showbysections($(this).val())
        })
        $(document).on('change','select[name="selectedsection"]', function(){
            
            if($(this).val() == 'all')
            {
                var selectedgradelevel = $('select[name="selectedgradelevel"]').val();
                
                showbysections(selectedgradelevel)
                // $('select[name="selectedgradelevel"]').select();
                // console.log('adas')

            }else{
                $.ajax({
                    url: '/show_enrollees/{{Crypt::encrypt("getstudents")}}',
                    type:"GET",
                    dataType:"json",
                    data    : {
                        gradelevelid: $('select[name="selectedgradelevel"]').val(),
                        sectionid   : $(this).val(),
                        daterange   : $('input[name="changedate"]').val()
                    },
                    success:function(data) {
                        
                        $('#totalNumOfEnrollees').text((data[0].length)+(data[1].length));
                        $('#studentscontainer').show();
                        $('#bar-chart-horizontal-gradelevel-container').hide()
                        $('#bar-chart-horizontal-sections-container').hide();
                        $('#studentscontainerbodymale').empty();
                        $('#studentscontainerbodyfemale').empty();
                        var countmale = 1;
                        var countfemale = 1;
                        if(data[0].length > 0){

                            $.each(data[0], function(key, value){
                                $('#studentscontainerbodymale').append(
                                    '<tr>'+
                                        '<td>'+countmale+'. '+value.lastname+', '+value.firstname+' '+value.middlename+' '+value.suffix+'</td>'+
                                    '</tr>'
                                )
                                countmale+=1;
                            })
                            
                        }
                        if(data[1].length > 0){

                            $.each(data[1], function(key, value){
                                $('#studentscontainerbodyfemale').append(
                                    '<tr>'+
                                        '<td>'+countfemale+'. '+value.lastname+', '+value.firstname+' '+value.middlename+' '+value.suffix+'</td>'+
                                    '</tr>'
                                )
                                countfemale+=1;
                            })
                            
                        }
                    }
                })
            }
        })
        $("#print").on('click',function () {
            var daterange = $('#reservation').val();
            console.log(daterange)
            $('form[name=formSubmit]').attr('action', '/show_enrollees/{{Crypt::encrypt("print")}}').submit();
        });
    </script>
@endsection